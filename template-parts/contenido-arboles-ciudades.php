<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', ()=> {
        const arrayCarousel = document.querySelectorAll('.imgs-carrusel');
            if(arrayCarousel.length >= 0){
                arrayCarousel[0].classList.add('active');
            }
            
            const arrayBoton = document.querySelectorAll('.boton-indicadores');
            if(arrayBoton.length >= 0){
                arrayBoton[0].classList.add('active');
                arrayBoton[0].setAttribute('aria-current','true');
            }
    })
</script>

<?php   printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true ));   ?>



<?php /** <!-- Carrusel con las imágenes de las iniciativas (convocatorías, anuncios, etc... ) --> */ ?>
<?php $carrusel = array_reverse( get_post_meta( get_the_ID(), 'reforestamos_group_informacion_carrusel_section', true ) ); ?>
<div class="container-carrusel">
    <div id="carruselHomeAyC" class="carousel carousel-dark slide" data-bs-ride="carousel">
        <?php /* <-- Indicadores --> */ ?>
        <div class="carousel-indicators">
            <?php 
                $indiceCarrusel = array_keys( $carrusel ); 

                foreach( $indiceCarrusel as $indice ) { ?>
                    
                    <button type="button" data-bs-target="#carruselHomeAyC" data-bs-slide-to="<?php echo $indice; ?>" class="boton-indicadores" aria-label="Slide <?php echo $indice; ?>"></button>
                
                <?php 
                }
            ?>
        </div>

        <?php /* <-- Diapositivas Carrusel --> */ ?>
        <div class="carousel-inner">
            <?php foreach($carrusel as $info ) { ?>
                    <div class="carousel-item imgs-carrusel">
                        <a href="<?php echo $info['link_carrusel']; ?>" target="_blank">
                            <img src="<?php echo $info['imagen_carrusel']; ?>" class="d-block w-100" alt="" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                        </a>
                        
                        <?php if(!empty( $info['titulo_carrusel'] ) || !empty( $info['texto_carrusel'] ) ) { ?>

                            <div class="carousel-caption d-none d-md-block">
                                <h5> <?php $info['titulo_carrusel']; ?></h5>
                                <p><?php $info['texto_carrusel']; ?></p>
                            </div>

                        <?php
                            }
                        ?>

                    </div>

            <?php
                }
            ?>


        </div>

        <?php /* <-- Botones para mover las diapositivas --> */?>
        <button class="carousel-control-prev" type="button" data-bs-target="#carruselHomeAyC" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#carruselHomeAyC" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

</div>

