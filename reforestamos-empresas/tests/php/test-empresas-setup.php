<?php
/**
 * Tests for Empresas Plugin setup.
 *
 * @package Reforestamos_Empresas_Tests
 */

class Test_Empresas_Setup extends Reforestamos_Test_Case {

    /**
     * Test that the plugin main class exists.
     */
    public function test_plugin_class_exists() {
        $this->assertTrue(
            class_exists( 'Reforestamos_Empresas' )
            || class_exists( 'Reforestamos_Company_Manager' )
        );
    }

    /**
     * Test that companies-grid shortcode is registered.
     */
    public function test_companies_grid_shortcode_registered() {
        $this->assertTrue( shortcode_exists( 'companies-grid' ) );
    }
}
