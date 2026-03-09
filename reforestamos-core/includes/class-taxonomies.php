<?php
/**
 * Custom Taxonomies Registration
 *
 * Handles registration of all Custom Taxonomies for the Reforestamos Core plugin.
 * Includes taxonomies for Empresas, Eventos, Integrantes, and Boletín CPTs.
 *
 * @package Reforestamos_Core
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Core_Taxonomies class
 *
 * Singleton class that registers all Custom Taxonomies.
 */
class Reforestamos_Core_Taxonomies {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Core_Taxonomies
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Core_Taxonomies
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
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	/**
	 * Register all Custom Taxonomies
	 */
	public function register_taxonomies() {
		$this->register_empresa_categoria();
		$this->register_evento_tipo();
		$this->register_ubicacion();
		$this->register_area();
		$this->register_boletin_estado();
	}

	/**
	 * Register Categoría de Empresa Taxonomy
	 *
	 * Hierarchical taxonomy for categorizing companies by industry/partnership type.
	 * Associated with: Empresas CPT
	 */
	private function register_empresa_categoria() {
		$labels = array(
			'name'                       => _x( 'Categorías de Empresa', 'Taxonomy General Name', 'reforestamos-core' ),
			'singular_name'              => _x( 'Categoría de Empresa', 'Taxonomy Singular Name', 'reforestamos-core' ),
			'menu_name'                  => __( 'Categorías', 'reforestamos-core' ),
			'all_items'                  => __( 'Todas las Categorías', 'reforestamos-core' ),
			'parent_item'                => __( 'Categoría Padre', 'reforestamos-core' ),
			'parent_item_colon'          => __( 'Categoría Padre:', 'reforestamos-core' ),
			'new_item_name'              => __( 'Nueva Categoría', 'reforestamos-core' ),
			'add_new_item'               => __( 'Añadir Nueva Categoría', 'reforestamos-core' ),
			'edit_item'                  => __( 'Editar Categoría', 'reforestamos-core' ),
			'update_item'                => __( 'Actualizar Categoría', 'reforestamos-core' ),
			'view_item'                  => __( 'Ver Categoría', 'reforestamos-core' ),
			'separate_items_with_commas' => __( 'Separar categorías con comas', 'reforestamos-core' ),
			'add_or_remove_items'        => __( 'Añadir o eliminar categorías', 'reforestamos-core' ),
			'choose_from_most_used'      => __( 'Elegir de las más usadas', 'reforestamos-core' ),
			'popular_items'              => __( 'Categorías Populares', 'reforestamos-core' ),
			'search_items'               => __( 'Buscar Categorías', 'reforestamos-core' ),
			'not_found'                  => __( 'No se encontraron categorías', 'reforestamos-core' ),
			'no_terms'                   => __( 'No hay categorías', 'reforestamos-core' ),
			'items_list'                 => __( 'Lista de categorías', 'reforestamos-core' ),
			'items_list_navigation'      => __( 'Navegación de lista de categorías', 'reforestamos-core' ),
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'show_in_quick_edit'         => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'empresa-categoria',
			'rest_controller_class'      => 'WP_REST_Terms_Controller',
			'rewrite'                    => array(
				'slug'         => 'empresa-categoria',
				'with_front'   => false,
				'hierarchical' => true,
			),
		);

		register_taxonomy( 'empresa_categoria', array( 'empresas' ), $args );
	}

