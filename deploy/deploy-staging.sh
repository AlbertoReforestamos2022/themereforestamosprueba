#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Staging Deployment Script
# =============================================================================
# Deploys the project to the staging environment.
# Usage: ./deploy/deploy-staging.sh
#
# Required environment variables (from .env.staging or exported):
#   STAGING_HOST, STAGING_USER, STAGING_PATH
# =============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
ENV_FILE="$PROJECT_ROOT/.env.staging"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log_info()  { echo -e "${GREEN}[STAGING]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

# Load environment
if [ -f "$ENV_FILE" ]; then
    set -a; source "$ENV_FILE"; set +a
    log_info "Loaded environment from .env.staging"
else
    log_warn "No .env.staging found, using exported environment variables"
fi

# Validate required vars
: "${STAGING_HOST:?'STAGING_HOST is required'}"
: "${STAGING_USER:?'STAGING_USER is required'}"
: "${STAGING_PATH:?'STAGING_PATH is required'}"

DEPLOY_TIMESTAMP=$(date +%Y%m%d_%H%M%S)
DEPLOY_COMPONENTS=(
    "reforestamos-block-theme"
    "reforestamos-core"
    "reforestamos-micrositios"
    "reforestamos-comunicacion"
    "reforestamos-empresas"
)

cd "$PROJECT_ROOT"

# 1. Run build
log_info "Running production build..."
bash "$SCRIPT_DIR/build.sh" --skip-tests

# 2. Create backup on staging
log_info "Creating backup on staging server..."
ssh "${STAGING_USER}@${STAGING_HOST}" \
    "cd ${STAGING_PATH} && mkdir -p backups && \
     tar -czf backups/pre-deploy-${DEPLOY_TIMESTAMP}.tar.gz \
     wp-content/themes/reforestamos-block-theme \
     wp-content/plugins/reforestamos-core \
     wp-content/plugins/reforestamos-micrositios \
     wp-content/plugins/reforestamos-comunicacion \
     wp-content/plugins/reforestamos-empresas \
     2>/dev/null || true"

# 3. Sync theme
log_info "Deploying theme..."
rsync -avz --delete \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='src' \
    --exclude='*.map' \
    reforestamos-block-theme/ \
    "${STAGING_USER}@${STAGING_HOST}:${STAGING_PATH}/wp-content/themes/reforestamos-block-theme/"

# 4. Sync plugins
for plugin in "${DEPLOY_COMPONENTS[@]:1}"; do
    log_info "Deploying plugin: $plugin..."
    rsync -avz --delete \
        --exclude='node_modules' \
        --exclude='.git' \
        --exclude='tests' \
        "$plugin/" \
        "${STAGING_USER}@${STAGING_HOST}:${STAGING_PATH}/wp-content/plugins/$plugin/"
done

# 5. Run post-deploy on staging
log_info "Running post-deploy tasks..."
ssh "${STAGING_USER}@${STAGING_HOST}" \
    "cd ${STAGING_PATH} && \
     wp cache flush 2>/dev/null || true && \
     wp rewrite flush 2>/dev/null || true"

# 6. Health check
log_info "Running health check..."
STAGING_URL="${STAGING_URL:-https://staging.reforestamos.org}"
HEALTH_URL="${STAGING_URL}/wp-json/reforestamos/v1/health"

HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$HEALTH_URL" 2>/dev/null || echo "000")
if [ "$HTTP_STATUS" = "200" ]; then
    log_info "Health check passed (HTTP $HTTP_STATUS)"
else
    log_warn "Health check returned HTTP $HTTP_STATUS (endpoint may not be configured yet)"
fi

log_info "Staging deployment completed! (${DEPLOY_TIMESTAMP})"
echo ""
echo "Staging URL: ${STAGING_URL}"
echo "Backup: backups/pre-deploy-${DEPLOY_TIMESTAMP}.tar.gz"
