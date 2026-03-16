<?php
/**
 * Newsletter Management Class
 *
 * @package Reforestamos_Comunicacion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Newsletter class
 *
 * Manages newsletter campaigns, subscribers, and email sending.
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */
class Reforestamos_Newsletter {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_pages' ] );
		add_action( 'admin_post_send_newsletter', [ $this, 'handle_send_newsletter' ] );
		add_action( 'wp_ajax_newsletter_subscribe', [ $this, 'handle_subscription' ] );
		add_action( 'wp_ajax_nopriv_newsletter_subscribe', [ $this, 'handle_subscription' ] );
		add_action( 'wp_ajax_add_subscriber', [ $this, 'handle_add_subscriber' ] );
		add_action( 'wp_ajax_retry_failed_sends', [ $this, 'handle_retry_failed_sends' ] );
		add_action( 'init', [ $this, 'handle_unsubscribe' ] );
		add_action( 'init', [ $this, 'handle_verify_subscription' ] );
		add_shortcode( 'newsletter-subscribe', [ $this, 'render_subscribe_form' ] );

		// Register WP Cron hooks for background processing
		add_action( 'reforestamos_process_newsletter_batch', [ $this, 'process_newsletter_batch' ], 10, 3 );
		add_action( 'reforestamos_retry_failed_newsletter', [ $this, 'retry_failed_newsletter_sends' ] );
	}

	/**
	 * Add admin menu pages
	 */
	public function add_admin_pages() {
		add_submenu_page(
			'edit.php?post_type=boletin',
			__( 'Campañas Newsletter', 'reforestamos-comunicacion' ),
			__( 'Campañas', 'reforestamos-comunicacion' ),
			'manage_options',
			'newsletter-campaigns',
			[ $this, 'render_campaigns_page' ]
		);

		add_submenu_page(
			'edit.php?post_type=boletin',
			__( 'Suscriptores', 'reforestamos-comunicacion' ),
			__( 'Suscriptores', 'reforestamos-comunicacion' ),
			'manage_options',
			'newsletter-subscribers',
			[ $this, 'render_subscribers_page' ]
		);

		add_submenu_page(
			'edit.php?post_type=boletin',
			__( 'Logs de Envío', 'reforestamos-comunicacion' ),
			__( 'Logs de Envío', 'reforestamos-comunicacion' ),
			'manage_options',
			'newsletter-send-logs',
			[ $this, 'render_send_logs_page' ]
		);

		add_submenu_page(
			'edit.php?post_type=boletin',
			__( 'Configuración', 'reforestamos-comunicacion' ),
			__( 'Configuración', 'reforestamos-comunicacion' ),
			'manage_options',
			'newsletter-settings',
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Render campaigns page
	 */
	public function render_campaigns_page() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'No tienes permisos para acceder a esta página.', 'reforestamos-comunicacion' ) );
		}

		// Get all published newsletters (Boletín CPT)
		$newsletters = get_posts(
			[
				'post_type'      => 'boletin',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);

		// Get subscriber count
		global $wpdb;
		$table_name       = $wpdb->prefix . 'reforestamos_subscribers';
		$subscriber_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE status = 'active'" );

		include REFORESTAMOS_COMM_DIR . 'admin/views/campaigns-page.php';
	}

	/**
	 * Render subscribers page
	 */
	public function render_subscribers_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'No tienes permisos para acceder a esta página.', 'reforestamos-comunicacion' ) );
		}

		include REFORESTAMOS_COMM_DIR . 'admin/views/subscribers-list.php';
	}

	/**
	 * Handle newsletter sending
	 */
	public function handle_send_newsletter() {
		// Verify nonce
		if ( ! isset( $_POST['newsletter_nonce'] ) || ! wp_verify_nonce( $_POST['newsletter_nonce'], 'send_newsletter' ) ) {
			wp_die( __( 'Error de seguridad', 'reforestamos-comunicacion' ) );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'No tienes permisos para realizar esta acción.', 'reforestamos-comunicacion' ) );
		}

		$newsletter_id  = intval( $_POST['newsletter_id'] );
		$recipient_type = sanitize_text_field( $_POST['recipient_type'] );

		// Get newsletter post
		$newsletter = get_post( $newsletter_id );
		if ( ! $newsletter || $newsletter->post_type !== 'boletin' ) {
			wp_die( __( 'Newsletter no encontrado', 'reforestamos-comunicacion' ) );
		}

		// Get recipients
		$recipients = $this->get_recipients( $recipient_type );

		if ( empty( $recipients ) ) {
			wp_redirect(
				add_query_arg(
					[
						'page'    => 'newsletter-campaigns',
						'message' => 'no_recipients',
					],
					admin_url( 'edit.php?post_type=boletin' )
				)
			);
			exit;
		}

		// Schedule newsletter sending
		$result = $this->send_newsletter( $newsletter, $recipients );

		if ( $result['success'] ) {
			$redirect_args = [
				'page'       => 'newsletter-campaigns',
				'message'    => 'sent',
				'sent_count' => $result['sent_count'],
			];

			if ( isset( $result['scheduled_count'] ) ) {
				$redirect_args['scheduled_count'] = $result['scheduled_count'];
			}

			wp_redirect( add_query_arg( $redirect_args, admin_url( 'edit.php?post_type=boletin' ) ) );
		} else {
			wp_redirect(
				add_query_arg(
					[
						'page'    => 'newsletter-campaigns',
						'message' => 'error',
					],
					admin_url( 'edit.php?post_type=boletin' )
				)
			);
		}
		exit;
	}

	/**
	 * Get recipients based on type
	 */
	private function get_recipients( $type = 'all' ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_subscribers';

		$where = "WHERE status = 'active'";

		// Add additional filters based on type if needed
		// For now, we only support 'all' active subscribers

		$recipients = $wpdb->get_results(
			"SELECT id, email, name FROM $table_name $where",
			ARRAY_A
		);

		return $recipients;
	}

	/**
	 * Send newsletter to recipients
	 */
	private function send_newsletter( $newsletter, $recipients ) {
		// Get rate limiting settings from options (with defaults)
		$batch_size                = get_option( 'reforestamos_newsletter_batch_size', 50 );
		$batch_delay               = get_option( 'reforestamos_newsletter_batch_delay', 2 );
		$use_background_processing = get_option( 'reforestamos_newsletter_use_cron', 'yes' );

		// If using background processing and we have many recipients, schedule via WP Cron
		if ( $use_background_processing === 'yes' && count( $recipients ) > $batch_size ) {
			return $this->schedule_newsletter_batches( $newsletter, $recipients, $batch_size );
		}

		// Otherwise, send immediately with rate limiting
		return $this->send_newsletter_immediate( $newsletter, $recipients, $batch_size, $batch_delay );
	}

	/**
	 * Schedule newsletter sending in batches via WP Cron
	 */
	private function schedule_newsletter_batches( $newsletter, $recipients, $batch_size ) {
		$batches         = array_chunk( $recipients, $batch_size );
		$scheduled_count = 0;

		foreach ( $batches as $index => $batch ) {
			// Schedule each batch with a delay
			$timestamp = time() + ( $index * 60 ); // 1 minute between batches

			wp_schedule_single_event(
				$timestamp,
				'reforestamos_process_newsletter_batch',
				[ $newsletter->ID, $batch, $index ]
			);

			$scheduled_count += count( $batch );
		}

		return [
			'success'         => true,
			'sent_count'      => 0,
			'scheduled_count' => $scheduled_count,
			'failed_count'    => 0,
			'message'         => sprintf(
				__( 'Newsletter programado para envío en %d lotes. El envío comenzará en breve.', 'reforestamos-comunicacion' ),
				count( $batches )
			),
		];
	}

	/**
	 * Process a newsletter batch (called by WP Cron)
	 */
	public function process_newsletter_batch( $newsletter_id, $recipients, $batch_index ) {
		$newsletter = get_post( $newsletter_id );

		if ( ! $newsletter || $newsletter->post_type !== 'boletin' ) {
			error_log( "Newsletter batch processing failed: Invalid newsletter ID {$newsletter_id}" );
			return;
		}

		$batch_delay = get_option( 'reforestamos_newsletter_batch_delay', 2 );
		$result      = $this->send_newsletter_immediate( $newsletter, $recipients, count( $recipients ), $batch_delay );

		error_log(
			sprintf(
				'Newsletter batch %d processed: %d sent, %d failed',
				$batch_index,
				$result['sent_count'],
				$result['failed_count']
			)
		);
	}

	/**
	 * Send newsletter immediately with rate limiting
	 */
	private function send_newsletter_immediate( $newsletter, $recipients, $batch_size, $batch_delay ) {
		$mailer = Reforestamos_Mailer::get_instance();

		$sent_count   = 0;
		$failed_count = 0;
		$batch_count  = 0;

		global $wpdb;
		$log_table = $wpdb->prefix . 'reforestamos_newsletter_logs';

		foreach ( $recipients as $recipient ) {
			// Rate limiting - pause after each batch
			if ( $batch_count >= $batch_size ) {
				sleep( $batch_delay );
				$batch_count = 0;
			}

			// Check if already sent to this recipient
			$already_sent = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM $log_table WHERE newsletter_id = %d AND subscriber_id = %d AND status = 'sent'",
					$newsletter->ID,
					$recipient['id']
				)
			);

			if ( $already_sent > 0 ) {
				continue; // Skip if already sent
			}

			// Prepare email content
			$subject = $newsletter->post_title;
			$content = $this->prepare_email_content( $newsletter, $recipient );

			// Send email
			$sent = $mailer->send_newsletter( $recipient['email'], $subject, $content );

			// Log the send attempt
			$wpdb->insert(
				$log_table,
				[
					'newsletter_id' => $newsletter->ID,
					'subscriber_id' => $recipient['id'],
					'email'         => $recipient['email'],
					'status'        => $sent ? 'sent' : 'failed',
					'sent_at'       => current_time( 'mysql' ),
					'retry_count'   => 0,
					'error_message' => $sent ? null : 'Failed to send email',
				],
				[ '%d', '%d', '%s', '%s', '%s', '%d', '%s' ]
			);

			if ( $sent ) {
				++$sent_count;
			} else {
				++$failed_count;
			}

			++$batch_count;
		}

		return [
			'success'      => $sent_count > 0,
			'sent_count'   => $sent_count,
			'failed_count' => $failed_count,
		];
	}

	/**
	 * Prepare email content with personalization
	 */
	private function prepare_email_content( $newsletter, $recipient ) {
		$content = apply_filters( 'the_content', $newsletter->post_content );

		// Add unsubscribe link
		$unsubscribe_url = $this->get_unsubscribe_url( $recipient['id'] );

		// Replace placeholders
		$content = str_replace( '{name}', $recipient['name'], $content );
		$content = str_replace( '{email}', $recipient['email'], $content );

		// Add footer with unsubscribe link
		$footer = sprintf(
			'<hr><p style="font-size: 12px; color: #666;">%s <a href="%s">%s</a></p>',
			__( 'Si no deseas recibir más correos,', 'reforestamos-comunicacion' ),
			esc_url( $unsubscribe_url ),
			__( 'cancela tu suscripción aquí', 'reforestamos-comunicacion' )
		);

		$content .= $footer;

		return $content;
	}

	/**
	 * Get unsubscribe URL for a subscriber
	 */
	private function get_unsubscribe_url( $subscriber_id ) {
		$token = $this->generate_unsubscribe_token( $subscriber_id );
		return add_query_arg(
			[
				'action'     => 'unsubscribe',
				'subscriber' => $subscriber_id,
				'token'      => $token,
			],
			home_url( '/' )
		);
	}

	/**
	 * Generate secure unsubscribe token
	 */
	private function generate_unsubscribe_token( $subscriber_id ) {
		return wp_hash( $subscriber_id . 'unsubscribe_salt' );
	}

	/**
	 * Verify unsubscribe token
	 */
	private function verify_unsubscribe_token( $subscriber_id, $token ) {
		return hash_equals( $this->generate_unsubscribe_token( $subscriber_id ), $token );
	}

	/**
	 * Handle unsubscribe requests
	 */
	public function handle_unsubscribe() {
		if ( ! isset( $_GET['action'] ) || $_GET['action'] !== 'unsubscribe' ) {
			return;
		}

		$subscriber_id = isset( $_GET['subscriber'] ) ? intval( $_GET['subscriber'] ) : 0;
		$token         = isset( $_GET['token'] ) ? sanitize_text_field( $_GET['token'] ) : '';

		if ( ! $subscriber_id || ! $token ) {
			wp_die( __( 'Enlace de baja inválido', 'reforestamos-comunicacion' ) );
		}

		// Verify token
		if ( ! $this->verify_unsubscribe_token( $subscriber_id, $token ) ) {
			wp_die( __( 'Token de seguridad inválido', 'reforestamos-comunicacion' ) );
		}

		// Unsubscribe user
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_subscribers';

		$updated = $wpdb->update(
			$table_name,
			[
				'status'          => 'unsubscribed',
				'unsubscribed_at' => current_time( 'mysql' ),
			],
			[ 'id' => $subscriber_id ],
			[ '%s', '%s' ],
			[ '%d' ]
		);

		if ( $updated ) {
			// Show confirmation page
			$this->show_unsubscribe_confirmation();
		} else {
			wp_die( __( 'Error al procesar la baja', 'reforestamos-comunicacion' ) );
		}
	}

	/**
	 * Show unsubscribe confirmation page
	 */
	private function show_unsubscribe_confirmation() {
		wp_head();
		?>
		<div style="max-width: 600px; margin: 100px auto; padding: 40px; text-align: center; font-family: Arial, sans-serif;">
			<h1><?php _e( 'Baja Confirmada', 'reforestamos-comunicacion' ); ?></h1>
			<p><?php _e( 'Tu suscripción ha sido cancelada exitosamente.', 'reforestamos-comunicacion' ); ?></p>
			<p><?php _e( 'Ya no recibirás más correos de nuestro boletín.', 'reforestamos-comunicacion' ); ?></p>
			<p><a href="<?php echo home_url( '/' ); ?>" class="button"><?php _e( 'Volver al inicio', 'reforestamos-comunicacion' ); ?></a></p>
		</div>
		<?php
		wp_footer();
		exit;
	}

	/**
	 * Handle subscription via AJAX
	 */
	public function handle_subscription() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'newsletter_subscribe' ) ) {
			wp_send_json_error( [ 'message' => __( 'Error de seguridad', 'reforestamos-comunicacion' ) ] );
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$name  = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';

		// Validate email
		if ( ! is_email( $email ) ) {
			wp_send_json_error( [ 'message' => __( 'Email inválido', 'reforestamos-comunicacion' ) ] );
		}

		// Check if already subscribed
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_subscribers';

		$existing = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $table_name WHERE email = %s",
				$email
			)
		);

		if ( $existing ) {
			if ( $existing->status === 'active' ) {
				wp_send_json_error( [ 'message' => __( 'Este email ya está suscrito', 'reforestamos-comunicacion' ) ] );
			} elseif ( $existing->status === 'pending' ) {
				// Resend verification email
				$this->send_verification_email( $email, $name, $existing->verification_token );
				wp_send_json_success( [ 'message' => __( 'Te hemos reenviado el email de confirmación. Por favor revisa tu bandeja de entrada.', 'reforestamos-comunicacion' ) ] );
			} else {
				// Reactivate subscription with new verification token
				$verification_token = $this->generate_verification_token( $email );

				$wpdb->update(
					$table_name,
					[
						'status'             => 'pending',
						'verification_token' => $verification_token,
						'subscribed_at'      => current_time( 'mysql' ),
						'verified_at'        => null,
						'unsubscribed_at'    => null,
					],
					[ 'email' => $email ],
					[ '%s', '%s', '%s', '%s', '%s' ],
					[ '%s' ]
				);

				// Send verification email
				$this->send_verification_email( $email, $name, $verification_token );

				wp_send_json_success( [ 'message' => __( '¡Gracias por suscribirte! Te hemos enviado un email de confirmación. Por favor revisa tu bandeja de entrada y haz clic en el enlace para activar tu suscripción.', 'reforestamos-comunicacion' ) ] );
			}
		}

		// Generate verification token
		$verification_token = $this->generate_verification_token( $email );

		// Insert new subscriber with pending status
		$inserted = $wpdb->insert(
			$table_name,
			[
				'email'              => $email,
				'name'               => $name,
				'status'             => 'pending',
				'verification_token' => $verification_token,
				'subscribed_at'      => current_time( 'mysql' ),
			],
			[ '%s', '%s', '%s', '%s', '%s' ]
		);

		if ( $inserted ) {
			// Send verification email
			$this->send_verification_email( $email, $name, $verification_token );

			wp_send_json_success( [ 'message' => __( '¡Gracias por suscribirte! Te hemos enviado un email de confirmación. Por favor revisa tu bandeja de entrada y haz clic en el enlace para activar tu suscripción.', 'reforestamos-comunicacion' ) ] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Error al procesar la suscripción', 'reforestamos-comunicacion' ) ] );
		}
	}

	/**
	 * Send subscription confirmation email
	 */
	private function send_subscription_confirmation( $email, $name ) {
		$mailer = Reforestamos_Mailer::get_instance();

		$subject = __( 'Confirmación de Suscripción - Reforestamos México', 'reforestamos-comunicacion' );

		$message  = sprintf(
			__( 'Hola %s,', 'reforestamos-comunicacion' ),
			$name ? $name : ''
		);
		$message .= "\n\n";
		$message .= __( 'Gracias por suscribirte a nuestro boletín.', 'reforestamos-comunicacion' );
		$message .= "\n\n";
		$message .= __( 'Recibirás noticias y actualizaciones sobre nuestros proyectos de reforestación.', 'reforestamos-comunicacion' );
		$message .= "\n\n";
		$message .= __( 'Saludos,', 'reforestamos-comunicacion' );
		$message .= "\n";
		$message .= __( 'Equipo Reforestamos México', 'reforestamos-comunicacion' );

		$mailer->send_email( $email, $subject, $message );
	}

	/**
	 * Generate verification token
	 */
	private function generate_verification_token( $email ) {
		return hash( 'sha256', $email . time() . wp_generate_password( 20, false ) );
	}

	/**
	 * Send verification email
	 */
	private function send_verification_email( $email, $name, $verification_token ) {
		$mailer = Reforestamos_Mailer::get_instance();

		$verification_url = $this->get_verification_url( $verification_token );

		$subject = __( 'Confirma tu suscripción - Reforestamos México', 'reforestamos-comunicacion' );

		$message  = sprintf(
			__( 'Hola %s,', 'reforestamos-comunicacion' ),
			$name ? $name : ''
		);
		$message .= "\n\n";
		$message .= __( 'Gracias por suscribirte a nuestro boletín de Reforestamos México.', 'reforestamos-comunicacion' );
		$message .= "\n\n";
		$message .= __( 'Para completar tu suscripción, por favor haz clic en el siguiente enlace:', 'reforestamos-comunicacion' );
		$message .= "\n\n";
		$message .= $verification_url;
		$message .= "\n\n";
		$message .= __( 'Si no solicitaste esta suscripción, puedes ignorar este mensaje.', 'reforestamos-comunicacion' );
		$message .= "\n\n";
		$message .= __( 'Este enlace expirará en 48 horas.', 'reforestamos-comunicacion' );
		$message .= "\n\n";
		$message .= __( 'Saludos,', 'reforestamos-comunicacion' );
		$message .= "\n";
		$message .= __( 'Equipo Reforestamos México', 'reforestamos-comunicacion' );

		$mailer->send_email( $email, $subject, $message );
	}

	/**
	 * Get verification URL
	 */
	private function get_verification_url( $verification_token ) {
		return add_query_arg(
			[
				'action' => 'verify_subscription',
				'token'  => $verification_token,
			],
			home_url( '/' )
		);
	}

	/**
	 * Handle subscription verification
	 */
	public function handle_verify_subscription() {
		if ( ! isset( $_GET['action'] ) || $_GET['action'] !== 'verify_subscription' ) {
			return;
		}

		$token = isset( $_GET['token'] ) ? sanitize_text_field( $_GET['token'] ) : '';

		if ( ! $token ) {
			wp_die( __( 'Enlace de verificación inválido', 'reforestamos-comunicacion' ) );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_subscribers';

		// Find subscriber by verification token
		$subscriber = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $table_name WHERE verification_token = %s",
				$token
			)
		);

		if ( ! $subscriber ) {
			wp_die( __( 'Enlace de verificación inválido o expirado', 'reforestamos-comunicacion' ) );
		}

		// Check if already verified
		if ( $subscriber->status === 'active' ) {
			$this->show_verification_message( 'already_verified' );
			return;
		}

		// Check if token is expired (48 hours)
		$subscribed_time = strtotime( $subscriber->subscribed_at );
		$current_time    = current_time( 'timestamp' );
		$hours_elapsed   = ( $current_time - $subscribed_time ) / 3600;

		if ( $hours_elapsed > 48 ) {
			wp_die( __( 'Este enlace de verificación ha expirado. Por favor suscríbete nuevamente.', 'reforestamos-comunicacion' ) );
		}

		// Activate subscription
		$updated = $wpdb->update(
			$table_name,
			[
				'status'             => 'active',
				'verified_at'        => current_time( 'mysql' ),
				'verification_token' => null,
			],
			[ 'id' => $subscriber->id ],
			[ '%s', '%s', '%s' ],
			[ '%d' ]
		);

		if ( $updated ) {
			// Send welcome email
			$this->send_subscription_confirmation( $subscriber->email, $subscriber->name );

			// Show confirmation page
			$this->show_verification_message( 'success' );
		} else {
			wp_die( __( 'Error al verificar la suscripción', 'reforestamos-comunicacion' ) );
		}
	}

	/**
	 * Show verification confirmation message
	 */
	private function show_verification_message( $type = 'success' ) {
		wp_head();
		?>
		<div style="max-width: 600px; margin: 100px auto; padding: 40px; text-align: center; font-family: Arial, sans-serif;">
			<?php if ( $type === 'success' ) : ?>
				<h1><?php _e( '¡Suscripción Confirmada!', 'reforestamos-comunicacion' ); ?></h1>
				<p><?php _e( 'Tu suscripción ha sido activada exitosamente.', 'reforestamos-comunicacion' ); ?></p>
				<p><?php _e( 'Comenzarás a recibir nuestro boletín con noticias y actualizaciones sobre proyectos de reforestación.', 'reforestamos-comunicacion' ); ?></p>
			<?php elseif ( $type === 'already_verified' ) : ?>
				<h1><?php _e( 'Ya estás suscrito', 'reforestamos-comunicacion' ); ?></h1>
				<p><?php _e( 'Tu suscripción ya fue confirmada anteriormente.', 'reforestamos-comunicacion' ); ?></p>
				<p><?php _e( 'No necesitas hacer nada más.', 'reforestamos-comunicacion' ); ?></p>
			<?php endif; ?>
			<p><a href="<?php echo home_url( '/' ); ?>" class="button" style="display: inline-block; padding: 10px 20px; background-color: #2E7D32; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px;"><?php _e( 'Volver al inicio', 'reforestamos-comunicacion' ); ?></a></p>
		</div>
		<?php
		wp_footer();
		exit;
	}

	/**
	 * Handle add subscriber via AJAX (admin only)
	 */
	public function handle_add_subscriber() {
		// Verify nonce
		if ( ! isset( $_POST['add_subscriber_nonce'] ) || ! wp_verify_nonce( $_POST['add_subscriber_nonce'], 'add_subscriber' ) ) {
			wp_send_json_error( [ 'message' => __( 'Error de seguridad', 'reforestamos-comunicacion' ) ] );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'No tienes permisos para realizar esta acción', 'reforestamos-comunicacion' ) ] );
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$name  = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';

		// Validate email
		if ( ! is_email( $email ) ) {
			wp_send_json_error( [ 'message' => __( 'Email inválido', 'reforestamos-comunicacion' ) ] );
		}

		// Check if already exists
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_subscribers';

		$existing = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $table_name WHERE email = %s",
				$email
			)
		);

		if ( $existing ) {
			wp_send_json_error( [ 'message' => __( 'Este email ya existe en la base de datos', 'reforestamos-comunicacion' ) ] );
		}

		// Insert new subscriber
		$inserted = $wpdb->insert(
			$table_name,
			[
				'email'         => $email,
				'name'          => $name,
				'status'        => 'active',
				'subscribed_at' => current_time( 'mysql' ),
			],
			[ '%s', '%s', '%s', '%s' ]
		);

		if ( $inserted ) {
			wp_send_json_success( [ 'message' => __( 'Suscriptor añadido exitosamente', 'reforestamos-comunicacion' ) ] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Error al añadir el suscriptor', 'reforestamos-comunicacion' ) ] );
		}
	}

	/**
	 * Render subscription form shortcode
	 */
	public function render_subscribe_form( $atts ) {
		$atts = shortcode_atts(
			[
				'title'       => __( 'Suscríbete a nuestro boletín', 'reforestamos-comunicacion' ),
				'button_text' => __( 'Suscribirse', 'reforestamos-comunicacion' ),
				'show_name'   => 'yes',
			],
			$atts
		);

		ob_start();
		?>
		<div class="reforestamos-newsletter-subscribe">
			<?php if ( $atts['title'] ) : ?>
				<h3><?php echo esc_html( $atts['title'] ); ?></h3>
			<?php endif; ?>
			
			<form class="newsletter-subscribe-form" method="post">
				<?php if ( $atts['show_name'] === 'yes' ) : ?>
				<div class="form-group mb-3">
					<label for="newsletter-name"><?php _e( 'Nombre', 'reforestamos-comunicacion' ); ?></label>
					<input type="text" id="newsletter-name" name="name" class="form-control" placeholder="<?php esc_attr_e( 'Tu nombre', 'reforestamos-comunicacion' ); ?>">
				</div>
				<?php endif; ?>
				
				<div class="form-group mb-3">
					<label for="newsletter-email"><?php _e( 'Email', 'reforestamos-comunicacion' ); ?> *</label>
					<input type="email" id="newsletter-email" name="email" class="form-control" required placeholder="<?php esc_attr_e( 'tu@email.com', 'reforestamos-comunicacion' ); ?>">
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary newsletter-submit">
						<?php echo esc_html( $atts['button_text'] ); ?>
					</button>
				</div>
				
				<div class="newsletter-messages mt-3"></div>
			</form>
		</div>
		
		<style>
			.reforestamos-newsletter-subscribe {
				max-width: 500px;
				margin: 0 auto;
				padding: 20px;
			}
			.reforestamos-newsletter-subscribe h3 {
				margin-bottom: 20px;
				color: #2E7D32;
			}
			.reforestamos-newsletter-subscribe .form-control {
				width: 100%;
				padding: 10px;
				border: 1px solid #ddd;
				border-radius: 4px;
				font-size: 16px;
			}
			.reforestamos-newsletter-subscribe .form-control:focus {
				outline: none;
				border-color: #2E7D32;
				box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
			}
			.reforestamos-newsletter-subscribe label {
				display: block;
				margin-bottom: 5px;
				font-weight: 500;
			}
			.reforestamos-newsletter-subscribe .newsletter-submit {
				width: 100%;
				padding: 12px;
				background-color: #2E7D32;
				color: white;
				border: none;
				border-radius: 4px;
				font-size: 16px;
				cursor: pointer;
				transition: background-color 0.3s;
			}
			.reforestamos-newsletter-subscribe .newsletter-submit:hover {
				background-color: #1B5E20;
			}
			.reforestamos-newsletter-subscribe .newsletter-submit:disabled {
				background-color: #ccc;
				cursor: not-allowed;
			}
			.reforestamos-newsletter-subscribe .newsletter-messages .alert {
				padding: 12px;
				border-radius: 4px;
				margin-top: 15px;
			}
			.reforestamos-newsletter-subscribe .newsletter-messages .alert-success {
				background-color: #d4edda;
				border: 1px solid #c3e6cb;
				color: #155724;
			}
			.reforestamos-newsletter-subscribe .newsletter-messages .alert-danger {
				background-color: #f8d7da;
				border: 1px solid #f5c6cb;
				color: #721c24;
			}
		</style>
		<?php
		return ob_get_clean();
	}

	/**
	 * Retry failed newsletter sends
	 */
	public function retry_failed_newsletter_sends() {
		global $wpdb;
		$log_table = $wpdb->prefix . 'reforestamos_newsletter_logs';

		// Get failed sends that haven't exceeded max retry count
		$max_retries = get_option( 'reforestamos_newsletter_max_retries', 3 );

		$failed_sends = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $log_table WHERE status = 'failed' AND retry_count < %d LIMIT 50",
				$max_retries
			),
			ARRAY_A
		);

		if ( empty( $failed_sends ) ) {
			return;
		}

		$mailer      = Reforestamos_Mailer::get_instance();
		$retry_count = 0;

		foreach ( $failed_sends as $log ) {
			$newsletter = get_post( $log['newsletter_id'] );

			if ( ! $newsletter ) {
				continue;
			}

			// Get subscriber info
			$subscriber_table = $wpdb->prefix . 'reforestamos_subscribers';
			$subscriber       = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $subscriber_table WHERE id = %d",
					$log['subscriber_id']
				),
				ARRAY_A
			);

			if ( ! $subscriber ) {
				continue;
			}

			// Prepare email content
			$subject = $newsletter->post_title;
			$content = $this->prepare_email_content( $newsletter, $subscriber );

			// Retry sending
			$sent = $mailer->send_newsletter( $subscriber['email'], $subject, $content );

			// Update log
			$wpdb->update(
				$log_table,
				[
					'status'        => $sent ? 'sent' : 'failed',
					'retry_count'   => intval( $log['retry_count'] ) + 1,
					'sent_at'       => current_time( 'mysql' ),
					'error_message' => $sent ? null : 'Retry failed',
				],
				[ 'id' => $log['id'] ],
				[ '%s', '%d', '%s', '%s' ],
				[ '%d' ]
			);

			if ( $sent ) {
				++$retry_count;
			}

			// Small delay between retries
			usleep( 500000 ); // 0.5 seconds
		}

		error_log( "Newsletter retry completed: {$retry_count} emails successfully resent" );
	}

	/**
	 * Handle retry failed sends via AJAX
	 */
	public function handle_retry_failed_sends() {
		// Verify nonce
		if ( ! isset( $_POST['retry_nonce'] ) || ! wp_verify_nonce( $_POST['retry_nonce'], 'retry_failed_sends' ) ) {
			wp_send_json_error( [ 'message' => __( 'Error de seguridad', 'reforestamos-comunicacion' ) ] );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'No tienes permisos para realizar esta acción', 'reforestamos-comunicacion' ) ] );
		}

		$newsletter_id = isset( $_POST['newsletter_id'] ) ? intval( $_POST['newsletter_id'] ) : 0;

		if ( ! $newsletter_id ) {
			wp_send_json_error( [ 'message' => __( 'ID de newsletter inválido', 'reforestamos-comunicacion' ) ] );
		}

		global $wpdb;
		$log_table = $wpdb->prefix . 'reforestamos_newsletter_logs';

		// Get failed sends for this newsletter
		$max_retries = get_option( 'reforestamos_newsletter_max_retries', 3 );

		$failed_sends = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $log_table WHERE newsletter_id = %d AND status = 'failed' AND retry_count < %d",
				$newsletter_id,
				$max_retries
			),
			ARRAY_A
		);

		if ( empty( $failed_sends ) ) {
			wp_send_json_error( [ 'message' => __( 'No hay envíos fallidos para reintentar', 'reforestamos-comunicacion' ) ] );
		}

		$mailer        = Reforestamos_Mailer::get_instance();
		$newsletter    = get_post( $newsletter_id );
		$retry_success = 0;
		$retry_failed  = 0;

		foreach ( $failed_sends as $log ) {
			// Get subscriber info
			$subscriber_table = $wpdb->prefix . 'reforestamos_subscribers';
			$subscriber       = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $subscriber_table WHERE id = %d",
					$log['subscriber_id']
				),
				ARRAY_A
			);

			if ( ! $subscriber ) {
				continue;
			}

			// Prepare email content
			$subject = $newsletter->post_title;
			$content = $this->prepare_email_content( $newsletter, $subscriber );

			// Retry sending
			$sent = $mailer->send_newsletter( $subscriber['email'], $subject, $content );

			// Update log
			$wpdb->update(
				$log_table,
				[
					'status'        => $sent ? 'sent' : 'failed',
					'retry_count'   => intval( $log['retry_count'] ) + 1,
					'sent_at'       => current_time( 'mysql' ),
					'error_message' => $sent ? null : 'Manual retry failed',
				],
				[ 'id' => $log['id'] ],
				[ '%s', '%d', '%s', '%s' ],
				[ '%d' ]
			);

			if ( $sent ) {
				++$retry_success;
			} else {
				++$retry_failed;
			}

			// Small delay between retries
			usleep( 500000 ); // 0.5 seconds
		}

		wp_send_json_success(
			[
				'message'       => sprintf(
					__( 'Reintento completado: %1$d exitosos, %2$d fallidos', 'reforestamos-comunicacion' ),
					$retry_success,
					$retry_failed
				),
				'retry_success' => $retry_success,
				'retry_failed'  => $retry_failed,
			]
		);
	}

	/**
	 * Render send logs page
	 */
	public function render_send_logs_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'No tienes permisos para acceder a esta página.', 'reforestamos-comunicacion' ) );
		}

		global $wpdb;
		$log_table = $wpdb->prefix . 'reforestamos_newsletter_logs';

		// Get filter parameters
		$newsletter_id = isset( $_GET['newsletter_id'] ) ? intval( $_GET['newsletter_id'] ) : 0;
		$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '';

		// Build query
		$where_clauses = [];
		$where_values  = [];

		if ( $newsletter_id > 0 ) {
			$where_clauses[] = 'newsletter_id = %d';
			$where_values[]  = $newsletter_id;
		}

		if ( $status_filter && in_array( $status_filter, [ 'sent', 'failed', 'pending' ] ) ) {
			$where_clauses[] = 'status = %s';
			$where_values[]  = $status_filter;
		}

		$where_sql = '';
		if ( ! empty( $where_clauses ) ) {
			$where_sql = 'WHERE ' . implode( ' AND ', $where_clauses );
		}

		// Get logs with pagination
		$per_page = 50;
		$page     = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
		$offset   = ( $page - 1 ) * $per_page;

		$query          = "SELECT * FROM $log_table $where_sql ORDER BY sent_at DESC LIMIT %d OFFSET %d";
		$where_values[] = $per_page;
		$where_values[] = $offset;

		if ( ! empty( $where_values ) ) {
			$logs = $wpdb->get_results( $wpdb->prepare( $query, $where_values ), ARRAY_A );
		} else {
			$logs = $wpdb->get_results( $query, ARRAY_A );
		}

		// Get total count for pagination
		$count_query = "SELECT COUNT(*) FROM $log_table $where_sql";
		if ( ! empty( $where_clauses ) ) {
			$total_logs = $wpdb->get_var( $wpdb->prepare( $count_query, array_slice( $where_values, 0, count( $where_clauses ) ) ) );
		} else {
			$total_logs = $wpdb->get_var( $count_query );
		}

		$total_pages = ceil( $total_logs / $per_page );

		// Get all newsletters for filter dropdown
		$newsletters = get_posts(
			[
				'post_type'      => 'boletin',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);

		// Get statistics
		$stats = [
			'total'   => $wpdb->get_var( "SELECT COUNT(*) FROM $log_table" ),
			'sent'    => $wpdb->get_var( "SELECT COUNT(*) FROM $log_table WHERE status = 'sent'" ),
			'failed'  => $wpdb->get_var( "SELECT COUNT(*) FROM $log_table WHERE status = 'failed'" ),
			'pending' => $wpdb->get_var( "SELECT COUNT(*) FROM $log_table WHERE status = 'pending'" ),
		];

		include REFORESTAMOS_COMM_DIR . 'admin/views/send-logs-page.php';
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'No tienes permisos para acceder a esta página.', 'reforestamos-comunicacion' ) );
		}

		// Handle form submission
		if ( isset( $_POST['save_newsletter_settings'] ) && check_admin_referer( 'newsletter_settings', 'newsletter_settings_nonce' ) ) {
			update_option( 'reforestamos_newsletter_batch_size', intval( $_POST['batch_size'] ) );
			update_option( 'reforestamos_newsletter_batch_delay', intval( $_POST['batch_delay'] ) );
			update_option( 'reforestamos_newsletter_max_retries', intval( $_POST['max_retries'] ) );
			update_option( 'reforestamos_newsletter_use_cron', sanitize_text_field( $_POST['use_cron'] ) );
			update_option( 'reforestamos_newsletter_auto_retry', sanitize_text_field( $_POST['auto_retry'] ) );

			// Schedule or unschedule auto retry cron
			if ( $_POST['auto_retry'] === 'yes' ) {
				if ( ! wp_next_scheduled( 'reforestamos_retry_failed_newsletter' ) ) {
					wp_schedule_event( time(), 'hourly', 'reforestamos_retry_failed_newsletter' );
				}
			} else {
				wp_clear_scheduled_hook( 'reforestamos_retry_failed_newsletter' );
			}

			echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Configuración guardada exitosamente.', 'reforestamos-comunicacion' ) . '</p></div>';
		}

		// Get current settings
		$batch_size  = get_option( 'reforestamos_newsletter_batch_size', 50 );
		$batch_delay = get_option( 'reforestamos_newsletter_batch_delay', 2 );
		$max_retries = get_option( 'reforestamos_newsletter_max_retries', 3 );
		$use_cron    = get_option( 'reforestamos_newsletter_use_cron', 'yes' );
		$auto_retry  = get_option( 'reforestamos_newsletter_auto_retry', 'no' );

		include REFORESTAMOS_COMM_DIR . 'admin/views/newsletter-settings-page.php';
	}
}

// Initialize
Reforestamos_Newsletter::get_instance();
