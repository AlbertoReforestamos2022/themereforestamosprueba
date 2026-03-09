<?php
/**
 * Performance Optimizations
 *
 * Implements lazy loading, asset optimization, and caching strategies
 * 
 * @package Reforestamos
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enable native lazy loading for images
 * 
 * Adds loading="lazy" attribute to all images below the fold
 * Validates Requirements: 19.2, 33.6
 */
function reforestamos_add_lazy_loading_to_images($content) {
    // Skip if in admin or feed
    if (is_admin() || is_feed()) {
        return $content;
    }
    
    // Add loading="lazy" to img tags that don't already have it
    $content = preg_replace_callback(
        '/<img([^>]+?)>/i',
        function($matches) {
            $img_tag = $matches[0];
            
            // Skip if already has loading attribute
            if (strpos($img_tag, 'loading=') !== false) {
                return $img_tag;
            }
            
            // Skip if has data-no-lazy attribute
            if (strpos($img_tag, 'data-no-lazy') !== false) {
                return $img_tag;
            }
            
            // Add loading="lazy" before the closing >
            return str_replace('<img', '<img loading="lazy"', $img_tag);
        },
        $content
    );
    
    return $content;
}
add_filter('the_content', 'reforestamos_add_lazy_loading_to_images', 20);
add_filter('post_thumbnail_html', 'reforestamos_add_lazy_loading_to_images', 20);
add_filter('widget_text', 'reforestamos_add_lazy_loading_to_images', 20);

/**
 * Add lazy loading to post thumbnails
 */
function reforestamos_lazy_load_post_thumbnails($attr) {
    if (!is_admin() && !is_feed()) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'reforestamos_lazy_load_post_thumbnails', 10, 1);

/**
 * Enable lazy loading for iframes (videos)
 * 
 * Adds loading="lazy" to iframe embeds (YouTube, Vimeo, etc.)
 * Validates Requirements: 19.2, 33.6
 */
function reforestamos_add_lazy_loading_to_iframes($content) {
    // Skip if in admin or feed
    if (is_admin() || is_feed()) {
        return $content;
    }
    
    // Add loading="lazy" to iframe tags
    $content = preg_replace_callback(
        '/<iframe([^>]+?)>/i',
        function($matches) {
            $iframe_tag = $matches[0];
            
            // Skip if already has loading attribute
            if (strpos($iframe_tag, 'loading=') !== false) {
                return $iframe_tag;
            }
            
            // Add loading="lazy" before the closing >
            return str_replace('<iframe', '<iframe loading="lazy"', $iframe_tag);
        },
        $content
    );
    
    return $content;
}
add_filter('the_content', 'reforestamos_add_lazy_loading_to_iframes', 20);
add_filter('embed_oembed_html', 'reforestamos_add_lazy_loading_to_iframes', 20);

/**
 * Add lazy loading to video elements
 */
function reforestamos_add_lazy_loading_to_videos($content) {
    // Skip if in admin or feed
    if (is_admin() || is_feed()) {
        return $content;
    }
    
    // Add preload="none" to video tags for lazy loading
    $content = preg_replace_callback(
        '/<video([^>]+?)>/i',
        function($matches) {
            $video_tag = $matches[0];
            
            // Skip if already has preload attribute
            if (strpos($video_tag, 'preload=') !== false) {
                return $video_tag;
            }
            
            // Add preload="none" for lazy loading
            return str_replace('<video', '<video preload="none"', $video_tag);
        },
        $content
    );
    
    return $content;
}
add_filter('the_content', 'reforestamos_add_lazy_loading_to_videos', 20);

/**
 * Disable lazy loading for above-the-fold images
 * 
 * Prevents lazy loading on the first image in content (likely above the fold)
 */
function reforestamos_skip_lazy_loading_first_image($content) {
    static $first_image_processed = false;
    
    if ($first_image_processed || is_admin() || is_feed()) {
        return $content;
    }
    
    // Find the first image and add data-no-lazy attribute
    $content = preg_replace(
        '/<img/',
        '<img data-no-lazy',
        $content,
        1 // Only replace the first occurrence
    );
    
    $first_image_processed = true;
    
    return $content;
}
// Run before lazy loading filter
add_filter('the_content', 'reforestamos_skip_lazy_loading_first_image', 10);


