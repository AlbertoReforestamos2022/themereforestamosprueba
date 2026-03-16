<?php
/**
 * Shortcode Converter Class
 *
 * Convierte shortcodes del tema legacy a bloques Gutenberg.
 * Maneja la conversión de shortcodes comunes a sus equivalentes en bloques.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Shortcode_Converter class
 *
 * Converts legacy shortcodes to Gutenberg blocks during migration.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */
class Reforestamos_Shortcode_Converter {

	/**
	 * Dry run mode
	 *
	 * @var bool
	 */
	private $dry_run = false;

	/**
	 * Verbose mode
	 *
	 * @var bool
	 */
	private $verbose = false;

	/**
	 * Conversion warnings
	 *
	 * @var array
	 */
	private $warnings = [];

	/**
	 * Shortcode mapping
	 *
	 * @var array
	 */
	private $shortcode_mapping = [
		'contact-form'         => 'reforestamos/contacto',
		'arboles-ciudades'     => 'reforestamos/arboles-ciudades',
		'red-oja'              => 'reforestamos/red-oja',
		'companies-grid'       => 'reforestamos/logos-aliados',
		'newsletter-subscribe' => 'reforestamos/newsletter-subscribe',
		'eventos-proximos'     => 'reforestamos/eventos-proximos',
		'company-gallery'      => 'reforestamos/galeria-tabs',
	];

	/**
	 * Constructor
	 *
	 * @param bool $dry_run Dry run mode
	 * @param bool $verbose Verbose mode
	 */
	public function __construct( $dry_run = false, $verbose = false ) {
		$this->dry_run = $dry_run;
		$this->verbose = $verbose;
	}

	/**
	 * Convert all shortcodes in posts and pages
	 *
	 * @return array Conversion results
	 */
	public function convert_all_shortcodes() {
		$results = [
			'converted_count' => 0,
			'warnings'        => [],
		];

		$this->log( 'Iniciando conversión de shortcodes...' );

		// Get all posts and pages
		$args = [
			'post_type'      => [ 'post', 'page' ],
			'posts_per_page' => -1,
			'post_status'    => 'any',
		];

		$posts = get_posts( $args );
		$this->log( 'Encontrados ' . count( $posts ) . ' posts/páginas para analizar' );

		foreach ( $posts as $post ) {
			$converted = $this->convert_post_shortcodes( $post );

			if ( $converted ) {
				++$results['converted_count'];
			}
		}

		$results['warnings'] = $this->warnings;

		$this->log( "Conversión completada: {$results['converted_count']} posts convertidos" );

		return $results;
	}

	/**
	 * Convert shortcodes in a single post
	 *
	 * @param WP_Post $post Post object
	 * @return bool True if post was converted
	 */
	private function convert_post_shortcodes( $post ) {
		$original_content = $post->post_content;
		$new_content      = $original_content;
		$has_changes      = false;

		// Check if post has any shortcodes
		if ( ! has_shortcode( $original_content, '' ) && strpos( $original_content, '[' ) === false ) {
			return false;
		}

		// Convert each shortcode type
		foreach ( $this->shortcode_mapping as $shortcode => $block_name ) {
			if ( has_shortcode( $original_content, $shortcode ) ) {
				$this->log( "Convirtiendo shortcode [{$shortcode}] en post {$post->ID}: {$post->post_title}" );
				$new_content = $this->convert_shortcode_to_block( $new_content, $shortcode, $block_name );
				$has_changes = true;
			}
		}

		// Update post if changes were made
		if ( $has_changes && $new_content !== $original_content ) {
			if ( ! $this->dry_run ) {
				$result = wp_update_post(
					[
						'ID'           => $post->ID,
						'post_content' => $new_content,
					],
					true
				);

				if ( is_wp_error( $result ) ) {
					$warning = "Error al actualizar post {$post->ID}: " . $result->get_error_message();
					$this->log( $warning, 'warning' );
					$this->warnings[] = $warning;
					return false;
				}

				$this->log( "Post {$post->ID} actualizado exitosamente" );
			} else {
				$this->log( "DRY-RUN: Post {$post->ID} sería actualizado" );
			}

			return true;
		}

		return false;
	}

