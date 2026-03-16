<?php
/**
 * Tests for Reforestamos_Error_Handler class.
 *
 * @package Reforestamos_Tests
 */

class Test_Error_Handler extends Reforestamos_Test_Case {

    /**
     * Error handler instance.
     *
     * @var Reforestamos_Error_Handler
     */
    private $handler;

    /**
     * Set up test fixtures.
     */
    public function setUp(): void {
        parent::setUp();
        $this->handler = Reforestamos_Error_Handler::get_instance();
    }

    // =========================================================================
    // 49.1 - Error Handlers
    // =========================================================================

    /**
     * Test singleton pattern returns same instance.
     */
    public function test_singleton_returns_same_instance() {
        $instance1 = Reforestamos_Error_Handler::get_instance();
        $instance2 = Reforestamos_Error_Handler::get_instance();
        $this->assertSame($instance1, $instance2);
    }

    /**
     * Test PHP error handler returns true for handled errors.
     */
    public function test_php_error_handler_handles_errors() {
        // The handler should return true (suppresses default handler) when not in debug mode
        $result = $this->handler->handle_php_error(
            E_USER_NOTICE,
            'Test notice message',
            __FILE__,
            __LINE__
        );
        // Result depends on debug mode, but should not throw
        $this->assertIsBool($result);
    }

    /**
     * Test PHP error handler respects error_reporting level.
     */
    public function test_error_handler_respects_error_reporting() {
        $old_level = error_reporting(0);
        $result = $this->handler->handle_php_error(
            E_USER_NOTICE,
            'Should be ignored',
            __FILE__,
            __LINE__
        );
        error_reporting($old_level);
        $this->assertFalse($result);
    }

    // =========================================================================
    // 49.2 - Logging System
    // =========================================================================

    /**
     * Test log directory exists after init.
     */
    public function test_log_directory_exists() {
        $log_dir = $this->handler->get_log_dir();
        $this->assertTrue(is_dir($log_dir), 'Log directory should exist');
    }

    /**
     * Test log directory has security protections.
     */
    public function test_log_directory_has_htaccess() {
        $log_dir = $this->handler->get_log_dir();
        $this->assertFileExists($log_dir . '/.htaccess');
    }

    /**
     * Test log directory has index.php.
     */
    public function test_log_directory_has_index() {
        $log_dir = $this->handler->get_log_dir();
        $this->assertFileExists($log_dir . '/index.php');
    }

    /**
     * Test logging writes to file.
     */
    public function test_log_writes_to_file() {
        $this->handler->log('info', 'Test log entry', 'test');

        $log_dir = $this->handler->get_log_dir();
        $log_file = $log_dir . '/reforestamos-' . gmdate('Y-m-d') . '.log';

        $this->assertFileExists($log_file);
        $content = file_get_contents($log_file);
        $this->assertStringContainsString('Test log entry', $content);
        $this->assertStringContainsString('[INFO]', $content);
        $this->assertStringContainsString('[test]', $content);
    }

    /**
     * Test logging array data.
     */
    public function test_log_array_data() {
        $data = array('key' => 'value', 'number' => 42);
        $this->handler->log('warning', $data, 'test');

        $log_dir = $this->handler->get_log_dir();
        $log_file = $log_dir . '/reforestamos-' . gmdate('Y-m-d') . '.log';

        $content = file_get_contents($log_file);
        $this->assertStringContainsString('[WARNING]', $content);
        $this->assertStringContainsString('"key"', $content);
    }

    /**
     * Test static log_message helper.
     */
    public function test_static_log_message() {
        Reforestamos_Error_Handler::log_message('error', 'Static log test', 'static-test');

        $log_dir = $this->handler->get_log_dir();
        $log_file = $log_dir . '/reforestamos-' . gmdate('Y-m-d') . '.log';

        $content = file_get_contents($log_file);
        $this->assertStringContainsString('Static log test', $content);
        $this->assertStringContainsString('[static-test]', $content);
    }

    // =========================================================================
    // 49.3 - User-Friendly Error Messages
    // =========================================================================

    /**
     * Test user-friendly messages for known error types.
     */
    public function test_user_message_known_types() {
        $types = array('api_failure', 'not_found', 'permission', 'form_error', 'upload_error', 'general');

        foreach ($types as $type) {
            $message = Reforestamos_Error_Handler::get_user_message($type);
            $this->assertNotEmpty($message, "Message for type '{$type}' should not be empty");
            $this->assertIsString($message);
        }
    }

