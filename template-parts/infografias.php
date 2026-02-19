<?php  // printf('<pre>%s</pre>', var_export( get_post_custom( get_the_ID() ), true ));  ?> 
<?php  $infografias = get_post_meta( get_the_ID(), 'reforestamos_group_infografias_section_', true ); ?>

<?php
// Agrupar infografías por categoría
$infografias_por_categoria = [];

if ($infografias) {
    foreach ($infografias as $index => $infografia) {
        $categoria = $infografia['categoria'];
        if (!isset($infografias_por_categoria[$categoria])) {
            $infografias_por_categoria[$categoria] = [];
        }
        $infografias_por_categoria[$categoria][] = $infografia;
    }
}

$categorias = [
    'arboles' => 'Árboles',
    'fauna' => 'Fauna',
    'agua' => 'Agua',
    'ecosistemas' => 'Ecosistemas',
    'ciudades-Arbolado Urbano' => 'Ciudades / Arbolado Urbano',
    'incendios-forestales' => 'Incendios Forestales',
    'gobernanza-territorial' => 'Gobernanza Territorial'
];

$iconos_categorias = [
    'arboles' => 'http://localhost:8080/wordpress/wp-content/uploads/2025/06/arbolado.png',
    'fauna' => 'http://localhost:8080/wordpress/wp-content/uploads/2025/06/fauna.png',
    'agua' => 'http://localhost:8080/wordpress/wp-content/uploads/2025/06/agua.png',
    'ecosistemas' => 'http://localhost:8080/wordpress/wp-content/uploads/2025/06/ecosistema.png',
    'ciudades-Arbolado Urbano' => 'http://localhost:8080/wordpress/wp-content/uploads/2025/06/arbolado-urbano.png',
    'incendios-forestales' => 'http://localhost:8080/wordpress/wp-content/uploads/2025/06/incendios-forestales.png',
    'gobernanza-territorial' => 'http://localhost:8080/wordpress/wp-content/uploads/2025/06/gobernanza-territorial.png'
]

?>