	/**
	 * Convert a specific shortcode to Gutenberg block
	 *
	 * @param string $content Post content
	 * @param string $shortcode Shortcode name
	 * @param string $block_name Block name
	 * @return string Converted content
	 */
	private function convert_shortcode_to_block( $content, $shortcode, $block_name ) {
		// Pattern to match shortcode with attributes and optional content
		// Matches both [shortcode] and [shortcode]content[/shortcode]
		$pattern = '/\[' . preg_quote( $shortcode, '/' ) . '([^\]]*)\](?:([^\[]*)\[\/' . preg_quote( $shortcode, '/' ) . '\])?/s';

		$content = preg_replace_callback(
			$pattern,
			function ( $matches ) use ( $shortcode, $block_name ) {
				$attributes_string = isset( $matches[1] ) ? trim( $matches[1] ) : '';
				$inner_content     = isset( $matches[2] ) ? trim( $matches[2] ) : '';

				$attributes = $this->parse_shortcode_attributes( $attributes_string );

				// Convert attributes to JSON for block
				$block_attributes = $this->convert_attributes_to_block_format( $attributes, $shortcode );

				// Check if this shortcode can be converted
				if ( ! $this->is_convertible( $shortcode, $attributes ) ) {
					$warning = "Shortcode [{$shortcode}] no puede ser convertido automáticamente. Requiere conversión manual.";
					$this->log( $warning, 'warning' );
					$this->warnings[] = $warning;

					// Return original shortcode with warning comment
					return "<!-- Advertencia: Este shortcode requiere conversión manual -->\n" . $matches[0];
				}

				// Generate block comment
				if ( ! empty( $inner_content ) ) {
					// Block with inner content
					if ( ! empty( $block_attributes ) ) {
						$json_attributes = wp_json_encode( $block_attributes );
						return "<!-- wp:{$block_name} {$json_attributes} -->\n{$inner_content}\n<!-- /wp:{$block_name} -->";
					} else {
						return "<!-- wp:{$block_name} -->\n{$inner_content}\n<!-- /wp:{$block_name} -->";
					}
				} else {
					// Self-closing block
					if ( ! empty( $block_attributes ) ) {
						$json_attributes = wp_json_encode( $block_attributes );
						return "<!-- wp:{$block_name} {$json_attributes} /-->";
					} else {
						return "<!-- wp:{$block_name} /-->";
					}
				}
			},
			$content
		);

		return $content;
	}

	/**
	 * Parse shortcode attributes string
	 *
	 * @param string $attributes_string Attributes string
	 * @return array Parsed attributes
	 */
	private function parse_shortcode_attributes( $attributes_string ) {
		$attributes = [];

		if ( empty( $attributes_string ) ) {
			return $attributes;
		}

		// Match attribute="value" or attribute='value' or attribute=value
		preg_match_all( '/(\w+)\s*=\s*["\']?([^"\'\s]+)["\']?/', $attributes_string, $matches, PREG_SET_ORDER );

		foreach ( $matches as $match ) {
			$attributes[ $match[1] ] = $match[2];
		}

		return $attributes;
	}

