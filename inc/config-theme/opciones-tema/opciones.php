<?php 
add_action( 'cmb2_admin_init', 'reforestamos_opciones_theme' );
function reforestamos_opciones_theme() {
	$cmb_options = new_cmb2_box( array(
		'id'           => 'reforestamos_opciones_theme',
		'title'        => esc_html__( 'Opciones del sitio', 'cmb2' ),
		'object_types' => array( 'options-page' ),

		'option_key'      => 'reforestamos_opciones_theme', 
		'icon_url'        => 'dashicons-edit', 

	) );

    // Logo sitio web
    $cmb_options->add_field( array(
		'name'    => esc_html__( 'Logo sitio web', 'cmb2' ),
		'desc'    => esc_html__( 'Logo del sitio web', 'cmb2' ),
		'id'      => 'logo_sitio_web',
		'type'    => 'file',
	) );

    // Icono titulo 
    $cmb_options->add_field( array(
		'name'    => esc_html__( 'Icono titulo', 'cmb2' ),
		'desc'    => esc_html__( 'Icono que acompaña el titulo en la pagina de inicio', 'cmb2' ),
		'id'      => 'icon_titulo',
		'type'    => 'file',
	) );

    // Color menú llamdos a la acción General
    $cmb_options->add_field( array(
		'name'    => esc_html__( 'Color barra acción general', 'cmb2' ),
		'desc'    => esc_html__( 'Barra navegación llamados a la acción', 'cmb2' ),
		'id'      => 'bg_nav_accion_general',
		'type'    => 'colorpicker',
		'default' => '#ffffff',
	) );

    // Color menú llamdos a la acción Incendios forestales
    $cmb_options->add_field( array(
		'name'    => esc_html__( 'Color barra acción incendios forestales', 'cmb2' ),
		'desc'    => esc_html__( 'Color del menú llamados a la acción en la página de incendios forestales', 'cmb2' ),
		'id'      => 'bg_nav_accion_incendios',
		'type'    => 'colorpicker',
		'default' => '#ffffff',
	) );


    $cmb_options->add_field(array(
        'name'    => '<h2 class="titulo-desc">Redes Sociales</h2>',
        'id'      => 'titulo_general',
        'type'    => 'title',
    ) );

    $cmb_options->add_field(array(
        'name'    => 'Facebook',
        'id'      => 'seccion_facebook',
        'type'    => 'title',
        // 'desc'   => '<p class="contenido-desc"></p>', // Puedes personalizar el texto de la separación aquí
    ) );

    // Icono Facebook 
    $cmb_options->add_field( array(
		'name'    => esc_html__( 'Icono Facebook', 'cmb2' ),
		'id'      => 'icono_facebook',
		'type'    => 'text',
	) );

    // link Facebook
    $cmb_options->add_field( array(
		'name'    => esc_html__( 'Link Facebook Reforestamos México', 'cmb2' ),
		'id'      => 'link_facebook',
		'type'    => 'text',
	) );

    $cmb_options->add_field(array(
        'name'    => 'X (Twitter)',
        'id'      => 'seccion_twitter',
        'type'    => 'title',
    ) );

    // Icono Twitter 
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Icono X (Twitter)', 'cmb2' ),
        'id'      => 'icono_twitter',
        'type'    => 'text',
    ) );

    // link Twitter
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Twitter Reforestamos México', 'cmb2' ),
        'id'      => 'link_twitter',
        'type'    => 'text',
    ) );

    $cmb_options->add_field(array(
        'name'    => 'Instagram',
        'id'      => 'seccion_instagram',
        'type'    => 'title',
    ) );

    // Icono Instagram 
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Icono Instagram', 'cmb2' ),
        'id'      => 'icono_instagram',
        'type'    => 'text',
    ) );

    // link Instagram
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Instagram Reforestamos México', 'cmb2' ),
        'id'      => 'link_instagram',
        'type'    => 'text',
    ) );

    $cmb_options->add_field(array(
        'name'    => 'LinkedIn',
        'id'      => 'seccion_linkedin',
        'type'    => 'title',
    ) );

    // Icono Linkedin 
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Icono Linkedin', 'cmb2' ),
        'id'      => 'icono_linkedin',
        'type'    => 'text',
    ) );

    // link Linkedin
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Linkedin Reforestamos México', 'cmb2' ),
        'id'      => 'link_linkedin',
        'type'    => 'text',
    ) );    

    $cmb_options->add_field(array(
        'name'    => 'Tik-Tok',
        'id'      => 'seccion_tiktok',
        'type'    => 'title',
    ) );

    // Icono Tik-tok 
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Icono Tik-Tok', 'cmb2' ),
        'id'      => 'icono_tiktok',
        'type'    => 'text',
    ) );

    // link Tik-tok
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Tik-Tok Reforestamos México', 'cmb2' ),
        'id'      => 'link_tiktok',
        'type'    => 'text',
    ) );

    $cmb_options->add_field(array(
        'name'    => 'YouTube',
        'id'      => 'seccion_youtube',
        'type'    => 'title',
    ) );

    // Icono YouTube 
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Icono YouTube', 'cmb2' ),
        'id'      => 'icono_youtube',
        'type'    => 'text',
    ) );

    // link YouTube
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'YouTube Reforestamos México', 'cmb2' ),
        'id'      => 'link_youtube',
        'type'    => 'text',
    ) );

    $cmb_options->add_field(array(
        'name'    => 'Teléfono',
        'id'      => 'seccion_telefono',
        'type'    => 'title',
    ) );

    // Número télefono
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Número de teléfono', 'cmb2' ),
        'id'      => 'numero_telefono',
        'type'    => 'text',
    ) );

    $cmb_options->add_field(array(
        'name'    => 'Correo',
        'id'      => 'seccion_correo',
        'type'    => 'title',
    ) );

    // Correo
    $cmb_options->add_field( array(
        'name'    => esc_html__( 'Correo', 'cmb2' ),
        'id'      => 'correo_email',
        'type'    => 'text',
    ) );

    // Feeds Redes sociales - Facebook - LinkedIn - Twitter
    $cmb_options->add_field(array(
        'name'    => '<h2 class="titulo-desc">Feeds Redes sociales para el blog</h2>',
        'id'      => 'titulo_general_feed',
        'type'    => 'title',
    ) );

    $cmb_options->add_field( array(
		'name' => esc_html__( 'Feed Facebook', 'cmb2' ),
		'desc' => esc_html__( 'Código para el feed de Facebook', 'cmb2' ),
		'id'   => 'facebook_feed',
		'type' => 'textarea_code',
		// 'attributes' => array(
		// 	// Optionally override the code editor defaults.
		// 	'data-codeeditor' => json_encode( array(
		// 		'codemirror' => array(
		// 			'lineNumbers' => false,
		// 			'mode' => 'css',
		// 		),
		// 	) ),
		// ),
		// To keep the previous formatting, you can disable codemirror.
		// 'options' => array( 'disable_codemirror' => true ),
	) );

    $cmb_options->add_field( array(
		'name' => esc_html__( 'Feed LinkedIn', 'cmb2' ),
		'desc' => esc_html__( 'Código para el feed de LinkedIn', 'cmb2' ),
		'id'   => 'linkedin_feed',
		'type' => 'textarea_code',
		// 'attributes' => array(
		// 	// Optionally override the code editor defaults.
		// 	'data-codeeditor' => json_encode( array(
		// 		'codemirror' => array(
		// 			'lineNumbers' => false,
		// 			'mode' => 'css',
		// 		),
		// 	) ),
		// ),
		// To keep the previous formatting, you can disable codemirror.
		// 'options' => array( 'disable_codemirror' => true ),
	) );

    $cmb_options->add_field( array(
		'name' => esc_html__( 'Feed X (Twitter)', 'cmb2' ),
		'desc' => esc_html__( 'Código para el feed de X (Twitter)', 'cmb2' ),
		'id'   => 'twitter_feed',
		'type' => 'textarea_code',
		// 'attributes' => array(
		// 	// Optionally override the code editor defaults.
		// 	'data-codeeditor' => json_encode( array(
		// 		'codemirror' => array(
		// 			'lineNumbers' => false,
		// 			'mode' => 'css',
		// 		),
		// 	) ),
		// ),
		// To keep the previous formatting, you can disable codemirror.
		// 'options' => array( 'disable_codemirror' => true ),
	) );
}
?>