<?php
/**
 * Custom Fields Registration using CMB2
 *
 * This class handles the registration of custom metaboxes and fields
 * for all Custom Post Types using the CMB2 library.
 *
 * @package Reforestamos_Core
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Core_Custom_Fields class
 *
 * Manages custom fields for all Custom Post Types using CMB2.
 * Implements singleton pattern for consistent initialization.
 */
class Reforestamos_Core_Custom_Fields {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Core_Custom_Fields
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Core_Custom_Fields
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
	 * Initializes CMB2 and registers hooks.
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Initialize the custom fields functionality
	 *
	 * Loads CMB2 library and hooks into cmb2_admin_init action.
	 */
	public function init() {
		// Load CMB2 library
		$this->load_cmb2();

		// Register metaboxes when CMB2 is ready
		add_action( 'cmb2_admin_init', array( $this, 'register_metaboxes' ) );
	}

	/**
	 * Load CMB2 library
	 *
	 * Checks if CMB2 is already loaded (as a plugin or by another source).
	 * If not, loads it from the lib directory.
	 */
	private function load_cmb2() {
		// Check if CMB2 is already loaded
		if ( ! class_exists( 'CMB2' ) ) {
			// Try to load from lib directory
			$cmb2_init = REFORESTAMOS_CORE_PATH . 'lib/cmb2/init.php';
			
			if ( file_exists( $cmb2_init ) ) {
				require_once $cmb2_init;
			} else {
				// Log error if CMB2 is not found
				add_action( 'admin_notices', array( $this, 'cmb2_missing_notice' ) );
			}
		}
	}

	/**
	 * Display admin notice if CMB2 is missing
	 *
	 * Shows an error message in the WordPress admin if CMB2 library
	 * cannot be found.
	 */
	public function cmb2_missing_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php
				esc_html_e(
					'Reforestamos Core: CMB2 library not found. Please run "composer install" in the plugin directory.',
					'reforestamos-core'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Register all metaboxes
	 *
	 * Calls individual metabox registration methods for each Custom Post Type.
	 * This method is hooked into 'cmb2_admin_init'.
	 */
	public function register_metaboxes() {
		// Register metaboxes for each CPT
		$this->register_empresas_metabox();
		$this->register_eventos_metabox();
		$this->register_integrantes_metabox();
		$this->register_boletin_metabox();
	}

	/**
	 * Register Empresas metabox
	 *
	 * Creates custom fields for the Empresas Custom Post Type:
	 * - Logo (image upload)
	 * - Website URL
	 * - Category
	 * - Year of collaboration
	 * - Number of trees planted
	 *
	 * @return void
	 */
	private function register_empresas_metabox() {
		$cmb = new_cmb2_box(
			array(
				'id'           => 'empresas_metabox',
				'title'        => __( 'Información de la Empresa', 'reforestamos-core' ),
				'object_types' => array( 'empresas' ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name'         => __( 'Logo', 'reforestamos-core' ),
				'desc'         => __( 'Sube el logo de la empresa', 'reforestamos-core' ),
				'id'           => 'empresa_logo',
				'type'         => 'file',
				'options'      => array(
					'url' => false,
				),
				'text'         => array(
					'add_upload_file_text' => __( 'Añadir Logo', 'reforestamos-core' ),
				),
				'query_args'   => array(
					'type' => 'image',
				),
				'preview_size' => 'medium',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'URL del Sitio Web', 'reforestamos-core' ),
				'desc' => __( 'Ingresa la URL completa del sitio web de la empresa', 'reforestamos-core' ),
				'id'   => 'empresa_url',
				'type' => 'text_url',
			)
		);

		$cmb->add_field(
			array(
				'name'    => __( 'Categoría', 'reforestamos-core' ),
				'desc'    => __( 'Selecciona el tipo de empresa', 'reforestamos-core' ),
				'id'      => 'empresa_categoria',
				'type'    => 'select',
				'options' => array(
					'corporativo' => __( 'Corporativo', 'reforestamos-core' ),
					'pyme'        => __( 'PyME', 'reforestamos-core' ),
					'gobierno'    => __( 'Gobierno', 'reforestamos-core' ),
					'ong'         => __( 'ONG', 'reforestamos-core' ),
				),
			)
		);

		$cmb->add_field(
			array(
				'name'       => __( 'Año de Colaboración', 'reforestamos-core' ),
				'desc'       => __( 'Año en que inició la colaboración', 'reforestamos-core' ),
				'id'         => 'empresa_anio',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d{4}',
					'min'     => '2000',
					'max'     => date( 'Y' ),
				),
			)
		);

		$cmb->add_field(
			array(
				'name'       => __( 'Árboles Plantados', 'reforestamos-core' ),
				'desc'       => __( 'Número total de árboles plantados por la empresa', 'reforestamos-core' ),
				'id'         => 'empresa_arboles',
				'type'       => 'text',
				'attributes' => array(
					'type' => 'number',
					'min'  => '0',
				),
			)
		);
	}

