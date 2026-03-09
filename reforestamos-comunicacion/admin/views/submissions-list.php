<?php
/**
 * Contact Form Submissions List Admin Page
 *
 * @package Reforestamos_Comunicacion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Handle single submission actions
if ( isset( $_GET['action'] ) && isset( $_GET['submission'] ) ) {
	check_admin_referer( 'submission-action' );

	$action        = sanitize_text_field( $_GET['action'] );
	$submission_id = intval( $_GET['submission'] );

	global $wpdb;
	$table_name = $wpdb->prefix . 'reforestamos_submissions';

	switch ( $action ) {
		case 'mark_read':
			$wpdb->update(
				$table_name,
				array( 'status' => 'read' ),
				array( 'id' => $submission_id ),
				array( '%s' ),
				array( '%d' )
			);
			$message = 'marked_read';
			break;
		case 'archive':
			$wpdb->update(
				$table_name,
				array( 'status' => 'archived' ),
				array( 'id' => $submission_id ),
				array( '%s' ),
				array( '%d' )
			);
			$message = 'archived';
			break;
		case 'delete':
			$wpdb->delete( $table_name, array( 'id' => $submission_id ), array( '%d' ) );
			$message = 'deleted';
			break;
	}

	wp_redirect(
		add_query_arg(
			array(
				'page'    => 'contact-submissions',
				'message' => $message,
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}

// Get pagination parameters
$paged    = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
$per_page = 20;
$offset   = ( $paged - 1 ) * $per_page;

// Get filter parameters
$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';
$search        = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

// Build query
global $wpdb;
$table_name = $wpdb->prefix . 'reforestamos_submissions';

$where        = array();
$where_values = array();

if ( 'all' !== $status_filter ) {
	$where[]        = 'status = %s';
	$where_values[] = $status_filter;
}

if ( ! empty( $search ) ) {
	$where[]        = '(name LIKE %s OR email LIKE %s OR subject LIKE %s)';
	$where_values[] = '%' . $wpdb->esc_like( $search ) . '%';
	$where_values[] = '%' . $wpdb->esc_like( $search ) . '%';
	$where_values[] = '%' . $wpdb->esc_like( $search ) . '%';
}

$where_clause = ! empty( $where ) ? 'WHERE ' . implode( ' AND ', $where ) : '';

// Get total count
if ( ! empty( $where_values ) ) {
	$total_items = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name $where_clause",
			$where_values
		)
	);
} else {
	$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name $where_clause" );
}

// Get submissions
$query        = "SELECT * FROM $table_name $where_clause ORDER BY submitted_at DESC LIMIT %d OFFSET %d";
$query_values = array_merge( $where_values, array( $per_page, $offset ) );

if ( ! empty( $where_values ) ) {
	$submissions = $wpdb->get_results( $wpdb->prepare( $query, $query_values ) );
} else {
	$submissions = $wpdb->get_results( $wpdb->prepare( $query, $per_page, $offset ) );
}

// Calculate pagination
$total_pages = ceil( $total_items / $per_page );

// Get status counts
$new_count      = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'new'" );
$read_count     = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'read'" );
$archived_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'archived'" );
$total_count    = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Formularios de Contacto', 'reforestamos-comunicacion' ); ?></h1>
	<hr class="wp-header-end">

	<?php
	// Show messages
	if ( isset( $_GET['message'] ) ) {
		switch ( $_GET['message'] ) {
			case 'marked_read':
				echo '<div class="notice notice-success is-dismissible"><p>';
				esc_html_e( 'Mensaje marcado como leído.', 'reforestamos-comunicacion' );
				echo '</p></div>';
				break;
			case 'archived':
				echo '<div class="notice notice-success is-dismissible"><p>';
				esc_html_e( 'Mensaje archivado exitosamente.', 'reforestamos-comunicacion' );
				echo '</p></div>';
				break;
			case 'deleted':
				echo '<div class="notice notice-success is-dismissible"><p>';
				esc_html_e( 'Mensaje eliminado exitosamente.', 'reforestamos-comunicacion' );
				echo '</p></div>';
				break;
		}
	}
	?>

	<!-- Status filter tabs -->
	<ul class="subsubsub">
		<li>
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'contact-submissions', 'status' => 'all' ), admin_url( 'admin.php' ) ) ); ?>"
			   class="<?php echo 'all' === $status_filter ? 'current' : ''; ?>">
				<?php esc_html_e( 'Todos', 'reforestamos-comunicacion' ); ?>
				<span class="count">(<?php echo esc_html( $total_count ); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'contact-submissions', 'status' => 'new' ), admin_url( 'admin.php' ) ) ); ?>"
			   class="<?php echo 'new' === $status_filter ? 'current' : ''; ?>">
				<?php esc_html_e( 'Nuevos', 'reforestamos-comunicacion' ); ?>
				<span class="count">(<?php echo esc_html( $new_count ); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'contact-submissions', 'status' => 'read' ), admin_url( 'admin.php' ) ) ); ?>"
			   class="<?php echo 'read' === $status_filter ? 'current' : ''; ?>">
				<?php esc_html_e( 'Leídos', 'reforestamos-comunicacion' ); ?>
				<span class="count">(<?php echo esc_html( $read_count ); ?>)</span>
			</a> |
		</li>
		<li>
			<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'contact-submissions', 'status' => 'archived' ), admin_url( 'admin.php' ) ) ); ?>"
			   class="<?php echo 'archived' === $status_filter ? 'current' : ''; ?>">
				<?php esc_html_e( 'Archivados', 'reforestamos-comunicacion' ); ?>
				<span class="count">(<?php echo esc_html( $archived_count ); ?>)</span>
			</a>
		</li>
	</ul>

	<!-- Search form -->
	<form method="get" class="search-form" style="float: right; margin-top: 10px;">
		<input type="hidden" name="page" value="contact-submissions">
		<?php if ( 'all' !== $status_filter ) : ?>
			<input type="hidden" name="status" value="<?php echo esc_attr( $status_filter ); ?>">
		<?php endif; ?>
		<p class="search-box">
			<label class="screen-reader-text" for="submission-search-input"><?php esc_html_e( 'Buscar mensajes:', 'reforestamos-comunicacion' ); ?></label>
			<input type="search" id="submission-search-input" name="s" value="<?php echo esc_attr( $search ); ?>">
			<input type="submit" id="search-submit" class="button" value="<?php esc_attr_e( 'Buscar', 'reforestamos-comunicacion' ); ?>">
		</p>
	</form>

	<form method="post" id="submissions-form">
		<!-- Pagination -->
		<?php if ( $total_pages > 1 ) : ?>
		<div class="tablenav top">
			<div class="tablenav-pages">
				<span class="displaying-num">
					<?php
					/* translators: %s: Number of items */
					printf( esc_html( _n( '%s elemento', '%s elementos', $total_items, 'reforestamos-comunicacion' ) ), esc_html( number_format_i18n( $total_items ) ) );
					?>
				</span>
				<?php
				echo wp_kses_post(
					paginate_links(
						array(
							'base'      => add_query_arg( 'paged', '%#%' ),
							'format'    => '',
							'prev_text' => '&laquo;',
							'next_text' => '&raquo;',
							'total'     => $total_pages,
							'current'   => $paged,
						)
					)
				);
				?>
			</div>
		</div>
		<?php endif; ?>

		<!-- Submissions table -->
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th style="width: 50px;"><?php esc_html_e( 'ID', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Nombre', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Email', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Asunto', 'reforestamos-comunicacion' ); ?></th>
					<th style="width: 120px;"><?php esc_html_e( 'Fecha', 'reforestamos-comunicacion' ); ?></th>
					<th style="width: 100px;"><?php esc_html_e( 'Estado', 'reforestamos-comunicacion' ); ?></th>
					<th style="width: 180px;"><?php esc_html_e( 'Acciones', 'reforestamos-comunicacion' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $submissions ) ) : ?>
					<tr>
						<td colspan="7" style="text-align: center; padding: 20px;">
							<?php esc_html_e( 'No se encontraron mensajes.', 'reforestamos-comunicacion' ); ?>
						</td>
					</tr>
				<?php else : ?>
					<?php foreach ( $submissions as $submission ) : ?>
						<tr>
							<td><?php echo esc_html( $submission->id ); ?></td>
							<td>
								<strong><?php echo esc_html( $submission->name ); ?></strong>
							</td>
							<td><?php echo esc_html( $submission->email ); ?></td>
							<td><?php echo esc_html( $submission->subject ); ?></td>
							<td><?php echo esc_html( mysql2date( 'Y-m-d H:i', $submission->submitted_at ) ); ?></td>
							<td>
								<?php
								$status_class = '';
								$status_text  = '';
								switch ( $submission->status ) {
									case 'new':
										$status_class = 'submission-status-new';
										$status_text  = __( 'Nuevo', 'reforestamos-comunicacion' );
										break;
									case 'read':
										$status_class = 'submission-status-read';
										$status_text  = __( 'Leído', 'reforestamos-comunicacion' );
										break;
									case 'archived':
										$status_class = 'submission-status-archived';
										$status_text  = __( 'Archivado', 'reforestamos-comunicacion' );
										break;
								}
								?>
								<span class="submission-status <?php echo esc_attr( $status_class ); ?>">
									<?php echo esc_html( $status_text ); ?>
								</span>
							</td>
							<td>
								<a href="#" class="view-submission" data-id="<?php echo esc_attr( $submission->id ); ?>">
									<?php esc_html_e( 'Ver', 'reforestamos-comunicacion' ); ?>
								</a>
								<?php if ( 'new' === $submission->status ) : ?>
									|
									<?php
									$mark_read_url = wp_nonce_url(
										add_query_arg(
											array(
												'page'       => 'contact-submissions',
												'action'     => 'mark_read',
												'submission' => $submission->id,
											),
											admin_url( 'admin.php' )
										),
										'submission-action'
									);
									?>
									<a href="<?php echo esc_url( $mark_read_url ); ?>">
										<?php esc_html_e( 'Marcar leído', 'reforestamos-comunicacion' ); ?>
									</a>
								<?php endif; ?>
								<?php if ( 'archived' !== $submission->status ) : ?>
									|
									<?php
									$archive_url = wp_nonce_url(
										add_query_arg(
											array(
												'page'       => 'contact-submissions',
												'action'     => 'archive',
												'submission' => $submission->id,
											),
											admin_url( 'admin.php' )
										),
										'submission-action'
									);
									?>
									<a href="<?php echo esc_url( $archive_url ); ?>">
										<?php esc_html_e( 'Archivar', 'reforestamos-comunicacion' ); ?>
									</a>
								<?php endif; ?>
								|
								<?php
								$delete_url = wp_nonce_url(
									add_query_arg(
										array(
											'page'       => 'contact-submissions',
											'action'     => 'delete',
											'submission' => $submission->id,
										),
										admin_url( 'admin.php' )
									),
									'submission-action'
								);
								?>
								<a href="<?php echo esc_url( $delete_url ); ?>"
								   onclick="return confirm('<?php esc_attr_e( '¿Estás seguro de eliminar este mensaje?', 'reforestamos-comunicacion' ); ?>');"
								   style="color: #a00;">
									<?php esc_html_e( 'Eliminar', 'reforestamos-comunicacion' ); ?>
								</a>
							</td>
						</tr>
						<!-- Hidden row for message content -->
						<tr id="submission-details-<?php echo esc_attr( $submission->id ); ?>" class="submission-details" style="display: none;">
							<td colspan="7">
								<div class="submission-message-box">
									<h3><?php esc_html_e( 'Mensaje completo:', 'reforestamos-comunicacion' ); ?></h3>
									<p><?php echo nl2br( esc_html( $submission->message ) ); ?></p>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</form>
</div>

<script>
jQuery(document).ready(function($) {
	// Toggle message details
	$('.view-submission').on('click', function(e) {
		e.preventDefault();
		var submissionId = $(this).data('id');
		var detailsRow = $('#submission-details-' + submissionId);
		
		// Hide all other details
		$('.submission-details').not(detailsRow).slideUp();
		
		// Toggle this one
		detailsRow.slideToggle();
	});
});
</script>
