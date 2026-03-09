<?php
/**
 * Main Plugin Class
 *
 * @package Reforestamos_Core
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Reforestamos_Core class
 *
 * Singleton pattern implementation for the main plugin class.
 * Handles plugin initialization, loading of components, and hooks.
 */
class Reforestamos_Core {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Core
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Core
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize WordPress hooks
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'init_components' ) );
	}

	/**
	 * Load plugin text domain for translations
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'reforestamos-core',
			false,
			dirname( REFORESTAMOS_CORE_BASENAME ) . '/languages'
		);
	}

	/**
	 * Initialize plugin components
	 *
	 * Loads and initializes all plugin components like post types,
	 * taxonomies, custom fields, REST API, etc.
	 */
	public function init_components() {
		// Load component files
		$this->load_includes();

		// Initialize components
		Reforestamos_Core_Post_Types::get_instance();
		Reforestamos_Core_Taxonomies::get_instance();
		Reforestamos_Core_Custom_Fields::get_instance();
		Reforestamos_Core_REST_API::get_instance();
		Reforestamos_Core_Admin_UI::get_instance();
	}

	/**
	 * Load required files
	 */
	private function load_includes() {
		// Load Post Types class
		require_once REFORESTAMOS_CORE_PATH . 'includes/class-post-types.php';
		
		// Load Taxonomies class
		require_once REFORESTAMOS_CORE_PATH . 'includes/class-taxonomies.php';
		
		// Load Custom Fields class
		require_once REFORESTAMOS_CORE_PATH . 'includes/class-custom-fields.php';
		
		// Load REST API class
		require_once REFORESTAMOS_CORE_PATH . 'includes/class-rest-api.php';
		
		// Load Admin UI class
		require_once REFORESTAMOS_CORE_PATH . 'includes/class-admin-ui.php';
	}

	/**
	 * Plugin activation
	 *
	 * Runs on plugin activation. Registers post types and taxonomies,
	 * flushes rewrite rules, and creates default terms.
	 */
	public static function activate() {
		// Load Post Types class
		require_once REFORESTAMOS_CORE_PATH . 'includes/class-post-types.php';
		
		// Load Taxonomies class
		require_once REFORESTAMOS_CORE_PATH . 'includes/class-taxonomies.php';
		
		// Initialize Post Types and Taxonomies to register them
		Reforestamos_Core_Post_Types::get_instance();
		Reforestamos_Core_Taxonomies::get_instance();
		
		// TODO: Create default terms when needed
		
		// Flush rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation
	 *
	 * Runs on plugin deactivation. Flushes rewrite rules.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
