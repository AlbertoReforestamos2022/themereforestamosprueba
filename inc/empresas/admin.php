<?php
// Adding CPT 'Empresas'
function register_empresas_cpt() {
    // Labels del CPT Empresas
    $labels = array(
        'name'                  => _x( 'Contenido empresas', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Contenido empresa', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Contenido empresa', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Empresa', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Agregar Nueva Empresa', 'textdomain' ),
        'add_new_item'          => __( 'Agregar Nueva Empresa', 'textdomain' ),
        'new_item'              => __( 'Nueva Empresa', 'textdomain' ),
        'edit_item'             => __( 'Editar Empresa', 'textdomain' ),
        'view_item'             => __( 'Ver Empresa', 'textdomain' ),
        'all_items'             => __( 'Todas las Empresas', 'textdomain' ),
        'search_items'          => __( 'Buscar Empresas', 'textdomain' ),
        'not_found'             => __( 'No se encontraron Empresas.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No se encontraron Empresas en la papelera', 'textdomain' ),
        'featured_image'        => _x( 'Imagen Destacada', 'Overrides the “Featured Image” phrase', 'textdomain' ),
        'set_featured_image'    => _x( 'Agregar imagen destacada', 'Overrides the “Set featured image” phrase', 'textdomain' ),
        'remove_featured_image' => _x( 'Borrar imagen destacada', 'Overrides the “Remove featured image” phrase', 'textdomain' ),
        'use_featured_image'    => _x( 'Usar como imagen destacada', 'Overrides the “Use as featured image” phrase', 'textdomain' ),
        'archives'              => _x( 'Archivo de Empresas', 'Post type archive label', 'textdomain' ),
        'insert_into_item'      => _x( 'Insertar en Empresa', 'Overrides the “Insert into post” phrase', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Cargado en Empresa', 'Overrides the “Uploaded to this post” phrase', 'textdomain' ),
        'items_list_navigation' => _x( 'Navegación de Empresas', 'Text for pagination', 'textdomain' ),
        'items_list'            => _x( 'Lista de Empresas', 'Screen reader text', 'textdomain' ),
    );

    // Argumentos del CPT
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-building',
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'         => true,
        'rewrite'             => array( 'slug' => 'empresas' ),
        'show_in_rest'        => true,
    );

    register_post_type( 'empresas', $args );
}
add_action( 'init', 'register_empresas_cpt' );


// Estilos
function scripts_CPT_empresas_galerias() {
    // condicional que identifica a la página de empresas
    if(is_page_template('page-empresas.php') ) {
        // Component carrusel 
        wp_enqueue_style('carrusel-css', get_template_directory_uri() . '/src/css/components/carrusel/carrusel.css', array(), '1.0'); 
        wp_enqueue_script('carrusel-js', get_template_directory_uri() . '/src/js/components/carrusel/carrusel.js', array(), '1.0'); 

    }
}
add_action('wp_enqueue_scripts', 'scripts_CPT_empresas_galerias');



// Agregar submenú para los clics en "Empresas"
function add_clics_submenu() {
    add_submenu_page(
        'edit.php?post_type=empresas', // El menú CPT de "Empresas"
        'Vistas a galerías (Empresas)', // Título del menú
        'Vistas a galerías', // Nombre del submenú
        'manage_options', // Capacidad requerida
        'clics-logos', // Slug del submenú
        'clics_logos_page_html' // Función que mostrará el contenido
    );
}
add_action('admin_menu', 'add_clics_submenu');