	/**
	 * Register Tipo de Evento Taxonomy
	 *
	 * Hierarchical taxonomy for categorizing events by type.
	 * Associated with: Eventos CPT
	 */
	private function register_evento_tipo() {
		$labels = array(
			'name'                       => _x( 'Tipos de Evento', 'Taxonomy General Name', 'reforestamos-core' ),
			'singular_name'              => _x( 'Tipo de Evento', 'Taxonomy Singular Name', 'reforestamos-core' ),
			'menu_name'                  => __( 'Tipos de Evento', 'reforestamos-core' ),
			'all_items'                  => __( 'Todos los Tipos', 'reforestamos-core' ),
			'parent_item'                => __( 'Tipo Padre', 'reforestamos-core' ),
			'parent_item_colon'          => __( 'Tipo Padre:', 'reforestamos-core' ),
			'new_item_name'              => __( 'Nuevo Tipo de Evento', 'reforestamos-core' ),
			'add_new_item'               => __( 'Añadir Nuevo Tipo', 'reforestamos-core' ),
			'edit_item'                  => __( 'Editar Tipo', 'reforestamos-core' ),
			'update_item'                => __( 'Actualizar Tipo', 'reforestamos-core' ),
			'view_item'                  => __( 'Ver Tipo', 'reforestamos-core' ),
			'separate_items_with_commas' => __( 'Separar tipos con comas', 'reforestamos-core' ),
			'add_or_remove_items'        => __( 'Añadir o eliminar tipos', 'reforestamos-core' ),
			'choose_from_most_used'      => __( 'Elegir de los más usados', 'reforestamos-core' ),
			'popular_items'              => __( 'Tipos Populares', 'reforestamos-core' ),
			'search_items'               => __( 'Buscar Tipos', 'reforestamos-core' ),
			'not_found'                  => __( 'No se encontraron tipos', 'reforestamos-core' ),
			'no_terms'                   => __( 'No hay tipos', 'reforestamos-core' ),
			'items_list'                 => __( 'Lista de tipos', 'reforestamos-core' ),
			'items_list_navigation'      => __( 'Navegación de lista de tipos', 'reforestamos-core' ),
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'show_in_quick_edit'         => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'evento-tipo',
			'rest_controller_class'      => 'WP_REST_Terms_Controller',
			'rewrite'                    => array(
				'slug'         => 'evento-tipo',
				'with_front'   => false,
				'hierarchical' => true,
			),
		);

		register_taxonomy( 'evento_tipo', array( 'eventos' ), $args );
	}

	/**
	 * Register Ubicación Taxonomy
	 *
	 * Hierarchical taxonomy for organizing events by location/region.
	 * Associated with: Eventos CPT
	 */
	private function register_ubicacion() {
		$labels = array(
			'name'                       => _x( 'Ubicaciones', 'Taxonomy General Name', 'reforestamos-core' ),
			'singular_name'              => _x( 'Ubicación', 'Taxonomy Singular Name', 'reforestamos-core' ),
			'menu_name'                  => __( 'Ubicaciones', 'reforestamos-core' ),
			'all_items'                  => __( 'Todas las Ubicaciones', 'reforestamos-core' ),
			'parent_item'                => __( 'Ubicación Padre', 'reforestamos-core' ),
			'parent_item_colon'          => __( 'Ubicación Padre:', 'reforestamos-core' ),
			'new_item_name'              => __( 'Nueva Ubicación', 'reforestamos-core' ),
			'add_new_item'               => __( 'Añadir Nueva Ubicación', 'reforestamos-core' ),
			'edit_item'                  => __( 'Editar Ubicación', 'reforestamos-core' ),
			'update_item'                => __( 'Actualizar Ubicación', 'reforestamos-core' ),
			'view_item'                  => __( 'Ver Ubicación', 'reforestamos-core' ),
			'separate_items_with_commas' => __( 'Separar ubicaciones con comas', 'reforestamos-core' ),
			'add_or_remove_items'        => __( 'Añadir o eliminar ubicaciones', 'reforestamos-core' ),
			'choose_from_most_used'      => __( 'Elegir de las más usadas', 'reforestamos-core' ),
			'popular_items'              => __( 'Ubicaciones Populares', 'reforestamos-core' ),
			'search_items'               => __( 'Buscar Ubicaciones', 'reforestamos-core' ),
			'not_found'                  => __( 'No se encontraron ubicaciones', 'reforestamos-core' ),
			'no_terms'                   => __( 'No hay ubicaciones', 'reforestamos-core' ),
			'items_list'                 => __( 'Lista de ubicaciones', 'reforestamos-core' ),
			'items_list_navigation'      => __( 'Navegación de lista de ubicaciones', 'reforestamos-core' ),
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'show_in_quick_edit'         => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'ubicacion',
			'rest_controller_class'      => 'WP_REST_Terms_Controller',
			'rewrite'                    => array(
				'slug'         => 'ubicacion',
				'with_front'   => false,
				'hierarchical' => true,
			),
		);

		register_taxonomy( 'ubicacion', array( 'eventos' ), $args );
	}

