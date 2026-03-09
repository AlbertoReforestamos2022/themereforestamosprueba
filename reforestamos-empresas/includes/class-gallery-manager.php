<?php
/**
 * Gallery Manager Class
 *
 * Manages company gallery functionality including image uploads,
 * captions, descriptions, and thumbnail generation.
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gallery Manager Class
 *
 * Handles:
 * - Gallery image management
 * - Image captions and descriptions
 * - Thumbnail generation
 * - Gallery rendering
 */
class Reforestamos_Gallery_Manager {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Gallery_Manager
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Gallery_Manager
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
		$this->init_hooks();
	}

	/**
	 * Initialize WordPress hooks
	 */
	private function init_hooks() {
		// Register image sizes for galleries
		add_action( 'after_setup_theme', array( $this, 'register_gallery_image_sizes' ) );
		
		// AJAX handlers for gallery management
		add_action( 'wp_ajax_save_gallery_caption', array( $this, 'ajax_save_caption' ) );
		add_action( 'wp_ajax_reorder_gallery_images', array( $this, 'ajax_reorder_images' ) );
		
		// Enqueue admin scripts for gallery management
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Register custom image sizes for galleries
	 */
	public function register_gallery_image_sizes() {
		// Gallery thumbnail for grid display
		add_image_size( 'gallery-thumb', 400, 300, true );
		
		// Gallery medium for lightbox preview
		add_image_size( 'gallery-medium', 800, 600, false );
		
		// Gallery large for full lightbox view
		add_image_size( 'gallery-large', 1200, 900, false );
	}

	/**
	 * Enqueue admin scripts for gallery management
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Only load on empresas edit screen
		if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
			return;
		}

		$screen = get_current_screen();
		if ( 'empresas' !== $screen->post_type ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	/**
	 * Get gallery images for a company
	 *
	 * @param int $company_id Company post ID.
	 * @return array Array of image data.
	 */
	public static function get_gallery_images( $company_id ) {
		$gallery_ids = get_post_meta( $company_id, '_company_gallery', true );
		
		if ( empty( $gallery_ids ) ) {
			return array();
		}

		$ids = explode( ',', $gallery_ids );
		$images = array();

		foreach ( $ids as $id ) {
			$id = intval( $id );
			if ( ! $id ) {
				continue;
			}

			$attachment = get_post( $id );
			if ( ! $attachment || 'attachment' !== $attachment->post_type ) {
				continue;
			}

			$images[] = array(
				'id'          => $id,
				'title'       => get_the_title( $id ),
				'caption'     => wp_get_attachment_caption( $id ),
				'description' => $attachment->post_content,
				'alt'         => get_post_meta( $id, '_wp_attachment_image_alt', true ),
				'thumb'       => wp_get_attachment_image_url( $id, 'gallery-thumb' ),
				'medium'      => wp_get_attachment_image_url( $id, 'gallery-medium' ),
				'large'       => wp_get_attachment_image_url( $id, 'gallery-large' ),
				'full'        => wp_get_attachment_image_url( $id, 'full' ),
			);
		}

		return $images;
	}

	/**
	 * Save image caption via AJAX
	 */
	public function ajax_save_caption() {
		check_ajax_referer( 'gallery_nonce', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permisos insuficientes', 'reforestamos-empresas' ) ) );
		}

		$image_id = intval( $_POST['image_id'] );
		$caption  = sanitize_text_field( $_POST['caption'] );

		if ( ! $image_id ) {
			wp_send_json_error( array( 'message' => __( 'ID de imagen inválido', 'reforestamos-empresas' ) ) );
		}

		// Update attachment caption
		$updated = wp_update_post(
			array(
				'ID'           => $image_id,
				'post_excerpt' => $caption,
			)
		);

		if ( is_wp_error( $updated ) ) {
			wp_send_json_error( array( 'message' => $updated->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Caption guardado', 'reforestamos-empresas' ) ) );
	}

	/**
	 * Reorder gallery images via AJAX
	 */
	public function ajax_reorder_images() {
		check_ajax_referer( 'gallery_nonce', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permisos insuficientes', 'reforestamos-empresas' ) ) );
		}

		$company_id = intval( $_POST['company_id'] );
		$order      = isset( $_POST['order'] ) ? array_map( 'intval', $_POST['order'] ) : array();

		if ( ! $company_id || empty( $order ) ) {
			wp_send_json_error( array( 'message' => __( 'Datos inválidos', 'reforestamos-empresas' ) ) );
		}

		// Save new order
		$gallery_ids = implode( ',', $order );
		update_post_meta( $company_id, '_company_gallery', $gallery_ids );

		wp_send_json_success( array( 'message' => __( 'Orden actualizado', 'reforestamos-empresas' ) ) );
	}

	/**
	 * Render gallery shortcode
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Gallery HTML.
	 */
	public static function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'      => 0,
				'columns' => 3,
				'size'    => 'gallery-medium',
			),
			$atts,
			'company-gallery'
		);

		$company_id = intval( $atts['id'] );
		if ( ! $company_id ) {
			return '<p>' . __( 'ID de empresa no especificado', 'reforestamos-empresas' ) . '</p>';
		}

		$images = self::get_gallery_images( $company_id );
		if ( empty( $images ) ) {
			return '<p>' . __( 'Esta empresa no tiene imágenes en su galería', 'reforestamos-empresas' ) . '</p>';
		}

		$columns = intval( $atts['columns'] );
		$columns = max( 1, min( 6, $columns ) ); // Limit between 1-6 columns

		ob_start();
		?>
		<div class="reforestamos-company-gallery" data-columns="<?php echo esc_attr( $columns ); ?>">
			<div class="gallery-grid gallery-columns-<?php echo esc_attr( $columns ); ?>">
				<?php foreach ( $images as $image ) : ?>
					<div class="gallery-item">
						<a href="<?php echo esc_url( $image['large'] ); ?>" 
						   data-lightbox="company-gallery-<?php echo esc_attr( $company_id ); ?>"
						   data-title="<?php echo esc_attr( $image['caption'] ?: $image['title'] ); ?>">
							<img src="<?php echo esc_url( $image['thumb'] ); ?>" 
								 alt="<?php echo esc_attr( $image['alt'] ?: $image['title'] ); ?>"
								 loading="lazy">
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

	/**
	 * Get all companies with galleries
	 *
	 * @return array Array of companies with gallery data.
	 */
	public static function get_companies_with_galleries() {
		$args = array(
			'post_type'      => 'empresas',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => '_company_gallery',
					'value'   => '',
					'compare' => '!=',
				),
			),
		);

		$query = new WP_Query( $args );
		$companies = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$company_id = get_the_ID();
				
				$companies[] = array(
					'id'           => $company_id,
					'title'        => get_the_title(),
					'permalink'    => get_permalink(),
					'logo'         => get_the_post_thumbnail_url( $company_id, 'company-logo' ),
					'image_count'  => count( self::get_gallery_images( $company_id ) ),
					'gallery_data' => self::get_gallery_images( $company_id ),
				);
			}
			wp_reset_postdata();
		}

		return $companies;
	}
}

