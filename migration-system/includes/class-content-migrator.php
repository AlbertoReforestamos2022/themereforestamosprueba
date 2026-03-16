<?php
/**
 * Content Migrator Class
 *
 * Migra contenido del tema legacy al Block Theme.
 * Maneja posts, páginas, Custom Post Types, custom fields y taxonomías.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Content_Migrator class
 *
 * Handles migration of posts, pages, CPTs, and custom fields.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */
class Reforestamos_Content_Migrator {

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
	 * Migration errors
	 *
	 * @var array
	 */
	private $errors = [];

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
	 * Migrate all content
	 *
	 * @return array Migration results
	 */
	public function migrate_all_content() {
		$results = [
			'migrated_count' => 0,
			'errors'         => [],
		];

		$this->log( 'Iniciando migración de contenido...' );

		// Migrate posts and pages
		$posts_result               = $this->migrate_posts_and_pages();
		$results['migrated_count'] += $posts_result['count'];
		$results['errors']          = array_merge( $results['errors'], $posts_result['errors'] );

		// Migrate Custom Post Types
		$cpt_result                 = $this->migrate_custom_post_types();
		$results['migrated_count'] += $cpt_result['count'];
		$results['errors']          = array_merge( $results['errors'], $cpt_result['errors'] );

		// Migrate custom fields
		$custom_fields_result       = $this->migrate_custom_fields();
		$results['migrated_count'] += $custom_fields_result['count'];
		$results['errors']          = array_merge( $results['errors'], $custom_fields_result['errors'] );

		// Migrate taxonomies
		$taxonomies_result          = $this->migrate_taxonomies();
		$results['migrated_count'] += $taxonomies_result['count'];
		$results['errors']          = array_merge( $results['errors'], $taxonomies_result['errors'] );

		// Migrate media
		$media_result               = $this->migrate_media();
		$results['migrated_count'] += $media_result['count'];
		$results['errors']          = array_merge( $results['errors'], $media_result['errors'] );

		// Migrate page templates
		$templates_result           = $this->migrate_page_templates();
		$results['migrated_count'] += $templates_result['count'];
		$results['errors']          = array_merge( $results['errors'], $templates_result['errors'] );

		$this->log( "Migración de contenido completada: {$results['migrated_count']} elementos procesados" );

		return $results;
	}

	/**
	 * Migrate posts and pages
	 *
	 * @return array Migration results
	 */
	private function migrate_posts_and_pages() {
		$this->log( 'Migrando posts y pages...' );

		$results = [
			'count'  => 0,
			'errors' => [],
		];

		// Get all posts and pages
		$args = [
			'post_type'      => [ 'post', 'page' ],
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		];

		$posts = get_posts( $args );

		$this->log( 'Encontrados ' . count( $posts ) . ' posts y páginas para verificar' );

		foreach ( $posts as $post ) {
			try {
				// Verify post data integrity
				$needs_update = false;
				$updates      = [ 'ID' => $post->ID ];

				// Preserve dates
				if ( empty( $post->post_date ) || $post->post_date === '0000-00-00 00:00:00' ) {
					$updates['post_date'] = current_time( 'mysql' );
					$needs_update         = true;
				}

				// Preserve author
				if ( empty( $post->post_author ) || ! get_user_by( 'ID', $post->post_author ) ) {
					$updates['post_author'] = get_current_user_id();
					$needs_update           = true;
				}

				// Verify featured image exists
				$thumbnail_id = get_post_thumbnail_id( $post->ID );
				if ( $thumbnail_id && ! get_post( $thumbnail_id ) ) {
					delete_post_meta( $post->ID, '_thumbnail_id' );
					$this->log( "Eliminada imagen destacada inválida para post {$post->ID}", 'warning' );
				}

				// Verify parent-child relationships for pages
				if ( $post->post_type === 'page' && $post->post_parent > 0 ) {
					$parent = get_post( $post->post_parent );
					if ( ! $parent ) {
						$updates['post_parent'] = 0;
						$needs_update           = true;
						$this->log( "Eliminada relación padre inválida para página {$post->ID}", 'warning' );
					}
				}

				// Update if needed
				if ( $needs_update && ! $this->dry_run ) {
					$updated = wp_update_post( $updates, true );
					if ( is_wp_error( $updated ) ) {
						$error = "Error al actualizar post {$post->ID}: " . $updated->get_error_message();
						$this->log( $error, 'error' );
						$results['errors'][] = $error;
					} else {
						$this->log( "Actualizado post {$post->ID}: {$post->post_title}" );
						++$results['count'];
					}
				} elseif ( $needs_update ) {
					$this->log( "DRY-RUN: Se actualizaría post {$post->ID}: {$post->post_title}" );
					++$results['count'];
				} else {
					++$results['count'];
				}
			} catch ( Exception $e ) {
				$error = "Excepción al migrar post {$post->ID}: " . $e->getMessage();
				$this->log( $error, 'error' );
				$results['errors'][] = $error;
			}
		}

		$this->log( "Posts y páginas verificados: {$results['count']}" );

		return $results;
	}