	/**
	 * Convert shortcode attributes to block format
	 *
	 * @param array  $attributes Shortcode attributes
	 * @param string $shortcode Shortcode name for specific conversions
	 * @return array Block attributes
	 */
	private function convert_attributes_to_block_format( $attributes, $shortcode = '' ) {
		$block_attributes = [];

		// Shortcode-specific attribute conversions
		switch ( $shortcode ) {
			case 'contact-form':
				// Map contact form attributes
				if ( isset( $attributes['id'] ) ) {
					$block_attributes['formId'] = $attributes['id'];
				}
				if ( isset( $attributes['show_phone'] ) ) {
					$block_attributes['showPhone'] = $this->parse_boolean( $attributes['show_phone'] );
				}
				if ( isset( $attributes['show_address'] ) ) {
					$block_attributes['showAddress'] = $this->parse_boolean( $attributes['show_address'] );
				}
				break;

			case 'companies-grid':
				// Map companies grid attributes
				if ( isset( $attributes['columns'] ) ) {
					$block_attributes['columns'] = (int) $attributes['columns'];
				}
				if ( isset( $attributes['category'] ) ) {
					$block_attributes['category'] = $attributes['category'];
				}
				if ( isset( $attributes['linkable'] ) ) {
					$block_attributes['linkable'] = $this->parse_boolean( $attributes['linkable'] );
				}
				break;

			case 'company-gallery':
				// Map company gallery attributes
				if ( isset( $attributes['id'] ) ) {
					$block_attributes['companyId'] = (int) $attributes['id'];
				}
				if ( isset( $attributes['columns'] ) ) {
					$block_attributes['columns'] = (int) $attributes['columns'];
				}
				if ( isset( $attributes['lightbox'] ) ) {
					$block_attributes['lightbox'] = $this->parse_boolean( $attributes['lightbox'] );
				}
				break;

			case 'eventos-proximos':
				// Map eventos proximos attributes
				if ( isset( $attributes['count'] ) ) {
					$block_attributes['count'] = (int) $attributes['count'];
				}
				if ( isset( $attributes['show_past'] ) ) {
					$block_attributes['showPast'] = $this->parse_boolean( $attributes['show_past'] );
				}
				if ( isset( $attributes['layout'] ) ) {
					$block_attributes['layout'] = $attributes['layout'];
				}
				break;

			case 'newsletter-subscribe':
				// Map newsletter subscribe attributes
				if ( isset( $attributes['show_name'] ) ) {
					$block_attributes['showName'] = $this->parse_boolean( $attributes['show_name'] );
				}
				if ( isset( $attributes['button_text'] ) ) {
					$block_attributes['buttonText'] = $attributes['button_text'];
				}
				break;

			case 'arboles-ciudades':
			case 'red-oja':
				// Map microsite attributes
				if ( isset( $attributes['height'] ) ) {
					$block_attributes['height'] = $attributes['height'];
				}
				if ( isset( $attributes['zoom'] ) ) {
					$block_attributes['zoom'] = (int) $attributes['zoom'];
				}
				if ( isset( $attributes['center_lat'] ) ) {
					$block_attributes['centerLat'] = (float) $attributes['center_lat'];
				}
				if ( isset( $attributes['center_lng'] ) ) {
					$block_attributes['centerLng'] = (float) $attributes['center_lng'];
				}
				break;
		}

		// Common attribute mappings
		$common_mapping = [
			'id'    => 'id',
			'class' => 'className',
			'title' => 'title',
			'align' => 'align',
		];

		foreach ( $attributes as $key => $value ) {
			// Skip if already processed in shortcode-specific section
			if ( isset( $block_attributes[ $key ] ) ) {
				continue;
			}

			// Map common attributes
			if ( isset( $common_mapping[ $key ] ) ) {
				$block_key                      = $common_mapping[ $key ];
				$block_attributes[ $block_key ] = $this->convert_value_type( $value );
			}
		}

		return $block_attributes;
	}

	/**
	 * Parse boolean value from string
	 *
	 * @param mixed $value Value to parse
	 * @return bool Boolean value
	 */
	private function parse_boolean( $value ) {
		if ( is_bool( $value ) ) {
			return $value;
		}

		$value = strtolower( trim( $value ) );
		return in_array( $value, [ 'true', '1', 'yes', 'on' ], true );
	}

