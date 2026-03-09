<?php
/**
 * Custom Post Types Registration
 *
 * Handles registration of all Custom Post Types for the Reforestamos Core plugin.
 * Includes: Empresas, Eventos, Integrantes, and Boletín.
 *
 * @package Reforestamos_Core
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Core_Post_Types class
 *
 * Singleton class that registers all Custom Post Types.
 */
class Reforestamos_Core_Post_Types {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Core_Post_Types
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Core_Post_Types
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
		$this->init();
	}

	/**
	 * Initialize hooks
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_post_types' ) );
	}

	/**
	 * Register all Custom Post Types
	 */
	public function register_post_types() {
		$this->register_empresas();
		$this->register_eventos();
		$this->register_integrantes();
		$this->register_boletin();
	}

	/**
	 * Register Empresas Custom Post Type
	 *
	 * Partner companies and collaborators.
	 */
	private function register_empresas() {
		$labels = array(
			'name'                  => _x( 'Empresas', 'Post Type General Name', 'reforestamos-core' ),
			'singular_name'         => _x( 'Empresa', 'Post Type Singular Name', 'reforestamos-core' ),
			'menu_name'             => __( 'Empresas', 'reforestamos-core' ),
			'name_admin_bar'        => __( 'Empresa', 'reforestamos-core' ),
			'archives'              => __( 'Archivo de Empresas', 'reforestamos-core' ),
			'attributes'            => __( 'Atributos de Empresa', 'reforestamos-core' ),
			'parent_item_colon'     => __( 'Empresa Padre:', 'reforestamos-core' ),
			'all_items'             => __( 'Todas las Empresas', 'reforestamos-core' ),
			'add_new_item'          => __( 'Añadir Nueva Empresa', 'reforestamos-core' ),
			'add_new'               => __( 'Añadir Nueva', 'reforestamos-core' ),
			'new_item'              => __( 'Nueva Empresa', 'reforestamos-core' ),
			'edit_item'             => __( 'Editar Empresa', 'reforestamos-core' ),
			'update_item'           => __( 'Actualizar Empresa', 'reforestamos-core' ),
			'view_item'             => __( 'Ver Empresa', 'reforestamos-core' ),
			'view_items'            => __( 'Ver Empresas', 'reforestamos-core' ),
			'search_items'          => __( 'Buscar Empresa', 'reforestamos-core' ),
			'not_found'             => __( 'No se encontraron empresas', 'reforestamos-core' ),
			'not_found_in_trash'    => __( 'No se encontraron empresas en la papelera', 'reforestamos-core' ),
			'featured_image'        => __( 'Logo de Empresa', 'reforestamos-core' ),
			'set_featured_image'    => __( 'Establecer logo de empresa', 'reforestamos-core' ),
			'remove_featured_image' => __( 'Eliminar logo de empresa', 'reforestamos-core' ),
			'use_featured_image'    => __( 'Usar como logo de empresa', 'reforestamos-core' ),
			'insert_into_item'      => __( 'Insertar en empresa', 'reforestamos-core' ),
			'uploaded_to_this_item' => __( 'Subido a esta empresa', 'reforestamos-core' ),
			'items_list'            => __( 'Lista de empresas', 'reforestamos-core' ),
			'items_list_navigation' => __( 'Navegación de lista de empresas', 'reforestamos-core' ),
			'filter_items_list'     => __( 'Filtrar lista de empresas', 'reforestamos-core' ),
		);

		$args = array(
			'label'               => __( 'Empresa', 'reforestamos-core' ),
			'description'         => __( 'Empresas colaboradoras y aliadas', 'reforestamos-core' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-building',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rest_base'           => 'empresas',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rewrite'             => array(
				'slug'       => 'empresas',
				'with_front' => false,
			),
		);

		register_post_type( 'empresas', $args );
	}

	/**
	 * Register Eventos Custom Post Type
	 *
	 * Reforestation events and activities.
	 */
	private function register_eventos() {
		$labels = array(
			'name'                  => _x( 'Eventos', 'Post Type General Name', 'reforestamos-core' ),
			'singular_name'         => _x( 'Evento', 'Post Type Singular Name', 'reforestamos-core' ),
			'menu_name'             => __( 'Eventos', 'reforestamos-core' ),
			'name_admin_bar'        => __( 'Evento', 'reforestamos-core' ),
			'archives'              => __( 'Archivo de Eventos', 'reforestamos-core' ),
			'attributes'            => __( 'Atributos de Evento', 'reforestamos-core' ),
			'parent_item_colon'     => __( 'Evento Padre:', 'reforestamos-core' ),
			'all_items'             => __( 'Todos los Eventos', 'reforestamos-core' ),
			'add_new_item'          => __( 'Añadir Nuevo Evento', 'reforestamos-core' ),
			'add_new'               => __( 'Añadir Nuevo', 'reforestamos-core' ),
			'new_item'              => __( 'Nuevo Evento', 'reforestamos-core' ),
			'edit_item'             => __( 'Editar Evento', 'reforestamos-core' ),
			'update_item'           => __( 'Actualizar Evento', 'reforestamos-core' ),
			'view_item'             => __( 'Ver Evento', 'reforestamos-core' ),
			'view_items'            => __( 'Ver Eventos', 'reforestamos-core' ),
			'search_items'          => __( 'Buscar Evento', 'reforestamos-core' ),
			'not_found'             => __( 'No se encontraron eventos', 'reforestamos-core' ),
			'not_found_in_trash'    => __( 'No se encontraron eventos en la papelera', 'reforestamos-core' ),
			'featured_image'        => __( 'Imagen del Evento', 'reforestamos-core' ),
			'set_featured_image'    => __( 'Establecer imagen del evento', 'reforestamos-core' ),
			'remove_featured_image' => __( 'Eliminar imagen del evento', 'reforestamos-core' ),
			'use_featured_image'    => __( 'Usar como imagen del evento', 'reforestamos-core' ),
			'insert_into_item'      => __( 'Insertar en evento', 'reforestamos-core' ),
			'uploaded_to_this_item' => __( 'Subido a este evento', 'reforestamos-core' ),
			'items_list'            => __( 'Lista de eventos', 'reforestamos-core' ),
			'items_list_navigation' => __( 'Navegación de lista de eventos', 'reforestamos-core' ),
			'filter_items_list'     => __( 'Filtrar lista de eventos', 'reforestamos-core' ),
		);

		$args = array(
			'label'               => __( 'Evento', 'reforestamos-core' ),
			'description'         => __( 'Eventos de reforestación y actividades', 'reforestamos-core' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 21,
			'menu_icon'           => 'dashicons-calendar-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rest_base'           => 'eventos',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rewrite'             => array(
				'slug'       => 'eventos',
				'with_front' => false,
			),
		);

		register_post_type( 'eventos', $args );
	}

	/**
	 * Register Integrantes Custom Post Type
	 *
	 * Team member profiles.
	 */
	private function register_integrantes() {
		$labels = array(
			'name'                  => _x( 'Integrantes', 'Post Type General Name', 'reforestamos-core' ),
			'singular_name'         => _x( 'Integrante', 'Post Type Singular Name', 'reforestamos-core' ),
			'menu_name'             => __( 'Integrantes', 'reforestamos-core' ),
			'name_admin_bar'        => __( 'Integrante', 'reforestamos-core' ),
			'archives'              => __( 'Archivo de Integrantes', 'reforestamos-core' ),
			'attributes'            => __( 'Atributos de Integrante', 'reforestamos-core' ),
			'parent_item_colon'     => __( 'Integrante Padre:', 'reforestamos-core' ),
			'all_items'             => __( 'Todos los Integrantes', 'reforestamos-core' ),
			'add_new_item'          => __( 'Añadir Nuevo Integrante', 'reforestamos-core' ),
			'add_new'               => __( 'Añadir Nuevo', 'reforestamos-core' ),
			'new_item'              => __( 'Nuevo Integrante', 'reforestamos-core' ),
			'edit_item'             => __( 'Editar Integrante', 'reforestamos-core' ),
			'update_item'           => __( 'Actualizar Integrante', 'reforestamos-core' ),
			'view_item'             => __( 'Ver Integrante', 'reforestamos-core' ),
			'view_items'            => __( 'Ver Integrantes', 'reforestamos-core' ),
			'search_items'          => __( 'Buscar Integrante', 'reforestamos-core' ),
			'not_found'             => __( 'No se encontraron integrantes', 'reforestamos-core' ),
			'not_found_in_trash'    => __( 'No se encontraron integrantes en la papelera', 'reforestamos-core' ),
			'featured_image'        => __( 'Foto del Integrante', 'reforestamos-core' ),
			'set_featured_image'    => __( 'Establecer foto del integrante', 'reforestamos-core' ),
			'remove_featured_image' => __( 'Eliminar foto del integrante', 'reforestamos-core' ),
			'use_featured_image'    => __( 'Usar como foto del integrante', 'reforestamos-core' ),
			'insert_into_item'      => __( 'Insertar en integrante', 'reforestamos-core' ),
			'uploaded_to_this_item' => __( 'Subido a este integrante', 'reforestamos-core' ),
			'items_list'            => __( 'Lista de integrantes', 'reforestamos-core' ),
			'items_list_navigation' => __( 'Navegación de lista de integrantes', 'reforestamos-core' ),
			'filter_items_list'     => __( 'Filtrar lista de integrantes', 'reforestamos-core' ),
		);

		$args = array(
			'label'               => __( 'Integrante', 'reforestamos-core' ),
			'description'         => __( 'Perfiles de integrantes del equipo', 'reforestamos-core' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 22,
			'menu_icon'           => 'dashicons-groups',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rest_base'           => 'integrantes',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rewrite'             => array(
				'slug'       => 'integrantes',
				'with_front' => false,
			),
		);

		register_post_type( 'integrantes', $args );
	}

	/**
	 * Register Boletín Custom Post Type
	 *
	 * Newsletter content and campaigns (admin only).
	 */
	private function register_boletin() {
		$labels = array(
			'name'                  => _x( 'Boletines', 'Post Type General Name', 'reforestamos-core' ),
			'singular_name'         => _x( 'Boletín', 'Post Type Singular Name', 'reforestamos-core' ),
			'menu_name'             => __( 'Boletines', 'reforestamos-core' ),
			'name_admin_bar'        => __( 'Boletín', 'reforestamos-core' ),
			'archives'              => __( 'Archivo de Boletines', 'reforestamos-core' ),
			'attributes'            => __( 'Atributos de Boletín', 'reforestamos-core' ),
			'parent_item_colon'     => __( 'Boletín Padre:', 'reforestamos-core' ),
			'all_items'             => __( 'Todos los Boletines', 'reforestamos-core' ),
			'add_new_item'          => __( 'Añadir Nuevo Boletín', 'reforestamos-core' ),
			'add_new'               => __( 'Añadir Nuevo', 'reforestamos-core' ),
			'new_item'              => __( 'Nuevo Boletín', 'reforestamos-core' ),
			'edit_item'             => __( 'Editar Boletín', 'reforestamos-core' ),
			'update_item'           => __( 'Actualizar Boletín', 'reforestamos-core' ),
			'view_item'             => __( 'Ver Boletín', 'reforestamos-core' ),
			'view_items'            => __( 'Ver Boletines', 'reforestamos-core' ),
			'search_items'          => __( 'Buscar Boletín', 'reforestamos-core' ),
			'not_found'             => __( 'No se encontraron boletines', 'reforestamos-core' ),
			'not_found_in_trash'    => __( 'No se encontraron boletines en la papelera', 'reforestamos-core' ),
			'featured_image'        => __( 'Imagen del Boletín', 'reforestamos-core' ),
			'set_featured_image'    => __( 'Establecer imagen del boletín', 'reforestamos-core' ),
			'remove_featured_image' => __( 'Eliminar imagen del boletín', 'reforestamos-core' ),
			'use_featured_image'    => __( 'Usar como imagen del boletín', 'reforestamos-core' ),
			'insert_into_item'      => __( 'Insertar en boletín', 'reforestamos-core' ),
			'uploaded_to_this_item' => __( 'Subido a este boletín', 'reforestamos-core' ),
			'items_list'            => __( 'Lista de boletines', 'reforestamos-core' ),
			'items_list_navigation' => __( 'Navegación de lista de boletines', 'reforestamos-core' ),
			'filter_items_list'     => __( 'Filtrar lista de boletines', 'reforestamos-core' ),
		);

		$args = array(
			'label'               => __( 'Boletín', 'reforestamos-core' ),
			'description'         => __( 'Contenido de boletines y campañas de newsletter', 'reforestamos-core' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 23,
			'menu_icon'           => 'dashicons-email-alt',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rest_base'           => 'boletin',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rewrite'             => array(
				'slug'       => 'boletin',
				'with_front' => false,
			),
		);

		register_post_type( 'boletin', $args );
	}
}
