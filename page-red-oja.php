<?php get_header(); 
    /*
    * Template Name: Micrositio Red Oja
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido Micrositio Red Oja  */
        get_template_part('template-parts/contenido', 'red-oja'); 


     endwhile; ?>
<?php get_footer(); ?>