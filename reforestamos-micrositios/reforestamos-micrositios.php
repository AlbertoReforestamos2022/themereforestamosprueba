<?php
/**
 * Plugin Name: Reforestamos Micrositios
 * Plugin URI: https://reforestamos.org
 * Description: Micrositios interactivos - Árboles y Ciudades, Red OJA con mapas Leaflet
 * Version: 1.0.0
 * Author: Reforestamos México
 * Author URI: https://reforestamos.org
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reforestamos-micrositios
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package Reforestamos_Micrositios
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'REFORESTAMOS_MICRO_VERSION', '1.0.0' );
define( 'REFORESTAMOS_MICRO_PATH', plugin_dir_path( __FILE__ ) );
define( 'REFORESTAMOS_MICRO_URL', plugin_dir_url( __FILE__ ) );
define( 'REFORESTAMOS_MICRO_BASENAME', plugin_basename( __FILE__ ) );

// Require main class file
require_once REFORESTAMOS_MICRO_PATH . 'includes/class-reforestamos-micrositios.php';

/**
 * Initialize the plugin
 */
function reforestamos_micrositios_init() {
	Reforestamos_Micrositios::get_instance();
}
add_action( 'plugins_loaded', 'reforestamos_micrositios_init' );

/**
 * Activation hook
 */
function reforestamos_micrositios_activate() {
	// Require main class
	require_once REFORESTAMOS_MICRO_PATH . 'includes/class-reforestamos-micrositios.php';
	
	// Run activation
	Reforestamos_Micrositios::activate();
}
register_activation_hook( __FILE__, 'reforestamos_micrositios_activate' );

/**
 * Deactivation hook
 */
function reforestamos_micrositios_deactivate() {
	// Cleanup if needed
}
register_deactivation_hook( __FILE__, 'reforestamos_micrositios_deactivate' );
