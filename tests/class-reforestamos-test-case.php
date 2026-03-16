<?php
/**
 * Base Test Case for Reforestamos Project
 *
 * Provides common utilities for all test classes.
 *
 * @package Reforestamos_Tests
 */

/**
 * Base test case class with shared helpers.
 */
class Reforestamos_Test_Case extends WP_UnitTestCase {

    /**
     * Create a test post of a given type.
     *
     * @param string $post_type Post type slug.
     * @param array  $args      Optional post arguments.
     * @return int Post ID.
     */
    protected function create_test_post( $post_type = 'post', $args = array() ) {
        $defaults = array(
            'post_type'   => $post_type,
            'post_status' => 'publish',
            'post_title'  => 'Test ' . ucfirst( $post_type ),
        );

        return $this->factory->post->create( array_merge( $defaults, $args ) );
    }

    /**
     * Create a test user with a given role.
     *
     * @param string $role WordPress role.
     * @return int User ID.
     */
    protected function create_test_user( $role = 'editor' ) {
        return $this->factory->user->create( array( 'role' => $role ) );
    }

    /**
     * Assert that a post type is registered.
     *
     * @param string $post_type Post type slug.
     */
    protected function assertPostTypeRegistered( $post_type ) {
        $this->assertTrue(
            post_type_exists( $post_type ),
            "Post type '{$post_type}' should be registered."
        );
    }

    /**
     * Assert that a taxonomy is registered.
     *
     * @param string $taxonomy Taxonomy slug.
     */
    protected function assertTaxonomyRegistered( $taxonomy ) {
        $this->assertTrue(
            taxonomy_exists( $taxonomy ),
            "Taxonomy '{$taxonomy}' should be registered."
        );
    }
}