/**
 * ============================================================================
 * ASSET OPTIMIZATION
 * ============================================================================
 */

/**
 * Defer non-critical JavaScript
 * 
 * Adds defer attribute to JavaScript files for better performance
 * Validates Requirements: 19.5
 */
function reforestamos_defer_non_critical_scripts($tag, $handle, $src) {
    // Skip if in admin
    if (is_admin()) {
        return $tag;
    }
    
    // List of scripts that should NOT be deferred (critical scripts)
    $critical_scripts = array(
        'jquery',
        'jquery-core',
        'jquery-migrate',
        'wp-polyfill',
    );
    
    // Don't defer critical scripts
    if (in_array($handle, $critical_scripts)) {
        return $tag;
    }
    
    // Don't defer scripts that already have async or defer
    if (strpos($tag, 'defer') !== false || strpos($tag, 'async') !== false) {
        return $tag;
    }
    
    // Add defer attribute
    return str_replace(' src', ' defer src', $tag);
}
add_filter('script_loader_tag', 'reforestamos_defer_non_critical_scripts', 10, 3);

/**
 * Optimize web fonts loading with font-display: swap
 * 
 * Adds font-display: swap to Google Fonts and other web fonts
 * Validates Requirements: 19.6
 */
function reforestamos_optimize_google_fonts() {
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        /* Font-display: swap for all fonts */
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 400 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/montserrat/v25/JTUSjIg1_i6t8kCHKm459Wlhyw.woff2) format('woff2');
        }
        
        @font-face {
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: 400 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/opensans/v34/memSYaGs126MiZpBA-UvWbX2vVnXBbObj2OVZyOOSr4dVJWUgsjZ0B4gaVI.woff2) format('woff2');
        }
    </style>
    <?php
}
add_action('wp_head', 'reforestamos_optimize_google_fonts', 1);

/**
 * Add resource hints for better performance
 */
function reforestamos_add_resource_hints($urls, $relation_type) {
    if ('dns-prefetch' === $relation_type) {
        $urls[] = '//fonts.googleapis.com';
        $urls[] = '//fonts.gstatic.com';
        $urls[] = '//cdn.jsdelivr.net';
    }
    
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin' => 'anonymous',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'reforestamos_add_resource_hints', 10, 2);

/**
 * Minify HTML output in production
 * 
 * Removes unnecessary whitespace from HTML
 * Validates Requirements: 19.3
 */
function reforestamos_minify_html($buffer) {
    // Only minify in production (not in development)
    if (defined('WP_DEBUG') && WP_DEBUG) {
        return $buffer;
    }
    
    // Don't minify if buffer is empty
    if (strlen($buffer) === 0) {
        return $buffer;
    }
    
    // Remove HTML comments (except IE conditional comments)
    $buffer = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer);
    
    // Remove whitespace between tags
    $buffer = preg_replace('/>\s+</', '><', $buffer);
    
    // Remove multiple spaces
    $buffer = preg_replace('/\s+/', ' ', $buffer);
    
    return $buffer;
}

/**
 * Start output buffering for HTML minification
 */
function reforestamos_start_html_minification() {
    if (!is_admin() && !is_feed()) {
        ob_start('reforestamos_minify_html');
    }
}
add_action('template_redirect', 'reforestamos_start_html_minification', 1);

/**
 * Remove query strings from static resources
 * 
 * Improves caching by removing version query strings
 */
function reforestamos_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'reforestamos_remove_query_strings', 10, 1);
add_filter('script_loader_src', 'reforestamos_remove_query_strings', 10, 1);

/**
 * Disable WordPress emoji scripts
 * 
 * Removes unnecessary emoji detection scripts
 */
function reforestamos_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'reforestamos_disable_emojis');

/**
 * Remove WordPress version from head
 */
remove_action('wp_head', 'wp_generator');

/**
 * Disable embeds if not needed
 */
