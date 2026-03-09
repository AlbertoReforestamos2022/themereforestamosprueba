<?php
/**
 * Adoptions Dashboard View
 *
 * @package Reforestamos_Comunicacion
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get adoptions data
global $wpdb;
$table_name = $wpdb->prefix . 'reforestamos_adoptions';

// Get statistics
$total_adoptions = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
$completed       = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'completed'" );
$pending         = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'pending'" );
$total_trees     = $wpdb->get_var( "SELECT SUM(quantity) FROM $table_name WHERE status = 'completed'" );

// Get recent adoptions
$recent_adoptions = $wpdb->get_results(
	"SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 10"
);

// Get monthly statistics
$monthly_stats = $wpdb->get_results(
	"SELECT 
		DATE_FORMAT(created_at, '%Y-%m') as month,
		COUNT(*) as count,
		SUM(quantity) as trees
	FROM $table_name 
	WHERE status = 'completed'
	GROUP BY month 
	ORDER BY month DESC 
	LIMIT 12"
);
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Dashboard de Adopciones', 'reforestamos-comunicacion' ); ?></h1>

	<!-- Statistics Cards -->
	<div class="adoption-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0;">
		<div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #2E7D32; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><?php esc_html_e( 'Total Adopciones', 'reforestamos-comunicacion' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #2E7D32;"><?php echo esc_html( number_format( $total_adoptions ) ); ?></p>
		</div>

		<div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #4CAF50; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><?php esc_html_e( 'Completadas', 'reforestamos-comunicacion' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #4CAF50;"><?php echo esc_html( number_format( $completed ) ); ?></p>
		</div>

		<div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #FF9800; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><?php esc_html_e( 'Pendientes', 'reforestamos-comunicacion' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #FF9800;"><?php echo esc_html( number_format( $pending ) ); ?></p>
		</div>

		<div class="stat-card" style="background: #fff; padding: 20px; border-left: 4px solid #1B5E20; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><?php esc_html_e( 'Árboles Adoptados', 'reforestamos-comunicacion' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #1B5E20;"><?php echo esc_html( number_format( $total_trees ) ); ?></p>
		</div>
	</div>

	<!-- Monthly Statistics Chart -->
	<div class="monthly-stats" style="background: #fff; padding: 20px; margin: 20px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
		<h2><?php esc_html_e( 'Estadísticas Mensuales', 'reforestamos-comunicacion' ); ?></h2>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Mes', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Adopciones', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Árboles', 'reforestamos-comunicacion' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( ! empty( $monthly_stats ) ) : ?>
					<?php foreach ( $monthly_stats as $stat ) : ?>
						<tr>
							<td><?php echo esc_html( date_i18n( 'F Y', strtotime( $stat->month . '-01' ) ) ); ?></td>
							<td><?php echo esc_html( number_format( $stat->count ) ); ?></td>
							<td><?php echo esc_html( number_format( $stat->trees ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="3"><?php esc_html_e( 'No hay datos disponibles', 'reforestamos-comunicacion' ); ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<!-- Recent Adoptions -->
	<div class="recent-adoptions" style="background: #fff; padding: 20px; margin: 20px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
		<h2><?php esc_html_e( 'Adopciones Recientes', 'reforestamos-comunicacion' ); ?></h2>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'ID', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Nombre', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Email', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Árboles', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Tipo', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Estado', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Fecha', 'reforestamos-comunicacion' ); ?></th>
					<th><?php esc_html_e( 'Acciones', 'reforestamos-comunicacion' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( ! empty( $recent_adoptions ) ) : ?>
					<?php foreach ( $recent_adoptions as $adoption ) : ?>
						<tr>
							<td><?php echo esc_html( $adoption->id ); ?></td>
							<td><?php echo esc_html( $adoption->name ); ?></td>
							<td><?php echo esc_html( $adoption->email ); ?></td>
							<td><?php echo esc_html( $adoption->quantity ); ?></td>
							<td>
								<?php
								$types = array(
									'one-time' => __( 'Una vez', 'reforestamos-comunicacion' ),
									'monthly'  => __( 'Mensual', 'reforestamos-comunicacion' ),
									'annual'   => __( 'Anual', 'reforestamos-comunicacion' ),
								);
								echo esc_html( $types[ $adoption->donation_type ] ?? $adoption->donation_type );
								?>
							</td>
							<td>
								<?php
								$status_class = 'completed' === $adoption->status ? 'success' : 'warning';
								$status_text  = 'completed' === $adoption->status ? __( 'Completada', 'reforestamos-comunicacion' ) : __( 'Pendiente', 'reforestamos-comunicacion' );
								?>
								<span class="status-badge status-<?php echo esc_attr( $status_class ); ?>" style="padding: 4px 8px; border-radius: 3px; font-size: 12px; background: <?php echo 'completed' === $adoption->status ? '#d4edda' : '#fff3cd'; ?>; color: <?php echo 'completed' === $adoption->status ? '#155724' : '#856404'; ?>;">
									<?php echo esc_html( $status_text ); ?>
								</span>
							</td>
							<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $adoption->created_at ) ) ); ?></td>
							<td>
								<?php if ( $adoption->certificate_generated ) : ?>
									<span style="color: #4CAF50;">✓ <?php esc_html_e( 'Certificado enviado', 'reforestamos-comunicacion' ); ?></span>
								<?php else : ?>
									<button class="button button-small regenerate-certificate" data-adoption-id="<?php echo esc_attr( $adoption->id ); ?>">
										<?php esc_html_e( 'Generar certificado', 'reforestamos-comunicacion' ); ?>
									</button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="8"><?php esc_html_e( 'No hay adopciones registradas', 'reforestamos-comunicacion' ); ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
jQuery(document).ready(function($) {
	$('.regenerate-certificate').on('click', function() {
		var adoptionId = $(this).data('adoption-id');
		var $button = $(this);
		
		$button.prop('disabled', true).text('<?php esc_html_e( 'Generando...', 'reforestamos-comunicacion' ); ?>');
		
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'regenerate_certificate',
				adoption_id: adoptionId,
				nonce: '<?php echo esc_js( wp_create_nonce( 'regenerate_certificate' ) ); ?>'
			},
			success: function(response) {
				if (response.success) {
					alert('<?php esc_html_e( 'Certificado generado y enviado', 'reforestamos-comunicacion' ); ?>');
					location.reload();
				} else {
					alert(response.data.message);
					$button.prop('disabled', false).text('<?php esc_html_e( 'Generar certificado', 'reforestamos-comunicacion' ); ?>');
				}
			},
			error: function() {
				alert('<?php esc_html_e( 'Error al generar certificado', 'reforestamos-comunicacion' ); ?>');
				$button.prop('disabled', false).text('<?php esc_html_e( 'Generar certificado', 'reforestamos-comunicacion' ); ?>');
			}
		});
	});
});
</script>
