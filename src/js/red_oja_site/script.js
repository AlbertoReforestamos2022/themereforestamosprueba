'use strict'; 

document.addEventListener('DOMContentLoaded', ()=>{
    // cambiar estilos del menú
    
    // cambiar de color menu llamado a la acción 
    const catNav = document.querySelector('.llamados-accion');
    catNav.classList.remove('bg-light');
    catNav.style.setProperty('background-color', 'rgb(159, 108, 197)');




})

// Galería de Imágenes
window.addEventListener('load', function () {
    const gallery = document.querySelector('.gallery_content');
    const items = [...document.querySelectorAll('.item_gallery')];

    const prevCarruselBtn = document.querySelector(".arrow-left-carrusel");
    const nextCarruselBtn = document.querySelector(".arrow-right-carrusel");

    const lightbox = document.querySelector('.lightbox-carrusel');
    const lightboxImg = document.querySelector('.lightbox-img img');
    const closeBtn = document.querySelector('.control-close span');

    const prevLightboxBtn = document.querySelector(".arrow-left-lightbox");
    const nextLightboxBtn = document.querySelector(".arrow-right-lightbox");

    let currentIndex = 0;
    let autoSlideInterval;
    let scrollAmount = 1; // Velocidad de desplazamiento
    let position = 0; // Posición inicial del carrusel

    // 🔹 Clonar imágenes para loop infinito
    function duplicateImages() {
        items.forEach(item => {
            const clone = item.cloneNode(true);

            gallery.appendChild(clone);
            items.push(clone)
        });

    }

    duplicateImages();

    // 🔹 Configurar carrusel con `left`
    gallery.style.position = "relative";
    gallery.style.left = "0px";

    // 🔹 Animación automática con `left`
    function startAutoScroll() {
        stopAutoScroll(); // Evita múltiples intervalos

        autoSlideInterval = setInterval(() => {
            position -= scrollAmount; // Mover a la izquierda
            gallery.style.left = `${position}px`;

            // Si llega al final del clon, reiniciar sin parpadeo
            if (Math.abs(position) >= gallery.scrollWidth / 2) {
                position = 0;
            }
        }, 20);
    }

    function stopAutoScroll() {
        clearInterval(autoSlideInterval);
    }

    // 🔹 Flechas de navegación
    prevCarruselBtn.addEventListener("click", () => {
        // stopAutoScroll();
        position += 220;
        gallery.style.left = `${position}px`;
        setTimeout(startAutoScroll, 2000);
    });

    nextCarruselBtn.addEventListener("click", () => {
        // stopAutoScroll();
        position -= 220;
        gallery.style.left = `${position}px`;
        setTimeout(startAutoScroll, 2000);
    });

    // Mostrar imagen en el Lightbox
    function showImage(index) {
        if (index < 0) index = items.length - 1;
        else if (index >= items.length) index = 0;

        currentIndex = index;
        lightboxImg.src = items[currentIndex].querySelector("img").src;
        lightbox.classList.remove("d-none");
        stopAutoScroll();
    }

    items.forEach((item, index) => {
        item.addEventListener("click", () => {
            showImage(index);
        });
    });

    closeBtn.addEventListener("click", () => {
        lightbox.classList.add("d-none");
        startAutoScroll();
    });

    prevLightboxBtn.addEventListener("click", () => {
        showImage(currentIndex - 1);
    });

    nextLightboxBtn.addEventListener("click", () => {
        showImage(currentIndex + 1);
    });

    startAutoScroll(); // ✅ Iniciar el desplazamiento automático
});





