<?php
/**
 * Error Handler for Reforestamos Block Theme
 *
 * Provides centralized error handling, logging, debug mode,
 * user-friendly messages, critical error notifications,
 * and dependency verification.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Reforestamos_Error_Handler
 *
 * Reusable error handling system for the theme and all plugins.
 * Implements custom PHP/JS error handlers, file-based logging with rotation,
 * user-friendly error templates, admin email notifications with throttling,
 * debug mode, and plugin dependency checks.
 */
class Reforestamos_Error_Handler {

    /**
     * Singleton instance
     *
     * @var Reforestamos_Error_Handler|null
     */
    private static $instance = null;

    /**
     * Log directory path
     *
     * @var string
     */
    private $log_dir;

    /**
     * Maximum log file size in bytes (5 MB)
     *
     * @var int
     */
    private $max_log_size = 5242880;

    /**
     * Maximum number of rotated log files to keep
     *
     * @var int
     */
    private $max_log_files = 5;

    /**
     * Error severity levels
     *
     * @var array
     */
    private $severity_levels = array(
        E_ERROR             => 'Fatal Error',
        E_WARNING           => 'Warning',
        E_NOTICE            => 'Notice',
        E_PARSE             => 'Parse Error',
        E_CORE_ERROR        => 'Core Error',
        E_CORE_WARNING      => 'Core Warning',
        E_COMPILE_ERROR     => 'Compile Error',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_USER_ERROR        => 'User Error',
        E_USER_WARNING      => 'User Warning',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Strict',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'User Deprecated',
    );

    /**
     * Critical error types that trigger admin notifications
     *
     * @var array
     */
    private $critical_errors = array(
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,
        E_COMPILE_ERROR,
        E_USER_ERROR,
        E_RECOVERABLE_ERROR,
    );

    /**
     * Notification throttle window in seconds (1 hour)
     *
     * @var int
     */
    private $notification_throttle = 3600;

    /**
     * Required plugins registry
     *
     * @var array
     */
    private $required_plugins = array();

    /**
     * Get singleton instance
     *
     * @return Reforestamos_Error_Handler
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize the error handler
     */
    public static function init() {
        $instance = self::get_instance();
        $instance->setup_log_directory();
        $instance->register_hooks();
    }

    /**
     * Private constructor
     */
    private function __construct() {
        $this->log_dir = WP_CONTENT_DIR . '/reforestamos-logs';
    }

    /**
     * Setup log directory with security protections
     */
    private function setup_log_directory() {
        if (!file_exists($this->log_dir)) {
            wp_mkdir_p($this->log_dir);
        }

        // Protect log directory with .htaccess
        $htaccess = $this->log_dir . '/.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Deny from all\n");
        }