<?php /** <!-- Nuestros programas --> */ ?>
<?php $nuestrosProgramas = get_post_meta( get_the_ID(), 'reforestamos_group_nuestros_programas_section', true); ?>
<div class="container" style="margin-top:50px;">

    <div class="row">
        <div class="col">
            <h3 class="text-primary text-center title-container-initiatives">Conoce más acerca de las iniciativas</h3>
        </div>
    </div>

    <div class="content-initiatives shadow-sm rounded">

        <div class="content-cards-initiatives ">
            
            <div class="carrusel-wrapper gallery">
                <div class="carrusel-items-initiatives gallery-container">
                
                    <?php foreach($nuestrosProgramas as $index => $programa) {
                        $tituloPrograma = $programa['titulo_programa'];
                        $logoPrograma = $programa['logo_programa'];
                        $resumenPrograma = $programa['descripcion_programa'];

                        // Resultados programas
                        $tituloResultado = $programa['titulo_resultado']; 

                        $resultadoUno = $programa['resultado_uno_numero'];
                        $textoResultadoUno = $programa['resultado_uno_texto'];

                        $resultadoDos = $programa['resultado_dos_numero'];
                        $textoResultadoDos = $programa['resultado_dos_texto'];

                        $resultadoTres = $programa['resultado_tres_numero'];
                        $textoResultadoTres = $programa['resultado_tres_texto'];

                        $resultadoCuatro = $programa['resultado_cuatro_numero'];
                        $textoResultadoCuatro = $programa['resultado_cuatro_texto'];

                        $documentoConocePrograma = !empty($programa['documento_conoce_programa']) ? $programa['documento_conoce_programa'] : '';
                        $enlaceConvocatoria = !empty($programa['enlace_convocatoria']) ? $programa['enlace_convocatoria'] : '';
                        $condicionNumeraliaDocArbol = $tituloPrograma === 'Doc. Árbol' ? '+' : '' ;

                        
                    ?>
                    <?php /** Logo iniciativas */ ?>
                    <div class="card d-grid align-items-center rounded card-content-initiatives card-content-initiatives-<?php echo $index; ?> gallery-item gallery-item-<?php echo $index; ?>" data-index="<?php echo $index;?>">
                        <div class="d-flex justify-content-center p-2">
                            <img class="img-fluid img-initiative" src="<?php echo $logoPrograma?>" alt="" data-bs-target="#<?php echo sanitize_title($tituloPrograma); ?> " id="<?php echo sanitize_title($tituloPrograma)?>-id">
                        </div>
                    
                        <div class="row row-cols-auto d-flex justify-content-center d-none px-3 py-1">
                            <button type="button" class="btn btn-danger text-white close-initiative "> Volver al inicio </button>
                        </div>

                        <div class="card-body d-flex justify-content-center">
                            <button type="button" class="btn btn-primary text-center btn-card-initiative w-100" data-bs-target="#<?php echo sanitize_title($tituloPrograma); ?> " id="<?php echo sanitize_title($tituloPrograma)?>-id"> Saber más </button>
                        </div>

                     
                    </div>

                    <?php 
                    }

                    ?>

                    <div class="container container-controls">
                        <div class="row row-cols-5 justify-content-between carrusel-initiatives-controls gallery-controls"></div>
                    </div>
                    
                </div>

            </div>
        </div>

        <?php /** Container inicitaivas*/ ?>
        <div class="container-initiatives">
            <?php foreach($nuestrosProgramas as $programa) {
                    $nombreIniciativa = $programa['nombre_iniciativa'];
                    $tituloPrograma = $programa['titulo_programa'];
                    $logoPrograma = $programa['logo_programa'];
                    $resumenPrograma = $programa['descripcion_programa'];

                    // Resultados programas
                    $tituloResultado = $programa['titulo_resultado']; 

                    $resultadoUno = $programa['resultado_uno_numero'];
                    $textoResultadoUno = $programa['resultado_uno_texto'];

                    $resultadoDos = $programa['resultado_dos_numero'];
                    $textoResultadoDos = $programa['resultado_dos_texto'];

                    $resultadoTres = $programa['resultado_tres_numero'];
                    $textoResultadoTres = $programa['resultado_tres_texto'];

                    $resultadoCuatro = $programa['resultado_cuatro_numero'];
                    $textoResultadoCuatro = $programa['resultado_cuatro_texto'];

                    $documentoDatosOrganizacionesCiudadesReconocidas = !empty($programa['organizaciones_reconocidas']) ? $programa['organizaciones_reconocidas'] : ''; 

                    $documentoConocePrograma = !empty($programa['documento_conoce_programa']) ? $programa['documento_conoce_programa'] : '';
                    $enlaceConvocatoria = !empty($programa['enlace_convocatoria']) ? $programa['enlace_convocatoria'] : '';
                    $condicionNumeraliaDocArbol = $tituloPrograma === 'Doc. Árbol' ? '+' : '' ;

                ?>

                <div class="description-initiatives" id="<?php echo sanitize_title($tituloPrograma)?>">
                    <h1 class="text-primary text-center title-initiative"> <?php echo $tituloPrograma?> </h1>

                    <div class="contenido-programas p-md-5 p-2">
                        <?php echo  wpautop( wp_kses_post(  $resumenPrograma )); ?>


                    <div class="container">
                        
                        <?php
                            if(!empty($documentoDatosOrganizacionesCiudadesReconocidas)) {
                                
                                    echo $documentoDatosOrganizacionesCiudadesReconocidas; 

                                    $datos = obtener_datos_excel($documentoDatosOrganizacionesCiudadesReconocidas);

                                    echo $datos; 

                                    if (!is_array($datos)) {
                                        return '<p>' . esc_html($datos) . '</p>';
                                    }

                                    if(!empty($documentoDatosOrganizacionesCiudadesReconocidas)) {
                                        $output = '<table border="1">';
                                        foreach ($datos as $fila) {
                                            $output .= '<tr>';
                                            foreach ($fila as $celda) {
                                                $output .= '<td>' . esc_html($celda) . '</td>';
                                            }
                                            $output .= '</tr>';
                                        }

                                        $output .= '</table>';

                                        return $output;

                                    }

                            }
                        ?>

                    </div>
                        


                        <div class="resultados">
                            <h2 class="text-primary text-center my-4"><?php echo $tituloResultado; ?></h2>

                            <div class="row justify-content-center content_resultados_iniciativas">
                                <?php 
                                if(!empty($resultadoUno) && !empty($textoResultadoUno)) { ?>
                                    <!-- Resultado Uno -->
                                    <div class="col-md iconos_resultados_tcw p-2">
                                        <div class="card icono-resultado-tcw bg-transparent border-0 shadow rounded-4">
                                            <div class="card-header bg-transparent border-0 ">
                                                <h3 class="text-center fw-semibold text-primary"> <?php echo $condicionNumeraliaDocArbol; ?> <span class="counter" data-target="<?php echo $resultadoUno; ?>"><?php echo $resultadoUno; ?></span> </h3>
                                            </div>

                                            <div class="card-body bg-transparent border-0 container_iconos_iniciativas">
                                                <p class="text-center texto-contenido-tcw"><?php echo $textoResultadoUno; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } 
                                
                                if(!empty($resultadoDos) && !empty($textoResultadoDos)) { ?>
                                    <!-- Resultado Dos -->
                                    <div class="col-md iconos_resultados_tcw p-2">
                                        <div class="card icono-resultado-tcw bg-transparent border-0 shadow rounded-4">
                                            <div class="card-header bg-transparent border-0 ">
                                                <h3 class="text-center fw-semibold text-primary"> <?php echo $condicionNumeraliaDocArbol; ?> <span class="counter" data-target="<?php echo $resultadoDos; ?>"><?php echo $resultadoDos; ?></span> </h3>
                                            </div>

                                            <div class="card-body bg-transparent border-0 container_iconos_iniciativas">
                                                <p class="text-center texto-contenido-tcw"><?php echo $textoResultadoDos; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } 

                                if(!empty($resultadoTres) && !empty($textoResultadoTres)) { ?>
                                    <!-- Resultado Tres -->
                                    <div class="col-md iconos_resultados_tcw p-2">
                                        <div class="card icono-resultado-tcw bg-transparent border-0 shadow rounded-4">
                                            <div class="card-header bg-transparent border-0 ">

                                                <h3 class="text-center fw-semibold text-primary"> <?php echo $condicionNumeraliaDocArbol; ?> <span class="counter" data-target="<?php echo $resultadoTres; ?>"><?php echo $resultadoTres; ?></span> </h3>
                                            </div>

                                            <div class="card-body bg-transparent border-0 container_iconos_iniciativas">
                                                <p class="text-center texto-contenido-tcw"><?php echo $textoResultadoTres; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                
                                if(!empty($resultadoTres) && !empty($textoResultadoTres)) { ?>
                                    <!-- Resultado Cuatro -->
                                    <div class="col-md iconos_resultados_tcw p-2">
                                        <div class="card icono-resultado-tcw bg-transparent border-0 shadow rounded-4">
                                            <div class="card-header bg-transparent border-0 ">
                                                <h3 class="text-center fw-semibold text-primary"> <?php echo $condicionNumeraliaDocArbol; ?> <span class="counter" data-target="<?php echo $resultadoCuatro; ?>"><?php echo $resultadoCuatro; ?></span> </h3>
                                            </div>

                                            <div class="card-body bg-transparent border-0 container_iconos_iniciativas">
                                                <p class="text-center texto-contenido-tcw"><?php echo $textoResultadoCuatro; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?> 

                            </div>

                            <?php
                            if($nombreIniciativa == "Tree_Cities_World"){ ?> 

                                <!-- Mapa Ciudades reconocidas TCW -->
                                <div class="container">
                                    <h4 class="text-center text-primary my-4">Ciudades reconocidas como Tree Cities of the World</h4>
                                    <div class="row row-cols-1 row-cols-md-2 justify-content-center shadow">
                                        <div class="col order-2 order-lg-1 p-0">
                                            <div class="map_TCW" id="map_TCW">

                                            </div>
                                        </div>

                                        <div class="col order-1 order-lg-2">

                                            <div class="row justify-content-start filter-cities">
                                                <div class="col-12 col-md-4 filter-year d-flex justify-content-center p-2">
                                                    <select class="btn btn-primary rounded-4" name="year-recognized" id="year-recognized">

                                                    </select>
                                                </div>

                                                <div class="col-12 col-md-4 filter-state d-flex justify-content-center p-2">
                                                    <select class="btn btn-primary rounded-4" name="state-recognized" id="state-recognized">
                                                        <option value="">Selecciona un estado</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-12 filter-year shadow p-2">
                                                    <p class="subtitulos-contenido-ac text-center p-2" id="year-selected"></p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="list" id="list">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            }
                            ?>

                            <?php
                            if($nombreIniciativa == 'doc_arbol') {
                                echo $tituloPrograma;
                            }   
                            ?>
                        </div>
                    </div>

                    <div class="footer-content d-flex justify-content-between my-5">

                        <?php  if(!empty($documentoConocePrograma)) { ?>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary rounded-4 btn-saber-mas" data-bs-toggle="modal" data-bs-target="#<?php echo sanitize_title($tituloPrograma) . 'Documento' ?>">
                            Saber más acerca de este programa
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="<?php echo sanitize_title($tituloPrograma) . 'Documento' ?>" tabindex="-1" aria-labelledby="<?php echo sanitize_title($tituloPrograma) . 'Documento' ?>Label" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="pdfModalLabel"><?php echo $tituloPrograma; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <iframe id="pdfViewer" src="<?php echo $documentoConocePrograma; ?>" style="width: 100%; height: 700px; border: none;"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php
                        }   
                        
                        ?>

                        <?php 
                            if(!empty($enlaceConvocatoria)) { ?>

                                <a class="btn btn-primary rounded-4 btn-saber-mas" href="<?php echo $enlaceConvocatoria; ?>" target="_blank">
                                Ver convocatoría
                                </a>

                            <?php 
                            }                     
                        ?>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>


</div>

<?php /** <!-- Sobre Nosotros --> */ ?>
<?php $titulo_nosotros = get_post_meta( get_the_ID(), 'titulo_nosotros', true); ?>
<?php $texto_nosotros = get_post_meta( get_the_ID(), 'texto_nosotros', true); ?>


<div class="container container_sobre-nosotros mt-5">
    <div class="row justify-content-between shadow content-sobre-nosotros">
        <div class="col-lg p-0">
            <img src="http://localhost:8080/wordpress/wp-content/uploads/2024/10/20190218_102327-scaled.jpg" class="img-fluid img-sobre-nosotros" alt="">
        </div>
        <div class="col-lg">
            <div class="container">
                <div class="row px-3 contenido-nosotros">
                    <div class="col-12 p-1 titulo-nosotros">
                        <h2> <?php echo $titulo_nosotros; ?> </h2>
                    </div>
                    <div class="col-12 p-1 texto-contenido-ac">

                        <?php echo wpautop( wp_kses_post(  $texto_nosotros )); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php /** <!-- Por que más árboles en las ciudades -->  */ ?>
<?php $mas_arboles_section = get_post_meta( get_the_ID(), 'reforestamos_group_mas_arboles_section', true); ?>
<?php $titulo_seccion_arbolado_urbano = get_post_meta( get_the_ID(), 'titulo_seccion_arbolado_urbano', true); ?>
<?php $documento_conoce_arbolado = get_post_meta( get_the_ID(), 'documento_conoce_arbolado', true); ?>

<div class="container container_beneficios-arbolado">
    
    <h2 class="text-primary fw-semibold text-center"><?php echo $titulo_seccion_arbolado_urbano; ?></h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center content_beneficios-arbolado">

    <?php foreach($mas_arboles_section as $beneficios){ ?>

        <div class="col iconos_beneficios-arbolado">
            <div class="card icono-beneficio bg-transparent border-0 shadow rounded-4">
                <div class="card-header bg-transparent border-0 ">
                    <div class="d-flex justify-content-center my-2">
                        <img src="<?php echo $beneficios['imagen_icono'];?>" width="100" class="img-fluid" alt="icon-beneficios-arbolado">
                    </div>
                    
                    <p class="text-center fw-semibold subtitulos-contenido-ac"><?php echo $beneficios['titulo_icono'];?></p>
                </div>

                <div class="card-body bg-transparent border-0 container_iconos-arbolado">
                    <p class="text-center texto-contenido-ac"> <?php echo $beneficios['descripcion_icono'];?> </p>
                </div>
            </div>
        </div>


    <?php }?>

    </div>

    <?php  if(!empty($documento_conoce_arbolado)) { ?>
                <div class="container content_saber-mas-arbolado">
                    <div class="row justify-content-center">
                        <div class="col-auto mt-5">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary rounded-4 btn-saber-mas" data-bs-toggle="modal" data-bs-target="#<?php echo sanitize_title($titulo_seccion_arbolado_urbano) . 'Documento' ?>">
                               Descarga el documento sobre los beneficios del arbolado urbano
                            </button>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="<?php echo sanitize_title($titulo_seccion_arbolado_urbano) . 'Documento' ?>" tabindex="-1" aria-labelledby="<?php echo sanitize_title($titulo_seccion_arbolado_urbano) . 'Documento' ?>Label" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="text-primary fw-semibold text-center" id="pdfModalLabel"><?php echo $titulo_seccion_arbolado_urbano?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe id="pdfViewer" src="<?php echo $documento_conoce_arbolado; ?>" style="width: 100%; height: 700px; border: none;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            <?php
            }
        ?>    
</div>

<?php /** <!-- Contacto --> */ ?>
<?php $textoContacto = get_post_meta( get_the_ID(), 'texto_contacto', true); ?>
<div class="container container_contacto-ac">
    <h1 class="text-center text-primary fw-semibold">Contáctanos</h1>

    <div class="row justify-content-center text-formulario"> 
        <div class="col-7">
            <h5 class="text-center fw-semibold texto-contenido-ac"> <?php echo $textoContacto?> </h5>
        </div>
    </div>

    <form class="container formulario_contacto-ac" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
        <?php wp_nonce_field('submit_contact_form', 'my_form_nonce'); ?>
        
        <div class="row">
            <div class="col-sm">
                <label class="text-light h5" for="name">Nombre y apellido(s):</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="col-sm">
                <label class="text-light h5" for="email">Correo:</label>
                <input type="email" id="email" name="email" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm">
                <label class="text-light h5" for="subject">Asunto:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="col-sm">
                <label class="text-light h5" for="phone">Teléfono:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
        </div>

        <div class="row-cols-1">
            <div class="col-12">
                <label class="text-light h5" for="message">Mensaje:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-8">
                <input class="btn btn-light text-white w-100 boton-enviar" type="submit" name="formulario_ayc" value="Enviar">
            </div>
        </div>
    </form>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 my-5">
                <div class="mensaje-formulario card border-0 p-3 text-white text-center" id="mensaje-formulario" style="opacity: 0; transition: opacity 0.5s ease-out;">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Eliminar después -->
<?php // echo  $correo_arboles_ciudades = get_post_meta(get_the_ID(), 'correo_arboles_ciudades', true);  ;?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.8.0/proj4.js"></script>
