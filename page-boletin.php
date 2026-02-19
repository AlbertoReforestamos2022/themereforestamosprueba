<?php get_header(); 
    /*
    * Template Name: Página Boletín **
    */
?>

    <?php while(have_posts()): the_post();
        /** Contenido documentos */
        get_template_part('template', 'parts/boletin'); 
     endwhile; ?>
<?php get_footer(); ?>   
 
    