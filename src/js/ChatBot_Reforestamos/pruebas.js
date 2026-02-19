
const opcionVoluntariado = document.createElement('div');
opcionVoluntariado.classList.add('col-12', 'w-100', 'px-0', 'voluntariado');

const divVoluntariado = document.createElement('div');
divVoluntariado.classList.add('opcion-content', 'shadow', 'rounded-2');

const contentVoluntariado = document.createElement('a');
contentVoluntariado.classList.add(`btn-opcion-1`, 'btn', 'btn-primary' );
contentVoluntariado.setAttribute('data-bs-toggle', 'collapse');
contentVoluntariado.setAttribute('href', `#btn-opcion-voluntariado`);
contentVoluntariado.setAttribute('aria-expanded', 'false');
contentVoluntariado.setAttribute('aria-controls', 'collapseExample')
contentVoluntariado.textContent = '1: Voluntariado 🦺';

divVoluntariado.appendChild(contentVoluntariado);
opcionVoluntariado.appendChild(divVoluntariado);


/* Marketing con causa */
const opcionMarketing = document.createElement('div');
opcionMarketing.classList.add('col-12', 'w-100', 'px-0', 'marketing');

const divMarketing = document.createElement('div');
divMarketing.classList.add('opcion-content', 'shadow', 'rounded-2');

const contentMarketing = document.createElement('a');
contentMarketing.classList.add(`btn-opcion-1`, 'btn', 'btn-primary' );
contentMarketing.setAttribute('data-bs-toggle', 'collapse');
contentMarketing.setAttribute('href', `#btn-opcion-marketing`);
contentMarketing.setAttribute('aria-expanded', 'false');
contentMarketing.setAttribute('aria-controls', 'collapseExample')
contentMarketing.textContent = '2: Marketing con causa 📱';

divMarketing.appendChild(contentMarketing);
opcionMarketing.appendChild(divMarketing);

/* Adopta un árbol */
const opcionAdoptaArbol = document.createElement('div');
opcionAdoptaArbol.classList.add('col-12', 'w-100', 'px-0', 'marketing');

const divAdoptaArbol = document.createElement('div');
divAdoptaArbol.classList.add('opcion-content', 'shadow', 'rounded-2');

const contentAdoptaArbol = document.createElement('a');
contentAdoptaArbol.classList.add(`btn-opcion-1`, 'btn', 'btn-primary' );
contentAdoptaArbol.setAttribute('data-bs-toggle', 'collapse');
contentAdoptaArbol.setAttribute('href', `#btn-opcion-adopta-arbol`);
contentAdoptaArbol.setAttribute('aria-expanded', 'false');
contentAdoptaArbol.setAttribute('aria-controls', 'collapseExample')
contentAdoptaArbol.textContent = '3: Adopta un árbol 🌱';

divAdoptaArbol.appendChild(contentAdoptaArbol);
opcionAdoptaArbol.appendChild(divAdoptaArbol);

/* Bolsa de trabajo  */
const opcionBolsaTrabajo = document.createElement('div');
opcionBolsaTrabajo.classList.add('col-12', 'w-100', 'px-0', 'marketing');

const divBolsaTrabajo = document.createElement('div');
divBolsaTrabajo.classList.add('opcion-content', 'shadow', 'rounded-2');

const contentBolsaTrabajo = document.createElement('a');
contentBolsaTrabajo.classList.add(`btn-opcion-1`, 'btn', 'btn-primary' );
contentBolsaTrabajo.setAttribute('data-bs-toggle', 'collapse');
contentBolsaTrabajo.setAttribute('href', `#btn-opcion-bolsa-trabajo`);
contentBolsaTrabajo.setAttribute('aria-expanded', 'false');
contentBolsaTrabajo.setAttribute('aria-controls', 'collapseExample')
contentBolsaTrabajo.textContent = '4: Bolsa de trabajo 👨‍👧';

divBolsaTrabajo.appendChild(contentBolsaTrabajo);
opcionBolsaTrabajo.appendChild(divBolsaTrabajo);

/* Centinelas del Tiempo */
const opcionCentielas = document.createElement('div');
opcionCentielas.classList.add('col-12', 'w-100', 'px-0', 'marketing');

const divCentinelas = document.createElement('div');
divCentinelas.classList.add('opcion-content', 'shadow', 'rounded-2');

const contentCentinelas = document.createElement('a');
contentCentinelas.classList.add(`btn-opcion-1`, 'btn', 'btn-primary' );
contentCentinelas.setAttribute('data-bs-toggle', 'collapse');
contentCentinelas.setAttribute('href', `#btn-opcion-centinelas`);
contentCentinelas.setAttribute('aria-expanded', 'false');
contentCentinelas.setAttribute('aria-controls', 'collapseExample')
contentCentinelas.textContent = '5: Centinelas del Tiempo 📷';

divCentinelas.appendChild(contentCentinelas);
opcionCentielas.appendChild(divCentinelas);

/* Compra y/o venta de árboles */
const opcioArboles = document.createElement('div');
opcioArboles.classList.add('col-12', 'w-100', 'px-0', 'marketing');

