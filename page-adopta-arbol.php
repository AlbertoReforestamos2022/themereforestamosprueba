<?php get_header(); 
    /*
    * Template Name: Página adopta un árbol **
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido documentos */
        get_template_part('template-parts/adopta', 'arbol'); 

        /** Contenido en inglés */
        get_template_part('template-parts/content-en/adoptaArbol', 'en'); 
     endwhile; ?>
<?php get_footer(); ?>    
    