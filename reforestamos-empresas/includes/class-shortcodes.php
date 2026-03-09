<?php
/**
 * Shortcodes Class
 *
 * Handles all shortcodes for the Empresas plugin including
 * companies grid display with filtering capabilities.
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcodes Class
 *
 * Provides shortcodes for displaying companies in various formats:
 * - [companies-grid] - Grid display of company logos
 * - Supports filtering by category/industry
 * - Responsive design
 */
class Reforestamos_Empresas_Shortcodes {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Empresas_Shortcodes
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Empresas_Shortcodes
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->register_shortcodes();
	}

	/**
	 * Register all shortcodes
	 */
	private function register_shortcodes() {
		add_shortcode( 'companies-grid', array( $this, 'render_companies_grid' ) );
		add_shortcode( 'company-gallery', array( $this, 'render_company_gallery' ) );
	}

	/**
	 * Render companies grid shortcode
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_companies_grid( $atts ) {
		// Parse attributes
		$atts = shortcode_atts(
			array(
				'columns'    => '3',
				'limit'      => '-1',
				'industry'   => '',
				'partnership' => '',
				'show_filter' => 'yes',
				'orderby'    => 'title',
				'order'      => 'ASC',
			),
			$atts,
			'companies-grid'
		);

		// Sanitize attributes
		$columns    = absint( $atts['columns'] );
		$limit      = intval( $atts['limit'] );
		$industry   = sanitize_text_field( $atts['industry'] );
		$partnership = sanitize_text_field( $atts['partnership'] );
		$show_filter = ( 'yes' === $atts['show_filter'] );
		$orderby    = sanitize_text_field( $atts['orderby'] );
		$order      = strtoupper( sanitize_text_field( $atts['order'] ) );

		// Validate columns (1-4)
		$columns = max( 1, min( 4, $columns ) );

		// Build query args
		$query_args = array(
			'post_type'      => 'empresas',
			'posts_per_page' => $limit,
			'orderby'        => $orderby,
			'order'          => $order,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => '_company_active',
					'value'   => '1',
					'compare' => '=',
				),
			),
		);

		// Add industry filter
		if ( ! empty( $industry ) ) {
			$query_args['meta_query'][] = array(
				'key'     => '_company_industry',
				'value'   => $industry,
				'compare' => '=',
			);
		}

		// Add partnership filter
		if ( ! empty( $partnership ) ) {
			$query_args['meta_query'][] = array(
				'key'     => '_company_partnership_type',
				'value'   => $partnership,
				'compare' => '=',
			);
		}

		// Query companies
		$companies = new WP_Query( $query_args );

		// Start output buffering
		ob_start();

		// Enqueue grid styles
		wp_enqueue_style(
			'reforestamos-empresas-grid',
			REFORESTAMOS_EMPRESAS_URL . 'assets/css/companies-grid.css',
			array(),
			REFORESTAMOS_EMPRESAS_VERSION
		);

		// Enqueue grid JavaScript
		wp_enqueue_script(
			'reforestamos-empresas-grid',
			REFORESTAMOS_EMPRESAS_URL . 'assets/js/companies-grid.js',
			array( 'jquery' ),
			REFORESTAMOS_EMPRESAS_VERSION,
			true
		);

		?>
		<div class="companies-grid-wrapper" data-columns="<?php echo esc_attr( $columns ); ?>">
			
			<?php if ( $show_filter ) : ?>
				<div class="companies-filter">
					<div class="filter-group">
						<label for="filter-industry"><?php esc_html_e( 'Filtrar por Industria:', 'reforestamos-empresas' ); ?></label>
						<select id="filter-industry" class="filter-select">
							<option value=""><?php esc_html_e( 'Todas las Industrias', 'reforestamos-empresas' ); ?></option>
							<?php
							$industries = $this->get_industry_options();
							foreach ( $industries as $value => $label ) {
								printf(
									'<option value="%s" %s>%s</option>',
									esc_attr( $value ),
									selected( $industry, $value, false ),
									esc_html( $label )
								);
							}
							?>
						</select>
					</div>

					<div class="filter-group">
						<label for="filter-partnership"><?php esc_html_e( 'Filtrar por Colaboración:', 'reforestamos-empresas' ); ?></label>
						<select id="filter-partnership" class="filter-select">
							<option value=""><?php esc_html_e( 'Todos los Tipos', 'reforestamos-empresas' ); ?></option>
							<?php
							$partnerships = $this->get_partnership_options();
							foreach ( $partnerships as $value => $label ) {
								printf(
									'<option value="%s" %s>%s</option>',
									esc_attr( $value ),
									selected( $partnership, $value, false ),
									esc_html( $label )
								);
							}
							?>
						</select>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $companies->have_posts() ) : ?>
				<div class="companies-grid columns-<?php echo esc_attr( $columns ); ?>">
					<?php while ( $companies->have_posts() ) : $companies->the_post(); ?>
						<?php
						$company_id = get_the_ID();
						$company_data = Reforestamos_Company_Manager::get_company_data( $company_id );
						?>
						<div class="company-item" 
							 data-industry="<?php echo esc_attr( $company_data['industry'] ); ?>"
							 data-partnership="<?php echo esc_attr( $company_data['partnership'] ); ?>">
							<a href="<?php echo esc_url( $company_data['permalink'] ); ?>" 
							   class="company-link company-logo-link"
							   data-company-id="<?php echo esc_attr( $company_id ); ?>">
								<div class="company-logo-container">
									<?php if ( $company_data['logo_url'] ) : ?>
										<img src="<?php echo esc_url( $company_data['logo_url'] ); ?>" 
											 alt="<?php echo esc_attr( $company_data['title'] ); ?>"
											 class="company-logo"
											 loading="lazy">
									<?php else : ?>
										<div class="company-logo-placeholder">
											<span class="dashicons dashicons-building"></span>
										</div>
									<?php endif; ?>
								</div>
								<div class="company-info">
									<h3 class="company-name"><?php echo esc_html( $company_data['title'] ); ?></h3>
									<?php if ( $company_data['industry'] ) : ?>
										<span class="company-industry"><?php echo esc_html( $company_data['industry'] ); ?></span>
									<?php endif; ?>
								</div>
							</a>
						</div>
					<?php endwhile; ?>
				</div>

				<div class="companies-count">
					<?php
					printf(
						/* translators: %d: number of companies */
						esc_html( _n( 'Mostrando %d empresa', 'Mostrando %d empresas', $companies->found_posts, 'reforestamos-empresas' ) ),
						absint( $companies->found_posts )
					);
					?>
				</div>

			<?php else : ?>
				<div class="no-companies-found">
					<p><?php esc_html_e( 'No se encontraron empresas con los filtros seleccionados.', 'reforestamos-empresas' ); ?></p>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get industry options
	 *
	 * @return array Industry options.
	 */
	private function get_industry_options() {
		return array(
			'tecnologia'     => __( 'Tecnología', 'reforestamos-empresas' ),
			'manufactura'    => __( 'Manufactura', 'reforestamos-empresas' ),
			'servicios'      => __( 'Servicios', 'reforestamos-empresas' ),
			'construccion'   => __( 'Construcción', 'reforestamos-empresas' ),
			'alimentos'      => __( 'Alimentos y Bebidas', 'reforestamos-empresas' ),
			'energia'        => __( 'Energía', 'reforestamos-empresas' ),
			'transporte'     => __( 'Transporte', 'reforestamos-empresas' ),
			'financiero'     => __( 'Sector Financiero', 'reforestamos-empresas' ),
			'retail'         => __( 'Retail', 'reforestamos-empresas' ),
			'telecomunicaciones' => __( 'Telecomunicaciones', 'reforestamos-empresas' ),
			'otro'           => __( 'Otro', 'reforestamos-empresas' ),
		);
	}

	/**
	 * Get partnership type options
	 *
	 * @return array Partnership options.
	 */
	private function get_partnership_options() {
		return array(
			'patrocinador'   => __( 'Patrocinador', 'reforestamos-empresas' ),
			'donante'        => __( 'Donante', 'reforestamos-empresas' ),
			'voluntario'     => __( 'Voluntariado Corporativo', 'reforestamos-empresas' ),
			'aliado'         => __( 'Aliado Estratégico', 'reforestamos-empresas' ),
			'proveedor'      => __( 'Proveedor', 'reforestamos-empresas' ),
		);
	}
}

	/**
	 * Render company gallery shortcode
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render_company_gallery( $atts ) {
		// Parse attributes
		$atts = shortcode_atts(
			array(
				'id'      => 0,
				'columns' => '3',
				'size'    => 'gallery-medium',
			),
			$atts,
			'company-gallery'
		);

		// Sanitize attributes
		$company_id = absint( $atts['id'] );
		$columns    = absint( $atts['columns'] );
		$size       = sanitize_text_field( $atts['size'] );

		// Validate company ID
		if ( ! $company_id ) {
			return '<p class="company-gallery-error">' . esc_html__( 'ID de empresa no especificado. Use: [company-gallery id="123"]', 'reforestamos-empresas' ) . '</p>';
		}

		// Check if company exists
		$company = get_post( $company_id );
		if ( ! $company || 'empresas' !== $company->post_type ) {
			return '<p class="company-gallery-error">' . esc_html__( 'Empresa no encontrada.', 'reforestamos-empresas' ) . '</p>';
		}

		// Get gallery images
		$images = Reforestamos_Gallery_Manager::get_gallery_images( $company_id );
		
		if ( empty( $images ) ) {
			return '<p class="company-gallery-empty">' . esc_html__( 'Esta empresa no tiene imágenes en su galería.', 'reforestamos-empresas' ) . '</p>';
		}

		// Validate columns (1-6)
		$columns = max( 1, min( 6, $columns ) );

		// Enqueue Lightbox2
		wp_enqueue_style(
			'lightbox2',
			'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css',
			array(),
			'2.11.4'
		);

		wp_enqueue_script(
			'lightbox2',
			'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js',
			array( 'jquery' ),
			'2.11.4',
			true
		);

		// Enqueue gallery styles
		wp_enqueue_style(
			'reforestamos-empresas-gallery',
			REFORESTAMOS_EMPRESAS_URL . 'assets/css/gallery.css',
			array(),
			REFORESTAMOS_EMPRESAS_VERSION
		);

		// Start output buffering
		ob_start();
		?>
		<div class="reforestamos-company-gallery" data-company-id="<?php echo esc_attr( $company_id ); ?>">
			<div class="gallery-header">
				<h3 class="gallery-title">
					<?php
					printf(
						/* translators: %s: company name */
						esc_html__( 'Galería de %s', 'reforestamos-empresas' ),
						esc_html( get_the_title( $company_id ) )
					);
					?>
				</h3>
				<span class="gallery-count">
					<?php
					printf(
						/* translators: %d: number of images */
						esc_html( _n( '%d imagen', '%d imágenes', count( $images ), 'reforestamos-empresas' ) ),
						count( $images )
					);
					?>
				</span>
			</div>

			<div class="gallery-grid gallery-columns-<?php echo esc_attr( $columns ); ?>">
				<?php foreach ( $images as $index => $image ) : ?>
					<div class="gallery-item">
						<a href="<?php echo esc_url( $image['large'] ); ?>" 
						   data-lightbox="company-gallery-<?php echo esc_attr( $company_id ); ?>"
						   data-title="<?php echo esc_attr( $image['caption'] ?: $image['title'] ); ?>"
						   class="gallery-link">
							<div class="gallery-image-wrapper">
								<img src="<?php echo esc_url( $image['thumb'] ); ?>" 
									 alt="<?php echo esc_attr( $image['alt'] ?: $image['title'] ); ?>"
									 class="gallery-image"
									 loading="lazy">
								<div class="gallery-overlay">
									<span class="gallery-icon dashicons dashicons-search"></span>
								</div>
							</div>
							<?php if ( $image['caption'] ) : ?>
								<div class="gallery-caption">
									<?php echo esc_html( $image['caption'] ); ?>
								</div>
							<?php endif; ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}
}
