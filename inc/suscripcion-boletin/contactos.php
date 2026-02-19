<?php
// Suscripciones Boletín
// Register Custom Post Type
function contactos_post_type() {

	$labels = array(
		'name'                  => _x( 'Suscripciones', 'Post Type General Name', 'contactos_domain' ),
		'singular_name'         => _x( 'Suscripcion', 'Post Type Singular Name', 'contactos_domain' ),
		'menu_name'             => __( 'Suscripciones', 'contactos_domain' ),
		'name_admin_bar'        => __( 'Suscripciones', 'contactos_domain' ),
		'archives'              => __( 'Archivo contactos', 'contactos_domain' ),
		'attributes'            => __( 'Atributos contactos', 'contactos_domain' ),
		'parent_item_colon'     => __( 'Contacto padre:', 'contactos_domain' ),
		'all_items'             => __( 'Todas', 'contactos_domain' ),
		'add_new_item'          => __( 'Agregar nueva', 'contactos_domain' ),
		'add_new'               => __( 'Agregar', 'contactos_domain' ),
		'new_item'              => __( 'Nueva', 'contactos_domain' ),
		'edit_item'             => __( 'Editar', 'contactos_domain' ),
		'update_item'           => __( 'Actualizar', 'contactos_domain' ),
		'view_item'             => __( 'Ver contacto', 'contactos_domain' ),
		'view_items'            => __( 'Ver contactos', 'contactos_domain' ),
		'search_items'          => __( 'Buscar contacto', 'contactos_domain' ),
		'not_found'             => __( 'No encontrado', 'contactos_domain' ),
		'not_found_in_trash'    => __( 'No encontrado en la papelera', 'contactos_domain' ),
		'featured_image'        => __( 'Imagen detacada', 'contactos_domain' ),
		'set_featured_image'    => __( 'Asignar imagen destacada', 'contactos_domain' ),
		'remove_featured_image' => __( 'Remover imagen', 'contactos_domain' ),
		'use_featured_image'    => __( 'Usar como imagen detacada', 'contactos_domain' ),
		'insert_into_item'      => __( 'Insertar en contacto', 'contactos_domain' ),
		'uploaded_to_this_item' => __( 'Subir a contacto', 'contactos_domain' ),
		'items_list'            => __( 'Lista contacto', 'contactos_domain' ),
		'items_list_navigation' => __( 'Navegación contactos', 'contactos_domain' ),
		'filter_items_list'     => __( 'Fitro contactos', 'contactos_domain' ),
	);


	$args = array(
		'label'                 => __( 'Contacto', 'contactos_domain' ),
		'description'           => __( 'Contenido de contactos', 'contactos_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 7,
		'menu_icon'             => 'dashicons-format-aside',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
        'capabilities' => array(
            'create_posts' => 'do_not_allow',
        ),
        'map_meta_cap' =>true,
	);
	register_post_type( 'contactos_post_type', $args );

}
add_action( 'init', 'contactos_post_type', 0 );


// function download_contacts() {
// 	if(!current_user_can('manage_options')) {
// 		return;
// 	}

// 	global $wpdb;
// 	$host = 'localhost';
// 	$bd = 'boletin_rmx'; 
// 	$user = 'root'; 
// 	$pass = ''; 
// 	$port = '3306'; 


// 	$conn = new mysql($host, $db, $user, $pass, $port); 

// 	if($conn->connect_error) {
// 		echo 'Error en la conexión con la base de datos: ' . $conn->connect_error; 
// 		return; 
// 	}

// 	$result = $conn->query("SELECT * FROM datos_boletin"); 

// 	if($result->num_rows > 0) {
// 		$filaname = 'Suscripciones_boletin_' . date('Y-m-d') . '.csv'; 
// 		header('Content-Type: text/csv');
// 		header('Content-Disposition: attactment;filname=' . $filaname); 

// 		$output = fopen('php://output', 'w');
// 		fputcsv($output, array('#', 'correo'));
		
		
// 		while($row = $result ->fetch_assoc()) {
// 			$correo = ucwords(substr(str_replace('-', ' ', $row['correo']), 0, -5)); 
// 			fputcsv($output, array($row['id'], $correo)); 
// 		}

// 		fclose($output); 
// 	} else {
// 		wp_die('No se encontraron datos para descargar'); 
// 	}

// 	$conn->close(); 
// 	exit(); 
// }
// add_action('admin_post_download_mails', 'download_contacts'); 

?>