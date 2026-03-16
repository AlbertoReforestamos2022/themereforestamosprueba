<?php
/**
 * Migration Manager Class
 *
 * Gestiona el proceso completo de migración del tema legacy al Block Theme.
 * Coordina el backup, migración de contenido, conversión de shortcodes y validación.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Migration_Manager class
 *
 * Orchestrates the content migration process.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */
class Reforestamos_Migration_Manager {

	/**
	 * Singleton instance
	 *
	 * @var Reforestamos_Migration_Manager
	 */
	private static $instance = null;

	/**
	 * Migration log
	 *
	 * @var array
	 */
	private $log = [];

	/**
	 * Detailed statistics
	 *
	 * @var array
	 */
	private $statistics = [
		'posts'         => [
			'migrated' => 0,
			'failed'   => 0,
		],
		'pages'         => [
			'migrated' => 0,
			'failed'   => 0,
		],
		'empresas'      => [
			'migrated' => 0,
			'failed'   => 0,
		],
		'eventos'       => [
			'migrated' => 0,
			'failed'   => 0,
		],
		'integrantes'   => [
			'migrated' => 0,
			'failed'   => 0,
		],
		'boletin'       => [
			'migrated' => 0,
			'failed'   => 0,
		],
		'custom_fields' => [
			'migrated' => 0,
			'failed'   => 0,
		],
		'taxonomies'    => [
			'migrated' => 0,
			'failed'   => 0,
		],
		'media'         => [
			'migrated' => 0,
			'failed'   => 0,
		],
		'shortcodes'    => [
			'converted' => 0,
			'failed'    => 0,
			'manual'    => 0,
		],
		'templates'     => [
			'converted' => 0,
			'failed'    => 0,
		],
	];

