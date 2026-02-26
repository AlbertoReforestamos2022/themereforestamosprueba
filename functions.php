<?php
/** Config theme */
// Opciones generales
require_once dirname(__FILE__) . '/inc/config-theme/opciones-tema/opciones.php';

// Template router
// require_once dirname(__FILE__) . '/inc/config-theme/config/template-router.php';

/** Agregar CMB2 **/
require_once dirname(__FILE__) . '/cmb2.php';
/** Custom Fields */
require_once dirname(__FILE__) . '/inc/custom-fields.php';


/* Config php mailer */
require_once dirname(__FILE__) . '/inc/php-mailer-config/config.php';
/* Form AyC */
require_once dirname(__FILE__) . '/inc/php-mailer-config/form-AyC.php';
/** Form Red Oja */
require_once dirname(__FILE__) . '/inc/php-mailer-config/form-red-oja.php';


/** Boletín CPT */
require_once dirname(__FILE__) . '/inc/suscripcion-boletin/contactos.php';

/** integrantes CPT */
require_once dirname(__FILE__) . '/inc/integrantes-rmx/integrantes.php';
/** Queries reutilizables */
require_once dirname(__FILE__) . '/inc/integrantes-rmx/queries.php';

/** Eventos Reforestamos México CPT */
require_once dirname(__FILE__) . '/inc/eventos/eventos.php';
/* Eventos Reforestamos México Queries */
require_once dirname(__FILE__) . '/inc/eventos/queries.php';

/* Galerías generales para reutilizar CPT */
// require_once dirname(__FILE__) . '/inc/galeria/galeria.php';

/** Vistas galerías página de empresas CPT */
require_once dirname(__FILE__) . '/inc/empresas/admin.php';
require_once dirname(__FILE__) . '/inc/empresas/queries.php';
require_once dirname(__FILE__) . '/inc/empresas/cards/template-empresas.php'; 


/** ChatBot Reforestamos */
require_once dirname(__FILE__) . '/inc/chat-bot/admin.php';
require_once dirname(__FILE__) . '/inc/chat-bot/chat-frontend.php';


/** DeepL API */
require_once dirname(__FILE__) . '/inc/DeepL/config/admin-translator.php';
// require_once dirname(__FILE__) . '/inc/DeepL/api.php';
require_once dirname(__FILE__) . '/inc/DeepL/config/save-json-text.php'; 



/** Micrositios Árboles y ciudades / Red OJA */
// Arboles y Ciudades components
# require_once dirname(__FILE__) . '/inc/arboles_ciudades_components/update_recognized_states_json.php';


// Red Oja components 
# require_once dirname(__FILE__) . '/inc/red_oja_components/update_institutions_json.php';
# require_once dirname(__FILE__) . '/inc/red_oja_components/update_dates_files_section.php';



/** 
 * PHPSpreadsheet 
 **/
# require_once dirname(__FILE__) . '/inc/html-reading-office/vendor/autoload.php';

// función leer excel 
# require_once dirname(__FILE__) . '/inc/html-reading-office/leer-excel.php';


/**
 * estilos y scripts para front-page.php
 */
add_action('wp_enqueue_scripts', 'styles_scripts_in_front_page');
function styles_scripts_in_front_page(){
    if(is_front_page()) {
        wp_enqueue_style('style_front_page', get_template_directory_uri() . '/src/css/Templates/front-page/front-page.css',  array() , '1.0.0');

        wp_enqueue_script('script_front_page', get_template_directory_uri() . '/src/js/Templates/front-page/script.js', array('jquery'), null, true);

    }
}


// Quitar el editor principal de las páginas y de los CPT
add_action('init', 'quitar_editor_principal'); 
function quitar_editor_principal() {
    // obtenemos todos los tipos de contenido
    $post_types = get_post_types(array('public' => true), 'names');

    foreach($post_types as $post_type) {
        if( $post_type !== 'post') {
             remove_post_type_support($post_type, 'editor'); 
        }
    }
}

