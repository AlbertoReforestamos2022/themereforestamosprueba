<?php
/**
 * GDPR Compliance Helper
 *
 * Provides utilities for GDPR compliance including IP anonymization,
 * data export, data deletion, and privacy preference management.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Reforestamos_GDPR_Compliance {
    
    /**
     * Initialize GDPR compliance
     */
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_privacy_page']);
        add_action('wp_ajax_export_user_data', [__CLASS__, 'export_user_data']);
        add_action('wp_ajax_delete_user_data', [__CLASS__, 'delete_user_data']);
        add_filter('wp_privacy_personal_data_exporters', [__CLASS__, 'register_data_exporters']);
        add_filter('wp_privacy_personal_data_erasers', [__CLASS__, 'register_data_erasers']);
        
        // Add privacy policy content suggestions
        add_action('admin_init', [__CLASS__, 'add_privacy_policy_content']);
    }
    
    /**
     * Add privacy settings page
     */
    public static function add_privacy_page() {
        add_options_page(
            __('GDPR Compliance', 'reforestamos'),
            __('GDPR Compliance', 'reforestamos'),
            'manage_options',
            'reforestamos-gdpr',
            [__CLASS__, 'render_privacy_page']
        );
    }
    
    /**
     * Render privacy settings page
     */
    public static function render_privacy_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('GDPR Compliance Settings', 'reforestamos'); ?></h1>
            
            <div class="card">
                <h2><?php _e('Data Protection Overview', 'reforestamos'); ?></h2>
                <p><?php _e('This site implements the following GDPR compliance measures:', 'reforestamos'); ?></p>
                <ul>
                    <li><strong><?php _e('IP Anonymization:', 'reforestamos'); ?></strong> <?php _e('All IP addresses are anonymized before storage (last octet removed for IPv4, last 80 bits for IPv6).', 'reforestamos'); ?></li>
                    <li><strong><?php _e('Cookie Consent:', 'reforestamos'); ?></strong> <?php _e('Users must explicitly consent to analytics cookies before tracking begins.', 'reforestamos'); ?></li>
                    <li><strong><?php _e('Data Minimization:', 'reforestamos'); ?></strong> <?php _e('Only essential data is collected and stored.', 'reforestamos'); ?></li>
                    <li><strong><?php _e('Right to Access:', 'reforestamos'); ?></strong> <?php _e('Users can request their personal data through WordPress privacy tools.', 'reforestamos'); ?></li>
                    <li><strong><?php _e('Right to Erasure:', 'reforestamos'); ?></strong> <?php _e('Users can request deletion of their personal data.', 'reforestamos'); ?></li>
                </ul>
            </div>
            
            <div class="card">
                <h2><?php _e('Data Retention', 'reforestamos'); ?></h2>
                <p><?php _e('Configure how long different types of data are retained:', 'reforestamos'); ?></p>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('reforestamos_gdpr');
                    do_settings_sections('reforestamos-gdpr');
                    submit_button();
                    ?>
                </form>
            </div>
            
            <div class="card">
                <h2><?php _e('Data Audit', 'reforestamos'); ?></h2>
                <p><?php _e('Current data storage:', 'reforestamos'); ?></p>
                <?php self::render_data_audit(); ?>
            </div>
            
            <div class="card">
                <h2><?php _e('Privacy Policy', 'reforestamos'); ?></h2>
                <p>
                    <?php _e('Make sure your privacy policy is up to date and includes information about:', 'reforestamos'); ?>
                </p>
                <ul>
                    <li><?php _e('What data is collected', 'reforestamos'); ?></li>
                    <li><?php _e('How data is used', 'reforestamos'); ?></li>
                    <li><?php _e('How long data is retained', 'reforestamos'); ?></li>
                    <li><?php _e('User rights (access, rectification, erasure)', 'reforestamos'); ?></li>
                    <li><?php _e('Cookie usage', 'reforestamos'); ?></li>
                </ul>
                <?php if (get_option('wp_page_for_privacy_policy')) : ?>
                    <p>
                        <a href="<?php echo get_edit_post_link(get_option('wp_page_for_privacy_policy')); ?>" class="button button-primary">
                            <?php _e('Edit Privacy Policy', 'reforestamos'); ?>
                        </a>
                    </p>
                <?php else : ?>
                    <p class="notice notice-warning">
                        <?php _e('No privacy policy page is set. Please create one in Settings > Privacy.', 'reforestamos'); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render data audit
     */
    private static function render_data_audit() {
        global $wpdb;
        
        $audit = [];
        
        // Company clicks
        $clicks_table = $wpdb->prefix . 'reforestamos_company_clicks';
        if ($wpdb->get_var("SHOW TABLES LIKE '$clicks_table'") == $clicks_table) {
            $count = $wpdb->get_var("SELECT COUNT(*) FROM $clicks_table");
            $audit[] = [
                'type' => __('Company Click Analytics', 'reforestamos'),
                'count' => $count,
                'table' => $clicks_table
            ];
        }
        
        // Newsletter subscribers
        $subscribers_table = $wpdb->prefix . 'reforestamos_subscribers';
        if ($wpdb->get_var("SHOW TABLES LIKE '$subscribers_table'") == $subscribers_table) {
            $count = $wpdb->get_var("SELECT COUNT(*) FROM $subscribers_table");
            $audit[] = [
                'type' => __('Newsletter Subscribers', 'reforestamos'),
                'count' => $count,
                'table' => $subscribers_table
            ];
        }
        
        // Newsletter logs
        $logs_table = $wpdb->prefix . 'reforestamos_newsletter_logs';
        if ($wpdb->get_var("SHOW TABLES LIKE '$logs_table'") == $logs_table) {
            $count = $wpdb->get_var("SELECT COUNT(*) FROM $logs_table");
            $audit[] = [
                'type' => __('Newsletter Send Logs', 'reforestamos'),
                'count' => $count,
                'table' => $logs_table
            ];
        }
        
        // Contact form submissions
        $submissions_table = $wpdb->prefix . 'reforestamos_submissions';
        if ($wpdb->get_var("SHOW TABLES LIKE '$submissions_table'") == $submissions_table) {
            $count = $wpdb->get_var("SELECT COUNT(*) FROM $submissions_table");
            $audit[] = [
                'type' => __('Contact Form Submissions', 'reforestamos'),
                'count' => $count,
                'table' => $submissions_table
            ];
        }
        
        if (empty($audit)) {
            echo '<p>' . __('No personal data tables found.', 'reforestamos') . '</p>';
            return;
        }
        
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Data Type', 'reforestamos'); ?></th>
                    <th><?php _e('Records', 'reforestamos'); ?></th>
                    <th><?php _e('Table', 'reforestamos'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($audit as $item) : ?>
                    <tr>
                        <td><?php echo esc_html($item['type']); ?></td>
                        <td><?php echo number_format($item['count']); ?></td>
                        <td><code><?php echo esc_html($item['table']); ?></code></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
    
    /**
     * Anonymize IP address
     */
    public static function anonymize_ip($ip) {
        // For IPv4, remove last octet
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = '0';
            return implode('.', $parts);
        }
        
        // For IPv6, remove last 80 bits (keep first 48 bits)
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ip);
            // Keep first 3 groups (48 bits), zero out the rest
            $parts = array_slice($parts, 0, 3);
            return implode(':', $parts) . '::';
        }
        
        return $ip;
    }
    
    /**
     * Register data exporters for WordPress privacy tools
     */
    public static function register_data_exporters($exporters) {
        $exporters['reforestamos-newsletter'] = [
            'exporter_friendly_name' => __('Newsletter Subscriptions', 'reforestamos'),
            'callback' => [__CLASS__, 'export_newsletter_data']
        ];
        
        $exporters['reforestamos-contact'] = [
            'exporter_friendly_name' => __('Contact Form Submissions', 'reforestamos'),
            'callback' => [__CLASS__, 'export_contact_data']
        ];
        
        return $exporters;
    }
    
    /**
     * Export newsletter data for a user
     */
    public static function export_newsletter_data($email_address, $page = 1) {
        global $wpdb;
        
        $data_to_export = [];
        $table_name = $wpdb->prefix . 'reforestamos_subscribers';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            return [
                'data' => $data_to_export,
                'done' => true
            ];
        }
        
        $subscriber = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE email = %s",
            $email_address
        ));
        
        if ($subscriber) {
            $data_to_export[] = [
                'group_id' => 'reforestamos-newsletter',
                'group_label' => __('Newsletter Subscription', 'reforestamos'),
                'item_id' => 'subscriber-' . $subscriber->id,
                'data' => [
                    [
                        'name' => __('Email', 'reforestamos'),
                        'value' => $subscriber->email
                    ],
                    [
                        'name' => __('Name', 'reforestamos'),
                        'value' => $subscriber->name
                    ],
                    [
                        'name' => __('Status', 'reforestamos'),
                        'value' => $subscriber->status
                    ],
                    [
                        'name' => __('Subscribed Date', 'reforestamos'),
                        'value' => $subscriber->subscribed_at
                    ]
                ]
            ];
        }
        
        return [
            'data' => $data_to_export,
            'done' => true
        ];
    }
    
    /**
     * Export contact form data for a user
     */
    public static function export_contact_data($email_address, $page = 1) {
        global $wpdb;
        
        $data_to_export = [];
        $table_name = $wpdb->prefix . 'reforestamos_submissions';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            return [
                'data' => $data_to_export,
                'done' => true
            ];
        }
        
        $submissions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE email = %s ORDER BY submitted_at DESC",
            $email_address
        ));
        
        foreach ($submissions as $submission) {
            $data_to_export[] = [
                'group_id' => 'reforestamos-contact',
                'group_label' => __('Contact Form Submissions', 'reforestamos'),
                'item_id' => 'submission-' . $submission->id,
                'data' => [
                    [
                        'name' => __('Name', 'reforestamos'),
                        'value' => $submission->name
                    ],
                    [
                        'name' => __('Email', 'reforestamos'),
                        'value' => $submission->email
                    ],
                    [
                        'name' => __('Subject', 'reforestamos'),
                        'value' => $submission->subject
                    ],
                    [
                        'name' => __('Message', 'reforestamos'),
                        'value' => $submission->message
                    ],
                    [
                        'name' => __('Submitted Date', 'reforestamos'),
                        'value' => $submission->submitted_at
                    ]
                ]
            ];
        }
        
        return [
            'data' => $data_to_export,
            'done' => true
        ];
    }
    
    /**
     * Register data erasers for WordPress privacy tools
     */
    public static function register_data_erasers($erasers) {
        $erasers['reforestamos-newsletter'] = [
            'eraser_friendly_name' => __('Newsletter Subscriptions', 'reforestamos'),
            'callback' => [__CLASS__, 'erase_newsletter_data']
        ];
        
        $erasers['reforestamos-contact'] = [
            'eraser_friendly_name' => __('Contact Form Submissions', 'reforestamos'),
            'callback' => [__CLASS__, 'erase_contact_data']
        ];
        
        return $erasers;
    }
    
    /**
     * Erase newsletter data for a user
     */
    public static function erase_newsletter_data($email_address, $page = 1) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'reforestamos_subscribers';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            return [
                'items_removed' => false,
                'items_retained' => false,
                'messages' => [],
                'done' => true
            ];
        }
        
        $deleted = $wpdb->delete($table_name, ['email' => $email_address]);
        
        return [
            'items_removed' => $deleted > 0,
            'items_retained' => false,
            'messages' => $deleted > 0 ? [__('Newsletter subscription removed.', 'reforestamos')] : [],
            'done' => true
        ];
    }
    
    /**
     * Erase contact form data for a user
     */
    public static function erase_contact_data($email_address, $page = 1) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'reforestamos_submissions';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            return [
                'items_removed' => false,
                'items_retained' => false,
                'messages' => [],
                'done' => true
            ];
        }
        
        $deleted = $wpdb->delete($table_name, ['email' => $email_address]);
        
        return [
            'items_removed' => $deleted > 0,
            'items_retained' => false,
            'messages' => $deleted > 0 ? [__('Contact form submissions removed.', 'reforestamos')] : [],
            'done' => true
        ];
    }
    
    /**
     * Add privacy policy content suggestions
     */
    public static function add_privacy_policy_content() {
        if (!function_exists('wp_add_privacy_policy_content')) {
            return;
        }
        
        $content = sprintf(
            '<h2>%s</h2>' .
            '<p>%s</p>' .
            '<h3>%s</h3>' .
            '<ul>' .
            '<li>%s</li>' .
            '<li>%s</li>' .
            '<li>%s</li>' .
            '<li>%s</li>' .
            '</ul>' .
            '<h3>%s</h3>' .
            '<p>%s</p>' .
            '<h3>%s</h3>' .
            '<p>%s</p>',
            __('Analytics and Tracking', 'reforestamos'),
            __('We use Google Analytics to understand how visitors interact with our website. All IP addresses are anonymized before being sent to Google Analytics.', 'reforestamos'),
            __('What data we collect:', 'reforestamos'),
            __('Anonymized IP addresses', 'reforestamos'),
            __('Pages visited and time spent on pages', 'reforestamos'),
            __('Browser type and device information', 'reforestamos'),
            __('Referring website', 'reforestamos'),
            __('Cookies', 'reforestamos'),
            __('We use cookies to remember your cookie consent preference and to maintain your session. You can control cookie settings through the cookie consent banner that appears on your first visit.', 'reforestamos'),
            __('Your Rights', 'reforestamos'),
            __('You have the right to access, rectify, or erase your personal data. You can also object to processing or request data portability. To exercise these rights, please contact us using the information provided on this page.', 'reforestamos')
        );
        
        wp_add_privacy_policy_content(
            'Reforestamos Block Theme',
            wp_kses_post(wpautop($content, false))
        );
    }
}