	/**
	 * Migrate Custom Post Types
	 *
	 * @return array Migration results
	 */
	private function migrate_custom_post_types() {
		$this->log( 'Migrando Custom Post Types...' );

		$results = [
			'count'  => 0,
			'errors' => [],
		];

		// Custom Post Types to migrate
		$cpt_types = [ 'empresas', 'eventos', 'integrantes', 'boletin' ];

		foreach ( $cpt_types as $cpt ) {
			try {
				$args = [
					'post_type'      => $cpt,
					'post_status'    => 'any',
					'posts_per_page' => -1,
					'orderby'        => 'ID',
					'order'          => 'ASC',
				];

				$posts = get_posts( $args );

				if ( empty( $posts ) ) {
					$this->log( "No se encontraron posts de tipo '{$cpt}'" );
					continue;
				}

				$this->log( 'Encontrados ' . count( $posts ) . " posts de tipo '{$cpt}'" );

				foreach ( $posts as $post ) {
					try {
						// Verify post data
						$needs_update = false;
						$updates      = [ 'ID' => $post->ID ];

						// Preserve dates
						if ( empty( $post->post_date ) || $post->post_date === '0000-00-00 00:00:00' ) {
							$updates['post_date'] = current_time( 'mysql' );
							$needs_update         = true;
						}

						// Preserve author
						if ( empty( $post->post_author ) || ! get_user_by( 'ID', $post->post_author ) ) {
							$updates['post_author'] = get_current_user_id();
							$needs_update           = true;
						}

						// Verify metadata exists
						$this->verify_cpt_metadata( $post->ID, $cpt );

						// Update if needed
						if ( $needs_update && ! $this->dry_run ) {
							$updated = wp_update_post( $updates, true );
							if ( is_wp_error( $updated ) ) {
								$error = "Error al actualizar {$cpt} {$post->ID}: " . $updated->get_error_message();
								$this->log( $error, 'error' );
								$results['errors'][] = $error;
							} else {
								$this->log( "Actualizado {$cpt} {$post->ID}: {$post->post_title}" );
								++$results['count'];
							}
						} elseif ( $needs_update ) {
							$this->log( "DRY-RUN: Se actualizaría {$cpt} {$post->ID}: {$post->post_title}" );
							++$results['count'];
						} else {
							++$results['count'];
						}
					} catch ( Exception $e ) {
						$error = "Excepción al migrar {$cpt} {$post->ID}: " . $e->getMessage();
						$this->log( $error, 'error' );
						$results['errors'][] = $error;
					}
				}
			} catch ( Exception $e ) {
				$error = "Excepción al migrar CPT '{$cpt}': " . $e->getMessage();
				$this->log( $error, 'error' );
				$results['errors'][] = $error;
			}
		}

		$this->log( "Custom Post Types verificados: {$results['count']}" );

		return $results;
	}

