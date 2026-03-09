<?php
/**
 * Search Functionality
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customize search query to include custom post types
 */
function reforestamos_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Get post type filter from URL
        $post_type_filter = isset($_GET['post_type_filter']) ? sanitize_text_field($_GET['post_type_filter']) : '';
        
        if ($post_type_filter && $post_type_filter !== 'all') {
            $query->set('post_type', $post_type_filter);
        } else {
            // Include all public post types in search
            $query->set('post_type', array('post', 'page', 'empresas', 'eventos'));
        }
        
        // Set posts per page
        $query->set('posts_per_page', 10);
        
        // Handle date filter
        $date_filter = isset($_GET['date_filter']) ? sanitize_text_field($_GET['date_filter']) : '';
        if ($date_filter) {
            switch ($date_filter) {
                case 'last_week':
                    $query->set('date_query', array(
                        array(
                            'after' => '1 week ago'
                        )
                    ));
                    break;
                case 'last_month':
                    $query->set('date_query', array(
                        array(
                            'after' => '1 month ago'
                        )
                    ));
                    break;
                case 'last_year':
                    $query->set('date_query', array(
                        array(
                            'after' => '1 year ago'
                        )
                    ));
                    break;
            }
        }
    }
    return $query;
}
add_action('pre_get_posts', 'reforestamos_search_filter');

/**
 * Highlight search terms in content
 */
function reforestamos_highlight_search_terms($text) {
    if (is_search() && !empty(get_search_query())) {
        $search_terms = explode(' ', get_search_query());
        
        foreach ($search_terms as $term) {
            if (strlen($term) > 2) { // Only highlight terms longer than 2 characters
                $term = preg_quote($term, '/');
                $text = preg_replace(
                    '/(' . $term . ')/iu',
                    '<mark class="search-highlight">$1</mark>',
                    $text
                );
            }
        }
    }
    
    return $text;
}
add_filter('the_excerpt', 'reforestamos_highlight_search_terms');
add_filter('the_title', 'reforestamos_highlight_search_terms');

/**
 * Get search excerpt with highlighted terms
 */
function reforestamos_get_search_excerpt($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    $excerpt = '';
    
    if (has_excerpt($post_id)) {
        $excerpt = get_the_excerpt($post_id);
    } else {
        $content = strip_shortcodes($post->post_content);
        $content = wp_strip_all_tags($content);
        $excerpt = wp_trim_words($content, 30, '...');
    }
    
    return reforestamos_highlight_search_terms($excerpt);
}

/**
 * Log search queries for analytics
 */
function reforestamos_log_search_query() {
    if (is_search() && !empty(get_search_query())) {
        $search_term = sanitize_text_field(get_search_query());
        $results_count = $GLOBALS['wp_query']->found_posts;
        
        // Get or create search log option
        $search_log = get_option('reforestamos_search_log', array());
        
        // Add new search entry
        $search_log[] = array(
            'term' => $search_term,
            'results' => $results_count,
            'timestamp' => current_time('mysql'),
            'user_ip' => reforestamos_get_user_ip(),
            'language' => reforestamos_get_current_language()
        );
        
        // Keep only last 1000 searches
        if (count($search_log) > 1000) {
            $search_log = array_slice($search_log, -1000);
        }
        
        update_option('reforestamos_search_log', $search_log);
    }
}
add_action('wp', 'reforestamos_log_search_query');

/**
 * Get user IP address (anonymized for GDPR)
 */
function reforestamos_get_user_ip() {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    // Anonymize IP for GDPR compliance
    $ip = wp_privacy_anonymize_ip($ip);
    
    return $ip;
}

/**
 * Get current language
 */
function reforestamos_get_current_language() {
    // Check if Polylang is active
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    // Check if WPML is active
    if (defined('ICL_LANGUAGE_CODE')) {
        return ICL_LANGUAGE_CODE;
    }
    
    // Default to site language
    return substr(get_locale(), 0, 2);
}

/**
 * Get search statistics
 */
function reforestamos_get_search_stats($days = 30) {
    $search_log = get_option('reforestamos_search_log', array());
    
    if (empty($search_log)) {
        return array(
            'total_searches' => 0,
            'top_terms' => array(),
            'no_results_terms' => array()
        );
    }
    
    // Filter by date range
    $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
    $recent_searches = array_filter($search_log, function($entry) use ($cutoff_date) {
        return $entry['timestamp'] >= $cutoff_date;
    });
    
    // Count term frequency
    $term_counts = array();
    $no_results = array();
    
    foreach ($recent_searches as $entry) {
        $term = $entry['term'];
        
        if (!isset($term_counts[$term])) {
            $term_counts[$term] = 0;
        }
        $term_counts[$term]++;
        
        if ($entry['results'] == 0) {
            if (!isset($no_results[$term])) {
                $no_results[$term] = 0;
            }
            $no_results[$term]++;
        }
    }
    
    // Sort by frequency
    arsort($term_counts);
    arsort($no_results);
    
    return array(
        'total_searches' => count($recent_searches),
        'top_terms' => array_slice($term_counts, 0, 10, true),
        'no_results_terms' => array_slice($no_results, 0, 10, true)
    );
}

