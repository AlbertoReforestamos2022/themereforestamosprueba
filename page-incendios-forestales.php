<?php get_header(); 
    /*
    * Template Name: Página Incendios Forestales ** 
    */
?>
    <?php while(have_posts()): the_post();

    /** Titulo Imagen destacada  */
    get_template_part('template-parts/contenido', 'paginas'); 

    /** Contenido Incendios Forestales  */
    get_template_part('template-parts/incendios', 'forestales'); 

    endwhile; ?>


<?php get_footer(); ?>