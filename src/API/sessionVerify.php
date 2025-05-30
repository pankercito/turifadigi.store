<?php
header('Content-Type: application/json');

@session_start();

use App\Models\Usuario;
use App\Models\DatosPersonales;

$sesion = $_SESSION['usuario'] ?? '';
$t = $_GET['t'] ?? '';

try {
    if ($sesion == '') {
        echo json_encode(["session" => false]);
        exit;
    } else {

        $usuario = new Usuario();
        $username = $_SESSION['usuario'];

        $result = $usuario->getByUsername($username);

        if ($result !== null) {
            // VERIFICA SI SE REQUIEREN DATOS PERSONALES
            if ($t) {
                $personal = new DatosPersonales();

                $result = $personal->getByUsuarioId($result['id_usuario']);
                echo json_encode([
                    "session" => true,
                    "user" => [
                        "id_usuario" => $result['id_usuario'],
                    ],
                ]);
                exit;
            }
            echo json_encode([
                "session" => true
            ]);
        } else {
            echo json_encode(["session" => false]);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