	/**
	 * Convert value to appropriate type
	 *
	 * @param mixed $value Value to convert
	 * @return mixed Converted value
	 */
	private function convert_value_type( $value ) {
		// Boolean values
		if ( $value === 'true' || $value === '1' ) {
			return true;
		}
		if ( $value === 'false' || $value === '0' ) {
			return false;
		}

		// Numeric values
		if ( is_numeric( $value ) ) {
			// Check if it's an integer or float
			if ( strpos( $value, '.' ) !== false ) {
				return (float) $value;
			}
			return (int) $value;
		}

		// String value
		return $value;
	}

	/**
	 * Check if shortcode can be converted automatically
	 *
	 * @param string $shortcode Shortcode name
	 * @param array  $attributes Shortcode attributes
	 * @return bool True if convertible
	 */
	private function is_convertible( $shortcode, $attributes ) {
		// All mapped shortcodes are convertible by default
		if ( ! isset( $this->shortcode_mapping[ $shortcode ] ) ) {
			return false;
		}

		// Check for specific non-convertible cases
		switch ( $shortcode ) {
			case 'company-gallery':
				// Requires company ID
				if ( ! isset( $attributes['id'] ) || empty( $attributes['id'] ) ) {
					return false;
				}
				break;

			// Add more specific checks as needed
		}

		return true;
	}

	/**
	 * Get conversion statistics
	 *
	 * @return array Statistics
	 */
	public function get_statistics() {
		$stats = [
			'total_shortcodes' => 0,
			'by_type'          => [],
			'posts_affected'   => 0,
			'non_convertible'  => [],
		];

		$args = [
			'post_type'      => [ 'post', 'page' ],
			'posts_per_page' => -1,
			'post_status'    => 'any',
		];

		$posts                 = get_posts( $args );
		$posts_with_shortcodes = [];

		foreach ( $posts as $post ) {
			$has_shortcodes = false;

			// Check for mapped shortcodes
			foreach ( $this->shortcode_mapping as $shortcode => $block_name ) {
				if ( has_shortcode( $post->post_content, $shortcode ) ) {
					++$stats['total_shortcodes'];
					$has_shortcodes = true;

					if ( ! isset( $stats['by_type'][ $shortcode ] ) ) {
						$stats['by_type'][ $shortcode ] = 0;
					}

					++$stats['by_type'][ $shortcode ];
				}
			}

			// Check for non-convertible shortcodes
			$non_convertible = $this->detect_non_convertible_shortcodes( $post->post_content );
			if ( ! empty( $non_convertible ) ) {
				foreach ( $non_convertible as $shortcode ) {
					if ( ! isset( $stats['non_convertible'][ $shortcode ] ) ) {
						$stats['non_convertible'][ $shortcode ] = [
							'count' => 0,
							'posts' => [],
						];
					}

					++$stats['non_convertible'][ $shortcode ]['count'];
					$stats['non_convertible'][ $shortcode ]['posts'][] = [
						'id'    => $post->ID,
						'title' => $post->post_title,
					];
				}

				$has_shortcodes = true;
			}

			if ( $has_shortcodes && ! in_array( $post->ID, $posts_with_shortcodes ) ) {
				$posts_with_shortcodes[] = $post->ID;
			}
		}

		$stats['posts_affected'] = count( $posts_with_shortcodes );

		return $stats;
	}

	/**
	 * Detect non-convertible shortcodes in content
	 *
	 * @param string $content Post content
	 * @return array List of non-convertible shortcodes
	 */
	private function detect_non_convertible_shortcodes( $content ) {
		$non_convertible = [];

		// Find all shortcodes in content
		preg_match_all( '/\[([a-zA-Z0-9_-]+)/', $content, $matches );

		if ( empty( $matches[1] ) ) {
			return $non_convertible;
		}

		$found_shortcodes = array_unique( $matches[1] );

		foreach ( $found_shortcodes as $shortcode ) {
			// Skip if it's a mapped shortcode
			if ( isset( $this->shortcode_mapping[ $shortcode ] ) ) {
				continue;
			}

			// Skip WordPress core shortcodes
			if ( in_array( $shortcode, [ 'caption', 'gallery', 'audio', 'video', 'embed' ] ) ) {
				continue;
			}

			// This is a non-convertible shortcode
			$non_convertible[] = $shortcode;
		}

		return $non_convertible;
	}

