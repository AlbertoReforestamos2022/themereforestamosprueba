<?php  // printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true ));   ?>
    
    <div class="container" style="margin-top:15px;">
        <h1 class="text-light text-center h1 t-va">Vacantes</h1>

        <div class="row row-cols-1 justify-content-center">
            <?php $vacantes = get_post_meta(get_the_ID(), 'reforestamos_group_vacantes', true);

            if ($vacantes) {
                foreach ($vacantes as $vacante) { ?>
                    <div class="col col-lg-7 py-2 px-3">
                        <div class="card border-0 shadow-sm text-black-50">
                            <div class="card-header border-0 bg-transparent">
                                <h2 class="h4"><a class="" href="<?php echo $vacante['documento_vacante']; ?>" target="_blank"><?php echo $vacante['nombre_vacante']; ?></a> </h2>
                            </div>

                            <div class="card-body">
                                <ul>
                                    <li class=""><span class="list-text-vac"> Interesados, enviar correo a: </span> <a class="text-success" href="mailto:<?php echo $vacante['correo_vacante']; ?>"><?php echo $vacante['correo_vacante']; ?></a></li>

                                    <?php if (isset($vacante['fecha_vacante']) && $vacante['fecha_vacante']) : ?>
                                        <li><span class="list-text-date">Fecha limite para postularse: </span><?php echo $vacante['fecha_vacante']; ?></li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <a class="btn btn-outline-light te-bot-vac" href="<?php echo $vacante['documento_vacante']; ?>" target="_blank"> Ver información detallada de la vacante</a>
                            </div>
                        </div>
                    </div>
            <?php }
            } else { ?>
                <div class="col col-lg-7 py-2 px-3">
                    <div class="card border-0 shadow-sm text-black-50">
                        <div class="card-header border-0 bg-transparent">
                            <h2 class="h4">oops, al parecer no hay vacantes </h2>
                        </div>

                        <div class="card-body">
                            <ul class="">
                                <li class="list-text-vac">Si tienes alguna duda sobre las vacantes comunicate al siguiente correo: <a href="mailto:fridagalicia@reforestamos.org">fridagalicia@reforestamos.org</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div> <!-- Contenido Vacantes -->
    

    <div class="container" style="margin-top:30px;">
    <h1 class="text-light text-center h1 t-ss-l">Proyectos para Servicio Social</h1>
    <?php $correo_servicio = get_post_meta( get_the_ID(), 'correo_servicio', true );?>
    <?php $condicionCorreo = (empty($correo_servicio)) ? 'personal@reforestamos.org' : $correo_servicio;?>

    <?php $documento_servicio = get_post_meta( get_the_ID(), 'documento_servicio', true );?>
    <?php $condicionDocumento =  (empty($documento_servicio)) ? 'mailto:personal@reforestamos.org' : $documento_servicio;?>
    <?php $textoBotonDocumento = (empty($documento_servicio)) ? 'Solicitar información sobre el proyecto' : 'Ver información detallada del proyecto';?>


        <div class="row row-cols-1 justify-content-center">
            <div class="col col-lg-7 py-2 px-3">
                <div class="card border-1">
                    <div class="card-body">
                        <p class="text-ss text-black-50">Interesados escribir al siguiente correo: </p>
                        <ul>
                            <li><a class="text-success" href="mailto:<?php echo $condicionCorreo ?>"><?php echo $condicionCorreo; ?></a></li>
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a class="btn btn-outline-light" href="<?php echo $condicionDocumento; ?>" target="_blank"><span class="tex-ss-pro"> <?php echo $textoBotonDocumento ?> </span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido en inglés -->
    <script>
        document.addEventListener('DOMContentLoaded', ()=> {
        const idiomaSeleccionado = localStorage.getItem("idioma");
        const titulo = document.querySelector('.title-general');
        const contentAvisoPrivacidad = document.querySelector('.con-a-p');

        const tituloVacantes = document.querySelector('.t-va');
        const contentVacante = document.querySelectorAll('.list-text-vac');
        const fechaLimite = document.querySelectorAll('.list-text-date');
        const botonVacantes = document.querySelectorAll('.te-bot-vac');

        const tituloServicioSocial = document.querySelector('.t-ss-l');
        const textServicioSocial = document.querySelector('.text-ss');
        const botonProyectSS = document.querySelector('.tex-ss-pro')

            if(idiomaSeleccionado === 'en-US') {
                <?php  $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true ); ?>
                titulo.textContent = `<?php echo $tituloPrincipal; ?>`;
                console.log(titulo);                 

                tituloVacantes.textContent = `Vacancies`;

                botonVacantes.forEach(texto => {
                    texto.textContent = `View detailed vacancy information`;
                })


                contentVacante.forEach((texto)=> {
                    texto.textContent = `Interested parties, please send mail to:`;
                })

                // Fecha lílite postulación
                fechaLimite.forEach(fecha => {
                    fecha.textContent = `Application deadline:`;
                })

                // let objTitVac = {};

                // contentVacante.forEach((item, index) => {
                //     let key = [index];
                //     objTitVac[key] = item.textContent;      
                    
                // })

                tituloServicioSocial.textContent = `Social Service Projects`;
                textServicioSocial.textContent = 'Interested parties should contact us at the following e-mail addresses:'
                botonProyectSS.textContent = `View detailed project information`
                
                // content_aviso_en
                // contentAvisoPrivacidad.innerHTML = ` `;
                let idiomaSelect = 'en-US';

                // petición al servidor 
                let traduccionJson = JSON.stringify({
                    idiomaSelect: idiomaSelect,
                   // objTitVac: objTitVac,
                });
            
                // fetch('http://localhost:7000/bolsa-trabajo', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //     },
                //     body: traduccionJson
                // })
                // .then(response => {
                //     if (!response.ok) {
                //         throw new Error('La solicitud no fue exitosa');
                //     }
            
                //     return response.text(); // O .text() si esperas una respuesta de texto en lugar de JSON 
                // })
                // .then((data) => {
                //     let traduccionBolsa = JSON.parse(data);

                //     console.log(traduccionBolsa);
                            
                // })
                // .catch(error => {

                //     console.error('Error:', error);
                // })                
            }
        })
    </script> 
    <!-- Contenido en inglés -->