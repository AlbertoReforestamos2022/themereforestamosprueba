<?php

function reforestamos_query_intergrantes($cantidad = -1) {
    $args = array(
        'post_type' => 'integrantes_rmx',
        'post_per_page' => $cantidad
    );

    $integrantes = new WP_Query($args);

    while($integrantes->have_posts() ): $integrantes->the_post();

    // printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true )); 
    ?>
        <div class="col px-4 px-md-2">
            <div class="card h-100 border-0 shadow" style="background-image:url('http://www.reforestamosmexico.org/wp-content/uploads/2023/08/fondo-tarjeta.png'); background-position:center; background-repeat:no-repeat;">
                <?php the_post_thumbnail('mediano', array('class' => 'card-img-top p-2 rounded-1 shadow-sm img-nota'))?>

                <div class="card-body px-3">
                    <?php 
                        $nombre = get_post_meta(get_the_ID(), 'reforestamos_integrantes_nombre_integrante', true);
                        $area = get_post_meta(get_the_ID(), 'reforestamos_integrantes_area_integrante', true);
                        $puesto = get_post_meta(get_the_ID(), 'reforestamos_integrantes_puesto_integrante', true);
                    ?>
                    <h4 class="card-title fw-normal text-primary text-center"><?php echo $nombre ?></h4>
                    <h5 class="card-title fw-normal text-primary text-center"><?php echo $puesto ?></h5>
                    <p class="card-title fw-normal text-primary text-center"><?php echo $area ?></p>

                </div>
                <div class="card-footer border-0 bg-transparent d-grid justify-content-center">
                    <a class="btn btn-outline-success" href="<?php the_permalink()?>">Ver información </a>
                </div>
            </div>
        </div>
    <?php
    endwhile; wp_reset_postdata();
}