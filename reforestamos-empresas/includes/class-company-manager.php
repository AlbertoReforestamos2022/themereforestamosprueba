<?php
/**
 * Company Manager Class
 *
 * Extends functionality of the Empresas Custom Post Type from Core Plugin.
 * Handles additional company-specific features like custom fields, metadata,
 * and business logic for company management.
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Company Manager Class
 *
 * Manages extended functionality for the Empresas CPT including:
 * - Custom meta boxes for company data
 * - Company logo management
 * - Contact information handling
 * - Gallery integration
 * - Category filtering
 */
class Reforestamos_Company_Manager {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Company_Manager
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Company_Manager
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
		// Add custom meta boxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		
		// Save meta box data
		add_action( 'save_post_empresas', array( $this, 'save_company_meta' ), 10, 2 );
		
		// Add custom columns to admin list
		add_filter( 'manage_empresas_posts_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_empresas_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
		
		// Make columns sortable
		add_filter( 'manage_edit-empresas_sortable_columns', array( $this, 'make_columns_sortable' ) );
		
		// Add image size for company logos
		add_action( 'after_setup_theme', array( $this, 'register_image_sizes' ) );
	}

	/**
	 * Register custom image sizes for company logos
	 */
	public function register_image_sizes() {
		// Logo size for grid display
		add_image_size( 'company-logo', 300, 200, false );
		
		// Logo size for single company page
		add_image_size( 'company-logo-large', 600, 400, false );
		
		// Thumbnail for admin
		add_image_size( 'company-logo-thumb', 150, 100, false );
	}

	/**
	 * Add custom meta boxes
	 */
	public function add_meta_boxes() {
		// Company Information meta box
		add_meta_box(
			'company_information',
			__( 'Información de la Empresa', 'reforestamos-empresas' ),
			array( $this, 'render_company_info_metabox' ),
			'empresas',
			'normal',
			'high'
		);

		// Contact Information meta box
		add_meta_box(
			'company_contact',
			__( 'Datos de Contacto', 'reforestamos-empresas' ),
			array( $this, 'render_contact_metabox' ),
			'empresas',
			'normal',
			'default'
		);

		// Gallery meta box
		add_meta_box(
			'company_gallery',
			__( 'Galería de Imágenes', 'reforestamos-empresas' ),
			array( $this, 'render_gallery_metabox' ),
			'empresas',
			'side',
			'default'
		);
	}

