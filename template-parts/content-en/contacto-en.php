        <!-- Titulo principal inglés -->
        <?php  $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true ); ?>
        <h1 class="text-light text-center title-principal-en d-none"><?php echo $tituloPrincipal; ?> </h1>

        <!-- Titulo sección ubicaciónes -->

        <div class="container d-none">
            <div class="row">
                <?php $ubicaciones = get_post_meta( get_the_ID(), 'reforestamos_group_seccion_contacto_ubicacion', true ); ?> 
                <?php 
                    if(!empty($ubicaciones)) {
                        foreach($ubicaciones as $ubicacion){ ?>                
                            <div class="col">
                                <h3 class="text-center my-5 text-primary">
                                    <i class="bi bi-globe"></i> 
                                    <span class="titulo-ubicaciones-en"><?php echo $ubicacion['titulo_ubicacion_en']; ?></span>
                                </h3>
                            </div>  
                <?php  }
                    }  
                ?>

            </div>
        </div>
