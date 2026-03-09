<?php
/**
 * Main Plugin Class
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Reforestamos_Empresas class
 *
 * Singleton pattern implementation for the main plugin class.
 * Handles plugin initialization, loading of components, and hooks.
 * Extends functionality of the "Empresas" Custom Post Type from Core Plugin.
 */
class Reforestamos_Empresas {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Empresas
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Empresas
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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_filter( 'template_include', array( $this, 'load_custom_template' ) );
		add_filter( 'theme_page_templates', array( $this, 'add_page_templates' ) );
	}

	/**
	 * Load plugin text domain for translations
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'reforestamos-empresas',
			false,
			dirname( REFORESTAMOS_EMPRESAS_BASENAME ) . '/languages'
		);
	}

	/**
	 * Initialize plugin components
	 *
	 * Loads and initializes all plugin components like analytics,
	 * galleries, shortcodes, etc.
	 */
	public function init_components() {
		// Load component files
		$this->load_includes();

		// Initialize components will be added in future tasks
		// Example: Reforestamos_Empresas_Analytics::get_instance();
		// Example: Reforestamos_Empresas_Gallery::get_instance();
		// Example: Reforestamos_Empresas_Shortcodes::get_instance();
	}

	/**
	 * Load required files
	 */
	private function load_includes() {
		// Load Company Manager
		require_once REFORESTAMOS_EMPRESAS_PATH . 'includes/class-company-manager.php';
		
		// Load Shortcodes
		require_once REFORESTAMOS_EMPRESAS_PATH . 'includes/class-shortcodes.php';
		
		// Load Image Optimizer
		require_once REFORESTAMOS_EMPRESAS_PATH . 'includes/class-image-optimizer.php';
		
		// Load Analytics
		require_once REFORESTAMOS_EMPRESAS_PATH . 'includes/class-analytics.php';
		
		// Load Gallery Manager
		require_once REFORESTAMOS_EMPRESAS_PATH . 'includes/class-gallery-manager.php';
		
		// Initialize Company Manager
		Reforestamos_Company_Manager::get_instance();
		
		// Initialize Shortcodes
		Reforestamos_Empresas_Shortcodes::get_instance();
		
		// Initialize Image Optimizer
		Reforestamos_Image_Optimizer::get_instance();
		
		// Initialize Analytics
		Reforestamos_Analytics::init();
		
		// Initialize Gallery Manager
		Reforestamos_Gallery_Manager::get_instance();
		
		// Component files will be loaded here in future tasks
		// Example: require_once REFORESTAMOS_EMPRESAS_PATH . 'includes/class-gallery.php';
	}

	/**
	 * Enqueue frontend assets
	 */
	public function enqueue_frontend_assets() {
		// Frontend CSS
		wp_enqueue_style(
			'reforestamos-empresas-frontend',
			REFORESTAMOS_EMPRESAS_URL . 'assets/css/frontend.css',
			array(),
			REFORESTAMOS_EMPRESAS_VERSION
		);

		// Company Profile CSS (only on single empresa pages)
		if ( is_singular( 'empresas' ) ) {
			wp_enqueue_style(
				'reforestamos-empresas-profile',
				REFORESTAMOS_EMPRESAS_URL . 'assets/css/company-profile.css',
				array(),
				REFORESTAMOS_EMPRESAS_VERSION
			);

			// Lightbox for gallery
			wp_enqueue_style(
				'lightbox2',
				'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css',
				array(),
				'2.11.4'
			);

			wp_enqueue_script(
				'lightbox2',
				'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js',
				array( 'jquery' ),
				'2.11.4',
				true
			);
		}

		// Frontend JS
		wp_enqueue_script(
			'reforestamos-empresas-frontend',
			REFORESTAMOS_EMPRESAS_URL . 'assets/js/frontend.js',
			array( 'jquery' ),
			REFORESTAMOS_EMPRESAS_VERSION,
			true
		);
		
		// Click Tracker JS
		wp_enqueue_script(
			'reforestamos-click-tracker',
			REFORESTAMOS_EMPRESAS_URL . 'assets/js/click-tracker.js',
			array( 'jquery' ),
			REFORESTAMOS_EMPRESAS_VERSION,
			true
		);

		// Localize script for AJAX
		wp_localize_script(
			'reforestamos-empresas-frontend',
			'reforestamosEmpresas',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'reforestamos_empresas_nonce' ),
			)
		);
		
		// Localize script for click tracker
		wp_localize_script(
			'reforestamos-click-tracker',
			'reforestamosComp',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'reforestamos_comp_nonce' ),
			)
		);
	}

	/**
	 * Enqueue admin assets
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on empresas post type pages
		$screen = get_current_screen();
		if ( ! $screen || 'empresas' !== $screen->post_type ) {
			return;
		}

		// Enqueue WordPress media uploader
		wp_enqueue_media();

		// Admin CSS
		wp_enqueue_style(
			'reforestamos-empresas-admin',
			REFORESTAMOS_EMPRESAS_URL . 'admin/css/admin.css',
			array(),
			REFORESTAMOS_EMPRESAS_VERSION
		);

		// Admin JS
		wp_enqueue_script(
			'reforestamos-empresas-admin',
			REFORESTAMOS_EMPRESAS_URL . 'admin/js/admin.js',
			array( 'jquery', 'media-upload', 'media-views' ),
			REFORESTAMOS_EMPRESAS_VERSION,
			true
		);
	}

	/**
	 * Plugin activation
	 *
	 * Runs on plugin activation. Creates database tables,
	 * sets default options, etc.
	 */
	public static function activate() {
		// Create database tables for analytics
		self::create_tables();

		// Set default options
		self::set_default_options();

		// Flush rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Create database tables
	 *
	 * Creates custom tables needed for analytics tracking.
	 */
	private static function create_tables() {
		// Create analytics table
		require_once REFORESTAMOS_EMPRESAS_PATH . 'includes/class-analytics.php';
		Reforestamos_Analytics::create_table();
	}

	/**
	 * Set default plugin options
	 */
	private static function set_default_options() {
		// Set default options if they don't exist
		$defaults = array(
			'reforestamos_empresas_enable_analytics' => '1',
			'reforestamos_empresas_enable_galleries'  => '1',
			'reforestamos_empresas_grid_columns'      => '3',
		);

		foreach ( $defaults as $option_name => $option_value ) {
			if ( false === get_option( $option_name ) ) {
				add_option( $option_name, $option_value );
			}
		}
	}

	/**
	 * Load custom template for single empresa
	 *
	 * @param string $template Template path.
	 * @return string Modified template path.
	 */
	public function load_custom_template( $template ) {
		if ( is_singular( 'empresas' ) ) {
			$plugin_template = REFORESTAMOS_EMPRESAS_PATH . 'templates/single-empresa-template.php';
			
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}
		
		// Check for page template
		if ( is_page() ) {
			$page_template = get_page_template_slug();
			
			if ( 'page-galleries.php' === $page_template ) {
				$plugin_template = REFORESTAMOS_EMPRESAS_PATH . 'templates/page-galleries.php';
				
				if ( file_exists( $plugin_template ) ) {
					return $plugin_template;
				}
			}
		}
		
		return $template;
	}

	/**
	 * Add custom page templates
	 *
	 * @param array $templates Existing templates.
	 * @return array Modified templates.
	 */
	public function add_page_templates( $templates ) {
		$templates['page-galleries.php'] = __( 'Galerías de Empresas', 'reforestamos-empresas' );
		return $templates;
	}
}
