<?php
/**
 * PHPUnit Bootstrap for Reforestamos WordPress Project
 *
 * Loads the WordPress test environment and project files.
 *
 * @package Reforestamos_Tests
 */

// Composer autoloader
$autoloader = dirname( __DIR__ ) . '/vendor/autoload.php';
if ( file_exists( $autoloader ) ) {
    require_once $autoloader;
}

// WordPress test library path - check environment variable first, then common locations
$wp_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $wp_tests_dir ) {
    // Check common locations
    $possible_paths = array(
        '/tmp/wordpress-tests-lib',
        dirname( __DIR__ ) . '/wordpress-tests-lib',
        getenv( 'HOME' ) . '/wordpress-tests-lib',
    );

    foreach ( $possible_paths as $path ) {
        if ( file_exists( $path . '/includes/functions.php' ) ) {
            $wp_tests_dir = $path;
            break;
        }
    }
}

if ( ! $wp_tests_dir ) {
    echo "WordPress test library not found. Set WP_TESTS_DIR environment variable.\n";
    echo "Install with: bash bin/install-wp-tests.sh <db-name> <db-user> <db-pass> [db-host] [wp-version]\n";
    exit( 1 );
}

// Give access to tests_add_filter() function
require_once $wp_tests_dir . '/includes/functions.php';

/**
 * Manually load plugins and theme for testing
 */
function _reforestamos_manually_load_plugins() {
    $project_root = dirname( __DIR__ );

    // Load Core plugin
    $core_plugin = $project_root . '/reforestamos-core/reforestamos-core.php';
    if ( file_exists( $core_plugin ) ) {
        require $core_plugin;
    }

    // Load Micrositios plugin
    $micro_plugin = $project_root . '/reforestamos-micrositios/reforestamos-micrositios.php';
    if ( file_exists( $micro_plugin ) ) {
        require $micro_plugin;
    }

    // Load Comunicacion plugin
    $comm_plugin = $project_root . '/reforestamos-comunicacion/reforestamos-comunicacion.php';
    if ( file_exists( $comm_plugin ) ) {
        require $comm_plugin;
    }

    // Load Empresas plugin
    $emp_plugin = $project_root . '/reforestamos-empresas/reforestamos-empresas.php';
    if ( file_exists( $emp_plugin ) ) {
        require $emp_plugin;
    }

    // Set theme directory to block theme
    switch_theme( 'reforestamos-block-theme' );
}
tests_add_filter( 'muplugins_loaded', '_reforestamos_manually_load_plugins' );

// Start up the WP testing environment
require $wp_tests_dir . '/includes/bootstrap.php';

// Load base test case classes
require_once __DIR__ . '/class-reforestamos-test-case.php';
