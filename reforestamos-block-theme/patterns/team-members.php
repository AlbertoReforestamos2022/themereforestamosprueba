<?php
/**
 * Title: Team Members
 * Slug: team-members
 * Description: A grid layout displaying team members with photos and roles
 * Categories: reforestamos-team
 * Keywords: team, staff, members, people, about
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","right":"2rem","bottom":"4rem","left":"2rem"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background" style="padding-top:4rem;padding-right:2rem;padding-bottom:4rem;padding-left:2rem">
    <!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"2.5rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"primary"} -->
    <h2 class="wp-block-heading has-text-align-center has-primary-color has-text-color" style="margin-bottom:1rem;font-size:2.5rem;font-weight:700"><?php esc_html_e('Nuestro Equipo', 'reforestamos'); ?></h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}},"textColor":"dark"} -->
    <p class="has-text-align-center has-dark-color has-text-color" style="margin-bottom:3rem"><?php esc_html_e('Conoce a las personas que hacen posible la reforestación en México', 'reforestamos'); ?></p>
    <!-- /wp:paragraph -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"2rem"}}}} -->
    <div class="wp-block-columns">
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
                <!-- wp:image {"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"8px"}}} -->
                <figure class="wp-block-image size-large has-custom-border">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-team.jpg'); ?>" alt="" style="border-radius:8px"/>
                </figure>
                <!-- /wp:image -->

                <!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"1rem","bottom":"0.5rem"}}},"textColor":"dark"} -->
                <h3 class="wp-block-heading has-text-align-center has-dark-color has-text-color" style="margin-top:1rem;margin-bottom:0.5rem"><?php esc_html_e('Laura Sánchez', 'reforestamos'); ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1rem"}},"textColor":"primary"} -->
                <p class="has-text-align-center has-primary-color has-text-color" style="font-size:1rem"><?php esc_html_e('Directora General', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"0.5rem"}}},"textColor":"dark"} -->
                <p class="has-text-align-center has-dark-color has-text-color" style="margin-top:0.5rem"><?php esc_html_e('Bióloga con 15 años de experiencia en conservación ambiental.', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","iconBackgroundColor":"primary","iconBackgroundColorValue":"#2E7D32","style":{"spacing":{"blockGap":{"top":"0.5rem","left":"0.5rem"}}},"className":"is-style-default","layout":{"type":"flex","justifyContent":"center"}} -->
                <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default">
                    <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                    <!-- wp:social-link {"url":"#","service":"twitter"} /-->
                    <!-- wp:social-link {"url":"#","service":"mail"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
                <!-- wp:image {"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"8px"}}} -->
                <figure class="wp-block-image size-large has-custom-border">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-team.jpg'); ?>" alt="" style="border-radius:8px"/>
                </figure>
                <!-- /wp:image -->

                <!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"1rem","bottom":"0.5rem"}}},"textColor":"dark"} -->
                <h3 class="wp-block-heading has-text-align-center has-dark-color has-text-color" style="margin-top:1rem;margin-bottom:0.5rem"><?php esc_html_e('Miguel Torres', 'reforestamos'); ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1rem"}},"textColor":"primary"} -->
                <p class="has-text-align-center has-primary-color has-text-color" style="font-size:1rem"><?php esc_html_e('Coordinador de Proyectos', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"0.5rem"}}},"textColor":"dark"} -->
                <p class="has-text-align-center has-dark-color has-text-color" style="margin-top:0.5rem"><?php esc_html_e('Ingeniero forestal especializado en restauración ecológica.', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","iconBackgroundColor":"primary","iconBackgroundColorValue":"#2E7D32","style":{"spacing":{"blockGap":{"top":"0.5rem","left":"0.5rem"}}},"className":"is-style-default","layout":{"type":"flex","justifyContent":"center"}} -->
                <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default">
                    <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                    <!-- wp:social-link {"url":"#","service":"twitter"} /-->
                    <!-- wp:social-link {"url":"#","service":"mail"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
                <!-- wp:image {"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"8px"}}} -->
                <figure class="wp-block-image size-large has-custom-border">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-team.jpg'); ?>" alt="" style="border-radius:8px"/>
                </figure>
                <!-- /wp:image -->

                <!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"1rem","bottom":"0.5rem"}}},"textColor":"dark"} -->
                <h3 class="wp-block-heading has-text-align-center has-dark-color has-text-color" style="margin-top:1rem;margin-bottom:0.5rem"><?php esc_html_e('Patricia Ruiz', 'reforestamos'); ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1rem"}},"textColor":"primary"} -->
                <p class="has-text-align-center has-primary-color has-text-color" style="font-size:1rem"><?php esc_html_e('Coordinadora de Voluntarios', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"0.5rem"}}},"textColor":"dark"} -->
                <p class="has-text-align-center has-dark-color has-text-color" style="margin-top:0.5rem"><?php esc_html_e('Psicóloga social con pasión por la educación ambiental.', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","iconBackgroundColor":"primary","iconBackgroundColorValue":"#2E7D32","style":{"spacing":{"blockGap":{"top":"0.5rem","left":"0.5rem"}}},"className":"is-style-default","layout":{"type":"flex","justifyContent":"center"}} -->
                <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default">
                    <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                    <!-- wp:social-link {"url":"#","service":"twitter"} /-->
                    <!-- wp:social-link {"url":"#","service":"mail"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->
