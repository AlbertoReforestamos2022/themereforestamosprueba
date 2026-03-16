<?php
/**
 * Tests for Comunicacion Plugin setup.
 *
 * @package Reforestamos_Comunicacion_Tests
 */

class Test_Comunicacion_Setup extends Reforestamos_Test_Case {

    /**
     * Test that the plugin main class exists.
     */
    public function test_plugin_class_exists() {
        $this->assertTrue( class_exists( 'Reforestamos_Comunicacion' ) );
    }

    /**
     * Test that newsletter shortcode is registered.
     */
    public function test_newsletter_shortcode_registered() {
        $this->assertTrue( shortcode_exists( 'newsletter-subscribe' ) );
    }

    /**
     * Test that contact form shortcode is registered.
     */
    public function test_contact_form_shortcode_registered() {
        $this->assertTrue( shortcode_exists( 'contact-form' ) );
    }
}