        // Add index.php to prevent directory listing
        $index = $this->log_dir . '/index.php';
        if (!file_exists($index)) {
            file_put_contents($index, "<?php\n// Silence is golden.\n");
        }
    }

    /**
     * Register WordPress hooks
     */
    private function register_hooks() {
        // Set custom PHP error handler
        set_error_handler(array($this, 'handle_php_error'));

        // Register shutdown function for fatal errors
        register_shutdown_function(array($this, 'handle_shutdown'));

        // JavaScript error tracking endpoint
        add_action('wp_ajax_reforestamos_log_js_error', array($this, 'handle_js_error_ajax'));
        add_action('wp_ajax_nopriv_reforestamos_log_js_error', array($this, 'handle_js_error_ajax'));

        // Enqueue JS error tracker on frontend
        add_action('wp_enqueue_scripts', array($this, 'enqueue_js_error_tracker'));

        // Admin notices for dependency warnings
        add_action('admin_notices', array($this, 'display_dependency_warnings'));

        // Theme activation dependency check
        add_action('after_switch_theme', array($this, 'check_dependencies_on_activation'));
    }

    // =========================================================================
    // PHP Error Handling (Req 35.1)
    // =========================================================================

    /**
     * Custom PHP error handler
     *
     * @param int    $errno   Error level.
     * @param string $errstr  Error message.
     * @param string $errfile File where error occurred.
     * @param int    $errline Line number.
     * @return bool True to prevent default PHP error handler.
     */
    public function handle_php_error($errno, $errstr, $errfile, $errline) {
        // Respect error_reporting setting
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $severity = isset($this->severity_levels[$errno]) ? $this->severity_levels[$errno] : 'Unknown';

        $error_data = array(
            'type'     => $severity,
            'message'  => $errstr,
            'file'     => $errfile,
            'line'     => $errline,
            'errno'    => $errno,
            'time'     => current_time('mysql'),
            'url'      => isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : 'CLI',
            'user_id'  => get_current_user_id(),
        );

        // Log the error
        $this->log('error', $error_data);

        // Send notification for critical errors
        if (in_array($errno, $this->critical_errors, true)) {
            $this->notify_critical_error($error_data);
        }

        // In debug mode, let PHP handle it too for display
        if ($this->is_debug_mode()) {
            return false;
        }

        return true;
    }

    /**
     * Handle fatal errors on shutdown
     */
    public function handle_shutdown() {
        $error = error_get_last();

        if ($error && in_array($error['type'], $this->critical_errors, true)) {
            $severity = isset($this->severity_levels[$error['type']]) ? $this->severity_levels[$error['type']] : 'Fatal';

            $error_data = array(
                'type'    => $severity,
                'message' => $error['message'],
                'file'    => $error['file'],
                'line'    => $error['line'],
                'errno'   => $error['type'],
                'time'    => current_time('mysql'),
                'url'     => isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : 'CLI',
            );

            $this->log('critical', $error_data);
            $this->notify_critical_error($error_data);
        }
    }

    // =========================================================================
    // JavaScript Error Handling (Req 35.5)
    // =========================================================================

    /**
     * Enqueue JavaScript error tracking script
     */
    public function enqueue_js_error_tracker() {
        $script = "
        (function() {
            window.addEventListener('error', function(event) {
                var data = new FormData();
                data.append('action', 'reforestamos_log_js_error');
                data.append('nonce', '" . wp_create_nonce('reforestamos_js_error') . "');
                data.append('message', event.message || 'Unknown error');
                data.append('source', event.filename || 'unknown');
                data.append('lineno', event.lineno || 0);
                data.append('colno', event.colno || 0);
                data.append('stack', event.error && event.error.stack ? event.error.stack : '');
                data.append('url', window.location.href);
                data.append('user_agent', navigator.userAgent);

                if (navigator.sendBeacon) {
                    navigator.sendBeacon('" . admin_url('admin-ajax.php') . "', data);
                } else {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '" . admin_url('admin-ajax.php') . "', true);
                    xhr.send(data);
                }
            });

            window.addEventListener('unhandledrejection', function(event) {
                var data = new FormData();
                data.append('action', 'reforestamos_log_js_error');
                data.append('nonce', '" . wp_create_nonce('reforestamos_js_error') . "');
                data.append('message', 'Unhandled Promise Rejection: ' + (event.reason ? event.reason.message || String(event.reason) : 'Unknown'));
                data.append('source', 'promise');
                data.append('lineno', 0);
                data.append('colno', 0);
                data.append('stack', event.reason && event.reason.stack ? event.reason.stack : '');
                data.append('url', window.location.href);
                data.append('user_agent', navigator.userAgent);

                if (navigator.sendBeacon) {
                    navigator.sendBeacon('" . admin_url('admin-ajax.php') . "', data);
                } else {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '" . admin_url('admin-ajax.php') . "', true);
                    xhr.send(data);
                }
            });
        })();
        ";

        wp_add_inline_script('jquery-core', $script, 'after');
    }

    /**
     * Handle JavaScript error AJAX request
     */
    public function handle_js_error_ajax() {
        check_ajax_referer('reforestamos_js_error', 'nonce');

        $error_data = array(
            'type'       => 'JavaScript Error',
            'message'    => isset($_POST['message']) ? sanitize_text_field(wp_unslash($_POST['message'])) : '',
            'source'     => isset($_POST['source']) ? sanitize_text_field(wp_unslash($_POST['source'])) : '',
            'line'       => isset($_POST['lineno']) ? absint($_POST['lineno']) : 0,
            'column'     => isset($_POST['colno']) ? absint($_POST['colno']) : 0,
            'stack'      => isset($_POST['stack']) ? sanitize_textarea_field(wp_unslash($_POST['stack'])) : '',
            'url'        => isset($_POST['url']) ? esc_url_raw(wp_unslash($_POST['url'])) : '',
            'user_agent' => isset($_POST['user_agent']) ? sanitize_text_field(wp_unslash($_POST['user_agent'])) : '',
            'time'       => current_time('mysql'),
        );

        $this->log('js_error', $error_data);

        wp_send_json_success();
    }

    // =========================================================================
    // Logging System (Req 35.2)
    // =========================================================================

    /**
     * Log a message to file
     *
     * @param string $level   Log level (error, warning, info, critical, js_error, debug).
     * @param mixed  $data    Data to log (string or array).
     * @param string $context Optional context identifier (e.g., plugin name).
     */
    public function log($level, $data, $context = 'theme') {
        $log_file = $this->log_dir . '/reforestamos-' . gmdate('Y-m-d') . '.log';

        // Rotate logs if needed
        $this->maybe_rotate_logs($log_file);

        $timestamp = current_time('Y-m-d H:i:s');
        $level     = strtoupper($level);

        if (is_array($data)) {
            $message = wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            $message = (string) $data;
        }

        $log_entry = sprintf(
            "[%s] [%s] [%s] %s\n",
            $timestamp,
            $level,
            $context,
            $message
        );

        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
        file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

        // Also log to WordPress debug.log if debug mode is on
        if ($this->is_debug_mode() && function_exists('error_log')) {
            error_log('[Reforestamos] ' . $log_entry);
        }
    }

    /**
     * Rotate log files when they exceed max size
     *
     * @param string $log_file Path to current log file.
     */
    private function maybe_rotate_logs($log_file) {
        if (!file_exists($log_file)) {
            return;
        }

        if (filesize($log_file) < $this->max_log_size) {
            return;
        }

        // Rotate existing files
        for ($i = $this->max_log_files - 1; $i >= 1; $i--) {
            $old_file = $log_file . '.' . $i;
            $new_file = $log_file . '.' . ($i + 1);

            if (file_exists($old_file)) {
                if ($i + 1 >= $this->max_log_files) {
                    // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
                    unlink($old_file);
                } else {
                    rename($old_file, $new_file);
                }
            }
        }

        // Rotate current file
        rename($log_file, $log_file . '.1');
    }

    /**
     * Clean up old log files (older than 30 days)
     */
    public function cleanup_old_logs() {
        if (!is_dir($this->log_dir)) {
            return;
        }

        $files = glob($this->log_dir . '/reforestamos-*.log*');
        if (!$files) {
            return;
        }

        $thirty_days_ago = time() - (30 * DAY_IN_SECONDS);

        foreach ($files as $file) {
            if (filemtime($file) < $thirty_days_ago) {
                // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
                unlink($file);
            }
        }
    }

    /**
     * Get the log directory path
     *
     * @return string
     */
    public function get_log_dir() {
        return $this->log_dir;
    }

    // =========================================================================
    // User-Friendly Error Messages (Req 35.3, 35.8)
    // =========================================================================

    /**
     * Get a user-friendly error message for display on the frontend
     *
     * @param string $error_type Type of error (api_failure, not_found, permission, general).
     * @param array  $context    Optional context data for the message.
     * @return string HTML-safe error message.
     */
    public static function get_user_message($error_type, $context = array()) {
        $messages = array(
            'api_failure'  => __('We are experiencing a temporary issue connecting to an external service. Please try again in a few minutes.', 'reforestamos'),
            'not_found'    => __('The content you are looking for could not be found. It may have been moved or removed.', 'reforestamos'),
            'permission'   => __('You do not have permission to access this content. Please log in or contact the administrator.', 'reforestamos'),
            'form_error'   => __('There was a problem processing your submission. Please check the form and try again.', 'reforestamos'),
            'upload_error' => __('There was a problem uploading your file. Please ensure it meets the size and format requirements.', 'reforestamos'),
            'general'      => __('Something went wrong. Please try again later. If the problem persists, contact us.', 'reforestamos'),
        );

        /**
         * Filter user-friendly error messages
         *
         * @param array $messages Error messages keyed by type.
         */
        $messages = apply_filters('reforestamos_error_messages', $messages);

        $message = isset($messages[$error_type]) ? $messages[$error_type] : $messages['general'];

        return esc_html($message);
    }

    /**
     * Render a user-friendly error template
     *
     * @param string $error_type Type of error.
     * @param array  $context    Optional context data.
     * @return string HTML output.
     */
    public static function render_error_template($error_type, $context = array()) {
        $message = self::get_user_message($error_type, $context);
        $icon    = self::get_error_icon($error_type);

        $html  = '<div class="reforestamos-error-message" role="alert">';
        $html .= '<div class="reforestamos-error-icon">' . $icon . '</div>';
        $html .= '<p class="reforestamos-error-text">' . $message . '</p>';

        if (!empty($context['retry_url'])) {
            $html .= '<a href="' . esc_url($context['retry_url']) . '" class="reforestamos-error-retry btn btn-primary">';
            $html .= esc_html__('Try Again', 'reforestamos');
            $html .= '</a>';
        }

        if (!empty($context['home_link'])) {
            $html .= '<a href="' . esc_url(home_url('/')) . '" class="reforestamos-error-home btn btn-secondary">';
            $html .= esc_html__('Go to Homepage', 'reforestamos');
            $html .= '</a>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Get an SVG icon for the error type
     *
     * @param string $error_type Error type.
     * @return string SVG icon markup.
     */
    private static function get_error_icon($error_type) {
        $icons = array(
            'api_failure'  => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
            'not_found'    => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>',
            'permission'   => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
            'form_error'   => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            'upload_error' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>',
            'general'      => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        );

        return isset($icons[$error_type]) ? $icons[$error_type] : $icons['general'];
    }

    /**
     * Graceful fallback for failed API calls (Req 35.8)
     *
     * @param string   $api_name    Name of the API.
     * @param callable $fallback_fn Fallback function to call.
     * @param mixed    $default     Default value if fallback also fails.
     * @return mixed Result from API or fallback.
     */
    public function api_fallback($api_name, $fallback_fn = null, $default = null) {
        $this->log('warning', sprintf('API call to %s failed, using fallback.', $api_name));

        if (is_callable($fallback_fn)) {
            try {
                return call_user_func($fallback_fn);
            } catch (\Exception $e) {
                $this->log('error', array(
                    'message'  => 'Fallback also failed for ' . $api_name,
                    'error'    => $e->getMessage(),
                ));
            }
        }

        return $default;
    }

    // =========================================================================
    // Critical Error Notifications (Req 35.4)
    // =========================================================================

    /**
     * Send notification email for critical errors with throttling
     *
     * @param array $error_data Error details.
     */
    private function notify_critical_error($error_data) {
        // Check throttle to avoid flooding admin inbox
        $throttle_key = 'reforestamos_error_notify_' . md5($error_data['message']);
        $last_sent    = get_transient($throttle_key);

        if (false !== $last_sent) {
            return; // Already notified within throttle window
        }

        // Set throttle transient
        set_transient($throttle_key, time(), $this->notification_throttle);

        $admin_email = get_option('admin_email');
        if (empty($admin_email)) {
            return;
        }

        $site_name = get_bloginfo('name');
        $subject   = sprintf(
            /* translators: 1: site name, 2: error type */
            __('[%1$s] Critical Error: %2$s', 'reforestamos'),
            $site_name,
            isset($error_data['type']) ? $error_data['type'] : 'Unknown'
        );

        $body  = sprintf(__('A critical error occurred on %s', 'reforestamos'), $site_name) . "\n\n";
        $body .= sprintf(__('Type: %s', 'reforestamos'), isset($error_data['type']) ? $error_data['type'] : 'N/A') . "\n";
        $body .= sprintf(__('Message: %s', 'reforestamos'), isset($error_data['message']) ? $error_data['message'] : 'N/A') . "\n";
        $body .= sprintf(__('File: %s', 'reforestamos'), isset($error_data['file']) ? $error_data['file'] : 'N/A') . "\n";
        $body .= sprintf(__('Line: %s', 'reforestamos'), isset($error_data['line']) ? $error_data['line'] : 'N/A') . "\n";
        $body .= sprintf(__('URL: %s', 'reforestamos'), isset($error_data['url']) ? $error_data['url'] : 'N/A') . "\n";
        $body .= sprintf(__('Time: %s', 'reforestamos'), isset($error_data['time']) ? $error_data['time'] : current_time('mysql')) . "\n\n";
        $body .= __('Please check the error logs for more details.', 'reforestamos') . "\n";
        $body .= sprintf(__('Log directory: %s', 'reforestamos'), $this->log_dir) . "\n";

        $headers = array('Content-Type: text/plain; charset=UTF-8');

        wp_mail($admin_email, $subject, $body, $headers);

        $this->log('info', 'Critical error notification sent to ' . $admin_email);
    }

    // =========================================================================
    // Debug Mode (Req 35.6, 35.7)
    // =========================================================================

    /**
     * Check if debug mode is enabled
     *
     * @return bool
     */
    public function is_debug_mode() {
        if (defined('REFORESTAMOS_DEBUG') && REFORESTAMOS_DEBUG) {
            return true;
        }

        return defined('WP_DEBUG') && WP_DEBUG;
    }

    /**
     * Output debug information (only in debug mode)
     *
     * @param string $label Label for the debug output.
     * @param mixed  $data  Data to display.
     * @param bool   $echo  Whether to echo or return.
     * @return string|void HTML output if $echo is false.
     */
    public function debug($label, $data, $echo = false) {
        if (!$this->is_debug_mode()) {
            return '';
        }

        // Only show to administrators
        if (!current_user_can('manage_options')) {
            return '';
        }

        $output  = '<div class="reforestamos-debug" style="background:#1a1a2e;color:#e0e0e0;padding:12px;margin:10px 0;border-left:4px solid #e94560;font-family:monospace;font-size:13px;overflow-x:auto;">';
        $output .= '<strong style="color:#e94560;">[DEBUG] ' . esc_html($label) . '</strong><br>';
        $output .= '<pre style="margin:8px 0 0;white-space:pre-wrap;word-break:break-all;">';
        $output .= esc_html(is_string($data) ? $data : print_r($data, true));
        $output .= '</pre></div>';

        if ($echo) {
            echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped above
            return;
        }

        return $output;
    }

    /**
     * Log debug information to file
     *
     * @param string $label   Debug label.
     * @param mixed  $data    Data to log.
     * @param string $context Context identifier.
     */
    public function debug_log($label, $data, $context = 'theme') {
        if (!$this->is_debug_mode()) {
            return;
        }

        $this->log('debug', array(
            'label' => $label,
            'data'  => $data,
        ), $context);
    }

    // =========================================================================
    // Dependency Verification (Req 35.9)
    // =========================================================================

    /**
     * Register a required plugin dependency
     *
     * @param string $plugin_slug Plugin slug (e.g., 'reforestamos-core/reforestamos-core.php').
     * @param string $plugin_name Human-readable plugin name.
     * @param string $required_by Who requires this plugin (e.g., 'Reforestamos Block Theme').
     */
    public function register_dependency($plugin_slug, $plugin_name, $required_by = '') {
        $this->required_plugins[$plugin_slug] = array(
            'name'        => $plugin_name,
            'required_by' => $required_by ? $required_by : __('Reforestamos Block Theme', 'reforestamos'),
        );
    }

    /**
     * Check if a plugin is active
     *
     * @param string $plugin_slug Plugin slug.
     * @return bool
     */
    public function is_plugin_active($plugin_slug) {
        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active($plugin_slug);
    }

    /**
     * Get list of missing required plugins
     *
     * @return array Missing plugins with name and required_by.
     */
    public function get_missing_dependencies() {
        $missing = array();

        foreach ($this->required_plugins as $slug => $info) {
            if (!$this->is_plugin_active($slug)) {
                $missing[$slug] = $info;
            }
        }

        return $missing;
    }

    /**
     * Display admin notices for missing dependencies
     */
    public function display_dependency_warnings() {
        $missing = $this->get_missing_dependencies();

        if (empty($missing)) {
            return;
        }

        foreach ($missing as $slug => $info) {
            $message = sprintf(
                /* translators: 1: required plugin name, 2: component that requires it */
                __('<strong>%1$s</strong> is recommended for full functionality of <strong>%2$s</strong>. Some features may not work correctly without it.', 'reforestamos'),
                esc_html($info['name']),
                esc_html($info['required_by'])
            );

            echo '<div class="notice notice-warning is-dismissible"><p>' . wp_kses_post($message) . '</p></div>';
        }
    }

    /**
     * Check dependencies on theme activation
     */
    public function check_dependencies_on_activation() {
        $missing = $this->get_missing_dependencies();

        if (!empty($missing)) {
            $names = array();
            foreach ($missing as $info) {
                $names[] = $info['name'];
            }

            $this->log('warning', array(
                'message'         => 'Missing recommended plugins on theme activation',
                'missing_plugins' => $names,
            ));
        }
    }

    // =========================================================================
    // Convenience / Static Helpers
    // =========================================================================

    /**
     * Quick static log method for use across plugins
     *
     * @param string $level   Log level.
     * @param mixed  $data    Data to log.
     * @param string $context Context identifier.
     */
    public static function log_message($level, $data, $context = 'theme') {
        $instance = self::get_instance();
        $instance->log($level, $data, $context);
    }

    /**
     * Quick static method to check debug mode
     *
     * @return bool
     */
    public static function is_debug() {
        $instance = self::get_instance();
        return $instance->is_debug_mode();
    }

    /**
     * Quick static method to render error template
     *
     * @param string $error_type Error type.
     * @param array  $context    Context data.
     * @return string HTML.
     */
    public static function render_error($error_type, $context = array()) {
        return self::render_error_template($error_type, $context);
    }
}