// Formulario 
document.querySelector(".formulario_contacto-red-oja").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append("action", "formulario_red_oja");

    // Tomamos los valores del formulario
    const nombre = document.getElementById("name").value;
    const correo = document.getElementById("email").value;
    const telefono = document.getElementById("phone").value;
    const asunto = document.getElementById("subject").value;
    const mensaje = document.getElementById("message").value;


    function Msj_status(idContent, classStatus, mensajeStatus) {
        const msj_status = document.querySelector(idContent);
            msj_status.classList.add(classStatus);
            msj_status.innerHTML = `
                <h5 class="">${mensajeStatus}</h5>
            `;
            msj_status.style.opacity = 1; // Cambia la opacidad a 1 para mostrar el mensaje

            // Después de 8 segundos, animar el "fade out" y recargar la página
            setTimeout(() => {
                msj_status.style.opacity = 0; // Cambia la opacidad a 0 para el "fade out"
                
                // Recargar la página después de 1 segundo para dar tiempo al "fade out"
                setTimeout(() => {
                    location.reload();
                }, 1000); // 1 segundo para la animación de "fade out"
            }, 5000); // Espera de 8 segundos antes de iniciar el "fade out"
    }
    
    // Convertir FormData a URLSearchParams para `fetch`
    const formDataParams = new URLSearchParams(formData);

    fetch(ajax_object.ajax_url, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: formDataParams
        // body: new URLSearchParams({
        //     action: "formulario_ayc",
        //     // Otros campos de tu formulario aquí
        //     name: nombre,
        //     email: correo,
        //     subject: asunto,
        //     message: mensaje,
        // }),
    })
    .then(async (response) => { 
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        try {
            // Intentar parsear JSON
            const data = await response.json();
            if (data.success) {
                const idContentSuccess = '#mensaje-formulario';
                const classStatusSuccess = 'bg-primary';
                const MsjSucess = 'Gracias por tu mensaje, en breve nos contactaremos contigo </br> Revisa también la bandeja de correo no deseado o "spam"';

                Msj_status(idContentSuccess, classStatusSuccess, MsjSucess);   
            } else {
                alert(data.data.message || 'Hubo un problema al enviar el formulario.');
            }
        } catch (jsonError) {
            console.error("JSON parse error:", jsonError);
            throw new Error("La respuesta no es un JSON válido.");
        }
    })
    .catch(error => {
        console.error('Error en el envío del formulario:', error);
        const idContentError = '#mensaje-formulario';
        const classStatusError = 'bg-danger';
        const MsjError = 'Hubo un error al enviar el formulario.';
        Msj_status(idContentError, classStatusError, MsjError); 
    });
});


// Mapa
// Inicializar el mapa centrado en México
let map = L.map('map_red-oja').setView([23.56, -100.90], 5);
// Agregar capa base
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// Agregar el botón de "Vista general" del mapa
const overviewButton = L.control({position: "topright"});

overviewButton.onAdd = function(map) {
    // Crear un contenedor div 
    const contentButton = L.DomUtil.create("div", "custom-button");
    contentButton.innerHTML = `
        <div class="col">
            <button class="w-100 btn btn-accion-red-oja" id="overviewMap">Vista general</button>
        </div>
    `;

    // Evitar que los eventos del botón interfieran con el mapa
    return contentButton; 
}; 

// Agregar el bóton al mapa
overviewButton.addTo(map); 

const listContainer = document.getElementById('list');
listContainer.classList.add('institution-container'); 

/** Crear función para agregar texto de inicio */
const textAllInstitutions = document.createElement('h4'); 
textAllInstitutions.classList.add('subtitulo-red-oja', 'instroduction-text');
textAllInstitutions.textContent = 'Haz click en el menú o en los pines de las ubicaciones para ponerte en contacto con cualquiera de las organizaciones aliadas de la Red Oja.';
listContainer.appendChild(textAllInstitutions);



console.log('Ruta del archivo JSON:', ajax_object.json_url); // Quitar después
console.log('Ruta del archivo JSON:', ajax_object.geojson_url); // Quitar después
// console.log('Ruta del archivo JSON:', ajax_object.content_files_url); // Quitar después


// const mapaSVG = document.querySelector('.mapa_svg_red_oja'); 

