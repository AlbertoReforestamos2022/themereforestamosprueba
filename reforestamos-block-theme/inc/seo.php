<?php
/**
 * SEO and Metadata Management
 *
 * Implements comprehensive SEO features including:
 * - Semantic HTML5 markup
 * - Open Graph meta tags
 * - Twitter Card meta tags
 * - Schema.org structured data (JSON-LD)
 * - XML sitemap generation
 * - Canonical URLs
 * - Breadcrumbs with schema markup
 * - Optimized page titles
 * - Image optimization for SEO
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize SEO functionality
 */
function reforestamos_seo_init() {
    // Add meta tags to head
    add_action('wp_head', 'reforestamos_add_meta_tags', 1);
    add_action('wp_head', 'reforestamos_add_structured_data', 2);
    add_action('wp_head', 'reforestamos_add_canonical_url', 3);
    
    // Add breadcrumbs support
    add_action('reforestamos_breadcrumbs', 'reforestamos_display_breadcrumbs');
    
    // Optimize page titles
    add_filter('document_title_parts', 'reforestamos_optimize_title', 10, 1);
    
    // Add alt text to images
    add_filter('wp_get_attachment_image_attributes', 'reforestamos_add_image_attributes', 10, 3);
    
    // Generate XML sitemap
    add_action('init', 'reforestamos_register_sitemap_rewrite');
    add_action('template_redirect', 'reforestamos_generate_sitemap');
}
add_action('init', 'reforestamos_seo_init');

/**
 * Add Open Graph and Twitter Card meta tags
 */
function reforestamos_add_meta_tags() {
    // Skip if Yoast SEO or similar plugin is active
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) {
        return;
    }
    
    $meta_tags = array();
    
    // Get current page information
    $title = wp_get_document_title();
    $description = reforestamos_get_meta_description();
    $url = reforestamos_get_current_url();
    $image = reforestamos_get_featured_image_url();
    $site_name = get_bloginfo('name');
    $locale = get_locale();
    
    // Open Graph meta tags
    $meta_tags[] = '<meta property="og:locale" content="' . esc_attr($locale) . '" />';
    $meta_tags[] = '<meta property="og:type" content="' . esc_attr(reforestamos_get_og_type()) . '" />';
    $meta_tags[] = '<meta property="og:title" content="' . esc_attr($title) . '" />';
    $meta_tags[] = '<meta property="og:description" content="' . esc_attr($description) . '" />';
    $meta_tags[] = '<meta property="og:url" content="' . esc_url($url) . '" />';
    $meta_tags[] = '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />';
    
    if ($image) {
        $meta_tags[] = '<meta property="og:image" content="' . esc_url($image) . '" />';
        $meta_tags[] = '<meta property="og:image:width" content="1200" />';
        $meta_tags[] = '<meta property="og:image:height" content="630" />';
    }
    
    // Twitter Card meta tags
    $meta_tags[] = '<meta name="twitter:card" content="summary_large_image" />';
    $meta_tags[] = '<meta name="twitter:title" content="' . esc_attr($title) . '" />';
    $meta_tags[] = '<meta name="twitter:description" content="' . esc_attr($description) . '" />';
    
    if ($image) {
        $meta_tags[] = '<meta name="twitter:image" content="' . esc_url($image) . '" />';
    }
    
    // Meta description
    $meta_tags[] = '<meta name="description" content="' . esc_attr($description) . '" />';
    
    // Output meta tags
    echo "<!-- Reforestamos SEO Meta Tags -->\n";
    echo implode("\n", $meta_tags) . "\n";
    echo "<!-- / Reforestamos SEO Meta Tags -->\n\n";
}

/**
 * Get meta description for current page
 */
