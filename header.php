<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $pagina_blog = get_option('page_for_posts');?>
    <title><?php echo $pagina_blog ? get_the_title() : get_the_title($pagina_blog); ?></title>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri();?>./img/Logo-Iconos/bellota líneas.png" type="image/x-icon">
    
    <?php wp_head(); ?>

</head>
<body <?php body_class(); ?>
    <?php # Chat ?>
    <div class="bot"></div>

    <?php #Se tiene que quitar?> 
    <?php # HTML para la animación de carga  ?>
    <div class="cargando ">
        <?php // Puedes insertar tu animación aquí ?>
        <div class="spinner"></div>
    </div>


    <?php # Menú ?>
    <header class="navegacion fixed-top">
        <?php // Navegación Principal ?>
        <nav class="navbar navbar-expand-md bg-white shadow-none  menu">
            <div class="container-fluid d-flex">
                <a class="navbar-brand logo" href="<?php echo esc_url( home_url('/') ); ?>">
                    <img src="<?php echo get_template_directory_uri() ?>/img/Logo-Iconos/LOGO REFORESTAMOS FONDO TRANSPARENTE.png" class="img-fluid"  alt="Reforestamos México Logo">
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuPrincipal" aria-controls="offcanvasNavbarLight">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-end mt-0" tabindex="-1" id="menuPrincipal" aria-labelledby="offcanvasNavbarLightLabel">
                    <div class="offcanvas-header">
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    
                    <div class="d-xxl-flex justify-content-between">
                        <?php 
                            $args = array(
                                'menu_class'      => 'navbar-nav flex-grow-1 mx-auto navegacion-principal',
                                'theme_location'  => 'menu-principal',
                                'container_id'    => 'nav_principal',
                                'container_class' => 'offcanvas-body d-flex',
                                'li_class'    => 'holas321',   
                            );

                            wp_nav_menu($args);

                        ?>
                        <div class="row row-cols-1 row-cols-md-3 justify-content-center con-form-tra">
                            <!-- Traductor -->
                            <div class="col traductor mt-1 d-flex justify-content-center" style="width:110px;"></div>
                            <!-- ./Traductor -->

                            <div class="col bus-gral" style="margin-top:8px;">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    </div>

                </div> 
            </div>
        </nav>

        <?php // Navegación Donar llamdos a la acción ?>
        <?php  $color_menu = get_post_meta( get_the_ID(), 'back_incendios', true );?> 
        <?php  $color_menu_general = get_post_meta( get_the_ID(), 'back_general', true );?>
        <nav class="nav-accion <?php echo  $color_menu ?: 'bg-light' ?> shadow llamados-accion">
            <div class="container-fluid">

                <?php 
                    $args = array(
                        'menu_class'      => 'row row-cols-3 d-flex list-unstyled m-0 p-0',
                        'theme_location'  => 'menu-llamados-accion',
                        'container_id'    => 'llamados-accion-menu',
                        'add_li_class'    => 'holas-accion',   
                    );

                    wp_nav_menu($args);
                ?> 

            </div>
        </nav>
        <?php // ./Navegación Donar llamdos a la acción ?> 

        <?php // Redes Sociales?>
        <nav class="nav-rrss fixed-bottom bg-dark d-flex align-items-center justify-content-between p-1">
                <div class="nav-contacto d-flex align-items-center">

                    <div class="nav-tel mx-2 d-flex align-items-center justify-content-center">
                        <a href="tel:+525555148670">
                            <i class="bi bi-telephone" style="font-size:25px;"></i>
                        </a>

                        <a href="tel:+525555148670" class="tel ms-1 d-none d-md-flex">+52 (55) 5514 8670</a>
                    </div>

                    <div class="nav-email mx-2 d-flex align-items-center justify-content-center">
                        <a href="mailto:contacto@reforestamos.org" class="email">
                            <i class="bi bi-envelope-at" style="font-size:25px;"></i>

                        </a>


                        <a href="mailto:contacto@reforestamos.org" class="email ms-1 d-none d-md-flex">contacto@reforestamos.org</a>
                    </div>
                </div>

                <div class="nav-red-social">
                    <a class="red-social facebook ms-1" target="_blank" href="https://www.facebook.com/ReforestamosMexico/">
                        <i class="bi bi-facebook" style="font-size:20px;"></i>
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                        </svg> -->
                    </a>

                    <a class="red-social twitter ms-1 text-white" target="_blank" href="https://twitter.com/ReforestamosMx">
                        <i class="bi bi-twitter-x" style="font-size:20px;"></i>
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 512 512">
                            <style>svg{fill:#ffffff}</style>
                            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                        </svg> -->
                    </a>

                    <a class="red-social instagram ms-1" target="_blank" href="https://www.instagram.com/reforestamosmexico/">
                        <i class="bi bi-instagram" style="font-size:20px;"></i>
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                        </svg> -->
                    </a>
                    <a class="red-social linkedin ms-1" target="_blank" href="https://www.linkedin.com/company/reforestamos-mexico/">
                        <i class="bi bi-linkedin" style="font-size:20px;"></i>
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                        <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                        </svg> -->
                    </a>

                    <a class="red-social tik-tok ms-1" target="_blank" href="https://www.tiktok.com/@reforestamosmx?lang=es">
                        <i class="bi bi-tiktok" style="font-size:20px;"></i>
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-tiktok" viewBox="0 0 16 16">
                            <path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3V0Z"/>
                        </svg> -->
                    </a>
                    <a class="red-social youtube ms-1" target="_blank" href="https://www.youtube.com/ReforestamosMex">
                        <i class="bi bi-youtube" style="font-size:20px;"></i>

                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                            <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                        </svg> -->
                    </a>
                </div>
        </nav>
        <?php // ./Redes Sociales?>
    </header>
    
    <div class="margen-principal"></div>

    <div class="">
    </div>
    <?php #Menú ?>

