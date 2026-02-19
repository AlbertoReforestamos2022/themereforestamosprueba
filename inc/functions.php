<?php
/* Config php mailer */
require_once dirname(__FILE__) . '/inc/php-mailer-config/config.php';

/** Agregar CMB2 **/
require_once dirname(__FILE__) . '/cmb2.php';
require_once dirname(__FILE__) . '/inc/custom-fields.php';
/* CPT Boletín */
require_once dirname(__FILE__) . '/inc/contactos.php';
/** integrantes CPT */
require_once dirname(__FILE__) . '/inc/integrantes/integrantes.php';
/** Queries reutilizables */
require_once dirname(__FILE__) . '/inc/integrantes/queries.php';

/** DeepL API */
require_once dirname(__FILE__) . '/inc/DeepL/config/admin-translator.php';
require_once dirname(__FILE__) . '/inc/DeepL/config/save-json-text.php'; 

/*
* Opciones del tema
*/
require_once dirname(__FILE__) . '/inc/opciones-tema/opciones.php';

/* Form AyC */
require_once dirname(__FILE__) . '/inc/php-mailer-config/form-AyC.php';

// Galeria Imgs CPT
// require_once dirname(__FILE__) . '/inc/empresas/admin.php';
// require_once dirname(__FILE__) . '/inc/empresas/queries.php';
// require_once dirname(__FILE__) . '/inc/empresas/cards/template-empresas.php';



/** Red Oja site */

// Form Red Oja 
require_once dirname(__FILE__) . '/inc/php-mailer-config/form-red-oja.php';
// Components
require_once dirname(__FILE__) . '/inc/red_oja_components/update_institutions_json.php';
// require_once dirname(__FILE__) . '/inc/red_oja_components/update_dates_files_section.php';



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




##  Carga los scripts y css del theme 
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


## Crear Shortcode 
function reforestamos_contacto_shortcode() {
    echo 'Desde shortcorde';
}
add_shortcode('reforestamos_contacto', 'reforestamos_contacto_shortcode');

## Añadir iframes a la lista de elementos permitidos
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


## Código js para la notas del blog 
function enqueue_notas_blog_script() {
    // Verificar si estamos en una página que muestra el contenido de las notas del blog
    if (is_single()) {
            // Enqueue el archivo JavaScript principal
            wp_enqueue_script('imagenes-blog', get_template_directory_uri() . '/src/blog/imagenes.js', array(), '1.0', true);

            // Enqueue el archivo JavaScript principal
            wp_enqueue_script('traduccion-notas', get_template_directory_uri() . '/src/blog/traductor-notas.js', array(),'1.0', true);

            wp_localize_script('traduccion-notas', 'ajax_object', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('traducir_nota_nonce'),
                'post_id'  => get_the_ID(),
                'notas_json' => get_template_directory_uri() . '/inc/DeepL/notas/notas-reforestamos.json'
            ));

    }
}
add_action('wp_enqueue_scripts', 'enqueue_notas_blog_script');



/**
 * Página empresas
 */

## Script pop-up empresas
function script_pop_up_empresas() {
    // condicional que identifica a la página de empresas
    if(is_page_template('page-empresas.php') ) {
        // Script pop-up
        wp_enqueue_script('pop-up_empresas', get_template_directory_uri() . '/src/js/Pop-up_Empresas/pop-up.js', array(), '1.0', true);
        // Styles pop-up
        wp_enqueue_style('pop-up_empresas', get_template_directory_uri() . '/src/css/Templates/Empresas/pop-up.css', array(), '1.0');
        // Script counter
        wp_enqueue_script('count_empresas', get_template_directory_uri() . '/src/js/Pop-up_Empresas/count.js', array('jquery'), '1.0', true);
        // Pasar la URL de admin-ajax.php a tu script JavaScript
        wp_localize_script('count_empresas', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }
}
add_action('wp_enqueue_scripts', 'script_pop_up_empresas');


/**
 * Página infografías
 */
function enqueue_pagina_infografias() {
    // verificar si estamos en la página de infografías. 
    if(is_page_template('page-infografias.php')) {
        // script
        wp_enqueue_script('buscador-script', get_template_directory_uri() . '/src/js/infografias/script.js', array(), '1.0', true); 

        // Style
        wp_enqueue_style('buscador-style', get_template_directory_uri() . '/src/css/Templates/infografias/style.css', array(), '1.0.0'); 
    }
}
add_action('wp_enqueue_scripts', 'enqueue_pagina_infografias'); 


// Micrositio Arboles y Ciudades
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
            // 'json_url' => get_template_directory_uri() . '/inc/json_files/institutions.json', // Datos organizaciones JSON
            'json_url' => get_template_directory_uri() . '/src/js/red_oja_site/institutions.json', // Datos organizaciones JSON
            'geojson_url' => get_template_directory_uri() . '/inc/json_files/nacional_divisiones_3_G.geojson', // Mapa Geojson

            'content_files_url' => get_template_directory_uri() . '/inc/json_files/dates_files_section.json' // Archivos Memorias
        ));
        
    }
}
add_action('wp_enqueue_scripts', 'enqueue_Red_Oja_script');