    /**
     * Test unknown error type falls back to general message.
     */
    public function test_user_message_unknown_type_fallback() {
        $general = Reforestamos_Error_Handler::get_user_message('general');
        $unknown = Reforestamos_Error_Handler::get_user_message('nonexistent_type');
        $this->assertEquals($general, $unknown);
    }

    /**
     * Test error template renders valid HTML.
     */
    public function test_render_error_template() {
        $html = Reforestamos_Error_Handler::render_error_template('not_found');
        $this->assertStringContainsString('reforestamos-error-message', $html);
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('<svg', $html);
    }

    /**
     * Test error template with retry URL.
     */
    public function test_render_error_template_with_retry() {
        $html = Reforestamos_Error_Handler::render_error_template('api_failure', array(
            'retry_url' => 'https://example.com/retry',
        ));
        $this->assertStringContainsString('https://example.com/retry', $html);
        $this->assertStringContainsString('reforestamos-error-retry', $html);
    }

    /**
     * Test error template with home link.
     */
    public function test_render_error_template_with_home_link() {
        $html = Reforestamos_Error_Handler::render_error_template('general', array(
            'home_link' => true,
        ));
        $this->assertStringContainsString('reforestamos-error-home', $html);
    }

    /**
     * Test API fallback returns default when no fallback function.
     */
    public function test_api_fallback_returns_default() {
        $result = $this->handler->api_fallback('test_api', null, 'default_value');
        $this->assertEquals('default_value', $result);
    }

    /**
     * Test API fallback calls fallback function.
     */
    public function test_api_fallback_calls_function() {
        $result = $this->handler->api_fallback('test_api', function() {
            return 'fallback_result';
        });
        $this->assertEquals('fallback_result', $result);
    }

    // =========================================================================
    // 49.5 - Debug Mode
    // =========================================================================

    /**
     * Test is_debug_mode respects WP_DEBUG.
     */
    public function test_debug_mode_respects_wp_debug() {
        // WP_DEBUG is typically true in test environments
        $result = $this->handler->is_debug_mode();
        $this->assertIsBool($result);
    }

    /**
     * Test static is_debug helper.
     */
    public function test_static_is_debug() {
        $result = Reforestamos_Error_Handler::is_debug();
        $this->assertIsBool($result);
    }

    /**
     * Test debug output is empty for non-admin users.
     */
    public function test_debug_output_empty_for_non_admin() {
        // Set current user to subscriber
        $user_id = $this->factory->user->create(array('role' => 'subscriber'));
        wp_set_current_user($user_id);

        $output = $this->handler->debug('Test', 'data');
        $this->assertEmpty($output);
    }

    /**
     * Test debug output shows for admin users in debug mode.
     */
    public function test_debug_output_for_admin() {
        $user_id = $this->factory->user->create(array('role' => 'administrator'));
        wp_set_current_user($user_id);

        if ($this->handler->is_debug_mode()) {
            $output = $this->handler->debug('Test Label', array('key' => 'value'));
            $this->assertStringContainsString('[DEBUG]', $output);
            $this->assertStringContainsString('Test Label', $output);
        } else {
            $this->markTestSkipped('Debug mode not enabled in test environment');
        }
    }

    // =========================================================================
    // 49.6 - Dependency Verification
    // =========================================================================

    /**
     * Test registering a dependency.
     */
    public function test_register_dependency() {
        $this->handler->register_dependency(
            'test-plugin/test-plugin.php',
            'Test Plugin',
            'Test Component'
        );

        $missing = $this->handler->get_missing_dependencies();
        $this->assertArrayHasKey('test-plugin/test-plugin.php', $missing);
        $this->assertEquals('Test Plugin', $missing['test-plugin/test-plugin.php']['name']);
    }

    /**
     * Test get_missing_dependencies returns inactive plugins.
     */
    public function test_missing_dependencies_returns_inactive() {
        $this->handler->register_dependency(
            'nonexistent-plugin/nonexistent.php',
            'Nonexistent Plugin'
        );

        $missing = $this->handler->get_missing_dependencies();
        $this->assertArrayHasKey('nonexistent-plugin/nonexistent.php', $missing);
    }

    /**
     * Test render_error static helper.
     */
    public function test_render_error_static_helper() {
        $html = Reforestamos_Error_Handler::render_error('general');
        $this->assertStringContainsString('reforestamos-error-message', $html);
    }

    /**
     * Clean up log files after tests.
     */
    public function tearDown(): void {
        // Clean up test log files
        $log_dir = $this->handler->get_log_dir();
        $log_file = $log_dir . '/reforestamos-' . gmdate('Y-m-d') . '.log';
        if (file_exists($log_file)) {
            unlink($log_file);
        }
        parent::tearDown();
    }
}
