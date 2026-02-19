<?php
// Formulario de contacto Arboles y Ciudades
function formulario_AyC() {
    // Verificar nonce
    if (!isset($_POST['my_form_nonce']) || !wp_verify_nonce($_POST['my_form_nonce'], 'submit_contact_form')) {
        wp_send_json_error(['message' => 'Nonce no válido']);
        wp_die();
    }

    // Verificar y sanitizar los datos recibidos
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? preg_replace('/[^\d]/', '', sanitize_text_field($_POST['phone'])) : '';
    $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
    $message = isset($_POST['message']) ? esc_textarea($_POST['message']) : '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        wp_send_json_error(['message' => 'Faltan campos por completar']);
        wp_die();
    }
    $correo_arboles_ciudades = get_post_meta(get_the_ID(), 'correo_arboles_ciudades', true);

    // Configurar el destinatario y encabezados
    $to = ['albertocortes@reforestamos.org', 'alberto3000cortes0003@gmail.com'];
    $title = "Árboles y Ciudades formulario de contacto";
    $headers = ["From: $email", "Reply-To: print_r($to)", "Content-Type: text/html"];

    // Cuerpo del mensaje
    $body = '
        <html>
            <body>
                <h3 style="text-align:center;">Nuevo mensaje de </h3>
                    <p><span style="font-weight:bold;">Nombre:</span></br> ' . $name . '</p>
                    <p><span style="font-weight:bold;">Correo:</span></br> ' . $email . '</p>
                    <p><span style="font-weight:bold;">Teléfono:</span></br> ' . $phone . '</p>
                    <p><span style="font-weight:bold;">Asunto:</span></br> ' . $subject . '</p>
                    <p><span style="font-weight:bold;">Mensaje:</span></br> ' . $message . '</p>
            </body>
        </html>
    ';

    // Intentar enviar el correo
    $sent = wp_mail($to, $title, $body, $headers);
    error_log("Datos del formulario: " . print_r($_POST, true));

    // Enviar un correo de confirmación al usuario
    $confirmation_subject = "Gracias por tu mensaje";
    $confirmation_message = "Hola $name, gracias por contactarnos. Hemos recibido tu mensaje y te responderemos pronto.";
    $confirmation_headers = [ "From: albertocortes@reforestamos.org", "Content-Type: text/html"];

    $send_confirmation = wp_mail($email, $confirmation_subject, $confirmation_message, $confirmation_headers);

    if ($sent && $send_confirmation) {
        wp_send_json_success(['message' => 'Correo enviado correctamente a: '. $to]);
    } else {
        wp_send_json_error(['message' => 'Error al enviar el correo']);
    }

    wp_die();
}
add_action('wp_ajax_formulario_AyC', 'formulario_AyC');
add_action('wp_ajax_nopriv_formulario_AyC', 'formulario_AyC');

?>