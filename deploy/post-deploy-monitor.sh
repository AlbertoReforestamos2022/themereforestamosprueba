#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Post-Deployment Monitoring Script
# =============================================================================
# Monitors the production site after deployment for errors, performance
# issues, and uptime problems.
#
# Usage:
#   ./deploy/post-deploy-monitor.sh [--env <staging|production>] [--duration <minutes>] [--interval <seconds>]
#
# Monitors:
#   - Error logs (PHP errors, WordPress debug log)
#   - Performance (response times, TTFB)
#   - Uptime (health endpoint polling)
#   - Critical issue detection and alerting
#
# Requirements: 35.2, 35.4, 25.8
# =============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

log_info()  { echo -e "${GREEN}[MONITOR]${NC} $(date '+%H:%M:%S') $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $(date '+%H:%M:%S') $1"; }
log_error() { echo -e "${RED}[ALERT]${NC} $(date '+%H:%M:%S') $1"; }
log_step()  { echo -e "${BLUE}[CHECK]${NC} $(date '+%H:%M:%S') $1"; }

# Parse arguments
ENV="production"
DURATION=30          # minutes
INTERVAL=60          # seconds between checks
ALERT_EMAIL=""       # optional email for alerts

while [[ $# -gt 0 ]]; do
    case $1 in
        --env)       ENV="$2"; shift 2 ;;
        --duration)  DURATION="$2"; shift 2 ;;
        --interval)  INTERVAL="$2"; shift 2 ;;
        --alert-email) ALERT_EMAIL="$2"; shift 2 ;;
        -h|--help)
            echo "Usage: ./deploy/post-deploy-monitor.sh [options]"
            echo ""
            echo "Options:"
            echo "  --env <env>          Environment: staging or production (default: production)"
            echo "  --duration <min>     Monitoring duration in minutes (default: 30)"
            echo "  --interval <sec>     Check interval in seconds (default: 60)"
            echo "  --alert-email <addr> Email address for critical alerts"
            echo "  -h, --help           Show this help"
            exit 0
            ;;
        *) shift ;;
    esac
done

# Load environment
ENV_FILE="$PROJECT_ROOT/.env.${ENV}"
if [ -f "$ENV_FILE" ]; then
    set -a; source "$ENV_FILE"; set +a
fi

case "$ENV" in
    staging)
        HOST="${STAGING_HOST:-}"
        USER="${STAGING_USER:-}"
        REMOTE_PATH="${STAGING_PATH:-}"
        SITE_URL="${STAGING_URL:-https://staging.reforestamos.org}"
        ;;
    production)
        HOST="${PRODUCTION_HOST:-}"
        USER="${PRODUCTION_USER:-}"
        REMOTE_PATH="${PRODUCTION_PATH:-}"
        SITE_URL="${PRODUCTION_URL:-https://reforestamos.org}"
        ;;
esac

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
LOG_DIR="${PROJECT_ROOT}/deploy/logs"
MONITOR_LOG="${LOG_DIR}/monitor-${TIMESTAMP}.log"
mkdir -p "$LOG_DIR"

# Thresholds
RESPONSE_TIME_WARN=3.0    # seconds
RESPONSE_TIME_CRIT=10.0   # seconds
ERROR_COUNT_WARN=5
ERROR_COUNT_CRIT=20

# Counters
TOTAL_CHECKS=0
UPTIME_OK=0
UPTIME_FAIL=0
SLOW_RESPONSES=0
CRITICAL_ERRORS=0
LAST_ERROR_COUNT=0

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Reforestamos México — Post-Deployment Monitor               ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo "  Environment: ${ENV}"
echo "  Site URL:    ${SITE_URL}"
echo "  Duration:    ${DURATION} minutes"
echo "  Interval:    ${INTERVAL} seconds"
echo "  Log:         ${MONITOR_LOG}"
echo ""
echo "  Press Ctrl+C to stop monitoring early"
echo ""

