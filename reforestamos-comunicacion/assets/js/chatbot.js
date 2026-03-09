/**
 * Chatbot Frontend JavaScript
 *
 * @package Reforestamos_Comunicacion
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Chatbot handler
	 */
	const ReforestamosChatBot = {
		/**
		 * Initialize chatbot
		 */
		init: function() {
			this.cacheDom();
			this.bindEvents();
		},

		/**
		 * Cache DOM elements
		 */
		cacheDom: function() {
			this.$container = $('#reforestamos-chatbot');
			this.$toggle = $('#chatbot-toggle');
			this.$window = $('#chatbot-window');
			this.$close = $('#chatbot-close');
			this.$messages = $('#chatbot-messages');
			this.$input = $('#chatbot-input');
			this.$send = $('#chatbot-send');
			this.sessionId = this.$container.data('session');
		},

		/**
		 * Bind events
		 */
		bindEvents: function() {
			this.$toggle.on('click', this.toggleWindow.bind(this));
			this.$close.on('click', this.closeWindow.bind(this));
			this.$send.on('click', this.sendMessage.bind(this));
			this.$input.on('keypress', this.handleKeyPress.bind(this));
		},

		/**
		 * Toggle chatbot window
		 */
		toggleWindow: function(e) {
			e.preventDefault();
			
			if (this.$window.is(':visible')) {
				this.closeWindow();
			} else {
				this.openWindow();
			}
		},

		/**
		 * Open chatbot window
		 */
		openWindow: function() {
			this.$window.fadeIn(300);
			this.$toggle.addClass('active');
			this.$input.focus();
			
			// Scroll to bottom
			this.scrollToBottom();
		},

		/**
		 * Close chatbot window
		 */
		closeWindow: function() {
			this.$window.fadeOut(300);
			this.$toggle.removeClass('active');
		},

		/**
		 * Handle key press in input
		 */
		handleKeyPress: function(e) {
			if (e.which === 13) { // Enter key
				e.preventDefault();
				this.sendMessage();
			}
		},

		/**
		 * Send message to chatbot
		 */
		sendMessage: function() {
			const message = this.$input.val().trim();

			if (message === '') {
				return;
			}

			// Disable input while processing
			this.$input.prop('disabled', true);
			this.$send.prop('disabled', true);

			// Add user message to chat
			this.addMessage(message, 'user');

			// Clear input
			this.$input.val('');

			// Show typing indicator
			this.showTypingIndicator();

			// Send AJAX request
			$.ajax({
				url: reforestamosChatbot.ajaxUrl,
				type: 'POST',
				data: {
					action: 'chatbot_message',
					nonce: reforestamosChatbot.nonce,
					message: message,
					session_id: this.sessionId
				},
				success: this.handleResponse.bind(this),
				error: this.handleError.bind(this),
				complete: function() {
					// Re-enable input
					this.$input.prop('disabled', false);
					this.$send.prop('disabled', false);
					this.$input.focus();
				}.bind(this)
			});
		},

		/**
		 * Handle successful response
		 */
		handleResponse: function(response) {
			// Remove typing indicator
			this.removeTypingIndicator();

			if (response.success && response.data.response) {
				// Add bot response to chat
				this.addMessage(response.data.response, 'bot');
			} else {
				// Show error message
				const errorMsg = response.data && response.data.message 
					? response.data.message 
					: 'Lo sentimos, hubo un error. Por favor intenta de nuevo.';
				this.addMessage(errorMsg, 'bot');
			}
		},

		/**
		 * Handle error response
		 */
		handleError: function() {
			// Remove typing indicator
			this.removeTypingIndicator();

			// Show error message
			this.addMessage('Lo sentimos, hubo un error de conexión. Por favor intenta de nuevo.', 'bot');
		},

		/**
		 * Add message to chat
		 */
		addMessage: function(text, type) {
			const messageClass = type === 'user' ? 'user-message' : 'bot-message';
			const messageHtml = `
				<div class="chatbot-message ${messageClass}">
					<div class="message-content">${this.escapeHtml(text)}</div>
				</div>
			`;

			this.$messages.append(messageHtml);
			this.scrollToBottom();
		},

		/**
		 * Show typing indicator
		 */
		showTypingIndicator: function() {
			const typingHtml = `
				<div class="chatbot-message bot-message typing-indicator" id="typing-indicator">
					<div class="message-content">
						<span class="dot"></span>
						<span class="dot"></span>
						<span class="dot"></span>
					</div>
				</div>
			`;

			this.$messages.append(typingHtml);
			this.scrollToBottom();
		},

		/**
		 * Remove typing indicator
		 */
		removeTypingIndicator: function() {
			$('#typing-indicator').remove();
		},

		/**
		 * Scroll messages to bottom
		 */
		scrollToBottom: function() {
			this.$messages.animate({
				scrollTop: this.$messages[0].scrollHeight
			}, 300);
		},

		/**
		 * Escape HTML to prevent XSS
		 */
		escapeHtml: function(text) {
			const map = {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#039;'
			};
			return text.replace(/[&<>"']/g, function(m) { return map[m]; });
		}
	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		if ($('#reforestamos-chatbot').length) {
			ReforestamosChatBot.init();
		}
	});

})(jQuery);
