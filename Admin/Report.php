<?php
// Register the admin menu
function your_plugin_add_admin_page() {
    add_menu_page(
        'Reporte de Lotes', // Page title
        'Reporte de Lotes', // Menu title
        'manage_options', // Capability required to access the page
        'reporte-de-lotes', // Menu slug (unique identifier)
        'your_plugin_admin_page_callback', // Callback function to display the page
        'dashicons-admin-generic', // Icon for the menu item
        9 // Position in the menu
    );
}
add_action('admin_menu', 'your_plugin_add_admin_page');

// Callback function to display the admin page
function your_plugin_admin_page_callback() {
    $data = ReportData::getInstance();
    $data->get_total_lotes_by_state();
    $data->get_total_lotes();
    $data->get_all_lotes();
    
    $lotes_by_state = $data->getData()['total_lotes_by_state'];
    $total_amount = $data->getData()['post_count'];
    $lotes = $data->getData()['all_lotes'];
    
    echo "
    <style>
    .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 16px;
        font-family: sans-serif;
        width: 100%;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }
    .styled-table thead tr {
        background-color: #2D6E76;
        color: #ffffff;
        text-align: left;
    }
    .styled-table th, .styled-table td {
        padding: 12px 15px;
        text-align: center;
    }
    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }
    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }
    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #2D6E76;
    }
    .button-36 {
        background-color: #2D6E76;
        border-radius: 8px;
        border-style: none;
        box-sizing: border-box;
        color: #FFFFFF;
        cursor: pointer;
        font-family: 'Inter UI','SF Pro Display',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen,Ubuntu,Cantarell,'Open Sans','Helvetica Neue',sans-serif;
        font-size: 16px;
        font-weight: 500;
        height: 4rem;
        padding: 0 1.6rem;
        text-align: center;
        text-shadow: rgba(0, 0, 0, 0.25) 0 3px 8px;
        transition: all .5s;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
    }
    .button-36:hover {
        box-shadow: rgba(80, 63, 205, 0.5) 0 1px 30px;
        transition-duration: .1s;
    }
    @media (min-width: 768px) {
        .button-36 {
            padding: 0 2.6rem;
        }
    }
    </style>
    <div class='wrap'>
        <table class='styled-table'>
            <thead>
                <tr>
                    <th>Disponibles</th>
                    <th>Reservados</th>
                    <th>Vendidos</th>
                    <th>Total de Lotes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{$lotes_by_state[0]['Disponible']}</td>
                    <td>{$lotes_by_state[1]['Reservado']}</td>
                    <td>{$lotes_by_state[2]['Vendido']}</td>
                    <td>{$total_amount}</td>
                </tr>
            </tbody>
        </table>
        <div>
            <canvas id='myChart'></canvas>
        </div>
    </div>
    <div class='wrap'>
        <table class='styled-table'>
            <thead>
                <tr>
                    <th>Lote</th>
                    <th>Bloque</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>";
            foreach ($lotes as $info) {
                echo "<tr>
                    <td>{$info->lote}</td>
                    <td>{$info->bloque}</td>
                    <td>{$info->estado}</td>
                </tr>";
            }
            echo "</tbody>
        </table>
    </div>
    <form id='excelForm'>
        <button class='button-36' type='button' id='createExcelButton'>Descargar Reporte</button>
    </form>
    <script>
        document.getElementById('createExcelButton').addEventListener('click', function () {
            var xhr = new XMLHttpRequest();
            var url = '/wp-json/lotes-excel/v1/report';
            xhr.open('GET', url, true);
            xhr.responseType = 'blob';
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var downloadLink = document.createElement('a');
                    var blob = new Blob([xhr.response], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                    var url = window.URL.createObjectURL(blob);
                    downloadLink.href = url;
                    downloadLink.download = 'reporte.xlsx';
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(downloadLink);
                }
            };
            xhr.send();
        });
    </script>
    ";
}
