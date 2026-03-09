<?php
/**
 * Translation Links Functions
 *
 * Handles displaying links between translated versions of content
 * and integration with DeepL translation system.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Translation Links Manager
 */
class Reforestamos_Translation_Links {
    
    /**
     * Initialize hooks
     */
    public static function init() {
        // Add translation links to content
        add_filter('the_content', array(__CLASS__, 'add_translation_link_to_content'), 20);
        
        // Register shortcode
        add_shortcode('translation_link', array(__CLASS__, 'translation_link_shortcode'));
        
        // Add translation notice in admin
        add_action('edit_form_after_title', array(__CLASS__, 'add_translation_notice'));
        
        // Add quick link to translation in admin bar
        add_action('admin_bar_menu', array(__CLASS__, 'add_admin_bar_translation_link'), 100);
    }
    
    /**
     * Add translation link to post content
     *
     * @param string $content Post content
     * @return string Modified content
     */
    public static function add_translation_link_to_content($content) {
        if (!is_singular()) {
            return $content;
        }
        
        $post_id = get_the_ID();
        if (!$post_id) {
            return $content;
        }
        
        $current_lang = reforestamos_get_current_language();
        $target_lang = $current_lang === 'es' ? 'en' : 'es';
        $translation_link = reforestamos_get_translation_link($post_id, $target_lang);
        
        if (!$translation_link) {
            return $content;
        }
        
        $link_html = self::get_translation_link_html($translation_link, $target_lang);
        
        // Add link after content
        $content .= $link_html;
        
        return $content;
    }
    
