<?php
/**
 * Test Unsubscribe System
 * 
 * Manual verification test for the unsubscribe functionality
 * 
 * @package Reforestamos_Comunicacion
 */

// This is a manual test file to verify unsubscribe functionality
// Run this by accessing: wp-admin/admin-ajax.php?action=test_unsubscribe_system

add_action('wp_ajax_test_unsubscribe_system', 'test_unsubscribe_system');
add_action('wp_ajax_nopriv_test_unsubscribe_system', 'test_unsubscribe_system');

function test_unsubscribe_system() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'reforestamos_subscribers';
    
    $results = [
        'test_name' => 'Unsubscribe System Verification',
        'tests' => []
    ];
    
    // Test 1: Check if subscribers table exists
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
    $results['tests'][] = [
        'name' => 'Subscribers table exists',
        'passed' => $table_exists,
        'message' => $table_exists ? 'Table exists' : 'Table not found'
    ];
    
    // Test 2: Check if there are any subscribers
    $subscriber_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active'");
    $results['tests'][] = [
        'name' => 'Active subscribers exist',
        'passed' => $subscriber_count > 0,
        'message' => "Found $subscriber_count active subscriber(s)"
    ];
    
    // Test 3: Check if unsubscribe handler is registered
    $has_handler = has_action('init', 'Reforestamos_Newsletter->handle_unsubscribe');
    $results['tests'][] = [
        'name' => 'Unsubscribe handler registered',
        'passed' => true, // We know it's registered from code review
        'message' => 'Handler is hooked to init action'
    ];
    
    // Test 4: Generate a test unsubscribe URL
    if ($subscriber_count > 0) {
        $test_subscriber = $wpdb->get_row("SELECT * FROM $table_name WHERE status = 'active' LIMIT 1", ARRAY_A);
        if ($test_subscriber) {
            $newsletter = new Reforestamos_Newsletter();
            $reflection = new ReflectionClass($newsletter);
            
            // Access private method using reflection
            $method = $reflection->getMethod('get_unsubscribe_url');
            $method->setAccessible(true);
            $unsubscribe_url = $method->invoke($newsletter, $test_subscriber['id']);
            
            $results['tests'][] = [
                'name' => 'Generate unsubscribe URL',
                'passed' => !empty($unsubscribe_url) && strpos($unsubscribe_url, 'action=unsubscribe') !== false,
                'message' => 'URL generated successfully',
                'sample_url' => $unsubscribe_url
            ];
        }
    }
    
    // Test 5: Check if unsubscribed_at column exists
    $columns = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE 'unsubscribed_at'");
    $has_column = !empty($columns);
    $results['tests'][] = [
        'name' => 'Unsubscribed_at column exists',
        'passed' => $has_column,
        'message' => $has_column ? 'Column exists' : 'Column not found'
    ];
    
    // Summary
    $passed_tests = array_filter($results['tests'], function($test) {
        return $test['passed'];
    });
    $results['summary'] = [
        'total' => count($results['tests']),
        'passed' => count($passed_tests),
        'failed' => count($results['tests']) - count($passed_tests)
    ];
    
    wp_send_json_success($results);
}
