<?php
/**
 * ChatBot Class
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_ChatBot class
 *
 * Singleton pattern implementation for chatbot functionality.
 * Handles widget rendering, message processing, and conversation logging.
 */
class Reforestamos_ChatBot {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_ChatBot
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_ChatBot
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
		add_action( 'wp_footer', array( $this, 'render_chatbot_widget' ) );
		add_action( 'wp_ajax_chatbot_message', array( $this, 'handle_message' ) );
		add_action( 'wp_ajax_nopriv_chatbot_message', array( $this, 'handle_message' ) );
		
		// Admin AJAX handlers for flow management
		add_action( 'wp_ajax_save_chatbot_flow', array( $this, 'ajax_save_flow' ) );
		add_action( 'wp_ajax_delete_chatbot_flow', array( $this, 'ajax_delete_flow' ) );
		add_action( 'wp_ajax_reset_chatbot_flows', array( $this, 'ajax_reset_flows' ) );
		
		// Export handler
		add_action( 'admin_post_export_chatbot_logs', array( $this, 'export_logs_csv' ) );
	}

	/**
	 * Render chatbot widget in footer
	 *
	 * Requirements: 10.1, 10.3, 10.7
	 */
	public function render_chatbot_widget() {
		// Check if chatbot is enabled
		$is_enabled = get_option( 'reforestamos_chatbot_enabled', true );
		
		if ( ! $is_enabled ) {
			return;
		}

		// Generate unique session ID for this user
		$session_id = $this->get_session_id();
		
		?>
		<div id="reforestamos-chatbot" class="chatbot-container" data-session="<?php echo esc_attr( $session_id ); ?>">
			<div class="chatbot-toggle" id="chatbot-toggle">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
				</svg>
			</div>
			
			<div class="chatbot-window" id="chatbot-window" style="display: none;">
				<div class="chatbot-header">
					<h3><?php esc_html_e( 'Chat con Reforestamos', 'reforestamos-comunicacion' ); ?></h3>
					<button class="chatbot-close" id="chatbot-close">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</button>
				</div>
				
				<div class="chatbot-messages" id="chatbot-messages">
					<div class="chatbot-message bot-message">
						<div class="message-content">
							<?php esc_html_e( '¡Hola! ¿En qué puedo ayudarte hoy?', 'reforestamos-comunicacion' ); ?>
						</div>
					</div>
				</div>
				
				<div class="chatbot-input-container">
					<input 
						type="text" 
						id="chatbot-input" 
						class="chatbot-input" 
						placeholder="<?php esc_attr_e( 'Escribe tu mensaje...', 'reforestamos-comunicacion' ); ?>"
						maxlength="500"
					>
					<button id="chatbot-send" class="chatbot-send">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<line x1="22" y1="2" x2="11" y2="13"></line>
							<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
						</svg>
					</button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Handle chatbot message via AJAX
	 *
	 * Requirements: 10.4, 10.5, 10.6, 10.8
	 */
	public function handle_message() {
		// Verify nonce for security
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'chatbot_nonce' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Error de seguridad.', 'reforestamos-comunicacion' ),
				)
			);
		}

		// Get and sanitize message
		$user_message = isset( $_POST['message'] ) ? sanitize_text_field( $_POST['message'] ) : '';
		$session_id   = isset( $_POST['session_id'] ) ? sanitize_text_field( $_POST['session_id'] ) : '';

		// Validate message
		if ( empty( $user_message ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Por favor escribe un mensaje.', 'reforestamos-comunicacion' ),
				)
			);
		}

		// Process message and get response
		$bot_response = $this->process_message( $user_message );

		// Log conversation (Requirement 10.6)
		$this->log_conversation( $session_id, $user_message, $bot_response );

		// Return response (Requirement 10.8: within 2 seconds)
		wp_send_json_success(
			array(
				'response' => $bot_response,
			)
		);
	}

	/**
	 * Process user message and generate response
	 *
	 * Requirements: 10.4, 10.5
	 *
	 * @param string $message User message.
	 * @return string Bot response.
	 */
	private function process_message( $message ) {
		$message_lower = mb_strtolower( $message );

		// Check for conversation flow context
		$conversation_state = $this->get_conversation_state();
		
		// If in a conversation flow, process within that context
		if ( ! empty( $conversation_state['flow'] ) ) {
			$flow_response = $this->process_conversation_flow( $message_lower, $conversation_state );
			if ( $flow_response !== null ) {
				return $flow_response;
			}
		}

		// Get predefined responses from options
		$responses = get_option( 'reforestamos_chatbot_responses', $this->get_default_responses() );

		// Enhanced matching: try exact phrase match first
		$best_match = $this->find_best_match( $message_lower, $responses );
		
		if ( $best_match !== null ) {
			// Check if this response triggers a conversation flow
			$flow = $this->get_flow_for_response( $best_match );
			if ( $flow ) {
				$this->set_conversation_state( array( 'flow' => $flow, 'step' => 0 ) );
			}
			return $best_match;
		}

		// Default response if no match found
		return __( 'Gracias por tu mensaje. Para más información, puedes contactarnos directamente o visitar nuestra sección de preguntas frecuentes.', 'reforestamos-comunicacion' );
	}

	/**
	 * Find best matching response using enhanced matching algorithm
	 *
	 * @param string $message User message (lowercase).
	 * @param array  $responses Available responses.
	 * @return string|null Matched response or null.
	 */
	private function find_best_match( $message, $responses ) {
		$best_score = 0;
		$best_response = null;

		foreach ( $responses as $pattern => $response ) {
			$keywords = explode( '|', $pattern );
			$score = 0;
			$matches = 0;

			foreach ( $keywords as $keyword ) {
				$keyword = trim( $keyword );
				
				// Exact phrase match gets highest score
				if ( $message === $keyword ) {
					return $response;
				}
				
				// Contains keyword
				if ( strpos( $message, $keyword ) !== false ) {
					$matches++;
					// Longer keywords get higher scores
					$score += strlen( $keyword );
				}
			}

			// Calculate match quality
			if ( $matches > 0 ) {
				// Bonus for multiple keyword matches
				$score = $score * ( 1 + ( $matches * 0.5 ) );
				
				if ( $score > $best_score ) {
					$best_score = $score;
					$best_response = $response;
				}
			}
		}

		// Only return if we have a reasonable match
		return $best_score > 0 ? $best_response : null;
	}

	/**
	 * Process message within a conversation flow
	 *
	 * @param string $message User message (lowercase).
	 * @param array  $state Current conversation state.
	 * @return string|null Flow response or null to exit flow.
	 */
	private function process_conversation_flow( $message, $state ) {
		$flows = $this->get_conversation_flows();
		$flow_name = $state['flow'];
		$step = $state['step'];

		if ( ! isset( $flows[ $flow_name ] ) ) {
			$this->clear_conversation_state();
			return null;
		}

		$flow = $flows[ $flow_name ];

		// Check for exit keywords
		$exit_keywords = array( 'salir', 'cancelar', 'no gracias', 'atrás', 'volver' );
		foreach ( $exit_keywords as $keyword ) {
			if ( strpos( $message, $keyword ) !== false ) {
				$this->clear_conversation_state();
				return __( 'Entendido. ¿Hay algo más en lo que pueda ayudarte?', 'reforestamos-comunicacion' );
			}
		}

		// Process current step
		if ( isset( $flow['steps'][ $step ] ) ) {
			$current_step = $flow['steps'][ $step ];
			
			// Check if user response matches expected options
			if ( isset( $current_step['options'] ) ) {
				foreach ( $current_step['options'] as $option_key => $option_data ) {
					$option_keywords = explode( '|', $option_data['keywords'] );
					foreach ( $option_keywords as $keyword ) {
						if ( strpos( $message, trim( $keyword ) ) !== false ) {
							// Move to next step
							$next_step = $step + 1;
							
							// Check if there's a specific next step for this option
							if ( isset( $option_data['next_step'] ) ) {
								$next_step = $option_data['next_step'];
							}
							
							// Update state
							if ( $next_step < count( $flow['steps'] ) ) {
								$this->set_conversation_state( array( 
									'flow' => $flow_name, 
									'step' => $next_step,
									'data' => array_merge(
										isset( $state['data'] ) ? $state['data'] : array(),
										array( 'step_' . $step => $option_key )
									)
								) );
								return $flow['steps'][ $next_step ]['message'];
							} else {
								// Flow completed
								$this->clear_conversation_state();
								return isset( $option_data['response'] ) ? $option_data['response'] : $flow['completion_message'];
							}
						}
					}
				}
			}
			
			// If no option matched, provide help
			return $current_step['help_message'] ?? __( 'No entendí tu respuesta. Por favor, elige una de las opciones disponibles.', 'reforestamos-comunicacion' );
		}

		// Flow completed or invalid step
		$this->clear_conversation_state();
		return null;
	}

	/**
	 * Get conversation flows
	 *
	 * @return array Conversation flows.
	 */
	private function get_conversation_flows() {
		$flows = get_option( 'reforestamos_chatbot_flows', $this->get_default_flows() );
		return $flows;
	}

	/**
	 * Get default conversation flows
	 *
	 * @return array Default flows.
	 */
	private function get_default_flows() {
		return array(
			'volunteer' => array(
				'name' => __( 'Proceso de Voluntariado', 'reforestamos-comunicacion' ),
				'trigger_keywords' => 'voluntario|participar|colaborar',
				'steps' => array(
					array(
						'message' => __( '¡Excelente! Hay varias formas de participar. ¿Te interesa participar en eventos de reforestación o prefieres colaborar de otra manera?', 'reforestamos-comunicacion' ),
						'options' => array(
							'events' => array(
								'keywords' => 'evento|reforestación|plantar|árboles',
								'response' => __( 'Perfecto. Puedes ver nuestros próximos eventos en la sección de Eventos. ¿Te gustaría que te enviemos información sobre cómo registrarte?', 'reforestamos-comunicacion' ),
								'next_step' => 1,
							),
							'other' => array(
								'keywords' => 'otra|diferente|más información',
								'response' => __( 'También puedes colaborar con donaciones, difusión en redes sociales, o como voluntario en oficina. Visita nuestra página de Participación para más detalles.', 'reforestamos-comunicacion' ),
							),
						),
						'help_message' => __( 'Por favor, dime si te interesan los eventos de reforestación o si prefieres otra forma de colaborar.', 'reforestamos-comunicacion' ),
					),
					array(
						'message' => __( '¿Quieres que te contactemos por email con más información?', 'reforestamos-comunicacion' ),
						'options' => array(
							'yes' => array(
								'keywords' => 'sí|si|claro|por favor|ok|vale',
								'response' => __( 'Genial. Por favor, envíanos tu email a través de nuestro formulario de contacto y te enviaremos toda la información. ¡Gracias por tu interés!', 'reforestamos-comunicacion' ),
							),
							'no' => array(
								'keywords' => 'no|ahora no|después',
								'response' => __( 'Entendido. Puedes consultar toda la información en nuestra web cuando quieras. ¡Gracias por tu interés!', 'reforestamos-comunicacion' ),
							),
						),
					),
				),
				'completion_message' => __( '¡Gracias por tu interés en colaborar con Reforestamos México!', 'reforestamos-comunicacion' ),
			),
			'donation' => array(
				'name' => __( 'Proceso de Donación', 'reforestamos-comunicacion' ),
				'trigger_keywords' => 'donar|donación|apoyo económico',
				'steps' => array(
					array(
						'message' => __( '¡Gracias por tu interés en apoyarnos! ¿Te gustaría hacer una donación única o prefieres una donación recurrente?', 'reforestamos-comunicacion' ),
						'options' => array(
							'single' => array(
								'keywords' => 'única|una vez|ahora',
								'response' => __( 'Puedes hacer una donación única a través de nuestra página de donaciones. Aceptamos tarjeta de crédito, PayPal y transferencia bancaria.', 'reforestamos-comunicacion' ),
							),
							'recurring' => array(
								'keywords' => 'recurrente|mensual|periódica',
								'response' => __( 'Las donaciones recurrentes nos ayudan a planificar mejor nuestros proyectos. Puedes configurar una donación mensual en nuestra página de donaciones.', 'reforestamos-comunicacion' ),
							),
							'info' => array(
								'keywords' => 'información|más detalles|cómo',
								'response' => __( 'Tu donación se destina directamente a proyectos de reforestación, compra de árboles nativos, y educación ambiental. Visita nuestra página de Transparencia para ver cómo usamos los fondos.', 'reforestamos-comunicacion' ),
							),
						),
					),
				),
				'completion_message' => __( '¡Gracias por considerar apoyar nuestro trabajo!', 'reforestamos-comunicacion' ),
			),
			'events' => array(
				'name' => __( 'Información de Eventos', 'reforestamos-comunicacion' ),
				'trigger_keywords' => 'evento|eventos|actividad',
				'steps' => array(
					array(
						'message' => __( '¿Buscas información sobre eventos próximos o quieres saber cómo funcionan nuestras jornadas de reforestación?', 'reforestamos-comunicacion' ),
						'options' => array(
							'upcoming' => array(
								'keywords' => 'próximos|cuándo|fechas',
								'response' => __( 'Puedes ver todos nuestros eventos próximos en la sección de Eventos de nuestra web. Ahí encontrarás fechas, ubicaciones y cómo registrarte.', 'reforestamos-comunicacion' ),
							),
							'how' => array(
								'keywords' => 'cómo|funcionan|qué hacer',
								'response' => __( 'En nuestras jornadas proporcionamos todo lo necesario: árboles, herramientas y capacitación. Solo necesitas ropa cómoda, protector solar y muchas ganas. La duración típica es de 3-4 horas.', 'reforestamos-comunicacion' ),
							),
						),
					),
				),
				'completion_message' => __( '¡Esperamos verte en nuestro próximo evento!', 'reforestamos-comunicacion' ),
			),
		);
	}

	/**
	 * Check if a response triggers a conversation flow
	 *
	 * @param string $response Bot response.
	 * @return string|null Flow name or null.
	 */
	private function get_flow_for_response( $response ) {
		$flows = $this->get_conversation_flows();
		
		foreach ( $flows as $flow_name => $flow ) {
			if ( isset( $flow['trigger_keywords'] ) ) {
				// This is a simple check - in practice, you might want more sophisticated logic
				// For now, flows are triggered by the matching system
			}
		}
		
		return null;
	}

	/**
	 * Get current conversation state from session
	 *
	 * @return array Conversation state.
	 */
	private function get_conversation_state() {
		$session_id = $this->get_session_id();
		$state = get_transient( 'chatbot_state_' . $session_id );
		return $state ? $state : array();
	}

	/**
	 * Set conversation state
	 *
	 * @param array $state Conversation state.
	 */
	private function set_conversation_state( $state ) {
		$session_id = $this->get_session_id();
		// Store state for 30 minutes
		set_transient( 'chatbot_state_' . $session_id, $state, 30 * MINUTE_IN_SECONDS );
	}

	/**
	 * Clear conversation state
	 */
	private function clear_conversation_state() {
		$session_id = $this->get_session_id();
		delete_transient( 'chatbot_state_' . $session_id );
	}

	/**
	 * Get default chatbot responses
	 *
	 * @return array Default responses.
	 */
	private function get_default_responses() {
		return array(
			'hola|buenos días|buenas tardes|buenas noches' => __( '¡Hola! Bienvenido a Reforestamos México. ¿En qué puedo ayudarte?', 'reforestamos-comunicacion' ),
			'ayuda|información|info' => __( 'Puedo ayudarte con información sobre nuestros proyectos, eventos, cómo participar y más. ¿Qué te gustaría saber?', 'reforestamos-comunicacion' ),
			'proyecto|proyectos|iniciativa' => __( 'Trabajamos en diversos proyectos de reforestación en México. Visita nuestra página de proyectos para conocer más detalles.', 'reforestamos-comunicacion' ),
			'evento|eventos|actividad' => __( 'Organizamos eventos de reforestación regularmente. Consulta nuestra sección de eventos para ver las próximas actividades.', 'reforestamos-comunicacion' ),
			'voluntario|participar|colaborar|ayudar' => __( '¡Nos encantaría contar contigo! Puedes participar como voluntario en nuestros eventos o hacer una donación. Visita nuestra página de participación.', 'reforestamos-comunicacion' ),
			'donar|donación|apoyo económico' => __( 'Tu apoyo es muy valioso. Puedes hacer una donación a través de nuestra página de donaciones. ¡Gracias por tu interés!', 'reforestamos-comunicacion' ),
			'contacto|contactar|email|teléfono' => __( 'Puedes contactarnos a través de nuestro formulario de contacto o enviarnos un email. Encontrarás toda la información en nuestra página de contacto.', 'reforestamos-comunicacion' ),
			'ubicación|dónde|dirección' => __( 'Trabajamos en diversas ubicaciones de México. Consulta nuestro mapa de proyectos para ver las zonas donde tenemos presencia.', 'reforestamos-comunicacion' ),
			'gracias|muchas gracias' => __( '¡De nada! Estamos aquí para ayudarte. ¿Hay algo más en lo que pueda asistirte?', 'reforestamos-comunicacion' ),
			'adiós|hasta luego|chao' => __( '¡Hasta pronto! Gracias por tu interés en Reforestamos México.', 'reforestamos-comunicacion' ),
		);
	}

	/**
	 * Log conversation to database
	 *
	 * Requirement 10.6: Log all chatbot conversations for analysis
	 *
	 * @param string $session_id Session ID.
	 * @param string $user_message User message.
	 * @param string $bot_response Bot response.
	 */
	private function log_conversation( $session_id, $user_message, $bot_response ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_chatbot_logs';

		$wpdb->insert(
			$table_name,
			array(
				'session_id'   => $session_id,
				'user_message' => $user_message,
				'bot_response' => $bot_response,
				'created_at'   => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%s', '%s' )
		);
	}

	/**
	 * Get or create session ID for user
	 *
	 * @return string Session ID.
	 */
	private function get_session_id() {
		// Check if session already exists
		if ( isset( $_COOKIE['reforestamos_chatbot_session'] ) ) {
			return sanitize_text_field( $_COOKIE['reforestamos_chatbot_session'] );
		}

		// Generate new session ID
		$session_id = wp_generate_password( 32, false );
		
		// Set cookie for 24 hours
		setcookie( 'reforestamos_chatbot_session', $session_id, time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );

		return $session_id;
	}

	/**
	 * Render chatbot configuration page
	 *
	 * Requirement 10.2: Admin interface for configuring chatbot responses
	 */
	public static function render_config() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No tienes permisos para acceder a esta página.', 'reforestamos-comunicacion' ) );
		}

		// Handle form submission
		if ( isset( $_POST['reforestamos_chatbot_save'] ) && check_admin_referer( 'reforestamos_chatbot_config' ) ) {
			// Save enabled/disabled status
			$enabled = isset( $_POST['chatbot_enabled'] ) ? 1 : 0;
			update_option( 'reforestamos_chatbot_enabled', $enabled );

			// Save custom responses
			if ( isset( $_POST['chatbot_responses'] ) ) {
				$responses = array();
				foreach ( $_POST['chatbot_responses'] as $response ) {
					if ( ! empty( $response['pattern'] ) && ! empty( $response['response'] ) ) {
						$pattern = sanitize_text_field( $response['pattern'] );
						$text    = sanitize_textarea_field( $response['response'] );
						$responses[ $pattern ] = $text;
					}
				}
				update_option( 'reforestamos_chatbot_responses', $responses );
			}

			echo '<div class="notice notice-success"><p>' . esc_html__( 'Configuración guardada correctamente.', 'reforestamos-comunicacion' ) . '</p></div>';
		}

		// Get current settings
		$is_enabled = get_option( 'reforestamos_chatbot_enabled', true );
		$responses  = get_option( 'reforestamos_chatbot_responses', self::get_instance()->get_default_responses() );

		// Render configuration form
		include REFORESTAMOS_COMM_PATH . 'admin/views/chatbot-config.php';
	}

	/**
	 * Render chatbot logs page
	 *
	 * Requirement 10.6: Interface to view conversation logs
	 */
	public static function render_logs() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No tienes permisos para acceder a esta página.', 'reforestamos-comunicacion' ) );
		}

		// Render logs view
		include REFORESTAMOS_COMM_PATH . 'admin/views/chatbot-logs.php';
	}

	/**
	 * AJAX handler to save a conversation flow
	 *
	 * Requirement 10.2: Allow configuring conversation flows
	 */
	public function ajax_save_flow() {
		// Check nonce and permissions
		if ( ! check_ajax_referer( 'chatbot_flow_nonce', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permiso denegado.', 'reforestamos-comunicacion' ) ) );
		}

		// Get flow data
		$flow_id   = isset( $_POST['flow_id'] ) ? sanitize_key( $_POST['flow_id'] ) : '';
		$flow_data = isset( $_POST['flow_data'] ) ? json_decode( stripslashes( $_POST['flow_data'] ), true ) : array();

		if ( empty( $flow_id ) || empty( $flow_data ) ) {
			wp_send_json_error( array( 'message' => __( 'Datos inválidos.', 'reforestamos-comunicacion' ) ) );
		}

		// Get existing flows
		$flows = get_option( 'reforestamos_chatbot_flows', array() );
		if ( empty( $flows ) ) {
			$flows = $this->get_default_flows();
		}

		// Add or update flow
		$flows[ $flow_id ] = $flow_data;

		// Save flows
		update_option( 'reforestamos_chatbot_flows', $flows );

		wp_send_json_success( array( 'message' => __( 'Flujo guardado correctamente.', 'reforestamos-comunicacion' ) ) );
	}

	/**
	 * AJAX handler to delete a conversation flow
	 *
	 * Requirement 10.2: Allow managing conversation flows
	 */
	public function ajax_delete_flow() {
		// Check nonce and permissions
		if ( ! check_ajax_referer( 'chatbot_flow_nonce', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permiso denegado.', 'reforestamos-comunicacion' ) ) );
		}

		// Get flow ID
		$flow_id = isset( $_POST['flow_id'] ) ? sanitize_key( $_POST['flow_id'] ) : '';

		if ( empty( $flow_id ) ) {
			wp_send_json_error( array( 'message' => __( 'ID de flujo inválido.', 'reforestamos-comunicacion' ) ) );
		}

		// Get existing flows
		$flows = get_option( 'reforestamos_chatbot_flows', array() );
		if ( empty( $flows ) ) {
			$flows = $this->get_default_flows();
		}

		// Remove flow
		if ( isset( $flows[ $flow_id ] ) ) {
			unset( $flows[ $flow_id ] );
			update_option( 'reforestamos_chatbot_flows', $flows );
			wp_send_json_success( array( 'message' => __( 'Flujo eliminado correctamente.', 'reforestamos-comunicacion' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Flujo no encontrado.', 'reforestamos-comunicacion' ) ) );
		}
	}

	/**
	 * AJAX handler to reset flows to defaults
	 *
	 * Requirement 10.2: Allow resetting conversation flows
	 */
	public function ajax_reset_flows() {
		// Check nonce and permissions
		if ( ! check_ajax_referer( 'chatbot_flow_nonce', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permiso denegado.', 'reforestamos-comunicacion' ) ) );
		}

		// Reset to default flows
		$default_flows = $this->get_default_flows();
		update_option( 'reforestamos_chatbot_flows', $default_flows );

		wp_send_json_success( array( 'message' => __( 'Flujos restaurados correctamente.', 'reforestamos-comunicacion' ) ) );
	}

	/**
	 * Export chatbot logs to CSV
	 *
	 * Requirement 10.6: Allow exporting conversation logs for analysis
	 */
	public function export_logs_csv() {
		// Check permissions and nonce
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No tienes permisos para realizar esta acción.', 'reforestamos-comunicacion' ) );
		}

		if ( ! isset( $_POST['export_nonce'] ) || ! wp_verify_nonce( $_POST['export_nonce'], 'export_chatbot_logs' ) ) {
			wp_die( esc_html__( 'Error de seguridad.', 'reforestamos-comunicacion' ) );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_chatbot_logs';

		// Get all logs
		$logs = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A );

		if ( empty( $logs ) ) {
			wp_die( esc_html__( 'No hay logs para exportar.', 'reforestamos-comunicacion' ) );
		}

		// Set headers for CSV download
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=chatbot-logs-' . date( 'Y-m-d-His' ) . '.csv' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		// Open output stream
		$output = fopen( 'php://output', 'w' );

		// Add BOM for UTF-8
		fprintf( $output, chr(0xEF).chr(0xBB).chr(0xBF) );

		// Write CSV headers
		fputcsv( $output, array(
			__( 'ID', 'reforestamos-comunicacion' ),
			__( 'Sesión', 'reforestamos-comunicacion' ),
			__( 'Mensaje del Usuario', 'reforestamos-comunicacion' ),
			__( 'Respuesta del Bot', 'reforestamos-comunicacion' ),
			__( 'Fecha/Hora', 'reforestamos-comunicacion' ),
		) );

		// Write data rows
		foreach ( $logs as $log ) {
			fputcsv( $output, array(
				$log['id'],
				$log['session_id'],
				$log['user_message'],
				$log['bot_response'],
				$log['created_at'],
			) );
		}

		fclose( $output );
		exit;
	}
}
