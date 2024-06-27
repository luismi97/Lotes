<?php
// Load PhpSpreadsheet
require_once 'composer/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create a new Spreadsheet
function generate($data) {
    $lotes = $data->getData()['all_lotes'];
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['Lote', 'Bloque', 'Estado'];
    $result = array_merge([$headers], $lotes);

    // Populate the cells with data
    foreach ($result as $rowIndex => $row) {
        foreach ($row as $columnIndex => $value) {
            $sheet->setCellValueByColumnAndRow($columnIndex + 1, $rowIndex + 1, $value);
        }
    }

    // Save the Excel file as a PHP output
    $writer = new Xlsx($spreadsheet);

    // Output the file to the browser
    $writer->save('php://output');
}
