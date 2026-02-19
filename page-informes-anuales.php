<?php get_header(); 
    /*
    * Template Name: Página Documentos Info
    */
?>
    <?php while(have_posts()): the_post();

    /** Titulo Imagen destacada  */
    get_template_part('template-parts/contenido', 'paginas'); 

    /** Contenido Sobre Nostros */

    /** Informes anuales */
    get_template_part('template-parts/contenido', 'documentos'); 

    /** Contenido en inglés */
    get_template_part('template-parts/content-en/informesAnuales', 'en'); 

    endwhile; ?>


<?php get_footer(); ?>