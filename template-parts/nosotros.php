<?php  /** printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true )); */  ?>
     
    <!-- Sobre Nosotros Mision-Vision -->
    <main class="container-xxl m-v-con">
        <div class="row">
            <?php  $nosotros = get_post_meta( get_the_ID(), 'reforestamos_group_nosotros', true ); 
                foreach($nosotros as $nos) {
            ?>  

            <div class="col-12 px-4 py-5">
                <div class="row align-items-center justify-content-center vision shadow rounded-2 p-4 p-2 p-md-5">
                    <div class="col-10 col-sm-8 col-lg-6 d-grid justify-content-center align-items-center">
                        <?php echo $nos['imagen_seccion_nosotros']?>
                    </div>

                    <div class="col-lg-6 mis-vis-info text-primary text-justify">
                    <h2 style="text-align: center;"><?php echo $nos['titulo_seccion_nosotros']?></h2>

                        <h4 style="text-align: center; color:#999999;">
                                <?php echo $nos['descripcion_seccion_nosotros']?> 
                        </h4>
                        
                    </div>

                </div>
            </div> 

        <?php } ?>
        </div>
    </main>
    <!-- /. Sobre Nostros Mision-Vision -->

    <!-- Objetivos -->
    <section class="container-xxl o-con">
        <?php $titulo = get_post_meta( get_the_ID(), 'reforestamos_group_titulo_objetivos', true );?>
        <h1 class="text-primary text-center espacio-lineas-accion t-o"><?php echo $titulo; ?></h1>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 justify-content-center p-lg-1 p-5">
            <!-- bg-danger bg-primary bg-success bg-light  -->

            <?php  $objetivos = get_post_meta( get_the_ID(), 'reforestamos_group_objetivos', true ); 
                foreach($objetivos as $objetivo) {
            ?> 
                <div class="col my-2">
                    <div class="card border-0 shadow-lg h-100 <?php echo $objetivo['background_objetivo'];?>"> 
                        <div class="card-header d-flex justify-content-center border-0 bg-transparent mt-4 mb-0">
                            <img src="<?php echo $objetivo['imagen_objetivos'];?>" class="linea-accion-img" alt="Objetivo <?php echo $objetivo['objetivos']?>">
                        </div>
                        <div class="card-body">
                            <h5 class="text-center text-white fw-bold p-4 c-o"><?php echo $objetivo['objetivos']?></h5>
                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>
    </section>
    <!-- ./objetivos -->

    <!-- Valores -->
    <section class="container-xxl v-con"> 
        <?php $tituloValores = get_post_meta( get_the_ID(), 'reforestamos_group_titulo_valores', true); ?>
        <h1 class="text-primary text-center espacio-lineas-accion t-va"><?php echo $tituloValores ?></h1>

        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 justify-content-center gap-4 gap-md-2 px-5 px-lg-1">
        <?php  $valores = get_post_meta( get_the_ID(), 'reforestamos_group_valores', true ); 
                foreach($valores as $valor) {
        ?> 

            <div class="col my-2">
                <div class="card-cover rounded-2 shadow valores">
                    <div class="d-flex flex-column p-3 pt-5 text-white text-shadow-1">
                        <ul class="d-flex justify-content-center list-unstyled mt-auto">
                            <li class="p-3 p-md-2 valores-img">
                                <img src="<?php echo $valor['imagen_valores']?>" height="150" alt="Generar Información">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        <?php }?>

        </div><!-- /.row row-cols-1 -->
    </section>
    <!-- ./Valores -->
    
    <!-- Procesos de Incidencia -->
    <section class="container p-i-con">
        <?php $tituloProcesos = get_post_meta( get_the_ID(), 'reforestamos_group_titulo_procesos', true); ?>
        <h1 class="text-primary text-center espacio-lineas-accion t-p-i"><?php echo $tituloProcesos ?></h1>

        <div class="row row-cols-1 row-cols-md-4 justify-content-center">
            <?php  $procesos = get_post_meta( get_the_ID(), 'reforestamos_group_procesos', true ); 
                    foreach($procesos as $proceso) {
            ?> 

            <div class="col p-5 p-md-3">
                <div class="card border-0 shadow rounded-2 h-100 d-flex justify-content-center align-items-center">
                    <div class="p-2 proc-list">
                        <img class="img-fluid" width="150" src="<?php echo $proceso['imagen_proceso']?>">
                    </div>
                </div>        
            </div>

        <?php }?>

        </div><!-- /.row row-cols-1 -->

    </section>
    <!-- ./Procesos de Incidencia  -->

    <!-- Logros y Reconocimientos -->
    <section class="container-xxl l-r-con">
        <?php $tituloLogros = get_post_meta( get_the_ID(), 'reforestamos_group_titulo_logros', true); ?>
        <h1 class="text-primary text-center espacio-lineas-accion t-l-r"><?php echo $tituloLogros ?></h1>
        
        <div class="row">
            <div class="container timeline">
                <?php
                    $logros = get_post_meta( get_the_ID(), 'reforestamos_group_logros', true );
                    foreach ($logros as $logro) {
                ?>

                <div class="contenedor <?php echo $logro['posicion_logro'] ?>">
                    <div class="bg-white shadow-lg content logros">

                        <div class="d-flex justify-content-center py-4">
                            <img src="<?php echo $logro['imagen_logros'] ?>" width="<?php echo $logro['tamanio_imagen'] ?>" alt="">
                        </div>
                        <h1 class="text-center text-light fw-semibold fe-l"><?php echo $logro['anio_logro'] ?></h1>
                        <p class="text-black-50 text-justify te-l"><?php echo $logro['texto_logro'] ?></p>

                    </div>
                </div>
                <?php  } ?>
            </div>
        </div>
    </section>
    <!-- ./Logros y Reconocimientos -->







