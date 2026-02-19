<?php

// ID de la página que contiene los datos
$page_id = 773; // Reemplaza con el ID real de la página

// Obtén los datos del campo grupal
$recognized_states = get_post_meta($page_id, 'reforestamos_group_Ciudades_reconocidas', true);

// Inicializa el array JSON
$states_array = ['states' => []];

// Verificar si $institutions es un array válido
if (!empty($recognized_states) && is_array($recognized_states)) {
    foreach ($recognized_states as $state) {
        // Validar que cada institución tenga los datos esperados
        if (isset($state['estado_reconocido'], $state['municipio_reconocido'], $state['veces_reconocido'], $state['anios_reconocido'])) {
            $state_name = $state['estado_reconocido'];
            $location = $state['municipio_reconocido'];
            $rocognized_times = (float) $state['veces_reconocido'];
            $recognized_year = (float) $state['anios_reconocido'];

            // Agregar al array del JSON
            $states_array['recognized_states'][] = [
                'state' => $state_name,
                'location' => $location,
                'rocognized_times' => $rocognized_times,
                'recognized_year' => $recognized_year,
            ];
        }
    }
} else {
    echo 'No se encontraron datos de instituciones o el formato no es válido.';
}

$filePath = get_theme_root().'/themereforestamos/inc/json_files/recognized_states.json';
$fp = fopen($filePath, 'w');
fwrite($fp, json_encode($states_array,JSON_UNESCAPED_UNICODE));
fclose($fp);  
?>
