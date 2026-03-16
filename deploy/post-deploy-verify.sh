#!/usr/bin/env bash
# =============================================================================
# Reforestamos México - Post-Deployment Verification Script
# =============================================================================
# Runs functional verification checks after deployment to production.
# Usage: ./deploy/post-deploy-verify.sh [--env <staging|production>]
#
# Checks:
#   - Homepage and key pages load (HTTP 200)
#   - Contact form endpoint responds
#   - Micrositios shortcodes render
#   - Newsletter system is operational
#   - Analytics tracking is configured
#   - REST API endpoints respond
#   - Health check passes
#
# Requirements: 9.1, 6.1, 7.1, 8.1, 34.1
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

log_info()  { echo -e "${GREEN}[VERIFY]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }
log_step()  { echo -e "${BLUE}[CHECK]${NC} $1"; }

pass() { echo -e "  ${GREEN}✓${NC} $1"; PASSED=$((PASSED + 1)); }
fail() { echo -e "  ${RED}✗${NC} $1"; FAILED=$((FAILED + 1)); }
warn() { echo -e "  ${YELLOW}⚠${NC} $1"; WARNINGS=$((WARNINGS + 1)); }

# Parse arguments
ENV="production"
while [[ $# -gt 0 ]]; do
    case $1 in
        --env) ENV="$2"; shift 2 ;;
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

PASSED=0
FAILED=0
WARNINGS=0
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
REPORT_FILE="${PROJECT_ROOT}/deploy/logs/verify-${TIMESTAMP}.log"

mkdir -p "$(dirname "$REPORT_FILE")"

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Reforestamos México — Post-Deployment Verification          ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo "  Environment: ${ENV}"
echo "  Site URL:    ${SITE_URL}"
echo "  Timestamp:   ${TIMESTAMP}"
echo ""

# Helper: check HTTP status of a URL
check_url() {
    local url="$1"
    local description="$2"
    local expected="${3:-200}"

    local status
    status=$(curl -s -o /dev/null -w "%{http_code}" -L --max-time 15 "$url" 2>/dev/null || echo "000")

    if [ "$status" = "$expected" ]; then
        pass "${description} (HTTP ${status})"
    elif [ "$status" = "000" ]; then
        fail "${description} — connection failed"
    else
        fail "${description} — expected HTTP ${expected}, got ${status}"
    fi
}

# Helper: check URL contains text
check_url_contains() {
    local url="$1"
    local description="$2"
    local search_text="$3"

    local body
    body=$(curl -s -L --max-time 15 "$url" 2>/dev/null || echo "")

    if echo "$body" | grep -qi "$search_text"; then
        pass "${description}"
    else
        fail "${description} — text '${search_text}' not found"
    fi
}

# ─────────────────────────────────────────────────────────────────────────────
# 1. Core Pages
# ─────────────────────────────────────────────────────────────────────────────
log_step "1. Core Pages"

check_url "${SITE_URL}/" "Homepage loads"
check_url "${SITE_URL}/contacto/" "Contact page loads"
check_url "${SITE_URL}/sobre-nosotros/" "About page loads"
check_url "${SITE_URL}/empresas/" "Companies archive loads"
check_url "${SITE_URL}/eventos/" "Events archive loads"

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 2. REST API & Health
# ─────────────────────────────────────────────────────────────────────────────
log_step "2. REST API & Health Endpoints"

check_url "${SITE_URL}/wp-json/reforestamos/v1/health" "Health check endpoint"
check_url "${SITE_URL}/wp-json/reforestamos/v1/uptime" "Uptime endpoint"
check_url "${SITE_URL}/wp-json/wp/v2/posts" "WP REST API — posts"
check_url "${SITE_URL}/wp-json/wp/v2/pages" "WP REST API — pages"
check_url "${SITE_URL}/wp-json/wp/v2/empresas" "REST API — empresas CPT"
check_url "${SITE_URL}/wp-json/wp/v2/eventos" "REST API — eventos CPT"

# Verify health response content
HEALTH_BODY=$(curl -s -L --max-time 10 "${SITE_URL}/wp-json/reforestamos/v1/health" 2>/dev/null || echo "{}")
if echo "$HEALTH_BODY" | grep -q '"status":"ok"'; then
    pass "Health check status is 'ok'"
else
    fail "Health check status is not 'ok': ${HEALTH_BODY}"
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 3. Contact Forms (Req 9.1)
# ─────────────────────────────────────────────────────────────────────────────
log_step "3. Contact Forms"

# Check that the contact page contains form elements
CONTACT_BODY=$(curl -s -L --max-time 15 "${SITE_URL}/contacto/" 2>/dev/null || echo "")

if echo "$CONTACT_BODY" | grep -qi '<form'; then
    pass "Contact page contains a form element"
else
    warn "Contact page may not contain a form (check manually)"
fi

if echo "$CONTACT_BODY" | grep -qi 'contact-form\|reforestamos-contact\|wp-block-reforestamos-contacto'; then
    pass "Contact form block/shortcode detected"
else
    warn "Contact form block/shortcode not detected in page source"
fi

# Check AJAX endpoint for form submission
check_url "${SITE_URL}/wp-admin/admin-ajax.php" "AJAX endpoint accessible" "400"

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 4. Micrositios (Req 6.1, 7.1)
# ─────────────────────────────────────────────────────────────────────────────
log_step "4. Micrositios"

# Check pages that should contain micrositio shortcodes
ARBOLES_URL="${SITE_URL}/arboles-ciudades/"
ARBOLES_STATUS=$(curl -s -o /dev/null -w "%{http_code}" -L --max-time 15 "$ARBOLES_URL" 2>/dev/null || echo "000")

if [ "$ARBOLES_STATUS" = "200" ]; then
    pass "Árboles y Ciudades page loads (HTTP ${ARBOLES_STATUS})"
    check_url_contains "$ARBOLES_URL" "Árboles page contains map container" "map\|leaflet\|arboles-ciudades"
elif [ "$ARBOLES_STATUS" = "404" ]; then
    warn "Árboles y Ciudades page not found (may use different URL)"
else
    fail "Árboles y Ciudades page returned HTTP ${ARBOLES_STATUS}"
fi

RED_OJA_URL="${SITE_URL}/red-oja/"
RED_OJA_STATUS=$(curl -s -o /dev/null -w "%{http_code}" -L --max-time 15 "$RED_OJA_URL" 2>/dev/null || echo "000")

if [ "$RED_OJA_STATUS" = "200" ]; then
    pass "Red OJA page loads (HTTP ${RED_OJA_STATUS})"
    check_url_contains "$RED_OJA_URL" "Red OJA page contains map container" "map\|leaflet\|red-oja"
elif [ "$RED_OJA_STATUS" = "404" ]; then
    warn "Red OJA page not found (may use different URL)"
else
    fail "Red OJA page returned HTTP ${RED_OJA_STATUS}"
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 5. Newsletter System (Req 8.1)
# ─────────────────────────────────────────────────────────────────────────────
log_step "5. Newsletter System"

# Check homepage or footer for newsletter subscription form
HOMEPAGE_BODY=$(curl -s -L --max-time 15 "${SITE_URL}/" 2>/dev/null || echo "")

if echo "$HOMEPAGE_BODY" | grep -qi 'newsletter\|suscri\|subscribe\|boletin'; then
    pass "Newsletter subscription form detected on site"
else
    warn "Newsletter form not detected on homepage (may be on another page)"
fi

# Verify newsletter admin functionality via WP-CLI (if SSH available)
if [ -n "$HOST" ] && [ -n "$USER" ] && [ -n "$REMOTE_PATH" ]; then
    NEWSLETTER_TABLE=$(ssh "${USER}@${HOST}" \
        "cd ${REMOTE_PATH} && wp db query \
         \"SHOW TABLES LIKE '%reforestamos_subscribers%'\" \
         --skip-column-names 2>/dev/null || echo ''")

    if [ -n "$NEWSLETTER_TABLE" ]; then
        pass "Newsletter subscribers table exists"
    else
        warn "Newsletter subscribers table not found"
    fi
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 6. Analytics Verification (Req 34.1)
# ─────────────────────────────────────────────────────────────────────────────
log_step "6. Analytics & Tracking"

if echo "$HOMEPAGE_BODY" | grep -qi 'gtag\|google.*analytics\|GA4\|G-[A-Z0-9]'; then
    pass "Google Analytics tracking code detected"
else
    warn "Google Analytics tracking code not detected in page source"
fi

if echo "$HOMEPAGE_BODY" | grep -qi 'cookie.*consent\|cookie.*banner\|gdpr'; then
    pass "Cookie consent mechanism detected"
else
    warn "Cookie consent mechanism not detected"
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 7. Theme & Block Rendering
# ─────────────────────────────────────────────────────────────────────────────
log_step "7. Theme & Block Rendering"

# Check that theme CSS is loaded
if echo "$HOMEPAGE_BODY" | grep -qi 'reforestamos-block-theme\|reforestamos-style'; then
    pass "Theme stylesheet loaded"
else
    fail "Theme stylesheet not detected in page source"
fi

# Check Bootstrap is loaded
if echo "$HOMEPAGE_BODY" | grep -qi 'bootstrap'; then
    pass "Bootstrap CSS/JS loaded"
else
    warn "Bootstrap not detected in page source"
fi

# Check for custom blocks
if echo "$HOMEPAGE_BODY" | grep -qi 'wp-block-reforestamos'; then
    pass "Custom Reforestamos blocks rendered on homepage"
else
    warn "Custom blocks not detected on homepage"
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 8. Performance Quick Check
# ─────────────────────────────────────────────────────────────────────────────
log_step "8. Performance Quick Check"

# Measure response time
RESPONSE_TIME=$(curl -s -o /dev/null -w "%{time_total}" -L --max-time 30 "${SITE_URL}/" 2>/dev/null || echo "0")

if (( $(echo "$RESPONSE_TIME < 3.0" | bc -l 2>/dev/null || echo 0) )); then
    pass "Homepage response time: ${RESPONSE_TIME}s (< 3s)"
elif (( $(echo "$RESPONSE_TIME < 5.0" | bc -l 2>/dev/null || echo 0) )); then
    warn "Homepage response time: ${RESPONSE_TIME}s (< 5s, could be faster)"
else
    fail "Homepage response time: ${RESPONSE_TIME}s (> 5s, too slow)"
fi

# Check for gzip compression
ENCODING=$(curl -s -H "Accept-Encoding: gzip" -o /dev/null -w "%{content_type}" -D - -L --max-time 10 "${SITE_URL}/" 2>/dev/null | grep -i "content-encoding" || echo "")

if echo "$ENCODING" | grep -qi "gzip\|br"; then
    pass "Response compression enabled"
else
    warn "Response compression not detected"
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 9. Security Checks
# ─────────────────────────────────────────────────────────────────────────────
log_step "9. Security Quick Checks"

# Check that wp-config.php is not accessible
WP_CONFIG_STATUS=$(curl -s -o /dev/null -w "%{http_code}" -L --max-time 10 "${SITE_URL}/wp-config.php" 2>/dev/null || echo "000")
if [ "$WP_CONFIG_STATUS" = "403" ] || [ "$WP_CONFIG_STATUS" = "404" ] || [ "$WP_CONFIG_STATUS" = "301" ]; then
    pass "wp-config.php not publicly accessible (HTTP ${WP_CONFIG_STATUS})"
else
    fail "wp-config.php may be accessible (HTTP ${WP_CONFIG_STATUS})"
fi

# Check X-Frame-Options or CSP header
HEADERS=$(curl -s -I -L --max-time 10 "${SITE_URL}/" 2>/dev/null || echo "")
if echo "$HEADERS" | grep -qi "x-frame-options\|content-security-policy"; then
    pass "Security headers present (X-Frame-Options or CSP)"
else
    warn "Security headers not detected"
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# 10. Mobile / Responsive Check
# ─────────────────────────────────────────────────────────────────────────────
log_step "10. Mobile Compatibility"

# Check viewport meta tag
if echo "$HOMEPAGE_BODY" | grep -qi 'viewport'; then
    pass "Viewport meta tag present"
else
    fail "Viewport meta tag missing"
fi

# Check mobile-friendly response with mobile user agent
MOBILE_STATUS=$(curl -s -o /dev/null -w "%{http_code}" -L --max-time 15 \
    -H "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15" \
    "${SITE_URL}/" 2>/dev/null || echo "000")

if [ "$MOBILE_STATUS" = "200" ]; then
    pass "Site responds to mobile user agent (HTTP ${MOBILE_STATUS})"
else
    fail "Site returned HTTP ${MOBILE_STATUS} for mobile user agent"
fi

echo ""

# ─────────────────────────────────────────────────────────────────────────────
# Summary
# ─────────────────────────────────────────────────────────────────────────────
TOTAL=$((PASSED + FAILED + WARNINGS))

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║   Verification Summary                                        ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo -e "  ${GREEN}Passed:${NC}   ${PASSED}/${TOTAL}"
echo -e "  ${RED}Failed:${NC}   ${FAILED}/${TOTAL}"
echo -e "  ${YELLOW}Warnings:${NC} ${WARNINGS}/${TOTAL}"
echo ""

# Save report
{
    echo "Post-Deployment Verification Report"
    echo "===================================="
    echo "Environment: ${ENV}"
    echo "URL: ${SITE_URL}"
    echo "Timestamp: $(date -Iseconds)"
    echo ""
    echo "Results: ${PASSED} passed, ${FAILED} failed, ${WARNINGS} warnings"
} > "$REPORT_FILE"

echo "  Report saved: ${REPORT_FILE}"
echo ""

if [ "$FAILED" -gt 0 ]; then
    log_error "${FAILED} check(s) failed. Review issues above."
    echo ""
    echo "If critical issues found:"
    echo "  ./deploy/rollback.sh --env ${ENV}"
    exit 1
elif [ "$WARNINGS" -gt 0 ]; then
    log_warn "${WARNINGS} warning(s). Review and address as needed."
    echo ""
    echo "Next step: ./deploy/post-deploy-monitor.sh --env ${ENV}"
    exit 0
else
    log_info "All checks passed!"
    echo ""
    echo "Next step: ./deploy/post-deploy-monitor.sh --env ${ENV}"
    exit 0
fi
