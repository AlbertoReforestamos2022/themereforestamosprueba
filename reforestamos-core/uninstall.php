<?php
/**
 * Uninstall Script
 *
 * Runs when the plugin is uninstalled (not just deactivated).
 * Cleans up plugin data from the database.
 *
 * @package Reforestamos_Core
 * @since 1.0.0
 */

// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete plugin options
 */
function reforestamos_core_delete_options() {
	// Delete plugin options
	delete_option( 'reforestamos_core_version' );
	delete_option( 'reforestamos_core_settings' );
	
	// For multisite
	delete_site_option( 'reforestamos_core_version' );
	delete_site_option( 'reforestamos_core_settings' );
}

/**
 * Delete custom post type posts
 *
 * Note: This will permanently delete all posts of custom post types.
 * Only runs if explicitly enabled by the user.
 */
function reforestamos_core_delete_posts() {
	// Check if user wants to delete data
	$delete_data = get_option( 'reforestamos_core_delete_data_on_uninstall', false );
	
	if ( ! $delete_data ) {
		return;
	}
	
	global $wpdb;
	
	// Custom post types to delete
	$post_types = array(
		'empresas',
		'eventos',
		'integrantes',
		'boletin',
	);
	
	foreach ( $post_types as $post_type ) {
		// Get all posts of this type
		$posts = get_posts(
			array(
				'post_type'      => $post_type,
				'posts_per_page' => -1,
				'post_status'    => 'any',
			)
		);
		
		// Delete each post
		foreach ( $posts as $post ) {
			// Delete post meta
			$wpdb->delete(
				$wpdb->postmeta,
				array( 'post_id' => $post->ID ),
				array( '%d' )
			);
			
			// Delete post
			wp_delete_post( $post->ID, true );
		}
	}
}

/**
 * Delete custom taxonomies and terms
 */
function reforestamos_core_delete_taxonomies() {
	// Check if user wants to delete data
	$delete_data = get_option( 'reforestamos_core_delete_data_on_uninstall', false );
	
	if ( ! $delete_data ) {
		return;
	}
	
	// Custom taxonomies will be automatically removed when plugin is uninstalled
	// Terms are preserved by default unless explicitly deleted
	
	// TODO: Add specific taxonomy cleanup if needed
}

/**
 * Clean up transients
 */
function reforestamos_core_delete_transients() {
	global $wpdb;
	
	// Delete all transients with our prefix
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
			'_transient_reforestamos_core_%',
			'_transient_timeout_reforestamos_core_%'
		)
	);
}

// Run cleanup functions
reforestamos_core_delete_options();
reforestamos_core_delete_transients();
reforestamos_core_delete_posts();
reforestamos_core_delete_taxonomies();

// Flush rewrite rules
flush_rewrite_rules();
