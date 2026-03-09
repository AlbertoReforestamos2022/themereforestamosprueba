<?php
/**
 * Title: Call to Action
 * Slug: call-to-action
 * Description: A centered call-to-action section with title, description, and button
 * Categories: reforestamos-cta
 * Keywords: cta, call to action, button, action
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","right":"2rem","bottom":"4rem","left":"2rem"}}},"backgroundColor":"light","layout":{"type":"constrained","contentSize":"800px"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background" style="padding-top:4rem;padding-right:2rem;padding-bottom:4rem;padding-left:2rem">
    <!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"2.5rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"1.5rem"}}},"textColor":"primary"} -->
    <h2 class="wp-block-heading has-text-align-center has-primary-color has-text-color" style="margin-bottom:1.5rem;font-size:2.5rem;font-weight:700"><?php esc_html_e('¿Listo para hacer la diferencia?', 'reforestamos'); ?></h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.25rem"},"spacing":{"margin":{"bottom":"2rem"}}},"textColor":"dark"} -->
    <p class="has-text-align-center has-dark-color has-text-color" style="margin-bottom:2rem;font-size:1.25rem"><?php esc_html_e('Únete a nuestra comunidad y ayúdanos a plantar árboles en todo México. Cada árbol cuenta, cada acción importa.', 'reforestamos'); ?></p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
    <div class="wp-block-buttons">
        <!-- wp:button {"backgroundColor":"primary","style":{"spacing":{"padding":{"top":"1rem","right":"2.5rem","bottom":"1rem","left":"2.5rem"}},"typography":{"fontSize":"1.125rem"}}} -->
        <div class="wp-block-button has-custom-font-size" style="font-size:1.125rem">
            <a class="wp-block-button__link has-primary-background-color has-background wp-element-button" style="padding-top:1rem;padding-right:2.5rem;padding-bottom:1rem;padding-left:2.5rem"><?php esc_html_e('Participa ahora', 'reforestamos'); ?></a>
        </div>
        <!-- /wp:button -->

        <!-- wp:button {"backgroundColor":"white","textColor":"primary","style":{"spacing":{"padding":{"top":"1rem","right":"2.5rem","bottom":"1rem","left":"2.5rem"}},"typography":{"fontSize":"1.125rem"},"border":{"width":"2px","color":"#2E7D32"}}} -->
        <div class="wp-block-button has-custom-font-size" style="font-size:1.125rem">
            <a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background has-border-color wp-element-button" style="border-color:#2E7D32;border-width:2px;padding-top:1rem;padding-right:2.5rem;padding-bottom:1rem;padding-left:2.5rem"><?php esc_html_e('Conoce más', 'reforestamos'); ?></a>
        </div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->
