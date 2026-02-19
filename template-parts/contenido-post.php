<main class="container contenedor-nota-es" data-id="<?php echo get_the_ID(); ?>" data-slug="<?php echo $post->post_name; ?>">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12 col-lg-10">
            <div class="card border-0 shadow-sm content-nota-ES">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="p-3">
                        <h2 class="text-primary text-center titulo-nota-es"> <?php echo the_title();?> </h2>
                    </div>

                    <div class="fw-bold">
                        <h4 class="text-black-50 date-nota"><?php the_time(get_option('date_format')); ?></h4>
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Comparte esta nota en tus Redes Sociales</p>

                        <hr class="p-2 text-primary">
                    </div>
                </div>
                <div class="card-body px-3 px-md-5 fs-5">
                    <div class="text-justify contenido-nota-es">
                        <?php echo do_shortcode(the_content()); ?>
                        
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 p-4">
                    <div class="fw-bold">
                        <hr class="p-2 text-primary">
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Comparte esta nota en tus Redes Sociales</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php /* Contenido en inglés */?>
<main class="container contenedor-nota-en" data-id="<?php echo get_the_ID(); ?>" data-slug="<?php echo $post->post_name; ?>">
    <div class="row justify-content-center align-items-center">
        <div class="col">
            <div class="card border-0 shadow-sm con-no-bl">
                <?php $titulo = get_post_meta( get_the_ID(), 'nota_blog_ingles_titulo_nota_ingles', true); ?>
                <?php $contenido = get_post_meta( get_the_ID(), 'nota_blog_ingles_contenido_nota_ingles', true); ?>
                <?php $contenidoSinProcesar = do_shortcode($contenido); ?>
                <?php $contenidoShortCode = do_shortcode(apply_filters('the_content', $contenido)) ?> 

                <div class="card-header bg-transparent border-0 p-4">
                    <div class="p-3">
                        <h2 class="text-primary text-center titulo-nota_en"> <?php echo $titulo;?> </h2>
                    </div>

                    <div class="fw-bold">
                        <h4 class="text-black-50 date-nota" ><?php the_time(get_option('date_format')); ?></h4>
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Share this note in your Social Networks</p>

                        <hr class="p-2 text-primary">
                    </div>
                </div>

                <div class="card-body px-3 px-md-5 fs-5">
                    <div class="text-justify contenido-nota-en">
                        <?php echo $contenidoShortCode; ?> 
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 p-4">
                    <div class="fw-bold">
                        <hr class="p-2 text-primary">
                        <h4 class="text-black-50 date-nota"><?php the_time(get_option('date_format')); ?></h4>
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Share this note in your Social Networks</p>
                    </div>
                </div>



            </div>
        </div>

    </div>
</main>

<script>



</script>