Promise.all([
// Cargar datos desde un archivo JSON externo
    fetch(ajax_object.json_url).then(response => {
        if(!response.ok) throw new Error('Error al cargar el archivo Json');
        return response.json();
    }),
    fetch(ajax_object.geojson_url).then(response => {
        if (!response.ok) throw new Error('Error al cargar el archivo GEOJSON');
        return response.json();
    })
    ,fetch(ajax_object.content_files_url).then(response => {
        if(!response.ok) throw new Error('No se pudo cargar el archivo content_files_url');
        return response.json(); 
    })
])
.then(([institutions, states, files]) => {
    // Obtener las coordenadas de los estados
    function getStatesCoordinates(states) {
        return states.features.map(feature => ({
            name: feature.properties["NOM_ENT"],
            coordinates: feature.geometry["coordinates"]
        }));
    }

    // Crear marcadores de las instituciones y agregarlos al mapa
    function createMarkers(institutions, map) {
        const allMarkers = [];
        institutions.forEach(inst => {
            const marker = L.marker([inst.lat, inst.lng]).addTo(map);

            // Contenido HTML para el popup
            const popupContent = `
                <div class="row">
                    <div class="col p-0">
                        <p class="fw-bold">${inst.name}</p>
                    </div>
                    <div class="col d-grid align-items-center justify-content-center">
                        <img src="${inst.logo}" alt="${inst.name}" style="width: 80px; height: auto; margin-bottom: 10px; border-radius: 5px;">
                    </div>
                </div>
            `;

            // Agregar el popup al marcador
            marker.bindPopup(popupContent);
            allMarkers.push({ 
                marker, 
                state: inst.state, 
                name: inst.name,
                localed: inst.location,
                linkRRSS: inst.link,
                urlLogo: inst.logo
            });
        });
        return allMarkers;
    }

    // Función para resaltar un estado en el mapa
    function highlightState(stateName, statesCoords, institutions, map) {
        if (!map) {
            console.error("⚠️ Error: El objeto 'map' no está definido.");
            return;
        }

        // Intentar encontrar el estado en la lista de coordenadas
        let matchedState = statesCoords.find(state => state.name === stateName);
        let geoJSONData;

        // Si se encuentra el estado, crear un polígono
        if (matchedState) {
            const coordinates = matchedState.coordinates.flat(2);
            const sourceProjection = "+proj=lcc +lat_0=12 +lon_0=-102 +lat_1=17.5 +lat_2=29.5 +x_0=2500000 +y_0=0 +datum=WGS84 +units=m +no_defs +type=crs";
            const targetProjection = "EPSG:4326";

            const convertedCoordinates = coordinates.map(coord =>
                proj4(sourceProjection, targetProjection, coord)
            );

            geoJSONData = {
                type: "FeatureCollection",
                features: [
                    {
                        type: "Feature",
                        geometry: {
                            type: "Polygon",
                            coordinates: [convertedCoordinates]
                        },
                        properties: {
                            name: stateName,
                            color: "#9f6cc5",
                            description: `Estado: ${stateName}`
                        }
                    }
                ]
            };
        } else {
            // Si no se encuentra el estado, buscar una institución en ese estado
            const institution = institutions.find(inst => inst.state === stateName);
            if (institution) {
                geoJSONData = {
                    type: "FeatureCollection",
                    features: [
                        {
                            type: "Feature",
                            geometry: {
                                type: "Point",
                                coordinates: [institution.lng, institution.lat] 
                            },
                            properties: {
                                name: institution.name,
                                color: "#ff5733",
                                description: `Institución: ${institution.name} en ${stateName}`
                            }
                        }
                    ]
                };
            } else {
                console.warn(`⚠️ No se encontró ${stateName} en statesCoords ni en institutions.`);
                return; // Si no hay coincidencias, salir de la función
            }
        }

        // Eliminar capa anterior si existe
        if (window.currentPolygonLayer && map.hasLayer(window.currentPolygonLayer)) {
            map.removeLayer(window.currentPolygonLayer);
        }

        // Crear y agregar nueva capa
        window.currentPolygonLayer = L.geoJSON(geoJSONData, {
            style: feature => ({ color: feature.properties.color, fillOpacity: 0.5 }),
            pointToLayer: (feature, latlng) => {
                return L.circleMarker(latlng, {
                    radius: 8,
                    fillColor: feature.properties.color,
                    color: "#000",
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.8
                });
            }
        }).bindPopup(layer => layer.feature.properties.description)
        .addTo(map);

        // Ajustar el zoom según el tipo de geometría (polígono o punto)
        const bounds = window.currentPolygonLayer.getBounds();
        if (geoJSONData.features[0].geometry.type === "Polygon") {
            map.fitBounds(bounds, { padding: [20, 20] });
        } else {
            map.setView(bounds.getCenter(), 10); // Si es un punto, hacer zoom más cercano
        }
    }

    // Variable global para almacenar el estado resaltado actualmente
    let selectedStateLayer = null;
    let statesLayer; 

    // Función para cargar los estados en el mapa como capa GeoJSON interactiva
    function loadStatesLayer(states, map) {
        statesLayer = L.geoJSON(states, {
            style: {
                color: "#555", // Color normal de frontera
                weight: 1,
                fillOpacity: 0.3
            },
            onEachFeature: function (feature, layer) {
                layer.on('click', function () {
                    highlightStateOnClick(layer);
                });
            }
        }).addTo(map);
    }

    // Función para cambiar el color del estado seleccionado
    function highlightStateOnClick(layer) {
        // Resetear el color del estado previamente seleccionado
        if (selectedStateLayer) {
            statesLayer.resetStyle(selectedStateLayer);
        }

        // Cambiar el color del estado seleccionado
        layer.setStyle({
            color: "#9f6cc5", // Nuevo color de borde
            weight: 2,
            fillOpacity: 0.5
        });

        selectedStateLayer = layer; // Guardar el estado seleccionado
    }

    // Mostrar todas las instituciones y sus estados en la lista
    function showAllInstitutions(allMarkers, listId, map, statesCoords) {
        const listContainer = document.getElementById(listId);
        listContainer.classList.add('institution-container'); 
        listContainer.innerHTML = ''; // Limpiar la lista

        const groupedByState = allMarkers.reduce((acc, { state, name, marker, localed, linkRRSS, urlLogo}) => {
            if (!acc[state]) acc[state] = [];
            acc[state].push({ name, marker, localed, linkRRSS, urlLogo });
            return acc;
        }, {});

        const textAllInstitutions = document.createElement('h2'); 
        textAllInstitutions.classList.add('titulo_red-oja')
        textAllInstitutions.textContent = 'Todas las organizaciones';
        listContainer.appendChild(textAllInstitutions);


        // Crear elementos para cada estado y sus instituciones
        for (const [state, institutions] of Object.entries(groupedByState)) {
            const stateTitle = document.createElement('h3');
            stateTitle.classList.add('subtitulo-red-oja', 'text-center', 'my-3');
            stateTitle.textContent = state;
            // agregar el nombre del estado al contenedor padre (listaContainer)
            listContainer.appendChild(stateTitle);

            // contenedor padre de la lista de organizaciones
            const stateContainer = document.createElement('div');
            stateContainer.classList.add('container'); 

            const stateList = document.createElement('div');
            stateList.classList.add('row', 'row-cols-1', 'd-flex', 'justify-content-center', 'institution');

            institutions.forEach(({ name, marker, localed, linkRRSS, urlLogo }) => {
                const listItem = document.createElement('div');
                listItem.classList.add('card', 'text-start', 'm-2', 'border-0', 'shadow','institution');
                listItem.innerHTML = `
                    <div class="card-body">
                        <div class="row row-cols-md-2 justify-content-center align-items-center">

                            <div class="col">
                                <p class="card-title fw-bold">${name}</p>
                                <p class="card-text">Localidad: ${localed}</p>
                                <button class="btn btn-accion-red-oja" id="location">Ver ubicación</button>
                            </div>

                            <div class="col d-flex justify-content-center align-items-center h-100">
                                <img src="${urlLogo}" alt="Logo ${name}" class="img-fluid" width="100">
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-transparent border-0 d-flex justify-content-center">
                        <a class="btn btn-accion-red-oja m-1" href="${linkRRSS}" target="_blank"> Saber más sobre la organización</a>
                        
                    </div>
                `;

                // Evento para centrar y abrir popup al hacer clic en la lista
                listItem.addEventListener('click', () => {
                    map.setView(marker.getLatLng(), 14);
                    marker.openPopup();

                    // Resaltar estado cuando se selecciona una institución
                    highlightState(state, statesCoords, map);
                });

                stateList.appendChild(listItem);
            });
            // agregar stateList a StateContainer
            stateContainer.appendChild(stateList)

            // agregra StateContainer a ListContainer 
            listContainer.appendChild(stateContainer);
        }

        // Mostrar todos los marcadores en el mapa
        allMarkers.forEach(({ marker }) => map.addLayer(marker));

        // Restablecer el color de los estados eliminando la capa resaltada si existe
        if(window.currentPolygonLayer){
            map.removeLayer(window.currentPolygonLayer)
            window.currentPolygonLayer = null;
        }


        // Ajustar el zoom para mostrar todos los marcadores
        const group = L.featureGroup(allMarkers.map(({ marker }) => marker));

        if(group.getBounds().isValid()) {
            map.fitBounds(group.getBounds(), {padding: [20,20]}); 
            
        } else {
            console.warn("No hay marcadores disponibles en este momento en el mapa"); 
        }
    }

    // Filtrar por estado y resaltar en el mapa
    function filterByState(selectedState, statesCoords, allMarkers, map, institutions) {
        const listContainer = document.getElementById('list');
        listContainer.classList.add('institution-container'); 

        // Limpiar lista
        listContainer.innerHTML = ''; 

        // Intentar resaltar el estado, si no se encuentra, usar las coordenadas de las instituciones
        const matchedState = statesCoords.find(state => state.name === selectedState);

        /* condición para resaltar el estado de Colombia */ 
        const matchColombiaState = selectedState === 'Colombia' || institutions.some( inst => inst.country === 'Colombia');

        // Comprobar que matchColombiaState sea true
        console.log(matchColombiaState) ; 

        if (matchedState) {
            highlightState(selectedState, statesCoords, institutions, map);
        } else if (matchColombiaState) {
            highlightState(selectedState, statesCoords, institutions, map);
        }

        const stateTitle = document.createElement('h3');
        stateTitle.classList.add('subtitulo-red-oja', 'text-center', 'my-3');
        stateTitle.textContent = `Organizaciones en ${selectedState}`;
        listContainer.appendChild(stateTitle);

        const stateContainer = document.createElement('div');
        stateContainer.classList.add('container'); 

        const stateList = document.createElement('div');
        stateList.classList.add('row', 'row-cols-1', 'd-flex', 'justify-content-center', 'institution');

        let hasResults = false;

        // Filtrar los marcadores correspondientes al estado seleccionado
        const filteredMarkers = allMarkers.filter(({ state }) => state === selectedState);

        // Mostrar solo los marcadores filtrados
        if (filteredMarkers.length > 0) {
            hasResults = true;
            filteredMarkers.forEach(({ marker, name, localed, linkRRSS, urlLogo }) => {
                map.addLayer(marker);

                const listItem = document.createElement('div');
                listItem.classList.add('card', 'text-start', 'm-2', 'border-0', 'shadow', 'institution');
                listItem.innerHTML = `
                    <div class="card-body">
                        <div class="row row-cols-md-2 justify-content-center align-items-center">
                            <div class="col">
                                <p class="card-title fw-bold">${name}</p>
                                <p class="card-text">Localidad: ${localed}</p>
                                <button class="btn btn-accion-red-oja" id="location">Ver ubicación</button>
                            </div>
                            <div class="col d-flex justify-content-center align-items-center h-100">
                                <img src="${urlLogo}" alt="Logo ${name}" class="img-fluid" width="100">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-center">
                        <a class="btn btn-accion-red-oja m-1" href="${linkRRSS}" target="_blank">Saber más sobre la organización</a>
                    </div>
                `;

                stateList.appendChild(listItem);
                stateContainer.appendChild(stateList);
                listContainer.appendChild(stateContainer);

                listItem.addEventListener('click', () => {
                    map.setView(marker.getLatLng(), 9);
                    marker.openPopup();
                    highlightState(selectedState, statesCoords, map);
                });
            });
        }

        // Eliminar marcadores que no pertenecen al estado seleccionado
        allMarkers.forEach(({ marker, state }) => {
            if (state !== selectedState) {
                map.removeLayer(marker);
            }
        });

        // Si no se encontraron resultados, mostrar el mensaje
        if (!hasResults) {
            const noResultsMessage = document.createElement('p');
            noResultsMessage.classList.add('text-center');
            noResultsMessage.textContent = 'No hay resultados para este estado.';
            listContainer.appendChild(noResultsMessage);
        }
    }

    // Ver los pines de todas las instituciones
    function overviewMap(overviewID, map, allMarkers, listID) {
        document.getElementById(overviewID).addEventListener('click', () => {

            const listContainer = document.getElementById(listID);
            listContainer.classList.add('institution-container'); 

            const textAllInstitutions = document.createElement('h4'); 
            textAllInstitutions.classList.add('subtitulo-red-oja', 'instroduction-text');
            listContainer.innerHTML = ''; // Limpiar la lista


            textAllInstitutions.textContent = 'Haz click en el menú o en los pines de las ubicaciones para ponerte en contacto con cualquiera de las organizaciones aliadas de la Red Oja.';
            listContainer.appendChild(textAllInstitutions);

        // Restablecer el color de los estados eliminando la capa resaltada si existe
        if(window.currentPolygonLayer){
            map.removeLayer(window.currentPolygonLayer)
            window.currentPolygonLayer = null;
        }

        // Asegurar que todos los marcadores se agreguen al mapa
        allMarkers.forEach(({ marker }) => {
            if(!map.addLayer(marker)){
                map.addLayer(marker);
            }
        });

        // Ajustar el zoom para mostrar todos los marcadores
        const group = L.featureGroup(allMarkers.map(({ marker }) => marker));

        if(group.getBounds().isValid()) {
            map.fitBounds(group.getBounds(), {padding: [20,20]}); 
            
        } else {
            console.warn("No hay marcadores disponibles en este momento en el mapa"); 
        }

        })

    }

    // Animación spinner para cuando se quiera mostrar todas las empresas y seleccionar cada una de las empresas
    function showLoader(IdElement) {
        const listcontainer = document.getElementById(IdElement);
        listcontainer.style.position = 'relative';  // Corregido el error en 'relative'

        // crear el contenedor del loader si no existe
        if(!document.getElementById('loadingIndicator')) {
            const loaderDiv = document.createElement('div'); 
            loaderDiv.id = 'loadingIndicator';
            loaderDiv.classList.add('loader-container');
            loaderDiv.innerHTML = ` <div class="loader"></div>`; 

            listcontainer.appendChild(loaderDiv);
        }
    }

    // Quitar la animación 
    function hideLoader(IdElement) {
        const listcontainer = document.getElementById(IdElement);
        listcontainer.style.position = '';  // Corregido el error en 'relative'

        const loaderDiv = document.getElementById('loadingIndicator');

        // Quitar la animación en caso de que esté en pantalla
        if(loaderDiv) {
            loaderDiv.remove();
        }
    }


    // Función principal: Inicializar mapa y eventos
    function initializeMap(states, institutions, map) {
        const statesCoords = getStatesCoordinates(states);
        const allMarkers = createMarkers(institutions.institutions, map);

        // Cargar los estados en el mapa como capa GeoJSON interactiva
        loadStatesLayer(states, map);

        // Evento para mostrar todos las organizaciones aliadas
        document.getElementById('showAll').addEventListener('click', () => {
            showLoader('list');

            setTimeout(()=>{
                showAllInstitutions(allMarkers, 'list', map, statesCoords);

                hideLoader('list');
            }, 1000); 
        });

        // Evento para el filtro de los estados
        document.getElementById('stateFilter').addEventListener('change', e => {
            showLoader('list');

            setTimeout(()=> {
                const selectedState = e.target.value;

                // Resaltar el estado en el mapa
                highlightState(selectedState, statesCoords, institutions.institutions, map); 
                // Filtara instituciones del estado seleccionado 
                filterByState(selectedState, statesCoords, allMarkers, map, institutions.institutions);

                hideLoader('list');
            }, 1000)
        });
        
        // Evento para resetear el mapa 

        overviewMap('overviewMap', map, allMarkers, 'list');


        // Evento para resaltar el estado cuando se haga clic en un marcador
        allMarkers.forEach(({ marker, state }) => {
            marker.on('click', () => {
                showLoader('list'); 

                setTimeout(()=> {
                    highlightState(state, statesCoords, institutions.institutions, map);
                    
                    hideLoader('list'); 
                }, 1000)


            });
        });
    }

    initializeMap(states, institutions, map);


    /** 
     * Sección ¿Qué se ha hecho?  
     * */

    // Mostrar los años
    function showFilterYear(allFiles) {
        const contentFilterYear = document.querySelector('#yearFilter');
        const memoriesForYear = {};
        
        allFiles.forEach(file => {
            let originalYear = file.date;

            // Verificar si originalYear es una cadena y limpiarla si es necesario
            if (typeof originalYear === 'string') {
                originalYear = originalYear.trim();  // Eliminar espacios si es una cadena
            }
    
            // Convertir a número
            originalYear = Number(originalYear);
    
            // Si no es un número válido o el año no está en el rango razonable, asignar "Desconocido"
            if (isNaN(originalYear) || originalYear < 1000 || originalYear > 9999) {
                originalYear = "Desconocido";
            }
    
            // Agrupar archivos por año
            if (!memoriesForYear[originalYear]) {
                memoriesForYear[originalYear] = [];
            }
    
            memoriesForYear[originalYear].push(file);
        });
    
        // Obtener los años únicos
        const uniqueYears = Object.keys(memoriesForYear)
        .sort((a, b) => (b - a));
    
        // Crear las opciones para el filtro
        contentFilterYear.innerHTML = uniqueYears.map(year => {
            return `<option value="${year}">${year}</option>`;
        }).join(''); // Unir las opciones en un solo string
        
    }

    // filtro para mostrar últimos años y categoría
    function showDocumentsOfLatestYearGroupedByCategory(allFiles) {
        const contentFilteredDocs = document.querySelector('#content_results_files');
    
        // Obtener el año más reciente de los documentos
        const latestYear = Math.max(...allFiles.map(file => {
            let fileYear = Number(file.date); // Asegúrate de que 'date' sea el año del documento
            return isNaN(fileYear) ? -Infinity : fileYear; // Si no es un número válido, lo excluimos
        }));
        
        // Agrupar los documentos por categoría y año (solo los documentos del año más reciente)
        const groupedDocuments = {};
    
        allFiles.forEach(file => {
            let fileYear = Number(file.date); // Asegúrate de que 'date' sea el año del documento
            let fileCategory = file.section; // 'category' es la categoría del documento
    
            // Solo procesamos documentos del año más reciente
            if (fileYear === latestYear) {
                // Verificar si la categoría existe en el objeto agrupado
                if (!groupedDocuments[fileCategory]) {
                    groupedDocuments[fileCategory] = {};
                }
    
                // Verificar si el año ya existe en la categoría
                if (!groupedDocuments[fileCategory][fileYear]) {
                    groupedDocuments[fileCategory][fileYear] = [];
                }
    
                // Agregar el documento al grupo correspondiente
                groupedDocuments[fileCategory][fileYear].push(file);
            }
        });
    
        // Mostrar los documentos filtrados y agrupados
        let resultHTML = '';
        
        // Iterar sobre las categorías
        for (let category in groupedDocuments) {
            resultHTML += `
            <div class="row justify-content-center">
                <div class="col py-3">
                    <!-- Mostrar solo un título de sección por categoría -->
                    <div class="d-flex align-items-center flex-shrink-0 p-3 link-body-emphasis text-decoration-none border-bottom border-top">
                        <span class="subtitulo-red-oja" style="font-size:20px; margin-right:5px; margin-left: 5px; margin-bottom:6px;"><i class="bi bi-archive"></i></span>
                        <h4 class="subtitulo-red-oja fw-semibold">${category}</h4>
                    </div>
            `;

            // Iterar sobre los años dentro de cada categoría (solo el último año)
            for (let year in groupedDocuments[category]) {
                const dateLoaded = document.querySelector('#dateLoaded'); 
                dateLoaded.textContent = `${year}`;

                resultHTML += `
                    <div class="list-group list-group-flush scrollarea">
                `;  

                // Listar los documentos para cada año en esa categoría
                groupedDocuments[category][year].forEach(doc => {
                    resultHTML += `
                        <div class="list-group-item list-group-item-action border-0 py-3 lh-sm" aria-current="true">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <strong class="mb-1">
                                    <span class="mx-1"><i class="bi bi-file-earmark-post"></i></span>
                                    ${doc.name}
                                </strong>
                                <a class="btn btn-accion-red-oja"href="${doc.link}" target="_blank"> Ver documento</a>
                                
                            </div>
                        </div> 
                    `;
                });

                resultHTML += `
                    </div> <!-- Cierra .list-group -->
                `; 
            }

            resultHTML += `</div></div>`; // Cierra la categoría y la fila
        }        
    
        // Insertar el HTML generado en el contenedor
        contentFilteredDocs.innerHTML = resultHTML;
    }
    
    // Filtar documentos por fecha 
    function filterByYear(year, allFiles) {       
        let titleYear = document.querySelector('#dateLoaded'); 
        titleYear.textContent = year;  
        // console.log(year);

        const contentFilteredDocs = document.querySelector('#content_results_files');

        // Filtrar documentos por el año seleccionado
        const filteredFiles = allFiles.filter(file => file.date == year); 
        
        if(filteredFiles.length == 0) {
            contentFilteredDocs.innerHTML = `<p class="text-center mt-3">No hay documentos disponibles para ${year}.</p>`;
            return; 
        }

        // Agrupar documentos por categoría
        const groupedDocuments = {}; 

        filteredFiles.forEach(({name, section, link }) => {
            if(!groupedDocuments[section]) {
                groupedDocuments[section] = []; 

            }

            groupedDocuments[section].push({name, link}); 

        })


        let hasResults = false
        let resultHTML = `
        <div class="row justify-content-center">
            <div class="col py-3">

        `;

        for (let category in groupedDocuments) {
            resultHTML += `
                <div class="category-section mt-3">
                    <div class="d-flex align-items-center flex-shrink-0 p-3 link-body-emphasis text-decoration-none border-bottom border-top">
                        <span class="subtitulo-red-oja" style="font-size:20px; margin-right:5px; margin-left: 5px; margin-bottom:6px;">
                            <i class="bi bi-archive"></i>
                        </span>
                        <h4 class="subtitulo-red-oja fw-semibold">${category}</h4>
                    </div>

                    <div class="list-group list-group-flush scrollarea">
            `;

            groupedDocuments[category].forEach(doc => {
                resultHTML += `
                        <div class="list-group-item list-group-item-action border-0 py-3 lh-sm" aria-current="true">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <strong class="mb-1">
                                    <span class="mx-1"><i class="bi bi-file-earmark-post"></i></span>
                                    ${doc.name}
                                </strong>
                                <a class="btn btn-accion-red-oja"href="${doc.link}" target="_blank"> Ver documento</a>
                                
                            </div>
                        </div>            
                `;
            });
            resultHTML += `
                    </div> <!-- .list-group -->
                </div> <!-- .category-section -->           
            `;       
        }
            resultHTML += `
            <div>
        </div>`;

        // Insertar el HTML generado en el contenedor
        contentFilteredDocs.innerHTML = resultHTML;


    }

    // Buscar documentos por palabra clave
    function filterByKeyword(keyword, allFiles) {
        let titleYear = document.querySelector('#dateLoaded'); 
        titleYear.textContent = '';  

        const contentFilteredDocs = document.querySelector('#content_results_files');
        keyword = keyword.toLowerCase().trim(); // Convertir a minúsculas y eliminar espacios extras

        let resultHTML = '';
        let hasResults = false;

        // Agrupar los documentos filtrados por categoría y año
        const groupedDocuments = {};

        allFiles.forEach(({ name, section, date, link }) => {
            if (name.toLowerCase().includes(keyword)) {
                hasResults = true;

                // Verificar si la categoría ya existe
                if (!groupedDocuments[section]) {
                    groupedDocuments[section] = {};
                }

                // Verificar si el año ya existe dentro de la categoría
                if (!groupedDocuments[section][date]) {
                    groupedDocuments[section][date] = [];
                }

                // Agregar el documento al grupo correcto
                groupedDocuments[section][date].push({ name, link });
            }

            if(keyword == ''){
                hasResults = false; 
                resultHTML = `<p class="text-center mt-3">Ingresa una palabra para poder mostrar resultados</strong>".</p>`;
            }
        });

        // Construir el HTML si hay resultados
        if (hasResults) {
            for (let category in groupedDocuments) {
                for (let year in groupedDocuments[category]) {
                    resultHTML += `
                    <div class="row justify-content-center">
                        <div class="col py-3">
                            <div class="d-flex align-items-center flex-shrink-0 p-3 link-body-emphasis text-decoration-none border-bottom border-top">
                                <span class="subtitulo-red-oja" style="font-size:20px; margin-right:5px; margin-left: 5px; margin-bottom:6px;">
                                    <i class="bi bi-archive"></i>
                                </span>
                                <h4 class="subtitulo-red-oja fw-semibold">${category} (${year})</h4> <!-- Mostrar la categoría y su año -->
                            </div>
                            <div class="list-group list-group-flush scrollarea">
                    `;

                    groupedDocuments[category][year].forEach(doc => {
                        resultHTML += `
                            <div class="list-group-item list-group-item-action border-0 py-3 lh-sm" aria-current="true">
                                <div class="d-flex w-100 align-items-center justify-content-between">
                                    <strong class="mb-1">
                                        <span class="mx-1"><i class="bi bi-file-earmark-post"></i></span>
                                        ${doc.name}
                                    </strong>
                                    <a class="btn btn-accion-red-oja"href="${doc.link}" target="_blank"> Ver documento</a>
                                    
                                </div>
                            </div> 
                        `;
                    });

                    resultHTML += `
                            </div> <!-- Cierra .list-group -->
                        </div>
                    </div>`; // Cierra la categoría y la fila
                }
            }
        } else {
            resultHTML = `<p class="text-center mt-3">No se encontraron resultados para "<strong>${keyword}</strong>".</p>`;
        }

        contentFilteredDocs.innerHTML = resultHTML;
    }

    // InitializeFiles 
    function initializeFiles(allFiles){

        // mostrar los años y los últimos documentos al cargar la página
        showFilterYear(allFiles); 
        // mostrar los documentos que se han actualizado el último año 
        showDocumentsOfLatestYearGroupedByCategory(allFiles);

        // filterByYear 
        document.querySelector('#yearFilter').addEventListener('change', e => {
            showLoader('content_results_files');

            setTimeout(()=> {
                const year = e.target.value; 

                filterByYear(year, allFiles);

                hideLoader('content_results_files');
            }, 1000)
        })

        // Escuchar el evento de entrada en el buscador
        document.querySelector('#searchFiles').addEventListener('input', e => {
            showLoader('content_results_files');

            setTimeout(() => {
                const keyword = e.target.value;
                filterByKeyword(keyword, allFiles);

                hideLoader('content_results_files');
            }, 1000)
        });
    }

    // Obtener files 
    const allFiles = files.files; 
    
    initializeFiles(allFiles);

        
})
.catch(error => console.error('Error cargando el archivo JSON:', error))


