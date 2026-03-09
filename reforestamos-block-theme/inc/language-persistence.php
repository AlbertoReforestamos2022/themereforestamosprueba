<?php
/**
 * Language Persistence Functions
 *
 * Handles storing and retrieving user language preferences
 * across sessions using cookies and WordPress options.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Language Persistence Manager
 */
class Reforestamos_Language_Persistence {
    
    /**
     * Cookie name for language storage
     */
    const COOKIE_NAME = 'reforestamos_lang';
    
    /**
     * Cookie expiration time (1 year)
     */
    const COOKIE_EXPIRATION = YEAR_IN_SECONDS;
    
    /**
     * Session key for language storage
     */
    const SESSION_KEY = 'reforestamos_lang';
    
    /**
     * User meta key for language preference
     */
    const USER_META_KEY = 'reforestamos_preferred_lang';
    
    /**
     * Initialize hooks
     */
    public static function init() {
        // Start session early
        add_action('init', array(__CLASS__, 'start_session'), 1);
        
        // Apply language on template redirect
        add_action('template_redirect', array(__CLASS__, 'apply_language'), 5);
        
        // Save language preference for logged-in users
        add_action('wp_login', array(__CLASS__, 'save_user_language_preference'), 10, 2);
        
        // Load user language preference on login
        add_action('wp_loaded', array(__CLASS__, 'load_user_language_preference'));
        
        // Add language to body class
        add_filter('body_class', array(__CLASS__, 'add_language_body_class'));
        
        // Set HTML lang attribute
        add_filter('language_attributes', array(__CLASS__, 'set_html_lang_attribute'));
    }
    
    /**
     * Start session if not already started
     */
    public static function start_session() {
        if (!session_id() && !headers_sent()) {
            session_start();
        }
    }
    
    /**
     * Get current language from various sources
     *
     * Priority order:
     * 1. URL parameter (for switching)
     * 2. Cookie
     * 3. Session
     * 4. User meta (for logged-in users)
     * 5. Browser language
     * 6. Default (Spanish)
     *
     * @return string Language code (es or en)
     */
    public static function get_language() {
        // Check URL parameter (for switching)
        if (isset($_GET['lang'])) {
            $lang = sanitize_text_field($_GET['lang']);
            if (self::is_valid_language($lang)) {
                return $lang;
            }
        }
        
        // Check cookie
        if (isset($_COOKIE[self::COOKIE_NAME])) {
            $lang = sanitize_text_field($_COOKIE[self::COOKIE_NAME]);
            if (self::is_valid_language($lang)) {
                return $lang;
            }
        }
        
        // Check session
        if (isset($_SESSION[self::SESSION_KEY])) {
            $lang = sanitize_text_field($_SESSION[self::SESSION_KEY]);
            if (self::is_valid_language($lang)) {
                return $lang;
            }
        }
        
        // Check user meta for logged-in users
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $lang = get_user_meta($user_id, self::USER_META_KEY, true);
            if (self::is_valid_language($lang)) {
                return $lang;
            }
        }
        
        // Check browser language
        $browser_lang = self::get_browser_language();
        if ($browser_lang) {
            return $browser_lang;
        }
        
