(()=> {
    'use strict'
    /** Container general bot --------------------------------------------*/
    const botContainer = document.querySelector('.bot');

    /** Container Chat ---------------------------------------------------*/
    // Container Chat
    const containerChat = document.createElement('div');
    containerChat.classList.add('container__chat', 'chat');
    containerChat.setAttribute('id', 'chat');

    /** Container body chat --------------------------------------------*/
    const chatBody = document.createElement('div');
    chatBody.classList.add('chat-body');

    /** Container body chat Info -----------------------------------------*/
    const chatInfo = document.createElement('div');
    chatInfo.classList.add('chat__info');

    /** Container footer input -------------------------------------------*/
    const chatInput = document.createElement('div');
    chatInput.classList.add('chat-input')
    
    /* Render SVG´s ------------------------------------------------------*/
    // Chat Glove
    const renderSvgChat = (chat) => {
        const iconSVG = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        const iconPath = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'path'
        );

        iconSVG.setAttribute('width', '20');
        iconSVG.setAttribute('height', '20');
        iconSVG.setAttribute('fill', 'currentColor');
        iconSVG.setAttribute('class', 'bi bi-chat-left-dots-fill');
        iconSVG.setAttribute('viewBox', '0 0 16 16');

        iconPath.setAttribute('d',
         'M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm5 4a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'
         );

        iconSVG.appendChild(iconPath);
        return chat.appendChild(iconSVG)
    }
    // Chat Header Exit 
    const renderSvgExit = (exit) => {
        const iconSVG = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        const iconPath = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'path'
        );
        const iconPath2 = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'path'
        );

        iconSVG.setAttribute('width', '20');
        iconSVG.setAttribute('height', '20');
        iconSVG.setAttribute('fill', 'currentColor');
        iconSVG.setAttribute('class', 'bi bi-x-circle');
        iconSVG.setAttribute('viewBox', '0 0 16 16');

        iconPath.setAttribute('d',
         'M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'
         );

         iconPath2.setAttribute('d',
         'M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z'
         );

        iconSVG.appendChild(iconPath);
        iconSVG.appendChild(iconPath2);
        return exit.appendChild(iconSVG);  
    }
    // Input Send 
    const renderSvgSend = (send) => {
        const iconSVG = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        const iconPath = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'path'
        );
        const iconPath2 = document.createElementNS(
            'http://www.w3.org/2000/svg',
            'path'
        );

        iconSVG.setAttribute('width', '20');
        iconSVG.setAttribute('height', '20');
        iconSVG.setAttribute('fill', 'currentColor');
        iconSVG.setAttribute('class', 'bi bi-chat-square-dots');
        iconSVG.setAttribute('viewBox', '0 0 16 16');

        iconPath.setAttribute('d',
         'M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z'
         );
        iconPath2.setAttribute('d',
         'M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z'
         );

        iconSVG.appendChild(iconPath);
        iconSVG.appendChild(iconPath2);
        return send.appendChild(iconSVG)
    }
    /** ------------------------------------------------------------------*/

    /* Glove chat --------------------------------------------------------*/
    function renderGloveChat() {
        /* Contendor boton chat */
        // .container__chat .chat__glove
        const chatGlove = document.createElement('div');
        chatGlove.classList.add('container__chat', 'chat__glove');
        // .button_chat_glove
        const bottonChatGlove = document.createElement('div');
        bottonChatGlove.classList.add('button_chat_glove');
        bottonChatGlove.setAttribute('id', 'chatGlove');
        // button chat glove
        const bottonChat = document.createElement('button');
        bottonChat.classList.add('button__chat');

        
        /* AppendChild´s Glove*/
        bottonChatGlove.appendChild(bottonChat);
        chatGlove.appendChild(bottonChatGlove);
        botContainer.appendChild(chatGlove);
        renderSvgChat(bottonChat);

        return;
    }
    /* -------------------------------------------------------------------*/

    /** Header chat ------------------------------------------------------*/
    function chatHeader() {
        const chatHeader = document.createElement('div');
        chatHeader.classList.add('chat-header');

        const logo = document.createElement('div');
        logo.classList.add('logo');
        const logoImg = document.createElement('h6');
        // logoImg.innerHTML = 'Preguntas frecuentes'; 
        logoImg.setAttribute('src', '../img/Logo-Iconos/LOGO REFORESTAMOS FONDO TRANSPARENTE.png');
        logoImg.classList.add('p-1','img-fluid');
        logoImg.setAttribute('alt', 'Logo Reforestamos México');
        logo.appendChild(logoImg);
        
        // const title = document.createElement('div');
        // title.classList.add('title');

        const exit = document.createElement('div');
        exit.classList.add('exit');

        const buttonExit = document.createElement('button');
        buttonExit.classList.add('exit__button');
        buttonExit.setAttribute('id', 'salir');
        renderSvgExit(buttonExit);
        exit.appendChild(buttonExit);

        chatHeader.appendChild(logo);
        // chatHeader.appendChild(title);
        chatHeader.appendChild(exit);

        // appendChild chat header
        containerChat.appendChild(chatHeader);
        
        return;
    } 
    /** ------------------------------------------------------------------*/
    
    
    /** Body chat --------------------------------------------------------*/
    function bodyChat() {
        bodyInfo();
        bodyPrincipalOptions();

        // appendChild bodyContainer
        containerChat.appendChild(chatBody)
        return;
    }
    /** ------------------------------------------------------------------*/

    /** Chat Info --------------------------------------------------------*/
    // Body Info
    function bodyInfo() {
        const contendorInfo = document.createElement('div');
        contendorInfo.classList.add('container');
        const rowInfo = document.createElement('div');
        rowInfo.classList.add('row');

        const listaParrafos = ()=> {
            parrafosTitulos.map(parrafoTitulo => {
                const {parrafo} = parrafoTitulo;
                const colInfo = document.createElement('div', 'content-inicio');
                colInfo.classList.add('col', 'shadow', 'rounded-2');

                const p = document.createElement("p");
                p.classList.add('titulo__info');
                p.textContent = `${parrafo}`;

                colInfo.appendChild(p)
                chatInfo.appendChild(colInfo);
            })
            return;
        }

        listaParrafos(rowInfo);
        contendorInfo.appendChild(chatInfo);
        chatBody.appendChild(contendorInfo);
    }
    /** ------------------------------------------------------------------*/

    /** Chat Principal Option --------------------------------------------*/
    // Body Chat Opciones Principales
    function bodyPrincipalOptions() {
        const opcionesContent = document.createElement('div');
        opcionesContent.classList.add('container', 'lista', 'm-0');

        const rowOpciones = document.createElement('div');
        rowOpciones.classList.add('row', 'justify-content-center');

        opcionesContent.appendChild(rowOpciones);

        /** lista opciones */

        const opcionesPrincipales = (f)=> {
        /** Voluntariado */
        const opcionVoluntariado = document.createElement('div');
        opcionVoluntariado.classList.add('col-12', 'w-100', 'px-0', 'voluntariado');

        const divVoluntariado = document.createElement('div');
        divVoluntariado.classList.add('opcion-content', 'shadow', 'rounded-2');

        const contentVoluntariado = document.createElement('a');
        contentVoluntariado.classList.add(`btn-opcion-1`, 'btn', 'btn-primary', 'w-100' );
        contentVoluntariado.setAttribute('data-bs-toggle', 'collapse');
        contentVoluntariado.setAttribute('href', `#btn-opcion-voluntariado`);
        contentVoluntariado.setAttribute('aria-expanded', 'false');
        contentVoluntariado.setAttribute('aria-controls', 'collapseExample')
        contentVoluntariado.textContent = '1: Voluntariado 🦺';

        const collapseContentVoluntariado = document.createElement('div');
        collapseContentVoluntariado.classList.add('collapse');
        collapseContentVoluntariado.setAttribute('id', 'btn-opcion-voluntariado');
        collapseContentVoluntariado.innerHTML = `
            <div class="card border-0 shadow-sm mt-3">
            
                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#reforestacion-coorporativa" aria-expanded="false" aria-controls="collapseWidthExample">
                        Reforestación corporativa
                    </a>
                </p>
                <div>
                    <div class="collapse" id="reforestacion-coorporativa">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">En Reforestamos México realizamos reforestaciones, así como jornadas de mantenimiento, limpieza y restauración a través de alianzas con empresas. Para participar contáctanos enviando un correo a adriana@reforestamos.org o alejandraespinosa@reforestamos.org </p>
                        </div>
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#Voluntariado" aria-expanded="false" aria-controls="collapseWidthExample">
                        Voluntariado
                    </a>
                </p>
                <div>
                    <div class="collapse" id="Voluntariado">
                        <div class="card border-0 text-primary">
                            <h5 class="p-2">¡Quiero Reforestar!</h5>
                            <p class="text-primary">Próximamente daremos a conocer fechas y montos de recuperación para sumarte a las jornadas de voluntariado. </p>
                        </div>

                        <div class="card border-0 text-primary">
                            <h5 class="p-2">Detectives Ambientales </h5>
                            <p class="text-primary">¡Súmate a la comunidad de Detectives Ambientales en TikTok! Registra tus especies de árboles, aves e insector en Naturalista y comparte tus hallazgos sumándote al hashtag #DetectivesAmbientales.</p>
                        </div>
                    </div>
                </div>

            </div>
        `;

        divVoluntariado.appendChild(contentVoluntariado);
        /** collapse opciones */
        divVoluntariado.appendChild(collapseContentVoluntariado);
        opcionVoluntariado.appendChild(divVoluntariado);


        /* Marketing con causa */
        const opcionMarketing = document.createElement('div');
        opcionMarketing.classList.add('col-12', 'w-100', 'px-0', 'marketing');

        const divMarketing = document.createElement('div');
        divMarketing.classList.add('opcion-content', 'shadow', 'rounded-2');

        const contentMarketing = document.createElement('a');
        contentMarketing.classList.add(`btn-opcion-2`, 'btn', 'btn-primary', 'w-100' );
        contentMarketing.setAttribute('data-bs-toggle', 'collapse');
        contentMarketing.setAttribute('href', `#btn-opcion-marketing`);
        contentMarketing.setAttribute('aria-expanded', 'false');
        contentMarketing.setAttribute('aria-controls', 'collapseExample')
        contentMarketing.textContent = '2: Marketing con causa 📱';

        const collapseContentmarketing = document.createElement('div');
        collapseContentmarketing.classList.add('collapse');
        collapseContentmarketing.setAttribute('id', 'btn-opcion-marketing');
        collapseContentmarketing.innerHTML = `
            <div class="card border-0 shadow-sm mt-3">
                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#campanas" aria-expanded="false" aria-controls="collapseWidthExample">
                    Campañas activas
                    </a>
                </p>
                <div>
                    <div class="collapse" id="campanas">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">Actualmente no contamos con campañas activas. </p>
                        </div>
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#realizar-campana" aria-expanded="false" aria-controls="collapseWidthExample">
                    ¿Cómo realizar una campaña?
                    </a>
                </p>
                <div>
                    <div class="collapse" id="realizar-campana">

                        <div class="card border-0 text-primary">
                            <p class="text-primary">Gracias por tu interés en realizar una campaña para apoyar a nuestros bosques. Con la finalidad de dar seguimiento a tu solicitud, te sugerimos escribir a  vinculacion@reforestamos.org y mariel@reforestamos.org con más información de la propuesta. </p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        divMarketing.appendChild(contentMarketing);
        /** Collapse opciones marketing */
        divMarketing.appendChild(collapseContentmarketing);
        opcionMarketing.appendChild(divMarketing);

        /* Adopta un árbol */
        const opcionAdoptaArbol = document.createElement('div');
        opcionAdoptaArbol.classList.add('col-12', 'w-100', 'px-0', 'adopta-arbol');

        const divAdoptaArbol = document.createElement('div');
        divAdoptaArbol.classList.add('opcion-content', 'shadow', 'rounded-2');

        const contentAdoptaArbol = document.createElement('a');
        contentAdoptaArbol.classList.add(`btn-opcion-3`, 'btn', 'btn-primary','w-100');
        contentAdoptaArbol.setAttribute('data-bs-toggle', 'collapse');
        contentAdoptaArbol.setAttribute('href', `#btn-opcion-adopta-arbol`);
        contentAdoptaArbol.setAttribute('aria-expanded', 'false');
        contentAdoptaArbol.setAttribute('aria-controls', 'collapseExample')
        contentAdoptaArbol.textContent = '3: Adopta un árbol 🌱';

        const collapseContentadopta = document.createElement('div');
        collapseContentadopta.classList.add('collapse');
        collapseContentadopta.setAttribute('id', 'btn-opcion-adopta-arbol');
        collapseContentadopta.innerHTML = `
            <div class="card border-0 shadow-sm mt-3 text-center">
                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#como-adoptar" aria-expanded="false" aria-controls="collapseWidthExample">
                    ¿Cómo adoptar?  
                    </a>
                </p>
                <div>
                    <div class="collapse" id="como-adoptar">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">A través de un donativo tendrás la oportunidad de adoptar un árbol, el cual será plantado en un espacio adecuado para su supervivencia.<br/> Guíate de este 👉 <a href="https://youtu.be/gY8blH37xiE"> tutorial</a>  </p>
                        </div>
                    </div>
                </div>

            </div>
        `;

        divAdoptaArbol.appendChild(contentAdoptaArbol);
        /** collapse opciones adopta arbol */
        divAdoptaArbol.appendChild(collapseContentadopta);
        opcionAdoptaArbol.appendChild(divAdoptaArbol);

        /* Bolsa de trabajo  */
        const opcionBolsaTrabajo = document.createElement('div');
        opcionBolsaTrabajo.classList.add('col-12', 'w-100', 'px-0', 'bolsa-trabajo');

        const divBolsaTrabajo = document.createElement('div');
        divBolsaTrabajo.classList.add('opcion-content', 'shadow', 'rounded-2');

        const contentBolsaTrabajo = document.createElement('a');
        contentBolsaTrabajo.classList.add(`btn-opcion-4`, 'btn', 'btn-primary', 'w-100' );
        contentBolsaTrabajo.setAttribute('data-bs-toggle', 'collapse');
        contentBolsaTrabajo.setAttribute('href', `#btn-opcion-bolsa-trabajo`);
        contentBolsaTrabajo.setAttribute('aria-expanded', 'false');
        contentBolsaTrabajo.setAttribute('aria-controls', 'collapseExample')
        contentBolsaTrabajo.textContent = '4: Bolsa de trabajo 👨‍👧';

        const collapseContentBolsa = document.createElement('div');
        collapseContentBolsa.classList.add('collapse');
        collapseContentBolsa.setAttribute('id', 'btn-opcion-bolsa-trabajo');
        collapseContentBolsa.innerHTML = `
            <div class="card border-0 shadow-sm mt-3">

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#vacantes" aria-expanded="false" aria-controls="collapseWidthExample">
                    Vacantes  
                    </a>
                </p>
                <div>
                    <div class="collapse" id="vacantes">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">No contamos con una bolsa de trabajo permanente, sin embargo, cuando surge una vacante, la publicamos en nuestras redes sociales, te sugerimos seguirlas.<br/>  Envía tu CV a <a href="mailto:damaris@reforestamos.org">damaris@reforestamos.org</a> para tener en consideración tu perfil en futuras oportunidades de contratación. </p>
                        </div>
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#servicio-social" aria-expanded="false" aria-controls="collapseWidthExample">
                    Servicio social  
                    </a>
                </p>
                <div>
                    <div class="collapse" id="servicio-social">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">Gracias por el interés en realizar tu servicio social en Reforestamos México. Te sugerimos escribir a <a href="mailto:damaris@reforestamos.org">damaris@reforestamos.org</a> para obtener más información de este proceso.</p>
                        </div>
                    </div>
                </div>

            </div>
        `;

        divBolsaTrabajo.appendChild(contentBolsaTrabajo);
        /** collapse content bolsa de trabajo */
        divBolsaTrabajo.appendChild(collapseContentBolsa);
        opcionBolsaTrabajo.appendChild(divBolsaTrabajo);

        /* Centinelas del Tiempo */
        const opcionCentielas = document.createElement('div');
        opcionCentielas.classList.add('col-12', 'w-100', 'px-0', 'centinelas');

        const divCentinelas = document.createElement('div');
        divCentinelas.classList.add('opcion-content', 'shadow', 'rounded-2');

        const contentCentinelas = document.createElement('a');
        contentCentinelas.classList.add(`btn-opcion-5`, 'btn', 'btn-primary' ,'w-100');
        contentCentinelas.setAttribute('data-bs-toggle', 'collapse');
        contentCentinelas.setAttribute('href', `#btn-opcion-centinelas`);
        contentCentinelas.setAttribute('aria-expanded', 'false');
        contentCentinelas.setAttribute('aria-controls', 'collapseExample')
        contentCentinelas.textContent = '5: Centinelas del Tiempo 📷';

        const collapseContentCentinelas = document.createElement('div');
        collapseContentCentinelas.classList.add('collapse');
        collapseContentCentinelas.setAttribute('id', 'btn-opcion-centinelas');
        collapseContentCentinelas.innerHTML = `
            <div class="card border-0 shadow-sm mt-3">

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#nueva-convocatoria" aria-expanded="false" aria-controls="collapseWidthExample">
                    Convocatoria 2023
                    </a>
                </p>
                <div>
                    <div class="collapse" id="nueva-convocatoria">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">¡Espérala próximamente! </p>
                        </div>
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#edicion-anterior" aria-expanded="false" aria-controls="collapseWidthExample">
                    Fotografías ganadoras edición 2022  
                    </a>
                </p>
                <div>
                    <div class="collapse" id="edicion-anterior">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">Revive la premiación de fotografías ganadoras 👉 <a href="https://youtu.be/MsAHwANEgrc?t=178">Aquí</a></p>
                        </div>
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#libro-centinelas" aria-expanded="false" aria-controls="collapseWidthExample">
                    Libro Centinelas del Tiempo 
                    </a>
                </p>
                <div>
                    <div class="collapse" id="libro-centinelas">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">Para obtener un ejemplar impreso del libro “Centinelas del Tiempo: Árboles Majestuosos de México” escribe a <a href="mailto:librocentinelas@reforestamos.org">librocentinelas@reforestamos.org</a></p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        divCentinelas.appendChild(contentCentinelas);
        /** collapse content centinelas */
        divCentinelas.appendChild(collapseContentCentinelas);
        opcionCentielas.appendChild(divCentinelas);

        /* Compra y/o venta de árboles */
        const opcioArboles = document.createElement('div');
        opcioArboles.classList.add('col-12', 'w-100', 'px-0', 'venta-arboles');

        const divArboles = document.createElement('div');
        divArboles.classList.add('opcion-content', 'shadow', 'rounded-2');

        const contentArboles = document.createElement('a');
        contentArboles.classList.add(`btn-opcion-6`, 'btn', 'btn-primary', 'w-100' );
        contentArboles.setAttribute('data-bs-toggle', 'collapse');
        contentArboles.setAttribute('href', `#btn-opcion-arboles`);
        contentArboles.setAttribute('aria-expanded', 'false');
        contentArboles.setAttribute('aria-controls', 'collapseExample')
        contentArboles.textContent = '6: Compra y/o venta de árboles 🌳';

        const collapseContentArboles = document.createElement('div');
        collapseContentArboles.classList.add('collapse');
        collapseContentArboles.setAttribute('id', 'btn-opcion-arboles');
        collapseContentArboles.innerHTML = `
            <div class="card border-0 shadow-sm mt-3 text-primary">
                <p class="text-primary"> Dado que somos una organización sin fines de lucro, no contamos con vivero, las actividades que realizamos se llevan a cabo gracias a las donaciones que recibimos. Sin embargo, si requieres una donación de árboles o buscas adquirirlos, puedes acercarte a la Comisión Nacional Forestal (CONAFOR), o a viveros cercanos a tu localidad.</p> 
            </div>
        `;

        divArboles.appendChild(contentArboles);
        /** collapse content arboles */
        divArboles.appendChild(collapseContentArboles);
        opcioArboles.appendChild(divArboles);

        /* Donar */
        const opciondonar = document.createElement('div');
        opciondonar.classList.add('col-12', 'w-100', 'px-0', 'donar');

        const divDonar = document.createElement('div');
        divDonar.classList.add('opcion-content', 'shadow', 'rounded-2');

        const contentDnar = document.createElement('a');
        contentDnar.classList.add(`btn-opcion-7`, 'btn', 'btn-primary', 'w-100' );
        contentDnar.setAttribute('data-bs-toggle', 'collapse');
        contentDnar.setAttribute('href', `#btn-opcion-donar`);
        contentDnar.setAttribute('aria-expanded', 'false');
        contentDnar.setAttribute('aria-controls', 'collapseExample')
        contentDnar.textContent = '7: Donar💰';

        const collapseContentDonar = document.createElement('div');
        collapseContentDonar.classList.add('collapse');
        collapseContentDonar.setAttribute('id', 'btn-opcion-donar');
        collapseContentDonar.innerHTML = `
            <div class="card border-0 shadow-sm mt-3">

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#como-donar" aria-expanded="false" aria-controls="collapseWidthExample">
                    ¿Cómo donar? 
                    </a>
                </p>
                <div>
                    <div class="collapse" id="como-donar">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">Puedes realizar tu donativo vía PayPal o con tu tarjeta de preferencia (VISA, MasterCard, American Express, OXXO).  </p>
                        </div>
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#funcionalidad-donativo" aria-expanded="false" aria-controls="collapseWidthExample">
                    ¿Para qué se utilizará mi donativo? 
                    </a>
                </p>
                <div>
                    <div class="collapse" id="funcionalidad-donativo">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">Con tu apoyo, contribuyes a la realización de las siguientes iniciativas: </p>
                                <ul>
                                    <li>Mejores Alianzas, Mejores Bosques</li>
                                    <li>Joven Emprendedor Forestal</li>
                                    <li>Emprendimientos Productivos En Áreas Naturales Protegidas</li>
                                    <li>Restauración Productiva del Paisaje</li>
                                    <li>Comunidades Forestales con Bosques Certificados FSC®</li>
                                <ul>
                        </div>
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#recibo" aria-expanded="false" aria-controls="collapseWidthExample">
                    ¿Cómo obtener mi recibo deducible de impuestos? 
                    </a>
                </p>
                <div>
                    <div class="collapse" id="recibo">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">Si requieres recibo deducible de impuestos, con gusto te lo hacemos llegar con los datos fiscales que proporciones a <a href="mailto:aurora@reforestamos.org">aurora@reforestamos.org</a> </p>
                        </div>
                    </div>
                </div>
            </div>

        `;

        divDonar.appendChild(contentDnar);
        /** collapse content donar */
        divDonar.appendChild(collapseContentDonar);
        opciondonar.appendChild(divDonar);


        /* Contacto ☎️ */
        const opcionContacto = document.createElement('div');
        opcionContacto.classList.add('col-12', 'w-100', 'px-0', 'contacto');

        const divContacto = document.createElement('div');
        divContacto.classList.add('opcion-content', 'shadow', 'rounded-2');

        const contentContacto = document.createElement('a');
        contentContacto.classList.add(`btn-opcion-8`, 'btn', 'btn-primary', 'w-100' );
        contentContacto.setAttribute('data-bs-toggle', 'collapse');
        contentContacto.setAttribute('href', `#btn-opcion-contacto`);
        contentContacto.setAttribute('aria-expanded', 'false');
        contentContacto.setAttribute('aria-controls', 'collapseExample')
        contentContacto.textContent = '8: Contacto ☎️';

        const collapseContentContacto = document.createElement('div');
        collapseContentContacto.classList.add('collapse');
        collapseContentContacto.setAttribute('id', 'btn-opcion-contacto');
        collapseContentContacto.innerHTML = `

            <div class="card border-0 shadow-sm mt-3">

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#horario" aria-expanded="false" aria-controls="collapseWidthExample">
                    Horario de atención
                    </a>
                </p>
                <div>
                    <div class="collapse" id="horario">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">Estamos disponibles de 9:00 a.m. a 6:00 p.m. (GMT-6).</p>
                        </div>
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#telefono" aria-expanded="false" aria-controls="collapseWidthExample">
                    Número telefónico 
                    </a>
                </p>
                <div>
                    <div class="collapse" id="telefono">
                        <div class="card border-0 text-primary">
                            <p class="text-primary">Contáctanos vía telefónica al <a href="tel:+525555148670">+52 (55) 5514-8670</a>  </p>
                        </div>
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary text-white w-100"  data-bs-toggle="collapse" data-bs-target="#oficinas" aria-expanded="false" aria-controls="collapseWidthExample">
                    Oficinas 
                    </a>
                </p>
                <div>
                    <div class="collapse" id="oficinas">
                        <div class="card border-0 text-primary">
                            <h5 class="text-primary">CDMX<h5>
                            <p class="text-primary">Encuéntranos en Culiacán 115 Piso 2, Col. Hipódromo, C.P. 06100, Ciudad de México, México.</p>

                            <h5 class="text-primary">Guadalajara <h5>
                            <p class="text-primary">Encuéntranos en Colonias 188A, Americana, C.P.44160, Guadalajara, Jalisco, México.</p>
                        </div>
                    </div>
                </div>
            </div>        

        `;

        divContacto.appendChild(contentContacto);
        /** collapse content contacto */
        divContacto.appendChild(collapseContentContacto);
        opcionContacto.appendChild(divContacto);

        /** Eventos y convocatorias 📝 */
        const opcionEventos = document.createElement('div');
        opcionEventos.classList.add('col-12', 'w-100', 'px-0', 'Eventos');

        const divEventos = document.createElement('div');
        divEventos.classList.add('opcion-content', 'shadow', 'rounded-2');

        const contentEventos = document.createElement('a');
        contentEventos.classList.add(`btn-opcion-9`, 'btn', 'btn-primary', 'w-100' );
        contentEventos.setAttribute('data-bs-toggle', 'collapse');
        contentEventos.setAttribute('href', `#btn-opcion-eventos`);
        contentEventos.setAttribute('aria-expanded', 'false');
        contentEventos.setAttribute('aria-controls', 'collapseExample')
        contentEventos.textContent = '9: Eventos y convocatorias 📝';

        const collapseContentEventos = document.createElement('div');
        collapseContentEventos.classList.add('collapse');
        collapseContentEventos.setAttribute('id', 'btn-opcion-eventos');
        collapseContentEventos.innerHTML = `
            <div class="card border-0 shadow-sm mt-3">
                <p class="text-primary h6">Próximos eventos </p>
                <p class="text-primary h6">Convocatorias activas </p>
            </div>
        `;
        /** opciones evenetos  */
        divEventos.appendChild(contentEventos);
        /** Collapse Eventos */
        divEventos.appendChild(collapseContentEventos);
        opcionEventos.appendChild(divEventos);

        rowOpciones.appendChild(opcionVoluntariado);
        rowOpciones.appendChild(opcionMarketing);
        rowOpciones.appendChild(opcionAdoptaArbol);
        rowOpciones.appendChild(opcionBolsaTrabajo);
        rowOpciones.appendChild(opcionCentielas);
        rowOpciones.appendChild(opcioArboles);
        rowOpciones.appendChild(opciondonar);
        rowOpciones.appendChild(opcionContacto);
        rowOpciones.appendChild(opcionEventos);

        return f;
        }
        opcionesPrincipales(chatBody).scrollIntoView();
        chatInfo.appendChild(opcionesContent);
        
    }

    /** Chat Footer ------------------------------------------------------*/
    // Body Chat Opciones Principales
    function chatFooter() {
        const inputDiv = document.createElement('div');
        inputDiv.classList.add('input-sec');

        // input 
        const inputResponse = document.createElement('input');
        inputResponse.setAttribute('type', 'number');      
        inputResponse.setAttribute('id', 'txtInput');
        inputResponse.setAttribute('maxlength', '3');
        inputResponse.setAttribute('placeholder', 'Escribe un número');
        inputDiv.appendChild(inputResponse);
        
        // .send 
        const sendButton = document.createElement('div');
        sendButton.classList.add('send');
        renderSvgSend(sendButton);

        chatInput.appendChild(inputDiv);
        chatInput.appendChild(sendButton);


        containerChat.appendChild(chatInput);
        return; 
    }
    // Footer chat (input)

    function renderChat(){
        chatHeader();
        bodyChat();
        chatFooter();

        // containerChat
        botContainer.appendChild(containerChat);
        return; 
    }

    renderGloveChat();
    renderChat();

    /** animation Glove-Container */
    function animationContainer() {
        const chat = document.querySelector('#chat');
        const salir = document.querySelector('.exit'); 
        const glove = document.querySelector('.chat__glove');
        const options = document.querySelector('.chat__info');
        const displayNone = 'disNo';
        const displayBlock = 'disBlo';
        const none = 'none';
        const block = 'block'

        chat.classList.add(displayNone);
        options.classList.add(displayNone);

    
        
        glove.addEventListener('click', glovechat => {
            const addChat = document.querySelector('#chat');
            glovechat.preventDefault();
            addChat.style.display = block;
            
            if(addChat.style.display = block){
                glove.style.display = none;
            }
    
            setTimeout(()=> {
                options.classList.remove(displayNone);
                options.classList.add(displayBlock);
            },1000)
        })
        
        salir.addEventListener('click', event =>{
            const removeChat = document.querySelector('#chat');
        
            event.preventDefault();
            removeChat.style.display = none;
            
            if (removeChat.style.display = none) {
                glove.style.display = block; 
            } else {
                glove.style.display = none; 
            }
        
        }); 
    }
    animationContainer();

    /** Select Options */
    function selectOptions() {
        const chatBody = document.querySelector('.chat-body');
        const inputValue = document.querySelector('#txtInput');
        const send = document.querySelector('.send');

        //*  Event Listeners */
        // Bloquear el elemento .send si txtInput == ""
        send.addEventListener("click", () => {
            return inputValue.value!=="" 
            ? renderUserMessage() 
            : false;
        });

        
        // Bloquear tecla enter si txt.value == ""
        inputValue.addEventListener("keyup", (event) => {
            return event.keyCode === 13 && inputValue.value!=="" 
            ? renderUserMessage() 
            : false;
        });

        //* Asignar los elementos a imprimir en html */
        const renderMessageEle = (txt, type) => {
            let className = "user-message";
            const messageEle = document.createElement("div");
            const linkMessage = document.createElement('p')
            linkMessage.classList.add('m-0');
            const txtNode = document.createTextNode(txt);

            if (type !== "user") {
            className = "chatbot-message";
            }
            messageEle.classList.add(className, 'shadow', 'p-2');

            linkMessage.append(txtNode);
            messageEle.append(linkMessage)
            chatBody.append(messageEle);
        };

        //* Renderizar el mensage del usuario  */
        const renderUserMessage = () => {
            const userInput = inputValue.value;
            renderMessageEle(userInput, "user");
            inputValue.value = "";

            setTimeout(() => {
            renderChatbotResponse(userInput);
            setScrollPosition();
            }, 1000);
        };
        // *** ------------------------------*/

        //* Renderizar el mensaje del "Bot"  */
        const renderChatbotResponse = (userInput) => {
            const res = userInput;


            if(res == 1 ) {
                renderMessageEle('Voluntariado 🦺');
                listaVoluntariado.map(voluntariado => {
                    const {numero, opcion} = voluntariado;
                    
                    renderMessageEle(`${numero} - ${opcion}`);
                })
                
            }else if(res == 1.1 ) {
                renderMessageEle('En Reforestamos México realizamos reforestaciones, así como jornadas de mantenimiento, limpieza y restauración a través de alianzas con empresas. Para participar contáctanos enviando un correo a adriana@reforestamos.org o alejandraespinosa@reforestamos.org ');

            }else if(res == 1.2 ) {
                renderMessageEle('¡Quiero reforestar!');
                renderMessageEle('Próximamente daremos a conocer fechas y montos de recuperación para sumarte a las jornadas de voluntariado.');
                renderMessageEle('Detectives Ambientales ');
                renderMessageEle('¡Súmate a la comunidad de Detectives Ambientales en TikTok! Registra tus especies de árboles, aves e insector en Naturalista y comparte tus hallazgos sumándote al hashtag #DetectivesAmbientales.')
            }else if(res == 2 ) {
                renderMessageEle('Marketing con causa 📱');

                listaMarketing.map(marketing => {
                    const {numero, opcion} = marketing;
                    renderMessageEle(`${numero} - ${opcion}`);
                    console.log(`${numero} - ${opcion}`)
                })
            }else if(res == 2.1 ) {
                renderMessageEle('Actualmente no contamos con campañas activas. ');

            }else if(res == 2.2 ) {
                renderMessageEle('¿Cómo realizar una campaña? ')
                renderMessageEle('Gracias por tu interés en realizar una campaña para apoyar a nuestros bosques. Con la finalidad de dar seguimiento a tu solicitud, te sugerimos escribir a  vinculacion@reforestamos.org y mariel@reforestamos.org con más información de la propuesta.');

            }else if(res == 3) {
                renderMessageEle('Adopta un árbol 🌱');
                listaAdopta.map(adopta => {
                    const {numero, opcion} = adopta;

                    renderMessageEle(`${numero} - ${opcion}`);
                    console.log(`${numero} - ${opcion}`)
                })
            }else if(res == 3.1 ) {
                renderMessageEle('¿Cómo adoptar?')
                renderMessageEle('A través de un donativo tendrás la oportunidad de adoptar un árbol, el cual será plantado en un espacio adecuado para su supervivencia. Guíate de este tutorial 👉 https://youtu.be/gY8blH37xiE ');

            } else if (res == 4) {
                renderMessageEle('Bolsa de trabajo 👨‍👧');
                listaBolsa.map(bolsa => {
                    const {numero, opcion} = bolsa;
                    renderMessageEle(`${numero} - ${opcion}`);
                    console.log(`${numero} - ${opcion}`)
                })
            }else if(res == 4.1 ) {
                renderMessageEle('Vacantes')
                renderMessageEle('No contamos con una bolsa de trabajo permanente, sin embargo, cuando surge una vacante, la publicamos en nuestras redes sociales, te sugerimos seguirlas.  Envía tu CV a damaris@reforestamos.org para tener en consideración tu perfil en futuras oportunidades de contratación. ');

            }else if(res == 4.2 ) {
                renderMessageEle('Servicio social ')
                renderMessageEle('Gracias por el interés en realizar tu servicio social en Reforestamos México. Te sugerimos escribir a damaris@reforestamos.org para obtener más información de este proceso.');

            } else if (res == 5) {
                renderMessageEle('Centinelas del Tiempo 📷');
                listaCentinelas.map(centinelas => {
                    const {numero, opcion} = centinelas;

                    renderMessageEle(`${numero} - ${opcion}`);
                    console.log(`${numero} - ${opcion}`)
                })

            }else if(res == 5.1 ) {
                renderMessageEle('Convocatoria 2023 ')
                renderMessageEle('¡Espérala próximamente! ');

            }else if(res == 5.2 ) {
                renderMessageEle('Fotografías ganadoras edición 2022')
                renderMessageEle('Revive la premiación de fotografías ganadoras 👉 https://youtu.be/MsAHwANEgrc?t=178');

            }else if(res == 5.3 ) {
                renderMessageEle('Libro Centinelas del Tiempo')
                renderMessageEle('Para obtener un ejemplar impreso del libro “Centinelas del Tiempo: Árboles Majestuosos de México” escribe a librocentinelas@reforestamos.org');

            } else if (res == 6 ) { // ------
                renderMessageEle('Compra y/o venta de árboles 🌳');

            }else if(res == 6.1 ) {
                renderMessageEle('Dado que somos una organización sin fines de lucro, no contamos con vivero, las actividades que realizamos se llevan a cabo gracias a las donaciones que recibimos. Sin embargo, si requieres una donación de árboles o buscas adquirirlos, puedes acercarte a la Comisión Nacional Forestal (CONAFOR), o a viveros cercanos a tu localidad.');

            } else if (res == 7 ) { // ------
                renderMessageEle('Donar💰');

                listaDonar.map(donar => {
                    const {numero, opcion} = donar;

                    renderMessageEle(`${numero} - ${opcion}`);
                    console.log(`${numero} - ${opcion}`)
                })
            }else if(res == 7.1 ) {
                renderMessageEle('¿Cómo donar?');
                renderMessageEle('Puedes realizar tu donativo vía PayPal o con tu tarjeta de preferencia (VISA, MasterCard, American Express, OXXO).');

            }else if(res == 7.2 ) {
                renderMessageEle('¿Para qué se utilizará mi donativo?');
                renderMessageEle(' 	Mejores Alianzas, Mejores Bosques   	Joven Emprendedor Forestal  	Emprendimientos Productivos En Áreas Naturales Protegidas  	Restauración Productiva del Paisaje  	Comunidades Forestales con Bosques Certificados FSC®');

            }else if(res == 7.3 ) {
                renderMessageEle('¿Cómo obtener mi recibo deducible de impuestos?');
                renderMessageEle('Si requieres recibo deducible de impuestos, con gusto te lo hacemos llegar con los datos fiscales que proporciones a aurora@reforestamos.org ');

            }else if (res == 8 ) { // ------
                renderMessageEle('Contacto ☎️');

                listaContacto.map(contacto => {
                    const {numero, opcion} = contacto;

                    renderMessageEle(`${numero} - ${opcion}`);
                    console.log(`${numero} - ${opcion}`)
                })
            } else if(res == 8.1 ) {
                renderMessageEle('Horario de atención ');
                renderMessageEle('Estamos disponibles de 9:00 a.m. a 6:00 p.m. (GMT-6)');

            }else if(res == 8.2 ) {
                renderMessageEle('Número telefónico');
                renderMessageEle('Contáctanos vía telefónica al +52 (55) 5514-8670 ');

            }else if(res == 8.3 ) {
                renderMessageEle('Oficinas');

                renderMessageEle('CDMX')
                renderMessageEle('Encuéntranos en Culiacán 115 Piso 2, Col. Hipódromo, C.P. 06100, Ciudad de México, México.');

                renderMessageEle('Guadalajara');
                renderMessageEle('Encuéntranos en Colonias 188A, Americana, C.P.44160, Guadalajara, Jalisco, México. ');

            } else if (res == 9 ) { // ------
                renderMessageEle('Eventos y convocatorias 📝');

                listaEventos.map(eventos => {
                    const {numero, opcion} = eventos;

                    renderMessageEle(`${numero} - ${opcion}`);
                    console.log(`${numero} - ${opcion}`)
                })
            } else {
                renderMessageEle('Ingresa una opcion valida');
                renderMessageEle('o también nos puedes contactar al correo de contacto contacto@reforestamos.org');
            }
        };

        //* Scroll del Chat  */ 
        const setScrollPosition = () => {
            if (chatBody.scrollHeight > 0) {
            chatBody.scrollTop = chatBody.scrollHeight;
            }
        };

        // *** ------------------------------*/

    }
    selectOptions()
})();


