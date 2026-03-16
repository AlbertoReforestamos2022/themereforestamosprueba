#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Launch Notification Script
# =============================================================================
# Sends launch notifications to stakeholders and generates communication
# artifacts for the deployment.
#
# Usage: ./deploy/notify-launch.sh [--env <staging|production>] [--dry-run]
#
# Actions:
#   1. Generates a launch summary email (HTML + plain text)
#   2. Sends notifications via configured channels (email, Slack webhook)
#   3. Creates a post-launch support checklist
#
# Requirements: Task 55.5 — Comunicar lanzamiento
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

log_info()  { echo -e "${GREEN}[NOTIFY]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

# Parse arguments
ENV="production"
DRY_RUN=false

while [[ $# -gt 0 ]]; do
    case $1 in
        --env)     ENV="$2"; shift 2 ;;
        --dry-run) DRY_RUN=true; shift ;;
        -h|--help)
            echo "Usage: ./deploy/notify-launch.sh [--env <staging|production>] [--dry-run]"
            echo ""
            echo "Options:"
            echo "  --env <env>   Environment (default: production)"
            echo "  --dry-run     Generate notifications without sending"
            echo "  -h, --help    Show this help"
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

SITE_URL="${PRODUCTION_URL:-https://reforestamos.org}"
SLACK_WEBHOOK="${SLACK_WEBHOOK_URL:-}"
NOTIFY_EMAILS="${NOTIFY_EMAILS:-}"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
LAUNCH_DATE=$(date '+%B %d, %Y')
OUTPUT_DIR="${PROJECT_ROOT}/deploy/logs"

mkdir -p "$OUTPUT_DIR"

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Reforestamos México — Launch Notification                   ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo "  Environment: ${ENV}"
echo "  Site URL:    ${SITE_URL}"
echo "  Dry Run:     ${DRY_RUN}"
echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 1. Generate plain text launch email
# ─────────────────────────────────────────────────────────────────────────────
log_info "Generating launch notification email..."

EMAIL_FILE="${OUTPUT_DIR}/launch-email-${TIMESTAMP}.txt"

cat > "$EMAIL_FILE" << EMAILEOF
Subject: [Reforestamos México] New Website Launch — Block Theme v2.0.0

Hello Team,

We are pleased to announce that the modernized Reforestamos México website
has been successfully deployed to production.

═══════════════════════════════════════════════════════════════
LAUNCH SUMMARY
═══════════════════════════════════════════════════════════════

  Date:        ${LAUNCH_DATE}
  URL:         ${SITE_URL}
  Version:     2.0.0 (Block Theme)
  Environment: ${ENV}

═══════════════════════════════════════════════════════════════
WHAT'S CHANGED
═══════════════════════════════════════════════════════════════

  • The website now uses a modern WordPress Block Theme
  • Content editing uses the Gutenberg Block Editor
  • 16+ custom blocks available for page building
  • 4 modular plugins for different site features
  • Improved performance, security, and accessibility
  • Full Spanish/English bilingual support

═══════════════════════════════════════════════════════════════
FOR CONTENT EDITORS
═══════════════════════════════════════════════════════════════

  • Use the Block Editor to create and edit content
  • Custom blocks are in the "Reforestamos" category
  • Block patterns provide pre-built page sections
  • See the Content User Guide for detailed instructions

═══════════════════════════════════════════════════════════════
SUPPORT
═══════════════════════════════════════════════════════════════

  If you encounter any issues:
  1. Check the known issues in the Release Notes
  2. Contact the development team
  3. For urgent issues, the site can be rolled back

  Release Notes: docs/RELEASE-NOTES.md
  Content Guide: reforestamos-block-theme/docs/USER-GUIDE-CONTENT.md
  Plugin Guide:  reforestamos-block-theme/docs/USER-GUIDE-PLUGINS.md

═══════════════════════════════════════════════════════════════

Thank you for your patience during the migration process.

— The Reforestamos México Development Team
EMAILEOF

log_info "Email saved: ${EMAIL_FILE}"

# ─────────────────────────────────────────────────────────────────────────────
# 2. Generate Slack notification
# ─────────────────────────────────────────────────────────────────────────────
log_info "Generating Slack notification..."

SLACK_PAYLOAD=$(cat << SLACKEOF
{
  "blocks": [
    {
      "type": "header",
      "text": {
        "type": "plain_text",
        "text": "🌳 Reforestamos México — Website Launched!",
        "emoji": true
      }
    },
    {
      "type": "section",
      "text": {
        "type": "mrkdwn",
        "text": "The modernized website has been deployed to *${ENV}*."
      }
    },
    {
      "type": "section",
      "fields": [
        { "type": "mrkdwn", "text": "*URL:*\n<${SITE_URL}|${SITE_URL}>" },
        { "type": "mrkdwn", "text": "*Version:*\n2.0.0 (Block Theme)" },
        { "type": "mrkdwn", "text": "*Date:*\n${LAUNCH_DATE}" },
        { "type": "mrkdwn", "text": "*Status:*\n✅ Live" }
      ]
    },
    {
      "type": "section",
      "text": {
        "type": "mrkdwn",
        "text": "*Key Changes:*\n• Modern Block Theme with Gutenberg editor\n• 16+ custom blocks\n• 4 modular plugins\n• Improved performance & security\n• Full ES/EN bilingual support"
      }
    },
    {
      "type": "divider"
    },
    {
      "type": "context",
      "elements": [
        {
          "type": "mrkdwn",
          "text": "📋 <${SITE_URL}/wp-json/reforestamos/v1/health|Health Check> | Report issues to the dev team"
        }
      ]
    }
  ]
}
SLACKEOF
)

