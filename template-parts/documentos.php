
    <!-- Documentos -->
    <div class="container my-5 con-doc">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 justify-content-center">
        <?php  $informacion_documentos = get_post_meta( get_the_ID(), 'reforestamos_group_documento', true ); 
            if($informacion_documentos === "") { ?>
                <p> </p>
            <?php } else { 

                foreach($informacion_documentos as $info_doc) {
                ?> 
                    <div class="col">
                        <div class="card d-grid justify-content-center align-items-center border-0 h-100">
                            <div class="card-body d-flex justify-content-center align-items-center p-0">
                                <div class="d-flex align-items-center justify-content-center p-4 pb-1">
                                    <?php echo $info_doc['imagen_logo']; ?>
                                </div>
                            </div>
                            <div class="card-footer border-0 bg-transparent d-grid justify-content-center align-items-center">
                                <button class="text-center btn btn-outline-success text-center link-documento display-4 " style="width: 200px;">
                                    <?php echo $info_doc['link_logo']; ?>
                                </button>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <!-- ./Documentos -->


    <!-- Contenido en inglés -->
    <script>
        document.addEventListener('DOMContentLoaded', ()=> {
        const idiomaSeleccionado = localStorage.getItem("idioma");
        const titulo = document.querySelector('.title-general');
        const contentDocumentos = document.querySelector('.con-doc');

            if(idiomaSeleccionado === 'en-US') {
                <?php  $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true ); ?>
                titulo.textContent = `<?php echo $tituloPrincipal ?>`;      

                contentDocumentos.innerHTML = `
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 justify-content-center">
                    <?php  $informacion_documentos = get_post_meta( get_the_ID(), 'reforestamos_group_documento', true ); 
                        if($informacion_documentos === "") { ?>
                            <p> </p>
                        <?php } else { 

                            foreach($informacion_documentos as $info_doc) {
                            ?> 
                                <div class="col">
                                    <div class="card d-grid justify-content-center align-items-center border-0 h-100">
                                        <div class="card-body d-flex justify-content-center align-items-center p-0">
                                            <div class="d-flex align-items-center justify-content-center p-4 pb-1">
                                                <?php echo $info_doc['imagen_logo']; ?>
                                            </div>
                                        </div>
                                        <div class="card-footer border-0 bg-transparent d-grid justify-content-center align-items-center">
                                            <button class="text-center btn btn-outline-success text-center link-documento display-4 " style="width: 200px;">
                                                <?php echo $info_doc['link_logo_en']; ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                        <?php } ?>
                    </div>
                `;
            }
        })
    </script>
    <!-- Contenido en inglés -->