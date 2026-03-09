# SEO Usage Examples

## Template Integration Examples

### Adding Breadcrumbs to Single Post Template

```php
<?php
/**
 * Template Name: Single Post with Breadcrumbs
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        // Display breadcrumbs
        do_action('reforestamos_breadcrumbs');
        ?>
        
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();
```

### Adding Breadcrumbs to Archive Template

```php
<?php
/**
 * Template Name: Archive with Breadcrumbs
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        // Display breadcrumbs
        do_action('reforestamos_breadcrumbs');
        ?>
        
        <header class="page-header">
            <?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
            <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
        </header>
        
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/content', get_post_type());
            endwhile;
            
            the_posts_pagination();
        else :
            get_template_part('template-parts/content', 'none');
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
```

### Custom Breadcrumbs in Block Template

For HTML block templates, you can add breadcrumbs using a shortcode:

```html
<!-- wp:shortcode -->
[reforestamos_breadcrumbs]
<!-- /wp:shortcode -->
```

Then register the shortcode in functions.php:

```php
function reforestamos_breadcrumbs_shortcode() {
    ob_start();
    do_action('reforestamos_breadcrumbs');
    return ob_get_clean();
}
add_shortcode('reforestamos_breadcrumbs', 'reforestamos_breadcrumbs_shortcode');
```

---

## Custom Meta Description Examples

### Setting Meta Description Programmatically

```php
// Set custom meta description for a post
update_post_meta($post_id, '_reforestamos_meta_description', 'Your custom description here');

// Get custom meta description
$description = get_post_meta($post_id, '_reforestamos_meta_description', true);
```

### Bulk Update Meta Descriptions

```php
// Example: Add meta descriptions to all posts without one
function reforestamos_bulk_add_meta_descriptions() {
    $posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_reforestamos_meta_description',
                'compare' => 'NOT EXISTS'
            )
        )
    ));
    
    foreach ($posts as $post) {
        // Generate description from excerpt or content
        $description = has_excerpt($post->ID) 
            ? get_the_excerpt($post->ID)
            : wp_trim_words($post->post_content, 30, '...');
        
        // Clean and truncate
        $description = wp_strip_all_tags($description);
        $description = substr($description, 0, 160);
        
        // Save
        update_post_meta($post->ID, '_reforestamos_meta_description', $description);
    }
}
```

---

## Structured Data Examples

### Adding Custom Schema to Events

```php
add_filter('reforestamos_event_schema', function($schema, $post_id) {
    // Add ticket information
    $schema['offers'] = array(
        '@type' => 'Offer',
        'price' => '0',
        'priceCurrency' => 'MXN',
        'availability' => 'https://schema.org/InStock',
        'url' => get_permalink($post_id)
    );
    
    return $schema;
}, 10, 2);
```

### Adding FAQ Schema

```php
function reforestamos_add_faq_schema($faqs) {
    if (empty($faqs)) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array()
    );
    
    foreach ($faqs as $faq) {
        $schema['mainEntity'][] = array(
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq['answer']
            )
        );
    }
    
    echo '<script type="application/ld+json">';
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo '</script>';
}
```

### Adding Product Schema for Empresas

```php
add_action('wp_head', function() {
    if (!is_singular('empresas')) {
        return;
    }
    
    global $post;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_the_title(),
        'description' => get_the_excerpt(),
        'url' => get_post_meta($post->ID, '_reforestamos_empresa_url', true),
        'logo' => get_the_post_thumbnail_url($post->ID, 'full')
    );
    
    echo '<script type="application/ld+json">';
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo '</script>';
});
```

---

## Sitemap Customization

### Excluding Post Types from Sitemap

```php
add_filter('reforestamos_sitemap_post_types', function($post_types) {
    // Remove 'integrantes' from sitemap
    $post_types = array_diff($post_types, array('integrantes'));
    return $post_types;
});
```

### Adding Custom URLs to Sitemap

```php
add_action('reforestamos_sitemap_custom_urls', function() {
    echo '<url>';
    echo '<loc>' . esc_url(home_url('/custom-page/')) . '</loc>';
    echo '<lastmod>' . date('c') . '</lastmod>';
    echo '<changefreq>monthly</changefreq>';
    echo '<priority>0.7</priority>';
    echo '</url>';
});
```

### Changing Sitemap Priorities

```php
add_filter('reforestamos_sitemap_priority', function($priority, $post) {
    // Higher priority for eventos
    if ($post->post_type === 'eventos') {
        return 0.9;
    }
    return $priority;
}, 10, 2);
```

---

## Image Optimization Examples

### Adding Default Alt Text

```php
add_filter('wp_get_attachment_image_attributes', function($attr, $attachment) {
    if (empty($attr['alt'])) {
        // Use post title as fallback
        $attr['alt'] = get_the_title($attachment->ID);
    }
    return $attr;
}, 20, 2);
```

### Bulk Update Image Alt Text

```php
function reforestamos_bulk_update_image_alt_text() {
    $images = get_posts(array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1
    ));
    
    foreach ($images as $image) {
        $alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
        
        if (empty($alt)) {
            // Use image title or filename
            $alt = $image->post_title ?: basename(get_attached_file($image->ID));
            update_post_meta($image->ID, '_wp_attachment_image_alt', $alt);
        }
    }
}
```

---

## Open Graph Customization

### Custom OG Image for Home Page

```php
add_filter('reforestamos_og_image', function($image_url) {
    if (is_front_page()) {
        // Use custom home page image
        return get_template_directory_uri() . '/assets/images/og-home.jpg';
    }
    return $image_url;
});
```

### Adding OG Video

