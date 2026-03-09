<?php
/**
 * JSON Data Manager
 *
 * @package Reforestamos_Micrositios
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reforestamos_JSON_Manager class
 *
 * Handles JSON data file management and admin interface.
 */
class Reforestamos_JSON_Manager {

	/**
	 * Initialize hooks
	 */
	public static function init() {
		add_action( 'admin_post_reforestamos_upload_json', array( __CLASS__, 'handle_upload' ) );
		add_action( 'admin_post_reforestamos_save_json', array( __CLASS__, 'handle_save' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
	}

	/**
	 * Enqueue admin assets
	 */
	public static function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_reforestamos-micrositios' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'reforestamos-admin',
			REFORESTAMOS_MICRO_URL . 'admin/css/admin-styles.css',
			array(),
			REFORESTAMOS_MICRO_VERSION
		);

		wp_enqueue_script(
			'reforestamos-admin-uploader',
			REFORESTAMOS_MICRO_URL . 'admin/js/admin-uploader.js',
			array( 'jquery' ),
			REFORESTAMOS_MICRO_VERSION,
			true
		);

		wp_localize_script(
			'reforestamos-admin-uploader',
			'reforestamosAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'reforestamos_admin_nonce' ),
				'strings' => array(
					'confirmUpload' => __( '¿Estás seguro de que deseas subir este archivo? Los datos actuales serán reemplazados.', 'reforestamos-micrositios' ),
					'invalidJson'   => __( 'El archivo JSON no es válido.', 'reforestamos-micrositios' ),
					'uploadSuccess' => __( 'Archivo subido exitosamente.', 'reforestamos-micrositios' ),
					'uploadError'   => __( 'Error al subir el archivo.', 'reforestamos-micrositios' ),
				),
			)
		);
	}

	/**
	 * Render admin page
	 */
	public static function render_admin_page() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No tienes permisos para acceder a esta página.', 'reforestamos-micrositios' ) );
		}

		// Get current section
		$section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : 'home';

		// Handle different sections
		switch ( $section ) {
			case 'arboles':
				self::render_arboles_section();
				break;
			case 'oja':
				self::render_oja_section();
				break;
			default:
				self::render_home_section();
				break;
		}
	}

	/**
	 * Render home section
	 */
	private static function render_home_section() {
		?>
		<div class="wrap reforestamos-admin">
			<h1><?php esc_html_e( 'Gestión de Micrositios', 'reforestamos-micrositios' ); ?></h1>
			
			<div class="card">
				<h2><?php esc_html_e( 'Árboles y Ciudades', 'reforestamos-micrositios' ); ?></h2>
				<p><?php esc_html_e( 'Gestiona los datos del micrositio Árboles y Ciudades.', 'reforestamos-micrositios' ); ?></p>
				<p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=reforestamos-micrositios&section=arboles' ) ); ?>" 
					   class="button button-primary">
						<?php esc_html_e( 'Gestionar Árboles', 'reforestamos-micrositios' ); ?>
					</a>
				</p>
			</div>
			
			<div class="card">
				<h2><?php esc_html_e( 'Red OJA', 'reforestamos-micrositios' ); ?></h2>
				<p><?php esc_html_e( 'Gestiona los datos del micrositio Red OJA.', 'reforestamos-micrositios' ); ?></p>
				<p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=reforestamos-micrositios&section=oja' ) ); ?>" 
					   class="button button-primary">
						<?php esc_html_e( 'Gestionar Organizaciones', 'reforestamos-micrositios' ); ?>
					</a>
				</p>
			</div>
			
			<div class="card">
				<h2><?php esc_html_e( 'Información', 'reforestamos-micrositios' ); ?></h2>
				<p><?php esc_html_e( 'Shortcodes disponibles:', 'reforestamos-micrositios' ); ?></p>
				<ul>
					<li><code>[arboles-ciudades]</code> - <?php esc_html_e( 'Muestra el mapa de Árboles y Ciudades', 'reforestamos-micrositios' ); ?></li>
					<li><code>[red-oja]</code> - <?php esc_html_e( 'Muestra el mapa de Red OJA', 'reforestamos-micrositios' ); ?></li>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Árboles y Ciudades section
	 */
	private static function render_arboles_section() {
		$data = self::read_json_file( 'arboles-ciudades.json' );
		$json_content = $data ? wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) : '';
		
		?>
		<div class="wrap reforestamos-admin">
			<h1><?php esc_html_e( 'Gestión de Árboles y Ciudades', 'reforestamos-micrositios' ); ?></h1>
			
			<p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=reforestamos-micrositios' ) ); ?>" class="button">
					&larr; <?php esc_html_e( 'Volver', 'reforestamos-micrositios' ); ?>
				</a>
			</p>

			<?php self::render_messages(); ?>

			<div class="reforestamos-admin-grid">
				<!-- Upload Section -->
				<div class="reforestamos-admin-card">
					<h2><?php esc_html_e( 'Subir Archivo JSON', 'reforestamos-micrositios' ); ?></h2>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" id="upload-form">
						<input type="hidden" name="action" value="reforestamos_upload_json">
						<input type="hidden" name="data_type" value="arboles">
						<?php wp_nonce_field( 'reforestamos_upload_json', 'reforestamos_nonce' ); ?>
						
						<p>
							<label for="json_file"><?php esc_html_e( 'Seleccionar archivo JSON:', 'reforestamos-micrositios' ); ?></label>
							<input type="file" name="json_file" id="json_file" accept=".json" required>
						</p>
						
						<p class="description">
							<?php esc_html_e( 'El archivo debe contener una estructura JSON válida con los campos: version, arboles (array).', 'reforestamos-micrositios' ); ?>
						</p>
						
						<div id="preview-container" style="display:none;">
							<h3><?php esc_html_e( 'Vista Previa', 'reforestamos-micrositios' ); ?></h3>
							<div id="preview-content" class="json-preview"></div>
							<p id="preview-stats"></p>
						</div>
						
						<p>
							<button type="submit" class="button button-primary" id="upload-button" disabled>
								<?php esc_html_e( 'Subir Archivo', 'reforestamos-micrositios' ); ?>
							</button>
						</p>
					</form>
				</div>

				<!-- Edit Section -->
				<div class="reforestamos-admin-card">
					<h2><?php esc_html_e( 'Editar Datos JSON', 'reforestamos-micrositios' ); ?></h2>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="edit-form">
						<input type="hidden" name="action" value="reforestamos_save_json">
						<input type="hidden" name="data_type" value="arboles">
						<?php wp_nonce_field( 'reforestamos_save_json', 'reforestamos_nonce' ); ?>
						
						<p>
							<label for="json_content"><?php esc_html_e( 'Contenido JSON:', 'reforestamos-micrositios' ); ?></label>
							<textarea name="json_content" id="json_content" rows="20" class="large-text code"><?php echo esc_textarea( $json_content ); ?></textarea>
						</p>
						
						<p class="description">
							<?php esc_html_e( 'Edita el contenido JSON directamente. Asegúrate de mantener la estructura válida.', 'reforestamos-micrositios' ); ?>
						</p>
						
						<div id="validation-message"></div>
						
						<p>
							<button type="button" class="button" id="validate-button">
								<?php esc_html_e( 'Validar JSON', 'reforestamos-micrositios' ); ?>
							</button>
							<button type="submit" class="button button-primary">
								<?php esc_html_e( 'Guardar Cambios', 'reforestamos-micrositios' ); ?>
							</button>
						</p>
					</form>
				</div>
			</div>

			<!-- Current Data Info -->
			<?php if ( $data ) : ?>
			<div class="card">
				<h2><?php esc_html_e( 'Información Actual', 'reforestamos-micrositios' ); ?></h2>
				<table class="widefat">
					<tr>
						<th><?php esc_html_e( 'Versión:', 'reforestamos-micrositios' ); ?></th>
						<td><?php echo esc_html( $data['version'] ?? 'N/A' ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Última actualización:', 'reforestamos-micrositios' ); ?></th>
						<td><?php echo esc_html( $data['last_updated'] ?? 'N/A' ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Total de árboles:', 'reforestamos-micrositios' ); ?></th>
						<td><?php echo esc_html( isset( $data['arboles'] ) ? count( $data['arboles'] ) : 0 ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Total de especies:', 'reforestamos-micrositios' ); ?></th>
						<td><?php echo esc_html( isset( $data['especies'] ) ? count( $data['especies'] ) : 0 ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Total de ciudades:', 'reforestamos-micrositios' ); ?></th>
						<td><?php echo esc_html( isset( $data['ciudades'] ) ? count( $data['ciudades'] ) : 0 ); ?></td>
					</tr>
				</table>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render Red OJA section
	 */
	private static function render_oja_section() {
		$data = self::read_json_file( 'red-oja.json' );
		$json_content = $data ? wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) : '';
		
		?>
		<div class="wrap reforestamos-admin">
			<h1><?php esc_html_e( 'Gestión de Red OJA', 'reforestamos-micrositios' ); ?></h1>
			
			<p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=reforestamos-micrositios' ) ); ?>" class="button">
					&larr; <?php esc_html_e( 'Volver', 'reforestamos-micrositios' ); ?>
				</a>
			</p>

			<?php self::render_messages(); ?>

			<div class="reforestamos-admin-grid">
				<!-- Upload Section -->
				<div class="reforestamos-admin-card">
					<h2><?php esc_html_e( 'Subir Archivo JSON', 'reforestamos-micrositios' ); ?></h2>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" id="upload-form">
						<input type="hidden" name="action" value="reforestamos_upload_json">
						<input type="hidden" name="data_type" value="oja">
						<?php wp_nonce_field( 'reforestamos_upload_json', 'reforestamos_nonce' ); ?>
						
						<p>
							<label for="json_file"><?php esc_html_e( 'Seleccionar archivo JSON:', 'reforestamos-micrositios' ); ?></label>
							<input type="file" name="json_file" id="json_file" accept=".json" required>
						</p>
						
						<p class="description">
							<?php esc_html_e( 'El archivo debe contener una estructura JSON válida con los campos: version, organizaciones (array).', 'reforestamos-micrositios' ); ?>
						</p>
						
						<div id="preview-container" style="display:none;">
							<h3><?php esc_html_e( 'Vista Previa', 'reforestamos-micrositios' ); ?></h3>
							<div id="preview-content" class="json-preview"></div>
							<p id="preview-stats"></p>
						</div>
						
						<p>
							<button type="submit" class="button button-primary" id="upload-button" disabled>
								<?php esc_html_e( 'Subir Archivo', 'reforestamos-micrositios' ); ?>
							</button>
						</p>
					</form>
				</div>

				<!-- Edit Section -->
				<div class="reforestamos-admin-card">
					<h2><?php esc_html_e( 'Editar Datos JSON', 'reforestamos-micrositios' ); ?></h2>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="edit-form">
						<input type="hidden" name="action" value="reforestamos_save_json">
						<input type="hidden" name="data_type" value="oja">
						<?php wp_nonce_field( 'reforestamos_save_json', 'reforestamos_nonce' ); ?>
						
						<p>
							<label for="json_content"><?php esc_html_e( 'Contenido JSON:', 'reforestamos-micrositios' ); ?></label>
							<textarea name="json_content" id="json_content" rows="20" class="large-text code"><?php echo esc_textarea( $json_content ); ?></textarea>
						</p>
						
						<p class="description">
							<?php esc_html_e( 'Edita el contenido JSON directamente. Asegúrate de mantener la estructura válida.', 'reforestamos-micrositios' ); ?>
						</p>
						
						<div id="validation-message"></div>
						
						<p>
							<button type="button" class="button" id="validate-button">
								<?php esc_html_e( 'Validar JSON', 'reforestamos-micrositios' ); ?>
							</button>
							<button type="submit" class="button button-primary">
								<?php esc_html_e( 'Guardar Cambios', 'reforestamos-micrositios' ); ?>
							</button>
						</p>
					</form>
				</div>
			</div>

			<!-- Current Data Info -->
			<?php if ( $data ) : ?>
			<div class="card">
				<h2><?php esc_html_e( 'Información Actual', 'reforestamos-micrositios' ); ?></h2>
				<table class="widefat">
					<tr>
						<th><?php esc_html_e( 'Versión:', 'reforestamos-micrositios' ); ?></th>
						<td><?php echo esc_html( $data['version'] ?? 'N/A' ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Última actualización:', 'reforestamos-micrositios' ); ?></th>
						<td><?php echo esc_html( $data['last_updated'] ?? 'N/A' ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Total de organizaciones:', 'reforestamos-micrositios' ); ?></th>
						<td><?php echo esc_html( isset( $data['organizaciones'] ) ? count( $data['organizaciones'] ) : 0 ); ?></td>
					</tr>
				</table>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render admin messages
	 */
	private static function render_messages() {
		if ( isset( $_GET['message'] ) ) {
			$message = sanitize_text_field( wp_unslash( $_GET['message'] ) );
			$type = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : 'success';
			
			$class = 'success' === $type ? 'notice-success' : 'notice-error';
			
			?>
			<div class="notice <?php echo esc_attr( $class ); ?> is-dismissible">
				<p><?php echo esc_html( urldecode( $message ) ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Handle file upload
	 */
	public static function handle_upload() {
		// Check nonce
		if ( ! isset( $_POST['reforestamos_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['reforestamos_nonce'] ) ), 'reforestamos_upload_json' ) ) {
			wp_die( esc_html__( 'Verificación de seguridad fallida.', 'reforestamos-micrositios' ) );
		}

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No tienes permisos para realizar esta acción.', 'reforestamos-micrositios' ) );
		}

		// Get data type
		$data_type = isset( $_POST['data_type'] ) ? sanitize_text_field( wp_unslash( $_POST['data_type'] ) ) : '';
		
		if ( ! in_array( $data_type, array( 'arboles', 'oja' ), true ) ) {
			wp_die( esc_html__( 'Tipo de datos inválido.', 'reforestamos-micrositios' ) );
		}

		// Check if file was uploaded
		if ( ! isset( $_FILES['json_file'] ) || UPLOAD_ERR_OK !== $_FILES['json_file']['error'] ) {
			self::redirect_with_message( $data_type, __( 'Error al subir el archivo.', 'reforestamos-micrositios' ), 'error' );
			return;
		}

		// Validate file type
		$file_type = wp_check_filetype( sanitize_file_name( wp_unslash( $_FILES['json_file']['name'] ) ) );
		if ( 'json' !== $file_type['ext'] ) {
			self::redirect_with_message( $data_type, __( 'El archivo debe ser de tipo JSON.', 'reforestamos-micrositios' ), 'error' );
			return;
		}

		// Read file content
		$json_content = file_get_contents( $_FILES['json_file']['tmp_name'] );
		$data = json_decode( $json_content, true );

		// Validate JSON
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			self::redirect_with_message( $data_type, __( 'El archivo JSON no es válido.', 'reforestamos-micrositios' ), 'error' );
			return;
		}

		// Validate data structure
		if ( ! self::validate_data( $data, $data_type ) ) {
			self::redirect_with_message( $data_type, __( 'La estructura del JSON no es válida.', 'reforestamos-micrositios' ), 'error' );
			return;
		}

		// Save file
		$filename = 'arboles' === $data_type ? 'arboles-ciudades.json' : 'red-oja.json';
		if ( self::write_json_file( $filename, $data ) ) {
			self::redirect_with_message( $data_type, __( 'Archivo subido exitosamente.', 'reforestamos-micrositios' ), 'success' );
		} else {
			self::redirect_with_message( $data_type, __( 'Error al guardar el archivo.', 'reforestamos-micrositios' ), 'error' );
		}
	}

	/**
	 * Handle JSON save
	 */
	public static function handle_save() {
		// Check nonce
		if ( ! isset( $_POST['reforestamos_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['reforestamos_nonce'] ) ), 'reforestamos_save_json' ) ) {
			wp_die( esc_html__( 'Verificación de seguridad fallida.', 'reforestamos-micrositios' ) );
		}

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No tienes permisos para realizar esta acción.', 'reforestamos-micrositios' ) );
		}

		// Get data type
		$data_type = isset( $_POST['data_type'] ) ? sanitize_text_field( wp_unslash( $_POST['data_type'] ) ) : '';
		
		if ( ! in_array( $data_type, array( 'arboles', 'oja' ), true ) ) {
			wp_die( esc_html__( 'Tipo de datos inválido.', 'reforestamos-micrositios' ) );
		}

		// Get JSON content
		$json_content = isset( $_POST['json_content'] ) ? wp_unslash( $_POST['json_content'] ) : '';
		$data = json_decode( $json_content, true );

		// Validate JSON
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			self::redirect_with_message( $data_type, __( 'El JSON no es válido.', 'reforestamos-micrositios' ), 'error' );
			return;
		}

		// Validate data structure
		if ( ! self::validate_data( $data, $data_type ) ) {
			self::redirect_with_message( $data_type, __( 'La estructura del JSON no es válida.', 'reforestamos-micrositios' ), 'error' );
			return;
		}

		// Save file
		$filename = 'arboles' === $data_type ? 'arboles-ciudades.json' : 'red-oja.json';
		if ( self::write_json_file( $filename, $data ) ) {
			self::redirect_with_message( $data_type, __( 'Cambios guardados exitosamente.', 'reforestamos-micrositios' ), 'success' );
		} else {
			self::redirect_with_message( $data_type, __( 'Error al guardar los cambios.', 'reforestamos-micrositios' ), 'error' );
		}
	}

	/**
	 * Redirect with message
	 *
	 * @param string $data_type Data type.
	 * @param string $message Message to display.
	 * @param string $type Message type (success or error).
	 */
	private static function redirect_with_message( $data_type, $message, $type = 'success' ) {
		$section = 'arboles' === $data_type ? 'arboles' : 'oja';
		$url = add_query_arg(
			array(
				'page'    => 'reforestamos-micrositios',
				'section' => $section,
				'message' => rawurlencode( $message ),
				'type'    => $type,
			),
			admin_url( 'admin.php' )
		);
		wp_safe_redirect( $url );
		exit;
	}

	/**
	 * Read JSON data file
	 *
	 * @param string $filename JSON filename.
	 * @return array|false Data array or false on failure.
	 */
	public static function read_json_file( $filename ) {
		$file_path = REFORESTAMOS_MICRO_PATH . 'data/' . $filename;
		
		if ( ! file_exists( $file_path ) ) {
			return false;
		}
		
		$json_content = file_get_contents( $file_path );
		$data = json_decode( $json_content, true );
		
		return ( json_last_error() === JSON_ERROR_NONE ) ? $data : false;
	}

	/**
	 * Write JSON data file
	 *
	 * @param string $filename JSON filename.
	 * @param array  $data Data to write.
	 * @return bool True on success, false on failure.
	 */
	public static function write_json_file( $filename, $data ) {
		$file_path = REFORESTAMOS_MICRO_PATH . 'data/' . $filename;
		
		// Add last_updated timestamp
		$data['last_updated'] = current_time( 'mysql' );
		
		$json_content = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
		
		return file_put_contents( $file_path, $json_content ) !== false;
	}

	/**
	 * Validate JSON data structure
	 *
	 * @param array  $data Data to validate.
	 * @param string $type Data type ('arboles' or 'oja').
	 * @return bool True if valid.
	 */
	public static function validate_data( $data, $type ) {
		if ( ! is_array( $data ) ) {
			return false;
		}
		
		// Check required fields based on type
		if ( 'arboles' === $type ) {
			return isset( $data['version'] ) && isset( $data['arboles'] ) && is_array( $data['arboles'] );
		} elseif ( 'oja' === $type ) {
			return isset( $data['version'] ) && isset( $data['organizaciones'] ) && is_array( $data['organizaciones'] );
		}
		
		return false;
	}
}
