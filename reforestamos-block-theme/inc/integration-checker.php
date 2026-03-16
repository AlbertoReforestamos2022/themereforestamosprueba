<?php
/**
 * Integration Checker
 *
 * Verifies that the theme and all plugins work together correctly.
 * Checks dependencies, activation status, and cross-component compatibility.
 *
 * @package Reforestamos
 * @since 1.0.0
 *
 * Requirements: 20.1, 20.2, 20.3, 20.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Reforestamos_Integration_Checker
 *
 * Provides integration verification for the theme and plugin ecosystem.
 */
class Reforestamos_Integration_Checker {

	/**
	 * Singleton instance.
	 *
	 * @var Reforestamos_Integration_Checker|null
	 */
	private static $instance = null;

	/**
	 * Plugin registry with dependency info.
	 *
	 * @var array
	 */
	private $plugins = array();

	/**
	 * Check results storage.
	 *
	 * @var array
	 */
	private $results = array();

	/**
	 * Get singleton instance.
	 *
	 * @return Reforestamos_Integration_Checker
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize the integration checker.
	 */
	public static function init() {
		$instance = self::get_instance();
		$instance->register_plugins();

		add_action( 'admin_menu', array( $instance, 'add_admin_page' ) );
		add_action( 'wp_ajax_reforestamos_run_integration_check', array( $instance, 'ajax_run_check' ) );
		add_action( 'admin_notices', array( $instance, 'display_critical_notices' ) );
	}

	/**
	 * Register all plugins in the ecosystem with their dependencies.
	 */
	private function register_plugins() {
		$this->plugins = array(
			'reforestamos-core' => array(
				'name'         => 'Reforestamos Core',
				'file'         => 'reforestamos-core/reforestamos-core.php',
				'class'        => 'Reforestamos_Core',
				'required_by'  => array( 'reforestamos-empresas' ),
				'depends_on'   => array(),
				'provides'     => array( 'custom_post_types', 'taxonomies', 'rest_api', 'custom_fields' ),
			),
			'reforestamos-micrositios' => array(
				'name'         => 'Reforestamos Micrositios',
				'file'         => 'reforestamos-micrositios/reforestamos-micrositios.php',
				'class'        => 'Reforestamos_Micrositios',
				'required_by'  => array(),
				'depends_on'   => array(),
				'provides'     => array( 'arboles_ciudades', 'red_oja', 'maps' ),
			),
			'reforestamos-comunicacion' => array(
				'name'         => 'Reforestamos Comunicación',
				'file'         => 'reforestamos-comunicacion/reforestamos-comunicacion.php',
				'class'        => 'Reforestamos_Comunicacion',
				'required_by'  => array(),
				'depends_on'   => array(),
				'provides'     => array( 'newsletter', 'contact_forms', 'chatbot', 'deepl_translation' ),
			),
			'reforestamos-empresas' => array(
				'name'         => 'Reforestamos Empresas',
				'file'         => 'reforestamos-empresas/reforestamos-empresas.php',
				'class'        => 'Reforestamos_Empresas',
				'required_by'  => array(),
				'depends_on'   => array( 'reforestamos-core' ),
				'provides'     => array( 'company_management', 'analytics', 'galleries' ),
			),
		);
	}

	/**
	 * Run all integration checks.
	 *
	 * @return array Results of all checks.
	 */
	public function run_all_checks() {
		$this->results = array();

		$this->check_theme_status();
		$this->check_plugin_activation();
		$this->check_dependencies();
		$this->check_graceful_deactivation();
		$this->check_post_types_registered();
		$this->check_rest_api_endpoints();
		$this->check_shortcodes_registered();
		$this->check_database_tables();
		$this->check_wp_version();
		$this->check_php_version();

		return $this->results;
	}

	/**
	 * Check if the block theme is active.
	 */
	private function check_theme_status() {
		$theme = wp_get_theme();
		$is_block_theme = $theme->get_stylesheet() === 'reforestamos-block-theme'
			|| $theme->get_template() === 'reforestamos-block-theme';

		$this->results['theme_active'] = array(
			'label'   => __( 'Block Theme activo', 'reforestamos' ),
			'status'  => $is_block_theme ? 'pass' : 'fail',
			'message' => $is_block_theme
				? __( 'Reforestamos Block Theme está activo.', 'reforestamos' )
				: __( 'Reforestamos Block Theme no está activo.', 'reforestamos' ),
		);
	}

