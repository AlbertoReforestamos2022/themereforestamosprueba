<?php
/**
 * Block Patterns Registration
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register block pattern categories
 */
function reforestamos_register_pattern_categories() {
    $categories = array(
        'reforestamos-headers' => array(
            'label' => __('Reforestamos Headers', 'reforestamos'),
        ),
        'reforestamos-content' => array(
            'label' => __('Reforestamos Content', 'reforestamos'),
        ),
        'reforestamos-cta' => array(
            'label' => __('Reforestamos Call to Action', 'reforestamos'),
        ),
        'reforestamos-footers' => array(
            'label' => __('Reforestamos Footers', 'reforestamos'),
        ),
        'reforestamos-team' => array(
            'label' => __('Reforestamos Team', 'reforestamos'),
        ),
        'reforestamos-contact' => array(
            'label' => __('Reforestamos Contact', 'reforestamos'),
        ),
    );

    foreach ($categories as $slug => $args) {
        register_block_pattern_category($slug, $args);
    }
}
add_action('init', 'reforestamos_register_pattern_categories');

/**
 * Register block patterns
 */
function reforestamos_register_patterns() {
    $patterns_dir = REFORESTAMOS_THEME_DIR . '/patterns/';
    
    if (!is_dir($patterns_dir)) {
        return;
    }

    $pattern_files = glob($patterns_dir . '*.php');
    
    foreach ($pattern_files as $pattern_file) {
        $pattern_slug = basename($pattern_file, '.php');
        
        // Include the pattern file to get its content
        ob_start();
        include $pattern_file;
        $pattern_content = ob_get_clean();
        
        // Extract pattern metadata from the file
        $pattern_data = get_file_data($pattern_file, array(
            'title'       => 'Title',
            'slug'        => 'Slug',
            'description' => 'Description',
            'categories'  => 'Categories',
            'keywords'    => 'Keywords',
            'viewport'    => 'Viewport Width',
        ));
        
        // Parse categories and keywords
        $categories = !empty($pattern_data['categories']) 
            ? array_map('trim', explode(',', $pattern_data['categories'])) 
            : array('reforestamos-content');
        
        $keywords = !empty($pattern_data['keywords']) 
            ? array_map('trim', explode(',', $pattern_data['keywords'])) 
            : array();
        
        // Register the pattern
        register_block_pattern(
            'reforestamos/' . ($pattern_data['slug'] ?: $pattern_slug),
            array(
                'title'       => $pattern_data['title'] ?: ucwords(str_replace('-', ' ', $pattern_slug)),
                'description' => $pattern_data['description'] ?: '',
                'content'     => $pattern_content,
                'categories'  => $categories,
                'keywords'    => $keywords,
                'viewportWidth' => $pattern_data['viewport'] ?: 1280,
            )
        );
    }
}
add_action('init', 'reforestamos_register_patterns');
