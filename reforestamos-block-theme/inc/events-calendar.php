<?php
/**
 * Events Calendar Functionality
 *
 * Provides calendar view for events with monthly navigation.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render events calendar
 *
 * @param int $year Year to display
 * @param int $month Month to display
 * @return string Calendar HTML
 */
function reforestamos_render_events_calendar($year = null, $month = null) {
    // Default to current month/year
    if (!$year) {
        $year = date('Y');
    }
    if (!$month) {
        $month = date('n');
    }
    
    // Sanitize inputs
    $year = absint($year);
    $month = absint($month);
    
    // Validate month
    if ($month < 1 || $month > 12) {
        $month = date('n');
    }
    
    // Get events for this month
    $events = reforestamos_get_events_for_month($year, $month);
    
    // Calendar data
    $first_day = mktime(0, 0, 0, $month, 1, $year);
    $days_in_month = date('t', $first_day);
    $day_of_week = date('w', $first_day);
    $month_name = date_i18n('F Y', $first_day);
    
    // Previous/Next month links
    $prev_month = $month - 1;
    $prev_year = $year;
    if ($prev_month < 1) {
        $prev_month = 12;
        $prev_year--;
    }
    
    $next_month = $month + 1;
    $next_year = $year;
    if ($next_month > 12) {
        $next_month = 1;
        $next_year++;
    }
    
    ob_start();
    ?>
    <div class="reforestamos-calendar">
        <div class="calendar-header">
            <a href="?year=<?php echo esc_attr($prev_year); ?>&month=<?php echo esc_attr($prev_month); ?>" 
               class="calendar-nav prev">
                <span class="dashicons dashicons-arrow-left-alt2"></span>
                <?php esc_html_e('Anterior', 'reforestamos'); ?>
            </a>
            <h2 class="calendar-title"><?php echo esc_html($month_name); ?></h2>
            <a href="?year=<?php echo esc_attr($next_year); ?>&month=<?php echo esc_attr($next_month); ?>" 
               class="calendar-nav next">
                <?php esc_html_e('Siguiente', 'reforestamos'); ?>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
            </a>
        </div>
        
        <table class="calendar-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Dom', 'reforestamos'); ?></th>
                    <th><?php esc_html_e('Lun', 'reforestamos'); ?></th>
                    <th><?php esc_html_e('Mar', 'reforestamos'); ?></th>
                    <th><?php esc_html_e('Mié', 'reforestamos'); ?></th>
                    <th><?php esc_html_e('Jue', 'reforestamos'); ?></th>
                    <th><?php esc_html_e('Vie', 'reforestamos'); ?></th>
                    <th><?php esc_html_e('Sáb', 'reforestamos'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $day = 1;
                $weeks = ceil(($days_in_month + $day_of_week) / 7);
                
                for ($week = 0; $week < $weeks; $week++) {
                    echo '<tr>';
                    
                    for ($dow = 0; $dow < 7; $dow++) {
                        if (($week == 0 && $dow < $day_of_week) || $day > $days_in_month) {
                            echo '<td class="calendar-day empty"></td>';
                        } else {
                            $current_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            $day_events = isset($events[$current_date]) ? $events[$current_date] : array();
                            $has_events = !empty($day_events);
                            $is_today = ($current_date == date('Y-m-d'));
                            
                            $classes = array('calendar-day');
                            if ($has_events) {
                                $classes[] = 'has-events';
                            }
                            if ($is_today) {
                                $classes[] = 'today';
                            }
                            
                            echo '<td class="' . esc_attr(implode(' ', $classes)) . '">';
                            echo '<div class="day-number">' . esc_html($day) . '</div>';
                            
                            if ($has_events) {
                                echo '<div class="day-events">';
                                foreach ($day_events as $event) {
                                    echo '<a href="' . esc_url(get_permalink($event->ID)) . '" class="event-marker" title="' . esc_attr($event->post_title) . '">';
                                    echo '<span class="event-dot"></span>';
                                    echo '<span class="event-title">' . esc_html($event->post_title) . '</span>';
                                    echo '</a>';
                                }
                                echo '</div>';
                            }
                            
                            echo '</td>';
                            $day++;
                        }
                    }
                    
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        
        <?php if (!empty($events)) : ?>
        <div class="calendar-events-list">
            <h3><?php esc_html_e('Eventos este mes', 'reforestamos'); ?></h3>
            <ul class="events-list">
                <?php
                foreach ($events as $date => $day_events) {
                    foreach ($day_events as $event) {
                        $fecha = get_post_meta($event->ID, 'evento_fecha', true);
                        $ubicacion = get_post_meta($event->ID, 'evento_ubicacion', true);
                        $fecha_formatted = $fecha ? date_i18n('j \d\e F, Y', $fecha) : '';
                        ?>
                        <li class="event-item">
                            <a href="<?php echo esc_url(get_permalink($event->ID)); ?>">
                                <span class="event-date"><?php echo esc_html($fecha_formatted); ?></span>
                                <span class="event-title"><?php echo esc_html($event->post_title); ?></span>
                                <?php if ($ubicacion) : ?>
                                    <span class="event-location">
                                        <span class="dashicons dashicons-location"></span>
                                        <?php echo esc_html($ubicacion); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * Get events for a specific month
 *
 * @param int $year Year
 * @param int $month Month
 * @return array Events grouped by date
 */
function reforestamos_get_events_for_month($year, $month) {
    $start_date = sprintf('%04d-%02d-01 00:00:00', $year, $month);
    $end_date = sprintf('%04d-%02d-%02d 23:59:59', $year, $month, date('t', mktime(0, 0, 0, $month, 1, $year)));
    
    $args = array(
        'post_type' => 'eventos',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_key' => 'evento_fecha',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'evento_fecha',
                'value' => array(strtotime($start_date), strtotime($end_date)),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            )
        )
    );
    
    $query = new WP_Query($args);
    $events = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $fecha = get_post_meta(get_the_ID(), 'evento_fecha', true);
            if ($fecha) {
                $date_key = date('Y-m-d', $fecha);
                if (!isset($events[$date_key])) {
                    $events[$date_key] = array();
                }
                $events[$date_key][] = get_post();
            }
        }
        wp_reset_postdata();
    }
    
    return $events;
}

/**
 * Calendar shortcode
 *
 * Usage: [eventos-calendario]
 */
function reforestamos_calendar_shortcode($atts) {
    $atts = shortcode_atts(array(
        'year' => isset($_GET['year']) ? absint($_GET['year']) : date('Y'),
        'month' => isset($_GET['month']) ? absint($_GET['month']) : date('n'),
    ), $atts);
    
    return reforestamos_render_events_calendar($atts['year'], $atts['month']);
}
add_shortcode('eventos-calendario', 'reforestamos_calendar_shortcode');