function reforestamos_disable_embeds() {
    // Remove the REST API endpoint for embeds
    remove_action('rest_api_init', 'wp_oembed_register_route');
    
    // Turn off oEmbed auto discovery
    add_filter('embed_oembed_discover', '__return_false');
    
    // Remove oEmbed-specific JavaScript from the front-end and back-end
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
}
// Uncomment if embeds are not needed
// add_action('init', 'reforestamos_disable_embeds', 9999);


/**
 * ============================================================================
 * CACHING AND HEADERS
 * ============================================================================
 */

/**
 * Add browser caching headers for static assets
 * 
 * Sets cache-control headers for better performance
 * Validates Requirements: 19.4
 */
function reforestamos_add_caching_headers() {
    if (!is_admin()) {
        // Cache static assets for 1 year
        header('Cache-Control: public, max-age=31536000, immutable');
    }
}

/**
 * Set proper cache headers via .htaccess
 * 
 * This function generates .htaccess rules for browser caching
 * Note: This should be added to .htaccess manually or via plugin activation
 */
function reforestamos_get_htaccess_caching_rules() {
    return '
# BEGIN Reforestamos Caching
<IfModule mod_expires.c>
    ExpiresActive On
    
    # Images
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/x-icon "access plus 1 year"
    
    # Video
    ExpiresByType video/mp4 "access plus 1 year"
    ExpiresByType video/webm "access plus 1 year"
    
    # Fonts
    ExpiresByType font/ttf "access plus 1 year"
    ExpiresByType font/otf "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
    ExpiresByType application/font-woff "access plus 1 year"
    
    # CSS and JavaScript
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    
    # Others
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType text/html "access plus 0 seconds"
</IfModule>

# Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE image/svg+xml
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Leverage Browser Caching
<IfModule mod_headers.c>
    <FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|ico)$">
        Header set Cache-Control "max-age=31536000, public"
    </FilesMatch>
    <FilesMatch "\.(css|js)$">
        Header set Cache-Control "max-age=2592000, public"
    </FilesMatch>
    <FilesMatch "\.(woff|woff2|ttf|otf|eot)$">
        Header set Cache-Control "max-age=31536000, public"
    </FilesMatch>
</IfModule>
# END Reforestamos Caching
';
}

/**
 * Implement critical CSS inlining
 * 
 * Inlines critical above-the-fold CSS for faster First Contentful Paint
 * Validates Requirements: 19.8
 */
function reforestamos_inline_critical_css() {
    // Only inline on frontend
    if (is_admin() || is_feed()) {
        return;
    }
    
    // Critical CSS for above-the-fold content
    $critical_css = '
    <style id="reforestamos-critical-css">
        /* Critical CSS - Above the fold styles */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #212121;
            background-color: #fff;
        }
        
        /* Header critical styles */
        .wp-block-reforestamos-header-navbar {
            position: relative;
            z-index: 1000;
        }
        
        /* Navigation critical styles */
        .navbar {
            padding: 1rem 0;
        }
        
        /* Hero section critical styles */
        .reforestamos-hero {
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Container critical styles */
        .container {
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Typography critical styles */
        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-weight: 600;
            line-height: 1.2;
        }
        
        h1 { font-size: 2.5rem; }
        h2 { font-size: 2rem; }
        h3 { font-size: 1.75rem; }
        
        /* Button critical styles */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 0.25rem;
            transition: all 0.15s ease-in-out;
        }
        
        .btn-primary {
            background-color: #2E7D32;
            color: #fff;
            border: 1px solid #2E7D32;
        }
        
        /* Hide non-critical content initially */
        .below-fold {
            visibility: hidden;
        }
    </style>
    ';
    
    echo $critical_css;
}
add_action('wp_head', 'reforestamos_inline_critical_css', 1);

/**
 * Load non-critical CSS asynchronously
 */
function reforestamos_async_css($html, $handle) {
    // Skip critical stylesheets
    $critical_handles = array('reforestamos-critical', 'bootstrap');
    
    if (in_array($handle, $critical_handles)) {
        return $html;
    }
    
    // Make stylesheet load asynchronously
    $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
    
    // Add noscript fallback
    $html .= '<noscript><link rel="stylesheet" href="' . esc_url(wp_styles()->registered[$handle]->src) . '"></noscript>';
    
    return $html;
}
// Uncomment to enable async CSS loading (may cause FOUC)
// add_filter('style_loader_tag', 'reforestamos_async_css', 10, 2);

