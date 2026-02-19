
<?php
// Opciones Chatbot
add_action('cmb2_admin_init', 'opciones_chatbot');
function opciones_chatbot() {
    $prefix = 'reforestamos_';

    $reforestamos_chatbot = new_cmb2_box(array(
        'id'           => $prefix . 'chatbot_options_page',
        'title'        => esc_html__('Chatbot Reforestamos', 'cmb2'),
        'object_types' => array('options-page'),
        'option_key'   => 'chatbot_theme_options', // The option key and admin menu page slug.
        'capability'   => 'manage_options', // Cap required to view options-page.
        'icon_url'     => 'dashicons-format-chat',
        // 'parent_slug'  => 'chatbot-reforestamos', // Slug del menú principal para mostrar la caja de opciones como un submenú
        'menu_title'   => esc_html__('Chatbot Reforestamos', 'cmb2'),
        'position'     => 20, // Menu position.
    ));

    // Textos introductorios
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Textos introducorios</h4>',
        'id'      => $prefix . 'separacion_introduccion',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección introducorios </p>', // Puedes personalizar el texto de la separación aquí
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
        'id'          => $prefix . 'chatbot_intro',
        'type'        => 'group',
        // 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
        'options'     => array(
            'group_title'    => esc_html__( 'Contenido intro {#}', 'cmb2' ), // {#} gets replaced by row number
            'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
            'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
            'sortable'       => true,

        ),
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido introducción', 'cmb2'),
        'id'   => 'contenido_introduccion',
        'type' => 'wysiwyg',
    ) );


    // Voluntariado
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Sección Voluntariado</h4>',
        'id'      => $prefix . 'separacion_voluintariado',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección de respuestas de voluntariado </p>', // Puedes personalizar el texto de la separación aquí
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
		'id'          => $prefix . 'chatbot_voluntariado',
		'type'        => 'group',
		// 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Contenido Voluntariado {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,

		),
	) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Campo 1', 'cmb2'),
        'desc' => esc_html__('Titulo voluntariado', 'cmb2'),
        'id'   => 'titulo_voluntariado',
        'type' => 'text',
	) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Campo 1', 'cmb2'),
        'desc' => esc_html__('Titulo voluntariado', 'cmb2'),
        'id'   => 'contenido_voluntariado',
        'type' => 'wysiwyg',
	) );


    
    // 2- Marketing con causa
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Sección Marketing con causa.</h4>',
        'id'      => $prefix . 'separacion_marketing',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección de respuestas de marketing </p>', // Puedes personalizar el texto de la separación aquí
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
		'id'          => $prefix . 'chatbot_marketing',
		'type'        => 'group',
		// 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Contenido Marketing {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,

		),
	) );


    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Titulo marketing', 'cmb2'),
        'id'   => 'titulo_marketing',
        'type' => 'text',
	) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido marketing', 'cmb2'),
        'id'   => 'contenido_marketing',
        'type' => 'wysiwyg',
	) );

    // 3- Adopta un árbol
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Sección Adopta un árbol.</h4>',
        'id'      => $prefix . 'separacion_adopta_arbol',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección de respuestas de Adopta un árbol.</p>',
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
        'id'          => $prefix . 'chatbot_adopta_arbol',
        'type'        => 'group',
        // 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
        'options'     => array(
            'group_title'    => esc_html__( 'Contenido {#}', 'cmb2' ), 
            'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
            'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
            'sortable'       => true,

        ),
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Titulo sección adopta un árbol', 'cmb2'),
        'id'   => 'titulo_adopta',
        'type' => 'text',
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido adopta un árbol', 'cmb2'),
        'id'   => 'contenido_adopta',
        'type' => 'wysiwyg',
    ) );

    // 4- Bolsa de trabajo
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Sección Bolsa de trabajo.</h4>',
        'id'      => $prefix . 'separacion_bolsa_trabajo',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección de respuestas de Bolsa de trabajo.</p>',
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
        'id'          => $prefix . 'chatbot_bolsa_trabajo',
        'type'        => 'group',
        // 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
        'options'     => array(
            'group_title'    => esc_html__( 'Contenido {#}', 'cmb2' ), 
            'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
            'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
            'sortable'       => true,

        ),
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Titulo sección bolsa de trabajo', 'cmb2'),
        'id'   => 'titulo_bolsa',
        'type' => 'text',
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido adopta un árbol', 'cmb2'),
        'id'   => 'contenido_bolsa',
        'type' => 'wysiwyg',
    ) );

    // 5-Centinelas del tiempo
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Sección Centinelas del tiempo.</h4>',
        'id'      => $prefix . 'separacion_centinelas',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección de respuestas de Centinelas del tiempo.</p>',
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
        'id'          => $prefix . 'chatbot_centinelas',
        'type'        => 'group',
        // 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
        'options'     => array(
            'group_title'    => esc_html__( 'Contenido {#}', 'cmb2' ), 
            'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
            'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
            'sortable'       => true,
        ),
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Titulo sección centinelas del tiempo', 'cmb2'),
        'id'   => 'titulo_centinelas',
        'type' => 'text',
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido adopta un árbol', 'cmb2'),
        'id'   => 'contenido_centinelas',
        'type' => 'wysiwyg',
    ) );

    // 6- Compra y/o venta de arboles  
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Sección Compra/Venta de arboles.</h4>',
        'id'      => $prefix . 'separacion_compra_venta_arboles',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección de respuestas de Compra/Venta de arboles.</p>',
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
        'id'          => $prefix . 'chatbot_compra_venta_arboles',
        'type'        => 'group',
        // 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
        'options'     => array(
            'group_title'    => esc_html__( 'Contenido {#}', 'cmb2' ), 
            'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
            'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
            'sortable'       => true,
        ),
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Titulo sección Compra/Venta de arboles', 'cmb2'),
        'id'   => 'titulo_compra_venta_arboles',
        'type' => 'text',
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido Compra/Venta de arboles', 'cmb2'),
        'id'   => 'contenido_compra_venta_arboles',
        'type' => 'wysiwyg',
    ) );

    // 7- Donar  
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Sección Donar.</h4>',
        'id'      => $prefix . 'separacion_donar',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección de respuestas de Donar.</p>',
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
        'id'          => $prefix . 'chatbot_donar',
        'type'        => 'group',
        // 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
        'options'     => array(
            'group_title'    => esc_html__( 'Contenido {#}', 'cmb2' ), 
            'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
            'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
            'sortable'       => true,
        ),
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Titulo sección donar', 'cmb2'),
        'id'   => 'titulo_donar',
        'type' => 'text',
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido donar', 'cmb2'),
        'id'   => 'contenido_donar',
        'type' => 'wysiwyg',
    ) );

    // 8- Contacto  
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Sección contacto.</h4>',
        'id'      => $prefix . 'separacion_contacto',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección de respuestas de contacto.</p>',
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
        'id'          => $prefix . 'chatbot_contacto',
        'type'        => 'group',
        // 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
        'options'     => array(
            'group_title'    => esc_html__( 'Contenido {#}', 'cmb2' ), 
            'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
            'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
            'sortable'       => true,
        ),
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Titulo sección contacto', 'cmb2'),
        'id'   => 'titulo_contacto',
        'type' => 'text',
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido contacto', 'cmb2'),
        'id'   => 'contenido_contacto',
        'type' => 'wysiwyg',
    ) );

    // 9- Eventos  
    $reforestamos_chatbot->add_field(array(
        'name'    => '<h4 class="titulo-desc">Sección eventos.</h4>',
        'id'      => $prefix . 'separacion_eventos',
        'type'    => 'title',
        'desc'   => '<p class="contenido-desc">Sección de respuestas de eventos.</p>',
    ) );


    $group_field_id = $reforestamos_chatbot->add_field( array(
        'id'          => $prefix . 'chatbot_eventos',
        'type'        => 'group',
        // 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
        'options'     => array(
            'group_title'    => esc_html__( 'Contenido {#}', 'cmb2' ), 
            'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
            'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
            'sortable'       => true,
        ),
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Titulo sección eventos', 'cmb2'),
        'id'   => 'titulo_eventos',
        'type' => 'text',
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido eventos', 'cmb2'),
        'id'   => 'contenido_eventos',
        'type' => 'wysiwyg',
    ) );
    

    $group_field_id = $reforestamos_chatbot->add_field( array(
        'id'          => $prefix . 'preguntas_frecuentes',
        'type'        => 'group',
        // 'description' => esc_html__( 'Agregar Imagenes Principal Home', 'cmb2' ),
        'options'     => array(
            'group_title'    => esc_html__( 'Pregunta {#}', 'cmb2' ), 
            'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
            'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
            'sortable'       => true,
        ),
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Pregunta', 'cmb2'),
        'id'   => 'pregunta_chat',
        'type' => 'text',
    ) );

    $reforestamos_chatbot->add_group_field( $group_field_id, array(
        'name' => esc_html__('Contenido eventos', 'cmb2'),
        'id'   => 'respuesta_chat',
        'type' => 'wysiwyg',
    ) );

}


