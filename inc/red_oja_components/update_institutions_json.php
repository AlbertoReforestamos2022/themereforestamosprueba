<?php

// ID de la página que contiene los datos
$page_id = 810; // Reemplaza con el ID real de la página

// Obtén los datos de los CMB Institutions
$institutions = get_post_meta($page_id, 'reforestamos_group_institutions_group', true);

// Obtenemos los datos de los CMB Files
$files = get_post_meta($page_id, 'reforestamos_group_que_hacemos', true);


// Arrays organizaciones aliadas
$institutions_array = ['institutions' => []];

// Array documentos
$files_array = ['files' => []];


// Verificar si $institutions es un array válido
if (!empty($institutions) && is_array($institutions)) {
    foreach ($institutions as $institution) {
        // Validar que cada institución tenga los datos esperados
        if (isset($institution['institution_name'], $institution['institution_lat'], $institution['institution_lng'], $institution['institution_state'])) {
            $name = $institution['institution_name'];
            $logo = $institution['institution_logo'];
            $lat = (float) $institution['institution_lat'];
            $lng = (float) $institution['institution_lng'];
            $state = $institution['institution_state'];
            $location = $institution['institution_location'];
            $country = $institution['institution_country'];
            $link = $institution['institution_link'];

            // Agregar al array del JSON
            $institutions_array['institutions'][] = [
                'name'  => $name,
                'logo'  => $logo,
                'lat'   => $lat,
                'lng'   => $lng,
                'state' => $state,
                'location' => $location,
                'country' => $country,
                'link' => $link,
            ];
        }
    }
} else {
    echo 'No se encontraron datos de instituciones o el formato no es válido.';
}

// Verificar si $files es un array válido
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


// Guardar información $institutions en un JSON
$filePathInstotutions = get_theme_root().'/themereforestamos/inc/json_files/institutions.json';
$fpInstitutions = fopen($filePathInstotutions, 'w');
fwrite($fpInstitutions, json_encode($institutions_array,JSON_UNESCAPED_UNICODE));
fclose($fpInstitutions);  

// Guardar la inforación de $files en un JSON 
$filePathFiles = get_theme_root().'/themereforestamos/inc/json_files/dates_files_section.json';
$fpFiles = fopen($filePathFiles, 'w');
fwrite($fpFiles, json_encode($files_array,JSON_UNESCAPED_UNICODE));
fclose($fpFiles); 

?>