/**
 * Preload key assets
 */
function reforestamos_preload_assets() {
    // Preload critical CSS
    echo '<link rel="preload" href="' . get_stylesheet_uri() . '" as="style">';
    
    // Preload critical JavaScript
    if (file_exists(REFORESTAMOS_THEME_DIR . '/build/index.js')) {
        echo '<link rel="preload" href="' . REFORESTAMOS_THEME_URI . '/build/index.js" as="script">';
    }
    
    // Preload hero image if on front page
    if (is_front_page()) {
        $hero_image = get_theme_mod('hero_image');
        if ($hero_image) {
            echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">';
        }
    }
}
add_action('wp_head', 'reforestamos_preload_assets', 1);

/**
 * Add security headers
 */
function reforestamos_add_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}
add_action('send_headers', 'reforestamos_add_security_headers');


/**
 * ============================================================================
 * IMAGE OPTIMIZATION
 * ============================================================================
 */

/**
 * Enable WebP support
 * 
 * Adds WebP to allowed upload types
 * Validates Requirements: 19.7, 33.3
 */
function reforestamos_enable_webp_upload($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'reforestamos_enable_webp_upload');

/**
 * Add WebP image sizes
 */
function reforestamos_add_webp_support() {
    add_theme_support('post-thumbnails');
    
    // Add custom image sizes for responsive images
    add_image_size('reforestamos-small', 480, 320, false);
    add_image_size('reforestamos-medium', 768, 512, false);
    add_image_size('reforestamos-large', 1200, 800, false);
    add_image_size('reforestamos-xlarge', 1920, 1280, false);
}
add_action('after_setup_theme', 'reforestamos_add_webp_support');

/**
 * Generate srcset for responsive images
 * 
 * Automatically generates srcset attributes for better responsive images
 * Validates Requirements: 19.7, 33.5
 */
