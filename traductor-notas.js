// Contenido en inglés -->
document.addEventListener('DOMContentLoaded', () => {
    /** Idioma seleccionado */
    const idiomaSeleccionado = localStorage.getItem("idioma");
    /** Nota en inglés */
    const contenedorNotaEn = document.querySelector('.contenedor-nota-en');
    const tituloNotaEn = document.querySelector('.titulo-nota_en');
    const contenidoNotaEn = document.querySelector('.contenido-nota-en');  
    
    /** Nota en Español */
    const contendedorNotaEs = document.querySelector('.contenedor-nota-es');     
    const tituloNotaEs = document.querySelector('.titulo-nota-es');
    const contenidoNotaEs = document.querySelector('.contenido-nota-es');

    const idNota = 'post-' + contendedorNotaEs.getAttribute('data-id');


    const contenidoNota = document.querySelector('.cont-not-en'); // Contenido nota en inglés
    contenedorNotaEn.classList.add('d-none'); // Ocultar la nota en inglés por defecto

    // comprobar si el valor de localstorage === 'en-US'
    if (idiomaSeleccionado === 'en-US') {
        
        if(tituloNotaEn && tituloNotaEn.textContent.trim().length > 0 && contenidoNotaEn && contenidoNotaEn.textContent.trim().length > 0) {
            contenedorNotaEn.classList.remove('d-none'); // Mostrar la nota en inglés
            contendedorNotaEs.classList.add('d-none'); // Ocultar la nota en español

            // Traducción fechas 
            traducirFechas('.date-nota'); 
        } else {
            contenedorNotaEn.classList.remove('d-none'); // Mostrar la nota en inglés
            contendedorNotaEs.classList.add('d-none'); // Ocultar la nota en español

            // extraer información 'notas-reforestamos.json' 
            fetch(ajax_object.notas_json)
            .then(response => {
                if (!response.ok) throw new Error('Error al cargar el archivo JSON');
                return response.json();
            })
            .then(data => {
                const contenidoEn = data[idNota]['notaEn'];
                const tituloNotaEn = contenidoEn['title'];
                const contenidoNotaEn = contenidoEn['contenido']; 

                const contenidoPluginEs = data[idNota]['notaEs'];

                const tituloEn = document.querySelector('.titulo-nota_en');
                tituloEn.innerText = `${tituloNotaEn}`; 

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
                        if (tagType === 'script') {
                            tag.type = 'text/javascript';
                            tag.textContent = desescaparHTML(contenido['html']); // más seguro para scripts
                        } else if (tagType === 'style') {
                            tag.type = 'text/css';
                            tag.innerHTML = desescaparHTML(contenido['html']);
                        }

                        containerHTMLEN.appendChild(tag);
                        contenedorHTMLEN.classList.remove('d-none');

                        console.log(`[${tagType.toUpperCase()} insertado]:`, desescaparHTML(contenido['html']));

                    };

                    const insertImgElement = (contenido) => {
                        const contenedorHTMLEN = document.querySelector('.contenedor-nota-en');
                        const containerHTMLEN = document.querySelector('.contenido-nota-en'); 

                        const divImgElement = document.createElement('div');
                        divImgElement.classList.add('d-flex', 'justify-content-center');

                        const img = document.createElement('img');
                        img.src = contenido['src'] ?? '';
                        img.alt = contenido['text'] ?? '';

                        divImgElement.appendChild(img); 
                        containerHTMLEN.appendChild(divImgElement); 
                    };


                    // Lógica principal
                    if (tagType === 'style' || tagType === 'script') {
                        insertStyleOrScriptElement(contenido, tagType);
                    } else if(tagType === 'img'){
                        insertImgElement(contenido,tagType); 
                    }
                    else {
                        createHTMLElement(contenido, tagType);
                    }
                });

                traducirFechas('.date-nota');
            })
            .catch(err => console.error('Error al cargar JSON:', err));                
            }

    } else if (idiomaSeleccionado !== 'en-US') {
        contenedorNotaEn.classList.add('d-none'); // Ocultar la nota en inglés
    }

    // Funciones Auxuliares
    function desescaparHTML(htmlString) {
        htmlString = htmlString.replace(/\\\//g, '/')
                            .replace(/\\"/g, '"')
                            .replace(/\\'/g, "'")
                            .replace(/\\n/g, '\n')
                            .replace(/\\t/g, '\t');
        const textarea = document.createElement('textarea');
        textarea.innerHTML = htmlString;
        return textarea.value;
    }

    function traducirFechas(selector) {
        const mesesES = ['enero,', 'febrero,', 'marzo,', 'abril,', 'mayo,', 'junio,', 'julio,', 'agosto,', 'septiembre,', 'octubre,', 'noviembre,', 'diciembre,'];
        const mesesEN = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        document.querySelectorAll(selector).forEach(fecha => {
            const partes = fecha.textContent.trim().split(' ');
            const dia = partes[0];
            const mes = partes[1];
            const anio = partes[2];
            const i = mesesES.findIndex(m => m.toLowerCase() === mes.toLowerCase());
            if (i !== -1) {
                fecha.textContent = `${mesesEN[i]} ${dia}, ${anio}`;
            }
        });
    }

});
// Contenido en inglés -->