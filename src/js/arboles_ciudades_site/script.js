'use strict'; 

document.addEventListener("DOMContentLoaded", function () {
    // Función para animar el counter
    function animateCounter(counter) {
        const target = +counter.getAttribute('data-target');
        const duration = 2000; // Duración en milisegundos
        const increment = target / (duration / 10); // Incremento en cada paso
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.innerText = Math.ceil(current);
                setTimeout(updateCounter, 10); // Cada 10ms actualiza
            } else {
                counter.innerText = target;
            }
        };

        updateCounter();
    }

    // Función para inicializar el observador
    function initializeCounters() {
        const counters = document.querySelectorAll('.counter');
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target); // Deja de observar después de activar
                }
            });
        }, { threshold: 0.5 }); // 50% del elemento visible para activar

        counters.forEach(counter => {
            observer.observe(counter);
        });
    }

    initializeCounters();
});

/** Eliminar después */
console.log("Ajax URL:", ajax_object.ajax_url);

// console.log("Últimas ciudades reconocidas JSON", ajax_object.recognized_cities_json_url);  // Últimas ciudades reconocidas
// console.log("Mapa GeoJSON", ajax_object.geojson_url);  // Poligonos mapa
// console.log("Ciudades reconocidas 2019 - 2024: ", ajax_object.recognized_cities) // Ciudades reconocidas 2019 - 2024

// Formulario de contacto
document.querySelector(".formulario_contacto-ac").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append("action", "formulario_AyC");

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

/* Carrrusel iniciativas Árboles y Ciudades */
/* gallery-container */
// .carrusel-items-initiatives

/* gallery-item */

// Variables
const galleryContainer = document.querySelector('.carrusel-items-initiatives'); 
const galleryControlsContainer = document.querySelector('.gallery-controls');
const galleryControls = ['previous', 'next']; 
const galleryItems = document.querySelectorAll('.card-content-initiatives');

// Clases 
class Carrusel {
    constructor(container, items, controls) {
        this.carruselContainer = container;
        this.carruselControls = controls;
        this.carruselArray = [...items];
    }

    updateGallery() {

        this.carruselArray.forEach(el => {
            el.classList.remove('card-content-initiatives-0');
            el.classList.remove('card-content-initiatives-1');
            el.classList.remove('card-content-initiatives-2');
            el.classList.remove('card-content-initiatives-3');
            el.classList.remove('card-content-initiatives-4');
        });

        this.carruselArray.slice(0, 5).forEach((el, i) => {
            el.classList.add(`card-content-initiatives-${i}`);
 
        })
    }

    // funciones
    setCurrentState(direction) {
        if(direction.classList.contains('gallery-controls-previous')) {
            this.carruselArray.unshift(this.carruselArray.pop());
        } else {
            this.carruselArray.push(this.carruselArray.shift());
        }

        this.updateGallery();
    }

    setControls() {
        this.carruselControls.forEach(control => {
            if(control == 'previous' || control == 'next') {
                let previousContent = document.createElement('div');
                let previousButton = document.createElement('button');

                previousContent.classList.add('col', 'd-flex', 'justify-content-center'); 
                previousButton.classList.add('btn', 'btn-primary', `gallery-controls-${control}`);
                previousButton.innerHTML = `<i class="bi bi-arrow-${control === 'previous' ? 'left' : 'right'}"></i>`;


                previousContent.appendChild(previousButton)
                galleryControlsContainer.appendChild(previousContent);
                
            }
        }); 
    }

    useControls() {
        const triggers = [...galleryControlsContainer.children]; // Cambiado de childNodes a children
        triggers.forEach(control => {
            const button = control.querySelector("button"); // Asegurar que estamos tomando el botón
            button.addEventListener("click", (e) => {
                e.preventDefault();
                this.setCurrentState(button); // Pasamos el botón, no el div
            });
        });
    }

}

const exampleCarrusel = new Carrusel(galleryContainer, galleryItems, galleryControls);

exampleCarrusel.setControls();
exampleCarrusel.useControls();



// Ocultar todas las iniciativas al inicio
document.querySelectorAll('.description-initiatives').forEach(initiative => {
    initiative.classList.add('d-none');
});

const containerGeneralInitiatives =  document.querySelector('.container-initiatives')
containerGeneralInitiatives.classList.add('d-none');

