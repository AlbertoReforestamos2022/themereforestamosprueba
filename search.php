<?php get_header(); ?>

    <div class="container ">
        <div class="row">
            <div class="col ti-re">
                <h1 class="text-success text-center">Resultados de la busqueda</h1>
            </div>
        </div>
    </div>

    <div class="search-page container">
        <div class="row row-cols-1 row-cols-md-4 re-bus">
            <?php while(have_posts()): the_post();  ?>

            <?php 
                if ( !isset( $post_type ) || $post_type !== get_post_type()  ) { ?>
                    <div class="row row-cols-md-2 row-cols-lg-4 justify-content-center"> 
                        <div class="col px-4 px-md-2">
                            <div class="card h-100 border-0 shadow-sm">
                                <!--  -->
                                <?php the_post_thumbnail('mediano', array('class' => 'card-img-top p-2 rounded-1 shadow-sm img-nota'));?>
            
                            <div class="card-header border-0 bg-transparent p-3">

                            </div>
            
                            <div class="card-body px-3">
                                <h4 class="card-title fw-normal"><a href="<?php the_permalink();?>" class="text-decoration-none text-light"><?php the_title(); ?></a></h4>
                            </div>
            
                            </div>
                        </div>
                    </div> 
                <?php }
                    $post_type = get_post_type();
                    // Fin de codigo
                    
                    get_template_part( 'content', get_post_format() );
            ?>

            <?php endwhile; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', ()=> {
        const idiomaSeleccionado = localStorage.getItem("idioma");

        // titulo Resultados
        const titResul = document.querySelector('.ti-re');
        
        // Contenido resultados
        const resultadosBusqueda = document.querySelector('.re-bus');

            if(idiomaSeleccionado === 'en-US') {
                titResul.innerHTML = `
                <h1 class="text-success text-center">Search results</h1>`;

                resultadosBusqueda.innerHTML = `
                <?php 
                if ( have_posts() ) {
                    while ( have_posts() ) : the_post();
                    $paginas_a_mostrar = array( 'nosotros', 'que-hacemos', 'empresas', 'organizaciones-de-la-sociedad-civi', 'gobierno', 'documentos','informes-anuales' , 'materiales-de-interes', 'infografias', 'contacto', 'adopta-un-arbol' );

                        // Verificar si la publicación no es una nota de blog
                        $tituloPrincipal = get_post_meta( get_the_ID(), 'titulo_general_en', true );
                        if ( get_post_type() == 'page' && in_array( $post->post_name, $paginas_a_mostrar) ) {
                        ?>    
                            <div class="col px-4 px-md-2">
                                </div>

                                <div class="card-body px-3">
                                    <h4 class="card-title fw-normal"><a href="<?php the_permalink();?>" class="text-decoration-none text-light"><?php echo $tituloPrincipal; ?></a></h4>
                                </div>

                                </div>
                            </div>
                        <?php    
                        } else {
                            // Si es una nota de blog, mostrar solo el título y el enlace
                            echo ' ';
                        } ?>
                    <?php 
                    endwhile;
                } else {
                    // Si no hay resultados de búsqueda
                    echo 'Lo siento, no se encontraron resultados.';
                } ?> 
                `;

                // Fecha Notas 
                let fechaNotas = document.querySelectorAll('.dat-not');
                let fechaOBJ = {}

                function traducirMeses(fecha) {
                    // Mapa de nombres de meses en español a inglés
                    const mesesES = ['enero,', 'febrero,', 'marzo,', 'abril,', 'mayo,', 'junio,', 'julio,', 'agosto,', 'septiembre,', 'octubre,', 'noviembre,', 'diciembre,'];
                    const mesesEN = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                    // Separar la cadena de fecha en día, mes y año
                    const partesFecha = fecha.split(' ');
                    const dia = partesFecha[0];
                    const mes = partesFecha[1];
                    const anio = partesFecha[2];
                
                    // Traducir el nombre del mes si está en español
                    const indice = mesesES.findIndex((mesEspanol) => mesEspanol.toLowerCase() === mes.toLowerCase());
                    const mesTraducido = indice !== -1 ? mesesEN[indice] : mes;

                    // Formar la nueva cadena de fecha con el nombre del mes traducido en la posición deseada
                    const nuevaFecha = mesTraducido + ' ' + dia + ', ' + anio;

                    return nuevaFecha;
                }       

                fechaNotas.forEach((fecha) => {
                    let fechaEnEspañol = fecha.textContent;
                    let fechaTraducida = traducirMeses(fechaEnEspañol);

                    fecha.textContent = fechaTraducida;      
                })

                tituloSideBar.textContent = ` What's happening in our social networks?`;
                // buscador 
                inputBuscador.placeholder = "keyword"
                botonBuscador.value = "Search";
                botonBuscador.textContent = "Search"; 
            }
        })
    </script>
<?php get_footer();?>