        // Default to Spanish
        return 'es';
    }
    
    /**
     * Set language preference
     *
     * @param string $lang Language code (es or en)
     * @return bool True on success, false on failure
     */
    public static function set_language($lang) {
        if (!self::is_valid_language($lang)) {
            return false;
        }
        
        // Set cookie
        $cookie_set = setcookie(
            self::COOKIE_NAME,
            $lang,
            time() + self::COOKIE_EXPIRATION,
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true // HTTP only
        );
        
        // Set session
        if (!session_id()) {
            self::start_session();
        }
        $_SESSION[self::SESSION_KEY] = $lang;
        
        // Set user meta for logged-in users
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            update_user_meta($user_id, self::USER_META_KEY, $lang);
        }
        
        return $cookie_set;
    }
    
    /**
     * Check if language code is valid
     *
     * @param string $lang Language code
     * @return bool True if valid, false otherwise
     */
    public static function is_valid_language($lang) {
        return in_array($lang, array('es', 'en'), true);
    }
    
    /**
     * Get browser language preference
     *
     * @return string|false Language code or false if not detected
     */
    private static function get_browser_language() {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return false;
        }
        
        $accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        
        // Parse Accept-Language header
        preg_match_all('/([a-z]{2})(?:-[A-Z]{2})?/', $accept_language, $matches);
        
        if (empty($matches[1])) {
            return false;
        }
        
        // Check for Spanish or English
        foreach ($matches[1] as $lang) {
            if ($lang === 'es') {
                return 'es';
            }
            if ($lang === 'en') {
                return 'en';
            }
        }
        
        return false;
    }
    
    /**
     * Apply language on template redirect
     */
    public static function apply_language() {
        $lang = self::get_language();
        
        // Set WordPress locale
        add_filter('locale', function($locale) use ($lang) {
            return $lang === 'en' ? 'en_US' : 'es_MX';
        });
        
        // Set language in global variable for easy access
        $GLOBALS['reforestamos_current_lang'] = $lang;
    }
    
    /**
     * Save user language preference on login
     *
     * @param string $user_login Username
     * @param WP_User $user User object
     */
    public static function save_user_language_preference($user_login, $user) {
        $current_lang = self::get_language();
        update_user_meta($user->ID, self::USER_META_KEY, $current_lang);
    }
    
    /**
     * Load user language preference on page load
     */
    public static function load_user_language_preference() {
        if (!is_user_logged_in()) {
            return;
        }
        
        // Only load if no cookie/session is set
        if (isset($_COOKIE[self::COOKIE_NAME]) || isset($_SESSION[self::SESSION_KEY])) {
            return;
        }
        
        $user_id = get_current_user_id();
        $user_lang = get_user_meta($user_id, self::USER_META_KEY, true);
        
        if (self::is_valid_language($user_lang)) {
            self::set_language($user_lang);
        }
    }
    
    /**
     * Add language to body class
     *
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public static function add_language_body_class($classes) {
        $lang = self::get_language();
        $classes[] = 'lang-' . $lang;
        $classes[] = 'language-' . ($lang === 'es' ? 'spanish' : 'english');
        return $classes;
    }
    
    /**
     * Set HTML lang attribute
     *
     * @param string $output Language attributes
     * @return string Modified language attributes
     */
    public static function set_html_lang_attribute($output) {
        $lang = self::get_language();
        $lang_attr = $lang === 'es' ? 'es-MX' : 'en-US';
        return 'lang="' . esc_attr($lang_attr) . '" dir="ltr"';
    }
    
    /**
     * Clear language preference
     *
     * @return bool True on success
     */
    public static function clear_language() {
        // Clear cookie
        setcookie(
            self::COOKIE_NAME,
            '',
            time() - 3600,
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true
        );
        
        // Clear session
        if (isset($_SESSION[self::SESSION_KEY])) {
            unset($_SESSION[self::SESSION_KEY]);
        }
        
        // Clear user meta for logged-in users
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            delete_user_meta($user_id, self::USER_META_KEY);
        }
        
        return true;
    }
    
    /**
     * Get language statistics
     *
     * @return array Language usage statistics
     */
    public static function get_statistics() {
        global $wpdb;
        
        $stats = array(
            'total_users' => 0,
            'spanish_users' => 0,
            'english_users' => 0,
            'no_preference' => 0,
        );
        
        // Get total users
        $stats['total_users'] = count_users();
        $stats['total_users'] = $stats['total_users']['total_users'];
        
        // Get users with Spanish preference
        $spanish_users = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value = %s",
            self::USER_META_KEY,
            'es'
        ));
        $stats['spanish_users'] = intval($spanish_users);
        
        // Get users with English preference
        $english_users = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value = %s",
            self::USER_META_KEY,
            'en'
        ));
        $stats['english_users'] = intval($english_users);
        
        // Calculate users with no preference
        $stats['no_preference'] = $stats['total_users'] - $stats['spanish_users'] - $stats['english_users'];
        
        return $stats;
    }
}

// Initialize language persistence
Reforestamos_Language_Persistence::init();

/**
 * Helper function to get current language
 *
 * @return string Language code (es or en)
 */
function reforestamos_get_language() {
    return Reforestamos_Language_Persistence::get_language();
}

/**
 * Helper function to set language
 *
 * @param string $lang Language code (es or en)
 * @return bool True on success, false on failure
 */
function reforestamos_set_language($lang) {
    return Reforestamos_Language_Persistence::set_language($lang);
}
