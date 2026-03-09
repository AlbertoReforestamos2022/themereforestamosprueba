<?php
/**
 * Contact Form Class
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_Contact_Form class
 *
 * Singleton pattern implementation for contact form functionality.
 * Handles shortcode registration and form rendering.
 */
class Reforestamos_Contact_Form {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_Contact_Form
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_Contact_Form
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize WordPress hooks
	 */
	private function init_hooks() {
		add_shortcode( 'contact-form', array( $this, 'render_contact_form' ) );
		add_action( 'wp_ajax_submit_contact_form', array( $this, 'handle_form_submission' ) );
		add_action( 'wp_ajax_nopriv_submit_contact_form', array( $this, 'handle_form_submission' ) );
	}

	/**
	 * Render contact form shortcode
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output of the contact form.
	 */
	public function render_contact_form( $atts ) {
		// Parse shortcode attributes with defaults
		$atts = shortcode_atts(
			array(
				'title'       => __( 'Contáctanos', 'reforestamos-comunicacion' ),
				'button_text' => __( 'Enviar Mensaje', 'reforestamos-comunicacion' ),
			),
			$atts,
			'contact-form'
		);

		// Sanitize attributes
		$title       = sanitize_text_field( $atts['title'] );
		$button_text = sanitize_text_field( $atts['button_text'] );

		// Start output buffering
		ob_start();

		// Load template
		$this->load_template( $title, $button_text );

		// Return buffered content
		return ob_get_clean();
	}

	/**
	 * Load contact form template
	 *
	 * @param string $title Form title.
	 * @param string $button_text Button text.
	 */
	private function load_template( $title, $button_text ) {
		$template_path = REFORESTAMOS_COMM_PATH . 'templates/forms/contact-form-template.php';

		if ( file_exists( $template_path ) ) {
			include $template_path;
		} else {
			// Fallback if template file doesn't exist
			echo '<p>' . esc_html__( 'Error: Template de formulario no encontrado.', 'reforestamos-comunicacion' ) . '</p>';
		}
	}

	/**
	 * Handle form submission via AJAX
	 *
	 * Validates and sanitizes form data, then processes the submission.
	 * Requirements: 9.3, 9.7, 9.9, 23.1, 23.4, 23.6
	 */
	public function handle_form_submission() {
		// Verify nonce for security
		if ( ! isset( $_POST['contact_form_nonce'] ) || ! wp_verify_nonce( $_POST['contact_form_nonce'], 'reforestamos_contact_form' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error de seguridad. Por favor recarga la página e intenta de nuevo.', 'reforestamos-comunicacion' ),
				)
			);
		}

		// Honeypot spam protection (Requirement 9.7)
		// If the honeypot field is filled, it's likely a bot
		$honeypot = isset( $_POST['website'] ) ? $_POST['website'] : '';
		if ( ! empty( $honeypot ) ) {
			// Log spam attempt
			error_log(
				sprintf(
					'Reforestamos Comunicación - Anti-Spam: Honeypot triggered. IP: %s, Time: %s, Honeypot value: %s',
					$this->get_user_ip(),
					current_time( 'mysql' ),
					$honeypot
				)
			);
			
			// Return success to not give hints to bots
			wp_send_json_success(
				array(
					'message' => __( '¡Gracias por tu mensaje! Te responderemos pronto.', 'reforestamos-comunicacion' ),
				)
			);
		}

		// Rate limiting spam protection (Requirement 23.6)
		$rate_limit_check = $this->check_rate_limit();
		if ( is_wp_error( $rate_limit_check ) ) {
			wp_send_json_error(
				array(
					'message' => $rate_limit_check->get_error_message(),
				)
			);
		}

		// Get and sanitize form data
		$nombre  = isset( $_POST['nombre'] ) ? sanitize_text_field( $_POST['nombre'] ) : '';
		$email   = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$asunto  = isset( $_POST['asunto'] ) ? sanitize_text_field( $_POST['asunto'] ) : '';
		$mensaje = isset( $_POST['mensaje'] ) ? sanitize_textarea_field( $_POST['mensaje'] ) : '';

