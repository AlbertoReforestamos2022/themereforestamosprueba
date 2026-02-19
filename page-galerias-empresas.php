<?php get_header(); 
    /*
    * Template Name: Empresas (CPT)
    */
?>

    <?php while(have_posts()): the_post();

        /** Titulo Imagen destacada  */
        get_template_part('template-parts/contenido', 'paginas'); 

        /** Contenido Galerías empresas */ 
        reforestamos_query_empresas(); 

     endwhile; ?>
<?php get_footer(); ?>