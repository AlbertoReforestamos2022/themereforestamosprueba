<?php
require('boletinCM.php');

if(isset($_POST['register'])) {
    // Variables entorno
    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "boletin_rmx";

    // Crear una conexión
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Verificar si hay errores en la conexión
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    // Limpiar los datos (opcional)
    $emailHTML = $_POST['correoBoletin'];
    $nombreHTML = $_POST['nombreBoletin'];
    $emailHTML = mysqli_real_escape_string($conn, $emailHTML);

    // Comprobar si los campos están vacios (nombre y correo)
    if(empty($nombreHTML)) { ?>
        <?php get_footer(); ?> 
            <?php nombreError(); ?>
            <?php contenidoEn(); ?>
        <?php
    }

    if(empty($emailHTML)) { ?>
        <?php get_footer(); ?>
            <?php correoError();?>
            <?php contenidoEn(); ?>
        <?php
    } 


    if(strlen($_POST['correoBoletin']) >= 1 ) {
        // Crear la consulta SQL para insertar los datos en la base de datos
        $consulta = "INSERT INTO datos_boletin (correo) VALUES ('$emailHTML')";
        $resultado = mysqli_query($conn,$consulta);

        if($resultado && !empty($nombreHTML)) {
                wp_insert_post([
                    'post_title'	=>'Suscripción de '. $nombreHTML,
                    'post_type'		=>'contactos_post_type',
                    'post_content'	=> $emailHTML,
                    'post_status' 	=> 'private',
                ]);
            ?>

            <?php get_footer(); ?>
                <?php mensajeEnviar(); ?>
                <?php contenidoEn(); ?>
            <?php
            
        } 
    } 
    // Cerrar la conexión
    $conn->close();

    exit;
}     
?>


