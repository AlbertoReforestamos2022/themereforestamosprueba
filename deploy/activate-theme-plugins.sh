#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Activate Theme & Plugins Script
# =============================================================================
# Activates the Block Theme and all plugins in the correct order on production.
# Usage: ./deploy/activate-theme-plugins.sh [--env <staging|production>]
#
# Activation order:
#   1. Reforestamos Core (provides CPTs — must be first)
#   2. Reforestamos Micrositios
#   3. Reforestamos Comunicación
#   4. Reforestamos Empresas (depends on Core)
#   5. Reforestamos Block Theme
#
# Requirements: 20.1, 20.2
# =============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log_info()  { echo -e "${GREEN}[ACTIVATE]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }
log_step()  { echo -e "${BLUE}[STEP]${NC} $1"; }

# Parse arguments
ENV="${1:-production}"
case "$ENV" in
    --env) ENV="${2:-production}" ;;
esac

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
        log_error "Invalid environment: $ENV (use staging or production)"
        exit 1
        ;;
esac

THEME_SLUG="reforestamos-block-theme"

# Plugins in activation order (Core first, Empresas last due to dependency)
PLUGINS=(
    "reforestamos-core/reforestamos-core.php"
    "reforestamos-micrositios/reforestamos-micrositios.php"
    "reforestamos-comunicacion/reforestamos-comunicacion.php"
    "reforestamos-empresas/reforestamos-empresas.php"
)

PLUGIN_NAMES=(
    "Reforestamos Core"
    "Reforestamos Micrositios"
    "Reforestamos Comunicación"
    "Reforestamos Empresas"
)

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Reforestamos México — Activate Theme & Plugins              ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo "  Environment: ${ENV}"
echo "  Host:        ${HOST}"
echo "  Theme:       ${THEME_SLUG}"
echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Pre-flight checks
# ─────────────────────────────────────────────────────────────────────────────
log_step "Pre-flight checks..."

# Verify SSH connectivity
ssh -o ConnectTimeout=10 "${USER}@${HOST}" "echo 'SSH OK'" > /dev/null 2>&1 || {
    log_error "Cannot connect to ${HOST} via SSH"
    exit 1
}
log_info "SSH connection verified"

# Verify WP-CLI is available
ssh "${USER}@${HOST}" "cd ${REMOTE_PATH} && wp --info > /dev/null 2>&1" || {
    log_error "WP-CLI is not available on the remote server"
    exit 1
}
log_info "WP-CLI available"

# Verify theme files exist
THEME_EXISTS=$(ssh "${USER}@${HOST}" \
    "test -f ${REMOTE_PATH}/wp-content/themes/${THEME_SLUG}/style.css && echo 'yes' || echo 'no'")
if [ "$THEME_EXISTS" != "yes" ]; then
    log_error "Theme files not found at wp-content/themes/${THEME_SLUG}/"
    log_error "Deploy the theme first: ./deploy/deploy-production.sh"
    exit 1
fi
log_info "Theme files verified"

# Verify plugin files exist
for i in "${!PLUGINS[@]}"; do
    PLUGIN_FILE="${PLUGINS[$i]}"
    PLUGIN_EXISTS=$(ssh "${USER}@${HOST}" \
        "test -f ${REMOTE_PATH}/wp-content/plugins/${PLUGIN_FILE} && echo 'yes' || echo 'no'")
    if [ "$PLUGIN_EXISTS" != "yes" ]; then
        log_error "Plugin not found: ${PLUGIN_FILE}"
        exit 1
    fi
done
log_info "All plugin files verified"

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Step 1: Activate plugins in order
# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 1/3: Activating plugins in order..."

ACTIVATION_ERRORS=0

for i in "${!PLUGINS[@]}"; do
    PLUGIN="${PLUGINS[$i]}"
    NAME="${PLUGIN_NAMES[$i]}"

    # Check if already active
    IS_ACTIVE=$(ssh "${USER}@${HOST}" \
        "cd ${REMOTE_PATH} && wp plugin is-active $(echo "$PLUGIN" | cut -d'/' -f1) 2>/dev/null && echo 'yes' || echo 'no'")

    if [ "$IS_ACTIVE" = "yes" ]; then
        log_info "  ✓ ${NAME} — already active"
        continue
    fi

    log_info "  Activating ${NAME}..."
    ACTIVATE_OUTPUT=$(ssh "${USER}@${HOST}" \
        "cd ${REMOTE_PATH} && wp plugin activate $(echo "$PLUGIN" | cut -d'/' -f1) 2>&1") || {
        log_error "  ✗ Failed to activate ${NAME}"
        log_error "    ${ACTIVATE_OUTPUT}"
        ACTIVATION_ERRORS=$((ACTIVATION_ERRORS + 1))
        continue
    }

    # Verify activation
    IS_ACTIVE_NOW=$(ssh "${USER}@${HOST}" \
        "cd ${REMOTE_PATH} && wp plugin is-active $(echo "$PLUGIN" | cut -d'/' -f1) 2>/dev/null && echo 'yes' || echo 'no'")

    if [ "$IS_ACTIVE_NOW" = "yes" ]; then
        log_info "  ✓ ${NAME} — activated successfully"
    else
        log_error "  ✗ ${NAME} — activation reported success but plugin is not active"
        ACTIVATION_ERRORS=$((ACTIVATION_ERRORS + 1))
    fi
