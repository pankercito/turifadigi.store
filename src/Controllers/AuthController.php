<?php

namespace App\Controllers;

use App\Models\Auth;

class AuthController
{
    private $login;

    public function __construct()
    {
        $this->login = new Auth();
    }

    public function login(array $request): void
    {
        header('Content-Type: application/json');
        $lang = $_SERVER['HTTP_X_LANGUAGE'] ?? 'es';
        if (!in_array($lang, ['es', 'en'])) $lang = 'es';
        $translations = $this->loadTranslations($lang);

        try {
            if (empty($request['usuario']) || empty($request['password'])) {
                echo json_encode([
                    'success' => false,
                    'message' => $translations['all_fields_required'],
                    'type' => 'error'
                ]);
                exit;
            }

            $result = $this->login->login($request);
            $response = $this->login->getStatusMessage($result, $translations);

            echo json_encode($response);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $translations['database_error'],
                'type' => 'error'
            ]);
        }
        exit();
    }



    public function recuperarPassword(array $request): void
    {
        $lang = $_SERVER['HTTP_X_LANGUAGE'] ?? 'es';
        if (!in_array($lang, ['es', 'en'])) $lang = 'es';
        $translations = $this->loadTranslations($lang);

        try {
            // Validación básica de datos
            if (empty($request['usuario']) & empty($request['password_algos']) & empty($request['re_password'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'El correo electrónico es requerido',
                    'type' => 'error'
                ]);
                exit;
            }

            if ($request['password_algos'] != $request['re_password']) {
                echo json_encode([
                    'success' => false,
                    'message' => "Las contraseñas no coinciden",
                    'type' => 'error'
                ]);
                exit;
            }
            
            $result = $this->login->resetPassword($request['usuario'], $request['password_algos']);
            $response = $this->login->getStatusMessage(5, $translations);

            echo json_encode($response);
        } catch (\Exception $e) {
            error_log("Error en AuthController::recuperarPassword: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al procesar la solicitud en la base de datos',
                'type' => 'error'
            ]);
        }
        exit();
    }

    public function resetPassword(array $request): void
    {
        header('Content-Type: application/json');
        try {
            // Validación básica de datos
            if (empty($request['token']) || empty($request['password'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Todos los campos son requeridos',
                    'type' => 'error'
                ]);
                exit;
            }

            $result = $this->login->resetPassword($request['token'], $request['password']);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Contraseña actualizada exitosamente',
                    'type' => 'success'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'El enlace de recuperación no es válido o ha expirado',
                    'type' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            error_log("Error en AuthController::resetPassword: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al procesar la solicitud',
                'type' => 'error'
            ]);
        }
        exit();
    }


    public function loadTranslations($lang = 'es')
    {
        $file = __DIR__ . "/../../assets/language/{$lang}.json";
        if (file_exists($file)) {
            $json = file_get_contents($file);
            return json_decode($json, true);
        }
        // Fallback a español si no existe el archivo
        $json = file_get_contents(__DIR__ . "/../../assets/language/es.json");
        return json_decode($json, true);
    }
}
