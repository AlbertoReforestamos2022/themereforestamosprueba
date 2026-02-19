(()=> {
    'use strict'

    document.addEventListener("DOMContentLoaded", function() {    
        // Selector del contenedor para el traductor
        const contentTranslator = document.querySelector('.traductor');
    
        // Crear etiqueta Style y agregar CSS
        const styleTag = document.createElement('style');
        styleTag.innerHTML = `
            /* CSS para la animación de carga */
            .cargando-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.2); /* Fondo semitransparente */
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999; /* Asegura que esté en la parte superior */
            }
    
            .spinner {
                border: 4px solid rgba(0, 0, 0, 0.1);
                border-top: 4px solid #65b492; /* Color del spinner */
                border-radius: 50%;
                width: 40px;
                height: 40px;
                animation: spin 2s linear infinite; /* Animación de giro */
            }
    
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
    
            /* Media query para dispositivos con un ancho de pantalla de hasta 600px */
            @media screen and (max-width: 600px) {
                .traductor {
                    margin-left: 40px;
                }
    
                .con-form-tra {
                    display: block!important;
                }
    
                .bus-gral {
                    margin-left: 80px;
                }
            }
    
            /* Media query para dispositivos con una resolución de pantalla de 1024x768 */
            @media screen and (min-device-width: 1024px) and (min-device-height: 768px) {
                .traductor {
                    width: 50%!important;
                }
    
                .bus-gral {
                    padding-top: 10px; 
                    padding-right: 80px; 
                    width:50%;
                }
            }
    
            .boton-traduccion {
                z-index: 10000;
                height: 60px;
                width: 200px;
                color: #fff!important;
                padding: 5px;
    
            }
    
            .boton-espanol {
                position: fixed;
                top: 35px;
                right: 720px;
                z-index: 10000;
    
    
                height: 60px;
                width: 150px;
                background-color: #94ce58!important;
                color: #fff!important;
                padding: 5px;
                cursor: pointer;
            }
    
            .boton-traducir {
                margin-left: 8px;
                padding: 1px;
                height: 30px;
                font-size: 14px;
            }
    
            #idioma-select {
                margin-top: -8x;
                font-size: 14px;
            }
    
            .form-traducir {
                height: 30px;
            }
        `;
    
        // Agregar la etiqueta Style al head
        document.head.appendChild(styleTag);
    
        // Crear el botón traductor
        const botonTraducir = document.createElement('div');
        botonTraducir.classList.add('boton-traduccion');
        botonTraducir.innerHTML = `
            <div class="container">
                <div class="row row-cols-2 d-flex justify-content-end">
                    <div class="col d-flex justify-content-center p-0">
                        <button class="border-0 bg-transparent mx-2" id="button-es" value="es">
                            <img src="http://www.reforestamosmexico.org/wp-content/uploads/2024/02/flag_12360368.png" class="img-fluid" width="40" height="40" alt=""> 
                            <p class="text-light">Esp</p>
                        </button>
    
                        <button class="border-0 bg-transparent mx-2" id="button-en" value="en-US">
                            <img src="http://www.reforestamosmexico.org/wp-content/uploads/2024/02/united-states_4855884.png" class="img-fluid" width="35" height="35" alt=""> 
                            <p class="text-light">Eng</p>
                        </button>
                    </div>
                </div>
            </div>
        `;
    
        // Agregar el botón traductor al contenedor
        contentTranslator.appendChild(botonTraducir);
    
        // Contenido navs - footer
        function contenidoHeaderFooter() {
            let idiomaSelect = 'en-US';
            
            // Header
            // Menú principal
            // Sobre nosotros
            let sobreNosotros = document.querySelector('#menu-item-34 a');
            sobreNosotros.textContent = 'About us';
            
            // Que hacemos
            let queHacemos = document.querySelector('#menu-item-363 a');
            queHacemos.textContent = 'What do we do ?'; 

            // Iniciativas que hacemos
            // Modelos de paisajes
            let modelosPaisajes = document.querySelector('#menu-item-362 a');
            modelosPaisajes.textContent = 'Landscape management models';
            // Politicas públicas
            let politicasPublicas = document.querySelector('#menu-item-364 a');
            politicasPublicas.textContent = 'Public policy advocacy'; 
            // Comunidad de emprendimiento
            let comunidadesEmprendimiento = document.querySelector('#menu-item-365 a');
            comunidadesEmprendimiento.textContent = 'Entrepreneurship communities'; 
            // Sector privado
            let sectorPrivado = document.querySelector('#menu-item-366 a');
            sectorPrivado.textContent = 'Private sector commitments';
            // Empoderamiento ciudadano
            let empoderamientoCiudadano = document.querySelector('#menu-item-367 a');
            empoderamientoCiudadano.textContent = 'Citizen empowerment campaigns';


            // Aliados
            let aliados = document.querySelector('#menu-item-155 a');
            aliados.textContent = 'Allies';
            // Empresas
            let empresas = document.querySelector('#menu-item-156 a');
            empresas.textContent = 'Enterprises';
            // Organizaciones de la sociedad civil
            let ongS = document.querySelector('#menu-item-153 a');
            ongS.textContent = 'Civil society organizations';
            // Gobierno
            let gobierno = document.querySelector('#menu-item-152 a');
            gobierno.textContent = 'Government'

            // Eventos
            let eventos = document.querySelector('#menu-item-482 a');
            eventos.textContent = 'Events';
            
            // Documents 
            let documentos = document.querySelector('#menu-item-47 a');
            documentos.textContent = 'Documents';
    
            // Nuestras notas
            let nuestrasNotas = document.querySelector('#menu-item-32 a');
            nuestrasNotas.textContent = 'Blog';
            
            // Contacto
            let contacto = document.querySelector('#menu-item-33 a');
            contacto.textContent = 'Contact';


            // Declarar y asignar atributos al buscador
            let inputBuscador = document.querySelector('.search-field' );
            inputBuscador.setAttribute("placeholder", "keyword");
            let buscadorValue = document.querySelector('.search-submit');
            buscadorValue.setAttribute("value", "Search");

        
            // Barra llamado a la acción
            // Donar
            let donarText = document.querySelector('.donar-text');
            donarText.textContent = 'Donate';
        
            // Adopta un árbol
            let adoptaText = document.querySelector('.adopta-text');
            adoptaText.textContent = 'Adopt a tree';
            // Boletín 
            let boletinText = document.querySelector('.boletin-text');
            boletinText.textContent = 'Subscribe to our newsletter';      


            // Footer sitios de interés
            let tituloNavSitiosInteres = document.querySelector('.t-s-i-footer');
            tituloNavSitiosInteres.textContent = 'Sites of interest';
        
            // let footerNav = document.querySelectorAll('#menu-menu-sitios-interes li a');
               
            // Footer Mapa 
            let tituloMapa = document.querySelector('.t-m-footer');
            let mapaInicio = document.querySelector('.menu-item-382 a');
            let mapaNosotros = document.querySelector('.menu-item-388 a');
            let mapaHacemos = document.querySelector('.menu-item-384 a');
            let mapaDocumentos = document.querySelector('.menu-item-386 a');
            let mapaBlog = document.querySelector('.menu-item-383 a');
            let mapaContacto = document.querySelector('.menu-item-385 a');
            
            tituloMapa.textContent     = "Map";
            mapaInicio.textContent     = "Home";
            mapaNosotros.textContent   = "About us";
            mapaHacemos.textContent    = "What do we do?";
            mapaDocumentos.textContent = "Documents";
            mapaBlog.textContent       = "Blog";
            mapaContacto.textContent   = "Contact";

        
            // Footer contáctanos
            let tituloContacto = document.querySelector('.t-c-footer');
            let textContactoBoletin = document.querySelector('#menu-item-465 a');
            
            tituloContacto.textContent = "Contact us";
            textContactoBoletin.textContent = "Subscribe to our newsletter";
        
            // Aviso de privacidad
            let avisoPrivacidad = document.querySelectorAll('#menu-item-467 a');     
            avisoPrivacidad.textContent = "Privacy notice";

            // eventos
            let urlActual = window.location.href;
            let urlEventos = 'https://www.reforestamosmexico.org/eventos/';

            if(urlActual === urlEventos) {
                let mensajeNoEventos = document.querySelector('.tribe-events-c-messages__message-list-item');
                mensajeNoEventos.textContent = 'No results were found for this view. Go to the';

                let linkMensajeEventos = document.querySelector('.tribe-events-c-messages__message-list-item-link tribe-common-anchor-thin-alt');
                linkMensajeEventos.textContent = 'proximos eventos'; 
            }
        }
     

    
        function traduccionEs() {
            // Valor idioma seleccionado
            setTimeout(()=>{
                const cargandoOverlay = document.querySelector('.cargando');
                cargandoOverlay.classList.remove('cargando-overlay');
        
                // Guardar informacion en el localStorage
                cargarContenido();
            }, 4000) 
        
        }
    
        // Función para cargar el contenido según el idioma almacenado en localStorage
        function cargarContenido() {
            let idioma = localStorage.getItem('idioma');
            if (idioma === 'en-US') {
                // Mostrar contenido en inglés
                contenidoHeaderFooter();
            } else if (idioma === 'es') {
                // Mostrar contenido en español
                traduccionEs();
            }
        }
    
        // Llamar a la función para cargar el contenido al cargar la página
        cargarContenido();
    
        // Asignar manejadores de eventos a los botones para cambiar el idioma
        document.getElementById('button-es').addEventListener('click', function() {
            localStorage.setItem('idioma', 'es');
            location.reload();
            cargarContenido(); // Actualizar el contenido al cambiar el idioma
        });
        
        document.getElementById('button-en').addEventListener('click', function() {
            localStorage.setItem('idioma', 'en-US');
            location.reload();
            cargarContenido(); // Actualizar el contenido al cambiar el idioma
        });
    
    
    });

})();



