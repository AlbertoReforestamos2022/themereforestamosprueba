<?php get_header(); 
    /*
    * Template Name: Página giveaway Centinelas 
    */
?>

    <?php while(have_posts()): the_post();

        /** Aviso Privacidad */
        get_template_part('template-parts/giveaway', 'centinelas'); 


     endwhile; ?>
<?php get_footer(); ?>