// Botón iniciativas
const btnInitiatives = document.querySelectorAll('.btn-card-initiative');
const imgCardInitiative = document.querySelectorAll('.img-initiative'); 

// Botón cerrar contenedor iniciativa
const btnCloseInitiative = document.querySelectorAll('.close-initiative'); 

// btnIniciativas
btnInitiatives.forEach(btnInitiative => {
    showMoreInformation(btnInitiative); 

});

// ImgCards
imgCardInitiative.forEach(imgCard => {
    showMoreInformation(imgCard); 

})

// función para mostrar/ ocultar la información de las iniciativas
function showMoreInformation(target) {
    target.addEventListener('click', (e) => {
        e.preventDefault();

        const dataTarget = target.getAttribute('data-bs-target');

        const idContainerfound = document.querySelector(`${dataTarget}`);

        if(idContainerfound) {    
            // borrar titulo general 
            document.querySelector('.title-container-initiatives').classList.add('d-none');       

            /**
             * Agregar 'd-none' al botón 
             * 
             * Cambiar la función que cuando hagamos click en la imgagen solamente agrege 'd-block' 
             * al botón de 'saber más' y no a la imagen, lo mismo para la función 'restaurarEstilos()'
             * 
             */
            
            target.parentElement.classList.add('d-none'); 

            btnCloseInitiative.forEach(btn => {
                btn.parentElement.classList.remove('d-none')
            })

            /** 
             * Comprobar si el elemento es una imagen 
             * 
            */

            if(target.classList.contains('img-initiative')) {
                target.parentElement.classList.remove('d-none'); 
                
                btnInitiatives.forEach(btnInitiative => {
                    btnInitiative.classList.add('d-none'); 
                })
            }

            const imgInitiative = document.querySelector('.img-initiative');
            imgInitiative.style.width = "500px"; 

            // Quitar clases d-none
            idContainerfound.classList.remove('d-none');
            containerGeneralInitiatives.classList.remove('d-none');
            containerGeneralInitiatives.classList.add('principal-initiative'); 
            // containerGeneralInitiatives.style.height = "700px";

            // Agregar estilos para hacer más ancho el contenedor y agregarle overflow 

            // Hacer mas alto el contenedor general
            const principalContent = document.querySelector('.content-initiatives');
            /** Agregar mediaqueries a .principal-initiative */
            principalContent.classList.add('principal-container');  
            // principalContent.style.height = "800px"

            // quitar las flechas que mueven las iniciativas
            const controls = document.querySelector('.container-controls')
            controls.classList.add('d-none'); 

            // Agregar 'd-none' a cards 1 y 2
            const cardOne = document.querySelector('.card-content-initiatives-1')
            cardOne.classList.add('d-none');
            const cardTwo = document.querySelector('.card-content-initiatives-2')
            cardTwo.classList.add('d-none'); 

             
            // Agregar los estilos a la card principal
            // Mover card 0 a la izquierdo (quitar left: 50%;)
            let cardZero = document.querySelector('.card-content-initiatives-0');
            cardZero.classList.add('principal-card'); 

            
        }

        btnCloseInitiative.forEach(close => {
            close.addEventListener('click', (e)=> {
                e.preventDefault();
                
                restaurarEstilos(target, idContainerfound, containerGeneralInitiatives); 
                
            })
        })
    });
}

// Función para restaurar los estilos de las cards
function restaurarEstilos(btnInitiative, idContainerfound, containerGeneralInitiatives) {
    document.querySelector('.title-container-initiatives').classList.remove('d-none');       


    idContainerfound.classList.add('d-none');
    containerGeneralInitiatives.classList.add('d-none'); 
    containerGeneralInitiatives.style.height = "";


    // Quitar la clase 'd-none' del boton 'saber más'
    btnInitiative.parentElement.classList.remove('d-none'); 

    // Quitar la clase d-none a los botones
    btnInitiatives.forEach(btnInitiative => {
        btnInitiative.classList.remove('d-none'); 
    })

    // Agregar la clase 'd-none' del botón 'saber más'
    btnCloseInitiative.forEach(btn => {
        btn.parentElement.classList.add('d-none'); 
    })


    const imgInitiative = document.querySelector('.img-initiative');
    imgInitiative.style.width = ""; 

    // Restaurar la altura del contenido principal
    document.querySelector('.content-initiatives').style.height = "";

    // Mostrar los controles de navegación
    document.querySelector('.container-controls').classList.remove('d-none');

    // Mostrar las tarjetas ocultas 
    document.querySelector('.card-content-initiatives-1').classList.remove('d-none');
    document.querySelector('.card-content-initiatives-2').classList.remove('d-none'); 


    // Restaurar la tarjeta 0 a su estado original
    let cardZero = document.querySelector('.card-content-initiatives-0');
    cardZero.classList.remove('principal-card'); 

    // Quitar la clase 'principal-initiative' del contenedor general
    containerGeneralInitiatives.classList.remove('principal-initiative');
    
    // Quitar la clase 'principal-container' del contenedor con la información
    const principalContent = document.querySelector('.content-initiatives');
    principalContent.classList.remove('principal-container');

}

