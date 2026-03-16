/**
 * Tree Adoption Form Handler
 * @param $
 */
(function ($) {
	'use strict';

	$(document).ready(function () {
		const $form = $('#tree-adoption-form');

		if (!$form.length) {
			return;
		}

		$form.on('submit', function (e) {
			e.preventDefault();

			const $messages = $('.adoption-messages');
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			// Clear previous messages
			$messages.html('');

			// Disable submit button
			$submitBtn
				.prop('disabled', true)
				.text(reforestamosCom.strings.sending || 'Enviando...');

			// Prepare form data
			const formData = new FormData(this);
			formData.append('action', 'process_tree_adoption');

			// Send AJAX request
			$.ajax({
				url: reforestamosCom.ajaxUrl,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success(response) {
					if (response.success) {
						$messages.html(
							'<div class="alert alert-success">' +
								response.data.message +
								'</div>'
						);

						// Redirect to payment if URL provided
						if (response.data.payment_url) {
							setTimeout(function () {
								window.location.href =
									response.data.payment_url;
							}, 2000);
						} else {
							$form[0].reset();
						}
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
							(reforestamosCom.strings.error ||
								'Error al procesar la solicitud') +
							'</div>'
					);
				},
				complete() {
					$submitBtn.prop('disabled', false).text(originalText);
				},
			});
		});

		// Update total price dynamically
		const $quantity = $('#adoption-quantity');
		const pricePerTree = 100;

		$quantity.on('input', function () {
			const quantity = parseInt($(this).val()) || 1;
			const total = quantity * pricePerTree;
			$(this)
				.siblings('.form-text')
				.text(
					'Precio por árbol: $' +
						pricePerTree +
						' MXN | Total: $' +
						total +
						' MXN'
				);
		});
	});
})(jQuery);
