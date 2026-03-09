<?php
/**
 * Company Analytics System
 *
 * Tracks clicks on company logos and links, stores analytics data,
 * and provides reporting functionality.
 *
 * @package Reforestamos_Empresas
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
        add_action('wp_ajax_track_company_click', [__CLASS__, 'track_click']);
        add_action('wp_ajax_nopriv_track_company_click', [__CLASS__, 'track_click']);
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);
    }
    
    /**
     * Create analytics database table
     */
    public static function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'reforestamos_company_clicks';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            company_id bigint(20) NOT NULL,
            click_type varchar(50) DEFAULT 'logo',
            user_ip varchar(45),
            user_agent text,
            referrer text,
            session_id varchar(64),
            is_unique tinyint(1) DEFAULT 1,
            clicked_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY company_id (company_id),
            KEY clicked_at (clicked_at),
            KEY session_id (session_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Track company click via AJAX
     */
    public static function track_click() {
        check_ajax_referer('reforestamos_comp_nonce', 'nonce');
        
        $company_id = intval($_POST['company_id'] ?? 0);
        $click_type = sanitize_text_field($_POST['click_type'] ?? 'logo');
        
        if (!$company_id) {
            wp_send_json_error(['message' => __('ID de empresa inválido', 'reforestamos-empresas')]);
        }
        
        // Verify company exists
        $company = get_post($company_id);
        if (!$company || $company->post_type !== 'empresas') {
            wp_send_json_error(['message' => __('Empresa no encontrada', 'reforestamos-empresas')]);
        }
        
        // Get or create session ID
        $session_id = self::get_session_id();
        
        // Check if this is a unique click
        $is_unique = self::is_unique_click($company_id, $session_id);
        
        // Track the click
        $result = self::record_click($company_id, $click_type, $session_id, $is_unique);
        
        if ($result) {
            wp_send_json_success([
                'message' => __('Clic registrado', 'reforestamos-empresas'),
                'is_unique' => $is_unique
            ]);
        } else {
            wp_send_json_error(['message' => __('Error al registrar clic', 'reforestamos-empresas')]);
        }
    }
    
    /**
     * Record click in database
     */
    private static function record_click($company_id, $click_type, $session_id, $is_unique) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'reforestamos_company_clicks';
        
        return $wpdb->insert(
            $table_name,
            [
                'company_id' => $company_id,
                'click_type' => $click_type,
                'user_ip' => self::get_user_ip(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'referrer' => $_SERVER['HTTP_REFERER'] ?? '',
                'session_id' => $session_id,
                'is_unique' => $is_unique ? 1 : 0,
            ],
            ['%d', '%s', '%s', '%s', '%s', '%s', '%d']
        );
    }
    
    /**
     * Get or create session ID for user
     */
    private static function get_session_id() {
        // Check if session cookie exists
        if (isset($_COOKIE['reforestamos_session'])) {
            return sanitize_text_field($_COOKIE['reforestamos_session']);
        }
        
        // Generate new session ID
        $session_id = wp_generate_password(32, false);
        
        // Set cookie for 30 days
        setcookie('reforestamos_session', $session_id, time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN);
        
        return $session_id;
    }
    
    /**
     * Check if this is a unique click for this session
     */
    private static function is_unique_click($company_id, $session_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'reforestamos_company_clicks';
        
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE company_id = %d AND session_id = %s",
            $company_id,
            $session_id
        ));
        
        return $existing == 0;
    }
    
    /**
     * Get user IP address (anonymized for GDPR)
     */
    private static function get_user_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        }
        
        $ip = sanitize_text_field($ip);
        
        // Anonymize IP for GDPR compliance
        return self::anonymize_ip($ip);
    }
    
    /**
     * Anonymize IP address by removing last octet
     */
    private static function anonymize_ip($ip) {
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
     * Add analytics admin menu
     */
    public static function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=empresas',
            __('Analytics', 'reforestamos-empresas'),
            __('Analytics', 'reforestamos-empresas'),
            'manage_options',
            'empresas-analytics',
            [__CLASS__, 'render_dashboard']
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public static function enqueue_admin_assets($hook) {
        if ($hook !== 'empresas_page_empresas-analytics') {
            return;
        }
        
        // Enqueue Chart.js
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
            [],
            '4.4.0',
            true
        );
        
        // Enqueue admin analytics script
        wp_enqueue_script(
            'reforestamos-analytics-admin',
            REFORESTAMOS_COMP_URL . 'admin/js/analytics.js',
            ['jquery', 'chartjs'],
            REFORESTAMOS_COMP_VERSION,
            true
        );
        
        // Enqueue admin styles
        wp_enqueue_style(
            'reforestamos-analytics-admin',
            REFORESTAMOS_COMP_URL . 'admin/css/analytics.css',
            [],
            REFORESTAMOS_COMP_VERSION
        );
    }
    
    /**
     * Render analytics dashboard
     */
    public static function render_dashboard() {
        // Handle CSV export
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            self::export_csv();
            exit;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'reforestamos_company_clicks';
        
        // Get date range from query params
        $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : date('Y-m-d');
        
        // Get total clicks
        $total_clicks = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE clicked_at BETWEEN %s AND %s",
            $start_date . ' 00:00:00',
            $end_date . ' 23:59:59'
        ));
        
        // Get unique clicks
        $unique_clicks = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE is_unique = 1 AND clicked_at BETWEEN %s AND %s",
            $start_date . ' 00:00:00',
            $end_date . ' 23:59:59'
        ));
        
        // Get clicks by company
        $clicks_by_company = $wpdb->get_results($wpdb->prepare(
            "SELECT c.company_id, p.post_title, 
                    COUNT(*) as total_clicks,
                    SUM(c.is_unique) as unique_clicks
            FROM $table_name c
            LEFT JOIN {$wpdb->posts} p ON c.company_id = p.ID
            WHERE c.clicked_at BETWEEN %s AND %s
            GROUP BY c.company_id
            ORDER BY total_clicks DESC
            LIMIT 10",
            $start_date . ' 00:00:00',
            $end_date . ' 23:59:59'
        ));
        
        // Get clicks by month (last 12 months)
        $clicks_by_month = $wpdb->get_results(
            "SELECT DATE_FORMAT(clicked_at, '%Y-%m') as month, 
                    COUNT(*) as total_clicks,
                    SUM(is_unique) as unique_clicks
            FROM $table_name
            WHERE clicked_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY month
            ORDER BY month ASC"
        );
        
        // Include dashboard view
        include REFORESTAMOS_COMP_DIR . 'admin/views/analytics-dashboard.php';
    }
    
    /**
     * Export analytics data to CSV
     */
    private static function export_csv() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'reforestamos_company_clicks';
        
        // Get date range
        $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : date('Y-m-d');
        
        // Get data
        $data = $wpdb->get_results($wpdb->prepare(
            "SELECT c.*, p.post_title as company_name
            FROM $table_name c
            LEFT JOIN {$wpdb->posts} p ON c.company_id = p.ID
            WHERE c.clicked_at BETWEEN %s AND %s
            ORDER BY c.clicked_at DESC",
            $start_date . ' 00:00:00',
            $end_date . ' 23:59:59'
        ), ARRAY_A);
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=empresas-analytics-' . date('Y-m-d') . '.csv');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add headers
        fputcsv($output, [
            'ID',
            'Empresa',
            'Tipo de Clic',
            'IP Usuario',
            'User Agent',
            'Referrer',
            'Session ID',
            'Único',
            'Fecha y Hora'
        ]);
        
        // Add data rows
        foreach ($data as $row) {
            fputcsv($output, [
                $row['id'],
                $row['company_name'],
                $row['click_type'],
                $row['user_ip'],
                $row['user_agent'],
                $row['referrer'],
                $row['session_id'],
                $row['is_unique'] ? 'Sí' : 'No',
                $row['clicked_at']
            ]);
        }
        
        fclose($output);
    }
    
    /**
     * Get analytics stats for a specific company
     */
    public static function get_company_stats($company_id, $start_date = null, $end_date = null) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'reforestamos_company_clicks';
        
        if (!$start_date) {
            $start_date = date('Y-m-01');
        }
        if (!$end_date) {
            $end_date = date('Y-m-d');
        }
        
        $stats = $wpdb->get_row($wpdb->prepare(
            "SELECT 
                COUNT(*) as total_clicks,
                SUM(is_unique) as unique_clicks,
                COUNT(DISTINCT DATE(clicked_at)) as days_with_clicks
            FROM $table_name
            WHERE company_id = %d AND clicked_at BETWEEN %s AND %s",
            $company_id,
            $start_date . ' 00:00:00',
            $end_date . ' 23:59:59'
        ));
        
        return $stats;
    }
}
