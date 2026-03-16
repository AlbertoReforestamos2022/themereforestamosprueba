<?php
/**
 * Error Logs Admin View
 *
 * Displays migration error logs with filtering and export options.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get error logger
require_once plugin_dir_path( dirname( __DIR__ ) ) . 'includes/class-error-logger.php';
$error_logger = new Reforestamos_Error_Logger();

// Handle actions
if ( isset( $_POST['action'] ) ) {
	check_admin_referer( 'reforestamos_migration_errors' );

	if ( 'clear_errors' === $_POST['action'] ) {
		$error_logger->clear_errors();
		echo '<div class="notice notice-success"><p>Todos los errores han sido eliminados.</p></div>';
	} elseif ( 'export_csv' === $_POST['action'] ) {
		$export_file = WP_CONTENT_DIR . '/reforestamos-migration-reports/errors-' . gmdate( 'Y-m-d-H-i-s' ) . '.csv';
		if ( $error_logger->export_to_csv( $export_file ) ) {
			echo '<div class="notice notice-success"><p>Errores exportados a: ' . esc_html( $export_file ) . '</p></div>';
		} else {
			echo '<div class="notice notice-error"><p>Error al exportar errores.</p></div>';
		}
	}
}

// Get filter parameters
$severity_filter = isset( $_GET['severity'] ) ? sanitize_text_field( wp_unslash( $_GET['severity'] ) ) : null;
$page_num        = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
$errors_per_page = 50;
$offset          = ( $page_num - 1 ) * $errors_per_page;

// Get errors.
$log_errors = $error_logger->get_errors_from_db(
	[
		'severity' => $severity_filter,
		'limit'    => $errors_per_page,
		'offset'   => $offset,
	]
);

// Get error counts
$error_counts = $error_logger->get_error_counts();
$total_errors = array_sum( $error_counts );

?>

<div class="wrap">
	<h1>Logs de Errores de Migración</h1>
	
	<!-- Summary Cards -->
	<div class="reforestamos-error-summary" style="display: flex; gap: 20px; margin: 20px 0;">
		<div class="error-card" style="flex: 1; padding: 20px; background: #fff; border-left: 4px solid #d63638;">
			<h3 style="margin: 0 0 10px 0;">Críticos</h3>
			<p style="font-size: 32px; margin: 0; font-weight: bold;"><?php echo esc_html( $error_counts['critical'] ); ?></p>
		</div>
		<div class="error-card" style="flex: 1; padding: 20px; background: #fff; border-left: 4px solid #dba617;">
			<h3 style="margin: 0 0 10px 0;">Errores</h3>
			<p style="font-size: 32px; margin: 0; font-weight: bold;"><?php echo esc_html( $error_counts['error'] ); ?></p>
		</div>
		<div class="error-card" style="flex: 1; padding: 20px; background: #fff; border-left: 4px solid #f0b849;">
			<h3 style="margin: 0 0 10px 0;">Advertencias</h3>
			<p style="font-size: 32px; margin: 0; font-weight: bold;"><?php echo esc_html( $error_counts['warning'] ); ?></p>
		</div>
		<div class="error-card" style="flex: 1; padding: 20px; background: #fff; border-left: 4px solid #00a0d2;">
			<h3 style="margin: 0 0 10px 0;">Info</h3>
			<p style="font-size: 32px; margin: 0; font-weight: bold;"><?php echo esc_html( $error_counts['info'] ); ?></p>
		</div>
	</div>
	
	<!-- Filters and Actions -->
	<div class="tablenav top">
		<div class="alignleft actions">
			<form method="get" style="display: inline-block;">
				<input type="hidden" name="page" value="reforestamos-migration-errors" />
				<select name="severity" id="severity-filter">
					<option value="">Todas las severidades</option>
					<option value="critical" <?php selected( $severity_filter, 'critical' ); ?>>Críticos</option>
					<option value="error" <?php selected( $severity_filter, 'error' ); ?>>Errores</option>
					<option value="warning" <?php selected( $severity_filter, 'warning' ); ?>>Advertencias</option>
					<option value="info" <?php selected( $severity_filter, 'info' ); ?>>Info</option>
				</select>
				<input type="submit" class="button" value="Filtrar" />
			</form>
		</div>
		
		<div class="alignright actions">
			<form method="post" style="display: inline-block;">
				<?php wp_nonce_field( 'reforestamos_migration_errors' ); ?>
				<input type="hidden" name="action" value="export_csv" />
				<input type="submit" class="button" value="Exportar CSV" />
			</form>
			
			<form method="post" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar todos los errores?');">
				<?php wp_nonce_field( 'reforestamos_migration_errors' ); ?>
				<input type="hidden" name="action" value="clear_errors" />
				<input type="submit" class="button button-secondary" value="Limpiar Errores" />
			</form>
		</div>
	</div>
	
	<!-- Errors Table -->
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th style="width: 50px;">ID</th>
				<th style="width: 150px;">Fecha/Hora</th>
				<th style="width: 100px;">Severidad</th>
				<th>Mensaje</th>
				<th style="width: 200px;">Contexto</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $log_errors ) ) : ?>
				<tr>
					<td colspan="5" style="text-align: center; padding: 40px;">
						No se encontraron errores.
					</td>
				</tr>
			<?php else : ?>
				<?php foreach ( $log_errors as $log_entry ) : ?>
					<?php
					$severity_colors = [
						'critical' => '#d63638',
						'error'    => '#dba617',
						'warning'  => '#f0b849',
						'info'     => '#00a0d2',
					];
					$color           = $severity_colors[ $log_entry['severity'] ] ?? '#000';
					?>
					<tr>
						<td><?php echo esc_html( $log_entry['id'] ); ?></td>
						<td><?php echo esc_html( $log_entry['timestamp'] ); ?></td>
						<td>
							<span style="display: inline-block; padding: 4px 8px; background: <?php echo esc_attr( $color ); ?>; color: #fff; border-radius: 3px; font-size: 11px; font-weight: bold; text-transform: uppercase;">
								<?php echo esc_html( $log_entry['severity'] ); ?>
							</span>
						</td>
						<td><?php echo esc_html( $log_entry['message'] ); ?></td>
						<td>
							<?php if ( ! empty( $log_entry['context'] ) ) : ?>
								<details>
									<summary style="cursor: pointer;">Ver contexto</summary>
									<pre style="margin-top: 10px; padding: 10px; background: #f5f5f5; overflow-x: auto; font-size: 11px;"><?php echo esc_html( $log_entry['context'] ); ?></pre>
								</details>
							<?php else : ?>
								<em>Sin contexto</em>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	
	<!-- Pagination -->
	<?php if ( $total_errors > $errors_per_page ) : ?>
		<div class="tablenav bottom">
			<div class="tablenav-pages">
				<?php
				$total_pages = ceil( $total_errors / $errors_per_page );
				echo wp_kses_post(
					paginate_links(
						[
							'base'      => add_query_arg( 'paged', '%#%' ),
							'format'    => '',
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
							'total'     => $total_pages,
							'current'   => $page_num,
						]
					)
				);
				?>
			</div>
		</div>
	<?php endif; ?>
</div>

<style>
.reforestamos-error-summary .error-card {
	box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.reforestamos-error-summary .error-card h3 {
	color: #666;
	font-size: 14px;
	font-weight: 600;
	text-transform: uppercase;
}
</style>
