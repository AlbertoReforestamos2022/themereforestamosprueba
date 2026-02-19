<?php

// Separar el contenido de los CMB de cada página

// Importar custom fields del cpt empresas
require_once dirname(__FILE__) . '/empresas/custom-fields/custom-fields.php';

/**
** Titulo en inlglés (Todas las páginas)
**/
add_action( 'cmb2_admin_init', 'reforestamos_titulo_ingles' );
function reforestamos_titulo_ingles() {
	$reforestamos_titulo_ingles = new_cmb2_box( array(
		'id'           => 'titulo_ingles',
		'title'        => esc_html__( '(EN) - Titulo de la pagina en inglés', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
	) );

	$reforestamos_titulo_ingles->add_field(array(
		'name' => esc_html__( '(EN) - Titulo en inglés', 'cmb2' ),
		'id'   => 'titulo_general_en',
		'type' => 'text',
	) );

}

/**
 * Inicio (Principal)
 */
/** Titulo nuestras notas y bellota refotestamos*/ 
add_action( 'cmb2_admin_init', 'reforestamos_campos_homepage' );
function reforestamos_campos_homepage() {
    $prefix = 'reforestamos_home_';
    $id_home = get_option('page_on_front');

	$reforestamos_campos_homepage = new_cmb2_box( array(
		'id'           => $prefix . 'homepage',
		'title'        => esc_html__( 'Bellota y titulo nuestras notas', 'cmb2' ),
		'object_types' => array( 'page' ), // Post type
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, 
		'show_on'      => array(
			'id' => array( $id_home ),
		), 
	) );

    /** Bellota Titulo **/
    $reforestamos_campos_homepage->add_field( array(
		'name' => esc_html__( 'Bellota Titulo Reforestamos', 'cmb2' ),
		'desc' => esc_html__( 'Bellota Titulo Reforestamos', 'cmb2' ),
		'id'   => $prefix . 'imagen_bellota',
		'type' => 'file',
	));

    $reforestamos_campos_homepage->add_field( array(
		'name' => esc_html__( 'Titulo nuestras notas', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo', 'cmb2' ),
		'id'   => $prefix . 'titulo_nuestras_notas',
		'type' => 'text',
	));

	$reforestamos_campos_homepage->add_field( array(
		'name' => esc_html__( 'Titulo nuestras notas en inglés', 'cmb2' ),
		'desc' => esc_html__( 'Agrega le tiulo en inglés', 'cmb2' ),
		'id'   => $prefix . 'titulo_nuestras_notas_en',
		'type' => 'text',
	));
}

/** Pop-Up */
add_action( 'cmb2_admin_init', 'reforestamos_homepage_pop_up' );
function reforestamos_homepage_pop_up() {
    $prefix = 'reforestamos_home_';
    $id_home = get_option('page_on_front');

	$reforestamos_carousel = new_cmb2_box( array(
		'id'           => $prefix . 'popup_main',
		'title'        => esc_html__( 'imágenes popup', 'cmb2' ),
		'object_types' => array( 'page' ),
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, 
		'show_on'      => array(
			'id' => array( $id_home ),
		), 

	) );


	$group_field_id = $reforestamos_carousel->add_field( array(
		'id'          => $prefix . 'popup',
		'type'        => 'group',
		'options'     => array(
			'group_title'    => esc_html__( 'Imagen {#}', 'cmb2' ), 
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
		),
	) );


    $reforestamos_carousel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen pop-up', 'cmb2' ),
		'id'   => 'imagen_popup',
		'type' => 'file',
	) );


    $reforestamos_carousel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'URL - Dirección Imagen si tiene una', 'cmb2' ),
		'desc' => esc_html__( 'Ejemplo => https://www.reforestamosmexico.org/', 'cmb2' ),
		'id'   => 'url_imagen_popup',
		'type' => 'text_url',
	) );

	$reforestamos_carousel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Texto btn ', 'cmb2' ),
		'id'   => 'btn_text',
		'type' => 'text',
	) );

	    $reforestamos_carousel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'url btn', 'cmb2' ),
		'id'   => 'url_btn',
		'type' => 'text',
	) );

}

/** Carousel inicio */
add_action( 'cmb2_admin_init', 'reforestamos_homepage_carousel' );
function reforestamos_homepage_carousel() {
    $prefix = 'reforestamos_home_';
    $id_home = get_option('page_on_front');

	$reforestamos_carousel = new_cmb2_box( array(
		'id'           => $prefix . 'carousel_main',
		'title'        => esc_html__( 'Imagenes carrusel', 'cmb2' ),
		'object_types' => array( 'page' ),
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true,
		'show_on'      => array(
			'id' => array( $id_home ),
		), // Specific post IDs to display this metabox
	) );


	$group_field_id = $reforestamos_carousel->add_field( array(
		'id'          => $prefix . 'carousel',
		'type'        => 'group',
		// 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Imagen {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,

		),
	) );

    $reforestamos_carousel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen Carousel', 'cmb2' ),
		'id'   => 'imagen_carousel',
		'type' => 'file',
	) );


    $reforestamos_carousel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'URL - Dirección Imagen si tiene una', 'cmb2' ),
		'desc' => esc_html__( 'Ejemplo => https://www.reforestamosmexico.org/', 'cmb2' ),
		'id'   => 'url_imagen_carousel',
		'type' => 'text_url',
	) );
}

/** Nuestras Lineas de acción */
add_action( 'cmb2_admin_init', 'reforestamos_homepage_lineas_accion' );
function reforestamos_homepage_lineas_accion() {
    $prefix = 'reforestamos_home_';
    $id_home = get_option('page_on_front');

	$reforestamos_lineas_accion = new_cmb2_box( array(
		'id'           => $prefix . 'lineas_accion',
		'title'        => esc_html__( 'Lineas de Acción', 'cmb2' ),
		'object_types' => array( 'page' ), // Post type
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, // Show field names on the left
		'show_on'      => array(
			'id' => array( $id_home ),
		), // Specific post IDs to display this metabox
	) );

    $reforestamos_lineas_accion->add_field( array(
		'name' => esc_html__( 'Titulo Lineas de acción', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo de la sección ', 'cmb2' ),
		'id'   => $prefix . 'titulo_lineas_accion',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_lineas_accion->add_field( array(
		'id'          => $prefix . 'lineas_accion',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega Lineas de acción', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Linea de acción {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

    $reforestamos_lineas_accion->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen Linea de Acción', 'cmb2' ),
		'id'   => 'imagen_linea_acción',
		'type' => 'file',
	) );

	$reforestamos_lineas_accion->add_group_field( $group_field_id, array(
		'name'       => esc_html__( 'Texto Linea de Acción', 'cmb2' ),
		'id'         => 'texto_linea_acción',
		'type'       => 'textarea',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
}

/** Sitios de interes */
add_action( 'cmb2_admin_init', 'reforestamos_homepage_sitios_interes' );
function reforestamos_homepage_sitios_interes() {
    $prefix = 'reforestamos_home_';
    $id_home = get_option('page_on_front');

	$reforestamos_sitios_interes = new_cmb2_box( array(
		'id'           => $prefix . 'sitios_interes',
		'title'        => esc_html__( 'Sitios de Interés', 'cmb2' ),
		'object_types' => array( 'page' ), // Post type
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, // Show field names on the left
		'show_on'      => array(
			'id' => array( $id_home ), 
		), // Specific post IDs to display this metabox
	) );

    $reforestamos_sitios_interes->add_field( array(
		'name' => esc_html__( 'Titulo Sitios Interés', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo', 'cmb2' ),
		'id'   => $prefix . 'titulo_sitios_interes',
		'type' => 'text',
	) );

	$reforestamos_sitios_interes->add_field( array(
		'name' => esc_html__( 'Titulo sitios interés', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo en inglés', 'cmb2' ),
		'id'   => $prefix . 'titulo_sitios_interes_en',
		'type' => 'text',
	) );

	// logos sitios de interés
	$group_field_id = $reforestamos_sitios_interes->add_field( array(
		'id'          => $prefix . 'sitios_interes',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega logos de las organizaciones', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Organización {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );
	
	$reforestamos_sitios_interes->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Nombre organización, Empresa, A.C.', 'cmb2' ),
		'id'   => 'nombre_imagen_sitio_interes',
		'type' => 'text',
	) );

    $reforestamos_sitios_interes->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Logo organización, Empresa, A.C.', 'cmb2' ),
		'id'   => 'imagen_sitio_interes',
		'type' => 'file',
	) );

	$reforestamos_sitios_interes->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link sitio web organización, Empresa, A.C.', 'cmb2' ),
		'id'   => 'link_imagen_sitios_interes',
		'type' => 'text',
	) );

	$reforestamos_sitios_interes->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'tamaño logo', 'cmb2' ),
		'id'   => 'tamano_imagen_sitios_interes',
		'type' => 'text',
	) );

}
/** 
** Lineas de Acción en inglés
**/ 
add_action( 'cmb2_admin_init', 'reforestamos_homepage_lineas_accion_ingles' );
function reforestamos_homepage_lineas_accion_ingles() {
    $prefix = 'reforestamos_home_';
    $id_home = get_option('page_on_front');

	$reforestamos_lineas_accion_ingles = new_cmb2_box( array(
		'id'           => $prefix . 'lineas_accion_ingles',
		'title'        => esc_html__( 'Lineas de acción en inglés', 'cmb2' ),
		'object_types' => array( 'page' ),
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, 
		'show_on'      => array(
			'id' => array( $id_home ),
		),
	) );

    $reforestamos_lineas_accion_ingles->add_field( array(
		'name' => esc_html__( 'Titulo lineas de acción en inglés', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo de la sección ', 'cmb2' ),
		'id'   => $prefix . 'titulo_lineas_accion_inlges',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_lineas_accion_ingles->add_field( array(
		'id'          => $prefix . 'lineas_accion_ingles',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega Lineas de acción', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Linea de acción {#}', 'cmb2' ), 
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
		),
	) );

    $reforestamos_lineas_accion_ingles->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen linea de acción inglés', 'cmb2' ),
		'id'   => 'imagen_linea_acción_ingles',
		'type' => 'file',
	) );

	$reforestamos_lineas_accion_ingles->add_group_field( $group_field_id, array(
		'name'       => esc_html__( 'Texto linea de acción en inglés', 'cmb2' ),
		'id'         => 'texto_linea_acción_ingles',
		'type'       => 'textarea',
	) );
}

/**
 * Nosotros
 */
