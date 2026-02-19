<?php get_header(); 
    /*
    * Template Name: Página Documentos
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido documentos */
        get_template_part('template', 'parts/documentos'); 
     endwhile; ?>
<?php get_footer(); ?>    
    
