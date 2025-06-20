<?php
// Carga las dependencias necesarias usando Composer
require_once '../vendor/autoload.php';

// Inicia la sesión para manejar la autenticación del usuario
@session_start();

// Configuración de zona horaria y localización
date_default_timezone_set('America/Caracas');
//Idiomas
setlocale(LC_TIME, 'Spanish');

/* // Obtener el idioma preferido de la sesión o cookie */
/* $preferred_language = $_SESSION['language'] ?? $_COOKIE['language'] ?? 'es'; */
/*  */
/* // Configurar la localización según el idioma seleccionado */
/* setlocale(LC_TIME, $preferred_language === 'en' ? 'English' : 'Spanish'); */


// Configuración de cookies para mayor seguridad
$cookie_duration = 30 * 24 * 60 * 60; // Duración de 30 días
$cookie_config = [
    'expires' => time() + $cookie_duration,
    'path' => '/',
    'domain' => $_ENV['APP_DOMAIN'] ?? '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
];

// Procesamiento de la solicitud HTTP
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Normalización de la ruta
$base_path1 = "/turifadigi";
$base_path2 = "/TuRifadigi";

$preroute = str_replace($base_path2, '', $request_uri);
$route = str_replace($base_path1, '', $preroute);

switch (strtok($route, '?')) {
    case '/api/login':
        require_once '../src/API/login.php';
        break;

    case '/api/recovery_password':
        require_once '../src/API/passwordRecovery.php';
        break;
    case '/api/get_user':
        require_once '../src/API/obtener_usuarios.php';
        break;
    case '/api/session_verfication':
        require_once '../src/API/sessionVerify.php';
        break;

    case '/api/get_tickets':
        require_once '../src/API/boletosDisponibility.php';
        break;

    case '/api/change_purchase_status':
        require_once '../src/API/admin.cambiarEstadoCompra.php';
        break;

    case '/api/change_draw_status':
        require_once '../src/API/admin.cambiarEstadoSorteo.php';
        break;

    case '/api/process_purchase':
        require_once '../src/API/procesarCompra.php';
        break;

    case '/api/session_destroy':
        require_once '../src/API/destruirSesion.php';
        break;

    case '/api/get_purchase':
        $cmp = $_GET["cmp"] ?? '';
        if ($cmp != '') {
            require_once '../src/API/admin.obtenerCompras.php';
            break;
        }
        require_once '../src/API/obtenerCompras.php';
        break;
    case '/api/get_sorteo':
        $cmp = $_GET["cmp"] ?? '';
        if ($cmp != '') {
            require_once '../src/API/admin.obtenerSorteo.php';
            break;
        }
        break;
    case '/api/exchange_rate':
        require_once '../src/API/verificar.tasa.php';
        break;
    case '/api/riffle_singup':
        require_once '../src/API/registrarSorteo.php';
        break;
    case '/api/riffle_verfication':
        require_once '../src/API/userVerifySorteo.php';
        break;
    case '/api/coin_update':
        require_once '../src/API/actualizar_coin.php';
        break;
    default:
        require_once 'views/errors/404.php';
        break;
}