```php
add_action('wp_head', function() {
    if (!is_singular('post')) {
        return;
    }
    
    global $post;
    $video_url = get_post_meta($post->ID, '_video_url', true);
    
    if ($video_url) {
        echo '<meta property="og:video" content="' . esc_url($video_url) . '" />';
    }
});
```

---

## Canonical URL Customization

### Custom Canonical for Paginated Archives

```php
add_filter('reforestamos_canonical_url', function($canonical_url) {
    if (is_paged()) {
        global $wp_query;
        $paged = get_query_var('paged') ?: 1;
        
        if ($paged > 1) {
            $canonical_url = get_pagenum_link($paged);
        }
    }
    return $canonical_url;
});
```

---

## Testing Examples

### Test Meta Tags in PHP

```php
// Get meta description for a post
$post_id = 123;
$description = get_post_meta($post_id, '_reforestamos_meta_description', true);

if (empty($description)) {
    $post = get_post($post_id);
    $description = has_excerpt($post_id) 
        ? get_the_excerpt($post_id)
        : wp_trim_words($post->post_content, 30, '...');
}

echo "Meta Description: " . $description;
```

### Test Structured Data Output

```php
// Test event schema generation
$post_id = 456; // Event post ID
$post = get_post($post_id);
setup_postdata($post);

ob_start();
reforestamos_add_structured_data();
$schema_output = ob_get_clean();

echo "<pre>";
echo htmlspecialchars($schema_output);
echo "</pre>";

wp_reset_postdata();
```

### Test Breadcrumbs Array

```php
// Test breadcrumbs for a specific page
$post_id = 789;
global $post;
$post = get_post($post_id);
setup_postdata($post);

$breadcrumbs = reforestamos_get_breadcrumbs_array();

echo "<pre>";
print_r($breadcrumbs);
echo "</pre>";

wp_reset_postdata();
```

---

## WP-CLI Commands

### Generate Sitemap

```bash
# Flush rewrite rules to enable sitemap
wp rewrite flush

# Test sitemap generation
curl https://yoursite.com/sitemap.xml
```

### Bulk Update Meta Descriptions

```bash
# Create WP-CLI command
wp eval 'reforestamos_bulk_add_meta_descriptions();'
```

### Check Image Alt Text

```bash
# Count images without alt text
wp db query "SELECT COUNT(*) FROM wp_postmeta WHERE meta_key = '_wp_attachment_image_alt' AND meta_value = ''"
```

---

## Debugging

### Enable SEO Debug Mode

Add to wp-config.php:

```php
define('REFORESTAMOS_SEO_DEBUG', true);
```

Then in inc/seo.php, add debug output:

```php
if (defined('REFORESTAMOS_SEO_DEBUG') && REFORESTAMOS_SEO_DEBUG) {
    echo '<!-- SEO Debug: Meta tags generated -->';
    echo '<!-- Description: ' . esc_html($description) . ' -->';
}
```

### Log Structured Data

```php
add_action('reforestamos_after_structured_data', function($schema) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Structured Data: ' . print_r($schema, true));
    }
});
```

---

## Performance Optimization

### Cache Structured Data

```php
function reforestamos_get_cached_structured_data($post_id) {
    $cache_key = 'reforestamos_schema_' . $post_id;
    $schema = wp_cache_get($cache_key);
    
    if (false === $schema) {
        $schema = reforestamos_generate_structured_data($post_id);
        wp_cache_set($cache_key, $schema, '', HOUR_IN_SECONDS);
    }
    
    return $schema;
}
```

### Lazy Load Sitemap

```php
// Only generate sitemap when requested
add_action('template_redirect', function() {
    if (get_query_var('reforestamos_sitemap')) {
        // Check cache first
        $sitemap = wp_cache_get('reforestamos_sitemap');
        
        if (false === $sitemap) {
            ob_start();
            reforestamos_generate_sitemap();
            $sitemap = ob_get_clean();
            wp_cache_set('reforestamos_sitemap', $sitemap, '', DAY_IN_SECONDS);
        }
        
        header('Content-Type: application/xml; charset=utf-8');
        echo $sitemap;
        exit;
    }
});
```

---

## Integration with Other Plugins

### Yoast SEO Integration

```php
// Use Yoast's meta description if available
add_filter('reforestamos_meta_description', function($description, $post_id) {
    if (defined('WPSEO_VERSION')) {
        $yoast_desc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
        if ($yoast_desc) {
            return $yoast_desc;
        }
    }
    return $description;
}, 10, 2);
```

### WPML Integration

```php
// Add language to structured data
add_filter('reforestamos_structured_data', function($schema) {
    if (defined('ICL_LANGUAGE_CODE')) {
        $schema['inLanguage'] = ICL_LANGUAGE_CODE;
    }
    return $schema;
});
```

---

## Best Practices Checklist

### Content Optimization
- [ ] Add custom meta descriptions to all pages
- [ ] Use descriptive page titles
- [ ] Add alt text to all images
- [ ] Use heading hierarchy (H1, H2, H3)
- [ ] Add internal links
- [ ] Keep URLs short and descriptive

### Technical SEO
- [ ] Submit sitemap to Google Search Console
- [ ] Verify canonical URLs
- [ ] Test structured data with Google Rich Results
- [ ] Check mobile-friendliness
- [ ] Optimize page speed
- [ ] Enable HTTPS

### Social Media
- [ ] Test Open Graph tags with Facebook Debugger
- [ ] Test Twitter Cards with Twitter Validator
- [ ] Add social sharing buttons
- [ ] Optimize images for social sharing (1200x630px)

### Monitoring
- [ ] Set up Google Search Console
- [ ] Set up Google Analytics
- [ ] Monitor search rankings
- [ ] Track click-through rates
- [ ] Review structured data errors

---

**Remember:** SEO is an ongoing process. Regularly review and update your content, meta tags, and structured data to maintain optimal search engine visibility.
