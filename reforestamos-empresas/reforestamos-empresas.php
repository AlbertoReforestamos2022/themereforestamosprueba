<?php
/**
 * Plugin Name: Reforestamos Empresas
 * Plugin URI: https://reforestamos.org
 * Description: Gestión avanzada de empresas colaboradoras con analytics y galerías
 * Version: 1.0.0
 * Author: Reforestamos México
 * Author URI: https://reforestamos.org
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reforestamos-empresas
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package Reforestamos_Empresas
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'REFORESTAMOS_EMPRESAS_VERSION', '1.0.0' );
define( 'REFORESTAMOS_EMPRESAS_PATH', plugin_dir_path( __FILE__ ) );
define( 'REFORESTAMOS_EMPRESAS_URL', plugin_dir_url( __FILE__ ) );
define( 'REFORESTAMOS_EMPRESAS_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Check if Core Plugin is active
 * This plugin requires Reforestamos Core to be active
 */
function reforestamos_empresas_check_dependencies() {
	// Check if Core plugin class exists
	if ( ! class_exists( 'Reforestamos_Core' ) ) {
		add_action( 'admin_notices', 'reforestamos_empresas_dependency_notice' );
		return false;
	}
	return true;
}

/**
 * Display admin notice when Core plugin is not active
 */
function reforestamos_empresas_dependency_notice() {
	?>
	<div class="notice notice-error">
		<p>
			<?php
			echo wp_kses_post(
				sprintf(
					/* translators: %s: Core plugin name */
					__( '<strong>Reforestamos Empresas</strong> requiere que el plugin <strong>%s</strong> esté activo. Por favor, activa el plugin Reforestamos Core primero.', 'reforestamos-empresas' ),
					'Reforestamos Core'
				)
			);
			?>
		</p>
	</div>
	<?php
}

/**
 * Initialize the plugin
 * Only loads if dependencies are met
 */
function reforestamos_empresas_init() {
	// Check dependencies first
	if ( ! reforestamos_empresas_check_dependencies() ) {
		return;
	}

	// Require main class file
	require_once REFORESTAMOS_EMPRESAS_PATH . 'includes/class-reforestamos-empresas.php';

	// Initialize plugin
	Reforestamos_Empresas::get_instance();
}
add_action( 'plugins_loaded', 'reforestamos_empresas_init' );

/**
 * Activation hook
 * Checks dependencies before activation
 */
function reforestamos_empresas_activate() {
	// Check if Core plugin is active
	if ( ! class_exists( 'Reforestamos_Core' ) ) {
		// Deactivate this plugin
		deactivate_plugins( plugin_basename( __FILE__ ) );
		
		// Show error message
		wp_die(
			wp_kses_post(
				sprintf(
					/* translators: %s: Core plugin name */
					__( '<strong>Reforestamos Empresas</strong> requiere que el plugin <strong>%s</strong> esté activo.<br><br>Por favor, instala y activa el plugin Reforestamos Core primero.', 'reforestamos-empresas' ),
					'Reforestamos Core'
				)
			),
			esc_html__( 'Dependencia de Plugin Requerida', 'reforestamos-empresas' ),
			array( 'back_link' => true )
		);
	}

	// Require main class
	require_once REFORESTAMOS_EMPRESAS_PATH . 'includes/class-reforestamos-empresas.php';
	
	// Run activation
	Reforestamos_Empresas::activate();
}
register_activation_hook( __FILE__, 'reforestamos_empresas_activate' );

/**
 * Deactivation hook
 */
function reforestamos_empresas_deactivate() {
	// Cleanup if needed
	// Note: We don't delete data on deactivation, only on uninstall
}
register_deactivation_hook( __FILE__, 'reforestamos_empresas_deactivate' );