	/**
	 * Render company information meta box
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_company_info_metabox( $post ) {
		// Add nonce for security
		wp_nonce_field( 'company_meta_nonce', 'company_meta_nonce_field' );

		// Get existing values
		$website_url = get_post_meta( $post->ID, '_company_website', true );
		$industry    = get_post_meta( $post->ID, '_company_industry', true );
		$partnership = get_post_meta( $post->ID, '_company_partnership_type', true );
		$active      = get_post_meta( $post->ID, '_company_active', true );
		
		// Default to active if not set
		if ( '' === $active ) {
			$active = '1';
		}
		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="company_website"><?php esc_html_e( 'Sitio Web', 'reforestamos-empresas' ); ?></label>
				</th>
				<td>
					<input type="url" 
						   id="company_website" 
						   name="company_website" 
						   value="<?php echo esc_url( $website_url ); ?>" 
						   class="regular-text" 
						   placeholder="https://ejemplo.com">
					<p class="description"><?php esc_html_e( 'URL del sitio web de la empresa', 'reforestamos-empresas' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="company_industry"><?php esc_html_e( 'Industria', 'reforestamos-empresas' ); ?></label>
				</th>
				<td>
					<select id="company_industry" name="company_industry" class="regular-text">
						<option value=""><?php esc_html_e( 'Seleccionar...', 'reforestamos-empresas' ); ?></option>
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
					<p class="description"><?php esc_html_e( 'Sector industrial de la empresa', 'reforestamos-empresas' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="company_partnership"><?php esc_html_e( 'Tipo de Colaboración', 'reforestamos-empresas' ); ?></label>
				</th>
				<td>
					<select id="company_partnership" name="company_partnership" class="regular-text">
						<option value=""><?php esc_html_e( 'Seleccionar...', 'reforestamos-empresas' ); ?></option>
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
					<p class="description"><?php esc_html_e( 'Tipo de colaboración con Reforestamos', 'reforestamos-empresas' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="company_active"><?php esc_html_e( 'Estado', 'reforestamos-empresas' ); ?></label>
				</th>
				<td>
					<label>
						<input type="checkbox" 
							   id="company_active" 
							   name="company_active" 
							   value="1" 
							   <?php checked( $active, '1' ); ?>>
						<?php esc_html_e( 'Empresa activa (mostrar en el sitio)', 'reforestamos-empresas' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'Solo las empresas activas se mostrarán en el grid público', 'reforestamos-empresas' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Render contact information meta box
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_contact_metabox( $post ) {
		// Get existing values
		$email   = get_post_meta( $post->ID, '_company_email', true );
		$phone   = get_post_meta( $post->ID, '_company_phone', true );
		$address = get_post_meta( $post->ID, '_company_address', true );
		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="company_email"><?php esc_html_e( 'Email', 'reforestamos-empresas' ); ?></label>
				</th>
				<td>
					<input type="email" 
						   id="company_email" 
						   name="company_email" 
						   value="<?php echo esc_attr( $email ); ?>" 
						   class="regular-text">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="company_phone"><?php esc_html_e( 'Teléfono', 'reforestamos-empresas' ); ?></label>
				</th>
				<td>
					<input type="tel" 
						   id="company_phone" 
						   name="company_phone" 
						   value="<?php echo esc_attr( $phone ); ?>" 
						   class="regular-text">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="company_address"><?php esc_html_e( 'Dirección', 'reforestamos-empresas' ); ?></label>
				</th>
				<td>
					<textarea id="company_address" 
							  name="company_address" 
							  rows="3" 
							  class="large-text"><?php echo esc_textarea( $address ); ?></textarea>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Render gallery meta box
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_gallery_metabox( $post ) {
		$gallery_ids = get_post_meta( $post->ID, '_company_gallery', true );
		wp_nonce_field( 'gallery_nonce_action', 'gallery_nonce' );
		?>
		<div class="company-gallery-container">
			<input type="hidden" id="company_gallery_ids" name="company_gallery" value="<?php echo esc_attr( $gallery_ids ); ?>">
			
			<div id="company_gallery_preview" class="company-gallery-preview">
				<?php
				if ( $gallery_ids ) {
					$ids = explode( ',', $gallery_ids );
					foreach ( $ids as $id ) {
						$image = wp_get_attachment_image( $id, 'thumbnail' );
						$caption = wp_get_attachment_caption( $id );
						$caption_html = $caption ? '<div class="gallery-caption">' . esc_html( $caption ) . '</div>' : '';
						if ( $image ) {
							echo '<div class="gallery-image" data-id="' . esc_attr( $id ) . '" title="Doble clic para editar caption">' . 
								 $image . 
								 '<span class="remove-image">&times;</span>' . 
								 $caption_html . 
								 '</div>';
						}
					}
				}
				?>
			</div>
			
			<p>
				<button type="button" class="button button-secondary" id="add_gallery_images">
					<?php esc_html_e( 'Agregar Imágenes', 'reforestamos-empresas' ); ?>
				</button>
			</p>
			<p class="description">
				<?php esc_html_e( 'Selecciona múltiples imágenes para la galería. Arrastra para reordenar. Doble clic en una imagen para editar su caption.', 'reforestamos-empresas' ); ?>
			</p>
		</div>
		
		<style>
			.company-gallery-preview {
				display: flex;
				flex-wrap: wrap;
				gap: 10px;
				margin-bottom: 10px;
				min-height: 50px;
			}
			.gallery-image {
				position: relative;
				width: 100px;
				height: 100px;
				cursor: move;
			}
			.gallery-image img {
				width: 100%;
				height: 100%;
				object-fit: cover;
				cursor: pointer;
			}
			.gallery-image .remove-image {
				position: absolute;
				top: -5px;
				right: -5px;
				background: #dc3232;
				color: white;
				border-radius: 50%;
				width: 20px;
				height: 20px;
				text-align: center;
				line-height: 20px;
				cursor: pointer;
				font-size: 14px;
				z-index: 10;
			}
			.gallery-image .gallery-caption {
				position: absolute;
				bottom: 0;
				left: 0;
				right: 0;
				background: rgba(0, 0, 0, 0.7);
				color: white;
				font-size: 10px;
				padding: 2px 4px;
				overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap;
			}
			.gallery-image-placeholder {
				background: #f0f0f0;
				border: 2px dashed #ccc;
				width: 100px;
				height: 100px;
			}
			.ui-sortable-helper {
				opacity: 0.7;
			}
		</style>
		<?php
	}

	/**
	 * Save company meta data
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function save_company_meta( $post_id, $post ) {
		// Check nonce
		if ( ! isset( $_POST['company_meta_nonce_field'] ) || 
			 ! wp_verify_nonce( $_POST['company_meta_nonce_field'], 'company_meta_nonce' ) ) {
			return;
		}

		// Check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save company information
		if ( isset( $_POST['company_website'] ) ) {
			update_post_meta( $post_id, '_company_website', esc_url_raw( $_POST['company_website'] ) );
		}

		if ( isset( $_POST['company_industry'] ) ) {
			update_post_meta( $post_id, '_company_industry', sanitize_text_field( $_POST['company_industry'] ) );
		}

		if ( isset( $_POST['company_partnership'] ) ) {
			update_post_meta( $post_id, '_company_partnership_type', sanitize_text_field( $_POST['company_partnership'] ) );
		}

		// Save active status (checkbox)
		$active = isset( $_POST['company_active'] ) ? '1' : '0';
		update_post_meta( $post_id, '_company_active', $active );

		// Save contact information
		if ( isset( $_POST['company_email'] ) ) {
			update_post_meta( $post_id, '_company_email', sanitize_email( $_POST['company_email'] ) );
		}

		if ( isset( $_POST['company_phone'] ) ) {
			update_post_meta( $post_id, '_company_phone', sanitize_text_field( $_POST['company_phone'] ) );
		}

		if ( isset( $_POST['company_address'] ) ) {
			update_post_meta( $post_id, '_company_address', sanitize_textarea_field( $_POST['company_address'] ) );
		}

		// Save gallery
		if ( isset( $_POST['company_gallery'] ) ) {
			update_post_meta( $post_id, '_company_gallery', sanitize_text_field( $_POST['company_gallery'] ) );
		}
	}

	/**
	 * Add custom columns to empresas list
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	public function add_custom_columns( $columns ) {
		$new_columns = array();
		
		// Add checkbox and title
		$new_columns['cb'] = $columns['cb'];
		$new_columns['title'] = $columns['title'];
		
		// Add custom columns
		$new_columns['logo'] = __( 'Logo', 'reforestamos-empresas' );
		$new_columns['industry'] = __( 'Industria', 'reforestamos-empresas' );
		$new_columns['partnership'] = __( 'Tipo de Colaboración', 'reforestamos-empresas' );
		$new_columns['active'] = __( 'Estado', 'reforestamos-empresas' );
		
		// Add remaining columns
		$new_columns['date'] = $columns['date'];
		
		return $new_columns;
	}

	/**
	 * Render custom column content
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function render_custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'logo':
				if ( has_post_thumbnail( $post_id ) ) {
					echo get_the_post_thumbnail( $post_id, 'company-logo-thumb' );
				} else {
					echo '<span class="dashicons dashicons-building" style="font-size: 50px; color: #ccc;"></span>';
				}
				break;

			case 'industry':
				$industry = get_post_meta( $post_id, '_company_industry', true );
				if ( $industry ) {
					$industries = $this->get_industry_options();
					echo esc_html( $industries[ $industry ] ?? $industry );
				} else {
					echo '—';
				}
				break;

			case 'partnership':
				$partnership = get_post_meta( $post_id, '_company_partnership_type', true );
				if ( $partnership ) {
					$partnerships = $this->get_partnership_options();
					echo esc_html( $partnerships[ $partnership ] ?? $partnership );
				} else {
					echo '—';
				}
				break;

			case 'active':
				$active = get_post_meta( $post_id, '_company_active', true );
				if ( '1' === $active ) {
					echo '<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span> ' . esc_html__( 'Activa', 'reforestamos-empresas' );
				} else {
					echo '<span class="dashicons dashicons-dismiss" style="color: #dc3232;"></span> ' . esc_html__( 'Inactiva', 'reforestamos-empresas' );
				}
				break;
		}
	}

	/**
	 * Make custom columns sortable
	 *
	 * @param array $columns Sortable columns.
	 * @return array Modified sortable columns.
	 */
	public function make_columns_sortable( $columns ) {
		$columns['industry'] = 'industry';
		$columns['partnership'] = 'partnership';
		$columns['active'] = 'active';
		return $columns;
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

	/**
	 * Get company data
	 *
	 * @param int $post_id Company post ID.
	 * @return array Company data.
	 */
	public static function get_company_data( $post_id ) {
		return array(
			'id'          => $post_id,
			'title'       => get_the_title( $post_id ),
			'description' => get_the_content( null, false, $post_id ),
			'excerpt'     => get_the_excerpt( $post_id ),
			'logo_url'    => get_the_post_thumbnail_url( $post_id, 'company-logo' ),
			'logo_large'  => get_the_post_thumbnail_url( $post_id, 'company-logo-large' ),
			'website'     => get_post_meta( $post_id, '_company_website', true ),
			'industry'    => get_post_meta( $post_id, '_company_industry', true ),
			'partnership' => get_post_meta( $post_id, '_company_partnership_type', true ),
			'active'      => get_post_meta( $post_id, '_company_active', true ),
			'email'       => get_post_meta( $post_id, '_company_email', true ),
			'phone'       => get_post_meta( $post_id, '_company_phone', true ),
			'address'     => get_post_meta( $post_id, '_company_address', true ),
			'gallery'     => get_post_meta( $post_id, '_company_gallery', true ),
			'permalink'   => get_permalink( $post_id ),
		);
	}
}