		// Validate all fields
		$validation_errors = $this->validate_form_data( $nombre, $email, $asunto, $mensaje );

		if ( ! empty( $validation_errors ) ) {
			wp_send_json_error(
				array(
					'message' => implode( '<br>', $validation_errors ),
				)
			);
		}

		// At this point, data is validated and sanitized
		
		// Requirement 9.8: Save submission to database BEFORE sending email (backup)
		$this->save_submission( $nombre, $email, $asunto, $mensaje );
		
		// Prepare data for email sending (Requirements 9.4, 9.5, 9.6)
		$email_data = array(
			'name'    => $nombre,
			'email'   => $email,
			'subject' => $asunto,
			'message' => $mensaje,
			'phone'   => '', // Optional field, not in current form
		);

		// Get mailer instance and send email
		$mailer = Reforestamos_Mailer::get_instance();
		$sent   = $mailer->send_contact_notification( $email_data );

		// Handle email sending result
		if ( $sent ) {
			// Increment rate limit counter after successful submission
			$this->increment_rate_limit();
			
			// Requirement 9.5: Display success message when email is sent successfully
			wp_send_json_success(
				array(
					'message' => __( '¡Gracias por tu mensaje! Te responderemos pronto.', 'reforestamos-comunicacion' ),
				)
			);
		} else {
			// Requirement 9.6: Log error and display user-friendly error message
			error_log(
				sprintf(
					'Reforestamos Comunicación: Failed to send contact form email. User: %s (%s), Time: %s',
					$nombre,
					$email,
					current_time( 'mysql' )
				)
			);

			wp_send_json_error(
				array(
					'message' => __( 'Lo sentimos, hubo un error al enviar tu mensaje. Por favor intenta de nuevo más tarde.', 'reforestamos-comunicacion' ),
				)
			);
		}
	}

	/**
	 * Validate form data
	 *
	 * @param string $nombre Name field.
	 * @param string $email Email field.
	 * @param string $asunto Subject field.
	 * @param string $mensaje Message field.
	 * @return array Array of validation error messages.
	 */
	private function validate_form_data( $nombre, $email, $asunto, $mensaje ) {
		$errors = array();

		// Validate nombre (name)
		if ( empty( $nombre ) ) {
			$errors[] = __( 'El campo nombre es requerido.', 'reforestamos-comunicacion' );
		} elseif ( mb_strlen( $nombre ) < 2 ) {
			$errors[] = __( 'El nombre debe tener al menos 2 caracteres.', 'reforestamos-comunicacion' );
		} elseif ( mb_strlen( $nombre ) > 100 ) {
			$errors[] = __( 'El nombre no puede tener más de 100 caracteres.', 'reforestamos-comunicacion' );
		}

		// Validate email
		if ( empty( $email ) ) {
			$errors[] = __( 'El campo email es requerido.', 'reforestamos-comunicacion' );
		} elseif ( ! is_email( $email ) ) {
			$errors[] = __( 'El formato del email no es válido.', 'reforestamos-comunicacion' );
		}

		// Validate asunto (subject)
		if ( empty( $asunto ) ) {
			$errors[] = __( 'El campo asunto es requerido.', 'reforestamos-comunicacion' );
		} elseif ( mb_strlen( $asunto ) < 3 ) {
			$errors[] = __( 'El asunto debe tener al menos 3 caracteres.', 'reforestamos-comunicacion' );
		} elseif ( mb_strlen( $asunto ) > 200 ) {
			$errors[] = __( 'El asunto no puede tener más de 200 caracteres.', 'reforestamos-comunicacion' );
		}

		// Validate mensaje (message)
		if ( empty( $mensaje ) ) {
			$errors[] = __( 'El campo mensaje es requerido.', 'reforestamos-comunicacion' );
		} elseif ( mb_strlen( $mensaje ) < 10 ) {
			$errors[] = __( 'El mensaje debe tener al menos 10 caracteres.', 'reforestamos-comunicacion' );
		} elseif ( mb_strlen( $mensaje ) > 5000 ) {
			$errors[] = __( 'El mensaje no puede tener más de 5000 caracteres.', 'reforestamos-comunicacion' );
		}

		return $errors;
	}

	/**
	 * Check rate limit for contact form submissions
	 *
	 * Implements rate limiting to prevent spam (Requirement 23.6)
	 * Limit: 3 submissions per IP every 15 minutes
	 *
	 * @return true|WP_Error True if within limit, WP_Error if exceeded
	 */
	private function check_rate_limit() {
		$ip            = $this->get_user_ip();
		$transient_key = 'contact_form_rate_limit_' . md5( $ip );
		$attempts      = get_transient( $transient_key );

		// If no attempts recorded, this is the first one
		if ( false === $attempts ) {
			return true;
		}

		// Check if limit exceeded (3 attempts per 15 minutes)
		if ( $attempts >= 3 ) {
			// Log rate limit exceeded
			error_log(
				sprintf(
					'Reforestamos Comunicación - Anti-Spam: Rate limit exceeded. IP: %s, Attempts: %d, Time: %s',
					$ip,
					$attempts,
					current_time( 'mysql' )
				)
			);

			return new WP_Error(
				'rate_limit_exceeded',
				__( 'Has enviado demasiados mensajes. Por favor espera unos minutos antes de intentar de nuevo.', 'reforestamos-comunicacion' )
			);
		}

		return true;
	}

	/**
	 * Increment rate limit counter
	 *
	 * Increments the submission counter for the current IP
	 * Counter expires after 15 minutes
	 */
	private function increment_rate_limit() {
		$ip            = $this->get_user_ip();
		$transient_key = 'contact_form_rate_limit_' . md5( $ip );
		$attempts      = get_transient( $transient_key );

		// If no attempts recorded, start at 1
		if ( false === $attempts ) {
			$attempts = 0;
		}

		// Increment and save for 15 minutes
		set_transient( $transient_key, $attempts + 1, 15 * MINUTE_IN_SECONDS );
	}

	/**
	 * Get user IP address
	 *
	 * Retrieves the user's IP address, checking for proxies
	 *
	 * @return string User IP address
	 */
	private function get_user_ip() {
		// Check for shared internet/ISP IP
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) && filter_var( $_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		}

		// Check for IPs passing through proxies
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			// Can contain multiple IPs, get the first one
			$ip_list = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
			$ip      = trim( $ip_list[0] );
			if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				return $ip;
			}
		}

		// Default to REMOTE_ADDR
		if ( ! empty( $_SERVER['REMOTE_ADDR'] ) && filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}

		// Fallback
		return '0.0.0.0';
	}

	/**
	 * Save form submission to database
	 *
	 * Saves all contact form submissions to the database as a backup.
	 * This happens BEFORE email sending, so submissions are preserved even if email fails.
	 * Requirement 9.8: Store form submissions in the database for backup
	 *
	 * @param string $nombre Name of the sender.
	 * @param string $email Email of the sender.
	 * @param string $asunto Subject of the message.
	 * @param string $mensaje Message content.
	 * @return int|false The number of rows inserted, or false on error.
	 */
	private function save_submission( $nombre, $email, $asunto, $mensaje ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_submissions';

		$result = $wpdb->insert(
			$table_name,
			array(
				'name'         => $nombre,
				'email'        => $email,
				'subject'      => $asunto,
				'message'      => $mensaje,
				'form_type'    => 'contact',
				'status'       => 'new',
				'submitted_at' => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( false === $result ) {
			// Log error if database insert fails
			error_log(
				sprintf(
					'Reforestamos Comunicación: Failed to save contact form submission to database. User: %s (%s), Time: %s, Error: %s',
					$nombre,
					$email,
					current_time( 'mysql' ),
					$wpdb->last_error
				)
			);
		}

		return $result;
	}
}
