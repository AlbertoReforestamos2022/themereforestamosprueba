<?php  /**  printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true )); */  ?>
    
    
    <!-- Video Incendios forestales -->
    <div class="background-incendios p-3 se-in-fo" style="background-color:rgba(236, 67, 44,.82)!important;">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-xl-2 align-items-center">

                <div class="col">
                    <div class="card text-white bg-transparent border-0 gap-0 gap-sm-2">
                        <div class="card-header border-0 bg-transparent p-md-3">
                            <?php  $titulo_incendios = get_post_meta( get_the_ID(), 'titulo_video', true ); ?>
                            <h2 class="tit-incendios-uno"> <?php echo $titulo_incendios; ?> </h2>
                        </div>
                        <div class="card-body py-3 tex-incendios-uno">
                            <?php $texto_1_incendios = get_post_meta( get_the_ID(), 'texto_1_incendios', true ); ?>
                            <?php echo $texto_1_incendios; ?>
                        </div>
                        <div class="card-footer border-0 bg-transparent p-md-3 tex-incendios-dos" >
                            <?php $texto_2_incendios = get_post_meta( get_the_ID(), 'texto_2_incendios', true ); ?>
                            <?php echo $texto_2_incendios; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col mt-3 mt-xl-0">
                    <?php $video_incendios = get_post_meta( get_the_ID(), 'video_incendios', true ); ?>
                    <?php echo $video_incendios; ?>
                    <!-- <video class="video-incendios shadow rounded-2" src="img/IncendiosForestales/IncendiosForestales.mp4" autoplay muted preload="auto" loop></video> -->
                </div>
            </div>
        </div>
    </div>
    <!-- ./Video Incendios forestales -->


    <!-- Donar -->
    <main class="donar-section se-do" style="background-color: #FAFBFE;padding-top:50px; padding-bottom:50px!important;">
        <div class="container">
            <?php $titulo_donar = get_post_meta( get_the_ID(), 'titulo_donar', true ); ?>
            <h2 class="text-center fw-bold text-danger my-4 titulo-donar"> <?php echo $titulo_donar; ?> </h2>
            <div class="row my-3 justify-content-center align-items-center">
                <div class="col-lg-4">
                    <div class="info-horizontal bg-white shadow p-5" style="height:500px;">
                        <div class="icon text-danger my-3">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                                        <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                    </svg>
                                </div>
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                                        <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                    </svg>
                                </div>
                            </div>
                            
                        </div>

                        <div class="description">
                            <h5 class="text-danger text-center fw-bold tit-donar-paypal">Por medio de una donación a nuestra cuenta PayPal:</h5>
                            <div class="d-flex justify-content-center" style="margin-top: 40px; margin-bottom:10px;">
                                <?php $imagen_paypal = get_post_meta( get_the_ID(), 'imagen_paypal', true ); ?>
                                <form action="https://www.paypal.com/donate" method="post" target="_blank">
                                    <input type="hidden" name="hosted_button_id" value="795TV9595M8AG" />
                                    <input type="image" src="http://www.reforestamosmexico.org/wp-content/uploads/2023/04/donar.png"  width="150" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donar con el botón PayPal" />
                                    <img alt="" border="0" src="https://www.paypal.com/es_MX/i/scr/pixel.gif" width="1" height="1" />
                                </form>
                            </div>
                            <div class="d-flex justify-content-center mt-5">
                                <?php $imagen_donar = get_post_meta( get_the_ID(), 'imagen_donar', true ); ?>
                                <?php echo $imagen_donar; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 px-lg-1 mt-lg-0 mt-4">
                    <div class="info-horizontal shadow rounded-1 p-5 px-4" style="height: 500px;background-color:rgba(236, 67, 44,.82)!important;">
                        <div class="icon">
                            <div class="d-flex justify-content-between my-3">
                                <div class="text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7H0zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1z"/>
                                    </svg>
                                </div>
                                <div class="text-white">
                                    <div class="text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7H0zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="description">
                            <?php $titulo_datos_bancarios = get_post_meta( get_the_ID(), 'titulo_datos_bancarios', true ); ?>
                            <h5 class="text-center text-white fw-bold tit-datos-banc"><?php echo $titulo_datos_bancarios; ?></h5>
                            <div class="text-white dat-benef" style="margin-top:40px;">
                                <?php $datos_beneficiario = get_post_meta( get_the_ID(), 'datos_beneficiario', true ); ?>
                                <?php echo $datos_beneficiario; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="container mt-sm-5">
            <div class="my-sm-3 mb-3 shadow rounded-2" style="padding:30px;background-color:rgba(236, 67, 44,.82)!important;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 ms-lg-5">
                            <div class="info-horizontal">
                                <div class="icon">
                                    <div class="d-flex justify-content-between my-3">
                                        <div class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-fire" viewBox="0 0 16 16">
                                                <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16Zm0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15Z"/>
                                            </svg>
                                        </div>
                                        <div class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-fire" viewBox="0 0 16 16">
                                                <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16Zm0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15Z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="description">
                                    <h3 class="text-white fw-bold tit-text-cierre">Los brigadistas que trabajan combatiendo los incendios necesitan de tu apoyo </h3>
                                    <div class="text-white text-cierre" style="margin-top:50px;">
                                    <?php $texto_cierre = get_post_meta( get_the_ID(), 'texto_cierre', true ); ?>
                                        <!-- <p>Tu ayuda hace la diferencia, juntos podemos acabar con este incendio forestal y restaurar los suelos que han sufrido grandes daños.</p> -->
                                        <?php echo $texto_cierre; ?>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ./Donar -->

    <!-- Infográfia Incendios -->
    <div class="container pri-inf">
        <?php $titulo_infografia_1 = get_post_meta( get_the_ID(), 'titulo_infografia_1', true ); ?>

        <h4 class="text-danger fw-semibold text-center my-5 tit-infogr-u"> <?php echo $titulo_infografia_1; ?> </h4>

        <div class="row row-cols-1">
            <div class="col timeline-incendios">
            <?php $infografias_1 = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_infografia_1', true ); ?>


            <?php 
                if($infografias_1) {
                    foreach($infografias_1 as $infografia) { ?>

                    <div class="contenido <?php echo $infografia['posicion_campo_infografia']; ?>"> 
                        <div class="card border-0 shadow-lg rounded-2">
							
							<div class="row g-0 justify-content-center">
								<div class="col-6 col-lg-4 d-flex justify-content-center align-items-center img-incendios">
									<img class="img-fluid p-2 p-md-1" src="<?php echo $infografia['imagen_infografia']; ?>" width="120" alt="<?php echo $infografia['alt_text']; ?>">
								</div>
								<div class="col-lg-8 d-grid align-items-center">
									<div class="card-body texto-incedios text-center text-danger">
										<h5 class="h6 mx-2 desc-infogr-u"><?php echo $infografia['desc_infografia']; ?></h5>
									</div>
								</div>
							</div>
							
                        </div>
                    </div>

                    <?php }
                } else {
                    var_dump($infografias_1);
                } ?>

            </div>
        </div>

    </div>
    <!-- ./Infográfia Incendios -->

    <!-- Segunda Infográfia incendios forestales -->
    <div class="container seg-inf">
        <?php $titulo_infografia_2 = get_post_meta( get_the_ID(), 'titulo_infografia_2', true ); ?>       
        <h3 class="text-danger fw-semibold my-5 text-center tit-infgr-d"><?php echo $titulo_infografia_2?></h3>

        <?php $infografias_2 = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_infografia_2', true ); ?>

        <div class="row row-cols-1">
            <div class="col timeline-incendios">
            <?php 
                if($infografias_2) {
                     foreach($infografias_2 as $infografia) { ?>

                    <div class="contenido <?php echo $infografia['posicion_campo_infografia']; ?>"> 
                        <div class="card border-0 shadow-lg rounded-2">
							
							<div class="row g-0 justify-content-center">
								<div class="col-6 col-lg-4 d-flex justify-content-center align-items-center img-incendios">
									<img class="img-fluid p-2 p-md-1" src="<?php echo $infografia['imagen_infografia']; ?>" width="120" alt="<?php echo $infografia['alt_text']; ?>">
								</div>
								<div class="col-lg-8 d-grid align-items-center">
									<div class="card-body texto-incedios text-center text-danger">
										<h5 class="h6 mx-2 desc-infogr-d"><?php echo $infografia['desc_infografia']; ?></h5>
									</div>
								</div>
							</div>

                        </div>
                    </div>

                    <?php }
                } else {
                    var_dump($infografias_2);
                } ?>

            </div>
        </div>

        <div class="row">
            <div class="col mt-5">
                <div class="card border-0">
                    <div class="card-body">
                    <?php $texto_footer_infografia = get_post_meta( get_the_ID(), 'text_footer', true ); ?>
                        <h5 class="card-title text-black-50 footer-inf-dos"><?php echo $texto_footer_infografia; ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Segunda Infográfia incendios forestales -->

    <!-- Acciones Reforestamos Mexico -->
    <section class="p-1 p-md-5 ac-re" style="background-color:rgba(245, 209, 56, .75)!important;">
        <div class="container-xl">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <div class="card shadow-lg border-0 py-4 w-75">
                        <div class="card-header border-0 bg-transparent text-center">
                            <?php $titulo_acciones= get_post_meta( get_the_ID(), 'titulo_acciones', true ); ?>
                            

                            <h3 class="text-primary tit-acc"><?php echo $titulo_acciones; ?></h3>
                        </div>
                        <div class="card-body desc-acc">    
                            <?php $titulo_acciones= get_post_meta( get_the_ID(), 'lista_acciones', true ); ?>
                            <?php echo $titulo_acciones;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ./Acciones Reforestamos México -->

    <!-- Que se necesita -->
    <div class="container qu-nec">
        <div class="card-header border-0 bg-transparent text-center">
            <?php $titulo_necesidades = get_post_meta( get_the_ID(), 'titulo_necesidades', true ); ?> 
            
            <h2 class="text-primary mt-5 tit-necesidades"><?php echo $titulo_necesidades; ?></h2>
        </div>

        <div class="row row-cols-1 row-cols-md-1 row-cols-lg-3 mt-5">
            <?php $necesidades = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_necesidades', true ); ?>
            <?php 
                if($necesidades) { ?>
                    <?php foreach($necesidades as $necesidad) { ?>
                        <div class="col d-flex justify-content-center my-4">
                            <div class="card border-0 shadow-lg h-100" style="width:25rem;" >
                                <div class="card-header bg-transparent border-0 d-flex justify-content-center align-items-center mb-3">
                                    <img class="img-fluid" src="<?php echo $necesidad['imagen_card']; ?>" width="300" alt="<?php echo $necesidad['titulo_card']; ?> imagen">
                                    
                                </div>
                                <div class="card-body card-neces">
                                <h5 class="text-light text-center my-3 tit-card-neces"><?php echo $necesidad['titulo_card']; ?></h5>
                                    <?php echo $necesidad['lista_card']; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
            <?php } ?>

        </div>
    </div>
    <!-- ./Que se necesita -->

    <!-- background-image: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.2)), url(<?php echo $background_incendios; ?>);-->

    <!-- Incendios a nivel nacional -->
    <?php $background_incendios = get_post_meta( get_the_ID(), 'background_incendios', true ); ?>
    <section class="mapa-incendios se-in-na" style="background-image: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.2)), url(<?php echo $background_incendios; ?>);">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gap-5 justify-content-center gap-lg-0 my-5"> 
            <?php $tarjetas_informativas = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_informacion_diaria', true ); ?>
           
            <?php 
                if($tarjetas_informativas){
                    foreach($tarjetas_informativas as $tarjeta) { ?>
                        <div class="col">
                            <div class="card border-danger bg-transparent h-100 p-3 d-grid align-items-center">
                                <div class="card-header bg-transparent border-0 text-white text-center">
                                    <?php echo $tarjeta['titulo_tarjeta']; ?>
                                </div>
                                <div class="card-body text-center text-white">
                                    <h3><?php echo $tarjeta['info_tarjeta']; ?></h3>
                                </div>
                            </div>
                        </div>
                    <?php  }

                } else {?>
                    <p class="text-danger"> Error </p>
                <?php } ?>        

            </div>
        </div>

        <div class="mt-4" style="background-color:rgba(236, 67, 44,.82)!important;">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="card border-0 bg-transparent p-5">
                            <div class="card-body">
                                <?php $lista_informativa = get_post_meta( get_the_ID(), 'tarjeta_informativa', true ); ?>
                                <?php echo $lista_informativa; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 justify-content-center mt-5">
                <div class="col">
                    <?php $mapa_incendios = get_post_meta( get_the_ID(), 'mapa_incendios', true ); ?>
                    <?php echo $mapa_incendios; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- ./Incendios a nivel nacional -->

    <!-- Donar -->
    <main class="donar-section se-do" style="background-color: #FAFBFE;padding-top:50px; padding-bottom:50px!important;">
        <div class="container">
            <?php $titulo_donar = get_post_meta( get_the_ID(), 'titulo_donar', true ); ?>
            <h2 class="text-center fw-bold text-danger my-4 titulo-donar"> <?php echo $titulo_donar; ?> </h2>
            <div class="row my-3 justify-content-center align-items-center">
                <div class="col-lg-4">
                    <div class="info-horizontal bg-white shadow p-5" style="height:500px;">
                        <div class="icon text-danger my-3">
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                                        <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                    </svg>
                                </div>
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                                        <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                    </svg>
                                </div>
                            </div>
                            
                        </div>

                        <div class="description">
                            <h5 class="text-danger text-center fw-bold tit-donar-paypal">Por medio de una donación a nuestra cuenta PayPal:</h5>
                            <div class="d-flex justify-content-center" style="margin-top: 40px; margin-bottom:10px;">
                                <?php $imagen_paypal = get_post_meta( get_the_ID(), 'imagen_paypal', true ); ?>
                                <form action="https://www.paypal.com/donate" method="post" target="_blank">
                                    <input type="hidden" name="hosted_button_id" value="795TV9595M8AG" />
                                    <input type="image" src="http://www.reforestamosmexico.org/wp-content/uploads/2023/04/donar.png"  width="150" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donar con el botón PayPal" />
                                    <img alt="" border="0" src="https://www.paypal.com/es_MX/i/scr/pixel.gif" width="1" height="1" />
                                </form>
                            </div>
                            <div class="d-flex justify-content-center mt-5">
                                <?php $imagen_donar = get_post_meta( get_the_ID(), 'imagen_donar', true ); ?>
                                <?php echo $imagen_donar; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 px-lg-1 mt-lg-0 mt-4">
                    <div class="info-horizontal shadow rounded-1 p-5 px-4" style="height: 500px;background-color:rgba(236, 67, 44,.82)!important;">
                        <div class="icon">
                            <div class="d-flex justify-content-between my-3">
                                <div class="text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7H0zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1z"/>
                                    </svg>
                                </div>
                                <div class="text-white">
                                    <div class="text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7H0zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="description">
                            <?php $titulo_datos_bancarios = get_post_meta( get_the_ID(), 'titulo_datos_bancarios', true ); ?>
                            <h5 class="text-center text-white fw-bold tit-datos-banc"><?php echo $titulo_datos_bancarios; ?></h5>
                            <div class="text-white dat-benef" style="margin-top:40px;">
                                <?php $datos_beneficiario = get_post_meta( get_the_ID(), 'datos_beneficiario', true ); ?>
                                <?php echo $datos_beneficiario; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="container mt-sm-5">
            <div class="my-sm-3 mb-3 shadow rounded-2" style="padding:30px;background-color:rgba(236, 67, 44,.82)!important;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 ms-lg-5">
                            <div class="info-horizontal">
                                <div class="icon">
                                    <div class="d-flex justify-content-between my-3">
                                        <div class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-fire" viewBox="0 0 16 16">
                                                <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16Zm0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15Z"/>
                                            </svg>
                                        </div>
                                        <div class="text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-fire" viewBox="0 0 16 16">
                                                <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16Zm0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15Z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="description">
                                    <h3 class="text-white fw-bold tit-text-cierre">Los brigadistas que trabajan combatiendo los incendios necesitan de tu apoyo </h3>
                                    <div class="text-white text-cierre" style="margin-top:50px;">
                                    <?php $texto_cierre = get_post_meta( get_the_ID(), 'texto_cierre', true ); ?>
                                        <!-- <p>Tu ayuda hace la diferencia, juntos podemos acabar con este incendio forestal y restaurar los suelos que han sufrido grandes daños.</p> -->
                                        <?php echo $texto_cierre; ?>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ./Donar -->

    <!-- Aliados -->
    <section class="container se-al"> 
        <?php $titulo_aliados = get_post_meta( get_the_ID(), 'titulo_aliados', true );?>
        <h3 class="text-danger fw-semibold my-5 text-center tit-alia"><?php echo $titulo_aliados; ?></h3>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 justify-content-center"> 
            <?php $logos_aliados = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_imagenes_aliados', true ); ?>

            <?php if($logos_aliados) { ?>
                <?php foreach($logos_aliados as $logo_aliado){ ?>
                    <div class="col">
                        <div class="card border-0">
                            <div class="card-body d-flex justify-content-center">
                                <?php echo $logo_aliado['logo_aliados']; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>    
            <?php } else { ?>
                <p class="text-danger"> Error </p>
            <?php } ?>
        </div>

        <div class="row">
            <div class="col my-5 foot-alia">
                <?php $footer_aliados = get_post_meta( get_the_ID(), 'texto_aliados_footer', true ); ?>

                <?php echo $footer_aliados; ?>

            </div>
        </div>
    </section>
    <!-- ./Aliados -->


    <!-- Contenido en inglés -->
    <script>
        document.addEventListener('DOMContentLoaded', ()=> {
            const idiomaSeleccionado = localStorage.getItem("idioma");
            const titulo = document.querySelector('.title-general');
            // Video incendios forestales  .
            const seccionIncendios = document.querySelector('.se-in-fo');
            // ¿Cómo puedes ayudar? - Donar   .se-do
            const seccionDonar = document.querySelectorAll('.se-do');      
            // 1ra infografía  .pri-inf
            const seccionPrimeraInf = document.querySelector('.pri-inf');
            // 2da infografía  .seg-inf
            const seccionSegundaInf = document.querySelector('.seg-inf');
            // Acciones Reforestamos  .ac-re
            const seccionAccionesRMX = document.querySelector('.ac-re')
            // ¿Qué se necesita?   .qu-nec
            const seccionNecesidades = document.querySelector('.qu-nec');
            
            // Incendios a nivel nacional .se-in-na
            const seccionIncendiosNac = document.querySelector('.se-in-na');
            // ¿Cómo puedes ayudar? - Donar
            
            // Aliados .se-al
            const seccionAliados = document.querySelector('.se-al');

            if(idiomaSeleccionado === 'en-US') {
                <?php  $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true ); ?>
                titulo.textContent = `<?php echo $tituloPrincipal ?>`;     

                seccionIncendios.innerHTML = `
                <div class="container-fluid">
                    <div class="row row-cols-1 row-cols-xl-2 align-items-center">
                        <div class="col">
                            <div class="card text-white bg-transparent border-0 gap-0 gap-sm-2">
                                <div class="card-header border-0 bg-transparent p-md-3">
                                    <?php  $titulo_incendios = get_post_meta( get_the_ID(), 'titulo_video_en', true ); ?>
                                    <h2 class="tit-incendios-uno"> <?php echo $titulo_incendios; ?> </h2>
                                </div>
                                <div class="card-body py-3 tex-incendios-uno">
                                    <?php $texto_1_incendios = get_post_meta( get_the_ID(), 'texto_1_incendios_en', true ); ?>
                                    <?php echo $texto_1_incendios; ?>
                                </div>
                                <div class="card-footer border-0 bg-transparent p-md-3 tex-incendios-dos" >
                                    <?php $texto_2_incendios = get_post_meta( get_the_ID(), 'texto_2_incendios_en', true ); ?>
                                    <?php echo $texto_2_incendios; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col mt-3 mt-xl-0">
                            <?php $video_incendios = get_post_meta( get_the_ID(), 'video_incendios', true ); ?>
                            <?php echo $video_incendios; ?>
                            <!-- <video class="video-incendios shadow rounded-2" src="img/IncendiosForestales/IncendiosForestales.mp4" autoplay muted preload="auto" loop></video> -->
                        </div>
                    </div>
                </div>          
                `; 

                seccionDonar.forEach((contenido)=> {
                    contenido.innerHTML = `
                    <div class="container">
                        <?php $titulo_donar = get_post_meta( get_the_ID(), 'titulo_donar_en', true ); ?>
                        <h2 class="text-center fw-bold text-danger my-4 titulo-donar"> <?php echo $titulo_donar; ?> </h2>
                        <div class="row my-3 justify-content-center align-items-center">
                            <div class="col-lg-4">
                                <div class="info-horizontal bg-white shadow p-5" style="height:500px;">
                                    <div class="icon text-danger my-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                                                    <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                                </svg>
                                            </div>
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                                                    <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="description">
                                        <h5 class="text-danger text-center fw-bold tit-donar-paypal">Through a donation to our PayPal account:</h5>
                                        <div class="d-flex justify-content-center" style="margin-top: 40px; margin-bottom:10px;">
                                            <?php $imagen_paypal = get_post_meta( get_the_ID(), 'imagen_paypal', true ); ?>
                                            <form action="https://www.paypal.com/donate" method="post" target="_blank">
                                                <input type="hidden" name="hosted_button_id" value="795TV9595M8AG" />
                                                <input type="image" src="http://www.reforestamosmexico.org/wp-content/uploads/2023/04/donar.png"  width="150" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donar con el botón PayPal" />
                                                <img alt="" border="0" src="https://www.paypal.com/es_MX/i/scr/pixel.gif" width="1" height="1" />
                                            </form>
                                        </div>
                                        <div class="d-flex justify-content-center mt-5">
                                            <?php $imagen_donar = get_post_meta( get_the_ID(), 'imagen_donar', true ); ?>
                                            <?php echo $imagen_donar; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 px-lg-1 mt-lg-0 mt-4">
                                <div class="info-horizontal shadow rounded-1 p-5 px-4" style="height: 500px;background-color:rgba(236, 67, 44,.82)!important;">
                                    <div class="icon">
                                        <div class="d-flex justify-content-between my-3">
                                            <div class="text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7H0zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1z"/>
                                                </svg>
                                            </div>
                                            <div class="text-white">
                                                <div class="text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7H0zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="description">
                                        <?php $titulo_datos_bancarios = get_post_meta( get_the_ID(), 'titulo_datos_bancarios_en', true ); ?>
                                        <h5 class="text-center text-white fw-bold tit-datos-banc"><?php echo $titulo_datos_bancarios; ?></h5>
                                        <div class="text-white dat-benef" style="margin-top:40px;">
                                            <?php $datos_beneficiario = get_post_meta( get_the_ID(), 'datos_beneficiario_en', true ); ?>
                                            <?php echo $datos_beneficiario; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container mt-sm-5">
                        <div class="my-sm-3 mb-3 shadow rounded-2" style="padding:30px;background-color:rgba(236, 67, 44,.82)!important;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-8 ms-lg-5">
                                        <div class="info-horizontal">
                                            <div class="icon">
                                                <div class="d-flex justify-content-between my-3">
                                                    <div class="text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-fire" viewBox="0 0 16 16">
                                                            <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16Zm0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15Z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-fire" viewBox="0 0 16 16">
                                                            <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16Zm0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15Z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="description">
                                                <h3 class="text-white fw-bold tit-text-cierre text-center">The brigadiers working on the fires need your support</h3>
                                                <div class="text-white text-cierre" style="margin-top:50px;">
                                                    <?php $texto_cierre = get_post_meta( get_the_ID(), 'texto_cierre_en', true ); ?>
                                                    <!-- <p>Tu ayuda hace la diferencia, juntos podemos acabar con este incendio forestal y restaurar los suelos que han sufrido grandes daños.</p> -->
                                                    <?php echo $texto_cierre; ?>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                 
                    `;
                })

                seccionPrimeraInf.innerHTML = `
                <?php $titulo_infografia_1 = get_post_meta( get_the_ID(), 'titulo_infografia_1_en', true ); ?>
                <h4 class="text-danger fw-semibold text-center my-5 tit-infogr-u"> <?php echo $titulo_infografia_1; ?> </h4>

                <div class="row row-cols-1">
                    <div class="col timeline-incendios">
                    <?php $infografias_1 = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_infografia_1', true ); ?>


                    <?php 
                        if($infografias_1) {
                            foreach($infografias_1 as $infografia) { ?>

                            <div class="contenido <?php echo $infografia['posicion_campo_infografia']; ?>"> 
                                <div class="card border-0 shadow-lg rounded-2">
                                    
                                    <div class="row g-0 justify-content-center">
                                        <div class="col-6 col-lg-4 d-flex justify-content-center align-items-center img-incendios">
                                            <img class="img-fluid p-2 p-md-1" src="<?php echo $infografia['imagen_infografia']; ?>" width="120" alt="<?php echo $infografia['alt_text_en']; ?>">
                                        </div>
                                        <div class="col-lg-8 d-grid align-items-center">
                                            <div class="card-body texto-incedios text-center text-danger">
                                                <h5 class="h6 mx-2 desc-infogr-u"><?php echo $infografia['desc_infografia_en']; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <?php }
                        } else {
                            var_dump($infografias_1);
                        } ?>

                    </div>
                </div>
                `;

                seccionSegundaInf.innerHTML = `
                <?php $titulo_infografia_2 = get_post_meta( get_the_ID(), 'titulo_infografia_2_en', true ); ?>       
                <h3 class="text-danger fw-semibold my-5 text-center tit-infgr-d"><?php echo $titulo_infografia_2?></h3>

                <?php $infografias_2 = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_infografia_2', true ); ?>

                <div class="row row-cols-1">
                    <div class="col timeline-incendios">
                    <?php 
                        if($infografias_2) {
                            foreach($infografias_2 as $infografia) { ?>

                            <div class="contenido <?php echo $infografia['posicion_campo_infografia']; ?>"> 
                                <div class="card border-0 shadow-lg rounded-2">
                                    
                                    <div class="row g-0 justify-content-center">
                                        <div class="col-6 col-lg-4 d-flex justify-content-center align-items-center img-incendios">
                                            <img class="img-fluid p-2 p-md-1" src="<?php echo $infografia['imagen_infografia']; ?>" width="120" alt="<?php echo $infografia['alt_text_en']; ?>">
                                        </div>
                                        <div class="col-lg-8 d-grid align-items-center">
                                            <div class="card-body texto-incedios text-center text-danger">
                                                <h5 class="h6 mx-2 desc-infogr-d"><?php echo $infografia['desc_infografia_en']; ?></h5>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <?php }
                        } else {
                            var_dump($infografias_2);
                        } ?>

                    </div>
                </div>

                <div class="row">
                    <div class="col mt-5">
                        <div class="card border-0">
                            <div class="card-body">
                            <?php $texto_footer_infografia = get_post_meta( get_the_ID(), 'text_footer_en', true ); ?>
                                <h5 class="card-title text-black-50 footer-inf-dos"><?php echo $texto_footer_infografia; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>            
                `;

                seccionAccionesRMX.innerHTML = `
                <div class="container-xl">
                    <div class="row">
                        <div class="col d-flex justify-content-center">
                            <div class="card shadow-lg border-0 py-4 w-75">
                                <div class="card-header border-0 bg-transparent text-center">
                                    <?php $titulo_acciones= get_post_meta( get_the_ID(), 'titulo_acciones_en', true ); ?>
                                    <h3 class="text-primary tit-acc"><?php echo $titulo_acciones; ?></h3>
                                </div>
                                <div class="card-body desc-acc">    
                                    <?php $titulo_acciones= get_post_meta( get_the_ID(), 'lista_acciones_en', true ); ?>
                                    <?php echo $titulo_acciones;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>            
                `;

                seccionNecesidades.innerHTML = `
                <div class="card-header border-0 bg-transparent text-center">
                    <?php $titulo_necesidades = get_post_meta( get_the_ID(), 'titulo_necesidades_en', true ); ?> 
                    
                    <h2 class="text-primary mt-5 tit-necesidades"><?php echo $titulo_necesidades; ?></h2>
                </div>

                <div class="row row-cols-1 row-cols-md-1 row-cols-lg-3 mt-5">
                    <?php $necesidades = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_necesidades', true ); ?>
                    <?php 
                        if($necesidades) { ?>
                            <?php foreach($necesidades as $necesidad) { ?>
                                <div class="col d-flex justify-content-center my-4">
                                    <div class="card border-0 shadow-lg h-100" style="width:25rem;" >
                                        <div class="card-header bg-transparent border-0 d-flex justify-content-center align-items-center mb-3">
                                            <img class="img-fluid" src="<?php echo $necesidad['imagen_card']; ?>" width="300" alt="<?php echo $necesidad['titulo_card_en']; ?> image">
                                            
                                        </div>
                                        <div class="card-body card-neces">
                                        <h5 class="text-light text-center my-3 tit-card-neces"><?php echo $necesidad['titulo_card_en']; ?></h5>
                                            <?php echo $necesidad['lista_card_en']; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                    <?php } ?>

                </div>            
                `;

                seccionIncendiosNac.innerHTML = `
                <div class="container">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 gap-5 justify-content-center gap-lg-0 my-5"> 
                    <?php $tarjetas_informativas = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_informacion_diaria', true ); ?>
                
                    <?php 
                        if($tarjetas_informativas){
                            foreach($tarjetas_informativas as $tarjeta) { ?>
                                <div class="col">
                                    <div class="card border-danger bg-transparent h-100 p-3 d-grid align-items-center">
                                        <div class="card-header bg-transparent border-0 text-white text-center">
                                            <?php echo $tarjeta['titulo_tarjeta_en']; ?>
                                        </div>
                                        <div class="card-body text-center text-white">
                                            <h3><?php echo $tarjeta['info_tarjeta_en']; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            <?php  }

                        } else {?>
                            <p class="text-light"> Comunicate con el admin del sitio </p>
                        <?php } ?>        

                    </div>
                </div>

                <div class="mt-4" style="background-color:rgba(236, 67, 44,.82)!important;">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <div class="card border-0 bg-transparent p-5">
                                    <div class="card-body text-white text-center">
                                        <?php $lista_informativa = get_post_meta( get_the_ID(), 'tarjeta_informativa_en', true ); ?>
                                        <?php echo $lista_informativa; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row row-cols-1 row-cols-md-2 justify-content-center mt-5">
                        <div class="col">
                            <?php $mapa_incendios = get_post_meta( get_the_ID(), 'mapa_incendios', true ); ?>
                            <?php echo $mapa_incendios; ?>
                        </div>
                    </div>
                </div>            
                `;

                seccionAliados.innerHTML = `
                <?php $titulo_aliados = get_post_meta( get_the_ID(), 'titulo_aliados_en', true );?>
                <h3 class="text-danger fw-semibold my-5 text-center tit-alia"><?php echo $titulo_aliados; ?></h3>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 justify-content-center"> 
                    <?php $logos_aliados = get_post_meta( get_the_ID(), 'reforestamos_group_incendios_imagenes_aliados', true ); ?>

                    <?php if($logos_aliados) { ?>
                        <?php foreach($logos_aliados as $logo_aliado){ ?>
                            <div class="col">
                                <div class="card border-0">
                                    <div class="card-body d-flex justify-content-center">
                                        <?php echo $logo_aliado['logo_aliados']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>    
                    <?php } else { ?>
                        <p class="text-danger"> Error </p>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col my-5 foot-alia">
                        <?php $footer_aliados = get_post_meta( get_the_ID(), 'texto_aliados_footer_en', true ); ?>
                        <?php echo $footer_aliados; ?>

                    </div>
                </div>            
                `;



            }
        })
    </script>
    <!-- Contenido en inglés -->

