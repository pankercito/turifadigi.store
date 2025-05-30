<?php

header('Content-Type: application/json');

use App\Controllers\RegisterUserController;
use App\Models\Usuario; // Asegúrate de que esta clase tenga un método para verificar la existencia del usuario

// Función para generar un nombre de usuario único
function generarNombreUsuarioUnico(string $nombre, string $apellido, Usuario $controladorUser): string
{
    // 1. Limpiar y convertir a minúsculas, permitiendo solo letras, números y guiones bajos (ahora '_')
    // El patrón se ajusta para permitir letras, números y guiones bajos (aunque no los introduces explícitamente, es buena práctica)
    $nombre_limpio = strtolower(preg_replace('/[^a-z0-9_]/i', '', $nombre));
    $apellido_limpio = strtolower(preg_replace('/[^a-z0-9_]/i', '', $apellido));

    // 2. Determinar la longitud del apellido a usar
    // Longitud máxima para el nombre_usuario completo (ej. 20 caracteres)
    $max_longitud_usuario_total = 20; // Puedes ajustar este valor
    $max_longitud_apellido = 4; // Máximo 4 primeras letras del apellido

    // Calcular la longitud disponible para el apellido (nombre + punto + 'X' caracteres apellido)
    $longitud_nombre = strlen($nombre_limpio);
    $longitud_disponible_apellido = $max_longitud_usuario_total - ($longitud_nombre + 1); // +1 por el '.'

    // Asegurarse de que la longitud del apellido no exceda el máximo global ni el específico del apellido
    $longitud_apellido_a_usar = min($max_longitud_apellido, $longitud_disponible_apellido);

    // Si el nombre ya es muy largo, quizás no agreguemos apellido o solo 1-2 chars
    if ($longitud_apellido_a_usar <= 0) {
        $apellido_reducido = '';
    } else {
        $apellido_reducido = substr($apellido_limpio, 0, $longitud_apellido_a_usar);
    }

    // 3. Generar el nombre de usuario base
    // Usamos '_' en lugar de '.' para seguir la regla de '_-' como caracteres especiales permitidos
    $nombre_usuario_base = $nombre_limpio . '_' . $apellido_reducido;

    // Eliminar guiones bajos duplicados o al final/inicio si se generan por limpieza
    $nombre_usuario_base = preg_replace('/_+/', '_', $nombre_usuario_base); // Reemplaza múltiples '_' por uno solo
    $nombre_usuario_base = trim($nombre_usuario_base, '_'); // Elimina '_' al inicio o final

    // Si por alguna razón el nombre de usuario base queda vacío después de la limpieza
    if (empty($nombre_usuario_base)) {
        // Podrías generar uno aleatorio o usar un prefijo por defecto
        $nombre_usuario_base = 'user_' . uniqid();
    }


    $nombre_usuario = $nombre_usuario_base;
    $contador = 1;

    // 4. Bucle para verificar la unicidad en la base de datos
    // Suponemos que $controladorUser->existeUsuario($nombre_usuario) devuelve true si existe, false si no.
    while ($controladorUser->existeUsuario($nombre_usuario)) {
        // Si el nombre de usuario ya existe, añadimos un número al final
        $nombre_usuario = $nombre_usuario_base . $contador;
        $contador++;

        // Opcional: poner un límite al contador para evitar bucles infinitos en casos extremos
        // if ($contador > 100) {
        //     // Podrías lanzar una excepción o generar un usuario con un UUID para casos raros
        //     throw new Exception("No se pudo generar un nombre de usuario único después de muchos intentos.");
        // }
    }

    return $nombre_usuario;
}

// 1. Obtener el contenido bruto del cuerpo de la solicitud
$json_data = file_get_contents('php://input');

// 2. Decodificar el JSON a un array asociativo
$request_data = json_decode($json_data, true);

// 3. Verificar si la decodificación fue exitosa y si hay datos
if ($request_data === null && json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'msj' => 'JSON mal formado: ' . json_last_error_msg()]);
    exit();
}

// 4. Ahora, usa $request_data para obtener los datos
$nombre = $request_data['nombre'] ?? null;
$apellido = $request_data['apellido'] ?? null;
$prefijo_pais = $request_data['prefijo_pais'] ?? null;
$telefono = $request_data['telefono'] ?? null;


$campos_requeridos = [
    'nombre' => 'Falta el nombre',
    'apellido' => 'Falta el apellido',
    'prefijo_pais' => 'Falta el prefijo del país',
    'telefono' => 'Falta el teléfono'
];

// Validar que los campos existan y no estén vacíos
foreach ($campos_requeridos as $campo => $mensaje) {
    if (!isset($request_data[$campo]) || empty(trim($request_data[$campo]))) { // Usamos trim() para considerar espacios vacíos como vacíos
        echo json_encode(['success' => false, 'msj' => $mensaje]);
        return;
    }
}

// Inicializar controladores (asumiendo que están configurados para la DB)
$controladorRegistro = new RegisterUserController();
$controladorUser = new Usuario(); // Esta clase es la que tiene el método existeUsuario

// --- AHORA LLAMAMOS A LA NUEVA FUNCIÓN PARA GENERAR EL NOMBRE DE USUARIO ---
$nombre_usuario = generarNombreUsuarioUnico($nombre, $apellido, $controladorUser);
// --- FIN DE LA LLAMADA A LA FUNCIÓN ---

// Después de generar el nombre de usuario (que ya es único o se intentó hacerlo),
// ya no es necesario el 'if ($controladorUser->existeUsuario($nombre_usuario))' aquí
// porque la función 'generarNombreUsuarioUnico' ya se encargó de la unicidad.

// Si la función generarNombreUsuarioUnico lanzara una excepción en caso de no poder generar uno,
// la capturarías aquí. Por ahora, asumimos que siempre devuelve uno válido y único.

// Aquí iría la lógica para registrar al usuario con $nombre_usuario y $telefono, etc.
// Por ejemplo: $controladorRegistro->registrar($nombre_usuario, $password_hash, ...);

$data = [
    "usuario" => $nombre_usuario,
    "password" => $telefono, // RECUERDA: NO GUARDAR CONTRASEÑAS EN TEXTO PLANO. Hashear antes de guardar.
    "nombre" => $nombre,
    "apellido" => $apellido,
    "telefono" => str_replace('+', '', $prefijo_pais . $telefono),
    "ubicacion" => "sin direccion establecida"
];

$resultado = $controladorRegistro->insert($data);

// if ($resultado && isset($resultado['success']) && $resultado['success'] == true) {
//     // Registro exitoso
//     echo json_encode([
//         "success" => true,
//         "data" => $data
//     ]);
// } else {
//     http_response_code(201);
//     // Error en el registro
//     $mensaje_error = isset($resultado['message']) ? $resultado['message'] : 'Error al registrar el usuario.';
//     echo json_encode([
//         "success" => false,
//         "msj" => $mensaje_error
//     ]);
// }
