#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Production Migration Script
# =============================================================================
# Orchestrates the full migration process: backup → migrate → verify.
# Usage: ./deploy/run-migration.sh [--dry-run] [--skip-backup] [--verbose]
#
# This script:
#   1. Creates a complete backup (database + files)
#   2. Runs the migration system (content, shortcodes, templates)
#   3. Validates data integrity post-migration
#
# Required environment variables (from .env.production or exported):
#   PRODUCTION_HOST, PRODUCTION_USER, PRODUCTION_PATH, PRODUCTION_DB_NAME
#
# Requirements: 16.1, 16.2, 16.9, 25.9
# =============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
ENV_FILE="$PROJECT_ROOT/.env.production"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log_info()  { echo -e "${GREEN}[MIGRATION]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }
log_step()  { echo -e "${BLUE}[STEP]${NC} $1"; }

# Parse arguments
DRY_RUN=false
SKIP_BACKUP=false
VERBOSE=false

while [[ $# -gt 0 ]]; do
    case $1 in
        --dry-run)      DRY_RUN=true; shift ;;
        --skip-backup)  SKIP_BACKUP=true; shift ;;
        --verbose)      VERBOSE=true; shift ;;
        -h|--help)
            echo "Usage: ./deploy/run-migration.sh [--dry-run] [--skip-backup] [--verbose]"
            echo ""
            echo "Options:"
            echo "  --dry-run       Preview migration without making changes"
            echo "  --skip-backup   Skip backup step (not recommended)"
            echo "  --verbose       Show detailed migration output"
            echo "  -h, --help      Show this help message"
            exit 0
            ;;
        *) log_error "Unknown option: $1"; exit 1 ;;
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

PRODUCTION_URL="${PRODUCTION_URL:-https://reforestamos.org}"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
MIGRATION_LOG="${PROJECT_ROOT}/deploy/logs/migration-${TIMESTAMP}.log"

# Create logs directory
mkdir -p "$(dirname "$MIGRATION_LOG")"

# ─────────────────────────────────────────────────────────────────────────────
# Header
# ─────────────────────────────────────────────────────────────────────────────
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Reforestamos México — Production Migration                  ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo "  Host:      ${PRODUCTION_HOST}"
echo "  Path:      ${PRODUCTION_PATH}"
echo "  Timestamp: ${TIMESTAMP}"
echo "  Dry Run:   ${DRY_RUN}"
echo "  Log:       ${MIGRATION_LOG}"
echo ""

if [ "$DRY_RUN" = true ]; then
    log_warn "DRY-RUN MODE: No permanent changes will be made"
    echo ""
fi

# Safety confirmation (skip for dry-run)
if [ "$DRY_RUN" = false ]; then
    echo -e "${RED}WARNING: This will run the migration on PRODUCTION${NC}"
    read -p "Type 'migrate' to confirm: " CONFIRM
    if [ "$CONFIRM" != "migrate" ]; then
        log_error "Migration cancelled."
        exit 1
    fi
fi

# ─────────────────────────────────────────────────────────────────────────────
# Step 1: Create complete backup
# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 1/3: Creating complete backup..."

if [ "$SKIP_BACKUP" = true ]; then
    log_warn "Backup skipped (--skip-backup flag)"
elif [ "$DRY_RUN" = true ]; then
    log_info "Dry-run: Would create backup pre-migration-${TIMESTAMP}"
