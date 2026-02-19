<?php
/* Config php mailer */
require_once dirname(__FILE__) . '/inc/php-mailer-config/config.php';
/* Form AyC */
require_once dirname(__FILE__) . '/inc/php-mailer-config/form-AyC.php';
/** Form Red Oja */
require_once dirname(__FILE__) . '/inc/php-mailer-config/form-red-oja.php';
/** Agregar CMB2 **/
require_once dirname(__FILE__) . '/cmb2.php';
require_once dirname(__FILE__) . '/inc/custom-fields.php';
/* CPT Boletín */
require_once dirname(__FILE__) . '/inc/contactos.php';
/** integrantes CPT */
require_once dirname(__FILE__) . '/inc/integrantes/integrantes.php';
/** Queries reutilizables */
require_once dirname(__FILE__) . '/inc/integrantes/queries.php';

/*
* Opciones del Theme
*/
require_once dirname(__FILE__) . '/inc/opciones.php';


/** Red Oja site */
require_once dirname(__FILE__) . '/inc/red_oja_components/update_institutions_json.php';



/** Función que muestra la imagen destacada en el page */
add_action('init', 'reforestamos_imagen_destacada');
function reforestamos_imagen_destacada($id) { 
    $imagen = get_the_post_thumbnail_url($id, 'full'); 
    $titulo = get_the_title();

    $html = '';

    if($imagen) {
        $html .= '<section class="title-background imagen-destacada">';
        $html .= '<h1 class="text-center text-white fw-bold title-general display-2">';
        $html .=  "  $titulo  ";
        $html .= '</h1>';
        $html .= '</section>';

        // Agregamos los estilos linealmente
        wp_register_style('custom', false);
        wp_enqueue_style('custom');

        // Creamos el css para el custom
        $imagen_destacda_css = "
            .imagen-destacada {
                background-image: url( $imagen );
                padding: 0.1px;
            }
        ";
        wp_add_inline_style('custom', $imagen_destacda_css);
    } else {
        $html .= '<section class="title-background bg-info p-1">';
        $html .= '<h1 class="text-center text-white fw-bold title-general display-2">';
        $html .=  "$titulo";
        $html .= '</h1>';
        $html .= '</section>';
    }
    return $html;


}

/** Función que muestra la imagen destacada la nota de blog */
add_action('init', 'reforestamos_imagen_destacada_nota_blog');
function reforestamos_imagen_destacada_nota_blog($id) { 
    $imagen = get_the_post_thumbnail_url($id, 'full'); 
    $titulo = get_the_title();

    $html = '';

    if($imagen) {
        $html .= '<section class="container">';
            $html .= '<div class="row justify-content-center align-items-center title-post imagen-destacada rounded-3">';
                $html .= '<h1 class="text-center text-white fw-bold title-general display-2">';
                $html .=  "  $titulo  ";
                $html .= '</h1>';
            $html .= "</div>";
        $html .= '</section>';

        // Agregamos los estilos linealmente
        wp_register_style('custom', false);
        wp_enqueue_style('custom');

        // Creamos el css para el custom
        $imagen_destacda_css = "
            .imagen-destacada {
                background-image: url( $imagen );
                padding: 0.1px;
            }
        ";
        wp_add_inline_style('custom', $imagen_destacda_css);
    } else {
        $html .= '<section class="title-post bg-info p-1">';
        $html .= '<h1 class="text-center text-white fw-bold title-general display-2">';
        $html .=  "$titulo";
        $html .= '</h1>';
        $html .= '</section>';
    }
    return $html;


}

/** Función que muestra la palabra error */
add_action('init', 'reforestamos_nota_error');
function reforestamos_nota_error() { 
    $error = ' ';
    $error .= ' <h3 class="text-danger text-justify p-3">Agrega un dato y sí el problema continua consúltalo con el administrador</h3>';

    return $error;
}

