<?php get_header(); ?>

    <?php while(have_posts()): the_post();?>
        <!-- Titulo-Background -->
        <?php  get_template_part('template-parts/contenido', 'paginas'); ?>
        <!-- /. Titulo-Background -->
    <?php endwhile; ?>
<?php get_footer(); ?>