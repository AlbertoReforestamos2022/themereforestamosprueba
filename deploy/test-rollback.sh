#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Rollback Test Script
# =============================================================================
# Tests the rollback procedure on staging to verify it works before
# performing a production deployment.
# Usage: ./deploy/test-rollback.sh --env staging
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

ENV=""

while [[ $# -gt 0 ]]; do
    case $1 in
        --env) ENV="$2"; shift 2 ;;
        *) echo -e "${RED}Unknown option: $1${NC}"; exit 1 ;;
    esac
done

if [ -z "$ENV" ]; then
    echo -e "${RED}Usage: ./deploy/test-rollback.sh --env <staging|production>${NC}"
    echo -e "${YELLOW}WARNING: Only use 'production' if you know what you're doing!${NC}"
    exit 1
fi

log_info()  { echo -e "${GREEN}[TEST]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[FAIL]${NC} $1"; }
log_step()  { echo -e "\n${CYAN}━━━ $1 ━━━${NC}"; }

# Load environment
ENV_FILE="$PROJECT_ROOT/.env.${ENV}"
if [ -f "$ENV_FILE" ]; then
    set -a; source "$ENV_FILE"; set +a
fi

case "$ENV" in
    staging)
        HOST="${STAGING_HOST:?'STAGING_HOST is required'}"
        USER="${STAGING_USER:?'STAGING_USER is required'}"
        REMOTE_PATH="${STAGING_PATH:?'STAGING_PATH is required'}"
        SITE_URL="${STAGING_URL:-https://staging.reforestamos.org}"
        ;;
    production)
        HOST="${PRODUCTION_HOST:?'PRODUCTION_HOST is required'}"
        USER="${PRODUCTION_USER:?'PRODUCTION_USER is required'}"
        REMOTE_PATH="${PRODUCTION_PATH:?'PRODUCTION_PATH is required'}"
        SITE_URL="${PRODUCTION_URL:-https://reforestamos.org}"
        ;;
    *)
        log_error "Invalid environment: $ENV"
        exit 1
        ;;
esac

HEALTH_URL="${SITE_URL}/wp-json/reforestamos/v1/health"
PASSED=0
FAILED=0

check_pass() { PASSED=$((PASSED + 1)); log_info "✓ $1"; }
check_fail() { FAILED=$((FAILED + 1)); log_error "✗ $1"; }

echo -e "${GREEN}╔══════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   Rollback Test — $ENV                          ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════╝${NC}"

# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 1: Verify site is currently working"
# ─────────────────────────────────────────────────────────────────────────────

HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$HEALTH_URL" 2>/dev/null || echo "000")
if [ "$HTTP_STATUS" = "200" ]; then
    check_pass "Health check returns 200 (site is up)"
else
    check_fail "Health check returned $HTTP_STATUS (site may be down)"
    log_error "Cannot proceed with rollback test — site is not responding"
    exit 1
fi

# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 2: Verify backups exist"
# ─────────────────────────────────────────────────────────────────────────────

BACKUP_COUNT=$(ssh "${USER}@${HOST}" \
    "ls ${REMOTE_PATH}/backups/pre-deploy-*.tar.gz 2>/dev/null | wc -l" || echo "0")

if [ "$BACKUP_COUNT" -gt 0 ]; then
    check_pass "Found $BACKUP_COUNT backup file(s)"
    
    LATEST_BACKUP=$(ssh "${USER}@${HOST}" \
        "ls -t ${REMOTE_PATH}/backups/pre-deploy-*.tar.gz 2>/dev/null | head -1")
    log_info "Latest backup: $(basename "$LATEST_BACKUP")"
else
    check_fail "No backup files found"
    log_error "Cannot test rollback without backups. Deploy first."
    exit 1
fi

# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 3: Create a test marker file"
# ─────────────────────────────────────────────────────────────────────────────

MARKER="rollback-test-$(date +%s).txt"
ssh "${USER}@${HOST}" \
    "echo 'rollback-test' > ${REMOTE_PATH}/wp-content/themes/reforestamos-block-theme/${MARKER}"
check_pass "Created test marker file: $MARKER"

# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 4: Execute rollback"
# ─────────────────────────────────────────────────────────────────────────────

log_info "Running rollback script..."
echo "rollback" | bash "$SCRIPT_DIR/rollback.sh" --env "$ENV" 2>&1 || true

# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 5: Verify rollback succeeded"
# ─────────────────────────────────────────────────────────────────────────────

# Check marker file is gone (restored from backup)
MARKER_EXISTS=$(ssh "${USER}@${HOST}" \
    "test -f ${REMOTE_PATH}/wp-content/themes/reforestamos-block-theme/${MARKER} && echo 'yes' || echo 'no'")

if [ "$MARKER_EXISTS" = "no" ]; then
    check_pass "Test marker file removed (files restored from backup)"
else
    check_fail "Test marker file still exists (rollback may not have restored files)"
    # Clean up
    ssh "${USER}@${HOST}" \
        "rm -f ${REMOTE_PATH}/wp-content/themes/reforestamos-block-theme/${MARKER}"
fi

# Check site is still responding
sleep 3
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$HEALTH_URL" 2>/dev/null || echo "000")
if [ "$HTTP_STATUS" = "200" ]; then
    check_pass "Site responds with 200 after rollback"
else
    check_fail "Site returned $HTTP_STATUS after rollback"
fi

# Check maintenance mode is off
MAINTENANCE=$(ssh "${USER}@${HOST}" \
    "test -f ${REMOTE_PATH}/.maintenance && echo 'yes' || echo 'no'")
if [ "$MAINTENANCE" = "no" ]; then
    check_pass "Maintenance mode is disabled"
else
    check_fail "Maintenance mode is still enabled"
    ssh "${USER}@${HOST}" "rm -f ${REMOTE_PATH}/.maintenance"
    log_info "Cleaned up maintenance file"
fi

# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 6: Re-deploy to restore current version"
# ─────────────────────────────────────────────────────────────────────────────

log_info "Re-deploying to restore current version..."
if [ "$ENV" = "staging" ]; then
    bash "$SCRIPT_DIR/deploy-staging.sh" 2>&1 || log_warn "Re-deploy had issues"
else
    log_warn "Skipping re-deploy for production (manual action required)"
fi

# ─────────────────────────────────────────────────────────────────────────────
# Summary
# ─────────────────────────────────────────────────────────────────────────────
echo ""
echo -e "${CYAN}━━━ ROLLBACK TEST SUMMARY ━━━${NC}"
echo ""
echo "Passed: $PASSED"
echo "Failed: $FAILED"
echo ""

if [ "$FAILED" -eq 0 ]; then
    echo -e "${GREEN}Rollback procedure verified successfully on $ENV!${NC}"
    exit 0
else
    echo -e "${RED}Rollback test had $FAILED failure(s). Review before production deploy.${NC}"
    exit 1
fi
