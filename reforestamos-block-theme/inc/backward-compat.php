<?php
/**
 * Backward Compatibility
 *
 * Handles legacy URL redirects, old slug rewrites, and data format
 * compatibility to ensure existing links and bookmarks continue working
 * after the migration to the block theme.
 *
 * @package Reforestamos
 * @since 1.0.0
 *
 * Requirements: 24.6, 26.2, 26.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Reforestamos_Backward_Compat
 *
 * Manages backward compatibility for URLs, data formats, and legacy features.
 */
class Reforestamos_Backward_Compat {

	/**
	 * Legacy URL mappings: old path => new path.
	 *
	 * @var array
	 */
	private static $url_mappings = array();

	/**
	 * Initialize backward compatibility hooks.
	 */
	public static function init() {
		self::register_url_mappings();

		// Handle legacy URL redirects.
		add_action( 'template_redirect', array( __CLASS__, 'handle_legacy_redirects' ) );

		// Register custom rewrite rules for old URL patterns.
		add_action( 'init', array( __CLASS__, 'register_rewrite_rules' ) );

		// Handle legacy shortcodes that may still exist in content.
		add_action( 'init', array( __CLASS__, 'register_legacy_shortcodes' ) );

		// Handle legacy query vars.
		add_filter( 'query_vars', array( __CLASS__, 'add_legacy_query_vars' ) );

		// Handle legacy template redirects.
		add_action( 'template_redirect', array( __CLASS__, 'handle_legacy_templates' ) );

		// Preserve legacy custom field access patterns.
		add_filter( 'get_post_metadata', array( __CLASS__, 'map_legacy_meta_keys' ), 10, 4 );
	}

	/**
	 * Register URL mappings from old theme to new theme.
	 */
	private static function register_url_mappings() {
		self::$url_mappings = array(
			// Legacy page slugs that may have changed.
			'/quienes-somos'          => '/about/',
			'/quienes-somos/'         => '/about/',
			'/nuestro-equipo'         => '/about/#equipo',
			'/nuestro-equipo/'        => '/about/#equipo',

			// Legacy category/archive patterns.
			'/category/noticias'      => '/blog/',
			'/category/noticias/'     => '/blog/',
			'/category/comunicados'   => '/blog/',
			'/category/comunicados/'  => '/blog/',

			// Legacy CPT archive patterns (if slugs changed).
			'/empresa'                => '/empresas/',
			'/empresa/'               => '/empresas/',
			'/evento'                 => '/eventos/',
			'/evento/'                => '/eventos/',

			// Legacy micrositio pages.
			'/arboles-y-ciudades'     => '/micrositios/arboles-ciudades/',
			'/arboles-y-ciudades/'    => '/micrositios/arboles-ciudades/',
			'/red-oja-mapa'           => '/micrositios/red-oja/',
			'/red-oja-mapa/'          => '/micrositios/red-oja/',

			// Legacy newsletter pages.
			'/boletin'                => '/newsletter/',
			'/boletin/'               => '/newsletter/',
			'/suscribirse'            => '/newsletter/',
			'/suscribirse/'           => '/newsletter/',
		);

		/**
		 * Filter the legacy URL mappings.
		 *
		 * @param array $mappings Array of old_path => new_path mappings.
		 */
		self::$url_mappings = apply_filters( 'reforestamos_legacy_url_mappings', self::$url_mappings );
	}

