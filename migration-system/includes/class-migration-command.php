<?php
/**
 * WP-CLI Migration Command
 *
 * Proporciona comandos WP-CLI para ejecutar la migración del tema legacy
 * al Block Theme con opciones de dry-run, backup y verbose.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) || ! defined( 'WP_CLI' ) ) {
	return;
}

/**
 * Comandos de migración para Reforestamos
 */
class Reforestamos_Migration_Command {

	/**
	 * Ejecuta la migración completa del tema legacy al Block Theme
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Ejecuta la migración en modo simulación sin realizar cambios permanentes
	 *
	 * [--backup]
	 * : Crea un backup de la base de datos antes de migrar (activado por defecto)
	 *
	 * [--no-backup]
	 * : Omite la creación del backup (no recomendado)
	 *
	 * [--verbose]
	 * : Muestra información detallada durante la migración
	 *
	 * ## EXAMPLES
	 *
	 *     # Ejecutar migración en modo dry-run (simulación)
	 *     $ wp reforestamos migrate --dry-run
	 *
	 *     # Ejecutar migración completa con backup
	 *     $ wp reforestamos migrate
	 *
	 *     # Ejecutar migración sin backup (no recomendado)
	 *     $ wp reforestamos migrate --no-backup
	 *
	 *     # Ejecutar migración con salida detallada
	 *     $ wp reforestamos migrate --verbose
	 *
	 *     # Combinar opciones
	 *     $ wp reforestamos migrate --dry-run --verbose
	 *
	 * @when after_wp_load
	 */
	public function migrate( $args, $assoc_args ) {
		$dry_run = WP_CLI\Utils\get_flag_value( $assoc_args, 'dry-run', false );
		$backup  = ! WP_CLI\Utils\get_flag_value( $assoc_args, 'no-backup', false );
		$verbose = WP_CLI\Utils\get_flag_value( $assoc_args, 'verbose', false );

		WP_CLI::line( '' );
		WP_CLI::line( WP_CLI::colorize( '%G=== MIGRACIÓN REFORESTAMOS ===%n' ) );
		WP_CLI::line( '' );

		if ( $dry_run ) {
			WP_CLI::warning( 'MODO DRY-RUN: No se realizarán cambios permanentes' );
		}

		if ( ! $backup ) {
			WP_CLI::warning( 'Backup deshabilitado - Los cambios no podrán revertirse fácilmente' );
			WP_CLI::confirm( '¿Está seguro de continuar sin backup?' );
		}

		// Verify prerequisites
		WP_CLI::line( 'Verificando requisitos previos...' );
		$this->verify_prerequisites();

		// Initialize migration manager
		$manager = Reforestamos_Migration_Manager::get_instance();

		// Run migration
		$options = [
			'dry_run' => $dry_run,
			'backup'  => $backup,
			'verbose' => $verbose,
		];

		try {
			$results = $manager->run_migration( $options );

			// Display results
			WP_CLI::line( '' );
			WP_CLI::line( WP_CLI::colorize( '%G=== RESULTADOS DE LA MIGRACIÓN ===%n' ) );
			WP_CLI::line( '' );

			if ( $results['success'] ) {
				WP_CLI::success( 'Migración completada exitosamente' );
			} else {
				WP_CLI::error( 'La migración falló', false );
			}

			WP_CLI::line( "Posts migrados: {$results['migrated_posts']}" );
			WP_CLI::line( "Shortcodes convertidos: {$results['converted_shortcodes']}" );

			if ( ! empty( $results['backup_file'] ) ) {
				WP_CLI::line( WP_CLI::colorize( "%GBackup creado:%n {$results['backup_file']}" ) );
			}

			if ( ! empty( $results['report_file'] ) ) {
				WP_CLI::line( WP_CLI::colorize( "%GReporte generado:%n {$results['report_file']}" ) );
			}

			// Display errors
			if ( ! empty( $results['errors'] ) ) {
				WP_CLI::line( '' );
				WP_CLI::warning( 'Se encontraron ' . count( $results['errors'] ) . ' errores:' );
				foreach ( $results['errors'] as $error ) {
					WP_CLI::line( '  - ' . $error );
				}
			}

			// Display warnings
			if ( ! empty( $results['warnings'] ) ) {
				WP_CLI::line( '' );
				WP_CLI::warning( 'Se encontraron ' . count( $results['warnings'] ) . ' advertencias:' );
				foreach ( array_slice( $results['warnings'], 0, 5 ) as $warning ) {
					WP_CLI::line( '  - ' . $warning );
				}

				if ( count( $results['warnings'] ) > 5 ) {
					WP_CLI::line( '  ... y ' . ( count( $results['warnings'] ) - 5 ) . ' más' );
				}
			}

			WP_CLI::line( '' );

			if ( $dry_run ) {
				WP_CLI::line( WP_CLI::colorize( '%YEsto fue una simulación. Ejecute sin --dry-run para aplicar los cambios.%n' ) );
			} else {
				WP_CLI::line( WP_CLI::colorize( '%GRevise el reporte completo para más detalles.%n' ) );
			}
		} catch ( Exception $e ) {
			WP_CLI::error( 'Error durante la migración: ' . $e->getMessage() );
		}
	}

