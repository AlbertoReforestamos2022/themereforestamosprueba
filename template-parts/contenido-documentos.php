<?php   /* printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true )); */  ?> 
    <!-- Datos Documentos -->
    <div class="container-xxl my-5 con-doc">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 ">
                <?php  $titulo_documentos = get_post_meta( get_the_ID(), 'reforestamos_group_documento_btn_', true );
                if(!empty($titulo_documentos) ) {
                    foreach($titulo_documentos as $titulo_documento) { ?>
                        <?php $key_documento = substr(str_replace(' ', '-', $titulo_documento['anio_titulo_documento']), 0, 10 ); ?>

                        <div class="p-2">
                            <button class="btn btn-success text-white shadow w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $key_documento; ?>" aria-expanded="false" aria-controls="collapse<?php echo $key_documento; ?>" style="height: 60px;">
                                <h5><?php echo $titulo_documento['anio_titulo_documento'] ?></h5>
                            </button>
                            
                            
                            <div class="collapse" id="collapse<?php echo $key_documento; ?>">                        
                                <div class="card card-body border-0 text-center mt-3 shadow text-docoration-none text-sucess documentos_link" style="text-align:left!important;">
                                    <?php echo $titulo_documento['link_documento']; ?>                                
                                </div>
                            </div>
                        </div>
                    <?php } 
                } ?>
        </div>
    </div>
    <!-- ./Datos Documentos -->


