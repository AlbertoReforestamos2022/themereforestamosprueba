#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Production Deployment Script
# =============================================================================
# Deploys the project to the production environment.
# Usage: ./deploy/deploy-production.sh [--force]
#
# Required environment variables (from .env.production or exported):
#   PRODUCTION_HOST, PRODUCTION_USER, PRODUCTION_PATH, PRODUCTION_DB_NAME
# =============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
ENV_FILE="$PROJECT_ROOT/.env.production"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log_info()  { echo -e "${GREEN}[PROD]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

FORCE=false
for arg in "$@"; do
    case $arg in
        --force) FORCE=true ;;
    esac
done

# Load environment
if [ -f "$ENV_FILE" ]; then
    set -a; source "$ENV_FILE"; set +a
    log_info "Loaded environment from .env.production"
else
    log_warn "No .env.production found, using exported environment variables"
fi

# Validate required vars
: "${PRODUCTION_HOST:?'PRODUCTION_HOST is required'}"
: "${PRODUCTION_USER:?'PRODUCTION_USER is required'}"
: "${PRODUCTION_PATH:?'PRODUCTION_PATH is required'}"
: "${PRODUCTION_DB_NAME:?'PRODUCTION_DB_NAME is required'}"

DEPLOY_TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Safety confirmation
if [ "$FORCE" = false ]; then
    echo -e "${RED}WARNING: You are about to deploy to PRODUCTION${NC}"
    echo "Host: ${PRODUCTION_HOST}"
    echo "Path: ${PRODUCTION_PATH}"
    read -p "Type 'deploy' to confirm: " CONFIRM
    if [ "$CONFIRM" != "deploy" ]; then
        log_error "Deployment cancelled."
        exit 1
    fi
fi

cd "$PROJECT_ROOT"

# 1. Run full build with tests
log_info "Running production build with tests..."
bash "$SCRIPT_DIR/build.sh"

# 2. Database backup
log_info "Creating database backup..."
bash "$SCRIPT_DIR/backup.sh" --env production --db-only
log_info "Database backup completed."

# 3. File backup on production server
log_info "Creating file backup on production server..."
ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
    "cd ${PRODUCTION_PATH} && mkdir -p backups && \
     tar -czf backups/pre-deploy-${DEPLOY_TIMESTAMP}.tar.gz \
     wp-content/themes/reforestamos-block-theme \
     wp-content/plugins/reforestamos-core \
     wp-content/plugins/reforestamos-micrositios \
     wp-content/plugins/reforestamos-comunicacion \
     wp-content/plugins/reforestamos-empresas \
     2>/dev/null || true"

# 4. Enable maintenance mode
log_info "Enabling maintenance mode..."
ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
    "cd ${PRODUCTION_PATH} && wp maintenance-mode activate 2>/dev/null || \
     echo '<?php \$upgrading = time(); ?>' > .maintenance"

# 5. Deploy theme
log_info "Deploying theme..."
rsync -avz --delete \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='src' \
    --exclude='tests' \
    --exclude='*.map' \
    reforestamos-block-theme/ \
    "${PRODUCTION_USER}@${PRODUCTION_HOST}:${PRODUCTION_PATH}/wp-content/themes/reforestamos-block-theme/"

# 6. Deploy plugins
PLUGINS=("reforestamos-core" "reforestamos-micrositios" "reforestamos-comunicacion" "reforestamos-empresas")
for plugin in "${PLUGINS[@]}"; do
    log_info "Deploying plugin: $plugin..."
    rsync -avz --delete \
        --exclude='node_modules' \
        --exclude='.git' \
        --exclude='tests' \
        "$plugin/" \
        "${PRODUCTION_USER}@${PRODUCTION_HOST}:${PRODUCTION_PATH}/wp-content/plugins/$plugin/"
done

# 7. Post-deploy tasks
log_info "Running post-deploy tasks..."
ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
    "cd ${PRODUCTION_PATH} && \
     wp cache flush 2>/dev/null || true && \
     wp rewrite flush 2>/dev/null || true"

# 8. Disable maintenance mode
log_info "Disabling maintenance mode..."
ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
    "cd ${PRODUCTION_PATH} && wp maintenance-mode deactivate 2>/dev/null || \
     rm -f .maintenance"

# 9. Health check
log_info "Running health check..."
PRODUCTION_URL="${PRODUCTION_URL:-https://reforestamos.org}"
HEALTH_URL="${PRODUCTION_URL}/wp-json/reforestamos/v1/health"

HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$HEALTH_URL" 2>/dev/null || echo "000")
if [ "$HTTP_STATUS" = "200" ]; then
    log_info "Health check passed (HTTP $HTTP_STATUS)"
else
    log_warn "Health check returned HTTP $HTTP_STATUS"
    log_warn "Consider running rollback if site is not functioning: ./deploy/rollback.sh --env production"
fi

log_info "Production deployment completed! (${DEPLOY_TIMESTAMP})"
echo ""
echo "Production URL: ${PRODUCTION_URL}"
echo "Backup: backups/pre-deploy-${DEPLOY_TIMESTAMP}.tar.gz"
echo ""
echo "If issues arise, run: ./deploy/rollback.sh --env production"
