/**
 * Contact Form Frontend Validation and Handling
 *
 * @package
 */

(function () {
	'use strict';

	/**
	 * Initialize contact forms when DOM is ready
	 */
	function initContactForms() {
		const forms = document.querySelectorAll('.reforestamos-contact-form');

		forms.forEach((form) => {
			form.addEventListener('submit', handleFormSubmit);

			// Add real-time validation
			const inputs = form.querySelectorAll(
				'input[required], textarea[required]'
			);
			inputs.forEach((input) => {
				input.addEventListener('blur', () => validateField(input));
				input.addEventListener('input', () => {
					if (input.classList.contains('is-invalid')) {
						validateField(input);
					}
				});
			});
		});
	}

	/**
	 * Validate a single form field
	 * @param field
	 */
	function validateField(field) {
		const value = field.value.trim();
		const type = field.type;
		let isValid = true;

		// Check if field is empty
		if (field.hasAttribute('required') && !value) {
			isValid = false;
		}

		// Email validation
		if (type === 'email' && value) {
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			isValid = emailRegex.test(value);
		}

		// Update field state
		if (isValid) {
			field.classList.remove('is-invalid');
			const feedback =
				field.parentElement.querySelector('.invalid-feedback');
			if (feedback) {
				feedback.classList.remove('d-block');
			}
		} else {
			field.classList.add('is-invalid');
			const feedback =
				field.parentElement.querySelector('.invalid-feedback');
			if (feedback) {
				feedback.classList.add('d-block');
			}
		}

		return isValid;
	}

	/**
	 * Validate entire form
	 * @param form
	 */
	function validateForm(form) {
		const inputs = form.querySelectorAll(
			'input[required], textarea[required]'
		);
		let isValid = true;

		inputs.forEach((input) => {
			if (!validateField(input)) {
				isValid = false;
			}
		});

		return isValid;
	}

	/**
	 * Handle form submission
	 * @param event
	 */
	function handleFormSubmit(event) {
		event.preventDefault();

		const form = event.target;
		const formId = form.dataset.formId;

		// Validate form
		if (!validateForm(form)) {
			return;
		}

		// Check honeypot
		const honeypot = form.querySelector('.reforestamos-honeypot');
		if (honeypot && honeypot.value) {
			// Spam detected, silently fail
			showMessage(form, 'success');
			form.reset();
			return;
		}

		// Disable submit button and show spinner
		const submitButton = form.querySelector('button[type="submit"]');
		const buttonText = submitButton.querySelector('.button-text');
		const spinner = submitButton.querySelector('.spinner-border');

		submitButton.disabled = true;
		if (spinner) {
			spinner.classList.remove('d-none');
		}

		// Collect form data
		const formData = new FormData(form);
		const data = {
			action: 'reforestamos_contact_form_submit',
			form_id: formId,
			name: formData.get('name'),
			email: formData.get('email'),
			subject: formData.get('subject'),
			message: formData.get('message'),
			nonce: reforestamosContactForm?.nonce || '',
		};

		// Send AJAX request
		// Note: This is prepared for future integration with Communication Plugin
		// For now, we'll show a placeholder message

		// Simulate AJAX delay
		setTimeout(() => {
			// TODO: Replace with actual AJAX call when Communication Plugin is implemented
			// For now, just show success message
			showMessage(form, 'success');
			form.reset();

			// Re-enable submit button
			submitButton.disabled = false;
			if (spinner) {
				spinner.classList.add('d-none');
			}

			// Remove validation classes
			const inputs = form.querySelectorAll('.is-invalid');
			inputs.forEach((input) => {
				input.classList.remove('is-invalid');
			});

			// Log to console for development
			console.log('Contact form submission (placeholder):', data);
			console.log(
				'Note: Actual email sending will be implemented in Communication Plugin (Task 20)'
			);
		}, 1000);

		/* 
        // Future implementation with Communication Plugin:
        
        fetch(reforestamosContactForm.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showMessage(form, 'success');
                form.reset();
            } else {
                showMessage(form, 'error');
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            showMessage(form, 'error');
        })
        .finally(() => {
            submitButton.disabled = false;
            if (spinner) {
                spinner.classList.add('d-none');
            }
        });
        */
	}

	/**
	 * Show success or error message
	 * @param form
	 * @param type
	 */
	function showMessage(form, type) {
		const messagesContainer = form.querySelector(
			'.reforestamos-contacto__form-messages'
		);
		if (!messagesContainer) return;

		const successAlert = messagesContainer.querySelector('.alert-success');
		const errorAlert = messagesContainer.querySelector('.alert-danger');

		// Hide all messages first
		if (successAlert) successAlert.classList.add('d-none');
		if (errorAlert) errorAlert.classList.add('d-none');

		// Show appropriate message
		if (type === 'success' && successAlert) {
			successAlert.classList.remove('d-none');

			// Auto-hide after 5 seconds
			setTimeout(() => {
				successAlert.classList.add('d-none');
			}, 5000);
		} else if (type === 'error' && errorAlert) {
			errorAlert.classList.remove('d-none');

			// Auto-hide after 5 seconds
			setTimeout(() => {
				errorAlert.classList.add('d-none');
			}, 5000);
		}

		// Scroll to message
		messagesContainer.scrollIntoView({
			behavior: 'smooth',
			block: 'nearest',
		});
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initContactForms);
	} else {
		initContactForms();
	}
})();
