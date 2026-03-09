<?php
/**
 * Plugin Name: Reforestamos Comunicación
 * Plugin URI: https://reforestamos.org
 * Description: Sistema de comunicación - Newsletter, Formularios de Contacto, ChatBot y Traducción DeepL
 * Version: 1.0.0
 * Author: Reforestamos México
 * Author URI: https://reforestamos.org
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reforestamos-comunicacion
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package Reforestamos_Comunicacion
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'REFORESTAMOS_COMM_VERSION', '1.0.0' );
define( 'REFORESTAMOS_COMM_PATH', plugin_dir_path( __FILE__ ) );
define( 'REFORESTAMOS_COMM_URL', plugin_dir_url( __FILE__ ) );
define( 'REFORESTAMOS_COMM_BASENAME', plugin_basename( __FILE__ ) );

// Require main class file
require_once REFORESTAMOS_COMM_PATH . 'includes/class-reforestamos-comunicacion.php';

/**
 * Initialize the plugin
 */
function reforestamos_comunicacion_init() {
	Reforestamos_Comunicacion::get_instance();
}
add_action( 'plugins_loaded', 'reforestamos_comunicacion_init' );

/**
 * Activation hook
 */
function reforestamos_comunicacion_activate() {
	// Require main class
	require_once REFORESTAMOS_COMM_PATH . 'includes/class-reforestamos-comunicacion.php';
	
	// Run activation
	Reforestamos_Comunicacion::activate();
}
register_activation_hook( __FILE__, 'reforestamos_comunicacion_activate' );

/**
 * Deactivation hook
 */
function reforestamos_comunicacion_deactivate() {
	// Cleanup if needed
}
register_deactivation_hook( __FILE__, 'reforestamos_comunicacion_deactivate' );
