<?php
/**
 * iCal Export Functionality
 *
 * Generates .ics files for events following RFC 5545 iCalendar specification.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate iCal file for an event
 *
 * @param int $event_id Event post ID
 */
function reforestamos_generate_ical($event_id) {
    // Validate event
    if (!$event_id || get_post_type($event_id) !== 'eventos') {
        wp_die(esc_html__('Evento no válido.', 'reforestamos'));
    }
    
    $event = get_post($event_id);
    
    // Get event metadata
    $fecha = get_post_meta($event_id, 'evento_fecha', true);
    $ubicacion = get_post_meta($event_id, 'evento_ubicacion', true);
    $lat = get_post_meta($event_id, 'evento_lat', true);
    $lng = get_post_meta($event_id, 'evento_lng', true);
    
    if (!$fecha) {
        wp_die(esc_html__('El evento no tiene fecha configurada.', 'reforestamos'));
    }
    
    // Generate iCal content
    $ical = reforestamos_build_ical_content($event, $fecha, $ubicacion, $lat, $lng);
    
    // Set headers for download
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="evento-' . sanitize_title($event->post_title) . '.ics"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    
    // Output iCal content
    echo $ical;
    exit;
}

/**
 * Build iCal content following RFC 5545
 *
 * @param WP_Post $event Event post object
 * @param int $fecha Event timestamp
 * @param string $ubicacion Event location
 * @param float $lat Latitude
 * @param float $lng Longitude
 * @return string iCal formatted content
 */
function reforestamos_build_ical_content($event, $fecha, $ubicacion, $lat, $lng) {
    // Format dates for iCal (YYYYMMDDTHHMMSSZ)
    $dtstart = gmdate('Ymd\THis\Z', $fecha);
    $dtend = gmdate('Ymd\THis\Z', $fecha + 3600); // Default 1 hour duration
    $dtstamp = gmdate('Ymd\THis\Z');
    
    // Generate unique ID
    $uid = $event->ID . '-' . $fecha . '@' . parse_url(home_url(), PHP_URL_HOST);
    
    // Escape text for iCal format
    $summary = reforestamos_ical_escape($event->post_title);
    $description = reforestamos_ical_escape(wp_strip_all_tags($event->post_content));
    $location = reforestamos_ical_escape($ubicacion);
    
    // Build iCal content
    $ical = "BEGIN:VCALENDAR\r\n";
    $ical .= "VERSION:2.0\r\n";
    $ical .= "PRODID:-//Reforestamos México//Eventos//ES\r\n";
    $ical .= "CALSCALE:GREGORIAN\r\n";
    $ical .= "METHOD:PUBLISH\r\n";
    $ical .= "X-WR-CALNAME:Reforestamos México - Eventos\r\n";
    $ical .= "X-WR-TIMEZONE:America/Mexico_City\r\n";
    $ical .= "X-WR-CALDESC:Eventos de reforestación de Reforestamos México\r\n";
    
    $ical .= "BEGIN:VEVENT\r\n";
    $ical .= "UID:" . $uid . "\r\n";
    $ical .= "DTSTAMP:" . $dtstamp . "\r\n";
    $ical .= "DTSTART:" . $dtstart . "\r\n";
    $ical .= "DTEND:" . $dtend . "\r\n";
    $ical .= "SUMMARY:" . $summary . "\r\n";
    
    if (!empty($description)) {
        $ical .= "DESCRIPTION:" . $description . "\r\n";
    }
    
    if (!empty($location)) {
        $ical .= "LOCATION:" . $location . "\r\n";
    }
    
    // Add geo coordinates if available
    if (!empty($lat) && !empty($lng)) {
        $ical .= "GEO:" . floatval($lat) . ";" . floatval($lng) . "\r\n";
    }
    
    // Add URL to event page
    $ical .= "URL:" . get_permalink($event->ID) . "\r\n";
    
    // Add organizer
    $ical .= "ORGANIZER;CN=Reforestamos México:MAILTO:" . get_option('admin_email') . "\r\n";
    
    // Add status
    $ical .= "STATUS:CONFIRMED\r\n";
    
    // Add sequence (for updates)
    $ical .= "SEQUENCE:0\r\n";
    
    // Add last modified
    $last_modified = gmdate('Ymd\THis\Z', strtotime($event->post_modified_gmt));
    $ical .= "LAST-MODIFIED:" . $last_modified . "\r\n";
    
    $ical .= "END:VEVENT\r\n";
    $ical .= "END:VCALENDAR\r\n";
    
    return $ical;
}

