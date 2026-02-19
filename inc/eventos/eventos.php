<?php
    /* Agrega el post type de Chefs Instructores */
    function reforestamos_eventos() {
        $labels = array(
            'name'                  => _x( 'Eventos', 'edc' ),
            'singular_name'         => _x( 'Evento',  'edc' ),
            'menu_name'             => _x( 'Eventos', 'Admin Menu text', 'edc' ),
            'name_admin_bar'        => _x( 'Eventos', 'Add New on Toolbar', 'edc' ),
            'add_new'               => __( 'Agregar ', 'edc' ),
            'add_new_item'          => __( 'Agregar Nuevo Evento', 'edc' ),
            'new_item'              => __( 'Nueva Evento', 'edc' ),
            'edit_item'             => __( 'Editar Evento', 'edc' ),
            'view_item'             => __( 'Ver Evento', 'edc' ),
            'all_items'             => __( 'Todos los Eventos', 'edc' ),
            'search_items'          => __( 'Buscar Eventos', 'edc' ),
            'parent_item_colon'     => __( 'Padre Eventos:', 'edc' ),
            'not_found'             => __( 'No se encontraron Eventos.', 'edc' ),
            'not_found_in_trash'    => __( 'No se encontrar Eventos en la Papelera', 'edc' ),
            'featured_image'        => _x( 'Imagen Destacada', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'edc' ),
            'set_featured_image'    => _x( 'Agregar imagen Destacada', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'edc' ),
            'remove_featured_image' => _x( 'Borrar imagen destacada', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'edc' ),
            'use_featured_image'    => _x( 'Usar Imagen destacada', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'edc' ),
            'archives'              => _x( 'Archivo de Eventos', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'edc' ),
            'insert_into_item'      => _x( 'Insertar en Eventos', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'edc' ),
            'uploaded_to_this_item' => _x( 'Cargadas En Eventos', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'edc' ),
            'filter_items_list'     => _x( 'Filtrar Lista de Eventos', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'edc' ),
            'items_list_navigation' => _x( 'Eventos navegación', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'edc' ),
            'items_list'            => _x( 'Eventos lista', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'edc' ),
        );
    
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'eventos-reforestamos' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'menu_icon'          => 'dashicons-calendar',
            // true como paginas (pueden tener hijos), false como posts (no tienen hijos)
            'hierarchical'       => false,
            'menu_position'      => 9,
            'supports'           => array('title', 'thumbnail', 'custom-fields'),
            'show_in_rest'       => true,
            'rest_base'          => 'eventos-reforestamos'
        );
    
        register_post_type( 'eventos_rmx', $args );
    }
    
    add_action( 'init', 'reforestamos_eventos' );

 ?>