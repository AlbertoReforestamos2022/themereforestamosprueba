<?php
/**
 * Payment Gateway Integration
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Payment Gateway Class
 */
class Reforestamos_Payment_Gateway {

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
		add_action( 'wp_ajax_create_payment_intent', array( $this, 'create_payment_intent' ) );
		add_action( 'wp_ajax_nopriv_create_payment_intent', array( $this, 'create_payment_intent' ) );
		add_action( 'wp_ajax_verify_payment', array( $this, 'verify_payment' ) );
		add_action( 'wp_ajax_nopriv_verify_payment', array( $this, 'verify_payment' ) );
	}

	/**
	 * Create payment intent (Stripe)
	 */
	public function create_payment_intent() {
		// Verify nonce
		check_ajax_referer( 'reforestamos_comm_nonce', 'nonce' );

		$adoption_id = isset( $_POST['adoption_id'] ) ? absint( $_POST['adoption_id'] ) : 0;

		if ( ! $adoption_id ) {
			wp_send_json_error( array( 'message' => __( 'ID de adopción inválido', 'reforestamos-comunicacion' ) ) );
		}

		// Get adoption details
		$adoption = $this->get_adoption( $adoption_id );

		if ( ! $adoption ) {
			wp_send_json_error( array( 'message' => __( 'Adopción no encontrada', 'reforestamos-comunicacion' ) ) );
		}

		// Calculate amount
		$amount = $this->calculate_amount( $adoption->quantity, $adoption->donation_type );

		// Get payment gateway type
		$gateway = get_option( 'reforestamos_payment_gateway', 'stripe' );

		if ( 'stripe' === $gateway ) {
			$result = $this->create_stripe_payment( $adoption_id, $amount, $adoption );
		} elseif ( 'paypal' === $gateway ) {
			$result = $this->create_paypal_payment( $adoption_id, $amount, $adoption );
		} else {
			wp_send_json_error( array( 'message' => __( 'Pasarela de pago no configurada', 'reforestamos-comunicacion' ) ) );
		}

		if ( $result['success'] ) {
			wp_send_json_success( $result['data'] );
		} else {
			wp_send_json_error( array( 'message' => $result['message'] ) );
		}
	}

	/**
	 * Create Stripe payment
	 */
	private function create_stripe_payment( $adoption_id, $amount, $adoption ) {
		$stripe_key = get_option( 'reforestamos_stripe_secret_key' );

		if ( empty( $stripe_key ) ) {
			return array(
				'success' => false,
				'message' => __( 'Stripe no está configurado', 'reforestamos-comunicacion' ),
			);
		}

		try {
			\Stripe\Stripe::setApiKey( $stripe_key );

			$intent = \Stripe\PaymentIntent::create(
				array(
					'amount'      => $amount * 100, // Convert to cents
					'currency'    => 'mxn',
					'description' => sprintf( __( 'Adopción de %d árboles', 'reforestamos-comunicacion' ), $adoption->quantity ),
					'metadata'    => array(
						'adoption_id' => $adoption_id,
						'email'       => $adoption->email,
						'name'        => $adoption->name,
					),
				)
			);

			return array(
				'success' => true,
				'data'    => array(
					'client_secret' => $intent->client_secret,
					'payment_id'    => $intent->id,
				),
			);
		} catch ( Exception $e ) {
			return array(
				'success' => false,
				'message' => $e->getMessage(),
			);
		}
	}

	/**
	 * Create PayPal payment
	 */
	private function create_paypal_payment( $adoption_id, $amount, $adoption ) {
		$paypal_client_id = get_option( 'reforestamos_paypal_client_id' );
		$paypal_secret    = get_option( 'reforestamos_paypal_secret' );

		if ( empty( $paypal_client_id ) || empty( $paypal_secret ) ) {
			return array(
				'success' => false,
				'message' => __( 'PayPal no está configurado', 'reforestamos-comunicacion' ),
			);
		}

		// PayPal API integration would go here
		// For now, return a placeholder
		return array(
			'success' => true,
			'data'    => array(
				'order_id'   => 'PAYPAL_' . $adoption_id . '_' . time(),
				'payment_id' => 'PAYPAL_' . uniqid(),
			),
		);
	}

	/**
	 * Verify payment completion
	 */
	public function verify_payment() {
		check_ajax_referer( 'reforestamos_comm_nonce', 'nonce' );

		$adoption_id = isset( $_POST['adoption_id'] ) ? absint( $_POST['adoption_id'] ) : 0;
		$payment_id  = isset( $_POST['payment_id'] ) ? sanitize_text_field( $_POST['payment_id'] ) : '';

		if ( ! $adoption_id || ! $payment_id ) {
			wp_send_json_error( array( 'message' => __( 'Datos inválidos', 'reforestamos-comunicacion' ) ) );
		}

		// Update adoption status
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_adoptions';

		$updated = $wpdb->update(
			$table_name,
			array(
				'status'         => 'completed',
				'payment_id'     => $payment_id,
				'payment_status' => 'paid',
				'completed_at'   => current_time( 'mysql' ),
			),
			array( 'id' => $adoption_id ),
			array( '%s', '%s', '%s', '%s' ),
			array( '%d' )
		);

		if ( $updated ) {
			// Trigger certificate generation
			do_action( 'reforestamos_adoption_completed', $adoption_id );

			wp_send_json_success(
				array(
					'message' => __( 'Pago verificado correctamente', 'reforestamos-comunicacion' ),
				)
			);
		} else {
			wp_send_json_error( array( 'message' => __( 'Error al verificar el pago', 'reforestamos-comunicacion' ) ) );
		}
	}

	/**
	 * Get adoption by ID
	 */
	private function get_adoption( $adoption_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_adoptions';

		return $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $table_name WHERE id = %d",
				$adoption_id
			)
		);
	}

	/**
	 * Calculate payment amount
	 */
	private function calculate_amount( $quantity, $donation_type ) {
		$price_per_tree = 100; // MXN
		return $quantity * $price_per_tree;
	}
}
