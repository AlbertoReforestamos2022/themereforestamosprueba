/**
 * Frontend JavaScript for Reforestamos Comunicación
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Initialize frontend functionality
	 */
	$(document).ready(function() {
		// Initialize contact forms
		initContactForms();
		
		// Initialize newsletter subscription
		initNewsletterSubscription();
		
		// Initialize chatbot
		initChatBot();
	});

	/**
	 * Initialize contact forms
	 */
	function initContactForms() {
		$('.reforestamos-contact-form form').on('submit', function(e) {
			e.preventDefault();
			
			var $form = $(this);
			var $button = $form.find('button[type="submit"]');
			var $messages = $form.find('.form-messages');
			var formData = $form.serialize();
			
			// Disable button and show loading
			$button.prop('disabled', true).text(reforestamosComm.strings.sending);
			$messages.html('');
			
			$.ajax({
				url: reforestamosComm.ajaxUrl,
				type: 'POST',
				data: formData + '&action=submit_contact_form',
				success: function(response) {
					if (response.success) {
						$messages.html('<div class="alert alert-success">' + response.data.message + '</div>');
						$form[0].reset();
					} else {
						$messages.html('<div class="alert alert-danger">' + response.data.message + '</div>');
					}
				},
				error: function() {
					$messages.html('<div class="alert alert-danger">' + reforestamosComm.strings.error + '</div>');
				},
				complete: function() {
					$button.prop('disabled', false).text($button.data('original-text') || 'Enviar Mensaje');
				}
			});
		});
	}

	/**
	 * Initialize newsletter subscription
	 */
	function initNewsletterSubscription() {
		$('.newsletter-subscribe-form').on('submit', function(e) {
			e.preventDefault();
			
			var $form = $(this);
			var $button = $form.find('button[type="submit"]');
			var $messages = $form.find('.newsletter-messages');
			var email = $form.find('input[name="email"]').val();
			var name = $form.find('input[name="name"]').val();
			
			$button.prop('disabled', true).text('Suscribiendo...');
			$messages.html('');
			
			$.ajax({
				url: reforestamosComm.ajaxUrl,
				type: 'POST',
				data: {
					action: 'newsletter_subscribe',
					email: email,
					name: name,
					nonce: reforestamosComm.nonce
				},
				success: function(response) {
					if (response.success) {
						$messages.html('<div class="alert alert-success">' + response.data.message + '</div>');
						$form[0].reset();
					} else {
						$messages.html('<div class="alert alert-danger">' + response.data.message + '</div>');
					}
				},
				error: function() {
					$messages.html('<div class="alert alert-danger">Error al procesar la suscripción</div>');
				},
				complete: function() {
					$button.prop('disabled', false).text('Suscribirse');
				}
			});
		});
	}

	/**
	 * Initialize chatbot
	 */
	function initChatBot() {
		// ChatBot toggle
		$('#reforestamos-chatbot-toggle').on('click', function() {
			$('#reforestamos-chatbot-widget').toggleClass('open');
		});
		
		// ChatBot close
		$('#reforestamos-chatbot-close').on('click', function() {
			$('#reforestamos-chatbot-widget').removeClass('open');
		});
		
		// ChatBot message send
		$('#reforestamos-chatbot-form').on('submit', function(e) {
			e.preventDefault();
			
			var $input = $(this).find('input[name="message"]');
			var message = $input.val().trim();
			
			if (!message) {
				return;
			}
			
			// Add user message to chat
			addChatMessage(message, 'user');
			$input.val('');
			
			// Send to server
			$.ajax({
				url: reforestamosComm.ajaxUrl,
				type: 'POST',
				data: {
					action: 'chatbot_message',
					message: message,
					nonce: reforestamosComm.nonce
				},
				success: function(response) {
					if (response.success) {
						addChatMessage(response.data.response, 'bot');
					} else {
						addChatMessage('Lo siento, no pude procesar tu mensaje.', 'bot');
					}
				},
				error: function() {
					addChatMessage('Error de conexión. Por favor intenta de nuevo.', 'bot');
				}
			});
		});
	}

	/**
	 * Add message to chat
	 *
	 * @param {string} message Message text
	 * @param {string} type Message type (user or bot)
	 */
	function addChatMessage(message, type) {
		var $messages = $('#reforestamos-chatbot-messages');
		var $message = $('<div class="chatbot-message ' + type + '"></div>').text(message);
		
		$messages.append($message);
		$messages.scrollTop($messages[0].scrollHeight);
	}

})(jQuery);
