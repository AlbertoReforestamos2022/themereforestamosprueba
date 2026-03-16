<?php
/**
 * Tests for Block Theme setup and configuration.
 *
 * @package Reforestamos_Tests
 */

class Test_Theme_Setup extends Reforestamos_Test_Case {

    /**
     * Test that the theme is active.
     */
    public function test_theme_is_active() {
        $this->assertEquals( 'reforestamos-block-theme', get_stylesheet() );
    }

    /**
     * Test that theme supports are registered.
     */
    public function test_theme_supports() {
        $this->assertTrue( current_theme_supports( 'editor-styles' ) );
        $this->assertTrue( current_theme_supports( 'wp-block-styles' ) );
        $this->assertTrue( current_theme_supports( 'responsive-embeds' ) );
        $this->assertTrue( current_theme_supports( 'post-thumbnails' ) );
        $this->assertTrue( current_theme_supports( 'title-tag' ) );
    }

    /**
     * Test that navigation menus are registered.
     */
    public function test_nav_menus_registered() {
        $menus = get_registered_nav_menus();
        $this->assertArrayHasKey( 'primary', $menus );
        $this->assertArrayHasKey( 'footer', $menus );
    }
}
