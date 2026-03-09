<?php
/**
 * Security Functions
 * 
 * Comprehensive security implementation including:
 * - Input sanitization and validation
 * - Output escaping
 * - Nonce verification
 * - AJAX security
 * - Credential encryption
 * - Rate limiting
 * - SQL injection prevention
 * - Security headers
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Security Manager Class
 */
class Reforestamos_Security {
    
    /**
     * Rate limit transient prefix
     */
    const RATE_LIMIT_PREFIX = 'reforestamos_rate_limit_';
    
    /**
     * Encryption key option name
     */
    const ENCRYPTION_KEY_OPTION = 'reforestamos_encryption_key';
    
    /**
     * Initialize security features
     */
    public static function init() {
        // Add security headers
        add_action('send_headers', [__CLASS__, 'add_security_headers']);
        
        // Sanitize AJAX requests
        add_action('wp_ajax_nopriv_*', [__CLASS__, 'verify_ajax_request'], 1);
        add_action('wp_ajax_*', [__CLASS__, 'verify_ajax_request'], 1);
        
        // Remove WordPress version from head
        remove_action('wp_head', 'wp_generator');
        
        // Disable XML-RPC if not needed
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Ensure encryption key exists
        self::ensure_encryption_key();
    }
    
    /**
     * Add security headers
     */
    public static function add_security_headers() {
        if (!is_admin()) {
            // X-Content-Type-Options
            header('X-Content-Type-Options: nosniff');
            
            // X-Frame-Options
            header('X-Frame-Options: SAMEORIGIN');
            
            // X-XSS-Protection
            header('X-XSS-Protection: 1; mode=block');
            
            // Referrer-Policy
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Content-Security-Policy (basic)
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' data: https://cdn.jsdelivr.net;";
            header("Content-Security-Policy: " . $csp);
        }
    }
    
    /**
     * Verify AJAX request security
     */
    public static function verify_ajax_request() {
        // Only check for our custom AJAX actions
        $action = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : '';
        
        if (strpos($action, 'reforestamos_') === 0) {
            // Verify nonce
            if (!self::verify_nonce('reforestamos_ajax_nonce', 'reforestamos_ajax_action')) {
                wp_send_json_error(['message' => __('Security verification failed', 'reforestamos')]);
                wp_die();
            }
        }
    }
    
    /**
     * Sanitize text input
     *
     * @param string $input Raw input
     * @return string Sanitized input
     */
    public static function sanitize_text($input) {
        return sanitize_text_field($input);
    }
    
    /**
     * Sanitize textarea input
     *
     * @param string $input Raw input
     * @return string Sanitized input
     */
    public static function sanitize_textarea($input) {
        return sanitize_textarea_field($input);
    }
    
    /**
     * Sanitize email
     *
     * @param string $email Raw email
     * @return string Sanitized email
     */
    public static function sanitize_email($email) {
        return sanitize_email($email);
    }
    
    /**
     * Sanitize URL
     *
     * @param string $url Raw URL
     * @return string Sanitized URL
     */
    public static function sanitize_url($url) {
        return esc_url_raw($url);
    }
    
    /**
     * Sanitize HTML content (allows safe HTML tags)
     *
     * @param string $content Raw HTML content
     * @return string Sanitized content
     */
    public static function sanitize_html($content) {
        return wp_kses_post($content);
    }
    
    /**
     * Sanitize array recursively
     *
     * @param array $array Raw array
     * @return array Sanitized array
     */
    public static function sanitize_array($array) {
        if (!is_array($array)) {
            return [];
        }
        
        return array_map(function($item) {
            if (is_array($item)) {
                return self::sanitize_array($item);
            }
            return sanitize_text_field($item);
        }, $array);
    }
    
    /**
     * Escape HTML output
     *
     * @param string $text Text to escape
     * @return string Escaped text
     */
    public static function escape_html($text) {
        return esc_html($text);
    }
    
    /**
     * Escape attribute output
     *
     * @param string $text Text to escape
     * @return string Escaped text
     */
    public static function escape_attr($text) {
        return esc_attr($text);
    }
    
    /**
     * Escape URL output
     *
     * @param string $url URL to escape
     * @return string Escaped URL
     */
    public static function escape_url($url) {
        return esc_url($url);
    }
    
    /**
     * Escape JavaScript output
     *
     * @param string $text Text to escape
     * @return string Escaped text
     */
    public static function escape_js($text) {
        return esc_js($text);
    }
    