// // función para adaptarlo a dispositibos móviles            
// function resizeHandler(cardZero) {
//     const iW = window.innerWidth;

//     const screen = {
//         extraSmall: 300,
//         small:  600,
//         medium: 900,
//         large:  1400
//     } 
    
//     // Determinar la medida de la pantalla
//     let size = null; 

//     for(let s in screen) {

//         // adaptarlo a dispositivos móviles
//         if( iW >= screen[s]) { 

//             size = s; 
        
//             if( s == 'large' || s == 'medium' ) {

//                 cardZero.style.left = "-30px"; 
//                 cardZero.style.top = "30%"; 
//                 cardZero.style.width = "200px";
//                 cardZero.style.maxHeight = "270px";

//             } else {

//                 cardZero.style.left = ""; 
//                 cardZero.style.top = "0"; 
//                 cardZero.style.width = "200px";
//                 cardZero.style.height = "250px";  
//             }
//     }

//     }

// }










// Agregar mapa 
let map = L.map('map_TCW').setView([24.35, -101.93], 5); 
// Agregar capa base
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// forzar la vista del mapa
document.addEventListener("DOMContentLoaded", () => {
    const mapContainer = document.getElementById("map_TCW");

    if (!mapContainer) return; // Evita errores si el elemento no existe

    // Observador para detectar cambios en los estilos del contenedor padre
    const observer = new MutationObserver(() => {
        if (getComputedStyle(mapContainer).display !== "none") {
            setTimeout(() => {
                if (typeof map !== "undefined") {
                    map.invalidateSize();
                }
            }, 300);
        }
    });

    // Configurar el observador para detectar cambios en los atributos y en el subtree
    observer.observe(mapContainer.parentElement, { attributes: true, attributeFilter: ["style"] });
});



// Botón general para vista previa
const overviewButton = L.control({position: "topright"});

overviewButton.onAdd = function(map) {
    // Crear un contenedor div 
    const contentButton = L.DomUtil.create("div", "custom-button");
    contentButton.innerHTML = `
        <div class="col">
            <button class="w-100 btn btn-primary rounded-4" id="overviewMap">Vista general</button>
        </div>
    `;

    // Evitar que los eventos del botón interfieran con el mapa
    return contentButton; 
}; 

// Agregar el bóton al mapa
overviewButton.addTo(map); 


