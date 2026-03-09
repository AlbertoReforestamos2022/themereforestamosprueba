<?php
/**
 * DeepL Integration Class - Translation API Integration
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_DeepL_Integration class
 *
 * Handles integration with DeepL API for automatic content translation.
 * Provides translation functionality for posts, pages, and custom fields.
 */
class Reforestamos_DeepL_Integration {

	/**
	 * Single instance of the class
	 *
	 * @var Reforestamos_DeepL_Integration
	 */
	private static $instance = null;

	/**
	 * DeepL API endpoint
	 *
	 * @var string
	 */
	private $api_endpoint = 'https://api-free.deepl.com/v2/translate';

	/**
	 * Get singleton instance
	 *
	 * @return Reforestamos_DeepL_Integration
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
		// Add settings to admin menu
		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 20 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		
		// AJAX handler for testing connection
		add_action( 'wp_ajax_test_deepl_connection', array( $this, 'ajax_test_connection' ) );
		
		// AJAX handler for translating posts
		add_action( 'wp_ajax_translate_post', array( $this, 'ajax_translate_post' ) );
		
		// Add translation metabox to post editor
		add_action( 'add_meta_boxes', array( $this, 'add_translation_metabox' ) );
		
		// Enqueue admin scripts for translation metabox
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_translation_scripts' ) );
		
		// Schedule cron job for processing translation queue
		add_action( 'reforestamos_process_translation_queue', array( $this, 'process_queue' ) );
	}

	/**
	 * Add DeepL settings page to admin menu
	 */
	public function add_settings_page() {
		add_submenu_page(
			'reforestamos-comunicacion',
			__( 'Configuración DeepL', 'reforestamos-comunicacion' ),
			__( 'DeepL', 'reforestamos-comunicacion' ),
			'manage_options',
			'reforestamos-deepl-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register plugin settings
	 */
	public function register_settings() {
		register_setting(
			'reforestamos_deepl_settings',
			'reforestamos_deepl_api_key',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
			)
		);

		register_setting(
			'reforestamos_deepl_settings',
			'reforestamos_deepl_use_pro',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'rest_sanitize_boolean',
				'default'           => false,
			)
		);

		add_settings_section(
			'reforestamos_deepl_main',
			__( 'Configuración de API', 'reforestamos-comunicacion' ),
			array( $this, 'render_settings_section' ),
			'reforestamos_deepl_settings'
		);

		add_settings_field(
			'reforestamos_deepl_api_key',
			__( 'API Key', 'reforestamos-comunicacion' ),
			array( $this, 'render_api_key_field' ),
			'reforestamos_deepl_settings',
			'reforestamos_deepl_main'
		);

		add_settings_field(
			'reforestamos_deepl_use_pro',
			__( 'Usar API Pro', 'reforestamos-comunicacion' ),
			array( $this, 'render_use_pro_field' ),
			'reforestamos_deepl_settings',
			'reforestamos_deepl_main'
		);
	}

	/**
	 * Render settings section description
	 */
	public function render_settings_section() {
		echo '<p>' . esc_html__( 'Configure su API key de DeepL para habilitar la traducción automática de contenido.', 'reforestamos-comunicacion' ) . '</p>';
		echo '<p>' . sprintf(
			/* translators: %s: DeepL website URL */
			esc_html__( 'Puede obtener una API key gratuita en %s', 'reforestamos-comunicacion' ),
			'<a href="https://www.deepl.com/pro-api" target="_blank">DeepL Pro API</a>'
		) . '</p>';
	}

	/**
	 * Render API key field
	 */
	public function render_api_key_field() {
		$api_key = get_option( 'reforestamos_deepl_api_key', '' );
		?>
		<input type="text" 
			   name="reforestamos_deepl_api_key" 
			   id="reforestamos_deepl_api_key" 
			   value="<?php echo esc_attr( $api_key ); ?>" 
			   class="regular-text"
			   placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx:fx">
		<p class="description">
			<?php esc_html_e( 'Ingrese su API key de DeepL. Las claves gratuitas terminan en ":fx"', 'reforestamos-comunicacion' ); ?>
		</p>
		<?php
	}