function clics_logos_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    global $wpdb;
    $host = 'localhost';
    $db = 'clics_logos';
    $user = 'root';
    $pass = '';
    $port = '3306'; // Por ejemplo, 3306

    $conn = new mysqli($host, $user, $pass, $db, $port);

    if ($conn->connect_error) {
        echo 'Error de conexión a la base de datos: ' . $conn->connect_error;
        return;
    }

    $result = $conn->query("SELECT * FROM wp_clics_logo_galeria");

    if ($result->num_rows > 0) {
        echo '<h1>Vistas a galerías de empresas</h1>';
        echo '<table class="widefat fixed" cellspacing="0">';
        echo '<thead><tr><th>Empresa</th><th>Vistas</th></thead>';
        echo '<tbody>';
        while ($row = $result->fetch_assoc()) {
            $empresa_modificada = ucwords(substr(str_replace('-', ' ', $row['empresa']),0, -5));
            echo '<tr>';
            echo '<td>' . esc_html($empresa_modificada) . '</td>';
            echo '<td>' . esc_html($row['clics']) . '</td>';
            // echo '<td>' . esc_html($row['fecha']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" style="margin-top:30px;">';
        echo '<input type="hidden" name="action" value="download_clics_stats">';
        echo '<input type="submit" class="button button-primary" value="Descargar archivo">';
        echo '</form>';
    } else {
        echo 'No se encontraron datos.';
    }


    // Aquí debes especificar manualmente el ID del post o encontrar una manera de obtenerlo dinámicamente
    $post_id = 145; // Reemplaza este número con el ID del post correcto

    // Obtener y mostrar los datos del metabox de CMB2
    $empresas = get_post_meta($post_id, 'reforestamos_group_seccion_empresas_b', true);
    if (!empty($empresas)) {
        echo '<div class="content" style="margin-top:30px;">';
        echo '<h1>Conteo material galerías de empresas</h1>';

        echo '<table class="widefat fixed" cellspacing="0">';
        echo '<thead><tr><th><strong>Empresa</strong></th><th><strong>Logo actualizado</strong></th><th><strong>Eventos</strong></th><th><strong>Fotos eventos</strong></th><th><strong>Detalles del eventos</strong></th></thead>';
        echo '<tbody>';
        foreach ($empresas as $empresa) {
            // nombre empresas
            $nombre_empresa = esc_html($empresa['nombre_empresa']);
            // logo empresas
            $logo = esc_html($empresa['logo_empresa_b']);

            // titulo actividades empresas
            $actividad_uno = !empty($empresa['titulo_reforestacion']) && 
                             !empty($empresa['fecha_actividad_uno']) ? 
                             '<p>'. esc_html($empresa['titulo_reforestacion']) . ' el ' . esc_html($empresa['fecha_actividad_uno']) .'</p>' : 
                             ''; 

            $actividad_dos = !empty($empresa['titulo_mantenimiento']) && 
                             !empty($empresa['fecha_actividad_dos']) ? 
                             '<p>'. esc_html($empresa['titulo_mantenimiento']) . ' el ' . esc_html($empresa['fecha_actividad_dos']) .'</p>' : 
                             '';

            $actividad_tres = !empty($empresa['titulo_brechas']) && 
                              !empty($empresa['fecha_actividad_tres']) ? 
                              '<p>'. esc_html($empresa['titulo_brechas']) . ' el ' . esc_html($empresa['fecha_actividad_tres']) .'</p>' : 
                              '';
            $actividad_cuatro = !empty($empresa['titulo_otras_asctividades']) && 
                                !empty($empresa['fecha_actividad_cuatro']) ? 
                                '<p>'. esc_html($empresa['titulo_otras_asctividades']) . ' el ' . esc_html($empresa['fecha_actividad_cuatro']) .'</p>' : 
                                '';
                                
            $actividad_cinco = !empty($empresa['titulo_otras_asctividades_dos']) && 
                               !empty($empresa['fecha_actividad_cinco']) ? 
                               '<p>'. esc_html($empresa['titulo_otras_asctividades_dos']) . ' el ' . esc_html($empresa['fecha_actividad_cinco']) .'</p>' : 
                               '';

            // Fotos actividades de empresas
            $imagenes_actividad_uno = !empty($empresa['titulo_reforestacion']) && 
                                      !empty($empresa['imagenes_reforestacion']) ? 
                                      '<p> Si </p>' : 
                                      ''; 
  
            $imagenes_actividad_dos = !empty($empresa['titulo_mantenimiento']) && 
                                      !empty($empresa['imagenes_mantenimiento']) ? 
                                      '<p> Si </p>' : 
                                      ''; 
  
            $imagenes_actividad_tres = !empty($empresa['titulo_brechas']) && 
                                       !empty($empresa['imagenes_brechas']) ? 
                                       '<p> Si </p>' : 
                                       ''; 

            $imagenes_actividad_cuatro = !empty($empresa['titulo_otras_asctividades']) && 
                                         !empty($empresa['imagenes_otras_actividades']) ? 
                                         '<p> Si </p>' : 
                                         ''; 
                                  
            $imagenes_actividad_cinco = !empty($empresa['titulo_otras_asctividades_dos']) && 
                                        !empty($empresa['imagenes_otras_actividades_dos']) ? 
                                        '<p> Si </p>' : 
                                        '';   
                                    
            // Detalles actividades de empresas
            $detalles_actividad_uno = !empty($empresa['titulo_reforestacion']) && 
                                      !empty($empresa['texto_reforestacion']) ? 
                                      '<p> Si </p>' : 
                                      '<p> No </p>';
  
            $detalles_actividad_dos = !empty($empresa['titulo_mantenimiento']) && 
                                      !empty($empresa['texto_matenimiento']) ? 
                                      '<p> Si </p>' : 
                                      ''; 
  
            $detalles_actividad_tres = !empty($empresa['titulo_brechas']) && 
                                       !empty($empresa['texto_brechas']) ? 
                                       '<p> Si </p>' : 
                                       ''; 

            $detalles_actividad_cuatro = !empty($empresa['titulo_otras_asctividades']) && 
                                         !empty($empresa['texto_otras_actividades']) ? 
                                         '<p> Si </p>' : 
                                         ''; 
                                  
            $detalles_actividad_cinco = !empty($empresa['titulo_otras_asctividades_dos']) && 
                                        !empty($empresa['texto_otras_actividades_dos']) ? 
                                        '<p> Si </p>' : 
                                        '';

            echo '<tr>';
            // Nombre empresa
            echo '<td style="border-bottom:.5px solid #c3c4c7;">' . '<h4>'. $nombre_empresa . '</h4>' . '</td>';
            // Logo empresa
            echo '<td style="border-bottom:.5px solid #c3c4c7;"> <img src="'. $logo .'" width="100" alt=""> </td>'; 
            // Actividades empresa
            echo '<td style="border-bottom:.5px solid #c3c4c7;">' . $actividad_uno . '</br>' . $actividad_dos . '</br>' . $actividad_tres . '</br>' . $actividad_cuatro . '</br>' . $actividad_cinco . '</br>' . '</td>';
            // Fotos empresas
            echo '<td style="border-bottom:.5px solid #c3c4c7;">' . $imagenes_actividad_uno . '</br>' . $imagenes_actividad_dos . '</br>' . $imagenes_actividad_tres . '</br>' . $imagenes_actividad_cuatro . '</br>' . $imagenes_actividad_cinco . '</br>' . '</td>';
            // Detalles empresas
            echo '<td style="border-bottom:.5px solid #c3c4c7;">' . $detalles_actividad_uno . '</br>' . $detalles_actividad_dos . '</br>' . $detalles_actividad_tres . '</br>' . $detalles_actividad_cuatro . '</br>' . $detalles_actividad_cinco . '</br>' . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '<div class="content" style="margin-top:30px;">';

    } else {
        echo '<p>No se encontraron datos de empresas en CMB2.</p>';
    }

    $conn->close();
}

function download_clics_stats() {
    global $wpdb;
    $host = 'localhost';
    $db = 'clics_logos';
    $user = 'root';
    $pass = '';
    $port = '3306'; // Por ejemplo, 3306

    $conn = new mysqli($host, $user, $pass, $db, $port);

    if ($conn->connect_error) {
        wp_die('Error de conexión a la base de datos: ' . $conn->connect_error);
    }

    $result = $conn->query("SELECT * FROM wp_clics_logo_galeria");

    if ($result->num_rows > 0) {
        $filename = 'vistas_empresas_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        fputcsv($output, array('#', 'Empresa', 'vistas'));
        
        while ($row = $result->fetch_assoc()) {
            $empresa = ucwords(substr(str_replace('-', ' ', $row['empresa']),0, -5));
            fputcsv($output, array($row['id'], $empresa, $row['clics']));
        }

        fclose($output);
    } else {
        wp_die('No se encontraron datos para descargar.');
    }

    $conn->close();
    exit();
}
add_action('admin_post_download_clics_stats', 'download_clics_stats');