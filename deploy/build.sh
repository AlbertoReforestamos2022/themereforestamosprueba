#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Production Build Script
# =============================================================================
# Compiles all assets, runs linters, and prepares the project for deployment.
# Usage: ./deploy/build.sh [--skip-tests] [--skip-lint]
# =============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

SKIP_TESTS=false
SKIP_LINT=false

for arg in "$@"; do
    case $arg in
        --skip-tests) SKIP_TESTS=true ;;
        --skip-lint) SKIP_LINT=true ;;
    esac
done

log_info()  { echo -e "${GREEN}[BUILD]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

cd "$PROJECT_ROOT"

log_info "Starting production build..."

# 1. Install dependencies
log_info "Installing npm dependencies..."
npm ci --production=false

log_info "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

# 2. Lint
if [ "$SKIP_LINT" = false ]; then
    log_info "Running ESLint..."
    npm run lint:js || { log_error "ESLint failed"; exit 1; }

    log_info "Running PHP_CodeSniffer..."
    composer run lint:php || log_warn "PHP linting had warnings (non-blocking)"
fi

# 3. Tests
if [ "$SKIP_TESTS" = false ]; then
    log_info "Running PHPUnit tests..."
    composer run test || { log_error "PHPUnit tests failed"; exit 1; }
fi

# 4. Build theme assets
log_info "Building block theme assets..."
if [ -f "reforestamos-block-theme/package.json" ]; then
    cd reforestamos-block-theme
    npm ci --production=false 2>/dev/null || true
    npm run build || { log_error "Theme build failed"; exit 1; }
    cd "$PROJECT_ROOT"
fi

# 5. Clean up dev files from build output
log_info "Cleaning development artifacts..."
find . -name "*.map" -path "*/build/*" -delete 2>/dev/null || true
find . -name "*.map" -path "*/dist/*" -delete 2>/dev/null || true

# 6. Verify no source maps remain
REMAINING_MAPS=$(find . -name "*.map" -path "*/build/*" 2>/dev/null || true)
if [ -n "$REMAINING_MAPS" ]; then
    log_warn "Some source maps could not be removed"
else
    log_info "Source maps cleaned from production build"
fi

log_info "Production build completed successfully!"
echo ""
echo "Build artifacts are ready in:"
echo "  - reforestamos-block-theme/build/"
echo ""
echo "Next steps:"
echo "  ./deploy/deploy-staging.sh   - Deploy to staging"
echo "  ./deploy/deploy-production.sh - Deploy to production"
