<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $pagina_blog = get_option('page_for_posts');?>
    <title><?php echo $pagina_blog ? get_the_title() : get_the_title($pagina_blog); ?></title>

    <link rel="shortcut icon" href="<?php echo get_template_directory_uri()?>/Logo/icon-reforestamos.jpg" type="image/x-icon">
    <meta property="og:type" content='website'>
    <meta property="og:title" content='<?php echo the_title();?>'>
    <meta property="og:description" content='<?php echo the_excerpt() ?: "Aseguramos más y mejores bosques para el desarrollo sostenible"?>'>
    <meta property="og:image" content='<?php echo the_post_thumbnail("mediano", array("class" => "card-img-top p-2 rounded-1 shadow-sm img-nota")) ?:  get_template_directory_uri()?>/Logo/icon-reforestamos.jpg'>
    <meta property="og:url" content='<?php echo the_permalink();?>'>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@ReforestamosMx" />
    <meta name="twitter:creator" content="@ReforestamosMx" />
    <meta property="og:url" content='<?php echo the_permalink();?>' />
    <meta property="og:title" content='<?php echo the_title();?>' />
    <meta property="og:description" content='<?php echo the_excerpt() ?: "Aseguramos más y mejores bosques para el desarrollo sostenible"?>' />
    <meta property="og:image" content='<?php echo the_post_thumbnail("mediano", array("class" => "card-img-top p-2 rounded-1 shadow-sm img-nota")) ?: get_template_directory_uri()?>/Logo/icon-reforestamos.jpg' />
    <meta name="google-site-verification" content="aKJGhphGyJ7XNEdcC_CSnpk85puPFGYyFQaWCBxlfW4" />
    <?php wp_head(); ?>
</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-6DFS3T09CX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-6DFS3T09CX');

</script>

<!-- ./Google tag (gtag.js) -->

<body <?php body_class(); ?>>
    <!-- Chat -->
    <div class="bot"></div>
    <!-- ./Chat -->

    <!-- Menú -->
    <header class="navegacion">
        <!-- Navegación Principal -->
        <nav class="navbar navbar-expand-md bg-white shadow-none fixed-top menu">
            <div class="container-fluid d-flex">
                 <a class="navbar-brand logo" href="<?php echo esc_url( home_url('/') ); ?>">
                    <img src="<?php echo get_template_directory_uri() ?>/Logo/LOGO REFORESTAMOS FONDO TRANSPARENTE.png" class="img-fluid"  alt="Reforestamos México Logo">
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuPrincipal" aria-controls="offcanvasNavbarLight">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-end mt-0" tabindex="-1" id="menuPrincipal" aria-labelledby="offcanvasNavbarLightLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>

                        <div class="container-fluid ">
                            <div class="row justify-content-lg-between align-items-center align-items-lg-reverse">
                                <div class="col-lg-7 d-flex justify-content-center justify-content-lg-start">
                                    <?php 
                                        $args = array(
                                            'menu_class'      => 'navbar-nav flex-grow-1 mx-auto navegacion-principal',
                                            'theme_location'  => 'menu-principal',
                                            'container_id'    => 'nav_principal px-auto d-grid align-items-center',
                                            'container_class' => 'offcanvas-body d-flex',
                                            'li_class'    => 'holas321',   

                                        );
                                        
                                        wp_nav_menu($args);
                                    ?>                                
                                </div>

                                <div class="col-12 col-lg-5">
                                    <div class="row row-cols-1 row-cols-md-2 justify-content-center justify-content-lg-end con-form-tra">
                                        <!-- Traductor -->
                                        <div class="col traductor mt-1 d-flex justify-content-center" style="width:110px;">
                                        </div>

                                        <!-- ./Traductor -->

                                        <div class="col bus-gral mt-2" style="margin-top:8px;">
                                            <?php get_search_form(); ?>
                                        </div>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                </div> 
            </div>
        </nav>
        <!-- ./Navegación Principal -->



        <!-- Navegación Donar llamdos a la acción -->
        <?php  $color_menu = get_post_meta( get_the_ID(), 'back_incendios', true );?> 
        <?php  $color_menu_general = get_post_meta( get_the_ID(), 'back_general', true );?>
        <nav class="nav-accion <?php echo  $color_menu ?: 'bg-light' ?> llamados-accion">
            <div class="container-fluid my-2">
                <?php 
                    $args = array(
                        'menu_class'      => 'row row-cols-3 d-flex justify-content-between align-items-center list-unstyled m-0 p-0',
                        'theme_location'  => 'menu-llamados-accion',
                        'container_id'    => 'llamados-accion-menu',
                        'add_li_class'    => 'holas-accion',   
                    );
                    wp_nav_menu($args);
                ?> 
            </div>

        </nav>

        <!-- ./Navegación Donar llamdos a la acción -->



        <!-- Redes Sociales -->

        <nav class="nav-rrss fixed-bottom bg-dark d-flex align-items-center justify-content-between p-2 p-md-3">
                <div class="nav-contacto d-flex align-items-center">
                    <div class="nav-tel mx-2 d-flex align-items-center justify-content-center">
                        <a href="tel:+525555148670">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                            </svg>
                        </a>

                        <a href="tel:+525555148670" class="tel ms-1 d-none d-md-flex">+52 (55) 5514 8670</a>

                    </div>



                    <div class="nav-email mx-2 d-flex align-items-center justify-content-center">
                        <a href="mailto:contacto@reforestamos.org" class="email">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-envelope-check" viewBox="0 0 16 16">
                                <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2H2Zm3.708 6.208L1 11.105V5.383l4.708 2.825ZM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2-7-4.2Z"/>
                                <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z"/>
                            </svg>
                        </a>
                        <a href="mailto:contacto@reforestamos.org" class="email ms-2 d-none d-md-flex">contacto@reforestamos.org</a>
                    </div>

                </div>



                <div class="nav-red-social">
                    <a class="red-social facebook ms-1" target="_blank" href="https://www.facebook.com/ReforestamosMexico/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                        </svg>

                    </a>



                    <a class="red-social twitter ms-1" target="_blank" href="https://twitter.com/ReforestamosMx">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                        </svg>                    
                    </a>

                    <a class="red-social instagram ms-1" target="_blank" href="https://www.instagram.com/reforestamosmexico/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                        </svg>
                    </a>

                    <a class="red-social linkedin ms-1" target="_blank" href="https://www.linkedin.com/company/reforestamos-mexico/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                        </svg>

                    </a>



                    <a class="red-social tik-tok ms-1" target="_blank" href="https://www.tiktok.com/@reforestamosmx?lang=es">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-tiktok" viewBox="0 0 16 16">
                            <path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3V0Z"/>
                        </svg>
                    </a>

                    <a class="red-social youtube ms-1" target="_blank" href="https://www.youtube.com/ReforestamosMex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                            <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                        </svg>
                    </a>
                </div>
        </nav>

        <!-- ./Redes Sociales -->

    </header>

    <div class="margen-principal bg-light"></div>
    <!-- ./Menú -->