# Write log header
{
    echo "Post-Deployment Monitoring Log"
    echo "=============================="
    echo "Environment: ${ENV}"
    echo "URL: ${SITE_URL}"
    echo "Started: $(date -Iseconds)"
    echo "Duration: ${DURATION} minutes"
    echo "Interval: ${INTERVAL} seconds"
    echo ""
} > "$MONITOR_LOG"

# ─────────────────────────────────────────────────────────────────────────────
# Initial baseline check
# ─────────────────────────────────────────────────────────────────────────────
log_step "Running initial baseline check..."

# Get initial error count from server
if [ -n "$HOST" ] && [ -n "$USER" ] && [ -n "$REMOTE_PATH" ]; then
    LAST_ERROR_COUNT=$(ssh "${USER}@${HOST}" \
        "wc -l < ${REMOTE_PATH}/wp-content/debug.log 2>/dev/null || echo 0")
    log_info "Baseline error log lines: ${LAST_ERROR_COUNT}"
fi

# Initial health check
HEALTH_BODY=$(curl -s -L --max-time 10 "${SITE_URL}/wp-json/reforestamos/v1/health" 2>/dev/null || echo '{"status":"error"}')
HEALTH_STATUS=$(echo "$HEALTH_BODY" | grep -o '"status":"[^"]*"' | head -1 | cut -d'"' -f4 || echo "unknown")
log_info "Initial health status: ${HEALTH_STATUS}"

echo ""
echo "─────────────────────────────────────────────────────────────────"
echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Monitoring loop
# ─────────────────────────────────────────────────────────────────────────────
END_TIME=$(($(date +%s) + DURATION * 60))

# Trap Ctrl+C for graceful shutdown
trap 'echo ""; log_info "Monitoring stopped by user"; MONITORING=false' INT
MONITORING=true

