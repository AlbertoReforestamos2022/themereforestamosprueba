    <!-- Aliados Tipo B -->
    <section class="container-xxl espacio">    
        <div class="wrapper espacio">
            <div class="wrapper-list owl-carousel">
                    <?php $logos = get_post_meta( get_the_ID(), 'reforestamos_home_logos_aliados',true );
                        foreach($logos as $logo) {
                    ?>
                    <div class="slide-item joven-emprededor-forestal img-fluid">
                        <a href="<?php echo $logo['url_sitio_aliado']?>" target="_blank">
                            <img src="<?php echo $logo['imagen_aliado']?>" alt="">
                        </a>
                    </div>
                    <?php }?>
                </div>
            </div>
    </section>
    <!--/.Aliados Tipo B-->


    
