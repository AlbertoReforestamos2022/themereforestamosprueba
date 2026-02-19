<?php

function traduccionContenido() { ?>
        <script>
            // Buscador Notas 
            function contenidoBuscadorNotas() {
                let idiomaSelect = 'en-US';
                let tituloGeneral = document.querySelector('.title-general'); 
                let resultadosTexto = document.querySelector('.res-en');

                // buscador
                let inputBuscador = document.querySelector('#table-dbf');
                let botonBuscador = document.querySelector('#sus');
                let tituloBusqueda = document.querySelectorAll('.tit-res'); 
                let tituloBusquedaOBJ = {}

                tituloBusqueda.forEach((item,index) => {
                    let key = [index];
                    tituloBusquedaOBJ[key] = item.textContent;
                })

                let botonSaberMas = document.querySelectorAll('.boton-s-m');

                // petición al servidor
                let titNotas = JSON.stringify({
                    tituloBusquedaOBJ: tituloBusquedaOBJ,
                    idiomaSelect: idiomaSelect
                });
                
                // consultar el servidor externo
                fetch('http://localhost:7000/buscadorNotas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: titNotas
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('La solicitud no fue exitosa');
                    }
            
                    return response.text(); // O .text() si esperas una respuesta de texto en lugar de JSON 
                })
                .then((data) => {
                    let traNotas = JSON.parse(data); 
                    Object.assign(tituloBusquedaOBJ, traNotas); 

                    tituloGeneral.textContent = 'Results'
                    resultadosTexto.textContent = 'Results';
                    
                    // buscador 
                    inputBuscador.placeholder = "keyword"
                    botonBuscador.value = "Search";
                    botonBuscador.textContent = "Search"; 

                    botonSaberMas.forEach(texto=> {
                        texto.textContent = 'Learn more';
                    })

                    tituloBusqueda.forEach((item, index) => {
                        let key = [index];
                            
                        item.textContent = tituloBusquedaOBJ[key]; 
        
                    })
                    
                })
                .catch(error => {

                    console.error('Error:', error);
                })

                setTimeout(()=>{
                    const cargandoOverlay = document.querySelector('.cargando');
                    cargandoOverlay.classList.remove('cargando-overlay')

                }, 4000)
            }

            function traduccionEs() {
                // Valor idioma seleccionado
            
                setTimeout(()=>{
                    const cargandoOverlay = document.querySelector('.cargando');
                    cargandoOverlay.classList.remove('cargando-overlay');

                    // Guardar informacion en el localStorage
                    guardarIdiomaSeleccionado('es');
                }, 4000) 
            }


            document.addEventListener('DOMContentLoaded', async()=> {
                 function actualizarContenidoAutomatico() {
                    const idiomaSeleccionado = localStorage.getItem("idioma"); 

                    if (idiomaSeleccionado == 'en-US') {
                            // Valor idioma seleccionado
                            let idioma = document.querySelector('#idioma-select');
                            let opcionEn = document.querySelector('#en');
                            opcionEn.selected = true;

                            contenidoBuscadorNotas();
                    } else if(idiomaSeleccionado == 'es') {
                        traduccionEs()  
                    }

                }
                actualizarContenidoAutomatico(); 

                let botonTraducir = document.querySelector('.boton-traducir');

                botonTraducir.addEventListener("submit", async(e)=>{
                    e.preventDefault();

                    const cargando = document.querySelector('.cargando');
                    cargando.classList.add('cargando-overlay'); 

                    const idioma = document.getElementById('idioma-select').value;
                    guardarIdiomaSeleccionado(idioma)
                    
                    if(idioma == 'en-US') {
                        contenidoBuscadorNotas(); 
                        location.reload(); 
                    }

                    if(idioma == 'es') {
                        location.reload(); 
                    }

                    const idiomaSeleccionado = localStorage.getItem("idioma");
                    actualizarContenidoIdioma(idiomaSeleccionado);
                    // location.reload(); 
                })

                function guardarIdiomaSeleccionado(idioma) {
                    localStorage.setItem('idioma', idioma);
                }


                function actualizarContenidoIdioma(idioma) {
                    switch(idioma) {
                        case 'es': 
                            traduccionEs();
                            break;
                        case 'en-US': 
                            contenidoBuscadorNotas();
                            break; 
                        default:
                            localStorage.removeItem("idioma");
                            break; 
                    }

                }
                
            });
        </script>
<?php
}

?>