	/**
	 * Register Área Taxonomy
	 *
	 * Hierarchical taxonomy for organizing team members by department/area.
	 * Associated with: Integrantes CPT
	 */
	private function register_area() {
		$labels = array(
			'name'                       => _x( 'Áreas', 'Taxonomy General Name', 'reforestamos-core' ),
			'singular_name'              => _x( 'Área', 'Taxonomy Singular Name', 'reforestamos-core' ),
			'menu_name'                  => __( 'Áreas', 'reforestamos-core' ),
			'all_items'                  => __( 'Todas las Áreas', 'reforestamos-core' ),
			'parent_item'                => __( 'Área Padre', 'reforestamos-core' ),
			'parent_item_colon'          => __( 'Área Padre:', 'reforestamos-core' ),
			'new_item_name'              => __( 'Nueva Área', 'reforestamos-core' ),
			'add_new_item'               => __( 'Añadir Nueva Área', 'reforestamos-core' ),
			'edit_item'                  => __( 'Editar Área', 'reforestamos-core' ),
			'update_item'                => __( 'Actualizar Área', 'reforestamos-core' ),
			'view_item'                  => __( 'Ver Área', 'reforestamos-core' ),
			'separate_items_with_commas' => __( 'Separar áreas con comas', 'reforestamos-core' ),
			'add_or_remove_items'        => __( 'Añadir o eliminar áreas', 'reforestamos-core' ),
			'choose_from_most_used'      => __( 'Elegir de las más usadas', 'reforestamos-core' ),
			'popular_items'              => __( 'Áreas Populares', 'reforestamos-core' ),
			'search_items'               => __( 'Buscar Áreas', 'reforestamos-core' ),
			'not_found'                  => __( 'No se encontraron áreas', 'reforestamos-core' ),
			'no_terms'                   => __( 'No hay áreas', 'reforestamos-core' ),
			'items_list'                 => __( 'Lista de áreas', 'reforestamos-core' ),
			'items_list_navigation'      => __( 'Navegación de lista de áreas', 'reforestamos-core' ),
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => false,
			'show_in_quick_edit'         => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'area',
			'rest_controller_class'      => 'WP_REST_Terms_Controller',
			'rewrite'                    => array(
				'slug'         => 'area',
				'with_front'   => false,
				'hierarchical' => true,
			),
		);

		register_taxonomy( 'area', array( 'integrantes' ), $args );
	}

	/**
	 * Register Estado de Boletín Taxonomy
	 *
	 * Hierarchical taxonomy for tracking newsletter status.
	 * Associated with: Boletín CPT
	 */
	private function register_boletin_estado() {
		$labels = array(
			'name'                       => _x( 'Estados de Boletín', 'Taxonomy General Name', 'reforestamos-core' ),
			'singular_name'              => _x( 'Estado de Boletín', 'Taxonomy Singular Name', 'reforestamos-core' ),
			'menu_name'                  => __( 'Estados', 'reforestamos-core' ),
			'all_items'                  => __( 'Todos los Estados', 'reforestamos-core' ),
			'parent_item'                => __( 'Estado Padre', 'reforestamos-core' ),
			'parent_item_colon'          => __( 'Estado Padre:', 'reforestamos-core' ),
			'new_item_name'              => __( 'Nuevo Estado', 'reforestamos-core' ),
			'add_new_item'               => __( 'Añadir Nuevo Estado', 'reforestamos-core' ),
			'edit_item'                  => __( 'Editar Estado', 'reforestamos-core' ),
			'update_item'                => __( 'Actualizar Estado', 'reforestamos-core' ),
			'view_item'                  => __( 'Ver Estado', 'reforestamos-core' ),
			'separate_items_with_commas' => __( 'Separar estados con comas', 'reforestamos-core' ),
			'add_or_remove_items'        => __( 'Añadir o eliminar estados', 'reforestamos-core' ),
			'choose_from_most_used'      => __( 'Elegir de los más usados', 'reforestamos-core' ),
			'popular_items'              => __( 'Estados Populares', 'reforestamos-core' ),
			'search_items'               => __( 'Buscar Estados', 'reforestamos-core' ),
			'not_found'                  => __( 'No se encontraron estados', 'reforestamos-core' ),
			'no_terms'                   => __( 'No hay estados', 'reforestamos-core' ),
			'items_list'                 => __( 'Lista de estados', 'reforestamos-core' ),
			'items_list_navigation'      => __( 'Navegación de lista de estados', 'reforestamos-core' ),
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => false,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'show_in_quick_edit'         => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'boletin-estado',
			'rest_controller_class'      => 'WP_REST_Terms_Controller',
			'rewrite'                    => array(
				'slug'         => 'boletin-estado',
				'with_front'   => false,
				'hierarchical' => true,
			),
		);

		register_taxonomy( 'boletin_estado', array( 'boletin' ), $args );
	}
}
