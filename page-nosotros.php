<?php get_header(); 
    /*
    * Template Name: Página Nosotros **
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido Sobre Nostros */
        get_template_part('template', 'parts/nosotros'); 

        /** Contenido en inglés */
        get_template_part('template-parts/content-en/nosotros', 'en'); 

     endwhile; ?>
<?php get_footer(); ?>