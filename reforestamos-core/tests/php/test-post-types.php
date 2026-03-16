<?php
/**
 * Tests for Core Plugin Custom Post Types.
 *
 * @package Reforestamos_Core_Tests
 */

class Test_Core_Post_Types extends Reforestamos_Test_Case {

    /**
     * Test that Empresas CPT is registered.
     */
    public function test_empresas_cpt_registered() {
        $this->assertPostTypeRegistered( 'empresas' );
    }

    /**
     * Test that Eventos CPT is registered.
     */
    public function test_eventos_cpt_registered() {
        $this->assertPostTypeRegistered( 'eventos' );
    }

    /**
     * Test that Empresas CPT supports REST API.
     */
    public function test_empresas_cpt_rest_enabled() {
        $post_type = get_post_type_object( 'empresas' );
        $this->assertTrue( $post_type->show_in_rest );
    }

    /**
     * Test creating an Empresa post.
     */
    public function test_create_empresa_post() {
        $post_id = $this->create_test_post( 'empresas' );
        $this->assertGreaterThan( 0, $post_id );

        $post = get_post( $post_id );
        $this->assertEquals( 'empresas', $post->post_type );
    }
}
