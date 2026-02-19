<?php /*  <!-- Links - scripts leafletMaps -->  */ ?>
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
    });
</script>

<?php /* <!-- Sobre Nosotros -->  */ ?>
<?php $imagen_nosotros = !empty(get_post_meta( get_the_ID(), 'imagen_nosotros', true)) ? get_post_meta( get_the_ID(), 'imagen_nosotros', true) : 'Agrega una imagen'; ?>
<?php $titulo_nosotros = !empty(get_post_meta( get_the_ID(), 'titulo_nosotros', true)) ? get_post_meta( get_the_ID(), 'titulo_nosotros', true) : 'Agrega un titulo'; ?>
<?php $contenido_nosotros = !empty(get_post_meta( get_the_ID(), 'contenido_nosotros', true)) ? get_post_meta( get_the_ID(), 'contenido_nosotros', true) : 'Agrega el contenido';?>

<div class="container container_sobre-nosotros mt-5">
    <div class="row justify-content-between shadow content-sobre-nosotros">
        <div class="col-lg px-0">
            <img src="<?php echo $imagen_nosotros; ?>" class="img-fluid img-sobre-nosotros" alt="">
        </div>
        <div class="col-lg">
            <div class="container">
                <div class="row px-3 contenido-nosotros">
                    <div class="col-12 p-3 titulo-nosotros">
                        <h2><?php echo $titulo_nosotros; ?></h2>
                    </div>
                    <div class="col-12 p-2 texto-contenido-ac texto-contenido-red-oja text-justify">
                        <?php echo wpautop( wp_kses_post( $contenido_nosotros )); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php /* <!-- Nuestros Principios -->  */ ?>
<?php 
    $seccion_nuestros_principios = get_post_meta( get_the_ID(), 'reforestamos_group_nuestros_principios', true); 
    $documento_nuestros_principios = !empty(get_post_meta( get_the_ID(), 'documento_nuestros_principios', true)) ? get_post_meta( get_the_ID(), 'documento_nuestros_principios', true) : '#';
?>

<div class="container container_beneficios-arbolado container_nuestros-principios">
    <h2 class="titulo_red-oja fw-semibold text-center">Nuestros Principios</h2>

    <div class="row justify-content-center content_nuestros-princios">

        <?php 
            foreach($seccion_nuestros_principios as $principios) {
            $titulo_icono = !empty($principios['titulo_icono']) ? $principios['titulo_icono'] : 'Agrega un titulo';
            $descripcion_icono = !empty($principios['descripcion_icono']) ? $principios['descripcion_icono'] : 'Agrega una descripción';
            $imagen_icono = !empty($principios['imagen_icono']) ? $principios['imagen_icono'] : 'Agrega una imagen';
            ?>

            <div class="col-md iconos_nuestros_principios">
                <div class="card icono_principio bg-transparent border-0 shadow rounded-4">
                    <div class="card-header bg-transparent border-0 ">
                        <div class="d-flex justify-content-center my-2">
                            <img src="<?php echo $imagen_icono;?>" class="img-fluid" width="60" alt="icon-beneficios-arbolado">
                        </div>
                        
                        <h5 class="text-center fw-semibold subtitulos-contenido-ac"><?php echo $titulo_icono; ?></h5>
                    </div>

                    <div class="card-body bg-transparent border-0 container_iconos-arbolado">
                        <p class="text-center texto-contenido-ac"><?php echo $descripcion_icono; ?></p>
                    </div>
                </div>
            </div>

        <?php    
        }
        ?>

    </div>

    <?php 
        if(!$documento_nuestros_principios == '#') {
            ?>
                <div class="container content_saber-mas-arbolado">
                    <div class="row justify-content-center">
                        <div class="col-auto mt-5">
                            <a class="btn rounded-4 btn-saber-mas" href="<?php echo $documento_nuestros_principios; ?>" target="_blank">
                                Conoce más acerca de nuestros principios
                            </a>
                        </div>
                    </div>
                </div>
            <?php
        }

    ?> 


</div>

<?php /* <!-- Modelo de incidencia --> */ ?>
<?php
    $modelos_de_incidencia = get_post_meta( get_the_ID(), 'reforestamos_group_modelo_incidencia', true); 
    $documento_modelo_incidencia = !empty(get_post_meta( get_the_ID(), 'documento_modelo_incidencia', true))  ? get_post_meta( get_the_ID(), 'reforestamos_group_modelo_incidencia', true) : '';