function reforestamos_add_responsive_image_srcset($attr, $attachment, $size) {
    // Get image metadata
    $image_meta = wp_get_attachment_metadata($attachment->ID);
    
    if (!$image_meta) {
        return $attr;
    }
    
    // Generate srcset
    $srcset = wp_calculate_image_srcset(
        array($image_meta['width'], $image_meta['height']),
        wp_get_attachment_url($attachment->ID),
        $image_meta,
        $attachment->ID
    );
    
    if ($srcset) {
        $attr['srcset'] = $srcset;
        $attr['sizes'] = wp_calculate_image_sizes(
            array($image_meta['width'], $image_meta['height']),
            null,
            null,
            $attachment->ID
        );
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'reforestamos_add_responsive_image_srcset', 10, 3);

/**
 * Add WebP fallback support
 * 
 * Provides fallback for browsers that don't support WebP
 * Validates Requirements: 19.7, 33.3
 */
function reforestamos_webp_fallback($content) {
    // Skip if in admin
    if (is_admin()) {
        return $content;
    }
    
    // Find all img tags with WebP sources
    $content = preg_replace_callback(
        '/<img([^>]+)src=["\']([^"\']+\.webp)["\']([^>]*)>/i',
        function($matches) {
            $img_tag = $matches[0];
            $webp_src = $matches[2];
            
            // Generate fallback URL (replace .webp with .jpg)
            $fallback_src = preg_replace('/\.webp$/i', '.jpg', $webp_src);
            
            // Create picture element with fallback
            $picture = '<picture>';
            $picture .= '<source srcset="' . esc_url($webp_src) . '" type="image/webp">';
            $picture .= '<source srcset="' . esc_url($fallback_src) . '" type="image/jpeg">';
            $picture .= $img_tag;
            $picture .= '</picture>';
            
            return $picture;
        },
        $content
    );
    
    return $content;
}
// Uncomment to enable WebP fallback
// add_filter('the_content', 'reforestamos_webp_fallback', 20);

/**
 * Compress images on upload
 * 
 * Automatically compresses images when uploaded
 * Validates Requirements: 19.7, 33.5
 */
function reforestamos_compress_image($file) {
    // Check if file is an image
    $filetype = wp_check_filetype($file['name']);
    
    if (strpos($filetype['type'], 'image') === false) {
        return $file;
    }
    
    // Get image path
    $image_path = $file['tmp_name'];
    
    // Compress based on image type
    switch ($filetype['type']) {
        case 'image/jpeg':
        case 'image/jpg':
            $image = imagecreatefromjpeg($image_path);
            if ($image) {
                // Compress JPEG to 85% quality
                imagejpeg($image, $image_path, 85);
                imagedestroy($image);
            }
            break;
            
        case 'image/png':
            $image = imagecreatefrompng($image_path);
            if ($image) {
                // Compress PNG (0-9, where 9 is maximum compression)
                imagepng($image, $image_path, 8);
                imagedestroy($image);
            }
            break;
            
        case 'image/webp':
            if (function_exists('imagecreatefromwebp')) {
                $image = imagecreatefromwebp($image_path);
                if ($image) {
                    // Compress WebP to 85% quality
                    imagewebp($image, $image_path, 85);
                    imagedestroy($image);
                }
            }
            break;
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'reforestamos_compress_image');

/**
 * Strip EXIF data from images for privacy
 * 
 * Removes metadata from uploaded images
 * Validates Requirements: 33.9
 */
function reforestamos_strip_image_metadata($file) {
    // Check if file is an image
    $filetype = wp_check_filetype($file['name']);
    
    if (strpos($filetype['type'], 'image') === false) {
        return $file;
    }
    
    $image_path = $file['tmp_name'];
    
    // Strip EXIF data based on image type
    switch ($filetype['type']) {
        case 'image/jpeg':
        case 'image/jpg':
            $image = imagecreatefromjpeg($image_path);
            if ($image) {
                // Save without EXIF data
                imagejpeg($image, $image_path, 90);
                imagedestroy($image);
            }
            break;
            
        case 'image/png':
            $image = imagecreatefrompng($image_path);
            if ($image) {
                imagepng($image, $image_path, 9);
                imagedestroy($image);
            }
            break;
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'reforestamos_strip_image_metadata', 20);

/**
 * Set default image quality
 */
function reforestamos_set_image_quality($quality, $mime_type) {
    // Set JPEG quality to 85%
    if ($mime_type === 'image/jpeg') {
        return 85;
    }
    
    return $quality;
}
add_filter('wp_editor_set_quality', 'reforestamos_set_image_quality', 10, 2);
add_filter('jpeg_quality', function() { return 85; });

/**
 * Add custom image sizes to media library
 */
function reforestamos_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'reforestamos-small' => __('Small (480x320)', 'reforestamos'),
        'reforestamos-medium' => __('Medium (768x512)', 'reforestamos'),
        'reforestamos-large' => __('Large (1200x800)', 'reforestamos'),
        'reforestamos-xlarge' => __('Extra Large (1920x1280)', 'reforestamos'),
    ));
}
add_filter('image_size_names_choose', 'reforestamos_custom_image_sizes');

/**
 * Disable WordPress default image sizes (optional)
 * Uncomment to disable unused image sizes
 */
function reforestamos_disable_default_image_sizes($sizes) {
    // Remove default WordPress image sizes
    unset($sizes['thumbnail']);    // 150px
    unset($sizes['medium']);        // 300px
    unset($sizes['medium_large']);  // 768px
    unset($sizes['large']);         // 1024px
    
    return $sizes;
}
// Uncomment to disable default image sizes
// add_filter('intermediate_image_sizes_advanced', 'reforestamos_disable_default_image_sizes');

/**
 * Optimize image file names on upload
 */
function reforestamos_sanitize_image_filename($filename) {
    // Convert to lowercase
    $filename = strtolower($filename);
    
    // Remove special characters
    $filename = preg_replace('/[^a-z0-9\._-]/', '-', $filename);
    
    // Remove multiple dashes
    $filename = preg_replace('/-+/', '-', $filename);
    
    // Remove leading/trailing dashes
    $filename = trim($filename, '-');
    
    return $filename;
}
add_filter('sanitize_file_name', 'reforestamos_sanitize_image_filename', 10);

