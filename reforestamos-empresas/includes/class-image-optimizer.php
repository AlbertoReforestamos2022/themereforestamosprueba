<?php
/**
 * Image Optimizer Class
 *
 * Handles image optimization for company logos and gallery images
 * including lazy loading and optimized image sizes.
 *
 * @package Reforestamos_Empresas
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Image Optimizer Class
 *
 * Provides image optimization features:
 * - Custom image sizes for company logos
 * - Lazy loading implementation
 * - Image compression on upload
 * - Responsive image srcset generation
 */
class Reforestamos_Image_Optimizer {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Image_Optimizer
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Image_Optimizer
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
		// Register custom image sizes
		add_action( 'after_setup_theme', array( $this, 'register_image_sizes' ) );
		
		// Add lazy loading to images
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazy_loading' ), 10, 3 );
		
		// Optimize images on upload
		add_filter( 'wp_handle_upload', array( $this, 'optimize_on_upload' ) );
		
		// Add image sizes to media library
		add_filter( 'image_size_names_choose', array( $this, 'add_custom_sizes_to_media' ) );
		
		// Generate WebP versions if supported
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'generate_webp_versions' ), 10, 2 );
	}

	/**
	 * Register custom image sizes for companies
	 */
	public function register_image_sizes() {
		// Logo sizes
		add_image_size( 'company-logo-thumb', 150, 100, false );
		add_image_size( 'company-logo', 300, 200, false );
		add_image_size( 'company-logo-large', 600, 400, false );
		
		// Gallery sizes
		add_image_size( 'company-gallery-thumb', 400, 300, true );
		add_image_size( 'company-gallery-medium', 800, 600, true );
		add_image_size( 'company-gallery-large', 1200, 900, true );
	}

	/**
	 * Add lazy loading attribute to images
	 *
	 * @param array        $attr       Image attributes.
	 * @param WP_Post      $attachment Attachment post object.
	 * @param string|array $size       Image size.
	 * @return array Modified attributes.
	 */
	public function add_lazy_loading( $attr, $attachment, $size ) {
		// Check if this is a company image
		$post_type = get_post_type( $attachment->post_parent );
		
		if ( 'empresas' === $post_type ) {
			// Add native lazy loading
			$attr['loading'] = 'lazy';
			
			// Add decoding attribute for better performance
			$attr['decoding'] = 'async';
		}
		
		return $attr;
	}

	/**
	 * Optimize image on upload
	 *
	 * @param array $upload Upload data.
	 * @return array Modified upload data.
	 */
	public function optimize_on_upload( $upload ) {
		// Only process images
		if ( ! isset( $upload['type'] ) || strpos( $upload['type'], 'image/' ) !== 0 ) {
			return $upload;
		}

		// Check if this is for a company post
		if ( ! isset( $_POST['post_id'] ) ) {
			return $upload;
		}

		$post_id = intval( $_POST['post_id'] );
		$post_type = get_post_type( $post_id );

		if ( 'empresas' !== $post_type ) {
			return $upload;
		}

		// Get image path
		$file_path = $upload['file'];

		// Optimize based on image type
		$image_type = exif_imagetype( $file_path );

		switch ( $image_type ) {
			case IMAGETYPE_JPEG:
				$this->optimize_jpeg( $file_path );
				break;
			case IMAGETYPE_PNG:
				$this->optimize_png( $file_path );
				break;
		}

		return $upload;
	}

	/**
	 * Optimize JPEG image
	 *
	 * @param string $file_path Path to image file.
	 */
	private function optimize_jpeg( $file_path ) {
		// Load image
		$image = imagecreatefromjpeg( $file_path );
		
		if ( ! $image ) {
			return;
		}

		// Save with optimized quality (85%)
		imagejpeg( $image, $file_path, 85 );
		
		// Free memory
		imagedestroy( $image );
	}

	/**
	 * Optimize PNG image
	 *
	 * @param string $file_path Path to image file.
	 */
	private function optimize_png( $file_path ) {
		// Load image
		$image = imagecreatefrompng( $file_path );
		
		if ( ! $image ) {
			return;
		}

		// Set compression level (0-9, 9 is best compression)
		imagepng( $image, $file_path, 9 );
		
		// Free memory
		imagedestroy( $image );
	}

	/**
	 * Add custom image sizes to media library dropdown
	 *
	 * @param array $sizes Existing sizes.
	 * @return array Modified sizes.
	 */
	public function add_custom_sizes_to_media( $sizes ) {
		return array_merge(
			$sizes,
			array(
				'company-logo-thumb'       => __( 'Logo Empresa (Miniatura)', 'reforestamos-empresas' ),
				'company-logo'             => __( 'Logo Empresa (Mediano)', 'reforestamos-empresas' ),
				'company-logo-large'       => __( 'Logo Empresa (Grande)', 'reforestamos-empresas' ),
				'company-gallery-thumb'    => __( 'Galería (Miniatura)', 'reforestamos-empresas' ),
				'company-gallery-medium'   => __( 'Galería (Mediano)', 'reforestamos-empresas' ),
				'company-gallery-large'    => __( 'Galería (Grande)', 'reforestamos-empresas' ),
			)
		);
	}

	/**
	 * Generate WebP versions of images
	 *
	 * @param array $metadata      Image metadata.
	 * @param int   $attachment_id Attachment ID.
	 * @return array Modified metadata.
	 */
	public function generate_webp_versions( $metadata, $attachment_id ) {
		// Check if WebP is supported
		if ( ! function_exists( 'imagewebp' ) ) {
			return $metadata;
		}

		// Check if this is a company image
		$post_parent = wp_get_post_parent_id( $attachment_id );
		if ( $post_parent ) {
			$post_type = get_post_type( $post_parent );
			if ( 'empresas' !== $post_type ) {
				return $metadata;
			}
		}

		// Get upload directory
		$upload_dir = wp_upload_dir();
		$base_dir = $upload_dir['basedir'];

		// Get original file path
		if ( ! isset( $metadata['file'] ) ) {
			return $metadata;
		}

		$original_file = $base_dir . '/' . $metadata['file'];
		$path_info = pathinfo( $original_file );

		// Generate WebP for original
		$this->create_webp_version( $original_file );

		// Generate WebP for all sizes
		if ( isset( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
			foreach ( $metadata['sizes'] as $size => $size_data ) {
				$size_file = $path_info['dirname'] . '/' . $size_data['file'];
				$this->create_webp_version( $size_file );
			}
		}

		return $metadata;
	}

	/**
	 * Create WebP version of an image
	 *
	 * @param string $file_path Path to image file.
	 * @return bool Success status.
	 */
	private function create_webp_version( $file_path ) {
		if ( ! file_exists( $file_path ) ) {
			return false;
		}

		// Get image type
		$image_type = exif_imagetype( $file_path );
		
		// Load image based on type
		switch ( $image_type ) {
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg( $file_path );
				break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng( $file_path );
				break;
			default:
				return false;
		}

		if ( ! $image ) {
			return false;
		}

		// Generate WebP filename
		$webp_path = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file_path );

		// Save as WebP with quality 85
		$result = imagewebp( $image, $webp_path, 85 );

		// Free memory
		imagedestroy( $image );

		return $result;
	}

	/**
	 * Get optimized image HTML with lazy loading
	 *
	 * @param int    $attachment_id Attachment ID.
	 * @param string $size          Image size.
	 * @param array  $attr          Additional attributes.
	 * @return string Image HTML.
	 */
	public static function get_optimized_image( $attachment_id, $size = 'company-logo', $attr = array() ) {
		// Add lazy loading by default
		if ( ! isset( $attr['loading'] ) ) {
			$attr['loading'] = 'lazy';
		}

		// Add decoding attribute
		if ( ! isset( $attr['decoding'] ) ) {
			$attr['decoding'] = 'async';
		}

		// Get image
		$image = wp_get_attachment_image( $attachment_id, $size, false, $attr );

		// Check if WebP version exists
		$image_url = wp_get_attachment_image_url( $attachment_id, $size );
		if ( $image_url ) {
			$webp_url = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $image_url );
			
			// Check if WebP file exists
			$upload_dir = wp_upload_dir();
			$webp_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $webp_url );
			
			if ( file_exists( $webp_path ) ) {
				// Wrap in picture element for WebP support
				$image = sprintf(
					'<picture><source srcset="%s" type="image/webp">%s</picture>',
					esc_url( $webp_url ),
					$image
				);
			}
		}

		return $image;
	}

	/**
	 * Get responsive image srcset
	 *
	 * @param int    $attachment_id Attachment ID.
	 * @param string $size          Image size.
	 * @return string Srcset attribute value.
	 */
	public static function get_responsive_srcset( $attachment_id, $size = 'company-logo' ) {
		return wp_get_attachment_image_srcset( $attachment_id, $size );
	}

	/**
	 * Get image sizes attribute
	 *
	 * @param int    $attachment_id Attachment ID.
	 * @param string $size          Image size.
	 * @return string Sizes attribute value.
	 */
	public static function get_image_sizes( $attachment_id, $size = 'company-logo' ) {
		return wp_get_attachment_image_sizes( $attachment_id, $size );
	}
}