?>
<div class="container container_nuestros-principios container_modelo-incidencia">
    <h2 class="titulo_red-oja fw-semibold text-center">Modelo de Incidencia</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4  justify-content-center content_nuestros-princios content_modelo-incidencia">
        <?php foreach($modelos_de_incidencia as $modelo) { 
            $titulo_icono = !empty($modelo['titulo_icono']) ? $modelo['titulo_icono'] : 'agrega un titulo'; 
            $descripcion_icono = !empty($modelo['descripcion_icono']) ? $modelo['descripcion_icono'] : 'agrega una descripción';
            $imagen_icono = !empty($modelo['imagen_icono']) ? $modelo['imagen_icono'] : '';             
        ?>
            <div class="col iconos_modelo_incidencia ">
                <div class="card icono_modelo-incidencia bg-transparent border-0 shadow rounded-4">
                    <div class="card-header bg-transparent border-0 ">
                        <div class="d-flex justify-content-center my-2">
                            <img src="<?php echo $imagen_icono; ?>" class="img-fluid" width="60" alt="<?php echo 'icono '. $titulo_icono; ?>">
                        </div>
                        
                        <h5 class="text-center fw-semibold subtitulos-contenido-ac"><?php echo $titulo_icono; ?></h5>
                    </div>

                    <div class="card-body bg-transparent border-0 container_iconos-arbolado">
                        <p class="text-center texto-contenido-ac"><?php echo $descripcion_icono; ?></p>
                    </div>
                </div>
            </div>



        <?php } ?> 


    </div>
    
    <?php
        if(!$documento_modelo_incidencia) {
           ?>

            <div class="container content_saber-mas-arbolado">
                <div class="row justify-content-center">
                    <div class="col-auto mt-5">
                        <a class="btn rounded-4 btn-saber-mas" href="<?php echo $documento_modelo_incidencia; ?>">
                            Conoce más acerca de nuestro modelo de incidencia
                        </a>
                    </div>
                </div>
            </div>

            <?php
        }
    ?>


</div>

<?php /* <!-- Nuestros Ejes  --> */ ?>
<?php $nuestros_ejes = get_post_meta( get_the_ID(), 'reforestamos_group_nuestros_ejes', true); ?> 
<div class="container container_nuestros-ejes">
    <h2 class="titulo_red-oja fw-semibold text-center">Nuestros Ejes</h2>

    <div class="row row-cols-lg-2 align-items-center justify-content-center content_nuestros-ejes">

    <?php  foreach($nuestros_ejes as $eje) {
        $titulo_icono = !empty($eje['titulo_icono']) ? $eje['titulo_icono'] : 'Agrega un titulo';
        $descripcion_icono = !empty($eje['descripcion_icono']) ? $eje['descripcion_icono'] : 'Agrega una descripción';
        $imagen_icono = !empty($eje['imagen_icono']) ? $eje['imagen_icono'] : '';
    
        $condicion_img_F = $eje['titulo_icono'] == 'Fortalecimiento de Organizaciones Juveniles' || $eje['titulo_icono'] == "Cultura Ambiental" ? "90" : "60";
    ?>
        <div class="col-md iconos_nuestros-ejes">
            <div class="icono_nuestros-ejes bg-transparent border-0 shadow rounded-4">
                <div class="card_icon-nuestros-ejes bg-transparent border-0">
                    <div class="d-flex justify-content-center my-2 img-nuestros-ejes">
                        <img src="<?php echo $imagen_icono; ?>" class="img-fluid" width="<?php echo $condicion_img_F; ?>" alt="icon-beneficios-arbolado">
                    </div>
                    
                    <h5 class="text-center fw-semibold subtitulos-contenido-ac"><?php echo $titulo_icono; ?></h5>
                </div>

                <div class="card_text-icon bg-transparent border-0 container_iconos-nuestros-ejes">
                    <p class="texto-contenido-ac"><?php echo $descripcion_icono; ?></p>
                </div>
            </div>
        </div>

    <?php
    }?>
    
    </div>


</div>

<?php /* <!-- ¿Qué se ha hecho?  --> */ ?>
<?php $memorias = get_post_meta( get_the_ID(), 'reforestamos_group_que_hacemos', true); ?>