function reforestamos_get_meta_description() {
    $description = '';
    
    if (is_singular()) {
        global $post;
        
        // Try to get custom meta description
        $custom_description = get_post_meta($post->ID, '_reforestamos_meta_description', true);
        if ($custom_description) {
            $description = $custom_description;
        } elseif (has_excerpt()) {
            $description = get_the_excerpt();
        } else {
            $description = wp_trim_words(strip_shortcodes($post->post_content), 30, '...');
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        $description = term_description();
        if (empty($description)) {
            $description = sprintf(__('Browse %s archives', 'reforestamos'), single_term_title('', false));
        }
    } elseif (is_post_type_archive()) {
        $post_type = get_post_type_object(get_queried_object()->name);
        $description = $post_type->description ?: sprintf(__('Browse %s archives', 'reforestamos'), $post_type->labels->name);
    } elseif (is_home()) {
        $description = get_bloginfo('description');
    } else {
        $description = get_bloginfo('description');
    }
    
    // Clean and truncate description
    $description = wp_strip_all_tags($description);
    $description = str_replace(array("\r", "\n", "\t"), ' ', $description);
    $description = preg_replace('/\s+/', ' ', $description);
    $description = trim($description);
    
    // Limit to 160 characters for SEO
    if (strlen($description) > 160) {
        $description = substr($description, 0, 157) . '...';
    }
    
    return $description;
}

/**
 * Get Open Graph type for current page
 */
function reforestamos_get_og_type() {
    if (is_singular('post')) {
        return 'article';
    } elseif (is_singular('eventos')) {
        return 'event';
    } elseif (is_front_page()) {
        return 'website';
    } else {
        return 'website';
    }
}

/**
 * Get current URL
 */
function reforestamos_get_current_url() {
    global $wp;
    return home_url(add_query_arg(array(), $wp->request));
}

/**
 * Get featured image URL for meta tags
 */
function reforestamos_get_featured_image_url() {
    if (is_singular() && has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image = wp_get_attachment_image_src($image_id, 'full');
        return $image ? $image[0] : '';
    }
    
    // Fallback to site logo or default image
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $image = wp_get_attachment_image_src($custom_logo_id, 'full');
        return $image ? $image[0] : '';
    }
    
    return '';
}

/**
 * Add structured data (Schema.org JSON-LD)
 */
function reforestamos_add_structured_data() {
    // Skip if Yoast SEO or similar plugin is active
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) {
        return;
    }
    
    $schema = array();
    
    // Organization schema (always present)
    $schema[] = reforestamos_get_organization_schema();
    
    // Page-specific schema
    if (is_singular('eventos')) {
        $schema[] = reforestamos_get_event_schema();
    } elseif (is_singular('post')) {
        $schema[] = reforestamos_get_article_schema();
    } elseif (is_front_page()) {
        $schema[] = reforestamos_get_website_schema();
    }
    
    // Add breadcrumbs schema
    if (!is_front_page()) {
        $breadcrumb_schema = reforestamos_get_breadcrumb_schema();
        if ($breadcrumb_schema) {
            $schema[] = $breadcrumb_schema;
        }
    }
    
    if (!empty($schema)) {
        echo "<!-- Reforestamos Structured Data -->\n";
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode(array('@context' => 'https://schema.org', '@graph' => $schema), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo "\n" . '</script>' . "\n";
        echo "<!-- / Reforestamos Structured Data -->\n\n";
    }
}

/**
 * Get Organization schema
 */
function reforestamos_get_organization_schema() {
    $logo = '';
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $image = wp_get_attachment_image_src($custom_logo_id, 'full');
        $logo = $image ? $image[0] : '';
    }
    
    return array(
        '@type' => 'Organization',
        '@id' => home_url('/#organization'),
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => $logo,
        ),
        'description' => get_bloginfo('description'),
        'sameAs' => reforestamos_get_social_profiles(),
    );
}

/**
 * Get social media profiles
 */
function reforestamos_get_social_profiles() {
    // This should be configurable via theme customizer
    // For now, return empty array
    return array();
}

/**
 * Get Website schema
 */
function reforestamos_get_website_schema() {
    return array(
        '@type' => 'WebSite',
        '@id' => home_url('/#website'),
        'url' => home_url('/'),
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'publisher' => array(
            '@id' => home_url('/#organization'),
        ),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string',
        ),
    );
}

