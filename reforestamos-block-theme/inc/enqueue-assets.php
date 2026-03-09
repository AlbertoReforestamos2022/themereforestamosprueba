<?php
/**
 * Enqueue Assets
 *
 * @package Reforestamos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue Bootstrap from CDN
 */
function reforestamos_enqueue_bootstrap() {
    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        array(),
        '5.3.0'
    );
    
    // Bootstrap JS
    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
        array(),
        '5.3.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'reforestamos_enqueue_bootstrap');
add_action('enqueue_block_editor_assets', 'reforestamos_enqueue_bootstrap');

/**
 * Enqueue GLightbox for gallery blocks
 */
function reforestamos_enqueue_glightbox() {
    // GLightbox CSS
    wp_enqueue_style(
        'glightbox',
        'https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css',
        array(),
        '3.2.0'
    );
    
    // GLightbox JS
    wp_enqueue_script(
        'glightbox-js',
        'https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js',
        array(),
        '3.2.0',
        true
    );
    
    // Frontend initialization script
    if (file_exists(REFORESTAMOS_THEME_DIR . '/build/frontend.js')) {
        wp_enqueue_script(
            'reforestamos-frontend',
            REFORESTAMOS_THEME_URI . '/build/frontend.js',
            array('glightbox-js'),
            filemtime(REFORESTAMOS_THEME_DIR . '/build/frontend.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'reforestamos_enqueue_glightbox');

/**
 * Enqueue theme styles
 */
function reforestamos_enqueue_styles() {
    // Theme main stylesheet
    wp_enqueue_style(
        'reforestamos-style',
        get_stylesheet_uri(),
        array('bootstrap'),
        REFORESTAMOS_VERSION
    );
    
    // Compiled block styles (if exists)
    if (file_exists(REFORESTAMOS_THEME_DIR . '/build/style-index.css')) {
        wp_enqueue_style(
            'reforestamos-blocks-style',
            REFORESTAMOS_THEME_URI . '/build/style-index.css',
            array(),
            filemtime(REFORESTAMOS_THEME_DIR . '/build/style-index.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'reforestamos_enqueue_styles');

/**
 * Enqueue theme scripts
 */
function reforestamos_enqueue_scripts() {
    // Compiled block scripts (if exists)
    if (file_exists(REFORESTAMOS_THEME_DIR . '/build/index.js')) {
        wp_enqueue_script(
            'reforestamos-blocks-script',
            REFORESTAMOS_THEME_URI . '/build/index.js',
            array('wp-element', 'wp-blocks', 'wp-i18n'),
            filemtime(REFORESTAMOS_THEME_DIR . '/build/index.js'),
            true
        );
    }
    
    // Responsive navigation script
    if (file_exists(REFORESTAMOS_THEME_DIR . '/src/js/responsive-navigation.js')) {
        wp_enqueue_script(
            'reforestamos-responsive-nav',
            REFORESTAMOS_THEME_URI . '/src/js/responsive-navigation.js',
            array(),
            filemtime(REFORESTAMOS_THEME_DIR . '/src/js/responsive-navigation.js'),
            true
        );
    }
    
    // Eventos Próximos block frontend script
    if (file_exists(REFORESTAMOS_THEME_DIR . '/blocks/eventos-proximos/frontend.js')) {
        wp_enqueue_script(
            'reforestamos-eventos-proximos',
            REFORESTAMOS_THEME_URI . '/blocks/eventos-proximos/frontend.js',
            array(),
            filemtime(REFORESTAMOS_THEME_DIR . '/blocks/eventos-proximos/frontend.js'),
            true
        );
        
        // Pass REST API URL to JavaScript
        wp_localize_script(
            'reforestamos-eventos-proximos',
            'reforestamosData',
            array(
                'eventosApiUrl' => rest_url('wp/v2/eventos'),
                'restNonce' => wp_create_nonce('wp_rest')
            )
        );
    }
    
    // Contacto block frontend script
    if (file_exists(REFORESTAMOS_THEME_DIR . '/blocks/contacto/frontend.js')) {
        wp_enqueue_script(
            'reforestamos-contacto',
            REFORESTAMOS_THEME_URI . '/blocks/contacto/frontend.js',
            array(),
            filemtime(REFORESTAMOS_THEME_DIR . '/blocks/contacto/frontend.js'),
            true
        );
        
        // Pass AJAX URL and nonce for future Communication Plugin integration
        wp_localize_script(
            'reforestamos-contacto',
            'reforestamosContactForm',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('reforestamos_contact_form')
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'reforestamos_enqueue_scripts');

/**
 * Enqueue block editor assets
 */
function reforestamos_enqueue_block_editor_assets() {
    // Editor scripts
    if (file_exists(REFORESTAMOS_THEME_DIR . '/build/index.js')) {
        wp_enqueue_script(
            'reforestamos-blocks-editor',
            REFORESTAMOS_THEME_URI . '/build/index.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
            filemtime(REFORESTAMOS_THEME_DIR . '/build/index.js')
        );
    }
    
    // Editor styles
    if (file_exists(REFORESTAMOS_THEME_DIR . '/build/style-index.css')) {
        wp_enqueue_style(
            'reforestamos-blocks-editor-style',
            REFORESTAMOS_THEME_URI . '/build/style-index.css',
            array('bootstrap'),
            filemtime(REFORESTAMOS_THEME_DIR . '/build/style-index.css')
        );
    }
}
add_action('enqueue_block_editor_assets', 'reforestamos_enqueue_block_editor_assets');
