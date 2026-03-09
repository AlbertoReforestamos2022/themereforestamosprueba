<?php
/**
 * Árboles y Ciudades Microsite
 *
 * @package Reforestamos_Micrositios
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Arboles_Ciudades class
 *
 * Handles the Árboles y Ciudades microsite functionality.
 */
class Reforestamos_Arboles_Ciudades {

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
				'zoom'       => 6,
				'center_lat' => 23.6345,
				'center_lng' => -102.5528,
			),
			$atts,
			'arboles-ciudades'
		);

		ob_start();
		?>
		<div class="reforestamos-microsite arboles-ciudades">
			<div class="microsite-filters">
				<select id="filter-ciudad" class="form-select">
					<option value=""><?php esc_html_e( 'Todas las ciudades', 'reforestamos-micrositios' ); ?></option>
				</select>
				<select id="filter-especie" class="form-select">
					<option value=""><?php esc_html_e( 'Todas las especies', 'reforestamos-micrositios' ); ?></option>
				</select>
				<input type="text" id="search-arbol" class="form-control" 
					   placeholder="<?php esc_attr_e( 'Buscar...', 'reforestamos-micrositios' ); ?>">
			</div>
			
			<div id="arboles-map" style="height: <?php echo esc_attr( $atts['height'] ); ?>"></div>
			
			<div class="microsite-stats">
				<div class="stat-item">
					<span class="stat-number" id="total-arboles">0</span>
					<span class="stat-label"><?php esc_html_e( 'Árboles Plantados', 'reforestamos-micrositios' ); ?></span>
				</div>
				<div class="stat-item">
					<span class="stat-number" id="total-especies">0</span>
					<span class="stat-label"><?php esc_html_e( 'Especies', 'reforestamos-micrositios' ); ?></span>
				</div>
				<div class="stat-item">
					<span class="stat-number" id="total-ciudades">0</span>
					<span class="stat-label"><?php esc_html_e( 'Ciudades', 'reforestamos-micrositios' ); ?></span>
				</div>
			</div>
		</div>
		
		<script>
		jQuery(document).ready(function($) {
			const mapConfig = {
				containerId: 'arboles-map',
				dataFile: 'arboles-ciudades.json',
				zoom: <?php echo intval( $atts['zoom'] ); ?>,
				center: [<?php echo floatval( $atts['center_lat'] ); ?>, <?php echo floatval( $atts['center_lng'] ); ?>]
			};
			
			if (typeof ReforestamosMap !== 'undefined') {
				ReforestamosMap.initArbolesMap(mapConfig);
			}
		});
		</script>
		<?php
		return ob_get_clean();
	}
}
