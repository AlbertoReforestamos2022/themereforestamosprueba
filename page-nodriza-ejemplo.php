<?php get_header(); 
    /*
    * Template Name: Página Ejemplo Nave **
    */
?>

    <!-- Nave nodriza pop-up -->
    <section class="" style="display:flex; align-items:center; margin-top:220px; ">
        <div class="container">
            <div class="row">
                <div class="col d-flex align-items-center justify-content-center">
                    <div class="card shadow-lg border-0 py-4 w-75">

                        <div class="card-body text-center">    
                            <h2 class="text-primary">Conoce el enfoque sistémico de nuestras iniciativas</h2>
                            <h2 class="fw-bold text-center"><a class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#naveModal"> da clic aquí </a></h2>                                               
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ./ Nave nodriza pop-up -->

        <!-- Modal -->
        <div class="modal fade" id="naveModal" tabindex="-1" aria-labelledby="navelLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="p-3">
                        <h1 class=" text-center text-primary" id="naveLabel">Enfoque sistémico de nuestras iniciativas</h1>
                    </div>
                    <div class="modal-body">
                        <!-- Instrucciones Nave -->
                        <div class="container" style="padding-top:30px; padding-bottom:30px;">
                            <div class="row">
                                <div class="col text-center">
                                    <h2 class="text-light">Instrucciones</h2>

                                    <h5 class="text-dark-50">Para obtener más información haz click en el número de la inicitaiva de tú interés </h5>
                                </div>
                            </div>
                        </div>
                        <!-- Instrucciones Nave -->

                        <!-- Nave nodriza -->
                        <div class="container">
                            <div class="row row-cols-1 row-cols-lg-2">
                                <div class="col col-lg-8 modal-nave-nodriza">
                                    <div class="nave-nodriza-content">
                                        <img class="img-fluid nave-nodriza-img" width="900" src="http://www.reforestamosmexico.org/wp-content/uploads/2023/06/photo_2023-06-20_16-50-55.jpg" alt="">
                                        <div class="nave-nodriza-info">

                                        </div>
                                        <div class="nave-nodriza-alert">

                                        </div>
                                    </div>
                                </div>

                                <div class="col col-lg-4 d-flex align-items-center">
                                    <div class="card border-0 w-100">
                                        <div class="card-body border-0">
                                            <ol class="list-group">
                                                <li class="list-group-item item-modelos" id="modelosPaisajes">Modelos Ejemplares de Manejo de Paisajes:</li>
                                                <li class="list-group-item item-politicas" id="politicasPublicas">Políticas Públicas Efectivas:</li>
                                                <li class="list-group-item item-emprendimiento text-black-50" id="comunidadesEmprendimiento">Comunidades de Emprendimiento:</li>
                                                <li class="list-group-item item-privado" id="sectorPrivado">Compromisos del Sector Privado:</li>
                                                <li class="list-group-item item-ciudadano" id="empoderamientoCiudadano">Campañas de Empoderamiento Ciudadano:</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="cols">
                                    <div class="modales-content">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Nave nodriza -->

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
<?php get_footer(); ?>