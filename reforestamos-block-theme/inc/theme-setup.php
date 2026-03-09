<?php
/**
 * Theme Setup Functions
 *
 * @package Reforestamos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Google Fonts
 */
function reforestamos_google_fonts() {
    wp_enqueue_style(
        'reforestamos-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'reforestamos_google_fonts');
add_action('enqueue_block_editor_assets', 'reforestamos_google_fonts');

/**
 * Add Material Icons and Font Awesome
 */
function reforestamos_icon_fonts() {
    // Material Icons
    wp_enqueue_style(
        'material-icons',
        'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined',
        array(),
        null
    );

    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );
}
add_action('wp_enqueue_scripts', 'reforestamos_icon_fonts');
add_action('enqueue_block_editor_assets', 'reforestamos_icon_fonts');

/**
 * Navbar scroll effect script
 */
function reforestamos_navbar_scroll_script() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navbar = document.querySelector('.navbar-custom');
            
            if (navbar) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'reforestamos_navbar_scroll_script');