	/**
	 * Verify CPT metadata
	 *
	 * @param int    $post_id Post ID
	 * @param string $cpt_type CPT type
	 */
	private function verify_cpt_metadata( $post_id, $cpt_type ) {
		switch ( $cpt_type ) {
			case 'empresas':
				// Verify empresa fields
				$required_fields = [ 'empresa_logo', 'empresa_url', 'empresa_descripcion' ];
				break;
			case 'eventos':
				// Verify evento fields
				$required_fields = [ 'evento_fecha', 'evento_ubicacion' ];
				break;
			case 'integrantes':
				// Verify integrante fields
				$required_fields = [ 'integrante_cargo', 'integrante_foto' ];
				break;
			case 'boletin':
				// Verify boletin fields
				$required_fields = [ 'boletin_fecha_publicacion' ];
				break;
			default:
				$required_fields = [];
		}

		foreach ( $required_fields as $field ) {
			$value = get_post_meta( $post_id, $field, true );
			if ( empty( $value ) ) {
				$this->log( "Campo '{$field}' vacío para {$cpt_type} {$post_id}", 'warning' );
			}
		}
	}

	/**
	 * Migrate custom fields structure
	 *
	 * @return array Migration results
	 */
	private function migrate_custom_fields() {
		global $wpdb;

		$this->log( 'Migrando custom fields...' );

		$results = [
			'count'  => 0,
			'errors' => [],
		];

		// Map old field names to new field names
		$field_mapping = [
			// Empresas fields
			'old_empresa_logo'        => 'empresa_logo',
			'old_empresa_website'     => 'empresa_url',
			'old_empresa_descripcion' => 'empresa_descripcion',
			'old_empresa_categoria'   => 'empresa_categoria',

			// Eventos fields
			'old_evento_fecha'        => 'evento_fecha',
			'old_evento_ubicacion'    => 'evento_ubicacion',
			'old_evento_capacidad'    => 'evento_capacidad',

			// Integrantes fields
			'old_integrante_cargo'    => 'integrante_cargo',
			'old_integrante_foto'     => 'integrante_foto',
			'old_integrante_bio'      => 'integrante_biografia',
		];

		foreach ( $field_mapping as $old_key => $new_key ) {
			try {
				$count = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = %s",
						$old_key
					)
				);

				if ( $count > 0 ) {
					$this->log( "Encontrados {$count} registros con clave '{$old_key}'" );

					if ( ! $this->dry_run ) {
						$updated = $wpdb->update(
							$wpdb->postmeta,
							[ 'meta_key' => $new_key ],
							[ 'meta_key' => $old_key ]
						);

						if ( $updated !== false ) {
							$this->log( "Migrados {$updated} registros de '{$old_key}' a '{$new_key}'" );
							$results['count'] += $updated;
						} else {
							$error = "Error al migrar campo '{$old_key}': " . $wpdb->last_error;
							$this->log( $error, 'error' );
							$results['errors'][] = $error;
						}
					} else {
						$this->log( "DRY-RUN: Se migrarían {$count} registros de '{$old_key}' a '{$new_key}'" );
						$results['count'] += $count;
					}
				}
			} catch ( Exception $e ) {
				$error = "Excepción al migrar campo '{$old_key}': " . $e->getMessage();
				$this->log( $error, 'error' );
				$results['errors'][] = $error;
			}
		}

		return $results;
	}

	/**
	 * Migrate page templates
	 *
	 * @return array Migration results
	 */
	private function migrate_page_templates() {
		$this->log( 'Migrando page templates...' );

		$results = [
			'count'  => 0,
			'errors' => [],
		];

		// Map old template files to new Block Theme templates
		$template_mapping = [
			'page-empresas.php'         => 'archive-empresas',
			'page-eventos.php'          => 'archive-eventos',
			'page-contacto.php'         => 'page',
			'page-nosotros.php'         => 'page',
			'page-arboles-ciudades.php' => 'page',
			'page-red-oja.php'          => 'page',
			'single-empresas.php'       => 'single-empresas',
			'single-eventos.php'        => 'single-eventos',
		];

		foreach ( $template_mapping as $old_template => $new_template ) {
			try {
				// Find pages using old template
				$pages = get_posts(
					[
						'post_type'      => 'page',
						'posts_per_page' => -1,
						'meta_key'       => '_wp_page_template',
						'meta_value'     => $old_template,
						'post_status'    => 'any',
					]
				);

				if ( ! empty( $pages ) ) {
					$this->log( 'Encontradas ' . count( $pages ) . " páginas usando template '{$old_template}'" );

					if ( ! $this->dry_run ) {
						foreach ( $pages as $page ) {
							$updated = update_post_meta( $page->ID, '_wp_page_template', $new_template );

							if ( $updated ) {
								$this->log( "Actualizado template para página {$page->ID}: {$page->post_title}" );
								++$results['count'];
							} else {
								$error = "Error al actualizar template para página {$page->ID}";
								$this->log( $error, 'error' );
								$results['errors'][] = $error;
							}
						}
					} else {
						$this->log( 'DRY-RUN: Se actualizarían ' . count( $pages ) . ' páginas' );
						$results['count'] += count( $pages );
					}
				}
			} catch ( Exception $e ) {
				$error = "Excepción al migrar template '{$old_template}': " . $e->getMessage();
				$this->log( $error, 'error' );
				$results['errors'][] = $error;
			}
		}

		return $results;
	}

	/**
	 * Migrate taxonomies
	 *
	 * @return array Migration results
	 */
	private function migrate_taxonomies() {
		$this->log( 'Migrando taxonomías...' );

		$results = [
			'count'  => 0,
			'errors' => [],
		];

		// Taxonomies to migrate
		$taxonomies_to_migrate = [
			'category'          => 'Categorías',
			'post_tag'          => 'Etiquetas',
			'categoria_empresa' => 'Categorías de Empresas',
			'tipo_evento'       => 'Tipos de Eventos',
			'categoria_boletin' => 'Categorías de Boletín',
		];

		foreach ( $taxonomies_to_migrate as $taxonomy => $label ) {
			try {
				if ( ! taxonomy_exists( $taxonomy ) ) {
					$this->log( "Taxonomía '{$taxonomy}' no existe (será creada por el Core Plugin)", 'warning' );
					continue;
				}

				$terms = get_terms(
					[
						'taxonomy'   => $taxonomy,
						'hide_empty' => false,
					]
				);

				if ( is_wp_error( $terms ) ) {
					$error = "Error al obtener términos de '{$taxonomy}': " . $terms->get_error_message();
					$this->log( $error, 'error' );
					$results['errors'][] = $error;
					continue;
				}

				$this->log( "Taxonomía '{$label}': " . count( $terms ) . ' términos encontrados' );

				// Verify term relationships
				foreach ( $terms as $term ) {
					// Get posts with this term
					$posts = get_posts(
						[
							'post_type'      => 'any',
							'posts_per_page' => -1,
							'tax_query'      => [
								[
									'taxonomy' => $taxonomy,
									'field'    => 'term_id',
									'terms'    => $term->term_id,
								],
							],
						]
					);

					if ( ! empty( $posts ) ) {
						$this->log( "  - {$term->name}: " . count( $posts ) . ' posts asociados' );
						++$results['count'];
					}

					// Verify parent-child relationships
					if ( $term->parent > 0 ) {
						$parent = get_term( $term->parent, $taxonomy );
						if ( is_wp_error( $parent ) || ! $parent ) {
							if ( ! $this->dry_run ) {
								wp_update_term( $term->term_id, $taxonomy, [ 'parent' => 0 ] );
								$this->log( "  - Eliminada relación padre inválida para término '{$term->name}'", 'warning' );
							} else {
								$this->log( "  - DRY-RUN: Se eliminaría relación padre inválida para término '{$term->name}'", 'warning' );
							}
						}
					}
				}
			} catch ( Exception $e ) {
				$error = "Excepción al migrar taxonomía '{$taxonomy}': " . $e->getMessage();
				$this->log( $error, 'error' );
				$results['errors'][] = $error;
			}
		}

		$this->log( "Taxonomías verificadas: {$results['count']} términos" );

		return $results;
	}

	/**
	 * Migrate media files
	 *
	 * @return array Migration results
	 */
	private function migrate_media() {
		$this->log( 'Verificando archivos de media...' );

		$results = [
			'count'  => 0,
			'errors' => [],
		];

		// Get all attachments
		$args = [
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		];

		$attachments = get_posts( $args );

		$this->log( 'Encontrados ' . count( $attachments ) . ' archivos de media' );

		$missing_files    = 0;
		$invalid_metadata = 0;

		foreach ( $attachments as $attachment ) {
			try {
				// Get file path
				$file_path = get_attached_file( $attachment->ID );

				// Verify file exists
				if ( ! file_exists( $file_path ) ) {
					++$missing_files;
					$this->log( "Archivo faltante: {$file_path} (ID: {$attachment->ID})", 'warning' );

					// Optionally delete attachment post if file is missing
					if ( ! $this->dry_run ) {
						// Uncomment to delete missing attachments
						// wp_delete_attachment($attachment->ID, true);
						// $this->log("Eliminado attachment {$attachment->ID} (archivo faltante)");
					}
					continue;
				}

				// Verify metadata
				$metadata = wp_get_attachment_metadata( $attachment->ID );

				if ( empty( $metadata ) ) {
					++$invalid_metadata;
					$this->log( "Metadata faltante para attachment {$attachment->ID}", 'warning' );

					// Regenerate metadata for images
					if ( wp_attachment_is_image( $attachment->ID ) && ! $this->dry_run ) {
						require_once ABSPATH . 'wp-admin/includes/image.php';
						$new_metadata = wp_generate_attachment_metadata( $attachment->ID, $file_path );
						wp_update_attachment_metadata( $attachment->ID, $new_metadata );
						$this->log( "Regenerada metadata para attachment {$attachment->ID}" );
					}
				}

				// Update references in post content
				$this->update_media_references( $attachment->ID );

				++$results['count'];

			} catch ( Exception $e ) {
				$error = "Excepción al verificar attachment {$attachment->ID}: " . $e->getMessage();
				$this->log( $error, 'error' );
				$results['errors'][] = $error;
			}
		}

		$this->log( "Media verificada: {$results['count']} archivos" );
		$this->log( "Archivos faltantes: {$missing_files}" );
		$this->log( "Metadata inválida: {$invalid_metadata}" );

		return $results;
	}

	/**
	 * Update media references in post content
	 *
	 * @param int $attachment_id Attachment ID
	 */
	private function update_media_references( $attachment_id ) {
		$file_url = wp_get_attachment_url( $attachment_id );

		if ( ! $file_url ) {
			return;
		}

		// Find posts that reference this media file
		global $wpdb;

		$posts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID, post_content FROM {$wpdb->posts} 
            WHERE post_content LIKE %s 
            AND post_type IN ('post', 'page', 'empresas', 'eventos', 'integrantes', 'boletin')",
				'%' . $wpdb->esc_like( $file_url ) . '%'
			)
		);

		if ( ! empty( $posts ) ) {
			$this->log( "  - Attachment {$attachment_id} referenciado en " . count( $posts ) . ' posts' );
		}
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
			echo "[{$timestamp}] [ContentMigrator] [{$level}] {$message}\n";
		}
	}
}
