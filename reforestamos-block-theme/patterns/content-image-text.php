<?php
/**
 * Title: Content with Image and Text
 * Slug: content-image-text
 * Description: A two-column layout with image on one side and text content on the other
 * Categories: reforestamos-content
 * Keywords: content, image, text, two-column, layout
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","right":"2rem","bottom":"4rem","left":"2rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:4rem;padding-right:2rem;padding-bottom:4rem;padding-left:2rem">
    <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"3rem","left":"3rem"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center">
        <!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
            <!-- wp:image {"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"8px"}}} -->
            <figure class="wp-block-image size-large has-custom-border">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-content.jpg'); ?>" alt="" style="border-radius:8px"/>
            </figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
            <!-- wp:heading {"style":{"typography":{"fontSize":"2rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"primary"} -->
            <h2 class="wp-block-heading has-primary-color has-text-color" style="margin-bottom:1rem;font-size:2rem;font-weight:700"><?php esc_html_e('Nuestra Misión', 'reforestamos'); ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"dark"} -->
            <p class="has-dark-color has-text-color" style="margin-bottom:1rem"><?php esc_html_e('Reforestamos México es una organización dedicada a la restauración de ecosistemas forestales en todo el país. Trabajamos con comunidades locales, empresas y voluntarios para plantar árboles nativos y recuperar áreas degradadas.', 'reforestamos'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"1.5rem"}}},"textColor":"dark"} -->
            <p class="has-dark-color has-text-color" style="margin-bottom:1.5rem"><?php esc_html_e('Desde nuestros inicios, hemos plantado más de 1.5 millones de árboles en 32 estados de México, contribuyendo a la captura de carbono, la conservación de la biodiversidad y el bienestar de las comunidades.', 'reforestamos'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons -->
            <div class="wp-block-buttons">
                <!-- wp:button {"backgroundColor":"primary"} -->
                <div class="wp-block-button">
                    <a class="wp-block-button__link has-primary-background-color has-background wp-element-button"><?php esc_html_e('Conoce más', 'reforestamos'); ?></a>
                </div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->
