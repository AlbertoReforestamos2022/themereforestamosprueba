<?php
/**
 * Error Logger Class
 *
 * Gestiona el logging estructurado de errores durante la migración.
 * Categoriza errores por severidad y permite continuar ante errores no críticos.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Error_Logger class
 *
 * Handles error logging during migration.
 *
 * @package Reforestamos_Migration
 * @since 1.0.0
 */
class Reforestamos_Error_Logger {

	/**
	 * Error severity levels
	 */
	const SEVERITY_CRITICAL = 'critical';
	const SEVERITY_ERROR    = 'error';
	const SEVERITY_WARNING  = 'warning';
	const SEVERITY_INFO     = 'info';

	/**
	 * Errors collection
	 *
	 * @var array
	 */
	private $errors = [];

	/**
	 * Database table name
	 *
	 * @var string
	 */
	private $table_name;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'reforestamos_migration_errors';
	}

	/**
	 * Log an error
	 *
	 * @param string $message Error message
	 * @param string $severity Severity level
	 * @param array  $context Additional context
	 * @return int|false Error ID or false on failure
	 */
	public function log_error( $message, $severity = self::SEVERITY_ERROR, $context = [] ) {
		global $wpdb;

		$error_data = [
			'message'   => $message,
			'severity'  => $severity,
			'context'   => json_encode( $context ),
			'timestamp' => current_time( 'mysql' ),
			'user_id'   => get_current_user_id(),
		];

		// Store in memory
		$this->errors[] = $error_data;

		// Store in database
		$result = $wpdb->insert(
			$this->table_name,
			$error_data,
			[ '%s', '%s', '%s', '%s', '%d' ]
		);

		return $result ? $wpdb->insert_id : false;
	}

	/**
	 * Log a critical error
	 *
	 * @param string $message Error message
	 * @param array  $context Additional context
	 * @return int|false Error ID or false on failure
	 */
	public function log_critical( $message, $context = [] ) {
		return $this->log_error( $message, self::SEVERITY_CRITICAL, $context );
	}

	/**
	 * Log a warning
	 *
	 * @param string $message Warning message
	 * @param array  $context Additional context
	 * @return int|false Error ID or false on failure
	 */
	public function log_warning( $message, $context = [] ) {
		return $this->log_error( $message, self::SEVERITY_WARNING, $context );
	}

	/**
	 * Log an info message
	 *
	 * @param string $message Info message
	 * @param array  $context Additional context
	 * @return int|false Error ID or false on failure
	 */
	public function log_info( $message, $context = [] ) {
		return $this->log_error( $message, self::SEVERITY_INFO, $context );
	}

	/**
	 * Check if error is critical
	 *
	 * @param string $severity Severity level
	 * @return bool
	 */
	public function is_critical( $severity ) {
		return $severity === self::SEVERITY_CRITICAL;
	}

	/**
	 * Get all errors
	 *
	 * @param string|null $severity Filter by severity
	 * @return array
	 */
	public function get_errors( $severity = null ) {
		if ( $severity ) {
			return array_filter(
				$this->errors,
				function ( $error ) use ( $severity ) {
					return $error['severity'] === $severity;
				}
			);
		}

		return $this->errors;
	}

	/**
	 * Get errors from database
	 *
	 * @param array $args Query arguments
	 * @return array
	 */
	public function get_errors_from_db( $args = [] ) {
		global $wpdb;

		$defaults = [
			'severity' => null,
			'limit'    => 100,
			'offset'   => 0,
			'order_by' => 'timestamp',
			'order'    => 'DESC',
		];

		$args = wp_parse_args( $args, $defaults );

		$where = '1=1';
		if ( $args['severity'] ) {
			$where .= $wpdb->prepare( ' AND severity = %s', $args['severity'] );
		}

		$query = "SELECT * FROM {$this->table_name} 
                  WHERE {$where} 
                  ORDER BY {$args['order_by']} {$args['order']} 
                  LIMIT %d OFFSET %d";

		return $wpdb->get_results(
			$wpdb->prepare( $query, $args['limit'], $args['offset'] ),
			ARRAY_A
		);
	}

	/**
	 * Get error count by severity
	 *
	 * @return array
	 */
	public function get_error_counts() {
		global $wpdb;

		$query = "SELECT severity, COUNT(*) as count 
                  FROM {$this->table_name} 
                  GROUP BY severity";

		$results = $wpdb->get_results( $query, ARRAY_A );

		$counts = [
			self::SEVERITY_CRITICAL => 0,
			self::SEVERITY_ERROR    => 0,
			self::SEVERITY_WARNING  => 0,
			self::SEVERITY_INFO     => 0,
		];

		foreach ( $results as $row ) {
			$counts[ $row['severity'] ] = (int) $row['count'];
		}

		return $counts;
	}

	/**
	 * Clear all errors
	 *
	 * @return bool
	 */
	public function clear_errors() {
		global $wpdb;

		$this->errors = [];

		return $wpdb->query( "TRUNCATE TABLE {$this->table_name}" ) !== false;
	}

	/**
	 * Get error recovery suggestions
	 *
	 * @param string $error_type Error type
	 * @return string
	 */
	public function get_recovery_suggestion( $error_type ) {
		$suggestions = [
			'post_migration'       => 'Verifica que el post existe y tiene permisos correctos. Intenta migrar manualmente.',
			'custom_field'         => 'Verifica que el custom field está registrado correctamente. Revisa la configuración de ACF/CMB2.',
			'shortcode_conversion' => 'Este shortcode requiere conversión manual. Consulta la guía de conversión.',
			'taxonomy'             => 'Verifica que la taxonomía está registrada. Revisa el plugin Core.',
			'media'                => 'Verifica que el archivo existe en wp-content/uploads. Puede requerir re-subida.',
			'database'             => 'Error de base de datos. Verifica la conexión y permisos. Considera restaurar el backup.',
		];

		return $suggestions[ $error_type ] ?? 'Consulta el log detallado para más información.';
	}

	/**
	 * Create database table
	 *
	 * @return bool
	 */
	public function create_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            message text NOT NULL,
            severity varchar(20) NOT NULL,
            context longtext,
            timestamp datetime NOT NULL,
            user_id bigint(20),
            PRIMARY KEY  (id),
            KEY severity (severity),
            KEY timestamp (timestamp)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		return true;
	}

	/**
	 * Export errors to CSV
	 *
	 * @param string $filepath File path
	 * @return bool
	 */
	public function export_to_csv( $filepath ) {
		$errors = $this->get_errors_from_db( [ 'limit' => 10000 ] );

		$fp = fopen( $filepath, 'w' );
		if ( ! $fp ) {
			return false;
		}

		// Write header
		fputcsv( $fp, [ 'ID', 'Timestamp', 'Severity', 'Message', 'Context' ] );

		// Write data
		foreach ( $errors as $error ) {
			fputcsv(
				$fp,
				[
					$error['id'],
					$error['timestamp'],
					$error['severity'],
					$error['message'],
					$error['context'],
				]
			);
		}

		fclose( $fp );

		return true;
	}
}
