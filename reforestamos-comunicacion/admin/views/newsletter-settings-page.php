<?php
/**
 * Newsletter Settings Admin Page
 *
 * @package Reforestamos_Comunicacion
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Configuración de Newsletter', 'reforestamos-comunicacion'); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('newsletter_settings', 'newsletter_settings_nonce'); ?>
        
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="batch_size"><?php _e('Tamaño de Lote', 'reforestamos-comunicacion'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="batch_size" name="batch_size" value="<?php echo esc_attr($batch_size); ?>" min="1" max="200" class="regular-text">
                        <p class="description">
                            <?php _e('Número de emails a enviar por lote antes de hacer una pausa. Recomendado: 50-100 emails.', 'reforestamos-comunicacion'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="batch_delay"><?php _e('Pausa entre Lotes (segundos)', 'reforestamos-comunicacion'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="batch_delay" name="batch_delay" value="<?php echo esc_attr($batch_delay); ?>" min="1" max="60" class="regular-text">
                        <p class="description">
                            <?php _e('Tiempo de espera en segundos entre cada lote de emails. Recomendado: 2-5 segundos.', 'reforestamos-comunicacion'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="max_retries"><?php _e('Máximo de Reintentos', 'reforestamos-comunicacion'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="max_retries" name="max_retries" value="<?php echo esc_attr($max_retries); ?>" min="0" max="10" class="regular-text">
                        <p class="description">
                            <?php _e('Número máximo de veces que se reintentará enviar un email fallido. Recomendado: 3 reintentos.', 'reforestamos-comunicacion'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="use_cron"><?php _e('Usar Procesamiento en Segundo Plano', 'reforestamos-comunicacion'); ?></label>
                    </th>
                    <td>
                        <select id="use_cron" name="use_cron">
                            <option value="yes" <?php selected($use_cron, 'yes'); ?>><?php _e('Sí', 'reforestamos-comunicacion'); ?></option>
                            <option value="no" <?php selected($use_cron, 'no'); ?>><?php _e('No', 'reforestamos-comunicacion'); ?></option>
                        </select>
                        <p class="description">
                            <?php _e('Cuando está activado, los newsletters con muchos destinatarios se enviarán en segundo plano usando WP Cron. Esto evita timeouts en el navegador.', 'reforestamos-comunicacion'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="auto_retry"><?php _e('Reintento Automático', 'reforestamos-comunicacion'); ?></label>
                    </th>
                    <td>
                        <select id="auto_retry" name="auto_retry">
                            <option value="yes" <?php selected($auto_retry, 'yes'); ?>><?php _e('Sí', 'reforestamos-comunicacion'); ?></option>
                            <option value="no" <?php selected($auto_retry, 'no'); ?>><?php _e('No', 'reforestamos-comunicacion'); ?></option>
                        </select>
                        <p class="description">
                            <?php _e('Cuando está activado, el sistema reintentará automáticamente enviar emails fallidos cada hora.', 'reforestamos-comunicacion'); ?>
                        </p>
                        <?php if ($auto_retry === 'yes'): ?>
                            <?php
                            $next_retry = wp_next_scheduled('reforestamos_retry_failed_newsletter');
                            if ($next_retry) {
                                echo '<p class="description" style="color: #46b450;">';
                                printf(
                                    __('Próximo reintento automático: %s', 'reforestamos-comunicacion'),
                                    date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $next_retry)
                                );
                                echo '</p>';
                            }
                            ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p class="submit">
            <input type="submit" name="save_newsletter_settings" class="button button-primary" value="<?php esc_attr_e('Guardar Configuración', 'reforestamos-comunicacion'); ?>">
        </p>
    </form>
    
    <hr>
    
    <h2><?php _e('Información del Sistema', 'reforestamos-comunicacion'); ?></h2>
    
    <table class="widefat">
        <tbody>
            <tr>
                <td><strong><?php _e('WP Cron Status:', 'reforestamos-comunicacion'); ?></strong></td>
                <td>
                    <?php if (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON): ?>
                        <span style="color: #dc3232;">
                            <?php _e('Desactivado', 'reforestamos-comunicacion'); ?>
                            <br>
                            <small><?php _e('WP Cron está desactivado. El procesamiento en segundo plano no funcionará correctamente.', 'reforestamos-comunicacion'); ?></small>
                        </span>
                    <?php else: ?>
                        <span style="color: #46b450;"><?php _e('Activado', 'reforestamos-comunicacion'); ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e('Tareas Programadas:', 'reforestamos-comunicacion'); ?></strong></td>
                <td>
                    <?php
                    $cron_jobs = _get_cron_array();
                    $newsletter_jobs = 0;
                    
                    foreach ($cron_jobs as $timestamp => $cron) {
                        if (isset($cron['reforestamos_process_newsletter_batch'])) {
                            $newsletter_jobs += count($cron['reforestamos_process_newsletter_batch']);
                        }
                    }
                    
                    if ($newsletter_jobs > 0) {
                        printf(
                            __('%d lotes de newsletter programados para envío', 'reforestamos-comunicacion'),
                            $newsletter_jobs
                        );
                    } else {
                        _e('No hay envíos programados actualmente', 'reforestamos-comunicacion');
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong><?php _e('Límite de Memoria PHP:', 'reforestamos-comunicacion'); ?></strong></td>
                <td><?php echo ini_get('memory_limit'); ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('Tiempo Máximo de Ejecución:', 'reforestamos-comunicacion'); ?></strong></td>
                <td><?php echo ini_get('max_execution_time'); ?> <?php _e('segundos', 'reforestamos-comunicacion'); ?></td>
            </tr>
        </tbody>
    </table>
    
    <hr>
    
    <h2><?php _e('Recomendaciones', 'reforestamos-comunicacion'); ?></h2>
    
    <div class="card">
        <h3><?php _e('Configuración Óptima', 'reforestamos-comunicacion'); ?></h3>
        <ul>
            <li><?php _e('Para listas pequeñas (< 500 suscriptores): Tamaño de lote 50, Pausa 2 segundos, Sin procesamiento en segundo plano', 'reforestamos-comunicacion'); ?></li>
            <li><?php _e('Para listas medianas (500-2000 suscriptores): Tamaño de lote 100, Pausa 3 segundos, Con procesamiento en segundo plano', 'reforestamos-comunicacion'); ?></li>
            <li><?php _e('Para listas grandes (> 2000 suscriptores): Tamaño de lote 100, Pausa 5 segundos, Con procesamiento en segundo plano y reintento automático', 'reforestamos-comunicacion'); ?></li>
        </ul>
        
        <h3><?php _e('Límites de Proveedores de Email', 'reforestamos-comunicacion'); ?></h3>
        <ul>
            <li><strong>Gmail/Google Workspace:</strong> <?php _e('500 emails por día (cuenta gratuita), 2000 por día (Google Workspace)', 'reforestamos-comunicacion'); ?></li>
            <li><strong>SendGrid:</strong> <?php _e('100 emails por día (plan gratuito)', 'reforestamos-comunicacion'); ?></li>
            <li><strong>Mailgun:</strong> <?php _e('5000 emails por mes (plan gratuito)', 'reforestamos-comunicacion'); ?></li>
        </ul>
        
        <p>
            <em><?php _e('Ajusta la configuración según los límites de tu proveedor de email para evitar bloqueos.', 'reforestamos-comunicacion'); ?></em>
        </p>
    </div>
</div>
