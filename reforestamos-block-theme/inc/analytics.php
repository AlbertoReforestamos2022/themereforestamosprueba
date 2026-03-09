<?php
/**
 * Google Analytics 4 Integration
 *
 * Handles GA4 tracking code, custom event tracking, and GDPR compliance.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Reforestamos_Analytics {
    
    /**
     * Initialize analytics system
     */
    public static function init() {
        add_action('wp_head', [__CLASS__, 'output_ga4_tracking_code'], 1);
        add_action('wp_footer', [__CLASS__, 'output_custom_events_script'], 99);
        add_action('admin_menu', [__CLASS__, 'add_settings_page']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_analytics_script']);
    }
    
    /**
     * Output GA4 tracking code in head
     */
    public static function output_ga4_tracking_code() {
        $ga4_id = get_option('reforestamos_ga4_measurement_id', '');
        
        if (empty($ga4_id)) {
            return;
        }
        
        // Check if user has consented to analytics
        if (!self::has_analytics_consent()) {
            return;
        }
        
        // Check if IP anonymization is enabled
        $anonymize_ip = get_option('reforestamos_ga4_anonymize_ip', '1');
        
        ?>
        <!-- Google Analytics 4 -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga4_id); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            
            gtag('config', '<?php echo esc_js($ga4_id); ?>', {
                <?php if ($anonymize_ip === '1') : ?>
                'anonymize_ip': true,
                <?php endif; ?>
                'cookie_flags': 'SameSite=None;Secure'
            });
        </script>
        <?php
    }
    
    /**
     * Output custom events tracking script
     */
    public static function output_custom_events_script() {
        if (!self::has_analytics_consent()) {
            return;
        }
        
        ?>
        <script>
        (function() {
            // Track form submissions
            document.addEventListener('submit', function(e) {
                if (e.target.matches('form')) {
                    var formName = e.target.getAttribute('name') || e.target.getAttribute('id') || 'unnamed_form';
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'form_submit', {
                            'form_name': formName,
                            'form_destination': e.target.action || window.location.href
                        });
                    }
                }
            });
            
            // Track file downloads
            document.addEventListener('click', function(e) {
                var link = e.target.closest('a');
                if (link && link.href) {
                    var fileExtensions = /\.(pdf|doc|docx|xls|xlsx|zip|rar|jpg|jpeg|png|gif)$/i;
                    if (fileExtensions.test(link.href)) {
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'file_download', {
                                'file_name': link.href.split('/').pop(),
                                'file_url': link.href,
                                'link_text': link.textContent.trim()
                            });
                        }
                    }
                }
            });
            
            // Track outbound links
            document.addEventListener('click', function(e) {
                var link = e.target.closest('a');
                if (link && link.href) {
                    var currentDomain = window.location.hostname;
                    var linkDomain = new URL(link.href).hostname;
                    
                    if (linkDomain !== currentDomain && !link.href.startsWith('mailto:') && !link.href.startsWith('tel:')) {
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'outbound_click', {
                                'link_url': link.href,
                                'link_domain': linkDomain,
                                'link_text': link.textContent.trim()
                            });
                        }
                    }
                }
            });
            
            // Track video plays (for embedded videos)
            document.addEventListener('play', function(e) {
                if (e.target.matches('video')) {
                    var videoSrc = e.target.currentSrc || e.target.src || 'unknown';
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'video_play', {
                            'video_url': videoSrc,
                            'video_title': e.target.title || 'untitled'
                        });
                    }
                }
            }, true);
            
            // Track scroll depth
            var scrollDepthTracked = {};
            window.addEventListener('scroll', function() {
                var scrollPercent = Math.round((window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100);
                
                [25, 50, 75, 90].forEach(function(threshold) {
                    if (scrollPercent >= threshold && !scrollDepthTracked[threshold]) {
                        scrollDepthTracked[threshold] = true;
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'scroll_depth', {
                                'percent_scrolled': threshold
                            });
                        }
                    }
                });
            });
        })();
        </script>
        <?php
    }
    
    /**
     * Enqueue analytics frontend script
     */
    public static function enqueue_analytics_script() {
        wp_enqueue_script(
            'reforestamos-analytics',
            get_template_directory_uri() . '/build/analytics.js',
            [],
            REFORESTAMOS_VERSION,
            true
        );
        
        wp_localize_script('reforestamos-analytics', 'reforestamosAnalytics', [
            'hasConsent' => self::has_analytics_consent(),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('reforestamos_analytics_nonce')
        ]);
    }
    
    /**
     * Check if user has consented to analytics
     */
    private static function has_analytics_consent() {
        // Check cookie consent
        if (isset($_COOKIE['reforestamos_analytics_consent'])) {
            return $_COOKIE['reforestamos_analytics_consent'] === 'accepted';
        }
        
        // Default to false (require explicit consent for GDPR)
        return false;
    }
    
    /**
     * Track custom event via PHP
     */
    public static function track_event($event_name, $event_params = []) {
        // This would typically send to GA4 Measurement Protocol API
        // For now, we'll log it for server-side tracking
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf(
                'GA4 Event: %s | Params: %s',
                $event_name,
                json_encode($event_params)
            ));
        }
    }
    
    /**
     * Add settings page
     */
    public static function add_settings_page() {
        add_options_page(
            __('Analytics Settings', 'reforestamos'),
            __('Analytics', 'reforestamos'),
            'manage_options',
            'reforestamos-analytics',
            [__CLASS__, 'render_settings_page']
        );
    }
    
    /**
     * Register settings
     */
    public static function register_settings() {
        register_setting('reforestamos_analytics', 'reforestamos_ga4_measurement_id', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => ''
        ]);
        
        register_setting('reforestamos_analytics', 'reforestamos_ga4_anonymize_ip', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ]);
        
        register_setting('reforestamos_analytics', 'reforestamos_cookie_consent_enabled', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ]);
        
        add_settings_section(
            'reforestamos_analytics_general',
            __('General Settings', 'reforestamos'),
            null,
            'reforestamos-analytics'
        );
        
        add_settings_field(
            'reforestamos_ga4_measurement_id',
            __('GA4 Measurement ID', 'reforestamos'),
            [__CLASS__, 'render_measurement_id_field'],
            'reforestamos-analytics',
            'reforestamos_analytics_general'
        );
        
        add_settings_field(
            'reforestamos_ga4_anonymize_ip',
            __('Anonymize IP Addresses', 'reforestamos'),
            [__CLASS__, 'render_anonymize_ip_field'],
            'reforestamos-analytics',
            'reforestamos_analytics_general'
        );
        
        add_settings_field(
            'reforestamos_cookie_consent_enabled',
            __('Enable Cookie Consent', 'reforestamos'),
            [__CLASS__, 'render_cookie_consent_field'],
            'reforestamos-analytics',
            'reforestamos_analytics_general'
        );
    }
    
    /**
     * Render measurement ID field
     */
    public static function render_measurement_id_field() {
        $value = get_option('reforestamos_ga4_measurement_id', '');
        ?>
        <input type="text" 
               name="reforestamos_ga4_measurement_id" 
               value="<?php echo esc_attr($value); ?>" 
               class="regular-text"
               placeholder="G-XXXXXXXXXX">
        <p class="description">
            <?php _e('Enter your Google Analytics 4 Measurement ID (e.g., G-XXXXXXXXXX)', 'reforestamos'); ?>
        </p>
        <?php
    }
    
    /**
     * Render anonymize IP field
     */
    public static function render_anonymize_ip_field() {
        $value = get_option('reforestamos_ga4_anonymize_ip', '1');
        ?>
        <label>
            <input type="checkbox" 
                   name="reforestamos_ga4_anonymize_ip" 
                   value="1" 
                   <?php checked($value, '1'); ?>>
            <?php _e('Anonymize IP addresses for GDPR compliance', 'reforestamos'); ?>
        </label>
        <p class="description">
            <?php _e('Recommended for GDPR compliance. Removes the last octet of IP addresses.', 'reforestamos'); ?>
        </p>
        <?php
    }
    
    /**
     * Render cookie consent field
     */
    public static function render_cookie_consent_field() {
        $value = get_option('reforestamos_cookie_consent_enabled', '1');
        ?>
        <label>
            <input type="checkbox" 
                   name="reforestamos_cookie_consent_enabled" 
                   value="1" 
                   <?php checked($value, '1'); ?>>
            <?php _e('Require cookie consent before tracking', 'reforestamos'); ?>
        </label>
        <p class="description">
            <?php _e('Users must accept cookies before analytics tracking begins (GDPR requirement).', 'reforestamos'); ?>
        </p>
        <?php
    }
    
    /**
     * Render settings page
     */
    public static function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Save settings
        if (isset($_GET['settings-updated'])) {
            add_settings_error(
                'reforestamos_analytics_messages',
                'reforestamos_analytics_message',
                __('Settings saved successfully.', 'reforestamos'),
                'updated'
            );
        }
        
        settings_errors('reforestamos_analytics_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('reforestamos_analytics');
                do_settings_sections('reforestamos-analytics');
                submit_button(__('Save Settings', 'reforestamos'));
                ?>
            </form>
            
            <hr>
            
            <h2><?php _e('Testing Analytics', 'reforestamos'); ?></h2>
            <p><?php _e('To test if Google Analytics is working:', 'reforestamos'); ?></p>
            <ol>
                <li><?php _e('Enter your GA4 Measurement ID above and save settings', 'reforestamos'); ?></li>
                <li><?php _e('Accept cookies on the frontend (if cookie consent is enabled)', 'reforestamos'); ?></li>
                <li><?php _e('Visit your website and perform some actions (click links, download files, etc.)', 'reforestamos'); ?></li>
                <li><?php _e('Check your Google Analytics 4 dashboard for real-time events', 'reforestamos'); ?></li>
            </ol>
            
            <h3><?php _e('Custom Events Tracked', 'reforestamos'); ?></h3>
            <ul>
                <li><strong>form_submit</strong> - <?php _e('When any form is submitted', 'reforestamos'); ?></li>
                <li><strong>file_download</strong> - <?php _e('When a file (PDF, DOC, etc.) is downloaded', 'reforestamos'); ?></li>
                <li><strong>outbound_click</strong> - <?php _e('When a link to external site is clicked', 'reforestamos'); ?></li>
                <li><strong>video_play</strong> - <?php _e('When a video starts playing', 'reforestamos'); ?></li>
                <li><strong>scroll_depth</strong> - <?php _e('When user scrolls to 25%, 50%, 75%, 90% of page', 'reforestamos'); ?></li>
            </ul>
        </div>
        <?php
    }
}

