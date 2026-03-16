<?php
/**
 * Health Check Endpoints
 *
 * Provides REST API endpoints for monitoring site health
 * and verifying connectivity to external services.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register health check REST routes.
 */
function reforestamos_register_health_routes() {
	register_rest_route(
		'reforestamos/v1',
		'/health',
		array(
			'methods'             => 'GET',
			'callback'            => 'reforestamos_health_check',
			'permission_callback' => '__return_true',
		)
	);

	register_rest_route(
		'reforestamos/v1',
		'/health/detailed',
		array(
			'methods'             => 'GET',
			'callback'            => 'reforestamos_health_check_detailed',
			'permission_callback' => function () {
				return current_user_can( 'manage_options' );
			},
		)
	);
}
add_action( 'rest_api_init', 'reforestamos_register_health_routes' );

/**
 * Basic health check — public endpoint.
 *
 * @return WP_REST_Response
 */
function reforestamos_health_check() {
	return new WP_REST_Response(
		array(
			'status'    => 'ok',
			'timestamp' => gmdate( 'c' ),
			'version'   => defined( 'REFORESTAMOS_VERSION' ) ? REFORESTAMOS_VERSION : '1.0.0',
		),
		200
	);
}

/**
 * Detailed health check — admin only.
 *
 * Checks database, active plugins, theme status, and external services.
 *
 * @return WP_REST_Response
 */
function reforestamos_health_check_detailed() {
	$checks = array();

	// Database connectivity.
	$checks['database'] = reforestamos_check_database();

	// Required plugins.
	$checks['plugins'] = reforestamos_check_plugins();

	// Theme status.
	$checks['theme'] = reforestamos_check_theme();

	// External services.
	$checks['external_services'] = reforestamos_check_external_services();

	// PHP environment.
	$checks['php'] = array(
		'status'  => 'ok',
		'version' => PHP_VERSION,
		'memory'  => ini_get( 'memory_limit' ),
	);

	$overall = 'ok';
	foreach ( $checks as $check ) {
		if ( isset( $check['status'] ) && 'error' === $check['status'] ) {
			$overall = 'degraded';
			break;
		}
	}

	return new WP_REST_Response(
		array(
			'status'    => $overall,
			'timestamp' => gmdate( 'c' ),
			'version'   => defined( 'REFORESTAMOS_VERSION' ) ? REFORESTAMOS_VERSION : '1.0.0',
			'checks'    => $checks,
		),
		'ok' === $overall ? 200 : 503
	);
}

/**
 * Check database connectivity.
 *
 * @return array
 */
function reforestamos_check_database() {
	global $wpdb;

	$result = $wpdb->get_var( 'SELECT 1' ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

	if ( '1' === $result ) {
		return array(
			'status'  => 'ok',
			'message' => 'Database connection successful',
		);
	}

	return array(
		'status'  => 'error',
		'message' => 'Database connection failed',
	);
}

/**
 * Check required plugins are active.
 *
 * @return array
 */
function reforestamos_check_plugins() {
	$required = array(
		'reforestamos-core/reforestamos-core.php' => 'Reforestamos Core',
	);

	$optional = array(
		'reforestamos-micrositios/reforestamos-micrositios.php' => 'Reforestamos Micrositios',
		'reforestamos-comunicacion/reforestamos-comunicacion.php' => 'Reforestamos Comunicación',
		'reforestamos-empresas/reforestamos-empresas.php' => 'Reforestamos Empresas',
	);

	$active  = get_option( 'active_plugins', array() );
	$results = array();
	$status  = 'ok';

	foreach ( $required as $plugin => $name ) {
		$is_active          = in_array( $plugin, $active, true );
		$results[ $name ] = $is_active ? 'active' : 'missing';
		if ( ! $is_active ) {
			$status = 'error';
		}
	}

	foreach ( $optional as $plugin => $name ) {
		$results[ $name ] = in_array( $plugin, $active, true ) ? 'active' : 'inactive';
	}

	return array(
		'status'  => $status,
		'plugins' => $results,
	);
}

/**
 * Check theme status.
 *
 * @return array
 */
function reforestamos_check_theme() {
	$theme = wp_get_theme();

	return array(
		'status'  => 'ok',
		'name'    => $theme->get( 'Name' ),
		'version' => $theme->get( 'Version' ),
	);
}

/**
 * Check external service connectivity.
 *
 * @return array
 */
function reforestamos_check_external_services() {
	$services = array();

	// DeepL API.
	$deepl_key = get_option( 'reforestamos_deepl_api_key', '' );
	if ( ! empty( $deepl_key ) ) {
		$response = wp_remote_get(
			'https://api-free.deepl.com/v2/usage',
			array(
				'headers' => array( 'Authorization' => 'DeepL-Auth-Key ' . $deepl_key ),
				'timeout' => 5,
			)
		);
		$services['deepl'] = array(
			'status' => is_wp_error( $response ) ? 'error' : 'ok',
		);
	} else {
		$services['deepl'] = array( 'status' => 'not_configured' );
	}

	// SMTP (basic socket check).
	$smtp_host = get_option( 'reforestamos_smtp_host', '' );
	if ( ! empty( $smtp_host ) ) {
		$smtp_port  = get_option( 'reforestamos_smtp_port', 587 );
		$connection = @fsockopen( $smtp_host, (int) $smtp_port, $errno, $errstr, 5 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		if ( $connection ) {
			fclose( $connection );
			$services['smtp'] = array( 'status' => 'ok' );
		} else {
			$services['smtp'] = array(
				'status'  => 'error',
				'message' => $errstr,
			);
		}
	} else {
		$services['smtp'] = array( 'status' => 'not_configured' );
	}

	return $services;
}