SLACK_FILE="${OUTPUT_DIR}/launch-slack-${TIMESTAMP}.json"
echo "$SLACK_PAYLOAD" > "$SLACK_FILE"
log_info "Slack payload saved: ${SLACK_FILE}"

# ─────────────────────────────────────────────────────────────────────────────
# 3. Send notifications (unless dry-run)
# ─────────────────────────────────────────────────────────────────────────────
if [ "$DRY_RUN" = true ]; then
    log_warn "Dry-run mode: Notifications generated but not sent"
else
    # Send Slack notification
    if [ -n "$SLACK_WEBHOOK" ]; then
        log_info "Sending Slack notification..."
        SLACK_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" \
            -X POST -H "Content-Type: application/json" \
            -d "$SLACK_PAYLOAD" \
            "$SLACK_WEBHOOK" 2>/dev/null || echo "000")

        if [ "$SLACK_RESPONSE" = "200" ]; then
            log_info "✓ Slack notification sent"
        else
            log_warn "Slack notification failed (HTTP ${SLACK_RESPONSE})"
        fi
    else
        log_warn "SLACK_WEBHOOK_URL not configured — skipping Slack notification"
    fi

    # Send email notification
    if [ -n "$NOTIFY_EMAILS" ]; then
        log_info "Sending email notifications to: ${NOTIFY_EMAILS}"
        IFS=',' read -ra EMAILS <<< "$NOTIFY_EMAILS"
        for email in "${EMAILS[@]}"; do
            email=$(echo "$email" | xargs)  # trim whitespace
            mail -s "[Reforestamos] Website Launch — Block Theme v2.0.0" \
                "$email" < "$EMAIL_FILE" 2>/dev/null && \
                log_info "  ✓ Email sent to ${email}" || \
                log_warn "  ✗ Failed to send email to ${email}"
        done
    else
        log_warn "NOTIFY_EMAILS not configured — skipping email notifications"
        log_info "To send manually, use the generated email at: ${EMAIL_FILE}"
    fi
fi

# ─────────────────────────────────────────────────────────────────────────────
# 4. Generate post-launch support checklist
# ─────────────────────────────────────────────────────────────────────────────
log_info "Generating post-launch support checklist..."

SUPPORT_FILE="${OUTPUT_DIR}/post-launch-support-${TIMESTAMP}.md"

cat > "$SUPPORT_FILE" << SUPPORTEOF
# Post-Launch Support Checklist

**Launch Date:** ${LAUNCH_DATE}
**Environment:** ${ENV}
**URL:** ${SITE_URL}

---

## Day 1 (Launch Day)

- [ ] Monitor error logs every 30 minutes for first 4 hours
- [ ] Verify all contact form submissions are received
- [ ] Verify newsletter subscription works
- [ ] Check Google Analytics real-time data
- [ ] Respond to any user-reported issues
- [ ] Verify micrositios maps load correctly
- [ ] Test site on mobile devices

## Day 2-3

- [ ] Review full error logs from Day 1
- [ ] Check PageSpeed Insights score
- [ ] Verify SEO: sitemap accessible, meta tags correct
- [ ] Monitor server resource usage (CPU, memory, disk)
- [ ] Address any non-critical issues found

## Week 1

- [ ] Collect feedback from content editors
- [ ] Review analytics data (traffic, bounce rate, page views)
- [ ] Check for any 404 errors from old URLs
- [ ] Verify all scheduled tasks (cron jobs) are running
- [ ] Review Sentry error reports (if configured)
- [ ] Update documentation based on feedback

## Week 2-4

- [ ] Conduct user training sessions if needed
- [ ] Address accumulated feedback
- [ ] Plan first maintenance update
- [ ] Remove old theme files from server (after confirming stability)
- [ ] Archive migration backups

---

## Emergency Contacts

| Role | Name | Contact |
|------|------|---------|
| Lead Developer | [Name] | [email/phone] |
| Server Admin | [Name] | [email/phone] |
| Project Manager | [Name] | [email/phone] |

## Quick Commands

\`\`\`bash
# Check site health
curl -s ${SITE_URL}/wp-json/reforestamos/v1/health | python3 -m json.tool

# Check error logs
ssh [user]@[host] 'tail -50 [path]/wp-content/debug.log'

# Run monitoring
bash deploy/post-deploy-monitor.sh --env ${ENV} --duration 60

# Emergency rollback
bash deploy/rollback.sh --env ${ENV}
\`\`\`
SUPPORTEOF

log_info "Support checklist saved: ${SUPPORT_FILE}"

# ─────────────────────────────────────────────────────────────────────────────
# Summary
# ─────────────────────────────────────────────────────────────────────────────
echo ""
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Notification Summary                                        ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo "  Generated files:"
echo "    - Launch email:       ${EMAIL_FILE}"
echo "    - Slack payload:      ${SLACK_FILE}"
echo "    - Support checklist:  ${SUPPORT_FILE}"
echo "    - Release notes:      docs/RELEASE-NOTES.md"
echo ""

if [ "$DRY_RUN" = true ]; then
    log_info "Dry-run complete. Review generated files and re-run without --dry-run to send."
else
    log_info "Launch notifications sent!"
fi

echo ""
echo "Don't forget to:"
echo "  1. Share the Release Notes with the team"
echo "  2. Update CHANGELOG.md"
echo "  3. Follow the post-launch support checklist"
