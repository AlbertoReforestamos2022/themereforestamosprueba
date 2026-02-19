    <?php  /** Pop-up  */ ?>
    <div class="modal fade" id="webinarModal" tabindex="-1" aria-labelledby="webinarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="carruselPopup" class="carousel slide" data-bs-ride="carousel">
                        <?php $imagenes = array_reverse(get_post_meta( get_the_ID(), 'reforestamos_home_popup', true )); ?>
                        
                        <div class="carousel-indicators" id="popup-indicators">
                            <?php  // Botones Carrusel
                            $indiceImagenes = array_keys($imagenes);

                            foreach($indiceImagenes as $indiceImagen){ ?>
                                <button type="button" data-bs-target="#carruselPopup" data-bs-slide-to="<?php echo $indiceImagen ?>" class="boton-indicadores-popup bg-black p-1" style="border-radius:50%; height:3px; width:3px;" aria-label="Slide <?php echo $indiceImagen ?>"></button>
                            <?php } ?>
                        </div>
                        
                        <!-- 1ra Imagen Carrusel  -->
                        <div class="carousel-inner">
                            <?php foreach($imagenes as $imagen) { ?>
                            <!-- Demás imagenes Carrusel  -->
                            <div class="carousel-item imgs-popup">
                                <a href="<?php echo $imagen['url_imagen_popup']?>">
                                    <img src="<?php echo $imagen['imagen_popup']?>" class="img-fluid imagen-popup"  alt="Imagen Pop up" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                                </a>
                                <rect width="100%" height="100%" fill="#777"/>
                            </div>
                            
                            <?php } ?>    
                        </div>

            
                        <button class="carousel-control-prev justify-content-start ps-2 prev-btn-pop-up" type="button" data-bs-target="#carruselPopup" data-bs-slide="prev">
                            <span class="p-2 rounded-3 bg-black h4" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                                </svg>
                            </span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                
                        <button class="carousel-control-next justify-content-end pe-2 next-btn-pop-up" type="button" data-bs-target="#carruselPopup" data-bs-slide="next">
                            <span class="p-2 rounded-3 bg-black h4" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                                </svg>
                            </span>
                            <span class="visually-hidden">Next</span>
                
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
                    <?php
                        foreach($imagenes as $imagen) {
                            if(!empty($imagen['btn_text']) && !empty($imagen['url_btn'])){ ?>
                            <a class="btn btn-info text-white" href="<?php echo $imagen['url_btn']; ?>" target="_blank"><?php echo  $imagen['btn_text']; ?></a>
                            <?php
                            }
                        }
                    ?>
                    

                </div>
            </div>
        </div>
    </div>
    <?php  /** Pop-up */ ?>