<?php
/**
 * Event Registration Functionality
 *
 * Handles event registration with capacity validation.
 * Integrates with Communication Plugin forms.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render event registration form
 *
 * @param int $event_id Event post ID
 * @return string Form HTML
 */
function reforestamos_render_event_registration_form($event_id) {
    // Get event data
    $capacidad = get_post_meta($event_id, 'evento_capacidad', true);
    $registro_activo = get_post_meta($event_id, 'evento_registro_activo', true);
    $fecha = get_post_meta($event_id, 'evento_fecha', true);
    
    // Check if registration is active
    if (!$registro_activo) {
        return '<div class="alert alert-info">' . esc_html__('El registro para este evento no está disponible.', 'reforestamos') . '</div>';
    }
    
    // Check if event has passed
    if ($fecha && $fecha < time()) {
        return '<div class="alert alert-warning">' . esc_html__('Este evento ya ha finalizado.', 'reforestamos') . '</div>';
    }
    
    // Get current registrations count
    $registrations_count = reforestamos_get_event_registrations_count($event_id);
    
    // Check capacity
    if ($capacidad && $registrations_count >= $capacidad) {
        return '<div class="alert alert-danger">' . esc_html__('Este evento ha alcanzado su capacidad máxima.', 'reforestamos') . '</div>';
    }
    
    // Calculate remaining spots
    $remaining_spots = $capacidad ? ($capacidad - $registrations_count) : null;
    
    ob_start();
    ?>
    <div class="event-registration-form">
        <?php if ($remaining_spots !== null && $remaining_spots <= 10) : ?>
            <div class="alert alert-warning">
                <?php
                printf(
                    esc_html(_n('¡Solo queda %d lugar disponible!', '¡Solo quedan %d lugares disponibles!', $remaining_spots, 'reforestamos')),
                    $remaining_spots
                );
                ?>
            </div>
        <?php endif; ?>
        
        <form id="event-registration-form-<?php echo esc_attr($event_id); ?>" 
              class="event-registration-form-inner" 
              method="post" 
              action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
            
            <input type="hidden" name="action" value="reforestamos_register_event">
            <input type="hidden" name="event_id" value="<?php echo esc_attr($event_id); ?>">
            <?php wp_nonce_field('event_registration_' . $event_id, 'event_registration_nonce'); ?>
            
            <div class="form-group mb-3">
                <label for="reg_nombre_<?php echo esc_attr($event_id); ?>" class="form-label">
                    <?php esc_html_e('Nombre completo', 'reforestamos'); ?> <span class="required">*</span>
                </label>
                <input type="text" 
                       class="form-control" 
                       id="reg_nombre_<?php echo esc_attr($event_id); ?>" 
                       name="nombre" 
                       required>
            </div>
            
            <div class="form-group mb-3">
                <label for="reg_email_<?php echo esc_attr($event_id); ?>" class="form-label">
                    <?php esc_html_e('Correo electrónico', 'reforestamos'); ?> <span class="required">*</span>
                </label>
                <input type="email" 
                       class="form-control" 
                       id="reg_email_<?php echo esc_attr($event_id); ?>" 
                       name="email" 
                       required>
            </div>
            
            <div class="form-group mb-3">
                <label for="reg_telefono_<?php echo esc_attr($event_id); ?>" class="form-label">
                    <?php esc_html_e('Teléfono', 'reforestamos'); ?>
                </label>
                <input type="tel" 
                       class="form-control" 
                       id="reg_telefono_<?php echo esc_attr($event_id); ?>" 
                       name="telefono">
            </div>
            
            <div class="form-group mb-3">
                <label for="reg_comentarios_<?php echo esc_attr($event_id); ?>" class="form-label">
                    <?php esc_html_e('Comentarios adicionales', 'reforestamos'); ?>
                </label>
                <textarea class="form-control" 
                          id="reg_comentarios_<?php echo esc_attr($event_id); ?>" 
                          name="comentarios" 
                          rows="3"></textarea>
            </div>
            
            <div class="form-group mb-3">
                <div class="form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="reg_acepto_<?php echo esc_attr($event_id); ?>" 
                           name="acepto_terminos" 
                           required>
                    <label class="form-check-label" for="reg_acepto_<?php echo esc_attr($event_id); ?>">
                        <?php esc_html_e('Acepto los términos y condiciones', 'reforestamos'); ?> <span class="required">*</span>
                    </label>
                </div>
            </div>
            
            <div class="form-message"></div>
            
            <button type="submit" class="btn btn-primary btn-lg">
                <?php esc_html_e('Registrarme al evento', 'reforestamos'); ?>
            </button>
        </form>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * Get event registrations count
 *
 * @param int $event_id Event post ID
 * @return int Number of registrations
 */
function reforestamos_get_event_registrations_count($event_id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'event_registrations';
    
    // Check if table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        return 0;
    }
    
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE event_id = %d AND status = 'confirmed'",
        $event_id
    ));
    
    return intval($count);
}

/**
 * Process event registration via AJAX
 */
