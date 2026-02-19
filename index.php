<?php get_header(); ?>
    <?php 
        $pagina_blog = get_option('page_for_posts');
        $imagen_id = get_post_thumbnail_id($pagina_blog);
        $imagen_src = wp_get_attachment_image_src($imagen_id, 'full')[0];
    ?>

    <!-- Titulo-Background -->
    <section class="title-background t-n-n" style="background-image: linear-gradient(rgba(38, 64, 64, .3), rgba(38, 64, 64, .3)), url(<?php echo $imagen_src ?>);  padding: 0.1px;">
        <h1 class="text-center text-white fw-semibold title-general display-2" ><?php echo get_the_title($pagina_blog) ?></h1>
    </section>
    <!-- /. Titulo-Background -->

    <!-- Buscador -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10" style="margin-top: 50px;">
                <form class="d-flex align-items-center justify-content-center">
                    <div class="col-6 col-md-5 p-2">
                        <input class="p-1 form-control" type="search" id="table-dbf" name="palabra-clave" placeholder="Palabra clave" />
                    </div>
                    <div class="col-4 col-md-3 p-2 d-flex justify-content-center">
                        <button name="buscar-notas" type="submit" class="btn btn-outline-light w-100" id="sus">Buscar</button>
                    </div>
                    
                </form>
            </div>
        </div>
        <?php require("template-parts/buscador/buscador.php"); ?>
    </div>

    <!-- Notas Recientes -->
    <div class="container-xxl py-5 notas-principales" id="notas-principales">
        <div class="row justify-content-end ">
            <div class="col notas-content">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center notas-colum to-no-re">                 
                    <?php while(have_posts()){ the_post();  ?>
                    <div class="col px-4 px-md-2 col-nota">
                        <div class="card h-100 border-0 shadow">
                            <!--  -->
                            <?php the_post_thumbnail('mediano', array('class' => 'card-img-top p-2 img-nota'));?>
          
                          <div class="card-header border-0 bg-transparent p-3">
                                <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->    
                          </div>
          
                          <div class="card-body px-3">
                            <h5 class="card-title fw-normal"><a href="<?php the_permalink();?>" class="titulo-nota text-decoration-none"><?php the_title(); ?></a></h5>
                          </div>
          
                          <div class="card-footer border-0 bg-transparent p-3">
                            <p class="text-black-50" ><?php the_time(get_option('date_format')); ?></p>
                            <p class="text-black-50" >
                                <i class="fa fa-eye"></i>
                                <?php if(function_exists('the_views')){ the_views();} ?>
                            </p>
                          </div>
          
                        </div>
                    </div>
                    <?php } ?>
                </div>
                
                <!-- índice -->
                <div class="row mt-5">
                    <ul class="pagination justify-content-center">
                        <li>
                            <?php the_posts_pagination(
                                array(
                                    'mid_size' => 2,
                                    'prev_text' => '<',
                                    'next_text' => '>',
                                    'screen_render_text' => false,
                                    'class' => 'navegacion-notas',
                                )
                            );?>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-lg-4 border-start order-lg-last">
                <div class="mt-2 d-grid justify-content-center">

                </div>

                <div class="position-sticky p-2">
                    <h5 class="text-center text-light mb-4 t-s-b-r-s">¿Qué está pasando en nuestras redes sociales?</h5>

                    <div class="container linked-In">
                        <div class="row">
                            <div class="col d-grid justify-content-center">
                                <div class="linked-In-logo d-flex justify-content-center my-2">
                                    <h2 class="text-info">LinkedIn</h2>
                                </div>

                                <div class="facebook-content contenedor-responsivo d-grid justify-content-center">
                                    <iframe src="https://www.linkedin.com/embed/feed/update/urn:li:ugcPost:7045256137440432128" height="720" width="404" frameborder="0" allowfullscreen="" title="Publicación integrada"></iframe>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="container twitter">
                        <div class="row">
                            <div class="col">
                                <div class="twitter-logo d-flex justify-content-center my-2">
                                    <h2 style="color:#55acee;">Twitter</h2>
                                </div>

                                <div class="twitter-content contenedor-responsivo">
                                    <a class="twitter-timeline" href="https://twitter.com/ReforestamosMx?ref_src=twsrc%5Etfw">Tweets by ReforestamosMx</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container facebook">
                        <div class="row">
                            <div class="col my-2">
                                <div class="facebook-logo my-2 d-flex justify-content-center">
                                    <h2 class="text-info">Facebook</h2>
                                </div>

                                <div class="facebook-content contenedor-responsivo frame d-flex justify-content-center">
                                    <iframe class="iframe-responsivo" src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fphoto%2F%3Ffbid%3D598952955601078%26set%3Da.477706551059053&show_text=true&width=500" width="500" height="648" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- ./Notas Recientes -->


    <!-- script paginación -->
    <script>
        document.addEventListener('DOMContentLoaded', ()=> {
        const contentNav = document.querySelector('.navegacion-notas');
        contentNav.classList.add('p-3');

        const tituloNav    = document.querySelector('.navegacion-notas h2.screen-reader-text');
        tituloNav.remove();

        const itemsNav  = document.querySelectorAll('.nav-links .page-numbers');
        
        for(i=0; i < itemsNav.length; i++ ){
            const nextBtnNav = document.querySelector('.nav-links .next');
            const prevBtnNav = document.querySelector('.nav-links .prev');

            if(itemsNav[i] != nextBtnNav && itemsNav[i] != prevBtnNav) {
                itemsNav[i].classList.add('text-decoration-none', 'fw-bold', 'p-2', 'text-light');
            }
            
        }

        const currentItem = document.querySelector('.nav-links span.current');
        currentItem.setAttribute("style", "color: #a2a2a2!important;")

        const nextBtn = document.querySelector('.nav-links .next');
        nextBtn.classList.add('btn', 'btn-outline-light', 'p-2', 'fw-normal');

        const prevBtn = document.querySelector('.nav-links .prev');
        prevBtn.classList.add('btn', 'btn-outline-light', 'p-2', 'fw-normal');
    

        
        
        // tituloNav.classList.add('d-none','text-primary');
        
        
        })
    </script>
    <!-- script paginación -->


    <script>
        document.addEventListener('DOMContentLoaded', ()=> {
        const idiomaSeleccionado = localStorage.getItem("idioma");
        const tituloNuestrasNotas = document.querySelector('.t-n-n');
        const contenidoNotasRecientes = document.querySelector('.to-no-re');
        const tituloSideBar = document.querySelector('.t-s-b-r-s');
        const inputBuscador = document.querySelector('#table-dbf');
        const botonBuscador = document.querySelector('#sus');

            if(idiomaSeleccionado === 'en-US') {
                tituloNuestrasNotas.innerHTML = `
                <h1 class="text-center text-white fw-semibold title-general display-2"> Our notes </h1> `;

                contenidoNotasRecientes.innerHTML = `
                    <?php while(have_posts()) { 
                        the_post();  ?>

                        <?php $titulos_en = get_post_meta( get_the_ID(), 'nota_blog_ingles_titulo_nota_ingles', false ); ?>

                        <?php if(!empty($titulos_en)) { ?>
                            <div class="col px-4 px-md-2">
                                <div class="card h-100 border-0 shadow-sm">
                                    
                                    <!--  -->
                                    <?php the_post_thumbnail('mediano', array('class' => 'card-img-top p-2 rounded-1 shadow-sm img-nota'));?>

                                    <div class="card-header border-0 bg-transparent p-3">
                                        <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->    
                                    </div>

                                    <div class="card-body px-3">
                                        <h5 class="card-title fw-normal">
                                            <a href="<?php the_permalink();?>" class="text-decoration-none t-n">
                                                <?php 
                                                foreach ($titulos_en as $titulo)
                                                    echo esc_html($titulo);
                                                ?>
                                            </a>
                                        </h5>
                                    </div>

                                    <div class="card-footer border-0 bg-transparent p-3">
                                        <p class="text-black-50 dat-not" ><?php the_time(get_option('date_format')); ?></p>
                                        <p class="text-black-50" >
                                            <i class="fa fa-eye"></i>
                                            <?php if(function_exists('the_views')){ the_views();} ?>
                                        </p>
                                    </div>

                                </div>
                            </div> 
                        <?php } ?>
                    <?php } ?>  
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
<?php get_footer(); ?>


