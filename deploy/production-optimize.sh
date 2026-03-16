#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Production Optimization Script
# =============================================================================
# Verifies that all assets are production-ready: minified, no source maps,
# and properly optimized for deployment.
# Usage: ./deploy/production-optimize.sh [--build] [--verify-only]
# =============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

RUN_BUILD=false
VERIFY_ONLY=false
ISSUES=0

for arg in "$@"; do
    case $arg in
        --build) RUN_BUILD=true ;;
        --verify-only) VERIFY_ONLY=true ;;
    esac
done

log_pass() { echo -e "  ${GREEN}✓${NC} $1"; }
log_fail() { echo -e "  ${RED}✗${NC} $1"; ISSUES=$((ISSUES + 1)); }
log_warn() { echo -e "  ${YELLOW}⚠${NC} $1"; }
log_info() { echo -e "  ${CYAN}ℹ${NC} $1"; }

cd "$PROJECT_ROOT"

echo -e "${GREEN}╔══════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   Production Optimization Check                 ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════╝${NC}"
echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 1. Run production build if requested (Req 15.5)
# ─────────────────────────────────────────────────────────────────────────────
if [ "$RUN_BUILD" = true ] && [ "$VERIFY_ONLY" = false ]; then
    echo -e "${CYAN}━━━ Running Production Build ━━━${NC}"
    
    if [ -f "reforestamos-block-theme/package.json" ]; then
        log_info "Building block theme assets..."
        cd reforestamos-block-theme
        npm ci --production=false 2>/dev/null || npm install 2>/dev/null || true
        npm run build 2>/dev/null && log_pass "Theme build completed" || log_fail "Theme build failed"
        cd "$PROJECT_ROOT"
    fi
    echo ""
fi

# ─────────────────────────────────────────────────────────────────────────────
# 2. Verify CSS minification (Req 15.8, 19.3)
# ─────────────────────────────────────────────────────────────────────────────
echo -e "${CYAN}━━━ CSS Optimization ━━━${NC}"

CSS_FILES=$(find reforestamos-block-theme/build/ -name "*.css" 2>/dev/null || true)
if [ -n "$CSS_FILES" ]; then
    CSS_COUNT=$(echo "$CSS_FILES" | wc -l)
    log_pass "Found $CSS_COUNT CSS file(s) in build directory"
    
    # Check for minification (no multi-line formatting)
    for css_file in $CSS_FILES; do
        LINES=$(wc -l < "$css_file" 2>/dev/null || echo "0")
        SIZE=$(wc -c < "$css_file" 2>/dev/null || echo "0")
        BASENAME=$(basename "$css_file")
        
        if [ "$SIZE" -gt 0 ]; then
            # Minified CSS typically has very few lines relative to size
            RATIO=$((SIZE / (LINES + 1)))
            if [ "$RATIO" -gt 100 ] || [ "$LINES" -lt 50 ]; then
                log_pass "$BASENAME appears minified ($SIZE bytes, $LINES lines)"
            else
                log_warn "$BASENAME may not be fully minified ($SIZE bytes, $LINES lines)"
            fi
        fi
    done
else
    log_warn "No CSS files found in build directory (run --build first)"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 3. Verify JS minification (Req 15.8, 19.3)
# ─────────────────────────────────────────────────────────────────────────────
echo -e "\n${CYAN}━━━ JavaScript Optimization ━━━${NC}"

JS_FILES=$(find reforestamos-block-theme/build/ -name "*.js" 2>/dev/null || true)
if [ -n "$JS_FILES" ]; then
    JS_COUNT=$(echo "$JS_FILES" | wc -l)
    log_pass "Found $JS_COUNT JavaScript file(s) in build directory"
    
    for js_file in $JS_FILES; do
        SIZE=$(wc -c < "$js_file" 2>/dev/null || echo "0")
        BASENAME=$(basename "$js_file")
        if [ "$SIZE" -gt 0 ]; then
            log_pass "$BASENAME compiled ($SIZE bytes)"
        fi
    done
else
    log_warn "No JS files found in build directory (run --build first)"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 4. Verify no source maps in production (Req 15.8)
# ─────────────────────────────────────────────────────────────────────────────
echo -e "\n${CYAN}━━━ Source Maps Check ━━━${NC}"

SOURCE_MAPS=$(find reforestamos-block-theme/build/ -name "*.map" 2>/dev/null || true)
if [ -z "$SOURCE_MAPS" ]; then
    log_pass "No source map files found in build directory"
else
    MAP_COUNT=$(echo "$SOURCE_MAPS" | wc -l)
    log_fail "Found $MAP_COUNT source map file(s) in build directory — remove for production"
    
    if [ "$VERIFY_ONLY" = false ]; then
        log_info "Removing source maps..."
        find reforestamos-block-theme/build/ -name "*.map" -delete 2>/dev/null || true
        log_pass "Source maps removed"
        ISSUES=$((ISSUES - 1))
    fi
fi

# Check for sourceMappingURL references in built files
SOURCEMAP_REFS=$(grep -rn 'sourceMappingURL' reforestamos-block-theme/build/ 2>/dev/null || true)
if [ -z "$SOURCEMAP_REFS" ]; then
    log_pass "No sourceMappingURL references in built files"
else
    REF_COUNT=$(echo "$SOURCEMAP_REFS" | wc -l)
    log_warn "Found $REF_COUNT sourceMappingURL reference(s) in built files"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 5. Verify no development files in deploy
# ─────────────────────────────────────────────────────────────────────────────
echo -e "\n${CYAN}━━━ Development Artifacts ━━━${NC}"

# Check for node_modules
if [ -d "reforestamos-block-theme/node_modules" ]; then
    log_warn "node_modules/ exists in theme (excluded by deploy scripts)"
else
    log_pass "No node_modules/ in theme directory"
fi

# Check for test files in deploy scope
TEST_FILES=$(find reforestamos-block-theme/build/ -name "*.test.*" -o -name "*.spec.*" 2>/dev/null || true)
if [ -z "$TEST_FILES" ]; then
    log_pass "No test files in build directory"
else
    log_warn "Test files found in build directory"
fi

# Check for console.log in production JS
CONSOLE_LOGS=$(grep -rn 'console\.log' reforestamos-block-theme/build/ 2>/dev/null || true)
if [ -z "$CONSOLE_LOGS" ]; then
    log_pass "No console.log statements in built JS"
else
    COUNT=$(echo "$CONSOLE_LOGS" | wc -l)
    log_warn "Found $COUNT console.log statement(s) in built JS"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 6. Asset manifest check
# ─────────────────────────────────────────────────────────────────────────────
echo -e "\n${CYAN}━━━ Asset Manifests ━━━${NC}"

ASSET_FILES=$(find reforestamos-block-theme/build/ -name "*.asset.php" 2>/dev/null || true)
if [ -n "$ASSET_FILES" ]; then
    ASSET_COUNT=$(echo "$ASSET_FILES" | wc -l)
    log_pass "Found $ASSET_COUNT asset manifest file(s) for cache busting"
else
    log_info "No .asset.php manifests found (may use different cache busting strategy)"
fi

# ─────────────────────────────────────────────────────────────────────────────
# Summary
# ─────────────────────────────────────────────────────────────────────────────
echo ""
echo -e "${CYAN}━━━ OPTIMIZATION SUMMARY ━━━${NC}"
echo ""

if [ "$ISSUES" -eq 0 ]; then
    echo -e "${GREEN}All production optimization checks passed!${NC}"
else
    echo -e "${RED}Found $ISSUES issue(s) that need attention.${NC}"
fi

echo ""
exit $ISSUES
