/**
 * Admin JavaScript for Reforestamos Comunicación
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Initialize admin functionality
	 */
	$(document).ready(function() {
		// Test email functionality
		initTestEmail();
		
		// Newsletter preview
		initNewsletterPreview();
		
		// ChatBot configuration
		initChatBotConfig();
	});

	/**
	 * Initialize test email functionality
	 */
	function initTestEmail() {
		$('#reforestamos-test-email-btn').on('click', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var $status = $('#test-email-status');
			var testEmail = $('#test-email-address').val();
			
			if (!testEmail) {
				$status.html('<div class="reforestamos-comm-notice error">Por favor ingresa un email</div>');
				return;
			}
			
			$button.prop('disabled', true).text('Enviando...');
			$status.html('');
			
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'reforestamos_send_test_email',
					email: testEmail,
					nonce: reforestamosCommAdmin.nonce
				},
				success: function(response) {
					if (response.success) {
						$status.html('<div class="reforestamos-comm-notice success">' + response.data.message + '</div>');
					} else {
						$status.html('<div class="reforestamos-comm-notice error">' + response.data.message + '</div>');
					}
				},
				error: function() {
					$status.html('<div class="reforestamos-comm-notice error">Error al enviar el email de prueba</div>');
				},
				complete: function() {
					$button.prop('disabled', false).text('Enviar Email de Prueba');
				}
			});
		});
	}

	/**
	 * Initialize newsletter preview
	 */
	function initNewsletterPreview() {
		$('#newsletter-preview-btn').on('click', function(e) {
			e.preventDefault();
			
			var content = $('#newsletter-content').val();
			var $preview = $('#newsletter-preview');
			
			$preview.html(content);
		});
	}

	/**
	 * Initialize ChatBot configuration
	 */
	function initChatBotConfig() {
		// Add new response
		$('#add-chatbot-response').on('click', function(e) {
			e.preventDefault();
			
			var template = $('#chatbot-response-template').html();
			$('#chatbot-responses-list').append(template);
		});
		
		// Remove response
		$(document).on('click', '.remove-chatbot-response', function(e) {
			e.preventDefault();
			$(this).closest('.chatbot-response-item').remove();
		});
	}

})(jQuery);
