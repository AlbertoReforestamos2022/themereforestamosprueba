<?php
/**
 * Title: Testimonials
 * Slug: testimonials
 * Description: A section displaying testimonials from volunteers and partners
 * Categories: reforestamos-content
 * Keywords: testimonials, reviews, quotes, feedback
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","right":"2rem","bottom":"4rem","left":"2rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:4rem;padding-right:2rem;padding-bottom:4rem;padding-left:2rem">
    <!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"2.5rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"3rem"}}},"textColor":"primary"} -->
    <h2 class="wp-block-heading has-text-align-center has-primary-color has-text-color" style="margin-bottom:3rem;font-size:2.5rem;font-weight:700"><?php esc_html_e('Lo que dicen nuestros voluntarios', 'reforestamos'); ?></h2>
    <!-- /wp:heading -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"2rem"}}}} -->
    <div class="wp-block-columns">
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"white","className":"shadow-sm"} -->
            <div class="wp-block-group shadow-sm has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem">
                <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.125rem","lineHeight":"1.6"},"spacing":{"margin":{"bottom":"1.5rem"}}},"textColor":"dark"} -->
                <p class="has-dark-color has-text-color" style="margin-bottom:1.5rem;font-size:1.125rem;line-height:1.6"><?php esc_html_e('"Participar en las jornadas de reforestación ha sido una experiencia transformadora. Ver cómo nuestro esfuerzo se convierte en bosques es increíble."', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group">
                    <!-- wp:image {"width":"60px","height":"60px","sizeSlug":"thumbnail","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image size-thumbnail is-resized has-custom-border">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-avatar.jpg'); ?>" alt="" style="border-radius:50%;width:60px;height:60px"/>
                    </figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group">
                        <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"textColor":"dark"} -->
                        <p class="has-dark-color has-text-color" style="font-weight:600"><?php esc_html_e('María González', 'reforestamos'); ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875rem"}},"textColor":"dark"} -->
                        <p class="has-dark-color has-text-color" style="font-size:0.875rem"><?php esc_html_e('Voluntaria desde 2020', 'reforestamos'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"white","className":"shadow-sm"} -->
            <div class="wp-block-group shadow-sm has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem">
                <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.125rem","lineHeight":"1.6"},"spacing":{"margin":{"bottom":"1.5rem"}}},"textColor":"dark"} -->
                <p class="has-dark-color has-text-color" style="margin-bottom:1.5rem;font-size:1.125rem;line-height:1.6"><?php esc_html_e('"Como empresa, colaborar con Reforestamos México nos ha permitido contribuir al medio ambiente de manera tangible y medible."', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group">
                    <!-- wp:image {"width":"60px","height":"60px","sizeSlug":"thumbnail","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image size-thumbnail is-resized has-custom-border">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-avatar.jpg'); ?>" alt="" style="border-radius:50%;width:60px;height:60px"/>
                    </figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group">
                        <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"textColor":"dark"} -->
                        <p class="has-dark-color has-text-color" style="font-weight:600"><?php esc_html_e('Carlos Ramírez', 'reforestamos'); ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875rem"}},"textColor":"dark"} -->
                        <p class="has-dark-color has-text-color" style="font-size:0.875rem"><?php esc_html_e('Director de RSE', 'reforestamos'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"white","className":"shadow-sm"} -->
            <div class="wp-block-group shadow-sm has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem">
                <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.125rem","lineHeight":"1.6"},"spacing":{"margin":{"bottom":"1.5rem"}}},"textColor":"dark"} -->
                <p class="has-dark-color has-text-color" style="margin-bottom:1.5rem;font-size:1.125rem;line-height:1.6"><?php esc_html_e('"La organización y el impacto de cada jornada son impresionantes. Realmente están haciendo la diferencia en México."', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group">
                    <!-- wp:image {"width":"60px","height":"60px","sizeSlug":"thumbnail","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image size-thumbnail is-resized has-custom-border">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-avatar.jpg'); ?>" alt="" style="border-radius:50%;width:60px;height:60px"/>
                    </figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group">
                        <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"textColor":"dark"} -->
                        <p class="has-dark-color has-text-color" style="font-weight:600"><?php esc_html_e('Ana Martínez', 'reforestamos'); ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875rem"}},"textColor":"dark"} -->
                        <p class="has-dark-color has-text-color" style="font-size:0.875rem"><?php esc_html_e('Voluntaria desde 2019', 'reforestamos'); ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->