/**
 * Add search form to header
 */
function reforestamos_add_search_to_header() {
    ?>
    <div class="header-search-wrapper">
        <?php get_search_form(); ?>
    </div>
    <?php
}

/**
 * Custom search form
 */
function reforestamos_search_form($form) {
    $form = '
    <form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">
        <label>
            <span class="screen-reader-text">' . esc_html__('Search for:', 'reforestamos') . '</span>
            <input type="search" class="search-field" placeholder="' . esc_attr__('Search...', 'reforestamos') . '" value="' . get_search_query() . '" name="s" />
        </label>
        <button type="submit" class="search-submit">
            <span class="screen-reader-text">' . esc_html__('Search', 'reforestamos') . '</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
        </button>
    </form>';
    
    return $form;
}
add_filter('get_search_form', 'reforestamos_search_form');

/**
 * Display search filters
 */
function reforestamos_display_search_filters() {
    if (!is_search()) {
        return;
    }
    
    $current_post_type = isset($_GET['post_type_filter']) ? sanitize_text_field($_GET['post_type_filter']) : 'all';
    $current_date = isset($_GET['date_filter']) ? sanitize_text_field($_GET['date_filter']) : '';
    
    ?>
    <div class="search-filters">
        <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-filters-form">
            <input type="hidden" name="s" value="<?php echo esc_attr(get_search_query()); ?>" />
            
            <div class="filter-group">
                <label for="post_type_filter"><?php esc_html_e('Content Type:', 'reforestamos'); ?></label>
                <select name="post_type_filter" id="post_type_filter">
                    <option value="all" <?php selected($current_post_type, 'all'); ?>><?php esc_html_e('All Types', 'reforestamos'); ?></option>
                    <option value="post" <?php selected($current_post_type, 'post'); ?>><?php esc_html_e('Posts', 'reforestamos'); ?></option>
                    <option value="page" <?php selected($current_post_type, 'page'); ?>><?php esc_html_e('Pages', 'reforestamos'); ?></option>
                    <option value="eventos" <?php selected($current_post_type, 'eventos'); ?>><?php esc_html_e('Events', 'reforestamos'); ?></option>
                    <option value="empresas" <?php selected($current_post_type, 'empresas'); ?>><?php esc_html_e('Companies', 'reforestamos'); ?></option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="date_filter"><?php esc_html_e('Date:', 'reforestamos'); ?></label>
                <select name="date_filter" id="date_filter">
                    <option value="" <?php selected($current_date, ''); ?>><?php esc_html_e('Any Time', 'reforestamos'); ?></option>
                    <option value="last_week" <?php selected($current_date, 'last_week'); ?>><?php esc_html_e('Last Week', 'reforestamos'); ?></option>
                    <option value="last_month" <?php selected($current_date, 'last_month'); ?>><?php esc_html_e('Last Month', 'reforestamos'); ?></option>
                    <option value="last_year" <?php selected($current_date, 'last_year'); ?>><?php esc_html_e('Last Year', 'reforestamos'); ?></option>
                </select>
            </div>
            
            <button type="submit" class="filter-button"><?php esc_html_e('Apply Filters', 'reforestamos'); ?></button>
        </form>
    </div>
    
    <?php
    // Display search stats
    global $wp_query;
    $results_count = $wp_query->found_posts;
    $search_query = get_search_query();
    
    if ($results_count > 0) {
        ?>
        <div class="search-stats">
            <p class="stats-text">
                <?php
                printf(
                    esc_html(_n(
                        'Found %1$s result for "%2$s"',
                        'Found %1$s results for "%2$s"',
                        $results_count,
                        'reforestamos'
                    )),
                    '<strong>' . number_format_i18n($results_count) . '</strong>',
                    '<strong>' . esc_html($search_query) . '</strong>'
                );
                ?>
            </p>
        </div>
        <?php
    }
}

/**
 * Display post type badge in search results
 */
function reforestamos_get_post_type_badge($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_type = get_post_type($post_id);
    $post_type_obj = get_post_type_object($post_type);
    
    if (!$post_type_obj) {
        return '';
    }
    
    $label = $post_type_obj->labels->singular_name;
    
    return '<span class="post-type-badge">' . esc_html($label) . '</span>';
}

/**
 * Enqueue search scripts
 */
function reforestamos_enqueue_search_scripts() {
    if (is_search()) {
        wp_enqueue_script(
            'reforestamos-search-filters',
            get_template_directory_uri() . '/build/search-filters.js',
            array(),
            REFORESTAMOS_VERSION,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'reforestamos_enqueue_search_scripts');
