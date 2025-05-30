<?php
header('Content-Type: application/json');

use App\Controllers\ConfigMainController;

$get = $_GET['est'];
$id = $_GET['id'];

if ($get == 'disabled') {
    // 1. Obtener el cuerpo de la solicitud JSON crudo
    //    php://input es un stream de solo lectura que permite acceder
    //    a los datos crudos del cuerpo de la solicitud.
    $json_data = file_get_contents('php://input');

    // 2. Decodificar el JSON a un array asociativo de PHP
    //    El segundo parámetro 'true' hace que json_decode devuelva un array asociativo.
    //    Si lo dejas en 'false' (o lo omites), devolverá un objeto stdClass.
    $data = json_decode($json_data, true);

    // 3. Verificar si la decodificación fue exitosa y si los datos existen
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        // Hubo un error al decodificar el JSON
        header('Content-Type: application/json');
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid JSON received', 'json_error' => json_last_error_msg()]);
        exit();
    }

    $boletos = [$data['boleto1'],  $data['boleto2'],  $data['boleto3']];

    $rut = new ConfigMainController();

    $ext = $rut->actualizarSorteo($id, 2, $boletos);
    echo json_encode($ext);
    exit;
}

if ($get == 'enabled') {
    $rut = new ConfigMainController();

    $ext = $rut->actualizarSorteo($id, 1);
    echo json_encode($ext);
    exit;
}
