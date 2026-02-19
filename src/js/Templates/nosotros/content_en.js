document.addEventListener('DOMContentLoaded', ()=> {
        // item localStorage
        const idiomaSeleccionado = localStorage.getItem("idioma");

        // Titulos principales
        const TITULOS_POR_IDIOMA = {
            'es' : '.title-general',
            'en-US' : '.title-principal-en',
        }
    
        // Contenido Secciones
        const CONTENIDO_SECCIONES = {
            'es' : {
                MISION_VISION : '.m-v-con',
                OBJETIVOS : '.o-con', 
                VALORES: '.v-con', 
                PROCESOS_INCIDENCIA : '.p-i-con',
                LOGROS_RECONOCIMIENTOS : '.l-r-con'
            } ,
            'en-US' : {
                MISION_VISION : '.section-mision-vision',
                OBJETIVOS : '.section-objetives', 
                VALORES: '.section-values', 
                PROCESOS_INCIDENCIA : '.section-advocacy-process',
                LOGROS_RECONOCIMIENTOS : '.section-recognitions-accreditations'
            }
        }
    
        // Contenido en Español 
        const misionVision          = document.querySelector(CONTENIDO_SECCIONES['es'].MISION_VISION);
        const objetivos             = document.querySelector(CONTENIDO_SECCIONES['es'].OBJETIVOS);
        const valores               = document.querySelector(CONTENIDO_SECCIONES['es'].VALORES); 
        const procesosIncidencia    = document.querySelector(CONTENIDO_SECCIONES['es'].PROCESOS_INCIDENCIA);
        const logrosReconocimientos = document.querySelector(CONTENIDO_SECCIONES['es'].LOGROS_RECONOCIMIENTOS);
    
        // Contenido Inglés 
        const misionVision_EN          =  document.querySelector(CONTENIDO_SECCIONES['en-US'].MISION_VISION);
        const objetivos_EN             = document.querySelector(CONTENIDO_SECCIONES['en-US'].OBJETIVOS);
        const valores_EN               = document.querySelector(CONTENIDO_SECCIONES['en-US'].VALORES);
        const procesosIncidencia_EN    = document.querySelector(CONTENIDO_SECCIONES['en-US'].PROCESOS_INCIDENCIA);
        const logrosReconocimientos_EN = document.querySelector(CONTENIDO_SECCIONES['en-US'].LOGROS_RECONOCIMIENTOS); 
        
    
        // Contenido titulo principal dependiendo del idioma
        const selectorTitulos = TITULOS_POR_IDIOMA[idiomaSeleccionado]; 
        const titulo = document.querySelector(selectorTitulos).textContent
        // asignar el texto del titulo dependiendo el Idioma
        document.querySelector('.title-general').textContent = titulo; 
    
        // Elementos por idioma
        const ELEMENTOS_POR_IDIOMA = {
            'en-US' : {
                misionVision: misionVision,
                objetivos: objetivos,
                valores: valores,
                procesosIncidencia: procesosIncidencia,
                logrosReconocimientos: logrosReconocimientos
            },
            'es' : {
                misionVision: misionVision_EN,
                objetivos: objetivos_EN,
                valores: valores_EN,
                procesosIncidencia: procesosIncidencia_EN,
                logrosReconocimientos: logrosReconocimientos_EN
            }
        }
        
        // Mostrar los elementos dependiendo el idioma
        const elementos = ELEMENTOS_POR_IDIOMA[idiomaSeleccionado];
        Object.values(elementos).forEach(elemento => {  elemento.innerHTML = '';  })
})