<?php  
    /*
    * Template Name: Página integrantes
    */
    
    get_header();
?>


    <div class="container">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-5">         
            <?php reforestamos_query_intergrantes(); ?> 
        </div>
    </div>



<?php get_footer(); ?>