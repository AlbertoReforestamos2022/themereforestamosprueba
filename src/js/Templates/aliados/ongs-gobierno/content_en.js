// <!-- Contenido en inglés -->

    document.addEventListener('DOMContentLoaded', ()=> {
        console.log('Página Aliados')
        const idiomaSeleccionado = localStorage.getItem("idioma");
        
        // Titulos principales
        const TITULOS_POR_IDIOMA = {
            'es' : '.title-general',
            'en-US' : '.title-principal-en',
        }

        // Contenido titulo principal dependiendo del idioma
        const selectorTitulos = TITULOS_POR_IDIOMA[idiomaSeleccionado]; 
        const titulo = document.querySelector(selectorTitulos).textContent
        // asignar el texto del titulo dependiendo el Idioma
        document.querySelector('.title-general').textContent = titulo; 
    })
 