<?php
/**
 * Title: Page Header
 * Slug: page-header
 * Description: A simple page header with title and breadcrumbs
 * Categories: reforestamos-headers
 * Keywords: header, page, title, breadcrumbs
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"3rem","right":"2rem","bottom":"3rem","left":"2rem"}}},"backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background" style="padding-top:3rem;padding-right:2rem;padding-bottom:3rem;padding-left:2rem">
    <!-- wp:group {"layout":{"type":"constrained","contentSize":"1140px"}} -->
    <div class="wp-block-group">
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875rem"}},"textColor":"dark"} -->
        <p class="has-dark-color has-text-color" style="font-size:0.875rem">
            <a href="/"><?php esc_html_e('Inicio', 'reforestamos'); ?></a> / 
            <?php esc_html_e('Página Actual', 'reforestamos'); ?>
        </p>
        <!-- /wp:paragraph -->

        <!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"3rem","fontWeight":"700"},"spacing":{"margin":{"top":"1rem","bottom":"0"}}},"textColor":"primary"} -->
        <h1 class="wp-block-heading has-primary-color has-text-color" style="margin-top:1rem;margin-bottom:0;font-size:3rem;font-weight:700"><?php esc_html_e('Título de la Página', 'reforestamos'); ?></h1>
        <!-- /wp:heading -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
