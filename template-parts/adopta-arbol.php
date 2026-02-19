    <section class="container mb-5 con-in-don content-intro-adopt-es">
		<div class="text-center text-primary py-lg-4">
			<?php $info_principal = get_post_meta( get_the_ID(), 'info_principal', true); ?> 
			<?php echo $info_principal; ?>
        </div>
		
        <div class="row">
            <?php $infos_principales = get_post_meta( get_the_ID(), 'reforestamos_group_adopta_info_principal', true); ?> 

            <?php if($infos_principales !== ' '){
                foreach($infos_principales as $info) { ?>
			  <div class="col-lg-4 mb-3 mb-lg-0 text-center">
				<div class="px-0 px-lg-3">
				  <div class="text-primary mb-3">
						<?php echo $info['info_imagen_principal'];?>
				  </div>
					<?php echo $info['info_texto_principal'];?>	
				</div>
			  </div>
            <?php }
            } else { ?>
                <h3 class="text-danger">Error</h3>
            <?php }?>
        </div>
    </section>
    <!-- ./adopta un árbol texto -->

    <!-- Sección adopción -->
    <section class="container mt-5 cont-se-adop content-cards-adopt-es">
        <div class="row row-cols-1 row-cols-lg-3 justify-content-center align-items-center gap-1">
            <?php $cards_adoptas = get_post_meta( get_the_ID(), 'reforestamos_group_adopta_arbol_principal', true); ?> 
            <?php if($cards_adoptas !== ' ') {
                foreach($cards_adoptas as $card) { ?>
                <div class="col my-3">
                    <div class="card border-0 bg-transparent shadow rounded">
                        <div class="card-header bg-transparent border-0">
                            <?php echo $card['card_header_adopta']; ?>
                        </div>
                        <div class="card-body">
                            <?php echo $card['card_body_adopta'];?>

                            <div class="d-flex justify-content-center">
                                <a href="<?php echo $card['link_card_adopta']?>" class="btn btn-outline-light text-q-a">Quiero Adoptar</a>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-grid justify-content-center align-items-center">
                            <?php echo $card['card_footer_adopta'];?>
                        </div>
                    </div>
                </div>                    

            <?php }
            } else { ?>
                <h3 class="text-danger fw-bold">Error</h3>
            <?php } ?>
        </div>
    </section>
    <!-- ./Sección adopción -->