	/**
	 * Register Eventos metabox
	 *
	 * Creates custom fields for the Eventos Custom Post Type:
	 * - Event date and time
	 * - Location
	 * - Coordinates (latitude/longitude)
	 * - Capacity
	 * - Registration status
	 *
	 * @return void
	 */
	private function register_eventos_metabox() {
		$cmb = new_cmb2_box(
			array(
				'id'           => 'eventos_metabox',
				'title'        => __( 'Detalles del Evento', 'reforestamos-core' ),
				'object_types' => array( 'eventos' ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Fecha del Evento', 'reforestamos-core' ),
				'desc' => __( 'Selecciona la fecha y hora del evento', 'reforestamos-core' ),
				'id'   => 'evento_fecha',
				'type' => 'text_datetime_timestamp',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Ubicación', 'reforestamos-core' ),
				'desc' => __( 'Dirección o nombre del lugar del evento', 'reforestamos-core' ),
				'id'   => 'evento_ubicacion',
				'type' => 'text',
			)
		);

		$cmb->add_field(
			array(
				'name'       => __( 'Coordenadas (Latitud)', 'reforestamos-core' ),
				'desc'       => __( 'Latitud para mostrar en el mapa', 'reforestamos-core' ),
				'id'         => 'evento_lat',
				'type'       => 'text_small',
				'attributes' => array(
					'type' => 'number',
					'step' => 'any',
				),
			)
		);

		$cmb->add_field(
			array(
				'name'       => __( 'Coordenadas (Longitud)', 'reforestamos-core' ),
				'desc'       => __( 'Longitud para mostrar en el mapa', 'reforestamos-core' ),
				'id'         => 'evento_lng',
				'type'       => 'text_small',
				'attributes' => array(
					'type' => 'number',
					'step' => 'any',
				),
			)
		);

		$cmb->add_field(
			array(
				'name'       => __( 'Capacidad', 'reforestamos-core' ),
				'desc'       => __( 'Número máximo de participantes', 'reforestamos-core' ),
				'id'         => 'evento_capacidad',
				'type'       => 'text',
				'attributes' => array(
					'type' => 'number',
					'min'  => '0',
				),
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Registro Activo', 'reforestamos-core' ),
				'desc' => __( 'Marcar si el registro está abierto', 'reforestamos-core' ),
				'id'   => 'evento_registro_activo',
				'type' => 'checkbox',
			)
		);
	}

	/**
	 * Register Integrantes metabox
	 *
	 * Creates custom fields for the Integrantes Custom Post Type:
	 * - Position/role
	 * - Email
	 * - Social media links (repeatable group)
	 *
	 * @return void
	 */
	private function register_integrantes_metabox() {
		$cmb = new_cmb2_box(
			array(
				'id'           => 'integrantes_metabox',
				'title'        => __( 'Información del Integrante', 'reforestamos-core' ),
				'object_types' => array( 'integrantes' ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Cargo', 'reforestamos-core' ),
				'desc' => __( 'Puesto o rol en la organización', 'reforestamos-core' ),
				'id'   => 'integrante_cargo',
				'type' => 'text',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Email', 'reforestamos-core' ),
				'desc' => __( 'Correo electrónico de contacto', 'reforestamos-core' ),
				'id'   => 'integrante_email',
				'type' => 'text_email',
			)
		);

		$group_field_id = $cmb->add_field(
			array(
				'id'          => 'integrante_redes',
				'type'        => 'group',
				'description' => __( 'Redes sociales del integrante', 'reforestamos-core' ),
				'options'     => array(
					'group_title'   => __( 'Red Social {#}', 'reforestamos-core' ),
					'add_button'    => __( 'Añadir Red Social', 'reforestamos-core' ),
					'remove_button' => __( 'Eliminar', 'reforestamos-core' ),
					'sortable'      => true,
				),
			)
		);

		$cmb->add_group_field(
			$group_field_id,
			array(
				'name'    => __( 'Plataforma', 'reforestamos-core' ),
				'id'      => 'plataforma',
				'type'    => 'select',
				'options' => array(
					'twitter'   => 'Twitter',
					'linkedin'  => 'LinkedIn',
					'facebook'  => 'Facebook',
					'instagram' => 'Instagram',
				),
			)
		);

		$cmb->add_group_field(
			$group_field_id,
			array(
				'name' => __( 'URL', 'reforestamos-core' ),
				'id'   => 'url',
				'type' => 'text_url',
			)
		);
	}

	/**
	 * Register Boletín metabox
	 *
	 * Creates custom fields for the Boletín Custom Post Type:
	 * - Send date
	 * - Status (draft/scheduled/sent)
	 * - Number of recipients (read-only)
	 *
	 * @return void
	 */
	private function register_boletin_metabox() {
		$cmb = new_cmb2_box(
			array(
				'id'           => 'boletin_metabox',
				'title'        => __( 'Configuración del Boletín', 'reforestamos-core' ),
				'object_types' => array( 'boletin' ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Fecha de Envío', 'reforestamos-core' ),
				'desc' => __( 'Fecha y hora programada para el envío', 'reforestamos-core' ),
				'id'   => 'boletin_fecha_envio',
				'type' => 'text_datetime_timestamp',
			)
		);

		$cmb->add_field(
			array(
				'name'    => __( 'Estado', 'reforestamos-core' ),
				'desc'    => __( 'Estado actual del boletín', 'reforestamos-core' ),
				'id'      => 'boletin_estado',
				'type'    => 'select',
				'default' => 'draft',
				'options' => array(
					'draft'     => __( 'Borrador', 'reforestamos-core' ),
					'scheduled' => __( 'Programado', 'reforestamos-core' ),
					'sent'      => __( 'Enviado', 'reforestamos-core' ),
				),
			)
		);

		$cmb->add_field(
			array(
				'name'       => __( 'Destinatarios', 'reforestamos-core' ),
				'desc'       => __( 'Número de destinatarios (se actualiza automáticamente)', 'reforestamos-core' ),
				'id'         => 'boletin_destinatarios',
				'type'       => 'text',
				'attributes' => array(
					'readonly' => 'readonly',
					'disabled' => 'disabled',
				),
			)
		);
	}
}