	/**
	 * Handle redirects for legacy URLs.
	 */
	public static function handle_legacy_redirects() {
		if ( is_admin() ) {
			return;
		}

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$path        = wp_parse_url( $request_uri, PHP_URL_PATH );

		if ( empty( $path ) ) {
			return;
		}

		// Check direct URL mappings.
		if ( isset( self::$url_mappings[ $path ] ) ) {
			wp_safe_redirect( home_url( self::$url_mappings[ $path ] ), 301 );
			exit;
		}

		// Handle legacy pagination patterns: /page/N/ → ?paged=N.
		if ( preg_match( '#^/empresas/page/(\d+)/?$#', $path, $matches ) ) {
			wp_safe_redirect( home_url( '/empresas/?paged=' . intval( $matches[1] ) ), 301 );
			exit;
		}

		if ( preg_match( '#^/eventos/page/(\d+)/?$#', $path, $matches ) ) {
			wp_safe_redirect( home_url( '/eventos/?paged=' . intval( $matches[1] ) ), 301 );
			exit;
		}

		// Handle legacy feed URLs.
		if ( preg_match( '#^/feed/empresas/?$#', $path ) ) {
			wp_safe_redirect( home_url( '/empresas/feed/' ), 301 );
			exit;
		}

		// Handle legacy attachment URLs (redirect to parent post).
		self::handle_attachment_redirects();
	}

	/**
	 * Redirect attachment pages to their parent post.
	 */
	private static function handle_attachment_redirects() {
		if ( ! is_attachment() ) {
			return;
		}

		global $post;

		if ( $post && $post->post_parent ) {
			wp_safe_redirect( get_permalink( $post->post_parent ), 301 );
			exit;
		}

		// Orphan attachments redirect to home.
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}

	/**
	 * Register rewrite rules for legacy URL patterns.
	 */
	public static function register_rewrite_rules() {
		// Legacy single empresa pattern: /empresa/slug → /empresas/slug.
		add_rewrite_rule(
			'^empresa/([^/]+)/?$',
			'index.php?post_type=empresas&name=$matches[1]',
			'top'
		);

		// Legacy single evento pattern: /evento/slug → /eventos/slug.
		add_rewrite_rule(
			'^evento/([^/]+)/?$',
			'index.php?post_type=eventos&name=$matches[1]',
			'top'
		);

		// Legacy integrante pattern.
		add_rewrite_rule(
			'^integrante/([^/]+)/?$',
			'index.php?post_type=integrantes&name=$matches[1]',
			'top'
		);

		// Legacy taxonomy archive patterns.
		add_rewrite_rule(
			'^tipo-empresa/([^/]+)/?$',
			'index.php?categoria_empresa=$matches[1]',
			'top'
		);

		// Legacy year/month archive for eventos.
		add_rewrite_rule(
			'^eventos/(\d{4})/(\d{2})/?$',
			'index.php?post_type=eventos&year=$matches[1]&monthnum=$matches[2]',
			'top'
		);

		// Legacy newsletter unsubscribe URL.
		add_rewrite_rule(
			'^newsletter/unsubscribe/([a-zA-Z0-9]+)/?$',
			'index.php?reforestamos_unsubscribe=$matches[1]',
			'top'
		);
	}

	/**
	 * Register legacy shortcodes that redirect to new implementations.
	 */
	public static function register_legacy_shortcodes() {
		$legacy_shortcodes = array(
			'reforestamos_empresas'   => 'companies-grid',
			'reforestamos_eventos'    => 'reforestamos/eventos-proximos',
			'reforestamos_newsletter' => 'newsletter-subscribe',
			'reforestamos_contacto'   => 'contact-form',
			'reforestamos_mapa'       => 'arboles-ciudades',
			'reforestamos_galeria'    => 'company-gallery',
		);

		foreach ( $legacy_shortcodes as $old_shortcode => $new_shortcode ) {
			if ( ! shortcode_exists( $old_shortcode ) ) {
				add_shortcode( $old_shortcode, function ( $atts ) use ( $new_shortcode ) {
					return self::legacy_shortcode_handler( $atts, $new_shortcode );
				} );
			}
		}
	}

