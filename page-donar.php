<?php get_header(); 
    /*
    * Template Name: Página Donar !! 
    */
?>

    <?php while(have_posts()): the_post();

        /** Donar */
        get_template_part('template', 'parts/donar'); 

        /** Contenido en inglés */
        get_template_part('template-parts/content-en/donar', 'en');


     endwhile; ?>
<?php get_footer(); ?>