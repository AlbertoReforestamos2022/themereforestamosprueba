document.addEventListener('DOMContentLoaded', ()=> {
    // Botones traducción 
    const btnES = document.querySelector('#button-es');
    const btnEN = document.querySelector('#button-en');

    // LocalStorage 
    const idiomaSeleccionado = localStorage.getItem("idioma");

    // Contenedor general nota español
    const contenedorNotaBlog = document.querySelector('.contenedor-nota-es');

    // Nota en Español
    const tituloNotaEs = document.querySelector('.titulo-nota-es'); 
    const containerNotaES = document.querySelector('.contenido-nota-es');

    // Contenedor general nota en Inglés
    const containerNotaEN = document.querySelector('.contenedor-nota-en');
    containerNotaEN.classList.add('d-none');

    // Contenido de la nota en Inglés
    const tituloNotaIngles = document.querySelector('.titulo-nota-en');
    const contenidoNotaIngles = document.querySelector('.contenido-nota-en');

    // id nota (get_post_ID())
    const idNota = 'post-'+ contenedorNotaBlog.getAttribute('data-id'); 

    // función traducción meses
    function traducirMeses(fecha) {
        // Mapa de nombres de meses en español a inglés
        const mesesES = ['enero,', 'febrero,', 'marzo,', 'abril,', 'mayo,', 'junio,', 'julio,', 'agosto,', 'septiembre,', 'octubre,', 'noviembre,', 'diciembre,'];
        const mesesEN = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // Separar la cadena de fecha en día, mes y año
        const partesFecha = fecha.split(' ');
        const dia = partesFecha[0];
        const mes = partesFecha[1];
        const anio = partesFecha[2];

        // Traducir el nombre del mes si está en español
        const indice = mesesES.findIndex((mesEspanol) => mesEspanol.toLowerCase() === mes.toLowerCase());
        const mesTraducido = indice !== -1 ? mesesEN[indice] : mes;

        // Formar la nueva cadena de fecha con el nombre del mes traducido en la posición deseada
        const nuevaFecha = mesTraducido + ' ' + dia + ', ' + anio;

        return nuevaFecha;
    }

    // .compartir-redes-sociales
    function traduccionRRSS(tagContenedor) {
        const compartirRRSS = document.querySelectorAll(tagContenedor);
        
        compartirRRSS.forEach(redSocialText => {
            redSocialText.textContent = 'Share this note in your Social Networks';
        })
    }


    btnEN.addEventListener('click', (e)=> {
        e.preventDefault(); 
        // Comprobar si no está vacio 
        if( tituloNotaIngles && tituloNotaIngles.textContent.trim().length > 0  && contenidoNotaIngles && contenidoNotaIngles.textContent.trim().length > 0) { 
            // Quitar el display none de la nota en inglés
            // Solo agregar d-none al container general. 

            containerNotaEN.classList.remove('d-none');

            tituloNotaEs.classList.add('d-none'); 
            containerNotaES.classList.add('d-none'); 

            // Traducción de las fechas
            let fechaNotas = document.querySelectorAll('.date-nota');
            let fechaOBJ = {}       

            fechaNotas.forEach((fecha) => {
                let fechaEnEspañol = fecha.textContent;
                let fechaTraducida = traducirMeses(fechaEnEspañol);

                fecha.textContent = fechaTraducida;      
            });
             

            // texto 'Compartir en RRSS'
            traduccionRRSS('.compartir-redes-sociales');

            // Extraer el texto de la nota en Inglés y agregarla a la sección EN en el JSON notas-reforestamos 
            // Se hizó desde save-json-text.json            

        } else {
            // Verificar que la traducción está en el JSON
            fetch(ajax_object.notas_json).then(response => {
                if (!response.ok) throw new Error('Error al cargar el archivo de las notas');
                return response.json();
            }).then( data => {
                console.log(ajax_object.notas_json);
                const contenidoEn = data[idNota]['notaEn'];
                const tituloNotaEn = contenidoEn['title'];
                const contenidoNotaEn = contenidoEn['contenido']; 

                console.log(tituloNotaEn); 

                contenidoNotaEn.forEach(contenido => {
                    console.log(contenido)
                })

                contenidoNotaEn.forEach((contenido, index) => {
                    const tagType = contenido['type'];

                    const desescaparHTML = (htmlString) => {
                        htmlString = htmlString.replace(/\\\//g, '/');
                        htmlString = htmlString.replace(/\\"/g, '"');
                        htmlString = htmlString.replace(/\\'/g, "'");
                        htmlString = htmlString.replace(/\\n/g, '\n');
                        htmlString = htmlString.replace(/\\t/g, '\t');

                        const textarea = document.createElement('textarea');
                        textarea.innerHTML = htmlString;
                        return textarea.value;
                    };

                    const createHTMLElement = (contenido, tagType) => {
                        const contenedorHTMLEN = document.querySelector('.contenedor-nota-en');
                        const containerHTMLEN = document.querySelector('.contenido-nota-en');

                        const contentHTML = desescaparHTML(contenido['html']);
                        const contenedorTemp = document.createElement('div');
                        contenedorTemp.innerHTML = contentHTML;

                        Array.from(contenedorTemp.childNodes).forEach(node => {
                            if (node instanceof Node) {
                                containerHTMLEN.appendChild(node.cloneNode(true));
                            } else {
                                console.warn('⚠️ Nodo inválido no insertado:', node);
                            }
                        });

                        contenedorHTMLEN.classList.remove('d-none');
                    };

                    const insertStyleOrScriptElement = (contenido, tagType) => {
                        const contenedorHTMLEN = document.querySelector('.contenedor-nota-en');
                        const containerHTMLEN = document.querySelector('.contenido-nota-en');

                        const tag = document.createElement(tagType);
                        tag.type = tagType === 'script' ? 'text/javascript' : 'text/css';
                        tag.innerHTML = desescaparHTML(contenido['html']);

                        containerHTMLEN.appendChild(tag);
                        contenedorHTMLEN.classList.remove('d-none');
                    };

                    // Lógica principal
                    if (tagType === 'style' || tagType === 'script') {
                        insertStyleOrScriptElement(contenido, tagType);
                    } else {
                        createHTMLElement(contenido, tagType);
                    }
                });


                // validar que exista el contenido de la tradución en 'notas-reforestamos'
                if(contenidoEn === 'undefined' || contenidoEn.length === 0) {
                    // si el contenido de la traducción en inglés está vació... hacer la paetición a DeepL  

                    const datosPromesa = {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        
                        body: new URLSearchParams({
                            action: 'traducir_nota_json',
                            idNota: idNota,
                            selectedLanguage: 'en-US',
                            security: ajax_object.nonce
                        })
                    }

                    const promesaTraduccion =  fetch(ajax_object.ajax_url, datosPromesa)
                    .then(response =>  {
                        if (!response.ok) throw new Error('La solicitud no fue exitosa');
                        return response.json();
                    })
                    .then(data =>  {
                        console.log('Respuesta AJAX:', data);

                        if (data.success) {
                            // Aquí manejas cómo mostrar la traducción.
                            console.log(data)
                        } else {
                            console.error('Error:', data.data);
                        }
                    })
                    .catch(error => console.error('Error en la solicitud:', error));

                    return Promise.all(promesaTraduccion); 
                }

            })
            .catch( error => console.error('Error en la solicitud: ', error)); 


            console.log('else');
            
        }
    })


}); 