else
    # Database backup
    log_info "Creating database backup..."
    ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "cd ${PRODUCTION_PATH} && mkdir -p backups && \
         wp db export - 2>/dev/null | gzip > backups/pre-migration-${TIMESTAMP}.sql.gz"

    DB_SIZE=$(ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "stat -c%s ${PRODUCTION_PATH}/backups/pre-migration-${TIMESTAMP}.sql.gz 2>/dev/null || echo 0")

    if [ "$DB_SIZE" -gt 0 ]; then
        log_info "Database backup created: pre-migration-${TIMESTAMP}.sql.gz (${DB_SIZE} bytes)"
    else
        log_error "Database backup failed or is empty!"
        exit 1
    fi

    # File backup (theme + plugins + uploads)
    log_info "Creating file backup..."
    ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "cd ${PRODUCTION_PATH} && \
         tar -czf backups/pre-migration-${TIMESTAMP}.tar.gz \
         wp-content/themes/ \
         wp-content/plugins/reforestamos-core \
         wp-content/plugins/reforestamos-micrositios \
         wp-content/plugins/reforestamos-comunicacion \
         wp-content/plugins/reforestamos-empresas \
         2>/dev/null || true"

    FILES_SIZE=$(ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "stat -c%s ${PRODUCTION_PATH}/backups/pre-migration-${TIMESTAMP}.tar.gz 2>/dev/null || echo 0")

    if [ "$FILES_SIZE" -gt 0 ]; then
        log_info "File backup created: pre-migration-${TIMESTAMP}.tar.gz (${FILES_SIZE} bytes)"
    else
        log_warn "File backup may be empty (first deployment?)"
    fi

    log_info "Backup completed successfully"
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Step 2: Run migration system
# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 2/3: Running migration system..."

# Build WP-CLI migration command
WP_MIGRATE_CMD="wp reforestamos migrate"

if [ "$DRY_RUN" = true ]; then
    WP_MIGRATE_CMD="${WP_MIGRATE_CMD} --dry-run"
fi

if [ "$VERBOSE" = true ]; then
    WP_MIGRATE_CMD="${WP_MIGRATE_CMD} --verbose"
fi

log_info "Executing: ${WP_MIGRATE_CMD}"

# Enable maintenance mode during migration (unless dry-run)
if [ "$DRY_RUN" = false ]; then
    log_info "Enabling maintenance mode..."
    ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "cd ${PRODUCTION_PATH} && wp maintenance-mode activate 2>/dev/null || \
         echo '<?php \$upgrading = time(); ?>' > .maintenance"
fi

# Run the migration
MIGRATION_EXIT_CODE=0
ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
    "cd ${PRODUCTION_PATH} && ${WP_MIGRATE_CMD} 2>&1" | tee "$MIGRATION_LOG" || MIGRATION_EXIT_CODE=$?

if [ "$MIGRATION_EXIT_CODE" -ne 0 ]; then
    log_error "Migration command exited with code ${MIGRATION_EXIT_CODE}"

    if [ "$DRY_RUN" = false ]; then
        log_warn "Disabling maintenance mode after failure..."
        ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
            "cd ${PRODUCTION_PATH} && wp maintenance-mode deactivate 2>/dev/null || rm -f .maintenance"

        echo ""
        log_error "Migration failed! To restore from backup, run:"
        echo "  ssh ${PRODUCTION_USER}@${PRODUCTION_HOST}"
        echo "  cd ${PRODUCTION_PATH}"
        echo "  gunzip -c backups/pre-migration-${TIMESTAMP}.sql.gz | wp db import -"
        echo "  tar -xzf backups/pre-migration-${TIMESTAMP}.tar.gz"
    fi
    exit 1
fi

log_info "Migration completed"
echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Step 3: Verify data integrity
# ─────────────────────────────────────────────────────────────────────────────
log_step "Step 3/3: Verifying data integrity..."

VALIDATION_EXIT_CODE=0

if [ "$DRY_RUN" = true ]; then
    log_info "Dry-run: Skipping post-migration validation"
