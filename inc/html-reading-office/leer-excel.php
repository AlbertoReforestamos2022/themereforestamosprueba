<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

function obtener_datos_excel($ruta_archivo) {
    if (!file_exists($ruta_archivo)) {
        return false;
    }

    // // $ruta_archivo = './sampleData/example1.xls';

    // /**
    //  * Identify the type of $ruta_archivo.
    //  * See below for a possible improvement for release 4.1.0+.
    //  */
    // $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($ruta_archivo);
    // /**  Create a new Reader of the type that has been identified  **/
    // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    // /**  Load $ruta_archivo to a Spreadsheet Object  **/
    // $spreadsheet = $reader->load($ruta_archivo);


    try {
        $spreadsheet = IOFactory::load($ruta_archivo);
        $hoja = $spreadsheet->getActiveSheet();
        return $hoja->toArray();
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
}


?>