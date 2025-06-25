<?php 
// header('Content-Type: application/json');

use App\Controllers\AuthController;

$ctrl = new AuthController();

$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : null;
$password_algos = isset($_POST['password']) ? $_POST['password'] : null;
$re_password = isset($_POST['re_password']) ? $_POST['re_password'] : null;

if ($usuario && $password_algos && $re_password) {
    // If all fields are filled, proceed with password recovery
    $response = $ctrl->recuperarPassword([
        'usuario' => $usuario,
        'password_algos' => $password_algos,
        're_password' => $re_password
    ]);
} else {
    // If not all fields are filled, return an error message
    $response = [
        'status' => 'error',
        'message' => 'All fields are required.'
    ];
}       

// $ctrl->recuperarPassword(['correo' => $_REQUEST['correo']]);
