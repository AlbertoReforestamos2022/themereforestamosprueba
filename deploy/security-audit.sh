#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Security Audit Script
# =============================================================================
# Performs automated security checks on the codebase before production deploy.
# Usage: ./deploy/security-audit.sh [--fix] [--verbose]
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

VERBOSE=false
FIX_MODE=false
ISSUES_FOUND=0
WARNINGS_FOUND=0

for arg in "$@"; do
    case $arg in
        --fix) FIX_MODE=true ;;
        --verbose) VERBOSE=true ;;
    esac
done

log_pass()    { echo -e "  ${GREEN}✓${NC} $1"; }
log_fail()    { echo -e "  ${RED}✗${NC} $1"; ISSUES_FOUND=$((ISSUES_FOUND + 1)); }
log_warn()    { echo -e "  ${YELLOW}⚠${NC} $1"; WARNINGS_FOUND=$((WARNINGS_FOUND + 1)); }
log_info()    { echo -e "  ${CYAN}ℹ${NC} $1"; }
log_section() { echo -e "\n${CYAN}━━━ $1 ━━━${NC}"; }

cd "$PROJECT_ROOT"

echo -e "${GREEN}╔══════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   Reforestamos México - Security Audit          ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════╝${NC}"
echo ""
echo "Date: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 1. Input Sanitization Checks (Req 23.1)
# ─────────────────────────────────────────────────────────────────────────────
log_section "1. Input Sanitization (Req 23.1)"

# Check for raw $_POST/$_GET/$_REQUEST usage without sanitization
RAW_SUPERGLOBALS=$(grep -rn '\$_POST\[\|$_GET\[\|$_REQUEST\[' \
    --include="*.php" \
    reforestamos-block-theme/inc/ \
    reforestamos-core/includes/ \
    reforestamos-comunicacion/includes/ \
    reforestamos-empresas/includes/ \
    reforestamos-micrositios/includes/ \
    2>/dev/null | grep -v 'sanitize_\|esc_\|absint\|intval\|wp_unslash' | grep -v '// phpcs' | grep -v 'test' || true)

if [ -z "$RAW_SUPERGLOBALS" ]; then
    log_pass "No unsanitized superglobal access found"
else
    COUNT=$(echo "$RAW_SUPERGLOBALS" | wc -l)
    log_warn "Found $COUNT potential unsanitized superglobal accesses"
    if [ "$VERBOSE" = true ]; then
        echo "$RAW_SUPERGLOBALS" | head -10
    fi
fi

# Check that sanitize functions are used
SANITIZE_COUNT=$(grep -rn 'sanitize_text_field\|sanitize_email\|sanitize_textarea_field\|sanitize_url\|absint\|intval\|wp_kses' \
    --include="*.php" \
    reforestamos-block-theme/inc/ \
    reforestamos-core/includes/ \
    reforestamos-comunicacion/includes/ \
    reforestamos-empresas/includes/ \
    2>/dev/null | wc -l || echo "0")

if [ "$SANITIZE_COUNT" -gt 0 ]; then
    log_pass "Found $SANITIZE_COUNT sanitization function calls across codebase"
else
    log_fail "No sanitization functions found — critical issue"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 2. Output Escaping Checks (Req 23.2)
# ─────────────────────────────────────────────────────────────────────────────
log_section "2. Output Escaping (Req 23.2)"

ESCAPE_COUNT=$(grep -rn 'esc_html\|esc_attr\|esc_url\|esc_js\|wp_kses_post\|esc_html__\|esc_html_e\|esc_attr__' \
    --include="*.php" \
    reforestamos-block-theme/ \
    reforestamos-core/ \
    reforestamos-comunicacion/ \
    reforestamos-empresas/ \
    2>/dev/null | wc -l || echo "0")

if [ "$ESCAPE_COUNT" -gt 0 ]; then
    log_pass "Found $ESCAPE_COUNT output escaping calls across codebase"
else
    log_fail "No output escaping functions found — critical issue"
fi

# Check for echo without escaping in templates
UNESCAPED_ECHO=$(grep -rn 'echo \$\|<?= \$' \
    --include="*.php" \
    reforestamos-block-theme/inc/ \
    reforestamos-block-theme/patterns/ \
    reforestamos-core/includes/ \
    reforestamos-comunicacion/includes/ \
    reforestamos-empresas/includes/ \
    2>/dev/null | grep -v 'esc_\|wp_kses\|// phpcs\|wp_nonce\|json_encode' || true)

if [ -z "$UNESCAPED_ECHO" ]; then
    log_pass "No unescaped echo statements found in includes"
else
    COUNT=$(echo "$UNESCAPED_ECHO" | wc -l)
    log_warn "Found $COUNT potential unescaped echo statements"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 3. Nonce Verification (Req 23.3)
