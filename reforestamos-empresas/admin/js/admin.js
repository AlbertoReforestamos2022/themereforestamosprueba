/**
 * Admin JavaScript for Reforestamos Empresas
 *
 * @param   $
 * @package Reforestamos_Empresas
 */

(function ($) {
	'use strict';

	/**
	 * Gallery Management
	 */
	const CompanyGallery = {
		frame: null,

		init() {
			this.bindEvents();
			this.initSortable();
		},

		bindEvents() {
			// Add gallery images button
			$('#add_gallery_images').on(
				'click',
				this.openMediaFrame.bind(this)
			);

			// Remove gallery image
			$(document).on(
				'click',
				'.gallery-image .remove-image',
				this.removeImage.bind(this)
			);

			// Edit caption on double-click
			$(document).on(
				'dblclick',
				'.gallery-image img',
				this.editCaption.bind(this)
			);
		},

		initSortable() {
			// Make gallery sortable for reordering
			if ($.fn.sortable) {
				$('#company_gallery_preview').sortable({
					items: '.gallery-image',
					cursor: 'move',
					opacity: 0.7,
					placeholder: 'gallery-image-placeholder',
					update: this.updateOrder.bind(this),
				});
			}
		},

		openMediaFrame(e) {
			e.preventDefault();

			// If the media frame already exists, reopen it
			if (this.frame) {
				this.frame.open();
				return;
			}

			// Create the media frame
			this.frame = wp.media({
				title: 'Seleccionar Imágenes de Galería',
				button: {
					text: 'Agregar a Galería',
				},
				multiple: true,
			});

			// When images are selected
			this.frame.on('select', this.selectImages.bind(this));

			// Open the modal
			this.frame.open();
		},

		selectImages() {
			const selection = this.frame.state().get('selection');
			const ids = $('#company_gallery_ids').val();
			const idsArray = ids ? ids.split(',') : [];

			selection.map(function (attachment) {
				attachment = attachment.toJSON();

				// Add to IDs array if not already present
				if (idsArray.indexOf(attachment.id.toString()) === -1) {
					idsArray.push(attachment.id);

					// Get caption if exists
					const caption = attachment.caption || '';
					const captionHtml = caption
						? '<div class="gallery-caption">' + caption + '</div>'
						: '';

					// Add preview
					const imageHtml =
						'<div class="gallery-image" data-id="' +
						attachment.id +
						'">' +
						'<img src="' +
						attachment.sizes.thumbnail.url +
						'" alt="" title="Doble clic para editar caption">' +
						'<span class="remove-image">&times;</span>' +
						captionHtml +
						'</div>';

					$('#company_gallery_preview').append(imageHtml);
				}
			});

			// Update hidden input
			$('#company_gallery_ids').val(idsArray.join(','));
		},

		removeImage(e) {
			e.preventDefault();

			const $image = $(e.currentTarget).closest('.gallery-image');
			const imageId = $image.data('id').toString();
			const ids = $('#company_gallery_ids').val();
			const idsArray = ids ? ids.split(',') : [];

			// Remove from array
			const index = idsArray.indexOf(imageId);
			if (index > -1) {
				idsArray.splice(index, 1);
			}

			// Update hidden input
			$('#company_gallery_ids').val(idsArray.join(','));

			// Remove preview
			$image.fadeOut(300, function () {
				$(this).remove();
			});
		},

		updateOrder() {
			const idsArray = [];
			$('#company_gallery_preview .gallery-image').each(function () {
				idsArray.push($(this).data('id'));
			});
			$('#company_gallery_ids').val(idsArray.join(','));
		},

		editCaption(e) {
			e.preventDefault();

			const $image = $(e.currentTarget).closest('.gallery-image');
			const imageId = $image.data('id');
			const $caption = $image.find('.gallery-caption');
			const currentCaption = $caption.length ? $caption.text() : '';

			const newCaption = prompt(
				'Ingrese el caption para esta imagen:',
				currentCaption
			);

			if (newCaption !== null) {
				// Save caption via AJAX
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'save_gallery_caption',
						nonce: $('#gallery_nonce').val() || '',
						image_id: imageId,
						caption: newCaption,
					},
					success(response) {
						if (response.success) {
							// Update caption display
							if ($caption.length) {
								if (newCaption) {
									$caption.text(newCaption);
								} else {
									$caption.remove();
								}
							} else if (newCaption) {
								$image.append(
									'<div class="gallery-caption">' +
										newCaption +
										'</div>'
								);
							}
						} else {
							alert('Error al guardar el caption');
						}
					},
					error() {
						alert('Error de conexión al guardar el caption');
					},
				});
			}
		},
	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function () {
		// Only initialize on empresas post type
		if ($('body').hasClass('post-type-empresas')) {
			CompanyGallery.init();
		}
	});
})(jQuery);
