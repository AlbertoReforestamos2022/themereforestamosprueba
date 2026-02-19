<?php get_header(); 
    /*
    * Template Name: Página ¿Que Hacemos? **
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido Sobre Nostros */
        get_template_part('template-parts/que', 'hacemos'); 

        /** Contenido en inglés */
        get_template_part('template-parts/content-en/quehacemos', 'en'); 

     endwhile; ?>
<?php get_footer(); ?>