# ─────────────────────────────────────────────────────────────────────────────
log_section "3. Nonce Verification (Req 23.3)"

NONCE_CREATE=$(grep -rn 'wp_create_nonce\|wp_nonce_field\|wp_nonce_url' \
    --include="*.php" \
    reforestamos-block-theme/ \
    reforestamos-core/ \
    reforestamos-comunicacion/ \
    reforestamos-empresas/ \
    2>/dev/null | wc -l || echo "0")

NONCE_VERIFY=$(grep -rn 'wp_verify_nonce\|check_ajax_referer\|check_admin_referer' \
    --include="*.php" \
    reforestamos-block-theme/ \
    reforestamos-core/ \
    reforestamos-comunicacion/ \
    reforestamos-empresas/ \
    2>/dev/null | wc -l || echo "0")

if [ "$NONCE_CREATE" -gt 0 ] && [ "$NONCE_VERIFY" -gt 0 ]; then
    log_pass "Nonce creation ($NONCE_CREATE) and verification ($NONCE_VERIFY) found"
else
    log_fail "Missing nonce creation or verification"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 4. AJAX Security (Req 23.4)
# ─────────────────────────────────────────────────────────────────────────────
log_section "4. AJAX Security (Req 23.4)"

AJAX_HANDLERS=$(grep -rn 'wp_ajax_\|wp_ajax_nopriv_' \
    --include="*.php" \
    reforestamos-block-theme/ \
    reforestamos-core/ \
    reforestamos-comunicacion/ \
    reforestamos-empresas/ \
    reforestamos-micrositios/ \
    2>/dev/null | wc -l || echo "0")

if [ "$AJAX_HANDLERS" -gt 0 ]; then
    log_pass "Found $AJAX_HANDLERS AJAX handler registrations"
else
    log_info "No AJAX handlers found (may be expected)"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 5. SQL Injection Prevention (Req 23.9, 23.10)
# ─────────────────────────────────────────────────────────────────────────────
log_section "5. SQL Injection Prevention (Req 23.9, 23.10)"

PREPARED_STMTS=$(grep -rn '\$wpdb->prepare' \
    --include="*.php" \
    reforestamos-block-theme/ \
    reforestamos-core/ \
    reforestamos-comunicacion/ \
    reforestamos-empresas/ \
    reforestamos-micrositios/ \
    2>/dev/null | wc -l || echo "0")

DIRECT_QUERIES=$(grep -rn '\$wpdb->query\|$wpdb->get_results\|$wpdb->get_var\|$wpdb->get_row' \
    --include="*.php" \
    reforestamos-block-theme/ \
    reforestamos-core/ \
    reforestamos-comunicacion/ \
    reforestamos-empresas/ \
    reforestamos-micrositios/ \
    2>/dev/null | grep -v 'prepare\|SELECT 1\|// phpcs' || true)

if [ "$PREPARED_STMTS" -gt 0 ]; then
    log_pass "Found $PREPARED_STMTS prepared statement usages"
fi

if [ -z "$DIRECT_QUERIES" ]; then
    log_pass "No direct (unprepared) database queries found"
else
    COUNT=$(echo "$DIRECT_QUERIES" | wc -l)
    log_warn "Found $COUNT direct database queries — verify they use prepared statements"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 6. File Permission Checks
# ─────────────────────────────────────────────────────────────────────────────
log_section "6. File Permissions"

# Check that PHP files are not world-writable
WORLD_WRITABLE=$(find reforestamos-block-theme/ reforestamos-core/ reforestamos-comunicacion/ reforestamos-empresas/ reforestamos-micrositios/ \
    -name "*.php" -perm -o+w 2>/dev/null || true)

if [ -z "$WORLD_WRITABLE" ]; then
    log_pass "No world-writable PHP files found"
else
    COUNT=$(echo "$WORLD_WRITABLE" | wc -l)
    log_fail "Found $COUNT world-writable PHP files"
    if [ "$FIX_MODE" = true ]; then
        find reforestamos-block-theme/ reforestamos-core/ reforestamos-comunicacion/ reforestamos-empresas/ reforestamos-micrositios/ \
            -name "*.php" -perm -o+w -exec chmod o-w {} \; 2>/dev/null || true
        log_info "Fixed: removed world-writable permissions"
    fi
fi

# Check that config/env files are not publicly accessible
SENSITIVE_FILES=(".env" ".env.production" ".env.staging" "wp-config.php")
for f in "${SENSITIVE_FILES[@]}"; do
    if [ -f "$f" ]; then
        PERMS=$(stat -c "%a" "$f" 2>/dev/null || stat -f "%Lp" "$f" 2>/dev/null || echo "unknown")
        if [ "$PERMS" = "600" ] || [ "$PERMS" = "640" ] || [ "$PERMS" = "644" ]; then
            log_pass "Sensitive file $f has permissions $PERMS"
        else
            log_warn "Sensitive file $f has permissions $PERMS (recommend 640 or stricter)"
        fi
    fi
