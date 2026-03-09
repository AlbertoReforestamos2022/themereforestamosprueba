<?php
/**
 * Mailer Class - PHPMailer Configuration and Email Sending
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Mailer class
 *
 * Handles email sending using WordPress wp_mail() with custom SMTP configuration.
 * PHPMailer is already included in WordPress core, so we configure it via filters.
 */
class Reforestamos_Mailer {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Mailer
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Mailer
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
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		// Configure PHPMailer
		add_action( 'phpmailer_init', array( $this, 'configure_phpmailer' ) );
		
		// Set custom from email and name
		add_filter( 'wp_mail_from', array( $this, 'set_from_email' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'set_from_name' ) );
	}

	/**
	 * Configure PHPMailer with SMTP settings
	 *
	 * @param PHPMailer $phpmailer The PHPMailer instance.
	 */
	public function configure_phpmailer( $phpmailer ) {
		// Get SMTP settings from options
		$smtp_host     = get_option( 'reforestamos_smtp_host' );
		$smtp_port     = get_option( 'reforestamos_smtp_port', '587' );
		$smtp_username = get_option( 'reforestamos_smtp_username' );
		$smtp_password = get_option( 'reforestamos_smtp_password' );
		$smtp_secure   = get_option( 'reforestamos_smtp_secure', 'tls' );

		// Only configure SMTP if host is set
		if ( empty( $smtp_host ) ) {
			return;
		}

		// Configure PHPMailer to use SMTP
		$phpmailer->isSMTP();
		$phpmailer->Host       = $smtp_host;
		$phpmailer->Port       = $smtp_port;
		$phpmailer->SMTPSecure = $smtp_secure;

		// Set authentication if username and password are provided
		if ( ! empty( $smtp_username ) && ! empty( $smtp_password ) ) {
			$phpmailer->SMTPAuth = true;
			$phpmailer->Username = $smtp_username;
			$phpmailer->Password = $smtp_password;
		}

		// Enable SMTP debugging in development (set to 0 in production)
		$phpmailer->SMTPDebug = 0;
	}

	/**
	 * Set custom from email
	 *
	 * @param string $email Default from email.
	 * @return string Custom from email.
	 */
	public function set_from_email( $email ) {
		$custom_email = get_option( 'reforestamos_smtp_from_email' );
		return ! empty( $custom_email ) ? $custom_email : $email;
	}

	/**
	 * Set custom from name
	 *
	 * @param string $name Default from name.
	 * @return string Custom from name.
	 */
	public function set_from_name( $name ) {
		$custom_name = get_option( 'reforestamos_smtp_from_name' );
		return ! empty( $custom_name ) ? $custom_name : $name;
	}

	/**
	 * Send contact form notification email
	 *
	 * @param array $data Form data containing name, email, subject, message, phone.
	 * @return bool True on success, false on failure.
	 */
	public function send_contact_notification( $data ) {
		$to      = get_option( 'admin_email' );
		$subject = sprintf(
			/* translators: %s: Contact form subject */
			__( 'Nuevo mensaje de contacto: %s', 'reforestamos-comunicacion' ),
			$data['subject']
		);

		// Build email body
		$message = $this->build_contact_email_body( $data );

		// Set headers
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			sprintf( 'Reply-To: %s <%s>', $data['name'], $data['email'] ),
		);

		// Send email
		$sent = wp_mail( $to, $subject, $message, $headers );

		// Log if failed
		if ( ! $sent ) {
			error_log( 'Reforestamos Comunicación: Failed to send contact form notification' );
		}

		return $sent;
	}

	/**
	 * Build contact form email body
	 *
	 * @param array $data Form data.
	 * @return string HTML email body.
	 */
	private function build_contact_email_body( $data ) {
		ob_start();
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF-8">
			<style>
				body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
				.container { max-width: 600px; margin: 0 auto; padding: 20px; }
				.header { background-color: #2E7D32; color: white; padding: 20px; text-align: center; }
				.content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
				.field { margin-bottom: 15px; }
				.label { font-weight: bold; color: #2E7D32; }
				.value { margin-top: 5px; }
				.footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
			</style>
		</head>
		<body>
			<div class="container">
				<div class="header">
					<h2><?php esc_html_e( 'Nuevo Mensaje de Contacto', 'reforestamos-comunicacion' ); ?></h2>
				</div>
				<div class="content">
					<div class="field">
						<div class="label"><?php esc_html_e( 'Nombre:', 'reforestamos-comunicacion' ); ?></div>
						<div class="value"><?php echo esc_html( $data['name'] ); ?></div>
					</div>
					<div class="field">
						<div class="label"><?php esc_html_e( 'Email:', 'reforestamos-comunicacion' ); ?></div>
						<div class="value"><?php echo esc_html( $data['email'] ); ?></div>
					</div>
					<?php if ( ! empty( $data['phone'] ) ) : ?>
					<div class="field">
						<div class="label"><?php esc_html_e( 'Teléfono:', 'reforestamos-comunicacion' ); ?></div>
						<div class="value"><?php echo esc_html( $data['phone'] ); ?></div>
					</div>
					<?php endif; ?>
					<div class="field">
						<div class="label"><?php esc_html_e( 'Asunto:', 'reforestamos-comunicacion' ); ?></div>
						<div class="value"><?php echo esc_html( $data['subject'] ); ?></div>
					</div>
					<div class="field">
						<div class="label"><?php esc_html_e( 'Mensaje:', 'reforestamos-comunicacion' ); ?></div>
						<div class="value"><?php echo nl2br( esc_html( $data['message'] ) ); ?></div>
					</div>
				</div>
				<div class="footer">
					<p><?php esc_html_e( 'Este mensaje fue enviado desde el formulario de contacto de', 'reforestamos-comunicacion' ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
					<p><?php echo esc_html( home_url() ); ?></p>
				</div>
			</div>
		</body>
		</html>
		<?php
		return ob_get_clean();
	}

	/**
	 * Send newsletter email
	 *
	 * @param string $to Recipient email address.
	 * @param string $subject Email subject.
	 * @param string $content Email content (HTML).
	 * @return bool True on success, false on failure.
	 */
	public function send_newsletter( $to, $subject, $content ) {
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
		);

		$message = $this->build_newsletter_email_body( $content );

		$sent = wp_mail( $to, $subject, $message, $headers );

		if ( ! $sent ) {
			error_log( sprintf( 'Reforestamos Comunicación: Failed to send newsletter to %s', $to ) );
		}

		return $sent;
	}

	/**
	 * Build newsletter email body
	 *
	 * @param string $content Newsletter content.
	 * @return string HTML email body.
	 */
	private function build_newsletter_email_body( $content ) {
		ob_start();
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF-8">
			<style>
				body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
				.container { max-width: 600px; margin: 0 auto; }
				.header { background-color: #2E7D32; color: white; padding: 20px; text-align: center; }
				.content { padding: 20px; }
				.footer { background-color: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; }
				.unsubscribe { margin-top: 10px; }
			</style>
		</head>
		<body>
			<div class="container">
				<div class="header">
					<h2><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h2>
				</div>
				<div class="content">
					<?php echo wp_kses_post( $content ); ?>
				</div>
				<div class="footer">
					<p><?php esc_html_e( 'Recibiste este correo porque estás suscrito a nuestro boletín.', 'reforestamos-comunicacion' ); ?></p>
					<div class="unsubscribe">
						<a href="<?php echo esc_url( home_url( '/unsubscribe' ) ); ?>"><?php esc_html_e( 'Cancelar suscripción', 'reforestamos-comunicacion' ); ?></a>
					</div>
				</div>
			</div>
		</body>
		</html>
		<?php
		return ob_get_clean();
	}

	/**
	 * Send test email
	 *
	 * @param string $to Recipient email address.
	 * @return bool True on success, false on failure.
	 */
	public function send_test_email( $to ) {
		$subject = __( 'Email de Prueba - Reforestamos Comunicación', 'reforestamos-comunicacion' );
		$message = sprintf(
			/* translators: %s: Site name */
			__( 'Este es un email de prueba desde %s. Si recibiste este mensaje, la configuración SMTP está funcionando correctamente.', 'reforestamos-comunicacion' ),
			get_bloginfo( 'name' )
		);

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
		);

		return wp_mail( $to, $subject, $message, $headers );
	}

	/**
	 * Send generic email
	 *
	 * @param string $to Recipient email address.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param array  $headers Optional. Email headers.
	 * @return bool True on success, false on failure.
	 */
	public function send_email( $to, $subject, $message, $headers = array() ) {
		if ( empty( $headers ) ) {
			$headers = array(
				'Content-Type: text/html; charset=UTF-8',
			);
		}

		$sent = wp_mail( $to, $subject, $message, $headers );

		if ( ! $sent ) {
			error_log( sprintf( 'Reforestamos Comunicación: Failed to send email to %s', $to ) );
		}

		return $sent;
	}

	/**
	 * Send email with attachment
	 *
	 * @param string $to Recipient email address.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param string $attachment_path Path to attachment file.
	 * @return bool True on success, false on failure.
	 */
	public function send_email_with_attachment( $to, $subject, $message, $attachment_path ) {
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
		);

		$attachments = array( $attachment_path );

		$sent = wp_mail( $to, $subject, $message, $headers, $attachments );

		if ( ! $sent ) {
			error_log( sprintf( 'Reforestamos Comunicación: Failed to send email with attachment to %s', $to ) );
		}

		return $sent;
	}
}