while [ "$(date +%s)" -lt "$END_TIME" ] && [ "$MONITORING" = true ]; do
    TOTAL_CHECKS=$((TOTAL_CHECKS + 1))
    CHECK_TIME=$(date '+%Y-%m-%d %H:%M:%S')

    echo -e "${CYAN}── Check #${TOTAL_CHECKS} at ${CHECK_TIME} ──${NC}"

    # 1. Uptime check
    HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" -L --max-time 15 \
        "${SITE_URL}/wp-json/reforestamos/v1/uptime" 2>/dev/null || echo "000")

    if [ "$HTTP_STATUS" = "200" ]; then
        UPTIME_OK=$((UPTIME_OK + 1))
        echo -e "  Uptime:    ${GREEN}OK${NC} (HTTP ${HTTP_STATUS})"
    else
        UPTIME_FAIL=$((UPTIME_FAIL + 1))
        echo -e "  Uptime:    ${RED}DOWN${NC} (HTTP ${HTTP_STATUS})"
        echo "[${CHECK_TIME}] UPTIME FAIL: HTTP ${HTTP_STATUS}" >> "$MONITOR_LOG"

        if [ "$HTTP_STATUS" = "000" ] || [ "$HTTP_STATUS" = "500" ] || [ "$HTTP_STATUS" = "502" ] || [ "$HTTP_STATUS" = "503" ]; then
            CRITICAL_ERRORS=$((CRITICAL_ERRORS + 1))
            log_error "CRITICAL: Site is down! HTTP ${HTTP_STATUS}"
            echo "[${CHECK_TIME}] CRITICAL: Site down (HTTP ${HTTP_STATUS})" >> "$MONITOR_LOG"
        fi
    fi

    # 2. Response time
    RESPONSE_TIME=$(curl -s -o /dev/null -w "%{time_total}" -L --max-time 30 \
        "${SITE_URL}/" 2>/dev/null || echo "99")

    if (( $(echo "$RESPONSE_TIME < $RESPONSE_TIME_WARN" | bc -l 2>/dev/null || echo 0) )); then
        echo -e "  Response:  ${GREEN}${RESPONSE_TIME}s${NC}"
    elif (( $(echo "$RESPONSE_TIME < $RESPONSE_TIME_CRIT" | bc -l 2>/dev/null || echo 0) )); then
        SLOW_RESPONSES=$((SLOW_RESPONSES + 1))
        echo -e "  Response:  ${YELLOW}${RESPONSE_TIME}s${NC} (slow)"
        echo "[${CHECK_TIME}] SLOW: ${RESPONSE_TIME}s" >> "$MONITOR_LOG"
    else
        SLOW_RESPONSES=$((SLOW_RESPONSES + 1))
        echo -e "  Response:  ${RED}${RESPONSE_TIME}s${NC} (critical)"
        echo "[${CHECK_TIME}] CRITICAL SLOW: ${RESPONSE_TIME}s" >> "$MONITOR_LOG"
    fi

    # 3. Error log check (via SSH)
    if [ -n "$HOST" ] && [ -n "$USER" ] && [ -n "$REMOTE_PATH" ]; then
        CURRENT_ERROR_COUNT=$(ssh "${USER}@${HOST}" \
            "wc -l < ${REMOTE_PATH}/wp-content/debug.log 2>/dev/null || echo 0" 2>/dev/null || echo "$LAST_ERROR_COUNT")

        NEW_ERRORS=$((CURRENT_ERROR_COUNT - LAST_ERROR_COUNT))

        if [ "$NEW_ERRORS" -gt "$ERROR_COUNT_CRIT" ]; then
            echo -e "  Errors:    ${RED}+${NEW_ERRORS} new errors${NC} (critical)"
            CRITICAL_ERRORS=$((CRITICAL_ERRORS + 1))
            echo "[${CHECK_TIME}] CRITICAL: ${NEW_ERRORS} new errors" >> "$MONITOR_LOG"

            # Show last few errors
            log_error "Recent errors:"
            ssh "${USER}@${HOST}" \
                "tail -5 ${REMOTE_PATH}/wp-content/debug.log 2>/dev/null" | while read -r line; do
                echo "    ${line}"
            done
        elif [ "$NEW_ERRORS" -gt "$ERROR_COUNT_WARN" ]; then
            echo -e "  Errors:    ${YELLOW}+${NEW_ERRORS} new errors${NC}"
            echo "[${CHECK_TIME}] WARN: ${NEW_ERRORS} new errors" >> "$MONITOR_LOG"
        elif [ "$NEW_ERRORS" -gt 0 ]; then
            echo -e "  Errors:    ${YELLOW}+${NEW_ERRORS}${NC}"
        else
            echo -e "  Errors:    ${GREEN}none${NC}"
        fi

        LAST_ERROR_COUNT=$CURRENT_ERROR_COUNT

        # 4. Check Reforestamos-specific logs
        REFO_LOG_DIR="${REMOTE_PATH}/wp-content/reforestamos-logs"
        REFO_ERRORS=$(ssh "${USER}@${HOST}" \
            "find ${REFO_LOG_DIR} -name '*.log' -newer /tmp/monitor-marker -exec grep -c 'ERROR\|CRITICAL' {} + 2>/dev/null | \
             awk -F: '{sum+=\$2} END{print sum+0}'" 2>/dev/null || echo "0")

        if [ "$REFO_ERRORS" -gt 0 ]; then
            echo -e "  App Logs:  ${YELLOW}${REFO_ERRORS} error(s)${NC}"
        else
            echo -e "  App Logs:  ${GREEN}clean${NC}"
        fi

        # Update marker file for next check
        ssh "${USER}@${HOST}" "touch /tmp/monitor-marker 2>/dev/null" || true
    fi

    # 5. Memory / resource check
    if [ -n "$HOST" ] && [ -n "$USER" ]; then
        MEMORY_USAGE=$(ssh "${USER}@${HOST}" \
            "free -m 2>/dev/null | awk '/^Mem:/{printf \"%.0f\", \$3/\$2*100}'" 2>/dev/null || echo "N/A")

        if [ "$MEMORY_USAGE" != "N/A" ]; then
            if [ "$MEMORY_USAGE" -gt 90 ]; then
                echo -e "  Memory:    ${RED}${MEMORY_USAGE}%${NC} (critical)"
                echo "[${CHECK_TIME}] CRITICAL: Memory at ${MEMORY_USAGE}%" >> "$MONITOR_LOG"
            elif [ "$MEMORY_USAGE" -gt 80 ]; then
                echo -e "  Memory:    ${YELLOW}${MEMORY_USAGE}%${NC}"
            else
                echo -e "  Memory:    ${GREEN}${MEMORY_USAGE}%${NC}"
            fi
        fi
    fi

    echo ""

    # Check if we should alert
    if [ "$CRITICAL_ERRORS" -gt 0 ] && [ -n "$ALERT_EMAIL" ]; then
        log_error "Sending alert email to ${ALERT_EMAIL}..."
        # In a real setup, this would use mail/sendmail/curl to a notification service
        echo "ALERT: ${CRITICAL_ERRORS} critical issue(s) on ${ENV} — ${SITE_URL}" | \
            mail -s "[REFORESTAMOS] Critical Alert — ${ENV}" "$ALERT_EMAIL" 2>/dev/null || \
            log_warn "Could not send alert email"
    fi

    # Auto-suggest rollback on repeated critical failures
    if [ "$UPTIME_FAIL" -ge 3 ]; then
        echo ""
        log_error "Site has been down for ${UPTIME_FAIL} consecutive checks!"
        log_error "Consider rolling back: ./deploy/rollback.sh --env ${ENV}"
        echo ""
    fi

    # Sleep until next check (unless monitoring was stopped)
    if [ "$MONITORING" = true ] && [ "$(date +%s)" -lt "$END_TIME" ]; then
        sleep "$INTERVAL"
    fi
