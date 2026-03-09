<?php
/**
 * Main Plugin Class
 *
 * @package Reforestamos_Micrositios
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Reforestamos_Micrositios class
 *
 * Singleton pattern implementation for the main plugin class.
 * Handles plugin initialization, loading of components, and hooks.
 */
class Reforestamos_Micrositios {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Micrositios
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Micrositios
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
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Load plugin text domain for translations
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'reforestamos-micrositios',
			false,
			dirname( REFORESTAMOS_MICRO_BASENAME ) . '/languages'
		);
	}

	/**
	 * Initialize plugin components
	 *
	 * Loads and initializes all plugin components.
	 */
	public function init_components() {
		// Load component files
		$this->load_includes();

		// Initialize components
		Reforestamos_JSON_Manager::init();
	}

	/**
	 * Load required files
	 */
	private function load_includes() {
		// Load Árboles y Ciudades class
		require_once REFORESTAMOS_MICRO_PATH . 'includes/class-arboles-ciudades.php';
		
		// Load Red OJA class
		require_once REFORESTAMOS_MICRO_PATH . 'includes/class-red-oja.php';
		
		// Load Map Renderer class
		require_once REFORESTAMOS_MICRO_PATH . 'includes/class-map-renderer.php';
		
		// Load JSON Manager class
		require_once REFORESTAMOS_MICRO_PATH . 'includes/class-json-manager.php';
	}

	/**
	 * Register shortcodes
	 */
	public function register_shortcodes() {
		add_shortcode( 'arboles-ciudades', array( 'Reforestamos_Arboles_Ciudades', 'render' ) );
		add_shortcode( 'red-oja', array( 'Reforestamos_Red_OJA', 'render' ) );
	}

	/**
	 * Enqueue frontend assets
	 */
	public function enqueue_assets() {
		// Leaflet CSS from CDN
		wp_enqueue_style(
			'leaflet',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
			array(),
			'1.9.4'
		);
		
		// Leaflet MarkerCluster CSS
		wp_enqueue_style(
			'leaflet-markercluster',
			'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css',
			array( 'leaflet' ),
			'1.5.3'
		);
		
		wp_enqueue_style(
			'leaflet-markercluster-default',
			'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css',
			array( 'leaflet-markercluster' ),
			'1.5.3'
		);
		
		// Maps CSS
		wp_enqueue_style(
			'reforestamos-maps',
			REFORESTAMOS_MICRO_URL . 'assets/css/maps.css',
			array( 'leaflet', 'leaflet-markercluster-default' ),
			REFORESTAMOS_MICRO_VERSION
		);
		
		// Leaflet JS from CDN
		wp_enqueue_script(
			'leaflet',
			'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
			array(),
			'1.9.4',
			true
		);
		
		// Leaflet MarkerCluster JS
		wp_enqueue_script(
			'leaflet-markercluster',
			'https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js',
			array( 'leaflet' ),
			'1.5.3',
			true
		);
		
		// Map handler JS
		wp_enqueue_script(
			'reforestamos-map-handler',
			REFORESTAMOS_MICRO_URL . 'assets/js/map-handler.js',
			array( 'jquery', 'leaflet', 'leaflet-markercluster' ),
			REFORESTAMOS_MICRO_VERSION,
			true
		);
		
		// Localize script with data
		wp_localize_script(
			'reforestamos-map-handler',
			'reforestamosData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'reforestamos_micro_nonce' ),
				'dataUrl' => REFORESTAMOS_MICRO_URL . 'data/',
			)
		);
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Micrositios', 'reforestamos-micrositios' ),
			__( 'Micrositios', 'reforestamos-micrositios' ),
			'manage_options',
			'reforestamos-micrositios',
			array( 'Reforestamos_JSON_Manager', 'render_admin_page' ),
			'dashicons-location-alt',
			30
		);
	}

	/**
	 * Plugin activation
	 *
	 * Runs on plugin activation.
	 */
	public static function activate() {
		// Create data directory if it doesn't exist
		$data_dir = REFORESTAMOS_MICRO_PATH . 'data';
		if ( ! file_exists( $data_dir ) ) {
			wp_mkdir_p( $data_dir );
		}
		
		// Create sample JSON files if they don't exist
		self::create_sample_data();
	}

	/**
	 * Create sample data files
	 */
	private static function create_sample_data() {
		$arboles_file = REFORESTAMOS_MICRO_PATH . 'data/arboles-ciudades.json';
		$oja_file     = REFORESTAMOS_MICRO_PATH . 'data/red-oja.json';
		
		// Create sample Árboles y Ciudades data
		if ( ! file_exists( $arboles_file ) ) {
			$sample_arboles = array(
				'version'      => '1.0',
				'last_updated' => current_time( 'mysql' ),
				'arboles'      => array(),
			);
			file_put_contents( $arboles_file, wp_json_encode( $sample_arboles, JSON_PRETTY_PRINT ) );
		}
		
		// Create sample Red OJA data
		if ( ! file_exists( $oja_file ) ) {
			$sample_oja = array(
				'version'       => '1.0',
				'last_updated'  => current_time( 'mysql' ),
				'organizaciones' => array(),
			);
			file_put_contents( $oja_file, wp_json_encode( $sample_oja, JSON_PRETTY_PRINT ) );
		}
	}
}
