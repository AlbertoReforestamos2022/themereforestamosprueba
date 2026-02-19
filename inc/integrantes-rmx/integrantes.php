<?php
/* Agrega el post type de los integrantes de reforestamos */
function reforestamos_postype_integrantes() {
     $labels = array(
         'name'                  => _x( 'Integrantes', 'edc' ),
         'singular_name'         => _x( 'Integrante',  'edc' ),
         'menu_name'             => _x( 'Integrantes', 'Admin Menu text', 'edc' ),
         'name_admin_bar'        => _x( 'Integrantes', 'Add New on Toolbar', 'edc' ),
         'add_new'               => __( 'Agregar ', 'edc' ),
         'add_new_item'          => __( 'Agregar Nuevo Integrante', 'edc' ),
         'new_item'              => __( 'Nueva Integrante', 'edc' ),
         'edit_item'             => __( 'Editar Integrante', 'edc' ),
         'view_item'             => __( 'Ver Integrante', 'edc' ),
         'all_items'             => __( 'Todos los Integrantes', 'edc' ),
         'search_items'          => __( 'Buscar Integrantes', 'edc' ),
         'parent_item_colon'     => __( 'Padre Integrantes:', 'edc' ),
         'not_found'             => __( 'No se encontraron Integrantes.', 'edc' ),
         'not_found_in_trash'    => __( 'No se encontrar Integrantes en la Papelera', 'edc' ),
         'featured_image'        => _x( 'Imagen Destacada', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'edc' ),
         'set_featured_image'    => _x( 'Agregar imagen Destacada', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'edc' ),
         'remove_featured_image' => _x( 'Borrar imagen destacada', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'edc' ),
         'use_featured_image'    => _x( 'Usar Imagen destacada', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'edc' ),
         'archives'              => _x( 'Archivo de Integrantes', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'edc' ),
         'insert_into_item'      => _x( 'Insertar en Integrantes', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'edc' ),
         'uploaded_to_this_item' => _x( 'Cargadas En Integrantes', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'edc' ),
         'filter_items_list'     => _x( 'Filtrar Lista de Integrantes', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'edc' ),
         'items_list_navigation' => _x( 'Integrantes navegación', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'edc' ),
         'items_list'            => _x( 'Integrantes lista', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'edc' ),
     );
  
     $args = array(
         'labels'             => $labels,
         'public'             => true,
         'publicly_queryable' => true,
         'show_ui'            => true,
         'show_in_menu'       => true,
         'query_var'          => true,
         'rewrite'            => array( 'slug' => 'integrantes-reforestamos' ),
         'capability_type'    => 'post',
         'has_archive'        => true,
         'menu_icon'          => 'dashicons-groups',
         // true como paginas (pueden tener hijos), false como posts (no tienen hijos)
         'hierarchical'       => false,
         'menu_position'      => 8,
         'supports'           => array('title', 'thumbnail', 'custom-fields'),
         'show_in_rest'       => true,
         'rest_base'          => 'integrantes-reforestamos'
     );
  
     register_post_type( 'integrantes_rmx', $args );
 }
  
 add_action( 'init', 'reforestamos_postype_integrantes' );

 ?>