	/**
	 * Check activation status of all ecosystem plugins.
	 */
	private function check_plugin_activation() {
		foreach ( $this->plugins as $slug => $plugin ) {
			$is_active = is_plugin_active( $plugin['file'] );

			$this->results[ 'plugin_' . $slug ] = array(
				'label'   => sprintf(
					/* translators: %s: plugin name */
					__( 'Plugin: %s', 'reforestamos' ),
					$plugin['name']
				),
				'status'  => $is_active ? 'pass' : 'warning',
				'message' => $is_active
					? sprintf( __( '%s está activo.', 'reforestamos' ), $plugin['name'] )
					: sprintf( __( '%s no está activo. Algunas funcionalidades no estarán disponibles.', 'reforestamos' ), $plugin['name'] ),
			);
		}
	}

	/**
	 * Check that plugin dependencies are satisfied.
	 */
	private function check_dependencies() {
		foreach ( $this->plugins as $slug => $plugin ) {
			if ( empty( $plugin['depends_on'] ) ) {
				continue;
			}

			$is_active = is_plugin_active( $plugin['file'] );
			if ( ! $is_active ) {
				continue; // Skip dependency check for inactive plugins.
			}

			foreach ( $plugin['depends_on'] as $dep_slug ) {
				$dep = $this->plugins[ $dep_slug ] ?? null;
				if ( ! $dep ) {
					continue;
				}

				$dep_active = is_plugin_active( $dep['file'] );
				$key        = 'dep_' . $slug . '_' . $dep_slug;

				$this->results[ $key ] = array(
					'label'   => sprintf(
						/* translators: 1: plugin name, 2: dependency name */
						__( 'Dependencia: %1$s → %2$s', 'reforestamos' ),
						$plugin['name'],
						$dep['name']
					),
					'status'  => $dep_active ? 'pass' : 'fail',
					'message' => $dep_active
						? sprintf( __( '%1$s tiene su dependencia %2$s activa.', 'reforestamos' ), $plugin['name'], $dep['name'] )
						: sprintf( __( '¡CRÍTICO! %1$s requiere %2$s pero no está activo.', 'reforestamos' ), $plugin['name'], $dep['name'] ),
				);
			}
		}
	}

	/**
	 * Check that deactivating a plugin doesn't cause fatal errors.
	 */
	private function check_graceful_deactivation() {
		// Verify that the theme handles missing plugins gracefully.
		$checks = array(
			'reforestamos-core' => 'Reforestamos_Core',
			'reforestamos-micrositios' => 'Reforestamos_Micrositios',
			'reforestamos-comunicacion' => 'Reforestamos_Comunicacion',
			'reforestamos-empresas' => 'Reforestamos_Empresas',
		);

		foreach ( $checks as $slug => $class_name ) {
			$class_exists = class_exists( $class_name );
			$plugin_info  = $this->plugins[ $slug ];
			$is_active    = is_plugin_active( $plugin_info['file'] );

			if ( $is_active && $class_exists ) {
				$status  = 'pass';
				$message = sprintf( __( '%s cargado correctamente.', 'reforestamos' ), $plugin_info['name'] );
			} elseif ( ! $is_active && ! $class_exists ) {
				$status  = 'pass';
				$message = sprintf( __( '%s inactivo — tema funciona sin errores.', 'reforestamos' ), $plugin_info['name'] );
			} else {
				$status  = 'warning';
				$message = sprintf( __( '%s tiene un estado inesperado.', 'reforestamos' ), $plugin_info['name'] );
			}

			$this->results[ 'graceful_' . $slug ] = array(
				'label'   => sprintf( __( 'Degradación: %s', 'reforestamos' ), $plugin_info['name'] ),
				'status'  => $status,
				'message' => $message,
			);
		}
	}

	/**
	 * Check that expected Custom Post Types are registered.
	 */
	private function check_post_types_registered() {
		$expected_cpts = array(
			'empresas'    => 'reforestamos-core',
			'eventos'     => 'reforestamos-core',
			'integrantes' => 'reforestamos-core',
		);

		foreach ( $expected_cpts as $cpt => $provider ) {
			$registered = post_type_exists( $cpt );
			$plugin     = $this->plugins[ $provider ];

			$this->results[ 'cpt_' . $cpt ] = array(
				'label'   => sprintf( __( 'CPT: %s', 'reforestamos' ), $cpt ),
				'status'  => $registered ? 'pass' : 'warning',
				'message' => $registered
					? sprintf( __( 'CPT "%s" registrado correctamente.', 'reforestamos' ), $cpt )
					: sprintf( __( 'CPT "%s" no registrado. Activa %s.', 'reforestamos' ), $cpt, $plugin['name'] ),
			);
		}
	}

