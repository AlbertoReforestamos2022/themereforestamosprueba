/**
 * Frontend JavaScript for Reforestamos Empresas
 *
 * General frontend functionality
 *
 * @param   $
 * @package Reforestamos_Empresas
 */

(function ($) {
	'use strict';

	/**
	 * Main Frontend Handler
	 */
	const ReforestamosEmpresas = {
		/**
		 * Initialize
		 */
		init() {
			this.initLazyLoading();
			this.bindEvents();
		},

		/**
		 * Initialize lazy loading for images
		 */
		initLazyLoading() {
			// Check if browser supports native lazy loading
			if ('loading' in HTMLImageElement.prototype) {
				// Native lazy loading is supported
				return;
			}

			// Fallback for browsers that don't support native lazy loading
			const lazyImages = document.querySelectorAll('img[loading="lazy"]');

			if ('IntersectionObserver' in window) {
				var imageObserver = new IntersectionObserver(function (
					entries,
					observer
				) {
					entries.forEach(function (entry) {
						if (entry.isIntersecting) {
							const image = entry.target;
							image.src = image.dataset.src || image.src;
							image.classList.remove('lazy');
							imageObserver.unobserve(image);
						}
					});
				});

				lazyImages.forEach(function (image) {
					imageObserver.observe(image);
				});
			}
		},

		/**
		 * Bind events
		 */
		bindEvents() {
			// Add any global event handlers here
		},
	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function () {
		ReforestamosEmpresas.init();
	});
})(jQuery);
