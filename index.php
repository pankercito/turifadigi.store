<?php
// Carga las dependencias necesarias usando Composer
require_once __DIR__ . '/vendor/autoload.php';

// Importa las clases necesarias
use Dotenv\Dotenv; // Para manejar variables de entorno
use App\Controllers\{AuthController, RegisterUserController, HomeController, BoletoController, ConfigMainController}; // Controladores principales

// Inicia la sesión para manejar la autenticación del usuario
@session_start();

// Configuración de zona horaria y localización
date_default_timezone_set('America/Caracas');
setlocale(LC_TIME, 'Spanish');

// Carga las variables de entorno desde el archivo .env
// $dotenv = Dotenv::createImmutable(__DIR__)->load();

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

if ($request_method === 'POST' && $route === '/verificarDisponibilidad') {
    (new BoletoController())->verificarDisponibilidad();
}

if ($request_method === 'GET' && strpos($route, '/boletos/obtenerBoletosPaginados') === 0) {
    header('Content-Type: application/json');
    try {
        $controller = new BoletoController();
        $controller->obtenerBoletosPaginados();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

if ($request_method === 'POST' && $route === '/reset_password') {
    (new AuthController())->resetPassword([
        'token' => $_REQUEST['token'],
        'password' => $_REQUEST['password']
    ]);
    exit;
}

if ($request_method === 'GET' && $route === '/reset-password') {
    if (!isset($_GET['token'])) {
        header('Location: /login');
        exit;
    }
    require_once 'views/auth/reset-password.php';
    exit;
}

if ($request_method === 'POST' && $route === '/registro_usuario') {
    (new RegisterUserController())->insert($_POST);
    exit;
}

if ($request_method === 'POST' && $route === '/crear_sorteo') {
    (new ConfigMainController())->crearSorteo();
    exit;
}

switch (strtok($route, '?')) {
    case '/':
        require_once 'views/main.php';
        break;

    case '/login':
        if (!isset($_SESSION['usuario'])) {
            require_once 'views/auth/login.php';
        } else {
            header("Location: /sorteo");
            exit;
        }
        break;

    case '/home':
        if (isset($_SESSION['usuario'])) {
            (new HomeController())->index();
        } else {
            header("Location: /login");
            exit;
        }
        break;

    case '/signup':
        if (!isset($_SESSION['usuario'])) {
            require_once 'views/auth/signup.php';
        } else {
            (new HomeController())->index();
        }
        break;

    case '/forgot-password':
        require_once 'views/auth/forgot-password.php';
        break;

    case '/sorteo':
            require_once 'views/rifa/sorteo.php';
        break;

    case '/rifa_config':
        require_once 'views/admin/rifa_config.php';
        break;

    case '/compras':
        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }
        require_once 'views/compras/index.php';
        break;

    //SECCION DE ADMINISTRADOR
    case '/compra_verificacion':
        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }
        require_once 'views/admin/compra.index.php';
        break;
    case '/sorteo_verificacion':
        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }
        require_once 'views/admin/sorteos.index.php';
        break;
    case '/editar_sorteo':
        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }
        require_once 'views/admin/editar_sorteo.php';
        break;

    case '/crear_sorteo':
        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }
        require_once 'views/admin/crear_sorteo.php';
        break;

    //SECCION PANTALLAS DE SWEET ALERT
    case '/compras/view/accions_view':
        require_once 'views/compras/view/vistaAcciones.php';
        break;

    case '/admin/views/sorteo/only_view':
        require_once 'views/admin/views/sorteo/vistaAcciones.php';
        break;

    case '/admin/views/compra/accions_view':
        require_once 'views/admin/views/compra/vistaAcciones.php';
        break;


    //SECCION PANTALLAS DE SWEET ALERT DE SORTEO
    case '/sorteo/view/accion_view':
        require_once 'views/sorteo/view/vistaAcciones.php';
        break;

    case '/admin/views/sorteo/accion_view':
        require_once 'views/admin/views/sorteo/vistaAcciones.php';
        break;


    //SECCIONES API
    case '/api/login':
        require_once 'src/API/login.php';
        break;

    case '/api/recovery_password':
        require_once 'src/API/passwordRecovery.php';
        break;
    case '/api/session_verfication':
        require_once 'src/API/sessionVerify.php';
        break;

    case '/api/get_tickets':
        require_once 'src/API/boletosDisponibility.php';
        break;

    case '/api/change_purchase_status':
        require_once 'src/API/admin.cambiarEstadoCompra.php';
        break;

    case '/api/change_draw_status':
        require_once 'src/API/admin.cambiarEstadoSorteo.php';
        break;

    case '/api/process_purchase':
        require_once 'src/API/procesarCompra.php';
        break;

    case '/api/session_destroy':
        require_once 'src/API/destruirSesion.php';
        break;

    case '/api/get_purchase':
        $cmp = $_GET["cmp"] ?? '';
        if ($cmp != '') {
            require_once 'src/API/admin.obtenerCompras.php';
            break;
        }
    case '/api/get_sorteo':
        $cmp = $_GET["cmp"] ?? '';
        if ($cmp != '') {
            require_once 'src/API/admin.obtenerSorteo.php';
            break;
        }
        break;
    case '/api/exchange_rate':
        require_once 'src/API/verificar.tasa.php';
        break;
    case '/api/coin_update':
        require_once 'src/API/actualizar_coin.php';
        break;

    default:
        require_once 'views/main.php';
        break;
}
