/**
 * Admin Validation Script
 *
 * Provides inline validation for admin forms
 * with clear error messages and contextual help.
 *
 * @param   $
 * @package Reforestamos_Core
 * @since 1.0.0
 */

(function ($) {
	'use strict';

	/**
	 * Validation object
	 */
	const ReforestamosValidation = {
		/**
		 * Initialize validation
		 */
		init() {
			this.bindEvents();
			this.addHelpText();
		},

		/**
		 * Bind validation events
		 */
		bindEvents() {
			// Validate on form submit
			$('#post').on('submit', this.validateForm.bind(this));

			// Real-time validation on blur
			$(document).on(
				'blur',
				'.reforestamos-field-input',
				this.validateField.bind(this)
			);

			// Clear error on focus
			$(document).on(
				'focus',
				'.reforestamos-field-input',
				this.clearFieldError.bind(this)
			);
		},

		/**
		 * Validate entire form
		 * @param e
		 */
		validateForm(e) {
			let hasErrors = false;
			const $form = $(e.target);

			// Clear previous errors
			$('.reforestamos-field-error').remove();
			$('.reforestamos-field-input').removeClass('error');

			// Validate required fields
			$form.find('[required]').each(function () {
				const $field = $(this);
				if (!$field.val() || $field.val().trim() === '') {
					ReforestamosValidation.showFieldError(
						$field,
						reforestamosAdmin.strings.required_field
					);
					hasErrors = true;
				}
			});

			// Validate email fields
			$form.find('input[type="email"]').each(function () {
				const $field = $(this);
				if (
					$field.val() &&
					!ReforestamosValidation.isValidEmail($field.val())
				) {
					ReforestamosValidation.showFieldError(
						$field,
						reforestamosAdmin.strings.invalid_email
					);
					hasErrors = true;
				}
			});

			// Validate URL fields
			$form.find('input[type="url"]').each(function () {
				const $field = $(this);
				if (
					$field.val() &&
					!ReforestamosValidation.isValidUrl($field.val())
				) {
					ReforestamosValidation.showFieldError(
						$field,
						reforestamosAdmin.strings.invalid_url
					);
					hasErrors = true;
				}
			});

			// Validate date fields
			$form.find('input[type="date"]').each(function () {
				const $field = $(this);
				if (
					$field.val() &&
					!ReforestamosValidation.isValidDate($field.val())
				) {
					ReforestamosValidation.showFieldError(
						$field,
						reforestamosAdmin.strings.invalid_date
					);
					hasErrors = true;
				}
			});

			// Prevent submission if there are errors
			if (hasErrors) {
				e.preventDefault();

				// Scroll to first error
				const $firstError = $(
					'.reforestamos-field-input.error'
				).first();
				if ($firstError.length) {
					$('html, body').animate(
						{
							scrollTop: $firstError.offset().top - 100,
						},
						500
					);
				}

				// Show error notice
				this.showNotice(
					'error',
					'Por favor corrija los errores antes de guardar.'
				);

				return false;
			}

			return true;
		},

		/**
		 * Validate individual field
		 * @param e
		 */
		validateField(e) {
			const $field = $(e.target);
			const value = $field.val();

			// Clear previous error
			this.clearFieldError(e);

			// Check if required
			if ($field.prop('required') && (!value || value.trim() === '')) {
				this.showFieldError(
					$field,
					reforestamosAdmin.strings.required_field
				);
				return false;
			}

			// Validate by type
			const type = $field.attr('type');

			if (type === 'email' && value && !this.isValidEmail(value)) {
				this.showFieldError(
					$field,
					reforestamosAdmin.strings.invalid_email
				);
				return false;
			}

			if (type === 'url' && value && !this.isValidUrl(value)) {
				this.showFieldError(
					$field,
					reforestamosAdmin.strings.invalid_url
				);
				return false;
			}

			if (type === 'date' && value && !this.isValidDate(value)) {
				this.showFieldError(
					$field,
					reforestamosAdmin.strings.invalid_date
				);
				return false;
			}

			return true;
		},

		/**
		 * Show field error
		 * @param $field
		 * @param message
		 */
		showFieldError($field, message) {
			$field.addClass('error');

			// Add error message if not exists
			if ($field.next('.reforestamos-field-error').length === 0) {
				$field.after(
					'<span class="reforestamos-field-error">' +
						message +
						'</span>'
				);
			}
		},

		/**
		 * Clear field error
		 * @param e
		 */
		clearFieldError(e) {
			const $field = $(e.target);
			$field.removeClass('error');
			$field.next('.reforestamos-field-error').remove();
		},

		/**
		 * Show admin notice
		 * @param type
		 * @param message
		 */
		showNotice(type, message) {
			const noticeClass =
				type === 'error' ? 'notice-error' : 'notice-success';
			const $notice = $(
				'<div class="notice ' +
					noticeClass +
					' is-dismissible"><p>' +
					message +
					'</p></div>'
			);

			$('.wrap h1').after($notice);

			// Auto-dismiss after 5 seconds
			setTimeout(function () {
				$notice.fadeOut(function () {
					$(this).remove();
				});
			}, 5000);
		},

		/**
		 * Add contextual help text
		 */
		addHelpText() {
			// Add help icons to specific fields
			const helpTexts = {
				_empresa_url:
					'Ingrese la URL completa del sitio web de la empresa, incluyendo http:// o https://',
				_evento_fecha:
					'Seleccione la fecha en que se realizará el evento',
				_evento_capacidad:
					'Número máximo de participantes permitidos en el evento',
				_integrante_email:
					'Email de contacto del integrante (no será público)',
			};

			$.each(helpTexts, function (fieldName, helpText) {
				const $field = $('[name="' + fieldName + '"]');
				if ($field.length) {
					const $label = $('label[for="' + fieldName + '"]');
					if ($label.length) {
						$label.append(
							'<span class="reforestamos-help-tooltip">' +
								'<span class="reforestamos-help-text">?</span>' +
								'<span class="tooltip-content">' +
								helpText +
								'</span>' +
								'</span>'
						);
					}
				}
			});
		},

		/**
		 * Validate email format
		 * @param email
		 */
		isValidEmail(email) {
			const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return regex.test(email);
		},

		/**
		 * Validate URL format
		 * @param url
		 */
		isValidUrl(url) {
			try {
				new URL(url);
				return true;
			} catch (e) {
				return false;
			}
		},

		/**
		 * Validate date format
		 * @param date
		 */
		isValidDate(date) {
			const regex = /^\d{4}-\d{2}-\d{2}$/;
			if (!regex.test(date)) {
				return false;
			}

			const d = new Date(date);
			return d instanceof Date && !isNaN(d);
		},
	};

	// Initialize on document ready
	$(document).ready(function () {
		ReforestamosValidation.init();
	});
})(jQuery);
