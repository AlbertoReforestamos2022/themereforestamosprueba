<?php
/**
 * Media Library and Asset Management
 *
 * Implements comprehensive media library management including:
 * - Year/month folder organization
 * - Multiple image sizes generation
 * - Upload security and validation
 * - EXIF data stripping
 * - Custom media library views
 * - Bulk optimization
 * 
 * @package Reforestamos
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ============================================================================
 * MEDIA LIBRARY ORGANIZATION
 * ============================================================================
 */

/**
 * Organize uploads in year/month folders
 * 
 * WordPress default behavior, but explicitly enabled here
 * Validates Requirements: 33.1
 */
function reforestamos_enable_uploads_organization() {
    // Enable year/month folder organization
    update_option('uploads_use_yearmonth_folders', 1);
}
add_action('after_setup_theme', 'reforestamos_enable_uploads_organization');

/**
 * Generate multiple image sizes for responsive images
 * 
 * Registers custom image sizes for different use cases
 * Validates Requirements: 33.2
 */
function reforestamos_register_image_sizes() {
    // Thumbnail sizes
    add_image_size('reforestamos-thumbnail', 150, 150, true);
    add_image_size('reforestamos-thumbnail-medium', 300, 300, true);
    
    // Content sizes (maintain aspect ratio)
    add_image_size('reforestamos-small', 480, 320, false);
    add_image_size('reforestamos-medium', 768, 512, false);
    add_image_size('reforestamos-large', 1200, 800, false);
    add_image_size('reforestamos-xlarge', 1920, 1280, false);
    
    // Hero/Banner sizes
    add_image_size('reforestamos-hero', 1920, 800, true);
    add_image_size('reforestamos-banner', 1200, 400, true);
    
    // Card/Grid sizes
    add_image_size('reforestamos-card', 600, 400, true);
    add_image_size('reforestamos-card-small', 400, 300, true);
    
    // Logo sizes
    add_image_size('reforestamos-logo', 300, 150, false);
    add_image_size('reforestamos-logo-small', 150, 75, false);
}
add_action('after_setup_theme', 'reforestamos_register_image_sizes');

/**
 * Add custom image sizes to media library dropdown
 */
function reforestamos_add_image_sizes_to_media_library($sizes) {
    return array_merge($sizes, array(
        'reforestamos-thumbnail' => __('Thumbnail (150x150)', 'reforestamos'),
        'reforestamos-thumbnail-medium' => __('Thumbnail Medium (300x300)', 'reforestamos'),
        'reforestamos-small' => __('Small (480x320)', 'reforestamos'),
        'reforestamos-medium' => __('Medium (768x512)', 'reforestamos'),
        'reforestamos-large' => __('Large (1200x800)', 'reforestamos'),
        'reforestamos-xlarge' => __('Extra Large (1920x1280)', 'reforestamos'),
        'reforestamos-hero' => __('Hero (1920x800)', 'reforestamos'),
        'reforestamos-banner' => __('Banner (1200x400)', 'reforestamos'),
        'reforestamos-card' => __('Card (600x400)', 'reforestamos'),
        'reforestamos-card-small' => __('Card Small (400x300)', 'reforestamos'),
        'reforestamos-logo' => __('Logo (300x150)', 'reforestamos'),
        'reforestamos-logo-small' => __('Logo Small (150x75)', 'reforestamos'),
    ));
}
add_filter('image_size_names_choose', 'reforestamos_add_image_sizes_to_media_library');


/**
 * ============================================================================
 * UPLOAD SECURITY AND VALIDATION
 * ============================================================================
 */

/**
 * Validate allowed file types (whitelist approach)
 * 
 * Restricts uploads to safe file types only
 * Validates Requirements: 23.7
 */
