<?php get_header(); 
    /*
    * Template Name: Página Aliados (Organizaciones -- Gobierno)
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido Aliados (Organizaciones - Gobierno) */
        get_template_part('template', 'parts/organizaciones'); 

        /** Contenido en inglés */
        get_template_part('template-parts/content-en/aliados', 'en'); 


     endwhile; ?>
<?php get_footer(); ?>