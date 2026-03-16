<?php
/**
 * Tests for Security Sanitization and Validation
 *
 * Tests the Reforestamos_Security class methods for input sanitization,
 * output escaping, and validation functions.
 *
 * Validates: Requirements 21.2, 21.7
 *
 * @package Reforestamos_Tests
 */

class Test_Security_Sanitization extends Reforestamos_Test_Case {

	/**
	 * Ensure the security class is loaded.
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( 'Reforestamos_Security' ) ) {
			$security_file = dirname( dirname( __DIR__ ) ) . '/inc/security.php';
			if ( file_exists( $security_file ) ) {
				require_once $security_file;
			}
		}
	}

	// -------------------------------------------------------
	// Text Sanitization
	// -------------------------------------------------------

	public function test_sanitize_text_strips_html_tags() {
		$input  = '<script>alert("xss")</script>Hello';
		$result = Reforestamos_Security::sanitize_text( $input );
		$this->assertStringNotContainsString( '<script>', $result );
		$this->assertStringContainsString( 'Hello', $result );
	}

	public function test_sanitize_text_trims_whitespace() {
		$result = Reforestamos_Security::sanitize_text( '  hello  ' );
		$this->assertEquals( 'hello', $result );
	}

	public function test_sanitize_text_handles_empty_string() {
		$this->assertEquals( '', Reforestamos_Security::sanitize_text( '' ) );
	}

	// -------------------------------------------------------
	// Textarea Sanitization
	// -------------------------------------------------------

	public function test_sanitize_textarea_preserves_newlines() {
		$input  = "Line 1\nLine 2\nLine 3";
		$result = Reforestamos_Security::sanitize_textarea( $input );
		$this->assertStringContainsString( "\n", $result );
	}

	public function test_sanitize_textarea_strips_dangerous_tags() {
		$input  = '<script>alert("xss")</script>Safe text';
		$result = Reforestamos_Security::sanitize_textarea( $input );
		$this->assertStringNotContainsString( '<script>', $result );
	}

	// -------------------------------------------------------
	// Email Sanitization
	// -------------------------------------------------------

	public function test_sanitize_email_valid() {
		$this->assertEquals( 'user@example.com', Reforestamos_Security::sanitize_email( 'user@example.com' ) );
	}

	public function test_sanitize_email_strips_invalid_chars() {
		$result = Reforestamos_Security::sanitize_email( 'user<script>@example.com' );
		$this->assertStringNotContainsString( '<script>', $result );
	}

	public function test_sanitize_email_empty_for_invalid() {
		$result = Reforestamos_Security::sanitize_email( 'not-an-email' );
		$this->assertEquals( '', $result );
	}

	// -------------------------------------------------------
	// URL Sanitization
	// -------------------------------------------------------

	public function test_sanitize_url_valid() {
		$this->assertEquals( 'https://example.com', Reforestamos_Security::sanitize_url( 'https://example.com' ) );
	}

	public function test_sanitize_url_strips_javascript_protocol() {
		$result = Reforestamos_Security::sanitize_url( 'javascript:alert(1)' );
		$this->assertStringNotContainsString( 'javascript:', $result );
	}

	// -------------------------------------------------------
	// HTML Sanitization
	// -------------------------------------------------------

	public function test_sanitize_html_allows_safe_tags() {
		$input  = '<p>Hello <strong>world</strong></p>';
		$result = Reforestamos_Security::sanitize_html( $input );
		$this->assertStringContainsString( '<p>', $result );
		$this->assertStringContainsString( '<strong>', $result );
	}

	public function test_sanitize_html_strips_script_tags() {
		$input  = '<p>Safe</p><script>alert("xss")</script>';
		$result = Reforestamos_Security::sanitize_html( $input );
		$this->assertStringNotContainsString( '<script>', $result );
		$this->assertStringContainsString( '<p>Safe</p>', $result );
	}

	public function test_sanitize_html_strips_event_handlers() {
		$input  = '<img src="x" onerror="alert(1)">';
		$result = Reforestamos_Security::sanitize_html( $input );
		$this->assertStringNotContainsString( 'onerror', $result );
	}

	// -------------------------------------------------------
	// Array Sanitization
	// -------------------------------------------------------

	public function test_sanitize_array_cleans_values() {
		$input  = array( '<b>bold</b>', '<script>xss</script>' );
		$result = Reforestamos_Security::sanitize_array( $input );
		$this->assertStringNotContainsString( '<script>', $result[1] );
	}

	public function test_sanitize_array_handles_nested_arrays() {
		$input  = array( 'level1' => array( 'level2' => '<script>xss</script>' ) );
		$result = Reforestamos_Security::sanitize_array( $input );
		$this->assertStringNotContainsString( '<script>', $result['level1']['level2'] );
	}

	public function test_sanitize_array_returns_empty_for_non_array() {
		$this->assertEquals( array(), Reforestamos_Security::sanitize_array( 'not-an-array' ) );
	}

	// -------------------------------------------------------
	// Output Escaping
	// -------------------------------------------------------

	public function test_escape_html_encodes_special_chars() {
		$result = Reforestamos_Security::escape_html( '<script>alert("xss")</script>' );
		$this->assertStringNotContainsString( '<script>', $result );
		$this->assertStringContainsString( '&lt;', $result );
	}

	public function test_escape_attr_encodes_quotes() {
		$result = Reforestamos_Security::escape_attr( '" onclick="alert(1)"' );
		$this->assertStringNotContainsString( '"', $result );
	}

	public function test_escape_url_sanitizes_output() {
		$result = Reforestamos_Security::escape_url( 'https://example.com/path?q=test' );
		$this->assertStringContainsString( 'https://example.com', $result );
	}

	public function test_escape_url_blocks_javascript_protocol() {
		$result = Reforestamos_Security::escape_url( 'javascript:alert(1)' );
		$this->assertStringNotContainsString( 'javascript:', $result );
	}

	// -------------------------------------------------------
	// Validation Functions
	// -------------------------------------------------------

	public function test_validate_email_valid() {
		$this->assertTrue( Reforestamos_Security::validate_email( 'user@example.com' ) );
	}

	public function test_validate_email_invalid() {
		$this->assertFalse( Reforestamos_Security::validate_email( 'not-an-email' ) );
		$this->assertFalse( Reforestamos_Security::validate_email( '' ) );
	}

	public function test_validate_url_valid() {
		$this->assertTrue( Reforestamos_Security::validate_url( 'https://example.com' ) );
		$this->assertTrue( Reforestamos_Security::validate_url( 'http://example.com/path' ) );
	}

	public function test_validate_url_invalid() {
		$this->assertFalse( Reforestamos_Security::validate_url( 'not-a-url' ) );
		$this->assertFalse( Reforestamos_Security::validate_url( '' ) );
	}

	public function test_validate_required_with_value() {
		$this->assertTrue( Reforestamos_Security::validate_required( 'hello' ) );
		$this->assertTrue( Reforestamos_Security::validate_required( array( 1 ) ) );
	}

	public function test_validate_required_empty() {
		$this->assertFalse( Reforestamos_Security::validate_required( '' ) );
		$this->assertFalse( Reforestamos_Security::validate_required( '   ' ) );
	}

	public function test_validate_min_length() {
		$this->assertTrue( Reforestamos_Security::validate_min_length( 'hello', 3 ) );
		$this->assertFalse( Reforestamos_Security::validate_min_length( 'hi', 3 ) );
	}

	public function test_validate_max_length() {
		$this->assertTrue( Reforestamos_Security::validate_max_length( 'hi', 5 ) );
		$this->assertFalse( Reforestamos_Security::validate_max_length( 'hello world', 5 ) );
	}

	// -------------------------------------------------------
	// File Upload Validation
	// -------------------------------------------------------

	public function test_validate_file_upload_invalid_structure() {
		$result = Reforestamos_Security::validate_file_upload( array() );
		$this->assertFalse( $result['valid'] );
	}

	public function test_validate_file_upload_error_code() {
		$file   = array( 'error' => UPLOAD_ERR_INI_SIZE, 'size' => 100, 'tmp_name' => '/tmp/test' );
		$result = Reforestamos_Security::validate_file_upload( $file );
		$this->assertFalse( $result['valid'] );
	}

	public function test_validate_file_upload_exceeds_max_size() {
		$file   = array( 'error' => UPLOAD_ERR_OK, 'size' => 10000000, 'tmp_name' => '/tmp/test' );
		$result = Reforestamos_Security::validate_file_upload( $file, array(), 5242880 );
		$this->assertFalse( $result['valid'] );
		$this->assertStringContainsString( 'size', strtolower( $result['error'] ) );
	}

	// -------------------------------------------------------
	// Filename Sanitization
	// -------------------------------------------------------

	public function test_sanitize_filename_removes_special_chars() {
		$result = Reforestamos_Security::sanitize_filename( '../../../etc/passwd' );
		$this->assertStringNotContainsString( '..', $result );
	}

	// -------------------------------------------------------
	// GET/POST Data Retrieval
	// -------------------------------------------------------

	public function test_get_post_returns_default_when_missing() {
		$this->assertEquals( 'default', Reforestamos_Security::get_post( 'nonexistent', 'default' ) );
	}

	public function test_get_query_returns_default_when_missing() {
		$this->assertEquals( 'default', Reforestamos_Security::get_query( 'nonexistent', 'default' ) );
	}

	public function test_get_post_sanitizes_value() {
		$_POST['test_field'] = '<script>alert("xss")</script>Hello';
		$result              = Reforestamos_Security::get_post( 'test_field' );
		$this->assertStringNotContainsString( '<script>', $result );
		$this->assertStringContainsString( 'Hello', $result );
		unset( $_POST['test_field'] );
	}

	public function test_get_query_sanitizes_value() {
		$_GET['test_field'] = '<script>alert("xss")</script>Hello';
		$result             = Reforestamos_Security::get_query( 'test_field' );
		$this->assertStringNotContainsString( '<script>', $result );
		$this->assertStringContainsString( 'Hello', $result );
		unset( $_GET['test_field'] );
	}

	public function test_get_post_sanitizes_array_value() {
		$_POST['test_array'] = array( '<b>bold</b>', '<script>xss</script>' );
		$result              = Reforestamos_Security::get_post( 'test_array' );
		$this->assertIsArray( $result );
		$this->assertStringNotContainsString( '<script>', $result[1] );
		unset( $_POST['test_array'] );
	}

	// -------------------------------------------------------
	// Encryption
	// -------------------------------------------------------

	public function test_encrypt_decrypt_roundtrip() {
		$original  = 'my-secret-api-key-12345';
		$encrypted = Reforestamos_Security::encrypt( $original );
		$this->assertNotEquals( $original, $encrypted );

		$decrypted = Reforestamos_Security::decrypt( $encrypted );
		$this->assertEquals( $original, $decrypted );
	}

	public function test_encrypt_empty_string() {
		$this->assertEquals( '', Reforestamos_Security::encrypt( '' ) );
	}

	public function test_decrypt_empty_string() {
		$this->assertEquals( '', Reforestamos_Security::decrypt( '' ) );
	}

	// -------------------------------------------------------
	// Rate Limiting
	// -------------------------------------------------------

	public function test_rate_limit_allows_first_attempt() {
		$this->assertTrue( Reforestamos_Security::check_rate_limit( '127.0.0.1', 'test_action', 3, 300 ) );
	}

	public function test_rate_limit_blocks_after_max_attempts() {
		$identifier = 'test-ip-' . wp_rand();
		$action     = 'test_rate_limit';

		// Use up all attempts
		for ( $i = 0; $i < 5; $i++ ) {
			Reforestamos_Security::check_rate_limit( $identifier, $action, 5, 300 );
		}

		// Next attempt should be blocked
		$this->assertFalse( Reforestamos_Security::check_rate_limit( $identifier, $action, 5, 300 ) );
	}

	public function test_rate_limit_status_returns_correct_data() {
		$identifier = 'status-test-' . wp_rand();
		$action     = 'test_status';

		$status = Reforestamos_Security::get_rate_limit_status( $identifier, $action );
		$this->assertEquals( 0, $status['attempts'] );
		$this->assertFalse( $status['blocked'] );
	}

	public function test_rate_limit_reset_clears_counter() {
		$identifier = 'reset-test-' . wp_rand();
		$action     = 'test_reset';

		// Make some attempts
		Reforestamos_Security::check_rate_limit( $identifier, $action, 5, 300 );
		Reforestamos_Security::check_rate_limit( $identifier, $action, 5, 300 );

		// Reset
		Reforestamos_Security::reset_rate_limit( $identifier, $action );

		$status = Reforestamos_Security::get_rate_limit_status( $identifier, $action );
		$this->assertEquals( 0, $status['attempts'] );
	}
}
