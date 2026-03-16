<?php
/**
 * Backup Manager Class
 *
 * Gestiona la creación, almacenamiento y restauración de backups de la base de datos.
 * Proporciona funcionalidades de seguridad para proteger los datos durante la migración.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Backup_Manager class
 *
 * Creates and manages database backups before migration.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */
class Reforestamos_Backup_Manager {

	/**
	 * Backup directory path
	 *
	 * @var string
	 */
	private $backup_dir;

	/**
	 * Maximum backup age in days
	 *
	 * @var int
	 */
	private $max_backup_age = 30;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->backup_dir = WP_CONTENT_DIR . '/reforestamos-backups';
		$this->ensure_backup_directory();
	}

	/**
	 * Ensure backup directory exists and is secure
	 */
	private function ensure_backup_directory() {
		if ( ! file_exists( $this->backup_dir ) ) {
			wp_mkdir_p( $this->backup_dir );
		}

		// Create .htaccess to deny access
		$htaccess_file = $this->backup_dir . '/.htaccess';
		if ( ! file_exists( $htaccess_file ) ) {
			$htaccess_content  = "# Reforestamos Backup Protection\n";
			$htaccess_content .= "Order deny,allow\n";
			$htaccess_content .= "Deny from all\n";
			file_put_contents( $htaccess_file, $htaccess_content );
		}

		// Create index.php to prevent directory listing
		$index_file = $this->backup_dir . '/index.php';
		if ( ! file_exists( $index_file ) ) {
			file_put_contents( $index_file, "<?php\n// Silence is golden\n" );
		}

		// Create .gitignore to exclude from version control
		$gitignore_file = $this->backup_dir . '/.gitignore';
		if ( ! file_exists( $gitignore_file ) ) {
			file_put_contents( $gitignore_file, "*\n!.gitignore\n" );
		}
	}

	/**
	 * Create a full database backup
	 *
	 * @param string $description Optional backup description
	 * @return array Backup information
	 * @throws Exception If backup fails
	 */
	public function create_backup( $description = '' ) {
		global $wpdb;

		$timestamp       = date( 'Y-m-d-H-i-s' );
		$backup_filename = 'backup-' . $timestamp . '.sql';
		$backup_filepath = $this->backup_dir . '/' . $backup_filename;

		// Start backup
		$sql_dump = $this->generate_sql_header( $description );

		// Get all tables
		$tables = $wpdb->get_results( 'SHOW TABLES', ARRAY_N );

		if ( empty( $tables ) ) {
			throw new Exception( 'No se encontraron tablas en la base de datos' );
		}

		$total_tables     = count( $tables );
		$processed_tables = 0;

		foreach ( $tables as $table ) {
			$table_name = $table[0];

			try {
				$sql_dump .= $this->backup_table( $table_name );
				++$processed_tables;
			} catch ( Exception $e ) {
				throw new Exception( "Error al respaldar tabla {$table_name}: " . $e->getMessage() );
			}
		}

		// Write backup file
		$bytes_written = file_put_contents( $backup_filepath, $sql_dump );

		if ( $bytes_written === false ) {
			throw new Exception( "No se pudo escribir el archivo de backup: {$backup_filepath}" );
		}

		// Create backup metadata
		$metadata = [
			'filename'          => $backup_filename,
			'filepath'          => $backup_filepath,
			'timestamp'         => $timestamp,
			'date'              => date( 'Y-m-d H:i:s' ),
			'description'       => $description,
			'size'              => $bytes_written,
			'size_formatted'    => size_format( $bytes_written ),
			'tables_count'      => $total_tables,
			'wordpress_version' => get_bloginfo( 'version' ),
			'php_version'       => PHP_VERSION,
			'mysql_version'     => $wpdb->db_version(),
		];

		// Save metadata
		$this->save_backup_metadata( $backup_filename, $metadata );

		return $metadata;
	}

	/**
	 * Generate SQL dump header
	 *
	 * @param string $description Backup description
	 * @return string SQL header
	 */
	private function generate_sql_header( $description = '' ) {
		global $wpdb;

		$header  = "-- =====================================================\n";
		$header .= "-- Reforestamos Migration Backup\n";
		$header .= "-- =====================================================\n";
		$header .= '-- Date: ' . date( 'Y-m-d H:i:s' ) . "\n";
		$header .= '-- WordPress Version: ' . get_bloginfo( 'version' ) . "\n";
		$header .= '-- PHP Version: ' . PHP_VERSION . "\n";
		$header .= '-- MySQL Version: ' . $wpdb->db_version() . "\n";
		$header .= '-- Database: ' . DB_NAME . "\n";
		$header .= '-- Table Prefix: ' . $wpdb->prefix . "\n";

		if ( ! empty( $description ) ) {
			$header .= "-- Description: {$description}\n";
		}

		$header .= "-- =====================================================\n\n";
		$header .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
		$header .= "SET time_zone = \"+00:00\";\n\n";

		return $header;
	}

	/**
	 * Backup a single table
	 *
	 * @param string $table_name Table name
	 * @return string SQL dump for table
	 */
	private function backup_table( $table_name ) {
		global $wpdb;

		$sql  = "\n-- =====================================================\n";
		$sql .= "-- Table: {$table_name}\n";
		$sql .= "-- =====================================================\n\n";

		// Get table structure
		$create_table = $wpdb->get_row( "SHOW CREATE TABLE `{$table_name}`", ARRAY_N );

		if ( ! $create_table ) {
			throw new Exception( "No se pudo obtener la estructura de la tabla {$table_name}" );
		}

		$sql .= "DROP TABLE IF EXISTS `{$table_name}`;\n";
		$sql .= $create_table[1] . ";\n\n";

		// Get table data
		$row_count = $wpdb->get_var( "SELECT COUNT(*) FROM `{$table_name}`" );

		if ( $row_count > 0 ) {
			$sql .= "-- Data for table {$table_name} ({$row_count} rows)\n\n";

			// Process in batches to avoid memory issues
			$batch_size = 1000;
			$offset     = 0;

			while ( $offset < $row_count ) {
				$rows = $wpdb->get_results(
					"SELECT * FROM `{$table_name}` LIMIT {$batch_size} OFFSET {$offset}",
					ARRAY_A
				);

				foreach ( $rows as $row ) {
					$values = array_map(
						function ( $value ) use ( $wpdb ) {
							if ( is_null( $value ) ) {
								return 'NULL';
							}
							return "'" . $wpdb->_real_escape( $value ) . "'";
						},
						array_values( $row )
					);

					$sql .= "INSERT INTO `{$table_name}` VALUES (" . implode( ', ', $values ) . ");\n";
				}

				$offset += $batch_size;
			}

			$sql .= "\n";
		} else {
			$sql .= "-- No data in table {$table_name}\n\n";
		}

		return $sql;
	}

	/**
	 * Save backup metadata to JSON file
	 *
	 * @param string $backup_filename Backup filename
	 * @param array  $metadata Backup metadata
	 */
	private function save_backup_metadata( $backup_filename, $metadata ) {
		$metadata_file = $this->backup_dir . '/' . str_replace( '.sql', '.json', $backup_filename );
		file_put_contents( $metadata_file, wp_json_encode( $metadata, JSON_PRETTY_PRINT ) );
	}

	/**
	 * Get list of all backups
	 *
	 * @return array List of backups with metadata
	 */
	public function get_backups_list() {
		$backups = [];

		if ( ! is_dir( $this->backup_dir ) ) {
			return $backups;
		}

		$files = glob( $this->backup_dir . '/backup-*.sql' );

		foreach ( $files as $file ) {
			$filename      = basename( $file );
			$metadata_file = str_replace( '.sql', '.json', $file );

			if ( file_exists( $metadata_file ) ) {
				$metadata = json_decode( file_get_contents( $metadata_file ), true );
			} else {
				// Generate basic metadata if JSON doesn't exist
				$metadata = [
					'filename'       => $filename,
					'filepath'       => $file,
					'size'           => filesize( $file ),
					'size_formatted' => size_format( filesize( $file ) ),
					'date'           => date( 'Y-m-d H:i:s', filemtime( $file ) ),
				];
			}

			$backups[] = $metadata;
		}

		// Sort by date (newest first)
		usort(
			$backups,
			function ( $a, $b ) {
				return strcmp( $b['date'], $a['date'] );
			}
		);

		return $backups;
	}

	/**
	 * Delete old backups
	 *
	 * @param int $days Maximum age in days
	 * @return int Number of deleted backups
	 */
	public function cleanup_old_backups( $days = null ) {
		if ( $days === null ) {
			$days = $this->max_backup_age;
		}

		$deleted_count = 0;
		$cutoff_time   = time() - ( $days * DAY_IN_SECONDS );

		$backups = $this->get_backups_list();

		foreach ( $backups as $backup ) {
			$backup_time = strtotime( $backup['date'] );

			if ( $backup_time < $cutoff_time ) {
				// Delete SQL file
				if ( file_exists( $backup['filepath'] ) ) {
					unlink( $backup['filepath'] );
				}

				// Delete metadata file
				$metadata_file = str_replace( '.sql', '.json', $backup['filepath'] );
				if ( file_exists( $metadata_file ) ) {
					unlink( $metadata_file );
				}

				++$deleted_count;
			}
		}

		return $deleted_count;
	}

	/**
	 * Get backup directory path
	 *
	 * @return string
	 */
	public function get_backup_dir() {
		return $this->backup_dir;
	}

	/**
	 * Get total size of all backups
	 *
	 * @return array Size information
	 */
	public function get_backups_size() {
		$total_size = 0;
		$backups    = $this->get_backups_list();

		foreach ( $backups as $backup ) {
			$total_size += $backup['size'];
		}

		return [
			'total_bytes'     => $total_size,
			'total_formatted' => size_format( $total_size ),
			'count'           => count( $backups ),
		];
	}

	/**
	 * Verify backup integrity
	 *
	 * @param string $backup_filename Backup filename
	 * @return bool True if backup is valid
	 */
	public function verify_backup( $backup_filename ) {
		$backup_filepath = $this->backup_dir . '/' . $backup_filename;

		if ( ! file_exists( $backup_filepath ) ) {
			return false;
		}

		// Check if file is readable
		if ( ! is_readable( $backup_filepath ) ) {
			return false;
		}

		// Check if file has content
		if ( filesize( $backup_filepath ) === 0 ) {
			return false;
		}

		// Check if file contains SQL
		$handle     = fopen( $backup_filepath, 'r' );
		$first_line = fgets( $handle );
		fclose( $handle );

		if ( strpos( $first_line, 'Reforestamos' ) === false && strpos( $first_line, 'SQL' ) === false ) {
			return false;
		}

		return true;
	}
}
