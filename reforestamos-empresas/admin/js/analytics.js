/**
 * Analytics Dashboard JavaScript
 *
 * Handles Chart.js initialization and interactive features
 * for the analytics dashboard.
 *
 * @param   $
 * @package Reforestamos_Empresas
 */

(function ($) {
	'use strict';

	/**
	 * Initialize analytics dashboard
	 */
	function initAnalyticsDashboard() {
		// Initialize monthly clicks chart
		initMonthlyClicksChart();

		// Initialize date range validation
		initDateRangeValidation();
	}

	/**
	 * Initialize monthly clicks chart with Chart.js
	 */
	function initMonthlyClicksChart() {
		const canvas = document.getElementById('monthly-clicks-chart');

		if (!canvas || typeof monthlyClicksData === 'undefined') {
			return;
		}

		const ctx = canvas.getContext('2d');

		new Chart(ctx, {
			type: 'line',
			data: monthlyClicksData,
			options: {
				responsive: true,
				maintainAspectRatio: false,
				interaction: {
					mode: 'index',
					intersect: false,
				},
				plugins: {
					legend: {
						display: true,
						position: 'top',
						labels: {
							usePointStyle: true,
							padding: 15,
							font: {
								size: 13,
							},
						},
					},
					tooltip: {
						backgroundColor: 'rgba(0, 0, 0, 0.8)',
						padding: 12,
						titleFont: {
							size: 14,
							weight: 'bold',
						},
						bodyFont: {
							size: 13,
						},
						callbacks: {
							label(context) {
								let label = context.dataset.label || '';
								if (label) {
									label += ': ';
								}
								label += context.parsed.y.toLocaleString();
								return label;
							},
						},
					},
				},
				scales: {
					y: {
						beginAtZero: true,
						ticks: {
							precision: 0,
							font: {
								size: 12,
							},
						},
						grid: {
							color: 'rgba(0, 0, 0, 0.05)',
						},
					},
					x: {
						ticks: {
							font: {
								size: 12,
							},
						},
						grid: {
							display: false,
						},
					},
				},
			},
		});
	}

	/**
	 * Initialize date range validation
	 */
	function initDateRangeValidation() {
		const $startDate = $('#start_date');
		const $endDate = $('#end_date');

		if (!$startDate.length || !$endDate.length) {
			return;
		}

		// Validate that end date is not before start date
		$endDate.on('change', function () {
			const startDate = $startDate.val();
			const endDate = $endDate.val();

			if (startDate && endDate && endDate < startDate) {
				alert(
					'La fecha final no puede ser anterior a la fecha inicial.'
				);
				$endDate.val(startDate);
			}
		});

		// Validate that start date is not after end date
		$startDate.on('change', function () {
			const startDate = $startDate.val();
			const endDate = $endDate.val();

			if (startDate && endDate && startDate > endDate) {
				alert(
					'La fecha inicial no puede ser posterior a la fecha final.'
				);
				$startDate.val(endDate);
			}
		});
	}

	/**
	 * Format number with thousands separator
	 * @param num
	 */
	function formatNumber(num) {
		return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	}

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function () {
		initAnalyticsDashboard();
	});
})(jQuery);
