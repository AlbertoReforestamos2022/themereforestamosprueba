<?php
/**
 * Events Archive Functionality
 *
 * Handles filtering of events by date (upcoming vs past).
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modify eventos archive query to show only upcoming events by default
 *
 * @param WP_Query $query The WordPress query object
 */
function reforestamos_filter_eventos_archive($query) {
    // Only modify main query on eventos archive
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('eventos')) {
        // Check if we want to show past events
        $show_past = isset($_GET['show_past']) && $_GET['show_past'] === '1';
        
        if ($show_past) {
            // Show only past events
            $query->set('meta_query', array(
                array(
                    'key' => 'evento_fecha',
                    'value' => time(),
                    'compare' => '<',
                    'type' => 'NUMERIC'
                )
            ));
            $query->set('meta_key', 'evento_fecha');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
        } else {
            // Show only upcoming events (default)
            $query->set('meta_query', array(
                array(
                    'key' => 'evento_fecha',
                    'value' => time(),
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                )
            ));
            $query->set('meta_key', 'evento_fecha');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'ASC');
        }
    }
}
add_action('pre_get_posts', 'reforestamos_filter_eventos_archive');

/**
 * Add archive filter toggle to eventos archive
 */
function reforestamos_eventos_archive_filter() {
    if (is_post_type_archive('eventos')) {
        $show_past = isset($_GET['show_past']) && $_GET['show_past'] === '1';
        $current_url = remove_query_arg('show_past');
        
        ?>
        <div class="eventos-archive-filter">
            <div class="container">
                <div class="filter-buttons">
                    <?php if ($show_past) : ?>
                        <a href="<?php echo esc_url($current_url); ?>" class="btn btn-outline-primary">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php esc_html_e('Ver eventos próximos', 'reforestamos'); ?>
                        </a>
                        <span class="current-filter">
                            <span class="dashicons dashicons-clock"></span>
                            <?php esc_html_e('Mostrando eventos pasados', 'reforestamos'); ?>
                        </span>
                    <?php else : ?>
                        <span class="current-filter">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php esc_html_e('Mostrando eventos próximos', 'reforestamos'); ?>
                        </span>
                        <a href="<?php echo esc_url(add_query_arg('show_past', '1', $current_url)); ?>" class="btn btn-outline-secondary">
                            <span class="dashicons dashicons-clock"></span>
                            <?php esc_html_e('Ver eventos pasados', 'reforestamos'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_head', 'reforestamos_eventos_archive_filter_styles');

/**
 * Add inline styles for archive filter
 */
function reforestamos_eventos_archive_filter_styles() {
    if (is_post_type_archive('eventos')) {
        ?>
        <style>
            .eventos-archive-filter {
                background: var(--wp--preset--color--light, #f1f8e9);
                padding: 1.5rem 0;
                margin-bottom: 2rem;
                border-bottom: 2px solid var(--wp--preset--color--primary, #2e7d32);
            }
            
            .eventos-archive-filter .filter-buttons {
                display: flex;
                gap: 1rem;
                align-items: center;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .eventos-archive-filter .current-filter {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: var(--wp--preset--color--primary, #2e7d32);
                color: var(--wp--preset--color--white, #fff);
                border-radius: 4px;
                font-weight: 600;
            }
            
            .eventos-archive-filter .btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            .eventos-archive-filter .dashicons {
                font-size: 20px;
                width: 20px;
                height: 20px;
            }
            
            @media (max-width: 768px) {
                .eventos-archive-filter .filter-buttons {
                    flex-direction: column;
                }
                
                .eventos-archive-filter .btn,
                .eventos-archive-filter .current-filter {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
        <?php
    }
}

/**
 * Add filter UI before archive content
 */
function reforestamos_add_eventos_filter_ui() {
    if (is_post_type_archive('eventos')) {
        reforestamos_eventos_archive_filter();
    }
}
add_action('wp_body_open', 'reforestamos_add_eventos_filter_ui', 20);

/**
 * Get upcoming events count
 *
 * @return int Number of upcoming events
 */
function reforestamos_get_upcoming_events_count() {
    $args = array(
        'post_type' => 'eventos',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'evento_fecha',
                'value' => time(),
                'compare' => '>=',
                'type' => 'NUMERIC'
            )
        ),
        'fields' => 'ids'
    );
    
    $query = new WP_Query($args);
    return $query->found_posts;
}

/**
 * Get past events count
 *
 * @return int Number of past events
 */
function reforestamos_get_past_events_count() {
    $args = array(
        'post_type' => 'eventos',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'evento_fecha',
                'value' => time(),
                'compare' => '<',
                'type' => 'NUMERIC'
            )
        ),
        'fields' => 'ids'
    );
    
    $query = new WP_Query($args);
    return $query->found_posts;
}

/**
 * Display event status badge
 *
 * @param int $event_id Event post ID
 * @return string Badge HTML
 */
function reforestamos_get_event_status_badge($event_id) {
    $fecha = get_post_meta($event_id, 'evento_fecha', true);
    $registro_activo = get_post_meta($event_id, 'evento_registro_activo', true);
    
    if (!$fecha) {
        return '';
    }
    
    $is_past = $fecha < time();
    
    if ($is_past) {
        return '<span class="badge bg-secondary">' . esc_html__('Finalizado', 'reforestamos') . '</span>';
    } elseif ($registro_activo) {
        return '<span class="badge bg-success">' . esc_html__('Registro abierto', 'reforestamos') . '</span>';
    } else {
        return '<span class="badge bg-warning">' . esc_html__('Próximamente', 'reforestamos') . '</span>';
    }
}

/**
 * Shortcode to display event status
 *
 * Usage: [evento-estado id="123"]
 */
function reforestamos_event_status_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => get_the_ID(),
    ), $atts);
    
    $event_id = absint($atts['id']);
    
    if (!$event_id || get_post_type($event_id) !== 'eventos') {
        return '';
    }
    
    return reforestamos_get_event_status_badge($event_id);
}
add_shortcode('evento-estado', 'reforestamos_event_status_shortcode');
