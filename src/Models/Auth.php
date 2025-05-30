<?php

namespace App\Models;

use App\config\Conexion;
use App\Controllers\CorreoController;
use App\Controllers\CriptografiaController;
use Exception;

class Auth
{
  // Constantes para las tablas y columnas
  const TABLE_NAME = 'usuarios';
  const COLUMN_ID = 'id_usuario';
  const COLUMN_NAME = 'usuario';
  const COLUMN_PASSWORD = 'password';
  const COLUMN_PHONE = 'telefono_usuario';

  // Códigos de estado de login
  const LOGIN_SUCCESS = 1;
  const ERROR_INVALID_CREDENTIALS = 2;
  const ERROR_DATABASE = 3;
  const ERROR_INVALID_DATA = 4;

  // Mensajes de respuesta
  const MESSAGE_LOGIN_SUCCESS = 'Inicio de sesión exitoso';
  const MESSAGE_INVALID_CREDENTIALS = 'Credenciales inválidas';
  const MESSAGE_DATABASE_ERROR = 'Error al procesar la solicitud en la base de datos';
  const MESSAGE_INVALID_DATA = 'Datos inválidos o incompletos';

  private $db;
  private $ctf;

  public function __construct()
  {
    $this->db = new Conexion();
    $this->ctf = new CriptografiaController();
  }