<div class="container espacio-lineas-accion">
    <div class="container_infographics shadow rounded">
        <div class="content_infographics">
            <div class="section_filters shadow-sm">
                <!-- Filtros -->
                <div class="row justify-content-evenly align-items-center">
                    <div class="col-auto">
                        <button id="resetFilters" class="btn btn-outline-secondary">Reiniciar filtros de búsquda </button>

                    </div>
                    <?php /**  Filtro por categoria  */ ?>
                    <div class="col-auto filter_category">
                        <p class="text-center text-secondary">Buscar por categoría</p>
                        <select class="btn btn-secondary text-white" id="categoryFilter">
                            <option value="">Seleccciona una opción</option>
                            <option value="Todas">Todas las categorias</option>
                            <?php foreach ($categorias as $key => $value) { ?>
                                <option value="<?php echo sanitize_title($key); ?>"><?php echo $value; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <?php /**  Filtro por palabra clave  */ ?>
                    <div class="col-auto filter_by_word">
                        <p class="text-center text-secondary">Buscar por palabra clave</p>
                        <div class="input_filter">
                            <input type="text" id="keywordFilter">
                            <button class="btn btn-secondary text-white" id="searchButton">Buscar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="content_all_infographics">

                <div class="all_infographics" id="infographicsContainer">
                    <h3 class="text-secondary text-center title_results my-1" id="title_results"></h3>

                    <?php 
                    /** Contendor todas las infografías 
                     *  Dentro del contenedor general estan separadas las infografías por categoria
                    */
                    if($infografias) { ?>
                        <div class="row row-cols-auto row_categories_infographics d-flex justify-content-center">
                            <?php foreach($infografias_por_categoria as $categoria => $infografias){ ?>   
                                
                                    <div class="card bg-transparent border-0 shadow m-2 py-2 category_section_infographics" data-key="<?php echo sanitize_title($categoria); ?>">
                                        <div class="card-header d-flex justify-content-center bg-transparent border-0">
                                            <img src="<?php echo $iconos_categorias[$categoria]; ?>" class="img-fluid" width="100"  alt="<?php echo 'icono infografía'. $categorias[$categoria] ?>">
                                        </div>
                                        <div class="card-body bg-transparent border-0">
                                            <p class="text-secondary text-center"> <?php echo $categorias[$categoria]; ?> </p>
                                        </div>
                                        <div class="card-footer bg-transparent border-0">
                                            <button class="btn btn-secondary text-white btn-show-inforaphics"  category-id="<?php echo sanitize_title($categoria); ?>"> Ver infografías de la sección </button>
                                        </div>
                                    </div>
                            
                            <?php } ?> 
                        </div>
                        <div class="row_infographics" id="todasInfografias">
                            <?php
                            /** Secciones */ 
                            foreach($infografias_por_categoria as $categoria => $infografias){ ?> 
                                <div class="category-group my-3" data-category="<?php echo sanitize_title($categoria); ?>">

                                    <div class="row justify-content-evenly">
                                        <div class="col d-flex justify-content-center titulo-categoria">
                                            <h3 class="text-success"><?php echo $categorias[$categoria]; ?> </h3>
                                        </div>

                                    </div>
                                    <div class="row justify-content-center infographics">
                                        <?php
                                        /** Infografias */
                                        foreach($infografias as $index => $infografia) {
                                         $infografia_id = sanitize_title($infografia['nombre_especie'] . $index); ?>

                                            <div class="col-auto col_infographics my-3" data-category="<?php echo sanitize_title($categoria); ?>" data-name="<?php echo strtolower(sanitize_title($infografia['nombre_especie'])); ?>">

                                                <div class="card card_infographics border-0 shadow rounded">
                                                    <div class="card-body d-flex justify-content-center">
                                                        <img src="<?php echo $infografia['url_imagen']?>" width="150" class="img-fluid" alt="<?php echo $infografia['nombre_especie']; ?>">
                                                    </div>

                                                    <div class="card-footer d-flex justify-content-center border-0 bg-transparent">
                                                        <button class="btn btn-secondary text-white" data-bs-toggle="modal" data-bs-target="#<?php echo $infografia_id; ?>Modal">Ver infografía</button>
                                                    </div>
                                                </div>

                                                <?php /** Modal infografías */ ?> 
                                                <div class="modal fade" id="<?php echo $infografia_id; ?>Modal" tabindex="-1" aria-labelledby="<?php echo $infografia_id; ?>ModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header justify-content-center">
                                                                    <h1 class="text-center text-light h4" id="<?php echo $infografia_id; ?>ModalLabel"><?php echo $infografia['nombre_especie']; ?></h1>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="card border-0">
                                                                        <div class="card-body d-flex justify-content-center">
                                                                            <img src="<?php echo $infografia['url_imagen']?>" class="img-fluid shadow imagen-descargar" width="500" id="<?php echo $infografia_id; ?>-image-download" alt="<?php echo $infografia['nombre_especie']; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="modal-footer justify-content-center">
                                                                    <button type="button" class="btn btn-outline-info descargar_img" id="<?php echo $infografia_id; ?>-image-download">Descargar imagen</button>
                                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="row row-cols-1 justify-content-center g-2">
                                        <div class="col-auto d-flex justify-content-center">
                                            <?php /** Botón ver más */ ?> 
                                            <button class="btn btn-outline-success toggle-btn d-none mx-auto my-2">Ver más</button>
                                        </div>

                                        <?php /** btn volver a las categorías */ ?>
                                        <div class="col-auto d-flex justify-content-center text-center d-none" id="btnVolverCategoriasContainer">
                                            <button class="btn btn-outline-danger mx-auto my-2" id="btnVolverCategorias">Regresar a todas la categorías</button>
                                        </div>

                                    </div>
                                    

                                </div>                               
                            <?php   
                            }
                            ?> 
                        </div>
        
                        <div class="row_results row d-flex justify-content-center"></div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


