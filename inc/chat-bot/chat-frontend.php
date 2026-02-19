<?php
add_action('wp_footer', 'reforestamos_chatbot_contenido');
function reforestamos_chatbot_contenido() {
    // Obtén las opciones del chatbot
    $chabot = get_option('chatbot_theme_options'); 

    $opciones_introduccion = $chabot['reforestamos_chatbot_intro'];  

    ?> 

    <!-- Contenedor general -->
    <div class="contenedor_general_chat">
        <div class="container"> 
            <!-- Contendedor opciones -->
            <div class="container chat_introduccion" style="margin-top:200px;">
                <?php foreach($opciones_introduccion as $key => $introduccion) {
                    $contenido = wp_kses($introduccion['contenido_introduccion'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); ?> 
                    
                    <h5 class="text-info "> <?php echo $contenido; ?> </h5> <?php 
                } ?>
            </div>

            <!-- Voluntariado -->
            <?php $opciones_voluntariado = $chabot['reforestamos_chatbot_voluntariado']; ?> 
            
            <!-- Contendor voluntariado -->
            <div class="container chat_voluntariado">
                <h4 class="text-center text-light"> Voluntariado </h4>
                <?php foreach($opciones_voluntariado as $key => $voluntariado) {
                    $titulo = wp_kses($voluntariado['titulo_voluntariado'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 

                    $contenido = wp_kses($voluntariado['contenido_voluntariado'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 
                    ?> 
                    
                    <h5 class="text-success"> <?php echo $titulo;?> </h5>
                    <p class="text-info "> <?php echo $contenido; ?> </p> 
                    <p class="text-info"><?php echo $key; ?></p>
                    <?php 
                } ?>
            </div>

            <!-- Marketing con causa -->
            <?php $opciones_marketing = $chabot['reforestamos_chatbot_marketing']; ?>

            <!-- Contendor Marketing con causa -->
            <div class="container chat_marketing">
                <h4 class="text-center text-light"> Marketing con causa </h4>
                <?php foreach($opciones_marketing as $key => $marketing) {
                    $titulo = wp_kses($marketing['titulo_marketing'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 

                    $contenido = wp_kses($marketing['contenido_marketing'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 
                    ?> 
                    
                    <h5 class="text-success"> <?php echo $titulo; ?> </h5>
                    <p class="text-info "> <?php echo $contenido; ?> </p> <?php 
                } ?>
            </div>

            <!-- Adopta un árbol -->
            <?php $opciones_adopcion = $chabot['reforestamos_chatbot_adopta_arbol']; ?>

            <!-- Contendor Adopta un árbol -->
            <div class="container chat_marketing">
                <h4 class="text-center text-light"> Adopta un árbol. </h4>
                
                <?php foreach($opciones_adopcion as $key => $adopcion) {
                    $titulo = wp_kses($adopcion['titulo_adopta'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 

                    $contenido = wp_kses($adopcion['contenido_adopta'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 
                    ?> 
                    
                    <h5 class="text-success"> <?php echo $titulo; ?> </h5>
                    <p class="text-info "> <?php echo $contenido; ?> </p> <?php 
                } ?>
            </div>

            <!-- Bolsa de trabajo -->
            <?php $opciones_bolsa_trabajo = $chabot['reforestamos_chatbot_bolsa_trabajo']; ?>

            <!-- Contendor Bolsa de trabajo -->
            <div class="container chat_marketing">
                <h4 class="text-center text-light"> Bolsa de trabajo </h4>
                
                <?php foreach($opciones_bolsa_trabajo as $key => $bolsa_trabajo) {
                    $titulo = wp_kses($bolsa_trabajo['titulo_bolsa'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 

                    $contenido = wp_kses($bolsa_trabajo['contenido_bolsa'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 
                    ?> 
                    
                    <h5 class="text-success"> <?php echo $titulo; ?> </h5>
                    <p class="text-info "> <?php echo $contenido; ?> </p> <?php 
                } ?>
            </div>     
            
            <!-- Centinelas del tiempo-->
            <?php $opciones_centinelas = $chabot['reforestamos_chatbot_centinelas']; ?>

            <!-- Contendor Centinelas del tiempo -->
            <div class="container chat_marketing">
                <h4 class="text-center text-light"> Centinelas del tiempo. </h4>
                
                <?php foreach($opciones_centinelas as $key => $centinelas) {
                    $titulo = wp_kses($centinelas['titulo_centinelas'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 

                    $contenido = wp_kses($centinelas['contenido_centinelas'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 
                    ?> 
                    
                    <h5 class="text-success"> <?php echo $titulo; ?> </h5>
                    <p class="text-info "> <?php echo $contenido; ?> </p> <?php 
                } ?>
            </div>    
            
            <!-- Compra y/o venta de arboles -->
            <?php $opciones_compra_venta_arboles = $chabot['reforestamos_chatbot_compra_venta_arboles']; ?>

            <!-- Contendor Compra y/o venta de arboles -->
            <div class="container chat_marketing">
                <h4 class="text-center text-light"> Compra y/o venta de arboles. </h4>
                
                <?php foreach($opciones_compra_venta_arboles as $key => $compra) {
                    $titulo = wp_kses($compra['titulo_compra_venta_arboles'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 

                    $contenido = wp_kses($compra['contenido_compra_venta_arboles'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 
                    ?> 
                    
                    <h5 class="text-success"> <?php echo $titulo; ?> </h5>
                    <p class="text-info "> <?php echo $contenido; ?> </p> 
                <?php 
                } ?>
            </div>        
        
            <!-- Donar -->
            <?php $opciones_donar = $chabot['reforestamos_chatbot_donar']; ?>

            <!-- Contendor Donar -->
            <div class="container chat_marketing">
                <h4 class="text-center text-light"> Donar. </h4>
                
                <?php foreach($opciones_donar as $key => $donar) {
                    $titulo = wp_kses($donar['titulo_donar'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 

                    $contenido = wp_kses($donar['contenido_donar'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 
                    ?> 
                    
                    <h5 class="text-success"> <?php echo $titulo; ?> </h5>
                    <p class="text-info "> <?php echo $contenido; ?> </p> <?php 
                } ?>
            </div>  

            <!-- Contacto-->
            <?php $opciones_contacto = $chabot['reforestamos_chatbot_contacto']; ?>

            <!-- Contendor Contacto -->
            <div class="container chat_marketing">
                <h4 class="text-center text-light"> Contacto. </h4>
                
                <?php foreach($opciones_contacto as $key => $contacto) {
                    $titulo = wp_kses($contacto['titulo_contacto'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 

                    $contenido = wp_kses($contacto['contenido_contacto'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 
                    ?> 
                    
                    <h5 class="text-success"> <?php echo $titulo; ?> </h5>
                    <p class="text-info "> <?php echo $contenido; ?> </p> <?php 
                } ?>
            </div> 

            <!-- Eventos  -->
            <?php $opciones_eventos = $chabot['reforestamos_chatbot_eventos']; ?>

            <!-- Contendor Eventos -->
            <div class="container chat_marketing">
                <h4 class="text-center text-light"> Eventos. </h4>
                
                <?php foreach($opciones_eventos as $key => $evento) {
                    $titulo = wp_kses($evento['titulo_eventos'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 

                    $contenido = wp_kses($evento['contenido_eventos'], array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        // Añade más etiquetas y atributos permitidos según sea necesario
                    )); 
                    ?> 
                    
                    <h5 class="text-success"> <?php echo $titulo; ?> </h5>
                    <p class="text-info "> <?php echo $contenido; ?> </p> <?php 
                } ?>
            </div>         

        
        </div>
    </div>
    <!-- Fin contendor general -->
     
<?php
    // Registra y encola el script vacío
    wp_register_script('contenido-chatbot', false);
    wp_enqueue_script('contenido-chatbot');

    // Construye el contenido del script con las introducciones
    $script_chatbot = "        
    document.addEventListener('DOMContentLoaded', ()=>{
        console.log('Hola');

        let etiqueta_Intro_Padre = document.createElement('div');
        etiqueta_Intro_Padre.classList.add('container', 'text-center');

    "; 
    
    // Blucle forEach(); 

    // $script_chatbot .= "
    //     const contenido_Intro_$key = document.createElement('h3'); 
    //     contenido_Intro_$key.classList.add('text-center','text-light');

    //     contenido_Intro_$key.innerHTML = '$contenido';
    //     etiqueta_Intro_Padre.appendChild(contenido_Intro_$key);

    //     console.log(contenido_Intro_$key)
    //     // console.log('$contenido');\n
    // ";

    $script_chatbot .= "
        document.body.appendChild(etiqueta_Intro_Padre);
    
    /* No borrar, llaves de cierre de 'document.addEventListener' */
    });";

    // Añade el script inline
    wp_add_inline_script('contenido-chatbot', $script_chatbot);
}
?>