<?php
header('Content-Type: application/json');

use App\Models\Usuario;
use App\Models\DatosPersonales;


$user = new Usuario();

$nameuser = $_GET['usuario'] ?? '';

if ($nameuser == '') {
    echo json_encode(["session" => false, "msj" => "No data"]);
    exit;
} else {
    $result = $user->getByUsername($nameuser);

    if ($result !== null) {
        $personal = new DatosPersonales();
        $resultPersonal = $personal->getByUsuarioId($result['id_usuario']);
        echo json_encode([
            "success" => true,
            "user" => [
                "nombre" => $resultPersonal['nombre'],
                "apellido" => $resultPersonal['apellido'],
                "id_usuario" => $resultPersonal['id_usuario'],
                "telefono" => $resultPersonal['telefono'],
                "ubicacion" => $resultPersonal['ubicacion']
            ],
        ]);
    } else {
        echo json_encode(["success" => false, "msj" => "User not found"]);
    }
}
