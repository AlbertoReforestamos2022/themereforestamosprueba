<?php

// adding item in admin_menu 
function api_deepl() {
    add_menu_page(
        'DeepL (Traductor)', // Título de la página
        'DeepL (Traductor)', // Título del menú
        'manage_options', // Capacidad requerida
        'traductor-api', // Slug del menú
        'api_deepl_page_html', // Función que mostrará el contenido
        'dashicons-admin-site-alt3', // Icono del menú
        20 // Posición del menú
    );
}
add_action('admin_menu', 'api_deepl');

function api_deepl_page_html(){
    if (!current_user_can('manage_options')) {
        return;
    }

    // Guardar el valor si se ha enviado corectame
    if(isset($_POST['clave_api_deepl'])) {
        $clave = sanitize_text_field($_POST['clave_api_deepl']);
        update_option('clave_api_deepl', $clave);

        ?>
            <div class="notice notice-success">
                <p>Clave guardada correctamente</p>
            </div>
        <?php
    }

    // Recuperar clave guardada
    $clave_guardada = get_option('clave_api_deepl', '');


    ?>
    <div class="container">
        <div class="titulo">
            <p>Clave API DeepL</p>
        </div>

        <div class="formulario">
            <form method="post">
                <h3 class="titulo-clave-api">Clave API</h3>

                <input type="password" class="input-clave-api" name="clave_api_deepl" style="width:600px;" value="<?php echo esc_attr($clave_guardada) ?>">
                <br><br>
                <button type="submit "class="btn btn-success"> Guardar </button>
            </form>
        </div>
    </div>

    <div class="container">
        <?php
            if(!empty($clave_guardada)) { ?>
                <p> <?php echo 'clave guardada' ?></p>
            <?php
            } else {
                echo '<p>Ingresa una clave válida.</p>';
            }
        ?>
        
    </div>

    <?php

    // Registra y encola el script vacío
    wp_register_style('esilos-panel-deepl', false);
    wp_enqueue_style('esilos-panel-deepl');

    // Construye el contenido del script con las introducciones
    $style_deepl_admin = "        
        .titulo-clave-api {
            margin: 3px!important;
        }

        .btn {
            border:solid 1px #a2a2a2;
            background-color: transparent;
            border-radius: 8px;
            padding: 5px;
        }

        .btn-success {
            background-color: #036935;
            color: #fff;
        } 

        .formulario {
            display: flex;
            justify-content-center; 
        }

        .d-flex {
            display: flex;
        }
        
        .justify-content-start {
            align-items: center;
            justify-content: start ;
            gap: 10px;
        }

        .editar-nota {
            height: 100%;
        }
    ";

    // Añade el script inline
    wp_add_inline_style('esilos-panel-deepl', $style_deepl_admin);

    // Agregar lista de traducciones y opcion para modificar las traducciones. 
    
    $file = get_template_directory() . '/inc/DeepL/notas/notas-reforestamos.json';
    if (file_exists($file)) {
        $json = json_decode(file_get_contents($file), true);

        // Guardar contenido editado
        if (isset($_POST['guardar_ediciones_nota_en']) && isset($_POST['nota_id'], $_POST['contenido_html'])) {
            $id = sanitize_text_field($_POST['nota_id']);
            $nuevoContenido = wp_unslash($_POST['contenido_html']);

            $bloques = preg_split("/\R{2,}/", $nuevoContenido);
            $contenido = [];

            foreach ($bloques as $index => $html) {
                $html = trim($html);
                if ($html === '') continue;

                if (preg_match('/^<([a-z1-6]+)[\s>]/i', $html, $matches)) {
                    $tag = strtolower($matches[1]);
                } else {
                    $tag = 'div';
                }

                $contenido[] = [
                    'type' => $tag,
                    'html' => $html
                ];
            }

            $json[$id]['notaEn']['contenido'] = $contenido;
            file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo '<div class="updated"><p>Contenido actualizado correctamente.</p></div>';
        }

        echo "<div class='lista-notas'>";
        foreach ($json as $id => $nota) {
            if (!isset($nota['notaEn'])) continue;
            echo "<div class='item-nota'>";
            echo '<div class="d-flex justify-content-start">';
            echo "<h3>{$nota['notaEn']['title']}</h3>";
            echo "<button class='button editar-nota' data-target='form-{$id}'>Editar nota</button>";
            echo '</div>';
            echo "<form id='form-{$id}' method='post' style='display:none;'>";
            echo "<input type='hidden' name='nota_id' value='{$id}'>";
            echo "<table class='form-table'><tr><th>Contenido HTML:</th><td>";
            echo "<textarea name='contenido_html' rows='20' cols='100'>";
            foreach ($nota['notaEn']['contenido'] as $i => $bloque) {
                if (!isset($bloque['html']) || trim($bloque['html']) === '') continue;
                echo esc_textarea($bloque['html']) . "\n\n";
            }
            echo "</textarea>";
            echo "</td></tr></table>";
            echo "<p><button class='button button-primary' type='submit' name='guardar_ediciones_nota_en'>Guardar cambios</button></p>";
            echo "</form>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo '<p>No se encontró el archivo JSON.</p>';
    }

    // Script para mostrar y ocultar formularios
    add_action('admin_footer', function() {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.editar-nota').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const formId = this.getAttribute('data-target');
                        const form = document.getElementById(formId);
                        if (form.style.display === 'none') {
                            form.style.display = 'block';
                        } else {
                            form.style.display = 'none';
                        }
                    });
                });
            });
        </script>";
    });
    
}

?>