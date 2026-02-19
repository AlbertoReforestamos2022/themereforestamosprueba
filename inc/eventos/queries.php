
<?php
    function reforestamos_query_eventos($cantidad = -1){
        // printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true )); 
        // Imprimir tarjetas de eventos
        $args = array(
            'post_type' => 'eventos_rmx',
            'post_per_page' => $cantidad
        );
        ?>
        <div class="container">
            <div class="row justify-content-center mt-3">
                <div class="col-2 d-flex justify-content-center align-items-center">
                    <a class="btn btn-outline-light" id="anterior">Mes Anterior</a>
                </div>
                <div class="col-3">
                    <h3 class="mes-actual text-center text-primary"></h3>
                </div>
                <div class="col-2 d-flex justify-content-center align-items-center">
                    <a class="btn btn-outline-light" id="siguiente">Mes Siguiente</a>
                </div>
            </div>
            
            <div id="calendario"></div>
        </div>

        <div class="container my-5">
            <div class="row row-cols-1 row-cols-md-3">

        <?php
            $eventos = new WP_Query($args);
            while($eventos->have_posts() ): $eventos->the_post(); ?>
            <div class="col px-4 px-md-2 evento-content" id="evento-content">
                <div class="card h-100 border-0 shadow card-evento" id="<?php echo get_post_meta(get_the_ID(), 'reforestamos_eventos_fecha_inicio', true);?>">
                    <?php the_post_thumbnail('mediano', array('class' => 'card-img-top p-2 rounded-1 shadow-sm img-nota imagenEvento'))?>
    
                    <div class="card-body px-3">
                        <?php
                            $titulo = the_title(); 
                            $inicio = get_post_meta(get_the_ID(), 'reforestamos_eventos_fecha_inicio', true);
                            $fin = get_post_meta(get_the_ID(), 'reforestamos_eventos_fecha_fin', true);
                            $detalles = get_post_meta(get_the_ID(), 'reforestamos_eventos_detalles_evento', true);
                            
                        ?>
                        <h4 class="card-title fw-normal text-primary text-center"><?php echo $titulo ?></h4>
                        <?php
                        // Condicional todo evento todo el día
                        if($inicio == $fin) { ?>
                            <h5 class="card-title fw-normal text-primary text-center"> Todo el día</h5>
                            <h5 class="card-title fw-normal text-primary text-center inicioEvento"><?php echo $inicio ?></h5>
                        <?php    
                        }else { ?>
                            <span class="text-bold text-primary">Inicio:</span>    
                            <h5 class="card-title fw-normal text-primary text-center inicioEvento"><?php echo $inicio ?></h5>

                            <span class="text-bold text-primary">Termino:</span>
                            <h5 class="card-title fw-normal text-primary text-center finEvento"> <?php echo $fin ?></h5>
                        <?php    
                        }
                        ?>    
                        <p class="card-title fw-normal text-primary text-center detallesEvento"><?php echo $detalles ?></p>
    
                    </div>
                    <div class="card-footer border-0 bg-transparent d-grid justify-content-center">
                        <a class="btn btn-outline-success" href="<?php the_permalink()?>">Ver evento</a>
                    </div>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata();?>
            </div>
        </div>
        <?php  
    }
?>
