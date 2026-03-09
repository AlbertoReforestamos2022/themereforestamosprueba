<?php
/**
 * Newsletter Campaigns Admin Page
 *
 * @package Reforestamos_Comunicacion
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Campañas de Newsletter', 'reforestamos-comunicacion'); ?></h1>
    
    <?php
    // Show messages
    if (isset($_GET['message'])) {
        switch ($_GET['message']) {
            case 'sent':
                $sent_count = isset($_GET['sent_count']) ? intval($_GET['sent_count']) : 0;
                $scheduled_count = isset($_GET['scheduled_count']) ? intval($_GET['scheduled_count']) : 0;
                
                if ($scheduled_count > 0) {
                    echo '<div class="notice notice-success is-dismissible"><p>';
                    printf(
                        __('Newsletter programado para envío a %d suscriptores en lotes. El envío comenzará en breve.', 'reforestamos-comunicacion'),
                        $scheduled_count
                    );
                    echo '</p></div>';
                } else {
                    echo '<div class="notice notice-success is-dismissible"><p>';
                    printf(
                        __('Newsletter enviado exitosamente a %d suscriptores.', 'reforestamos-comunicacion'),
                        $sent_count
                    );
                    echo '</p></div>';
                }
                break;
            case 'error':
                echo '<div class="notice notice-error is-dismissible"><p>';
                _e('Error al enviar el newsletter.', 'reforestamos-comunicacion');
                echo '</p></div>';
                break;
            case 'no_recipients':
                echo '<div class="notice notice-warning is-dismissible"><p>';
                _e('No hay suscriptores activos para enviar el newsletter.', 'reforestamos-comunicacion');
                echo '</p></div>';
                break;
        }
    }
    ?>
    
    <div class="newsletter-stats" style="margin: 20px 0;">
        <div class="card" style="max-width: 300px;">
            <h3><?php _e('Estadísticas', 'reforestamos-comunicacion'); ?></h3>
            <p>
                <strong><?php _e('Suscriptores activos:', 'reforestamos-comunicacion'); ?></strong>
                <?php echo esc_html($subscriber_count); ?>
            </p>
            <p>
                <strong><?php _e('Boletines publicados:', 'reforestamos-comunicacion'); ?></strong>
                <?php echo count($newsletters); ?>
            </p>
        </div>
    </div>
    
    <h2><?php _e('Boletines Disponibles', 'reforestamos-comunicacion'); ?></h2>
    
    <?php if (empty($newsletters)): ?>
        <p><?php _e('No hay boletines publicados.', 'reforestamos-comunicacion'); ?></p>
        <p>
            <a href="<?php echo admin_url('post-new.php?post_type=boletin'); ?>" class="button button-primary">
                <?php _e('Crear Nuevo Boletín', 'reforestamos-comunicacion'); ?>
            </a>
        </p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Título', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Fecha de Publicación', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Estado de Envío', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Acciones', 'reforestamos-comunicacion'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsletters as $newsletter): ?>
                    <?php
                    // Check if newsletter has been sent
                    global $wpdb;
                    $log_table = $wpdb->prefix . 'reforestamos_newsletter_logs';
                    $sent_count = $wpdb->get_var($wpdb->prepare(
                        "SELECT COUNT(DISTINCT subscriber_id) FROM $log_table WHERE newsletter_id = %d AND status = 'sent'",
                        $newsletter->ID
                    ));
                    ?>
                    <tr>
                        <td>
                            <strong>
                                <a href="<?php echo get_edit_post_link($newsletter->ID); ?>">
                                    <?php echo esc_html($newsletter->post_title); ?>
                                </a>
                            </strong>
                        </td>
                        <td><?php echo get_the_date('', $newsletter->ID); ?></td>
                        <td>
                            <?php if ($sent_count > 0): ?>
                                <span class="dashicons dashicons-yes-alt" style="color: green;"></span>
                                <?php printf(__('Enviado a %d suscriptores', 'reforestamos-comunicacion'), $sent_count); ?>
                            <?php else: ?>
                                <span class="dashicons dashicons-minus" style="color: #999;"></span>
                                <?php _e('No enviado', 'reforestamos-comunicacion'); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="display: inline;">
                                <?php wp_nonce_field('send_newsletter', 'newsletter_nonce'); ?>
                                <input type="hidden" name="action" value="send_newsletter">
                                <input type="hidden" name="newsletter_id" value="<?php echo esc_attr($newsletter->ID); ?>">
                                <input type="hidden" name="recipient_type" value="all">
                                
                                <button type="submit" class="button button-primary" 
                                        onclick="return confirm('<?php esc_attr_e('¿Estás seguro de enviar este newsletter a todos los suscriptores activos?', 'reforestamos-comunicacion'); ?>');">
                                    <?php _e('Enviar Newsletter', 'reforestamos-comunicacion'); ?>
                                </button>
                            </form>
                            
                            <a href="<?php echo get_permalink($newsletter->ID); ?>" class="button" target="_blank">
                                <?php _e('Vista Previa', 'reforestamos-comunicacion'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p style="margin-top: 20px;">
            <a href="<?php echo admin_url('post-new.php?post_type=boletin'); ?>" class="button button-primary">
                <?php _e('Crear Nuevo Boletín', 'reforestamos-comunicacion'); ?>
            </a>
        </p>
    <?php endif; ?>
    
    <hr>
    
    <h2><?php _e('Historial de Envíos', 'reforestamos-comunicacion'); ?></h2>
    
    <?php
    // Get recent send logs
    global $wpdb;
    $log_table = $wpdb->prefix . 'reforestamos_newsletter_logs';
    $recent_logs = $wpdb->get_results(
        "SELECT newsletter_id, COUNT(*) as total, 
                SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
                MAX(sent_at) as last_sent
         FROM $log_table 
         GROUP BY newsletter_id 
         ORDER BY last_sent DESC 
         LIMIT 10"
    );
    ?>
    
    <?php if (empty($recent_logs)): ?>
        <p><?php _e('No hay historial de envíos.', 'reforestamos-comunicacion'); ?></p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Newsletter', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Enviados', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Fallidos', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Último Envío', 'reforestamos-comunicacion'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_logs as $log): ?>
                    <?php $newsletter = get_post($log->newsletter_id); ?>
                    <tr>
                        <td>
                            <?php if ($newsletter): ?>
                                <a href="<?php echo get_edit_post_link($newsletter->ID); ?>">
                                    <?php echo esc_html($newsletter->post_title); ?>
                                </a>
                            <?php else: ?>
                                <?php _e('(Boletín eliminado)', 'reforestamos-comunicacion'); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="color: green;">
                                <?php echo esc_html($log->sent); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($log->failed > 0): ?>
                                <span style="color: red;">
                                    <?php echo esc_html($log->failed); ?>
                                </span>
                            <?php else: ?>
                                <span style="color: #999;">0</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html($log->last_sent); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
.newsletter-stats .card {
    padding: 20px;
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.newsletter-stats .card h3 {
    margin-top: 0;
}

.newsletter-stats .card p {
    margin: 10px 0;
}
</style>