done

# ─────────────────────────────────────────────────────────────────────────────
# 7. Security Headers Check
# ─────────────────────────────────────────────────────────────────────────────
log_section "7. Security Headers"

if grep -q 'X-Content-Type-Options' reforestamos-block-theme/inc/security.php 2>/dev/null; then
    log_pass "X-Content-Type-Options header configured"
else
    log_fail "Missing X-Content-Type-Options header"
fi

if grep -q 'X-Frame-Options' reforestamos-block-theme/inc/security.php 2>/dev/null; then
    log_pass "X-Frame-Options header configured"
else
    log_fail "Missing X-Frame-Options header"
fi

if grep -q 'Content-Security-Policy' reforestamos-block-theme/inc/security.php 2>/dev/null; then
    log_pass "Content-Security-Policy header configured"
else
    log_warn "Missing Content-Security-Policy header"
fi

if grep -q 'Referrer-Policy' reforestamos-block-theme/inc/security.php 2>/dev/null; then
    log_pass "Referrer-Policy header configured"
else
    log_warn "Missing Referrer-Policy header"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 8. Sensitive Data Exposure
# ─────────────────────────────────────────────────────────────────────────────
log_section "8. Sensitive Data Exposure"

# Check for hardcoded credentials
HARDCODED=$(grep -rn 'password\s*=\s*["\x27][^"\x27]\+["\x27]\|api_key\s*=\s*["\x27][^"\x27]\+["\x27]' \
    --include="*.php" \
    reforestamos-block-theme/inc/ \
    reforestamos-core/includes/ \
    reforestamos-comunicacion/includes/ \
    reforestamos-empresas/includes/ \
    2>/dev/null | grep -vi 'example\|placeholder\|test\|default\|sanitize\|option\|get_option\|empty\|__(' || true)

if [ -z "$HARDCODED" ]; then
    log_pass "No hardcoded credentials detected"
else
    log_fail "Potential hardcoded credentials found"
    if [ "$VERBOSE" = true ]; then
        echo "$HARDCODED" | head -5
    fi
fi

# Check for debug mode indicators
if grep -rq "define.*WP_DEBUG.*true" reforestamos-block-theme/ 2>/dev/null; then
    log_warn "WP_DEBUG set to true found in theme files"
else
    log_pass "No WP_DEBUG=true in theme files"
fi

# Check .gitignore for sensitive files
if [ -f ".gitignore" ]; then
    for pattern in ".env" "*.log" "node_modules"; do
        if grep -q "$pattern" .gitignore 2>/dev/null; then
            log_pass ".gitignore excludes $pattern"
        else
            log_warn ".gitignore does not exclude $pattern"
        fi
    done
fi

# ─────────────────────────────────────────────────────────────────────────────
# 9. Encryption Check
# ─────────────────────────────────────────────────────────────────────────────
log_section "9. Credential Encryption"

if grep -q 'openssl_encrypt\|openssl_decrypt' reforestamos-block-theme/inc/security.php 2>/dev/null; then
    log_pass "Encryption functions available in security module"
else
    log_warn "No encryption functions found in security module"
fi

# ─────────────────────────────────────────────────────────────────────────────
# 10. Rate Limiting
# ─────────────────────────────────────────────────────────────────────────────
log_section "10. Rate Limiting"

if grep -rq 'rate_limit\|check_rate_limit' reforestamos-block-theme/inc/security.php 2>/dev/null; then
    log_pass "Rate limiting implemented in security module"
else
    log_warn "No rate limiting found"
fi

# ─────────────────────────────────────────────────────────────────────────────
# Summary
# ─────────────────────────────────────────────────────────────────────────────
echo ""
echo -e "${CYAN}━━━ AUDIT SUMMARY ━━━${NC}"
echo ""

if [ "$ISSUES_FOUND" -eq 0 ] && [ "$WARNINGS_FOUND" -eq 0 ]; then
    echo -e "${GREEN}All security checks passed!${NC}"
elif [ "$ISSUES_FOUND" -eq 0 ]; then
    echo -e "${YELLOW}No critical issues found. $WARNINGS_FOUND warning(s) to review.${NC}"
else
    echo -e "${RED}Found $ISSUES_FOUND critical issue(s) and $WARNINGS_FOUND warning(s).${NC}"
    echo -e "${RED}Please address critical issues before deploying to production.${NC}"
fi

echo ""
echo "Issues:   $ISSUES_FOUND"
echo "Warnings: $WARNINGS_FOUND"
echo ""

exit $ISSUES_FOUND
