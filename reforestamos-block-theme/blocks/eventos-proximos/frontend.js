/**
 * Frontend JavaScript for Eventos Próximos Block
 * Loads events dynamically via REST API
 *
 * @package
 */

(function () {
	'use strict';

	/**
	 * Format date to display day and month
	 * @param dateString
	 */
	function formatDateBadge(dateString) {
		const date = new Date(dateString);
		const dia = date.getDate();
		const meses = [
			'Ene',
			'Feb',
			'Mar',
			'Abr',
			'May',
			'Jun',
			'Jul',
			'Ago',
			'Sep',
			'Oct',
			'Nov',
			'Dic',
		];
		const mes = meses[date.getMonth()];

		return { dia, mes };
	}

	/**
	 * Format date to full format
	 * @param dateString
	 */
	function formatDateFull(dateString) {
		const date = new Date(dateString);
		const meses = [
			'Enero',
			'Febrero',
			'Marzo',
			'Abril',
			'Mayo',
			'Junio',
			'Julio',
			'Agosto',
			'Septiembre',
			'Octubre',
			'Noviembre',
			'Diciembre',
		];
		const dia = date.getDate();
		const mes = meses[date.getMonth()];
		const año = date.getFullYear();

		return `${mes} ${dia}, ${año}`;
	}

	/**
	 * Render event card layout
	 * @param evento
	 */
	function renderEventCard(evento) {
		const imageUrl =
			evento._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
		const fecha = evento.meta?.fecha_evento || evento.date;
		const ubicacion = evento.meta?.ubicacion || '';
		const { dia, mes } = formatDateBadge(fecha);

		return `
            <div class="evento-card">
                ${
					imageUrl
						? `
                    <div class="evento-image">
                        <img src="${imageUrl}" alt="${evento.title.rendered}" loading="lazy" />
                        <div class="evento-fecha-badge">
                            <span class="dia">${dia}</span>
                            <span class="mes">${mes}</span>
                        </div>
                    </div>
                `
						: ''
				}
                <div class="evento-content">
                    <h3 class="evento-title">${evento.title.rendered}</h3>
                    ${
						ubicacion
							? `
                        <p class="evento-ubicacion">
                            <span class="dashicons dashicons-location"></span>
                            ${ubicacion}
                        </p>
                    `
							: ''
					}
                    <div class="evento-excerpt">${evento.excerpt.rendered}</div>
                    <a href="${evento.link}" class="btn btn-outline-primary">Ver detalles</a>
                </div>
            </div>
        `;
	}

	/**
	 * Render event list layout
	 * @param evento
	 */
	function renderEventList(evento) {
		const fecha = evento.meta?.fecha_evento || evento.date;
		const ubicacion = evento.meta?.ubicacion || '';
		const fechaFormateada = formatDateFull(fecha);

		return `
            <div class="evento-list-item">
                <div class="evento-fecha">
                    <span class="dashicons dashicons-calendar-alt"></span>
                    ${fechaFormateada}
                </div>
                <div class="evento-info">
                    <h4 class="evento-title">${evento.title.rendered}</h4>
                    ${
						ubicacion
							? `
                        <p class="evento-ubicacion">
                            <span class="dashicons dashicons-location"></span>
                            ${ubicacion}
                        </p>
                    `
							: ''
					}
                </div>
                <a href="${evento.link}" class="btn btn-sm btn-primary">Ver más</a>
            </div>
        `;
	}

	/**
	 * Render event grid layout
	 * @param evento
	 */
	function renderEventGrid(evento) {
		const imageUrl =
			evento._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
		const fecha = evento.meta?.fecha_evento || evento.date;
		const ubicacion = evento.meta?.ubicacion || '';
		const fechaFormateada = formatDateFull(fecha);

		return `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="evento-grid-card">
                    ${
						imageUrl
							? `
                        <div class="evento-image">
                            <img src="${imageUrl}" alt="${evento.title.rendered}" loading="lazy" />
                        </div>
                    `
							: ''
					}
                    <div class="evento-body">
                        <div class="evento-meta">
                            <span class="evento-fecha">
                                <span class="dashicons dashicons-calendar-alt"></span>
                                ${fechaFormateada}
                            </span>
                            ${
								ubicacion
									? `
                                <span class="evento-ubicacion">
                                    <span class="dashicons dashicons-location"></span>
                                    ${ubicacion}
                                </span>
                            `
									: ''
							}
                        </div>
                        <h4 class="evento-title">${evento.title.rendered}</h4>
                        <a href="${evento.link}" class="btn btn-sm btn-outline-primary">Ver detalles</a>
                    </div>
                </div>
            </div>
        `;
	}

	/**
	 * Load events from REST API
	 * @param block
	 */
	function loadEventos(block) {
		const count = parseInt(block.dataset.count) || 3;
		const showPast = block.dataset.showPast === 'true';
		const layout = block.dataset.layout || 'cards';
		const container = block.querySelector('[data-eventos-container]');

		if (!container) return;

		// Build API URL
		// Try custom endpoint first, fallback to standard WP REST API
		const apiUrl =
			window.reforestamosData?.eventosApiUrl || '/wp-json/wp/v2/eventos';
		const params = new URLSearchParams({
			per_page: count,
			_embed: true,
			status: 'publish',
			orderby: 'meta_value',
			order: 'asc',
			meta_key: 'fecha_evento',
		});

		// If not showing past events, add date filter
		if (!showPast) {
			const today = new Date().toISOString().split('T')[0];
			params.append('meta_query[0][key]', 'fecha_evento');
			params.append('meta_query[0][value]', today);
			params.append('meta_query[0][compare]', '>=');
			params.append('meta_query[0][type]', 'DATE');
		}

		const fullUrl = `${apiUrl}?${params.toString()}`;

		// Fetch events
		fetch(fullUrl)
			.then((response) => {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			})
			.then((eventos) => {
				if (eventos.length === 0) {
					container.innerHTML = `
                        <div class="eventos-empty">
                            <p>No hay eventos disponibles en este momento.</p>
                        </div>
                    `;
					return;
				}

				// Render events based on layout
				let html = '';

				if (layout === 'cards') {
					html = '<div class="eventos-cards">';
					eventos.forEach((evento) => {
						html += renderEventCard(evento);
					});
					html += '</div>';
				} else if (layout === 'list') {
					html = '<div class="eventos-list">';
					eventos.forEach((evento) => {
						html += renderEventList(evento);
					});
					html += '</div>';
				} else if (layout === 'grid') {
					html = '<div class="row">';
					eventos.forEach((evento) => {
						html += renderEventGrid(evento);
					});
					html += '</div>';
				}

				container.innerHTML = html;
			})
			.catch((error) => {
				console.error('Error loading eventos:', error);
				container.innerHTML = `
                    <div class="eventos-empty">
                        <p>Error al cargar los eventos. Por favor, intenta de nuevo más tarde.</p>
                        <p class="text-muted">El CPT "Eventos" debe estar configurado en el Plugin Core.</p>
                    </div>
                `;
			});
	}

	/**
	 * Initialize all eventos blocks on page
	 */
	function initEventosBlocks() {
		const blocks = document.querySelectorAll('.eventos-proximos-block');
		blocks.forEach((block) => {
			loadEventos(block);
		});
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initEventosBlocks);
	} else {
		initEventosBlocks();
	}
})();