/**
 * Get Event schema for eventos post type
 */
function reforestamos_get_event_schema() {
    global $post;
    
    $fecha = get_post_meta($post->ID, '_reforestamos_evento_fecha', true);
    $ubicacion = get_post_meta($post->ID, '_reforestamos_evento_ubicacion', true);
    $lat = get_post_meta($post->ID, '_reforestamos_evento_lat', true);
    $lng = get_post_meta($post->ID, '_reforestamos_evento_lng', true);
    
    $schema = array(
        '@type' => 'Event',
        '@id' => get_permalink() . '#event',
        'name' => get_the_title(),
        'description' => reforestamos_get_meta_description(),
        'url' => get_permalink(),
        'startDate' => $fecha ? date('c', strtotime($fecha)) : '',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'organizer' => array(
            '@id' => home_url('/#organization'),
        ),
    );
    
    // Add image if available
    if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image = wp_get_attachment_image_src($image_id, 'full');
        if ($image) {
            $schema['image'] = $image[0];
        }
    }
    
    // Add location if available
    if ($ubicacion) {
        $location = array(
            '@type' => 'Place',
            'name' => $ubicacion,
        );
        
        if ($lat && $lng) {
            $location['geo'] = array(
                '@type' => 'GeoCoordinates',
                'latitude' => $lat,
                'longitude' => $lng,
            );
        }
        
        $schema['location'] = $location;
    }
    
    return $schema;
}

/**
 * Get Article schema for blog posts
 */
function reforestamos_get_article_schema() {
    global $post;
    
    $schema = array(
        '@type' => 'Article',
        '@id' => get_permalink() . '#article',
        'headline' => get_the_title(),
        'description' => reforestamos_get_meta_description(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author(),
        ),
        'publisher' => array(
            '@id' => home_url('/#organization'),
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink(),
        ),
    );
    
    // Add image if available
    if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image = wp_get_attachment_image_src($image_id, 'full');
        if ($image) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $image[0],
                'width' => $image[1],
                'height' => $image[2],
            );
        }
    }
    
    return $schema;
}

/**
 * Get Breadcrumb schema
 */
function reforestamos_get_breadcrumb_schema() {
    $breadcrumbs = reforestamos_get_breadcrumbs_array();
    
    if (empty($breadcrumbs)) {
        return null;
    }
    
    $items = array();
    $position = 1;
    
    foreach ($breadcrumbs as $breadcrumb) {
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $breadcrumb['title'],
            'item' => $breadcrumb['url'],
        );
    }
    
    return array(
        '@type' => 'BreadcrumbList',
        '@id' => reforestamos_get_current_url() . '#breadcrumb',
        'itemListElement' => $items,
    );
}

/**
 * Add canonical URL
 */
function reforestamos_add_canonical_url() {
    // Skip if Yoast SEO or similar plugin is active
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) {
        return;
    }
    
    $canonical_url = '';
    
    if (is_singular()) {
        $canonical_url = get_permalink();
    } elseif (is_category() || is_tag() || is_tax()) {
        $canonical_url = get_term_link(get_queried_object());
    } elseif (is_post_type_archive()) {
        $canonical_url = get_post_type_archive_link(get_post_type());
    } elseif (is_home()) {
        $canonical_url = home_url('/');
    } else {
        $canonical_url = reforestamos_get_current_url();
    }
    
    if ($canonical_url && !is_wp_error($canonical_url)) {
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
    }
}

/**
 * Optimize page titles
 */
