(()=>{
    'use strict'
    const naveNodrizaInfo = document.querySelector('.nave-nodriza-info');
    const navenodrizaAlert = document.querySelector('.nave-nodriza-alert');

    // Lista items 
    // Lista de Modelos de paisajes
    const listaModelos = document.querySelector('#modelosPaisajes');
    listaModelos.style.cursor = "pointer";

    // Lista de Politicas públicas
    const listaPoliticas = document.querySelector('#politicasPublicas');
    listaPoliticas.style.cursor = "pointer";

    // Lista de Comunidades de emprendimiento
    const listaEmprendimiento = document.querySelector('#comunidadesEmprendimiento');
    listaEmprendimiento.style.cursor = "pointer";

    // Lista de Sector privado
    const listaPrivado = document.querySelector('#sectorPrivado');
    listaPrivado.style.cursor = "pointer";

    // Lista de Empoderamiento ciudadano
    const listaCiudadano = document.querySelector('#empoderamientoCiudadano');
    listaCiudadano.style.cursor = "pointer";


    // Span Iniciativas 
    const infoUno = document.createElement('span');
    const infoDos = document.createElement('span');
    const infoTres = document.createElement('span');
    const infoCuatro = document.createElement('span');
    const infoCinco = document.createElement('span');


    // Span Modelos de paisajes 
    infoUno.classList.add('uno');
    infoUno.setAttribute('id', 'modalUno');
    infoUno.innerHTML = `1`;

    naveNodrizaInfo.appendChild(infoUno);

    // Item 1 Modelos de paisajes
    const modalItemUno = document.createElement('div');
    modalItemUno.innerHTML = `
        <div class="info-nave-1 display-none info-nave">
            <div class="d-flex justify-content-between p-1">
                <div class="text-center">
                    <h5 class="text-center"> Modelos Ejemplares de Manejo de Paisajes </h5>
                </div>
            </div>
            <div class="border rounded-2 p-3">
                <p> Son acciones para incrementar el Manejo Forestal Sostenible, así como acciones de restauración y conservación. </p>
            </div>

            <div class="pe-2 my-1 content-close d-flex justify-content-center">
                <span class="nave-close">Cerrar</span>
            </div>
        </div>
    `;
    navenodrizaAlert.appendChild(modalItemUno);

    infoUno.addEventListener('click', () => {
        const infoNaveUno = document.querySelector('.info-nave-1');
        infoNaveUno.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');
    });

    listaModelos.addEventListener('click', ()=>{
        const infoNaveUno = document.querySelector('.info-nave-1');
        infoNaveUno.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');
    })

    // Span 2 Politicas públicas
    infoDos.classList.add('dos');
    infoDos.setAttribute('id', 'modalDos');
    infoDos.innerHTML = `2`;

    // Item 2 Politicas Públicas
    const modalItemDos = document.createElement('div');
    modalItemDos.innerHTML = `
        <div class="info-nave-2 display-none info-nave">
            <div class="d-flex justify-content-between p-1">
                <div class="text-center">
                    <h5 class="text-center"> Políticas Públicas Efectivas </h5>
                </div>
            </div>
            <div class="border rounded-2 p-3">
                <p> Generamos información y alianzas multisectoriales para incidir en el marco legal y normativo a fin de asegurar mejores condiciones en beneficios de los bosques. </p>
            </div>

            <div class="pe-2 my-1 content-close d-flex justify-content-center">
                <span class="nave-close">Cerrar</span>
            </div>
        </div>
    `;
    navenodrizaAlert.appendChild(modalItemDos);

    naveNodrizaInfo.appendChild(infoDos);
    infoDos.addEventListener('click', () => {
        const infoNaveDos = document.querySelector('.info-nave-2');
        infoNaveDos.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');
    });

    listaPoliticas.addEventListener('click', () => {
        const infoNaveDos = document.querySelector('.info-nave-2');
        infoNaveDos.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');
    });


    // Span 3 Comunidades de emprendimiento 
    infoTres.classList.add('tres');
    infoTres.setAttribute('id', 'modalTres');
    infoTres.innerHTML = `3`;

    // Item 3 Comunidades de emprendimiento
    const modalItemTres = document.createElement('div');
    modalItemTres.innerHTML = `
        <div class="info-nave-3 display-none info-nave">
            <div class="d-flex justify-content-between p-1">
                <div class="text-center">
                    <h5 class="text-center"> Comunidades de Emprendimiento </h5>
                </div>
            </div>
            <div class="border rounded-2 p-3">
                <p> Impulsamos bienes y servicios forestales, ambientales y ecosistémicos, a través del desarrollo de capacidades y conexiones para mejorar competitividad de empresas y emprendimientos. </p>
            </div>

            <div class="pe-2 my-1 content-close d-flex justify-content-center">
                <span class="nave-close">Cerrar</span>
            </div>
        </div>
    `;
    navenodrizaAlert.appendChild(modalItemTres);

    naveNodrizaInfo.appendChild(infoTres);
    infoTres.addEventListener('click', () => {
        const infoNaveTres = document.querySelector('.info-nave-3');
        infoNaveTres.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');

    });

    listaEmprendimiento.addEventListener('click', () => {
        const infoNaveTres = document.querySelector('.info-nave-3');
        infoNaveTres.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');

    });


    // Span 4 Sector Privado
    infoCuatro.classList.add('cuatro');
    infoCuatro.setAttribute('id', 'modalCuatro');
    infoCuatro.innerHTML = `4`;

    // Item 4 Sector Privado
    const modalItemCuatro = document.createElement('div');
    modalItemCuatro.innerHTML = `
        <div class="info-nave-4 display-none info-nave">
            <div class="d-flex justify-content-between p-1">
                <div class="text-center">
                    <h5 class="text-center"> Compromisos del Sector Privado </h5>
                </div>
            </div>
            <div class="border rounded-2 p-3">
                <p> Generamos información y alianzas con el sector privado para favorecer el desarrollo de proyectos e inversiones de impacto. </p>
            </div>

            <div class="pe-2 my-1 content-close d-flex justify-content-center">
                <span class="nave-close">Cerrar</span>
            </div>
        </div>
    `;

    navenodrizaAlert.appendChild(modalItemCuatro);

    naveNodrizaInfo.appendChild(infoCuatro);
    
    // clic para mostrar la información
    infoCuatro.addEventListener('click', () => {
        const infoCuatro = document.querySelector('.info-nave-4');
        infoCuatro.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');
    });

    listaPrivado.addEventListener('click', () => {
        const infoCuatro = document.querySelector('.info-nave-4');
        infoCuatro.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');
    });

    // Span 5 Empoderamiento ciudadano
    infoCinco.classList.add('cinco');
    infoCinco.setAttribute('id', 'modalCinco');
    infoCinco.innerHTML = `5`;

    // Item 5 Empoderamiento ciuadadano
    const modalItemCinco = document.createElement('div');
    modalItemCinco.innerHTML = `
        <div class="info-nave-5 display-none info-nave">
            <div class="d-flex justify-content-between p-1">
                <div class="text-center">
                    <h5 class="text-center"> Campañas de Empoderamiento Ciudadano </h5>
                </div>

            </div>
            <div class="border rounded p-3">
                <p> Sensibilizamos y motivamos a las personas a realizar acciones a favor de los árboles y los bosques mediante campañas de información. </p>
            </div>

            <div class="pe-2 my-1 content-close d-flex justify-content-center">
                <span class="nave-close">Cerrar</span>
            </div>
        </div>
    `;

    navenodrizaAlert.appendChild(modalItemCinco);

    naveNodrizaInfo.appendChild(infoCinco);
    infoCinco.addEventListener('click', () => {
        const infoCuatro = document.querySelector('.info-nave-5');
        infoCuatro.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');
    });

    listaCiudadano.addEventListener('click', () => {
        const infoCuatro = document.querySelector('.info-nave-5');
        infoCuatro.classList.remove('display-none');

        naveNodrizaInfo.classList.add('b-n-n');
    });


    // Cursor close 
    const naveClose = document.querySelectorAll('.nave-close');

    for(let i = 0; i < naveClose.length; i++) {
        naveClose[i].style.cursor = "pointer";

        naveClose[i].addEventListener('click', ()=>{
            const infoNaveUno = document.querySelectorAll('.info-nave');
            for(let j=0; j < naveClose.length; j++){
                infoNaveUno[j].classList.add('display-none');
                naveNodrizaInfo.classList.remove('b-n-n');  
            }       
        });
    }



})();

