<?php
/**
 * Uninstall Script
 *
 * Fired when the plugin is uninstalled.
 * Removes all plugin data from the database.
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options
delete_option( 'reforestamos_smtp_host' );
delete_option( 'reforestamos_smtp_port' );
delete_option( 'reforestamos_smtp_username' );
delete_option( 'reforestamos_smtp_password' );
delete_option( 'reforestamos_smtp_secure' );
delete_option( 'reforestamos_smtp_from_email' );
delete_option( 'reforestamos_smtp_from_name' );
delete_option( 'reforestamos_deepl_api_key' );
delete_option( 'reforestamos_chatbot_enabled' );

// Drop plugin tables
global $wpdb;

$tables = array(
	$wpdb->prefix . 'reforestamos_subscribers',
	$wpdb->prefix . 'reforestamos_submissions',
	$wpdb->prefix . 'reforestamos_chatbot_logs',
);

foreach ( $tables as $table ) {
	$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
}

// Clear any cached data
wp_cache_flush();