    /**
     * Get translation link HTML
     *
     * @param string $url Translation URL
     * @param string $target_lang Target language code
     * @return string HTML for translation link
     */
    private static function get_translation_link_html($url, $target_lang) {
        $text = $target_lang === 'es' 
            ? __('🇪🇸 Ver este contenido en Español', 'reforestamos')
            : __('🇬🇧 View this content in English', 'reforestamos');
        
        ob_start();
        ?>
        <div class="translation-link-container">
            <div class="translation-link-box">
                <span class="translation-icon">🌐</span>
                <div class="translation-link-content">
                    <p class="translation-link-label">
                        <?php echo $target_lang === 'es' 
                            ? esc_html__('Este contenido está disponible en otro idioma', 'reforestamos')
                            : esc_html__('This content is available in another language', 'reforestamos'); ?>
                    </p>
                    <a href="<?php echo esc_url($url); ?>" class="btn btn-primary translation-link-button">
                        <?php echo esc_html($text); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Translation link shortcode
     *
     * Usage: [translation_link]
     * Usage: [translation_link lang="en"]
     * Usage: [translation_link post_id="123"]
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public static function translation_link_shortcode($atts) {
        $atts = shortcode_atts(array(
            'post_id' => get_the_ID(),
            'lang' => null,
            'text' => '',
            'class' => 'btn btn-outline-primary',
            'show_icon' => 'yes',
        ), $atts);
        
        $post_id = intval($atts['post_id']);
        if (!$post_id) {
            return '';
        }
        
        $current_lang = reforestamos_get_current_language();
        $target_lang = $atts['lang'] ? $atts['lang'] : ($current_lang === 'es' ? 'en' : 'es');
        
        $translation_link = reforestamos_get_translation_link($post_id, $target_lang);
        
        if (!$translation_link) {
            return '';
        }
        
        // Default text based on target language
        if (empty($atts['text'])) {
            $atts['text'] = $target_lang === 'es' 
                ? __('Ver en Español', 'reforestamos')
                : __('View in English', 'reforestamos');
        }
        
        $icon = $atts['show_icon'] === 'yes' ? '🌐 ' : '';
        
        return sprintf(
            '<a href="%s" class="%s">%s%s</a>',
            esc_url($translation_link),
            esc_attr($atts['class']),
            $icon,
            esc_html($atts['text'])
        );
    }
    
    /**
     * Add translation notice in post editor
     *
     * @param WP_Post $post Current post object
     */
    public static function add_translation_notice($post) {
        $translated_post_id = get_post_meta($post->ID, '_translated_post_id', true);
        $original_post_id = get_post_meta($post->ID, '_original_post_id', true);
        $translation_lang = get_post_meta($post->ID, '_translation_lang', true);
        
        if (!$translated_post_id && !$original_post_id) {
            return;
        }
        
        ?>
        <div class="notice notice-info inline" style="margin: 15px 0;">
            <p>
                <?php if ($translated_post_id && get_post($translated_post_id)) : ?>
                    <strong><?php esc_html_e('Traducción:', 'reforestamos'); ?></strong>
                    <?php
                    $trans_lang = get_post_meta($translated_post_id, '_translation_lang', true);
                    $lang_name = $trans_lang === 'EN' ? 'English' : 'Español';
                    ?>
                    <?php esc_html_e('Este contenido tiene una traducción en', 'reforestamos'); ?> 
                    <strong><?php echo esc_html($lang_name); ?></strong>.
                    <a href="<?php echo esc_url(get_edit_post_link($translated_post_id)); ?>" target="_blank">
                        <?php esc_html_e('Editar traducción →', 'reforestamos'); ?>
                    </a>
                    |
                    <a href="<?php echo esc_url(get_permalink($translated_post_id)); ?>" target="_blank">
                        <?php esc_html_e('Ver traducción →', 'reforestamos'); ?>
                    </a>
                <?php elseif ($original_post_id && get_post($original_post_id)) : ?>
                    <strong><?php esc_html_e('Original:', 'reforestamos'); ?></strong>
                    <?php esc_html_e('Este es un contenido traducido. El original está en Español.', 'reforestamos'); ?>
                    <a href="<?php echo esc_url(get_edit_post_link($original_post_id)); ?>" target="_blank">
                        <?php esc_html_e('Editar original →', 'reforestamos'); ?>
                    </a>
                    |
                    <a href="<?php echo esc_url(get_permalink($original_post_id)); ?>" target="_blank">
                        <?php esc_html_e('Ver original →', 'reforestamos'); ?>
                    </a>
                <?php endif; ?>
            </p>
        </div>
        <?php
    }
    
    /**
     * Add translation link to admin bar
     *
     * @param WP_Admin_Bar $wp_admin_bar Admin bar object
     */
    public static function add_admin_bar_translation_link($wp_admin_bar) {
        if (!is_singular() || !is_admin_bar_showing()) {
            return;
        }
        
        $post_id = get_the_ID();
        if (!$post_id) {
            return;
        }
        
        $current_lang = reforestamos_get_current_language();
        $target_lang = $current_lang === 'es' ? 'en' : 'es';
        $translation_link = reforestamos_get_translation_link($post_id, $target_lang);
        
        if (!$translation_link) {
            return;
        }
        
        $translated_post_id = reforestamos_get_translated_post_id($post_id, $target_lang);
        
        $title = $target_lang === 'es' 
            ? '🇪🇸 Ver en Español'
            : '🇬🇧 View in English';
        
        $wp_admin_bar->add_node(array(
            'id' => 'view-translation',
            'title' => $title,
            'href' => $translation_link,
            'meta' => array(
                'target' => '_blank',
            ),
        ));
        
        // Add edit translation link if user can edit
        if ($translated_post_id && current_user_can('edit_post', $translated_post_id)) {
            $wp_admin_bar->add_node(array(
                'id' => 'edit-translation',
                'parent' => 'view-translation',
                'title' => __('Editar traducción', 'reforestamos'),
                'href' => get_edit_post_link($translated_post_id),
            ));
        }
    }
    
    /**
     * Get all posts that need translation
     *
     * @param string $target_lang Target language code
     * @param array $post_types Post types to check
     * @return array Array of post IDs
     */
    public static function get_posts_needing_translation($target_lang = 'en', $post_types = array('post', 'page')) {
        $args = array(
            'post_type' => $post_types,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_translated_post_id',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key' => '_original_post_id',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        );
        
        $query = new WP_Query($args);
        return $query->posts;
    }
    
    /**
     * Get translation statistics
     *
     * @return array Translation statistics
     */
    public static function get_statistics() {
        global $wpdb;
        
        $stats = array(
            'total_posts' => 0,
            'translated_posts' => 0,
            'untranslated_posts' => 0,
            'translation_percentage' => 0,
        );
        
        // Get total published posts
        $total_posts = wp_count_posts('post');
        $stats['total_posts'] = $total_posts->publish;
        
        // Get posts with translations
        $translated = $wpdb->get_var(
            "SELECT COUNT(DISTINCT post_id) 
             FROM {$wpdb->postmeta} 
             WHERE meta_key = '_translated_post_id' 
             AND meta_value != ''"
        );
        $stats['translated_posts'] = intval($translated);
        
        // Calculate untranslated
        $stats['untranslated_posts'] = $stats['total_posts'] - $stats['translated_posts'];
        
        // Calculate percentage
        if ($stats['total_posts'] > 0) {
            $stats['translation_percentage'] = round(
                ($stats['translated_posts'] / $stats['total_posts']) * 100,
                2
            );
        }
        
        return $stats;
    }
}

// Initialize translation links
Reforestamos_Translation_Links::init();

/**
 * Helper function to display translation link
 *
 * @param int $post_id Optional. Post ID. Default current post.
 * @param array $args Optional. Arguments for customizing the link.
 */
function reforestamos_the_translation_link($post_id = null, $args = array()) {
    echo reforestamos_get_translation_link_html($post_id, $args);
}

/**
 * Helper function to get translation link HTML
 *
 * @param int $post_id Optional. Post ID. Default current post.
 * @param array $args Optional. Arguments for customizing the link.
 * @return string Translation link HTML
 */
function reforestamos_get_translation_link_html($post_id = null, $args = array()) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (!$post_id) {
        return '';
    }
    
    $defaults = array(
        'before' => '<div class="translation-link">',
        'after' => '</div>',
        'text_es' => __('Ver en Español', 'reforestamos'),
        'text_en' => __('View in English', 'reforestamos'),
        'class' => 'btn btn-outline-primary btn-sm',
        'show_icon' => true,
    );
    
    $args = wp_parse_args($args, $defaults);
    $current_lang = reforestamos_get_current_language();
    $target_lang = $current_lang === 'es' ? 'en' : 'es';
    $translation_link = reforestamos_get_translation_link($post_id, $target_lang);
    
    if (!$translation_link) {
        return '';
    }
    
    $text = $target_lang === 'es' ? $args['text_es'] : $args['text_en'];
    $icon = $args['show_icon'] ? '🌐 ' : '';
    
    $html = $args['before'];
    $html .= sprintf(
        '<a href="%s" class="%s">%s%s</a>',
        esc_url($translation_link),
        esc_attr($args['class']),
        $icon,
        esc_html($text)
    );
    $html .= $args['after'];
    
    return $html;
}