done

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Step 2: Activate Block Theme
# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 2/3: Activating Block Theme..."

CURRENT_THEME=$(ssh "${USER}@${HOST}" \
    "cd ${REMOTE_PATH} && wp theme list --status=active --field=name 2>/dev/null || echo 'unknown'")

if [ "$CURRENT_THEME" = "$THEME_SLUG" ]; then
    log_info "Theme '${THEME_SLUG}' is already active"
else
    log_info "Current theme: ${CURRENT_THEME}"
    log_info "Switching to: ${THEME_SLUG}"

    THEME_OUTPUT=$(ssh "${USER}@${HOST}" \
        "cd ${REMOTE_PATH} && wp theme activate ${THEME_SLUG} 2>&1") || {
        log_error "Failed to activate theme: ${THEME_OUTPUT}"
        ACTIVATION_ERRORS=$((ACTIVATION_ERRORS + 1))
    }

    # Verify theme activation
    NEW_THEME=$(ssh "${USER}@${HOST}" \
        "cd ${REMOTE_PATH} && wp theme list --status=active --field=name 2>/dev/null || echo 'unknown'")

    if [ "$NEW_THEME" = "$THEME_SLUG" ]; then
        log_info "✓ Theme activated successfully"
    else
        log_error "✗ Theme activation failed — active theme is: ${NEW_THEME}"
        ACTIVATION_ERRORS=$((ACTIVATION_ERRORS + 1))
    fi
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Step 3: Post-activation verification
# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 3/3: Post-activation verification..."

# Flush rewrite rules
log_info "Flushing rewrite rules..."
ssh "${USER}@${HOST}" \
    "cd ${REMOTE_PATH} && wp rewrite flush 2>/dev/null || true"

# Flush caches
log_info "Flushing caches..."
ssh "${USER}@${HOST}" \
    "cd ${REMOTE_PATH} && \
     wp cache flush 2>/dev/null || true && \
     wp transient delete --all 2>/dev/null || true"

# Check for PHP fatal errors
log_info "Checking for PHP errors..."
PHP_ERRORS=$(ssh "${USER}@${HOST}" \
    "cd ${REMOTE_PATH} && wp eval 'echo \"PHP OK\";' 2>&1") || true

if echo "$PHP_ERRORS" | grep -q "PHP OK"; then
    log_info "✓ No PHP fatal errors detected"
else
    log_error "✗ PHP errors detected:"
    echo "  ${PHP_ERRORS}"
    ACTIVATION_ERRORS=$((ACTIVATION_ERRORS + 1))
fi

# Verify health endpoint
log_info "Checking health endpoint..."
HEALTH_STATUS=$(curl -s -o /dev/null -w "%{http_code}" \
    "${SITE_URL}/wp-json/reforestamos/v1/health" 2>/dev/null || echo "000")

if [ "$HEALTH_STATUS" = "200" ]; then
    log_info "✓ Health endpoint returned HTTP 200"
else
    log_warn "Health endpoint returned HTTP ${HEALTH_STATUS}"
fi

# List active plugins
log_info "Active plugins:"
ssh "${USER}@${HOST}" \
    "cd ${REMOTE_PATH} && wp plugin list --status=active --fields=name,version --format=table 2>/dev/null || true"

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Summary
# ─────────────────────────────────────────────────────────────────────────────
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Activation Summary                                          ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

if [ "$ACTIVATION_ERRORS" -eq 0 ]; then
    log_info "All components activated successfully!"
    echo ""
    echo "Next steps:"
    echo "  1. Verify functionality: ./deploy/post-deploy-verify.sh"
    echo "  2. Monitor: ./deploy/post-deploy-monitor.sh"
else
    log_error "${ACTIVATION_ERRORS} activation error(s) detected"
    echo ""
    echo "Troubleshooting:"
    echo "  - Check PHP error log: ssh ${USER}@${HOST} 'tail -50 ${REMOTE_PATH}/wp-content/debug.log'"
    echo "  - Rollback if needed: ./deploy/rollback.sh --env ${ENV}"
fi

exit $ACTIVATION_ERRORS
