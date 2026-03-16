/**
 * Cookie Consent Banner JavaScript
 *
 * Handles user interactions with the cookie consent banner.
 */

(function () {
	'use strict';

	// Wait for DOM to be ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

	function init() {
		const banner = document.getElementById('reforestamos-cookie-consent');
		if (!banner) return;

		// Show banner with animation
		setTimeout(() => {
			banner.classList.add('show');
		}, 500);

		// Handle button clicks
		const buttons = banner.querySelectorAll('.cookie-consent-btn');
		buttons.forEach((button) => {
			button.addEventListener('click', handleConsentClick);
		});

		// Handle keyboard navigation
		banner.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') {
				// Treat Escape as decline
				saveConsent('declined');
			}
		});
	}

	function handleConsentClick(e) {
		const consent = e.target.getAttribute('data-consent');
		saveConsent(consent);
	}

	function saveConsent(consent) {
		const banner = document.getElementById('reforestamos-cookie-consent');
		if (!banner) return;

		// Hide banner immediately
		banner.classList.remove('show');
		setTimeout(() => {
			banner.remove();
		}, 300);

		// Save preference via AJAX
		if (typeof reforestamosConsent !== 'undefined') {
			const formData = new FormData();
			formData.append('action', 'save_cookie_consent');
			formData.append('nonce', reforestamosConsent.nonce);
			formData.append('consent', consent);

			fetch(reforestamosConsent.ajaxUrl, {
				method: 'POST',
				body: formData,
			})
				.then((response) => response.json())
				.then((data) => {
					if (data.success) {
						// If accepted, reload page to activate analytics
						if (consent === 'accepted') {
							window.location.reload();
						}
					}
				})
				.catch((error) => {
					console.error('Error saving cookie consent:', error);
				});
		}
	}
})();
