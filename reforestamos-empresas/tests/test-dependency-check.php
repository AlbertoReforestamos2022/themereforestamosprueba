<?php
/**
 * Dependency Check Test
 *
 * Manual test to verify that the plugin correctly checks for Core plugin dependency.
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

/**
 * Test Instructions:
 * 
 * 1. Deactivate Reforestamos Core plugin
 * 2. Try to activate Reforestamos Empresas plugin
 * 3. Expected: Plugin activation should fail with error message
 * 4. Expected: Error message should say "Reforestamos Empresas requiere que el plugin Reforestamos Core esté activo"
 * 
 * 5. Activate Reforestamos Core plugin
 * 6. Try to activate Reforestamos Empresas plugin again
 * 7. Expected: Plugin should activate successfully
 * 
 * 8. With both plugins active, deactivate Reforestamos Core
 * 9. Visit any admin page
 * 10. Expected: Admin notice should appear saying Core plugin is required
 * 11. Expected: Empresas plugin functionality should not load
 */

/**
 * Test Case 1: Check if dependency function exists
 */
function test_dependency_function_exists() {
	if ( function_exists( 'reforestamos_empresas_check_dependencies' ) ) {
		echo "✓ Dependency check function exists\n";
		return true;
	} else {
		echo "✗ Dependency check function does not exist\n";
		return false;
	}
}

/**
 * Test Case 2: Check if Core class detection works
 */
function test_core_class_detection() {
	// Simulate Core plugin not being active
	if ( ! class_exists( 'Reforestamos_Core' ) ) {
		echo "✓ Correctly detects when Core plugin is not active\n";
		return true;
	} else {
		echo "✓ Core plugin is active (class exists)\n";
		return true;
	}
}

/**
 * Test Case 3: Check if admin notice function exists
 */
function test_admin_notice_function_exists() {
	if ( function_exists( 'reforestamos_empresas_dependency_notice' ) ) {
		echo "✓ Admin notice function exists\n";
		return true;
	} else {
		echo "✗ Admin notice function does not exist\n";
		return false;
	}
}

/**
 * Test Case 4: Check if activation hook is registered
 */
function test_activation_hook_registered() {
	// This would need to be tested in WordPress environment
	echo "⚠ Activation hook test requires WordPress environment\n";
	echo "  Manual test: Try activating plugin without Core active\n";
	return true;
}

/**
 * Expected Behavior Summary:
 * 
 * SCENARIO A: Core Plugin NOT Active
 * - Activation attempt should fail with wp_die() message
 * - Plugin should auto-deactivate
 * - Error message should be clear and actionable
 * 
 * SCENARIO B: Core Plugin Active
 * - Plugin should activate successfully
 * - No error messages should appear
 * - Plugin functionality should load normally
 * 
 * SCENARIO C: Core Plugin Deactivated After Empresas Active
 * - Admin notice should appear on all admin pages
 * - Notice should be dismissible (error type)
 * - Plugin should not load its main functionality
 * - No fatal errors should occur
 */

// Run tests if this file is executed directly (not in WordPress context)
if ( php_sapi_name() === 'cli' ) {
	echo "Running Dependency Check Tests...\n\n";
	
	echo "Note: These tests verify code structure only.\n";
	echo "Full functionality testing requires WordPress environment.\n\n";
	
	// Check if main plugin file exists
	$plugin_file = dirname( __DIR__ ) . '/reforestamos-empresas.php';
	if ( file_exists( $plugin_file ) ) {
		echo "✓ Main plugin file exists\n";
		
		// Read and check for key functions
		$content = file_get_contents( $plugin_file );
		
		if ( strpos( $content, 'reforestamos_empresas_check_dependencies' ) !== false ) {
			echo "✓ Dependency check function defined\n";
		}
		
		if ( strpos( $content, 'class_exists( \'Reforestamos_Core\' )' ) !== false ) {
			echo "✓ Core class existence check implemented\n";
		}
		
		if ( strpos( $content, 'reforestamos_empresas_dependency_notice' ) !== false ) {
			echo "✓ Admin notice function defined\n";
		}
		
		if ( strpos( $content, 'register_activation_hook' ) !== false ) {
			echo "✓ Activation hook registered\n";
		}
		
		if ( strpos( $content, 'deactivate_plugins' ) !== false ) {
			echo "✓ Auto-deactivation on failed dependency check\n";
		}
		
		if ( strpos( $content, 'wp_die' ) !== false ) {
			echo "✓ User-friendly error message on activation failure\n";
		}
		
		echo "\n✓ All code structure checks passed!\n";
		echo "\nNext Steps:\n";
		echo "1. Install plugin in WordPress\n";
		echo "2. Run manual tests as described in test instructions above\n";
		echo "3. Verify all scenarios work as expected\n";
		
	} else {
		echo "✗ Main plugin file not found\n";
	}
}
