document.addEventListener('DOMContentLoaded', ()=>{
    // item localStorage
    const idiomaSeleccionado = localStorage.getItem("idioma");

    const TITULOS_POR_IDIOMA = {
        'es' : '.title-general',
        'en-US' : '.title-principal-en',
    }


    const  CONTENIDO_SECCIONES = {
        'es' : {
            SECCION_INTRO : '.content-intro-adopt-es',
            SECCION_CARDS : '.content-cards-adopt-es'
        }, 

        'en-US': {
            SECCION_INTRO : '.content-intro-adopt-en',
            SECCION_CARDS : '.content-cards-adopt-en'
        }
    }

    // Contenido en español
    const intro_es = document.querySelector(CONTENIDO_SECCIONES['es'].SECCION_INTRO); 
    const cards_es = document.querySelector(CONTENIDO_SECCIONES['es'].SECCION_CARDS); 


    // Contenido en inglés
    const intro_en = document.querySelector(CONTENIDO_SECCIONES['en-US'].SECCION_INTRO); 
    const cards_en = document.querySelector(CONTENIDO_SECCIONES['en-US'].SECCION_CARDS); 


    // Contenido titulo principal dependiendo del idioma
    const selectorTitulos = TITULOS_POR_IDIOMA[idiomaSeleccionado]; 
    const titulo = document.querySelector(selectorTitulos).textContent;

    // asignar el texto del titulo dependiendo el Idioma
    document.querySelector('.title-general').textContent = titulo; 

    // Elementos por idioma
    const ELEMENTOS_POR_IDIOMA = {
        'en-US' : {
            intro : intro_es,
            cards: cards_es
        },
        'es' : {
            intro : intro_en,
            cards: cards_en
        }
    }

    // Mostrar los elementos dependiendo el idioma
    const elementos = ELEMENTOS_POR_IDIOMA[idiomaSeleccionado];
    Object.values(elementos).forEach(elemento => {  elemento.innerHTML = '';  })

})