/** Sección misión, visión **/
add_action( 'cmb2_admin_init', 'reforestamos_seccion_nosotros' );
function reforestamos_seccion_nosotros() {
    $prefix = 'reforestamos_group_';

	$reforestamos_nosotros = new_cmb2_box( array(
		'id'           => $prefix . 'mision_vision',
		'title'        => esc_html__( 'Sección Misión, Visión', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-nosotros.php' 
        )
	) );

	$group_field_id = $reforestamos_nosotros->add_field( array(
		'id'          => $prefix . 'nosotros',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega opciones según sea necesrio', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Campo {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_nosotros->add_group_field( $group_field_id, array(
		'name'       => esc_html__( 'Titulo', 'cmb2' ),
		'id'         => 'titulo_seccion_nosotros',
		'type'       => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$reforestamos_nosotros->add_group_field( $group_field_id, array(
		'name'        => esc_html__( 'Descripción', 'cmb2' ),
		'description' => esc_html__( 'Agrege una descripción', 'cmb2' ),
		'id'          => 'descripcion_seccion_nosotros',
		'type'    => 'textarea',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	$reforestamos_nosotros->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen', 'cmb2' ),
		'id'   => 'imagen_seccion_nosotros',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 15,
		))
	);

	// Sección en inglés
	$reforestamos_nosotros->add_group_field( $group_field_id, array(
		'name'       => esc_html__( '(EN) Titulo en inglés', 'cmb2' ),
		'description' => esc_html__( 'Agrega el titulo en inglés', 'cmb2' ),
		'id'         => 'titulo_seccion_nosotros_en',
		'type'       => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$reforestamos_nosotros->add_group_field( $group_field_id, array(
		'name'        => esc_html__( '(EN) Descripción en inglés', 'cmb2' ),
		'description' => esc_html__( 'Agrege la descripción en inglés', 'cmb2' ),
		'id'          => 'descripcion_seccion_nosotros_en',
		'type'    => 'textarea',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

}

/* Sección de objetivos */
add_action( 'cmb2_admin_init', 'reforestamos_seccion_objetivos' );
function reforestamos_seccion_objetivos() {
    $prefix = 'reforestamos_group_';

	$reforestamos_objetivos = new_cmb2_box( array(
		'id'           => $prefix . 'objetivo',
		'title'        => esc_html__( 'Sección Objetivos', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-nosotros.php' 
        )
	) );

    $reforestamos_objetivos->add_field( array(
		'name' => esc_html__( 'Titulo objetivos', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo ', 'cmb2' ),
		'id'   => $prefix . 'titulo_objetivos',
		'type' => 'text',
	) );
	$reforestamos_objetivos->add_field( array(
		'name' => esc_html__( '(EN) Titulo objetivos en inglés', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo en inglés', 'cmb2' ),
		'id'   => $prefix . 'titulo_objetivos_en',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_objetivos->add_field( array(
		'id'          => $prefix . 'objetivos',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega Objetivos', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Objetivo {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
		),
	) );

    $reforestamos_objetivos->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen objetivo', 'cmb2' ),
		'id'   => 'imagen_objetivos',
		'type' => 'file',
	) );

	$reforestamos_objetivos->add_group_field( $group_field_id, array(
		'name'       => esc_html__( 'Descripción objetivo', 'cmb2' ),
		'id'         => 'objetivos',
		'type'       => 'text',
	) );

	/** 
	** Sección inglés objetivos 
	**/

	$reforestamos_objetivos->add_group_field( $group_field_id, array(
		'name'       => esc_html__( '(EN) Descripción objetivo', 'cmb2' ),
		'desc'       => esc_html__( 'Agrega la descripción en ingles', 'cmb2' ),
		'id'         => 'objetivos_en',
		'type'       => 'text',
	) );

	/** Colores */
	$reforestamos_objetivos->add_group_field( $group_field_id, array(
		'name'             => esc_html__( 'Fondo card del objetivo', 'cmb2' ),
		'desc'             => esc_html__( 'Selecciona el color de fondo del objetivo', 'cmb2' ),
		'id'               => 'background_objetivo',
		'type'             => 'radio_inline',
		'options'          => array(
			'bg-danger'    => esc_html__( 'Color rojo', 'cmb2' ),
			'bg-primary'   => esc_html__( 'Color verde institucional', 'cmb2' ),
			'bg-success'   => esc_html__( 'Color verde claro', 'cmb2' ),
			'bg-light'     => esc_html__( 'Color verde-agua claro', 'cmb2' ),
		),
	) );

}

/* Sección valores */
add_action( 'cmb2_admin_init', 'reforestamos_seccion_valores' );
function reforestamos_seccion_valores() {
    $prefix = 'reforestamos_group_';

	$reforestamos_valores = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => esc_html__( 'Sección Valores', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-nosotros.php' 
        )
	) );

    $reforestamos_valores->add_field( array(
		'name' => esc_html__( 'Titulo Valores Reforestamos', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo ', 'cmb2' ),
		'id'   => $prefix . 'titulo_valores',
		'type' => 'text',
	) );

	$reforestamos_valores->add_field( array(
		'name' => esc_html__( '(EN) Titulo nuestros valores en inglés', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo en inglés', 'cmb2' ),
		'id'   => $prefix . 'titulo_valores_en',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_valores->add_field( array(
		'id'          => $prefix . 'valores',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega Valores', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Valor {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
		),
	) );

    $reforestamos_valores->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen nuestros valores', 'cmb2' ),
		'id'   => 'imagen_valores',
		'type' => 'file',
	) );

	/**
	* Valores en inglés
	**/
	 $reforestamos_valores->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) Imagen nuestros valores en inglés', 'cmb2' ),
		'id'   => 'imagen_valores_en',
		'type' => 'file',
	) );	 
}

/* Sección procesos de incidencia */
add_action( 'cmb2_admin_init', 'reforestamos_seccion_procesos_incidencia' );
function reforestamos_seccion_procesos_incidencia() {
    $prefix = 'reforestamos_group_';

	$reforestamos_procesos_incidencia = new_cmb2_box( array(
		'id'           => $prefix . 'procesos_incidencia',
		'title'        => esc_html__( 'Sección procesos de incidencia', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-nosotros.php' 
        )
	) );

    $reforestamos_procesos_incidencia->add_field( array(
		'name' => esc_html__( 'Titulo procesos de incidencia', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo ', 'cmb2' ),
		'id'   => $prefix . 'titulo_procesos',
		'type' => 'text',
	) );

	/** Titulo en inglés */
	$reforestamos_procesos_incidencia->add_field( array(
		'name' => esc_html__( '(EN) Titulo procesos de incidencia en inglés', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo en inglés', 'cmb2' ),
		'id'   => $prefix . 'titulo_procesos_en',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_procesos_incidencia->add_field( array(
		'id'          => $prefix . 'procesos',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega los procesos de incidencia', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Proceso de incidencia {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

    $reforestamos_procesos_incidencia->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen proceso de incidencia', 'cmb2' ),
		'id'   => 'imagen_proceso',
		'type' => 'file',
	) );

	/** Contenido en inglés */
	$reforestamos_procesos_incidencia->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) Imagen proceso de incidencia en inglés', 'cmb2' ),
		'desc' => esc_html__( 'Agrega la imagen en inglés', 'cmb2' ),
		'id'   => 'imagen_proceso_en',
		'type' => 'file',
	) );

}

/** Sección logros, reconocimientos y/o acrecitaciones */
add_action( 'cmb2_admin_init', 'reforestamos_seccion_logros' );
function reforestamos_seccion_logros() {
    $prefix = 'reforestamos_group_';

	$reforestamos_logros = new_cmb2_box( array(
		'id'           => $prefix . 'logros',
		'title'        => esc_html__( 'Sección Logros', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-nosotros.php' 
        )
	) );

	// Titulo Logros
    $reforestamos_logros->add_field( array(
		'name' => esc_html__( 'Titulo logros y reconocimientos reforestamos', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo ', 'cmb2' ),
		'id'   => $prefix . 'titulo_logros',
		'type' => 'text',
	) );

	/** Titulo logros en inglés */
	$reforestamos_logros->add_field( array(
		'name' => esc_html__( '(EN) Titulo logros y reconocimientos reforestamos', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo en inglés', 'cmb2' ),
		'id'   => $prefix . 'titulo_logros_en',
		'type' => 'text',
	) );
 
	$group_field_id = $reforestamos_logros->add_field( array(
		'id'          => $prefix . 'logros',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega Logros y/o Reconocimientos', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Logro o Reconocimiento {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );
	
    // Imagen logros
    $reforestamos_logros->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Logo de la Organización - Empresa', 'cmb2' ),
		'id'   => 'imagen_logros',
		'type' => 'file',
	) );
	// Tamaño imagen logros
	$reforestamos_logros->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Tamaño de la imagen', 'cmb2' ),
		'id'   => 'tamanio_imagen',
		'type' => 'text',
	) );
    // Año Logro
    $reforestamos_logros->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Año que se obtuvo el logro - reconocimiento', 'cmb2' ),
		'id'   => 'anio_logro',
		'type' => 'text',
	) );
    // Año Logro Inglés
    $reforestamos_logros->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Año que se obtuvo el logro - reconocimiento', 'cmb2' ),
		'id'   => 'anio_logro_en',
		'type' => 'text',
	) );	
	// Texto logro	
    $reforestamos_logros->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Descripción del logro - reconocimiento', 'cmb2' ),
		'id'   => 'texto_logro',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		))
	);

	/** Texto en inglés */
	$reforestamos_logros->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Descripción del logro - reconocimiento', 'cmb2' ),
		'desc' => esc_html__( 'Agrega la descripción en inglés', 'cmb2' ),
		'id'      => 'texto_logro_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		))
	);

	// Posición Logro	
	$reforestamos_logros->add_group_field( $group_field_id, array(
		'name'             => esc_html__( 'Posición del logro (Derecha - izquierda)', 'cmb2' ),
		'desc'             => esc_html__( 'Selecciona la posición del campo del logro', 'cmb2' ),
		'id'               => 'posicion_logro',
		'type'             => 'radio_inline',
		'options'          => array(
			'left' => esc_html__( 'Izquierda', 'cmb2' ),
			'right'   => esc_html__( 'Derecha', 'cmb2' ),
		),
	) );

}


/**
 * ¿Qué Hacemos?
 */
/** Iniciativa (Modelos de manejo de paisajes) */
add_action( 'cmb2_admin_init', 'reforestamos_hacemos_contenido' );
function reforestamos_hacemos_contenido() {
    $prefix = 'reforestamos_group_';

	$reforestamos_hacemos_contenido_cards = new_cmb2_box( array(
		'id'           => $prefix . 'que_hacemos',
		'title'        => esc_html__( 'Modelos de manejo de paisajes', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-que-hacemos.php' 
        )
	) );

	$reforestamos_hacemos_contenido_cards->add_field( array(
		'name' => esc_html__( 'Titulo principal de la sección', 'cmb2' ),
		'id'   => 'titulo_principal',
		'type' => 'text',
	) );

	/** Titulo en inlgés **/
	$reforestamos_hacemos_contenido_cards->add_field( array(
		'name' => esc_html__( 'Titulo principal de la sección en inglés', 'cmb2' ),
		'id'   => 'titulo_principal_en',
		'type' => 'text',
	) );	

	$group_field_id = $reforestamos_hacemos_contenido_cards->add_field( array(
		'id'          => $prefix . 'que_hacemos_cards',
		'type'        => 'group',
		'description' => esc_html__( 'Modelos de manejo de paisajes (Iniciativas)', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Iniativa {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_hacemos_contenido_cards->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Nombre de la iniciativa', 'cmb2' ),
		'id'   => 'nombre_iniciativa',
		'type' => 'textarea',
	) );

	$reforestamos_hacemos_contenido_cards->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Logo Iniciativa', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'logo_iniciativa',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

	$reforestamos_hacemos_contenido_cards->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Descripción de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_iniciativa_modal',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

	$reforestamos_hacemos_contenido_cards->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link en el botón "Saber Más" de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'id'   => 'link_iniciativa',
		'type' => 'text',
	) );

	$reforestamos_hacemos_contenido_cards->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Opción para mostrar botón "Saber más"', 'cmb2' ),
		'id'      => 'boton_modelos',
		'type'    => 'radio',
		'options' => array(
			'd-block' => esc_html__( 'Mostrar botón', 'cmb2' ),
			'd-none' => esc_html__( 'Ocultar botón', 'cmb2' ),
		),
	) );

	/** Versión en inglés **/
	$reforestamos_hacemos_contenido_cards->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) Nombre de la iniciativa en inglés', 'cmb2' ),
		'id'   => 'nombre_iniciativa_en',
		'type' => 'text',
	) );


	$reforestamos_hacemos_contenido_cards->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Logo Iniciativa en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa en inlgés', 'cmb2' ),
		'id'      => 'logo_iniciativa_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 14,
		),
	) );

	$reforestamos_hacemos_contenido_cards->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Descripción de la iniciativa en inglés (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_iniciativa_modal_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );	


}

/** Iniciativa (Incidencia en políticas públicas)  */
add_action( 'cmb2_admin_init', 'reforestamos_hacemos_incidencia' );
function reforestamos_hacemos_incidencia() {
    $prefix = 'reforestamos_group_';

	$reforestamos_hacemos_contenido_incidencia = new_cmb2_box( array(
		'id'           => $prefix . 'que_hacemos_incidencia',
		'title'        => esc_html__( 'Incidencia en políticas públicas', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-que-hacemos.php' 
        )
	) );

	$reforestamos_hacemos_contenido_incidencia->add_field( array(
		'name' => esc_html__( 'Titulo principal de la sección', 'cmb2' ),
		'id'   => 'titulo_principal_incidencia',
		'type' => 'text',
	) );

	/** Titulo principal en inlgés **/
	$reforestamos_hacemos_contenido_incidencia->add_field( array(
		'name' => esc_html__( '(EN) Titulo principal de la sección en inglés', 'cmb2' ),
		'id'   => 'titulo_principal_incidencia_en',
		'type' => 'text',
	) );	


	$group_field_id = $reforestamos_hacemos_contenido_incidencia->add_field( array(
		'id'          => $prefix . 'que_hacemos_cards_incidencia',
		'type'        => 'group',
		'description' => esc_html__( 'Incidencia en políticas públicas (Iniciativas)', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Iniciativa {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_hacemos_contenido_incidencia->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Nombre de la iniciativa', 'cmb2' ),
		'id'   => 'nombre_incidencia',
		'type' => 'textarea',
	) );


	$reforestamos_hacemos_contenido_incidencia->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Logo Iniciativa', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'logo_inicidencia',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );


	$reforestamos_hacemos_contenido_incidencia->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Descripción de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_incidencia_modal',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

	$reforestamos_hacemos_contenido_incidencia->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link en el botón "Saber Más" de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'id'   => 'link_incidencia',
		'type' => 'text',
	) );

	$reforestamos_hacemos_contenido_incidencia->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Opción para mostrar botón "Saber más"', 'cmb2' ),
		'id'      => 'boton_incidencia',
		'type'    => 'radio',
		'options' => array(
			'd-block' => esc_html__( 'Mostrar botón', 'cmb2' ),
			'd-none' => esc_html__( 'Ocultar botón', 'cmb2' ),
		),

	) );

	/** Contenido en inglés */
	$reforestamos_hacemos_contenido_incidencia->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) Nombre de la iniciativa en inglés', 'cmb2' ),
		'id'   => 'nombre_incidencia_en',
		'type' => 'textarea',
	) );


	$reforestamos_hacemos_contenido_incidencia->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Logo Iniciativa en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'logo_inicidencia_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 14,
		),
	) );


	$reforestamos_hacemos_contenido_incidencia->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Descripción de la iniciativa en inglés (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_incidencia_modal_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

}

/** Iniciativa (Comunidades de emprendimiento)  */
add_action( 'cmb2_admin_init', 'reforestamos_hacemos_comunidades' );
function reforestamos_hacemos_comunidades() {
    $prefix = 'reforestamos_group_';

	$reforestamos_hacemos_contenido_comunidades = new_cmb2_box( array(
		'id'           => $prefix . 'que_hacemos_comunidades',
		'title'        => esc_html__( 'Comunidades de emprendimiento', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-que-hacemos.php' 
        )
	) );

	$reforestamos_hacemos_contenido_comunidades->add_field( array(
		'name' => esc_html__( 'Titulo principal de la sección', 'cmb2' ),
		'id'   => 'titulo_principal_comunidades',
		'type' => 'text',
	) );
	
	/** Titulo principal en inglés */
	$reforestamos_hacemos_contenido_comunidades->add_field( array(
		'name' => esc_html__( '(EN) Titulo principal de la sección en iglés', 'cmb2' ),
		'id'   => 'titulo_principal_comunidades_en',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_hacemos_contenido_comunidades->add_field( array(
		'id'          => $prefix . 'que_hacemos_cards_comunidades',
		'type'        => 'group',
		'description' => esc_html__( 'Comunidades de emprendimiento (Iniciativas)', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Iniciativa {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_hacemos_contenido_comunidades->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Nombre de la iniciativa', 'cmb2' ),
		'id'   => 'titulo_comunidad',
		'type' => 'textarea',
	) );


	$reforestamos_hacemos_contenido_comunidades->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Logo Iniciativa', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'logo_comunidad',
		'type'    => 'wysiwyg',
	) );

	$reforestamos_hacemos_contenido_comunidades->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Descripción de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_comunidad_modal',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

	$reforestamos_hacemos_contenido_comunidades->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link en el botón "Saber Más" de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'id'   => 'link_comunidad',
		'type' => 'text',
	) );

	$reforestamos_hacemos_contenido_comunidades->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Opción para mostrar botón "Saber más"', 'cmb2' ),
		'id'      => 'boton_comunidades',
		'type'    => 'radio',
		'options' => array(
			'd-block' => esc_html__( 'Mostrar botón', 'cmb2' ),
			'd-none' => esc_html__( 'Ocultar botón', 'cmb2' ),
		),
	) );

	/** Contenido en inglés */
	$reforestamos_hacemos_contenido_comunidades->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) Nombre de la iniciativa en inglés', 'cmb2' ),
		'id'   => 'titulo_comunidad_en',
		'type' => 'text',
	) );


	$reforestamos_hacemos_contenido_comunidades->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Logo Iniciativa en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'logo_comunidad_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 14,
		),
	) );

	$reforestamos_hacemos_contenido_comunidades->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Descripción de la iniciativa en inglés (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_comunidad_modal_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

}

