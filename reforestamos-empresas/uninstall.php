<?php
/**
 * Uninstall Script
 *
 * Fired when the plugin is uninstalled.
 * Removes all plugin data from the database.
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Check if user has permission to uninstall
if ( ! current_user_can( 'activate_plugins' ) ) {
	exit;
}

global $wpdb;

/**
 * Delete plugin options
 */
$options = array(
	'reforestamos_empresas_enable_analytics',
	'reforestamos_empresas_enable_galleries',
	'reforestamos_empresas_grid_columns',
);

foreach ( $options as $option ) {
	delete_option( $option );
}

/**
 * Drop custom tables
 */
$table_name = $wpdb->prefix . 'reforestamos_empresas_analytics';
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

/**
 * Delete post meta for empresas
 * Note: We only delete meta added by this plugin, not the posts themselves
 * as they belong to the Core plugin
 */
$meta_keys = array(
	'_reforestamos_empresa_gallery',
	'_reforestamos_empresa_analytics_enabled',
);

foreach ( $meta_keys as $meta_key ) {
	$wpdb->delete(
		$wpdb->postmeta,
		array( 'meta_key' => $meta_key ),
		array( '%s' )
	);
}

/**
 * Clear any cached data
 */
wp_cache_flush();
