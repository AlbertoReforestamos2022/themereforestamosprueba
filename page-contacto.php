<?php get_header(); 
    /*
    * Template Name: Página Contacto ** 
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido contacto */
        get_template_part('template', 'parts/contacto'); 

        
        /** Contenido en inglés */
        get_template_part('template-parts/content-en/contacto', 'en'); 
     endwhile; ?>
<?php get_footer(); ?>    
    