	/**
	 * Get list of non-convertible shortcodes with details
	 *
	 * @return array Non-convertible shortcodes report
	 */
	public function get_non_convertible_report() {
		$report = [];

		$args = [
			'post_type'      => [ 'post', 'page' ],
			'posts_per_page' => -1,
			'post_status'    => 'any',
		];

		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			$non_convertible = $this->detect_non_convertible_shortcodes( $post->post_content );

			if ( ! empty( $non_convertible ) ) {
				foreach ( $non_convertible as $shortcode ) {
					if ( ! isset( $report[ $shortcode ] ) ) {
						$report[ $shortcode ] = [
							'shortcode' => $shortcode,
							'count'     => 0,
							'posts'     => [],
						];
					}

					++$report[ $shortcode ]['count'];
					$report[ $shortcode ]['posts'][] = [
						'id'       => $post->ID,
						'title'    => $post->post_title,
						'type'     => $post->post_type,
						'edit_url' => get_edit_post_link( $post->ID ),
					];
				}
			}
		}

		return $report;
	}

	/**
	 * Convert page templates
	 *
	 * @return array Conversion results
	 */
	public function convert_page_templates() {
		$results = [
			'converted_count' => 0,
			'warnings'        => [],
		];

		$this->log( 'Iniciando conversión de templates personalizados...' );

		// Template mapping from old PHP templates to new HTML templates
		$template_mapping = [
			'template-full-width.php' => 'page-full-width',
			'template-landing.php'    => 'page-landing',
			'template-contact.php'    => 'page-contact',
			'template-about.php'      => 'page-about',
			'page-empresas.php'       => 'single-empresas',
			'page-eventos.php'        => 'archive-eventos',
			'single-empresa.php'      => 'single-empresas',
			'single-evento.php'       => 'single-eventos',
		];

		// Get all pages with custom templates
		$args = [
			'post_type'      => 'page',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'meta_query'     => [
				[
					'key'     => '_wp_page_template',
					'value'   => 'default',
					'compare' => '!=',
				],
			],
		];

		$pages = get_posts( $args );

		$this->log( 'Encontradas ' . count( $pages ) . ' páginas con templates personalizados' );

		foreach ( $pages as $page ) {
			$old_template = get_post_meta( $page->ID, '_wp_page_template', true );

			// Check if we have a mapping for this template
			if ( isset( $template_mapping[ $old_template ] ) ) {
				$new_template = $template_mapping[ $old_template ];

				$this->log( "Convirtiendo template '{$old_template}' a '{$new_template}' para página {$page->ID}: {$page->post_title}" );

				if ( ! $this->dry_run ) {
					update_post_meta( $page->ID, '_wp_page_template', $new_template );
					++$results['converted_count'];
				} else {
					$this->log( "DRY-RUN: Se convertiría template para página {$page->ID}" );
					++$results['converted_count'];
				}
			} else {
				$warning = "Template '{$old_template}' no tiene mapeo definido para página {$page->ID}: {$page->post_title}";
				$this->log( $warning, 'warning' );
				$results['warnings'][] = $warning;
			}
		}

		$this->log( "Conversión de templates completada: {$results['converted_count']} templates convertidos" );

		return $results;
	}

	/**
	 * Get warnings
	 *
	 * @return array Warnings
	 */
	public function get_warnings() {
		return $this->warnings;
	}

	/**
	 * Log message
	 *
	 * @param string $message Log message
	 * @param string $level Log level
	 */
	private function log( $message, $level = 'info' ) {
		if ( $this->verbose ) {
			$timestamp = date( 'H:i:s' );
			echo "[{$timestamp}] [ShortcodeConverter] [{$level}] {$message}\n";
		}
	}
}
