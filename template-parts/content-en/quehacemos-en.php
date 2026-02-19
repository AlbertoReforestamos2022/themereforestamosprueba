    <?php  $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true ); ?>
    <h1 class="text-ligh d-none title-principal-en"><?php echo $tituloPrincipal ?></h1>
    <!-- Card Modelos de manejo de paisajes -->
    <section class="container-xxl modelo-paisajes-en" id="manejo-paisajes-en">
        <?php  $titulo_principal = get_post_meta( get_the_ID(), 'titulo_principal_en', true ); ?>
        <h2 class="espacio-lineas-accion text-center text-primary t-m-p"> <?php echo $titulo_principal; ?> </h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center px-5"> 
            <?php  $contenidos_iniciativa = get_post_meta( get_the_ID(), 'reforestamos_group_que_hacemos_cards', true ); ?>
            <?php  if($contenidos_iniciativa) { ?>
            <?php  foreach($contenidos_iniciativa as $contenido) { ?>
            <?php $target = substr(str_replace(' ', '-', $contenido['nombre_iniciativa_en']), 0, 10 ); ?>
    
            <!-- Card - Modelos de manejo de paisajes  -->
            <div class="col col-iniciativa p-3">
                <div class="card h-100 border-0 shadow-lg rounded-3 d-grid align-items-center justify-content-center card-iniciativa">
                    <div class="card-header border-0 bg-transparent d-grid align-items-end justify-content-center p-3">
                        <?php echo $contenido['logo_iniciativa_en'];?>
                    </div>

                    <div class="card-body">
                        <h5 class="text-primary text-center bg-transparent <?php echo $target; ?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?php echo $target; ?>" id="<?php echo $target;?>target"><?php echo $contenido['nombre_iniciativa_en'];?></h5>
                    </div>
                </div>
            </div>

            <!-- Modal - Modelos de manejo de paisajes  -->
            <div class="modal fade" id="<?php echo $target; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="<?php echo $target;?>Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h3 class="modal-title text-light text-center" id="<?php echo $target; ?>"><?php echo $contenido['nombre_iniciativa_en'];?> </h3>
                            
                        </div>
            
                        <div class="modal-body py-5 <?php echo $target; ?>-Modal ">
                            <?php echo $contenido['desc_iniciativa_modal_en'];?>
                        </div>
            

                        <?php
                        if($contenido['boton_modelos'] === 'd-none'){
                        ?>
                            <div class="modal-footer border-0 d-flex justify-content-center">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                            </div>      
                        <?php
                        } else {
                        ?>
                            <div class="modal-footer border-0 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                                <a class="btn btn-outline-primary b-s-m" href="<?php echo $contenido['link_iniciativa'];?>" target="_blank">Saber más</a>
                            </div>      
                        <?php
                            }
                        ?>

                    </div>
                </div>
            </div>
            <?php }?>
            <?php } else { ?> 
            <?php echo reforestamos_nota_error( get_the_ID() ); ?>
            <?php } ?>
        </div>
    </section>

    <!-- Incidencia en políticas públicas -->
    <section class="container-xxl inicidencia-politicas-publicas-en" id="incidencia-politica-en">
        <?php  $titulo_incidencia = get_post_meta( get_the_ID(), 'titulo_principal_incidencia_en', true ); ?>
        <h2 class="espacio-lineas-accion text-center text-primary t-i-p-p"><?php echo $titulo_incidencia ?></h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center px-5 py-3">
            <?php  $contenidos_incidencias = get_post_meta( get_the_ID(), 'reforestamos_group_que_hacemos_cards_incidencia', true ); ?>

            <?php if($contenidos_incidencias) { ?>
            <?php foreach($contenidos_incidencias as $contenido_incidencia) { ?>
            <?php $incidencia_target = substr(str_replace(' ', '-', $contenido_incidencia['nombre_incidencia_en']), 0, 10 ); ?> 

                <div class="col col-iniciativa p-3">
                    <div class="card h-100 border-0 shadow-lg rounded-3 d-grid align-items-center justify-content-center card-iniciativa">
                        <div class="card-header border-0 bg-transparent d-grid align-items-center justify-content-center p-3" >
                            <?php echo $contenido_incidencia['logo_inicidencia_en'];?>
                        </div>

                        <div class="card-body border-0 bg-transparent" >
                            <h5 class="text-primary text-center bg-transparent <?php echo $incidencia_target;?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?php echo $incidencia_target;?>" id="<?php echo $incidencia_target;?>target" ><?php echo $contenido_incidencia['nombre_incidencia_en']?></h5>
                        </div>
                    </div>
                </div>

                <!-- Modal - Incidencia en politicas públicas  -->
                <div class="modal fade" id="<?php echo $incidencia_target; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="<?php echo $incidencia_target; ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                            <h4 class="modal-title text-light text-center bg-transparent" id="<?php echo $incidencia_target; ?>"><?php echo $contenido_incidencia['nombre_incidencia_en'];?> </h4>
                            
                            </div>
                
                            <div class="modal-body py-5 <?php echo $incidencia_target; ?>-Modal">
                            <?php echo $contenido_incidencia['desc_incidencia_modal_en'];?>
                            </div>

                            <?php
                            if($contenido_incidencia['boton_incidencia'] == 'd-none') {
                            ?>
                            <div class="modal-footer border-0 d-flex justify-content-center">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                            </div>                             
                            <?php
                            } else {
                            ?>
                            <div class="modal-footer border-0 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                                <a class="btn btn-outline-primary b-s-m" href="<?php echo $contenido_incidencia['link_incidencia'];?>" target="_blank">Saber más</a>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>  
            <?php }else { ?>
                <?php echo reforestamos_nota_error( get_the_ID() ); ?>
            <?php }?>  
        </div>  
    </section>

    <!-- Comunidades de emprendimiento -->
    <section class="container-xxl comunidades-emprendimiento-en" id="comunidades-emprendimiento-en">
        <?php  $titulo_comunidades = get_post_meta( get_the_ID(), 'titulo_principal_comunidades_en', true ); ?>
        <h2 class="espacio-lineas-accion text-center text-primary ti-co-em"><?php echo $titulo_comunidades;?></h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center px-5 py-3">
        <?php  $contenidos_comunidades = get_post_meta( get_the_ID(), 'reforestamos_group_que_hacemos_cards_comunidades', true ); ?>
        
        <?php if($contenidos_comunidades) {
            foreach($contenidos_comunidades as $comunidad){ ?>
            <?php $arrayComunidad = [
                    ' ' => '-',
                    '&' => 'y',
                    ';' => 'y'
            ] ?>
            
            <?php $comunidad_target = substr(str_replace(array_keys($arrayComunidad), array_values($arrayComunidad), $comunidad['titulo_comunidad_en']), 0, 23 ); ?>
            <div class="col col-iniciativa p-3">
                
                <div class="card h-100 border-0 shadow-lg rounded-3 d-grid align-items-center justify-content-center card-iniciativa">
                    <div class="card-header border-0 bg-transparent d-grid align-items-center justify-content-center p-3" >
                        <?php echo $comunidad['logo_comunidad_en'];?>
                    </div>

                    <div class="card-body">
                        <h5 class="text-primary text-center bg-transparent <?php echo $comunidad_target; ?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?php echo $comunidad_target; ?>" id="<?php echo $comunidad_target;?>target" ><?php echo $comunidad['titulo_comunidad_en'];?></h5>
                    </div>
                </div>
            </div>

                <!-- Modal - Comunidades de emprendimiento  -->
                <div class="modal fade" id="<?php echo $comunidad_target; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="<?php echo $comunidad_target; ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                            <h3 class="modal-title text-light text-center" id="<?php echo $comunidad_target; ?>"><?php echo $comunidad['titulo_comunidad_en'];?> </h3>
                            
                            </div>
                
                            <div class="modal-body py-5 <?php echo $comunidad_target; ?>-Modal">
                            <?php echo $comunidad['desc_comunidad_modal_en'];?>
                            </div>


                            <?php
                            if($comunidad['boton_comunidades'] == 'd-none'){
                            ?>
                            <div class="modal-footer border-0 d-flex justify-content-center">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>

                            </div>
                            <?php
                            } else {
                            ?>
                            <div class="modal-footer border-0 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                                <a class="btn btn-outline-primary b-s-m" href="<?php echo $comunidad['link_comunidad'];?>" target="_blank">Saber más</a>
                            </div>
                            <?php
                            }
                            ?>
            
                        </div>
                    </div>
                </div>
        <?php  }
        } else { ?>
            <?php echo reforestamos_nota_error( get_the_ID() ); ?>
        <?php }?>

        </div> 
    </section>

    <!-- Compromisos del sector privado -->
    <section class="container-xxl sector-privado-en" id="sector-privado-en">
        <?php  $titulo_privado = get_post_meta( get_the_ID(), 'titulo_principal_privado_en', true ); ?>
        <h2 class="espacio-lineas-accion text-center text-primary t-c-s-p"><?php echo $titulo_privado; ?></h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center px-5 py-3">
            <?php  $contenidos_privados = get_post_meta( get_the_ID(), 'reforestamos_group_que_hacemos_cards_privado', true ); ?>
            <?php if($contenidos_privados){
                foreach($contenidos_privados as $privado) { ?>
                <?php $arraySecPriv = [
                    ' ' => '-',
                    'ó' => 'o',
                    '.' => '-',
                    '1' => 'one',
                ] ?>
                <?php $privado_target = substr(str_replace(array_keys($arraySecPriv), array_values($arraySecPriv), $privado['titulo_privado_en']), 0, 23 ); ?>
                <div class="col col-iniciativa p-3">
                    <div class="card h-100 border-0 shadow-lg rounded-3 d-grid align-items-center justify-content-center card-iniciativa">
                        <div class="card-header border-0 bg-transparent d-grid align-items-center justify-content-center p-3" >
                            <?php echo $privado['logo_privado_en'];?>
                        </div>

                        <div class="card-body">
                            <h5 class="text-primary text-center bg-transparent <?php echo $privado_target;?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?php echo $privado_target;?>" id="<?php echo $privado_target;?>target"><?php echo $privado['titulo_privado_en'];?></h5>
                        </div>
                    </div>
                </div>
                
                <!-- Modal - Compromisos del sector privado -->
                <div class="modal fade" id="<?php echo $privado_target; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="<?php echo $privado_target; ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                            <h3 class="modal-title text-light text-center" id="<?php echo $privado_target; ?>"><?php echo $privado['titulo_privado_en'];?> </h3>
                            
                            </div>
                
                            <div class="modal-body py-5 <?php echo $privado_target; ?>-Modal">
                            <?php echo $privado['desc_privado_modal_en'];?>
                            </div>
            
                            <?php
                            if($privado['boton_privado'] == 'd-none'){
                            ?>
                                <div class="modal-footer border-0 d-flex justify-content-center">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                                </div>                            
                            <?php
                            } else {
                            ?>
                                <div class="modal-footer border-0 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                                <a class="btn btn-outline-primary b-s-m" href="<?php echo $privado['link_privado'];?>" target="_blank">Saber más</a>
                                </div> 
                            <?php
                            }
                            ?>
            
                        </div>
                    </div>
                </div>
            <?php  }
            } else {?>
                <?php echo reforestamos_nota_error( get_the_ID() ); ?>
            <?php }?>
        </div>  
    </section>

    <!-- Campañas de empoderamiento ciudadano -->
    <section class="container-xxl empoderamiento-ciudadano-en" id="empoderamiento-ciudadano-en">
        <?php  $titulo_ciudadano = get_post_meta( get_the_ID(), 'titulo_principal_ciudadano_en', true ); ?> 
        <h2 class="espacio-lineas-accion text-center text-primary t-c-e-c"><?php echo $titulo_ciudadano ?></h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center px-5 py-3">
        <?php  $contenidos_ciudadanos = get_post_meta( get_the_ID(), 'reforestamos_group_que_hacemos_cards_ciudadano', true ); ?>
        <?php if($contenidos_ciudadanos) {
                foreach($contenidos_ciudadanos as $ciudadano) { ?>
                <?php $ciudadano_target = substr(str_replace(' ', '-', $ciudadano['titulo_ciudadano_en']), 0, 10 ); ?>
                <div class="col col-iniciativa p-3">
                    <div class="card h-100 border-0 shadow-lg rounded-3 d-grid align-items-center justify-content-center card-iniciativa">
                        <div class="card-header border-0 bg-transparent d-grid align-items-center justify-content-center p-3">
                            <?php echo $ciudadano['logo_ciudadano_en'];?>
                        </div>

                        <div class="card-body">
                            <h5 class="text-primary text-center bg-transparent <?php echo $ciudadano_target;?>" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#<?php echo $ciudadano_target;?>" id="<?php echo $ciudadano_target;?>target"><?php echo $ciudadano['titulo_ciudadano_en'];?></h5>
                        </div>
                    </div>
                </div>

                <!-- Modal - Campañas de empoderamiento ciudadano  -->
                <div class="modal fade" id="<?php echo $ciudadano_target; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="<?php echo $ciudadano_target; ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                            <h3 class="modal-title text-light text-center" id="<?php echo $ciudadano_target; ?>"><?php echo $ciudadano['titulo_ciudadano_en'];?> </h3>
                            
                            </div>
                
                            <div class="modal-body py-5 <?php echo $ciudadano_target; ?>-Modal">
                            <?php echo $ciudadano['desc_ciudadano_modal_en'];?>
                            </div>

                            <?php
                            if($ciudadano['boton_ciudadano'] == 'd-none'){
                            ?>
                            <div class="modal-footer border-0 d-flex justify-content-center">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                            </div>                            
                            <?php
                            } else {
                            ?>
                            <div class="modal-footer border-0 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-light b-s" data-bs-dismiss="modal" aria-label="Close">Salir </button>
                                <a class="btn btn-outline-primary b-s-m" href="<?php echo $ciudadano['link_ciudadano'];?>" target="_blank">Saber más</a>
                            </div>                              
                            <?php  
                            }
                            ?>
                        
                        </div>
                    </div>
                </div>
        <?php  }
        } else { ?>
            <?php echo reforestamos_nota_error( get_the_ID() ); ?>
        <?php } ?>
        </div>
    </section>

