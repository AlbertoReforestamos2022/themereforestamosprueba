<?php
/**
 * Subscribers List Admin Page
 *
 * @package Reforestamos_Comunicacion
 */

if (!defined('ABSPATH')) {
    exit;
}

// Handle bulk actions
if (isset($_POST['action']) && isset($_POST['subscribers'])) {
    check_admin_referer('bulk-subscribers');
    
    $action = sanitize_text_field($_POST['action']);
    $subscriber_ids = array_map('intval', $_POST['subscribers']);
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'reforestamos_subscribers';
    
    switch ($action) {
        case 'delete':
            foreach ($subscriber_ids as $id) {
                $wpdb->delete($table_name, ['id' => $id], ['%d']);
            }
            $message = 'deleted';
            break;
        case 'activate':
            foreach ($subscriber_ids as $id) {
                $wpdb->update(
                    $table_name,
                    ['status' => 'active'],
                    ['id' => $id],
                    ['%s'],
                    ['%d']
                );
            }
            $message = 'activated';
            break;
        case 'deactivate':
            foreach ($subscriber_ids as $id) {
                $wpdb->update(
                    $table_name,
                    ['status' => 'unsubscribed'],
                    ['id' => $id],
                    ['%s'],
                    ['%d']
                );
            }
            $message = 'deactivated';
            break;
    }
    
    wp_redirect(add_query_arg([
        'page' => 'newsletter-subscribers',
        'message' => $message
    ], admin_url('edit.php?post_type=boletin')));
    exit;
}

// Handle single subscriber actions
if (isset($_GET['action']) && isset($_GET['subscriber'])) {
    check_admin_referer('subscriber-action');
    
    $action = sanitize_text_field($_GET['action']);
    $subscriber_id = intval($_GET['subscriber']);
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'reforestamos_subscribers';
    
    switch ($action) {
        case 'delete':
            $wpdb->delete($table_name, ['id' => $subscriber_id], ['%d']);
            $message = 'deleted';
            break;
        case 'activate':
            $wpdb->update(
                $table_name,
                ['status' => 'active'],
                ['id' => $subscriber_id],
                ['%s'],
                ['%d']
            );
            $message = 'activated';
            break;
        case 'deactivate':
            $wpdb->update(
                $table_name,
                ['status' => 'unsubscribed'],
                ['id' => $subscriber_id],
                ['%s'],
                ['%d']
            );
            $message = 'deactivated';
            break;
    }
    
    wp_redirect(add_query_arg([
        'page' => 'newsletter-subscribers',
        'message' => $message
    ], admin_url('edit.php?post_type=boletin')));
    exit;
}

// Get pagination parameters
$paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$per_page = 20;
$offset = ($paged - 1) * $per_page;

// Get filter parameters
$status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';
$search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

// Build query
global $wpdb;
$table_name = $wpdb->prefix . 'reforestamos_subscribers';

$where = [];
$where_values = [];

if ($status_filter !== 'all') {
    $where[] = 'status = %s';
    $where_values[] = $status_filter;
}

if (!empty($search)) {
    $where[] = '(email LIKE %s OR name LIKE %s)';
    $where_values[] = '%' . $wpdb->esc_like($search) . '%';
    $where_values[] = '%' . $wpdb->esc_like($search) . '%';
}

$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
if (!empty($where_values)) {
    $total_items = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name $where_clause",
        $where_values
    ));
} else {
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name $where_clause");
}

// Get subscribers
$query = "SELECT * FROM $table_name $where_clause ORDER BY subscribed_at DESC LIMIT %d OFFSET %d";
$query_values = array_merge($where_values, [$per_page, $offset]);

if (!empty($where_values)) {
    $subscribers = $wpdb->get_results($wpdb->prepare($query, $query_values));
} else {
    $subscribers = $wpdb->get_results($wpdb->prepare($query, $per_page, $offset));
}

// Calculate pagination
$total_pages = ceil($total_items / $per_page);