<!-- Hacer que el contendor no mida de largo mas de 300 px en laptop para que se aprecie mejor -->
<div class="container container-files">
    <h2 class="titulo_red-oja text-center fw-semibold margen-titulo">¿Qué se a hecho?</h2>

    <div class="row shadow content-search">

        <div class="col-12">
            <div class="row section-filter-files p-3 shadow-sm">
                <div class="col-4 col-md-auto d-grid align-items-center">
                    <h5 class="title-filter-section subtitulo-red-oja text-center">Documentos <span class="mx-1" id="dateLoaded"></span></h5>
                </div>

                <!-- Hacer que este contenedor tenga scroll y no se haga larga la lista de años -->           
                <div class="col-6 col-md-auto content-anios m-0">
                    <div class="content-title-years m-2">
                        <p class="subtitulo-red-oja m-0 text-center">Buscar por año</p>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="content-filter-years">
                            <select class="btn btn-accion-red-oja" id="yearFilter"> 

                            </select>                    
                        </div>
                    </div>
                </div>

                <!-- Agregar estilos al buscador de documentos -->
                <div class="col-auto col-md-auto ">
                    <p class="subtitulo-red-oja text-center m-0">Buscar por palabra clave: </p>

                    <div class="buscador d-flex m-2 shadow-sm rounded p-3" >
                        <span class="mx-2 mt-1 subtitulo-red-oja"><i class="bi bi-search"></i></span>
                        <input type="text"class="rounded search-file-input" name="buscador-docs" id="searchFiles">
                    </div>
                   
                    <!-- <button class="btn btn-buscar-red-oja mx-2">Buscar</button> -->
                </div>


            </div>

            <div class="container content-results my-0" id="content_results_files">
                
            </div>        
        </div>
    </div>

</div>

<?php /* <!-- Galería --> */ ?>
<?php $galeria_red_OJA = get_post_meta( get_the_ID(), 'imagenes_galeria', true ); ?>
<?php $galeria_red_OJA = array_values($galeria_red_OJA); ?>
<?php $titulos_galeria = get_post_meta( get_the_ID(), 'reforestamos_group_galeria', true); ?>

<div class="container" style="margin-top: 50px; margin-bottom:50px; padding: 0px;">
    <h2 class="titulo_red-oja text-center fw-semibold">Galería</h2>

    <div class="gallery_container">
        <div class="gallery_content">
                <?php
                    foreach($galeria_red_OJA as $indice => $galeria) { ?>  
                    <div class="item_gallery shadow rounded">
                        <img src="<?php echo $galeria; ?>" index-img="<?php echo $indice ?>" class="img-fluid" alt="">
                    </div>

                <?php            
                    }
                ?>

        </div>

        <div class="carrusel_controls">
                <button class="btn btn-accion-red-oja arrow-left-carrusel" id="prev"><i class="bi bi-arrow-left"></i></button>
                <button class="btn btn-accion-red-oja arrow-right-carrusel" id="next"><i class="bi bi-arrow-right"></i></button>
        </div>

    </div>

    <div class="lightbox-carrusel d-none">
        <div class="container-lightbox">

            <div class="lightbox-img">
                    <img class="img-fluid" src="" alt="">
            </div>

            <div class="gallery_controls">
                    <button class="btn btn-accion-red-oja arrow-left-lightbox" id="prev"><i class="bi bi-arrow-left"></i></button>
                    <button class="btn btn-accion-red-oja arrow-right-lightbox" id="next"><i class="bi bi-arrow-right"></i></button>
            </div>

            <div class="control-close d-flex justify-content-end p-2">
                <span class="btn btn-danger text-white"> Cerrar </span>
            </div>
        </div>

    </div>

</div>

<?php /* <!-- Nuestro Mapa --> */ ?>
<div class="container container_mapa">
    <h1 class="titulo_red-oja fw-semibold text-center">Nuestro Mapa</h1>

    <div class="row row-cols-1 row-cols-lg-2 content_mapa">
        <div class="col order-2 order-lg-1 content_mapa-instituciones">
            <div class="map_red-oja" id="map_red-oja"></div>
        </div>

        <div class="col order-1 order-lg-2" id="list-content" >
            <div class="row justify-content-center align-items-center" >
                <div class="col">  <?php /* <!-- Botón para ver todas las instituciones --> */?>
                    <button class="w-100 btn btn-accion-red-oja" id="showAll">Ver todas las organizaciones</button>
                </div>
                
                <div class="col">
                    <select class="w-100 btn btn-accion-red-oja text-white" id="stateFilter"> 
                        <option class="" value="">Selecciona un estado de México o un país</option>
                        <?php 
                            $organizaciones = get_post_meta(get_the_ID(), 'reforestamos_group_institutions_group', true);

                            $mexico_estados = [];
                            $sudamerica_paises = [];

                            foreach ($organizaciones as $organizacion) {
                                $estado = $organizacion['institution_state'];
                                $pais = $organizacion['institution_country'];

                                if ($estado === 'Sudamérica') {
                                    if (!in_array($estado, $sudamerica_paises)) {
                                        $sudamerica_paises[$estado] = $pais;
                                    }
                                } else {
                                    if (!in_array($estado, $mexico_estados)) {
                                        $mexico_estados[] = $estado;
                                    }
                                }
                            }

                            // Ordenar alfabéticamente
                            sort($mexico_estados, SORT_NATURAL | SORT_FLAG_CASE);
                            sort($sudamerica_paises, SORT_NATURAL | SORT_FLAG_CASE);
                        ?>

                        <optgroup class="border" label="México">
                            <?php foreach ($mexico_estados as $estado ): ?>
                                <option value="<?php echo $estado; ?>"><?php echo $estado; ?></option>
                            <?php endforeach; ?>
                        </optgroup>

                        <optgroup class="border" label="Latinoamérica">
                            <?php foreach ($sudamerica_paises as $estado  => $pais): ?>
                                <option value="Sudamérica"><?php echo $pais; ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="institutions-list" id="list">
                <h2 id="stateTitle"></h2>
            </div>
        </div>
    </div>
