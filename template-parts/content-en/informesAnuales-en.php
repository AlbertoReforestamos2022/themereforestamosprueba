    <?php  $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true ); ?>
    <h1 class="text-light text-center title-principal-en d-none"><?php echo $tituloPrincipal; ?> </h1>

    <div class="container-xxl my-5 d-none container_documents_en">          
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 ">
            <?php  $titulo_documentos = get_post_meta( get_the_ID(), 'reforestamos_group_documento_btn_', true );
                if(!empty($titulo_documentos)) {
                    foreach($titulo_documentos as $titulo_doc) { ?> 
                        <?php $key_documento = substr(str_replace(' ', '-', $titulo_doc['anio_titulo_documento_en']), 0, 10 ); ?>
                            <div class="p-2">
                                <button class="btn btn-success text-white shadow w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $key_documento; ?>" aria-expanded="false" aria-controls="collapse<?php echo $key_documento; ?>" style="height: 60px;">
                                    <h5><?php echo $titulo_doc['anio_titulo_documento_en'] ?></h5>
                                </button>
        
                                
                                <div class="collapse" id="collapse<?php echo $key_documento; ?>">                        
                                    <div class="card card-body border-0 text-center mt-3 shadow text-docoration-none text-sucess documentos_link">
                                        <?php echo $titulo_doc['link_documento_en']; ?>                                
                                    </div>
                                </div>
                            </div>
                    <?php } ?>
                <?php } ?>
        </div>
    </div>