	/**
	 * Muestra estadísticas sobre los shortcodes que serán convertidos
	 *
	 * ## EXAMPLES
	 *
	 *     # Ver estadísticas de shortcodes
	 *     $ wp reforestamos stats
	 *
	 * @when after_wp_load
	 */
	public function stats( $args, $assoc_args ) {
		WP_CLI::line( '' );
		WP_CLI::line( WP_CLI::colorize( '%G=== ESTADÍSTICAS DE MIGRACIÓN ===%n' ) );
		WP_CLI::line( '' );

		$converter = new Reforestamos_Shortcode_Converter( true, false );
		$stats     = $converter->get_statistics();

		WP_CLI::line( "Total de shortcodes encontrados: {$stats['total_shortcodes']}" );
		WP_CLI::line( "Posts afectados: {$stats['posts_affected']}" );
		WP_CLI::line( '' );

		if ( ! empty( $stats['by_type'] ) ) {
			WP_CLI::line( 'Shortcodes convertibles por tipo:' );

			foreach ( $stats['by_type'] as $shortcode => $count ) {
				WP_CLI::line( "  [{$shortcode}]: {$count}" );
			}
			WP_CLI::line( '' );
		}

		if ( ! empty( $stats['non_convertible'] ) ) {
			WP_CLI::warning( 'Shortcodes NO convertibles encontrados:' );

			foreach ( $stats['non_convertible'] as $shortcode => $data ) {
				WP_CLI::line( "  [{$shortcode}]: {$data['count']} ocurrencias" );
			}

			WP_CLI::line( '' );
			WP_CLI::line( 'Ejecute "wp reforestamos non-convertible" para ver detalles completos' );
		} else {
			WP_CLI::success( 'Todos los shortcodes encontrados son convertibles' );
		}

		WP_CLI::line( '' );
	}

	/**
	 * Muestra reporte detallado de shortcodes no convertibles
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Formato de salida (table, json, csv)
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - json
	 *   - csv
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     # Ver reporte de shortcodes no convertibles
	 *     $ wp reforestamos non-convertible
	 *
	 *     # Exportar a JSON
	 *     $ wp reforestamos non-convertible --format=json
	 *
	 * @when after_wp_load
	 */
	public function non_convertible( $args, $assoc_args ) {
		$format = WP_CLI\Utils\get_flag_value( $assoc_args, 'format', 'table' );

		$converter = new Reforestamos_Shortcode_Converter( true, false );
		$report    = $converter->get_non_convertible_report();

		if ( empty( $report ) ) {
			WP_CLI::success( 'No se encontraron shortcodes no convertibles' );
			return;
		}

		WP_CLI::line( '' );
		WP_CLI::warning( 'Shortcodes que requieren conversión manual:' );
		WP_CLI::line( '' );

		if ( $format === 'json' ) {
			WP_CLI::line( json_encode( $report, JSON_PRETTY_PRINT ) );
			return;
		}

		if ( $format === 'csv' ) {
			WP_CLI::line( 'Shortcode,Count,Post ID,Post Title,Post Type,Edit URL' );
			foreach ( $report as $data ) {
				foreach ( $data['posts'] as $post ) {
					WP_CLI::line(
						sprintf(
							'"%s",%d,%d,"%s","%s","%s"',
							$data['shortcode'],
							$data['count'],
							$post['id'],
							str_replace( '"', '""', $post['title'] ),
							$post['type'],
							$post['edit_url']
						)
					);
				}
			}
			return;
		}

		// Table format (default)
		foreach ( $report as $data ) {
			WP_CLI::line( WP_CLI::colorize( "%Y[{$data['shortcode']}]%n - {$data['count']} ocurrencias" ) );
			WP_CLI::line( 'Posts afectados:' );

			$table_data = [];
			foreach ( $data['posts'] as $post ) {
				$table_data[] = [
					'ID'     => $post['id'],
					'Título' => $post['title'],
					'Tipo'   => $post['type'],
				];
			}

			WP_CLI\Utils\format_items( 'table', $table_data, [ 'ID', 'Título', 'Tipo' ] );
			WP_CLI::line( '' );
		}

		WP_CLI::line( WP_CLI::colorize( '%YEstos shortcodes deben ser convertidos manualmente después de la migración.%n' ) );
	}