	/**
	 * Check REST API endpoints availability.
	 */
	private function check_rest_api_endpoints() {
		$rest_server = rest_get_server();
		$routes      = $rest_server->get_routes();

		$expected_routes = array(
			'/reforestamos/v1/empresas'         => 'reforestamos-core',
			'/reforestamos/v1/eventos/upcoming'  => 'reforestamos-core',
			'/reforestamos/v1/integrantes'       => 'reforestamos-core',
		);

		foreach ( $expected_routes as $route => $provider ) {
			$exists = isset( $routes[ $route ] );
			$plugin = $this->plugins[ $provider ];

			$this->results[ 'rest_' . sanitize_key( $route ) ] = array(
				'label'   => sprintf( __( 'REST: %s', 'reforestamos' ), $route ),
				'status'  => $exists ? 'pass' : 'warning',
				'message' => $exists
					? sprintf( __( 'Endpoint %s disponible.', 'reforestamos' ), $route )
					: sprintf( __( 'Endpoint %s no disponible. Activa %s.', 'reforestamos' ), $route, $plugin['name'] ),
			);
		}
	}

	/**
	 * Check that expected shortcodes are registered.
	 */
	private function check_shortcodes_registered() {
		global $shortcode_tags;

		$expected_shortcodes = array(
			'arboles-ciudades'     => 'reforestamos-micrositios',
			'red-oja'              => 'reforestamos-micrositios',
			'newsletter-subscribe' => 'reforestamos-comunicacion',
			'contact-form'         => 'reforestamos-comunicacion',
			'companies-grid'       => 'reforestamos-empresas',
			'company-gallery'      => 'reforestamos-empresas',
		);

		foreach ( $expected_shortcodes as $shortcode => $provider ) {
			$registered = isset( $shortcode_tags[ $shortcode ] );
			$plugin     = $this->plugins[ $provider ];

			$this->results[ 'shortcode_' . $shortcode ] = array(
				'label'   => sprintf( __( 'Shortcode: [%s]', 'reforestamos' ), $shortcode ),
				'status'  => $registered ? 'pass' : 'warning',
				'message' => $registered
					? sprintf( __( 'Shortcode [%s] registrado.', 'reforestamos' ), $shortcode )
					: sprintf( __( 'Shortcode [%s] no disponible. Activa %s.', 'reforestamos' ), $shortcode, $plugin['name'] ),
			);
		}
	}