  public function login(array $request): int
  {
    try {
      // Debug para ver los datos recibidos
      error_log('Datos recibidos: ' . json_encode($request));

      // Validar datos requeridos
      if (empty($request['usuario']) || empty($request['password'])) {
        return self::ERROR_INVALID_DATA;
      }

      $request;

      // Consulta SQL para validar solo por usuario
      $sql = "SELECT * FROM usuarios WHERE 
             usuario = :usuario";

      // Sanitize the input to prevent HTML, script, and SQL injection
      $request['usuario'] = filter_var($request['usuario'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $request['usuario'] = strip_tags($request['usuario']); // Remove HTML tags
      $request['usuario'] = htmlspecialchars($request['usuario'], ENT_QUOTES, 'UTF-8'); // Escape HTML entities


      // Sanitize the input to prevent HTML, script, and SQL injection
      $request['password'] = filter_var($request['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $request['password'] = strip_tags($request['password']); // Remove HTML tags
      $request['password'] = htmlspecialchars($request['password'], ENT_QUOTES, 'UTF-8'); // Escape HTML entities

      $params = [
        ':usuario' => trim($request['usuario']),
      ];

      // Debug de la consulta
      error_log('SQL Query: ' . $sql);
      error_log('Params: ' . json_encode($params));

      $result = $this->db->consultar($sql, $params);

      // Debug del resultado
      error_log('Query result: ' . json_encode($result));

      if ($result && count($result) > 0) {
        $lomo = $result[0]["password"];

        if (password_verify($request['password'], $lomo) == false) {
          return self::ERROR_INVALID_DATA;
        }

        // Guardar datos en sesión
        $_SESSION['id_usuario'] = $result[0]['id_usuario'];
        $_SESSION['usuario'] = $result[0]['usuario'];
        $_SESSION['rol_usuario'] = $result[0]['nivel'];
        $_SESSION['logged_in'] = true;
        return self::LOGIN_SUCCESS;
      }

      return self::ERROR_INVALID_CREDENTIALS;
    } catch (Exception $e) {
      error_log("Error en Auth::login: " . $e->getMessage());
      return self::ERROR_DATABASE;
    }
  }

  public function recuperarPassword(array $request): int
  {
    try {
      // Validar datos requeridos
      if (empty($request['correo'])) {
        error_log("Error: Correo vacío");
        return self::ERROR_INVALID_DATA;
      }

      error_log("Intentando recuperar contraseña para: " . $request['correo']);

      // Consulta SQL para validar solo por correo
      $sql = "SELECT id_usuario, usuario, correo FROM usuarios WHERE correo = :correo AND estado = 1 LIMIT 1";
      $params = [':correo' => trim($request['correo'])];

      error_log("Ejecutando consulta SQL: " . $sql);
      error_log("Con parámetros: " . json_encode($params));

      $result = $this->db->consultar($sql, $params);

      error_log("Resultado de la consulta: " . json_encode($result));

      if ($result && count($result) > 0) {
        error_log("Usuario encontrado, generando token de recuperación");

        try {
          // Generar token único
          $token = bin2hex(random_bytes(32));
          $expiracion = date('Y-m-d H:i:s', strtotime('+24 hours'));

          // Actualizar el token en la base de datos
          $sqlUpdate = "UPDATE usuarios SET 
                       token_recuperacion = :token,
                       token_expiracion = :expiracion,
                       fecha_modificacion = NOW() 
                       WHERE correo = :correo";

          $paramsUpdate = [
            ':token' => $token,
            ':expiracion' => $expiracion,
            ':correo' => trim($request['correo'])
          ];

          error_log("Actualizando token en la base de datos con expiración: " . $expiracion);
          $this->db->ejecutar($sqlUpdate, $paramsUpdate);

          // Preparar datos para el correo
          $datosCorreo = [
            'usuario' => $result[0]['usuario'],
            'token' => $token
          ];

          error_log("Enviando correo de recuperación");

          $correoController = new CorreoController(
            'smtp.gmail.com',
            true,
            'victorcarrillox2@gmail.com',
            'hvop zmdr wstd knhr',
            587
          );

          if ($correoController->enviarCorreoRecuperacion(
            'victorcarrillox2@gmail.com',
            $request['correo'],
            'Recuperación de Contraseña - TuRifaDigi',
            $datosCorreo
          )) {
            error_log("Correo enviado exitosamente a: " . $request['correo']);
            $_SESSION['reset_email'] = $request['correo'];
            return self::LOGIN_SUCCESS;
          }

          error_log("Error al enviar el correo");
          return self::ERROR_DATABASE;
        } catch (Exception $e) {
          error_log("Error en la generación/actualización del token: " . $e->getMessage());
          return self::ERROR_DATABASE;
        }
      }

      error_log("No se encontró el usuario con el correo: " . $request['correo']);
      return self::ERROR_INVALID_CREDENTIALS;
    } catch (Exception $e) {
      error_log("Error en Auth::recuperarPassword: " . $e->getMessage());
      error_log("Stack trace: " . $e->getTraceAsString());
      return self::ERROR_DATABASE;
    }
  }

  public function resetPassword(string $token, string $newPassword): bool
  {
    try {
      $sql = "SELECT * FROM usuarios WHERE token_recuperacion = :token LIMIT 1";
      $params = [':token' => $token];
      $result = $this->db->consultar($sql, $params);

      if ($result && count($result) > 0) {
        $sqlUpdate = "UPDATE usuarios SET password = :password, token_recuperacion = NULL WHERE token_recuperacion = :token";
        $paramsUpdate = [
          ':password' => $newPassword,
          ':token' => $token
        ];

        $this->db->ejecutar($sqlUpdate, $paramsUpdate);
        return true;
      }
      return false;
    } catch (Exception $e) {
      error_log("Error en Auth::resetPassword: " . $e->getMessage());
      return false;
    }
  }

  public function getStatusMessage(int $status, $translations): array
  {
    switch ($status) {
      case self::LOGIN_SUCCESS:
        return [
          'success' => true,
          'message' => $translations['login_success_redirect'],
          'type' => 'success'
        ];
      case self::ERROR_INVALID_CREDENTIALS:
        return [
          'success' => false,
          'message' => $translations['no_account_found'],
          'type' => 'error'
        ];
      case self::ERROR_INVALID_DATA:
        return [
          'success' => false,
          'message' => $translations['invalid_password'],
          'type' => 'error'
        ];
      case self::ERROR_DATABASE:
        return [
          'success' => false,
          'message' => $translations['database_error'],
          'type' => 'error'
        ];
      default:
        return [
          'success' => false,
          'message' => $translations['unexpected_error'],
          'type' => 'error'
        ];
    }
  }
}