	/**
	 * Crea un backup manual de la base de datos
	 *
	 * ## EXAMPLES
	 *
	 *     # Crear backup
	 *     $ wp reforestamos backup
	 *
	 * @when after_wp_load
	 */
	public function backup( $args, $assoc_args ) {
		WP_CLI::line( 'Creando backup de la base de datos...' );

		try {
			$backup_manager = new Reforestamos_Backup_Manager();
			$backup_info    = $backup_manager->create_backup( 'Backup manual desde WP-CLI' );

			WP_CLI::success( 'Backup creado exitosamente' );
			WP_CLI::line( "Archivo: {$backup_info['filepath']}" );
			WP_CLI::line( "Tamaño: {$backup_info['size_formatted']}" );
			WP_CLI::line( "Tablas: {$backup_info['tables_count']}" );

		} catch ( Exception $e ) {
			WP_CLI::error( 'Error al crear backup: ' . $e->getMessage() );
		}
	}

	/**
	 * Verifica los requisitos previos para la migración
	 *
	 * ## EXAMPLES
	 *
	 *     # Verificar requisitos
	 *     $ wp reforestamos check
	 *
	 * @when after_wp_load
	 */
	public function check( $args, $assoc_args ) {
		WP_CLI::line( '' );
		WP_CLI::line( WP_CLI::colorize( '%G=== VERIFICACIÓN DE REQUISITOS ===%n' ) );
		WP_CLI::line( '' );

		$this->verify_prerequisites( true );

		WP_CLI::line( '' );
		WP_CLI::success( 'Todos los requisitos están satisfechos' );
	}

	/**
	 * Valida la integridad de los datos después de la migración
	 *
	 * ## OPTIONS
	 *
	 * [--verbose]
	 * : Muestra información detallada durante la validación
	 *
	 * ## EXAMPLES
	 *
	 *     # Validar integridad de datos
	 *     $ wp reforestamos validate
	 *
	 *     # Validar con salida detallada
	 *     $ wp reforestamos validate --verbose
	 *
	 * @when after_wp_load
	 */
	public function validate( $args, $assoc_args ) {
		$verbose = WP_CLI\Utils\get_flag_value( $assoc_args, 'verbose', false );

		WP_CLI::line( '' );
		WP_CLI::line( WP_CLI::colorize( '%G=== VALIDACIÓN POST-MIGRACIÓN ===%n' ) );
		WP_CLI::line( '' );

		require_once REFORESTAMOS_MIGRATION_INCLUDES . '/class-migration-validator.php';
		$validator = new Reforestamos_Migration_Validator( $verbose );

		WP_CLI::line( 'Ejecutando validaciones...' );
		$results = $validator->validate_all();

		WP_CLI::line( '' );
		WP_CLI::line( WP_CLI::colorize( '%G=== RESULTADOS DE VALIDACIÓN ===%n' ) );
		WP_CLI::line( '' );

		// Display check results
		foreach ( $results['checks'] as $check_name => $check_data ) {
			$status_icon  = $check_data['status'] === 'passed' ? '✓' :
							( $check_data['status'] === 'warning' ? '⚠' : '✗' );
			$status_color = $check_data['status'] === 'passed' ? '%G' :
							( $check_data['status'] === 'warning' ? '%Y' : '%R' );

			$check_label = ucfirst( str_replace( '_', ' ', $check_name ) );
			WP_CLI::line( WP_CLI::colorize( "{$status_color}{$status_icon}%n {$check_label}" ) );
		}

		WP_CLI::line( '' );

		// Display errors
		if ( ! empty( $results['errors'] ) ) {
			WP_CLI::warning( 'Errores encontrados:' );
			foreach ( $results['errors'] as $error ) {
				WP_CLI::line( '  - ' . $error );
			}
			WP_CLI::line( '' );
		}

		// Display warnings
		if ( ! empty( $results['warnings'] ) ) {
			WP_CLI::warning( 'Advertencias:' );
			foreach ( $results['warnings'] as $warning ) {
				WP_CLI::line( '  - ' . $warning );
			}
			WP_CLI::line( '' );
		}

		// Generate report
		$report_file = $validator->generate_report();
		WP_CLI::line( WP_CLI::colorize( "%GReporte generado:%n {$report_file}" ) );
		WP_CLI::line( '' );

		if ( $results['success'] ) {
			WP_CLI::success( 'Validación completada exitosamente' );
		} else {
			WP_CLI::error( 'La validación detectó problemas críticos', false );
		}
	}

