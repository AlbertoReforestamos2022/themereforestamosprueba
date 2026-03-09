<?php
/**
 * Map Renderer
 *
 * @package Reforestamos_Micrositios
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Map_Renderer class
 *
 * Handles map rendering and marker generation.
 */
class Reforestamos_Map_Renderer {

	/**
	 * Generate marker HTML
	 *
	 * @param array $data Marker data.
	 * @return string HTML for marker popup.
	 */
	public static function generate_marker_html( $data ) {
		$html = '<div class="map-marker-popup">';
		
		if ( ! empty( $data['title'] ) ) {
			$html .= '<h3>' . esc_html( $data['title'] ) . '</h3>';
		}
		
		if ( ! empty( $data['description'] ) ) {
			$html .= '<p>' . esc_html( $data['description'] ) . '</p>';
		}
		
		if ( ! empty( $data['image'] ) ) {
			$html .= '<img src="' . esc_url( $data['image'] ) . '" alt="' . esc_attr( $data['title'] ) . '" />';
		}
		
		if ( ! empty( $data['link'] ) ) {
			$html .= '<a href="' . esc_url( $data['link'] ) . '" class="btn btn-primary btn-sm">' . 
					 esc_html__( 'Ver más', 'reforestamos-micrositios' ) . '</a>';
		}
		
		$html .= '</div>';
		
		return $html;
	}

	/**
	 * Get marker icon configuration
	 *
	 * @param string $type Marker type.
	 * @return array Icon configuration.
	 */
	public static function get_marker_icon( $type = 'default' ) {
		$icons = array(
			'default' => array(
				'iconUrl'    => REFORESTAMOS_MICRO_URL . 'assets/images/markers/default.png',
				'iconSize'   => array( 25, 41 ),
				'iconAnchor' => array( 12, 41 ),
				'popupAnchor' => array( 1, -34 ),
			),
			'tree'    => array(
				'iconUrl'    => REFORESTAMOS_MICRO_URL . 'assets/images/markers/tree.png',
				'iconSize'   => array( 30, 30 ),
				'iconAnchor' => array( 15, 30 ),
				'popupAnchor' => array( 0, -30 ),
			),
			'organization' => array(
				'iconUrl'    => REFORESTAMOS_MICRO_URL . 'assets/images/markers/organization.png',
				'iconSize'   => array( 30, 30 ),
				'iconAnchor' => array( 15, 30 ),
				'popupAnchor' => array( 0, -30 ),
			),
		);

		return isset( $icons[ $type ] ) ? $icons[ $type ] : $icons['default'];
	}

	/**
	 * Validate coordinates
	 *
	 * @param float $lat Latitude.
	 * @param float $lng Longitude.
	 * @return bool True if valid.
	 */
	public static function validate_coordinates( $lat, $lng ) {
		return ( $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180 );
	}
}