function reforestamos_process_event_registration() {
    // Verify nonce
    $event_id = isset($_POST['event_id']) ? absint($_POST['event_id']) : 0;
    
    if (!isset($_POST['event_registration_nonce']) || 
        !wp_verify_nonce($_POST['event_registration_nonce'], 'event_registration_' . $event_id)) {
        wp_send_json_error(array('message' => __('Error de seguridad. Por favor, recarga la página.', 'reforestamos')));
    }
    
    // Validate event exists
    if (!$event_id || get_post_type($event_id) !== 'eventos') {
        wp_send_json_error(array('message' => __('Evento no válido.', 'reforestamos')));
    }
    
    // Check if registration is active
    $registro_activo = get_post_meta($event_id, 'evento_registro_activo', true);
    if (!$registro_activo) {
        wp_send_json_error(array('message' => __('El registro para este evento no está disponible.', 'reforestamos')));
    }
    
    // Check capacity
    $capacidad = get_post_meta($event_id, 'evento_capacidad', true);
    $registrations_count = reforestamos_get_event_registrations_count($event_id);
    
    if ($capacidad && $registrations_count >= $capacidad) {
        wp_send_json_error(array('message' => __('Este evento ha alcanzado su capacidad máxima.', 'reforestamos')));
    }
    
    // Sanitize input
    $nombre = isset($_POST['nombre']) ? sanitize_text_field($_POST['nombre']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $telefono = isset($_POST['telefono']) ? sanitize_text_field($_POST['telefono']) : '';
    $comentarios = isset($_POST['comentarios']) ? sanitize_textarea_field($_POST['comentarios']) : '';
    $acepto_terminos = isset($_POST['acepto_terminos']) ? true : false;
    
    // Validate required fields
    if (empty($nombre) || empty($email) || !$acepto_terminos) {
        wp_send_json_error(array('message' => __('Por favor, completa todos los campos requeridos.', 'reforestamos')));
    }
    
    // Validate email
    if (!is_email($email)) {
        wp_send_json_error(array('message' => __('Por favor, ingresa un correo electrónico válido.', 'reforestamos')));
    }
    
    // Check for duplicate registration
    global $wpdb;
    $table_name = $wpdb->prefix . 'event_registrations';
    
    // Create table if it doesn't exist
    reforestamos_create_registrations_table();
    
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE event_id = %d AND email = %s",
        $event_id,
        $email
    ));
    
    if ($existing) {
        wp_send_json_error(array('message' => __('Ya estás registrado para este evento.', 'reforestamos')));
    }
    
    // Insert registration
    $inserted = $wpdb->insert(
        $table_name,
        array(
            'event_id' => $event_id,
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'comentarios' => $comentarios,
            'status' => 'confirmed',
            'registered_at' => current_time('mysql')
        ),
        array('%d', '%s', '%s', '%s', '%s', '%s', '%s')
    );
    
    if (!$inserted) {
        wp_send_json_error(array('message' => __('Error al procesar el registro. Por favor, intenta de nuevo.', 'reforestamos')));
    }
    
    // Send confirmation email
    reforestamos_send_registration_confirmation($event_id, $nombre, $email);
    
    wp_send_json_success(array(
        'message' => __('¡Registro exitoso! Recibirás un correo de confirmación.', 'reforestamos')
    ));
}
add_action('wp_ajax_reforestamos_register_event', 'reforestamos_process_event_registration');
add_action('wp_ajax_nopriv_reforestamos_register_event', 'reforestamos_process_event_registration');

/**
 * Create registrations table
 */
function reforestamos_create_registrations_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'event_registrations';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        event_id bigint(20) NOT NULL,
        nombre varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        telefono varchar(50) DEFAULT NULL,
        comentarios text DEFAULT NULL,
        status varchar(20) DEFAULT 'confirmed',
        registered_at datetime NOT NULL,
        PRIMARY KEY  (id),
        KEY event_id (event_id),
        KEY email (email)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * Send registration confirmation email
 *
 * @param int $event_id Event post ID
 * @param string $nombre Registrant name
 * @param string $email Registrant email
 */
function reforestamos_send_registration_confirmation($event_id, $nombre, $email) {
    $event = get_post($event_id);
    $fecha = get_post_meta($event_id, 'evento_fecha', true);
    $ubicacion = get_post_meta($event_id, 'evento_ubicacion', true);
    
    $fecha_formatted = $fecha ? date_i18n('l, j \d\e F \d\e Y \a \l\a\s H:i', $fecha) : '';
    
    $subject = sprintf(__('Confirmación de registro - %s', 'reforestamos'), $event->post_title);
    
    $message = sprintf(
        __("Hola %s,\n\nGracias por registrarte al evento:\n\n%s\n\nFecha: %s\nUbicación: %s\n\nNos vemos pronto!\n\nSaludos,\nEquipo Reforestamos México", 'reforestamos'),
        $nombre,
        $event->post_title,
        $fecha_formatted,
        $ubicacion
    );
    
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    wp_mail($email, $subject, $message, $headers);
}

/**
 * Event registration shortcode
 *
 * Usage: [evento-registro id="123"]
 */
function reforestamos_event_registration_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => get_the_ID(),
    ), $atts);
    
    $event_id = absint($atts['id']);
    
    if (!$event_id || get_post_type($event_id) !== 'eventos') {
        return '<div class="alert alert-danger">' . esc_html__('Evento no encontrado.', 'reforestamos') . '</div>';
    }
    
    return reforestamos_render_event_registration_form($event_id);
}
add_shortcode('evento-registro', 'reforestamos_event_registration_shortcode');

/**
 * Enqueue registration form scripts
 */
function reforestamos_enqueue_registration_scripts() {
    if (is_singular('eventos') || has_shortcode(get_post()->post_content, 'evento-registro')) {
        wp_enqueue_script(
            'reforestamos-event-registration',
            REFORESTAMOS_THEME_URI . '/src/js/event-registration.js',
            array('jquery'),
            REFORESTAMOS_VERSION,
            true
        );
        
        wp_localize_script('reforestamos-event-registration', 'reforestamosEventReg', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'processing' => __('Procesando...', 'reforestamos'),
            'error' => __('Error al procesar el registro.', 'reforestamos')
        ));
    }
}
add_action('wp_enqueue_scripts', 'reforestamos_enqueue_registration_scripts');
