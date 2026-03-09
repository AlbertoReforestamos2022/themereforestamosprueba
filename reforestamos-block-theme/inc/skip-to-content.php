<?php
/**
 * Skip to Content Link
 * Accessibility feature for keyboard navigation
 * 
 * @package Reforestamos
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add skip to content link
 */
function reforestamos_skip_to_content() {
    ?>
    <a href="#main-content" class="skip-to-content">
        <?php esc_html_e('Skip to main content', 'reforestamos'); ?>
    </a>
    <?php
}
add_action('wp_body_open', 'reforestamos_skip_to_content', 1);

/**
 * Add main content ID to content area
 */
function reforestamos_add_main_content_id($content) {
    if (is_singular() || is_archive() || is_home()) {
        $content = '<div id="main-content" tabindex="-1">' . $content . '</div>';
    }
    return $content;
}
add_filter('the_content', 'reforestamos_add_main_content_id', 1);

/**
 * Add ARIA landmarks
 */
function reforestamos_add_aria_landmarks() {
    ?>
    <script>
    (function() {
        // Add role="main" to main content area
        const mainContent = document.getElementById('main-content');
        if (mainContent && !mainContent.closest('[role="main"]')) {
            const mainWrapper = mainContent.closest('main') || mainContent.parentElement;
            if (mainWrapper) {
                mainWrapper.setAttribute('role', 'main');
                mainWrapper.setAttribute('aria-label', '<?php esc_attr_e('Main content', 'reforestamos'); ?>');
            }
        }

        // Add role="navigation" to nav elements without it
        document.querySelectorAll('nav:not([role])').forEach(function(nav) {
            nav.setAttribute('role', 'navigation');
        });

        // Add role="banner" to header
        const header = document.querySelector('header:not([role])');
        if (header) {
            header.setAttribute('role', 'banner');
        }

        // Add role="contentinfo" to footer
        const footer = document.querySelector('footer:not([role])');
        if (footer) {
            footer.setAttribute('role', 'contentinfo');
        }
    })();
    </script>
    <?php
}
add_action('wp_footer', 'reforestamos_add_aria_landmarks', 100);

/**
 * Add language attribute to HTML tag
 */
function reforestamos_language_attributes($output) {
    $current_lang = function_exists('pll_current_language') ? pll_current_language() : get_bloginfo('language');
    return 'lang="' . esc_attr($current_lang) . '" dir="' . (is_rtl() ? 'rtl' : 'ltr') . '"';
}
add_filter('language_attributes', 'reforestamos_language_attributes');

/**
 * Add accessibility statement link to footer
 */
function reforestamos_accessibility_statement_link() {
    $accessibility_page = get_option('reforestamos_accessibility_page');
    if ($accessibility_page) {
        ?>
        <div class="accessibility-statement">
            <a href="<?php echo esc_url(get_permalink($accessibility_page)); ?>">
                <?php esc_html_e('Accessibility Statement', 'reforestamos'); ?>
            </a>
        </div>
        <?php
    }
}
add_action('wp_footer', 'reforestamos_accessibility_statement_link', 99);