// Get status counts
$active_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'active'");
$unsubscribed_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'unsubscribed'");
$total_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Suscriptores del Newsletter', 'reforestamos-comunicacion'); ?></h1>
    <a href="#" class="page-title-action" id="add-subscriber-btn"><?php _e('Añadir Nuevo', 'reforestamos-comunicacion'); ?></a>
    <hr class="wp-header-end">
    
    <?php
    // Show messages
    if (isset($_GET['message'])) {
        switch ($_GET['message']) {
            case 'deleted':
                echo '<div class="notice notice-success is-dismissible"><p>';
                _e('Suscriptor(es) eliminado(s) exitosamente.', 'reforestamos-comunicacion');
                echo '</p></div>';
                break;
            case 'activated':
                echo '<div class="notice notice-success is-dismissible"><p>';
                _e('Suscriptor(es) activado(s) exitosamente.', 'reforestamos-comunicacion');
                echo '</p></div>';
                break;
            case 'deactivated':
                echo '<div class="notice notice-success is-dismissible"><p>';
                _e('Suscriptor(es) desactivado(s) exitosamente.', 'reforestamos-comunicacion');
                echo '</p></div>';
                break;
            case 'added':
                echo '<div class="notice notice-success is-dismissible"><p>';
                _e('Suscriptor añadido exitosamente.', 'reforestamos-comunicacion');
                echo '</p></div>';
                break;
        }
    }
    ?>
    
    <!-- Status filter tabs -->
    <ul class="subsubsub">
        <li>
            <a href="<?php echo add_query_arg(['page' => 'newsletter-subscribers', 'status' => 'all'], admin_url('edit.php?post_type=boletin')); ?>" 
               class="<?php echo $status_filter === 'all' ? 'current' : ''; ?>">
                <?php _e('Todos', 'reforestamos-comunicacion'); ?> 
                <span class="count">(<?php echo esc_html($total_count); ?>)</span>
            </a> |
        </li>
        <li>
            <a href="<?php echo add_query_arg(['page' => 'newsletter-subscribers', 'status' => 'active'], admin_url('edit.php?post_type=boletin')); ?>" 
               class="<?php echo $status_filter === 'active' ? 'current' : ''; ?>">
                <?php _e('Activos', 'reforestamos-comunicacion'); ?> 
                <span class="count">(<?php echo esc_html($active_count); ?>)</span>
            </a> |
        </li>
        <li>
            <a href="<?php echo add_query_arg(['page' => 'newsletter-subscribers', 'status' => 'unsubscribed'], admin_url('edit.php?post_type=boletin')); ?>" 
               class="<?php echo $status_filter === 'unsubscribed' ? 'current' : ''; ?>">
                <?php _e('Dados de baja', 'reforestamos-comunicacion'); ?> 
                <span class="count">(<?php echo esc_html($unsubscribed_count); ?>)</span>
            </a>
        </li>
    </ul>
    
    <!-- Search form -->
    <form method="get" class="search-form" style="float: right; margin-top: 10px;">
        <input type="hidden" name="post_type" value="boletin">
        <input type="hidden" name="page" value="newsletter-subscribers">
        <?php if ($status_filter !== 'all'): ?>
            <input type="hidden" name="status" value="<?php echo esc_attr($status_filter); ?>">
        <?php endif; ?>
        <p class="search-box">
            <label class="screen-reader-text" for="subscriber-search-input"><?php _e('Buscar suscriptores:', 'reforestamos-comunicacion'); ?></label>
            <input type="search" id="subscriber-search-input" name="s" value="<?php echo esc_attr($search); ?>">
            <input type="submit" id="search-submit" class="button" value="<?php esc_attr_e('Buscar', 'reforestamos-comunicacion'); ?>">
        </p>
    </form>
    
    <form method="post" id="subscribers-form">
        <?php wp_nonce_field('bulk-subscribers'); ?>
        
        <!-- Bulk actions -->
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-top" class="screen-reader-text"><?php _e('Seleccionar acción masiva', 'reforestamos-comunicacion'); ?></label>
                <select name="action" id="bulk-action-selector-top">
                    <option value="-1"><?php _e('Acciones masivas', 'reforestamos-comunicacion'); ?></option>
                    <option value="activate"><?php _e('Activar', 'reforestamos-comunicacion'); ?></option>
                    <option value="deactivate"><?php _e('Desactivar', 'reforestamos-comunicacion'); ?></option>
                    <option value="delete"><?php _e('Eliminar', 'reforestamos-comunicacion'); ?></option>
                </select>
                <input type="submit" class="button action" value="<?php esc_attr_e('Aplicar', 'reforestamos-comunicacion'); ?>">
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="tablenav-pages">
                <span class="displaying-num">
                    <?php printf(_n('%s elemento', '%s elementos', $total_items, 'reforestamos-comunicacion'), number_format_i18n($total_items)); ?>
                </span>
                <?php
                echo paginate_links([
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'total' => $total_pages,
                    'current' => $paged
                ]);
                ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Subscribers table -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column">
                        <input type="checkbox" id="cb-select-all-1">
                    </td>
                    <th><?php _e('Email', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Nombre', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Estado', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Fecha de Suscripción', 'reforestamos-comunicacion'); ?></th>
                    <th><?php _e('Acciones', 'reforestamos-comunicacion'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($subscribers)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            <?php _e('No se encontraron suscriptores.', 'reforestamos-comunicacion'); ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subscribers as $subscriber): ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input type="checkbox" name="subscribers[]" value="<?php echo esc_attr($subscriber->id); ?>">
                            </th>
                            <td>
                                <strong><?php echo esc_html($subscriber->email); ?></strong>
                            </td>
                            <td><?php echo esc_html($subscriber->name ? $subscriber->name : '-'); ?></td>
                            <td>
                                <?php if ($subscriber->status === 'active'): ?>
                                    <span class="dashicons dashicons-yes-alt" style="color: green;"></span>
                                    <?php _e('Activo', 'reforestamos-comunicacion'); ?>
                                <?php else: ?>
                                    <span class="dashicons dashicons-dismiss" style="color: red;"></span>
                                    <?php _e('Inactivo', 'reforestamos-comunicacion'); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($subscriber->subscribed_at); ?></td>
                            <td>
                                <?php
                                $action_url = wp_nonce_url(
                                    add_query_arg([
                                        'page' => 'newsletter-subscribers',
                                        'action' => $subscriber->status === 'active' ? 'deactivate' : 'activate',
                                        'subscriber' => $subscriber->id
                                    ], admin_url('edit.php?post_type=boletin')),
                                    'subscriber-action'
                                );
                                ?>
                                <a href="<?php echo esc_url($action_url); ?>">
                                    <?php echo $subscriber->status === 'active' ? __('Desactivar', 'reforestamos-comunicacion') : __('Activar', 'reforestamos-comunicacion'); ?>
                                </a> |
                                
                                <?php
                                $delete_url = wp_nonce_url(
                                    add_query_arg([
                                        'page' => 'newsletter-subscribers',
                                        'action' => 'delete',
                                        'subscriber' => $subscriber->id
                                    ], admin_url('edit.php?post_type=boletin')),
                                    'subscriber-action'
                                );
                                ?>
                                <a href="<?php echo esc_url($delete_url); ?>" 
                                   onclick="return confirm('<?php esc_attr_e('¿Estás seguro de eliminar este suscriptor?', 'reforestamos-comunicacion'); ?>');"
                                   style="color: #a00;">
                                    <?php _e('Eliminar', 'reforestamos-comunicacion'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>

<!-- Add subscriber modal -->
<div id="add-subscriber-modal" style="display: none;">
    <div style="padding: 20px;">
        <h2><?php _e('Añadir Nuevo Suscriptor', 'reforestamos-comunicacion'); ?></h2>
        <form id="add-subscriber-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
            <input type="hidden" name="action" value="add_subscriber">
            <?php wp_nonce_field('add_subscriber', 'add_subscriber_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="subscriber-email"><?php _e('Email', 'reforestamos-comunicacion'); ?> *</label></th>
                    <td>
                        <input type="email" id="subscriber-email" name="email" class="regular-text" required>
                    </td>
                </tr>
                <tr>
                    <th><label for="subscriber-name"><?php _e('Nombre', 'reforestamos-comunicacion'); ?></label></th>
                    <td>
                        <input type="text" id="subscriber-name" name="name" class="regular-text">
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" class="button button-primary"><?php _e('Añadir Suscriptor', 'reforestamos-comunicacion'); ?></button>
                <button type="button" class="button" id="cancel-add-subscriber"><?php _e('Cancelar', 'reforestamos-comunicacion'); ?></button>
            </p>
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Select all checkbox
    $('#cb-select-all-1').on('click', function() {
        $('input[name="subscribers[]"]').prop('checked', this.checked);
    });
    
    // Add subscriber modal (simple implementation)
    $('#add-subscriber-btn').on('click', function(e) {
        e.preventDefault();
        var modal = $('#add-subscriber-modal');
        
        // Simple modal implementation
        if (typeof tb_show === 'function') {
            tb_show('<?php esc_js(_e('Añadir Suscriptor', 'reforestamos-comunicacion')); ?>', '#TB_inline?inlineId=add-subscriber-modal&width=500&height=300');
        } else {
            alert('<?php esc_js(_e('Por favor, añade suscriptores manualmente desde la base de datos o usa el formulario de suscripción del frontend.', 'reforestamos-comunicacion')); ?>');
        }
    });
    
    $('#cancel-add-subscriber').on('click', function() {
        if (typeof tb_remove === 'function') {
            tb_remove();
        }
    });
    
    // Handle add subscriber form submission
    $('#add-subscriber-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?php echo add_query_arg(['page' => 'newsletter-subscribers', 'message' => 'added'], admin_url('edit.php?post_type=boletin')); ?>';
                } else {
                    alert(response.data.message);
                }
            }
        });
    });
});
</script>
