<?php
/**
 * Certificate Generator
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Require TCPDF library
require_once REFORESTAMOS_COMM_PATH . 'vendor/tcpdf/tcpdf.php';

/**
 * Certificate Generator Class
 */
class Reforestamos_Certificate_Generator {

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
		add_action( 'reforestamos_adoption_completed', array( $this, 'generate_and_send_certificate' ) );
		add_action( 'wp_ajax_regenerate_certificate', array( $this, 'ajax_regenerate_certificate' ) );
	}

	/**
	 * Generate and send certificate
	 */
	public function generate_and_send_certificate( $adoption_id ) {
		$adoption = $this->get_adoption( $adoption_id );

		if ( ! $adoption || $adoption->certificate_generated ) {
			return;
		}

		// Generate PDF certificate
		$pdf_path = $this->generate_certificate( $adoption );

		if ( $pdf_path ) {
			// Send certificate via email
			$this->send_certificate_email( $adoption, $pdf_path );

			// Mark certificate as generated
			$this->mark_certificate_generated( $adoption_id );
		}
	}

	/**
	 * Generate PDF certificate
	 */
	private function generate_certificate( $adoption ) {
		// Create new PDF document
		$pdf = new TCPDF( 'L', 'mm', 'A4', true, 'UTF-8', false );

		// Set document information
		$pdf->SetCreator( 'Reforestamos México' );
		$pdf->SetAuthor( 'Reforestamos México' );
		$pdf->SetTitle( 'Certificado de Adopción de Árboles' );

		// Remove default header/footer
		$pdf->setPrintHeader( false );
		$pdf->setPrintFooter( false );

		// Set margins
		$pdf->SetMargins( 15, 15, 15 );

		// Add a page
		$pdf->AddPage();

		// Set font
		$pdf->SetFont( 'helvetica', '', 12 );

		// Certificate content
		$html = $this->get_certificate_html( $adoption );

		// Output the HTML content
		$pdf->writeHTML( $html, true, false, true, false, '' );

		// Save PDF to file
		$upload_dir = wp_upload_dir();
		$cert_dir   = $upload_dir['basedir'] . '/certificates/';

		if ( ! file_exists( $cert_dir ) ) {
			wp_mkdir_p( $cert_dir );
		}

		$filename = 'certificate_' . $adoption->id . '_' . time() . '.pdf';
		$filepath = $cert_dir . $filename;

		$pdf->Output( $filepath, 'F' );

		return $filepath;
	}

	/**
	 * Get certificate HTML content
	 */
	private function get_certificate_html( $adoption ) {
		$date = date_i18n( 'j \d\e F \d\e Y', strtotime( $adoption->completed_at ) );

		$html = '
		<style>
			.certificate {
				text-align: center;
				padding: 40px;
			}
			.certificate-title {
				font-size: 32px;
				font-weight: bold;
				color: #2E7D32;
				margin-bottom: 30px;
			}
			.certificate-body {
				font-size: 16px;
				line-height: 1.8;
				margin: 30px 0;
			}
			.recipient-name {
				font-size: 24px;
				font-weight: bold;
				color: #1B5E20;
				margin: 20px 0;
			}
			.tree-count {
				font-size: 20px;
				font-weight: bold;
				color: #2E7D32;
			}
			.certificate-footer {
				margin-top: 50px;
				font-size: 14px;
				color: #666;
			}
		</style>
		
		<div class="certificate">
			<div class="certificate-title">
				CERTIFICADO DE ADOPCIÓN DE ÁRBOLES
			</div>
			
			<div class="certificate-body">
				<p>Este certificado se otorga a:</p>
				
				<div class="recipient-name">' . esc_html( $adoption->name ) . '</div>
				
				<p>Por su valiosa contribución a la reforestación de México mediante la adopción de:</p>
				
				<div class="tree-count">' . absint( $adoption->quantity ) . ' ' . _n( 'árbol', 'árboles', $adoption->quantity, 'reforestamos-comunicacion' ) . '</div>
				
				<p>Su apoyo ayuda a restaurar los ecosistemas forestales y combatir el cambio climático.</p>
				
				<p>¡Gracias por ser parte del cambio!</p>
			</div>
			
			<div class="certificate-footer">
				<p>Certificado #' . str_pad( $adoption->id, 6, '0', STR_PAD_LEFT ) . '</p>
				<p>Fecha: ' . $date . '</p>
				<p>Reforestamos México A.C.</p>
			</div>
		</div>';

		return $html;
	}

	/**
	 * Send certificate via email
	 */
	private function send_certificate_email( $adoption, $pdf_path ) {
		$mailer = Reforestamos_Mailer::get_instance();

		$subject = __( 'Tu Certificado de Adopción de Árboles', 'reforestamos-comunicacion' );
		$message = sprintf(
			__( 'Hola %s,<br><br>¡Gracias por adoptar %d %s con Reforestamos México!<br><br>Adjunto encontrarás tu certificado de adopción.<br><br>Tu contribución está ayudando a restaurar los bosques de México y combatir el cambio climático.<br><br>Con gratitud,<br>Equipo Reforestamos México', 'reforestamos-comunicacion' ),
			$adoption->name,
			$adoption->quantity,
			_n( 'árbol', 'árboles', $adoption->quantity, 'reforestamos-comunicacion' )
		);

		// Send email with attachment
		return $mailer->send_email_with_attachment(
			$adoption->email,
			$subject,
			$message,
			$pdf_path
		);
	}

	/**
	 * Mark certificate as generated
	 */
	private function mark_certificate_generated( $adoption_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_adoptions';

		$wpdb->update(
			$table_name,
			array( 'certificate_generated' => 1 ),
			array( 'id' => $adoption_id ),
			array( '%d' ),
			array( '%d' )
		);
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
}

	/**
	 * AJAX handler for certificate regeneration
	 */
	public function ajax_regenerate_certificate() {
		check_ajax_referer( 'regenerate_certificate', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permisos insuficientes', 'reforestamos-comunicacion' ) ) );
		}

		$adoption_id = isset( $_POST['adoption_id'] ) ? absint( $_POST['adoption_id'] ) : 0;

		if ( ! $adoption_id ) {
			wp_send_json_error( array( 'message' => __( 'ID inválido', 'reforestamos-comunicacion' ) ) );
		}

		$this->generate_and_send_certificate( $adoption_id );

		wp_send_json_success( array( 'message' => __( 'Certificado generado y enviado', 'reforestamos-comunicacion' ) ) );
	}
}