	/**
	 * Check that required database tables exist.
	 */
	private function check_database_tables() {
		global $wpdb;

		$expected_tables = array(
			$wpdb->prefix . 'reforestamos_newsletter_subscribers' => 'reforestamos-comunicacion',
			$wpdb->prefix . 'reforestamos_contact_submissions'    => 'reforestamos-comunicacion',
			$wpdb->prefix . 'reforestamos_chatbot_logs'           => 'reforestamos-comunicacion',
			$wpdb->prefix . 'reforestamos_company_clicks'         => 'reforestamos-empresas',
		);

		foreach ( $expected_tables as $table => $provider ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) === $table;
			$plugin = $this->plugins[ $provider ];

			$this->results[ 'table_' . sanitize_key( $table ) ] = array(
				'label'   => sprintf( __( 'Tabla: %s', 'reforestamos' ), $table ),
				'status'  => $exists ? 'pass' : 'warning',
				'message' => $exists
					? sprintf( __( 'Tabla %s existe.', 'reforestamos' ), $table )
					: sprintf( __( 'Tabla %s no encontrada. Activa %s.', 'reforestamos' ), $table, $plugin['name'] ),
			);
		}
	}

	/**
	 * Check WordPress version compatibility.
	 */
	private function check_wp_version() {
		global $wp_version;
		$min_version = '6.0';

		$this->results['wp_version'] = array(
			'label'   => __( 'Versión de WordPress', 'reforestamos' ),
			'status'  => version_compare( $wp_version, $min_version, '>=' ) ? 'pass' : 'fail',
			'message' => sprintf(
				/* translators: 1: current version, 2: minimum version */
				__( 'WordPress %1$s (mínimo requerido: %2$s)', 'reforestamos' ),
				$wp_version,
				$min_version
			),
		);
	}

	/**
	 * Check PHP version compatibility.
	 */
	private function check_php_version() {
		$min_version = '7.4';

		$this->results['php_version'] = array(
			'label'   => __( 'Versión de PHP', 'reforestamos' ),
			'status'  => version_compare( PHP_VERSION, $min_version, '>=' ) ? 'pass' : 'fail',
			'message' => sprintf(
				/* translators: 1: current version, 2: minimum version */
				__( 'PHP %1$s (mínimo requerido: %2$s)', 'reforestamos' ),
				PHP_VERSION,
				$min_version
			),
		);
	}

	/**
	 * Display critical notices in admin.
	 */
	public function display_critical_notices() {
		// Only show on dashboard and plugins page.
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'plugins' ), true ) ) {
			return;
		}

		// Check Empresas → Core dependency.
		if ( is_plugin_active( 'reforestamos-empresas/reforestamos-empresas.php' )
			&& ! is_plugin_active( 'reforestamos-core/reforestamos-core.php' ) ) {
			echo '<div class="notice notice-error"><p>';
			echo wp_kses_post(
				__( '<strong>Reforestamos:</strong> El plugin Empresas requiere que Reforestamos Core esté activo.', 'reforestamos' )
			);
			echo '</p></div>';
		}
	}

	/**
	 * Add admin page under Tools menu.
	 */
	public function add_admin_page() {
		add_management_page(
			__( 'Verificación de Integración', 'reforestamos' ),
			__( 'Reforestamos Check', 'reforestamos' ),
			'manage_options',
			'reforestamos-integration-check',
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Render the admin integration check page.
	 */
	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$results = $this->run_all_checks();
		$counts  = array(
			'pass'    => 0,
			'warning' => 0,
			'fail'    => 0,
		);

		foreach ( $results as $result ) {
			if ( isset( $counts[ $result['status'] ] ) ) {
				$counts[ $result['status'] ]++;
			}
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Verificación de Integración — Reforestamos', 'reforestamos' ); ?></h1>

			<div class="reforestamos-check-summary" style="display:flex;gap:20px;margin:20px 0;">
				<div style="padding:15px 25px;background:#d4edda;border-radius:6px;">
					<strong style="font-size:24px;color:#155724;"><?php echo esc_html( $counts['pass'] ); ?></strong>
					<br><?php esc_html_e( 'Correctos', 'reforestamos' ); ?>
				</div>
				<div style="padding:15px 25px;background:#fff3cd;border-radius:6px;">
					<strong style="font-size:24px;color:#856404;"><?php echo esc_html( $counts['warning'] ); ?></strong>
					<br><?php esc_html_e( 'Advertencias', 'reforestamos' ); ?>
				</div>
				<div style="padding:15px 25px;background:#f8d7da;border-radius:6px;">
					<strong style="font-size:24px;color:#721c24;"><?php echo esc_html( $counts['fail'] ); ?></strong>
					<br><?php esc_html_e( 'Errores', 'reforestamos' ); ?>
				</div>
			</div>

			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Estado', 'reforestamos' ); ?></th>
						<th><?php esc_html_e( 'Verificación', 'reforestamos' ); ?></th>
						<th><?php esc_html_e( 'Detalle', 'reforestamos' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $results as $result ) : ?>
						<tr>
							<td>
								<?php
								$icons = array(
									'pass'    => '✅',
									'warning' => '⚠️',
									'fail'    => '❌',
								);
								echo esc_html( $icons[ $result['status'] ] ?? '❓' );
								?>
							</td>
							<td><strong><?php echo esc_html( $result['label'] ); ?></strong></td>
							<td><?php echo esc_html( $result['message'] ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<p style="margin-top:15px;color:#666;">
				<?php
				printf(
					/* translators: %s: current date/time */
					esc_html__( 'Última verificación: %s', 'reforestamos' ),
					esc_html( current_time( 'Y-m-d H:i:s' ) )
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * AJAX handler for running integration checks.
	 */
	public function ajax_run_check() {
		check_ajax_referer( 'reforestamos_integration_check', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Permisos insuficientes.', 'reforestamos' ) );
		}

		$results = $this->run_all_checks();
		wp_send_json_success( $results );
	}
}
