<?php get_header(); 
    /*
    * Template Name: Página Aviso de Privacidad !! 
    */
?>

    <?php while(have_posts()): the_post();

        /** Aviso Privacidad */
        get_template_part('template-parts/aviso', 'privacidad'); 


     endwhile; ?>
<?php get_footer(); ?>