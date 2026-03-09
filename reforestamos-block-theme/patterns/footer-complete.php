<?php
/**
 * Title: Complete Footer
 * Slug: footer-complete
 * Description: A complete footer with multiple columns, social links, and copyright
 * Categories: reforestamos-footers
 * Keywords: footer, bottom, social, links, copyright
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"3rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"dark","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-color has-dark-background-color has-text-color has-background" style="padding-top:3rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem">
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"3rem"},"margin":{"bottom":"2rem"}}}} -->
    <div class="wp-block-columns" style="margin-bottom:2rem">
        <!-- wp:column {"width":"40%"} -->
        <div class="wp-block-column" style="flex-basis:40%">
            <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"white"} -->
            <h3 class="wp-block-heading has-white-color has-text-color" style="margin-bottom:1rem"><?php esc_html_e('Reforestamos México', 'reforestamos'); ?></h3>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"1.5rem"}}},"textColor":"light"} -->
            <p class="has-light-color has-text-color" style="margin-bottom:1.5rem"><?php esc_html_e('Trabajamos por un México más verde, plantando árboles y restaurando ecosistemas en todo el país.', 'reforestamos'); ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","iconBackgroundColor":"secondary","iconBackgroundColorValue":"#66BB6A","size":"has-normal-icon-size","style":{"spacing":{"blockGap":{"top":"0.75rem","left":"0.75rem"}}},"className":"is-style-default"} -->
            <ul class="wp-block-social-links has-normal-icon-size has-icon-color has-icon-background-color is-style-default">
                <!-- wp:social-link {"url":"#","service":"facebook"} /-->
                <!-- wp:social-link {"url":"#","service":"twitter"} /-->
                <!-- wp:social-link {"url":"#","service":"instagram"} /-->
                <!-- wp:social-link {"url":"#","service":"youtube"} /-->
                <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
            </ul>
            <!-- /wp:social-links -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"white"} -->
            <h4 class="wp-block-heading has-white-color has-text-color" style="margin-bottom:1rem"><?php esc_html_e('Enlaces Rápidos', 'reforestamos'); ?></h4>
            <!-- /wp:heading -->

            <!-- wp:list {"style":{"spacing":{"padding":{"left":"0"}}},"className":"is-style-none"} -->
            <ul class="is-style-none" style="padding-left:0">
                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Inicio', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Sobre Nosotros', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Proyectos', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Eventos', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Blog', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->
            </ul>
            <!-- /wp:list -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"white"} -->
            <h4 class="wp-block-heading has-white-color has-text-color" style="margin-bottom:1rem"><?php esc_html_e('Participa', 'reforestamos'); ?></h4>
            <!-- /wp:heading -->

            <!-- wp:list {"style":{"spacing":{"padding":{"left":"0"}}},"className":"is-style-none"} -->
            <ul class="is-style-none" style="padding-left:0">
                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Voluntariado', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Donaciones', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Empresas', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Adopta un Árbol', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->

                <!-- wp:list-item -->
                <li><a href="#"><?php esc_html_e('Boletín', 'reforestamos'); ?></a></li>
                <!-- /wp:list-item -->
            </ul>
            <!-- /wp:list -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"bottom":"1rem"}}},"textColor":"white"} -->
            <h4 class="wp-block-heading has-white-color has-text-color" style="margin-bottom:1rem"><?php esc_html_e('Contacto', 'reforestamos'); ?></h4>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"0.5rem"}}},"textColor":"light"} -->
            <p class="has-light-color has-text-color" style="margin-bottom:0.5rem">📧 contacto@reforestamos.org</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"0.5rem"}}},"textColor":"light"} -->
            <p class="has-light-color has-text-color" style="margin-bottom:0.5rem">📞 +52 55 1234 5678</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"textColor":"light"} -->
            <p class="has-light-color has-text-color">📍 <?php esc_html_e('Ciudad de México', 'reforestamos'); ?></p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:separator {"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}},"backgroundColor":"secondary"} -->
    <hr class="wp-block-separator has-text-color has-secondary-color has-alpha-channel-opacity has-secondary-background-color has-background" style="margin-top:2rem;margin-bottom:2rem"/>
    <!-- /wp:separator -->

    <!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group">
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875rem"}},"textColor":"light"} -->
        <p class="has-light-color has-text-color" style="font-size:0.875rem"><?php echo sprintf(esc_html__('© %s Reforestamos México. Todos los derechos reservados.', 'reforestamos'), date('Y')); ?></p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875rem"}},"textColor":"light"} -->
        <p class="has-light-color has-text-color" style="font-size:0.875rem">
            <a href="#"><?php esc_html_e('Aviso de Privacidad', 'reforestamos'); ?></a> | 
            <a href="#"><?php esc_html_e('Términos y Condiciones', 'reforestamos'); ?></a>
        </p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
