<?php 
        // Función que relaciona el numero de los meses con su nombre 
        // Define los meses en español
        $meses_es = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
        if (!function_exists('formatear_fecha')) {
            function formatear_fecha($fecha, $meses_es) {
                $date = DateTime::createFromFormat('d-m-Y', $fecha);
                if ($date) {
                    $dia = $date->format('d');
                    $mes = $meses_es[intval($date->format('n'))];
                    $ano = $date->format('Y');
                    return "$dia de $mes de $ano";
                } else {
                    return __('Fecha inválida', 'text-domain');
                }
            }
        }
            
    ?>

    <?php
    // función cards Aliados A y B 
    function cardsLogoComponent($empresa) {
        // Sombra card logo 
        $sombra_card = $empresa['tipo'] === 'A' ? 'shadow' : 'shadow-sm'; 
        // Tamaño card
        $tamanio_card = $empresa['tipo'] === 'A' ? '300px' : '200px';


        $hay_contenido = !empty($empresa['galeria']) && !empty($empresa['galeria'][0]['titulo_actividad']);
        $modalId       = $hay_contenido ? $empresa['id'] . 'Modal' : '';
     
        ?>
        <div class="col px-0 px-md-5 py-3">
            <div class="card border-0 <?php echo $sombra_card; ?>" style="height: <?php echo $tamanio_card; ?>;">
                <div class="card-body d-flex justify-content-center align-items-center">
                    <img 
                        src="<?php echo esc_url($empresa['logo']); ?>"
                        class="img-fluid my-auto <?php echo $hay_contenido ? 'cursor-pointer' : ''; ?>" 
                        alt="<?php echo esc_attr($empresa['nombre']); ?> logo"
                        
                        <?php // Condición para que muestre el tamaño de la imagen ?>
                        style="width:<?php echo $empresa['ancho_logo'].'px'; ?>"
                        <?php if ($hay_contenido): ?>
                            data-bs-toggle="modal" 
                            data-bs-target="#<?php echo esc_attr($modalId); ?>"
                        <?php endif; ?>
                    >

                    <?php if($hay_contenido): ?>
                        <!-- Primer modal -->
                        <?php primerModalComponent($empresa); ?>

                        <!-- Segundo Modal -->
                        <?php foreach($empresa['galeria'] as $actividad) : ?>
                            <?php segundoModalComponent($actividad, $empresa); ?>

                        <?php endforeach; ?> 
                    <?php endif;
                    ?>
                </div>
            </div>
        </div>
    <?php     
    }

    // función primer modal 
    function primerModalComponent($empresa) { ?> 
        <?php
            $hay_contenido = !empty($empresa['galeria']) && !empty($empresa['galeria'][0]['titulo_actividad']);
            $modalId       = $hay_contenido ? $empresa['id'] . 'Modal' : '';
            $empresa_id = $empresa['id']; 
        ?>

        <div class="modal fade" id="<?php echo esc_attr($modalId); ?>" tabindex="-1" aria-labelledby="<?php echo esc_attr($modalId . 'Label'); ?>" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h1 class="text-center text-light h4" id="<?php echo esc_attr($modalId . 'Label'); ?>">
                            <?php echo esc_html($empresa['nombre']); ?>
                        </h1>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <!-- Botones actividades de empresas -->
                            <div class="row row-cols-1 row-cols-md-3 justify-content-center align-items-center my-3">
                                <?php 
                                foreach($empresa['galeria'] as $index => $actividad) : ?>
                                    <div class="col w-auto">
                                        <?php // botonComponent ?>
                                        <?php echo botonComponent($index, $actividad, $empresa); ?> 
                                    </div>
                                <?php 
                                endforeach; 
                                ?>

                            </div>
                        </div> 

                        <?php foreach ($empresa['galeria'] as $actividad): ?>

                            <?php // Carrusel component ?> 
                            <?php contenidoActividadComponent($actividad, $empresa); ?> 

                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
  
    <?php
    }

    // función botones actividades (componente botón)
    function botonComponent($indice, $actividad, $empresa) {
        $arrayBtns = [
            'light'    => [0, 5, 10],
            'warning'  => [1, 6, 11],
            'danger'   => [2, 7, 12],
            'success'  => [3, 8, 13],
            'info'     => [4, 9, 14],

        ];

        $idActividad = sanitize_title($actividad['titulo_actividad']);    
        $nombre_id = sanitize_title( $empresa['nombre']); 

        foreach($arrayBtns as $colorKeys => $indexKey )
            foreach($indexKey as $key ) {
                if($indice === $key ) { ?> 

                    <a class="btn btn-<?php echo $colorKeys?> text-white btn-actividad btn-actividad-<?php echo $index; ?>" data-actividad="<?php echo $nombre_id . '-' . $idActividad ?>">
                        <?php echo esc_html($actividad['titulo_actividad']); ?>  
                    </a>

                <?php
                }
            }   

    }

    // función carrusel (primer Modal)
    function contenidoActividadComponent($actividad, $empresa) { ?> 
        <?php 
            $idcarrusel = sanitize_title( $empresa['id']);  
            $idActividad = sanitize_title($actividad['titulo_actividad']);      
            $carrusel_id = $idcarrusel .'-'. $idActividad;
            // titulo id 
            $empresa_id = sanitize_title( $empresa['id']);
        ?> 

        <?php // Carrusel ?>
        <div class="row align-items-center content-acciones-empresas" id="<?php echo $empresa_id . '-' . $idActividad; ?>">
            <div class="col-12 col-lg-6">
                <p class="text-black-50"><?php echo $actividad['texto_actividad']; ?></p>
            </div>

            <?php // Carrusel primera actividad ?>
            <div class="col-12 col-lg-6">

                <?php // Carrusel de imágenes de reforación?>
                <div id="<?php echo $carrusel_id; ?>" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach(array_reverse($actividad['imagenes_actividad']) as $index => $imagen) { ?>
                            <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                <div class="d-flex justify-content-center content-carrusel">
                                    <img class="img-fluid" src="<?php echo $imagen; ?>" data-bs-toggle="modal" data-bs-target="#<?php echo $carrusel_id; ?>-Modal" alt="Imagen <?php echo $index + 1; ?>"> 
                                </div>
                                
                            </div>
                        <?php } ?>
                    </div>

                    <?php // Contenedor btns ?>
                    <div class="container d-flex justify-content-center btns-first-carrusel">
                        <?php //  Botón prev ?>
                        <div class="row d-flex justify-content-center align-items-center mx-1">
                            <div class="col">
                                <button class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $carrusel_id; ?>" data-bs-slide="prev">
                                    <span class="btn-light" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                        </svg>
                                    </span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                            </div>
                        </div>

                        <?php // Botón next ?>
                        <div class="row d-flex justify-content-center align-items-center mx-1">
                            <div class="col">
                                <button class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $carrusel_id; ?>" data-bs-slide="next">
                                    <span class="btn-light" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                        </svg>
                                    </span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                        </div>                                                
                    </div>
                </div>
            </div> 
        </div>
    <?php 
    }

    // Función galería actividad 
    function segundoModalComponent($actividad, $empresa) { ?> 
        <?php 

            $imagenes_actividad= array_reverse($actividad['imagenes_actividad']);
            $nueva_imagenes_actividad = array_combine(range(1, count($imagenes_actividad)), array_values($imagenes_actividad)); // nuevo array de imgs
            $total_imagenes = count($imagenes_actividad); // Obtener el total de imágenes    

            $fecha_actividad = $actividad['fecha_actividad'];
            $fechaFormateadaUno = formatear_fecha($fecha_actividad, $meses_es);

            $idcarrusel = sanitize_title( $empresa['id']); 
            $idActividad = sanitize_title($actividad['titulo_actividad']);      
            $carrusel_id = $idcarrusel .'-'. $idActividad;  
        ?>
        <?php // Segundo modal ?>
        <div class="modal fade" id="<?php echo $carrusel_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $carrusel_id; ?>-ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content"> 
                    <div class="modal-header">
                        <button type="button" class="btn-close text-danger" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>                     
                    <div class="modal-body">
                        <div class="container">
                            <h3 class="text-center text-light fw-light">
                                <span><?php echo $actividad['texto_actividad']; ?></span> 
                                </br> 
                                <span class=""><?php echo $fechaFormateadaUno; ?></span>
                            </h3>
                        </div>

                        <?php // Empieza segundo carrusel ?>
                        <div class="container carousel slides" id="" style="margin-top:30px;">
                            <div class="row justify-content-center align-items-center">

                                <?php //Contenido Carrusel ?>
                                <div class="col-8 d-flex justify-content-center content-image-principal">
                                    <?php // Segundo carrusel imágenes ?> 
                                    <div id="<?php echo $carrusel_id . '-carrusel-actividad'; ?>" 
                                        class="carousel carousel-dark slide carousel-fade common-carousel" 
                                        data-bs-ride="carousel">

                                        <?php // Índice carrusel ?>
                                        <div class="carousel-indicators indice-segundo-modal">
                                            <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                <button 
                                                    data-bs-target="#<?php echo $carrusel_id . '-carrusel-actividad'; ?>" 
                                                    data-bs-slide-to="<?php echo $index - 1; ?>" 
                                                    class="<?php echo $index == 1 ? 'active' : ''; ?>" 
                                                    aria-current="true" style="background-color:#65b492!important;">
                                                </button>
                                            <?php } ?>
                                        </div>

                                        <?php // Imágenes del carrusel?>
                                        <div class="carousel-inner second-image-carousel">
                                            <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?> ">
                                                    <div class="d-flex justify-content-center image-second-carrusel">
                                                        <img class="img-fluid image-second-modal" src="<?php echo $imagen; ?>" 
                                                            alt="Imagen <?php echo $carrusel_id; ?>" 
                                                            data-index-image="<?php echo $index;?>">
                                                    </div>
                                                    <div class="content_index_images mt-3">
                                                        <h5 class="text-center text-light">
                                                            <span class="mb-3 index_active"><?php echo $index; ?></span> 
                                                            / 
                                                            <span class="mb-3 total_images"><?php echo $total_imagenes; ?></span> 
                                                        </h5>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php // Contenedor btns ?>
                        <div class="container d-flex justify-content-evenly content-btns-gallery">
                            <?php // Botón prev?>
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col">
                                    <button 
                                        class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                        type="button" 
                                        data-bs-target="#<?php echo $carrusel_id . '-carrusel-actividad'; ?>" 
                                        data-bs-slide="prev">
                                        <span class="btn-light" aria-hidden="true">
                                            <svg 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                width="20" 
                                                height="20" 
                                                fill="currentColor" 
                                                class="bi bi-arrow-left" 
                                                viewBox="0 0 16 16"
                                            >
                                                <path 
                                                    fill-rule="evenodd" 
                                                    d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"
                                                />
                                            </svg>
                                        </span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                </div>
                            </div>

                            <?php // Botón next ?>
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col">
                                    <button 
                                        class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                        type="button" 
                                        data-bs-target="#<?php echo $carrusel_id . '-carrusel-actividad'; ?>" 
                                        data-bs-slide="next"
                                    >
                                        <span 
                                        class="btn-light" 
                                        aria-hidden="true">
                                            <svg 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            width="20" 
                                            height="20" 
                                            fill="currentColor" 
                                            class="bi bi-arrow-right" 
                                            viewBox="0 0 16 16"
                                            >
                                                <path 
                                                    fill-rule="evenodd" 
                                                    d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"
                                                />
                                            </svg>
                                        </span>
                                        <span class="visually-hidden">Siguiente</span>
                                    </button>
                                </div>
                            </div>   

                        </div>                        
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $carrusel_id; ?>Modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>    
    <?php      
    }

    // función carrusel 
    function carruselImgs($empresa) { ?> 
        <?php
            $hay_contenido = !empty($empresa['galeria']) && !empty($empresa['galeria'][0]['titulo_actividad']);
            $modalId       = $hay_contenido ? $empresa['id'] . 'Modal' : '';
            $empresa_id = $empresa['id']; 
        ?>
        <div class="item_gallery rounded" index-img="<?php echo $indice ?>">
                <img 
                    src="<?php echo esc_url($empresa['logo']); ?>" 
                    class="img-fluid" 
                    style="width: <?php echo esc_html($empresa['ancho_logo']).'px'; ?>" 
                    index-img="<?php echo $indice ?>"  
                    alt="<?php echo esc_attr($empresa['nombre']); ?>"

                    <?php if ($hay_contenido): ?>
                        data-bs-toggle="modal" 
                        data-bs-target="#<?php echo esc_attr($modalId); ?>"
                    <?php endif; ?>
                >

            <?php if($hay_contenido): ?>
                <?php // Primer modal ?>
                <?php primerModalComponent($empresa); ?>

                <?php // Segundo Modal ?> 
                <?php foreach($empresa['galeria'] as $actividad) : ?>
                    <?php segundoModalComponent($actividad, $empresa); ?>

                <?php endforeach; ?> 
            <?php endif;
            ?>
        </div>
    <?php 
    }

    // Cards Reforestamos 
    // este irá en inc/galerias-empresas/cards/empresa-card.php
    // Serán 3 secciones (Aliados A , Aliados B, Aliados C)

    // Aliados A 
    function reforestamos_aliados_A($aliadosA) { ?>
        <?php // función cards aliados empresas A?> 
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2 justify-content-center align-items-center">
            <?php foreach ($aliadosA as $empresa): ?>
                <?php cardsLogoComponent($empresa); ?>
            <?php endforeach; ?>
        </div>
    <?php
    }

    // Aliados B
    function reforestamos_aliados_B($aliadosB) { ?> 
        <?php // función cards aliados empresas B ?> 
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2 justify-content-center align-items-center">
            <?php foreach ($aliadosB as $empresa): ?>
                <?php cardsLogoComponent($empresa); ?> 
            <?php endforeach; ?>
        </div>

        
    <?php     
    }

    // Alidos C y D
    function reforestamos_aliados_C($aliadosC) { ?>
        <?php // carrusel imgs ?>
        <div class="gallery_container">
            <div class="gallery_content">
                <?php
                    foreach($aliadosC as $indice => $empresa) :
                        carruselImgs($empresa);            
                    endforeach; 
                ?>
            </div>

            <div class="carrusel_controls">
                    <button class="btn btn-success arrow-left-carrusel" id="prev"><i class="bi bi-arrow-left"></i></button>
                    <button class="btn btn-success arrow-right-carrusel" id="next"><i class="bi bi-arrow-right"></i></button>
            </div>

        </div>
    <?php    
    }