/**
 * Admin Uploader JavaScript
 *
 * Handles JSON file upload, validation, and preview functionality.
 *
 * @param   $
 * @package Reforestamos_Micrositios
 */

(function ($) {
	'use strict';

	/**
	 * Initialize admin uploader
	 */
	function initAdminUploader() {
		// File input change handler
		$('#json_file').on('change', handleFileSelect);

		// Validate button handler
		$('#validate-button').on('click', validateJSON);

		// Form submit handler for upload
		$('#upload-form').on('submit', handleUploadSubmit);

		// Form submit handler for edit
		$('#edit-form').on('submit', handleEditSubmit);
	}

	/**
	 * Handle file selection
	 * @param event
	 */
	function handleFileSelect(event) {
		const file = event.target.files[0];

		if (!file) {
			hidePreview();
			return;
		}

		// Check file type
		if (!file.name.endsWith('.json')) {
			showError(reforestamosAdmin.strings.invalidJson);
			hidePreview();
			$('#upload-button').prop('disabled', true);
			return;
		}

		// Read file content
		const reader = new FileReader();

		reader.onload = function (e) {
			try {
				const content = e.target.result;
				const data = JSON.parse(content);

				// Validate structure
				if (validateStructure(data)) {
					showPreview(data);
					$('#upload-button').prop('disabled', false);
				} else {
					showError(reforestamosAdmin.strings.invalidJson);
					hidePreview();
					$('#upload-button').prop('disabled', true);
				}
			} catch (error) {
				showError(
					reforestamosAdmin.strings.invalidJson + ' ' + error.message
				);
				hidePreview();
				$('#upload-button').prop('disabled', true);
			}
		};

		reader.onerror = function () {
			showError('Error al leer el archivo.');
			hidePreview();
			$('#upload-button').prop('disabled', true);
		};

		reader.readAsText(file);
	}

	/**
	 * Validate JSON structure
	 * @param data
	 */
	function validateStructure(data) {
		if (!data || typeof data !== 'object') {
			return false;
		}

		// Check for version field
		if (!data.version) {
			return false;
		}

		// Check for arboles or organizaciones
		if (data.arboles && Array.isArray(data.arboles)) {
			return true;
		}

		if (data.organizaciones && Array.isArray(data.organizaciones)) {
			return true;
		}

		return false;
	}

	/**
	 * Show preview of JSON data
	 * @param data
	 */
	function showPreview(data) {
		const previewContainer = $('#preview-container');
		const previewContent = $('#preview-content');
		const previewStats = $('#preview-stats');

		// Format JSON for display
		const formattedJSON = JSON.stringify(data, null, 2);

		// Truncate if too long
		const maxLength = 2000;
		const displayJSON =
			formattedJSON.length > maxLength
				? formattedJSON.substring(0, maxLength) +
					'\n\n... (contenido truncado)'
				: formattedJSON;

		previewContent.text(displayJSON);

		// Generate stats
		let stats = '<strong>Estadísticas:</strong><br>';
		stats += 'Versión: ' + (data.version || 'N/A') + '<br>';

		if (data.arboles) {
			stats += 'Total de árboles: ' + data.arboles.length + '<br>';
			if (data.especies) {
				stats += 'Total de especies: ' + data.especies.length + '<br>';
			}
			if (data.ciudades) {
				stats += 'Total de ciudades: ' + data.ciudades.length;
			}
		} else if (data.organizaciones) {
			stats += 'Total de organizaciones: ' + data.organizaciones.length;
		}

		previewStats.html(stats);
		previewContainer.show();
	}

	/**
	 * Hide preview
	 */
	function hidePreview() {
		$('#preview-container').hide();
		$('#preview-content').text('');
		$('#preview-stats').html('');
	}

	/**
	 * Show error message
	 * @param message
	 */
	function showError(message) {
		alert(message);
	}

	/**
	 * Validate JSON in textarea
	 */
	function validateJSON() {
		const content = $('#json_content').val();
		const validationMessage = $('#validation-message');

		try {
			const data = JSON.parse(content);

			if (validateStructure(data)) {
				validationMessage
					.removeClass('error')
					.addClass('success')
					.html(
						'<strong>✓ JSON válido</strong><br>La estructura es correcta y puede ser guardada.'
					);
			} else {
				validationMessage
					.removeClass('success')
					.addClass('error')
					.html(
						'<strong>✗ Estructura inválida</strong><br>El JSON debe contener los campos requeridos: version y arboles/organizaciones (array).'
					);
			}
		} catch (error) {
			validationMessage
				.removeClass('success')
				.addClass('error')
				.html('<strong>✗ JSON inválido</strong><br>' + error.message);
		}
	}

	/**
	 * Handle upload form submit
	 * @param event
	 */
	function handleUploadSubmit(event) {
		if (!confirm(reforestamosAdmin.strings.confirmUpload)) {
			event.preventDefault();
			return false;
		}

		// Show loading state
		const button = $('#upload-button');
		button.prop('disabled', true);
		button.html('Subiendo... <span class="reforestamos-loading"></span>');
	}

	/**
	 * Handle edit form submit
	 * @param event
	 */
	function handleEditSubmit(event) {
		const content = $('#json_content').val();

		try {
			const data = JSON.parse(content);

			if (!validateStructure(data)) {
				event.preventDefault();
				alert(
					'La estructura del JSON no es válida. Por favor, valida el JSON antes de guardar.'
				);
				return false;
			}
		} catch (error) {
			event.preventDefault();
			alert('El JSON no es válido: ' + error.message);
			return false;
		}

		// Show loading state
		const button = $('#edit-form button[type="submit"]');
		button.prop('disabled', true);
		button.html('Guardando... <span class="reforestamos-loading"></span>');
	}

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function () {
		initAdminUploader();
	});
})(jQuery);