const divArboles = document.createElement('div');
divArboles.classList.add('opcion-content', 'shadow', 'rounded-2');

const contentArboles = document.createElement('a');
contentArboles.classList.add(`btn-opcion-1`, 'btn', 'btn-primary' );
contentArboles.setAttribute('data-bs-toggle', 'collapse');
contentArboles.setAttribute('href', `#btn-opcion-arboles`);
contentArboles.setAttribute('aria-expanded', 'false');
contentArboles.setAttribute('aria-controls', 'collapseExample')
contentArboles.textContent = '6: Compra y/o venta de árboles 🌳';

divArboles.appendChild(contentArboles);
opcioArboles.appendChild(divArboles);

/* Donar */
const opciondonar = document.createElement('div');
opciondonar.classList.add('col-12', 'w-100', 'px-0', 'marketing');

const divDonar = document.createElement('div');
divDonar.classList.add('opcion-content', 'shadow', 'rounded-2');

const contentDnar = document.createElement('a');
contentDnar.classList.add(`btn-opcion-1`, 'btn', 'btn-primary' );
contentDnar.setAttribute('data-bs-toggle', 'collapse');
contentDnar.setAttribute('href', `#btn-opcion-donar`);
contentDnar.setAttribute('aria-expanded', 'false');
contentDnar.setAttribute('aria-controls', 'collapseExample')
contentDnar.textContent = '7: Donar💰';

divDonar.appendChild(contentDnar);
opciondonar.appendChild(divDonar);


/* Contacto ☎️ */
const opcionContacto = document.createElement('div');
opcionContacto.classList.add('col-12', 'w-100', 'px-0', 'marketing');

const divContacto = document.createElement('div');
divContacto.classList.add('opcion-content', 'shadow', 'rounded-2');

const contentContacto = document.createElement('a');
contentContacto.classList.add(`btn-opcion-1`, 'btn', 'btn-primary' );
contentContacto.setAttribute('data-bs-toggle', 'collapse');
contentContacto.setAttribute('href', `#btn-opcion-contacto`);
contentContacto.setAttribute('aria-expanded', 'false');
contentContacto.setAttribute('aria-controls', 'collapseExample')
contentContacto.textContent = '8: Contacto ☎️';

divContacto.appendChild(contentContacto);
opcionContacto.appendChild(divContacto);

/** Eventos y convocatorias 📝 */
const opcionEventos = document.createElement('div');
opcionEventos.classList.add('col-12', 'w-100', 'px-0', 'marketing');

const divEventos = document.createElement('div');
divEventos.classList.add('opcion-content', 'shadow', 'rounded-2');

const contentEventos = document.createElement('a');
contentEventos.classList.add(`btn-opcion-1`, 'btn', 'btn-primary' );
contentEventos.setAttribute('data-bs-toggle', 'collapse');
contentEventos.setAttribute('href', `#btn-opcion-eventos`);
contentEventos.setAttribute('aria-expanded', 'false');
contentEventos.setAttribute('aria-controls', 'collapseExample')
contentEventos.textContent = '9: Eventos y convocatorias 📝';

const collapseContentEventos = document.createElement('div');
collapseContentEventos.classList.add('collapse');
collapseContentEventos.setAttribute('id', 'btn-opcion-eventos');
collapseContentEventos.textContent = `
    <div class="card card-body">
        <p>Próximos eventos </p>
        <p>Convocatorias activas </p>
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



/** Response con .map */
const opcionesPrincipales = (f) =>{
    listaOpciones.map(listaOpcion => {
        const {numero, opcion} = listaOpcion;
        
        const colOpciones = document.createElement('div');
        colOpciones.classList.add('col-12', 'w-100', 'px-0');

        const opcionDiv = document.createElement('div');
        opcionDiv.classList.add('opcion-content', 'shadow', 'rounded-2');

        const btnOpcion = document.createElement('a');
        btnOpcion.classList.add(`btn-opcion-${numero}`, 'btn', 'btn-primary' );
        btnOpcion.setAttribute('data-bs-toggle', 'collapse');
        btnOpcion.setAttribute('href', `#btn-opcion-${numero}`);
        btnOpcion.setAttribute('aria-expanded', 'false');
        btnOpcion.setAttribute('aria-controls', 'collapseExample')
        btnOpcion.textContent = `
            ${numero}: ${opcion}
        `;
        if(numero == 1) {
            listaVoluntariado.map(voluntariadoLista => {
                const {numero, opcion} = voluntariadoLista;

                const divCollapse = document.createElement('div');
                divCollapse.classList.add('collpse');
                divCollapse.setAttribute('id', `btn-opcion-${numero}`);
                
                const contentCollapse = document.createElement('div');
                contentCollapse.classList.add('card', 'card-body');
                contentCollapse.textContent = `
                    ${numero}: ${opcion}
                `
            })
        }

        opcionDiv.appendChild(btnOpcion);
        colOpciones.appendChild(opcionDiv);
        rowOpciones.appendChild(colOpciones);
        return;
    })
    return f;
}