// Agregar Bootstrap popper al CPT empresas
function enqueue_bootstrap_for_cpt() {
    if ( is_singular('empresas') ) {
        // Popper
        wp_enqueue_script(
            'popperBootstrap',
            'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js',
            array(),
            '2.11.6',
            true
        );

        // Bootstrap JS
        wp_enqueue_script(
            'bootstrap-js',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js',
            array('popperBootstrap'),
            '5.3.0',
            true
        );

        // Bootstrap CSS también (si no lo tienes encolado globalmente)
        wp_enqueue_style(
            'bootstrap-css',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css',
            array(),
            '5.3.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap_for_cpt');


/** 
 * Función que muestra la imagen destacada en el page 
 **/

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

/** 
 * Función que muestra la imagen destacada la nota de blog 
 **/
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

/**
 * Función para agregar el carrusel en el micrositio de Árboles y cidades. 
 * 
 */
add_action('init', 'reforestamos_carrusel_inicio_arboles_ciudades');
function reforestamos_carrusel_inicio_arboles_ciudades() {

}

/** 
 * Función que muestra la palabra error 
 **/
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

    // Menú de Navegación
    register_nav_menus(array(
        'menu-principal' => esc_html__('Menú Principal', 'Reforestamos México'),
        'menu-llamados-accion' => esc_html__('Menú Llamados a la Acción', 'Reforestamos México'), // Pendiente
        'menu-redes-sociales' => esc_html__('Menú Redes Sociales', 'Reforestamos México'), // Pendiente
        'menu-footer-sitios-interes' => esc_html__('Menú Sitios de Interés', 'Reforestamos México'),
        'menu-mapa-footer'           => esc_html__('Directorio Footer', 'Reforestamos México'),
        'menu-contacto-footer'       => esc_html__('Contacto Footer', 'Reforestamos México'),
        'aviso-privacidad'       => esc_html__('Aviso de privacidad', 'Reforestamos México'),  
        
        // Menús inglés 
        
    ));
}
add_action('after_setup_theme', 'reforestamos_setup' );

/**
 * Quitar el editor principal de todas las páginas
 */
add_action( 'init', 'quitar_editor_en_paginas' );
function quitar_editor_en_paginas() {
    // Solo aplica a las páginas
    remove_post_type_support( 'page', 'editor' );
}


// Ordenar resultados buscador
add_action( 'pre_get_posts', 'dcms_sort_post_type_search_results' );
function dcms_sort_post_type_search_results( $query ) {
	if ( $query->is_search && $query->is_main_query() ) {
		$query->set( 'orderby', [ 'post_type' => 'ASC', 'date' => 'DESC' ] );
	}
}

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
    wp_enqueue_style('chat', get_template_directory_uri() . './dist/chat.css', array('style'));
    // Bootstrap Icons
    wp_enqueue_style('bootstrap-icons', get_template_directory_uri() . '/node_modules/bootstrap-icons/font/bootstrap-icons.css');

    /** Shared Icons */
    wp_enqueue_script('sharedIcons', 'https://platform-api.sharethis.com/js/sharethis.js#property=62d1c674ebc31f0019d5bde7&product=inline-share-buttons', '1.0.0', false);

    /** Scripts */
    wp_enqueue_script('jquery');
    wp_enqueue_script('jqueryWeb', 'https://code.jquery.com/jquery-3.6.1.min.js', '3.6.1', false);

    /* Script Iniciativas */
    // wp_enqueue_script('script-iniciativas', get_template_directory_uri() . '/src/js/iniciativas.js', array(), '1.0.0', true );

    /** Script Nave Nodriza */
    // wp_enqueue_script('script-nave', get_template_directory_uri() . '/src/js/nave.js', array(), '1.0.0', true );


    /** Slider  */
    wp_enqueue_script('sliderJs', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), '1.0.0', false);

    /** Slider CSS */
    wp_enqueue_style('sliderCss', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', array(), '1.0.0' );
    wp_enqueue_style('sliderCss01', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '1.0.0');

    /* Links footer */
    wp_enqueue_script('popperBootstrap', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js', array(), '1.0.0', true);
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js',array(), '5.0.5', true);

    /** Slider JS footer */
    wp_enqueue_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '2.3.1', true);

    /** ChatBot */
    wp_enqueue_script('response', get_template_directory_uri() . './src/js/ChatBot_Reforestamos/response.js', '1.0.0', true);
    wp_enqueue_script('chat',  get_template_directory_uri() .  './src/js/ChatBot_Reforestamos/chat.js', array('response'), '1.2.3', true);

    /** JS General */
    wp_enqueue_script('script', get_template_directory_uri() . './src/js/script.js', '1.0.0', true );

    // Traductor Español/ Inglés
    wp_enqueue_script('traductor', get_template_directory_uri() . './src/traductor/traductor.js', array(),'1.0.0', true );

    // Calendario
    // wp_enqueue_script('calendario', get_template_directory_uri() . './src/calendario/calendario.js', array(), '1.0.0', true);

    // Verificar si estamos en un CPT específico
    if (is_singular('reforestamos_eventos')) {
        // Registrar y encolar el script
        wp_enqueue_script('calendario', get_template_directory_uri() . './src/calendario/calendario.js', array(), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'reforestamos_scripts');

// Crear Shortcode 
function reforestamos_contacto_shortcode() {
    echo 'Desde shortcorde';
}
add_shortcode('reforestamos_contacto', 'reforestamos_contacto_shortcode');

// Formulario buscar
add_action("wp_enqueue_scripts", "reforestamos_buscar_notas");
function reforestamos_buscar_notas(){
	$params = [
		'div' => 'notas-content.notas-col',
		'placeholder' => 'Buscar notas'
	];
    wp_enqueue_script('searchtable', get_stylesheet_directory_uri(). '/src/js/search.js', array('jquery'), '1', true );
	wp_localize_script('searchtable', 'vars_table', $params);
}

/** Agregar un mensaje personalizado en el CPT de Intergrantes en el admin */
add_action('display_post_states', 'reforestamos_cambiar_estado', 10, 2);
function reforestamos_cambiar_estado($states, $post) {
    if( ('page' === get_post_type($post->ID )) && ('page-integrantes.php' == get_page_template_slug( $post->ID)) ){
        $states[] = _('Página de integrantes reforestamos <a href="edit.php?post_type=integrantes_rmx">Administrar integrantes</a>');

    }
    return $states;
}
function script_calendario() {

}
add_action('wp_enqueue_scripts', 'script_calendario');



// Función para permitir iframes
function permitir_iframes_en_wysiwyg($allowedtags) {
    // Etiquetas y atributos para <iframe>
    $extra_iframe_tags = array(
        'iframe' => array(
            'src' => array(),
            'width' => array(),
            'height' => array(),
            'frameborder' => array(),
            'allowfullscreen' => array()
        )
    );

    // Mezclar las etiquetas adicionales con las etiquetas permitidas existentes
    return array_merge($allowedtags, $extra_iframe_tags);
}

// Función para permitir etiquetas como <br>, <hr>, <img>
function permitir_etiquetas_adicionales_en_wysiwyg($allowedtags) {
    // Etiquetas y atributos adicionales
    $extra_tags = array(
        'br' => array(), // Permite <br> y <br/>
        'hr' => array(), // Permite <hr>
        'img' => array( // Permite <img> con atributos
            'src' => array(),
            'alt' => array(),
            'width' => array(),
            'height' => array()
        )
    );

    // Mezclar las etiquetas adicionales con las etiquetas permitidas existentes
    return array_merge($allowedtags, $extra_tags);
}

// Filtrar el contenido antes de guardarlo para que los saltos de línea no se eliminen
function permitir_saltos_de_linea($content) {
    // Reemplazar los saltos de línea dobles o simples con etiquetas <br>
    $content = preg_replace('/\r\n|\r|\n/', '<br />', $content);
    return $content;
}

// Aplicar los filtros
add_filter('wp_kses_allowed_html', 'permitir_iframes_en_wysiwyg', 10, 1);
add_filter('wp_kses_allowed_html', 'permitir_etiquetas_adicionales_en_wysiwyg', 10, 1);
add_filter('the_content', 'permitir_saltos_de_linea', 10, 1); // Asegura que los saltos de línea se mantengan
add_filter('the_excerpt', 'permitir_saltos_de_linea', 10, 1); // Aplicar también en extractos



/**
 * Página inografías
 */
function enqueue_pagina_infografias() {
    // verificar si estamos en la página de infografías. 
    if(is_page_template('page-infografias.php')) {
        // script
        wp_enqueue_script('buscador-script', get_template_directory_uri() . '/src/js/Templates/infografias/script.js', array(), '1.0', true); 

        // Style
        wp_enqueue_style('buscador-style', get_template_directory_uri() . '/src/css/Templates/infografias/style.css', array(), '1.0.0'); 
    }
}
add_action('wp_enqueue_scripts', 'enqueue_pagina_infografias'); 


/**
 * Notas de Blog
*/

// función para encolar scripts y ajax en el template 
function enqueue_pagina_blog_en() {
    global $template;

    if (basename($template) === 'single-en.php') {
        wp_enqueue_script('traduccion-nota-blog', get_template_directory_uri() . '/inc/DeepL/frontend/script-en.js', array('jquery'), '1.0.0', true);

        wp_localize_script('traduccion-nota-blog', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('traducir_nota_nonce'),
            'post_id'  => get_the_ID(),
            'notas_json' => get_template_directory_uri() . '/inc/DeepL/notas/notas-reforestamos.json'
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_pagina_blog_en');


// Código js para la notas del blog 
function enqueue_notas_blog_script() {
    // Verificar si estamos en una página que muestra el contenido de las notas del blog
    if (is_single()) {
        // Enqueue el archivo JavaScript principal
        // wp_enqueue_script('traduccion-notas', get_template_directory_uri() . '/inc/DeepL/script.js', array('jquery'), null, true);
        wp_enqueue_script('traduccion-notas', get_template_directory_uri() . '/src/blog/traductor-notas.js', array(),'1.0', true);

        // Enqueue el archivo JavaScript principal
        wp_enqueue_script('imagenes-blog', get_template_directory_uri() . '/src/blog/imagenes.js', array(), '1.0', true);

        wp_localize_script('traduccion-notas', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('traducir_nota_nonce'),
            'post_id'  => get_the_ID(),
            'notas_json' => get_template_directory_uri() . '/inc/DeepL/notas/notas-reforestamos.json'
        ));
        

        // Agregar el script en línea al archivo JavaScript encolado
        // wp_add_inline_script('traduccion-notas', $script);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_notas_blog_script');



// contenido en inglés
function content_en() {
    //Inicio

    /** Sobre nosotros */
    if (is_page_template('page-nosotros.php')) {
        // Enqueue tu script JavaScript
        wp_enqueue_script('nosotros_en', get_template_directory_uri() . '/src/js/Templates/nosotros/content_en.js', array(), '1.0', true);
    }

    /** ¿Qué hacemos? */
    if(is_page_template('page-que-hacemos.php')) {
        // Enqueue tu script JavaScript
        wp_enqueue_script('queHacemos_en', get_template_directory_uri() . '/src/js/Templates/que-hacemos/content_en.js', array(), '1.0', true);
    }

    /** Aliados */
    // Empresas

    // Organizaciones de la sociedad civil -  Gobierno
    if(is_page_template('page-aliados.php') ) {
        // Enqueue tu script JavaScript
        wp_enqueue_script('aliados_en', get_template_directory_uri() . '/src/js/Templates/aliados/ongs-gobierno/content_en.js', array(), '1.0', true);
    }
    
    // Calendario
    // Documentos (Informes anuales, documentos de interés, inforgrafías)
    if(is_page_template('page-informes-anuales.php')) {
            // Enqueue tu script JavaScript
            wp_enqueue_script('documentos_single_en', get_template_directory_uri() . '/src/js/Templates/documentos/informes-anuales/content_en.js', array(), '1.0', true); 
    }
    // Blog general

    // Contacto
    if(is_page_template('page-contacto.php') ) {
        // Enqueue tu script JavaScript
        wp_enqueue_script('contacto_en', get_template_directory_uri() . '/src/js/Templates/contacto/content_en.js', array(), '1.0', true);
    }

    // Donar 
    if(is_page_template('page-donar.php') ) {
        // Enqueue tu script JavaScript
        wp_enqueue_script('donar_en', get_template_directory_uri() . '/src/js/Templates/donar/content_en.js', array(), '1.0', true);
    } 

    // Aopta un árbol 
    if(is_page_template('page-adopta-arbol.php') ) {
        // Enqueue tu script JavaScript
        wp_enqueue_script('donar_en', get_template_directory_uri() . '/src/js/Templates/adopta-arbol/content_en.js', array(), '1.0', true);
    } 
    
    // Incendios Forestales
}
add_action('wp_enqueue_scripts', 'content_en');

/*
 * Página empresas 
 */

// Script pop-up empresas
function script_pop_up_empresas() {
    // condicional que identifica a la página de empresas
    if(is_page_template('page-empresas.php') ) {
        // // Component carrusel 
        // wp_enqueue_style('carrusel-css', get_template_directory_uri() . '/src/css/components/carrusel/carrusel.css', array(), '1.0'); 
        // wp_enqueue_script('carrusel-js', get_template_directory_uri() . '/src/js/components/carrusel/carrusel.js', array(), '1.0'); 

        // // Script pop-up
        // wp_enqueue_script('pop-up_empresas', get_template_directory_uri() . '/src/js/Pop-up_Empresas/pop-up.js', array(), '1.0', true);

        // Styles pop-up
        wp_enqueue_style('pop-up_empresas', get_template_directory_uri() . '/src/css/Templates/Empresas/pop-up.css', array(), '1.0');

        // Script counter
        wp_enqueue_script('count_empresas', get_template_directory_uri() . '/src/js/Pop-up_Empresas/count.js', array(), '1.0', true);

        // Contador de logo en empresas
        wp_enqueue_script('count_empresas', get_template_directory_uri() . '/src/js/Pop-up_Empresas/count.js', array('jquery'), null, true);

        // Pasar la URL de admin-ajax.php a tu script JavaScript
        wp_localize_script('count_empresas', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }
}
add_action('wp_enqueue_scripts', 'script_pop_up_empresas');


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
    $db = 'clics_logos';
    $user = 'root';
    $pass = '';
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

/** 
 * Micrositio Arboles y Ciudades  
 * */
// Scripts
function enqueue_AyC_script() {
    if(is_page_template('page-arboles-ciudades.php') ) {
        wp_enqueue_style('style_ayc_site', get_template_directory_uri() . '/src/css/Templates/arboles_ciudades/arboles.css',  array() , '1.0.0');
        wp_enqueue_script('script_ayc_site', get_template_directory_uri() . '/src/js/arboles_ciudades_site/script.js', array('jquery'), null, true);
        

        // Pasar la URL de admin-ajax.php al archivo JavaScript
        wp_localize_script('script_ayc_site', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            // 'estados_mexico_svg' => get_template_directory_uri() . '/inc/json_files/estadosMexicoSVG.json', // Estados SVG
            'recognized_cities_json_url' => get_template_directory_uri() . '/inc/json_files/recognized_states.json', // ciudades reconocidas JSON
            'geojson_url' => get_template_directory_uri() . '/inc/json_files/nacional_divisiones_3_G.geojson', // Mapa Geojson
            'recognized_cities' => get_template_directory_uri() . '/inc/json_files/prev_recognizides_states.json' // Ciudades reconocidas años 2019 - 2024


        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_AyC_script');

// función que lee los datos de uun excel
// add_action('init', 'render_excel_como_tabla');
// function render_excel_como_tabla($ruta_archivo) {
//     echo $ruta_archivo; 

//     $datos = obtener_datos_excel($ruta_archivo);

//     echo $datos; 

//     if (!is_array($datos)) {
//         return '<p>' . esc_html($datos) . '</p>';
//     }

//     if(!empty($ruta_archivo)) {
//         $output = '<table border="1">';
//         foreach ($datos as $fila) {
//             $output .= '<tr>';
//             foreach ($fila as $celda) {
//                 $output .= '<td>' . esc_html($celda) . '</td>';
//             }
//             $output .= '</tr>';
//         }

//         $output .= '</table>';

//         return $output;

//     }


// }

// Micrositio Red Oja
function enqueue_Red_Oja_script() {
    if(is_page_template('page-red-oja.php') ) {
        wp_enqueue_style('style_red_Oja_site', get_template_directory_uri() . '/src/css/Templates/red_oja/red_oja.css',  array() , '1.0.0');
        wp_enqueue_script('script_red_Oja_site', get_template_directory_uri() . '/src/js/red_oja_site/script.js', array('jquery'), null, true);
        // wp_enqueue_script('script_mapa_site', get_template_directory_uri() . '/src/js/arboles_ciudades_site/mapa.js', array('jquery'), null, true);

        wp_localize_script('script_red_Oja_site', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'json_url' => get_template_directory_uri() . '/inc/json_files/institutions.json', // Datos aliados JSON
            'geojson_url' => get_template_directory_uri() . '/inc/json_files/nacional_divisiones_3_G.geojson', // Mapa Geojson
            'content_files_url' => get_template_directory_uri() . '/inc/json_files/dates_files_section.json' // datos documentos

        ));
        
    }
}
add_action('wp_enqueue_scripts', 'enqueue_Red_Oja_script');
