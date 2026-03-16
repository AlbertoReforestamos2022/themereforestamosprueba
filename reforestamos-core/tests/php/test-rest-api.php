<?php
/**
 * Tests for REST API Endpoints
 *
 * Tests the custom REST API endpoints provided by the Core plugin,
 * including parameter validation, response format, and pagination.
 *
 * Validates: Requirements 21.2, 21.7
 *
 * @package Reforestamos_Core_Tests
 */

class Test_REST_API extends Reforestamos_Test_Case {

	/**
	 * REST server instance.
	 *
	 * @var WP_REST_Server
	 */
	protected $server;

	/**
	 * API namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'reforestamos/v1';

	/**
	 * Set up test fixtures.
	 */
	public function setUp(): void {
		parent::setUp();

		// Initialize REST server
		global $wp_rest_server;
		$this->server = $wp_rest_server = new WP_REST_Server();
		do_action( 'rest_api_init' );
	}

	/**
	 * Tear down test fixtures.
	 */
	public function tearDown(): void {
		global $wp_rest_server;
		$wp_rest_server = null;
		parent::tearDown();
	}

	// -------------------------------------------------------
	// Route Registration
	// -------------------------------------------------------

	public function test_empresas_route_registered() {
		$routes = $this->server->get_routes();
		$this->assertArrayHasKey( '/' . $this->namespace . '/empresas', $routes );
	}

	public function test_eventos_upcoming_route_registered() {
		$routes = $this->server->get_routes();
		$this->assertArrayHasKey( '/' . $this->namespace . '/eventos/upcoming', $routes );
	}

	public function test_integrantes_route_registered() {
		$routes = $this->server->get_routes();
		$this->assertArrayHasKey( '/' . $this->namespace . '/integrantes', $routes );
	}

	// -------------------------------------------------------
	// Empresas Endpoint
	// -------------------------------------------------------

