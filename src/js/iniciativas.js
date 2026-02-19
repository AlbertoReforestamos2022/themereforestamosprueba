(()=> {
    'use strict'
    const URLactual = window.location.href;

    // Click en las iniciativas fuera de la página sobre nosotros 
    class IniciativaExterno {
        constructor(idIniciativa, idUrl , iconTag ) { // id iniciativa, Url (https://www.reforestamosmexico.org/que-hacemos/#), itemLnk, itemTag
            this.idIniciativa = idIniciativa;
            this.idUrl = idUrl;
            // this.itemLink = itemLink;
            this.itemTag = iconTag;
        }

        direccionarPagina() {
            const idIniciativa = document.querySelector(`${this.idIniciativa}`);
            idIniciativa.setAttribute('href', `${this.idUrl}`);
            console.log(URLactual);

            idIniciativa.addEventListener('click', ()=>{
                alert('hiciste click');
            });
        }

        hacerClic() {
            // window.location.href = "http://localhost:8080/wordpress/que-hacemos";
            setTimeout(()=>{
                
                const link = document.querySelector(`${this.iconTag}`);
                console.log(link);
                link.click();
                
            },700 ) // tiempo espera de nuestro evento
        }
    }

    function direccionarCardExterno() {
        const tagMAMB = new IniciativaExterno('.menu-item-468 a', 'http://localhost:8080/wordpress/que-hacemos/#manejo-paisajes', '#Mejores-Altarget' ); // id="Mejores-Altarget"
        const tagCorredor = new IniciativaExterno('.menu-item-469 a', 'http://localhost:8080/wordpress/que-hacemos/#manejo-paisajes', '#Corredor-Btarget');
        // const LinkSIG = new IniciativaExterno('.menu-item-470 a', '#manejo-paisajes', '#SIG')
        const tagCadenas = new IniciativaExterno('.menu-item-471 a', 'http://localhost:8080/wordpress/que-hacemos/#manejo-paisajes', '#Cadenas-detarget');
        const tagIncendios = new IniciativaExterno('.menu-item-547 a', 'http://localhost:8080/wordpress/que-hacemos/#manejo-paisajes', '#Prevenciótarget');
        
        tagMAMB.direccionarPagina();
        tagCorredor.direccionarPagina();
        tagCadenas.direccionarPagina();
        tagIncendios.direccionarPagina();
    }

    // Click en las iniciativas dentro de la página sobre nosotros
    class Iniciativa {
        constructor(itemTarget, itemLink, itemTag) {
            this.itemTarget = itemTarget;
            this.itemLink = itemLink;
            this.itemTag = itemTag;
        }
        mostrarInformacion() {
            const item = document.querySelector(`${this.itemTarget}`); //definimos constante Target 
            item.setAttribute('href', `${this.itemLink}`); // agregamos la etiqueta href
    
            item.addEventListener('click', ()=>{
                setTimeout(()=>{
                    const link = document.querySelector(`${this.itemTag}`);
    
                    link.click();
                    console.log(link);
                },700 ) // tiempo espera de nuestro evento
            })
            return;
        }
    }

    // Direccionar a la sección del card
    function mostrarInformacionMenu() {
        // Manejo de Paisajes
        const linkMAMB = new Iniciativa('.menu-item-468 a', '#manejo-paisajes', '#Mejores-Altarget' ); // id="Mejores-Altarget"
        const linkCorredor = new Iniciativa('.menu-item-469 a', '#manejo-paisajes', '#Corredor-Btarget');
        // const LinkSIG = new Iniciativa('.menu-item-470 a', '#manejo-paisajes', '#SIG')
        const linkCadenas = new Iniciativa('.menu-item-471 a', '#manejo-paisajes', '#Cadenas-detarget');
        const linkIncendios = new Iniciativa('.menu-item-547 a', '#manejo-paisajes', '#Prevenciótarget');

        // Incidencia en politicas públicas
        const linkAMERE = new Iniciativa('.menu-item-550 a', '#incidencia-politica', '#Alianza-Metarget');
        const linkITRN = new Iniciativa('.menu-item-551 a', '#incidencia-politica', '#Índice-detarget');
        const linkTreeCities = new Iniciativa('.menu-item-552 a', '#incidencia-politica', '#Tree-Citietarget');

        // Comunidades de emprendimiento
        const linkJEF = new Iniciativa('.menu-item-553 a', '#comunidades-emprendimiento', '#Joven-emprendedor-forestarget' );
        const linkBosCo = new Iniciativa('.menu-item-554 a', '#comunidades-emprendimiento', '#Bosques-&-Cotarget' );
        const linkEjeNeo = new Iniciativa('.menu-item-555 a', '#comunidades-emprendimiento', '#Talento-emprendedor-rurtarget' );
        const linkBosTec = new Iniciativa('.menu-item-556 a', '#comunidades-emprendimiento', '#Bosques-y-Tecnologíatarget' );
        const linkArbolesNav = new Iniciativa('.menu-item-560 a', '#comunidades-emprendimiento', '#Árboles-de-navidadtarget' );

        // Compromisos del sector privado
        const linkBoscares = new Iniciativa('.menu-item-558 a', '#sector-privado', '#Los-Bóscarestarget' );
        const linkAMEBIN = new Iniciativa('.menu-item-559 a', '#sector-privado', '#AMEBINtarget' );
        const link1Torg = new Iniciativa('.menu-item-560 a', '#sector-privado', '#1T.ORGtarget' );

        // Campañas de empoderamiento Ciudadano
        const linkVFCT = new Iniciativa('.menu-item-561 a', '#sector-privado', '#Visión-Fotarget' );
        const linkDA = new Iniciativa('.menu-item-562 a', '#sector-privado', '#Detectivestarget' );
        const linkDocArbol = new Iniciativa('.menu-item-563 a', '#sector-privado', '#DocÁrboltarget' );
        const linkRedOJA = new Iniciativa('.menu-item-564 a', '#sector-privado', '#Red-OJAtarget' );

        linkMAMB.mostrarInformacion();
        linkCorredor.mostrarInformacion();
        linkCadenas.mostrarInformacion();
        linkIncendios.mostrarInformacion();
        // politicas públicas
        linkAMERE.mostrarInformacion();
        linkITRN.mostrarInformacion();
        linkTreeCities.mostrarInformacion();

        // Comunidades de emprendimiento
        linkJEF.mostrarInformacion();
        linkBosCo.mostrarInformacion();
        linkEjeNeo.mostrarInformacion();
        linkBosTec.mostrarInformacion();
        linkArbolesNav.mostrarInformacion();
        
        // Sector Privado
        linkBoscares.mostrarInformacion();
        linkAMEBIN.mostrarInformacion();
        link1Torg.mostrarInformacion();

        // Empoderamiento Ciudadano
        linkVFCT.mostrarInformacion();
        linkDA.mostrarInformacion();
        linkDocArbol.mostrarInformacion();
        linkRedOJA.mostrarInformacion();
    }

    // Modales fuera de Sobre Nosostros
    function ModaleManejoPaisajes() {
    // MANEJO DE PAISAJES
    // MAMB
    const itemMAMB = document.querySelector('.menu-item-468');
    console.log(itemMAMB);
    itemMAMB.setAttribute('data-bs-toggle', 'modal');
    itemMAMB.setAttribute('data-bs-target', '#MAMB');

    // CORREDOR
    const itemCorredor = document.querySelector('.menu-item-469');
    console.log(itemCorredor);
    itemCorredor.setAttribute('data-bs-toggle', 'modal');
    itemCorredor.setAttribute('data-bs-target', '#Corredor');

    // // SIG
    const itemSIG = document.querySelector('.menu-item-470');
    console.log(itemSIG);
    itemSIG.setAttribute('data-bs-toggle', 'modal');
    itemSIG.setAttribute('data-bs-target', '#SIG');
    
    // // CADENAS
    const itemCadenas = document.querySelector('.menu-item-471');
    console.log(itemCadenas);
    itemCadenas.setAttribute('data-bs-toggle', 'modal');
    itemCadenas.setAttribute('data-bs-target', '#Cadenas');

    // // INCENDIOS
    const itemIncendios = document.querySelector('.menu-item-547');
    console.log(itemIncendios);
    itemIncendios.setAttribute('data-bs-toggle', 'modal');
    itemIncendios.setAttribute('data-bs-target', '#Incendios');



    // Imprimir modales
    const modalBody = document.querySelector('.modal-iniciativas');
    console.log(modalBody);
    // MAMB
    const modalMAMB = document.createElement('div');
    modalMAMB.classList.add('modal','fade');
    modalMAMB.setAttribute('id', 'MAMB');
    modalMAMB.setAttribute('data-bs-backdrop', 'static');
    modalMAMB.setAttribute('data-bs-keyboard', 'false');
    modalMAMB.setAttribute('tabindex', '-1');
    modalMAMB.setAttribute('aria-labelledby', 'MAMBLabel');
    modalMAMB.setAttribute('aria-hidden', 'true');
    modalMAMB.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="MAMBLabel">Mejores Alianzas Mejores Bosques (MAMB) </h3>
            
            </div>

            <div class="modal-body py-5">
            Mejores Alianzas, Mejores Bosques (MAMB) es una plataforma que promueve el desarrollo de sistemas de gobernanza para facilitar la toma de decisiones y acciones multisectoriales que contribuyan a la restauración y conectividad de los bosques, a partir de información geoespacial validada, con el fin de asegurar los servicios ecosistémicos a las grandes ciudades del país.
                <ul class="my-4">
                <li>Más de 2.3 millones de árboles plantado</li>
                <li>Más de 12 mil hectáreas impactadas</li>
                <li>20 estados intervenidos</li>
                <li>30 brigadas para actividades de conservación y restauración</li>
                <li>20 proyectos productivos y emprendimientos comunitarios en 12 ejidos y comunidades</li>
                <li>Más de 122 mil voluntarios en acciones de conservación y restauración</li>
                <li>Reconocimiento de la ONU por su metodología para la contribución a los ODS 2021</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            <a class="btn btn-outline-primary" href="https://www.mejoresalianzasmejoresbosques.org/" target="_blank">Saber más</a>
            </div>

        </div>
    </div>
    `;


    modalBody.appendChild(modalMAMB);
    
    // CORREDOR 
    const modalCORREDOR = document.createElement('div');
    modalCORREDOR.classList.add('modal','fade');
    modalCORREDOR.setAttribute('id', 'Corredor');
    modalCORREDOR.setAttribute('data-bs-backdrop', 'static');
    modalCORREDOR.setAttribute('data-bs-keyboard', 'false');
    modalCORREDOR.setAttribute('tabindex', '-1');
    modalCORREDOR.setAttribute('aria-labelledby', 'CorredorLabel');
    modalCORREDOR.setAttribute('aria-hidden', 'true');
    modalCORREDOR.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="CorredorLabel">Corredor Biocultural Centro-Occidente de México </h3>
            
            </div>

            <div class="modal-body py-5">
                <p>El Corredor Biocultural Centro-Occidente de México es una plataforma territorial en la cual Reforestamos México participa activamente, que busca establecer mecanismos de coordinación y colaboración para manejar y conservar 15 millones de hectáreas, facilitando la conectividad de 82 áreas naturales protegidas (ANP), los ecosistemas prioritarios y biodiversidad en 8 estados del país, bajo la premisa de que son fundamentales para el bienestar de las comunidades que habitan en dicho espacio territorial.</p>
                <ul class="my-4">
                    <li>El plan de acción 2020-2024 del COBIOCOM fue aprobado por los titulares de las Secretarías de Medio Ambiente de los ocho estados que integran el corredor</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                <a class="btn btn-outline-primary" href="http://www.reforestamosmexico.org/wp-content/uploads/2024/06/COBIOCOM.pdf" target="_blank">Saber más</a>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalCORREDOR);

    // SIG 
    const modalSIG = document.createElement('div');
    modalSIG.classList.add('modal','fade');
    modalSIG.setAttribute('id', 'SIG');
    modalSIG.setAttribute('data-bs-backdrop', 'static');
    modalSIG.setAttribute('data-bs-keyboard', 'false');
    modalSIG.setAttribute('tabindex', '-1');
    modalSIG.setAttribute('aria-labelledby', 'SIGLabel');
    modalSIG.setAttribute('aria-hidden', 'true');
    modalSIG.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 class="modal-title text-light text-center" id="SIGLabel">Sistemas de información geoespacial  </h3>
            </div>

            <div class="modal-body py-5">
                <p>Sistemas de información geoespacial (SIG) es una iniciativa para la generación de información estadística y geográfica para facilitar la toma de decisiones y una mejor intervención territorial en los paisajes y corredores de conectividad forestal.</p>
                <ul>
                    <li>12 millones de hectáreas caracterizadas en 8 corredores de conectividad forestal en 16 estados</li>
                    <li>645 polígonos cargados en la plataforma Restor, 1 corredor en la plataforma Terraso</li>
                    <li>513 hectáreas mapeadas con dron</li>
                    <li>Premio Grupo Observadores de la Tierra (GEO 2022)</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalSIG);

    // Cadenas 
    const modalCadenas = document.createElement('div');
    modalCadenas.classList.add('modal','fade');
    modalCadenas.setAttribute('id', 'Cadenas');
    modalCadenas.setAttribute('data-bs-backdrop', 'static');
    modalCadenas.setAttribute('data-bs-keyboard', 'false');
    modalCadenas.setAttribute('tabindex', '-1');
    modalCadenas.setAttribute('aria-labelledby', 'CadenasLabel');
    modalCadenas.setAttribute('aria-hidden', 'true');
    modalCadenas.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 class="modal-title text-light text-center" id="CadenasLabel">Cadenas de Valor FSC® </h3>
            </div>

            <div class="modal-body py-5">
                <p>Con esta iniciativa se busca generar modelos que sirvan de inspiración a otras comunidades a través de fortalecer la gobernanza, las capacidades productivas, mejorar la rentabilidad, competitividad y sustentabilidad de acciones forestales, con el fin de incrementar el bienestar de las comunidades locales y brindar servicios ecosistémicos.</p>
                <ul class="my-4">
                    <li><strong>Villa del Carbón:</strong> más de mil personas beneficiadas, cerca de 8 mil hectáreas bajo manejo sostenible. Servicios ecosistémicos de agua y turismo en la comunidad de San Gerónimo Zacapexco certificados bajo el estándar del Forest Stewardship Council (FSC®)</li>
                    <li><strong>Unión para el Desarrollo Forestal y Agropecuario del Valle de México (UDEFAM):</strong> empresa forestal comunitaria formada por 13 ejidos y comunidades en 6 municipios del Estado de México. Más de 3 mil personas beneficiadas, más de 32 mil hectáreas bajo manejo sostenible.</li>
                    <li><strong>Consorcio de candelilla</strong>: Creación y consolidación de un consorcio multisectorial integrado por 6 organizaciones aliadas (Pronatura Noreste, CONANP, FSC®, UNOFOC, Reserva del Carmen y Reforestamos) con apoyo AMEX como doanante.
                        <ul class="my-4">
                            <li>2 regiones: Maderas del Carmen y Ocampo y Cañón del Hipólito Alto de Norias.</li>
                            <li>20 ejidos con 564 beneficiarios.</li>
                            <li>90,000 hectáreas identificadas para manejo sostenible</li>
                        </ul>
                    </li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    
    `;

    modalBody.appendChild(modalCadenas);

    // Incendios 
    const modalIncendios = document.createElement('div');
    modalIncendios.classList.add('modal','fade');
    modalIncendios.setAttribute('id', 'Incendios');
    modalIncendios.setAttribute('data-bs-backdrop', 'static');
    modalIncendios.setAttribute('data-bs-keyboard', 'false');
    modalIncendios.setAttribute('tabindex', '-1');
    modalIncendios.setAttribute('aria-labelledby', 'IncendiosLabel');
    modalIncendios.setAttribute('aria-hidden', 'true');
    modalIncendios.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="IncendiosLabel">Prevención de incendios forestales  </h3>
            
            </div>

            <div class="modal-body py-5">
                <p>Iniciativa para impulsar la restauración de tierras degradadas, trabajando en alianza con ejidos y comunidades forestales a través de la iniciativa MAMB. Los incendios forestales pueden ocasionar: incremento en la erosión de suelos, reducción de la infiltración de agua, pérdida de hábitat natural para muchas especies de flora y fauna silvestre y liberación no deseada de bióxido de carbono (CO2) a la atmósfera.</p>
                <ul class="my-4">
                <li>Apoyo a ejidos y comunidades y a sus brigadas forestales para la prevención de incendios</li>
                <li>Capacitación técnica, entrega de equipo y herramientas de campo para la prevención y combate de incendios forestales</li>
                <li>Apertura y el mantenimiento de brechas cortafuego&nbsp;</li>
                <li>Recorridos de monitoreo y vigilancia</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            <a class="btn btn-outline-primary" href="http://reforestamosmexico.org/incendios-forestales/" target="_blank">Saber más</a>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalIncendios);
    }

    function ModalIncidenciaPolitica() {
    // POLITICAS PUBLICAS
    // AMERE

    const itemAMERE = document.querySelector('.menu-item-550');
    itemAMERE.setAttribute('data-bs-toggle', 'modal');
    itemAMERE.setAttribute('data-bs-target', '#AMERE');

    // ITRN
    const itemITRN = document.querySelector('.menu-item-550');
    itemITRN.setAttribute('data-bs-toggle', 'modal');
    itemITRN.setAttribute('data-bs-target', '#ITRN');

    // Tree Cities
    const itemTreeCities = document.querySelector('.menu-item-550');
    itemTreeCities.setAttribute('data-bs-toggle', 'modal');
    itemTreeCities.setAttribute('data-bs-target', '#TreeCities');

    const modalBody = document.querySelector('.modal-iniciativas');

    // MODAL AMERE
    const modalAMERE = document.createElement('div');
    modalAMERE.classList.add('modal','fade');
    modalAMERE.setAttribute('id', 'AMERE');
    modalAMERE.setAttribute('data-bs-backdrop', 'static');
    modalAMERE.setAttribute('data-bs-keyboard', 'false');
    modalAMERE.setAttribute('tabindex', '-1');
    modalAMERE.setAttribute('aria-labelledby', 'AMERELabel');
    modalAMERE.setAttribute('aria-hidden', 'true');
    modalAMERE.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h4 class="modal-title text-light text-center bg-transparent" id="AMERELabel">Alianza Mexicana por la Restauración de los Ecosistemas (AMERE) </h4>
            </div>

            <div class="modal-body py-5">
                <p>La Alianza Mexicana por la Restauración de los Ecosistemas (AMERE) en la cual Reforestamos participa activamente, busca articular iniciativas, compartir y generar información a través de la creación de plataformas multisectoriales que favorezcan la gobernanza territorial para poder incidir en políticas públicas y privadas en temas de restauración y así contribuir a detener la deforestación y fomentar la restauración de tierras degradadas.</p>
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                <a class="btn btn-outline-primary" href="https://sites.google.com/view/amere" target="_blank">Saber más</a>
            </div>

        </div>
    </div>
`;
    modalBody.appendChild(modalAMERE);

    // MODAL ITRN
    const modalITRN = document.createElement('div');
    modalITRN.classList.add('modal','fade');
    modalITRN.setAttribute('id', 'ITRN');
    modalITRN.setAttribute('data-bs-backdrop', 'static');
    modalITRN.setAttribute('data-bs-keyboard', 'false');
    modalITRN.setAttribute('tabindex', '-1');
    modalITRN.setAttribute('aria-labelledby', 'ITRNLabel');
    modalITRN.setAttribute('aria-hidden', 'true');
    modalITRN.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h4 class="modal-title text-light text-center bg-transparent" id="ITRNLabel">Índice de Transparencia de los Recursos Naturales </h4>
            
            </div>

            <div class="modal-body py-5">
                <p>El Índice de Transparencia de los Recursos Naturales (ITRN) es un instrumento de medición de la transparencia en la información pública sobre los recursos naturales (pesqueros, forestales e hídricos) que busca mejorar el acceso y calidad de información forestal para tomadores de decisiones multisectoriales.</p>

                <p>El ITRN es implementado por CartoCrítica, Causa Natura, Fondo para la educación y comunicación ambiental y Reforestamos México.</p>
                <ul class="my-4">
                    <li>Incremento del Índice Forestal de 0.35 a 0.44/1</li>
                    <li>Incremento del Índice General: de 0.29 a 0.52/1</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                <a class="btn btn-outline-primary" href="https://www.transparenciarecursosnaturales.org/" target="_blank">Saber más</a>
            </div>

        </div>
    </div>
`;

    modalBody.appendChild(modalITRN);

    // MODAL Tree Cities
    const modalTreeCities = document.createElement('div');
    modalTreeCities.classList.add('modal','fade');
    modalTreeCities.setAttribute('id', 'TreeCities');
    modalTreeCities.setAttribute('data-bs-backdrop', 'static');
    modalTreeCities.setAttribute('data-bs-keyboard', 'false');
    modalTreeCities.setAttribute('tabindex', '-1');
    modalTreeCities.setAttribute('aria-labelledby', 'TreeCitiesLabel');
    modalTreeCities.setAttribute('aria-hidden', 'true');
    modalTreeCities.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title text-light text-center bg-transparent" id="TreeCitiesLabel">Tree Cities of the World </h4> 
            </div>

            <div class="modal-body py-5">
                <p>A través de esta iniciativa, Reforestamos México promueve y acompaña a gobiernos locales, para que más ciudades de México y América Latina tengan acceso al distintivo Tree Cities of the World y así poder generar condiciones habilitadoras que posibiliten la cobertura forestal urbana.</p>

                <p><em>Tree Cities of the World</em> (Ciudades Árbol del Mundo) es un programa creado por Arbor Day Foundation (ADF), y la Organización de las Naciones Unidas para la Alimentación y la Agricultura (FAO), para reconocer a las ciudades de todo el mundo que se han comprometido a crear y crecer las áreas verdes e incrementar y cuidar el arbolado urbano en su comunidad.</p>
                
                <ul class="my-4">
                    <li>169 ciudades de 21 países, de las cuales 44 pertenecen a América Latina.</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
`;

    modalBody.appendChild(modalTreeCities);
    }

    function ModalComunidadesEmprendimiento() {
    // COMUNIDADES DE EMPRENDIMIENTO
    // JEF
    const itemJEF = document.querySelector('.menu-item-553');
    itemJEF.setAttribute('data-bs-toggle', 'modal');
    itemJEF.setAttribute('data-bs-target', '#JEF');

    // BOSQUES & CO 
    const itemBosquesCO = document.querySelector('.menu-item-554');
    itemBosquesCO.setAttribute('data-bs-toggle', 'modal');
    itemBosquesCO.setAttribute('data-bs-target', '#BosquesCo');

    // Eje Neovolcánico
    const itemEjeNeo = document.querySelector('.menu-item-555');
    itemEjeNeo.setAttribute('data-bs-toggle', 'modal');
    itemEjeNeo.setAttribute('data-bs-target', '#EjeNeo');
    
    // Bosque y Tecnología
    const itemBosTec = document.querySelector('.menu-item-556');
    itemBosTec.setAttribute('data-bs-toggle', 'modal');
    itemBosTec.setAttribute('data-bs-target', '#BosTEc');

    // Árboles de Navidad
    const itemArboles = document.querySelector('.menu-item-557');
    itemArboles.setAttribute('data-bs-toggle', 'modal');
    itemArboles.setAttribute('data-bs-target', '#Arboles');



    // Imprimir modales
    const modalBody = document.querySelector('.modal-iniciativas');
    // JEF
    const modalJEF = document.createElement('div');
    modalJEF.classList.add('modal','fade');
    modalJEF.setAttribute('id', 'JEF');
    modalJEF.setAttribute('data-bs-backdrop', 'static');
    modalJEF.setAttribute('data-bs-keyboard', 'false');
    modalJEF.setAttribute('tabindex', '-1');
    modalJEF.setAttribute('aria-labelledby', 'JEFLabel');
    modalJEF.setAttribute('aria-hidden', 'true');
    modalJEF.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="JEFLabel">Joven emprendedor forestal (JEF) </h3>
            </div>

            <div class="modal-body py-5">
                <p>Es una iniciativa que promueve el desarrollo de habilidades y competencias en materia de emprendimiento con jóvenes universitarios que cursen o que hayan terminado una carrera forestal en Latinoamérica, con el objetivo de incubar empresas que aseguren mejores bosques.</p>
                <ul class="my-4">
                    <li>8 ediciones del certamen con 13 países involucrados.</li>
                    <li>Más de 10 mil jóvenes y más de 300 docentes capacitados.</li>
                    <li>80 universidades participantes y más de 600 proyectos presentados por jóvenes.</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                <a class="btn btn-outline-primary" href="https://www.emprendedorforestal.org/" target="_blank">Saber más</a>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalJEF);
    // Bosques y Co
    const modalBosCo = document.createElement('div');
    modalBosCo.classList.add('modal','fade');
    modalBosCo.setAttribute('id', 'BosTec');
    modalBosCo.setAttribute('data-bs-backdrop', 'static');
    modalBosCo.setAttribute('data-bs-keyboard', 'false');
    modalBosCo.setAttribute('tabindex', '-1');
    modalBosCo.setAttribute('aria-labelledby', 'BosCoLabel');
    modalBosCo.setAttribute('aria-hidden', 'true');
    modalBosCo.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 class="modal-title text-light text-center" id="BosCoLabel">Bosques &amp; Co </h3>
            </div>

            <div class="modal-body py-5">
                <p>Es un programa de aceleración de empresas del bosque que busca conectar a una generación renovada de talento en México, Guatemala, Honduras, El Salvador, Costa Rica, Panamá y República Dominicana, para que, a través de su fortalecimiento, generen un impacto positivo en la regeneración de paisajes forestales.</p>
                <ul class="my-4">
                    <li>48 empresas aceleradas (4 con acceso a capital) en los 7 países participantes</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalBosCo);

    // Eje Neovolcánico
    const modalEjeNeo = document.createElement('div');
    modalEjeNeo.classList.add('modal','fade');
    modalEjeNeo.setAttribute('id', 'EjeNeo');
    modalEjeNeo.setAttribute('data-bs-backdrop', 'static');
    modalEjeNeo.setAttribute('data-bs-keyboard', 'false');
    modalEjeNeo.setAttribute('tabindex', '-1');
    modalEjeNeo.setAttribute('aria-labelledby', 'EjeNeoLabel');
    modalEjeNeo.setAttribute('aria-hidden', 'true');
    modalEjeNeo.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 class="modal-title text-light text-center" id="EjeNeoLabel">Talento emprendedor rural del Eje Neovolcánico </h3>
            </div>

            <div class="modal-body py-5">
                <p>Es una iniciativa que busca propiciar la generación de emprendimientos socioambientales de jóvenes universitarios del Estado de México que viven en zonas rurales/semiurbanas del Eje Neovolcánico, a través del desarrollo de habilidades que les permitan emprender con la visión de mejorar la calidad de vida y lograr el bienestar de sus comunidades.</p>
                
                <ul class="my-4">
                    <li>Más de mil jóvenes y 20 docentes capacitados, 16 escuelas con acompañamiento y 18 emprendimientos desarrollados.</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalEjeNeo);

    // Bosques y Tecnología 
    const modalBosquesTec = document.createElement('div');
    modalBosquesTec.classList.add('modal','fade');
    modalBosquesTec.setAttribute('id', 'BosquesTec');
    modalBosquesTec.setAttribute('data-bs-backdrop', 'static');
    modalBosquesTec.setAttribute('data-bs-keyboard', 'false');
    modalBosquesTec.setAttribute('tabindex', '-1');
    modalBosquesTec.setAttribute('aria-labelledby', 'BosquesTecLabel');
    modalBosquesTec.setAttribute('aria-hidden', 'true');
    modalBosquesTec.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="BosquesTecLabel">Bosques y Tecnología </h3>
            
            </div>

            <div class="modal-body py-5">
                <p>Es un programa que fomenta el desarrollo de redes de colaboración entre emprendedores que implementan proyectos del bosque y desarrolladores de tecnología, para que los emprendedores vean la posibilidad de integrarlos a sus proyectos y así reducir la brecha tecnológica.</p>
                
                <ul class="my-4">
                    <li>2 foros con más de 700 personas participantes.</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                <a class="btn btn-outline-primary" href="https://www.empresasdelbosque.org/bosquesytecnologa2022" target="_blank">Saber más</a>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalBosquesTec);

    // Árboles de Navidad Modal
    const modalArboles = document.createElement('div');
    modalArboles.classList.add('modal','fade');
    modalArboles.setAttribute('id', 'Arboles');
    modalArboles.setAttribute('data-bs-backdrop', 'static');
    modalArboles.setAttribute('data-bs-keyboard', 'false');
    modalArboles.setAttribute('tabindex', '-1');
    modalArboles.setAttribute('aria-labelledby', 'ArbolesLabel');
    modalArboles.setAttribute('aria-hidden', 'true');
    modalArboles.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="ArbolesLabel">Árboles de navidad </h3>
            
            </div>

            <div class="modal-body py-5">
                <p>Es una iniciativa que busca fomentar e incentivar el consumo responsable de árboles de navidad con certificación del Forest Stewardship Council (FSC®) a través del fortalecimiento de la cadena de valor, la implementación de buenas prácticas, la vinculación comercial y la implementación de estrategias de marketing.<p>
                <ul class="my-4">
                    <li>127 productores beneficiados, 74 hectáreas certificadas bajo FSC®, primera certificación de árboles de navidad bajo el FSC® en Latinoamérica.</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalArboles);    
    }

    function ModalSectorPrivado() {
    // Boscares
    const itemBoscares = document.querySelector('.menu-item-558');
    itemBoscares.setAttribute('data-bs-toggle', 'modal');
    itemBoscares.setAttribute('data-bs-target', '#Boscares');

    // AMEBIN 
    const itemAMEBIN = document.querySelector('.menu-item-559');
    itemAMEBIN.setAttribute('data-bs-toggle', 'modal');
    itemAMEBIN.setAttribute('data-bs-target', '#AMEBIN');

    const item1TORG = document.querySelector('.menu-item-560');
    item1TORG.setAttribute('data-bs-toggle', 'modal');
    item1TORG.setAttribute('data-bs-target', '#1Torg');

    // Imprimir modales
    const modalBody = document.querySelector('.modal-iniciativas');

    // Boscares
    const modalBoscares = document.createElement('div');
    modalBoscares.classList.add('modal','fade');
    modalBoscares.setAttribute('id', 'Boscares');
    modalBoscares.setAttribute('data-bs-backdrop', 'static');
    modalBoscares.setAttribute('data-bs-keyboard', 'false');
    modalBoscares.setAttribute('tabindex', '-1');
    modalBoscares.setAttribute('aria-labelledby', 'BoscaresLabel');
    modalBoscares.setAttribute('aria-hidden', 'true');
    modalBoscares.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 class="modal-title text-light text-center" id="BoscaresLabel">Los Bóscares </h3>
            </div>

            <div class="modal-body py-5">
                <p>Es una iniciativa de investigación y reconocimiento de las mejores acciones empresariales para el cuidado de los bosques con el propósito de visibilizar la importancia de la agenda forestal y motivar e inspirar la participación del sector privado en favor de la agenda forestal.</p>
                
                <ul class="my-4">
                <li>Cerca de mil quinientas empresas analizadas y 59 reconocidas en 3 países (México, Colombia y Perú) durante los 5 años de implementación de la iniciativa.</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            <a class="btn btn-outline-primary" href="https://losboscares.com/" target="_blank">Saber más</a>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalBoscares);

    // AMEBIN
    const modalAMEBIN = document.createElement('div');
    modalAMEBIN.classList.add('modal','fade');
    modalAMEBIN.setAttribute('id', 'AMEBIN');
    modalAMEBIN.setAttribute('data-bs-backdrop', 'static');
    modalAMEBIN.setAttribute('data-bs-keyboard', 'false');
    modalAMEBIN.setAttribute('tabindex', '-1');
    modalAMEBIN.setAttribute('aria-labelledby', 'AMEBINLabel');
    modalAMEBIN.setAttribute('aria-hidden', 'true');
    modalAMEBIN.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 class="modal-title text-light text-center" id="AMEBINLabel">Alianza Mexicana de Biodiversidad y Negocios (AMEBIN) </h3>
            </div>

            <div class="modal-body py-5">
                <p>Es una plataforma de colaboración de la cual Reforestamos México forma parte activa y que vincula a empresas, organizaciones de la sociedad civil y agencias de cooperación internacional para detonar acciones en favor de la conservación, restauración y uso sostenible de la biodiversidad en México.</p>
                
                <ul class="my-4">
                    <li>5 metodologías aprobadas con 5 empresas para aplicar el Protocolo de Capital Natural</li>
                    <li>Diálogos empresariales</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalAMEBIN);

    // 1T.org
    const modal1TORG = document.createElement('div');
    modal1TORG.classList.add('modal','fade');
    modal1TORG.setAttribute('id', '1Torg');
    modal1TORG.setAttribute('data-bs-backdrop', 'static');
    modal1TORG.setAttribute('data-bs-keyboard', 'false');
    modal1TORG.setAttribute('tabindex', '-1');
    modal1TORG.setAttribute('aria-labelledby', '1TORGLabel');
    modal1TORG.setAttribute('aria-hidden', 'true');
    modal1TORG.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="1TORGLabel">1T.ORG </h3>
            
            </div>

            <div class="modal-body py-5">
                <p>Es una iniciativa del Foro Económico Mundial, anidada en la Alianza Mexicana para la Restauración, liderada por Reforestamos México, para acelerar soluciones basadas en la naturaleza en apoyo al decenio de la ONU para la Restauración de Ecosistemas. Su objetivo es movilizar, conectar y empoderar a actores clave de los sectores público, privado y de la sociedad civil para conservar, restaurar y cuidar un trillón de árboles para el 2030.<p>
                <ul class="my-4">
                    <li>38 empresas con compromisos públicos validados por One Trillion Trees para conservar, restaurar y/o cuidar más de 4.6 billones de árboles en más de 60 países.</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modal1TORG);

    }

    function ModalEmpoderanientoCiudadano() {
    // Centinelas
    const itemCentinelas = document.querySelector('.menu-item-561');
    itemCentinelas.setAttribute('data-bs-toggle', 'modal');
    itemCentinelas.setAttribute('data-bs-target', '#Centinelas');

    // Detectives Ambientales
    const itemDetectivesAmbientales = document.querySelector('.menu-item-562');
    itemDetectivesAmbientales.setAttribute('data-bs-toggle', 'modal');
    itemDetectivesAmbientales.setAttribute('data-bs-target', '#DA');

    // DocÁrbol
    const itemDocArbol = document.querySelector('.menu-item-563');
    itemDocArbol.setAttribute('data-bs-toggle', 'modal');
    itemDocArbol.setAttribute('data-bs-target', '#DocArbol');

    // Red Oja
    const itemRedOja = document.querySelector('.menu-item-564');
    itemRedOja.setAttribute('data-bs-toggle', 'modal');
    itemRedOja.setAttribute('data-bs-target', '#RedOja');


    // Imprimir modales
    const modalBody = document.querySelector('.modal-iniciativas');
    // Centinelas Modal
    const modalCentinelas = document.createElement('div');
    modalCentinelas.classList.add('modal','fade');
    modalCentinelas.setAttribute('id', 'Centinelas');
    modalCentinelas.setAttribute('data-bs-backdrop', 'static');
    modalCentinelas.setAttribute('data-bs-keyboard', 'false');
    modalCentinelas.setAttribute('tabindex', '-1');
    modalCentinelas.setAttribute('aria-labelledby', 'CentinelasLabel');
    modalCentinelas.setAttribute('aria-hidden', 'true');
    modalCentinelas.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="CentinelasLabel">Visión Forestal y Centinelas del Tiempo (VFCT) </h3>
            
            </div>

            <div class="modal-body py-5">
                Concurso nacional de fotografía para incentivar <span data-contrast="auto">, promover y difundir el aprecio a los ecosistemas forestales y a los árboles a través de la fotografía, así como dar a conocer de manera visual, las acciones y prácticas que impulsen el desarrollo forestal sustentable, la conservación de los ecosistemas y su biodiversidad. Desde 2016 se realiza en conjunto con la Comisión Nacional Forestal.</span>
                <ul class="my-4">
                    <li>Siete ediciones del concurso, más de 39 mil fotografías recibidas y más de 13 mil participantes.</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            <a class="btn btn-outline-primary" href="https://centinelasdeltiempo.org/" target="_blank">Saber más</a>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalCentinelas);

    // Detectives Ambientales Modal
    const modalDetectivesAmbientales = document.createElement('div');
    modalDetectivesAmbientales.classList.add('modal','fade');
    modalDetectivesAmbientales.setAttribute('id', 'DA');
    modalDetectivesAmbientales.setAttribute('data-bs-backdrop', 'static');
    modalDetectivesAmbientales.setAttribute('data-bs-keyboard', 'false');
    modalDetectivesAmbientales.setAttribute('tabindex', '-1');
    modalDetectivesAmbientales.setAttribute('aria-labelledby', 'DALabel');
    modalDetectivesAmbientales.setAttribute('aria-hidden', 'true');
    modalDetectivesAmbientales.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="DALabel">Detectives Ambientales </h3>
            
            </div>

            <div class="modal-body py-5">
                <p>Es una iniciativa para empoderar a la sociedad a través de las formas en las que pude participar para obtener conocimiento del medio ambiente en su entorno y el potencial que tiene de involucrarse en la generación de información y toma de decisiones.</p>
                <ul class="my-4">
                    <li>Una brigada universitaria y más de 200 personas capacitadas en temas de biodiversidad</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalDetectivesAmbientales);

    // DocÁrbol Modal
    const modalDocArbol = document.createElement('div');
    modalDocArbol.classList.add('modal','fade');
    modalDocArbol.setAttribute('id', 'DocArbol');
    modalDocArbol.setAttribute('data-bs-backdrop', 'static');
    modalDocArbol.setAttribute('data-bs-keyboard', 'false');
    modalDocArbol.setAttribute('tabindex', '-1');
    modalDocArbol.setAttribute('aria-labelledby', 'DocArbolLabel');
    modalDocArbol.setAttribute('aria-hidden', 'true');
    modalDocArbol.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
            <h3 class="modal-title text-light text-center" id="DocArbolLabel">DocÁrbol </h3>
            
            </div>

            <div class="modal-body py-5">
                <p>Es una iniciativa que promueve el desarrollo de habilidades a través de capacitaciones virtuales dirigidas a jóvenes en México y América Latina con el objetivo de dotarlos de habilidades técnicas en temas de manejo de arbolado y áreas verdes urbanas que les permitan incrementar sus posibilidades de empleo y emprendimiento.</p>
                <ul class="my-4">
                    <li>Más de 300 jóvenes capacitados de 10 países</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalDocArbol);

    // Red Oja Modal
    const modalRedOja = document.createElement('div');
    modalRedOja.classList.add('modal','fade');
    modalRedOja.setAttribute('id', 'RedOja');
    modalRedOja.setAttribute('data-bs-backdrop', 'static');
    modalRedOja.setAttribute('data-bs-keyboard', 'false');
    modalRedOja.setAttribute('tabindex', '-1');
    modalRedOja.setAttribute('aria-labelledby', 'RedOjaLabel');
    modalRedOja.setAttribute('aria-hidden', 'true');
    modalRedOja.innerHTML= `
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 class="modal-title text-light text-center" id="RedOjaLabel">Red OJA </h3>
            </div>

            <div class="modal-body py-5">
                <p>Es una plataforma que fomenta el fortalecimiento organizacional y técnico forestal de las organizaciones ambientales lideradas por jóvenes de México y América Latina, con la finalidad de realizar acciones en pro de la conectividad ambiental a nivel rural y urbano en las áreas donde las organizaciones trabajan.</p>
                <ul class="my-4">
                    <li>Más de 200 jóvenes de 55 organizaciones en 5 países</li>
                </ul>                          
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal" aria-label="Close">Salir </button>
            </div>

        </div>
    </div>
    `;

    modalBody.appendChild(modalRedOja);

    }

    const queHacemos = 'http://localhost:8080/wordpress/que-hacemos/'
    const manejoPaisajes = 'http://localhost:8080/wordpress/que-hacemos/#manejo-paisajes';
    const incidenciaPolitica = 'http://localhost:8080/wordpress/que-hacemos/#incidencia-politica/';
    const comunidadesEmprendimiento = 'http://localhost:8080/wordpress/que-hacemos/#comunidades-emprendimiento';
    const sectorPrivado = 'http://localhost:8080/wordpress/que-hacemos/#sector-privado';
    const empoderamientoCiudadano = 'http://localhost:8080/wordpress/que-hacemos/#empoderamiento-ciudadano';

    if (URLactual == queHacemos ||  URLactual == manejoPaisajes || URLactual == incidenciaPolitica ||  URLactual == comunidadesEmprendimiento || URLactual == sectorPrivado || URLactual == empoderamientoCiudadano ){
        mostrarInformacionMenu();        
    }else if(URLactual != queHacemos ||  URLactual != manejoPaisajes || URLactual != incidenciaPolitica ||  URLactual != comunidadesEmprendimiento || URLactual != sectorPrivado || URLactual != empoderamientoCiudadano) {
        direccionarCardExterno();
        hacerClic();
        // ModaleManejoPaisajes();
        // ModalIncidenciaPolitica();
        // ModalComunidadesEmprendimiento();
        // ModalSectorPrivado();
        // ModalEmpoderanientoCiudadano();
    }
    
})();



 