	/**
	 * Handle legacy shortcode by delegating to the new shortcode.
	 *
	 * @param array  $atts          Shortcode attributes.
	 * @param string $new_shortcode The new shortcode name.
	 * @return string Shortcode output.
	 */
	private static function legacy_shortcode_handler( $atts, $new_shortcode ) {
		// If the new shortcode exists, delegate to it.
		if ( shortcode_exists( $new_shortcode ) ) {
			$atts_string = '';
			if ( is_array( $atts ) ) {
				foreach ( $atts as $key => $value ) {
					if ( is_numeric( $key ) ) {
						$atts_string .= ' ' . esc_attr( $value );
					} else {
						$atts_string .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
					}
				}
			}
			return do_shortcode( '[' . $new_shortcode . $atts_string . ']' );
		}

		// Fallback message if the new shortcode's plugin is not active.
		return '<p class="reforestamos-legacy-notice">'
			. esc_html__( 'Este contenido requiere un plugin adicional. Por favor contacta al administrador.', 'reforestamos' )
			. '</p>';
	}

	/**
	 * Add legacy query vars for backward compatibility.
	 *
	 * @param array $vars Existing query vars.
	 * @return array Modified query vars.
	 */
	public static function add_legacy_query_vars( $vars ) {
		$vars[] = 'reforestamos_unsubscribe';
		$vars[] = 'empresa_categoria';
		$vars[] = 'evento_fecha';
		return $vars;
	}

	/**
	 * Handle legacy template requests.
	 */
	public static function handle_legacy_templates() {
		// Handle legacy unsubscribe token.
		$unsubscribe_token = get_query_var( 'reforestamos_unsubscribe' );
		if ( ! empty( $unsubscribe_token ) && class_exists( 'Reforestamos_Comunicacion' ) ) {
			/**
			 * Action fired when a legacy unsubscribe URL is accessed.
			 *
			 * @param string $token The unsubscribe token.
			 */
			do_action( 'reforestamos_process_unsubscribe', sanitize_text_field( $unsubscribe_token ) );
		}
	}

	/**
	 * Map legacy meta keys to new meta keys.
	 * Ensures old meta key access patterns still work.
	 *
	 * @param mixed  $value     Current meta value (null to proceed with default).
	 * @param int    $object_id Post ID.
	 * @param string $meta_key  Meta key being accessed.
	 * @param bool   $single    Whether to return a single value.
	 * @return mixed The meta value or null to use default.
	 */
	public static function map_legacy_meta_keys( $value, $object_id, $meta_key, $single ) {
		$legacy_key_map = array(
			// Old key => New key.
			'empresa_logo'       => '_empresa_logo',
			'empresa_url'        => '_empresa_url',
			'empresa_categoria'  => '_empresa_categoria',
			'empresa_anio'       => '_empresa_anio',
			'empresa_arboles'    => '_empresa_arboles',
			'evento_fecha'       => '_evento_fecha',
			'evento_ubicacion'   => '_evento_ubicacion',
			'evento_lat'         => '_evento_lat',
			'evento_lng'         => '_evento_lng',
			'evento_capacidad'   => '_evento_capacidad',
			'integrante_cargo'   => '_integrante_cargo',
			'integrante_email'   => '_integrante_email',
		);

		// If accessing a legacy key, redirect to the new key.
		if ( isset( $legacy_key_map[ $meta_key ] ) ) {
			$new_key = $legacy_key_map[ $meta_key ];

			// Prevent infinite recursion by removing this filter temporarily.
			remove_filter( 'get_post_metadata', array( __CLASS__, 'map_legacy_meta_keys' ), 10 );
			$result = get_post_meta( $object_id, $new_key, $single );
			add_filter( 'get_post_metadata', array( __CLASS__, 'map_legacy_meta_keys' ), 10, 4 );

			// If the new key has data, return it.
			if ( ! empty( $result ) ) {
				return $single ? $result : array( $result );
			}
		}

		// Return null to let WordPress proceed with the original meta key.
		return $value;
	}

	/**
	 * Flush rewrite rules if needed (run once after theme activation).
	 */
	public static function flush_rules_if_needed() {
		if ( get_option( 'reforestamos_compat_rules_flushed' ) !== '1.0.0' ) {
			flush_rewrite_rules();
			update_option( 'reforestamos_compat_rules_flushed', '1.0.0' );
		}
	}
}

// Flush rewrite rules on theme switch.
add_action( 'after_switch_theme', array( 'Reforestamos_Backward_Compat', 'flush_rules_if_needed' ) );