// Ciudades reconocidas TCW 
Promise.all([
    fetch(ajax_object.geojson_url).then(response => {
        if (!response.ok) throw new Error('Error al cargar el archivo GEOJSON');
        return response.json(); // Parsear el archivo .geojson
    }),
    fetch(ajax_object.recognized_cities).then(response => {
        if(!response.ok) throw new Error('Error al cargar el archivo JSON');
        return response.json(); // Parsear el archivo .json
    })
])
.then(([statesMaps, recognizedCities]) => {
    function getStatesCoordinates(statesMaps) {
        return statesMaps.features.map(feature => ({
            name: feature.properties["NOM_ENT"],
            coordinates: feature.geometry["coordinates"]
        }));
    }
    
    function getRecognizedCities(recognizedCities) {
        return recognizedCities["recognized_states"] || {};
    }
    
    function createMarkers(recognizedCities, year, map) {
        const allMarkers = [];
    
        if (!recognizedCities["recognized_states"][year]) {
            console.warn(`⚠️ No hay datos para el año ${year}`);
            return [];
        }
    
        const cities = recognizedCities["recognized_states"][year]["cities"];
    
        if (!cities || !Array.isArray(cities)) {
            console.warn(`⚠️ No hay ciudades reconocidas para el año ${year}`);
            return [];
        }
    
        cities.forEach(city => {
            if (!city || !city["alt"] || !city["lang"]) {
                console.warn("⚠️ Coordenadas inválidas para la ciudad:", city);
                return;
            }
    
            const marker = L.marker([city["alt"], city["lang"]]);
            const popupContent = `
                <div class="row">
                    <div class="col p-0">
                        <p class="fw-bold text-primary">${city["ciudad"]}</p>
                        <p class="text-primary">${city["estado"]}</p>
                    </div>
                </div>
            `;
    
            marker.bindPopup(popupContent);
            allMarkers.push({
                marker,
                state: city["estado"],
                name: city["ciudad"],
                alt: city["alt"],
                lat: city["lang"]
            });
        });
    
        return allMarkers;
    }
    
    function filterByYear(recognizedCities, map, statesCoords) {
        const contentFilter = document.querySelector('#year-recognized');
        contentFilter.innerHTML = '';
    
        const yearsCities = Object.keys(recognizedCities.recognized_states);
        const newYear = yearsCities.sort((a, b)=> b - a )
        if (yearsCities.length === 0) return;
    
        newYear.forEach(year => {
            contentFilter.innerHTML += `<option value="${year}">${year}</option>`;
        });
    
        let currentMarkers = [];
    
        function updateMarkersAndFilters(selectedYear) {
            const yearSelected = document.querySelector('#year-selected');
            yearSelected.innerHTML = "Selecciona el año para ver las ciudades que obtuvieron el reconocimiento durante ese periodo"; 

            currentMarkers.forEach(({ marker }) => map.removeLayer(marker));
            currentMarkers = createMarkers(recognizedCities, selectedYear, map);
            currentMarkers.forEach(({ marker }) => marker.addTo(map));
    
            filterByState(recognizedCities, selectedYear, statesCoords, map, currentMarkers);
        }
    
        // Seleccionar el último año disponible en lugar del primero
        const lastYear = yearsCities[0];
        contentFilter.value = lastYear;
        updateMarkersAndFilters(lastYear);
    
        contentFilter.addEventListener("change", function () {
            const selectedYear = this.value;
            updateMarkersAndFilters(selectedYear);
            
        });
    }

    function calculateRecognitionStreaks(recognizedCities) {
        const cityRecognitionStreaks = new Map();
    
        // 🔹 Obtener todos los años disponibles y ordenarlos de menor a mayor
        const availableYears = Object.keys(recognizedCities.recognized_states)
            .map(Number)
            .sort((a, b) => a - b);

    
        // 🔹 Recorrer los años en orden y contar reconocimiento consecutivo por ciudad
        availableYears.forEach((year) => {
            const yearData = recognizedCities.recognized_states[year];
    
            if (yearData && yearData.cities) {
                yearData.cities.forEach(({ ciudad, estado }) => {
                    const cityKey = `${ciudad}-${estado}`;
    
                    if (!cityRecognitionStreaks.has(cityKey)) {
                        cityRecognitionStreaks.set(cityKey, { streak: 1, lastYear: year });
                    } else {
                        const cityData = cityRecognitionStreaks.get(cityKey);
    
                        if (cityData.lastYear === year - 1) {
                            cityData.streak++; // Aumentar si es consecutivo
                        } else {
                            cityData.streak = 1; // Reiniciar si hay una interrupción
                        }
    
                        cityData.lastYear = year;
                    }
                });
            }
        });
    
        return cityRecognitionStreaks;
    }
    
    function filterByState(recognizedCities, year, statesCoords, map, allMarkers) {
        const contentFilter = document.querySelector("#state-recognized");
        contentFilter.innerHTML = '';
        const uniqueStates = new Set();
        const yearData = recognizedCities.recognized_states[year];
    
        if (!yearData || !yearData.cities || !Array.isArray(yearData.cities)) {
            console.warn(`⚠️ No hay datos válidos para el año ${year}:`, yearData);
            return;
        }
    
        yearData.cities.forEach(city => {
            if (city && city.estado) {
                uniqueStates.add(city.estado);
            }
        });
    
        if (uniqueStates.size === 0) {
            contentFilter.innerHTML = `<option value="">No hay estados</option>`;
            return;
        }
    
        [...uniqueStates].sort().forEach(state => {
            const option = document.createElement("option");
            option.value = state;
            option.textContent = state;
            contentFilter.appendChild(option);
        });
    
        // 🔹 Calcular los años consecutivos ANTES de actualizar la lista
        const cityRecognitionStreaks = calculateRecognitionStreaks(recognizedCities);
    
        function updateCityList(selectedState = null) {
            const stateContainer = document.querySelector('#list');
            stateContainer.innerHTML = ''; // Limpiar lista previa
    
            // Eliminar todos los marcadores del mapa antes de agregar nuevos
            allMarkers.forEach(({ marker }) => map.removeLayer(marker));
    
            let hasResults = false;
            const statesMap = new Map();
    
            // 🔹 Agrupar las ciudades por estado
            allMarkers.forEach(({ marker, state, name }) => {
                if (!selectedState || state === selectedState) {
                    hasResults = true;
                    map.addLayer(marker); // Solo agregar marcadores del estado seleccionado
    
                    if (!statesMap.has(state)) {
                        statesMap.set(state, []);
                    }
    
                    const cityKey = `${name}-${state}`;
                    const recognitionStreak = cityRecognitionStreaks.get(cityKey)?.streak || 1;


    
                    statesMap.get(state).push({ name, marker, recognitionStreak });
                }
            });
    
            // 🔹 Ordenar los estados alfabéticamente
            const sortedStates = [...statesMap.keys()].sort();
    
            // 🔹 Construir la lista de estados con sus ciudades
            sortedStates.forEach(state => {
                const stateSection = document.createElement('div');
                stateSection.classList.add('state-section');
    
                // Título del estado
                const stateTitle = document.createElement('h5');
                stateTitle.classList.add('subtitulos-contenido-ac', 'fw-bold', 'mt-3', 'text-center');
                stateTitle.innerText = `${state}`;
                stateSection.appendChild(stateTitle);
    
                // Lista de ciudades
                const stateList = document.createElement('div');
                stateList.classList.add('state-list');
                stateList.style.padding = "10px 30px";
    
                // Ordenar ciudades dentro del estado alfabéticamente
                statesMap.get(state)
                    .sort((a, b) => a.name.localeCompare(b.name))
                    .forEach(({ name, marker, recognitionStreak }) => {
                        // const ordenedTextfirst = recognitionStreak == 1 
                        const ordenedText = recognitionStreak == 1 ? 'Reconocida por <strong> Primera vez </strong>':  `Reconocida por <strong> ${recognitionStreak} </strong>años consecutivos`
                        const listItem = document.createElement('div');
                        listItem.classList.add('card', 'text-start', 'm-2', 'border-0', 'shadow', 'institution');
                        listItem.innerHTML = `
                            <div class="card-body">
                                <div class="row row-cols-md-2 justify-content-start align-items-center">
                                    <div class="col">
                                        <p class="card-title subtitulos-contenido-ac fw-bold">${name}</p>
                                        <p class="text-muted">${ordenedText}</p> 
                                        
                                    </div>

                                    <div class="col d-grid align-items-center justify-content-end">
                                        <button class="btn btn-primary location-btn">Ver ciudad</button>
                                    </div>
                                </div>
                            </div>
                        `;
    
                        stateList.appendChild(listItem);
    
                        // Evento para centrar en el mapa al hacer clic
                        listItem.querySelector('.location-btn').addEventListener('click', () => {
                            map.setView(marker.getLatLng(), 9);
                            marker.openPopup();
                            highlightState(state, statesCoords, map);
    
                            // Asegurar que solo los marcadores del estado seleccionado sean visibles
                            allMarkers.forEach(({ marker, state: markerState }) => {
                                if (markerState !== state) {
                                    map.removeLayer(marker);
                                }
                            });
                        });
                    });
    
                stateSection.appendChild(stateList);
                stateContainer.appendChild(stateSection);
            });
    
            if (!hasResults) {
                stateContainer.innerHTML = `<p class="text-center text-muted">No hay ciudades disponibles.</p>`;
            }
    
            // Si se ha seleccionado un estado, resaltarlo en el mapa
            if (selectedState) {
                highlightState(selectedState, statesCoords, map);
            }
        }
    
        // Evento para actualizar la lista y mapa cuando se seleccione un estado
        contentFilter.addEventListener('change', e => {
            updateCityList(e.target.value);
        });
    
        // Mostrar todas las ciudades del año por defecto
        updateCityList();
    }
    
    function highlightState(stateName, statesCoords, map) {
        const matchedState = statesCoords.find(state => state.name == stateName);
        if (!matchedState) {
            console.warn(`⚠️ No se encontró el estado: ${stateName}`);
            return;
        }
    
        const coordinates = matchedState.coordinates.flat(2);
        const sourceProjection = "+proj=lcc +lat_0=12 +lon_0=-102 +lat_1=17.5 +lat_2=29.5 +x_0=2500000 +y_0=0 +datum=WGS84 +units=m +no_defs +type=crs";
        const targetProjection = "EPSG:4326";
    
        const convertedCoordinates = coordinates.map(coord =>
            proj4(sourceProjection, targetProjection, coord)
        );
    
        const geoJSONData = {
            type: "FeatureCollection",
            features: [{
                type: "Feature",
                geometry: {
                    type: "Polygon",
                    coordinates: [convertedCoordinates]
                },
                properties: {
                    name: stateName,
                    color: "#036935",
                    description: `Estado: ${stateName}`
                }
            }]
        };
    
        if (window.currentPolygonLayer) {
            map.removeLayer(window.currentPolygonLayer);
        }
    
        window.currentPolygonLayer = L.geoJSON(geoJSONData, {
            style: feature => ({ color: feature.properties.color, fillOpacity: 0.5 })
        }).bindPopup(layer => layer.feature.properties.description).addTo(map);
        map.fitBounds(L.geoJSON(geoJSONData).getBounds(), { padding: [20, 20] });
    }

    function overviewMap(overviewID, map, allMarkers, listID) {
        document.getElementById(overviewID).addEventListener('click', () => {

            const listContainer = document.getElementById(listID);
            listContainer.classList.add('institution-container', 'mt-5'); 

            const textAllInstitutions = document.createElement('h5'); 
            textAllInstitutions.classList.add('text-primary');

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

    function initializeMap(statesMaps, recognizedCities, map) {
        const statesCoords = getStatesCoordinates(statesMaps);
        const years = Object.keys(recognizedCities.recognized_states);
        const defaultYear = years.length > 0 ? years[0] : "2024";
        const allMarkers = createMarkers(recognizedCities, defaultYear, map);

        allMarkers.forEach(({ marker }) => marker.addTo(map));


        filterByYear(recognizedCities, map, statesCoords, allMarkers);

        overviewMap( "overviewMap",  map, allMarkers, "list" )
    }
    
    initializeMap(statesMaps, recognizedCities, map);
    
    
    

    
})
.catch(error => console.error('Error cargando el archivo JSON:', error))




// Guardar los elementos en un JSON mapa México SVG 
// document.getElementById('button-states').addEventListener('click', ()=> {
//     const elementos = document.querySelectorAll("#g4 g path"); 

//     let objetoEstados = {
//         "type" : "FeatureCollection",
//         "name" : "MapasEstadosMéxico",
//         "features" : []
//     };
    
//     elementos.forEach(elemento => {
//         let estados = { 
//             "Type": "Feature", 
//             "properties": { 
//                 "ID": elemento.getAttribute("data-id") ,
//                 "NAME": elemento.getAttribute("data-estado"), 
//                 "CAPITAL": "", 
//                 "AREA": elemento.getAttribute("d")
//             } 
//         };

//         objetoEstados.features.push(estados);

//     });

//     // ordenar los estado de forma ascendente
//     objetoEstados.features.sort((a, b) => parseInt(a.properties.ID) - parseInt(b.properties.ID));
//     console.log(objetoEstados);

    
//     // convertir a JSON 
//     const jsonData = JSON.stringify(objetoEstados, null, 2);

//     descargarJSON("estadosMexicoSVG.JSON", jsonData);
     

// }); 

// function descargarJSON(nombreArchivo, contenido){
//     const blob = new Blob([contenido], { type: "application/json "}); 
//     const enlace = document.createElement("a");
//     enlace.href = URL.createObjectURL(blob);
//     enlace.download = nombreArchivo;

//     document.body.appendChild(enlace);
//     enlace.click();
//     document.body.removeChild(enlace); 

// }


