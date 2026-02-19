<?php

// ID de la página que contiene los datos
$page_id = 810; // Reemplaza con el ID real de la página

// Obtén los datos del campo grupal
$files = get_post_meta($page_id, 'reforestamos_group_que_hacemos', true);

// Inicializa el array JSON
$files_array = ['files' => []];

// Verificar si $institutions es un array válido
if (!empty($files) && is_array($files)) {
    foreach ($files as $file) {
        // Validar que cada institución tenga los datos esperados       
        if (isset($file['seccion_documento'], $file['titulo_pop_up'], $file['documento_memoria'], $file['fecha_memoria'])) {
            // nombre file 
            $name = $file['titulo_pop_up'];
            // sección file
            $section = $file['seccion_documento'];
            // fecha file - memoria 
            $date = (float) $file['fecha_memoria'];
            // link file - memoria
            $link = $file['documento_memoria'];

            // Agregar al array del JSON
            $files_array['files'][] = [
                'name'  => $name,
                'section'  => $section,
                'date'   => $date,
                'link' => $link,
            ];
        }
    }
} else {
    echo 'No se encontraron documentos o el formato no es válido.';
}

$filePath = get_theme_root().'/themereforestamos/inc/json_files/dates_files_section.json';
$fp = fopen($filePath, 'w');
fwrite($fp, json_encode($files_array,JSON_UNESCAPED_UNICODE));
fclose($fp);  


// Convertir a JSON
// $institutions_json = json_encode($institutions_array, JSON_PRETTY_PRINT);

// // Ruta del archivo JSON
// $json_file_path = get_template_directory() . '/inc/json_files/institutions.json';

// // Crear el archivo JSON
// if (file_put_contents($json_file_path, $institutions_json)) {
//     echo 'Archivo JSON generado correctamente: ' . $json_file_path;
// } else {
//     echo 'No se pudo crear el archivo JSON.';
// }

// Mostrar el JSON en el frontend (opcional para depuración)
// echo '<pre>' . $institutions_array . '</pre>';
?>

