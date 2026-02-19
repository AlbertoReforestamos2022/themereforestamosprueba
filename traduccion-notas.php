<main class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col">
            <div class="card border-0 shadow-sm con-no-bl">
                <?php $titulo = get_post_meta( get_the_ID(), 'nota_blog_ingles_titulo_nota_ingles', true); ?>
                <?php $contenido = get_post_meta( get_the_ID(), 'nota_blog_ingles_contenido_nota_ingles', true); ?>
                <?php $contenidoSinProcesar = do_shortcode($contenido); ?>
                <?php $contenidoShortCode = do_shortcode(apply_filters('the_content', $contenido)) ?> 

                <div class="card-header bg-transparent border-0 p-4">
                    <div class="p-3">
                        <h1 class="text-primary text-center tit-nota aviso-title"> <?php echo $titulo;?> </h1>
                    </div>

                    <div class="fw-bold">
                        <h4 class="text-black-50 dat-not" ><?php the_time(get_option('date_format')); ?></h4>
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Share this note in your Social Networks</p>

                        <hr class="p-2 text-primary">
                    </div>
                </div>

                <div class="card-body px-3 px-md-5 fs-5">
                    <div class="text-justify cont-nota content-title">
                        <?php echo $contenidoShortCode; ?> 
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 p-4">
                    <div class="fw-bold">
                        <hr class="p-2 text-primary">
                        <h4 class="text-black-50 dat-not" ><?php the_time(get_option('date_format')); ?></h4>
                        <div class="sharethis-inline-share-buttons"></div>
                        <p class="mt-3 compartir-redes-sociales">Share this note in your Social Networks</p>
                    </div>
                </div>



            </div>
        </div>

    </div>
</main>







