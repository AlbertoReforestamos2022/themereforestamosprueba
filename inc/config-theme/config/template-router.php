<?php
// Detectamos el tipo de template y cargamos partials
if ( is_front_page() || is_home() ) {
    get_template_part('template-parts/front', 'page'); 

    // Contenido en inglés 
    
} elseif ( is_singular('post') ) {
    // Nuestras notas
    get_template_part('template', 'parts/index');

    // contenido en ingles 

} elseif ( is_page_template('template-parts/nosotros.php') ) {

    // Sobre Nosotros
    get_template_part('template', 'parts/nosotros'); 

    // Contenido en inglés
        get_template_part('template-parts/content-en/nosotros', 'en'); 

} elseif ( is_page_template('template-parts/que-hacemos') ) {

    // ¿Qué hacemos? 
    get_template_part('template-parts', '/que-hacemos'); 

    // Contenido en inglés
    get_template_part('template-parts/content-en/quehacemos', 'en'); 


} elseif (is_page_template('template-parts/organizaciones.php') ) {

    // Aliados
    get_template_part('template', 'parts/organizaciones'); 

    // Contenido en inglés
    get_template_part('template-parts/content-en/aliados', 'en'); 
    
} elseif (is_page_template('template-parts/documentos.php') ) {
    // Agregar CPT 
    // Empresas
    get_template_part('template', 'parts/documentos'); 
    // Contenido en inglés 

} elseif (is_page_template('template-parts/informes-anuales.php') ) {

    // Informes anuales
    get_template_part('template-parts', 'informes-anuales'); 

    /** Contenido en inglés */
    get_template_part('template-parts/content-en/informesAnuales', 'en'); 

}  elseif (is_page_template('template-parts/materiales-interes.php') ) {

    // Materiales de interés
    get_template_part('template-parts', '/materiales-interes'); 

    // Contenido inglés
    // pendiente

}  elseif (is_page_template('template-parts/infografias') ) {

    // Infografías
    get_template_part('template', 'parts/infografias'); 

    // contenido en inglés
    // pendiente

}  elseif (is_page_template('template-parts/informes-anuales.php') ) {

    // Recursos - Informes anuales
    get_template_part('template-parts', 'informes-anuales'); 
    
    /** Contenido en inglés */
    get_template_part('template-parts/content-en/informesAnuales', 'en'); 

}  elseif (is_page_template('template-parts/bolsa-trabajo-content.php') ) {

    // Bolsa de trabajo
    get_template_part('template-parts/bolsa', 'trabajo-content'); 
    
    // pendiente


}  elseif (is_page_template('template-parts/contacto.php') ) {

    // Contacto
    get_template_part('template', 'parts/contacto'); 
    
    get_template_part('template-parts/content-en/contacto', 'en'); 

} elseif (is_page_template('template-parts/boletin.php')){

    // Boletín 
    get_template_part('template', 'part/boletin'); 

    // pendiente

} elseif (is_page_template('template-parts/donar.php')) {

    // Donar
    get_template_part('template', 'part/donar'); 

    // Contenido en Inglés
    get_template_part('template-parts/content-en/donar', 'en'); 


} elseif(is_page_template('template-parts/adopta-arbol.php')) {

    // Adoptar un Árbol 
    get_template_part('template-part', 'adopta-arbol'); 

    // Contenido en Inglés
    get_template_part('template-parts/content-en/adoptaArbol', 'en'); 


} elseif(is_page_template('template-parts/incendios-forestales.php')) {

    // Incendios Forestales
    get_template_part('template-part', 'incendios-forestales'); 

    // Contenido en Inglés
    // pendiente

} else {

    // front page 
    get_template_part('template-parts/front', 'page'); 

}


// Agregar el Título
// agrergar el contenido del buscador, 
// Eliminamos sidebar 


/**
 * $array_templates = [
 *      'page_template' => 'name_page_template'
 *      'get_template'  =>  'name template 
 * ]; 
 * 
 * 
 * 
 * function select_template($page_template, $get_template) {
 *      if( $page_template) {
 *          $get_template
 *      }
 * 
 * }
 * 
 */
















