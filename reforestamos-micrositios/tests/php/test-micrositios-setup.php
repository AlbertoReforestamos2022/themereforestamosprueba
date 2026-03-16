<?php
/**
 * Tests for Micrositios Plugin setup.
 *
 * @package Reforestamos_Micrositios_Tests
 */

class Test_Micrositios_Setup extends Reforestamos_Test_Case {

    /**
     * Test that the plugin main class exists.
     */
    public function test_plugin_class_exists() {
        $this->assertTrue( class_exists( 'Reforestamos_Micrositios' ) );
    }

    /**
     * Test that shortcodes are registered.
     */
    public function test_shortcodes_registered() {
        $this->assertTrue( shortcode_exists( 'arboles-ciudades' ) );
    }
}
