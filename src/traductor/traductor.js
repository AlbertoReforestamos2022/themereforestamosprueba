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
            
            const ENGLISH_MENU = {
                HOME : 'Home', 
                SOBRE_NOSOTROS : 'About us',
                QUE_HACEMOS : {
                    TITULO : 'What do we do ?', 
                    MODELOS_PAISAJES : {
                        TITULO : 'Landscape management models' 
                    }, 
                    POLITICAS_PUBLICAS : {
                        TITULO : 'Public policy advocacy'
                    },
                    COMUNIDADES_EMPREDIMIENTO : {
                        TITULO : 'Entrepreneurship communities'
                    }, 
                    SECTOR_PTIVADO : {
                        TITULO : 'Private sector commitments'
                    },
                    EMPODERAMIENTO_CIUDADANO : {
                        TITULO : 'Citizen empowerment campaigns'
                    }
                },
                ALIADOS : {
                    TITULO : 'Allies',
                    EMPRESAS : 'Enterprises', 
                    ONGS : 'Civil society organizations',
                    GOBIERNO : 'Government'
                },
                EVENTOS : 'Events',
                DOCUMENTOS : 'Documents',
                BLOG : 'Blog',
                CONTACTO : 'Contact'
            }
            // Header
            // Menú principal
            // Sobre nosotros
            let sobreNosotros = document.querySelector('#menu-item-34 a');
            sobreNosotros.textContent = ENGLISH_MENU.SOBRE_NOSOTROS;
            
            // Que hacemos
            let queHacemos = document.querySelector('#menu-item-363 a');
            queHacemos.textContent = ENGLISH_MENU.QUE_HACEMOS.TITULO; 

            // Iniciativas que hacemos
            // Modelos de paisajes
            let modelosPaisajes = document.querySelector('#menu-item-362 a');
            modelosPaisajes.textContent = ENGLISH_MENU.QUE_HACEMOS.MODELOS_PAISAJES.TITULO;
            // Politicas públicas
            let politicasPublicas = document.querySelector('#menu-item-364 a');
            politicasPublicas.textContent = ENGLISH_MENU.QUE_HACEMOS.POLITICAS_PUBLICAS.TITULO; 
            // Comunidad de emprendimiento
            let comunidadesEmprendimiento = document.querySelector('#menu-item-365 a');
            comunidadesEmprendimiento.textContent = ENGLISH_MENU.QUE_HACEMOS.EMPODERAMIENTO_CIUDADANO.TITULO; 
            // Sector privado
            let sectorPrivado = document.querySelector('#menu-item-366 a');
            sectorPrivado.textContent = ENGLISH_MENU.QUE_HACEMOS.SECTOR_PTIVADO.TITULO;
            // Empoderamiento ciudadano
            let empoderamientoCiudadano = document.querySelector('#menu-item-367 a');
            empoderamientoCiudadano.textContent = ENGLISH_MENU.QUE_HACEMOS.EMPODERAMIENTO_CIUDADANO.TITULO;


            // Aliados
            let aliados = document.querySelector('#menu-item-155 a');
            aliados.textContent = ENGLISH_MENU.ALIADOS.TITULO;
            // Empresas
            let empresas = document.querySelector('#menu-item-156 a');
            empresas.textContent = ENGLISH_MENU.ALIADOS.EMPRESAS;
            // Organizaciones de la sociedad civil
            let ongS = document.querySelector('#menu-item-153 a');
            ongS.textContent = ENGLISH_MENU.ALIADOS.ONGS;
            // Gobierno
            let gobierno = document.querySelector('#menu-item-152 a');
            gobierno.textContent = ENGLISH_MENU.ALIADOS.GOBIERNO;

            // Eventos
            let eventos = document.querySelector('#menu-item-482 a');
            eventos.textContent = ENGLISH_MENU.EVENTOS;
            
            // Documents 
            let documentos = document.querySelector('#menu-item-47 a');
            documentos.textContent = ENGLISH_MENU.DOCUMENTOS;
    
            // Nuestras notas
            let nuestrasNotas = document.querySelector('#menu-item-32 a');
            nuestrasNotas.textContent = ENGLISH_MENU.BLOG;
            
            // Contacto
            let contacto = document.querySelector('#menu-item-33 a');
            contacto.textContent = ENGLISH_MENU.CONTACTO;


            // obj search button on nav
            const ENGLISH_BUTTON_NAV = {
                PLACEHOLDER : 'keyword',
                VALUE : 'Search'
            }

            // Declarar y asignar atributos al buscador
            let inputBuscador = document.querySelector('.search-field' );
            let buscadorValue = document.querySelector('.search-submit');

            inputBuscador.setAttribute("placeholder", ENGLISH_BUTTON_NAV.PLACEHOLDER);
            buscadorValue.setAttribute("value", ENGLISH_BUTTON_NAV.VALUE);

        
            // Barra llamado a la acción
            const ENGLISH_CALL_TO_ACTION = {
                DONAR : 'Donate', 
                ADOPTA_UN_ARBOL : 'Adopt a tree',
                BOLETIN : 'Subscribe to our newsletter',
            }

            // Donar
            let donarText = document.querySelector('.donar-text');
            donarText.textContent = ENGLISH_CALL_TO_ACTION.DONAR;
        
            // Adopta un árbol
            let adoptaText = document.querySelector('.adopta-text');
            adoptaText.textContent = ENGLISH_CALL_TO_ACTION.ADOPTA_UN_ARBOL;
            // Boletín 
            let boletinText = document.querySelector('.boletin-text');
            boletinText.textContent = ENGLISH_CALL_TO_ACTION.BOLETIN;      


            // Footer sitios de interés
            // Lista sitios de interés 
           const ENGLISH_INITIATIVES = {
                TITULO : 'Sites of interest',
                RED_OJA : 'Youth Organizations for the Environment Network',
                LOS_BOSCARES : 'Los Bóscares',
                JOVEN_EMPRENDEDOR_FORESTAL: 'Youth Forestry Entrepreneur', 
                VISION_FORESTAL_CENTINELAS_DEL_TIEMPO: 'Forestry Vision and Sentinels of Time',
                FSC: 'Forest Stewardship Council®',
                AMERE: 'Mexican Alliance for Ecosystems Restoration',
                ITRN: 'Natural Resources Transparency Index', 
                COBIOCOM: 'West-center Biocultural Corridor of Mexico',
                UICN: 'International Union for Conservation of Nature',
                AMEBIN: 'Biodiversity and Business Mexican Alliance'
           }

            const tituloNavSitiosInteres = document.querySelector('.t-s-i-footer');
            tituloNavSitiosInteres.textContent = ENGLISH_INITIATIVES.TITULO;

            const redOJAFooter = document.querySelector('#menu-item-56 a');
            redOJAFooter.textContent = ENGLISH_INITIATIVES.RED_OJA; 

            const losBoscaresFooter = document.querySelector('#menu-item-57 a');
            losBoscaresFooter.textContent = ENGLISH_INITIATIVES.LOS_BOSCARES; 

            const jefFooter = document.querySelector('#menu-item-58 a');
            jefFooter.textContent = ENGLISH_INITIATIVES.JOVEN_EMPRENDEDOR_FORESTAL;

            const centinelasFooter = document.querySelector('#menu-item-59 a');
            centinelasFooter.textContent = ENGLISH_INITIATIVES.VISION_FORESTAL_CENTINELAS_DEL_TIEMPO; 

            const fscFooter = document.querySelector('#menu-item-60 a');
            fscFooter.textContent = ENGLISH_INITIATIVES.FSC;

            const amereFooter = document.querySelector('#menu-item-61 a');
            amereFooter.textContent = ENGLISH_INITIATIVES.AMERE;

            const itrnFooter = document.querySelector('#menu-item-62 a');
            itrnFooter.textContent = ENGLISH_INITIATIVES.ITRN;

            const cobiocomFooter = document.querySelector('#menu-item-63 a');
            cobiocomFooter.textContent = ENGLISH_INITIATIVES.COBIOCOM;

            const uicnFooter = document.querySelector('#menu-item-64 a');
            uicnFooter.textContent = ENGLISH_INITIATIVES.UICN;

            const amebinFooter = document.querySelector('#menu-item-65 a');
            amebinFooter.textContent = ENGLISH_INITIATIVES.AMEBIN;

            // let footerNav = document.querySelectorAll('#menu-menu-sitios-interes li a');
           // obj map-site on footer
            const ENGLISH_MAP = {
                TITULO : 'Map' , 
                INICIO : 'Home' , 
                SOBRE_NOSOTROS : 'About us' , 
                QUE_HACEMOS : 'What do we do?', 
                DOCUMENTOS : 'Documnets', 
                BLOG : 'Blog' , 
                CONTACTO: 'Contact'
            }

            // Footer Mapa 
            let tituloMapa = document.querySelector('.t-m-footer');
            let mapaInicio = document.querySelector('.menu-item-382 a');
            let mapaNosotros = document.querySelector('.menu-item-388 a');
            let mapaHacemos = document.querySelector('.menu-item-384 a');
            let mapaDocumentos = document.querySelector('.menu-item-386 a');
            let mapaBlog = document.querySelector('.menu-item-383 a');
            let mapaContacto = document.querySelector('.menu-item-385 a');
            
            tituloMapa.textContent     = ENGLISH_MAP.TITULO;
            mapaInicio.textContent     = ENGLISH_MAP.INICIO;
            mapaNosotros.textContent   = ENGLISH_MAP.SOBRE_NOSOTROS;
            mapaHacemos.textContent    = ENGLISH_MAP.QUE_HACEMOS;
            mapaDocumentos.textContent = ENGLISH_MAP.DOCUMENTOS;
            mapaBlog.textContent       = ENGLISH_MAP.BLOG;
            mapaContacto.textContent   = ENGLISH_MAP.CONTACTO;

        
            // Footer contáctanos

            // obj contact us on footer
            const ENGLISH_CONTACT_US_FOOTER = {
                TITULO : 'Contact us',
                BOLETIN : 'Subscribe to our newsletter',
                AVISO_PRIVACIDAD : 'Privacy notice'
            }

            let tituloContacto = document.querySelector('.t-c-footer');
            let textContactoBoletin = document.querySelector('#menu-item-465 a');
            
            tituloContacto.textContent = ENGLISH_CONTACT_US_FOOTER.TITULO;
            textContactoBoletin.textContent = ENGLISH_CONTACT_US_FOOTER.BOLETIN;
        
            // Aviso de privacidad
            let avisoPrivacidad = document.querySelector('#menu-item-467 a');     
            avisoPrivacidad.textContent = ENGLISH_CONTACT_US_FOOTER.AVISO_PRIVACIDAD;
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

        // Establecer el valor por defecto en localStorage por determinado tiempo
        function valorPorDefecto() {
            let idioma = localStorage.getItem('idioma');
            if (idioma === 'en-US') {
                localStorage.setItem('idioma', 'es'); // Establecer el idioma por defecto si no está definido
                location.reload();

            }
        }

        // Calcular el tiempo en milisegundos para 12 horas
        let tiempoEnMilisegundos = 60 * 60 * 1000; //  60 minutos * 60 segundos * 1000 milisegundos

        // Establecer el temporizador para establecer el valor por defecto después de 12 horas
        setTimeout(valorPorDefecto, tiempoEnMilisegundos);        
    
        // Llamar a la función para cargar el contenido al cargar la página
        cargarContenido();
    
        // Asignar manejadores de eventos a los botones para cambiar el idioma
        document.getElementById('button-es').addEventListener('click', function() {
            localStorage.setItem('idioma', 'es');
            location.reload();
            cargarContenido(); // Actualizar el contenido al cambiar el idioma
        });
        
        document.getElementById('button-en').addEventListener('click', function() {
            let idioma = localStorage.getItem('idioma');

            if(idioma === 'es' || idioma === '') {
                localStorage.setItem('idioma', 'en-US');
            }

            // localStorage.setItem('idioma', 'en-US');
            location.reload();
            cargarContenido(); // Actualizar el contenido al cambiar el idioma
        });
    
        // window.addEventListener('beforeunload', function(event) {
        //     // Aquí puedes guardar un valor por defecto en localStorage justo antes de que el usuario cierre la ventana
        //     localStorage.setItem('idioma', 'es');
        // });
    
    });

})();



