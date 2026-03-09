<?php
/**
 * Title: Hero Section
 * Slug: hero-section
 * Description: A full-width hero section with background image, title, subtitle, and call-to-action button
 * Categories: reforestamos-headers
 * Keywords: hero, banner, header, cta
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
    <!-- wp:cover {"url":"<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-hero.jpg'); ?>","dimRatio":50,"overlayColor":"dark","minHeight":600,"minHeightUnit":"px","align":"full"} -->
    <div class="wp-block-cover alignfull" style="min-height:600px">
        <span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim"></span>
        <img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-hero.jpg'); ?>" data-object-fit="cover"/>
        <div class="wp-block-cover__inner-container">
            <!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
            <div class="wp-block-group">
                <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"3.5rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"1.5rem"}}},"textColor":"white"} -->
                <h1 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="margin-bottom:1.5rem;font-size:3.5rem;font-weight:700"><?php esc_html_e('Reforestamos México', 'reforestamos'); ?></h1>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.5rem"},"spacing":{"margin":{"bottom":"2rem"}}},"textColor":"white"} -->
                <p class="has-text-align-center has-white-color has-text-color" style="margin-bottom:2rem;font-size:1.5rem"><?php esc_html_e('Juntos podemos crear un futuro más verde y sostenible para nuestro país', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                <div class="wp-block-buttons">
                    <!-- wp:button {"backgroundColor":"primary","style":{"spacing":{"padding":{"top":"1rem","right":"2rem","bottom":"1rem","left":"2rem"}},"typography":{"fontSize":"1.125rem"}}} -->
                    <div class="wp-block-button has-custom-font-size" style="font-size:1.125rem">
                        <a class="wp-block-button__link has-primary-background-color has-background wp-element-button" style="padding-top:1rem;padding-right:2rem;padding-bottom:1rem;padding-left:2rem"><?php esc_html_e('Conoce más', 'reforestamos'); ?></a>
                    </div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
    </div>
    <!-- /wp:cover -->
</div>
<!-- /wp:group -->
