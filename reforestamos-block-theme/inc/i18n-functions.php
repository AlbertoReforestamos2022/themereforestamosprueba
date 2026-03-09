<?php
/**
 * Internationalization Functions
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get current language
 *
 * @return string Current language code (es or en)
 */
function reforestamos_get_current_language() {
    return Reforestamos_Language_Persistence::get_language();
}

/**
 * Set current language
 *
 * @param string $lang Language code (es or en)
 * @return bool True on success, false on failure
 */
function reforestamos_set_language($lang) {
    return Reforestamos_Language_Persistence::set_language($lang);
}

/**
 * Get language switcher HTML
 *
 * @param array $args Optional. Arguments for customizing the switcher.
 * @return string Language switcher HTML
 */
function reforestamos_get_language_switcher($args = array()) {
    $defaults = array(
        'show_flags' => true,
        'show_text' => true,
        'class' => 'language-switcher',
        'button_class' => 'lang-btn',
    );
    
    $args = wp_parse_args($args, $defaults);
    $current_lang = reforestamos_get_current_language();
    $current_url = home_url(add_query_arg(array(), $_SERVER['REQUEST_URI']));
    
    ob_start();
    ?>
    <div class="<?php echo esc_attr($args['class']); ?>">
        <a href="<?php echo esc_url(add_query_arg('lang', 'es', $current_url)); ?>" 
           class="<?php echo esc_attr($args['button_class']); ?> <?php echo $current_lang === 'es' ? 'active' : ''; ?>"
           data-lang="es"
           aria-label="<?php esc_attr_e('Cambiar a Español', 'reforestamos'); ?>">
            <?php if ($args['show_flags']) : ?>
                <span class="flag-icon">🇪🇸</span>
            <?php endif; ?>
            <?php if ($args['show_text']) : ?>
                <span class="lang-text">ES</span>
            <?php endif; ?>
        </a>
        <a href="<?php echo esc_url(add_query_arg('lang', 'en', $current_url)); ?>" 
           class="<?php echo esc_attr($args['button_class']); ?> <?php echo $current_lang === 'en' ? 'active' : ''; ?>"
           data-lang="en"
           aria-label="<?php esc_attr_e('Switch to English', 'reforestamos'); ?>">
            <?php if ($args['show_flags']) : ?>
                <span class="flag-icon">🇬🇧</span>
            <?php endif; ?>
            <?php if ($args['show_text']) : ?>
                <span class="lang-text">EN</span>
            <?php endif; ?>
        </a>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Display language switcher
 *
 * @param array $args Optional. Arguments for customizing the switcher.
 */
function reforestamos_language_switcher($args = array()) {
    echo reforestamos_get_language_switcher($args);
}

/**
 * Handle language switching via URL parameter
 */
function reforestamos_handle_language_switch() {
    if (isset($_GET['lang'])) {
        $lang = sanitize_text_field($_GET['lang']);
        if (in_array($lang, array('es', 'en'))) {
            reforestamos_set_language($lang);
            
            // Redirect to remove lang parameter from URL
            $redirect_url = remove_query_arg('lang');
            wp_safe_redirect($redirect_url);
            exit;
        }
    }
}
add_action('template_redirect', 'reforestamos_handle_language_switch');

/**
 * Get translated post ID
 *
 * @param int $post_id Post ID
 * @param string $target_lang Target language code
 * @return int|false Translated post ID or false if not found
 */
function reforestamos_get_translated_post_id($post_id, $target_lang) {
    $current_lang = get_post_meta($post_id, '_translation_lang', true);
    
    // If current post is in target language, return same ID
    if ($current_lang === strtoupper($target_lang)) {
        return $post_id;
    }
    
    // Check if this is a translation
    $original_post_id = get_post_meta($post_id, '_original_post_id', true);
    if ($original_post_id) {
        // This is a translation, check if original is in target language
        $original_lang = get_post_meta($original_post_id, '_translation_lang', true);
        if (!$original_lang || $original_lang === 'ES') {
            // Original is Spanish
            if ($target_lang === 'es') {
                return $original_post_id;
            }
        }
        
        // Look for translation from original
        $translated_id = get_post_meta($original_post_id, '_translated_post_id', true);
        if ($translated_id && get_post($translated_id)) {
            return $translated_id;
        }
    } else {
        // This is original, look for translation
        $translated_id = get_post_meta($post_id, '_translated_post_id', true);
        if ($translated_id && get_post($translated_id)) {
            $trans_lang = get_post_meta($translated_id, '_translation_lang', true);
            if ($trans_lang === strtoupper($target_lang)) {
                return $translated_id;
            }
        }
    }
    
    return false;
}

/**
 * Get translation link for current post
 *
 * @param int $post_id Optional. Post ID. Default current post.
 * @param string $target_lang Optional. Target language. Default opposite of current.
 * @return string|false Translation URL or false if not available
 */
function reforestamos_get_translation_link($post_id = null, $target_lang = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (!$post_id) {
        return false;
    }
    
    if (!$target_lang) {
        $current_lang = reforestamos_get_current_language();
        $target_lang = $current_lang === 'es' ? 'en' : 'es';
    }
    
    $translated_post_id = reforestamos_get_translated_post_id($post_id, $target_lang);
    
    if ($translated_post_id && $translated_post_id !== $post_id) {
        return get_permalink($translated_post_id);
    }
    
    return false;
}

/**
 * Display translation link
 *
 * @param array $args Optional. Arguments for customizing the link.
 */
function reforestamos_translation_link($args = array()) {
    $defaults = array(
        'before' => '<div class="translation-link">',
        'after' => '</div>',
        'text_es' => __('Ver en Español', 'reforestamos'),
        'text_en' => __('View in English', 'reforestamos'),
        'class' => 'btn btn-outline-primary btn-sm',
    );
    
    $args = wp_parse_args($args, $defaults);
    $current_lang = reforestamos_get_current_language();
    $target_lang = $current_lang === 'es' ? 'en' : 'es';
    $translation_link = reforestamos_get_translation_link(null, $target_lang);
    
    if ($translation_link) {
        echo $args['before'];
        echo '<a href="' . esc_url($translation_link) . '" class="' . esc_attr($args['class']) . '">';
        echo esc_html($target_lang === 'es' ? $args['text_es'] : $args['text_en']);
        echo '</a>';
        echo $args['after'];
    }
}

/**
 * Enqueue language switcher scripts
 */
function reforestamos_enqueue_language_switcher_scripts() {
    wp_enqueue_script(
        'reforestamos-language-switcher',
        get_template_directory_uri() . '/build/language-switcher.js',
        array('jquery'),
        REFORESTAMOS_VERSION,
        true
    );
    
    wp_localize_script('reforestamos-language-switcher', 'reforestamosI18n', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('reforestamos_lang_switch'),
        'currentLang' => reforestamos_get_current_language(),
    ));
}
add_action('wp_enqueue_scripts', 'reforestamos_enqueue_language_switcher_scripts');

/**
 * AJAX handler for language switching
 */
function reforestamos_ajax_switch_language() {
    check_ajax_referer('reforestamos_lang_switch', 'nonce');
    
    $lang = isset($_POST['lang']) ? sanitize_text_field($_POST['lang']) : '';
    
    if (!in_array($lang, array('es', 'en'))) {
        wp_send_json_error(array(
            'message' => __('Invalid language code', 'reforestamos'),
        ));
    }
    
    if (reforestamos_set_language($lang)) {
        wp_send_json_success(array(
            'message' => __('Language changed successfully', 'reforestamos'),
            'lang' => $lang,
        ));
    } else {
        wp_send_json_error(array(
            'message' => __('Failed to change language', 'reforestamos'),
        ));
    }
}
add_action('wp_ajax_reforestamos_switch_language', 'reforestamos_ajax_switch_language');
add_action('wp_ajax_nopriv_reforestamos_switch_language', 'reforestamos_ajax_switch_language');
