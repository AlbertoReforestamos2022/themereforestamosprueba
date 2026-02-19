 <!-- Logos empresas A -->
    <div class="container-lg espacio-lineas-accion">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2 justify-content-center align-items-center">
            <?php  $logos = get_post_meta( get_the_ID(), 'reforestamos_group_logos', true ); 
            if($logos) {
               foreach($logos as $logo) { ?> 
                    <div class="col px-0 px-md-5 py-3">
                        <div class="card border-0 shadow" style="height: 300px;">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <?php 
                                    $empresa_id = sanitize_title($logo['nombre_logo']); 
                                    $condicionModal = (!empty($logo['titulo_actividad_uno']) &&  
                                                        !empty($logo['texto_actividad_uno']) &&
                                                        !empty($logo['fecha_actividad_uno_A']) &&  
                                                        !empty($logo['imagenes_actividad_uno'])) ? 
                                                        $empresa_id . 'Modal' : 
                                                        ' '; 

                                    $cursorPointerCondicion = (!empty($logo['titulo_actividad_uno']) &&  
                                                        !empty($logo['texto_actividad_uno']) &&  
                                                        !empty($logo['imagenes_actividad_uno'])) ?  
                                                        'cursor-pointer' : 
                                                        ' ';                                                        
                                ?>
                                    <img 
                                        src="<?php echo $logo['imagen_logo']; ?>"
                                        class="img-fluid my-auto <?php echo $cursorPointerCondicion; ?>" 
                                        alt="<?php echo $logo['nombre_logo']; ?>"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#<?php echo $condicionModal; ?>"
                                    >
                                
                            </div>
                        </div>
                    </div>
    
                <?php } ?>       
            <?php } ?>
        </div>
    </div>
    <!-- ./Logos empresas A -->

    <!-- Modal empresas A  -->
    <?php if(!empty($logos)) { ?>
        <?php foreach($logos as $key => $logo) { ?>
        <!-- Nombre Empresa -->
        <?php $empresa_id = sanitize_title($logo['nombre_logo']); ?> 

        <!-- Primer modal empresas -->
        <div class="modal fade" id="<?php echo $empresa_id; ?>Modal" tabindex="-1" aria-labelledby="<?php echo $empresa_id; ?>ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h1 class="text-center text-light h4" id="<?php echo $empresa_id; ?>ModalLabel"><?php echo $tituloEmpresa = $logo['nombre_logo']; ?></h1>
                    </div>

                    <div class="modal-body">
                        <!-- Secciones de actividades empresas -->
                        <div class="container">
                            <!-- Botones actividades de empresas -->
                            <div class="row row-cols-1 row-cols-md-3 justify-content-center align-items-center my-3">
                                <!-- Boton reforestación light -->
                                <?php  if(!empty($logo['titulo_actividad_uno'])) { ?>
                                    <div class="col w-auto"> 
                                        <p class="btn btn-outline-light btn-actividad actividad-uno <?php echo sanitize_title($logo['titulo_actividad_uno']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-reforestacion';?>">
                                            <?php echo $logo['titulo_actividad_uno']; ?>
                                        </p>
                                    </div>
                                <?php } ?>

                                <!-- Boton Matenimiento warning -->
                                <?php if(!empty($logo['titulo_actividad_dos'])) { ?>
                                    <div class="col w-auto">
                                        <p class="btn btn-outline-warning btn-actividad actividad-dos <?php echo sanitize_title($logo['titulo_actividad_dos']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-mantenimiento';?>">
                                            <?php echo $logo['titulo_actividad_dos']; ?>
                                        </p>
                                    </div>
                                <?php } ?>     

                                <!-- Botón Brechas Cortafuego danger -->
                                <?php  if(!empty($logo['titulo_actividad_tres'])) { ?>
                                    <div class="col w-auto">
                                        <p class="btn btn-outline-danger btn-actividad brechas-cortafuego-empresa actividad-tres <?php echo sanitize_title($logo['titulo_actividad_tres']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-brechas';?>">
                                            <?php echo $logo['titulo_actividad_tres']; ?>
                                        </p>
                                    </div>
                                <?php } ?>          
                                
                                <!-- Bóton otras actividades 1 success -->
                                <?php  if(!empty($logo['titulo_actividad_cuatro'])) { ?>
                                    <div class="col w-auto">
                                        <p class="btn btn-outline-success btn-actividad actividades-uno-empresa actividad-cuatro <?php echo sanitize_title($logo['titulo_actividad_cuatro']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-actividades-uno';?>">
                                            <?php echo $logo['titulo_actividad_cuatro']; ?>
                                        </p>
                                    </div>
                                <?php } ?>         
                                
                                <!-- Botón otras actividades 2 info -->
                                <?php  if(!empty($logo['titulo_actividad_cinco'])) { ?>
                                    <div class="col w-auto">
                                        <p class="btn btn-outline-info btn-actividad actividades-dos-empresa actividad-cinco <?php echo sanitize_title($logo['titulo_actividad_cinco']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-actividades-dos';?>">
                                            <?php echo $logo['titulo_actividad_cinco']; ?>
                                        </p>
                                    </div>
                                <?php } ?>                                                
                            </div>   
                            <!-- ./Botones actividades de empresas -->

                            <!-- Contenido actividad -->
                            <div class="row act-emp">
                                <!-- Contenido primera actividad  imagenes_actividad_uno texto_actividad_uno -->
                                <?php if(!empty($logo['texto_actividad_uno']) && !empty($logo['imagenes_actividad_uno'])){?>
                                <?php $reforestacion_id = sanitize_title($logo['nombre_logo'] .'-actividad-uno' );?>
                                <div class="content-collapse reforestacion-content contenido-actividad" id="<?php echo $empresa_id . '-btn-reforestacion';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                            <p class="text-black-50"><?php echo $logo['texto_actividad_uno']; ?></p>
                                        </div>

                                        <!-- Carrusel de imagenes de reforestación  -->
                                        <div class="col-12 col-lg-6">
                                            <!-- Carrusel de imágenes de reforación -->
                                            <div id="<?php echo $empresa_id . '-carrusel-reforestacion'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($logo['imagenes_actividad_uno']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img class="img-fluid image-first-modal" src="<?php echo $imagen; ?>" data-bs-toggle="modal" data-bs-target="#<?php echo $reforestacion_id; ?>-Modal" id="<?php echo $reforestacion_id; ?>-Galery" alt="Imagen <?php echo $index + 1; ?>"> 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-reforestacion'; ?>" data-bs-slide="prev">
                                                                <span class="btn-light" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-reforestacion'; ?>" data-bs-slide="next">
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
                                </div>                                             
                                <?php }?>   
                                <!-- Contenido primera actividad -->

                                <!-- Contenido segunda actividad warning  imagenes_actividad_dos texto_actividad_dos -->
                                <?php if(!empty($logo['texto_actividad_dos']) && !empty($logo['imagenes_actividad_dos'])){?>
                                <?php $matenimiento_id = sanitize_title($logo['nombre_logo'] .'-actividad-dos' );?>
                                <div class="content-collapse mantenimiento-content contenido-actividad" id="<?php echo $empresa_id . '-btn-mantenimiento';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                                <p class="text-black-50"><?php echo $logo['texto_actividad_dos']; ?></p>
                                        </div>
                                        <!-- Carrusel de imagenes de mantenimiento  -->
                                        <div class="col-12 col-lg-6">
                                            <!-- Carrusel de imágenes de mantenimiento -->
                                            <div id="<?php echo $empresa_id . '-carrusel-mantenimiento'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($logo['imagenes_actividad_dos']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img src="<?php echo $imagen; ?>" class="img-fluid image-first-modal" data-bs-toggle="modal" data-bs-target="#<?php echo $matenimiento_id; ?>-Modal" id="<?php echo $matenimiento_id; ?>-Galery" alt="Imagen <?php echo $index + 1; ?>"> 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-warning border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-mantenimiento'; ?>" data-bs-slide="prev">
                                                                <span class="btn-warning" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-warning border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-mantenimiento'; ?>" data-bs-slide="next">
                                                                <span class="btn-warning" aria-hidden="true">
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
                                </div> 
                                <?php } ?>
                                <!-- Contenido segunda actividad -->

                                <!-- Contenido tercera actividad danger  imagenes_actividad_tres texto_actividad_tres -->
                                <?php if(!empty($logo['texto_actividad_tres']) && !empty($logo['imagenes_actividad_tres'])){?>
                                <?php $brechas_id = sanitize_title($logo['nombre_logo'] .'-actividad-tres' );?>
                                <div class="content-collapse brechas-content contenido-actividad" id="<?php echo $empresa_id . '-btn-brechas';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                            <p class="text-black-50"><?php echo $logo['texto_actividad_tres']; ?></p>
                                        </div>
                                        <!-- Carrusel de imagenes de reforestación  -->
                                        <div class="col-12 col-lg-6">

                                            <!-- Carrusel de imágenes de reforación -->
                                            <div id="<?php echo $empresa_id . '-carrusel-brechas'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($logo['imagenes_actividad_tres']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img src="<?php echo $imagen; ?>" class="img-fluid image-first-modal" data-bs-toggle="modal" data-bs-target="#<?php echo $brechas_id; ?>-Modal" alt="Imagen <?php echo $index + 1; ?>"> 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-danger border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-brechas'; ?>" data-bs-slide="prev">
                                                                <span class="btn-danger" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-danger border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-brechas'; ?>" data-bs-slide="next">
                                                                <span class="btn-danger" aria-hidden="true">
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
                                            <!-- ./Carrusel de imágenes de reforación -->

                                        </div>
                                    </div>                                              
                                </div>
                                <?php }?>                                 
                                <!-- Contenido tercera actividad -->

                                <!-- Contenido cuarta actividad  imagenes_actividad_cuatro texto_actividad_cuatro -->
                                <?php if(!empty($logo['texto_actividad_cuatro']) && !empty($logo['imagenes_actividad_cuatro'])){?>
                                <?php $actividades_uno_id = sanitize_title($logo['nombre_logo'].'-actividad-cuatro' );?>
                                <div class="content-collapse actividades-uno-content contenido-actividad" id="<?php echo $empresa_id . '-btn-actividades-uno';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                            <p class="text-black-50"><?php echo $logo['texto_actividad_cuatro']; ?></p>
                                        </div>
                                        <!-- Carrusel de imagenes de reforestación  -->
                                        <div class="col-12 col-lg-6">
                                            <!-- Carrusel de imágenes de reforación -->
                                            <div id="<?php echo $empresa_id . '-carrusel-actividades-uno'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($logo['imagenes_actividad_cuatro']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img src="<?php echo $imagen; ?>" class="img-fluid image-first-modal" data-bs-toggle="modal" data-bs-target="#<?php echo $actividades_uno_id; ?>-Modal" alt="Imagen <?php echo $index + 1; ?>" > 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-success border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-actividades-uno'; ?>" data-bs-slide="prev">
                                                                <span class="btn-success" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-success border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-actividades-uno'; ?>" data-bs-slide="next">
                                                                <span class="btn-success" aria-hidden="true">
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
                                            <!-- ./ Carrusel de imágenes de reforación -->
                                        </div>
                                    </div>                                         
                                </div>
                                <?php }?> 
                                <!-- Contenido cuarta actividad -->

                                <!-- Contenido quinta actividad  imagenes_actividad_cinco texto_actividad_cinco -->
                                <?php if(!empty($logo['texto_actividad_cinco']) && !empty($logo['imagenes_actividad_cinco'])){?>
                                <?php $actividades_dos_id = sanitize_title($logo['nombre_logo'] .'-actividad-cinco' );?>
                                <div class="content-collapse actividades-dos-content contenido-actividad" id="<?php echo $empresa_id . '-btn-actividades-dos';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                            <p class="text-black-50"><?php echo $logo['texto_actividad_cinco']; ?></p>
                                        </div>
                                        <!-- Carrusel de imagenes de reforestación  -->
                                        <div class="col-12 col-lg-6">
                                            <!-- Carrusel de imágenes de reforación -->
                                            <div id="<?php echo $empresa_id . '-carrusel-actividades-dos'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($logo['imagenes_actividad_cinco']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img src="<?php echo $imagen; ?>" class="img-fluid image-first-modal" data-bs-toggle="modal" data-bs-target="#<?php echo $actividades_dos_id; ?>-Modal" id="<?php echo $actividades_dos_id; ?>-Galery" alt="Imagen <?php echo $index + 1; ?>"> 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-info border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-actividades-dos'; ?>" data-bs-slide="prev">
                                                                <span class="btn-info" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-info border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-actividades-dos'; ?>" data-bs-slide="next">
                                                                <span class="btn-info" aria-hidden="true">
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
                                          
                                </div>
                                <?php }?>                                 
                                <!-- Contenido quinta actividad -->
                            </div>  
                            <!-- Contenido actividad -->
                            
                        </div>
                        <!-- ./Secciones de actividades empresas -->
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" id="cerrarPopup">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>                
        <!-- ./Primer modal empresas -->

        <!-- Segundo modal empresas light imagenes_actividad_uno texto_actividad_uno -->
        <?php if(!empty($logo['texto_actividad_uno']) && !empty($logo['imagenes_actividad_uno']) && !empty($logo['fecha_actividad_uno_A'])){?>
            <?php 
                $reforestacion_id = sanitize_title($logo['nombre_logo'] .'-actividad-uno' );

                $imagenes_reforestacion = array_reverse($logo['imagenes_actividad_uno']);
                $nueva_imagenes_reforestacion = array_combine(range(1, count($imagenes_reforestacion)), array_values($imagenes_reforestacion)); // nuevo array de imgs
                $total_imagenes = count($imagenes_reforestacion); // Obtener el total de imágenes    

                $fechaPrimeraActividad = $logo['fecha_actividad_uno_A'];
                $fechaFormateadaUno = formatear_fecha($fechaPrimeraActividad, $meses_es);
            ?>
            <!-- Second modal with bootstrap -->
            <div class="modal fade" id="<?php echo $reforestacion_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $reforestacion_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content"> 
                        <div class="modal-header">
                            <button type="button" class="btn-close text-danger" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal" aria-label="Close"></button>
                        </div>                     
                        <div class="modal-body">
                            <div class="container">
                                <h3 class="text-center text-light fw-light">
                                    <span><?php echo $logo['titulo_actividad_uno']; ?></span> 
                                    </br> 
                                    <span class=""><?php echo $fechaFormateadaUno; ?></span>
                                </h3>
                            </div>

                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:30px;">
                                <div class="row justify-content-center align-items-center">

                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-principal" id="modalEmpresaUno-ImageContent">
                                        <!-- Segundo carrusel imágenes -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-reforestacion'; ?>" 
                                             class="carousel carousel-dark slide carousel-fade common-carousel" 
                                             data-bs-ride="carousel">

                                            <!-- Índice carrusel -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_reforestacion as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-reforestacion'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" 
                                                        class="<?php echo $index == 1 ? 'active' : ''; ?>" 
                                                        aria-current="true" style="background-color:#65b492!important;">
                                                    </button>
                                                <?php } ?>
                                            </div>

                                            <!-- Imágenes del carrusel -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_reforestacion as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?> ">
                                                        <div class="d-flex justify-content-center image-second-carrusel">
                                                            <img class="img-fluid image-second-modal" src="<?php echo $imagen; ?>" 
                                                                alt="Imagen <?php echo $empresa_id; ?>" 
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
                                        <!-- Segundo carrusel imágenes -->
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-reforestacion'; ?>" 
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

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-reforestacion'; ?>" 
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
                            <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Second modal with bootstrap -->    
        <?php }?>    
        <!-- Contenido primera actividad -->

        <!-- Contenido segunda actividad warning imagenes_actividad_dos texto_actividad_dos -->
        <?php if(!empty($logo['texto_actividad_dos']) && !empty($logo['imagenes_actividad_dos']) && !empty($logo['fecha_actividad_dos_A'])){?>
            <?php 
                $matenimiento_id = sanitize_title($logo['nombre_logo'] .'-actividad-dos');

                $imagenes_actividad = array_reverse($logo['imagenes_actividad_dos']);
                $nueva_imagenes_actividad = array_combine(range(1, count($imagenes_actividad)), array_values($imagenes_actividad)); // nuevo array de imgs
                $total_imagenes = count($imagenes_actividad); // Obtener el total de imágenes 

                $fechaPrimeraActividad = $logo['fecha_actividad_dos_A'];
                $fechaFormateadaUno = formatear_fecha($fechaPrimeraActividad, $meses_es);

            ?>
            <!-- Second modal -->
            <div class="modal fade" id="<?php echo $matenimiento_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $matenimiento_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close text-dasnger" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal" aria-label="Close"></button>
                        </div>                        
                        <div class="modal-body">
                            <div class="container">
                                <h3 class="text-center text-warning fw-light">
                                    <span><?php echo $logo['titulo_actividad_dos']; ?></span> 
                                    </br> 
                                    <span class=""><?php echo $fechaFormateadaUno; ?></span>
                                </h3>
                            </div>                            
                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:30px;">
                                <div class="row justify-content-center align-items-center">
                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-mantenimiento" id="modalEmpresaUno-ImageContent">
                                        <!-- Segundo carrusel imágenes -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-mantenimiento'; ?>" class="carousel carousel-dark slide carousel-fade common-carousel" data-bs-ride="carousel">
                                            <!-- Índice carrusel -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-mantenimiento'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" 
                                                        class="<?php echo $index == 1 ? 'active' : ''; ?>" 
                                                        aria-current="true" 
                                                        style="background-color:#f5d138!important;"
                                                    >
                                                    </button>
                                                <?php } ?>
                                            </div>

                                            <!-- Imágenes del carrusel -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                                        <div class="d-flex justify-content-center image-second-carrusel" style="margin-bottom:30px!important;">
                                                            <img 
                                                                class="img-fluid image-second-modal" 
                                                                src="<?php echo $imagen; ?>" 
                                                                alt="Imagen <?php echo $empresa_id; ?>"
                                                                data-index-image="<?php echo $index;?>"
                                                            >
                                                        </div>

                                                        <div class="content_index_images mt-3">
                                                            <h5 class="text-center text-warning">
                                                                <span class="mb-3 index_active"><?php echo $index; ?></span> / 
                                                                <span class="mb-3 total_images"><?php echo $total_imagenes; ?></span> 
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Segundo carrusel imágenes -->
                                    </div>

                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-warning border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-mantenimiento'; ?>" 
                                            data-bs-slide="prev" 
                                            >
                                            <span class="btn-warning" aria-hidden="true">
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

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-warning border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-mantenimiento'; ?>" 
                                            data-bs-slide="next">
                                            <span  class="btn-warning" aria-hidden="true">
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
                            <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Second modal -->    
        <?php }?>
        <!-- Contenido segunda actividad -->

        <!-- Contenido tercera actividad danger imagenes_actividad_tres texto_actividad_tres  -->
        <?php if(!empty($logo['texto_actividad_tres']) && !empty($logo['imagenes_actividad_tres']) && !empty($logo['fecha_actividad_tres_A'])){ ?>
            <?php 
                $brechas_id = sanitize_title($logo['nombre_logo'] .'-actividad-tres' );

                $imagenes_actividad = array_reverse($logo['imagenes_actividad_tres']);
                $nueva_imagenes_actividad = array_combine(range(1, count($imagenes_actividad)), array_values($imagenes_actividad)); // nuevo array de imgs
                $total_imagenes = count($imagenes_actividad); // Obtener el total de imágenes    

                $fechaPrimeraActividad = $logo['fecha_actividad_tres_A'];
                $fechaFormateadaUno = formatear_fecha($fechaPrimeraActividad, $meses_es);
            ?>
            <!-- Second modal -->
            <div class="modal fade" id="<?php echo $brechas_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $brechas_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content"> 
                        <div class="modal-header">
                            <button type="button" class="btn-close text-danger" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal" aria-label="Close"></button>
                        </div>                       
                        <div class="modal-body">
                            <div class="container">
                                <h3 class="text-center text-danger fw-light">
                                    <span><?php echo $logo['titulo_actividad_tres']; ?></span> 
                                    </br> 
                                    <span class=""><?php echo $fechaFormateadaUno; ?></span>
                                </h3>
                            </div>  

                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:60px;">
                                <div class="row justify-content-center align-items-center">
                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-brechas" id="modalEmpresaUno-ImageContent">
                                        <!-- Carrusel -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-brechas'; ?>" class="carousel carousel-dark slide carousel-fade common-carousel" data-bs-ride="carousel">
                                            <!-- Índice -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-brechas'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" 
                                                        class="<?php echo $index == 1 ? 'active' : ''; ?>" 
                                                        aria-current="true" 
                                                        style="background-color:#ec432c!important;">
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <!-- Imágenes -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                                        <div class="d-flex justify-content-center image-second-carrusel">
                                                            <img 
                                                                class="img-fluid image-second-modal" 
                                                                src="<?php echo $imagen; ?>" 
                                                                alt="Imagen <?php echo $empresa_id?>" 
                                                                data-index-image="<?php echo $index;?>"
                                                            >
                                                        </div>
                                                        <div class="content_index_images mt-3">
                                                            <h5 class="text-center text-danger">
                                                                <span class="mb-3 index_active"><?php echo $index; ?></span> / 
                                                                <span class="mb-3 total_images"><?php echo $total_imagenes; ?></span> 
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Carrusel -->
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                                class="carousel-control p-2 bg-danger border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                                type="button" 
                                                data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-brechas'; ?>" 
                                                data-bs-slide="prev"
                                                >
                                            <span class="btn-danger" aria-hidden="true">
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

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-danger border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-brechas'; ?>" 
                                            data-bs-slide="next">

                                            <span class="btn-danger" aria-hidden="true">
                                                <svg 
                                                    xmlns="http://www.w3.org/2000/svg" 
                                                    width="20" 
                                                    height="20" 
                                                    fill="currentColor" 
                                                    class="bi bi-arrow-right" 
                                                    viewBox="0 0 16 16">
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
                            <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Second modal -->    
        <?php }?>
        <!-- Contenido tercera actividad -->

        <!-- Contenido cuarta actividad success imagenes_actividad_cuatro texto_actividad_cuatro -->
        <?php if(!empty($logo['texto_actividad_cuatro']) && !empty($logo['imagenes_actividad_cuatro']) && !empty($logo['fecha_actividad_cuatro_A'])){?>
            <?php 
                $actividades_uno_id = sanitize_title($logo['nombre_logo'].'-actividad-cuatro' );

                $imagenes_actividad = array_reverse($logo['imagenes_actividad_cuatro']);
                $nueva_imagenes_actividad = array_combine(range(1, count($imagenes_actividad)), array_values($imagenes_actividad)); // nuevo array de imgs
                $total_imagenes = count($imagenes_actividad); // Obtener el total de imágenes  

                $fechaPrimeraActividad = $logo['fecha_actividad_cuatro_A'];
                $fechaFormateadaUno = formatear_fecha($fechaPrimeraActividad, $meses_es);
            ?>
            <!-- Second modal -->
            <div class="modal fade" id="<?php echo $actividades_uno_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $actividades_uno_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close text-danger" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal" aria-label="Close"></button>
                        </div>                        
                        <div class="modal-body">
                            <div class="container">
                                <h3 class="text-center text-success fw-light">
                                    <span><?php echo $logo['titulo_actividad_cuatro']; ?></span> 
                                    </br> 
                                    <span class=""><?php echo $fechaFormateadaUno; ?></span>
                                </h3>
                            </div>

                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:60px;">
                                <div class="row justify-content-center align-items-center">
                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-brechas" id="modalEmpresaUno-ImageContent">
                                        <!-- Segundo carrusel imágenes -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-actividad-uno'; ?>" class="carousel carousel-dark slide carousel-fade common-carousel" data-bs-ride="carousel">
                                            <!-- Índice carrusel -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-uno'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" class="<?php echo $index == 1 ? 'active' : ''; ?>" 
                                                        aria-current="true" style="background-color:#94ce58!important;">
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <!-- Imágenes del carrusel -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                                        <div class="d-flex justify-content-center image-second-carrusel">
                                                            <img 
                                                                class="img-fluid image-second-modal" 
                                                                src="<?php echo $imagen; ?>" 
                                                                alt="Imagen <?php echo $empresa_id; ?>" 
                                                                data-index-image="<?php echo $index; ?>"
                                                            >
                                                        </div>
                                                        <div class="content_index_images mt-3">
                                                            <h5 class="text-center text-success">
                                                                <span class="mb-3 index_active"><?php echo $index; ?></span> / 
                                                                <span class="mb-3 total_images"><?php echo $total_imagenes; ?></span> 
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Segundo carrusel imágenes -->
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-success border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-uno'; ?>" 
                                            data-bs-slide="prev">
                                            <span class="btn-success" aria-hidden="true">
                                                <svg 
                                                    xmlns="http://www.w3.org/2000/svg" 
                                                    width="20"
                                                    height="20" 
                                                    fill="currentColor" 
                                                    class="bi bi-arrow-left" 
                                                    viewBox="0 0 16 16">
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

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-success border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-uno'; ?>" 
                                            data-bs-slide="next"
                                            >
                                            <span class="btn-success" aria-hidden="true">
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
                            <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Second modal-->    
        <?php }?>
        <!-- Contenido cuarta actividad -->

        <!-- Contenido quinta actividad info imagenes_actividad_cinco texto_actividad_cinco -->
        <?php if(!empty($logo['texto_actividad_cinco']) && !empty($logo['imagenes_actividad_cinco']) && !empty($logo['fecha_actividad_cinco_A'])){?>
            <?php 
                $actividades_dos_id = sanitize_title($logo['nombre_logo'] .'-actividad-cinco' );

                $imagenes_actividad = array_reverse($logo['imagenes_actividad_cinco']);
                $nueva_imagenes_actividad = array_combine(range(1, count($imagenes_actividad)), array_values($imagenes_actividad)); // nuevo array de imgs
                $total_imagenes = count($imagenes_actividad); // Obtener el total de imágenes

                $fechaPrimeraActividad = $logo['fecha_actividad_cinco_A'];
                $fechaFormateadaUno = formatear_fecha($fechaPrimeraActividad, $meses_es);
            ?>
            <div class="modal fade" id="<?php echo $actividades_dos_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $actividades_dos_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">   
                        <div class="modal-header">
                            <button type="button" class="btn-close text-danger" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal" aria-label="Close"></button>
                        </div>                   
                        <div class="modal-body">

                            <div class="container">
                                <h3 class="text-center text-info fw-light">
                                    <span><?php echo $logo['titulo_actividad_cinco']; ?></span> 
                                    </br> 
                                    <span class=""><?php echo $fechaFormateadaUno; ?></span>
                                </h3>
                            </div>
                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:60px;">
                                <div class="row justify-content-center align-items-center">
                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-brechas" id="modalEmpresaUno-ImageContent">
                                        <!-- Segundo carrusel imágenes -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-actividad-dos'; ?>" class="carousel carousel-dark slide carousel-fade common-carousel" data-bs-ride="carousel">
                                            <!-- Índice carrusel -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-dos'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" class="<?php echo $index == 1 ? 'active' : ''; ?>"
                                                        aria-current="true" style="background-color:#4674c1!important;"
                                                        >
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <!-- Imágenes del carrusel -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                                        <div class="d-flex justify-content-center image-second-carrusel">
                                                            <img 
                                                                class="img-fluid image-second-modal" 
                                                                src="<?php echo $imagen; ?>"
                                                                alt="Imagen <?php echo $empresa_id; ?>" 
                                                                data-index-image="<?php echo $index;?>"
                                                            >
                                                        </div>
                                                        <div class="content_index_images mt-3">
                                                            <h5 class="text-center text-info">
                                                                <span class="mb-3 index_active"><?php echo $index; ?></span> / 
                                                                <span class="mb-3 total_images"><?php echo $total_imagenes; ?></span> 
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Segundo carrusel imágenes -->
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-info border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-dos'; ?>" 
                                            data-bs-slide="prev"
                                            >
                                            <span class="btn-info" aria-hidden="true">
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

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-info border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-dos'; ?>" 
                                            data-bs-slide="next"
                                            >
                                            <span class="btn-info" aria-hidden="true">
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
                            <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>            
        <?php }?>
        <!-- Contenido quinta actividad -->

        <?php }?>
    <?php }?>               
    <!-- Modal Empresas A  -->


    <!-- Logos empresas B -->
    <section class="container-xxl my-5">    
        <div class="wrapper logos-empresas-b">
            <div class="wrapper-list owl-carousel owl-carousel-empresas d-grid align-items-center">
                <?php 
                $empresas = get_post_meta(get_the_ID(), 'reforestamos_group_seccion_empresas_b', true);
                if (!empty($empresas)) {
                    foreach ($empresas as $empresa) {
                        $tituloEmpresa = $empresa['nombre_empresa']; 
                        $empresa_id = sanitize_title($empresa['nombre_empresa']); 
                        $condicionModal = (!empty($empresa['titulo_reforestacion']) &&  
                                        !empty($empresa['texto_reforestacion']) &&  
                                        !empty($empresa['imagenes_reforestacion']) &&  
                                        !empty($empresa['fecha_actividad_uno'])) 
                                        ? 'data-bs-toggle="modal" data-bs-target="#' . $empresa_id . 'Modal"' 
                                        : ''; 

                        $cursorPointerCondicion = (!empty($empresa['nombre_empresa']) &&  
                                                !empty($empresa['titulo_reforestacion']) &&  
                                                !empty($empresa['texto_reforestacion'])) 
                                                ? 'cursor-pointer' 
                                                : ''; 

                        $anchoCondicion = !empty($empresa['medida_ancho_imagen']) ?  $empresa['medida_ancho_imagen'] : ''; 
                        $altoCondicion = !empty($empresa['medida_largo_imagen']) ?  $empresa['medida_largo_imagen'] : ''; 
                        $colorCondicion = !empty($empresa['fondo_logo']) ?  $empresa['fondo_logo'] : ''; 
                    ?>
                        <div class="slide-item w-100 d-grid align-items-center justify-content-center cursor-pointer"> 
                            <div class="d-grid align-items-center justify-content-center content-logos-b my-auto">
                                <img 
                                    class="figure-img img-fluid aliados-b <?php echo $cursorPointerCondicion; ?>"  
                                    
                                    <?php echo $condicionModal; ?> 

                                    src="<?php echo $empresa['logo_empresa_b']; ?>" 

                                    alt="Logo <?php echo $empresa['nombre_empresa']; ?>" 

                                    style="width: <?php echo $anchoCondicion; ?>px!important; 
                                        height: <?php echo $altoCondicion; ?>px; 
                                        margin-top: <?php echo $empresa['margen_imagen']; ?>px; 
                                        background-color: <?php echo $colorCondicion; ?>;"
                                />  
                            </div>
                        </div> 
                        <?php
                    }
                }?>
            </div>
        </div>
    </section>
    <!-- Logos empresas B -->

    <!-- Modal Empresas B -->
    <?php if(!empty($empresas)) { ?>
        <?php foreach($empresas as $key => $empresa) { ?>
        <?php $empresa_id = sanitize_title($empresa['nombre_empresa']); ?>

        <!-- Primer modal empresas -->
        <div class="modal fade" id="<?php echo $empresa_id; ?>Modal" tabindex="-1" aria-labelledby="<?php echo $empresa_id; ?>ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h1 class="text-center text-light h4" id="<?php echo $empresa_id; ?>ModalLabel"><?php echo $tituloEmpresa = $empresa['nombre_empresa']; ?></h1>
                    </div>

                    <div class="modal-body">
                        <!-- Secciones de actividades empresas -->
                        <div class="container">
                            <!-- Botones actividades de empresas -->
                            <div class="row row-cols-1 row-cols-md-3 justify-content-center align-items-center my-3">
                                <!-- Boton reforestación light -->
                                <?php  if(!empty($empresa['titulo_reforestacion'])) { ?>
                                    <div class="col w-auto"> 
                                        <p class="btn btn-outline-light btn-actividad actividad-uno <?php echo sanitize_title($empresa['titulo_reforestacion']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-reforestacion';?>">
                                            <?php echo $empresa['titulo_reforestacion']; ?>
                                        </p>
                                    </div>
                                <?php } ?>

                                <!-- Boton Matenimiento warning -->
                                <?php if(!empty($empresa['titulo_mantenimiento'])) { ?>
                                    <div class="col w-auto">
                                        <p class="btn btn-outline-warning btn-actividad actividad-dos <?php echo sanitize_title($empresa['titulo_mantenimiento']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-mantenimiento';?>">
                                            <?php echo $empresa['titulo_mantenimiento']; ?>
                                        </p>
                                    </div>
                                <?php } ?>     

                                <!-- Botón Brechas Cortafuego danger -->
                                <?php  if(!empty($empresa['titulo_brechas'])) { ?>
                                    <div class="col w-auto">
                                        <p class="btn btn-outline-danger btn-actividad brechas-cortafuego-empresa actividad-tres <?php echo sanitize_title($empresa['titulo_brechas']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-brechas';?>">
                                            <?php echo $empresa['titulo_brechas']; ?>
                                        </p>
                                    </div>
                                <?php } ?>          
                                
                                <!-- Bóton otras actividades 1 success -->
                                <?php  if(!empty($empresa['titulo_otras_asctividades'])) { ?>
                                    <div class="col w-auto">
                                        <p class="btn btn-outline-success btn-actividad actividades-uno-empresa actividad-cuatro <?php echo sanitize_title($empresa['titulo_otras_asctividades']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-actividades-uno';?>">
                                            <?php echo $empresa['titulo_otras_asctividades']; ?>
                                        </p>
                                    </div>
                                <?php } ?>         
                                
                                <!-- Botón otras actividades 2 info -->
                                <?php  if(!empty($empresa['titulo_otras_asctividades_dos'])) { ?>
                                    <div class="col w-auto">
                                        <p class="btn btn-outline-info btn-actividad actividades-dos-empresa actividad-cinco <?php echo sanitize_title($empresa['titulo_otras_asctividades_dos']) . '-empresa';?>"  data-target="<?php echo $empresa_id . '-btn-actividades-dos';?>">
                                            <?php echo $empresa['titulo_otras_asctividades_dos']; ?>
                                        </p>
                                    </div>
                                <?php } ?>                                                
                            </div>   
                            <!-- ./Botones actividades de empresas -->

                            <!-- Imgs del primer pop-up -->
                            <div class="row act-emp">
                                <!-- Contenido primera actividad -->
                                <?php if(!empty($empresa['texto_reforestacion']) && !empty($empresa['imagenes_reforestacion'])){?>
                                <?php $reforestacion_id = sanitize_title($empresa['nombre_empresa'] .'-actividad-uno' );?>

                                <div class="content-collapse reforestacion-content contenido-actividad" id="<?php echo $empresa_id . '-btn-reforestacion';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                            <p class="text-black-50"><?php echo $empresa['texto_reforestacion']; ?></p>
                                        </div>

                                        <!-- Carrusel de imagenes de reforestación  -->
                                        <div class="col-12 col-lg-6">
                                            <!-- Carrusel de imágenes de reforación -->
                                            <div id="<?php echo $empresa_id . '-carrusel-reforestacion'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($empresa['imagenes_reforestacion']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img class="img-fluid image-first-modal" src="<?php echo $imagen; ?>" data-bs-toggle="modal" data-bs-target="#<?php echo $reforestacion_id; ?>-Modal" id="<?php echo $reforestacion_id; ?>-Galery" alt="Imagen <?php echo $index + 1; ?>"> 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-reforestacion'; ?>" data-bs-slide="prev">
                                                                <span class="btn-light" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-reforestacion'; ?>" data-bs-slide="next">
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
                                </div>                                             
                                <?php }?>   
                                <!-- Contenido primera actividad -->

                                <!-- Contenido segunda actividad warning -->
                                <?php if(!empty($empresa['texto_matenimiento']) && !empty($empresa['imagenes_mantenimiento'])){?>
                                <?php $matenimiento_id = sanitize_title($empresa['nombre_empresa'] .'-actividad-dos' );?>
                                <div class="content-collapse mantenimiento-content contenido-actividad" id="<?php echo $empresa_id . '-btn-mantenimiento';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                                <p class="text-black-50"><?php echo $empresa['texto_matenimiento']; ?></p>
                                        </div>
                                        <!-- Carrusel de imagenes de mantenimiento  -->
                                        <div class="col-12 col-lg-6">
                                            <!-- Carrusel de imágenes de mantenimiento -->
                                            <div id="<?php echo $empresa_id . '-carrusel-mantenimiento'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($empresa['imagenes_mantenimiento']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img src="<?php echo $imagen; ?>" class="img-fluid image-first-modal" data-bs-toggle="modal" data-bs-target="#<?php echo $matenimiento_id; ?>-Modal" id="<?php echo $matenimiento_id; ?>-Galery" alt="Imagen <?php echo $index + 1; ?>"> 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-warning border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-mantenimiento'; ?>" data-bs-slide="prev">
                                                                <span class="btn-warning" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-warning border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-mantenimiento'; ?>" data-bs-slide="next">
                                                                <span class="btn-warning" aria-hidden="true">
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
                                </div> 
                                <?php } ?>
                                <!-- Contenido segunda actividad -->

                                <!-- Contenido tercera actividad danger-->
                                <?php if(!empty($empresa['texto_brechas']) && !empty($empresa['imagenes_brechas'])){?>
                                <?php $brechas_id = sanitize_title($empresa['nombre_empresa'] .'-actividad-tres' );?>
                                <div class="content-collapse brechas-content contenido-actividad" id="<?php echo $empresa_id . '-btn-brechas';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                            <p class="text-black-50"><?php echo $empresa['texto_brechas']; ?></p>
                                        </div>
                                        <!-- Carrusel de imagenes de reforestación  -->
                                        <div class="col-12 col-lg-6">

                                            <!-- Carrusel de imágenes de reforación -->
                                            <div id="<?php echo $empresa_id . '-carrusel-brechas'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($empresa['imagenes_brechas']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img src="<?php echo $imagen; ?>" class="img-fluid image-first-modal" data-bs-toggle="modal" data-bs-target="#<?php echo $brechas_id; ?>-Modal" alt="Imagen <?php echo $index + 1; ?>"> 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-danger border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-brechas'; ?>" data-bs-slide="prev">
                                                                <span class="btn-danger" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-danger border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-brechas'; ?>" data-bs-slide="next">
                                                                <span class="btn-danger" aria-hidden="true">
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
                                            <!-- ./Carrusel de imágenes de reforación -->
                                        </div>
                                    </div>                                              
                                </div>
                                <?php }?>                                 
                                <!-- Contenido tercera actividad -->

                                <!-- Contenido cuarta actividad -->
                                <?php if(!empty($empresa['texto_otras_actividades']) && !empty($empresa['imagenes_otras_actividades'])){?>
                                <?php $actividades_uno_id = sanitize_title($empresa['nombre_empresa'].'-actividad-cuatro' );?>
                                <div class="content-collapse actividades-uno-content contenido-actividad" id="<?php echo $empresa_id . '-btn-actividades-uno';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                            <p class="text-black-50"><?php echo $empresa['texto_otras_actividades']; ?></p>
                                        </div>
                                        <!-- Carrusel de imagenes de reforestación  -->
                                        <div class="col-12 col-lg-6">
                                            <!-- Carrusel de imágenes de reforación -->
                                            <div id="<?php echo $empresa_id . '-carrusel-actividades-uno'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($empresa['imagenes_otras_actividades']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img src="<?php echo $imagen; ?>" class="img-fluid image-first-modal" data-bs-toggle="modal" data-bs-target="#<?php echo $actividades_uno_id; ?>-Modal" alt="Imagen <?php echo $index + 1; ?>" > 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-success border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-actividades-uno'; ?>" data-bs-slide="prev">
                                                                <span class="btn-success" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-success border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-actividades-uno'; ?>" data-bs-slide="next">
                                                                <span class="btn-success" aria-hidden="true">
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
                                            <!-- ./ Carrusel de imágenes de reforación -->
                                        </div>
                                    </div>                                         
                                </div>
                                <?php }?> 
                                <!-- Contenido cuarta actividad -->

                                <!-- Contenido quinta actividad -->
                                <?php if(!empty($empresa['texto_otras_actividades_dos']) && !empty($empresa['imagenes_otras_actividades_dos'])){?>
                                <?php $actividades_dos_id = sanitize_title($empresa['nombre_empresa'] .'-actividad-cinco' );?>
                                <div class="content-collapse actividades-dos-content contenido-actividad" id="<?php echo $empresa_id . '-btn-actividades-dos';?>">
                                    <div class="row align-items-center acciones_empresas">
                                        <div class="col-12 col-lg-6">
                                            <p class="text-black-50"><?php echo $empresa['texto_otras_actividades_dos']; ?></p>
                                        </div>
                                        <!-- Carrusel de imagenes de reforestación  -->
                                        <div class="col-12 col-lg-6">
                                            <!-- Carrusel de imágenes de reforación -->
                                            <div id="<?php echo $empresa_id . '-carrusel-actividades-dos'; ?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php foreach(array_reverse($empresa['imagenes_otras_actividades_dos']) as $index => $imagen) { ?>
                                                        <div class="carousel-item  <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <div class="d-flex justify-content-center content-carrusel">
                                                                <img src="<?php echo $imagen; ?>" class="img-fluid image-first-modal" data-bs-toggle="modal" data-bs-target="#<?php echo $actividades_dos_id; ?>-Modal" id="<?php echo $actividades_dos_id; ?>-Galery" alt="Imagen <?php echo $index + 1; ?>"> 
                                                            </div>
                                                            
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <!-- Contenedor btns  -->
                                                <div class="container d-flex justify-content-center btns-first-carrusel">
                                                    <!-- Botón prev -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-info border-0 rounded-2 shadow-sm btn-first-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-actividades-dos'; ?>" data-bs-slide="prev">
                                                                <span class="btn-info" aria-hidden="true">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                                    </svg>
                                                                </span>
                                                                <span class="visually-hidden">Anterior</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Botón next -->
                                                    <div class="row d-flex justify-content-center align-items-center mx-1">
                                                        <div class="col">
                                                            <button class="carousel-control p-2 bg-info border-0 rounded-2 shadow-sm btn-first-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-carrusel-actividades-dos'; ?>" data-bs-slide="next">
                                                                <span class="btn-info" aria-hidden="true">
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
                                          
                                </div>
                                <?php }?>                                 
                                <!-- Contenido quinta actividad -->
                            </div>  
                            
                        </div>
                        <!-- ./Secciones de actividades empresas -->
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" id="cerrarPopup">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>                
        <!-- ./Primer modal empresas -->

        <!-- Segundo modal empresas light-->
        <?php if(!empty($empresa['texto_reforestacion']) && !empty($empresa['imagenes_reforestacion']) && !empty($empresa['fecha_actividad_uno'])){ ?>
            <?php 
                $reforestacion_id = sanitize_title($empresa['nombre_empresa'] .'-actividad-uno' );
                $imagenes_reforestacion = array_reverse($empresa['imagenes_reforestacion']);
                $nueva_imagenes_reforestacion = array_combine(range(1, count($imagenes_reforestacion)), array_values($imagenes_reforestacion)); // nuevo array de imgs
                $total_imagenes = count($imagenes_reforestacion); // Obtener el total de imágenes

                $fechaPrimeraActividad = $empresa['fecha_actividad_uno'];
                $fechaFormateadaUno = formatear_fecha($fechaPrimeraActividad, $meses_es);
            ?>
            <div class="modal fade" id="<?php echo $reforestacion_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $reforestacion_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content"> 
                        <div class="modal-header">
                            <button type="button" class="btn-close text-light" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal" aria-label="Close"></button>
                        </div>  

                        <div class="modal-body">
                            <div class="container">
                                <h3 class="text-center text-light fw-light">
                                    <span><?php echo $empresa['titulo_reforestacion']; ?></span> 
                                    </br> 
                                    <span class=""><?php echo $fechaFormateadaUno; ?></span>
                                </h3>
                            </div>

                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:30px;">
                                <div class="row justify-content-center align-items-center">

                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-principal" id="modalEmpresaUno-ImageContent">
                                        <!-- Segundo carrusel imágenes -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-reforestacion'; ?>" class="carousel carousel-dark slide carousel-fade common-carousel" data-bs-ride="carousel">
                                            <!-- Índice carrusel -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_reforestacion as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-reforestacion'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" class="<?php echo $index == 1 ? 'active' : ''; ?>" 
                                                        aria-current="true" style="background-color:#65b492!important;">
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <!-- Imágenes del carrusel -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_reforestacion as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?> ">
                                                        <div class="d-flex justify-content-center image-second-carrusel">
                                                            <img class="img-fluid image-second-modal" src="<?php echo $imagen; ?>" 
                                                                alt="Imagen <?php echo $empresa_id; ?>" 
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
                                        <!-- Segundo carrusel imágenes -->
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-reforestacion'; ?>" 
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

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-light border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-reforestacion'; ?>" 
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
                            <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div> 
        <?php }?>    
        <!-- Contenido primera actividad -->

        <!-- Contenido segunda actividad warning -->
        <?php if(!empty($empresa['texto_matenimiento']) && !empty($empresa['imagenes_mantenimiento']) && !empty($empresa['fecha_actividad_dos'])){?>
            <?php 
                $matenimiento_id = sanitize_title($empresa['nombre_empresa'] .'-actividad-dos');
                $imagenes_actividad = array_reverse($empresa['imagenes_mantenimiento']);
                $nueva_imagenes_actividad = array_combine(range(1, count($imagenes_actividad)), array_values($imagenes_actividad)); // nuevo array de imgs
                $total_imagenes = count($imagenes_actividad); // Obtener el total de imágenes

                $fechaSegundaActividad = $empresa['fecha_actividad_dos'];
                $fechaFormateadaDos = formatear_fecha($fechaSegundaActividad, $meses_es);
            ?>
            <div class="modal fade" id="<?php echo $matenimiento_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $matenimiento_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button 
                                type="button" 
                                class="btn-close text-light" 
                                data-bs-toggle="modal" 
                                data-bs-target="#<?php echo $empresa_id; ?>Modal" 
                                aria-label="Close"
                            >
                            </button>
                        </div>                        
                        <div class="modal-body">
                            <div class="container">
                                <h3 class="text-center text-warning fw-light"> 
                                    <span class=""><?php echo $empresa['titulo_mantenimiento']; ?></span> 
                                    </br> 
                                    <span class=""><?php echo $fechaFormateadaDos; ?></span>
                                </h3>
                            </div>

                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:30px;">
                                <div class="row justify-content-center align-items-center">
                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-mantenimiento" id="modalEmpresaUno-ImageContent">
                                        <!-- Segundo carrusel imágenes -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-mantenimiento'; ?>" class="carousel carousel-dark slide carousel-fade common-carousel" data-bs-ride="carousel">
                                            <!-- Índice carrusel -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-mantenimiento'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" 
                                                        class="<?php echo $index == 1 ? 'active' : ''; ?>" 
                                                        aria-current="true" 
                                                        style="background-color:#f5d138!important;"
                                                    >
                                                    </button>
                                                <?php } ?>
                                            </div>

                                            <!-- Imágenes del carrusel -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                                        <div class="d-flex justify-content-center image-second-carrusel" style="margin-bottom:30px!important;">
                                                            <img 
                                                                class="img-fluid image-second-modal" 
                                                                src="<?php echo $imagen; ?>" 
                                                                alt="Imagen <?php echo $empresa_id; ?>"
                                                                data-index-image="<?php echo $index;?>"
                                                            >
                                                        </div>

                                                        <div class="content_index_images mt-3">
                                                            <h5 class="text-center text-warning">
                                                                <span class="mb-3 index_active"><?php echo $index; ?></span> / 
                                                                <span class="mb-3 total_images"><?php echo $total_imagenes; ?></span> 
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Segundo carrusel imágenes -->
                                    </div>

                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-warning border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-mantenimiento'; ?>" 
                                            data-bs-slide="prev"
                                        >
                                            <span class="btn-warning" aria-hidden="true">
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

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-warning border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-mantenimiento'; ?>" 
                                            data-bs-slide="next">
                                            <span  class="btn-warning" aria-hidden="true">
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
                            <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div> 
        <?php }?>
        <!-- Contenido segunda actividad -->

        <!-- Contenido tercera actividad danger -->
        <?php if(!empty($empresa['texto_brechas']) && !empty($empresa['imagenes_brechas']) && !empty($empresa['fecha_actividad_tres'])) {?>
            <?php 
                $brechas_id = sanitize_title($empresa['nombre_empresa'] .'-actividad-tres' ); 

                $imagenes_actividad = array_reverse($empresa['imagenes_brechas']);
                $nueva_imagenes_actividad = array_combine(range(1, count($imagenes_actividad)), array_values($imagenes_actividad)); // nuevo array de imgs
                $total_imagenes = count($imagenes_actividad); // Obtener el total de imágenes


                $fechaTerceraActividad = $empresa['fecha_actividad_tres'];
                $fechaFormateadaTres = formatear_fecha($fechaTerceraActividad, $meses_es);
            ?>
            <!-- Second modal -->
            <div class="modal fade" id="<?php echo $brechas_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $brechas_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close text-light" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal" aria-label="Close"></button>
                        </div>   

                        <div class="modal-body">
                            <div class="container">
                                <h3 class="text-center text-danger fw-light"> 
                                    <span><?php echo $empresa['titulo_brechas']; ?></span> 
                                    </br> 
                                    <span class=""><?php echo $fechaFormateadaTres; ?></span>
                                </h3>
                            </div>

                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:60px;">
                                <div class="row justify-content-center align-items-center">
                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-brechas" id="modalEmpresaUno-ImageContent">
                                        <!-- Carrusel -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-brechas'; ?>" class="carousel carousel-dark slide carousel-fade common-carousel" data-bs-ride="carousel">
                                            <!-- Índice -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-brechas'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" 
                                                        class="<?php echo $index == 1 ? 'active' : ''; ?>" 
                                                        aria-current="true" 
                                                        style="background-color:#ec432c!important;">
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <!-- Imágenes -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                                        <div class="d-flex justify-content-center image-second-carrusel">
                                                            <img 
                                                                class="img-fluid image-second-modal" 
                                                                src="<?php echo $imagen; ?>" 
                                                                alt="Imagen <?php echo $empresa_id?>" 
                                                                data-index-image="<?php echo $index;?>"
                                                            >
                                                        </div>
                                                        <div class="content_index_images mt-3">
                                                            <h5 class="text-center text-danger">
                                                                <span class="mb-3 index_active"><?php echo $index; ?></span> / 
                                                                <span class="mb-3 total_images"><?php echo $total_imagenes; ?></span> 
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Carrusel -->
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button class="carousel-control p-2 bg-danger border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" type="button" data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-brechas'; ?>" data-bs-slide="prev">
                                            <span class="btn-danger" aria-hidden="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                                                </svg>
                                            </span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button class="carousel-control p-2 bg-danger border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" type="button" data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-brechas'; ?>" data-bs-slide="next">
                                            <span class="btn-danger" aria-hidden="true">
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

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Second modal -->    
        <?php } ?>
        <!-- Contenido tercera actividad -->

        <!-- Contenido cuarta actividad success -->
        <?php if(!empty($empresa['texto_otras_actividades']) && !empty($empresa['imagenes_otras_actividades']) && !empty($empresa['fecha_actividad_cuatro'])){?>
            <?php
                $actividades_uno_id = sanitize_title($empresa['nombre_empresa'].'-actividad-cuatro' );

                $imagenes_actividad = array_reverse($empresa['imagenes_brechas']);
                $nueva_imagenes_actividad = array_combine(range(1, count($imagenes_actividad)), array_values($imagenes_actividad)); // nuevo array de imgs
                $total_imagenes = count($imagenes_actividad); // Obtener el total de imágenes

                $fechaCuartaActividad = $empresa['fecha_actividad_cuatro'];
                $fechaFormateadaCuatro = formatear_fecha($fechaCuartaActividad, $meses_es);
            ?>        
            <div class="modal fade" id="<?php echo $actividades_uno_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $actividades_uno_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close text-light" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal" aria-label="Close"></button>
                        </div>  

                        <div class="modal-body">
                            <div class="container">
                                <h3 class="text-center text-success fw-light">
                                    <span><?php echo $empresa['titulo_brechas']; ?></span> 
                                    </br> 
                                    <span class=""><?php echo $fechaFormateadaCuatro; ?></span>
                                </h3>
                            </div>

                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:60px;">
                                <div class="row justify-content-center align-items-center">
                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-brechas" id="modalEmpresaUno-ImageContent">
                                        <!-- Segundo carrusel imágenes -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-actividad-uno'; ?>" class="carousel carousel-dark slide carousel-fade common-carousel" data-bs-ride="carousel">
                                            <!-- Índice carrusel -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-uno'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" class="<?php echo $index == 1 ? 'active' : ''; ?>" 
                                                        aria-current="true" style="background-color:#94ce58!important;">
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <!-- Imágenes del carrusel -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                                        <div class="d-flex justify-content-center image-second-carrusel">
                                                            <img 
                                                                class="img-fluid image-second-modal" 
                                                                src="<?php echo $imagen; ?>" 
                                                                alt="Imagen <?php echo $empresa_id; ?>" 
                                                                data-index-image="<?php echo $index; ?>"
                                                            >
                                                        </div>
                                                        <div class="content_index_images mt-3">
                                                            <h5 class="text-center text-success">
                                                                <span class="mb-3 index_active"><?php echo $index; ?></span> / 
                                                                <span class="mb-3 total_images"><?php echo $total_imagenes; ?></span> 
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Segundo carrusel imágenes -->
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-success border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-uno'; ?>" 
                                            data-bs-slide="prev">
                                            <span class="btn-success" aria-hidden="true">
                                                <svg 
                                                    xmlns="http://www.w3.org/2000/svg" 
                                                    width="20"
                                                    height="20" 
                                                    fill="currentColor" 
                                                    class="bi bi-arrow-left" 
                                                    viewBox="0 0 16 16">
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

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-success border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-uno'; ?>" 
                                            data-bs-slide="next"
                                            >
                                            <span class="btn-success" aria-hidden="true">
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
                            <button type="button" class="btn btn-danger text-white" id="btn-close" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>           
        <?php }?>
        <!-- Contenido cuarta actividad -->

        <!-- Contenido quinta actividad info -->
        <?php if(!empty($empresa['texto_otras_actividades_dos']) && !empty($empresa['imagenes_otras_actividades_dos']) && !empty($empresa['fecha_actividad_cinco'])){?>
            <?php
                $actividades_dos_id = sanitize_title($empresa['nombre_empresa'] .'-actividad-cinco' );
                
                $imagenes_actividad = array_reverse($empresa['imagenes_otras_actividades_dos']);
                $nueva_imagenes_actividad = array_combine(range(1, count($imagenes_actividad)), array_values($imagenes_actividad)); // nuevo array de imgs
                $total_imagenes = count($imagenes_actividad); // Obtener el total de imágenes


                $fechaQuintaActividad = $empresa['fecha_actividad_cinco'];
                $fechaFormateadaCinco = formatear_fecha($fechaQuintaActividad, $meses_es);
            ?>
            <!-- Second modal -->
            <div class="modal fade" id="<?php echo $actividades_dos_id; ?>-Modal" tabindex="-1" aria-labelledby="<?php echo $actividades_dos_id; ?>-ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">   
                        <div class="modal-header">
                            <button type="button" class="btn-close text-light" data-bs-toggle="modal" data-bs-target="#<?php echo $empresa_id; ?>Modal" aria-label="Close"></button>
                        </div>                   
                        <div class="modal-body">
                            <!-- Titulo c/fecha -->
                            <div class="container">
                                <h3 class="text-center text-info fw-light"> 
                                    <span class=""><?php echo $empresa['titulo_brechas']; ?></span> 
                                    </br>
                                    <span class=""><?php echo $fechaFormateadaCinco; ?></span>
                                </h3>
                            </div>

                            <!-- Empieza segundo carrusel -->
                            <div class="container carousel slides" id="" style="margin-top:60px;">
                                <div class="row justify-content-center align-items-center">
                                    <!-- Contenido Carrusel -->
                                    <div class="col-8 d-flex justify-content-center content-image-brechas" id="modalEmpresaUno-ImageContent">
                                        <!-- Segundo carrusel imágenes -->
                                        <div id="<?php echo $empresa_id . '-segundo-carrusel-actividad-dos'; ?>" class="carousel carousel-dark slide carousel-fade common-carousel" data-bs-ride="carousel">
                                            <!-- Índice carrusel -->
                                            <div class="carousel-indicators indice-segundo-modal">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <button 
                                                        data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-dos'; ?>" 
                                                        data-bs-slide-to="<?php echo $index - 1; ?>" class="<?php echo $index == 1 ? 'active' : ''; ?>"
                                                        aria-current="true" style="background-color:#4674c1!important;"
                                                        >
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <!-- Imágenes del carrusel -->
                                            <div class="carousel-inner second-image-carousel">
                                                <?php foreach($nueva_imagenes_actividad as $index => $imagen) { ?>
                                                    <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                                        <div class="d-flex justify-content-center image-second-carrusel">
                                                            <img 
                                                                class="img-fluid image-second-modal" 
                                                                src="<?php echo $imagen; ?>"
                                                                alt="Imagen <?php echo $empresa_id; ?>" 
                                                                data-index-image="<?php echo $index;?>"
                                                            >
                                                        </div>
                                                        <div class="content_index_images mt-3">
                                                            <h5 class="text-center text-info">
                                                                <span class="mb-3 index_active"><?php echo $index; ?></span> / 
                                                                <span class="mb-3 total_images"><?php echo $total_imagenes; ?></span> 
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Segundo carrusel imágenes -->
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor btns  -->
                            <div class="container d-flex justify-content-evenly content-btns-gallery">
                                <!-- Botón prev -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-info border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-prev" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-dos'; ?>" 
                                            data-bs-slide="prev"
                                            >
                                            <span class="btn-info" aria-hidden="true">
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

                                <!-- Botón next -->
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col">
                                        <button 
                                            class="carousel-control p-2 bg-info border-0 rounded-2 shadow-sm btn-second-carrusel btn-second-carrusel-next" 
                                            type="button" 
                                            data-bs-target="#<?php echo $empresa_id . '-segundo-carrusel-actividad-dos'; ?>" 
                                            data-bs-slide="next"
                                            >
                                            <span class="btn-info" aria-hidden="true">
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
                            <button 
                                type="button" 
                                class="btn btn-danger text-white" 
                                id="btn-close" 
                                data-bs-toggle="modal" 
                                data-bs-target="#<?php echo $empresa_id; ?>Modal"
                                >
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>        
            <!-- Second modal -->    
        <?php }?>
        <!-- Contenido quinta actividad -->

        <?php }?>
    <?php }?>
    <!-- Modal Empresas B -->
