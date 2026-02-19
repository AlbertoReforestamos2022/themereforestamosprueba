<?php get_header(); 
    /*
    * Template Name: Micrositio Árboles y Ciudades
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        // get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido Aliados (Organizaciones - Gobierno) */
        get_template_part('template-parts/contenido', 'arboles-ciudades'); 



     endwhile; ?>
<?php get_footer(); ?>