done

# ─────────────────────────────────────────────────────────────────────────────
# Final Summary
# ─────────────────────────────────────────────────────────────────────────────
echo ""
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Monitoring Summary                                          ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo "  Total checks:      ${TOTAL_CHECKS}"

UPTIME_PCT=0
if [ "$TOTAL_CHECKS" -gt 0 ]; then
    UPTIME_PCT=$(echo "scale=1; $UPTIME_OK * 100 / $TOTAL_CHECKS" | bc -l 2>/dev/null || echo "N/A")
fi

echo -e "  Uptime:            ${UPTIME_OK}/${TOTAL_CHECKS} (${UPTIME_PCT}%)"
echo -e "  Downtime events:   ${UPTIME_FAIL}"
echo -e "  Slow responses:    ${SLOW_RESPONSES}"
echo -e "  Critical errors:   ${CRITICAL_ERRORS}"
echo ""

# Write summary to log
{
    echo ""
    echo "Summary"
    echo "======="
    echo "Ended: $(date -Iseconds)"
    echo "Total checks: ${TOTAL_CHECKS}"
    echo "Uptime: ${UPTIME_OK}/${TOTAL_CHECKS} (${UPTIME_PCT}%)"
    echo "Downtime events: ${UPTIME_FAIL}"
    echo "Slow responses: ${SLOW_RESPONSES}"
    echo "Critical errors: ${CRITICAL_ERRORS}"
} >> "$MONITOR_LOG"

echo "  Full log: ${MONITOR_LOG}"
echo ""

if [ "$CRITICAL_ERRORS" -gt 0 ]; then
    log_error "Critical issues detected during monitoring!"
    echo ""
    echo "Recommended actions:"
    echo "  1. Review error logs: ssh ${USER}@${HOST} 'tail -100 ${REMOTE_PATH}/wp-content/debug.log'"
    echo "  2. Check health: curl -s ${SITE_URL}/wp-json/reforestamos/v1/health"
    echo "  3. Rollback if needed: ./deploy/rollback.sh --env ${ENV}"
    exit 1
elif [ "$UPTIME_FAIL" -gt 0 ] || [ "$SLOW_RESPONSES" -gt 2 ]; then
    log_warn "Some issues detected. Review the monitoring log."
    exit 0
else
    log_info "Monitoring completed — no issues detected."
    exit 0
fi
