<?php
include('resultados.php');
// traducción Inglés 
if (isset($_GET['palabra-clave']) && !empty($_GET['palabra-clave'])) {
    $search_query = sanitize_text_field($_GET['palabra-clave']);

    // Realizamos la consulta de búsqueda
    $args = array(
        's' => $search_query,
        'post_type' => 'post', // Ajusta esto al tipo de entrada que estás buscando (en este caso, entradas de blog)
    );

    $query = new WP_Query($args);

    // borramos las notas principales del blog
    borrarContenido(); 
    
    // Mostramos el número de resultados de la búsqueda
    $result_count = $query->found_posts;
    ?>
        <div class="col-12 mt-3">
            <h4 class="text-center text-light res-en"><?php echo $result_count ?> <span class="te-res">resultados encontrados</span></h4>
        </div>
    <?php

    ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 re-no" style="margin-bottom:100px;">   
            <?php
            if ($query->have_posts()) { ?>
                <script>
                    const idiomaSeleccionado = localStorage.getItem("idioma");
                    const titulo = document.querySelector('.title-general');

                    // campo notas
                    let resNotas = document.querySelector('.re-no');

                    if(idiomaSeleccionado === 'en-US') {
                        titulo.textContent = `Results`;
                        resNotas.innerHTML = `
                        <?php 
                        while ($query->have_posts()) {
                            $query->the_post();

                            // Obtenemos la información de la entrada
                            $post_id = get_the_ID();
                            $post_title = get_the_title();
                            $post_date = get_the_date();
                            $thumbnail_id = get_post_thumbnail_id();

                            // Obtenemos la URL de la imagen principal
                            $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full')[0];

                            // Obtenemos el enlace a la entrada
                            $post_link = get_permalink();
                            
                            // metaboxes en inglés
                            $titulos_en = get_post_meta( get_the_ID(), 'nota_blog_ingles_titulo_nota_ingles', false );

                            // Mostramos la información
                            ?>
                            <div class="col my-3" id="res-bu">
                                <div class="card shadow bg-transparent border-0 my-3" style="width: 100%; height:100%!important;">
                                <img src="<?php echo esc_url($thumbnail_url) ?>" class="card-img-top p-2 rounded-1 shadow-sm">
                                    <div class="card-header bg-transparent border-0 h-100 d-grid align-items-between">
                                        <a href="<?php the_permalink();?>" class="text-decoration-none t-n">
                                            <?php 
                                            foreach ($titulos_en as $titulo)
                                                echo esc_html($titulo);
                                            ?>
                                        </a>

                                    </div>
                                    <div class="card-body bg-transparent border-0">
                                        <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons" style="z-index: 0;"></div><!-- ShareThis END -->    
                                        <p class="text-primary my-2"><?php echo esc_html($post_date) ?></p>
                                    </div>
                                </div>                
                            </div>
                        <?php } ?>`;           
                    } else {
                        resNotas.innerHTML = `
                        <?php
                        while ($query->have_posts()) {
                            $query->the_post();

                            // Obtenemos la información de la entrada
                            $post_id = get_the_ID();
                            $post_title = get_the_title();
                            $post_date = get_the_date();
                            $thumbnail_id = get_post_thumbnail_id();

                            // Obtenemos la URL de la imagen principal
                            $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full')[0];

                            // Obtenemos el enlace a la entrada
                            $post_link = get_permalink();

                            // Mostramos la información
                            ?>
                            <div class="col my-3" id="res-bu">
                                <div class="card shadow bg-transparent border-0 my-3" style="width: 100%; height:100%!important;">
                                <img src="<?php echo esc_url($thumbnail_url) ?>" class="card-img-top p-2 rounded-1 shadow-sm">
                                    <div class="card-header bg-transparent border-0 h-100 d-grid align-items-between">
                                        <h5 class="text-primary my-3 tit-res"><?php echo esc_html($post_title)?></h5>

                                    </div>
                                    <div class="card-body bg-transparent border-0">
                                        <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons" style="z-index: 0;"></div><!-- ShareThis END -->    
                                        <p class="text-primary my-2"><?php echo esc_html($post_date) ?></p>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 py-3">
                                        <a href="<?php echo esc_url($post_link) ?>" class="btn btn-outline-light boton-s-m">Saber más</a>
                                    </div>
                                </div>                
                            </div>
                            <?php
                        } 
                        ?>                       
                        `;
                    }
                </script>
                <?php
            } else {
                echo 'No se encontraron resultados.';
            }
            ?>
        </div>
    <?php
    wp_reset_postdata();
} else {

    echo ' ';
}

?> 