	/**
	 * Verify migration prerequisites
	 *
	 * @param bool $verbose Show detailed output
	 * @throws Exception If prerequisites are not met
	 */
	private function verify_prerequisites( $verbose = false ) {
		$checks = [];

		// Check WordPress version
		global $wp_version;
		$min_wp_version      = '6.0';
		$checks['WordPress'] = version_compare( $wp_version, $min_wp_version, '>=' );

		if ( $verbose ) {
			if ( $checks['WordPress'] ) {
				WP_CLI::line( WP_CLI::colorize( "%G✓%n WordPress {$wp_version} (mínimo: {$min_wp_version})" ) );
			} else {
				WP_CLI::line( WP_CLI::colorize( "%R✗%n WordPress {$wp_version} (mínimo: {$min_wp_version})" ) );
			}
		}

		// Check PHP version
		$min_php_version = '7.4';
		$checks['PHP']   = version_compare( PHP_VERSION, $min_php_version, '>=' );

		if ( $verbose ) {
			if ( $checks['PHP'] ) {
				WP_CLI::line( WP_CLI::colorize( '%G✓%n PHP ' . PHP_VERSION . " (mínimo: {$min_php_version})" ) );
			} else {
				WP_CLI::line( WP_CLI::colorize( '%R✗%n PHP ' . PHP_VERSION . " (mínimo: {$min_php_version})" ) );
			}
		}

		// Check write permissions
		$backup_dir         = WP_CONTENT_DIR . '/reforestamos-backups';
		$checks['Permisos'] = is_writable( WP_CONTENT_DIR );

		if ( $verbose ) {
			if ( $checks['Permisos'] ) {
				WP_CLI::line( WP_CLI::colorize( '%G✓%n Permisos de escritura en wp-content' ) );
			} else {
				WP_CLI::line( WP_CLI::colorize( '%R✗%n Sin permisos de escritura en wp-content' ) );
			}
		}

		// Check database connection
		global $wpdb;
		$checks['Database'] = $wpdb->check_connection();

		if ( $verbose ) {
			if ( $checks['Database'] ) {
				WP_CLI::line( WP_CLI::colorize( '%G✓%n Conexión a base de datos' ) );
			} else {
				WP_CLI::line( WP_CLI::colorize( '%R✗%n Sin conexión a base de datos' ) );
			}
		}

		// Check if Core Plugin is active
		$checks['Core Plugin'] = is_plugin_active( 'reforestamos-core/reforestamos-core.php' );

		if ( $verbose ) {
			if ( $checks['Core Plugin'] ) {
				WP_CLI::line( WP_CLI::colorize( '%G✓%n Reforestamos Core Plugin activo' ) );
			} else {
				WP_CLI::line( WP_CLI::colorize( '%Y⚠%n Reforestamos Core Plugin no activo (se recomienda activarlo)' ) );
			}
		}

		// Check for failures
		$failed_checks = array_filter(
			$checks,
			function ( $passed ) {
				return ! $passed;
			}
		);

		// Core Plugin is optional, remove from failed checks
		unset( $failed_checks['Core Plugin'] );

		if ( ! empty( $failed_checks ) ) {
			$failed_names = implode( ', ', array_keys( $failed_checks ) );
			throw new Exception( "Requisitos no satisfechos: {$failed_names}" );
		}
	}
}

// Register WP-CLI command
WP_CLI::add_command( 'reforestamos', 'Reforestamos_Migration_Command' );
