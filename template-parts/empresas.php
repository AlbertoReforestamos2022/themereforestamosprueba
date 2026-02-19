    <?php             
        echo reforestamos_query_empresas();
   
     //  Script contenido en inglés - script para ocultar/mostrar las actividades 
    ?>


    <script>
        document.addEventListener('DOMContentLoaded', ()=> {

        const idiomaSeleccionado = localStorage.getItem("idioma");
        const titulo = document.querySelector('.title-general');

            if(idiomaSeleccionado === 'en-US') {
                <?php  $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true ); ?>
                titulo.textContent = `<?php echo $tituloPrincipal ?>`;            
            }


        /* content-acciones-empresas */
        const contenido_acciones = document.querySelectorAll('.content-acciones-empresas');

            contenido_acciones.forEach(contenido => {
                // id contenido acciones
                const id_contenido_acciones = contenido.getAttribute('id'); 
                // btns titulo actividad
                const btns_acciones = document.querySelectorAll('.btn-actividad'); 
                
                
                // borrar el contenido de los elementos del modal
                contenido.classList.add('d-none'); 
 
                // 
                btns_acciones.forEach(btn => {
                    btn.addEventListener('click', ()=> {
                        const data_btn = btn.getAttribute('data-actividad');
                        
                        if(data_btn === id_contenido_acciones) {
                            contenido.classList.toggle('d-none'); 
                        } else {
                            if( !contenido.classList.contains('d-none')) {
                                contenido.classList.add('d-none'); 
                            }
                            
                        }
                    }) 
                })
            })
            
            //     cuando carga la página ocultar todo el contenido de las actividades 
            //     Si el ID del btn de la actividad es igual al ID del contenedor de la actividad
            //     entonces mostrar la actividad 
            //     ocultar las demás actividades             
            //     Necestio que el ID o data-id del boton de la actividad sea igual al id del contenedor de la actividad

        })

    </script>