/**Iniciativa (Compromisos del sector privado) */
add_action( 'cmb2_admin_init', 'reforestamos_hacemos_privado' );
function reforestamos_hacemos_privado() {
    $prefix = 'reforestamos_group_';

	$reforestamos_hacemos_contenido_privado = new_cmb2_box( array(
		'id'           => $prefix . 'que_hacemos_privado',
		'title'        => esc_html__( 'Compromisos del sector privado', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-que-hacemos.php' 
        )
	) );

	$reforestamos_hacemos_contenido_privado->add_field( array(
		'name' => esc_html__( 'Titulo principal de la sección', 'cmb2' ),
		'id'   => 'titulo_principal_privado',
		'type' => 'text',
	) );

	/** Titulo principal en inglés */
	$reforestamos_hacemos_contenido_privado->add_field( array(
		'name' => esc_html__( '(EN) Titulo principal de la sección en inglés', 'cmb2' ),
		'id'   => 'titulo_principal_privado_en',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_hacemos_contenido_privado->add_field( array(
		'id'          => $prefix . 'que_hacemos_cards_privado',
		'type'        => 'group',
		'description' => esc_html__( 'Compromisos del sector privado (Iniciativas)', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Iniciativa {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_hacemos_contenido_privado->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Nombre de la iniciativa', 'cmb2' ),
		'id'   => 'titulo_privado',
		'type' => 'textarea',
	) );


	$reforestamos_hacemos_contenido_privado->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Logo Iniciativa', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'logo_privado',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );


	$reforestamos_hacemos_contenido_privado->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Descripción de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_privado_modal',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

	$reforestamos_hacemos_contenido_privado->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link en el botón "Saber Más" de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'id'   => 'link_privado',
		'type' => 'text',
	) );

	$reforestamos_hacemos_contenido_privado->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Opción para mostrar botón "Saber más"', 'cmb2' ),
		'id'      => 'boton_privado',
		'type'    => 'radio',
		'options' => array(
			'd-block' => esc_html__( 'Mostrar botón', 'cmb2' ),
			'd-none' => esc_html__( 'Ocultar botón', 'cmb2' ),
		),
	) );

	/** Contenido de la inciativa en inglés */
	$reforestamos_hacemos_contenido_privado->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) Nombre de la iniciativa en inglés', 'cmb2' ),
		'id'   => 'titulo_privado_en',
		'type' => 'text',
	) );


	$reforestamos_hacemos_contenido_privado->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Logo Iniciativa en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'logo_privado_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 14,
		),
	) );


	$reforestamos_hacemos_contenido_privado->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Descripción de la iniciativa en inglés (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_privado_modal_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

}

/**Iniciativa (Campañas de empoderamiento ciudadano) */
add_action( 'cmb2_admin_init', 'reforestamos_hacemos_ciudadano' );
function reforestamos_hacemos_ciudadano() {
    $prefix = 'reforestamos_group_';

	$reforestamos_hacemos_contenido_ciudadano = new_cmb2_box( array(
		'id'           => $prefix . 'que_hacemos_ciudadano',
		'title'        => esc_html__( 'Campañas de empoderamiento ciudadano', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-que-hacemos.php' 
        )
	) );

	$reforestamos_hacemos_contenido_ciudadano->add_field( array(
		'name' => esc_html__( 'Titulo principal de la sección', 'cmb2' ),
		'id'   => 'titulo_principal_ciudadano',
		'type' => 'text',
	) );

	/** Titulo principal en inglés */
	$reforestamos_hacemos_contenido_ciudadano->add_field( array(
		'name' => esc_html__( '(EN)Titulo principal de la sección en inglés', 'cmb2' ),
		'id'   => 'titulo_principal_ciudadano_en',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_hacemos_contenido_ciudadano->add_field( array(
		'id'          => $prefix . 'que_hacemos_cards_ciudadano',
		'type'        => 'group',
		'description' => esc_html__( 'Campañas de empoderamiento ciudadano (campañas)', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Campaña {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_hacemos_contenido_ciudadano->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Nombre de la iniciativa', 'cmb2' ),
		'id'   => 'titulo_ciudadano',
		'type' => 'textarea',
	) );


	$reforestamos_hacemos_contenido_ciudadano->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Logo Iniciativa', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'logo_ciudadano',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

	$reforestamos_hacemos_contenido_ciudadano->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Descripción de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_ciudadano_modal',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );

	/** Links y botones del modal */
	$reforestamos_hacemos_contenido_ciudadano->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link en el botón "Saber Más" de la iniciativa (Modal - Pop Up)', 'cmb2' ),
		'id'   => 'link_ciudadano',
		'type' => 'text',
	) );

	$reforestamos_hacemos_contenido_ciudadano->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Opción para mostrar botón "Saber más"', 'cmb2' ),
		'id'      => 'boton_ciudadano',
		'type'    => 'radio',
		'options' => array(
			'd-block' => esc_html__( 'Mostrar botón', 'cmb2' ),
			'd-none' => esc_html__( 'Ocultar botón', 'cmb2' ),
		),
	) );

	/** Contenido en inglés */
	$reforestamos_hacemos_contenido_ciudadano->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN)Nombre de la iniciativa en inglés', 'cmb2' ),
		'id'   => 'titulo_ciudadano_en',
		'type' => 'text',
	) );


	$reforestamos_hacemos_contenido_ciudadano->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN)Logo Iniciativa en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'logo_ciudadano_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 14,
		),
	) );

	$reforestamos_hacemos_contenido_ciudadano->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN)Descripción de la iniciativa en inglés (Modal - Pop Up)', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el Logo de la iniciativa', 'cmb2' ),
		'id'      => 'desc_ciudadano_modal_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 18,
		),
	) );	


}

/** Pop up (Ventana Modal) */


 /**
 * Eventos
 */



/** Scripts */
function reforestamos_metabox_js() {
    ?>
	<style>
		.cmb-td .titulo-desc {
			background-color: #e9e9e9;
			border: 1px solid #c3c4c7;
			margin: 10px 15px!important;
		}
		.titulo-desc {
			text-align: center;
			font-size: 16px!important;
			color: #1d2327;
			font-weight: 600!important;
		}
		.contenido-desc {
			text-align: center;
		}
	</style>
    <?php
}
add_action( 'admin_head', 'reforestamos_metabox_js' );


 /**
* Organizaciones de la sociedad civil --- Gobierno 
*/
/* Logo Organizaciones de la sociedad civil - Gobierno */
add_action( 'cmb2_admin_init', 'reforestamos_aliados_logos' );
function reforestamos_aliados_logos() {
    $prefix = 'reforestamos_group_';

	$reforestamos_logos_aliados = new_cmb2_box( array(
		'id'           => $prefix . 'logos_aliados_',
		'title'        => esc_html__( 'Logos aliados ', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-aliados.php' 
        )
	) );

	$reforestamos_logos_aliados->add_field( array(
		'name' => esc_html__( 'Titulo Sección', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el titulo si tiene', 'cmb2' ),
		'id'   => 'titulo_principal',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_logos_aliados->add_field( array(
		'id'          => $prefix . 'seccion_aliados',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega el logo del aliado (Organización de la sociedad civil -- Gobierno)', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Logo {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_logos_aliados->add_group_field( $group_field_id, array(
		'name'         => esc_html__( 'Ingresa el icono de la sección ', 'cmb2' ),
		'id'   		   => 'imagen_logo',
		'type'         => 'wysiwyg',
		'options'      => array(
			'textarea_rows'   => 15,
		),
	) );

}

 /**
 * Documentos General
 */
/** Documentos Template Inicio */
add_action( 'cmb2_admin_init', 'reforestamos_documentos' );
function reforestamos_documentos() {
    $prefix = 'reforestamos_group_';

	$reforestamos_documentos_principal = new_cmb2_box( array(
		'id'           => $prefix . 'documentos',
		'title'        => esc_html__( 'Sección Documentos ', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-documentos.php' 
        )
	) );

	$group_field_id = $reforestamos_documentos_principal->add_field( array(
		'id'          => $prefix . 'documento',
		'type'        => 'group',
		'description' => esc_html__( 'Agrega sección documento', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Sección documento {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			// 'closed'      => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_documentos_principal->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Ingresa el icono de la sección ', 'cmb2' ),
		'id'   => 'imagen_logo',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );


	$reforestamos_documentos_principal->add_group_field( $group_field_id, array(
		'name'    => esc_html__( 'Titulo documento', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el titulo del documento ', 'cmb2' ),
		'id'      => 'link_logo',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	// Contenido en inglés
	$reforestamos_documentos_principal->add_group_field( $group_field_id, array(
		'name'    => esc_html__( '(EN) Titulo documento en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Agrega el titulo del documento en inglés', 'cmb2' ),
		'id'      => 'link_logo_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );


}

/**
 * Documentos (Informes Anuales - Documentos de interés - Infográfias - Recursos)
 */
add_action( 'cmb2_admin_init', 'reforestamos_documentos_btn_titulo' );
function reforestamos_documentos_btn_titulo() {
    $prefix = 'reforestamos_group_';

	$reforestamos_documentos_principal = new_cmb2_box( array(
		'id'           => $prefix . 'documentos_btn',
		'title'        => esc_html__( 'Información Documentos', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-informes-anuales.php', 
        )
	) );

	$group_field_id = $reforestamos_documentos_principal->add_field( array(
		'id'          => $prefix . 'documento_btn_',
		'type'        => 'group',
		'description' => esc_html__( 'Titulo Documentos', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Información Documento {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_documentos_principal->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo sección/documento', 'cmb2' ),
		'id'   => 'anio_titulo_documento',
		'type' => 'text',
	) );

	$reforestamos_documentos_principal->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Documento', 'cmb2' ),
		'id'   => 'link_documento',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 100,
		),
	) );

	/** Contenido en inglés */
	$reforestamos_documentos_principal->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) Titulo sección/documento', 'cmb2' ),
		'id'   => 'anio_titulo_documento_en',
		'type' => 'text',
	) );

	$reforestamos_documentos_principal->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Documento', 'cmb2' ),
		'id'   => 'link_documento_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 100,
		),
	) );
}


/**
 * Infografías
**/
add_action( 'cmb2_admin_init', 'reforestamos_infografias' );
function reforestamos_infografias() {
    $prefix = 'reforestamos_group_';

	$reforestamos_infografias = new_cmb2_box( array(
		'id'           => $prefix . 'infografias',
		'title'        => esc_html__( 'Documentos infografías', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-infografias.php', 
        )
	) );

	$group_field_id = $reforestamos_infografias->add_field( array(
		'id'          => $prefix . 'infografias_section_',
		'type'        => 'group',
		'description' => esc_html__( 'Información infografías', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Información infografía {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_infografias->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Nombre de la especie', 'cmb2' ),
		'id'   => 'nombre_especie',
		'type' => 'text',
	) );

	$reforestamos_infografias->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Infografía', 'cmb2' ),
		'id'   => 'url_imagen',
		'type'    => 'file',
	) );

	$reforestamos_infografias->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Categoría', 'cmb2' ),
		'id'   => 'categoria',
		'type' => 'select', 
		'options' => array(
			'arboles' => 'Árboles',
			'fauna' => 'Fauna',
			'agua' => 'Agua',
			'ecosistemas' => 'Ecosistemas',
			'ciudades-Arbolado Urbano' => 'Ciudades/ Arbolado Urbano',
			'incendios-forestales' => 'Incendios Forestales',
			'gobernanza-territorial' => 'Gobernanza Territorial'
		),
	) );




	/** Contenido en inglés */
	$reforestamos_infografias->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) Nombre de la especie', 'cmb2' ),
		'id'   => 'nombre_especie_en',
		'type' => 'text',
	) );

}


/** Nuestras Notas */
/** Siderbar content */
add_action( 'cmb2_admin_init', 'reforestamos_nuestras_notas_sidebar' );
function reforestamos_nuestras_notas_sidebar() {
    $prefix = 'reforestamos_group_';

	$reforestamos_siderbar_rrss = new_cmb2_box( array(
		'id'           => $prefix . 'nuestras_notas',
		'title'        => esc_html__( 'Sidebar nuestras notas', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'sidebar.php', 
        )
	));

	/* Sidebar*/
	$group_field_id = $reforestamos_siderbar_rrss->add_field( array(
		'id'          => $prefix . 'sibedar_rrss',
		'type'        => 'group',
		'description' => esc_html__( 'Redes Sociales', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Red Social {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	));

	$reforestamos_siderbar_rrss->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo Red Social', 'cmb2' ),
		'id'   => 'titulo_rs',
		'type' => 'text',
	));

	$reforestamos_siderbar_rrss->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link feed', 'cmb2' ),
		'id'   => 'link_feed',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	));
}


