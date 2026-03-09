<?php
/**
 * Red OJA Microsite
 *
 * @package Reforestamos_Micrositios
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Red_OJA class
 *
 * Handles the Red OJA microsite functionality.
 */
class Reforestamos_Red_OJA {

	/**
	 * Render shortcode
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'height'     => '600px',
				'zoom'       => 5,
				'center_lat' => 23.6345,
				'center_lng' => -102.5528,
				'view'       => 'map', // 'map', 'directory', or 'both'
			),
			$atts,
			'red-oja'
		);

		ob_start();
		?>
		<div class="reforestamos-microsite red-oja">
			<div class="microsite-filters">
				<select id="filter-estado" class="form-select">
					<option value=""><?php esc_html_e( 'Todos los estados', 'reforestamos-micrositios' ); ?></option>
				</select>
				<select id="filter-tipo" class="form-select">
					<option value=""><?php esc_html_e( 'Todos los tipos', 'reforestamos-micrositios' ); ?></option>
				</select>
				<select id="filter-actividad" class="form-select">
					<option value=""><?php esc_html_e( 'Todas las actividades', 'reforestamos-micrositios' ); ?></option>
				</select>
				<input type="text" id="search-organizacion" class="form-control" 
					   placeholder="<?php esc_attr_e( 'Buscar organización...', 'reforestamos-micrositios' ); ?>">
			</div>
			
			<?php if ( 'directory' !== $atts['view'] ) : ?>
			<div id="oja-map" style="height: <?php echo esc_attr( $atts['height'] ); ?>"></div>
			<?php endif; ?>
			
			<?php if ( 'map' !== $atts['view'] ) : ?>
			<div id="oja-directory" class="microsite-directory">
				<div class="directory-list">
					<!-- Directory items will be populated by JavaScript -->
				</div>
			</div>
			<?php endif; ?>
			
			<div class="microsite-stats">
				<div class="stat-item">
					<span class="stat-number" id="total-organizaciones">0</span>
					<span class="stat-label"><?php esc_html_e( 'Organizaciones', 'reforestamos-micrositios' ); ?></span>
				</div>
				<div class="stat-item">
					<span class="stat-number" id="total-estados">0</span>
					<span class="stat-label"><?php esc_html_e( 'Estados', 'reforestamos-micrositios' ); ?></span>
				</div>
				<div class="stat-item">
					<span class="stat-number" id="total-jovenes">0</span>
					<span class="stat-label"><?php esc_html_e( 'Jóvenes', 'reforestamos-micrositios' ); ?></span>
				</div>
			</div>
		</div>
		
		<script>
		jQuery(document).ready(function($) {
			const mapConfig = {
				containerId: 'oja-map',
				dataFile: 'red-oja.json',
				zoom: <?php echo intval( $atts['zoom'] ); ?>,
				center: [<?php echo floatval( $atts['center_lat'] ); ?>, <?php echo floatval( $atts['center_lng'] ); ?>],
				view: '<?php echo esc_js( $atts['view'] ); ?>'
			};
			
			if (typeof ReforestamosMap !== 'undefined') {
				ReforestamosMap.initOJAMap(mapConfig);
			}
		});
		</script>
		<?php
		return ob_get_clean();
	}
}
