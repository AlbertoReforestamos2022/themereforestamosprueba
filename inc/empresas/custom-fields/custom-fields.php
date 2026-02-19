<?php
// /** CPT (Logos - Galerias emrpesas) */
add_action( 'cmb2_admin_init', 'reforestamos_galerias_empresas' );
function reforestamos_galerias_empresas() {
    $prefix = 'reforestamos_galerias_empresas_';

	$reforestamos_campos_galeria= new_cmb2_box( array(
		'id'           => $prefix . 'galeria',
		'title'        => esc_html__( 'Información de las galerías', 'cmb2' ),
		'object_types' => array( 'empresas' ), 
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, 

	) );

	// Seleccionar tipo paquete
	$reforestamos_campos_galeria->add_field( array(
		'name'             => esc_html__( 'Tipo Empresa', 'cmb2' ),
		'desc'             => esc_html__( 'Selecciona el tipo de paquete que le corresponde', 'cmb2' ),
		'id'               => 'tipo_paquete',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'A' => esc_html__( 'Grupo A', 'cmb2' ),
			'B'   => esc_html__( 'Grupo B', 'cmb2' ),
			'C'   => esc_html__( 'Grupo C, D, E, F', 'cmb2' ),
		),
	) );

	// Logo
	$reforestamos_campos_galeria->add_field( array(
		'name'         => esc_html__( 'Logo de la empresa', 'cmb2' ),
		'id'   		   => 'logo_empresa',
		'type'         => 'file',
		'options'      => array(
			'textarea_rows'   => 15,
		),
	) );

	// Fondo 
	$reforestamos_campos_galeria->add_field( array(
		'name'         => esc_html__( 'Fondo del logo', 'cmb2' ),
		'id'   		   => 'fondo_logo',
		'type'    => 'colorpicker',
		'default' => '#ffffff',
		'attributes' => array(
			'data-colorpicker' => json_encode( array(
				'palettes' => array( '#036935', '#b1cb36', '#94ce58', '#4674c1', '#2abde3', '#65b492', '#1e94d4', '#9f6cc5', '#e76571', '#ec432c','#ef7323', '#f5d138' ),
			) ),
		),
	) );
	
	// Tamaño ancho del logo 
	$reforestamos_campos_galeria->add_field( array(
		'name'         => esc_html__( 'Ancho del logo', 'cmb2' ),
		'id'   		   => 'ancho_logo',
		'type'         => 'text',
	) );

	// Margen de separación del logo
	$reforestamos_campos_galeria->add_field( array(
		'name'         => esc_html__( 'Margen ', 'cmb2' ),
		'id'   		   => 'margen_imagen',
		'type'         => 'text',
	) );

	$group_field_id = $reforestamos_campos_galeria->add_field( array(
		'id'          => $prefix . 'galeria_empresa',
		'type'        => 'group',
		'description' => esc_html__( 'Galerias empresa', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'Actividad {#}', 'cmb2' ), 
			'add_button'     => esc_html__( 'Agregar grupo', 'cmb2' ),
			'remove_button'  => esc_html__( 'Eliminar', 'cmb2' ),
			'sortable'       => true,
			'closed'      	 => true, 
 			'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ),
		),
	) );

	// Titulo reforestación 
	$reforestamos_campos_galeria->add_group_field( $group_field_id, array(
		'name'         => esc_html__( 'Titulo', 'cmb2' ),
		'id'   		   => 'titulo_actividad',
		'type'         => 'text',
	) );

	// Imagenes reforestación 
	$reforestamos_campos_galeria->add_group_field( $group_field_id, array(
		'name'         => esc_html__( 'Imagenes de la actividad', 'cmb2' ),
		'id'   		   => 'imagenes_actividad',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
	) );

	// Fecha actividad
	$reforestamos_campos_galeria->add_group_field( $group_field_id, array(
		'name' => esc_html__( 'Fecha actividad', 'cmb2' ),
		'id'   => 'fecha_actividad',
		'type' => 'text_date',
		'date_format' => 'd-m-Y',
	) );

	// Texto reforestación
	$reforestamos_campos_galeria->add_group_field( $group_field_id, array(
		'name'         => esc_html__( 'Caracteristicas de la actividad', 'cmb2' ),
		'id'   		   => 'texto_actividad',
		'type'         => 'wysiwyg',
		'options'      => array(
			'textarea_rows'   => 15,
		),
	) );


}

