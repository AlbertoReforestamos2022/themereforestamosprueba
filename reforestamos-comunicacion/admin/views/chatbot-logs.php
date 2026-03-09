<?php
/**
 * ChatBot Logs Admin View
 *
 * Displays conversation logs for analysis
 * Requirement 10.6: Interface to view conversation logs
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'reforestamos_chatbot_logs';

// Pagination settings
$per_page     = 50;
$current_page = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
$offset       = ( $current_page - 1 ) * $per_page;

// Filter settings
$session_filter = isset( $_GET['session_id'] ) ? sanitize_text_field( $_GET['session_id'] ) : '';
$date_from      = isset( $_GET['date_from'] ) ? sanitize_text_field( $_GET['date_from'] ) : '';
$date_to        = isset( $_GET['date_to'] ) ? sanitize_text_field( $_GET['date_to'] ) : '';
$search_query   = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

// Build WHERE clause
$where_clauses = array( '1=1' );
$where_values  = array();

if ( ! empty( $session_filter ) ) {
	$where_clauses[] = 'session_id = %s';
	$where_values[]  = $session_filter;
}

if ( ! empty( $date_from ) ) {
	$where_clauses[] = 'DATE(created_at) >= %s';
	$where_values[]  = $date_from;
}

if ( ! empty( $date_to ) ) {
	$where_clauses[] = 'DATE(created_at) <= %s';
	$where_values[]  = $date_to;
}

if ( ! empty( $search_query ) ) {
	$where_clauses[] = '(user_message LIKE %s OR bot_response LIKE %s)';
	$where_values[]  = '%' . $wpdb->esc_like( $search_query ) . '%';
	$where_values[]  = '%' . $wpdb->esc_like( $search_query ) . '%';
}

$where_sql = implode( ' AND ', $where_clauses );

// Get total count
if ( ! empty( $where_values ) ) {
	$total_items = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE $where_sql", $where_values ) );
} else {
	$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE $where_sql" );
}

$total_pages = ceil( $total_items / $per_page );

// Get logs
if ( ! empty( $where_values ) ) {
	$logs = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM $table_name WHERE $where_sql ORDER BY created_at DESC LIMIT %d OFFSET %d",
			array_merge( $where_values, array( $per_page, $offset ) )
		)
	);
} else {
	$logs = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM $table_name WHERE $where_sql ORDER BY created_at DESC LIMIT %d OFFSET %d",
			$per_page,
			$offset
		)
	);
}

// Get statistics
$stats = array();

// Total conversations
$stats['total_conversations'] = $wpdb->get_var( "SELECT COUNT(DISTINCT session_id) FROM $table_name" );

// Total messages
$stats['total_messages'] = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

// Messages today
$stats['messages_today'] = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE DATE(created_at) = CURDATE()" );

// Average messages per session
if ( $stats['total_conversations'] > 0 ) {
	$stats['avg_messages_per_session'] = round( $stats['total_messages'] / $stats['total_conversations'], 1 );
} else {
	$stats['avg_messages_per_session'] = 0;
}

// Get unique sessions for filter dropdown
$sessions = $wpdb->get_results(
	"SELECT DISTINCT session_id, MIN(created_at) as first_message, COUNT(*) as message_count 
	FROM $table_name 
	GROUP BY session_id 
	ORDER BY first_message DESC 
	LIMIT 100"
);

?>

<div class="wrap">
	<h1><?php esc_html_e( 'Logs de Conversaciones del ChatBot', 'reforestamos-comunicacion' ); ?></h1>
	<p><?php esc_html_e( 'Visualiza y analiza todas las conversaciones del chatbot.', 'reforestamos-comunicacion' ); ?></p>

	<!-- Statistics Cards -->
	<div class="chatbot-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0;">
		<div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #2271b1; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px 0; font-size: 14px; color: #666;"><?php esc_html_e( 'Total Conversaciones', 'reforestamos-comunicacion' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #2271b1;"><?php echo esc_html( number_format_i18n( $stats['total_conversations'] ) ); ?></p>
		</div>
		
		<div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #00a32a; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px 0; font-size: 14px; color: #666;"><?php esc_html_e( 'Total Mensajes', 'reforestamos-comunicacion' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #00a32a;"><?php echo esc_html( number_format_i18n( $stats['total_messages'] ) ); ?></p>
		</div>
		
		<div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #d63638; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px 0; font-size: 14px; color: #666;"><?php esc_html_e( 'Mensajes Hoy', 'reforestamos-comunicacion' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #d63638;"><?php echo esc_html( number_format_i18n( $stats['messages_today'] ) ); ?></p>
		</div>
		
		<div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #dba617; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px 0; font-size: 14px; color: #666;"><?php esc_html_e( 'Promedio Mensajes/Sesión', 'reforestamos-comunicacion' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #dba617;"><?php echo esc_html( $stats['avg_messages_per_session'] ); ?></p>
		</div>
	</div>

	<!-- Filters -->
	<div class="tablenav top">
		<form method="get" action="">
			<input type="hidden" name="page" value="reforestamos-chatbot-logs" />
			
			<div class="alignleft actions" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
				<!-- Search -->
				<input 
					type="search" 
					name="s" 
					value="<?php echo esc_attr( $search_query ); ?>" 
					placeholder="<?php esc_attr_e( 'Buscar en mensajes...', 'reforestamos-comunicacion' ); ?>"
					style="width: 250px;"
				/>
				
				<!-- Session Filter -->
				<select name="session_id" style="width: 200px;">
					<option value=""><?php esc_html_e( 'Todas las sesiones', 'reforestamos-comunicacion' ); ?></option>
					<?php foreach ( $sessions as $session ) : ?>
						<option value="<?php echo esc_attr( $session->session_id ); ?>" <?php selected( $session_filter, $session->session_id ); ?>>
							<?php
							echo esc_html(
								sprintf(
									/* translators: 1: session ID (first 8 chars), 2: message count, 3: date */
									__( 'Sesión %1$s (%2$d mensajes) - %3$s', 'reforestamos-comunicacion' ),
									substr( $session->session_id, 0, 8 ),
									$session->message_count,
									mysql2date( get_option( 'date_format' ), $session->first_message )
								)
							);
							?>
						</option>
					<?php endforeach; ?>
				</select>
				
				<!-- Date From -->
				<input 
					type="date" 
					name="date_from" 
					value="<?php echo esc_attr( $date_from ); ?>" 
					placeholder="<?php esc_attr_e( 'Desde', 'reforestamos-comunicacion' ); ?>"
				/>
				
				<!-- Date To -->
				<input 
					type="date" 
					name="date_to" 
					value="<?php echo esc_attr( $date_to ); ?>" 
					placeholder="<?php esc_attr_e( 'Hasta', 'reforestamos-comunicacion' ); ?>"
				/>
				
				<button type="submit" class="button"><?php esc_html_e( 'Filtrar', 'reforestamos-comunicacion' ); ?></button>
				
				<?php if ( ! empty( $session_filter ) || ! empty( $date_from ) || ! empty( $date_to ) || ! empty( $search_query ) ) : ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=reforestamos-chatbot-logs' ) ); ?>" class="button">
						<?php esc_html_e( 'Limpiar filtros', 'reforestamos-comunicacion' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</form>
	</div>

	<!-- Logs Table -->
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th style="width: 15%;"><?php esc_html_e( 'Fecha/Hora', 'reforestamos-comunicacion' ); ?></th>
				<th style="width: 15%;"><?php esc_html_e( 'Sesión', 'reforestamos-comunicacion' ); ?></th>
				<th style="width: 35%;"><?php esc_html_e( 'Mensaje del Usuario', 'reforestamos-comunicacion' ); ?></th>
				<th style="width: 35%;"><?php esc_html_e( 'Respuesta del Bot', 'reforestamos-comunicacion' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! empty( $logs ) ) : ?>
				<?php foreach ( $logs as $log ) : ?>
					<tr>
						<td>
							<strong><?php echo esc_html( mysql2date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $log->created_at ) ); ?></strong>
						</td>
						<td>
							<code style="font-size: 11px;"><?php echo esc_html( substr( $log->session_id, 0, 12 ) ); ?>...</code>
							<br>
							<a href="<?php echo esc_url( add_query_arg( 'session_id', $log->session_id ) ); ?>" class="button button-small" style="margin-top: 5px;">
								<?php esc_html_e( 'Ver conversación', 'reforestamos-comunicacion' ); ?>
							</a>
						</td>
						<td>
							<div style="background: #f0f0f1; padding: 10px; border-radius: 4px; border-left: 3px solid #2271b1;">
								<?php echo esc_html( $log->user_message ); ?>
							</div>
						</td>
						<td>
							<div style="background: #f6f7f7; padding: 10px; border-radius: 4px; border-left: 3px solid #00a32a;">
								<?php echo esc_html( $log->bot_response ); ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="4" style="text-align: center; padding: 40px;">
						<p style="color: #666; font-size: 16px;">
							<?php esc_html_e( 'No se encontraron conversaciones.', 'reforestamos-comunicacion' ); ?>
						</p>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<!-- Pagination -->
	<?php if ( $total_pages > 1 ) : ?>
		<div class="tablenav bottom">
			<div class="tablenav-pages">
				<span class="displaying-num">
					<?php
					echo esc_html(
						sprintf(
							/* translators: %s: number of items */
							_n( '%s elemento', '%s elementos', $total_items, 'reforestamos-comunicacion' ),
							number_format_i18n( $total_items )
						)
					);
					?>
				</span>
				<?php
				$page_links = paginate_links(
					array(
						'base'      => add_query_arg( 'paged', '%#%' ),
						'format'    => '',
						'prev_text' => '&laquo;',
						'next_text' => '&raquo;',
						'total'     => $total_pages,
						'current'   => $current_page,
					)
				);

				if ( $page_links ) {
					echo '<span class="pagination-links">' . $page_links . '</span>';
				}
				?>
			</div>
		</div>
	<?php endif; ?>

	<!-- Export Section -->
	<div style="margin-top: 30px; padding: 20px; background: #fff; border: 1px solid #ccd0d4;">
		<h2><?php esc_html_e( 'Exportar Datos', 'reforestamos-comunicacion' ); ?></h2>
		<p><?php esc_html_e( 'Exporta los logs de conversaciones a formato CSV para análisis externo.', 'reforestamos-comunicacion' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="export_chatbot_logs" />
			<?php wp_nonce_field( 'export_chatbot_logs', 'export_nonce' ); ?>
			
			<p>
				<label>
					<input type="checkbox" name="export_all" value="1" checked />
					<?php esc_html_e( 'Exportar todos los logs (sin filtros)', 'reforestamos-comunicacion' ); ?>
				</label>
			</p>
			
			<button type="submit" class="button button-primary">
				<span class="dashicons dashicons-download" style="vertical-align: middle;"></span>
				<?php esc_html_e( 'Exportar a CSV', 'reforestamos-comunicacion' ); ?>
			</button>
		</form>
	</div>
</div>

<style>
.chatbot-stats .stat-card h3 {
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.wp-list-table td {
	vertical-align: top;
}

.pagination-links {
	display: inline-block;
}

.pagination-links a,
.pagination-links span {
	padding: 5px 10px;
	margin: 0 2px;
	border: 1px solid #ddd;
	background: #fff;
	text-decoration: none;
}

.pagination-links .current {
	background: #2271b1;
	color: #fff;
	border-color: #2271b1;
}

.pagination-links a:hover {
	background: #f0f0f1;
}
</style>