	/**
	 * Render use pro field
	 */
	public function render_use_pro_field() {
		$use_pro = get_option( 'reforestamos_deepl_use_pro', false );
		?>
		<label>
			<input type="checkbox" 
				   name="reforestamos_deepl_use_pro" 
				   id="reforestamos_deepl_use_pro" 
				   value="1" 
				   <?php checked( $use_pro, true ); ?>>
			<?php esc_html_e( 'Usar endpoint de API Pro (api.deepl.com)', 'reforestamos-comunicacion' ); ?>
		</label>
		<p class="description">
			<?php esc_html_e( 'Marque esta opción si tiene una suscripción Pro de DeepL', 'reforestamos-comunicacion' ); ?>
		</p>
		<?php
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Check if settings were saved
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
				'reforestamos_deepl_messages',
				'reforestamos_deepl_message',
				__( 'Configuración guardada correctamente', 'reforestamos-comunicacion' ),
				'success'
			);
		}

		settings_errors( 'reforestamos_deepl_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<form action="options.php" method="post">
				<?php
				settings_fields( 'reforestamos_deepl_settings' );
				do_settings_sections( 'reforestamos_deepl_settings' );
				submit_button( __( 'Guardar Configuración', 'reforestamos-comunicacion' ) );
				?>
			</form>

			<?php if ( $this->is_configured() ) : ?>
				<hr>
				<h2><?php esc_html_e( 'Estado de la API', 'reforestamos-comunicacion' ); ?></h2>
				
				<?php
				// Get usage statistics
				$usage = $this->get_usage();
				if ( ! is_wp_error( $usage ) && isset( $usage['character_count'] ) && isset( $usage['character_limit'] ) ) {
					$used = intval( $usage['character_count'] );
					$limit = intval( $usage['character_limit'] );
					$percentage = $limit > 0 ? ( $used / $limit ) * 100 : 0;
					$remaining = $limit - $used;
					
					// Determine status color
					$status_class = 'notice-success';
					if ( $percentage >= 90 ) {
						$status_class = 'notice-error';
					} elseif ( $percentage >= 75 ) {
						$status_class = 'notice-warning';
					}
					?>
					<div class="notice <?php echo esc_attr( $status_class ); ?> inline">
						<p>
							<strong><?php esc_html_e( 'Uso de caracteres:', 'reforestamos-comunicacion' ); ?></strong>
							<?php
							echo sprintf(
								/* translators: 1: Used characters, 2: Character limit, 3: Percentage */
								esc_html__( '%1$s / %2$s caracteres (%3$s%%)', 'reforestamos-comunicacion' ),
								number_format_i18n( $used ),
								number_format_i18n( $limit ),
								number_format_i18n( $percentage, 1 )
							);
							?>
							<br>
							<strong><?php esc_html_e( 'Caracteres restantes:', 'reforestamos-comunicacion' ); ?></strong>
							<?php echo number_format_i18n( $remaining ); ?>
						</p>
					</div>
					<?php
					
					// Show warning if approaching limit
					if ( $percentage >= 90 ) {
						?>
						<div class="notice notice-warning inline">
							<p>
								<?php esc_html_e( '⚠️ Advertencia: Está cerca de alcanzar su límite de caracteres. Las traducciones se agregarán a la cola si se excede el límite.', 'reforestamos-comunicacion' ); ?>
							</p>
						</div>
						<?php
					}
				}
				
				// Get queue statistics
				$queue_stats = $this->get_queue_status();
				$total_queued = $queue_stats['pending'] + $queue_stats['processing'] + $queue_stats['rate_limited'];
				
				if ( $total_queued > 0 || $queue_stats['completed'] > 0 || $queue_stats['failed'] > 0 ) {
					?>
					<h3><?php esc_html_e( 'Cola de Traducciones', 'reforestamos-comunicacion' ); ?></h3>
					<table class="widefat" style="max-width: 600px;">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Estado', 'reforestamos-comunicacion' ); ?></th>
								<th><?php esc_html_e( 'Cantidad', 'reforestamos-comunicacion' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if ( $queue_stats['pending'] > 0 ) : ?>
								<tr>
									<td><?php esc_html_e( '⏳ Pendientes', 'reforestamos-comunicacion' ); ?></td>
									<td><?php echo intval( $queue_stats['pending'] ); ?></td>
								</tr>
							<?php endif; ?>
							<?php if ( $queue_stats['processing'] > 0 ) : ?>
								<tr>
									<td><?php esc_html_e( '⚙️ Procesando', 'reforestamos-comunicacion' ); ?></td>
									<td><?php echo intval( $queue_stats['processing'] ); ?></td>
								</tr>
							<?php endif; ?>
							<?php if ( $queue_stats['rate_limited'] > 0 ) : ?>
								<tr>
									<td><?php esc_html_e( '⏱️ Límite de tasa', 'reforestamos-comunicacion' ); ?></td>
									<td><?php echo intval( $queue_stats['rate_limited'] ); ?></td>
								</tr>
							<?php endif; ?>
							<?php if ( $queue_stats['completed'] > 0 ) : ?>
								<tr>
									<td><?php esc_html_e( '✓ Completadas', 'reforestamos-comunicacion' ); ?></td>
									<td><?php echo intval( $queue_stats['completed'] ); ?></td>
								</tr>
							<?php endif; ?>
							<?php if ( $queue_stats['failed'] > 0 ) : ?>
								<tr>
									<td><?php esc_html_e( '✗ Fallidas', 'reforestamos-comunicacion' ); ?></td>
									<td><?php echo intval( $queue_stats['failed'] ); ?></td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
					<p class="description">
						<?php esc_html_e( 'La cola se procesa automáticamente cada hora. Las traducciones pendientes se procesarán cuando el límite de tasa se restablezca.', 'reforestamos-comunicacion' ); ?>
					</p>
					<?php
				}
				?>
				
				<hr>
				<h2><?php esc_html_e( 'Probar Conexión', 'reforestamos-comunicacion' ); ?></h2>
				<p><?php esc_html_e( 'Pruebe la conexión con la API de DeepL:', 'reforestamos-comunicacion' ); ?></p>
				<button type="button" class="button" id="test-deepl-connection">
					<?php esc_html_e( 'Probar Conexión', 'reforestamos-comunicacion' ); ?>
				</button>
				<div id="deepl-test-result" style="margin-top: 10px;"></div>

				<script>
				jQuery(document).ready(function($) {
					$('#test-deepl-connection').on('click', function() {
						var button = $(this);
						var resultDiv = $('#deepl-test-result');
						
						button.prop('disabled', true).text('<?php esc_html_e( 'Probando...', 'reforestamos-comunicacion' ); ?>');
						resultDiv.html('');

						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: 'test_deepl_connection',
								nonce: '<?php echo esc_js( wp_create_nonce( 'test_deepl_connection' ) ); ?>'
							},
							success: function(response) {
								if (response.success) {
									resultDiv.html('<div class="notice notice-success inline"><p>' + response.data.message + '</p></div>');
								} else {
									resultDiv.html('<div class="notice notice-error inline"><p>' + response.data.message + '</p></div>');
								}
							},
							error: function() {
								resultDiv.html('<div class="notice notice-error inline"><p><?php esc_html_e( 'Error al conectar con el servidor', 'reforestamos-comunicacion' ); ?></p></div>');
							},
							complete: function() {
								button.prop('disabled', false).text('<?php esc_html_e( 'Probar Conexión', 'reforestamos-comunicacion' ); ?>');
							}
						});
					});
				});
				</script>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Check if DeepL is configured
	 *
	 * @return bool True if API key is set.
	 */
	public function is_configured() {
		$api_key = get_option( 'reforestamos_deepl_api_key', '' );
		return ! empty( $api_key );
	}

	/**
	 * Get API endpoint based on configuration
	 *
	 * @return string API endpoint URL.
	 */
	private function get_api_endpoint() {
		$use_pro = get_option( 'reforestamos_deepl_use_pro', false );
		return $use_pro ? 'https://api.deepl.com/v2/translate' : 'https://api-free.deepl.com/v2/translate';
	}

	/**
	 * Translate text using DeepL API
	 *
	 * @param string $text Text to translate.
	 * @param string $target_lang Target language code (e.g., 'EN', 'ES').
	 * @param string $source_lang Optional. Source language code. Auto-detect if empty.
	 * @return array|WP_Error Translation result or error.
	 */
	public function translate_text( $text, $target_lang, $source_lang = '' ) {
		if ( ! $this->is_configured() ) {
			return new WP_Error(
				'deepl_not_configured',
				__( 'DeepL API no está configurado. Por favor configure su API key.', 'reforestamos-comunicacion' )
			);
		}

		if ( empty( $text ) ) {
			return new WP_Error(
				'empty_text',
				__( 'El texto a traducir no puede estar vacío.', 'reforestamos-comunicacion' )
			);
		}

		$api_key = get_option( 'reforestamos_deepl_api_key' );
		$endpoint = $this->get_api_endpoint();

		// Prepare request body
		$body = array(
			'text'        => array( $text ),
			'target_lang' => strtoupper( $target_lang ),
		);

		if ( ! empty( $source_lang ) ) {
			$body['source_lang'] = strtoupper( $source_lang );
		}

		// Make API request
		$response = wp_remote_post(
			$endpoint,
			array(
				'headers' => array(
					'Authorization' => 'DeepL-Auth-Key ' . $api_key,
					'Content-Type'  => 'application/json',
				),
				'body'    => wp_json_encode( $body ),
				'timeout' => 30,
			)
		);

		// Handle response and check for rate limits
		return $this->handle_api_response( $response );
	}


	/**
	 * Translate HTML content preserving tags
	 *
	 * @param string $html HTML content to translate.
	 * @param string $target_lang Target language code.
	 * @param string $source_lang Optional. Source language code.
	 * @return array|WP_Error Translation result or error.
	 */
	public function translate_html( $html, $target_lang, $source_lang = '' ) {
		if ( ! $this->is_configured() ) {
			return new WP_Error(
				'deepl_not_configured',
				__( 'DeepL API no está configurado. Por favor configure su API key.', 'reforestamos-comunicacion' )
			);
		}

		$api_key = get_option( 'reforestamos_deepl_api_key' );
		$endpoint = $this->get_api_endpoint();

		// Prepare request body with HTML tag handling
		$body = array(
			'text'        => array( $html ),
			'target_lang' => strtoupper( $target_lang ),
			'tag_handling' => 'html',
		);

		if ( ! empty( $source_lang ) ) {
			$body['source_lang'] = strtoupper( $source_lang );
		}

		// Make API request
		$response = wp_remote_post(
			$endpoint,
			array(
				'headers' => array(
					'Authorization' => 'DeepL-Auth-Key ' . $api_key,
					'Content-Type'  => 'application/json',
				),
				'body'    => wp_json_encode( $body ),
				'timeout' => 30,
			)
		);

		// Handle response and check for rate limits
		return $this->handle_api_response( $response );
	}

	/**
	 * Get usage statistics from DeepL API
	 *
	 * @return array|WP_Error Usage data or error.
	 */
	public function get_usage() {
		if ( ! $this->is_configured() ) {
			return new WP_Error(
				'deepl_not_configured',
				__( 'DeepL API no está configurado.', 'reforestamos-comunicacion' )
			);
		}

		$api_key = get_option( 'reforestamos_deepl_api_key' );
		$use_pro = get_option( 'reforestamos_deepl_use_pro', false );
		$endpoint = $use_pro ? 'https://api.deepl.com/v2/usage' : 'https://api-free.deepl.com/v2/usage';

		$response = wp_remote_get(
			$endpoint,
			array(
				'headers' => array(
					'Authorization' => 'DeepL-Auth-Key ' . $api_key,
				),
				'timeout' => 15,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		if ( $response_code !== 200 ) {
			return new WP_Error(
				'deepl_api_error',
				sprintf(
					/* translators: %d: HTTP status code */
					__( 'Error al obtener uso de API (%d)', 'reforestamos-comunicacion' ),
					$response_code
				)
			);
		}

		return json_decode( $response_body, true );
	}

	/**
	 * Test API connection
	 *
	 * @return bool True if connection successful.
	 */
	public function test_connection() {
		$result = $this->translate_text( 'Hello', 'ES', 'EN' );
		return ! is_wp_error( $result );
	}

	/**
	 * AJAX handler for testing DeepL connection
	 */
	public function ajax_test_connection() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'test_deepl_connection' ) ) {
			wp_send_json_error( array(
				'message' => __( 'Error de seguridad. Por favor recargue la página.', 'reforestamos-comunicacion' ),
			) );
		}

		// Check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => __( 'No tiene permisos para realizar esta acción.', 'reforestamos-comunicacion' ),
			) );
		}

		// Test connection
		$result = $this->translate_text( 'Hello', 'ES', 'EN' );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array(
				'message' => sprintf(
					/* translators: %s: Error message */
					__( 'Error de conexión: %s', 'reforestamos-comunicacion' ),
					$result->get_error_message()
				),
			) );
		}

		// Get usage info
		$usage = $this->get_usage();
		$usage_info = '';
		
		if ( ! is_wp_error( $usage ) && isset( $usage['character_count'] ) && isset( $usage['character_limit'] ) ) {
			$usage_info = sprintf(
				/* translators: 1: Used characters, 2: Character limit */
				__( ' Uso: %1$s / %2$s caracteres', 'reforestamos-comunicacion' ),
				number_format_i18n( $usage['character_count'] ),
				number_format_i18n( $usage['character_limit'] )
			);
		}

		wp_send_json_success( array(
			'message' => __( '✓ Conexión exitosa con DeepL API.', 'reforestamos-comunicacion' ) . $usage_info,
		) );
	}

	/**
	 * AJAX handler for translating posts
	 */
	public function ajax_translate_post() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'reforestamos_translate' ) ) {
			wp_send_json_error( array(
				'message' => __( 'Error de seguridad. Por favor recargue la página.', 'reforestamos-comunicacion' ),
			) );
		}

		// Check permissions
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array(
				'message' => __( 'No tiene permisos para realizar esta acción.', 'reforestamos-comunicacion' ),
			) );
		}

		// Get and validate post ID
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		if ( ! $post_id ) {
			wp_send_json_error( array(
				'message' => __( 'ID de post inválido.', 'reforestamos-comunicacion' ),
			) );
		}

		// Get post
		$post = get_post( $post_id );
		if ( ! $post ) {
			wp_send_json_error( array(
				'message' => __( 'Post no encontrado.', 'reforestamos-comunicacion' ),
			) );
		}

		// Get target language
		$target_lang = isset( $_POST['target_lang'] ) ? sanitize_text_field( $_POST['target_lang'] ) : '';
		if ( ! in_array( $target_lang, array( 'EN', 'ES' ) ) ) {
			wp_send_json_error( array(
				'message' => __( 'Idioma de destino inválido.', 'reforestamos-comunicacion' ),
			) );
		}

		// Determine source language (opposite of target)
		$source_lang = $target_lang === 'EN' ? 'ES' : 'EN';

		// Translate the post
		$result = $this->translate_post( $post, $target_lang, $source_lang );

		if ( is_wp_error( $result ) ) {
			$error_code = $result->get_error_code();
			
			// Check if it's a rate limit error
			if ( in_array( $error_code, array( 'deepl_rate_limit', 'deepl_quota_exceeded' ) ) ) {
				// Queue the translation for later processing
				$queue_id = $this->queue_translation( $post_id, $target_lang, $source_lang );
				
				if ( $queue_id ) {
					wp_send_json_success( array(
						'message' => __( '⏱ Traducción agregada a la cola. Se procesará automáticamente cuando el límite se restablezca.', 'reforestamos-comunicacion' ),
						'queued' => true,
						'queue_id' => $queue_id,
					) );
				}
			}
			
			// For other errors, return error message
			wp_send_json_error( array(
				'message' => $result->get_error_message(),
			) );
		}

		wp_send_json_success( array(
			'message' => sprintf(
				/* translators: %s: Target language name */
				__( '✓ Post traducido exitosamente a %s', 'reforestamos-comunicacion' ),
				$target_lang === 'EN' ? 'English' : 'Español'
			),
			'translated_post_id' => $result['translated_post_id'],
			'edit_link' => get_edit_post_link( $result['translated_post_id'], 'raw' ),
		) );
	}

	/**
	 * Translate a post to target language
	 *
	 * @param WP_Post $post Post to translate.
	 * @param string $target_lang Target language code (EN or ES).
	 * @param string $source_lang Source language code (EN or ES).
	 * @return array|WP_Error Array with translated_post_id on success, WP_Error on failure.
	 */
	private function translate_post( $post, $target_lang, $source_lang ) {
		// Check if translation already exists
		$translated_post_id = get_post_meta( $post->ID, '_translated_post_id', true );
		
		// Translate title (plain text)
		$title_result = $this->translate_text( $post->post_title, $target_lang, $source_lang );
		if ( is_wp_error( $title_result ) ) {
			return new WP_Error(
				'translation_failed',
				sprintf(
					/* translators: %s: Error message */
					__( 'Error al traducir el título: %s', 'reforestamos-comunicacion' ),
					$title_result->get_error_message()
				)
			);
		}

		// Translate content (HTML)
		$content_result = $this->translate_html( $post->post_content, $target_lang, $source_lang );
		if ( is_wp_error( $content_result ) ) {
			return new WP_Error(
				'translation_failed',
				sprintf(
					/* translators: %s: Error message */
					__( 'Error al traducir el contenido: %s', 'reforestamos-comunicacion' ),
					$content_result->get_error_message()
				)
			);
		}

		// Translate excerpt if exists (plain text)
		$translated_excerpt = '';
		if ( ! empty( $post->post_excerpt ) ) {
			$excerpt_result = $this->translate_text( $post->post_excerpt, $target_lang, $source_lang );
			if ( ! is_wp_error( $excerpt_result ) ) {
				$translated_excerpt = $excerpt_result['translated_text'];
			}
		}

		// Prepare translated post data
		$translated_post_data = array(
			'post_title'   => $title_result['translated_text'],
			'post_content' => $content_result['translated_text'],
			'post_excerpt' => $translated_excerpt,
			'post_type'    => $post->post_type,
			'post_status'  => $post->post_status,
			'post_author'  => $post->post_author,
		);

		// If translation exists, update it
		if ( $translated_post_id && get_post( $translated_post_id ) ) {
			$translated_post_data['ID'] = $translated_post_id;
			$result = wp_update_post( $translated_post_data, true );
			
			if ( is_wp_error( $result ) ) {
				return new WP_Error(
					'update_failed',
					sprintf(
						/* translators: %s: Error message */
						__( 'Error al actualizar el post traducido: %s', 'reforestamos-comunicacion' ),
						$result->get_error_message()
					)
				);
			}
			
			$translated_post_id = $result;
		} else {
			// Create new translated post
			$translated_post_id = wp_insert_post( $translated_post_data, true );
			
			if ( is_wp_error( $translated_post_id ) ) {
				return new WP_Error(
					'creation_failed',
					sprintf(
						/* translators: %s: Error message */
						__( 'Error al crear el post traducido: %s', 'reforestamos-comunicacion' ),
						$translated_post_id->get_error_message()
					)
				);
			}

			// Copy featured image if exists
			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			if ( $thumbnail_id ) {
				set_post_thumbnail( $translated_post_id, $thumbnail_id );
			}

			// Copy categories and tags for posts
			if ( $post->post_type === 'post' ) {
				$categories = wp_get_post_categories( $post->ID );
				if ( ! empty( $categories ) ) {
					wp_set_post_categories( $translated_post_id, $categories );
				}

				$tags = wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) );
				if ( ! empty( $tags ) ) {
					wp_set_post_tags( $translated_post_id, $tags );
				}
			}

			// Copy taxonomies for custom post types
			$taxonomies = get_object_taxonomies( $post->post_type );
			foreach ( $taxonomies as $taxonomy ) {
				$terms = wp_get_post_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					wp_set_post_terms( $translated_post_id, $terms, $taxonomy );
				}
			}
		}

		// Translate custom fields
		$this->translate_custom_fields( $post->ID, $translated_post_id, $target_lang, $source_lang );

		// Link posts with post meta
		// Set translated post ID on original post
		update_post_meta( $post->ID, '_translated_post_id', $translated_post_id );
		
		// Set original post ID on translated post
		update_post_meta( $translated_post_id, '_original_post_id', $post->ID );
		
		// Set translation language on translated post
		update_post_meta( $translated_post_id, '_translation_lang', $target_lang );

		return array(
			'translated_post_id' => $translated_post_id,
		);
	}

	/**
	 * Add translation metabox to post editor
	 */
	public function add_translation_metabox() {
		// Only show metabox if DeepL is configured
		if ( ! $this->is_configured() ) {
			return;
		}

		$post_types = array( 'post', 'page', 'empresas', 'eventos', 'integrantes', 'boletin' );

		foreach ( $post_types as $post_type ) {
			add_meta_box(
				'reforestamos_translation',
				__( 'Traducción Automática', 'reforestamos-comunicacion' ),
				array( $this, 'render_translation_metabox' ),
				$post_type,
				'side',
				'default'
			);
		}
	}

	/**
	 * Render translation metabox
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render_translation_metabox( $post ) {
		// Add nonce for security
		wp_nonce_field( 'reforestamos_translate', 'translate_nonce' );

		// Get translated post ID if exists
		$translated_post_id = get_post_meta( $post->ID, '_translated_post_id', true );
		$original_post_id = get_post_meta( $post->ID, '_original_post_id', true );
		$translation_lang = get_post_meta( $post->ID, '_translation_lang', true );

		// Determine current post language
		$current_lang = 'ES'; // Default to Spanish
		if ( $translation_lang ) {
			$current_lang = $translation_lang;
		} elseif ( $original_post_id ) {
			$current_lang = 'ES'; // If this is a translation, original is Spanish
		}

		?>
		<div class="reforestamos-translation-box">
			<div class="translation-info" style="margin-bottom: 15px;">
				<p style="margin: 0 0 10px 0;">
					<strong><?php esc_html_e( 'Idioma actual:', 'reforestamos-comunicacion' ); ?></strong>
					<span class="current-language">
						<?php echo $current_lang === 'EN' ? '🇬🇧 English' : '🇪🇸 Español'; ?>
					</span>
				</p>

				<?php if ( $translated_post_id && get_post( $translated_post_id ) ) : ?>
					<p style="margin: 0 0 10px 0; padding: 8px; background: #f0f0f1; border-radius: 4px;">
						<strong><?php esc_html_e( 'Estado:', 'reforestamos-comunicacion' ); ?></strong>
						<span style="color: #2271b1;">
							<?php esc_html_e( '✓ Traducción existente', 'reforestamos-comunicacion' ); ?>
						</span>
						<br>
						<a href="<?php echo esc_url( get_edit_post_link( $translated_post_id ) ); ?>" target="_blank" style="text-decoration: none;">
							<?php esc_html_e( 'Ver traducción →', 'reforestamos-comunicacion' ); ?>
						</a>
					</p>
				<?php elseif ( $original_post_id && get_post( $original_post_id ) ) : ?>
					<p style="margin: 0 0 10px 0; padding: 8px; background: #f0f0f1; border-radius: 4px;">
						<strong><?php esc_html_e( 'Original:', 'reforestamos-comunicacion' ); ?></strong>
						<br>
						<a href="<?php echo esc_url( get_edit_post_link( $original_post_id ) ); ?>" target="_blank" style="text-decoration: none;">
							<?php esc_html_e( 'Ver original →', 'reforestamos-comunicacion' ); ?>
						</a>
					</p>
				<?php else : ?>
					<p style="margin: 0 0 10px 0; color: #646970;">
						<?php esc_html_e( 'Sin traducción', 'reforestamos-comunicacion' ); ?>
					</p>
				<?php endif; ?>
			</div>

			<div class="translation-buttons" style="margin-bottom: 10px;">
				<button type="button" class="button button-primary button-large" id="translate-to-english" style="width: 100%; margin-bottom: 8px;">
					🇬🇧 <?php esc_html_e( 'Translate to English', 'reforestamos-comunicacion' ); ?>
				</button>

				<button type="button" class="button button-large" id="translate-to-spanish" style="width: 100%;">
					🇪🇸 <?php esc_html_e( 'Traducir a Español', 'reforestamos-comunicacion' ); ?>
				</button>
			</div>

			<div class="translation-status" style="margin-top: 10px; padding: 8px; border-radius: 4px; display: none;"></div>

			<p class="description" style="margin-top: 10px; font-size: 12px; color: #646970;">
				<?php esc_html_e( 'La traducción creará o actualizará un post vinculado en el idioma seleccionado.', 'reforestamos-comunicacion' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts for translation metabox
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_translation_scripts( $hook ) {
		// Only load on post edit screens
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
			return;
		}

		// Only load if DeepL is configured
		if ( ! $this->is_configured() ) {
			return;
		}

		// Enqueue inline script for translation functionality
		wp_add_inline_script( 'jquery', $this->get_translation_script() );
	}

	/**
	 * Get translation JavaScript code
	 *
	 * @return string JavaScript code.
	 */
	private function get_translation_script() {
		ob_start();
		?>
		jQuery(document).ready(function($) {
			$('#translate-to-english, #translate-to-spanish').on('click', function(e) {
				e.preventDefault();
				
				const targetLang = $(this).attr('id') === 'translate-to-english' ? 'EN' : 'ES';
				const $status = $('.translation-status');
				const $button = $(this);
				const $allButtons = $('#translate-to-english, #translate-to-spanish');
				
				// Get post ID from URL or hidden field
				const postId = $('#post_ID').val();
				
				if (!postId) {
					$status.html('<span style="color: #d63638;">✗ <?php echo esc_js( __( 'Error: No se pudo obtener el ID del post', 'reforestamos-comunicacion' ) ); ?></span>').show();
					return;
				}
				
				// Get nonce
				const nonce = $('#translate_nonce').val();
				if (!nonce) {
					$status.html('<span style="color: #d63638;">✗ <?php echo esc_js( __( 'Error de seguridad: nonce no encontrado', 'reforestamos-comunicacion' ) ); ?></span>').show();
					return;
				}
				
				// Disable buttons and show loading
				$allButtons.prop('disabled', true);
				$status.html('<span class="spinner is-active" style="float: none; margin: 0 5px 0 0;"></span><?php echo esc_js( __( 'Traduciendo contenido...', 'reforestamos-comunicacion' ) ); ?>').css({
					'display': 'block',
					'background': '#f0f0f1',
					'color': '#2c3338'
				}).show();
				
				// Make AJAX request
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'translate_post',
						post_id: postId,
						target_lang: targetLang,
						nonce: nonce
					},
					success: function(response) {
						if (response.success) {
							// Show success message with link
							const langName = targetLang === 'EN' ? 'English' : 'Español';
							const editLink = response.data.edit_link || '';
							
							let message = '<span style="color: #00a32a;">✓ ' + response.data.message + '</span>';
							if (editLink) {
								message += '<br><a href="' + editLink + '" target="_blank" style="text-decoration: none; color: #2271b1; margin-top: 5px; display: inline-block;"><?php echo esc_js( __( 'Ver post traducido →', 'reforestamos-comunicacion' ) ); ?></a>';
							}
							
							$status.html(message).css({
								'background': '#d5f4e6',
								'color': '#2c3338'
							});
							
							// Update translation info if needed
							setTimeout(function() {
								location.reload();
							}, 2000);
						} else {
							// Show error message
							$status.html('<span style="color: #d63638;">✗ ' + response.data.message + '</span>').css({
								'background': '#fcf0f1',
								'color': '#2c3338'
							});
							$allButtons.prop('disabled', false);
						}
					},
					error: function(xhr, status, error) {
						$status.html('<span style="color: #d63638;">✗ <?php echo esc_js( __( 'Error de conexión. Por favor intente nuevamente.', 'reforestamos-comunicacion' ) ); ?></span>').css({
							'background': '#fcf0f1',
							'color': '#2c3338'
						});
						$allButtons.prop('disabled', false);
					}
				});
			});
		});
		<?php
		return ob_get_clean();
	}

	/**
	 * Get list of translatable custom field keys for a post type
	 *
	 * Returns an array of custom field keys that should be translated.
	 * Only includes text-based fields, excluding URLs, emails, numbers, dates, files, etc.
	 *
	 * @param string $post_type Post type to get translatable fields for.
	 * @return array Array of field keys that should be translated.
	 */
	private function get_translatable_fields( $post_type ) {
		$translatable_fields = array();

		switch ( $post_type ) {
			case 'eventos':
				// Only ubicacion (location) is translatable text
				$translatable_fields = array( 'evento_ubicacion' );
				break;

			case 'integrantes':
				// Only cargo (position/role) is translatable text
				$translatable_fields = array( 'integrante_cargo' );
				break;

			case 'empresas':
				// No translatable text fields in empresas
				// (logo, url, categoria, anio, arboles are all non-translatable)
				$translatable_fields = array();
				break;

			case 'boletin':
				// No translatable text fields in boletin
				// (fecha_envio, estado, destinatarios are all non-translatable)
				$translatable_fields = array();
				break;

			default:
				// For other post types, no custom fields to translate
				$translatable_fields = array();
				break;
		}

		/**
		 * Filter the list of translatable custom fields for a post type
		 *
		 * Allows other plugins or themes to add or remove translatable fields.
		 *
		 * @param array  $translatable_fields Array of field keys to translate.
		 * @param string $post_type           Post type being translated.
		 */
		return apply_filters( 'reforestamos_translatable_fields', $translatable_fields, $post_type );
	}

	/**
	 * Translate custom fields from source post to translated post
	 *
	 * Identifies translatable custom fields, translates their values,
	 * and copies them to the translated post. Non-translatable fields
	 * are copied as-is to maintain data integrity.
	 *
	 * @param int    $source_post_id      Source post ID.
	 * @param int    $translated_post_id  Translated post ID.
	 * @param string $target_lang         Target language code.
	 * @param string $source_lang         Source language code.
	 * @return void
	 */
	private function translate_custom_fields( $source_post_id, $translated_post_id, $target_lang, $source_lang ) {
		// Get post type
		$post = get_post( $source_post_id );
		if ( ! $post ) {
			return;
		}

		$post_type = $post->post_type;

		// Get all custom fields from source post
		$all_meta = get_post_meta( $source_post_id );
		if ( empty( $all_meta ) ) {
			return;
		}

		// Get list of translatable fields for this post type
		$translatable_fields = $this->get_translatable_fields( $post_type );

		// Process each meta field
		foreach ( $all_meta as $meta_key => $meta_values ) {
			// Skip WordPress internal meta fields (starting with _)
			// except for our custom fields which use underscore prefix
			if ( strpos( $meta_key, '_' ) === 0 && ! in_array( $meta_key, $translatable_fields ) ) {
				// Check if it's a CMB2 field (without underscore prefix)
				$cmb2_key = ltrim( $meta_key, '_' );
				if ( ! in_array( $cmb2_key, $translatable_fields ) ) {
					continue;
				}
				$meta_key = $cmb2_key;
			}

			// Get the first value (WordPress stores meta as arrays)
			$meta_value = isset( $meta_values[0] ) ? $meta_values[0] : '';

			// Skip empty values
			if ( empty( $meta_value ) ) {
				continue;
			}

			// Check if this field should be translated
			if ( in_array( $meta_key, $translatable_fields ) ) {
				// Translate the field value
				$translation_result = $this->translate_text( $meta_value, $target_lang, $source_lang );

				if ( ! is_wp_error( $translation_result ) && isset( $translation_result['translated_text'] ) ) {
					// Update with translated value
					update_post_meta( $translated_post_id, $meta_key, $translation_result['translated_text'] );
				} else {
					// If translation fails, log it but continue with other fields
					// Copy original value as fallback
					update_post_meta( $translated_post_id, $meta_key, $meta_value );

					// Log the error for debugging
					if ( is_wp_error( $translation_result ) ) {
						error_log(
							sprintf(
								'DeepL Translation: Failed to translate custom field "%s" for post %d: %s',
								$meta_key,
								$source_post_id,
								$translation_result->get_error_message()
							)
						);
					}
				}
			} else {
				// For non-translatable fields, copy as-is to maintain data integrity
				// This includes: URLs, emails, numbers, dates, files, selects, checkboxes
				update_post_meta( $translated_post_id, $meta_key, $meta_value );
			}
		}
	}

	/**
	 * Handle API response and check for rate limits
	 *
	 * @param array|WP_Error $response API response.
	 * @return array|WP_Error Parsed response data or error.
	 */
	private function handle_api_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		// Check for rate limit (429)
		if ( $response_code === 429 ) {
			$retry_after = wp_remote_retrieve_header( $response, 'retry-after' );
			return new WP_Error(
				'deepl_rate_limit',
				__( 'Límite de tasa de API alcanzado. La traducción se ha agregado a la cola.', 'reforestamos-comunicacion' ),
				array(
					'retry_after' => $retry_after ? intval( $retry_after ) : 3600,
					'status' => 429,
				)
			);
		}

		// Check for quota exceeded (456)
		if ( $response_code === 456 ) {
			return new WP_Error(
				'deepl_quota_exceeded',
				__( 'Cuota de caracteres de DeepL excedida. Por favor actualice su plan o espere al próximo período.', 'reforestamos-comunicacion' ),
				array( 'status' => 456 )
			);
		}

		// Check for other errors
		if ( $response_code !== 200 ) {
			$error_data = json_decode( $response_body, true );
			$error_message = isset( $error_data['message'] ) ? $error_data['message'] : __( 'Error desconocido de la API', 'reforestamos-comunicacion' );
			
			return new WP_Error(
				'deepl_api_error',
				sprintf(
					/* translators: 1: HTTP status code, 2: Error message */
					__( 'Error de DeepL API (%1$d): %2$s', 'reforestamos-comunicacion' ),
					$response_code,
					$error_message
				)
			);
		}

		// Parse successful response
		$data = json_decode( $response_body, true );

		if ( ! isset( $data['translations'][0]['text'] ) ) {
			return new WP_Error(
				'invalid_response',
				__( 'Respuesta inválida de la API de DeepL', 'reforestamos-comunicacion' )
			);
		}

		return array(
			'translated_text' => $data['translations'][0]['text'],
			'detected_source_language' => isset( $data['translations'][0]['detected_source_language'] ) 
				? $data['translations'][0]['detected_source_language'] 
				: '',
		);
	}

	/**
	 * Create translation queue table
	 */
	public function create_queue_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_translation_queue';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			post_id bigint(20) NOT NULL,
			target_lang varchar(2) NOT NULL,
			source_lang varchar(2) NOT NULL,
			status varchar(20) DEFAULT 'pending',
			priority int(11) DEFAULT 0,
			retry_count int(11) DEFAULT 0,
			error_message text,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			processed_at datetime,
			PRIMARY KEY (id),
			KEY post_id (post_id),
			KEY status (status)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Queue a translation for later processing
	 *
	 * @param int    $post_id     Post ID to translate.
	 * @param string $target_lang Target language code.
	 * @param string $source_lang Source language code.
	 * @param int    $priority    Priority (higher = processed first).
	 * @return int|false Queue item ID on success, false on failure.
	 */
	public function queue_translation( $post_id, $target_lang, $source_lang, $priority = 0 ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_translation_queue';

		// Check if already queued
		$existing = $wpdb->get_var( $wpdb->prepare(
			"SELECT id FROM $table_name WHERE post_id = %d AND target_lang = %s AND status IN ('pending', 'processing', 'rate_limited')",
			$post_id,
			$target_lang
		) );

		if ( $existing ) {
			return $existing;
		}

		// Insert into queue
		$result = $wpdb->insert(
			$table_name,
			array(
				'post_id' => $post_id,
				'target_lang' => $target_lang,
				'source_lang' => $source_lang,
				'status' => 'pending',
				'priority' => $priority,
				'created_at' => current_time( 'mysql' ),
			),
			array( '%d', '%s', '%s', '%s', '%d', '%s' )
		);

		return $result ? $wpdb->insert_id : false;
	}

	/**
	 * Process translation queue
	 *
	 * Processes up to 10 pending translations from the queue.
	 * Called by WP-Cron hourly.
	 */
	public function process_queue() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_translation_queue';

		// Get pending translations (up to 10, ordered by priority and creation date)
		$queue_items = $wpdb->get_results(
			"SELECT * FROM $table_name 
			WHERE status IN ('pending', 'rate_limited') 
			ORDER BY priority DESC, created_at ASC 
			LIMIT 10"
		);

		if ( empty( $queue_items ) ) {
			return;
		}

		foreach ( $queue_items as $item ) {
			// Mark as processing
			$wpdb->update(
				$table_name,
				array( 'status' => 'processing' ),
				array( 'id' => $item->id ),
				array( '%s' ),
				array( '%d' )
			);

			// Get post
			$post = get_post( $item->post_id );
			if ( ! $post ) {
				// Post doesn't exist, mark as failed
				$wpdb->update(
					$table_name,
					array(
						'status' => 'failed',
						'error_message' => __( 'Post no encontrado', 'reforestamos-comunicacion' ),
						'processed_at' => current_time( 'mysql' ),
					),
					array( 'id' => $item->id ),
					array( '%s', '%s', '%s' ),
					array( '%d' )
				);
				continue;
			}

			// Attempt translation
			$result = $this->translate_post( $post, $item->target_lang, $item->source_lang );

			if ( is_wp_error( $result ) ) {
				$error_code = $result->get_error_code();
				$retry_count = intval( $item->retry_count ) + 1;

				// Check if it's a rate limit error
				if ( $error_code === 'deepl_rate_limit' ) {
					// Mark as rate_limited and retry later
					$wpdb->update(
						$table_name,
						array(
							'status' => 'rate_limited',
							'retry_count' => $retry_count,
							'error_message' => $result->get_error_message(),
						),
						array( 'id' => $item->id ),
						array( '%s', '%d', '%s' ),
						array( '%d' )
					);
				} elseif ( $retry_count >= 3 ) {
					// Max retries reached, mark as failed
					$wpdb->update(
						$table_name,
						array(
							'status' => 'failed',
							'retry_count' => $retry_count,
							'error_message' => $result->get_error_message(),
							'processed_at' => current_time( 'mysql' ),
						),
						array( 'id' => $item->id ),
						array( '%s', '%d', '%s', '%s' ),
						array( '%d' )
					);
				} else {
					// Retry with exponential backoff
					$wpdb->update(
						$table_name,
						array(
							'status' => 'pending',
							'retry_count' => $retry_count,
							'error_message' => $result->get_error_message(),
						),
						array( 'id' => $item->id ),
						array( '%s', '%d', '%s' ),
						array( '%d' )
					);
				}
			} else {
				// Success
				$wpdb->update(
					$table_name,
					array(
						'status' => 'completed',
						'processed_at' => current_time( 'mysql' ),
					),
					array( 'id' => $item->id ),
					array( '%s', '%s' ),
					array( '%d' )
				);
			}

			// Small delay between translations to avoid hitting rate limits
			sleep( 2 );
		}
	}

	/**
	 * Get queue statistics
	 *
	 * @return array Queue statistics.
	 */
	public function get_queue_status() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_translation_queue';

		$stats = array(
			'pending' => 0,
			'processing' => 0,
			'completed' => 0,
			'failed' => 0,
			'rate_limited' => 0,
		);

		$results = $wpdb->get_results(
			"SELECT status, COUNT(*) as count FROM $table_name GROUP BY status"
		);

		foreach ( $results as $row ) {
			if ( isset( $stats[ $row->status ] ) ) {
				$stats[ $row->status ] = intval( $row->count );
			}
		}

		return $stats;
	}

	/**
	 * Retry a failed translation
	 *
	 * @param int $queue_id Queue item ID.
	 * @return bool True on success, false on failure.
	 */
	public function retry_failed_translation( $queue_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'reforestamos_translation_queue';

		$result = $wpdb->update(
			$table_name,
			array(
				'status' => 'pending',
				'retry_count' => 0,
				'error_message' => null,
			),
			array( 'id' => $queue_id ),
			array( '%s', '%d', '%s' ),
			array( '%d' )
		);

		return $result !== false;
	}
}
