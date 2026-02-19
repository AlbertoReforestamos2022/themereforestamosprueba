<?php
    // Función render posts logos aliados empresas.
    // este irá en queries.php
    function reforestamos_query_empresas($cantidad = -1) {
        // Query personalizada para el CPT "empresas"
        $args = array(
            'post_type'      => 'empresas',
            'posts_per_page' => $cantidad,
            'order'          => 'ASC'
        );

        $empresas = new WP_Query($args);

        // Arrays de clasificación
        $aliadosA = [];
        $aliadosB = [];
        $aliadosC = [];

        if ($empresas->have_posts()) :
            while ($empresas->have_posts()) : $empresas->the_post();
                // printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true ));  
                // Obtener meta
                $logo          = get_post_meta(get_the_ID(), 'logo_empresa', true);  
                $empresaType   = get_post_meta(get_the_ID(), 'tipo_paquete', true);
                $nombreEmpresa = get_the_title();
                $empresa_id    = sanitize_title($nombreEmpresa);
                $ancho_logo    = get_post_meta(get_the_ID(), 'ancho_logo', true);

                // Este es un group field (array de arrays)
                $contenidoGalerias = get_post_meta(get_the_ID(), 'reforestamos_galerias_empresas_galeria_empresa', true);

                // Armar array de datos
                $empresaData = [
                    'id'       => $empresa_id,
                    'logo'     => $logo,
                    'ancho_logo' => $ancho_logo,
                    'tipo'     => $empresaType,
                    'nombre'   => $nombreEmpresa,
                    'galeria'  => $contenidoGalerias
                ];

                // Clasificar según tipo
                if ($empresaType === 'A') {
                    $aliadosA[] = $empresaData;
                } elseif ($empresaType === 'B') {
                    $aliadosB[] = $empresaData;
                } else {
                    $aliadosC[] = $empresaData;
                }

            endwhile;
            wp_reset_postdata();
        endif;

        // -------------------
        // PINTAR RESULTADOS
        // -------------------

        // Aliados A
        if (!empty($aliadosA)) : ?>
            <div class="container espacio-lineas-accion" style="margin-top: 80px; margin-bottom: 80px;">
                    <?php reforestamos_aliados_A($aliadosA); ?>
            </div>
        <?php
        endif;

        // Aliados B
        if (!empty($aliadosB)) : ?>
            <div class="container espacio-lineas-accion" style="margin-top: 80px; margin-bottom: 80px;">
                <?php reforestamos_aliados_B($aliadosB) ?>
            </div>

        <?php
        endif;

        // Aliados C (ejemplo: logos en carrusel)
        if (!empty($aliadosC)) : ?>
            <div class="container" style="margin-top: 80px; margin-bottom: 80px;"> 
                <?php reforestamos_aliados_C($aliadosC); ?>
            </div>
        <?php
        endif;
    }    

?> 