<?php get_header(); 
    /*
    * Template Name: Bolsa de trabajo ** 
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido documentos */
        get_template_part('template-parts/bolsa', 'trabajo-content'); 
     endwhile; ?>

<?php get_footer(); ?>    
    