<?php
/**
 * Uninstall Script
 *
 * Runs when the plugin is uninstalled.
 *
 * @package Reforestamos_Micrositios
 * @since 1.0.0
 */

// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options
delete_option( 'reforestamos_micrositios_version' );
delete_option( 'reforestamos_micrositios_settings' );

// Note: We don't delete the data files by default to prevent data loss
// Users can manually delete the data/ directory if needed