/**
 * Escape text for iCal format
 *
 * @param string $text Text to escape
 * @return string Escaped text
 */
function reforestamos_ical_escape($text) {
    // Remove HTML tags
    $text = wp_strip_all_tags($text);
    
    // Escape special characters
    $text = str_replace(array('\\', ',', ';', "\n", "\r"), array('\\\\', '\\,', '\\;', '\\n', ''), $text);
    
    // Limit line length to 75 characters (RFC 5545)
    $text = reforestamos_ical_fold_line($text);
    
    return $text;
}

/**
 * Fold long lines for iCal format (max 75 chars per line)
 *
 * @param string $text Text to fold
 * @return string Folded text
 */
function reforestamos_ical_fold_line($text) {
    // If text is shorter than 75 chars, return as is
    if (strlen($text) <= 75) {
        return $text;
    }
    
    // Split into chunks of 75 characters
    $chunks = str_split($text, 75);
    
    // Join with line breaks and space (continuation)
    return implode("\r\n ", $chunks);
}

/**
 * Handle iCal download request
 */
function reforestamos_handle_ical_download() {
    if (isset($_GET['ical_download']) && isset($_GET['event_id'])) {
        $event_id = absint($_GET['event_id']);
        
        // Verify nonce for security
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'ical_download_' . $event_id)) {
            wp_die(esc_html__('Error de seguridad.', 'reforestamos'));
        }
        
        reforestamos_generate_ical($event_id);
    }
}
add_action('template_redirect', 'reforestamos_handle_ical_download');

/**
 * Get iCal download URL for an event
 *
 * @param int $event_id Event post ID
 * @return string Download URL
 */
function reforestamos_get_ical_download_url($event_id) {
    return wp_nonce_url(
        add_query_arg(
            array(
                'ical_download' => '1',
                'event_id' => $event_id
            ),
            home_url()
        ),
        'ical_download_' . $event_id
    );
}

/**
 * Render iCal download button
 *
 * @param int $event_id Event post ID
 * @return string Button HTML
 */
function reforestamos_render_ical_button($event_id = null) {
    if (!$event_id) {
        $event_id = get_the_ID();
    }
    
    if (!$event_id || get_post_type($event_id) !== 'eventos') {
        return '';
    }
    
    $download_url = reforestamos_get_ical_download_url($event_id);
    
    ob_start();
    ?>
    <a href="<?php echo esc_url($download_url); ?>" 
       class="btn btn-outline-primary ical-download-btn"
       title="<?php esc_attr_e('Agregar a mi calendario', 'reforestamos'); ?>">
        <span class="dashicons dashicons-calendar-alt"></span>
        <?php esc_html_e('Agregar a calendario', 'reforestamos'); ?>
    </a>
    <?php
    
    return ob_get_clean();
}

/**
 * iCal download button shortcode
 *
 * Usage: [ical-download id="123"]
 */
function reforestamos_ical_button_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => get_the_ID(),
    ), $atts);
    
    return reforestamos_render_ical_button(absint($atts['id']));
}
add_shortcode('ical-download', 'reforestamos_ical_button_shortcode');

/**
 * Add iCal button to single event pages automatically
 */
function reforestamos_add_ical_button_to_content($content) {
    if (is_singular('eventos') && in_the_loop() && is_main_query()) {
        $button = '<div class="event-actions">' . reforestamos_render_ical_button() . '</div>';
        $content .= $button;
    }
    
    return $content;
}
add_filter('the_content', 'reforestamos_add_ical_button_to_content', 20);

/**
 * Add iCal meta tag to event pages for better calendar integration
 */
function reforestamos_add_ical_meta_tag() {
    if (is_singular('eventos')) {
        $event_id = get_the_ID();
        $ical_url = reforestamos_get_ical_download_url($event_id);
        
        echo '<link rel="alternate" type="text/calendar" href="' . esc_url($ical_url) . '" title="' . esc_attr__('Agregar a calendario', 'reforestamos') . '">' . "\n";
    }
}
add_action('wp_head', 'reforestamos_add_ical_meta_tag');
