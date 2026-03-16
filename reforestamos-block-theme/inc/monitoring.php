<?php
/**
 * Production Monitoring Configuration
 *
 * Integrates error tracking (Sentry), uptime monitoring,
 * and verifies Google Analytics configuration.
 *
 * @package Reforestamos
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Reforestamos_Monitoring
 *
 * Centralizes production monitoring: Sentry error tracking,
 * uptime monitoring endpoint, and GA4 verification.
 */
class Reforestamos_Monitoring {

	/**
	 * Initialize monitoring systems.
	 */
	public static function init() {
		add_action( 'wp_head', array( __CLASS__, 'output_sentry_script' ), 0 );
		add_action( 'admin_menu', array( __CLASS__, 'add_monitoring_settings_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_monitoring_settings' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_uptime_endpoint' ) );
		add_action( 'admin_notices', array( __CLASS__, 'display_monitoring_status' ) );

		// Schedule uptime self-check (hourly).
		if ( ! wp_next_scheduled( 'reforestamos_uptime_self_check' ) ) {
			wp_schedule_event( time(), 'hourly', 'reforestamos_uptime_self_check' );
		}
		add_action( 'reforestamos_uptime_self_check', array( __CLASS__, 'run_self_check' ) );
	}

	// =========================================================================
	// Sentry Error Tracking (Req 35.4)
	// =========================================================================

	/**
	 * Output Sentry JavaScript SDK in the head.
	 */
	public static function output_sentry_script() {
		$dsn = get_option( 'reforestamos_sentry_dsn', '' );

		if ( empty( $dsn ) ) {
			return;
		}

		$environment = self::get_environment();
		$version     = defined( 'REFORESTAMOS_VERSION' ) ? REFORESTAMOS_VERSION : '1.0.0';
		?>
		<!-- Sentry Error Tracking -->
		<script
			src="https://browser.sentry-cdn.com/7.100.0/bundle.tracing.min.js"
			integrity="sha384-placeholder"
			crossorigin="anonymous"
		></script>
		<script>
			if (typeof Sentry !== 'undefined') {
				Sentry.init({
					dsn: <?php echo wp_json_encode( $dsn ); ?>,
					environment: <?php echo wp_json_encode( $environment ); ?>,
					release: <?php echo wp_json_encode( 'reforestamos@' . $version ); ?>,
					integrations: [new Sentry.BrowserTracing()],
					tracesSampleRate: <?php echo esc_js( 'production' === $environment ? '0.1' : '1.0' ); ?>,
					beforeSend: function(event) {
						// Strip PII from error reports
						if (event.request && event.request.cookies) {
							delete event.request.cookies;
						}
						return event;
					}
				});
			}
		</script>
		<?php
	}

	/**
	 * Log PHP errors to Sentry via HTTP API (server-side).
	 *
	 * @param array $error_data Error data array.
	 */
	public static function capture_php_error( $error_data ) {
		$dsn = get_option( 'reforestamos_sentry_dsn', '' );
		if ( empty( $dsn ) ) {
			return;
		}

		// Parse DSN to get project ID and key.
		$parsed = wp_parse_url( $dsn );
		if ( ! $parsed || empty( $parsed['path'] ) ) {
			return;
		}

		$project_id = ltrim( $parsed['path'], '/' );
		$public_key = $parsed['user'] ?? '';
		$host       = $parsed['scheme'] . '://' . $parsed['host'];

		$payload = array(
			'event_id'  => wp_generate_uuid4(),
			'timestamp' => gmdate( 'c' ),
			'level'     => 'error',
			'platform'  => 'php',
			'server_name' => gethostname(),
			'release'   => 'reforestamos@' . ( defined( 'REFORESTAMOS_VERSION' ) ? REFORESTAMOS_VERSION : '1.0.0' ),
			'environment' => self::get_environment(),
			'message'   => array(
				'formatted' => isset( $error_data['message'] ) ? $error_data['message'] : 'Unknown error',
			),
			'tags'      => array(
				'php_version' => PHP_VERSION,
				'wp_version'  => get_bloginfo( 'version' ),
			),
		);

		$url = $host . '/api/' . $project_id . '/store/';

		wp_remote_post( $url, array(
			'headers' => array(
				'Content-Type'  => 'application/json',
				'X-Sentry-Auth' => sprintf(
					'Sentry sentry_version=7, sentry_client=reforestamos-php/1.0, sentry_key=%s',
					$public_key
				),
			),
			'body'    => wp_json_encode( $payload ),
			'timeout' => 5,
		) );
	}

	// =========================================================================
	// Uptime Monitoring (Req 25.8)
	// =========================================================================

	/**
	 * Register a lightweight uptime monitoring endpoint.
	 */
	public static function register_uptime_endpoint() {
		register_rest_route( 'reforestamos/v1', '/uptime', array(
			'methods'             => 'GET',
			'callback'            => array( __CLASS__, 'uptime_check' ),
			'permission_callback' => '__return_true',
		) );
	}

	/**
	 * Uptime check callback — returns minimal response for monitoring services.
	 *
	 * @return WP_REST_Response
	 */
	public static function uptime_check() {
		global $wpdb;

		$db_ok = ( '1' === $wpdb->get_var( 'SELECT 1' ) );

		$status_code = $db_ok ? 200 : 503;

		return new WP_REST_Response( array(
			'status'    => $db_ok ? 'ok' : 'degraded',
			'timestamp' => gmdate( 'c' ),
			'db'        => $db_ok ? 'connected' : 'error',
		), $status_code );
	}

	/**
	 * Scheduled self-check: verify site is responding and log issues.
	 */
	public static function run_self_check() {
		$site_url    = home_url( '/wp-json/reforestamos/v1/uptime' );
		$response    = wp_remote_get( $site_url, array( 'timeout' => 10 ) );
		$status_code = wp_remote_retrieve_response_code( $response );

		if ( is_wp_error( $response ) || 200 !== $status_code ) {
			// Log the issue.
			if ( class_exists( 'Reforestamos_Error_Handler' ) ) {
				Reforestamos_Error_Handler::log_message( 'critical', array(
					'message'     => 'Uptime self-check failed',
					'status_code' => $status_code,
					'error'       => is_wp_error( $response ) ? $response->get_error_message() : 'Non-200 response',
				), 'monitoring' );
			}
		}
	}

	// =========================================================================
	// Google Analytics Verification (Req 34.1)
	// =========================================================================

	/**
	 * Verify GA4 is properly configured.
	 *
	 * @return array Verification results.
	 */
	public static function verify_analytics() {
		$results = array();

		$ga4_id = get_option( 'reforestamos_ga4_measurement_id', '' );
		$results['ga4_configured'] = ! empty( $ga4_id );
		$results['ga4_id']         = $ga4_id ? substr( $ga4_id, 0, 4 ) . '****' : 'not set';

		$cookie_consent = get_option( 'reforestamos_cookie_consent_enabled', '1' );
		$results['cookie_consent_enabled'] = '1' === $cookie_consent;

		$anonymize_ip = get_option( 'reforestamos_ga4_anonymize_ip', '1' );
		$results['ip_anonymization'] = '1' === $anonymize_ip;

		return $results;
	}

	// =========================================================================
	// Settings Page
	// =========================================================================

	/**
	 * Add monitoring settings page under Settings menu.
	 */
	public static function add_monitoring_settings_page() {
		add_options_page(
			__( 'Monitoring Settings', 'reforestamos' ),
			__( 'Monitoring', 'reforestamos' ),
			'manage_options',
			'reforestamos-monitoring',
			array( __CLASS__, 'render_settings_page' )
		);
	}

	/**
	 * Register monitoring settings.
	 */
	public static function register_monitoring_settings() {
		register_setting( 'reforestamos_monitoring', 'reforestamos_sentry_dsn', array(
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		) );

		register_setting( 'reforestamos_monitoring', 'reforestamos_uptime_url', array(
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		) );

		add_settings_section(
			'reforestamos_monitoring_sentry',
			__( 'Sentry Error Tracking', 'reforestamos' ),
			function () {
				echo '<p>' . esc_html__( 'Configure Sentry for real-time error tracking and alerting.', 'reforestamos' ) . '</p>';
			},
			'reforestamos-monitoring'
		);

		add_settings_field(
			'reforestamos_sentry_dsn',
			__( 'Sentry DSN', 'reforestamos' ),
			array( __CLASS__, 'render_sentry_dsn_field' ),
			'reforestamos-monitoring',
			'reforestamos_monitoring_sentry'
		);

		add_settings_section(
			'reforestamos_monitoring_uptime',
			__( 'Uptime Monitoring', 'reforestamos' ),
			function () {
				$endpoint = home_url( '/wp-json/reforestamos/v1/uptime' );
				echo '<p>' . sprintf(
					/* translators: %s: uptime endpoint URL */
					esc_html__( 'Uptime endpoint: %s', 'reforestamos' ),
					'<code>' . esc_html( $endpoint ) . '</code>'
				) . '</p>';
				echo '<p>' . esc_html__( 'Configure your uptime monitoring service (UptimeRobot, Pingdom, etc.) to ping this endpoint.', 'reforestamos' ) . '</p>';
			},
			'reforestamos-monitoring'
		);

		add_settings_field(
			'reforestamos_uptime_url',
			__( 'External Monitoring URL', 'reforestamos' ),
			array( __CLASS__, 'render_uptime_url_field' ),
			'reforestamos-monitoring',
			'reforestamos_monitoring_uptime'
		);
	}

	/**
	 * Render Sentry DSN field.
	 */
	public static function render_sentry_dsn_field() {
		$value = get_option( 'reforestamos_sentry_dsn', '' );
		?>
		<input type="url"
			   name="reforestamos_sentry_dsn"
			   value="<?php echo esc_attr( $value ); ?>"
			   class="regular-text"
			   placeholder="https://examplePublicKey@o0.ingest.sentry.io/0">
		<p class="description">
			<?php esc_html_e( 'Enter your Sentry DSN from Project Settings > Client Keys.', 'reforestamos' ); ?>
		</p>
		<?php
	}

	/**
	 * Render uptime URL field.
	 */
	public static function render_uptime_url_field() {
		$value = get_option( 'reforestamos_uptime_url', '' );
		?>
		<input type="url"
			   name="reforestamos_uptime_url"
			   value="<?php echo esc_attr( $value ); ?>"
			   class="regular-text"
			   placeholder="https://uptimerobot.com/dashboard/...">
		<p class="description">
			<?php esc_html_e( 'Optional: Link to your external uptime monitoring dashboard.', 'reforestamos' ); ?>
		</p>
		<?php
	}

	/**
	 * Render the monitoring settings page.
	 */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$analytics = self::verify_analytics();
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<h2><?php esc_html_e( 'Monitoring Status', 'reforestamos' ); ?></h2>
			<table class="widefat striped" style="max-width:600px;">
				<tbody>
					<tr>
						<td><strong><?php esc_html_e( 'Google Analytics 4', 'reforestamos' ); ?></strong></td>
						<td>
							<?php if ( $analytics['ga4_configured'] ) : ?>
								<span style="color:green;">✓ <?php esc_html_e( 'Configured', 'reforestamos' ); ?></span>
								(<?php echo esc_html( $analytics['ga4_id'] ); ?>)
							<?php else : ?>
								<span style="color:red;">✗ <?php esc_html_e( 'Not configured', 'reforestamos' ); ?></span>
								— <a href="<?php echo esc_url( admin_url( 'options-general.php?page=reforestamos-analytics' ) ); ?>">
									<?php esc_html_e( 'Configure', 'reforestamos' ); ?>
								</a>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Cookie Consent', 'reforestamos' ); ?></strong></td>
						<td>
							<?php echo $analytics['cookie_consent_enabled']
								? '<span style="color:green;">✓ ' . esc_html__( 'Enabled', 'reforestamos' ) . '</span>'
								: '<span style="color:orange;">⚠ ' . esc_html__( 'Disabled', 'reforestamos' ) . '</span>'; ?>
						</td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'IP Anonymization', 'reforestamos' ); ?></strong></td>
						<td>
							<?php echo $analytics['ip_anonymization']
								? '<span style="color:green;">✓ ' . esc_html__( 'Enabled', 'reforestamos' ) . '</span>'
								: '<span style="color:orange;">⚠ ' . esc_html__( 'Disabled', 'reforestamos' ) . '</span>'; ?>
						</td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Sentry Error Tracking', 'reforestamos' ); ?></strong></td>
						<td>
							<?php
							$sentry_dsn = get_option( 'reforestamos_sentry_dsn', '' );
							echo ! empty( $sentry_dsn )
								? '<span style="color:green;">✓ ' . esc_html__( 'Configured', 'reforestamos' ) . '</span>'
								: '<span style="color:orange;">⚠ ' . esc_html__( 'Not configured', 'reforestamos' ) . '</span>';
							?>
						</td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Uptime Endpoint', 'reforestamos' ); ?></strong></td>
						<td>
							<span style="color:green;">✓ <?php esc_html_e( 'Active', 'reforestamos' ); ?></span>
							<code><?php echo esc_html( home_url( '/wp-json/reforestamos/v1/uptime' ) ); ?></code>
						</td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Health Check', 'reforestamos' ); ?></strong></td>
						<td>
							<span style="color:green;">✓ <?php esc_html_e( 'Active', 'reforestamos' ); ?></span>
							<code><?php echo esc_html( home_url( '/wp-json/reforestamos/v1/health' ) ); ?></code>
						</td>
					</tr>
				</tbody>
			</table>

			<hr>

			<form action="options.php" method="post">
				<?php
				settings_fields( 'reforestamos_monitoring' );
				do_settings_sections( 'reforestamos-monitoring' );
				submit_button( __( 'Save Monitoring Settings', 'reforestamos' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Display monitoring status notice on dashboard.
	 */
	public static function display_monitoring_status() {
		$screen = get_current_screen();
		if ( ! $screen || 'dashboard' !== $screen->id ) {
			return;
		}

		$sentry_dsn = get_option( 'reforestamos_sentry_dsn', '' );
		$ga4_id     = get_option( 'reforestamos_ga4_measurement_id', '' );

		$missing = array();
		if ( empty( $sentry_dsn ) ) {
			$missing[] = __( 'Sentry error tracking', 'reforestamos' );
		}
		if ( empty( $ga4_id ) ) {
			$missing[] = __( 'Google Analytics 4', 'reforestamos' );
		}

		if ( ! empty( $missing ) ) {
			$message = sprintf(
				/* translators: %s: comma-separated list of unconfigured services */
				__( 'Reforestamos Monitoring: %s not configured. <a href="%s">Configure now</a>.', 'reforestamos' ),
				implode( ', ', $missing ),
				admin_url( 'options-general.php?page=reforestamos-monitoring' )
			);
			echo '<div class="notice notice-info is-dismissible"><p>' . wp_kses_post( $message ) . '</p></div>';
		}
	}

	// =========================================================================
	// Helpers
	// =========================================================================

	/**
	 * Determine the current environment.
	 *
	 * @return string Environment name (production, staging, development).
	 */
	private static function get_environment() {
		if ( defined( 'WP_ENVIRONMENT_TYPE' ) ) {
			return WP_ENVIRONMENT_TYPE;
		}

		$site_url = get_site_url();
		if ( strpos( $site_url, 'staging' ) !== false || strpos( $site_url, 'test' ) !== false ) {
			return 'staging';
		}
		if ( strpos( $site_url, 'localhost' ) !== false || strpos( $site_url, '.local' ) !== false ) {
			return 'development';
		}

		return 'production';
	}
}
