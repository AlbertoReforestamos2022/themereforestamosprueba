<?php
/**
 * Newsletter Send Logs Admin Page
 *
 * @package Reforestamos_Comunicacion
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Logs de Envío de Newsletter', 'reforestamos-comunicacion'); ?></h1>
    
    <!-- Statistics Cards -->
    <div class="newsletter-stats" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 20px 0;">
        <div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #0073aa; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><?php _e('Total Envíos', 'reforestamos-comunicacion'); ?></h3>
            <p style="margin: 0; font-size: 32px; font-weight: bold; color: #0073aa;"><?php echo number_format($stats['total']); ?></p>
        </div>
        
        <div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #46b450; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><?php _e('Enviados', 'reforestamos-comunicacion'); ?></h3>
            <p style="margin: 0; font-size: 32px; font-weight: bold; color: #46b450;"><?php echo number_format($stats['sent']); ?></p>
        </div>
        
        <div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #dc3232; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><?php _e('Fallidos', 'reforestamos-comunicacion'); ?></h3>
            <p style="margin: 0; font-size: 32px; font-weight: bold; color: #dc3232;"><?php echo number_format($stats['failed']); ?></p>
        </div>
        
        <div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #ffb900; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><?php _e('Pendientes', 'reforestamos-comunicacion'); ?></h3>
            <p style="margin: 0; font-size: 32px; font-weight: bold; color: #ffb900;"><?php echo number_format($stats['pending']); ?></p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="tablenav top">
        <form method="get" style="display: inline-block;">
            <input type="hidden" name="post_type" value="boletin">
            <input type="hidden" name="page" value="newsletter-send-logs">
            
            <select name="newsletter_id" style="margin-right: 10px;">
                <option value=""><?php _e('Todas las newsletters', 'reforestamos-comunicacion'); ?></option>
                <?php foreach ($newsletters as $newsletter): ?>
                    <option value="<?php echo esc_attr($newsletter->ID); ?>" <?php selected($newsletter_id, $newsletter->ID); ?>>
                        <?php echo esc_html($newsletter->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <select name="status" style="margin-right: 10px;">
                <option value=""><?php _e('Todos los estados', 'reforestamos-comunicacion'); ?></option>
                <option value="sent" <?php selected($status_filter, 'sent'); ?>><?php _e('Enviados', 'reforestamos-comunicacion'); ?></option>
                <option value="failed" <?php selected($status_filter, 'failed'); ?>><?php _e('Fallidos', 'reforestamos-comunicacion'); ?></option>
                <option value="pending" <?php selected($status_filter, 'pending'); ?>><?php _e('Pendientes', 'reforestamos-comunicacion'); ?></option>
            </select>
            
            <button type="submit" class="button"><?php _e('Filtrar', 'reforestamos-comunicacion'); ?></button>
            
            <?php if ($newsletter_id || $status_filter): ?>
                <a href="<?php echo admin_url('edit.php?post_type=boletin&page=newsletter-send-logs'); ?>" class="button">
                    <?php _e('Limpiar filtros', 'reforestamos-comunicacion'); ?>
                </a>
            <?php endif; ?>
        </form>
        
        <?php if ($newsletter_id && $stats['failed'] > 0): ?>
            <button type="button" class="button button-primary" id="retry-failed-sends" style="margin-left: 10px;">
                <?php _e('Reintentar Envíos Fallidos', 'reforestamos-comunicacion'); ?>
            </button>
        <?php endif; ?>
    </div>
    
    <!-- Logs Table -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('ID', 'reforestamos-comunicacion'); ?></th>
                <th><?php _e('Newsletter', 'reforestamos-comunicacion'); ?></th>
                <th><?php _e('Email', 'reforestamos-comunicacion'); ?></th>
                <th><?php _e('Estado', 'reforestamos-comunicacion'); ?></th>
                <th><?php _e('Intentos', 'reforestamos-comunicacion'); ?></th>
                <th><?php _e('Fecha de Envío', 'reforestamos-comunicacion'); ?></th>
                <th><?php _e('Error', 'reforestamos-comunicacion'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        <?php _e('No se encontraron logs de envío.', 'reforestamos-comunicacion'); ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <?php
                    $newsletter = get_post($log['newsletter_id']);
                    $status_class = '';
                    $status_text = '';
                    
                    switch ($log['status']) {
                        case 'sent':
                            $status_class = 'success';
                            $status_text = __('Enviado', 'reforestamos-comunicacion');
                            break;
                        case 'failed':
                            $status_class = 'error';
                            $status_text = __('Fallido', 'reforestamos-comunicacion');
                            break;
                        case 'pending':
                            $status_class = 'warning';
                            $status_text = __('Pendiente', 'reforestamos-comunicacion');
                            break;
                    }
                    ?>
                    <tr>
                        <td><?php echo esc_html($log['id']); ?></td>
                        <td>
                            <?php if ($newsletter): ?>
                                <a href="<?php echo get_edit_post_link($newsletter->ID); ?>">
                                    <?php echo esc_html($newsletter->post_title); ?>
                                </a>
                            <?php else: ?>
                                <em><?php _e('Newsletter eliminado', 'reforestamos-comunicacion'); ?></em>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html($log['email']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo esc_attr($status_class); ?>" style="padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: 600;">
                                <?php echo esc_html($status_text); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html($log['retry_count']); ?></td>
                        <td><?php echo esc_html(mysql2date('Y-m-d H:i:s', $log['sent_at'])); ?></td>
                        <td>
                            <?php if ($log['error_message']): ?>
                                <span style="color: #dc3232; font-size: 12px;">
                                    <?php echo esc_html($log['error_message']); ?>
                                </span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <span class="displaying-num">
                    <?php printf(__('%s elementos', 'reforestamos-comunicacion'), number_format($total_logs)); ?>
                </span>
                <?php
                $page_links = paginate_links([
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'total' => $total_pages,
                    'current' => $page
                ]);
                
                if ($page_links) {
                    echo '<span class="pagination-links">' . $page_links . '</span>';
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .status-badge.status-success {
        background-color: #d4edda;
        color: #155724;
    }
    .status-badge.status-error {
        background-color: #f8d7da;
        color: #721c24;
    }
    .status-badge.status-warning {
        background-color: #fff3cd;
        color: #856404;
    }
</style>

<script>
jQuery(document).ready(function($) {
    $('#retry-failed-sends').on('click', function() {
        if (!confirm('<?php _e('¿Estás seguro de que deseas reintentar todos los envíos fallidos?', 'reforestamos-comunicacion'); ?>')) {
            return;
        }
        
        var button = $(this);
        button.prop('disabled', true).text('<?php _e('Reintentando...', 'reforestamos-comunicacion'); ?>');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'retry_failed_sends',
                newsletter_id: <?php echo intval($newsletter_id); ?>,
                retry_nonce: '<?php echo wp_create_nonce('retry_failed_sends'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message);
                    button.prop('disabled', false).text('<?php _e('Reintentar Envíos Fallidos', 'reforestamos-comunicacion'); ?>');
                }
            },
            error: function() {
                alert('<?php _e('Error al procesar la solicitud', 'reforestamos-comunicacion'); ?>');
                button.prop('disabled', false).text('<?php _e('Reintentar Envíos Fallidos', 'reforestamos-comunicacion'); ?>');
            }
        });
    });
});
</script>
