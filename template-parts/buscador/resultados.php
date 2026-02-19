<?php

function borrarContenido() { ?>
        <script>
            function borrarContenido(){
                document.addEventListener('DOMContentLoaded', ()=> {
                    const contenidoBorrar = document.querySelector('.notas-principales');
                    contenidoBorrar.remove();
                    //classList.add('d-none');
                                    
                    // titulo Resultados
                    const tituloActualizado = document.querySelector('.title-general');
                    const contenidoTitulo = tituloActualizado;
                    contenidoTitulo.textContent = 'Resultados';


                    // titulo Resultados en inglés
                    const idiomaSeleccionado = localStorage.getItem("idioma");
                    // testo resultados 
                    const textRe = document.querySelector('.te-res');
                    if(idiomaSeleccionado === 'en-US') {
                        contenidoTitulo.textContent = 'Results';
                        textRe.textContent = `results found`;

                    // formulario buscador
                    let inputBuscador = document.querySelector('#table-dbf');
                    let botonBuscador = document.querySelector('#sus');
                    inputBuscador.placeholder = "keyword"
                    botonBuscador.value = "Search";
                    botonBuscador.textContent = "Search"; 
                    }
                })


                // campos buscador en inglés
                    
            }
            borrarContenido();
        </script>
<?php
}
?>