</div>


<?php /* <!-- Súmate a la Red Oja --> */ ?>
<?php $como_sumarte = get_post_meta( get_the_ID(), 'reforestamos_group_como_sumarte', true); ?>

<div class="container container_sumate">
    <h2 class="titulo_red-oja fw-semibold text-center margen-titulo">Súmte a la Red Oja</h2>

    <div class="row row-cols-1 row-cols-lg-3 justify-content-center content_sumate">

        <?php foreach($como_sumarte as $sumate) {
            $titulo_seccion = $sumate['titulo_seccion'];
            $descripción_seccion = $sumate['descripción_seccion'];
        ?>

            <div class="col content_como-sumo shadow">
                <h3 class="subtitulo-red-oja subtitulo_sumar"><?php echo $titulo_seccion; ?></h3>

                <div class="list_como_sumo">
                    <?php echo $descripción_seccion; ?>
                </div>

            </div>

        <?php
        }?>
    </div>
</div>



<?php /* <!-- Contáctanos y RRSS --> */ ?>
<?php /* <!-- Agregar el campo Organización --> */ ?>
<div class="container container_contacto-ac">
    <h1 class="titulo_red-oja text-center fw-semibold">Contáctanos</h1>

    <div class="row justify-content-center text-formulario">
        <div class="col-12 col-md-10">
            <h5 class="text-center fw-semibold texto-contenido-red-oja">Si tienes dudas sobre alguno de nuestros programas o estás interesado en participar con nosotros, deja tu mensaje y nos pondremos en contacto contigo.</h5>
        </div>
    </div>

    <form class="container formulario_contacto-red-oja" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
        <?php wp_nonce_field('submit_contact_form', 'form_red_oja'); ?>
        
        <div class="row">
            <div class="col-sm">
                <label class="subtitulo-red-oja h5" for="name">Nombre y apellido(s):</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="col-sm">
                <label class="subtitulo-red-oja h5" for="email">Correo:</label>
                <input type="email" id="email" name="email" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm">
                <label class="subtitulo-red-oja h5" for="subject">Asunto:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="col-sm">
                <label class="subtitulo-red-oja h5" for="phone">Teléfono:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
        </div>

        <div class="row-cols-1">
            <div class="col-12">
                <label class="subtitulo-red-oja h5" for="message">Mensaje:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-8">
                <input class="btn btn-accion-red-oja w-100 boton-enviar" type="submit" name="formulario_red-oja" value="Enviar">
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

<div class="container">
    <h1 class="titulo_red-oja text-center fw-semibold margen-titulo">Sígue a la Red Oja en sus Redes Sociales</h1>

    <div class="row row-cols-12 row-cols-lg-3 justify-content-center">
        <div class="col col-rs-content">
            <div class="card shadow border-0 facebook-card">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="div">
                        <span class="facebook-icon"> 
                            <a href="https://www.facebook.com/@RedOjaMX" target="__blank"><i class="bi bi-facebook"></i></a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <!-- <h5 class="text-center subtitulo-red-oja mt-4 fw-bold">Facebook (@RedOjaMX)</h5> -->
            </div>
        </div>
        <div class="col col-rs-content">
            <div class="card shadow border-0 instagram-card">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="div">
                        <span class="instagram-icon">
                            <a href="https://www.instagram.com/red.oja/" target="__blank"><i class="bi bi-instagram"></i></a>
                        </span>
                    </div>
                    
                </div>

            </div> 
            <div class="card-footer bg-transparent border-0">
                <!-- <h5 class="text-center subtitulo-red-oja mt-4 fw-bold">Instagram (red.oja)</h5> -->
            </div>   
        </div>
        <div class="col col-rs-content">
            <div class="card shadow border-0 youtube-card">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div class="div">
                        <span class="youtube-icon">
                            <a href="https://www.youtube.com/@redoja8292" target="__blank"><i class="bi bi-youtube"></i></a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                    <!-- <h5 class="text-center subtitulo-red-oja mt-4 fw-bold">Youtube (@redoja8292)</h5> -->
            </div>     
        </div>
    </div>
</div>








<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.8.0/proj4.js"></script>


             
                
