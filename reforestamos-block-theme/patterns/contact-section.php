<?php
/**
 * Title: Contact Section
 * Slug: contact-section
 * Description: A complete contact section with form, map, and contact information
 * Categories: reforestamos-contact
 * Keywords: contact, form, map, address, email, phone
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","right":"2rem","bottom":"4rem","left":"2rem"}}},"backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background" style="padding-top:4rem;padding-right:2rem;padding-bottom:4rem;padding-left:2rem">
    <!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"2.5rem","fontWeight":"700"},"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"primary"} -->
    <h2 class="wp-block-heading has-text-align-center has-primary-color has-text-color" style="margin-bottom:1rem;font-size:2.5rem;font-weight:700"><?php esc_html_e('Contáctanos', 'reforestamos'); ?></h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}},"textColor":"dark"} -->
    <p class="has-text-align-center has-dark-color has-text-color" style="margin-bottom:3rem"><?php esc_html_e('¿Tienes preguntas? Estamos aquí para ayudarte', 'reforestamos'); ?></p>
    <!-- /wp:paragraph -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"3rem","left":"3rem"}}}} -->
    <div class="wp-block-columns">
        <!-- wp:column {"width":"40%"} -->
        <div class="wp-block-column" style="flex-basis:40%">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem">
                <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"1.5rem"}}},"textColor":"primary"} -->
                <h3 class="wp-block-heading has-primary-color has-text-color" style="margin-bottom:1.5rem"><?php esc_html_e('Información de Contacto', 'reforestamos'); ?></h3>
                <!-- /wp:heading -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem"}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group">
                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group">
                        <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.5rem"}},"textColor":"primary"} -->
                        <p class="has-primary-color has-text-color" style="font-size:1.5rem">📍</p>
                        <!-- /wp:paragraph -->

                        <!-- wp:group {"layout":{"type":"constrained"}} -->
                        <div class="wp-block-group">
                            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"textColor":"dark"} -->
                            <p class="has-dark-color has-text-color" style="font-weight:600"><?php esc_html_e('Dirección', 'reforestamos'); ?></p>
                            <!-- /wp:paragraph -->

                            <!-- wp:paragraph {"textColor":"dark"} -->
                            <p class="has-dark-color has-text-color"><?php esc_html_e('Av. Insurgentes Sur 1234, Col. Del Valle, CDMX', 'reforestamos'); ?></p>
                            <!-- /wp:paragraph -->
                        </div>
                        <!-- /wp:group -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group">
                        <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.5rem"}},"textColor":"primary"} -->
                        <p class="has-primary-color has-text-color" style="font-size:1.5rem">📧</p>
                        <!-- /wp:paragraph -->

                        <!-- wp:group {"layout":{"type":"constrained"}} -->
                        <div class="wp-block-group">
                            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"textColor":"dark"} -->
                            <p class="has-dark-color has-text-color" style="font-weight:600"><?php esc_html_e('Email', 'reforestamos'); ?></p>
                            <!-- /wp:paragraph -->

                            <!-- wp:paragraph {"textColor":"dark"} -->
                            <p class="has-dark-color has-text-color">contacto@reforestamos.org</p>
                            <!-- /wp:paragraph -->
                        </div>
                        <!-- /wp:group -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group">
                        <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.5rem"}},"textColor":"primary"} -->
                        <p class="has-primary-color has-text-color" style="font-size:1.5rem">📞</p>
                        <!-- /wp:paragraph -->

                        <!-- wp:group {"layout":{"type":"constrained"}} -->
                        <div class="wp-block-group">
                            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"textColor":"dark"} -->
                            <p class="has-dark-color has-text-color" style="font-weight:600"><?php esc_html_e('Teléfono', 'reforestamos'); ?></p>
                            <!-- /wp:paragraph -->

                            <!-- wp:paragraph {"textColor":"dark"} -->
                            <p class="has-dark-color has-text-color">+52 55 1234 5678</p>
                            <!-- /wp:paragraph -->
                        </div>
                        <!-- /wp:group -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group">
                        <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.5rem"}},"textColor":"primary"} -->
                        <p class="has-primary-color has-text-color" style="font-size:1.5rem">🕒</p>
                        <!-- /wp:paragraph -->

                        <!-- wp:group {"layout":{"type":"constrained"}} -->
                        <div class="wp-block-group">
                            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"textColor":"dark"} -->
                            <p class="has-dark-color has-text-color" style="font-weight:600"><?php esc_html_e('Horario', 'reforestamos'); ?></p>
                            <!-- /wp:paragraph -->

                            <!-- wp:paragraph {"textColor":"dark"} -->
                            <p class="has-dark-color has-text-color"><?php esc_html_e('Lunes a Viernes: 9:00 - 18:00', 'reforestamos'); ?></p>
                            <!-- /wp:paragraph -->
                        </div>
                        <!-- /wp:group -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->

                <!-- wp:separator {"style":{"spacing":{"margin":{"top":"1.5rem","bottom":"1.5rem"}}},"backgroundColor":"light"} -->
                <hr class="wp-block-separator has-text-color has-light-color has-alpha-channel-opacity has-light-background-color has-background" style="margin-top:1.5rem;margin-bottom:1.5rem"/>
                <!-- /wp:separator -->

                <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"primary"} -->
                <h4 class="wp-block-heading has-primary-color has-text-color" style="margin-bottom:1rem"><?php esc_html_e('Síguenos', 'reforestamos'); ?></h4>
                <!-- /wp:heading -->

                <!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","iconBackgroundColor":"primary","iconBackgroundColorValue":"#2E7D32","size":"has-normal-icon-size","style":{"spacing":{"blockGap":{"top":"0.75rem","left":"0.75rem"}}},"className":"is-style-default"} -->
                <ul class="wp-block-social-links has-normal-icon-size has-icon-color has-icon-background-color is-style-default">
                    <!-- wp:social-link {"url":"#","service":"facebook"} /-->
                    <!-- wp:social-link {"url":"#","service":"twitter"} /-->
                    <!-- wp:social-link {"url":"#","service":"instagram"} /-->
                    <!-- wp:social-link {"url":"#","service":"youtube"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"60%"} -->
        <div class="wp-block-column" style="flex-basis:60%">
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem">
                <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"1.5rem"}}},"textColor":"primary"} -->
                <h3 class="wp-block-heading has-primary-color has-text-color" style="margin-bottom:1.5rem"><?php esc_html_e('Envíanos un Mensaje', 'reforestamos'); ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"2rem"}}},"textColor":"dark"} -->
                <p class="has-dark-color has-text-color" style="margin-bottom:2rem"><?php esc_html_e('Completa el formulario y nos pondremos en contacto contigo lo antes posible.', 'reforestamos'); ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:shortcode -->
                [contact-form]
                <!-- /wp:shortcode -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->
