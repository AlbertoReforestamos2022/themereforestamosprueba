<?php
/**
 * Pattern Manager - Export/Import Functionality
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Pattern Manager admin menu
 */
function reforestamos_pattern_manager_menu() {
    add_theme_page(
        __('Pattern Manager', 'reforestamos'),
        __('Pattern Manager', 'reforestamos'),
        'edit_theme_options',
        'reforestamos-pattern-manager',
        'reforestamos_pattern_manager_page'
    );
}
add_action('admin_menu', 'reforestamos_pattern_manager_menu');

/**
 * Render Pattern Manager page
 */
function reforestamos_pattern_manager_page() {
    // Check user capabilities
    if (!current_user_can('edit_theme_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'reforestamos'));
    }

    // Handle form submissions
    if (isset($_POST['reforestamos_export_pattern'])) {
        check_admin_referer('reforestamos_export_pattern');
        reforestamos_export_pattern();
    }

    if (isset($_POST['reforestamos_import_pattern'])) {
        check_admin_referer('reforestamos_import_pattern');
        reforestamos_import_pattern();
    }

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Pattern Manager', 'reforestamos'); ?></h1>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php esc_html_e('Export Pattern', 'reforestamos'); ?></h2>
            <p><?php esc_html_e('Export a custom pattern to share or backup. The pattern will be exported as a JSON file.', 'reforestamos'); ?></p>
            
            <form method="post" action="">
                <?php wp_nonce_field('reforestamos_export_pattern'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="pattern_slug"><?php esc_html_e('Pattern Slug', 'reforestamos'); ?></label>
                        </th>
                        <td>
                            <select name="pattern_slug" id="pattern_slug" class="regular-text" required>
                                <option value=""><?php esc_html_e('Select a pattern...', 'reforestamos'); ?></option>
                                <?php
                                $patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
                                foreach ($patterns as $pattern) {
                                    if (strpos($pattern['name'], 'reforestamos/') === 0) {
                                        echo '<option value="' . esc_attr($pattern['name']) . '">' . esc_html($pattern['title']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <p class="description"><?php esc_html_e('Select the pattern you want to export.', 'reforestamos'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="reforestamos_export_pattern" class="button button-primary" value="<?php esc_attr_e('Export Pattern', 'reforestamos'); ?>">
                </p>
            </form>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php esc_html_e('Import Pattern', 'reforestamos'); ?></h2>
            <p><?php esc_html_e('Import a custom pattern from a JSON file. The pattern will be registered and available in the block inserter.', 'reforestamos'); ?></p>
            
            <form method="post" action="" enctype="multipart/form-data">
                <?php wp_nonce_field('reforestamos_import_pattern'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="pattern_file"><?php esc_html_e('Pattern File', 'reforestamos'); ?></label>
                        </th>
                        <td>
                            <input type="file" name="pattern_file" id="pattern_file" accept=".json" required>
                            <p class="description"><?php esc_html_e('Upload a JSON file containing the pattern data.', 'reforestamos'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="reforestamos_import_pattern" class="button button-primary" value="<?php esc_attr_e('Import Pattern', 'reforestamos'); ?>">
                </p>
            </form>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php esc_html_e('Available Patterns', 'reforestamos'); ?></h2>
            <p><?php esc_html_e('List of all registered Reforestamos patterns:', 'reforestamos'); ?></p>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Title', 'reforestamos'); ?></th>
                        <th><?php esc_html_e('Slug', 'reforestamos'); ?></th>
                        <th><?php esc_html_e('Categories', 'reforestamos'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
                    foreach ($patterns as $pattern) {
                        if (strpos($pattern['name'], 'reforestamos/') === 0) {
                            echo '<tr>';
                            echo '<td><strong>' . esc_html($pattern['title']) . '</strong><br><small>' . esc_html($pattern['description'] ?? '') . '</small></td>';
                            echo '<td><code>' . esc_html($pattern['name']) . '</code></td>';
                            echo '<td>' . esc_html(implode(', ', $pattern['categories'] ?? [])) . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

/**
 * Export a pattern to JSON
 */
function reforestamos_export_pattern() {
    if (!isset($_POST['pattern_slug']) || empty($_POST['pattern_slug'])) {
        add_settings_error('reforestamos_patterns', 'pattern_slug', __('Please select a pattern to export.', 'reforestamos'));
        return;
    }

    $pattern_slug = sanitize_text_field($_POST['pattern_slug']);
    $patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
    
    $pattern_data = null;
    foreach ($patterns as $pattern) {
        if ($pattern['name'] === $pattern_slug) {
            $pattern_data = $pattern;
            break;
        }
    }

    if (!$pattern_data) {
        add_settings_error('reforestamos_patterns', 'pattern_not_found', __('Pattern not found.', 'reforestamos'));
        return;
    }

    // Prepare export data
    $export_data = array(
        'name' => $pattern_data['name'],
        'title' => $pattern_data['title'],
        'description' => $pattern_data['description'] ?? '',
        'content' => $pattern_data['content'],
        'categories' => $pattern_data['categories'] ?? array(),
        'keywords' => $pattern_data['keywords'] ?? array(),
        'viewportWidth' => $pattern_data['viewportWidth'] ?? 1280,
        'exported_at' => current_time('mysql'),
        'exported_from' => get_bloginfo('name'),
    );

    // Generate filename
    $filename = str_replace('reforestamos/', '', $pattern_data['name']) . '-' . date('Y-m-d') . '.json';

    // Send headers for download
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: 0');

    // Output JSON
    echo wp_json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Import a pattern from JSON
 */
function reforestamos_import_pattern() {
    if (!isset($_FILES['pattern_file']) || $_FILES['pattern_file']['error'] !== UPLOAD_ERR_OK) {
        add_settings_error('reforestamos_patterns', 'upload_error', __('Error uploading file.', 'reforestamos'));
        return;
    }

    // Validate file type
    $file_type = wp_check_filetype($_FILES['pattern_file']['name']);
    if ($file_type['ext'] !== 'json') {
        add_settings_error('reforestamos_patterns', 'invalid_file', __('Please upload a valid JSON file.', 'reforestamos'));
        return;
    }

    // Read file content
    $file_content = file_get_contents($_FILES['pattern_file']['tmp_name']);
    $pattern_data = json_decode($file_content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        add_settings_error('reforestamos_patterns', 'invalid_json', __('Invalid JSON file.', 'reforestamos'));
        return;
    }

    // Validate required fields
    $required_fields = array('name', 'title', 'content');
    foreach ($required_fields as $field) {
        if (!isset($pattern_data[$field]) || empty($pattern_data[$field])) {
            add_settings_error('reforestamos_patterns', 'missing_field', sprintf(__('Missing required field: %s', 'reforestamos'), $field));
            return;
        }
    }

    // Save pattern to custom patterns directory
    $custom_patterns_dir = WP_CONTENT_DIR . '/reforestamos-custom-patterns/';
    if (!file_exists($custom_patterns_dir)) {
        wp_mkdir_p($custom_patterns_dir);
    }

    // Generate PHP file for the pattern
    $pattern_slug = str_replace('reforestamos/', '', $pattern_data['name']);
    $pattern_file = $custom_patterns_dir . $pattern_slug . '.php';

    $php_content = "<?php\n";
    $php_content .= "/**\n";
    $php_content .= " * Title: " . $pattern_data['title'] . "\n";
    $php_content .= " * Slug: " . $pattern_slug . "\n";
    $php_content .= " * Description: " . ($pattern_data['description'] ?? '') . "\n";
    $php_content .= " * Categories: " . implode(', ', $pattern_data['categories'] ?? array()) . "\n";
    $php_content .= " * Keywords: " . implode(', ', $pattern_data['keywords'] ?? array()) . "\n";
    $php_content .= " * Viewport Width: " . ($pattern_data['viewportWidth'] ?? 1280) . "\n";
    $php_content .= " */\n";
    $php_content .= "?>\n";
    $php_content .= $pattern_data['content'];

    // Write file
    $result = file_put_contents($pattern_file, $php_content);

    if ($result === false) {
        add_settings_error('reforestamos_patterns', 'write_error', __('Error writing pattern file.', 'reforestamos'));
        return;
    }

    add_settings_error('reforestamos_patterns', 'import_success', __('Pattern imported successfully!', 'reforestamos'), 'success');
}

/**
 * Register custom patterns from wp-content directory
 */
function reforestamos_register_custom_patterns() {
    $custom_patterns_dir = WP_CONTENT_DIR . '/reforestamos-custom-patterns/';
    
    if (!is_dir($custom_patterns_dir)) {
        return;
    }

    $pattern_files = glob($custom_patterns_dir . '*.php');
    
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
add_action('init', 'reforestamos_register_custom_patterns', 11);

/**
 * Display admin notices
 */
function reforestamos_pattern_manager_notices() {
    settings_errors('reforestamos_patterns');
}
add_action('admin_notices', 'reforestamos_pattern_manager_notices');
