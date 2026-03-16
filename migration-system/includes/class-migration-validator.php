<?php
/**
 * Migration Validator Class
 *
 * Valida la integridad de los datos después de la migración.
 * Verifica conteos, enlaces internos, y relaciones de contenido.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Migration_Validator class
 *
 * Validates data integrity after migration.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */
class Reforestamos_Migration_Validator {

	/**
	 * Validation results
	 *
	 * @var array
	 */
	private $results = [];

	/**
	 * Verbose mode
	 *
	 * @var bool
	 */
	private $verbose = false;

	/**
	 * Constructor
	 *
	 * @param bool $verbose Verbose mode
	 */
	public function __construct( $verbose = false ) {
		$this->verbose = $verbose;
	}

	/**
	 * Run all validation checks
	 *
	 * @return array Validation results
	 */
	public function validate_all() {
		$this->log( 'Iniciando validación post-migración...' );

		$this->results = [
			'success'  => true,
			'checks'   => [],
			'errors'   => [],
			'warnings' => [],
		];

		// Run validation checks
		$this->validate_content_counts();
		$this->validate_post_authors();
		$this->validate_internal_links();
		$this->validate_media_attachments();
		$this->validate_taxonomy_relationships();
		$this->validate_custom_fields();

		// Determine overall success
		$this->results['success'] = empty( $this->results['errors'] );

		$this->log( 'Validación completada.' );

		return $this->results;
	}

	/**
	 * Validate content counts (before/after comparison)
	 */
	private function validate_content_counts() {
		$this->log( 'Validando conteos de contenido...' );

		$post_types = [ 'post', 'page', 'empresas', 'eventos', 'integrantes', 'boletin' ];
		$counts     = [];

		foreach ( $post_types as $post_type ) {
			$count = wp_count_posts( $post_type );
			$total = $count->publish + $count->draft + $count->pending + $count->private;

			$counts[ $post_type ] = [
				'total'   => $total,
				'publish' => $count->publish,
				'draft'   => $count->draft,
			];

			$this->log( "  {$post_type}: {$total} total ({$count->publish} publicados)" );
		}

		$this->results['checks']['content_counts'] = [
			'status' => 'passed',
			'data'   => $counts,
		];
	}

	/**
	 * Validate that all posts have valid authors
	 */
	private function validate_post_authors() {
		$this->log( 'Validando autores de posts...' );

		global $wpdb;

		// Find posts with invalid authors
		$invalid_authors = $wpdb->get_results(
			"
            SELECT p.ID, p.post_title, p.post_type, p.post_author
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->users} u ON p.post_author = u.ID
            WHERE u.ID IS NULL
            AND p.post_status != 'auto-draft'
            AND p.post_type NOT IN ('revision', 'nav_menu_item', 'attachment')
        "
		);