else
    # Run the migration validator via WP-CLI
    ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "cd ${PRODUCTION_PATH} && wp reforestamos validate-migration 2>&1" | tee -a "$MIGRATION_LOG" || VALIDATION_EXIT_CODE=$?

    if [ "$VALIDATION_EXIT_CODE" -ne 0 ]; then
        log_warn "Validation reported issues (exit code ${VALIDATION_EXIT_CODE})"
        log_warn "Review the migration report for details"
    else
        log_info "Data integrity validation passed"
    fi

    # Verify key content counts
    log_info "Verifying content counts..."
    ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "cd ${PRODUCTION_PATH} && \
         echo '  Posts:       ' \$(wp post list --post_type=post --format=count 2>/dev/null) && \
         echo '  Pages:       ' \$(wp post list --post_type=page --format=count 2>/dev/null) && \
         echo '  Empresas:    ' \$(wp post list --post_type=empresas --format=count 2>/dev/null) && \
         echo '  Eventos:     ' \$(wp post list --post_type=eventos --format=count 2>/dev/null) && \
         echo '  Integrantes: ' \$(wp post list --post_type=integrantes --format=count 2>/dev/null) && \
         echo '  Boletín:     ' \$(wp post list --post_type=boletin --format=count 2>/dev/null) && \
         echo '  Media:       ' \$(wp post list --post_type=attachment --format=count 2>/dev/null)"

    # Verify no orphaned metadata
    log_info "Checking for orphaned metadata..."
    ORPHANED=$(ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "cd ${PRODUCTION_PATH} && wp db query \
         \"SELECT COUNT(*) FROM \$(wp db prefix 2>/dev/null)postmeta pm LEFT JOIN \$(wp db prefix 2>/dev/null)posts p ON pm.post_id = p.ID WHERE p.ID IS NULL\" \
         --skip-column-names 2>/dev/null || echo 'N/A'")
    log_info "Orphaned metadata entries: ${ORPHANED}"

    # Disable maintenance mode
    log_info "Disabling maintenance mode..."
    ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "cd ${PRODUCTION_PATH} && wp maintenance-mode deactivate 2>/dev/null || rm -f .maintenance"

    # Flush caches
    log_info "Flushing caches..."
    ssh "${PRODUCTION_USER}@${PRODUCTION_HOST}" \
        "cd ${PRODUCTION_PATH} && \
         wp cache flush 2>/dev/null || true && \
         wp rewrite flush 2>/dev/null || true && \
         wp transient delete --all 2>/dev/null || true"
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Summary
# ─────────────────────────────────────────────────────────────────────────────
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Migration Summary                                           ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo "  Mode:       $([ "$DRY_RUN" = true ] && echo 'DRY-RUN' || echo 'PRODUCTION')"
echo "  Migration:  $([ "$MIGRATION_EXIT_CODE" -eq 0 ] && echo '✓ Completed' || echo '✗ Failed')"
echo "  Validation: $([ "$VALIDATION_EXIT_CODE" -eq 0 ] && echo '✓ Passed' || echo '⚠ Issues found')"
echo "  Log:        ${MIGRATION_LOG}"

if [ "$DRY_RUN" = false ] && [ "$SKIP_BACKUP" = false ]; then
    echo "  Backup DB:  backups/pre-migration-${TIMESTAMP}.sql.gz"
    echo "  Backup Files: backups/pre-migration-${TIMESTAMP}.tar.gz"
fi

echo ""

if [ "$MIGRATION_EXIT_CODE" -eq 0 ] && [ "$VALIDATION_EXIT_CODE" -eq 0 ]; then
    log_info "Migration completed successfully!"
    echo ""
    echo "Next steps:"
    echo "  1. Activate theme and plugins: ./deploy/activate-theme-plugins.sh"
    echo "  2. Verify functionality: ./deploy/post-deploy-verify.sh"
    echo "  3. Monitor: ./deploy/post-deploy-monitor.sh"
elif [ "$MIGRATION_EXIT_CODE" -eq 0 ]; then
    log_warn "Migration completed with validation warnings. Review the log before proceeding."
else
    log_error "Migration failed. Review the log and consider restoring from backup."
    echo ""
    echo "To rollback:"
    echo "  bash deploy/rollback.sh --env production --backup pre-migration-${TIMESTAMP}.tar.gz"
fi
