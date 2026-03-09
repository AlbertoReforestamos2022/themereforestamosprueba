<?php
/**
 * REST API Extensions
 *
 * Provides custom REST API endpoints for Custom Post Types and exposes
 * custom fields in REST API responses.
 *
 * @package Reforestamos_Core
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Core_REST_API class
 *
 * Handles REST API endpoint registration and custom field exposure.
 * Uses singleton pattern for consistent initialization.
 */
class Reforestamos_Core_REST_API {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Core_REST_API
	 */
	private static $instance = null;

	/**
	 * REST API namespace
	 *
	 * @var string
	 */
	private $namespace = 'reforestamos/v1';

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Core_REST_API
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
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		add_action( 'rest_api_init', array( $this, 'register_custom_fields' ) );
	}

	/**
	 * Register custom REST routes
	 */
	public function register_routes() {
		// GET /reforestamos/v1/empresas
		register_rest_route(
			$this->namespace,
			'/empresas',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_empresas' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'categoria'    => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'description'       => __( 'Filter by category', 'reforestamos-core' ),
					),
					'anio'         => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'description'       => __( 'Filter by year', 'reforestamos-core' ),
					),
					'arboles_min'  => array(
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
						'description'       => __( 'Minimum number of trees', 'reforestamos-core' ),
					),
					'per_page'     => array(
						'type'              => 'integer',
						'default'           => 10,
						'sanitize_callback' => 'absint',
						'description'       => __( 'Items per page', 'reforestamos-core' ),
					),
					'page'         => array(
						'type'              => 'integer',
						'default'           => 1,
						'sanitize_callback' => 'absint',
						'description'       => __( 'Page number', 'reforestamos-core' ),
					),
				),
			)
		);

		// GET /reforestamos/v1/eventos/upcoming
		register_rest_route(
			$this->namespace,
			'/eventos/upcoming',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_upcoming_eventos' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'ubicacion' => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'description'       => __( 'Filter by location', 'reforestamos-core' ),
					),
					'tipo'      => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'description'       => __( 'Filter by event type', 'reforestamos-core' ),
					),
					'per_page'  => array(
						'type'              => 'integer',
						'default'           => 10,
						'sanitize_callback' => 'absint',
						'description'       => __( 'Items per page', 'reforestamos-core' ),
					),
					'page'      => array(
						'type'              => 'integer',
						'default'           => 1,
						'sanitize_callback' => 'absint',
						'description'       => __( 'Page number', 'reforestamos-core' ),
					),
				),
			)
		);

		// GET /reforestamos/v1/integrantes
		register_rest_route(
			$this->namespace,
			'/integrantes',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_integrantes' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'area'     => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'description'       => __( 'Filter by area', 'reforestamos-core' ),
					),
					'per_page' => array(
						'type'              => 'integer',
						'default'           => 10,
						'sanitize_callback' => 'absint',
						'description'       => __( 'Items per page', 'reforestamos-core' ),
					),
					'page'     => array(
						'type'              => 'integer',
						'default'           => 1,
						'sanitize_callback' => 'absint',
						'description'       => __( 'Page number', 'reforestamos-core' ),
					),
				),
			)
		);
	}

	/**
	 * Register custom fields in REST API responses
	 */
	public function register_custom_fields() {
		// Register Empresas custom fields
		register_rest_field(
			'empresas',
			'meta',
			array(
				'get_callback' => array( $this, 'get_empresas_meta' ),
				'schema'       => array(
					'description' => __( 'Custom fields for Empresas', 'reforestamos-core' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'empresa_logo'      => array(
							'type'        => 'string',
							'description' => __( 'Company logo URL', 'reforestamos-core' ),
						),
						'empresa_url'       => array(
							'type'        => 'string',
							'description' => __( 'Company website URL', 'reforestamos-core' ),
						),
						'empresa_categoria' => array(
							'type'        => 'string',
							'description' => __( 'Company category', 'reforestamos-core' ),
						),
						'empresa_anio'      => array(
							'type'        => 'string',
							'description' => __( 'Year of collaboration', 'reforestamos-core' ),
						),
						'empresa_arboles'   => array(
							'type'        => 'string',
							'description' => __( 'Number of trees planted', 'reforestamos-core' ),
						),
					),
				),
			)
		);

		// Register Eventos custom fields
		register_rest_field(
			'eventos',
			'meta',
			array(
				'get_callback' => array( $this, 'get_eventos_meta' ),
				'schema'       => array(
					'description' => __( 'Custom fields for Eventos', 'reforestamos-core' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'evento_fecha'           => array(
							'type'        => 'string',
							'description' => __( 'Event date (ISO format)', 'reforestamos-core' ),
						),
						'evento_ubicacion'       => array(
							'type'        => 'string',
							'description' => __( 'Event location', 'reforestamos-core' ),
						),
						'evento_lat'             => array(
							'type'        => 'string',
							'description' => __( 'Event latitude', 'reforestamos-core' ),
						),
						'evento_lng'             => array(
							'type'        => 'string',
							'description' => __( 'Event longitude', 'reforestamos-core' ),
						),
						'evento_capacidad'       => array(
							'type'        => 'string',
							'description' => __( 'Event capacity', 'reforestamos-core' ),
						),
						'evento_registro_activo' => array(
							'type'        => 'string',
							'description' => __( 'Registration active status', 'reforestamos-core' ),
						),
					),
				),
			)
		);

		// Register Integrantes custom fields
		register_rest_field(
			'integrantes',
			'meta',
			array(
				'get_callback' => array( $this, 'get_integrantes_meta' ),
				'schema'       => array(
					'description' => __( 'Custom fields for Integrantes', 'reforestamos-core' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'integrante_cargo' => array(
							'type'        => 'string',
							'description' => __( 'Team member position', 'reforestamos-core' ),
						),
						'integrante_email' => array(
							'type'        => 'string',
							'description' => __( 'Team member email', 'reforestamos-core' ),
						),
						'integrante_redes' => array(
							'type'        => 'array',
							'description' => __( 'Social media links', 'reforestamos-core' ),
						),
					),
				),
			)
		);

		// Register Boletín custom fields
		register_rest_field(
			'boletin',
			'meta',
			array(
				'get_callback' => array( $this, 'get_boletin_meta' ),
				'schema'       => array(
					'description' => __( 'Custom fields for Boletín', 'reforestamos-core' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
					'properties'  => array(
						'boletin_fecha_envio'    => array(
							'type'        => 'string',
							'description' => __( 'Send date (ISO format)', 'reforestamos-core' ),
						),
						'boletin_estado'         => array(
							'type'        => 'string',
							'description' => __( 'Newsletter status', 'reforestamos-core' ),
						),
						'boletin_destinatarios'  => array(
							'type'        => 'string',
							'description' => __( 'Number of recipients', 'reforestamos-core' ),
						),
					),
				),
			)
		);
	}

	/**
	 * Get Empresas custom fields
	 *
	 * @param array $post Post data.
	 * @return array Custom fields data.
	 */
	public function get_empresas_meta( $post ) {
		$post_id = $post['id'];
		
		// Get logo URL (handle both attachment ID and URL)
		$logo = get_post_meta( $post_id, 'empresa_logo', true );
		if ( is_numeric( $logo ) ) {
			$logo = wp_get_attachment_url( $logo );
		}

		return array(
			'empresa_logo'      => $logo ? esc_url( $logo ) : '',
			'empresa_url'       => esc_url( get_post_meta( $post_id, 'empresa_url', true ) ),
			'empresa_categoria' => sanitize_text_field( get_post_meta( $post_id, 'empresa_categoria', true ) ),
			'empresa_anio'      => sanitize_text_field( get_post_meta( $post_id, 'empresa_anio', true ) ),
			'empresa_arboles'   => sanitize_text_field( get_post_meta( $post_id, 'empresa_arboles', true ) ),
		);
	}

	/**
	 * Get Eventos custom fields
	 *
	 * @param array $post Post data.
	 * @return array Custom fields data.
	 */
	public function get_eventos_meta( $post ) {
		$post_id = $post['id'];
		
		// Get fecha and convert to ISO format if it's a timestamp
		$fecha = get_post_meta( $post_id, 'evento_fecha', true );
		if ( is_numeric( $fecha ) ) {
			$fecha = gmdate( 'c', $fecha );
		}

		return array(
			'evento_fecha'           => $fecha,
			'evento_ubicacion'       => sanitize_text_field( get_post_meta( $post_id, 'evento_ubicacion', true ) ),
			'evento_lat'             => sanitize_text_field( get_post_meta( $post_id, 'evento_lat', true ) ),
			'evento_lng'             => sanitize_text_field( get_post_meta( $post_id, 'evento_lng', true ) ),
			'evento_capacidad'       => sanitize_text_field( get_post_meta( $post_id, 'evento_capacidad', true ) ),
			'evento_registro_activo' => sanitize_text_field( get_post_meta( $post_id, 'evento_registro_activo', true ) ),
		);
	}

	/**
	 * Get Integrantes custom fields
	 *
	 * @param array $post Post data.
	 * @return array Custom fields data.
	 */
	public function get_integrantes_meta( $post ) {
		$post_id = $post['id'];
		
		// Get redes (social media) - may be serialized array
		$redes = get_post_meta( $post_id, 'integrante_redes', true );
		if ( is_string( $redes ) ) {
			$redes = maybe_unserialize( $redes );
		}
		if ( ! is_array( $redes ) ) {
			$redes = array();
		}

		return array(
			'integrante_cargo' => sanitize_text_field( get_post_meta( $post_id, 'integrante_cargo', true ) ),
			'integrante_email' => sanitize_email( get_post_meta( $post_id, 'integrante_email', true ) ),
			'integrante_redes' => $redes,
		);
	}

	/**
	 * Get Boletín custom fields
	 *
	 * @param array $post Post data.
	 * @return array Custom fields data.
	 */
	public function get_boletin_meta( $post ) {
		$post_id = $post['id'];
		
		// Get fecha_envio and convert to ISO format if it's a timestamp
		$fecha_envio = get_post_meta( $post_id, 'boletin_fecha_envio', true );
		if ( is_numeric( $fecha_envio ) ) {
			$fecha_envio = gmdate( 'c', $fecha_envio );
		}

		return array(
			'boletin_fecha_envio'   => $fecha_envio,
			'boletin_estado'        => sanitize_text_field( get_post_meta( $post_id, 'boletin_estado', true ) ),
			'boletin_destinatarios' => sanitize_text_field( get_post_meta( $post_id, 'boletin_destinatarios', true ) ),
		);
	}

	/**
	 * Get empresas with filters
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error Response object or error.
	 */
	public function get_empresas( $request ) {
		$page     = $request->get_param( 'page' );
		$per_page = $request->get_param( 'per_page' );

		$args = array(
			'post_type'      => 'empresas',
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		// Build meta_query for filters
		$meta_query = array();

		// Filter by categoria
		if ( $categoria = $request->get_param( 'categoria' ) ) {
			$meta_query[] = array(
				'key'     => 'empresa_categoria',
				'value'   => $categoria,
				'compare' => '=',
			);
		}

		// Filter by anio
		if ( $anio = $request->get_param( 'anio' ) ) {
			$meta_query[] = array(
				'key'     => 'empresa_anio',
				'value'   => $anio,
				'compare' => '=',
			);
		}

		// Filter by arboles_min
		if ( $arboles_min = $request->get_param( 'arboles_min' ) ) {
			$meta_query[] = array(
				'key'     => 'empresa_arboles',
				'value'   => $arboles_min,
				'compare' => '>=',
				'type'    => 'NUMERIC',
			);
		}

		// Add meta_query to args if we have filters
		if ( ! empty( $meta_query ) ) {
			$args['meta_query'] = $meta_query;
		}

		$query    = new WP_Query( $args );
		$empresas = array();

		foreach ( $query->posts as $post ) {
			$logo = get_post_meta( $post->ID, 'empresa_logo', true );
			if ( is_numeric( $logo ) ) {
				$logo = wp_get_attachment_url( $logo );
			}

			$empresas[] = array(
				'id'      => $post->ID,
				'title'   => $post->post_title,
				'content' => $post->post_content,
				'excerpt' => $post->post_excerpt,
				'link'    => get_permalink( $post->ID ),
				'meta'    => array(
					'empresa_logo'      => $logo ? esc_url( $logo ) : '',
					'empresa_url'       => esc_url( get_post_meta( $post->ID, 'empresa_url', true ) ),
					'empresa_categoria' => sanitize_text_field( get_post_meta( $post->ID, 'empresa_categoria', true ) ),
					'empresa_anio'      => sanitize_text_field( get_post_meta( $post->ID, 'empresa_anio', true ) ),
					'empresa_arboles'   => sanitize_text_field( get_post_meta( $post->ID, 'empresa_arboles', true ) ),
				),
			);
		}

		$response = rest_ensure_response( $empresas );

		// Add pagination headers
		$response->header( 'X-WP-Total', $query->found_posts );
		$response->header( 'X-WP-TotalPages', $query->max_num_pages );

		return $response;
	}

	/**
	 * Get upcoming eventos
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error Response object or error.
	 */
	public function get_upcoming_eventos( $request ) {
		$page     = $request->get_param( 'page' );
		$per_page = $request->get_param( 'per_page' );

		$args = array(
			'post_type'      => 'eventos',
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'post_status'    => 'publish',
			'meta_key'       => 'evento_fecha',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'evento_fecha',
					'value'   => current_time( 'timestamp' ),
					'compare' => '>=',
					'type'    => 'NUMERIC',
				),
			),
		);

		// Filter by ubicacion
		if ( $ubicacion = $request->get_param( 'ubicacion' ) ) {
			$args['meta_query'][] = array(
				'key'     => 'evento_ubicacion',
				'value'   => $ubicacion,
				'compare' => 'LIKE',
			);
		}

		// Filter by tipo (taxonomy)
		if ( $tipo = $request->get_param( 'tipo' ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'tipo_evento',
					'field'    => 'slug',
					'terms'    => $tipo,
				),
			);
		}

		$query   = new WP_Query( $args );
		$eventos = array();

		foreach ( $query->posts as $post ) {
			$fecha = get_post_meta( $post->ID, 'evento_fecha', true );
			if ( is_numeric( $fecha ) ) {
				$fecha = gmdate( 'c', $fecha );
			}

			$eventos[] = array(
				'id'        => $post->ID,
				'title'     => $post->post_title,
				'content'   => $post->post_content,
				'excerpt'   => $post->post_excerpt,
				'link'      => get_permalink( $post->ID ),
				'thumbnail' => get_the_post_thumbnail_url( $post->ID, 'medium' ),
				'meta'      => array(
					'evento_fecha'           => $fecha,
					'evento_ubicacion'       => sanitize_text_field( get_post_meta( $post->ID, 'evento_ubicacion', true ) ),
					'evento_lat'             => sanitize_text_field( get_post_meta( $post->ID, 'evento_lat', true ) ),
					'evento_lng'             => sanitize_text_field( get_post_meta( $post->ID, 'evento_lng', true ) ),
					'evento_capacidad'       => sanitize_text_field( get_post_meta( $post->ID, 'evento_capacidad', true ) ),
					'evento_registro_activo' => sanitize_text_field( get_post_meta( $post->ID, 'evento_registro_activo', true ) ),
				),
			);
		}

		$response = rest_ensure_response( $eventos );

		// Add pagination headers
		$response->header( 'X-WP-Total', $query->found_posts );
		$response->header( 'X-WP-TotalPages', $query->max_num_pages );

		return $response;
	}

	/**
	 * Get integrantes
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error Response object or error.
	 */
	public function get_integrantes( $request ) {
		$page     = $request->get_param( 'page' );
		$per_page = $request->get_param( 'per_page' );

		$args = array(
			'post_type'      => 'integrantes',
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);

		// Filter by area (taxonomy)
		if ( $area = $request->get_param( 'area' ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'area_integrante',
					'field'    => 'slug',
					'terms'    => $area,
				),
			);
		}

		$query       = new WP_Query( $args );
		$integrantes = array();

		foreach ( $query->posts as $post ) {
			$redes = get_post_meta( $post->ID, 'integrante_redes', true );
			if ( is_string( $redes ) ) {
				$redes = maybe_unserialize( $redes );
			}
			if ( ! is_array( $redes ) ) {
				$redes = array();
			}

			$integrantes[] = array(
				'id'      => $post->ID,
				'name'    => $post->post_title,
				'content' => $post->post_content,
				'excerpt' => $post->post_excerpt,
				'link'    => get_permalink( $post->ID ),
				'photo'   => get_the_post_thumbnail_url( $post->ID, 'medium' ),
				'meta'    => array(
					'integrante_cargo' => sanitize_text_field( get_post_meta( $post->ID, 'integrante_cargo', true ) ),
					'integrante_email' => sanitize_email( get_post_meta( $post->ID, 'integrante_email', true ) ),
					'integrante_redes' => $redes,
				),
			);
		}

		$response = rest_ensure_response( $integrantes );

		// Add pagination headers
		$response->header( 'X-WP-Total', $query->found_posts );
		$response->header( 'X-WP-TotalPages', $query->max_num_pages );

		return $response;
	}
}
