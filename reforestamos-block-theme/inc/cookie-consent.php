<?php
/**
 * Cookie Consent Banner (GDPR Compliance)
 *
 * Displays cookie consent banner and manages user preferences.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Reforestamos_Cookie_Consent {
    
    /**
     * Initialize cookie consent system
     */
    public static function init() {
        add_action('wp_footer', [__CLASS__, 'render_consent_banner']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('wp_ajax_save_cookie_consent', [__CLASS__, 'save_consent']);
        add_action('wp_ajax_nopriv_save_cookie_consent', [__CLASS__, 'save_consent']);
    }
    
    /**
     * Enqueue cookie consent assets
     */
    public static function enqueue_assets() {
        wp_enqueue_style(
            'reforestamos-cookie-consent',
            get_template_directory_uri() . '/build/cookie-consent.css',
            [],
            REFORESTAMOS_VERSION
        );
        
        wp_enqueue_script(
            'reforestamos-cookie-consent',
            get_template_directory_uri() . '/build/cookie-consent.js',
            [],
            REFORESTAMOS_VERSION,
            true
        );
        
        wp_localize_script('reforestamos-cookie-consent', 'reforestamosConsent', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cookie_consent_nonce')
        ]);
    }
    
    /**
     * Render cookie consent banner
     */
    public static function render_consent_banner() {
        // Check if consent is enabled
        $consent_enabled = get_option('reforestamos_cookie_consent_enabled', '1');
        if ($consent_enabled !== '1') {
            return;
        }
        
        // Don't show if user already made a choice
        if (isset($_COOKIE['reforestamos_analytics_consent'])) {
            return;
        }
        
        ?>
        <div id="reforestamos-cookie-consent" class="cookie-consent-banner" role="dialog" aria-live="polite" aria-label="<?php esc_attr_e('Cookie Consent', 'reforestamos'); ?>">
            <div class="cookie-consent-content">
                <div class="cookie-consent-text">
                    <h3><?php _e('We use cookies', 'reforestamos'); ?></h3>
                    <p>
                        <?php _e('We use cookies to improve your experience on our website and to analyze site traffic. By clicking "Accept", you consent to the use of cookies for analytics purposes.', 'reforestamos'); ?>
                        <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" target="_blank">
                            <?php _e('Learn more', 'reforestamos'); ?>
                        </a>
                    </p>
                </div>
                <div class="cookie-consent-actions">
                    <button type="button" class="cookie-consent-btn cookie-consent-accept" data-consent="accepted">
                        <?php _e('Accept', 'reforestamos'); ?>
                    </button>
                    <button type="button" class="cookie-consent-btn cookie-consent-decline" data-consent="declined">
                        <?php _e('Decline', 'reforestamos'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Save user consent preference via AJAX
     */
    public static function save_consent() {
        check_ajax_referer('cookie_consent_nonce', 'nonce');
        
        $consent = sanitize_text_field($_POST['consent'] ?? '');
        
        if (!in_array($consent, ['accepted', 'declined'])) {
            wp_send_json_error(['message' => __('Invalid consent value', 'reforestamos')]);
        }
        
        // Set cookie for 1 year
        setcookie(
            'reforestamos_analytics_consent',
            $consent,
            time() + (365 * DAY_IN_SECONDS),
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true // HttpOnly
        );
        
        wp_send_json_success([
            'message' => __('Preference saved', 'reforestamos'),
            'consent' => $consent
        ]);
    }
}

