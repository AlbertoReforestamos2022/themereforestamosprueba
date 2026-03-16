<?php
/**
 * Reforestamos Migration System
 *
 * Sistema de migración para transformar el tema legacy a Block Theme
 * con arquitectura modular de plugins.
 *
 * @package Reforestamos_Migration
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define migration system constants
define( 'REFORESTAMOS_MIGRATION_VERSION', '1.0.0' );
define( 'REFORESTAMOS_MIGRATION_DIR', __DIR__ );
define( 'REFORESTAMOS_MIGRATION_INCLUDES', REFORESTAMOS_MIGRATION_DIR . '/includes' );

// Autoload classes
require_once REFORESTAMOS_MIGRATION_INCLUDES . '/class-backup-manager.php';
require_once REFORESTAMOS_MIGRATION_INCLUDES . '/class-migration-manager.php';
require_once REFORESTAMOS_MIGRATION_INCLUDES . '/class-content-migrator.php';
require_once REFORESTAMOS_MIGRATION_INCLUDES . '/class-shortcode-converter.php';

// Load WP-CLI commands if available
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once REFORESTAMOS_MIGRATION_INCLUDES . '/class-migration-command.php';
}

/**
 * Initialize migration system
 */
function reforestamos_migration_init() {
	// Initialize migration manager
	Reforestamos_Migration_Manager::get_instance();

	// Add admin menu
	add_action( 'admin_menu', 'reforestamos_migration_admin_menu' );
}

/**
 * Register admin menu
 */
function reforestamos_migration_admin_menu() {
	add_menu_page(
		'Reforestamos Migration',
		'Migration',
		'manage_options',
		'reforestamos-migration',
		'reforestamos_migration_dashboard',
		'dashicons-update',
		80
	);

	add_submenu_page(
		'reforestamos-migration',
		'Error Logs',
		'Error Logs',
		'manage_options',
		'reforestamos-migration-errors',
		'reforestamos_migration_error_logs'
	);
}

/**
 * Render migration dashboard
 */
function reforestamos_migration_dashboard() {
	echo '<div class="wrap">';
	echo '<h1>Reforestamos Migration System</h1>';
	echo '<p>Use WP-CLI commands to run migrations:</p>';
	echo '<pre>wp reforestamos migrate --dry-run</pre>';
	echo '<pre>wp reforestamos migrate</pre>';
	echo '<p>See <a href="' . admin_url( 'admin.php?page=reforestamos-migration-errors' ) . '">Error Logs</a> for migration errors.</p>';
	echo '</div>';
}

/**
 * Render error logs page
 */
function reforestamos_migration_error_logs() {
	require_once REFORESTAMOS_MIGRATION_DIR . '/admin/views/error-logs.php';
}

add_action( 'plugins_loaded', 'reforestamos_migration_init' );

// Create error log table on activation
register_activation_hook( __FILE__, 'reforestamos_migration_activate' );

function reforestamos_migration_activate() {
	require_once REFORESTAMOS_MIGRATION_INCLUDES . '/class-error-logger.php';
	$error_logger = new Reforestamos_Error_Logger();
	$error_logger->create_table();
}
