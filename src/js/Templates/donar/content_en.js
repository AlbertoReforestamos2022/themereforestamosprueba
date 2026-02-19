document.addEventListener('DOMContentLoaded', ()=> {
    const idiomaSeleccionado = localStorage.getItem("idioma");


    const CONTENIDO_SECCIONES = {
        'es': {
            DONATE_CONTENT  : '.donate_content_title_es',
            SUBTITULO_DONAR : '.donate_subtitle-es',
            TEXTO_DONAR     : '.donate_text-es',
            CONTENIDO_DONAR : '.donate_section-es'
        }, 

        'en-US': {
            DONATE_CONTENT  : '.donate_content_title_en',
            SUBTITULO_DONAR : '.donate_subtitle-en',
            TEXTO_DONAR     : '.donate_text-en',
            CONTENIDO_DONAR : '.donate_section-en'
        }
    } 
    
    // Contenido en español
    const contenedorTituloDonar_es = document.querySelector(CONTENIDO_SECCIONES['es'].DONATE_CONTENT)
    const subtituloDonar_es = document.querySelector(CONTENIDO_SECCIONES['es'].SUBTITULO_DONAR);
    const textoDonar_es     = document.querySelector(CONTENIDO_SECCIONES['es'].TEXTO_DONAR);
    const contenidoDonar_es = document.querySelector(CONTENIDO_SECCIONES['es'].CONTENIDO_DONAR);
    
    // Contenido en inglés  
    const contenedorTituloDonar_en = document.querySelector(CONTENIDO_SECCIONES['en-US'].DONATE_CONTENT)
    const subtituloDonar_en = document.querySelector(CONTENIDO_SECCIONES['en-US'].SUBTITULO_DONAR);
    const textoDonar_en     = document.querySelector(CONTENIDO_SECCIONES['en-US'].TEXTO_DONAR);
    const contenidoDonar_en = document.querySelector(CONTENIDO_SECCIONES['en-US'].CONTENIDO_DONAR);

    // obj condicional que muestra el contenido según el valor de localStorage
    
    const ELEMENTOS_POR_IDIOMA = {
        'en-US' : {
            contenedorTituloDonar : contenedorTituloDonar_es,
            subtituloDonar  : subtituloDonar_es,
            textoDonar      : textoDonar_es,
            contenidoDonar  : contenidoDonar_es
        },
        'es' : {
            contenedorTituloDonar : contenedorTituloDonar_en,
            subtituloDonar  : subtituloDonar_en,
            textoDonar      : textoDonar_en,
            contenidoDonar  : contenidoDonar_en
        }
    }


    const elementos = ELEMENTOS_POR_IDIOMA[idiomaSeleccionado];
    Object.values(elementos).forEach(elemento => {  elemento.innerHTML = ''; if(idiomaSeleccionado == 'en-US'){ contenedorTituloDonar_es.classList.add('d-none'); contenidoDonar_es.classList.add('d-none');}  })

})