function reforestamos_optimize_title($title_parts) {
    $site_name = get_bloginfo('name');
    $separator = '|';
    
    if (is_front_page()) {
        // Home: Site Name | Tagline
        $title_parts['title'] = $site_name;
        $title_parts['tagline'] = get_bloginfo('description');
        $title_parts['site'] = '';
    } elseif (is_singular()) {
        // Single: Post Title | Site Name
        $title_parts['site'] = $site_name;
    } elseif (is_category() || is_tag() || is_tax()) {
        // Archive: Term Name | Site Name
        $title_parts['title'] = single_term_title('', false);
        $title_parts['site'] = $site_name;
    } elseif (is_post_type_archive()) {
        // Archive: Post Type Name | Site Name
        $post_type = get_post_type_object(get_queried_object()->name);
        $title_parts['title'] = $post_type->labels->name;
        $title_parts['site'] = $site_name;
    } elseif (is_search()) {
        // Search: Search Results for "query" | Site Name
        $title_parts['title'] = sprintf(__('Search Results for "%s"', 'reforestamos'), get_search_query());
        $title_parts['site'] = $site_name;
    } elseif (is_404()) {
        // 404: Page Not Found | Site Name
        $title_parts['title'] = __('Page Not Found', 'reforestamos');
        $title_parts['site'] = $site_name;
    }
    
    return $title_parts;
}

/**
 * Add alt text and title attributes to images
 */
function reforestamos_add_image_attributes($attr, $attachment, $size) {
    // Add alt text if not present
    if (empty($attr['alt'])) {
        $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        if (empty($alt)) {
            // Fallback to image title or filename
            $alt = $attachment->post_title ?: basename(get_attached_file($attachment->ID));
        }
        $attr['alt'] = $alt;
    }
    
    // Add title attribute
    if (empty($attr['title'])) {
        $attr['title'] = $attachment->post_title;
    }
    
    return $attr;
}

/**
 * Get breadcrumbs array
 */
function reforestamos_get_breadcrumbs_array() {
    $breadcrumbs = array();
    
    // Home
    $breadcrumbs[] = array(
        'title' => __('Home', 'reforestamos'),
        'url' => home_url('/'),
    );
    
    if (is_singular()) {
        global $post;
        
        // Add post type archive if not a post
        if ($post->post_type !== 'post' && $post->post_type !== 'page') {
            $post_type = get_post_type_object($post->post_type);
            if ($post_type && $post_type->has_archive) {
                $breadcrumbs[] = array(
                    'title' => $post_type->labels->name,
                    'url' => get_post_type_archive_link($post->post_type),
                );
            }
        }
        
        // Add categories for posts
        if ($post->post_type === 'post') {
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumbs[] = array(
                    'title' => $category->name,
                    'url' => get_category_link($category->term_id),
                );
            }
        }
        
        // Add current page
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'url' => get_permalink(),
        );
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $breadcrumbs[] = array(
            'title' => $term->name,
            'url' => get_term_link($term),
        );
    } elseif (is_post_type_archive()) {
        $post_type = get_post_type_object(get_queried_object()->name);
        $breadcrumbs[] = array(
            'title' => $post_type->labels->name,
            'url' => get_post_type_archive_link(get_queried_object()->name),
        );
    } elseif (is_search()) {
        $breadcrumbs[] = array(
            'title' => sprintf(__('Search Results for "%s"', 'reforestamos'), get_search_query()),
            'url' => '',
        );
    } elseif (is_404()) {
        $breadcrumbs[] = array(
            'title' => __('Page Not Found', 'reforestamos'),
            'url' => '',
        );
    }
    
    return $breadcrumbs;
}

/**
 * Display breadcrumbs
 */
