<?php get_header(); ?>
    <!-- Carrusel indicadores -->
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
    <!-- Carrusel indicadores -->
    <?php while(have_posts()) { 
        the_post();  ?> 
    <!-- Carousel -->
    <main class="carrusel-principal">
        <div class="">
            <div id="myCarousel1" class="carousel slide" data-bs-ride="carousel">
                <?php $imagenes = array_reverse(get_post_meta( get_the_ID(), 'reforestamos_home_carousel', true )); ?>
                
                <div class="carousel-indicators">
                    <?php  // Botones Carrusel
                    $indiceImagenes = array_keys($imagenes);

                    foreach($indiceImagenes as $indiceImagen){ ?>
                        <button type="button" data-bs-target="#myCarousel1" data-bs-slide-to="<?php echo $indiceImagen ?>" class="boton-indicadores bg-secondary p-1" style="border-radius:50%; height:2px; width:2px;" aria-label="Slide <?php echo $indiceImagen ?>"></button>
                    <?php } ?>
                </div>
                
                <!-- 1ra Imagen Carrusel  -->
                <div class="carousel-inner">
                    <?php foreach($imagenes as $imagen) { ?>
                        
                    <!-- Demás imagenes Carrusel  -->
                    <div class="carousel-item imgs-carrusel">
                        <?php $condicion_url_img = empty($imagen['url_imagen_carousel'] ? $imagen['url_imagen_carousel'] : '#')?>
                        <a href="<?php echo $condicion_url_img; ?> ">
                            <img src="<?php echo $imagen['imagen_carousel']?>" class="img-fluid imagen-carousel"  alt="Imagen carousel" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                        </a>
                        <rect width="100%" height="100%" fill="#777"/>
                    </div>
                    
                    <?php } ?>    
                </div>
    
                <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel1" data-bs-slide="prev">
                    <span class="p-2 rounded-3 bg-secondary h4" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                            <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                        </svg>
                    </span>
                    <span class="visually-hidden">Previous</span>
                </button>
        
                <button class="carousel-control-next" type="button" data-bs-target="#myCarousel1" data-bs-slide="next">
                    <span class="p-2 rounded-3 bg-secondary h4" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                        </svg>
                    </span>
                    <span class="visually-hidden">Next</span>
        
            </div>
        </div>
    </main>
    <!-- /. Carousel -->

    <!-- Lineas de Acción -->
    <section class="container-xxl espacio lineas-accion-sec">        
        <?php $titulo_lineas = get_post_meta( get_the_ID(), 'reforestamos_home_titulo_lineas_accion', true ); ?>
        <div class="d-flex justify-content-center text-primary text-center espacio-lineas-accion">
            <img src="<?php echo get_post_meta( get_the_ID(), 'reforestamos_home_imagen_bellota', true ); ?>" alt="Bellota Reforestamos" class="mt-2 me-3 bellota-rm">
            <h1 class="titulo-lineas-accion t-l-a"><?php echo $titulo_lineas ?></h1>
        </div>
    
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center"> 
            <?php $lineas = get_post_meta( get_the_ID(), 'reforestamos_home_lineas_accion',true );
                foreach($lineas as $linea) {
            ?>  
            <div class="col d-flex justify-content-center col-lineas-accion">
                <div class="card border-primary shadow mb-3 espacio-card-lineas" style="width:16rem;">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-center card-lineas">
                        <img class="linea-accion-img img-lineas" src="<?php echo $linea['imagen_linea_acción']?>" alt="">
                    </div>
                    <div class="card-body d-flex align-items-center text-primary text-lineas">
                        <h5 class="card-title text-center l-a-t"><?php echo $linea['texto_linea_acción'] ?></h5>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
    <!-- ./ Lineas de Acccion-->      
    
    <?php  /* Carrusel sitios de interés */  ?>
    <?php $logos = get_post_meta( get_the_ID(), 'reforestamos_home_sitios_interes',true ); ?>

    <div class="container" style="margin-top: 50px; margin-bottom:50px; padding: 0px;">
        <div class="d-flex justify-content-center text-primary text-center py-3">
            <img src="<?php echo get_post_meta( get_the_ID(), 'reforestamos_home_imagen_bellota', true ); ?>" alt="Bellota Reforestamos" class="mt-2 me-3 bellota-rm">
            <h1 class="titulo-sitios-interes t-s-i"><?php echo get_post_meta( get_the_ID(), 'reforestamos_home_titulo_sitios_interes', true ); ?></h1>
        </div>


        <div class="gallery_container">
            <div class="gallery_content">
                    <?php
                        foreach($logos as $indice => $logo) { ?>  
                        <div class="item_gallery rounded" index-img="<?php echo $indice ?>">
                            <a href="<?php echo $logo['link_imagen_sitios_interes']; ?>">
                                <img src="<?php echo $logo['imagen_sitio_interes']; ?>" class="img-fluid" width="<?php echo $logo['tamano_imagen_sitios_interes'];?>" index-img="<?php echo $indice ?>"  alt="<?php echo $logo['nombre_imagen_sitio_interes'];?>">
                            </a>
                        </div>
                    <?php            
                        }
                    ?>

            </div>

            <div class="carrusel_controls">
                    <button class="btn btn-success arrow-left-carrusel" id="prev"><i class="bi bi-arrow-left"></i></button>
                    <button class="btn btn-success arrow-right-carrusel" id="next"><i class="bi bi-arrow-right"></i></button>
            </div>

        </div>

    </div>

    <?php // Nuestras Notas ?>
    <div class="container-xxl">
        <div class="d-flex justify-content-center text-primary text-center py-3">
            <img src="<?php echo get_post_meta( get_the_ID(), 'reforestamos_home_imagen_bellota', true ); ?>" alt="Bellota Reforestamos" class="mt-2 me-3 bellota-rm">
            <h2 class="titulo-nuestras-notas t-n-b"><?php echo get_post_meta( get_the_ID(), 'reforestamos_home_titulo_nuestras_notas', true ); ?></h2>
        </div> 

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 co-n-b">
            <?php

            $the_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 4,
                'orderby' => 'date',
                'order' => 'DESC'
            )); 
            ?>

            <?php if ( $the_query->have_posts() ) { ?>
                <?php while ( $the_query->have_posts() ) { 
                    $the_query->the_post(); ?>
                <div class="col px-4 px-md-2">
                    <div class="card h-100 border-0 shadow-sm">

                        <?php the_post_thumbnail('mediano', array('class' => 'card-img-top p-2 rounded-1 shadow-sm img-nota'));?>

                        <div class="card-header border-0 bg-transparent p-3">
                            <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->    
                        </div>

                        <div class="card-body px-3">
                            <h5 class="card-title fw-normal"><a href="<?php the_permalink();?>" class="text-decoration-none t-n"><?php the_title();  ?></a></h5>
                        </div>

                        <div class="card-footer d-flex justify-content-evenly border-0 bg-transparent p-3">
                            <p class="text-black-50 dat-not" ><?php the_time(get_option('date_format')); ?></p>
                            <p class="text-black-50" >
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eyeglasses" viewBox="0 0 16 16">
                                    <path d="M4 6a2 2 0 1 1 0 4 2 2 0 0 1 0-4m2.625.547a3 3 0 0 0-5.584.953H.5a.5.5 0 0 0 0 1h.541A3 3 0 0 0 7 8a1 1 0 0 1 2 0 3 3 0 0 0 5.959.5h.541a.5.5 0 0 0 0-1h-.541a3 3 0 0 0-5.584-.953A2 2 0 0 0 8 6c-.532 0-1.016.208-1.375.547M14 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/>
                                </svg>
                                <?php if(function_exists('the_views')){ the_views();} ?>
                            </p>
                        </div>

                    </div>
                </div> 
                <?php } ?>

                <?php wp_reset_postdata(); ?>

            <?php } else { ?>
                <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
            <?php } ?>    
        </div>

        <div class="row row-cols-1 row-cols-md-1">
            <div class="d-flex justify-content-center justify-content-md-end my-3">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline-light btn-v-m">Ver todas las notas</a>
            </div>
        </div>
        
    </div>
    <?php // Nuestras Notas  ?>




    <?php // loop front-page ?>
    <?php }?> 
    <?php // loop front-page ?>



    <?php  /** Contenido en inglés */ ?>
    <script>
        document.addEventListener('DOMContentLoaded', ()=>{
            const idiomaSeleccionado = localStorage.getItem("idioma");
            const contentLineasAccion = document.querySelector('.lineas-accion-sec');
            const tituloSitiosInteres = document.querySelector('.t-s-i');
            const tituloNuestrasNotas = document.querySelector('.t-n-b');
            const contenidoNota = document.querySelector('.co-n-b'); 
            const btnVerNotas = document.querySelector('.btn-v-m');


            if(idiomaSeleccionado === 'en-US') {
                contentLineasAccion.innerHTML = `
                    <?php $titulo_lineas_ingles = get_post_meta( get_the_ID(), 'reforestamos_home_titulo_lineas_accion_inlges', true ); ?>
                    <div class="d-flex justify-content-center text-primary text-center espacio-lineas-accion">
                        <img src="<?php echo get_post_meta( get_the_ID(), 'reforestamos_home_imagen_bellota', true ); ?>" alt="Bellota Reforestamos" class="mt-2 me-3 bellota-rm">
                        <h1 class="titulo-lineas-accion t-l-a"><?php echo $titulo_lineas_ingles ?></h1>
                    </div>
                
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center"> 
                        <?php $lineas_ingles = get_post_meta( get_the_ID(), 'reforestamos_home_lineas_accion_ingles',true );
                            foreach($lineas_ingles as $linea_ingles) {
                        ?>  
                        <div class="col d-flex justify-content-center col-lineas-accion">
                            <div class="card border-primary shadow-lg mb-3 espacio-card-lineas" style="width:16rem;">
                                <div class="card-header bg-transparent border-0 d-flex justify-content-center card-lineas">
                                    <img class="linea-accion-img img-lineas" src="<?php echo $linea_ingles['imagen_linea_acción_ingles']?>" alt="">
                                </div>
                                <div class="card-body d-flex align-items-center text-primary text-lineas">
                                    <h5 class="card-title text-center l-a-t"><?php echo $linea_ingles['texto_linea_acción_ingles'] ?></h5>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                `;
                tituloSitiosInteres.innerHTML = `
                <?php echo get_post_meta( get_the_ID(), 'reforestamos_home_titulo_sitios_interes_en', true ); ?>
                `;
                tituloNuestrasNotas.innerHTML = `
                <?php echo get_post_meta( get_the_ID(), 'reforestamos_home_titulo_nuestras_notas_en', true ); ?>
                `;
                contenidoNota.innerHTML = `
                <?php
                // the query
                $the_query = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC'
                )); ?>

                <?php if ( $the_query->have_posts() ) { ?>
                    <!-- the loop -->
                    <?php while ( $the_query->have_posts() ) { 
                        $the_query->the_post(); ?>

                        <?php $titulos_en = get_post_meta( get_the_ID(), 'nota_blog_ingles_titulo_nota_ingles', false ); ?>
                        <?php if(!empty($titulos_en)) { ?>
                            <div class="col px-4 px-md-2">
                            <div class="card h-100 border-0 shadow-sm">
                                
                                <!--  -->
                                <?php the_post_thumbnail('mediano', array('class' => 'card-img-top p-2 rounded-1 shadow-sm img-nota'));?>

                                <div class="card-header border-0 bg-transparent p-3">
                                    <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->    
                                </div>

                                <div class="card-body px-3">
                                    <h5 class="card-title fw-normal">
                                        <a href="<?php the_permalink();?>" class="text-decoration-none t-n">
                                            <?php 
                                            foreach ($titulos_en as $titulo)
                                                echo esc_html($titulo);
                                            ?>
                                        </a>
                                    </h5>
                                </div>

                                <div class="card-footer d-flex justify-content-evenly border-0 bg-transparent p-3">
                                    <p class="text-black-50 dat-not" ><?php the_time(get_option('date_format')); ?></p>
                                    <p class="text-black-50" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eyeglasses" viewBox="0 0 16 16">
                                            <path d="M4 6a2 2 0 1 1 0 4 2 2 0 0 1 0-4m2.625.547a3 3 0 0 0-5.584.953H.5a.5.5 0 0 0 0 1h.541A3 3 0 0 0 7 8a1 1 0 0 1 2 0 3 3 0 0 0 5.959.5h.541a.5.5 0 0 0 0-1h-.541a3 3 0 0 0-5.584-.953A2 2 0 0 0 8 6c-.532 0-1.016.208-1.375.547M14 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/>
                                        </svg>
                                        <?php if(function_exists('the_views')){ the_views();} ?>
                                    </p>
                                </div>

                            </div>
                        </div> 
                        <?php } ?>  
                    <?php } ?>
                    <!-- end of the loop -->

                    <!-- pagination here -->

                    <?php wp_reset_postdata(); ?>

                <?php } else { ?>
                    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
                <?php } ?>  
                `;
                
                btnVerNotas.innerHTML = ` View all notes `;

                // Fecha Notas 
                let fechaNotas = document.querySelectorAll('.dat-not');
                let fechaOBJ = {}

                function traducirMeses(fecha) {
                    // Mapa de nombres de meses en español a inglés
                    const mesesES = ['enero,', 'febrero,', 'marzo,', 'abril,', 'mayo,', 'junio,', 'julio,', 'agosto,', 'septiembre,', 'octubre,', 'noviembre,', 'diciembre,'];
                    const mesesEN = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                    // Separar la cadena de fecha en día, mes y año
                    const partesFecha = fecha.split(' ');
                    const dia = partesFecha[0];
                    const mes = partesFecha[1];
                    const anio = partesFecha[2];
                
                    // Traducir el nombre del mes si está en español
                    const indice = mesesES.findIndex((mesEspanol) => mesEspanol.toLowerCase() === mes.toLowerCase());
                    const mesTraducido = indice !== -1 ? mesesEN[indice] : mes;

                    // Formar la nueva cadena de fecha con el nombre del mes traducido en la posición deseada
                    const nuevaFecha = mesTraducido + ' ' + dia + ', ' + anio;

                    return nuevaFecha;
                }       

                fechaNotas.forEach((fecha) => {
                    let fechaEnEspañol = fecha.textContent;
                    let fechaTraducida = traducirMeses(fechaEnEspañol);

                    fecha.textContent = fechaTraducida;      
                })
            }

        })
    </script>
    <?php  /** Contenido en inglés */ ?>

    <?php  /** script Modal */ ?>
    <?php  /** Bootstrap JS (con Popper) */ ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php  /** Script para mostrar el modal al cargar */ ?>
    <script>
        window.addEventListener('load', function () {
        const webinarModal = new bootstrap.Modal(document.getElementById('webinarModal'));
        webinarModal.show();
        });
    </script>

<?php get_footer(); ?>



