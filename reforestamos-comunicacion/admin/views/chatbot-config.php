<?php
/**
 * ChatBot Configuration Page
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Configuración del ChatBot', 'reforestamos-comunicacion' ); ?></h1>
	
	<div class="notice notice-info">
		<p>
			<strong><?php esc_html_e( 'Consejos para configurar el chatbot:', 'reforestamos-comunicacion' ); ?></strong>
		</p>
		<ul style="margin-left: 20px;">
			<li><?php esc_html_e( 'Usa palabras clave separadas por "|" para crear múltiples variaciones (ej: "hola|buenos días|buenas tardes")', 'reforestamos-comunicacion' ); ?></li>
			<li><?php esc_html_e( 'Las respuestas más específicas deben tener palabras clave más largas para mejor coincidencia', 'reforestamos-comunicacion' ); ?></li>
			<li><?php esc_html_e( 'Los flujos de conversación permiten crear diálogos interactivos guiados paso a paso', 'reforestamos-comunicacion' ); ?></li>
			<li><?php esc_html_e( 'Revisa las estadísticas regularmente para mejorar las respuestas según las preguntas más frecuentes', 'reforestamos-comunicacion' ); ?></li>
		</ul>
	</div>
	
	<form method="post" action="">
		<?php wp_nonce_field( 'reforestamos_chatbot_config' ); ?>
		
		<h2><?php esc_html_e( 'Configuración General', 'reforestamos-comunicacion' ); ?></h2>
		
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="chatbot_enabled">
						<?php esc_html_e( 'Estado del ChatBot', 'reforestamos-comunicacion' ); ?>
					</label>
				</th>
				<td>
					<label>
						<input 
							type="checkbox" 
							name="chatbot_enabled" 
							id="chatbot_enabled" 
							value="1" 
							<?php checked( $is_enabled, 1 ); ?>
						>
						<?php esc_html_e( 'Activar ChatBot en el sitio', 'reforestamos-comunicacion' ); ?>
					</label>
					<p class="description">
						<?php esc_html_e( 'Cuando está activado, el widget de chat aparecerá en todas las páginas del sitio.', 'reforestamos-comunicacion' ); ?>
					</p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Respuestas Predefinidas', 'reforestamos-comunicacion' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'Configura las respuestas automáticas del chatbot. Usa el símbolo "|" para separar múltiples palabras clave.', 'reforestamos-comunicacion' ); ?>
		</p>

		<table class="widefat striped" id="chatbot-responses-table">
			<thead>
				<tr>
					<th style="width: 35%;"><?php esc_html_e( 'Palabras Clave (separadas por |)', 'reforestamos-comunicacion' ); ?></th>
					<th style="width: 60%;"><?php esc_html_e( 'Respuesta', 'reforestamos-comunicacion' ); ?></th>
					<th style="width: 5%;"><?php esc_html_e( 'Acción', 'reforestamos-comunicacion' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$index = 0;
				foreach ( $responses as $pattern => $response ) :
					?>
					<tr>
						<td>
							<input 
								type="text" 
								name="chatbot_responses[<?php echo esc_attr( $index ); ?>][pattern]" 
								value="<?php echo esc_attr( $pattern ); ?>"
								class="regular-text"
								placeholder="<?php esc_attr_e( 'ej: hola|buenos días', 'reforestamos-comunicacion' ); ?>"
							>
						</td>
						<td>
							<textarea 
								name="chatbot_responses[<?php echo esc_attr( $index ); ?>][response]" 
								rows="2"
								class="large-text"
								placeholder="<?php esc_attr_e( 'Respuesta del chatbot...', 'reforestamos-comunicacion' ); ?>"
							><?php echo esc_textarea( $response ); ?></textarea>
						</td>
						<td>
							<button type="button" class="button remove-response" title="<?php esc_attr_e( 'Eliminar', 'reforestamos-comunicacion' ); ?>">
								<span class="dashicons dashicons-trash"></span>
							</button>
						</td>
					</tr>
					<?php
					$index++;
				endforeach;
				?>
			</tbody>
		</table>

		<p>
			<button type="button" class="button" id="add-response">
				<span class="dashicons dashicons-plus-alt"></span>
				<?php esc_html_e( 'Agregar Respuesta', 'reforestamos-comunicacion' ); ?>
			</button>
			<button type="button" class="button" id="import-responses">
				<span class="dashicons dashicons-upload"></span>
				<?php esc_html_e( 'Importar Respuestas', 'reforestamos-comunicacion' ); ?>
			</button>
			<button type="button" class="button" id="export-responses">
				<span class="dashicons dashicons-download"></span>
				<?php esc_html_e( 'Exportar Respuestas', 'reforestamos-comunicacion' ); ?>
			</button>
		</p>

		<div id="import-modal" style="display: none;">
			<div class="import-modal-content">
				<h3><?php esc_html_e( 'Importar Respuestas', 'reforestamos-comunicacion' ); ?></h3>
				<p class="description">
					<?php esc_html_e( 'Pega el JSON de respuestas exportadas previamente:', 'reforestamos-comunicacion' ); ?>
				</p>
				<textarea id="import-json" rows="10" style="width: 100%; font-family: monospace;"></textarea>
				<p>
					<button type="button" class="button button-primary" id="do-import">
						<?php esc_html_e( 'Importar', 'reforestamos-comunicacion' ); ?>
					</button>
					<button type="button" class="button" id="cancel-import">
						<?php esc_html_e( 'Cancelar', 'reforestamos-comunicacion' ); ?>
					</button>
				</p>
			</div>
		</div>

		<p class="submit">
			<input 
				type="submit" 
				name="reforestamos_chatbot_save" 
				class="button button-primary" 
				value="<?php esc_attr_e( 'Guardar Configuración', 'reforestamos-comunicacion' ); ?>"
			>
		</p>
	</form>

	<hr>

	<h2><?php esc_html_e( 'Flujos de Conversación', 'reforestamos-comunicacion' ); ?></h2>
	<p class="description">
		<?php esc_html_e( 'Los flujos de conversación permiten crear diálogos interactivos guiados. El chatbot puede hacer preguntas y responder según las opciones elegidas por el usuario.', 'reforestamos-comunicacion' ); ?>
	</p>

	<?php
	$flows = get_option( 'reforestamos_chatbot_flows', array() );
	if ( empty( $flows ) ) {
		$chatbot_instance = Reforestamos_ChatBot::get_instance();
		// Use reflection to access private method
		$reflection = new ReflectionClass( $chatbot_instance );
		$method = $reflection->getMethod( 'get_default_flows' );
		$method->setAccessible( true );
		$flows = $method->invoke( $chatbot_instance );
	}
	?>

	<div class="chatbot-flows-list">
		<?php foreach ( $flows as $flow_id => $flow ) : ?>
			<div class="chatbot-flow-card" data-flow-id="<?php echo esc_attr( $flow_id ); ?>">
				<div class="flow-card-header">
					<h3><?php echo esc_html( $flow['name'] ); ?></h3>
					<div class="flow-actions">
						<button type="button" class="button button-small edit-flow" data-flow-id="<?php echo esc_attr( $flow_id ); ?>">
							<span class="dashicons dashicons-edit"></span>
							<?php esc_html_e( 'Editar', 'reforestamos-comunicacion' ); ?>
						</button>
						<button type="button" class="button button-small button-link-delete delete-flow" data-flow-id="<?php echo esc_attr( $flow_id ); ?>">
							<span class="dashicons dashicons-trash"></span>
							<?php esc_html_e( 'Eliminar', 'reforestamos-comunicacion' ); ?>
						</button>
					</div>
				</div>
				<p><strong><?php esc_html_e( 'Palabras clave de activación:', 'reforestamos-comunicacion' ); ?></strong> 
					<code><?php echo esc_html( $flow['trigger_keywords'] ); ?></code>
				</p>
				<p><strong><?php esc_html_e( 'Pasos:', 'reforestamos-comunicacion' ); ?></strong> 
					<?php echo esc_html( count( $flow['steps'] ) ); ?>
				</p>
				<details>
					<summary><?php esc_html_e( 'Ver detalles del flujo', 'reforestamos-comunicacion' ); ?></summary>
					<div class="flow-steps">
						<?php foreach ( $flow['steps'] as $step_index => $step ) : ?>
							<div class="flow-step">
								<h4><?php echo esc_html( sprintf( __( 'Paso %d', 'reforestamos-comunicacion' ), $step_index + 1 ) ); ?></h4>
								<p><strong><?php esc_html_e( 'Mensaje:', 'reforestamos-comunicacion' ); ?></strong><br>
									<?php echo esc_html( $step['message'] ); ?>
								</p>
								<?php if ( isset( $step['options'] ) ) : ?>
									<p><strong><?php esc_html_e( 'Opciones:', 'reforestamos-comunicacion' ); ?></strong></p>
									<ul>
										<?php foreach ( $step['options'] as $option_key => $option_data ) : ?>
											<li>
												<strong><?php echo esc_html( $option_key ); ?>:</strong> 
												<?php echo esc_html( $option_data['keywords'] ); ?>
												<?php if ( isset( $option_data['response'] ) ) : ?>
													<br><em><?php echo esc_html( $option_data['response'] ); ?></em>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</details>
			</div>
		<?php endforeach; ?>
	</div>

	<p>
		<button type="button" class="button button-secondary" id="add-flow">
			<span class="dashicons dashicons-plus-alt"></span>
			<?php esc_html_e( 'Crear Nuevo Flujo', 'reforestamos-comunicacion' ); ?>
		</button>
		<button type="button" class="button" id="reset-flows">
			<span class="dashicons dashicons-update"></span>
			<?php esc_html_e( 'Restaurar Flujos Predeterminados', 'reforestamos-comunicacion' ); ?>
		</button>
	</p>

	<hr>

	<h2><?php esc_html_e( 'Estadísticas del ChatBot', 'reforestamos-comunicacion' ); ?></h2>
	<?php
	global $wpdb;
	$table_name = $wpdb->prefix . 'reforestamos_chatbot_logs';
	
	// Get statistics
	$total_conversations = $wpdb->get_var( "SELECT COUNT(DISTINCT session_id) FROM $table_name" );
	$total_messages      = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
	$today_messages      = $wpdb->get_var( 
		$wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name WHERE DATE(created_at) = %s",
			current_time( 'Y-m-d' )
		)
	);
	?>
	
	<table class="form-table">
		<tr>
			<th><?php esc_html_e( 'Total de Conversaciones', 'reforestamos-comunicacion' ); ?></th>
			<td><strong><?php echo esc_html( number_format_i18n( $total_conversations ) ); ?></strong></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Total de Mensajes', 'reforestamos-comunicacion' ); ?></th>
			<td><strong><?php echo esc_html( number_format_i18n( $total_messages ) ); ?></strong></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Mensajes Hoy', 'reforestamos-comunicacion' ); ?></th>
			<td><strong><?php echo esc_html( number_format_i18n( $today_messages ) ); ?></strong></td>
		</tr>
	</table>
</div>

<script>
jQuery(document).ready(function($) {
	var responseIndex = <?php echo esc_js( $index ); ?>;

	// Add new response row
	$('#add-response').on('click', function() {
		var newRow = `
			<tr>
				<td>
					<input 
						type="text" 
						name="chatbot_responses[${responseIndex}][pattern]" 
						class="regular-text"
						placeholder="<?php esc_attr_e( 'ej: hola|buenos días', 'reforestamos-comunicacion' ); ?>"
					>
				</td>
				<td>
					<textarea 
						name="chatbot_responses[${responseIndex}][response]" 
						rows="2"
						class="large-text"
						placeholder="<?php esc_attr_e( 'Respuesta del chatbot...', 'reforestamos-comunicacion' ); ?>"
					></textarea>
				</td>
				<td>
					<button type="button" class="button remove-response" title="<?php esc_attr_e( 'Eliminar', 'reforestamos-comunicacion' ); ?>">
						<span class="dashicons dashicons-trash"></span>
					</button>
				</td>
			</tr>
		`;
		
		$('#chatbot-responses-table tbody').append(newRow);
		responseIndex++;
	});

	// Remove response row
	$(document).on('click', '.remove-response', function() {
		if (confirm('<?php esc_html_e( '¿Estás seguro de eliminar esta respuesta?', 'reforestamos-comunicacion' ); ?>')) {
			$(this).closest('tr').remove();
		}
	});

	// Flow management
	$('#add-flow').on('click', function() {
		var flowName = prompt('<?php esc_html_e( 'Nombre del nuevo flujo:', 'reforestamos-comunicacion' ); ?>');
		if (flowName) {
			var flowId = flowName.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
			var triggerKeywords = prompt('<?php esc_html_e( 'Palabras clave de activación (separadas por |):', 'reforestamos-comunicacion' ); ?>');
			
			if (triggerKeywords) {
				// Create basic flow structure
				var flowData = {
					name: flowName,
					trigger_keywords: triggerKeywords,
					steps: [
						{
							message: '<?php esc_html_e( 'Mensaje del primer paso...', 'reforestamos-comunicacion' ); ?>',
							options: {}
						}
					],
					completion_message: '<?php esc_html_e( '¡Gracias!', 'reforestamos-comunicacion' ); ?>'
				};
				
				// Send AJAX request to save flow
				$.post(ajaxurl, {
					action: 'save_chatbot_flow',
					nonce: '<?php echo wp_create_nonce( 'chatbot_flow_nonce' ); ?>',
					flow_id: flowId,
					flow_data: JSON.stringify(flowData)
				}, function(response) {
					if (response.success) {
						location.reload();
					} else {
						alert('<?php esc_html_e( 'Error al crear el flujo.', 'reforestamos-comunicacion' ); ?>');
					}
				});
			}
		}
	});

	// Edit flow
	$(document).on('click', '.edit-flow', function() {
		var flowId = $(this).data('flow-id');
		alert('<?php esc_html_e( 'La edición avanzada de flujos estará disponible próximamente. Por ahora, puedes editar los flujos directamente en la base de datos o contactar al equipo de desarrollo.', 'reforestamos-comunicacion' ); ?>');
	});

	// Delete flow
	$(document).on('click', '.delete-flow', function() {
		if (!confirm('<?php esc_html_e( '¿Estás seguro de eliminar este flujo?', 'reforestamos-comunicacion' ); ?>')) {
			return;
		}
		
		var flowId = $(this).data('flow-id');
		var $card = $(this).closest('.chatbot-flow-card');
		
		$.post(ajaxurl, {
			action: 'delete_chatbot_flow',
			nonce: '<?php echo wp_create_nonce( 'chatbot_flow_nonce' ); ?>',
			flow_id: flowId
		}, function(response) {
			if (response.success) {
				$card.fadeOut(300, function() {
					$(this).remove();
				});
			} else {
				alert('<?php esc_html_e( 'Error al eliminar el flujo.', 'reforestamos-comunicacion' ); ?>');
			}
		});
	});

	// Reset flows to defaults
	$('#reset-flows').on('click', function() {
		if (!confirm('<?php esc_html_e( '¿Estás seguro de restaurar los flujos predeterminados? Esto eliminará todos los flujos personalizados.', 'reforestamos-comunicacion' ); ?>')) {
			return;
		}
		
		$.post(ajaxurl, {
			action: 'reset_chatbot_flows',
			nonce: '<?php echo wp_create_nonce( 'chatbot_flow_nonce' ); ?>'
		}, function(response) {
			if (response.success) {
				location.reload();
			} else {
				alert('<?php esc_html_e( 'Error al restaurar los flujos.', 'reforestamos-comunicacion' ); ?>');
			}
		});
	});

	// Import/Export responses
	$('#export-responses').on('click', function() {
		var responses = {};
		$('#chatbot-responses-table tbody tr').each(function() {
			var pattern = $(this).find('input[type="text"]').val();
			var response = $(this).find('textarea').val();
			if (pattern && response) {
				responses[pattern] = response;
			}
		});
		
		var json = JSON.stringify(responses, null, 2);
		var blob = new Blob([json], { type: 'application/json' });
		var url = URL.createObjectURL(blob);
		var a = document.createElement('a');
		a.href = url;
		a.download = 'chatbot-responses-' + new Date().toISOString().split('T')[0] + '.json';
		document.body.appendChild(a);
		a.click();
		document.body.removeChild(a);
		URL.revokeObjectURL(url);
	});

	$('#import-responses').on('click', function() {
		$('#import-modal').fadeIn();
	});

	$('#cancel-import').on('click', function() {
		$('#import-modal').fadeOut();
		$('#import-json').val('');
	});

	$('#do-import').on('click', function() {
		try {
			var json = $('#import-json').val();
			var responses = JSON.parse(json);
			
			// Clear existing rows
			$('#chatbot-responses-table tbody').empty();
			
			// Add imported responses
			responseIndex = 0;
			$.each(responses, function(pattern, response) {
				var newRow = `
					<tr>
						<td>
							<input 
								type="text" 
								name="chatbot_responses[${responseIndex}][pattern]" 
								value="${$('<div>').text(pattern).html()}"
								class="regular-text"
							>
						</td>
						<td>
							<textarea 
								name="chatbot_responses[${responseIndex}][response]" 
								rows="2"
								class="large-text"
							>${$('<div>').text(response).html()}</textarea>
						</td>
						<td>
							<button type="button" class="button remove-response">
								<span class="dashicons dashicons-trash"></span>
							</button>
						</td>
					</tr>
				`;
				$('#chatbot-responses-table tbody').append(newRow);
				responseIndex++;
			});
			
			$('#import-modal').fadeOut();
			$('#import-json').val('');
			alert('<?php esc_html_e( 'Respuestas importadas correctamente. No olvides guardar los cambios.', 'reforestamos-comunicacion' ); ?>');
		} catch (e) {
			alert('<?php esc_html_e( 'Error al importar: JSON inválido.', 'reforestamos-comunicacion' ); ?>');
		}
	});
});
</script>

<style>
#chatbot-responses-table input[type="text"],
#chatbot-responses-table textarea {
	width: 100%;
}

#chatbot-responses-table .remove-response {
	padding: 4px 8px;
}

#chatbot-responses-table .dashicons {
	font-size: 16px;
	width: 16px;
	height: 16px;
}

#add-response .dashicons {
	vertical-align: middle;
	margin-right: 4px;
}

.chatbot-flows-list {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
	gap: 20px;
	margin: 20px 0;
}

.chatbot-flow-card {
	background: #fff;
	border: 1px solid #ccd0d4;
	border-radius: 4px;
	padding: 20px;
	box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.chatbot-flow-card .flow-card-header {
	display: flex;
	justify-content: space-between;
	align-items: flex-start;
	margin-bottom: 10px;
}

.chatbot-flow-card .flow-card-header h3 {
	margin: 0;
	color: #2271b1;
	flex: 1;
}

.chatbot-flow-card .flow-actions {
	display: flex;
	gap: 5px;
}

.chatbot-flow-card .flow-actions .button {
	padding: 4px 8px;
	height: auto;
	line-height: 1.4;
}

.chatbot-flow-card .flow-actions .dashicons {
	font-size: 16px;
	width: 16px;
	height: 16px;
	vertical-align: middle;
}

.chatbot-flow-card h3 {
	margin-top: 0;
	color: #2271b1;
}

.chatbot-flow-card code {
	background: #f0f0f1;
	padding: 2px 6px;
	border-radius: 3px;
	font-size: 12px;
}

.chatbot-flow-card details {
	margin-top: 15px;
}

.chatbot-flow-card summary {
	cursor: pointer;
	color: #2271b1;
	font-weight: 500;
	padding: 8px 0;
}

.chatbot-flow-card summary:hover {
	color: #135e96;
}

.flow-steps {
	margin-top: 15px;
	padding-left: 10px;
}

.flow-step {
	background: #f6f7f7;
	padding: 15px;
	margin-bottom: 15px;
	border-left: 3px solid #2271b1;
	border-radius: 3px;
}

.flow-step h4 {
	margin-top: 0;
	color: #1d2327;
	font-size: 14px;
}

.flow-step ul {
	margin: 10px 0;
	padding-left: 20px;
}

.flow-step li {
	margin-bottom: 8px;
}

.flow-step em {
	color: #646970;
	font-size: 13px;
}

#import-modal {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.7);
	z-index: 100000;
	display: flex;
	align-items: center;
	justify-content: center;
}

#import-modal .import-modal-content {
	background: #fff;
	padding: 30px;
	border-radius: 4px;
	max-width: 600px;
	width: 90%;
	box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

#import-modal h3 {
	margin-top: 0;
}

#import-modal #import-json {
	border: 1px solid #ddd;
	border-radius: 3px;
	padding: 10px;
}
</style>
