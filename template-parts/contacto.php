<?php   /** printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true )); */   ?>

    <!-- formulario de contacto -->
    <main class="container espacio-lineas-accion">   
        <div class="row row-cols-1 row-cols-md-2 justify-content-center align-items-center">
			<div class="col d-flex justify-content-center">
			  <div class="py-3">
				  <?php echo the_content(); ?>
			  </div>  
          	</div>	
        </div>
    </main>
    <!-- ./formulario de contacto -->


    <!-- Ubicaciones Reforestamos -->
    <section class="container con-ub">
        <div class="row row-cols-1 row-cols-lg-2 justify-content-center align-items-center p-md-3">
            <?php $ubicaciones = get_post_meta( get_the_ID(), 'reforestamos_group_seccion_contacto_ubicacion', true ); ?>
            <?php if(!empty($ubicaciones)) { 
                foreach($ubicaciones as $ubicacion){ ?>
                    <div class="col d-flex justify-content-center align-items-center my-3">
                        <div class="card h-100 w-md-75 border-0 shadow">  
							<h3 class="text-center my-5 text-primary"><i class="bi bi-globe"></i> <span class="titulo-oficina"><?php echo $ubicacion['titulo_ubicacion']; ?></span></h3>
							
                            <div class="card-header border-0 bg-transparent p-3 text-center d-flex justify-content-center align-items-center">

                                <a href="<?php echo $ubicacion['link_dicreccion_oficina']; ?>" target="_blank">
                                    <?php echo $ubicacion['imagen_oficina']; ?>
                                </a>
                                
                            </div>
            
                            <div class="card-body p-2">
								<table class="table">
									<thead>
										
									</thead>
									<tbody>
										<tr>
											<th class="border-0"><span class="text-primary m-2 h6"><i class="bi bi-geo-alt"></i></span></th>
											<th class="border-0"> <h6 class="card-title text-primary"> <?php echo $ubicacion['ubicacion_oficina']; ?></h6> </th>
										</tr>
										<tr> 
											<th class="border-0"><span class="text-primary m-2 h6"><i class="bi bi-telephone"></i></span></th>
											<th class="border-0"><h6 class="text-primary text-primary"><a class="text-decoration-none" href="tel:<?php echo $ubicacion['tel_oficina']; ?>"><?php echo $ubicacion['tel_oficina']; ?></a></h6></th>
										</tr>
										<tr>
											<th class="border-0"><span class="text-primary m-2 h6"><i class="bi bi-envelope"></i></span></th>
											<th class="border-0"><h6 class="text-primary text-primary"><a class="text-decoration-none" href ="mailto:<?php echo $ubicacion['mail_oficina']; ?>"><?php echo $ubicacion['mail_oficina']; ?></a></h6></th>
										</tr>
									</tbody>
								</table>
   
                            </div>

                        </div>

                    </div>
            <?php }
            } else { ?> 
                   <h1> Error </h1> 
            <?php }?>
        </div>
    </section>
    <!-- ./Ubicaciones Reforestamos -->