    /**
     * Create nonce
     *
     * @param string $action Action name
     * @return string Nonce value
     */
    public static function create_nonce($action) {
        return wp_create_nonce($action);
    }
    
    /**
     * Verify nonce
     *
     * @param string $nonce_field Nonce field name
     * @param string $action Action name
     * @return bool True if valid
     */
    public static function verify_nonce($nonce_field, $action) {
        $nonce = isset($_REQUEST[$nonce_field]) ? $_REQUEST[$nonce_field] : '';
        return wp_verify_nonce($nonce, $action);
    }
    
    /**
     * Create nonce field for forms
     *
     * @param string $action Action name
     * @param string $name Field name
     * @param bool $referer Include referer field
     * @param bool $echo Echo or return
     * @return string Nonce field HTML
     */
    public static function nonce_field($action, $name = '_wpnonce', $referer = true, $echo = true) {
        return wp_nonce_field($action, $name, $referer, $echo);
    }
    
    /**
     * Validate email format
     *
     * @param string $email Email to validate
     * @return bool True if valid
     */
    public static function validate_email($email) {
        return is_email($email) !== false;
    }
    
    /**
     * Validate URL format
     *
     * @param string $url URL to validate
     * @return bool True if valid
     */
    public static function validate_url($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Validate required field
     *
     * @param mixed $value Value to validate
     * @return bool True if not empty
     */
    public static function validate_required($value) {
        if (is_string($value)) {
            return trim($value) !== '';
        }
        return !empty($value);
    }
    
    /**
     * Validate minimum length
     *
     * @param string $value Value to validate
     * @param int $min_length Minimum length
     * @return bool True if valid
     */
    public static function validate_min_length($value, $min_length) {
        return strlen($value) >= $min_length;
    }
    
    /**
     * Validate maximum length
     *
     * @param string $value Value to validate
     * @param int $max_length Maximum length
     * @return bool True if valid
     */
    public static function validate_max_length($value, $max_length) {
        return strlen($value) <= $max_length;
    }
    
    /**
     * Check rate limit
     *
     * @param string $identifier Unique identifier (IP, user ID, etc.)
     * @param string $action Action being rate limited
     * @param int $max_attempts Maximum attempts allowed
     * @param int $time_window Time window in seconds
     * @return bool True if within limit, false if exceeded
     */
    public static function check_rate_limit($identifier, $action, $max_attempts = 5, $time_window = 300) {
        $transient_key = self::RATE_LIMIT_PREFIX . $action . '_' . md5($identifier);
        $attempts = get_transient($transient_key);
        
        if ($attempts === false) {
            // First attempt
            set_transient($transient_key, 1, $time_window);
            return true;
        }
        
        if ($attempts >= $max_attempts) {
            return false;
        }
        
        // Increment attempts
        set_transient($transient_key, $attempts + 1, $time_window);
        return true;
    }
    
    /**
     * Get rate limit status
     *
     * @param string $identifier Unique identifier
     * @param string $action Action being checked
     * @return array Status information
     */
    public static function get_rate_limit_status($identifier, $action) {
        $transient_key = self::RATE_LIMIT_PREFIX . $action . '_' . md5($identifier);
        $attempts = get_transient($transient_key);
        
        return [
            'attempts' => $attempts !== false ? (int)$attempts : 0,
            'blocked' => $attempts !== false && $attempts >= 5,
        ];
    }
    
    /**
     * Reset rate limit
     *
     * @param string $identifier Unique identifier
     * @param string $action Action to reset
     */
    public static function reset_rate_limit($identifier, $action) {
        $transient_key = self::RATE_LIMIT_PREFIX . $action . '_' . md5($identifier);
        delete_transient($transient_key);
    }
    
    /**
     * Ensure encryption key exists
     */
    private static function ensure_encryption_key() {
        if (!get_option(self::ENCRYPTION_KEY_OPTION)) {
            $key = wp_generate_password(64, true, true);
            update_option(self::ENCRYPTION_KEY_OPTION, $key, false);
        }
    }
    
    /**
     * Get encryption key
     *
     * @return string Encryption key
     */
    private static function get_encryption_key() {
        $key = get_option(self::ENCRYPTION_KEY_OPTION);
        if (!$key) {
            self::ensure_encryption_key();
            $key = get_option(self::ENCRYPTION_KEY_OPTION);
        }
        return $key;
    }
    
    /**
     * Encrypt data
     *
     * @param string $data Data to encrypt
     * @return string Encrypted data (base64 encoded)
     */
    public static function encrypt($data) {
        if (empty($data)) {
            return '';
        }
        
        $key = self::get_encryption_key();
        $method = 'AES-256-CBC';
        
        // Generate IV
        $iv_length = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($iv_length);
        
        // Encrypt
        $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
        
        // Combine IV and encrypted data
        $result = base64_encode($iv . $encrypted);
        
        return $result;
    }
    
    /**
     * Decrypt data
     *
     * @param string $encrypted_data Encrypted data (base64 encoded)
     * @return string Decrypted data
     */
    public static function decrypt($encrypted_data) {
        if (empty($encrypted_data)) {
            return '';
        }
        
        $key = self::get_encryption_key();
        $method = 'AES-256-CBC';
        
        // Decode
        $decoded = base64_decode($encrypted_data);
        
        // Extract IV
        $iv_length = openssl_cipher_iv_length($method);
        $iv = substr($decoded, 0, $iv_length);
        $encrypted = substr($decoded, $iv_length);
        
        // Decrypt
        $decrypted = openssl_decrypt($encrypted, $method, $key, 0, $iv);
        
        return $decrypted;
    }
    
    /**
     * Check user capability
     *
     * @param string $capability Capability to check
     * @param int $user_id User ID (optional, defaults to current user)
     * @return bool True if user has capability
     */
    public static function user_can($capability, $user_id = null) {
        if ($user_id === null) {
            return current_user_can($capability);
        }
        return user_can($user_id, $capability);
    }
    
    /**
     * Verify user is logged in
     *
     * @return bool True if logged in
     */
    public static function is_user_logged_in() {
        return is_user_logged_in();
    }
    
    /**
     * Get sanitized POST data
     *
     * @param string $key POST key
     * @param mixed $default Default value
     * @return mixed Sanitized value
     */
    public static function get_post($key, $default = '') {
        if (!isset($_POST[$key])) {
            return $default;
        }
        
        $value = $_POST[$key];
        
        if (is_array($value)) {
            return self::sanitize_array($value);
        }
        
        return sanitize_text_field($value);
    }
    
    /**
     * Get sanitized GET data
     *
     * @param string $key GET key
     * @param mixed $default Default value
     * @return mixed Sanitized value
     */
    public static function get_query($key, $default = '') {
        if (!isset($_GET[$key])) {
            return $default;
        }
        
        $value = $_GET[$key];
        
        if (is_array($value)) {
            return self::sanitize_array($value);
        }
        
        return sanitize_text_field($value);
    }
    
    /**
     * Validate file upload
     *
     * @param array $file File from $_FILES
     * @param array $allowed_types Allowed MIME types
     * @param int $max_size Maximum file size in bytes
     * @return array ['valid' => bool, 'error' => string]
     */
    public static function validate_file_upload($file, $allowed_types = [], $max_size = 5242880) {
        // Check if file was uploaded
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['valid' => false, 'error' => __('Invalid file upload', 'reforestamos')];
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => __('File upload error', 'reforestamos')];
        }
        
        // Check file size
        if ($file['size'] > $max_size) {
            return ['valid' => false, 'error' => __('File size exceeds maximum allowed', 'reforestamos')];
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!empty($allowed_types) && !in_array($mime_type, $allowed_types)) {
            return ['valid' => false, 'error' => __('File type not allowed', 'reforestamos')];
        }
        
        return ['valid' => true, 'error' => ''];
    }
    
    /**
     * Sanitize filename
     *
     * @param string $filename Filename to sanitize
     * @return string Sanitized filename
     */
    public static function sanitize_filename($filename) {
        return sanitize_file_name($filename);
    }
    
    /**
     * Prepare SQL query safely
     *
     * @param string $query SQL query with placeholders
     * @param mixed ...$args Values to replace placeholders
     * @return string Prepared query
     */
    public static function prepare_query($query, ...$args) {
        global $wpdb;
        return $wpdb->prepare($query, ...$args);
    }
    
    /**
     * Log security event
     *
     * @param string $event Event description
     * @param array $data Additional data
     */
    public static function log_security_event($event, $data = []) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf(
                '[Reforestamos Security] %s - %s',
                $event,
                json_encode($data)
            ));
        }
    }
}

// Initialize security
Reforestamos_Security::init();
