#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Backup Script
# =============================================================================
# Creates database and/or file backups before deployment.
# Usage: ./deploy/backup.sh --env <staging|production> [--db-only] [--files-only]
# =============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log_info()  { echo -e "${GREEN}[BACKUP]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

ENV=""
DB_ONLY=false
FILES_ONLY=false

while [[ $# -gt 0 ]]; do
    case $1 in
        --env) ENV="$2"; shift 2 ;;
        --db-only) DB_ONLY=true; shift ;;
        --files-only) FILES_ONLY=true; shift ;;
        *) log_error "Unknown option: $1"; exit 1 ;;
    esac
done

if [ -z "$ENV" ]; then
    log_error "Usage: ./deploy/backup.sh --env <staging|production> [--db-only] [--files-only]"
    exit 1
fi

# Load environment
ENV_FILE="$PROJECT_ROOT/.env.${ENV}"
if [ -f "$ENV_FILE" ]; then
    set -a; source "$ENV_FILE"; set +a
fi

# Resolve host/user/path based on environment
case "$ENV" in
    staging)
        HOST="${STAGING_HOST:?'STAGING_HOST is required'}"
        USER="${STAGING_USER:?'STAGING_USER is required'}"
        REMOTE_PATH="${STAGING_PATH:?'STAGING_PATH is required'}"
        DB_NAME="${STAGING_DB_NAME:-}"
        ;;
    production)
        HOST="${PRODUCTION_HOST:?'PRODUCTION_HOST is required'}"
        USER="${PRODUCTION_USER:?'PRODUCTION_USER is required'}"
        REMOTE_PATH="${PRODUCTION_PATH:?'PRODUCTION_PATH is required'}"
        DB_NAME="${PRODUCTION_DB_NAME:?'PRODUCTION_DB_NAME is required'}"
        ;;
    *)
        log_error "Invalid environment: $ENV (use staging or production)"
        exit 1
        ;;
esac

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="${REMOTE_PATH}/backups"
RETENTION_DAYS="${BACKUP_RETENTION_DAYS:-30}"

# Ensure backup directory exists
ssh "${USER}@${HOST}" "mkdir -p ${BACKUP_DIR}"

# Database backup
if [ "$FILES_ONLY" = false ]; then
    log_info "Creating database backup on $ENV..."
    ssh "${USER}@${HOST}" \
        "cd ${REMOTE_PATH} && \
         wp db export - 2>/dev/null | gzip > ${BACKUP_DIR}/pre-deploy-${TIMESTAMP}.sql.gz"

    # Verify backup was created
    DB_SIZE=$(ssh "${USER}@${HOST}" \
        "stat -c%s ${BACKUP_DIR}/pre-deploy-${TIMESTAMP}.sql.gz 2>/dev/null || echo 0")

    if [ "$DB_SIZE" -gt 0 ]; then
        log_info "Database backup created: pre-deploy-${TIMESTAMP}.sql.gz (${DB_SIZE} bytes)"
    else
        log_error "Database backup appears empty or failed!"
        exit 1
    fi
fi

# File backup
if [ "$DB_ONLY" = false ]; then
    log_info "Creating file backup on $ENV..."
    ssh "${USER}@${HOST}" \
        "cd ${REMOTE_PATH} && \
         tar -czf ${BACKUP_DIR}/pre-deploy-${TIMESTAMP}.tar.gz \
         wp-content/themes/reforestamos-block-theme \
         wp-content/plugins/reforestamos-core \
         wp-content/plugins/reforestamos-micrositios \
         wp-content/plugins/reforestamos-comunicacion \
         wp-content/plugins/reforestamos-empresas \
         2>/dev/null || true"

    FILES_SIZE=$(ssh "${USER}@${HOST}" \
        "stat -c%s ${BACKUP_DIR}/pre-deploy-${TIMESTAMP}.tar.gz 2>/dev/null || echo 0")

    if [ "$FILES_SIZE" -gt 0 ]; then
        log_info "File backup created: pre-deploy-${TIMESTAMP}.tar.gz (${FILES_SIZE} bytes)"
    else
        log_warn "File backup may be empty (first deployment?)"
    fi
fi

# Clean old backups
log_info "Cleaning backups older than ${RETENTION_DAYS} days..."
ssh "${USER}@${HOST}" \
    "find ${BACKUP_DIR} -name 'pre-deploy-*' -mtime +${RETENTION_DAYS} -delete 2>/dev/null || true"

# List current backups
log_info "Current backups on $ENV:"
ssh "${USER}@${HOST}" \
    "ls -lh ${BACKUP_DIR}/pre-deploy-* 2>/dev/null || echo '  (none)'"

log_info "Backup completed successfully!"
