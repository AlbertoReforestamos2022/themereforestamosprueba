/**
 * Map Handler
 * 
 * Handles Leaflet map initialization and interactions for microsites.
 * 
 * @package Reforestamos_Micrositios
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * ReforestamosMap object
     * Main object for handling map functionality
     */
    window.ReforestamosMap = {
        
        /**
         * Map instances storage
         */
        maps: {},
        
        /**
         * Data cache
         */
        dataCache: {},

        /**
         * Initialize Árboles y Ciudades map
         * 
         * @param {Object} config Map configuration
         */
        initArbolesMap: function(config) {
            const self = this;
            const containerId = config.containerId || 'arboles-map';
            
            // Initialize map
            const map = L.map(containerId).setView(config.center, config.zoom);
            
            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 18
            }).addTo(map);
            
            // Store map instance
            this.maps[containerId] = map;
            
            // Load data
            this.loadData(config.dataFile, function(data) {
                if (data && data.arboles) {
                    self.renderArbolesMarkers(map, data.arboles);
                    self.updateArbolesStats(data.arboles);
                    self.populateArbolesFilters(data.arboles);
                }
            });
            
            // Setup filters
            this.setupArbolesFilters(map);
        },

        /**
         * Initialize Red OJA map
         * 
         * @param {Object} config Map configuration
         */
        initOJAMap: function(config) {
            const self = this;
            const containerId = config.containerId || 'oja-map';
            
            // Only initialize map if container exists
            if (!document.getElementById(containerId)) {
                return;
            }
            
            // Initialize map
            const map = L.map(containerId).setView(config.center, config.zoom);
            
            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 18
            }).addTo(map);
            
            // Store map instance
            this.maps[containerId] = map;
            
            // Load data
            this.loadData(config.dataFile, function(data) {
                if (data && data.organizaciones) {
                    self.renderOJAMarkers(map, data.organizaciones);
                    self.updateOJAStats(data.organizaciones);
                    self.populateOJAFilters(data.organizaciones);
                    
                    // Render directory if needed
                    if (config.view === 'both' || config.view === 'directory') {
                        self.renderOJADirectory(data.organizaciones);
                    }
                }
            });
            
            // Setup filters
            this.setupOJAFilters(map);
        },

        /**
         * Load JSON data
         * 
         * @param {string} filename JSON filename
         * @param {Function} callback Callback function
         */
        loadData: function(filename, callback) {
            const self = this;
            
            // Check cache
            if (this.dataCache[filename]) {
                callback(this.dataCache[filename]);
                return;
            }
            
            // Load from server
            $.ajax({
                url: reforestamosData.dataUrl + filename,
                dataType: 'json',
                success: function(data) {
                    self.dataCache[filename] = data;
                    callback(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading data:', error);
                    callback(null);
                }
            });
        },

        /**
         * Render Árboles markers on map
         * 
         * @param {Object} map Leaflet map instance
         * @param {Array} arboles Array of tree data
         */
        renderArbolesMarkers: function(map, arboles) {
            const self = this;
            
            // Create marker cluster group if available
            let markerGroup;
            if (typeof L.markerClusterGroup !== 'undefined') {
                markerGroup = L.markerClusterGroup({
                    maxClusterRadius: 50,
                    spiderfyOnMaxZoom: true,
                    showCoverageOnHover: false,
                    zoomToBoundsOnClick: true
                });
            } else {
                markerGroup = L.layerGroup();
            }
            
            arboles.forEach(function(arbol) {
                if (arbol.ubicacion && arbol.ubicacion.lat && arbol.ubicacion.lng) {
                    const marker = L.marker([arbol.ubicacion.lat, arbol.ubicacion.lng]);
                    
                    // Create popup content
                    const popupContent = self.createArbolPopup(arbol);
                    marker.bindPopup(popupContent);
                    
                    // Store data in marker
                    marker.arbolData = arbol;
                    
                    // Add to cluster group
                    markerGroup.addLayer(marker);
                }
            });
            
            // Add marker group to map
            map.addLayer(markerGroup);
            
            // Store marker group for filtering
            map.arbolesMarkerGroup = markerGroup;
        },

        /**
         * Render Red OJA markers on map
         * 
         * @param {Object} map Leaflet map instance
         * @param {Array} organizaciones Array of organization data
         */
        renderOJAMarkers: function(map, organizaciones) {
            const self = this;
            
            // Create marker cluster group if available
            let markerGroup;
            if (typeof L.markerClusterGroup !== 'undefined') {
                markerGroup = L.markerClusterGroup({
                    maxClusterRadius: 50,
                    spiderfyOnMaxZoom: true,
                    showCoverageOnHover: false,
                    zoomToBoundsOnClick: true
                });
            } else {
                markerGroup = L.layerGroup();
            }
            
            organizaciones.forEach(function(org) {
                if (org.ubicacion && org.ubicacion.lat && org.ubicacion.lng) {
                    const marker = L.marker([org.ubicacion.lat, org.ubicacion.lng]);
                    
                    // Create popup content
                    const popupContent = self.createOJAPopup(org);
                    marker.bindPopup(popupContent);
                    
                    // Store data in marker
                    marker.orgData = org;
                    
                    // Add to cluster group
                    markerGroup.addLayer(marker);
                }
            });
            
            // Add marker group to map
            map.addLayer(markerGroup);
            
            // Store marker group for filtering
            map.ojaMarkerGroup = markerGroup;
        },

        /**
         * Create popup content for tree marker
         * 
         * @param {Object} arbol Tree data
         * @return {string} HTML content
         */
        createArbolPopup: function(arbol) {
            let html = '<div class="map-popup-content">';
            html += '<h4>' + this.escapeHtml(arbol.especie) + '</h4>';
            
            if (arbol.nombre_cientifico) {
                html += '<p class="scientific-name"><em>' + this.escapeHtml(arbol.nombre_cientifico) + '</em></p>';
            }
            
            if (arbol.ciudad) {
                html += '<p><strong>Ciudad:</strong> ' + this.escapeHtml(arbol.ciudad) + '</p>';
            }
            
            if (arbol.cantidad) {
                html += '<p><strong>Cantidad:</strong> ' + arbol.cantidad + ' árboles</p>';
            }
            
            if (arbol.descripcion) {
                html += '<p>' + this.escapeHtml(arbol.descripcion) + '</p>';
            }
            
            html += '</div>';
            return html;
        },

        /**
         * Create popup content for organization marker
         * 
         * @param {Object} org Organization data
         * @return {string} HTML content
         */
        createOJAPopup: function(org) {
            let html = '<div class="map-popup-content">';
            html += '<h4>' + this.escapeHtml(org.nombre) + '</h4>';
            
            if (org.ciudad && org.estado) {
                html += '<p><strong>Ubicación:</strong> ' + this.escapeHtml(org.ciudad) + ', ' + this.escapeHtml(org.estado) + '</p>';
            }
            
            if (org.tipo) {
                html += '<p><strong>Tipo:</strong> ' + this.escapeHtml(org.tipo) + '</p>';
            }
            
            if (org.descripcion) {
                html += '<p>' + this.escapeHtml(org.descripcion) + '</p>';
            }
            
            if (org.contacto && org.contacto.website) {
                html += '<p><a href="' + this.escapeHtml(org.contacto.website) + '" target="_blank" class="btn btn-sm btn-primary">Visitar sitio web</a></p>';
            }
            
            html += '</div>';
            return html;
        },

        /**
         * Update Árboles statistics
         * 
         * @param {Array} arboles Array of tree data
         */
        updateArbolesStats: function(arboles) {
            const totalArboles = arboles.reduce((sum, arbol) => sum + (arbol.cantidad || 0), 0);
            const especies = new Set(arboles.map(a => a.especie));
            const ciudades = new Set(arboles.map(a => a.ciudad));
            
            // Animate the numbers
            this.animateNumber($('#total-arboles'), 0, totalArboles);
            this.animateNumber($('#total-especies'), 0, especies.size);
            this.animateNumber($('#total-ciudades'), 0, ciudades.size);
        },
        
        /**
         * Animate number counter
         * 
         * @param {jQuery} $element jQuery element
         * @param {number} start Start value
         * @param {number} end End value
         */
        animateNumber: function($element, start, end) {
            const duration = 1000;
            const startTime = Date.now();
            
            const updateNumber = function() {
                const now = Date.now();
                const progress = Math.min((now - startTime) / duration, 1);
                const current = Math.floor(start + (end - start) * progress);
                
                $element.text(current.toLocaleString());
                
                if (progress < 1) {
                    requestAnimationFrame(updateNumber);
                }
            };
            
            requestAnimationFrame(updateNumber);
        },

        /**
         * Update Red OJA statistics
         * 
         * @param {Array} organizaciones Array of organization data
         */
        updateOJAStats: function(organizaciones) {
            const totalOrgs = organizaciones.length;
            const estados = new Set(organizaciones.map(o => o.estado));
            const totalJovenes = organizaciones.reduce((sum, org) => sum + (org.miembros || 0), 0);
            
            // Animate the numbers
            this.animateNumber($('#total-organizaciones'), 0, totalOrgs);
            this.animateNumber($('#total-estados'), 0, estados.size);
            this.animateNumber($('#total-jovenes'), 0, totalJovenes);
        },

        /**
         * Populate Árboles filters
         * 
         * @param {Array} arboles Array of tree data
         */
        populateArbolesFilters: function(arboles) {
            const ciudades = new Set(arboles.map(a => a.ciudad).filter(Boolean));
            const especies = new Set(arboles.map(a => a.especie).filter(Boolean));
            
            const $ciudadSelect = $('#filter-ciudad');
            const $especieSelect = $('#filter-especie');
            
            ciudades.forEach(ciudad => {
                $ciudadSelect.append($('<option>', {
                    value: ciudad,
                    text: ciudad
                }));
            });
            
            especies.forEach(especie => {
                $especieSelect.append($('<option>', {
                    value: especie,
                    text: especie
                }));
            });
        },

        /**
         * Populate Red OJA filters
         * 
         * @param {Array} organizaciones Array of organization data
         */
        populateOJAFilters: function(organizaciones) {
            const estados = new Set(organizaciones.map(o => o.estado).filter(Boolean));
            const tipos = new Set(organizaciones.map(o => o.tipo).filter(Boolean));
            const actividades = new Set();
            
            // Collect all unique activities
            organizaciones.forEach(org => {
                if (org.actividades && Array.isArray(org.actividades)) {
                    org.actividades.forEach(act => actividades.add(act));
                }
            });
            
            const $estadoSelect = $('#filter-estado');
            const $tipoSelect = $('#filter-tipo');
            const $actividadSelect = $('#filter-actividad');
            
            estados.forEach(estado => {
                $estadoSelect.append($('<option>', {
                    value: estado,
                    text: estado
                }));
            });
            
            tipos.forEach(tipo => {
                $tipoSelect.append($('<option>', {
                    value: tipo,
                    text: tipo
                }));
            });
            
            actividades.forEach(actividad => {
                $actividadSelect.append($('<option>', {
                    value: actividad,
                    text: actividad
                }));
            });
        },

        /**
         * Setup Árboles filters
         * 
         * @param {Object} map Leaflet map instance
         */
        setupArbolesFilters: function(map) {
            const self = this;
            
            // Filter by ciudad or especie
            $('#filter-ciudad, #filter-especie').on('change', function() {
                self.filterArbolesMarkers(map);
            });
            
            // Search functionality
            let searchTimeout;
            $('#search-arbol').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    self.filterArbolesMarkers(map);
                }, 300);
            });
        },
        
        /**
         * Filter Árboles markers based on current filter values
         * 
         * @param {Object} map Leaflet map instance
         */
        filterArbolesMarkers: function(map) {
            const ciudad = $('#filter-ciudad').val();
            const especie = $('#filter-especie').val();
            const searchText = $('#search-arbol').val().toLowerCase();
            
            const markerGroup = map.arbolesMarkerGroup;
            if (!markerGroup) return;
            
            let visibleCount = 0;
            
            markerGroup.eachLayer(function(marker) {
                const arbol = marker.arbolData;
                let visible = true;
                
                // Filter by ciudad
                if (ciudad && arbol.ciudad !== ciudad) {
                    visible = false;
                }
                
                // Filter by especie
                if (especie && arbol.especie !== especie) {
                    visible = false;
                }
                
                // Filter by search text
                if (searchText) {
                    const searchableText = [
                        arbol.especie,
                        arbol.nombre_cientifico,
                        arbol.ciudad,
                        arbol.descripcion,
                        arbol.proyecto
                    ].join(' ').toLowerCase();
                    
                    if (searchableText.indexOf(searchText) === -1) {
                        visible = false;
                    }
                }
                
                // Show or hide marker
                if (visible) {
                    if (!map.hasLayer(marker)) {
                        markerGroup.addLayer(marker);
                    }
                    visibleCount++;
                } else {
                    if (map.hasLayer(marker)) {
                        markerGroup.removeLayer(marker);
                    }
                }
            });
            
            // Update stats with filtered data
            this.updateFilteredStats(map, visibleCount);
        },
        
        /**
         * Update statistics with filtered data
         * 
         * @param {Object} map Leaflet map instance
         * @param {number} visibleCount Number of visible markers
         */
        updateFilteredStats: function(map, visibleCount) {
            const markerGroup = map.arbolesMarkerGroup;
            if (!markerGroup) return;
            
            let totalArboles = 0;
            const especies = new Set();
            const ciudades = new Set();
            
            markerGroup.eachLayer(function(marker) {
                if (map.hasLayer(marker)) {
                    const arbol = marker.arbolData;
                    totalArboles += arbol.cantidad || 0;
                    especies.add(arbol.especie);
                    ciudades.add(arbol.ciudad);
                }
            });
            
            $('#total-arboles').text(totalArboles.toLocaleString());
            $('#total-especies').text(especies.size);
            $('#total-ciudades').text(ciudades.size);
        },

        /**
         * Setup Red OJA filters
         * 
         * @param {Object} map Leaflet map instance
         */
        setupOJAFilters: function(map) {
            const self = this;
            
            // Filter by estado, tipo, or actividad
            $('#filter-estado, #filter-tipo, #filter-actividad').on('change', function() {
                self.filterOJAMarkers(map);
            });
            
            // Search functionality
            let searchTimeout;
            $('#search-organizacion').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    self.filterOJAMarkers(map);
                }, 300);
            });
        },
        
        /**
         * Filter Red OJA markers based on current filter values
         * 
         * @param {Object} map Leaflet map instance
         */
        filterOJAMarkers: function(map) {
            const estado = $('#filter-estado').val();
            const tipo = $('#filter-tipo').val();
            const actividad = $('#filter-actividad').val();
            const searchText = $('#search-organizacion').val().toLowerCase();
            
            const markerGroup = map.ojaMarkerGroup;
            if (!markerGroup) return;
            
            let visibleCount = 0;
            
            markerGroup.eachLayer(function(marker) {
                const org = marker.orgData;
                let visible = true;
                
                // Filter by estado
                if (estado && org.estado !== estado) {
                    visible = false;
                }
                
                // Filter by tipo
                if (tipo && org.tipo !== tipo) {
                    visible = false;
                }
                
                // Filter by actividad
                if (actividad && org.actividades) {
                    if (!org.actividades.includes(actividad)) {
                        visible = false;
                    }
                }
                
                // Filter by search text
                if (searchText) {
                    const searchableText = [
                        org.nombre,
                        org.ciudad,
                        org.estado,
                        org.descripcion,
                        org.tipo,
                        org.actividades ? org.actividades.join(' ') : ''
                    ].join(' ').toLowerCase();
                    
                    if (searchableText.indexOf(searchText) === -1) {
                        visible = false;
                    }
                }
                
                // Show or hide marker
                if (visible) {
                    if (!map.hasLayer(marker)) {
                        markerGroup.addLayer(marker);
                    }
                    visibleCount++;
                } else {
                    if (map.hasLayer(marker)) {
                        markerGroup.removeLayer(marker);
                    }
                }
            });
            
            // Update directory if present
            this.filterOJADirectory(map);
        },
        
        /**
         * Filter Red OJA directory view
         * 
         * @param {Object} map Leaflet map instance
         */
        filterOJADirectory: function(map) {
            const self = this;
            const markerGroup = map.ojaMarkerGroup;
            if (!markerGroup) return;
            
            const $directory = $('#oja-directory .directory-list');
            if ($directory.length === 0) return;
            
            $directory.empty();
            
            markerGroup.eachLayer(function(marker) {
                if (map.hasLayer(marker)) {
                    const org = marker.orgData;
                    const $item = $('<div>', { 
                        class: 'directory-item',
                        'data-org-id': org.id
                    });
                    
                    let html = '<h4>' + self.escapeHtml(org.nombre) + '</h4>';
                    html += '<p><strong>Ubicación:</strong> ' + self.escapeHtml(org.ciudad) + ', ' + self.escapeHtml(org.estado) + '</p>';
                    
                    if (org.tipo) {
                        html += '<p><strong>Tipo:</strong> ' + self.escapeHtml(org.tipo) + '</p>';
                    }
                    
                    if (org.actividades && org.actividades.length > 0) {
                        html += '<p><strong>Actividades:</strong> ' + org.actividades.map(a => self.escapeHtml(a)).join(', ') + '</p>';
                    }
                    
                    if (org.descripcion) {
                        html += '<p>' + self.escapeHtml(org.descripcion) + '</p>';
                    }
                    
                    if (org.contacto && org.contacto.website) {
                        html += '<a href="' + self.escapeHtml(org.contacto.website) + '" target="_blank" class="btn btn-sm btn-primary">Visitar sitio web</a> ';
                    }
                    
                    html += '<button class="btn btn-sm btn-secondary view-on-map" data-lat="' + org.ubicacion.lat + '" data-lng="' + org.ubicacion.lng + '">Ver en mapa</button>';
                    
                    $item.html(html);
                    $directory.append($item);
                }
            });
            
            // Add click handlers for "View on map" buttons
            $('.view-on-map').on('click', function() {
                const lat = parseFloat($(this).data('lat'));
                const lng = parseFloat($(this).data('lng'));
                
                if (map) {
                    map.setView([lat, lng], 12);
                    
                    // Find and open the marker popup
                    const markerGroup = map.ojaMarkerGroup;
                    if (markerGroup) {
                        markerGroup.eachLayer(function(marker) {
                            const markerLatLng = marker.getLatLng();
                            if (Math.abs(markerLatLng.lat - lat) < 0.0001 && Math.abs(markerLatLng.lng - lng) < 0.0001) {
                                marker.openPopup();
                            }
                        });
                    }
                }
            });
        },

        /**
         * Render Red OJA directory view
         * 
         * @param {Array} organizaciones Array of organization data
         */
        renderOJADirectory: function(organizaciones) {
            const self = this;
            const $directory = $('#oja-directory .directory-list');
            $directory.empty();
            
            organizaciones.forEach(function(org) {
                const $item = $('<div>', { 
                    class: 'directory-item',
                    'data-org-id': org.id
                });
                
                let html = '<h4>' + self.escapeHtml(org.nombre) + '</h4>';
                html += '<p><strong>Ubicación:</strong> ' + self.escapeHtml(org.ciudad) + ', ' + self.escapeHtml(org.estado) + '</p>';
                
                if (org.tipo) {
                    html += '<p><strong>Tipo:</strong> ' + self.escapeHtml(org.tipo) + '</p>';
                }
                
                if (org.actividades && org.actividades.length > 0) {
                    html += '<p><strong>Actividades:</strong> ' + org.actividades.map(a => self.escapeHtml(a)).join(', ') + '</p>';
                }
                
                if (org.descripcion) {
                    html += '<p>' + self.escapeHtml(org.descripcion) + '</p>';
                }
                
                if (org.contacto && org.contacto.website) {
                    html += '<a href="' + self.escapeHtml(org.contacto.website) + '" target="_blank" class="btn btn-sm btn-primary">Visitar sitio web</a> ';
                }
                
                html += '<button class="btn btn-sm btn-secondary view-on-map" data-lat="' + org.ubicacion.lat + '" data-lng="' + org.ubicacion.lng + '">Ver en mapa</button>';
                
                $item.html(html);
                $directory.append($item);
            });
            
            // Add click handlers for "View on map" buttons
            $('.view-on-map').on('click', function() {
                const lat = parseFloat($(this).data('lat'));
                const lng = parseFloat($(this).data('lng'));
                const map = self.maps['oja-map'];
                
                if (map) {
                    map.setView([lat, lng], 12);
                    
                    // Find and open the marker popup
                    const markerGroup = map.ojaMarkerGroup;
                    if (markerGroup) {
                        markerGroup.eachLayer(function(marker) {
                            const markerLatLng = marker.getLatLng();
                            if (Math.abs(markerLatLng.lat - lat) < 0.0001 && Math.abs(markerLatLng.lng - lng) < 0.0001) {
                                marker.openPopup();
                            }
                        });
                    }
                }
            });
        },

        /**
         * Escape HTML to prevent XSS
         * 
         * @param {string} text Text to escape
         * @return {string} Escaped text
         */
        escapeHtml: function(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    };

})(jQuery);
