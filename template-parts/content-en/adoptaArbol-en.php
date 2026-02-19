<?php  $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true ); ?>
<h1 class="text-light text-center title-principal-en d-none"><?php echo $tituloPrincipal; ?> </h1>
 
<!-- Introducción adopta un árbol  -->
<div class="container mb-5 content-intro-adopt-en">
    <div class="text-center text-primary py-lg-4">
            <?php $info_principal = get_post_meta( get_the_ID(), 'info_principal_en', true); ?> 
            <?php echo $info_principal; ?>
        </div>

        <div class="row">
            <?php $infos_principales = get_post_meta( get_the_ID(), 'reforestamos_group_adopta_info_principal', true); ?> 

            <?php if(!empty($infos_principales)){
                foreach($infos_principales as $info) { ?>
            <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                <div class="px-0 px-lg-3">
                <div class="text-primary mb-3">
                        <?php echo $info['info_imagen_principal'];?>
                </div>
                    <?php echo $info['info_texto_principal_en'];?>	
                </div>
            </div>
            <?php }
            } ?>
        </div> 
</div>

<!-- cards adopta un árbol -->
<div class="container mt-5 content-cards-adopt-en">
        <div class="row row-cols-1 row-cols-lg-3 justify-content-center gap-1">
            <?php $cards_adoptar = get_post_meta( get_the_ID(), 'reforestamos_group_adopta_arbol_principal', true); ?> 
            <?php if(!empty($cards_adoptar)) {
                foreach($cards_adoptar as $card) { ?>
                <div class="col my-3">
                    <div class="card border-0 bg-transparent shadow rounded">
                        <div class="card-header bg-transparent border-0">
                            <?php echo $card['card_header_adopta_en']; ?>
                        </div>
                        <div class="card-body">
                            <?php echo $card['card_body_adopta_en'];?>

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
            }  ?>
        </div>   
</div>




<!-- <iframe width="640" height="360" src="https://us05web.zoom.us/j/85897179275?pwd=EwbUCKBarvth7BqkRjTfR7Y4iP5u1V.1" frameborder="0" allowfullscreen></iframe> -->

<!-- <iframe width="640" height="360" src="https://centinelasdeltiempo.org/" frameborder="0"></iframe> -->