/* Contacto */
/** Pagina Contacto*/
add_action( 'cmb2_admin_init', 'reforestamos_contacto' );
function reforestamos_contacto() {
    $prefix = 'reforestamos_group_';

	$reforestamos_contacto = new_cmb2_box( array(
		'id'           => $prefix . 'contacto_',
		'title'        => esc_html__( 'Sección de Contacto', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-contacto.php', 
        )
	));

	/** Imagen Formulario */
	$reforestamos_contacto->add_field( array(
		'name' => esc_html__( 'Term Image', 'cmb2' ),
		'desc' => esc_html__( 'Imagen Formulario', 'cmb2' ),
		'id'   => 'imagen_formulario',
		'type' => 'file',
	));

	/* Formulario de Contacto */
	$reforestamos_contacto->add_field( array(
		'name'    => esc_html__( 'Campo Formulario', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el ShortCode del Formulario, está ubicado en menú lateral con el nombre de "Contacto" ', 'cmb2' ),
		'id'      => 'campo_formulario',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/* Ubicaciones Reforestamos (CDMX - Guadalajara) */
	$group_field_id = $reforestamos_contacto->add_field( array(
		'id'          => $prefix . 'seccion_contacto_ubicacion',
		'type'        => 'group',
		'description' => esc_html__( 'Ubicación Oficinas Reforestamos', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Oficina {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	));
	$reforestamos_contacto->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo de la oficina', 'cmb2' ),
		'id'   => 'titulo_ubicacion',
		'type' => 'text',
	));

	/** contenido en inglés */
	$reforestamos_contacto->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) Titulo de la oficina en inglés', 'cmb2' ),
		'desc' => esc_html__('Agrega el titulo en inglés', 'cmb2'),
		'id'   => 'titulo_ubicacion_en',
		'type' => 'text',
	));

	$reforestamos_contacto->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Ubicación Oficina', 'cmb2' ),
		'id'   => 'ubicacion_oficina',
		'type' => 'textarea',
	));

	$reforestamos_contacto->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Teléfono Oficina', 'cmb2' ),
		'desc' => esc_html__('Ingresa el Teléfono de la oficina', 'cmb2'),
		'id'   => 'tel_oficina',
		'type' => 'text',
	));

	$reforestamos_contacto->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Correo Institucional', 'cmb2' ),
		'desc' => esc_html__('Ingresa el Correo Institucinal', 'cmb2'),
		'id'   => 'mail_oficina',
		'type'    => 'text',
	));

	$reforestamos_contacto->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Archivo - Link Ubicación', 'cmb2' ),
		'desc' => esc_html__('Ingresa la imagen de la úbicación de la Oficina', 'cmb2'),
		'id'   => 'imagen_oficina',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 100,
		),
	));

	$reforestamos_contacto->add_group_field( $group_field_id, array(
		'name' => esc_html__('Enlace dirección', 'cmb2' ),
		'desc' => esc_html__('Ingresa el enlace de la dirección', 'cmb2'),
		'id'   => 'link_dicreccion_oficina',
		'type'    => 'text',
	));

}


/**
 * Incendios Forestales 
 */
