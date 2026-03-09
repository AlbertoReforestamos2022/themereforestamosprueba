/**
 * Companies Grid JavaScript
 *
 * Handles filtering and interactions for the companies grid
 *
 * @package Reforestamos_Empresas
 */

(function($) {
	'use strict';

	/**
	 * Companies Grid Handler
	 */
	var CompaniesGrid = {
		
		/**
		 * Initialize
		 */
		init: function() {
			this.bindEvents();
		},

		/**
		 * Bind events
		 */
		bindEvents: function() {
			// Filter change events
			$('.companies-filter').on('change', '.filter-select', this.handleFilter.bind(this));
			
			// Track company clicks
			$('.companies-grid').on('click', '.company-link', this.trackClick.bind(this));
		},

		/**
		 * Handle filter changes
		 */
		handleFilter: function(e) {
			var $wrapper = $(e.target).closest('.companies-grid-wrapper');
			var $grid = $wrapper.find('.companies-grid');
			var $items = $grid.find('.company-item');
			
			// Get filter values
			var industryFilter = $wrapper.find('#filter-industry').val();
			var partnershipFilter = $wrapper.find('#filter-partnership').val();
			
			var visibleCount = 0;
			
			// Filter items
			$items.each(function() {
				var $item = $(this);
				var industry = $item.data('industry');
				var partnership = $item.data('partnership');
				
				var showItem = true;
				
				// Check industry filter
				if (industryFilter && industry !== industryFilter) {
					showItem = false;
				}
				
				// Check partnership filter
				if (partnershipFilter && partnership !== partnershipFilter) {
					showItem = false;
				}
				
				// Show/hide item
				if (showItem) {
					$item.removeClass('hidden').fadeIn(300);
					visibleCount++;
				} else {
					$item.addClass('hidden').fadeOut(300);
				}
			});
			
			// Update count
			this.updateCount($wrapper, visibleCount);
			
			// Show no results message if needed
			this.toggleNoResults($wrapper, visibleCount);
		},

		/**
		 * Update companies count
		 */
		updateCount: function($wrapper, count) {
			var $countEl = $wrapper.find('.companies-count');
			
			if ($countEl.length) {
				var text = count === 1 
					? 'Mostrando 1 empresa' 
					: 'Mostrando ' + count + ' empresas';
				
				$countEl.text(text);
			}
		},

		/**
		 * Toggle no results message
		 */
		toggleNoResults: function($wrapper, count) {
			var $noResults = $wrapper.find('.no-companies-found');
			var $grid = $wrapper.find('.companies-grid');
			var $countEl = $wrapper.find('.companies-count');
			
			if (count === 0) {
				if ($noResults.length === 0) {
					$noResults = $('<div class="no-companies-found"><p>No se encontraron empresas con los filtros seleccionados.</p></div>');
					$grid.after($noResults);
				}
				$noResults.show();
				$grid.hide();
				$countEl.hide();
			} else {
				$noResults.hide();
				$grid.show();
				$countEl.show();
			}
		},

		/**
		 * Track company click
		 */
		trackClick: function(e) {
			var $link = $(e.currentTarget);
			var companyId = $link.data('company-id');
			
			if (!companyId || !reforestamosEmpresas) {
				return;
			}
			
			// Send AJAX request to track click
			$.ajax({
				url: reforestamosEmpresas.ajaxUrl,
				type: 'POST',
				data: {
					action: 'track_company_click',
					nonce: reforestamosEmpresas.nonce,
					company_id: companyId,
					click_type: 'logo'
				},
				// Don't wait for response, let navigation continue
				async: true
			});
		}
	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		if ($('.companies-grid-wrapper').length) {
			CompaniesGrid.init();
		}
	});

})(jQuery);