function reforestamos_allowed_upload_mimes($mimes) {
    // Remove potentially dangerous file types
    unset($mimes['exe']);
    unset($mimes['php']);
    unset($mimes['phtml']);
    unset($mimes['php3']);
    unset($mimes['php4']);
    unset($mimes['php5']);
    unset($mimes['phar']);
    unset($mimes['pl']);
    unset($mimes['py']);
    unset($mimes['jsp']);
    unset($mimes['asp']);
    unset($mimes['sh']);
    unset($mimes['cgi']);
    
    // Allowed image types
    $mimes['jpg|jpeg|jpe'] = 'image/jpeg';
    $mimes['gif'] = 'image/gif';
    $mimes['png'] = 'image/png';
    $mimes['webp'] = 'image/webp';
    $mimes['svg'] = 'image/svg+xml';
    $mimes['ico'] = 'image/x-icon';
    
    // Allowed document types
    $mimes['pdf'] = 'application/pdf';
    $mimes['doc'] = 'application/msword';
    $mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    $mimes['xls'] = 'application/vnd.ms-excel';
    $mimes['xlsx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    $mimes['ppt'] = 'application/vnd.ms-powerpoint';
    $mimes['pptx'] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
    
    // Allowed media types
    $mimes['mp4'] = 'video/mp4';
    $mimes['webm'] = 'video/webm';
    $mimes['mp3'] = 'audio/mpeg';
    $mimes['wav'] = 'audio/wav';
    
    // Allowed archive types
    $mimes['zip'] = 'application/zip';
    
    return $mimes;
}
add_filter('upload_mimes', 'reforestamos_allowed_upload_mimes');

/**
 * Validate file sizes based on file type
 * 
 * Sets maximum file size limits for different file types
 * Validates Requirements: 23.8
 */
function reforestamos_validate_file_size($file) {
    $filetype = wp_check_filetype($file['name']);
    $filesize = $file['size'];
    
    // Define size limits (in bytes)
    $size_limits = array(
        'image' => 5 * 1024 * 1024,      // 5MB for images
        'document' => 10 * 1024 * 1024,  // 10MB for documents
        'video' => 50 * 1024 * 1024,     // 50MB for videos
        'audio' => 10 * 1024 * 1024,     // 10MB for audio
        'archive' => 20 * 1024 * 1024,   // 20MB for archives
    );
    
    // Determine file category
    $mime_type = $filetype['type'];
    $category = 'document'; // default
    
    if (strpos($mime_type, 'image') !== false) {
        $category = 'image';
    } elseif (strpos($mime_type, 'video') !== false) {
        $category = 'video';
    } elseif (strpos($mime_type, 'audio') !== false) {
        $category = 'audio';
    } elseif (strpos($mime_type, 'zip') !== false) {
        $category = 'archive';
    }
    
    // Check if file exceeds size limit
    if (isset($size_limits[$category]) && $filesize > $size_limits[$category]) {
        $max_size_mb = $size_limits[$category] / (1024 * 1024);
        $file['error'] = sprintf(
            __('File size exceeds the maximum allowed size of %s MB for %s files.', 'reforestamos'),
            $max_size_mb,
            $category
        );
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'reforestamos_validate_file_size', 5);

/**
 * Validate MIME type (not just extension)
 * 
 * Checks actual file MIME type to prevent extension spoofing
 * Validates Requirements: 23.7
 */
function reforestamos_validate_mime_type($file) {
    $filetype = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);
    
    // Check if MIME type could be determined
    if (!$filetype['type']) {
        $file['error'] = __('File type could not be determined. Please upload a valid file.', 'reforestamos');
        return $file;
    }
    
    // Check if extension matches MIME type
    if ($filetype['ext'] === false) {
        $file['error'] = __('File extension does not match file type. Upload rejected for security reasons.', 'reforestamos');
        return $file;
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'reforestamos_validate_mime_type', 10);

/**
 * Prevent PHP file uploads (additional security layer)
 * 
 * Blocks any file with PHP-related extensions
 * Validates Requirements: 23.7
 */
function reforestamos_block_php_uploads($file) {
    $filename = $file['name'];
    
    // List of dangerous extensions
    $dangerous_extensions = array(
        'php', 'php3', 'php4', 'php5', 'php7', 'phtml', 'phar',
        'exe', 'sh', 'cgi', 'pl', 'py', 'jsp', 'asp', 'aspx'
    );
    
    // Get file extension
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    // Block if extension is dangerous
    if (in_array($ext, $dangerous_extensions)) {
        $file['error'] = __('This file type is not allowed for security reasons.', 'reforestamos');
    }
    
    // Also check for double extensions (e.g., file.php.jpg)
    $parts = explode('.', $filename);
    if (count($parts) > 2) {
        foreach ($parts as $part) {
            if (in_array(strtolower($part), $dangerous_extensions)) {
                $file['error'] = __('Files with multiple extensions are not allowed for security reasons.', 'reforestamos');
                break;
            }
        }
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'reforestamos_block_php_uploads', 1);

/**
 * Sanitize uploaded filenames
 * 
 * Removes special characters and normalizes filenames
 * Validates Requirements: 23.1
 */
function reforestamos_sanitize_upload_filename($filename) {
    // Get file extension
    $info = pathinfo($filename);
    $ext = isset($info['extension']) ? '.' . $info['extension'] : '';
    $name = basename($filename, $ext);
    
    // Convert to lowercase
    $name = strtolower($name);
    
    // Remove accents and special characters
    $name = remove_accents($name);
    
    // Replace spaces and special characters with hyphens
    $name = preg_replace('/[^a-z0-9\-_]/', '-', $name);
    
    // Remove multiple consecutive hyphens
    $name = preg_replace('/-+/', '-', $name);
    
    // Remove leading/trailing hyphens
    $name = trim($name, '-');
    
    // Limit filename length (max 100 characters)
    if (strlen($name) > 100) {
        $name = substr($name, 0, 100);
    }
    
    // Add timestamp to prevent filename collisions
    $name = $name . '-' . time();
    
    return $name . $ext;
}
add_filter('sanitize_file_name', 'reforestamos_sanitize_upload_filename', 10);

/**
 * Strip EXIF data from images for privacy
 * 
 * Removes metadata from uploaded images
 * Validates Requirements: 33.9
 */
function reforestamos_strip_exif_data($file) {
    // Check if file is an image
    $filetype = wp_check_filetype($file['name']);
    
    if (strpos($filetype['type'], 'image') === false) {
        return $file;
    }
    
    // Skip SVG files (they don't have EXIF data)
    if ($filetype['type'] === 'image/svg+xml') {
        return $file;
    }
    
    $image_path = $file['tmp_name'];
    
    // Strip EXIF data based on image type
    switch ($filetype['type']) {
        case 'image/jpeg':
        case 'image/jpg':
            if (function_exists('imagecreatefromjpeg')) {
                $image = @imagecreatefromjpeg($image_path);
                if ($image) {
                    // Save without EXIF data (quality 90 to maintain good quality)
                    imagejpeg($image, $image_path, 90);
                    imagedestroy($image);
                }
            }
            break;
            
        case 'image/png':
            if (function_exists('imagecreatefrompng')) {
                $image = @imagecreatefrompng($image_path);
                if ($image) {
                    // Save without metadata
                    imagepng($image, $image_path, 8);
                    imagedestroy($image);
                }
            }
            break;
            
        case 'image/webp':
            if (function_exists('imagecreatefromwebp')) {
                $image = @imagecreatefromwebp($image_path);
                if ($image) {
                    // Save without metadata
                    imagewebp($image, $image_path, 90);
                    imagedestroy($image);
                }
            }
            break;
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'reforestamos_strip_exif_data', 25);


/**
 * ============================================================================
 * CUSTOM MEDIA LIBRARY VIEWS
 * ============================================================================
 */

/**
 * Add custom columns to media library
 * 
 * Displays additional information in media library list view
 * Validates Requirements: 33.4
 */
function reforestamos_add_media_columns($columns) {
    $columns['file_size'] = __('File Size', 'reforestamos');
    $columns['dimensions'] = __('Dimensions', 'reforestamos');
    $columns['mime_type'] = __('Type', 'reforestamos');
    
    return $columns;
}
add_filter('manage_media_columns', 'reforestamos_add_media_columns');

/**
 * Populate custom media library columns
 */
function reforestamos_populate_media_columns($column_name, $post_id) {
    switch ($column_name) {
        case 'file_size':
            $file_path = get_attached_file($post_id);
            if (file_exists($file_path)) {
                $file_size = filesize($file_path);
                echo reforestamos_format_file_size($file_size);
            } else {
                echo '—';
            }
            break;
            
        case 'dimensions':
            $metadata = wp_get_attachment_metadata($post_id);
            if (isset($metadata['width']) && isset($metadata['height'])) {
                echo $metadata['width'] . ' × ' . $metadata['height'];
            } else {
                echo '—';
            }
            break;
            
        case 'mime_type':
            $mime_type = get_post_mime_type($post_id);
            if ($mime_type) {
                // Display friendly type name
                $type_parts = explode('/', $mime_type);
                echo esc_html(ucfirst($type_parts[0]));
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_media_custom_column', 'reforestamos_populate_media_columns', 10, 2);

/**
 * Make custom columns sortable
 */
function reforestamos_make_media_columns_sortable($columns) {
    $columns['file_size'] = 'file_size';
    $columns['mime_type'] = 'mime_type';
    
    return $columns;
}
add_filter('manage_upload_sortable_columns', 'reforestamos_make_media_columns_sortable');

/**
 * Add media library filters by type
 * 
 * Adds dropdown filters for different asset types
 * Validates Requirements: 33.4
 */
function reforestamos_add_media_filters() {
    $screen = get_current_screen();
    
    if ($screen && $screen->id === 'upload') {
        // Get current filter
        $current_type = isset($_GET['media_type_filter']) ? $_GET['media_type_filter'] : '';
        
        // Define filter options
        $types = array(
            '' => __('All Media Types', 'reforestamos'),
            'image' => __('Images', 'reforestamos'),
            'video' => __('Videos', 'reforestamos'),
            'audio' => __('Audio', 'reforestamos'),
            'document' => __('Documents', 'reforestamos'),
            'archive' => __('Archives', 'reforestamos'),
        );
        
        echo '<select name="media_type_filter" id="media-type-filter">';
        foreach ($types as $value => $label) {
            $selected = ($current_type === $value) ? ' selected="selected"' : '';
            echo '<option value="' . esc_attr($value) . '"' . $selected . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
}
add_action('restrict_manage_posts', 'reforestamos_add_media_filters');

/**
 * Filter media library by type
 */
function reforestamos_filter_media_by_type($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $screen = get_current_screen();
    if ($screen && $screen->id === 'upload' && isset($_GET['media_type_filter']) && $_GET['media_type_filter'] !== '') {
        $type = sanitize_text_field($_GET['media_type_filter']);
        
        // Map type to MIME type pattern
        $mime_patterns = array(
            'image' => 'image',
            'video' => 'video',
            'audio' => 'audio',
            'document' => array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument'),
            'archive' => 'application/zip',
        );
        
        if (isset($mime_patterns[$type])) {
            $query->set('post_mime_type', $mime_patterns[$type]);
        }
    }
}
add_action('pre_get_posts', 'reforestamos_filter_media_by_type');


/**
 * ============================================================================
 * BULK OPTIMIZATION
 * ============================================================================
 */

/**
 * Add bulk optimization action to media library
 * 
 * Allows bulk optimization of images
 * Validates Requirements: 33.5
 */
function reforestamos_add_bulk_optimization_action($bulk_actions) {
    $bulk_actions['reforestamos_optimize'] = __('Optimize Images', 'reforestamos');
    return $bulk_actions;
}
add_filter('bulk_actions-upload', 'reforestamos_add_bulk_optimization_action');

/**
 * Handle bulk optimization action
 */
function reforestamos_handle_bulk_optimization($redirect_to, $action, $post_ids) {
    if ($action !== 'reforestamos_optimize') {
        return $redirect_to;
    }
    
    $optimized_count = 0;
    
    foreach ($post_ids as $post_id) {
        // Check if it's an image
        $mime_type = get_post_mime_type($post_id);
        if (strpos($mime_type, 'image') === false) {
            continue;
        }
        
        // Get image path
        $file_path = get_attached_file($post_id);
        if (!file_exists($file_path)) {
            continue;
        }
        
        // Optimize image
        $optimized = reforestamos_optimize_image_file($file_path, $mime_type);
        
        if ($optimized) {
            $optimized_count++;
            
            // Regenerate thumbnails
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $metadata = wp_generate_attachment_metadata($post_id, $file_path);
            wp_update_attachment_metadata($post_id, $metadata);
        }
    }
    
    // Add query arg for admin notice
    $redirect_to = add_query_arg('reforestamos_optimized', $optimized_count, $redirect_to);
    
    return $redirect_to;
}
add_filter('handle_bulk_actions-upload', 'reforestamos_handle_bulk_optimization', 10, 3);

/**
 * Display admin notice after bulk optimization
 */
function reforestamos_bulk_optimization_admin_notice() {
    if (!empty($_REQUEST['reforestamos_optimized'])) {
        $count = intval($_REQUEST['reforestamos_optimized']);
        printf(
            '<div class="notice notice-success is-dismissible"><p>' .
            _n(
                '%s image has been optimized.',
                '%s images have been optimized.',
                $count,
                'reforestamos'
            ) .
            '</p></div>',
            $count
        );
    }
}
add_action('admin_notices', 'reforestamos_bulk_optimization_admin_notice');

/**
 * Optimize a single image file
 * 
 * @param string $file_path Path to image file
 * @param string $mime_type MIME type of image
 * @return bool True if optimized successfully
 */
function reforestamos_optimize_image_file($file_path, $mime_type) {
    switch ($mime_type) {
        case 'image/jpeg':
        case 'image/jpg':
            if (function_exists('imagecreatefromjpeg')) {
                $image = @imagecreatefromjpeg($file_path);
                if ($image) {
                    // Optimize JPEG to 85% quality
                    imagejpeg($image, $file_path, 85);
                    imagedestroy($image);
                    return true;
                }
            }
            break;
            
        case 'image/png':
            if (function_exists('imagecreatefrompng')) {
                $image = @imagecreatefrompng($file_path);
                if ($image) {
                    // Optimize PNG (compression level 8)
                    imagepng($image, $file_path, 8);
                    imagedestroy($image);
                    return true;
                }
            }
            break;
            
        case 'image/webp':
            if (function_exists('imagecreatefromwebp')) {
                $image = @imagecreatefromwebp($file_path);
                if ($image) {
                    // Optimize WebP to 85% quality
                    imagewebp($image, $file_path, 85);
                    imagedestroy($image);
                    return true;
                }
            }
            break;
    }
    
    return false;
}


/**
 * ============================================================================
 * HELPER FUNCTIONS
 * ============================================================================
 */

/**
 * Format file size for display
 * 
 * @param int $bytes File size in bytes
 * @return string Formatted file size
 */
function reforestamos_format_file_size($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Get media library statistics
 * 
 * @return array Statistics about media library
 */
function reforestamos_get_media_stats() {
    global $wpdb;
    
    $stats = array();
    
    // Total media count
    $stats['total'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment'");
    
    // Count by type
    $stats['images'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'image%'");
    $stats['videos'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'video%'");
    $stats['documents'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'application%'");
    
    // Total storage used
    $upload_dir = wp_upload_dir();
    $stats['storage_used'] = reforestamos_get_directory_size($upload_dir['basedir']);
    
    return $stats;
}

/**
 * Calculate directory size recursively
 * 
 * @param string $directory Directory path
 * @return int Size in bytes
 */
function reforestamos_get_directory_size($directory) {
    $size = 0;
    
    if (!is_dir($directory)) {
        return $size;
    }
    
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)) as $file) {
        $size += $file->getSize();
    }
    
    return $size;
}

/**
 * Add media library dashboard widget
 */
function reforestamos_add_media_dashboard_widget() {
    wp_add_dashboard_widget(
        'reforestamos_media_stats',
        __('Media Library Statistics', 'reforestamos'),
        'reforestamos_render_media_dashboard_widget'
    );
}
add_action('wp_dashboard_setup', 'reforestamos_add_media_dashboard_widget');

/**
 * Render media library dashboard widget
 */
function reforestamos_render_media_dashboard_widget() {
    $stats = reforestamos_get_media_stats();
    ?>
    <div class="reforestamos-media-stats">
        <ul>
            <li>
                <strong><?php _e('Total Files:', 'reforestamos'); ?></strong>
                <?php echo number_format($stats['total']); ?>
            </li>
            <li>
                <strong><?php _e('Images:', 'reforestamos'); ?></strong>
                <?php echo number_format($stats['images']); ?>
            </li>
            <li>
                <strong><?php _e('Videos:', 'reforestamos'); ?></strong>
                <?php echo number_format($stats['videos']); ?>
            </li>
            <li>
                <strong><?php _e('Documents:', 'reforestamos'); ?></strong>
                <?php echo number_format($stats['documents']); ?>
            </li>
            <li>
                <strong><?php _e('Storage Used:', 'reforestamos'); ?></strong>
                <?php echo reforestamos_format_file_size($stats['storage_used']); ?>
            </li>
        </ul>
        <p>
            <a href="<?php echo admin_url('upload.php'); ?>" class="button button-primary">
                <?php _e('Manage Media', 'reforestamos'); ?>
            </a>
        </p>
    </div>
    <style>
        .reforestamos-media-stats ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .reforestamos-media-stats li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .reforestamos-media-stats li:last-child {
            border-bottom: none;
        }
        .reforestamos-media-stats strong {
            display: inline-block;
            min-width: 120px;
        }
    </style>
    <?php
}