/* Sección color menú */
add_action( 'cmb2_admin_init', 'reforestamos_incendios_menu' );
function reforestamos_incendios_menu() {
    $prefix = 'reforestamos_group_';

	$reforestamos_incendios_color_menu = new_cmb2_box( array(
		'id'           => $prefix . 'incendios_menu_',
		'title'        => esc_html__( 'Color Menú Incendios Forestales ', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-incendios-forestales.php', 
        )
	));

	/**  Color Menú   */
	$reforestamos_incendios_color_menu->add_field(  array(
		'name'             => esc_html__( 'Color del Menú llamados a la acción', 'cmb2' ),
		'id'               => 'back_incendios',
		'type'             => 'radio_inline',
		'options'          => array(
			'bg-danger' => esc_html__( 'Color Rojo', 'cmb2' ),
			'bg-light'   => esc_html__( 'Color Verde', 'cmb2' ),
		),
	) );
}
/* Sección Video Incendios Forestales */
add_action( 'cmb2_admin_init', 'reforestamos_incendios_forestales_video' );
function reforestamos_incendios_forestales_video() {
    $prefix = 'reforestamos_group_';

	$reforestamos_incendios_inicio = new_cmb2_box( array(
		'id'           => $prefix . 'incendios_inicio',
		'title'        => esc_html__( 'Inicio - Video Incendios Forestales ', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-incendios-forestales.php', 
        )
	));

	/** Titulo Video Incendios Forestales  */
	$reforestamos_incendios_inicio->add_field( array(
		'name'    => esc_html__( 'Titulo Incendios Forestales', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el titulo de la sección" ', 'cmb2' ),
		'id'      => 'titulo_video',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));
	
	/* Texto Incendios 1 */
	$reforestamos_incendios_inicio->add_field( array(
		'name' => esc_html__( 'Texto 1 video incendios forestales', 'cmb2' ),
		'desc' => esc_html__( 'Ingresa el primer texto del video', 'cmb2' ),
		'id'   => 'texto_1_incendios',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** Texto Incedios 2 */
	$reforestamos_incendios_inicio->add_field( array(
		'name'    => esc_html__( 'Texto 2 video incendios forestales', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el segundo texto del video ', 'cmb2' ),
		'id'      => 'texto_2_incendios',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/* Video Incendios */
	$reforestamos_incendios_inicio->add_field( array(
		'name' => esc_html__( 'Video incendios forestales', 'cmb2' ),
		'desc' => esc_html__( 'Ingresa el video', 'cmb2' ),
		'id'   => 'video_incendios',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));


	/** 
	** Contenido en inglés 
	**/
	/** Titulo Video Incendios Forestales  */
	$reforestamos_incendios_inicio->add_field( array(
		'name'    => esc_html__( '(EN) - Titulo incendios forestales', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el titulo en inglés ', 'cmb2' ),
		'id'      => 'titulo_video_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));
	
	/* Texto Incendios 1 */
	$reforestamos_incendios_inicio->add_field( array(
		'name' => esc_html__( '(EN) - Texto 1 video incendios forestales en inglés', 'cmb2' ),
		'desc' => esc_html__( 'Ingresa el primer texto en inglés', 'cmb2' ),
		'id'   => 'texto_1_incendios_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** Texto Incedios 2 */
	$reforestamos_incendios_inicio->add_field( array(
		'name'    => esc_html__( '(EN) - Texto 2 video incendios forestales en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el segundo texto en inglés', 'cmb2' ),
		'id'      => 'texto_2_incendios_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));	

}

/** Sección Donar Incendios Forestales */
add_action( 'cmb2_admin_init', 'reforestamos_incendios_forestales_donar' );
function reforestamos_incendios_forestales_donar() {
    $prefix = 'reforestamos_group_';

	$reforestamos_incendios_donar = new_cmb2_box( array(
		'id'           => $prefix . 'incendios_donar_',
		'title'        => esc_html__( 'Sección Donar', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-incendios-forestales.php', 
        )
	));

	/** Titulo Donar  */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( 'Titulo sección donar', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el titulo de la sección donar', 'cmb2' ),
		'id'      => 'titulo_donar',
		'type'    => 'textarea',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/* Intro Donar  */
	$reforestamos_incendios_donar->add_field( array(
		'name' => esc_html__( 'Texto sección donar', 'cmb2' ),
		'desc' => esc_html__( 'Ingresa el texto de la sección donar', 'cmb2' ),
		'id'   => 'texto_donar',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** Logo paypal */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( 'Imagen Paypal', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa la imagen de Paypal ', 'cmb2' ),
		'id'      => 'imagen_paypal',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** Imangen Donar */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( 'Imagen sección donar', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa la imagen de Donar ', 'cmb2' ),
		'id'      => 'imagen_donar',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** Titulo Datos Bancarios */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( 'Titulo datos bancarios', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el titulo de la sección ', 'cmb2' ),
		'id'      => 'titulo_datos_bancarios',
		'type'    => 'textarea',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** Datos Beneficiario  */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( 'Datos beneficiario', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa los datos del beneficiario" ', 'cmb2' ),
		'id'      => 'datos_beneficiario',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** Texto de cierre (Footer) */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( 'Texto cierre (Footer)', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el texto de cierre" ', 'cmb2' ),
		'id'      => 'texto_cierre',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** 
	** Contenido en inglés 
	**/
	/** Titulo Donar  */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( '(EN) - Titulo donar en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el titulo en inglés', 'cmb2' ),
		'id'      => 'titulo_donar_en',
		'type'    => 'textarea',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/* Intro Donar  */
	$reforestamos_incendios_donar->add_field( array(
		'name' => esc_html__( '(EN) - Texto de la sección en inlgés', 'cmb2' ),
		'desc' => esc_html__( 'Ingresa el texto de la sección donar en inglés', 'cmb2' ),
		'id'   => 'texto_donar_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));	

	/** Titulo Datos Bancarios */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( '(EN) - Titulo datos bancarios', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el titulo de la sección en inglés', 'cmb2' ),
		'id'      => 'titulo_datos_bancarios_en',
		'type'    => 'textarea',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** Datos Beneficiario  */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( '(EN) - Datos beneficiario en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa los datos del beneficiarioen inglés', 'cmb2' ),
		'id'      => 'datos_beneficiario_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

	/** Texto de cierre (Footer) */
	$reforestamos_incendios_donar->add_field( array(
		'name'    => esc_html__( '(EN) - Texto cierre (Footer) en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el texto de cierre en inglés" ', 'cmb2' ),
		'id'      => 'texto_cierre_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	));

}

/** Infografía 1 Incendios Forestales */
add_action( 'cmb2_admin_init', 'reforestamos_incendios_forestales_infografia_1' );
function reforestamos_incendios_forestales_infografia_1() {
    $prefix = 'reforestamos_group_';

	$reforestamos_incendios_infografia_1 = new_cmb2_box( array(
		'id'           => $prefix . 'incendios_infografia_1',
		'title'        => esc_html__( 'Infografía 1 Incendios Forestales', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-incendios-forestales.php', 
        )
	) );

	/** Titulo Infografia */
	$reforestamos_incendios_infografia_1->add_field( array(
		'name' => esc_html__( 'Titulo infografía 1', 'cmb2' ),
		'id'   => 'titulo_infografia_1',
		'type' => 'textarea',
	) );

	/** Titulo en inglés */
	$reforestamos_incendios_infografia_1->add_field( array(
		'name' => esc_html__( '(EN) - Titulo infografía 1 en inglés', 'cmb2' ),
		'id'   => 'titulo_infografia_1_en',
		'type' => 'textarea',
	) );

	$group_field_id = $reforestamos_incendios_infografia_1->add_field( array(
		'id'          => $prefix . 'incendios_infografia_1',
		'type'        => 'group',
		'description' => esc_html__( 'Datos Infografía', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Sección Infográfia {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	/** Imagen Infografía */
	$reforestamos_incendios_infografia_1->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen Infografía', 'cmb2' ),
		'id'   => 'imagen_infografia',
		'type' => 'file',
	) );

	$reforestamos_incendios_infografia_1->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Texto alternativo imagen', 'cmb2' ),
		'id'   => 'alt_text',
		'type' => 'text',
	) );

	/** Texto infografía */
	$reforestamos_incendios_infografia_1->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Descripción de la infografía', 'cmb2' ),
		'desc' => esc_html__('Ingresa la descripción del icono de la infografía', 'cmb2'),
		'id'   => 'desc_infografia',
		'type' => 'textarea',
	) );

	/** Posición */
	$reforestamos_incendios_infografia_1->add_group_field( $group_field_id, array(
		'name'             => esc_html__( 'Posición campo infgrafía', 'cmb2' ),
		'desc'             => esc_html__( 'Selecciona la posición del campo en la infografía', 'cmb2' ),
		'id'               => 'posicion_campo_infografia',
		'type'             => 'radio_inline',
		'options'          => array(
			'derecha' => esc_html__( 'Derecha', 'cmb2' ),
			'izquierda'   => esc_html__( 'Izquierda', 'cmb2' ),
		),
	) );

	/** 
	** Contenido en inglés
	**/
	$reforestamos_incendios_infografia_1->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Texto alternativo imagen en inglés', 'cmb2' ),
		'id'   => 'alt_text_en',
		'type' => 'text',
	) );

	/** Texto infografía */
	$reforestamos_incendios_infografia_1->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Descripción del ícono en inglés', 'cmb2' ),
		'desc' => esc_html__('Ingresa la descripción del icono del ícono en inglés', 'cmb2'),
		'id'   => 'desc_infografia_en',
		'type' => 'textarea',
	) );	
}

/** Infografía 2 Incendios Forestales */
add_action( 'cmb2_admin_init', 'reforestamos_incendios_forestales_infografia_2' );
function reforestamos_incendios_forestales_infografia_2() {
    $prefix = 'reforestamos_group_';

	$reforestamos_incendios_infografia_2 = new_cmb2_box( array(
		'id'           => $prefix . 'incendios_infografia_2',
		'title'        => esc_html__( 'Infografía 2 Incendios Forestales', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-incendios-forestales.php', 
        )
	) );

	/** Titulo Infografia */
	$reforestamos_incendios_infografia_2->add_field( array(
		'name' => esc_html__( 'Titulo infografía 2', 'cmb2' ),
		'id'   => 'titulo_infografia_2',
		'type' => 'textarea',
	) );

	/** Titulo en inglés */
	$reforestamos_incendios_infografia_2->add_field( array(
		'name' => esc_html__( '(EN) - Titulo infografía 2 en inglés', 'cmb2' ),
		'id'   => 'titulo_infografia_2_en',
		'type' => 'textarea',
	) );

	$group_field_id = $reforestamos_incendios_infografia_2->add_field( array(
		'id'          => $prefix . 'incendios_infografia_2',
		'type'        => 'group',
		'description' => esc_html__( 'Datos Infografía', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Sección Infográfia {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	/** Imagen Infografía */
	$reforestamos_incendios_infografia_2->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen Infografía', 'cmb2' ),
		'id'   => 'imagen_infografia',
		'type' => 'file',
	) );

	$reforestamos_incendios_infografia_2->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Texto alternativo imagen', 'cmb2' ),
		'id'   => 'alt_text',
		'type' => 'text',
	) );

	/** Texto infografía */
	$reforestamos_incendios_infografia_2->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Descripción de la infografía', 'cmb2' ),
		'desc' => esc_html__('Ingresa la descripción del icono de la infografía', 'cmb2'),
		'id'   => 'desc_infografia',
		'type' => 'textarea',
	) );

	/** Posición */
	$reforestamos_incendios_infografia_2->add_group_field( $group_field_id, array(
		'name'             => esc_html__( 'Posición campo infgrafía', 'cmb2' ),
		'desc'             => esc_html__( 'Selecciona la posición del campo en la infografía', 'cmb2' ),
		'id'               => 'posicion_campo_infografia',
		'type'             => 'radio_inline',
		'options'          => array(
			'derecha' => esc_html__( 'Derecha', 'cmb2' ),
			'izquierda'   => esc_html__( 'Izquierda', 'cmb2' ),
		),
	) );

	/** Texto Footer  */
	$reforestamos_incendios_infografia_2->add_field( array(
		'name' => esc_html__( 'Texto footer infografía 2', 'cmb2' ),
		'id'   => 'text_footer',
		'type' => 'textarea',
	) );

	/** 
	** Contenido en inglés 
	**/
	$reforestamos_incendios_infografia_2->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Texto alternativo imagen en inglés', 'cmb2' ),
		'id'   => 'alt_text_en',
		'type' => 'text',
	) );

	/** Texto infografía */
	$reforestamos_incendios_infografia_2->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Descripción de la infografía en inglés', 'cmb2' ),
		'desc' => esc_html__('Ingresa la descripción del icono en inglés', 'cmb2'),
		'id'   => 'desc_infografia_en',
		'type' => 'textarea',
	) );

	$reforestamos_incendios_infografia_2->add_field( array(
		'name' => esc_html__( '(EN) - Texto footer infografía 2 en inglés', 'cmb2' ),
		'id'   => 'text_footer_en',
		'type' => 'textarea',
	) );

}

/* Sección Acciones Reforestamos México */
add_action( 'cmb2_admin_init', 'reforestamos_incendios_forestales_acciones' );
function reforestamos_incendios_forestales_acciones() {
    $prefix = 'reforestamos_group_';

	$reforestamos_incendios_acciones = new_cmb2_box( array(
		'id'           => $prefix . 'incendios_acciones',
		'title'        => esc_html__( 'Sección Acciones Reforestamos', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-incendios-forestales.php', 
        )
	));

	/** Titulo Aciones Reforestamos México  */
	$reforestamos_incendios_acciones->add_field( array(
		'name'    => esc_html__( 'Titulo acciones reforestamos méxico', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el titulo de las acciones ', 'cmb2' ),
		'id'      => 'titulo_acciones',
		'type'    => 'textarea',
		'options' => array(
			'textarea_rows' => 7,
		),
	));
	
	/* Texto Incendios 1 */
	$reforestamos_incendios_acciones->add_field( array(
		'name' => esc_html__( 'Lista acciones reforestamos méxico', 'cmb2' ),
		'desc' => esc_html__( 'Ingresa las acciones de reforestamos méxico', 'cmb2' ),
		'id'   => 'lista_acciones',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	));

	/**
	** Contenido en inglés
	**/
	/** Titulo Aciones Reforestamos México  */
	$reforestamos_incendios_acciones->add_field( array(
		'name'    => esc_html__( '(EN) - Titulo de acciones de reforestamos en inglés', 'cmb2' ),
		'desc'    => esc_html__( 'Ingresa el titulo de las acciones de reforestamos en inglés ', 'cmb2' ),
		'id'      => 'titulo_acciones_en',
		'type'    => 'textarea',
		'options' => array(
			'textarea_rows' => 7,
		),
	));
	
	/* Texto Incendios 1 */
	$reforestamos_incendios_acciones->add_field( array(
		'name' => esc_html__( '(EN) - Lista acciones reforestamos méxico en inglés', 'cmb2' ),
		'desc' => esc_html__( 'Ingresa las acciones de reforestamos méxico en inglés', 'cmb2' ),
		'id'   => 'lista_acciones_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	));
}

/** Sección que se necesita */
add_action( 'cmb2_admin_init', 'reforestamos_incendios_forestales_necesidades' );
function reforestamos_incendios_forestales_necesidades() {
    $prefix = 'reforestamos_group_';

	$reforestamos_incendios_necesidades = new_cmb2_box( array(
		'id'           => $prefix . 'incendios_necesidades',
		'title'        => esc_html__( 'Sección ¿Qué se necesita?', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-incendios-forestales.php', 
        )
	) );

	/** Titulo Necesidades */
	$reforestamos_incendios_necesidades->add_field( array(
		'name' => esc_html__( 'Titulo ¿Qué se necesita?', 'cmb2' ),
		'id'   => 'titulo_necesidades',
		'type' => 'textarea',
	) );

	/** Titulo en inglés */
	$reforestamos_incendios_necesidades->add_field( array(
		'name' => esc_html__( '(EN) - Titulo ¿Qué se necesita? en inglés', 'cmb2' ),
		'id'   => 'titulo_necesidades_en',
		'type' => 'textarea',
	) );	

	$group_field_id = $reforestamos_incendios_necesidades->add_field( array(
		'id'          => $prefix . 'incendios_necesidades',
		'type'        => 'group',
		'description' => esc_html__( 'Datos Infografía', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Card necesidades {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	/** Imagen card necesidades */
	$reforestamos_incendios_necesidades->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen card', 'cmb2' ),
		'id'   => 'imagen_card',
		'type' => 'file',
	) );

	$reforestamos_incendios_necesidades->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo card (necesidades)', 'cmb2' ),
		'id'   => 'titulo_card',
		'type' => 'textarea',
	) );

	/** Texto card necesidades */
	$reforestamos_incendios_necesidades->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Lista card (necesidades)', 'cmb2' ),
		'desc' => esc_html__('Ingresa la lista de necesidades', 'cmb2'),
		'id'   => 'lista_card',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	) );

	/**
	** Contenido en inglés
	**/
	// Titulo necesidades
	$reforestamos_incendios_necesidades->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Titulo card (necesidades) en inglés', 'cmb2' ),
		'id'   => 'titulo_card_en',
		'type' => 'textarea',
	) );

	/** Texto card necesidades */
	$reforestamos_incendios_necesidades->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Lista card (necesidades) en inlgés', 'cmb2' ),
		'desc' => esc_html__('Ingresa la lista de necesidades en inglés', 'cmb2'),
		'id'   => 'lista_card_en',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	) );	

}

/** Sección Incendios a nivel Nacional */
add_action( 'cmb2_admin_init', 'reforestamos_incendios_forestales_informacion' );
function reforestamos_incendios_forestales_informacion() {
    $prefix = 'reforestamos_group_';

	$reforestamos_incendios_informacion = new_cmb2_box( array(
		'id'           => $prefix . 'incendios_informacion',
		'title'        => esc_html__( 'Sección Incendios a nivel Nacional', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-incendios-forestales.php', 
        )
	) );

	/** Backgrond sección  */
	$reforestamos_incendios_informacion->add_field( array(
		'name' => esc_html__( 'Background Sección', 'cmb2' ),
		'id'   => 'background_incendios',
		'type' => 'file',
	) );


	$group_field_id = $reforestamos_incendios_informacion->add_field( array(
		'id'          => $prefix . 'incendios_informacion_diaria',
		'type'        => 'group',
		'description' => esc_html__( 'Sección tarjeta informativa de incendios', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Card tarjeta informativa {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	/** Titulo tarjeta informativa */
	$reforestamos_incendios_informacion->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo tarjeta informativa', 'cmb2' ),
		'id'   => 'titulo_tarjeta',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	) );

	/** Contenido tarjeta informativa */
	$reforestamos_incendios_informacion->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Información tarjeta informativa', 'cmb2' ),
		'id'   => 'info_tarjeta',
		'type' => 'textarea',
	) );

	/** Lista monitor tarjeta informativa */
	$reforestamos_incendios_informacion->add_field( array(
		'name' => esc_html__( 'Lista tarjeta informativa', 'cmb2' ),
		'id'   => 'tarjeta_informativa',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	) );

	/** Mapa incendios forestales nacionales */
	$reforestamos_incendios_informacion->add_field( array(
		'name' => esc_html__( 'Mapa tarjeta informativa', 'cmb2' ),
		'id'   => 'mapa_incendios',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	) );

	/**
	** Contenido en inglés
	**/
	/** Titulo tarjeta informativa en inglés*/
	$reforestamos_incendios_informacion->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Titulo tarjeta informativa en inglés', 'cmb2' ),
		'id'   => 'titulo_tarjeta_en',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	) );

	/** Contenido tarjeta informativa en inglés*/
	$reforestamos_incendios_informacion->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Información tarjeta informativa en inglés', 'cmb2' ),
		'id'   => 'info_tarjeta_en',
		'type' => 'textarea',
	) );

	/** Lista monitor tarjeta informativa en inglés*/
	$reforestamos_incendios_informacion->add_field( array(
		'name' => esc_html__( '(EN) - Lista tarjeta informativa en inglés', 'cmb2' ),
		'id'   => 'tarjeta_informativa_en',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 30,
		),
	) );		

}

/** Sección Aliados incendios forestales */
add_action( 'cmb2_admin_init', 'reforestamos_incendios_forestales_aliados' );
function reforestamos_incendios_forestales_aliados() {
    $prefix = 'reforestamos_group_';

	$reforestamos_incendios_aliados = new_cmb2_box( array(
		'id'           => $prefix . 'incendios_aliados',
		'title'        => esc_html__( 'Sección aliados', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-incendios-forestales.php', 
        )
	) );

	/** Titulo aliados */
	$reforestamos_incendios_aliados->add_field( array(
		'name' => esc_html__( 'Titulo aliados', 'cmb2' ),
		'id'   => 'titulo_aliados',
		'type' => 'textarea',
	) );
	
	/** Titulo en inglés **/
	$reforestamos_incendios_aliados->add_field( array(
		'name' => esc_html__( '(EN) - Titulo aliados en inglés', 'cmb2' ),
		'id'   => 'titulo_aliados_en',
		'type' => 'textarea',
	) );

	$group_field_id = $reforestamos_incendios_aliados->add_field( array(
		'id'          => $prefix . 'incendios_imagenes_aliados',
		'type'        => 'group',
		'description' => esc_html__( 'Imagenes aliados', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Aliado {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	/** Logo - imagen aliados  */
	$reforestamos_incendios_aliados->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Logo aliados', 'cmb2' ),
		'id'   => 'logo_aliados',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	/** Texto contacto footer  */
	$reforestamos_incendios_aliados->add_field( array(
		'name' => esc_html__( 'Texto footer aliados', 'cmb2' ),
		'id'   => 'texto_aliados_footer',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	/** Texto contacto footer en inglés  */
	$reforestamos_incendios_aliados->add_field( array(
		'name' => esc_html__( '(EN) - Texto footer aliados', 'cmb2' ),
		'id'   => 'texto_aliados_footer_en',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );


}


/**
** Adopta un Árbol !! 
**/
/** Dashboard principal */
add_action( 'cmb2_admin_init', 'reforestamos_adopta_arbol_principal' );
function reforestamos_adopta_arbol_principal() {
    $prefix = 'reforestamos_group_';

	$reforestamos_adopta_principal = new_cmb2_box( array(
		'id'           => $prefix . 'adopta_principal',
		'title'        => esc_html__( 'Sección principal adopta un árbol', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-adopta-arbol.php', 
        )
	) );

	$reforestamos_adopta_principal->add_field( array(
		'name'    => esc_html__( 'Información principal', 'cmb2' ),
		'id'      => 'info_principal',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	/** Titulo en inglés */
	$reforestamos_adopta_principal->add_field( array(
		'name'    => esc_html__( '(EN) - Información principal en inglés', 'cmb2' ),
		'id'      => 'info_principal_en',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	$group_field_id = $reforestamos_adopta_principal->add_field( array(
		'id'          => $prefix . 'adopta_info_principal',
		'type'        => 'group',
		'description' => esc_html__( 'Adopta un árbol', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Información {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	/* Texto  */
	$reforestamos_adopta_principal->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'texto de la sección', 'cmb2' ),
		'id'   => 'info_texto_principal',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	/** imagen */
	$reforestamos_adopta_principal->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen principal', 'cmb2' ),
		'id'   => 'info_imagen_principal',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	/** Contenido en inglés */
	/* Texto  */
	$reforestamos_adopta_principal->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Texto de la sección en inglés', 'cmb2' ),
		'id'   => 'info_texto_principal_en',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );


}

/** Dashboard adopta */
add_action( 'cmb2_admin_init', 'reforestamos_adopta_arbol_card' );
function reforestamos_adopta_arbol_card() {
    $prefix = 'reforestamos_group_';

	$reforestamos_adopta_arbol = new_cmb2_box( array(
		'id'           => $prefix . 'adopta_card',
		'title'        => esc_html__( 'Cards adopta un árbol', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-adopta-arbol.php', 
        )
	) );

	$group_field_id = $reforestamos_adopta_arbol->add_field( array(
		'id'          => $prefix . 'adopta_arbol_principal',
		'type'        => 'group',
		'description' => esc_html__( 'Adopta un árbol', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Card adopta {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	/** Card Header  */
	$reforestamos_adopta_arbol->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'card header adopta', 'cmb2' ),
		'id'   => 'card_header_adopta',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	/** Card body */
	$reforestamos_adopta_arbol->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Card body adopta', 'cmb2' ),
		'id'   => 'card_body_adopta',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	/** Card footer imagen */
	$reforestamos_adopta_arbol->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Card footer adopta', 'cmb2' ),
		'id'   => 'card_footer_adopta',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	/** Card footer botón-link */
	$reforestamos_adopta_arbol->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link card adopta', 'cmb2' ),
		'id'   => 'link_card_adopta',
		'type' => 'text',
	) );

	/** 
	** Contenido en inglés 
	*/
	/** Card Header  */
	$reforestamos_adopta_arbol->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Card header adopta en inglés', 'cmb2' ),
		'id'   => 'card_header_adopta_en',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );

	/** Card body */
	$reforestamos_adopta_arbol->add_group_field( $group_field_id, array(
		'name' => esc_html__( '(EN) - Card body adopta en inglés', 'cmb2' ),
		'id'   => 'card_body_adopta_en',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
	) );
}


/** 
** Donar con tarjeta de crédito y pay-pal
**/
/** Sección Donar  */
add_action( 'cmb2_admin_init', 'reforestamos_donar' );
function reforestamos_donar() {
    $prefix = 'reforestamos_group_';

	$reforestamos_donar = new_cmb2_box( array(
		'id'           => $prefix . 'donar',
		'title'        => esc_html__( 'Donar ', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-donar.php' 
        )
	) );

	$reforestamos_donar->add_field( array(
		'name' => esc_html__( 'Imagen donar con tarjeta', 'cmb2' ),
		'id'   => 'img_tarjeta',
		'type' => 'file',
	) );

	// Contenido donar con tarjeta de Crédito o Débito
	$reforestamos_donar->add_field( array(
		'name' => esc_html__( 'Imagen donar con tarjeta', 'cmb2' ),
		'id'   =>'code_contenido_donar',
		'type' => 'textarea_code',
	) );


	// Contenido en inglés - Imagen donar con tarjeta
	$reforestamos_donar->add_field( array(
		'name' => esc_html__( '(EN) - Imagen donar con tarjeta', 'cmb2' ),
		'id'   => 'img_tarjeta_en',
		'type' => 'file',
	) );

	$reforestamos_donar->add_field( array(
		'name' => esc_html__( 'Imagen donar con Paypal', 'cmb2' ),
		'id'   => 'img_paypal',
		'type' => 'file',
	) );

	// Contenido en inglés - Imagen donar con paypal
	$reforestamos_donar->add_field( array(
		'name' => esc_html__( '(EN) - Imagen donar con Paypal', 'cmb2' ),
		'id'   => 'img_paypal_en',
		'type' => 'file',
	) );

}

/** 
** Aviso de privacidad
**/
add_action( 'cmb2_admin_init', 'reforestamos_aviso_privacidad' );
function reforestamos_aviso_privacidad() {
    $prefix = 'reforestamos_group_';

	$reforestamos_aviso_privacidad = new_cmb2_box( array(
		'id'           => $prefix . 'aviso',
		'title'        => esc_html__( '(EN) - Aviso de privacidad ', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-aviso-privacidad.php' 
        )
	) );

	$reforestamos_aviso_privacidad->add_field( array(
		'name' => esc_html__( '(EN) - Aviso de privacidad en inglés', 'cmb2' ),
		'id'   => 'title_aviso_en',
		'type' => 'text',

	) );

	$reforestamos_aviso_privacidad->add_field( array(
		'name' => esc_html__( '(EN) - Aviso de privacidad en inglés', 'cmb2' ),
		'id'   => 'content_aviso_en',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 15,
		),
	) );
}


// Bolsa de trabajo
add_action( 'cmb2_admin_init', 'reforestamos_bolsa_trabajo' );
function reforestamos_bolsa_trabajo() {
    $prefix = 'reforestamos_group_';

	$reforestamos_bolsa_trabajo = new_cmb2_box( array(
		'id'           => $prefix . 'bolsa',
		'title'        => esc_html__( 'Bolsa de trabajo', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-bolsa-trabajo.php' 
        )
	) );

	$group_field_id = $reforestamos_bolsa_trabajo->add_field( array(
		'id'          => $prefix . 'vacantes',
		'type'        => 'group',
		'description' => esc_html__( 'Vacantes información', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Vacante {#}', 'cmb2' ), 
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      	 => true, 
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ),
		),
	) );

	// Nombre vacante - text
	$reforestamos_bolsa_trabajo->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Nombre de la vacante', 'cmb2' ),
		'id'   => 'nombre_vacante',
		'type' => 'text',
	) );

	// Fecha limite vacante - text
	$reforestamos_bolsa_trabajo->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Fecha limite vacante', 'cmb2' ),
		'id'   => 'fecha_vacante',
		'type' => 'text_date',
		// 'date_format' => 'Y-m-d',
	) );

	// Archivo vacante - file
	$reforestamos_bolsa_trabajo->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Archivo de la vacante', 'cmb2' ),
		'id'   => 'documento_vacante',
		'type' => 'file',
	) );

	// correo para interesados - text
	$reforestamos_bolsa_trabajo->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Correo de la vacante', 'cmb2' ),
		'id'   => 'correo_vacante',
		'type' => 'text',
	) );

	// campos servicio social
	// campo correo
	$reforestamos_bolsa_trabajo->add_field( array(
		'name' => esc_html__( 'Correo servicio social', 'cmb2' ),
		'id'   => 'correo_servicio',
		'type' => 'text',
	) );

	// archivo servicio social
	$reforestamos_bolsa_trabajo->add_field( array(
		'name' => esc_html__( 'Correo servicio social', 'cmb2' ),
		'id'   => 'documento_servicio',
		'type' => 'file',
	) );

}


/** CPT (Tarjetas de presentación - Eventos) */
/** Integrantes */
add_action( 'cmb2_admin_init', 'reforestamos_campos_integrantes' );
function reforestamos_campos_integrantes() {
    $prefix = 'reforestamos_integrantes_';

	$reforestamos_campos_integrante = new_cmb2_box( array(
		'id'           => $prefix . 'integrantes',
		'title'        => esc_html__( 'Información de los integrantes', 'cmb2' ),
		'object_types' => array( 'integrantes_rmx' ), 
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, 

	) );

	// Nombre completo
    $reforestamos_campos_integrante->add_field( array(
		'name' => esc_html__( 'Nombre completo del integrante', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el nombre completo del integrante', 'cmb2' ),
		'id'   => $prefix . 'nombre_integrante',
		'type' => 'text',
	) );

	// Área
    $reforestamos_campos_integrante->add_field( array(
		'name' => esc_html__( 'Área del integrante', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el área del integrante del integrante', 'cmb2' ),
		'id'   => $prefix . 'area_integrante',
		'type' => 'text',
	) );

	// Puesto
    $reforestamos_campos_integrante->add_field( array(
		'name' => esc_html__( 'Puesto institucional del integrante', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el puesto institucional del integrante', 'cmb2' ),
		'id'   => $prefix . 'puesto_integrante',
		'type' => 'text',
		'column' => true
	) );

	// Correo integrante
    $reforestamos_campos_integrante->add_field( array(
		'name' => esc_html__( 'Correo de contacto del integrante', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el correo de contacto del integrante', 'cmb2' ),
		'id'   => $prefix . 'correo_integrante',
		'type' => 'text',
	) );
		
	// Teléfono Reforestamos
    $reforestamos_campos_integrante->add_field( array(
		'name' => esc_html__( 'Teléfono de contacto', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el teléfono de contacto', 'cmb2' ),
		'id'   => $prefix . 'telefono_integrante',
		'type' => 'text',
	) );

	// Medios contacto
	// Sitio web Reforestamos
    $reforestamos_campos_integrante->add_field( array(
		'name' => esc_html__( 'URL del sitio web de Reforestamos', 'cmb2' ),
		'desc' => esc_html__( 'Agrega el URL del sitio web de Reforestamos', 'cmb2' ),
		'id'   => $prefix . 'web_integrante',
		'type' => 'text',
	) );

	// Logo reforestamos
	$reforestamos_campos_integrante->add_field( array(
		'name' => esc_html__( 'Logo reforestamos', 'cmb2' ),
		'id'   => $prefix . 'logo_reforestamos',
		'type' => 'file',
	) );
}


/** Eventos */
add_action( 'cmb2_admin_init', 'reforestamos_campos_eventos' );
function reforestamos_campos_eventos() {
    $prefix = 'reforestamos_eventos_';

	$reforestamos_campos_evento = new_cmb2_box( array(
		'id'           => $prefix . 'Eventos',
		'title'        => esc_html__( 'Información del evento', 'cmb2' ),
		'object_types' => array( 'eventos_rmx' ), 
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, 

	) );

	// Fecha Inicio
	$reforestamos_campos_evento->add_field( array(
		'name' => esc_html__( 'Fecha de Inicio', 'cmb2' ),
		'desc' => esc_html__( 'Selecciona la fecha de inicio del evento', 'cmb2' ),
		'id'   => $prefix . 'fecha_inicio',
		'type' => 'text_date',
		// 'timezone_meta_key' => 'yourprefix_demo_timezone', // Optionally make this field honor the timezone selected in the select_timezone specified above
	) );

	// Hora inicio
	$reforestamos_campos_evento->add_field( array(
		'name' => esc_html__( 'Hora de Inicio', 'cmb2' ),
		'id'   => $prefix .'hora_inicio',
		'type' => 'text_time',
		// 'time_format' => 'H:i', // Set to 24hr format
	) );

	// Fecha Termino
	$reforestamos_campos_evento->add_field( array(
		'name' => esc_html__( 'Fecha en que termina el evento', 'cmb2' ),
		'desc' => esc_html__( 'Selecciona la fecha de finalización del evento', 'cmb2' ),
		'id'   => $prefix . 'fecha_fin',
		'type' => 'text_date',
		// 'timezone_meta_key' => 'yourprefix_demo_timezone', // Optionally make this field honor the timezone selected in the select_timezone specified above
	) );

	// Hora inicio
	$reforestamos_campos_evento->add_field( array(
		'name' => esc_html__( 'Hora en que termina el evento', 'cmb2' ),
		'id'   => $prefix .'hora_fin',
		'type' => 'text_time',
		// 'time_format' => 'H:i', // Set to 24hr format
	) );
	
	// Dirección
	$reforestamos_campos_evento->add_field( array(
		'name' => esc_html__( 'Dirección del evento', 'cmb2' ),
		'desc' => esc_html__( 'Agrega la dirección del evento', 'cmb2' ),
		'id'   => $prefix . 'direccion_evento',
		'type' => 'text',
	) );

	// Detalles 
	$reforestamos_campos_evento->add_field( array(
		'name' => esc_html__( 'Detalles adicionales del evento', 'cmb2' ),
		'desc' => esc_html__( 'Agrega los detalles adicionales del evento', 'cmb2' ),
		'id'   => $prefix . 'detalles_evento',
		'type' => 'textarea',
	) );
}


/** Galería carrusel */
add_action( 'cmb2_admin_init', 'reforestamos_galeria_carrusel' );
function reforestamos_galeria_carrusel() {
    $prefix = 'reforestamos_galeria_';

	$reforestamos_galeria = new_cmb2_box( array(
		'id'           => $prefix . 'galeria',
		'title'        => esc_html__( 'Galería carrusel', 'cmb2' ),
		'object_types' => array( 'galeria_carrusel' ), 
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, 

	) );

	$reforestamos_galeria->add_field( array(
		'name'         => esc_html__( 'Agrega las imágenes', 'cmb2' ),
		'desc'         => esc_html__( 'Selecciona las imagenes para crear la galería de imágenes', 'cmb2' ),
		'id'           => 'galeria_imgs',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
	) );
}


/** CPT Notas de blog inglés */
/** Metabox notas de blog en Inglés */
// Agrega metaboxes adicionales (titulo - contenido nota de blog en inglés) en la pantalla de edición de las notas de blog
add_action( 'cmb2_admin_init', 'metabox_nota_ingles' );
function metabox_nota_ingles() {
    $prefix = 'nota_blog_ingles_';

    $reforestamos_nota_ingles = new_cmb2_box( array(
        'id'           => $prefix . 'metabox',
        'title'        => esc_html__( 'Metabox contenido en inglés', 'cmb2' ),
		'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'object_types' => array( 'post' ), // Tipo de contenido 'post' para las notas de blog 
    ) );

    $reforestamos_nota_ingles->add_field( array(
        'name' => esc_html__( 'Campo titulo en inglés', 'cmb2' ),
        'id'   => $prefix . 'titulo_nota_ingles',
        'type' => 'text',
    ) );

	$reforestamos_nota_ingles->add_field( array(
        'name' => esc_html__( 'Campo contenido en inglés', 'cmb2' ),
        'id'   => $prefix . 'contenido_nota_ingles',
		'type' => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 20,
		),
    ) );
}




/** 
 * Arboles y Ciudades site 
 * */
// Carrusel de Inicio
add_action( 'cmb2_admin_init', 'reforestamos_arboles_ciudades_carrusel'); 
function reforestamos_arboles_ciudades_carrusel() {
	$prefix = 'reforestamos_group_';

	$reforestamos_arboles_ciudades_carrusel = new_cmb2_box( array(
		'id'           => $prefix . 'arboles_ciudades_carrusel',
		'title'        => esc_html__( 'Sección carrusel', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-arboles-ciudades.php', 
        )
	) );

	$group_field_id = $reforestamos_arboles_ciudades_carrusel->add_field( array(
		'id'          => $prefix . 'informacion_carrusel_section',
		'type'        => 'group',
		'description' => esc_html__( 'Sección carrusel', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'información {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_arboles_ciudades_carrusel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen carrusel', 'cmb2' ),
		'id'   => 'imagen_carrusel',
		'type' => 'file',
	) );

	$reforestamos_arboles_ciudades_carrusel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Link imagen carrusel', 'cmb2' ),
		'id'   => 'link_carrusel',
		'type' => 'file',
	) );

	$reforestamos_arboles_ciudades_carrusel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo carrusel', 'cmb2' ),
		'id'   => 'titulo_carrusel',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_carrusel->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Texto carrusel', 'cmb2' ),
		'id'   => 'texto_carrusel',
		'type' => 'textarea',
	) );

}

// Sección nuestros programas 
add_action( 'cmb2_admin_init', 'reforestamos_arboles_ciudades_nuestros_programas' );
function reforestamos_arboles_ciudades_nuestros_programas() {
    $prefix = 'reforestamos_group_';

	$reforestamos_arboles_ciudades_nuestros_programas = new_cmb2_box( array(
		'id'           => $prefix . 'arboles_ciudades_nuestros_programas',
		'title'        => esc_html__( 'Sección - Nuestros programas', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-arboles-ciudades.php', 
        )
	) );


	$group_field_id = $reforestamos_arboles_ciudades_nuestros_programas->add_field( array(
		'id'          => $prefix . 'nuestros_programas_section',
		'type'        => 'group',
		'description' => esc_html__( 'Sección - Nuestros programas', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Programa {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );


	/** 
	 * Agregar una condición que muestre el contenido según la seleción del campo 'nombre_iniciativa' 
	 * 
	 * Mostrar los campos del mapa que corresponde a Tree Cities of the world 
	 * Mostrar los campos del mapa que corresponden a Doc.Árbol
	 * Mostrar los campos que corresponden a Acciones de Resiliencia Climática en Acapulco
	 * 
	 * 
	*/

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field($group_field_id, array(
		'name'             => esc_html__( 'Nombres iniciativa', 'cmb2' ),
		'id'               => 'nombre_iniciativa',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'Tree_Cities_World'          => esc_html__( 'Tree Cities of the World', 'cmb2' ),
			'doc_arbol'                  => esc_html__( 'Doc.Árbol', 'cmb2' ),
			'acapulco'                   => esc_html__( 'Acciones de Resiliencia climatica en Acapulco', 'cmb2' )
		),
	) );


	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo programa', 'cmb2' ),
		'id'   => 'titulo_programa',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Logo programa', 'cmb2' ),
		'id'   => 'logo_programa',
		'type' => 'file',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Descripción programa', 'cmb2' ),
		'id'   => 'descripcion_programa',
		'type' => 'wysiwyg',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo restultados', 'cmb2' ),
		'id'   => 'titulo_resultado',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Número del resultado 1', 'cmb2' ),
		'id'   => 'resultado_uno_numero',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Texto del resultado 1', 'cmb2' ),
		'id'   => 'resultado_uno_texto',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Número del resultado 2', 'cmb2' ),
		'id'   => 'resultado_dos_numero',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Texto del resultado 2', 'cmb2' ),
		'id'   => 'resultado_dos_texto',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Número del resultado 3', 'cmb2' ),
		'id'   => 'resultado_tres_numero',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Texto del resultado 3', 'cmb2' ),
		'id'   => 'resultado_tres_texto',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Número del resultado 4', 'cmb2' ),
		'id'   => 'resultado_cuatro_numero',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Texto del resultado 4', 'cmb2' ),
		'id'   => 'resultado_cuatro_texto',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Documento con las organizaciones ganadoras de los programas', 'cmb2' ),
		'id'   => 'organizaciones_reconocidas',
		'type' => 'file',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Documento "Conoce más sobre el programa"', 'cmb2' ),
		'id'   => 'documento_conoce_programa',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nuestros_programas->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Enlace convocatoría', 'cmb2' ),
		'id'   => 'enlace_convocatoria',
		'type' => 'text',
	) );
	
}

// Sobre nosotros
add_action( 'cmb2_admin_init', 'reforestamos_arboles_ciudades_nosotros' );
function reforestamos_arboles_ciudades_nosotros() {
    $prefix = 'reforestamos_group_';

	$reforestamos_arboles_ciudades_nosotros = new_cmb2_box( array(
		'id'           => $prefix . 'arboles_ciudades_nosotros',
		'title'        => esc_html__( 'Sección sobre nosotros', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-arboles-ciudades.php', 
        )
	) );

	$reforestamos_arboles_ciudades_nosotros->add_field( array(
		'name' => esc_html__( 'Titulo sobre nosotros', 'cmb2' ),
		'id'   => 'titulo_nosotros',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_nosotros->add_field( array(
		'name' => esc_html__( 'texto sobre nosotros', 'cmb2' ),
		'id'   => 'texto_nosotros',
		'type' => 'wysiwyg',
	) );

	
}

// Beneficios Arbolado Urbano
add_action( 'cmb2_admin_init', 'reforestamos_arboles_ciudades_conocer_mas_arbolado_urbano' );
function reforestamos_arboles_ciudades_conocer_mas_arbolado_urbano() {
    $prefix = 'reforestamos_group_';

	$reforestamos_arboles_ciudades_conocer_mas_arbolado_urbano = new_cmb2_box( array(
		'id'           => $prefix . 'arboles_ciudades_mas_arboles_urbanos',
		'title'        => esc_html__( 'Sección ¿Por qué más árboles urbanos?', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-arboles-ciudades.php', 
        )
	) );

	/** Documneto ¿Por qué más árboles urbanos?  */
	$reforestamos_arboles_ciudades_conocer_mas_arbolado_urbano->add_field( array(
		'name' => esc_html__( 'Titulo sección beneficios arbolado urbano', 'cmb2' ),
		'id'   => 'titulo_seccion_arbolado_urbano',
		'type' => 'text',
	) );

	$group_field_id = $reforestamos_arboles_ciudades_conocer_mas_arbolado_urbano->add_field( array(
		'id'          => $prefix . 'mas_arboles_section',
		'type'        => 'group',
		'description' => esc_html__( 'Sección ¿Por qué más árboles urbanos?', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Icono {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_arboles_ciudades_conocer_mas_arbolado_urbano->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo icono', 'cmb2' ),
		'id'   => 'titulo_icono',
		'type' => 'text',
	) );

	$reforestamos_arboles_ciudades_conocer_mas_arbolado_urbano->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Descripción icono', 'cmb2' ),
		'id'   => 'descripcion_icono',
		'type' => 'textarea',
	) );

	$reforestamos_arboles_ciudades_conocer_mas_arbolado_urbano->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen icono', 'cmb2' ),
		'id'   => 'imagen_icono',
		'type' => 'file',
	) );

	/** Documneto ¿Por qué más árboles urbanos?  */
	$reforestamos_arboles_ciudades_conocer_mas_arbolado_urbano->add_field( array(
		'name' => esc_html__( 'link botón "Saber más"', 'cmb2' ),
		'id'   => 'documento_conoce_arbolado',
		'type' => 'text',
	) );
	
}

// Contacto
add_action( 'cmb2_admin_init', 'reforestamos_arboles_ciudades_contacto' );
function reforestamos_arboles_ciudades_contacto() {
    $prefix = 'reforestamos_group_';

	$reforestamos_arboles_ciudades_contacto = new_cmb2_box( array(
		'id'           => $prefix . 'arboles_ciudades_contacto',
		'title'        => esc_html__( 'Sección contacto', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-arboles-ciudades.php', 
        )
	) );

	/** Titulo en inglés */
	$reforestamos_arboles_ciudades_contacto->add_field( array(
		'name' => esc_html__( 'texto contacto', 'cmb2' ),
		'id'   => 'texto_contacto',
		'type' => 'textarea',
	) );

	
}

// Ciudades reconocidas TCW
add_action( 'cmb2_admin_init', 'reforestamos_arboles_ciudades_TCW' );
function reforestamos_arboles_ciudades_TCW() {
    $prefix = 'reforestamos_group_';

	$reforestamos_arboles_ciudades_TCW = new_cmb2_box( array(
		'id'           => $prefix . 'arboles_ciudades_Tree_Cities_Wolrd',
		'title'        => esc_html__( 'Ciudades reconocidas del programa Tree Cities of the world', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-arboles-ciudades.php', 
        )
	) );


	$group_field_id = $reforestamos_arboles_ciudades_TCW->add_field( array(
		'id'          => $prefix . 'Ciudades_reconocidas',
		'type'        => 'group',
		'description' => esc_html__( 'Ciudades Reconocidas', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Ciudad {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );
	
	/** Estado reconocido */
	$reforestamos_arboles_ciudades_TCW->add_group_field($group_field_id, array(
		'name'    => 'Estado',
		'id'      => 'estado_reconocido',
		'type'    => 'select',
		'options' => array(
			'Aguascalientes' => 'Aguascalientes',
			'Baja California' => 'Baja California',
			'Baja California Sur' => 'Baja California Sur',
			'Campeche' => 'Campeche',
			'Chiapas' => 'Chiapas',
			'Chihuahua' => 'Chihuahua',
			'Ciudad de México' => 'Ciudad de México',
			'Coahuila' => 'Coahuila',
			'Colima' => 'Colima',
			'Durango' => 'Durango',
			'Guanajuato' => 'Guanajuato',
			'Guerrero' => 'Guerrero',
			'Hidalgo' => 'Hidalgo',
			'Jalisco' => 'Jalisco',
			'Estado de México' => 'Estado de México',
			'Michoacán' => 'Michoacán',
			'Morelos' => 'Morelos',
			'Nayarit' => 'Nayarit',
			'Nuevo León' => 'Nuevo León',
			'Oaxaca' => 'Oaxaca',
			'Puebla' => 'Puebla',
			'Querétaro' => 'Querétaro',
			'Quintana Roo' => 'Quintana Roo',
			'San Luis Potosí' => 'San Luis Potosí',
			'Sinaloa' => 'Sinaloa',
			'Sonora' => 'Sonora',
			'Tabasco' => 'Tabasco',
			'Tamaulipas' => 'Tamaulipas',
			'Tlaxcala' => 'Tlaxcala',
			'Veracruz' => 'Veracruz',
			'Yucatán' => 'Yucatán',
			'Zacatecas' => 'Zacatecas'
		),
	));	

	/** Municipio Reconocido */
	$reforestamos_arboles_ciudades_TCW->add_group_field($group_field_id, array(
		'name' => esc_html__( 'Municipio Reconocido', 'cmb2' ),
		'id'   => 'municipio_reconocido',
		'type' => 'text',
	) );

	/** Veces en que ha sido reconocido */
	$reforestamos_arboles_ciudades_TCW->add_group_field($group_field_id, array(
		'name' => esc_html__( 'Veces reconocido', 'cmb2' ),
		'id'   => 'veces_reconocido',
		'type' => 'text',
	) );

	/** año desde que ha sido reconocido */
	$reforestamos_arboles_ciudades_TCW->add_group_field($group_field_id, array(
		'name' => esc_html__( 'Año desde que ha sido reconocido', 'cmb2' ),
		'id'   => 'anios_reconocido',
		'type' => 'text',
	) );

	
}

// Organizaciones reconocidas Doc.árbol
add_action('cmb2_admin_init', 'reforestamos_arboles_ciudades_doc_arbol');
function reforestamos_arboles_ciudades_doc_arbol() {
	$prefix = 'reforestamos_group_'; 

	$reforestamos_arboles_ciudades_doc_arbol = new_cmb2_box( array(
		'id'           => $prefix . 'arboles_ciudades_doc_arbol',
		'title'        => esc_html__( 'Organizaciones reconocidas del progama Doc.Árbol', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-arboles-ciudades.php', 
        )
	) );


	$reforestamos_arboles_ciudades_doc_arbol->add_field( array(
		'name' => esc_html('organizaciones reconocidas del programa Doc.Árbol', 'cmb2'),
		'id'   => 'anios_reconocidos', 
		'type' => 'file'
	));

}




/** Micrositio Red Oja */
add_action( 'cmb2_admin_init', 'reforestamos_red_oja_sobre_nosotros' );
function reforestamos_red_oja_sobre_nosotros() {
    $prefix = 'reforestamos_group_';

	$reforestamos_red_oja_sobre_nosotros = new_cmb2_box( array(
		'id'           => $prefix . 'red_oja_nosotros',
		'title'        => esc_html__( 'Sección Nosotros', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-red-oja.php', 
        )
	) );

	/** imagen sobre nostros */
	$reforestamos_red_oja_sobre_nosotros->add_field( array(
		'name' => esc_html__( 'Imagen parte izquierda', 'cmb2' ),
		'id'   => 'imagen_nosotros',
		'type' => 'file',
	) );

	// Titulo sobre nosotros
	$reforestamos_red_oja_sobre_nosotros->add_field( array(
		'name' => esc_html__( 'Titulo sobre nosotros', 'cmb2' ),
		'id'   => 'titulo_nosotros',
		'type' => 'text',
	) );

	// Imágenes sobre nosotros
	$reforestamos_red_oja_sobre_nosotros->add_field( array(
		'name' => esc_html__( 'Galería Sobre nosotros', 'cmb2' ),
		'id'   => 'galeria_nosotros',
		'type' => 'file_list',
	) );


	// Contenido sobre nosotros
	$reforestamos_red_oja_sobre_nosotros->add_field( array(
		'name' => esc_html__( 'Contenido Sobre nosotros', 'cmb2' ),
		'id'   => 'contenido_nosotros',
		'type' => 'wysiwyg',
	) );

}

add_action( 'cmb2_admin_init', 'reforestamos_red_oja_principios' );
function reforestamos_red_oja_principios() {
    $prefix = 'reforestamos_group_';

	$reforestamos_red_oja_principios = new_cmb2_box( array(
		'id'           => $prefix . 'red_oja_principios',
		'title'        => esc_html__( 'Sección nuestros principios', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-red-oja.php', 
        )
	) );

	/** Titulo en inglés */
	// $reforestamos_red_oja_principios->add_field( array(
	// 	'name' => esc_html__( 'Correo', 'cmb2' ),
	// 	'id'   => 'correo_arboles_ciudades',
	// 	'type' => 'text',
	// ) );

	$group_field_id = $reforestamos_red_oja_principios->add_field( array(
		'id'          => $prefix . 'nuestros_principios',
		'type'        => 'group',
		'description' => esc_html__( 'Sección nuestros principios', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Icono {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_red_oja_principios->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo icono', 'cmb2' ),
		'id'   => 'titulo_icono',
		'type' => 'text',
	) );

	$reforestamos_red_oja_principios->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Descripción icono', 'cmb2' ),
		'id'   => 'descripcion_icono',
		'type' => 'textarea',
	) );

	$reforestamos_red_oja_principios->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen icono', 'cmb2' ),
		'id'   => 'imagen_icono',
		'type' => 'file',
	) );

	/** Documneto ¿Por qué más árboles urbanos?  */
	$reforestamos_red_oja_principios->add_field( array(
		'name' => esc_html__( 'link botón "Saber más"', 'cmb2' ),
		'id'   => 'documento_nuestros_principios',
		'type' => 'file',
	) );
	
}

add_action( 'cmb2_admin_init', 'reforestamos_red_oja_modelo_incidencia' );
function reforestamos_red_oja_modelo_incidencia() {
    $prefix = 'reforestamos_group_';

	$reforestamos_red_oja_modelo_incidencia = new_cmb2_box( array(
		'id'           => $prefix . 'red_oja_incidencia',
		'title'        => esc_html__( 'Sección modelo de incidencia', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-red-oja.php', 
        )
	) );

	/** Titulo en inglés */
	// $reforestamos_red_oja_modelo_incidencia->add_field( array(
	// 	'name' => esc_html__( 'Correo', 'cmb2' ),
	// 	'id'   => 'correo_arboles_ciudades',
	// 	'type' => 'text',
	// ) );

	$group_field_id = $reforestamos_red_oja_modelo_incidencia->add_field( array(
		'id'          => $prefix . 'modelo_incidencia',
		'type'        => 'group',
		'description' => esc_html__( 'Sección modelo de incidencia', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Icono {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_red_oja_modelo_incidencia->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo icono', 'cmb2' ),
		'id'   => 'titulo_icono',
		'type' => 'text',
	) );

	$reforestamos_red_oja_modelo_incidencia->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Descripción icono', 'cmb2' ),
		'id'   => 'descripcion_icono',
		'type' => 'textarea',
	) );

	$reforestamos_red_oja_modelo_incidencia->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen icono', 'cmb2' ),
		'id'   => 'imagen_icono',
		'type' => 'file',
	) );

	/** Documneto ¿Por qué más árboles urbanos?  */
	$reforestamos_red_oja_modelo_incidencia->add_field( array(
		'name' => esc_html__( 'link botón "Saber más"', 'cmb2' ),
		'id'   => 'documento_modelo_incidencia',
		'type' => 'file',
	) );
	
}

add_action( 'cmb2_admin_init', 'reforestamos_red_oja_nuestros_ejes' );
function reforestamos_red_oja_nuestros_ejes() {
    $prefix = 'reforestamos_group_';

	$reforestamos_red_oja_nuestros_ejes = new_cmb2_box( array(
		'id'           => $prefix . 'red_oja_ejes',
		'title'        => esc_html__( 'Sección nuestros ejes', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-red-oja.php', 
        )
	) );

	/** Titulo en inglés */
	// $reforestamos_red_oja_nuestros_ejes->add_field( array(
	// 	'name' => esc_html__( 'Correo', 'cmb2' ),
	// 	'id'   => 'correo_arboles_ciudades',
	// 	'type' => 'text',
	// ) );

	$group_field_id = $reforestamos_red_oja_nuestros_ejes->add_field( array(
		'id'          => $prefix . 'nuestros_ejes',
		'type'        => 'group',
		'description' => esc_html__( 'Sección Nuestros ejes', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Icono {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_red_oja_nuestros_ejes->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo icono', 'cmb2' ),
		'id'   => 'titulo_icono',
		'type' => 'text',
	) );

	$reforestamos_red_oja_nuestros_ejes->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Descripción icono', 'cmb2' ),
		'id'   => 'descripcion_icono',
		'type' => 'textarea',
	) );

	$reforestamos_red_oja_nuestros_ejes->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Imagen icono', 'cmb2' ),
		'id'   => 'imagen_icono',
		'type' => 'file',
	) );

	/** Documneto ¿Por qué más árboles urbanos?  */
	$reforestamos_red_oja_nuestros_ejes->add_field( array(
		'name' => esc_html__( 'link botón "Saber más"', 'cmb2' ),
		'id'   => 'documento_modelo_incidencia',
		'type' => 'file',
	) );
	
}

add_action( 'cmb2_admin_init', 'reforestamos_red_oja_que_hacemos' );
function reforestamos_red_oja_que_hacemos() {
    $prefix = 'reforestamos_group_';

	$reforestamos_red_oja_que_hacemos = new_cmb2_box( array(
		'id'           => $prefix . 'red_oja_hacemos',
		'title'        => esc_html__( 'Sección ¿Qué hacemos?', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-red-oja.php', 
        )
	) );

	/** Titulo en inglés */
	// $reforestamos_red_oja_que_hacemos->add_field( array(
	// 	'name' => esc_html__( 'Correo', 'cmb2' ),
	// 	'id'   => 'correo_arboles_ciudades',
	// 	'type' => 'text',
	// ) );

	$group_field_id = $reforestamos_red_oja_que_hacemos->add_field( array(
		'id'          => $prefix . 'que_hacemos',
		'type'        => 'group',
		'description' => esc_html__( 'Sección ¿Qué hacemos?', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'dcoumento {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	/** Sección documento */
	$reforestamos_red_oja_que_hacemos->add_group_field( $group_field_id, array(
		'name'    => 'Sección documento',
		'id'      => 'seccion_documento',
		'type'    => 'select',
		'options' => array(
			'Memorias' => 'Memorias',
			'documento_dos' => 'Documento Dos',
			'documento_tres' => 'Documento Tres',
			'document_cuatro' => 'Documento Cuatro'
		),
	) );


	/** Titulo Memoria */
	$reforestamos_red_oja_que_hacemos->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo icono', 'cmb2' ),
		'id'   => 'titulo_pop_up',
		'type' => 'text',
	) );

	/** Link documento */
	$reforestamos_red_oja_que_hacemos->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Enlace memoria', 'cmb2' ),
		'id'   => 'documento_memoria',
		'type' => 'file',
	) );
	
	/** Fecha documento */
	$reforestamos_red_oja_que_hacemos->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Fecha Documento', 'cmb2' ),
		'id'   => 'fecha_memoria',
		'type'    => 'select',
		'options' => array_combine(range(date('Y'), 1900), range(date('Y'), 1900)), // Desde el año actual hasta 1900
	) );

	/* Icono memoria (icono de documento) */
	$reforestamos_red_oja_que_hacemos->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Icono memoria', 'cmb2' ),
		'id'   => 'imagen_pop_up',
		'type' => 'file',
	) );
	
}

add_action( 'cmb2_admin_init', 'reforestamos_red_oja_galeria' );
function reforestamos_red_oja_galeria() {
    $prefix = 'reforestamos_group_';

	$reforestamos_red_oja_galeria = new_cmb2_box( array(
		'id'           => $prefix . 'red_oja_galeria',
		'title'        => esc_html__( 'Galería', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-red-oja.php', 
        )
	) );

	/** Imagen galería */
	$reforestamos_red_oja_galeria->add_field( array(
		'name' => esc_html__( 'Imágenes Galería', 'cmb2' ),
		'id'   => 'imagenes_galeria',
		'type' => 'file_list',
	) );

	$group_field_id = $reforestamos_red_oja_galeria->add_field( array(
		'id'          => $prefix . 'titulos_galeria',
		'type'        => 'group',
		'description' => esc_html__( 'Titulos galería', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'titulo galería {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );


	$group_field_id = $reforestamos_red_oja_galeria->add_field( array(
		'id'          => $prefix . 'galeria',
		'type'        => 'group',
		'description' => esc_html__( 'Los titulo de la galería deben tener relación con las imaágenes de la galería', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Título galería {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_red_oja_galeria->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo galería', 'cmb2' ),
		'id'   => 'titulos_galeria',
		'type' => 'text',
	) );


	
}

add_action('cmb2_admin_init', 'reforestamos_red_oja_nuestro_mapa');
function reforestamos_red_oja_nuestro_mapa() {
	$prefix = 'reforestamos_group_';
    $reforestamos_red_oja_nuestro_mapa = new_cmb2_box(array(
        'id'            => $prefix . 'red_oja_instituciones_mapa',
        'title'         => esc_html__('Sección Organizaciones', 'cmb2'),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-red-oja.php', 
        )
    ));

    $group_field_id = $reforestamos_red_oja_nuestro_mapa->add_field(array(
        'id'          => $prefix . 'institutions_group',
        'type'        => 'group',
        'description' => __('Agrega las organizaciones y sus ubicaciones.', 'cmb2'),
        'options'     => array(
            'group_title'   => __('Institución {#}', 'cmb2'),
            'add_button'    => __('Agregar Institución', 'cmb2'),
            'remove_button' => __('Eliminar Institución', 'cmb2'),
            'sortable'      => true,
        ),
    ));

    $reforestamos_red_oja_nuestro_mapa->add_group_field($group_field_id, array(
        'name' => 'Nombre de la organización',
        'id'   => 'institution_name',
        'type' => 'text',
    ));

	$reforestamos_red_oja_nuestro_mapa->add_group_field($group_field_id, array(
        'name' => 'Logo de la organización',
        'id'   => 'institution_logo',
        'type' => 'file',
    ));

	$reforestamos_red_oja_nuestro_mapa->add_group_field($group_field_id, array(
		'name'    => 'Estado',
		'id'      => 'institution_state',
		'type'    => 'select',
		'options' => array(
			'Aguascalientes' => 'Aguascalientes',
			'Baja California' => 'Baja California',
			'Baja California Sur' => 'Baja California Sur',
			'Campeche' => 'Campeche',
			'Chiapas' => 'Chiapas',
			'Chihuahua' => 'Chihuahua',
			'Ciudad de México' => 'Ciudad de México',
			'Coahuila' => 'Coahuila',
			'Colima' => 'Colima',
			'Durango' => 'Durango',
			'Guanajuato' => 'Guanajuato',
			'Guerrero' => 'Guerrero',
			'Hidalgo' => 'Hidalgo',
			'Jalisco' => 'Jalisco',
			'Estado de México' => 'Estado de México',
			'Michoacán' => 'Michoacán',
			'Morelos' => 'Morelos',
			'Nayarit' => 'Nayarit',
			'Nuevo León' => 'Nuevo León',
			'Oaxaca' => 'Oaxaca',
			'Puebla' => 'Puebla',
			'Querétaro' => 'Querétaro',
			'Quintana Roo' => 'Quintana Roo',
			'San Luis Potosí' => 'San Luis Potosí',
			'Sinaloa' => 'Sinaloa',
			'Sonora' => 'Sonora',
			'Tabasco' => 'Tabasco',
			'Tamaulipas' => 'Tamaulipas',
			'Tlaxcala' => 'Tlaxcala',
			'Veracruz' => 'Veracruz',
			'Yucatán' => 'Yucatán',
			'Zacatecas' => 'Zacatecas',
			"Norteamérica" => "Norteamérica",
			"Sudamérica" => "Sudamérica",
			"Europa" => "Europa",
			"Asia" => "Asia",
			"África" => "África",
			"Oceanía" => "Oceanía",
			'Otro' => 'Otro'
		),
	));

	$reforestamos_red_oja_nuestro_mapa->add_group_field($group_field_id, array(
        'name' => 'Localidad o Municipio',
        'id'   => 'institution_location',
        'type' => 'text',
    ));

	$reforestamos_red_oja_nuestro_mapa->add_group_field($group_field_id, array(
        'name' => 'País',
        'id'   => 'institution_country',
        'type' => 'text',
    ));

    $reforestamos_red_oja_nuestro_mapa->add_group_field($group_field_id, array(
        'name' => 'Latitud',
        'id'   => 'institution_lat',
        'type' => 'text',
    ));

    $reforestamos_red_oja_nuestro_mapa->add_group_field($group_field_id, array(
        'name' => 'Longitud',
        'id'   => 'institution_lng',
        'type' => 'text',
    ));

	$reforestamos_red_oja_nuestro_mapa->add_group_field($group_field_id, array(
        'name' => 'Link contacto, Red Social',
        'id'   => 'institution_link',
        'type' => 'text',
    ));
}

add_action( 'cmb2_admin_init', 'reforestamos_red_oja_como_sumarte' );
function reforestamos_red_oja_como_sumarte() {
    $prefix = 'reforestamos_group_';

	$reforestamos_red_oja_como_sumarte = new_cmb2_box( array(
		'id'           => $prefix . 'red_oja_sumarte',
		'title'        => esc_html__( 'Sección ¿Cómo sumarte?', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-red-oja.php', 
        )
	) );


	$group_field_id = $reforestamos_red_oja_como_sumarte->add_field( array(
		'id'          => $prefix . 'como_sumarte',
		'type'        => 'group',
		'description' => esc_html__( 'Sección ¿Cómo sumarte?', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Icono {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      => true, // true to have the groups closed by default
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
		),
	) );

	$reforestamos_red_oja_como_sumarte->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Titulo sección', 'cmb2' ),
		'id'   => 'titulo_seccion',
		'type' => 'text',
	) );

	$reforestamos_red_oja_como_sumarte->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Descripción sección', 'cmb2' ),
		'id'   => 'descripción_seccion',
		'type' => 'wysiwyg',
	) );

	// $reforestamos_red_oja_como_sumarte->add_group_field( $group_field_id, array(
	// 	'name' => esc_html__( 'Imagen icono', 'cmb2' ),
	// 	'id'   => 'imagen_icono',
	// 	'type' => 'file',
	// ) );

	/** Documneto ¿Por qué más árboles urbanos?  */
	// $reforestamos_red_oja_como_sumarte->add_field( array(
	// 	'name' => esc_html__( 'link botón "Saber más"', 'cmb2' ),
	// 	'id'   => 'documento_modelo_incidencia',
	// 	'type' => 'file',
	// ) );
	
}

add_action( 'cmb2_admin_init', 'reforestamos_red_oja_contacto' );
function reforestamos_red_oja_contacto() {
    $prefix = 'reforestamos_group_';

	$reforestamos_red_oja_como_sumarte = new_cmb2_box( array(
		'id'           => $prefix . 'red_oja_contacto',
		'title'        => esc_html__( 'Sección Contacto', 'cmb2' ),
		'object_types' => array( 'page' ),
        'context'      => 'normal',
        'priority'     => 'high', 
        'show_names'   => 'true',
        'show_on'      => array(
            'key'      => 'page-template',
            'value'    => 'page-red-oja.php', 
        )
	) );

	
	$reforestamos_red_oja_como_sumarte->add_field( array(
		'name' => esc_html__( 'Titulo contacto', 'cmb2' ),
		'id'   => 'titulo_contacto',
		'type' => 'text',
	) );

	$reforestamos_red_oja_como_sumarte->add_field( array(
		'name' => esc_html__( 'Primer texto contacto', 'cmb2' ),
		'id'   => 'contenido_contacto',
		'type' => 'textarea',
	) );


	// $reforestamos_red_oja_como_sumarte->add_group_field( $group_field_id, array(
	// 	'name' => esc_html__( 'Imagen icono', 'cmb2' ),
	// 	'id'   => 'imagen_icono',
	// 	'type' => 'file',
	// ) );

	/** Documneto ¿Por qué más árboles urbanos?  */
	// $reforestamos_red_oja_como_sumarte->add_field( array(
	// 	'name' => esc_html__( 'link botón "Saber más"', 'cmb2' ),
	// 	'id'   => 'documento_modelo_incidencia',
	// 	'type' => 'file',
	// ) );
	
}










