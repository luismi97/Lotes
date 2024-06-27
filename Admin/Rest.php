<?php

// Registrar la ruta de la API REST para obtener los lotes
function register_lotes_routes() {
    register_rest_route('lotes/v1', '/report', [
        'methods'  => 'GET',
        'callback' => 'get_lotes_callback',
    ]);

    register_rest_route('lotes-excel/v1', '/report', [
        'methods'  => 'GET',
        'callback' => 'generate_report_callback',
    ]);
}
add_action('rest_api_init', 'register_lotes_routes');

// Callback para obtener los lotes por estado y bloque
function get_lotes_callback() {
    $data = ReportData::getInstance();
    $data::get_total_lotes_by_state_by_block();
    $resp = $data->getData()['total_lotes_by_state_by_block'];

    return rest_ensure_response($resp);
}

// Callback para generar el reporte de lotes en Excel
function generate_report_callback() {
    $data = ReportData::getInstance();
    $data::get_all_lotes();

    generate($data);

    return rest_ensure_response('ok');
}