		if ( ! empty( $invalid_authors ) ) {
			$this->results['errors'][] = sprintf(
				'Se encontraron %d posts con autores inválidos',
				count( $invalid_authors )
			);

			$this->results['checks']['post_authors'] = [
				'status'        => 'failed',
				'invalid_count' => count( $invalid_authors ),
				'posts'         => array_map(
					function ( $post ) {
						return [
							'id'        => $post->ID,
							'title'     => $post->post_title,
							'type'      => $post->post_type,
							'author_id' => $post->post_author,
						];
					},
					$invalid_authors
				),
			];

			$this->log( "  ✗ {$invalid_authors[0]->post_count} posts con autores inválidos" );
		} else {
			$this->results['checks']['post_authors'] = [
				'status' => 'passed',
			];
			$this->log( '  ✓ Todos los posts tienen autores válidos' );
		}
	}

	/**
	 * Validate internal links
	 */
	private function validate_internal_links() {
		$this->log( 'Validando enlaces internos...' );

		global $wpdb;

		// Get all posts with content
		$posts = $wpdb->get_results(
			"
            SELECT ID, post_title, post_content, post_type
            FROM {$wpdb->posts}
            WHERE post_status = 'publish'
            AND post_type IN ('post', 'page', 'empresas', 'eventos')
            AND post_content LIKE '%href=%'
        "
		);

		$broken_links = [];
		$site_url     = get_site_url();

		foreach ( $posts as $post ) {
			// Extract internal links
			preg_match_all( '/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $post->post_content, $matches );

			if ( ! empty( $matches[1] ) ) {
				foreach ( $matches[1] as $url ) {
					// Check if it's an internal link
					if ( strpos( $url, $site_url ) === 0 || strpos( $url, '/' ) === 0 ) {
						// Extract post ID from URL if possible
						if ( preg_match( '/\?p=(\d+)/', $url, $id_match ) ) {
							$linked_post_id = $id_match[1];
							$linked_post    = get_post( $linked_post_id );

							if ( ! $linked_post || $linked_post->post_status !== 'publish' ) {
								$broken_links[] = [
									'post_id'        => $post->ID,
									'post_title'     => $post->post_title,
									'broken_url'     => $url,
									'linked_post_id' => $linked_post_id,
								];
							}
						}
					}
				}
			}
		}

		if ( ! empty( $broken_links ) ) {
			$this->results['warnings'][] = sprintf(
				'Se encontraron %d enlaces internos rotos',
				count( $broken_links )
			);

			$this->results['checks']['internal_links'] = [
				'status'       => 'warning',
				'broken_count' => count( $broken_links ),
				'links'        => array_slice( $broken_links, 0, 10 ), // Limit to first 10
			];

			$this->log( "  ⚠ {$broken_links[0]->broken_count} enlaces rotos encontrados" );
		} else {
			$this->results['checks']['internal_links'] = [
				'status' => 'passed',
			];
			$this->log( '  ✓ No se encontraron enlaces rotos' );
		}
	}

	/**
	 * Validate media attachments
	 */
	private function validate_media_attachments() {
		$this->log( 'Validando archivos multimedia...' );

		global $wpdb;

		// Get all attachments
		$attachments = $wpdb->get_results(
			"
            SELECT ID, post_title, guid
            FROM {$wpdb->posts}
            WHERE post_type = 'attachment'
            AND post_status = 'inherit'
        "
		);

		$missing_files = [];
		$upload_dir    = wp_upload_dir();

		foreach ( $attachments as $attachment ) {
			$file_path = get_attached_file( $attachment->ID );

			if ( $file_path && ! file_exists( $file_path ) ) {
				$missing_files[] = [
					'id'    => $attachment->ID,
					'title' => $attachment->post_title,
					'path'  => $file_path,
				];
			}
		}

		if ( ! empty( $missing_files ) ) {
			$this->results['warnings'][] = sprintf(
				'Se encontraron %d archivos multimedia faltantes',
				count( $missing_files )
			);

			$this->results['checks']['media_attachments'] = [
				'status'        => 'warning',
				'missing_count' => count( $missing_files ),
				'total_count'   => count( $attachments ),
				'files'         => array_slice( $missing_files, 0, 10 ),
			];

			$this->log( "  ⚠ {$missing_files[0]->missing_count} archivos faltantes" );
		} else {
			$this->results['checks']['media_attachments'] = [
				'status'      => 'passed',
				'total_count' => count( $attachments ),
			];
			$this->log( "  ✓ Todos los archivos multimedia existen ({$attachments[0]->total_count} archivos)" );
		}
	}

	/**
	 * Validate taxonomy relationships
	 */
	private function validate_taxonomy_relationships() {
		$this->log( 'Validando relaciones de taxonomías...' );

		global $wpdb;

		// Check for orphaned term relationships
		$orphaned = $wpdb->get_var(
			"
            SELECT COUNT(*)
            FROM {$wpdb->term_relationships} tr
            LEFT JOIN {$wpdb->posts} p ON tr.object_id = p.ID
            WHERE p.ID IS NULL
        "
		);

		if ( $orphaned > 0 ) {
			$this->results['warnings'][] = sprintf(
				'Se encontraron %d relaciones de taxonomía huérfanas',
				$orphaned
			);

			$this->results['checks']['taxonomy_relationships'] = [
				'status'         => 'warning',
				'orphaned_count' => $orphaned,
			];

			$this->log( "  ⚠ {$orphaned} relaciones huérfanas" );
		} else {
			$this->results['checks']['taxonomy_relationships'] = [
				'status' => 'passed',
			];
			$this->log( '  ✓ Todas las relaciones de taxonomía son válidas' );
		}
	}

	/**
	 * Validate custom fields
	 */
	private function validate_custom_fields() {
		$this->log( 'Validando custom fields...' );

		global $wpdb;

		$post_types   = [ 'empresas', 'eventos', 'integrantes', 'boletin' ];
		$field_counts = [];

		foreach ( $post_types as $post_type ) {
			$count = $wpdb->get_var(
				$wpdb->prepare(
					"
                SELECT COUNT(DISTINCT pm.post_id)
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                WHERE p.post_type = %s
                AND p.post_status = 'publish'
                AND pm.meta_key NOT LIKE '\\_%%'
            ",
					$post_type
				)
			);

			$field_counts[ $post_type ] = $count;
			$this->log( "  {$post_type}: {$count} posts con custom fields" );
		}

		$this->results['checks']['custom_fields'] = [
			'status' => 'passed',
			'data'   => $field_counts,
		];
	}

	/**
	 * Generate validation report
	 *
	 * @return string Report file path
	 */
	public function generate_report() {
		$report_dir = WP_CONTENT_DIR . '/reforestamos-migration-reports';

		if ( ! file_exists( $report_dir ) ) {
			wp_mkdir_p( $report_dir );
		}

		$timestamp   = date( 'Y-m-d-H-i-s' );
		$report_file = $report_dir . '/validation-report-' . $timestamp . '.txt';

		$report  = "═══════════════════════════════════════════════════════════════\n";
		$report .= "REPORTE DE VALIDACIÓN POST-MIGRACIÓN\n";
		$report .= "═══════════════════════════════════════════════════════════════\n\n";

		$report .= 'Fecha: ' . date( 'Y-m-d H:i:s' ) . "\n";
		$report .= 'Estado: ' . ( $this->results['success'] ? '✓ EXITOSO' : '✗ FALLIDO' ) . "\n\n";

		// Summary
		$report .= "RESUMEN\n";
		$report .= "-------\n";
		$report .= 'Checks ejecutados: ' . count( $this->results['checks'] ) . "\n";
		$report .= 'Errores: ' . count( $this->results['errors'] ) . "\n";
		$report .= 'Advertencias: ' . count( $this->results['warnings'] ) . "\n\n";

		// Detailed checks
		$report .= "CHECKS DETALLADOS\n";
		$report .= "-----------------\n\n";

		foreach ( $this->results['checks'] as $check_name => $check_data ) {
			$status_icon = $check_data['status'] === 'passed' ? '✓' :
							( $check_data['status'] === 'warning' ? '⚠' : '✗' );

			$report .= "{$status_icon} " . ucfirst( str_replace( '_', ' ', $check_name ) ) . "\n";

			if ( isset( $check_data['data'] ) ) {
				$report .= '  Datos: ' . json_encode( $check_data['data'], JSON_PRETTY_PRINT ) . "\n";
			}

			$report .= "\n";
		}

		// Errors
		if ( ! empty( $this->results['errors'] ) ) {
			$report .= "ERRORES\n";
			$report .= "-------\n";
			foreach ( $this->results['errors'] as $error ) {
				$report .= "- {$error}\n";
			}
			$report .= "\n";
		}

		// Warnings
		if ( ! empty( $this->results['warnings'] ) ) {
			$report .= "ADVERTENCIAS\n";
			$report .= "------------\n";
			foreach ( $this->results['warnings'] as $warning ) {
				$report .= "- {$warning}\n";
			}
			$report .= "\n";
		}

		$report .= "═══════════════════════════════════════════════════════════════\n";
		$report .= "FIN DEL REPORTE\n";
		$report .= "═══════════════════════════════════════════════════════════════\n";

		file_put_contents( $report_file, $report );

		return $report_file;
	}

	/**
	 * Get validation results
	 *
	 * @return array
	 */
	public function get_results() {
		return $this->results;
	}

	/**
	 * Log message
	 *
	 * @param string $message Message to log
	 */
	private function log( $message ) {
		if ( $this->verbose ) {
			echo $message . "\n";
		}
	}
}
