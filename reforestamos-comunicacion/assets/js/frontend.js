/**
 * Frontend JavaScript for Reforestamos Comunicación
 *
 * @param   $
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

(function ($) {
	'use strict';

	/**
	 * Initialize frontend functionality
	 */
	$(document).ready(function () {
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
		$('.reforestamos-contact-form form').on('submit', function (e) {
			e.preventDefault();

			const $form = $(this);
			const $button = $form.find('button[type="submit"]');
			const $messages = $form.find('.form-messages');
			const formData = $form.serialize();

			// Disable button and show loading
			$button
				.prop('disabled', true)
				.text(reforestamosComm.strings.sending);
			$messages.html('');

			$.ajax({
				url: reforestamosComm.ajaxUrl,
				type: 'POST',
				data: formData + '&action=submit_contact_form',
				success(response) {
					if (response.success) {
						$messages.html(
							'<div class="alert alert-success">' +
								response.data.message +
								'</div>'
						);
						$form[0].reset();
					} else {
						$messages.html(
							'<div class="alert alert-danger">' +
								response.data.message +
								'</div>'
						);
					}
				},
				error() {
					$messages.html(
						'<div class="alert alert-danger">' +
							reforestamosComm.strings.error +
							'</div>'
					);
				},
				complete() {
					$button
						.prop('disabled', false)
						.text(
							$button.data('original-text') || 'Enviar Mensaje'
						);
				},
			});
		});
	}

	/**
	 * Initialize newsletter subscription
	 */
	function initNewsletterSubscription() {
		$('.newsletter-subscribe-form').on('submit', function (e) {
			e.preventDefault();

			const $form = $(this);
			const $button = $form.find('button[type="submit"]');
			const $messages = $form.find('.newsletter-messages');
			const email = $form.find('input[name="email"]').val();
			const name = $form.find('input[name="name"]').val();

			$button.prop('disabled', true).text('Suscribiendo...');
			$messages.html('');

			$.ajax({
				url: reforestamosComm.ajaxUrl,
				type: 'POST',
				data: {
					action: 'newsletter_subscribe',
					email,
					name,
					nonce: reforestamosComm.nonce,
				},
				success(response) {
					if (response.success) {
						$messages.html(
							'<div class="alert alert-success">' +
								response.data.message +
								'</div>'
						);
						$form[0].reset();
					} else {
						$messages.html(
							'<div class="alert alert-danger">' +
								response.data.message +
								'</div>'
						);
					}
				},
				error() {
					$messages.html(
						'<div class="alert alert-danger">Error al procesar la suscripción</div>'
					);
				},
				complete() {
					$button.prop('disabled', false).text('Suscribirse');
				},
			});
		});
	}

	/**
	 * Initialize chatbot
	 */
	function initChatBot() {
		// ChatBot toggle
		$('#reforestamos-chatbot-toggle').on('click', function () {
			$('#reforestamos-chatbot-widget').toggleClass('open');
		});

		// ChatBot close
		$('#reforestamos-chatbot-close').on('click', function () {
			$('#reforestamos-chatbot-widget').removeClass('open');
		});

		// ChatBot message send
		$('#reforestamos-chatbot-form').on('submit', function (e) {
			e.preventDefault();

			const $input = $(this).find('input[name="message"]');
			const message = $input.val().trim();

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
					message,
					nonce: reforestamosComm.nonce,
				},
				success(response) {
					if (response.success) {
						addChatMessage(response.data.response, 'bot');
					} else {
						addChatMessage(
							'Lo siento, no pude procesar tu mensaje.',
							'bot'
						);
					}
				},
				error() {
					addChatMessage(
						'Error de conexión. Por favor intenta de nuevo.',
						'bot'
					);
				},
			});
		});
	}

	/**
	 * Add message to chat
	 *
	 * @param {string} message Message text
	 * @param {string} type    Message type (user or bot)
	 */
	function addChatMessage(message, type) {
		const $messages = $('#reforestamos-chatbot-messages');
		const $message = $(
			'<div class="chatbot-message ' + type + '"></div>'
		).text(message);

		$messages.append($message);
		$messages.scrollTop($messages[0].scrollHeight);
	}
})(jQuery);