function reforestamos_display_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $breadcrumbs = reforestamos_get_breadcrumbs_array();
    
    if (empty($breadcrumbs)) {
        return;
    }
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'reforestamos') . '">';
    echo '<ol class="breadcrumb-list">';
    
    $last_index = count($breadcrumbs) - 1;
    
    foreach ($breadcrumbs as $index => $breadcrumb) {
        $is_last = ($index === $last_index);
        
        echo '<li class="breadcrumb-item' . ($is_last ? ' active' : '') . '">';
        
        if (!$is_last && !empty($breadcrumb['url'])) {
            echo '<a href="' . esc_url($breadcrumb['url']) . '">' . esc_html($breadcrumb['title']) . '</a>';
        } else {
            echo '<span>' . esc_html($breadcrumb['title']) . '</span>';
        }
        
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Register sitemap rewrite rule
 */
function reforestamos_register_sitemap_rewrite() {
    add_rewrite_rule('^sitemap\.xml$', 'index.php?reforestamos_sitemap=1', 'top');
    add_rewrite_tag('%reforestamos_sitemap%', '([^&]+)');
}

/**
 * Generate XML sitemap
 */
function reforestamos_generate_sitemap() {
    if (!get_query_var('reforestamos_sitemap')) {
        return;
    }
    
    // Skip if Yoast SEO or similar plugin is active
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) {
        return;
    }
    
    header('Content-Type: application/xml; charset=utf-8');
    
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    // Home page
    echo '<url>';
    echo '<loc>' . esc_url(home_url('/')) . '</loc>';
    echo '<lastmod>' . date('c', strtotime(get_lastpostmodified('gmt'))) . '</lastmod>';
    echo '<changefreq>daily</changefreq>';
    echo '<priority>1.0</priority>';
    echo '</url>';
    
    // Posts and pages
    $post_types = array('post', 'page', 'eventos', 'empresas');
    
    foreach ($post_types as $post_type) {
        $posts = get_posts(array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'modified',
            'order' => 'DESC',
        ));
        
        foreach ($posts as $post) {
            echo '<url>';
            echo '<loc>' . esc_url(get_permalink($post)) . '</loc>';
            echo '<lastmod>' . date('c', strtotime($post->post_modified_gmt)) . '</lastmod>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>';
        }
    }
    
    // Categories and tags
    $taxonomies = array('category', 'post_tag');
    
    foreach ($taxonomies as $taxonomy) {
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ));
        
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                echo '<url>';
                echo '<loc>' . esc_url(get_term_link($term)) . '</loc>';
                echo '<changefreq>weekly</changefreq>';
                echo '<priority>0.6</priority>';
                echo '</url>';
            }
        }
    }
    
    echo '</urlset>';
    exit;
}

/**
 * Add custom meta description field to post editor
 */
function reforestamos_add_meta_description_metabox() {
    $post_types = array('post', 'page', 'eventos', 'empresas');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'reforestamos_meta_description',
            __('SEO Meta Description', 'reforestamos'),
            'reforestamos_meta_description_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'reforestamos_add_meta_description_metabox');

/**
 * Meta description metabox callback
 */
function reforestamos_meta_description_callback($post) {
    wp_nonce_field('reforestamos_meta_description_nonce', 'reforestamos_meta_description_nonce');
    
    $value = get_post_meta($post->ID, '_reforestamos_meta_description', true);
    
    echo '<p>';
    echo '<label for="reforestamos_meta_description">' . __('Custom meta description for search engines (recommended: 150-160 characters)', 'reforestamos') . '</label>';
    echo '</p>';
    echo '<textarea id="reforestamos_meta_description" name="reforestamos_meta_description" rows="3" style="width:100%;" maxlength="160">' . esc_textarea($value) . '</textarea>';
    echo '<p class="description">' . sprintf(__('Characters: %s / 160', 'reforestamos'), '<span id="meta-description-count">0</span>') . '</p>';
    
    // Add character counter
    ?>
    <script>
    (function() {
        var textarea = document.getElementById('reforestamos_meta_description');
        var counter = document.getElementById('meta-description-count');
        
        function updateCount() {
            counter.textContent = textarea.value.length;
        }
        
        textarea.addEventListener('input', updateCount);
        updateCount();
    })();
    </script>
    <?php
}

/**
 * Save meta description
 */
function reforestamos_save_meta_description($post_id) {
    // Check nonce
    if (!isset($_POST['reforestamos_meta_description_nonce']) || 
        !wp_verify_nonce($_POST['reforestamos_meta_description_nonce'], 'reforestamos_meta_description_nonce')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save meta description
    if (isset($_POST['reforestamos_meta_description'])) {
        $meta_description = sanitize_textarea_field($_POST['reforestamos_meta_description']);
        update_post_meta($post_id, '_reforestamos_meta_description', $meta_description);
    }
}
add_action('save_post', 'reforestamos_save_meta_description');
