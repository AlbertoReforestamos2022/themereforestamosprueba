<?php
/**
 * Conditional Assets Loading
 *
 * Ensures assets are loaded only when needed, prevents duplicates,
 * and implements smart conditional loading for blocks and plugins.
 *
 * @package Reforestamos
 * @since 1.0.0
 *
 * Requirements: 15.9, 19.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Reforestamos_Conditional_Assets
 *
 * Manages conditional loading of CSS and JS assets based on page content.
 */
class Reforestamos_Conditional_Assets {

	/**
	 * Blocks detected on the current page.
	 *
	 * @var array
	 */
	private static $detected_blocks = array();

	/**
	 * Shortcodes detected on the current page.
	 *
	 * @var array
	 */
	private static $detected_shortcodes = array();

	/**
	 * Initialize conditional asset loading.
	 */
	public static function init() {
		// Detect blocks and shortcodes early.
		add_action( 'wp', array( __CLASS__, 'detect_page_content' ) );

		// Conditionally load assets.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'conditional_enqueue' ), 5 );

		// Dequeue unnecessary assets.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'dequeue_unnecessary_assets' ), 999 );

		// Prevent duplicate asset registration.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'prevent_duplicate_assets' ), 998 );

		// Optimize admin assets.
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'conditional_admin_assets' ) );
	}

	/**
	 * Detect which blocks and shortcodes are used on the current page.
	 */
	public static function detect_page_content() {
		if ( is_admin() ) {
			return;
		}

		global $post;

		if ( ! $post || empty( $post->post_content ) ) {
			return;
		}

		$content = $post->post_content;

		// Detect Gutenberg blocks.
		$reforestamos_blocks = array(
			'reforestamos/hero',
			'reforestamos/carousel',
			'reforestamos/contacto',
			'reforestamos/documents',
			'reforestamos/faqs',
			'reforestamos/galeria-tabs',
			'reforestamos/logos-aliados',
			'reforestamos/timeline',
			'reforestamos/cards-enlaces',
			'reforestamos/cards-iniciativas',
			'reforestamos/texto-imagen',
			'reforestamos/list',
			'reforestamos/sobre-nosotros',
			'reforestamos/header-navbar',
			'reforestamos/footer',
			'reforestamos/eventos-proximos',
		);

		foreach ( $reforestamos_blocks as $block_name ) {
			if ( has_block( $block_name, $post ) ) {
				self::$detected_blocks[] = $block_name;
			}
		}

		// Detect shortcodes.
		$shortcodes = array(
			'arboles-ciudades',
			'red-oja',
			'newsletter-subscribe',
			'contact-form',
			'companies-grid',
			'company-gallery',
			'tree-adoption-form',
		);

		foreach ( $shortcodes as $shortcode ) {
			if ( has_shortcode( $content, $shortcode ) ) {
				self::$detected_shortcodes[] = $shortcode;
			}
		}
	}

	/**
	 * Conditionally enqueue assets based on detected content.
	 */
	public static function conditional_enqueue() {
		if ( is_admin() ) {
			return;
		}

		// GLightbox: only load on pages with gallery blocks.
		$needs_lightbox = in_array( 'reforestamos/galeria-tabs', self::$detected_blocks, true )
			|| in_array( 'company-gallery', self::$detected_shortcodes, true );

		if ( ! $needs_lightbox ) {
			wp_dequeue_style( 'glightbox' );
			wp_dequeue_script( 'glightbox-js' );
			wp_dequeue_script( 'reforestamos-frontend' );
		}

		// Leaflet: only load on pages with map shortcodes.
		$needs_maps = in_array( 'arboles-ciudades', self::$detected_shortcodes, true )
			|| in_array( 'red-oja', self::$detected_shortcodes, true );

		if ( ! $needs_maps ) {
			wp_dequeue_style( 'leaflet-css' );
			wp_dequeue_script( 'leaflet-js' );
			wp_dequeue_script( 'reforestamos-map-handler' );
		}

		// Chart.js: only load on admin analytics pages.
		if ( ! is_admin() ) {
			wp_dequeue_script( 'chart-js' );
		}

		// Eventos frontend script: only on pages with eventos block.
		$needs_eventos = in_array( 'reforestamos/eventos-proximos', self::$detected_blocks, true )
			|| is_post_type_archive( 'eventos' )
			|| is_singular( 'eventos' );

		if ( ! $needs_eventos ) {
			wp_dequeue_script( 'reforestamos-eventos-proximos' );
		}

		// Contact form script: only on pages with contacto block or shortcode.
		$needs_contact = in_array( 'reforestamos/contacto', self::$detected_blocks, true )
			|| in_array( 'contact-form', self::$detected_shortcodes, true );

		if ( ! $needs_contact ) {
			wp_dequeue_script( 'reforestamos-contacto' );
		}

		// Chatbot: only load if Communication plugin is active and chatbot is enabled.
		if ( ! self::is_chatbot_enabled() ) {
			wp_dequeue_style( 'reforestamos-chatbot-css' );
			wp_dequeue_script( 'reforestamos-chatbot-js' );
		}
	}

	/**
	 * Dequeue assets that are not needed on the current page.
	 */
	public static function dequeue_unnecessary_assets() {
		if ( is_admin() ) {
			return;
		}

		// Remove WordPress emoji scripts if not needed.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		// Remove wp-embed script if not using embeds.
		if ( ! self::page_has_embeds() ) {
			wp_dequeue_script( 'wp-embed' );
		}

		// Remove block library CSS on pages without blocks (e.g., simple pages).
		if ( empty( self::$detected_blocks ) && ! is_singular() ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
		}

		// Remove jQuery migrate on frontend (not needed for modern code).
		if ( ! is_admin() ) {
			wp_dequeue_script( 'jquery-migrate' );
		}
	}

	/**
	 * Prevent duplicate asset registrations.
	 */
	public static function prevent_duplicate_assets() {
		global $wp_styles, $wp_scripts;

		if ( ! $wp_styles || ! $wp_scripts ) {
			return;
		}

		// Track Bootstrap registrations to prevent duplicates.
		$bootstrap_handles = array();
		foreach ( $wp_styles->registered as $handle => $style ) {
			if ( isset( $style->src ) && strpos( $style->src, 'bootstrap' ) !== false ) {
				$bootstrap_handles[] = $handle;
			}
		}

		// Keep only the first Bootstrap registration.
		if ( count( $bootstrap_handles ) > 1 ) {
			array_shift( $bootstrap_handles ); // Keep the first one.
			foreach ( $bootstrap_handles as $handle ) {
				wp_dequeue_style( $handle );
				wp_deregister_style( $handle );
			}
		}

		// Same for Bootstrap JS.
		$bootstrap_js_handles = array();
		foreach ( $wp_scripts->registered as $handle => $script ) {
			if ( isset( $script->src ) && strpos( $script->src, 'bootstrap' ) !== false ) {
				$bootstrap_js_handles[] = $handle;
			}
		}

		if ( count( $bootstrap_js_handles ) > 1 ) {
			array_shift( $bootstrap_js_handles );
			foreach ( $bootstrap_js_handles as $handle ) {
				wp_dequeue_script( $handle );
				wp_deregister_script( $handle );
			}
		}
	}

	/**
	 * Conditionally load admin assets only on relevant pages.
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 */
	public static function conditional_admin_assets( $hook_suffix ) {
		// Chart.js only on analytics dashboard.
		$analytics_pages = array(
			'toplevel_page_reforestamos-empresas',
			'reforestamos-empresas_page_reforestamos-analytics',
		);

		if ( ! in_array( $hook_suffix, $analytics_pages, true ) ) {
			wp_dequeue_script( 'chart-js' );
			wp_dequeue_script( 'reforestamos-analytics-js' );
		}

		// Chatbot config assets only on chatbot config page.
		if ( strpos( $hook_suffix, 'chatbot' ) === false ) {
			wp_dequeue_script( 'reforestamos-chatbot-admin' );
			wp_dequeue_style( 'reforestamos-chatbot-admin-css' );
		}
	}

	/**
	 * Check if the chatbot feature is enabled.
	 *
	 * @return bool
	 */
	private static function is_chatbot_enabled() {
		if ( ! class_exists( 'Reforestamos_Comunicacion' ) ) {
			return false;
		}

		return (bool) get_option( 'reforestamos_chatbot_enabled', false );
	}

	/**
	 * Check if the current page has embed content.
	 *
	 * @return bool
	 */
	private static function page_has_embeds() {
		global $post;

		if ( ! $post || empty( $post->post_content ) ) {
			return false;
		}

		// Check for common embed patterns.
		$embed_patterns = array(
			'<!-- wp:embed',
			'<!-- wp:core-embed',
			'[embed]',
			'<iframe',
		);

		foreach ( $embed_patterns as $pattern ) {
			if ( strpos( $post->post_content, $pattern ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get list of detected blocks on current page.
	 *
	 * @return array
	 */
	public static function get_detected_blocks() {
		return self::$detected_blocks;
	}

	/**
	 * Get list of detected shortcodes on current page.
	 *
	 * @return array
	 */
	public static function get_detected_shortcodes() {
		return self::$detected_shortcodes;
	}
}