	/**
	 * Error logger instance
	 *
	 * @var Reforestamos_Error_Logger
	 */
	private $error_logger;

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
	 * Get singleton instance
	 *
	 * @return Reforestamos_Migration_Manager
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
		// Private constructor for singleton
		require_once plugin_dir_path( __FILE__ ) . 'class-error-logger.php';
		$this->error_logger = new Reforestamos_Error_Logger();
	}

	/**
	 * Run full migration process
	 *
	 * @param array $options Migration options
	 * @return array Migration results
	 */
	public function run_migration( $options = [] ) {
		$this->dry_run  = isset( $options['dry_run'] ) ? $options['dry_run'] : false;
		$this->verbose  = isset( $options['verbose'] ) ? $options['verbose'] : false;
		$backup_enabled = isset( $options['backup'] ) ? $options['backup'] : true;

		$this->log( '=== Iniciando migración de Reforestamos ===' );

		if ( $this->dry_run ) {
			$this->log( 'MODO DRY-RUN: No se realizarán cambios permanentes' );
		}

		$results = [
			'success'              => false,
			'backup_file'          => null,
			'migrated_posts'       => 0,
			'converted_shortcodes' => 0,
			'errors'               => [],
			'warnings'             => [],
		];

		try {
			// Step 1: Create backup
			if ( $backup_enabled && ! $this->dry_run ) {
				$this->log( 'Paso 1: Creando backup de la base de datos...' );
				$results['backup_file'] = $this->create_backup();
				$this->log( "Backup creado: {$results['backup_file']}" );
			} else {
				$this->log( 'Paso 1: Backup omitido (dry-run o deshabilitado)' );
			}

			// Step 2: Migrate content
			$this->log( 'Paso 2: Migrando contenido...' );
			$content_migrator          = new Reforestamos_Content_Migrator( $this->dry_run, $this->verbose );
			$migration_result          = $content_migrator->migrate_all_content();
			$results['migrated_posts'] = $migration_result['migrated_count'];
			$results['errors']         = array_merge( $results['errors'], $migration_result['errors'] );

			// Step 3: Convert shortcodes
			$this->log( 'Paso 3: Convirtiendo shortcodes a bloques...' );
			$shortcode_converter             = new Reforestamos_Shortcode_Converter( $this->dry_run, $this->verbose );
			$conversion_result               = $shortcode_converter->convert_all_shortcodes();
			$results['converted_shortcodes'] = $conversion_result['converted_count'];
			$results['warnings']             = array_merge( $results['warnings'], $conversion_result['warnings'] );

			// Step 3.1: Convert page templates
			$this->log( 'Paso 3.1: Convirtiendo templates personalizados...' );
			$template_result                = $shortcode_converter->convert_page_templates();
			$results['converted_templates'] = $template_result['converted_count'];
			$results['warnings']            = array_merge( $results['warnings'], $template_result['warnings'] );

			// Step 4: Generate report
			$this->log( 'Paso 4: Generando reporte de migración...' );
			$report_file            = $this->generate_report( $results );
			$results['report_file'] = $report_file;

			// Step 5: Validate migration (if not dry-run)
			if ( ! $this->dry_run ) {
				$this->log( 'Paso 5: Validando integridad de datos migrados...' );
				require_once plugin_dir_path( __FILE__ ) . 'class-migration-validator.php';
				$validator             = new Reforestamos_Migration_Validator( $this->verbose );
				$validation_results    = $validator->validate_all();
				$results['validation'] = $validation_results;

				if ( ! $validation_results['success'] ) {
					$this->log( 'ADVERTENCIA: La validación detectó problemas', 'warning' );
				}

				$validation_report = $validator->generate_report();
				$this->log( "Reporte de validación: {$validation_report}" );
			}

			$results['success'] = true;
			$this->log( '=== Migración completada exitosamente ===' );

		} catch ( Exception $e ) {
			$results['success']  = false;
			$results['errors'][] = $e->getMessage();
			$this->log( 'ERROR: ' . $e->getMessage(), 'error' );
		}

		return $results;
	}

	/**
	 * Create database backup
	 *
	 * @return string Backup file path
	 * @throws Exception If backup fails
	 */
	public function create_backup() {
		$backup_manager = new Reforestamos_Backup_Manager();
		$backup_info    = $backup_manager->create_backup( 'Migración automática' );

		$this->log( "Backup creado exitosamente: {$backup_info['filepath']} ({$backup_info['size_formatted']})" );

		return $backup_info['filepath'];
	}

	/**
	 * Generate migration report
	 *
	 * @param array $results Migration results
	 * @return string Report file path
	 */
	private function generate_report( $results ) {
		$report_dir = WP_CONTENT_DIR . '/reforestamos-migration-reports';

		if ( ! file_exists( $report_dir ) ) {
			wp_mkdir_p( $report_dir );
		}

		$timestamp = date( 'Y-m-d-H-i-s' );

		// Generate both text and JSON reports
		$text_report_file = $report_dir . '/migration-report-' . $timestamp . '.txt';
		$json_report_file = $report_dir . '/migration-report-' . $timestamp . '.json';

		// Generate text report
		$text_report = $this->generate_text_report( $results );
		file_put_contents( $text_report_file, $text_report );

		// Generate JSON report
		$json_report = $this->generate_json_report( $results );
		file_put_contents( $json_report_file, json_encode( $json_report, JSON_PRETTY_PRINT ) );

		$this->log( 'Reportes generados:' );
		$this->log( "  - Texto: {$text_report_file}" );
		$this->log( "  - JSON: {$json_report_file}" );

		return $text_report_file;
	}

	/**
	 * Generate text format report
	 *
	 * @param array $results Migration results
	 * @return string Report content
	 */
	private function generate_text_report( $results ) {
		$report  = "╔════════════════════════════════════════════════════════════════╗\n";
		$report .= "║     REPORTE DE MIGRACIÓN REFORESTAMOS - BLOCK THEME           ║\n";
		$report .= "╚════════════════════════════════════════════════════════════════╝\n\n";

		$report .= 'Fecha: ' . date( 'Y-m-d H:i:s' ) . "\n";
		$report .= 'Modo: ' . ( $this->dry_run ? 'DRY-RUN (Simulación)' : 'PRODUCCIÓN' ) . "\n";
		$report .= 'Estado: ' . ( $results['success'] ? '✓ EXITOSO' : '✗ FALLIDO' ) . "\n\n";

		// Summary statistics
		$report .= "═══════════════════════════════════════════════════════════════\n";
		$report .= "RESUMEN GENERAL\n";
		$report .= "═══════════════════════════════════════════════════════════════\n\n";

		$total_migrated = array_sum( array_column( $this->statistics, 'migrated' ) );
		$total_failed   = array_sum( array_column( $this->statistics, 'failed' ) );

		$report .= sprintf( "Total de items migrados: %d\n", $total_migrated );
		$report .= sprintf( "Total de items fallidos: %d\n", $total_failed );
		$report .= sprintf( "Shortcodes convertidos: %d\n", $this->statistics['shortcodes']['converted'] );
		$report .= sprintf( "Templates convertidos: %d\n\n", $this->statistics['templates']['converted'] );

		// Detailed statistics by content type
		$report .= "═══════════════════════════════════════════════════════════════\n";
		$report .= "ESTADÍSTICAS DETALLADAS POR TIPO DE CONTENIDO\n";
		$report .= "═══════════════════════════════════════════════════════════════\n\n";

		$content_types = [
			'posts'         => 'Posts',
			'pages'         => 'Páginas',
			'empresas'      => 'Empresas',
			'eventos'       => 'Eventos',
			'integrantes'   => 'Integrantes',
			'boletin'       => 'Boletín',
			'custom_fields' => 'Custom Fields',
			'taxonomies'    => 'Taxonomías',
			'media'         => 'Media',
		];

		foreach ( $content_types as $key => $label ) {
			$migrated = $this->statistics[ $key ]['migrated'];
			$failed   = $this->statistics[ $key ]['failed'];
			$total    = $migrated + $failed;

			if ( $total > 0 ) {
				$success_rate = $total > 0 ? round( ( $migrated / $total ) * 100, 2 ) : 0;
				$report      .= sprintf(
					"%-20s: %4d migrados, %4d fallidos (%s%%)\n",
					$label,
					$migrated,
					$failed,
					$success_rate
				);
			}
		}

		// Shortcode conversion details
		$report .= "\n═══════════════════════════════════════════════════════════════\n";
		$report .= "CONVERSIÓN DE SHORTCODES\n";
		$report .= "═══════════════════════════════════════════════════════════════\n\n";

		$report .= sprintf( "Convertidos automáticamente: %d\n", $this->statistics['shortcodes']['converted'] );
		$report .= sprintf( "Requieren conversión manual: %d\n", $this->statistics['shortcodes']['manual'] );
		$report .= sprintf( "Fallidos: %d\n\n", $this->statistics['shortcodes']['failed'] );

		// Backup information
		if ( ! empty( $results['backup_file'] ) ) {
			$report .= "═══════════════════════════════════════════════════════════════\n";
			$report .= "INFORMACIÓN DE BACKUP\n";
			$report .= "═══════════════════════════════════════════════════════════════\n\n";
			$report .= "Archivo: {$results['backup_file']}\n";
			if ( file_exists( $results['backup_file'] ) ) {
				$size    = size_format( filesize( $results['backup_file'] ) );
				$report .= "Tamaño: {$size}\n";
			}
			$report .= "\n";
		}

		// Errors
		if ( ! empty( $results['errors'] ) ) {
			$report .= "═══════════════════════════════════════════════════════════════\n";
			$report .= 'ERRORES (' . count( $results['errors'] ) . ")\n";
			$report .= "═══════════════════════════════════════════════════════════════\n\n";
			foreach ( $results['errors'] as $i => $error ) {
				$report .= sprintf( "%3d. %s\n", $i + 1, $error );
			}
			$report .= "\n";
		}

		// Warnings
		if ( ! empty( $results['warnings'] ) ) {
			$report .= "═══════════════════════════════════════════════════════════════\n";
			$report .= 'ADVERTENCIAS (' . count( $results['warnings'] ) . ")\n";
			$report .= "═══════════════════════════════════════════════════════════════\n\n";
			foreach ( $results['warnings'] as $i => $warning ) {
				$report .= sprintf( "%3d. %s\n", $i + 1, $warning );
			}
			$report .= "\n";
		}

		// Detailed log
		$report .= "═══════════════════════════════════════════════════════════════\n";
		$report .= "LOG DETALLADO\n";
		$report .= "═══════════════════════════════════════════════════════════════\n\n";
		$report .= implode( "\n", $this->log );
		$report .= "\n\n";

		$report .= "═══════════════════════════════════════════════════════════════\n";
		$report .= "FIN DEL REPORTE\n";
		$report .= "═══════════════════════════════════════════════════════════════\n";

		return $report;
	}

	/**
	 * Generate JSON format report
	 *
	 * @param array $results Migration results
	 * @return array Report data
	 */
	private function generate_json_report( $results ) {
		return [
			'metadata'   => [
				'timestamp'  => date( 'c' ),
				'date_human' => date( 'Y-m-d H:i:s' ),
				'mode'       => $this->dry_run ? 'dry-run' : 'production',
				'success'    => $results['success'],
			],
			'summary'    => [
				'total_migrated'       => array_sum( array_column( $this->statistics, 'migrated' ) ),
				'total_failed'         => array_sum( array_column( $this->statistics, 'failed' ) ),
				'shortcodes_converted' => $this->statistics['shortcodes']['converted'],
				'templates_converted'  => $this->statistics['templates']['converted'],
			],
			'statistics' => $this->statistics,
			'backup'     => [
				'file' => $results['backup_file'] ?? null,
				'size' => ! empty( $results['backup_file'] ) && file_exists( $results['backup_file'] )
					? filesize( $results['backup_file'] )
					: null,
			],
			'errors'     => $results['errors'] ?? [],
			'warnings'   => $results['warnings'] ?? [],
			'log'        => $this->log,
		];
	}

	/**
	 * Add entry to migration log
	 *
	 * @param string $message Log message
	 * @param string $level Log level (info, warning, error, critical)
	 * @param array  $context Additional context
	 */
	public function log( $message, $level = 'info', $context = [] ) {
		$timestamp = date( 'Y-m-d H:i:s' );
		$log_entry = "[{$timestamp}] [{$level}] {$message}";

		$this->log[] = $log_entry;

		// Log to error logger if it's an error or warning
		if ( in_array( $level, [ 'error', 'warning', 'critical' ] ) ) {
			$this->error_logger->log_error( $message, $level, $context );
		}

		if ( $this->verbose ) {
			echo $log_entry . "\n";
		}
	}

	/**
	 * Get migration log
	 *
	 * @return array
	 */
	public function get_log() {
		return $this->log;
	}

	/**
	 * Update migration statistics
	 *
	 * @param string $type Content type (posts, pages, empresas, etc.)
	 * @param string $status Status (migrated, failed)
	 * @param int    $count Count to add
	 */
	public function update_statistics( $type, $status, $count = 1 ) {
		if ( isset( $this->statistics[ $type ][ $status ] ) ) {
			$this->statistics[ $type ][ $status ] += $count;
		}
	}

	/**
	 * Get migration statistics
	 *
	 * @return array
	 */
	public function get_statistics() {
		return $this->statistics;
	}

	/**
	 * Get error logger instance
	 *
	 * @return Reforestamos_Error_Logger
	 */
	public function get_error_logger() {
		return $this->error_logger;
	}
}
