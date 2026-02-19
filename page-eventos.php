<?php  
    /*
    * Template Name: Página Eventos
    */
    
    get_header();
?>
    <?php while(have_posts()): the_post();

    /** Titulo Imagen destacada  */
    get_template_part('template-parts/contenido', 'paginas'); 

    endwhile; ?>


    <?php // reforestamos_query_eventos(); ?> 




<?php get_footer(); ?>