	public function test_get_empresas_returns_200() {
		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );
	}

	public function test_get_empresas_returns_array() {
		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();
		$this->assertIsArray( $data );
	}

	public function test_get_empresas_returns_created_post() {
		$post_id = $this->create_test_post( 'empresas', array( 'post_title' => 'Empresa Test' ) );
		update_post_meta( $post_id, 'empresa_categoria', 'tecnologia' );
		update_post_meta( $post_id, 'empresa_arboles', '500' );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$this->assertGreaterThanOrEqual( 1, count( $data ) );

		$found = false;
		foreach ( $data as $empresa ) {
			if ( $empresa['id'] === $post_id ) {
				$found = true;
				$this->assertEquals( 'Empresa Test', $empresa['title'] );
				$this->assertArrayHasKey( 'meta', $empresa );
				$this->assertEquals( 'tecnologia', $empresa['meta']['empresa_categoria'] );
				$this->assertEquals( '500', $empresa['meta']['empresa_arboles'] );
				break;
			}
		}
		$this->assertTrue( $found, 'Created empresa should appear in API response' );
	}

	public function test_get_empresas_pagination_headers() {
		// Create multiple posts
		for ( $i = 0; $i < 3; $i++ ) {
			$this->create_test_post( 'empresas', array( 'post_title' => "Empresa {$i}" ) );
		}

		$request = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$request->set_param( 'per_page', 2 );
		$request->set_param( 'page', 1 );
		$response = $this->server->dispatch( $request );

		$headers = $response->get_headers();
		$this->assertArrayHasKey( 'X-WP-Total', $headers );
		$this->assertArrayHasKey( 'X-WP-TotalPages', $headers );
		$this->assertGreaterThanOrEqual( 3, (int) $headers['X-WP-Total'] );
	}

	public function test_get_empresas_filter_by_categoria() {
		$post_id1 = $this->create_test_post( 'empresas', array( 'post_title' => 'Tech Company' ) );
		update_post_meta( $post_id1, 'empresa_categoria', 'tecnologia' );

		$post_id2 = $this->create_test_post( 'empresas', array( 'post_title' => 'Food Company' ) );
		update_post_meta( $post_id2, 'empresa_categoria', 'alimentos' );

		$request = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$request->set_param( 'categoria', 'tecnologia' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		foreach ( $data as $empresa ) {
			$this->assertEquals( 'tecnologia', $empresa['meta']['empresa_categoria'] );
		}
	}

	public function test_get_empresas_response_structure() {
		$post_id = $this->create_test_post( 'empresas', array( 'post_title' => 'Structure Test' ) );
		update_post_meta( $post_id, 'empresa_url', 'https://example.com' );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$this->assertNotEmpty( $data );
		$empresa = $data[0];

		$this->assertArrayHasKey( 'id', $empresa );
		$this->assertArrayHasKey( 'title', $empresa );
		$this->assertArrayHasKey( 'content', $empresa );
		$this->assertArrayHasKey( 'link', $empresa );
		$this->assertArrayHasKey( 'meta', $empresa );
		$this->assertArrayHasKey( 'empresa_logo', $empresa['meta'] );
		$this->assertArrayHasKey( 'empresa_url', $empresa['meta'] );
		$this->assertArrayHasKey( 'empresa_categoria', $empresa['meta'] );
		$this->assertArrayHasKey( 'empresa_anio', $empresa['meta'] );
		$this->assertArrayHasKey( 'empresa_arboles', $empresa['meta'] );
	}

	// -------------------------------------------------------
	// Eventos Upcoming Endpoint
	// -------------------------------------------------------

	public function test_get_upcoming_eventos_returns_200() {
		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/eventos/upcoming' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );
	}

	public function test_get_upcoming_eventos_only_future() {
		// Create a future event
		$future_id = $this->create_test_post( 'eventos', array( 'post_title' => 'Future Event' ) );
		update_post_meta( $future_id, 'evento_fecha', strtotime( '+30 days' ) );
		update_post_meta( $future_id, 'evento_ubicacion', 'CDMX' );

		// Create a past event
		$past_id = $this->create_test_post( 'eventos', array( 'post_title' => 'Past Event' ) );
		update_post_meta( $past_id, 'evento_fecha', strtotime( '-30 days' ) );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/eventos/upcoming' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$ids = wp_list_pluck( $data, 'id' );
		$this->assertContains( $future_id, $ids, 'Future event should be in upcoming' );
		$this->assertNotContains( $past_id, $ids, 'Past event should not be in upcoming' );
	}

	public function test_get_upcoming_eventos_response_structure() {
		$post_id = $this->create_test_post( 'eventos', array( 'post_title' => 'Event Structure' ) );
		$future  = strtotime( '+7 days' );
		update_post_meta( $post_id, 'evento_fecha', $future );
		update_post_meta( $post_id, 'evento_ubicacion', 'Monterrey' );
		update_post_meta( $post_id, 'evento_capacidad', '100' );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/eventos/upcoming' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$this->assertNotEmpty( $data );
		$evento = $data[0];

		$this->assertArrayHasKey( 'id', $evento );
		$this->assertArrayHasKey( 'title', $evento );
		$this->assertArrayHasKey( 'meta', $evento );
		$this->assertArrayHasKey( 'evento_fecha', $evento['meta'] );
		$this->assertArrayHasKey( 'evento_ubicacion', $evento['meta'] );
		$this->assertArrayHasKey( 'evento_capacidad', $evento['meta'] );
	}

	public function test_get_upcoming_eventos_pagination() {
		for ( $i = 0; $i < 5; $i++ ) {
			$post_id = $this->create_test_post( 'eventos', array( 'post_title' => "Event {$i}" ) );
			update_post_meta( $post_id, 'evento_fecha', strtotime( "+{$i} days", strtotime( '+1 day' ) ) );
		}

		$request = new WP_REST_Request( 'GET', '/' . $this->namespace . '/eventos/upcoming' );
		$request->set_param( 'per_page', 2 );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$this->assertLessThanOrEqual( 2, count( $data ) );
	}

	// -------------------------------------------------------
	// Integrantes Endpoint
	// -------------------------------------------------------

	public function test_get_integrantes_returns_200() {
		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/integrantes' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );
	}

	public function test_get_integrantes_returns_created_member() {
		$post_id = $this->create_test_post( 'integrantes', array( 'post_title' => 'Juan Pérez' ) );
		update_post_meta( $post_id, 'integrante_cargo', 'Director' );
		update_post_meta( $post_id, 'integrante_email', 'juan@example.com' );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/integrantes' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$found = false;
		foreach ( $data as $integrante ) {
			if ( $integrante['id'] === $post_id ) {
				$found = true;
				$this->assertEquals( 'Juan Pérez', $integrante['name'] );
				$this->assertEquals( 'Director', $integrante['meta']['integrante_cargo'] );
				$this->assertEquals( 'juan@example.com', $integrante['meta']['integrante_email'] );
				break;
			}
		}
		$this->assertTrue( $found, 'Created integrante should appear in API response' );
	}

	public function test_get_integrantes_response_structure() {
		$post_id = $this->create_test_post( 'integrantes', array( 'post_title' => 'Member Test' ) );
		update_post_meta( $post_id, 'integrante_cargo', 'Coordinador' );
		update_post_meta( $post_id, 'integrante_redes', array( 'twitter' => 'https://twitter.com/test' ) );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/integrantes' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$this->assertNotEmpty( $data );
		$member = $data[0];

		$this->assertArrayHasKey( 'id', $member );
		$this->assertArrayHasKey( 'name', $member );
		$this->assertArrayHasKey( 'meta', $member );
		$this->assertArrayHasKey( 'integrante_cargo', $member['meta'] );
		$this->assertArrayHasKey( 'integrante_email', $member['meta'] );
		$this->assertArrayHasKey( 'integrante_redes', $member['meta'] );
	}

	// -------------------------------------------------------
	// Input Sanitization in API
	// -------------------------------------------------------

	public function test_empresas_endpoint_sanitizes_categoria_param() {
		$request = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$request->set_param( 'categoria', '<script>alert(1)</script>' );
		$response = $this->server->dispatch( $request );
		// Should not error out - sanitize_callback handles it
		$this->assertEquals( 200, $response->get_status() );
	}

	public function test_empresas_endpoint_sanitizes_per_page_param() {
		$request = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$request->set_param( 'per_page', -1 );
		$response = $this->server->dispatch( $request );
		// absint should convert negative to 0 or handle gracefully
		$this->assertEquals( 200, $response->get_status() );
	}

	public function test_eventos_endpoint_sanitizes_ubicacion_param() {
		$request = new WP_REST_Request( 'GET', '/' . $this->namespace . '/eventos/upcoming' );
		$request->set_param( 'ubicacion', "'; DROP TABLE wp_posts; --" );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );
	}

	// -------------------------------------------------------
	// Meta Field Sanitization
	// -------------------------------------------------------

	public function test_empresas_meta_sanitizes_url() {
		$post_id = $this->create_test_post( 'empresas' );
		update_post_meta( $post_id, 'empresa_url', 'javascript:alert(1)' );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		foreach ( $data as $empresa ) {
			if ( $empresa['id'] === $post_id ) {
				$this->assertStringNotContainsString( 'javascript:', $empresa['meta']['empresa_url'] );
				break;
			}
		}
	}

	public function test_integrantes_meta_sanitizes_email() {
		$post_id = $this->create_test_post( 'integrantes' );
		update_post_meta( $post_id, 'integrante_email', 'invalid<script>@email' );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/integrantes' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		foreach ( $data as $member ) {
			if ( $member['id'] === $post_id ) {
				$this->assertStringNotContainsString( '<script>', $member['meta']['integrante_email'] );
				break;
			}
		}
	}

	// -------------------------------------------------------
	// Edge Cases
	// -------------------------------------------------------

	public function test_empresas_empty_result() {
		// With no empresas posts, should return empty array
		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$request->set_param( 'categoria', 'nonexistent-category-xyz' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();
		$this->assertIsArray( $data );
	}

	public function test_eventos_no_upcoming() {
		// Create only past events
		$post_id = $this->create_test_post( 'eventos' );
		update_post_meta( $post_id, 'evento_fecha', strtotime( '-1 year' ) );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/eventos/upcoming' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		// Past event should not appear
		$ids = wp_list_pluck( $data, 'id' );
		$this->assertNotContains( $post_id, $ids );
	}

	public function test_draft_posts_not_in_api() {
		$post_id = $this->create_test_post( 'empresas', array(
			'post_title'  => 'Draft Company',
			'post_status' => 'draft',
		) );

		$request  = new WP_REST_Request( 'GET', '/' . $this->namespace . '/empresas' );
		$response = $this->server->dispatch( $request );
		$data     = $response->get_data();

		$ids = wp_list_pluck( $data, 'id' );
		$this->assertNotContains( $post_id, $ids, 'Draft posts should not appear in API' );
	}
}