/* Funciones que se activan al cargar el tema */
function reforestamos_setup() {
    // Definir tamaños de las imagenes
    add_image_size('mediano', 340, 320, true);
    add_image_size('chico', 220, 200, true);

    // Añadir Imagen destacada
    add_theme_support('post-thumbnails');
	// Añadir logo
	add_theme_support('custom-logo');



    // Menú de Navegación
    register_nav_menus(array(
        'menu-principal' => esc_html__('Menú Principal', 'Reforestamos México'),
        'menu-llamados-accion' => esc_html__('Menú Llamados a la Acción', 'Reforestamos México'),
        'menu-redes-sociales' => esc_html__('Menú Redes Sociales', 'Reforestamos México'),
        'menu-footer-sitios-interes' => esc_html__('Menú Sitios de Interés', 'Reforestamos México'),
        'menu-mapa-footer'           => esc_html__('Directorio Footer', 'Reforestamos México'),
        'menu-contacto-footer'       => esc_html__('Contacto Footer', 'Reforestamos México'),
        'aviso-privacidad'       => esc_html__('Aviso de privacidad', 'Reforestamos México'),   
    ));
}
add_action('after_setup_theme', 'reforestamos_setup' );


/** * Agrega la clase nav-link de bootstrap al menu principal * **/
function reforestamos_enlace_class($atts, $item, $args) {
    if($args->theme_location == 'menu-principal') {
        $atts['class'] = 'nav-link';
    }

    if($args->theme_location == 'menu-footer-sitios-interes') {
        $atts['class'] = 'nav-link px-0';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'reforestamos_enlace_class', 10, 3);




/** Carga los scripts y css del theme **/
function reforestamos_scripts() {
    /** Styles */
    wp_enqueue_style('style', get_stylesheet_uri(), '5.3.0');
	/* ChatBot */
    wp_enqueue_style('chat', 'https://spontaneous-marigold-97389d.netlify.app/chat/chat.css', array('style'));
	/* estilos generales web */
	wp_enqueue_style('generalStyles', 'https://spontaneous-marigold-97389d.netlify.app/General/style.css', array('style'));
	
    
    wp_enqueue_style('icons-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css');

    /** Shared Icons */
    wp_enqueue_script('sharedIcons', 'https://platform-api.sharethis.com/js/sharethis.js#property=62d1c674ebc31f0019d5bde7&product=inline-share-buttons', '1.0.0', false);

    /** Scripts */
    wp_enqueue_script('jquery');
    wp_enqueue_script('jqueryWeb', 'https://code.jquery.com/jquery-3.6.1.min.js', '3.6.1', false);
    /** Slider  */
    wp_enqueue_script('sliderJs', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), '1.0.0', false);
    /** Slider CSS */
    wp_enqueue_style('sliderCss', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', array(), '1.0.0' );
    wp_enqueue_style('sliderCss01', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '1.0.0');


    /* Links footer */
    wp_enqueue_script('popperBootstrap', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js', array(), '1.0.0', true);
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js',array(), '5.0.5', true);
	
	/*ChatBot link*/
	wp_enqueue_script('response', 'https://spontaneous-marigold-97389d.netlify.app/chat/response.js', array(), '1.0.0', true);
	wp_enqueue_script('chatJs', 'https://spontaneous-marigold-97389d.netlify.app/chat/chat.js', array('response'), '1.0.0', true);
	
    /* Enfoque Sistémico Link */
	// wp_enqueue_script('enfoque', 'https://spontaneous-marigold-97389d.netlify.app/chat/nave.js', array(), '1.0.0', true);

    /* Iniciativas Menú Link */
	wp_enqueue_script('iniciativas', 'https://spontaneous-marigold-97389d.netlify.app/Iniciativas/iniciativas.js', array(), '1.0.0', true);

    /** Slider JS footer */
    wp_enqueue_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '2.3.1', true);

    /** ChatBot */
    // wp_enqueue_script('response', get_template_directory_uri() . '/src/js/response.js', '1.0.0', true);
    // wp_enqueue_script('chat',  get_template_directory_uri() .  '/src/js/chat.js', array('response'), '1.2.3', true);

    /** JS General */
    // wp_enqueue_script('script', get_template_directory_uri() . './src/js/script.js', '1.0.0', true );

    // Traductor navs - contenido Español/ Inglés
    wp_enqueue_script('traductor', get_template_directory_uri() . '/src/traductor/traductor.js', array(),'1.0.0', true );    
}
add_action('wp_enqueue_scripts', 'reforestamos_scripts');


// Crear Shortcode 
function reforestamos_contacto_shortcode() {
    echo 'Desde shortcorde';
}
add_shortcode('reforestamos_contacto', 'reforestamos_contacto_shortcode');

// Añadir iframes a la lista de elementos permitidos
function permitir_iframes_en_wysiwyg($allowedtags) {
    $allowedtags['iframe'] = array(
        'src' => array(),
        'width' => array(),
        'height' => array(),
        'frameborder' => array(),
        'allowfullscreen' => array()
    );
    return $allowedtags;
}
add_filter('wp_kses_allowed_html', 'permitir_iframes_en_wysiwyg', 10, 1);


// función que centra las imágenes de forma automática
// Código js para la notas del blog 
function enqueue_imagenes_blog_script() {
    // Verificar si estamos en una página que muestra el contenido de las notas del blog
    if (is_single()) {
        // Enqueue el archivo JavaScript principal
        wp_enqueue_script('imagenes-blog', get_template_directory_uri() . '/src/blog/imagenes.js', array(), '1.0', true);

        // Agregar código JavaScript específico dentro de la función

        // Agregar el script en línea al archivo JavaScript encolado
        // wp_add_inline_script('imagenes-blog', $script);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_imagenes_blog_script');

// Script pop-up empresas
function script_pop_up_empresas() {
    // condicional que identifica a la página de empresas
    if(is_page_template('page-empresas.php') ) {
        // Script pop-up
        wp_enqueue_script('pop-up_empresas', get_template_directory_uri() . '/src/js/Pop-up_Empresas/pop-up.js', array(), '1.0', true);

        // Styles pop-up
        wp_enqueue_style('pop-up_empresas', get_template_directory_uri() . '/src/css/Templates/Empresas/pop-up.css', array(), '1.0');

        // Script counter
        wp_enqueue_script('count_empresas', get_template_directory_uri() . '/src/js/Pop-up_Empresas/count.js', array(), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'script_pop_up_empresas');


// enqueue contador clics en página empresas
function enqueue_click_counter_script() {
    wp_enqueue_script('count_empresas', get_template_directory_uri() . '/src/js/Pop-up_Empresas/count.js', array('jquery'), null, true);

    // Pasar la URL de admin-ajax.php a tu script JavaScript
    wp_localize_script('count_empresas', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
    // wp_localize_script('count_empresas', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', 'enqueue_click_counter_script');

// function para alamcenar los clics 
function record_click() {
    // Verificar y obtener los datos de la solicitud POST
    if (empty($_POST['empresa']) || !isset($_POST['clics'])) {
        wp_send_json_error(array('message' => 'Valores inválidos recibidos'));
        return;
    }

    // Obtener y sanitizar los datos
    $empresa = sanitize_text_field($_POST['empresa']);
    $clics = intval($_POST['clics']);

    // Conectar a la base de datos externa
    $host = 'localhost';
    $db = 'db7hdgtqpymeql';
    $user = 'uxnytjopubqjx';
    $pass = 'plwekawqeksc';
    $port = '3306'; // Por ejemplo, 3306

    $conn = new mysqli($host, $user, $pass, $db, $port);

    // Verificar la conexión
    if ($conn->connect_error) {
        wp_send_json_error(array('message' => 'Error de conexión a la base de datos: ' . $conn->connect_error));
        return;
    }

    // Comprobar si la empresa ya existe en la base de datos
    $stmt = $conn->prepare("SELECT * FROM wp_clics_logo_galeria WHERE empresa = ?");
    $stmt->bind_param("s", $empresa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si la empresa ya existe, actualizar el conteo de clics
        $row = $result->fetch_assoc();
        $updated_clics = $row['clics'] + $clics;
        $stmt = $conn->prepare("UPDATE wp_clics_logo_galeria SET clics = ? WHERE empresa = ?");
        $stmt->bind_param("is", $updated_clics, $empresa);
    } else {
        // Si la empresa no existe, insertar una nueva fila
        $stmt = $conn->prepare("INSERT INTO wp_clics_logo_galeria (empresa, clics) VALUES (?, ?)");
        $stmt->bind_param("si", $empresa, $clics);
    }

    if ($stmt->execute()) {
        wp_send_json_success(array('message' => 'Datos recibidos correctamente: empresa=' . $empresa . ', clics=' . $clics));
    } else {
        wp_send_json_error(array('message' => 'Error al guardar en la base de datos: ' . $stmt->error));
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}

// Hook para manejar la solicitud AJAX
add_action('wp_ajax_record_click', 'record_click');
add_action('wp_ajax_nopriv_record_click', 'record_click'); // Permite acceso a usuarios no autenticados

// add item in admin_menu 
function clics_menu_page() {
    add_menu_page(
        'Vistas a galerías (Empresas)', // Título de la página
        'Vistas a galerías (Empresas)', // Título del menú
        'manage_options', // Capacidad requerida
        'clics-logos', // Slug del menú
        'clics_logos_page_html', // Función que mostrará el contenido
        'dashicons-chart-bar', // Icono del menú
        20 // Posición del menú
    );
}
add_action('admin_menu', 'clics_menu_page');

function clics_logos_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    global $wpdb;
    // Conectar a la base de datos externa
    $host = 'localhost';
    $db = 'db7hdgtqpymeql';
    $user = 'uxnytjopubqjx';
    $pass = 'plwekawqeksc';
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
                                          ''; 
      
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
    $db = 'db7hdgtqpymeql';
    $user = 'uxnytjopubqjx';
    $pass = 'plwekawqeksc';
    $port = '3306';
    

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


// Micrositio Arboles y Ciudades
// Scripts
function enqueue_AyC_script() {
    if(is_page_template('page-arboles-ciudades.php') ) {
        wp_enqueue_style('style_ayc_site', get_template_directory_uri() . '/src/css/Templates/arboles_ciudades/arboles.css',  array() , '1.0.0');
        wp_enqueue_script('script_ayc_site', get_template_directory_uri() . '/src/js/arboles_ciudades_site/script.js', array('jquery'), null, true);

        // Pasar la URL de admin-ajax.php al archivo JavaScript
        wp_localize_script('script_ayc_site', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
        
        // wp_localize_script('count_empresas', 'ajaxurl', admin_url('admin-ajax.php'));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_AyC_script');


// Micrositio Red Oja
function enqueue_Red_Oja_script() {
    if(is_page_template('page-red-oja.php') ) {
        wp_enqueue_style('style_red_Oja_site', get_template_directory_uri() . '/src/css/Templates/red_oja/red_oja.css',  array() , '1.0.0');
        wp_enqueue_script('script_red_Oja_site', get_template_directory_uri() . '/src/js/red_oja_site/script.js', array('jquery'), null, true);
        // wp_enqueue_script('script_mapa_site', get_template_directory_uri() . '/src/js/arboles_ciudades_site/mapa.js', array('jquery'), null, true);

        wp_localize_script('script_red_Oja_site', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'json_url' => get_template_directory_uri() . '/inc/json_files/institutions.json' // Ruta del archivo JSON
        ));
        
    }
}
add_action('wp_enqueue_scripts', 'enqueue_Red_Oja_script');