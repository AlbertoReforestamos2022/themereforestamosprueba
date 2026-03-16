#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Rollback Script
# =============================================================================
# Rolls back a failed deployment by restoring the most recent backup.
# Usage: ./deploy/rollback.sh --env <staging|production> [--backup <filename>]
# =============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log_info()  { echo -e "${GREEN}[ROLLBACK]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

ENV=""
BACKUP_FILE=""

while [[ $# -gt 0 ]]; do
    case $1 in
        --env) ENV="$2"; shift 2 ;;
        --backup) BACKUP_FILE="$2"; shift 2 ;;
        *) log_error "Unknown option: $1"; exit 1 ;;
    esac
done

if [ -z "$ENV" ]; then
    log_error "Usage: ./deploy/rollback.sh --env <staging|production> [--backup <filename>]"
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
        ;;
    production)
        HOST="${PRODUCTION_HOST:?'PRODUCTION_HOST is required'}"
        USER="${PRODUCTION_USER:?'PRODUCTION_USER is required'}"
        REMOTE_PATH="${PRODUCTION_PATH:?'PRODUCTION_PATH is required'}"
        ;;
    *)
        log_error "Invalid environment: $ENV (use staging or production)"
        exit 1
        ;;
esac

# Find the latest backup if none specified
if [ -z "$BACKUP_FILE" ]; then
    log_info "Finding latest backup on $ENV server..."
    BACKUP_FILE=$(ssh "${USER}@${HOST}" \
        "ls -t ${REMOTE_PATH}/backups/pre-deploy-*.tar.gz 2>/dev/null | head -1")

    if [ -z "$BACKUP_FILE" ]; then
        log_error "No backup files found on $ENV server."
        exit 1
    fi
    log_info "Latest backup: $(basename "$BACKUP_FILE")"
else
    BACKUP_FILE="${REMOTE_PATH}/backups/${BACKUP_FILE}"
fi

# Confirmation
echo -e "${YELLOW}Rolling back $ENV to backup: $(basename "$BACKUP_FILE")${NC}"
read -p "Type 'rollback' to confirm: " CONFIRM
if [ "$CONFIRM" != "rollback" ]; then
    log_error "Rollback cancelled."
    exit 1
fi

# Enable maintenance mode
log_info "Enabling maintenance mode..."
ssh "${USER}@${HOST}" \
    "cd ${REMOTE_PATH} && wp maintenance-mode activate 2>/dev/null || \
     echo '<?php \$upgrading = time(); ?>' > .maintenance"

# Restore backup
log_info "Restoring files from backup..."
ssh "${USER}@${HOST}" \
    "cd ${REMOTE_PATH} && tar -xzf ${BACKUP_FILE}"

# Restore database if SQL backup exists
DB_BACKUP="${BACKUP_FILE%.tar.gz}.sql.gz"
DB_EXISTS=$(ssh "${USER}@${HOST}" "test -f ${DB_BACKUP} && echo 'yes' || echo 'no'")
if [ "$DB_EXISTS" = "yes" ]; then
    log_info "Restoring database from backup..."
    ssh "${USER}@${HOST}" \
        "cd ${REMOTE_PATH} && gunzip -c ${DB_BACKUP} | wp db import -"
    log_info "Database restored."
else
    log_warn "No database backup found at $(basename "$DB_BACKUP"), skipping DB restore."
fi

# Flush caches
log_info "Flushing caches..."
ssh "${USER}@${HOST}" \
    "cd ${REMOTE_PATH} && \
     wp cache flush 2>/dev/null || true && \
     wp rewrite flush 2>/dev/null || true"

# Disable maintenance mode
log_info "Disabling maintenance mode..."
ssh "${USER}@${HOST}" \
    "cd ${REMOTE_PATH} && wp maintenance-mode deactivate 2>/dev/null || \
     rm -f .maintenance"

log_info "Rollback completed successfully on $ENV!"
echo ""
echo "Restored from: $(basename "$BACKUP_FILE")"
echo "Verify the site is functioning correctly."
