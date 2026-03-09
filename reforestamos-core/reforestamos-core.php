<?php
/**
 * Plugin Name: Reforestamos Core
 * Plugin URI: https://reforestamos.org
 * Description: Core functionality for Reforestamos - Custom Post Types, Taxonomies, and REST API
 * Version: 1.0.0
 * Author: Reforestamos México
 * Author URI: https://reforestamos.org
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reforestamos-core
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package Reforestamos_Core
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'REFORESTAMOS_CORE_VERSION', '1.0.0' );
define( 'REFORESTAMOS_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'REFORESTAMOS_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'REFORESTAMOS_CORE_BASENAME', plugin_basename( __FILE__ ) );

// Require main class file
require_once REFORESTAMOS_CORE_PATH . 'includes/class-reforestamos-core.php';

/**
 * Initialize the plugin
 */
function reforestamos_core_init() {
	Reforestamos_Core::get_instance();
}
add_action( 'plugins_loaded', 'reforestamos_core_init' );

/**
 * Activation hook
 */
function reforestamos_core_activate() {
	// Require main class
	require_once REFORESTAMOS_CORE_PATH . 'includes/class-reforestamos-core.php';
	
	// Run activation
	Reforestamos_Core::activate();
}
register_activation_hook( __FILE__, 'reforestamos_core_activate' );

/**
 * Deactivation hook
 */
function reforestamos_core_deactivate() {
	// Flush rewrite rules
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'reforestamos_core_deactivate' );
