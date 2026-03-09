<?php
/**
 * Tree Adoption System
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tree Adoption Class
 */
class Reforestamos_Tree_Adoption {

	/**
	 * Single instance
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
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
		$this->init_hooks();
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_shortcode( 'tree-adoption-form', array( $this, 'render_form' ) );
		add_action( 'wp_ajax_process_tree_adoption', array( $this, 'process_adoption' ) );
		add_action( 'wp_ajax_nopriv_process_tree_adoption', array( $this, 'process_adoption' ) );
	}

	/**
	 * Render adoption form shortcode
	 */
	public function render_form( $atts ) {
		$atts = shortcode_atts(
			array(
				'title' => __( 'Adopta un Árbol', 'reforestamos-comunicacion' ),
			),
			$atts,
			'tree-adoption-form'
		);

		ob_start();
		?>
		<div class="reforestamos-adoption-form">
			<h3><?php echo esc_html( $atts['title'] ); ?></h3>
			
			<form id="tree-adoption-form" class="adoption-form" method="post">
				<?php wp_nonce_field( 'tree_adoption_form', 'adoption_nonce' ); ?>
				
				<div class="form-group mb-3">
					<label for="adoption-name"><?php esc_html_e( 'Nombre', 'reforestamos-comunicacion' ); ?> *</label>
					<input type="text" id="adoption-name" name="name" class="form-control" required>
				</div>
				
				<div class="form-group mb-3">
					<label for="adoption-email"><?php esc_html_e( 'Email', 'reforestamos-comunicacion' ); ?> *</label>
					<input type="email" id="adoption-email" name="email" class="form-control" required>
				</div>
				
				<div class="form-group mb-3">
					<label for="adoption-quantity"><?php esc_html_e( 'Cantidad de Árboles', 'reforestamos-comunicacion' ); ?> *</label>
					<input type="number" id="adoption-quantity" name="quantity" class="form-control" min="1" value="1" required>
					<small class="form-text text-muted"><?php esc_html_e( 'Precio por árbol: $100 MXN', 'reforestamos-comunicacion' ); ?></small>
				</div>
				
				<div class="form-group mb-3">
					<label for="adoption-type"><?php esc_html_e( 'Tipo de Donación', 'reforestamos-comunicacion' ); ?> *</label>
					<select id="adoption-type" name="donation_type" class="form-control" required>
						<option value="one-time"><?php esc_html_e( 'Una vez', 'reforestamos-comunicacion' ); ?></option>
						<option value="monthly"><?php esc_html_e( 'Mensual', 'reforestamos-comunicacion' ); ?></option>
						<option value="annual"><?php esc_html_e( 'Anual', 'reforestamos-comunicacion' ); ?></option>
					</select>
				</div>
				
				<div class="form-group mb-3">
					<label for="adoption-message"><?php esc_html_e( 'Mensaje (opcional)', 'reforestamos-comunicacion' ); ?></label>
					<textarea id="adoption-message" name="message" class="form-control" rows="3"></textarea>
				</div>
				
				<div class="form-group mb-3">
					<button type="submit" class="btn btn-primary btn-lg">
						<?php esc_html_e( 'Proceder al Pago', 'reforestamos-comunicacion' ); ?>
					</button>
				</div>
				
				<div class="adoption-messages"></div>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Process adoption form submission
	 */
	public function process_adoption() {
		// Verify nonce
		if ( ! isset( $_POST['adoption_nonce'] ) || ! wp_verify_nonce( $_POST['adoption_nonce'], 'tree_adoption_form' ) ) {
			wp_send_json_error( array( 'message' => __( 'Error de seguridad', 'reforestamos-comunicacion' ) ) );
		}

		// Rate limiting check
		if ( ! $this->check_rate_limit() ) {
			wp_send_json_error( array( 'message' => __( 'Demasiadas solicitudes. Por favor intenta más tarde.', 'reforestamos-comunicacion' ) ) );
		}

		// Validate and sanitize input
		$name          = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$email         = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$quantity      = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 0;
		$donation_type = isset( $_POST['donation_type'] ) ? sanitize_text_field( $_POST['donation_type'] ) : '';
		$message       = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

		// Validation
		$errors = array();

		if ( empty( $name ) ) {
			$errors[] = __( 'El nombre es requerido', 'reforestamos-comunicacion' );
		}

		if ( empty( $email ) || ! is_email( $email ) ) {
			$errors[] = __( 'Email inválido', 'reforestamos-comunicacion' );
		}

		if ( $quantity < 1 ) {
			$errors[] = __( 'La cantidad debe ser al menos 1', 'reforestamos-comunicacion' );
		}

		if ( ! in_array( $donation_type, array( 'one-time', 'monthly', 'annual' ), true ) ) {
			$errors[] = __( 'Tipo de donación inválido', 'reforestamos-comunicacion' );
		}

		if ( ! empty( $errors ) ) {
			wp_send_json_error( array( 'message' => implode( '<br>', $errors ) ) );
		}

		// Save adoption to database
		$adoption_id = $this->save_adoption( $name, $email, $quantity, $donation_type, $message );

		if ( ! $adoption_id ) {
			wp_send_json_error( array( 'message' => __( 'Error al guardar la adopción', 'reforestamos-comunicacion' ) ) );
		}

		// Send confirmation email
		$this->send_confirmation_email( $adoption_id, $name, $email, $quantity, $donation_type );

		// Return payment URL (will be implemented in payment integration)
		$payment_url = $this->get_payment_url( $adoption_id, $quantity, $donation_type );

		wp_send_json_success(
			array(
				'message'     => __( 'Adopción registrada correctamente', 'reforestamos-comunicacion' ),
				'adoption_id' => $adoption_id,
				'payment_url' => $payment_url,
			)
		);
	}

	/**
	 * Save adoption to database
	 */
	private function save_adoption( $name, $email, $quantity, $donation_type, $message ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_adoptions';

		$result = $wpdb->insert(
			$table_name,
			array(
				'name'          => $name,
				'email'         => $email,
				'quantity'      => $quantity,
				'donation_type' => $donation_type,
				'message'       => $message,
				'status'        => 'pending',
				'created_at'    => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%d', '%s', '%s', '%s', '%s' )
		);

		return $result ? $wpdb->insert_id : false;
	}

	/**
	 * Send confirmation email
	 */
	private function send_confirmation_email( $adoption_id, $name, $email, $quantity, $donation_type ) {
		$mailer = Reforestamos_Mailer::get_instance();

		$donation_types = array(
			'one-time' => __( 'Una vez', 'reforestamos-comunicacion' ),
			'monthly'  => __( 'Mensual', 'reforestamos-comunicacion' ),
			'annual'   => __( 'Anual', 'reforestamos-comunicacion' ),
		);

		$subject = __( 'Confirmación de Adopción de Árboles', 'reforestamos-comunicacion' );
		$message = sprintf(
			__( 'Hola %s,<br><br>Gracias por tu interés en adoptar árboles con Reforestamos México.<br><br>Detalles de tu adopción:<br>- Cantidad de árboles: %d<br>- Tipo de donación: %s<br>- ID de adopción: #%d<br><br>Por favor completa el pago para finalizar tu adopción.<br><br>Saludos,<br>Equipo Reforestamos México', 'reforestamos-comunicacion' ),
			$name,
			$quantity,
			$donation_types[ $donation_type ],
			$adoption_id
		);

		return $mailer->send_email( $email, $subject, $message );
	}

	/**
	 * Get payment URL (placeholder for payment integration)
	 */
	private function get_payment_url( $adoption_id, $quantity, $donation_type ) {
		// This will be implemented in payment gateway integration
		return add_query_arg(
			array(
				'adoption_id' => $adoption_id,
				'action'      => 'pay',
			),
			home_url( '/adopcion-pago/' )
		);
	}

	/**
	 * Create database table
	 */
	public static function create_table() {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'reforestamos_adoptions';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			email varchar(255) NOT NULL,
			quantity int(11) NOT NULL,
			donation_type varchar(20) NOT NULL,
			message text,
			status varchar(20) DEFAULT 'pending',
			payment_id varchar(255),
			payment_status varchar(20),
			certificate_generated tinyint(1) DEFAULT 0,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			completed_at datetime,
			PRIMARY KEY (id),
			KEY email (email),
			KEY status (status)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Check rate limit for form submissions
	 */
	private function check_rate_limit() {
		$ip          = $this->get_client_ip();
		$transient   = 'adoption_rate_limit_' . md5( $ip );
		$submissions = get_transient( $transient );

		if ( false === $submissions ) {
			$submissions = 0;
		}

		// Allow 3 submissions per hour
		if ( $submissions >= 3 ) {
			return false;
		}

		set_transient( $transient, $submissions + 1, HOUR_IN_SECONDS );
		return true;
	}

	/**
	 * Get client IP address
	 */
	private function get_client_ip() {
		$ip = '';

		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}

		return $ip;
	}
}
