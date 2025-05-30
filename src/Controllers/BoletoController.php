<?php

namespace App\Controllers;

require_once __DIR__ . '/../Models/BoletoModel.php';

use App\Models\BoletoModel;
use Exception;

class BoletoController
{
  private $model;

  public function __construct()
  {
    $this->model = new BoletoModel();
    // Asegurarnos de que existan los boletos en la base de datos
    // $this->model->inicializarBoletos();
  }

  public function index()
  {
    require_once 'views/rifa/sorteo.php';
  }

  public function obtenerCompra($id_compra = null, $id_usuario = null)
  {
    try {
      if ($id_usuario !== null) {
        $data = $this->model->obtenerComprasByUser($id_usuario);
        return ([
          "success" => true,
          "data" => $data
        ]);
      }
      if ($id_compra !== null) {
        $data = $this->model->obtenerComprasByID($id_compra);
        return ([
          "success" => true,
          "data" => $data
        ]);
      }
      $data = $this->model->obtenerCompras();
      return ([
        "success" => true,
        "data" => $data
      ]);
    } catch (Exception $e) {
      $_SESSION['mensaje'] = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
      require_once 'views/administracion/boletos/index.php';
    }
  }

  public function procesarCompra()
  {
    header('Content-Type: application/json');

    try {
      $data = json_decode(file_get_contents('php://input'), true);
      if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error en el formato JSON de entrada');
      }

      // Validar datos requeridos
      $camposRequeridos = [
        'id_usuario' => 'ID de usuario',
        'boletos' => 'Boletos seleccionados',
        'monto_pago' => 'Nombre completo',
        'titular' => 'Titular de la cuenta',
        'referencia' => 'Referencia de pago',
        'metodoPago' => 'Método de pago'
      ];

      foreach ($camposRequeridos as $campo => $nombre) {
        if (empty($data[$campo])) {
          throw new Exception("El campo {$nombre} es requerido");
        }
      }

      try {
        // Procesar la compra usando una única transacción con INNER JOIN
        $result = $this->model->procesarCompraConJoin(
          $data['id_usuario'],
          $data['boletos'],
          $data['monto_pago'],
          $data['titular'],
          $data['referencia'],
          $data['metodoPago']
        );

        echo json_encode([
          'success' => $result['success'],
          'message' => 'Compra procesada correctamente. Los boletos quedarán reservados hasta que se confirme el pago'
        ]);
      } catch (\Throwable $th) {
        http_response_code(500);
        echo json_encode([
          'success' => false,
          'error' => $th->getMessage()
        ]);
      }
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
      ]);
    }
  }

  public function verificarDisponibilidad()
  {
    // Limpiar cualquier salida anterior
    ob_clean();

    // Establecer headers
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

    try {
      // Obtener y decodificar datos de entrada
      $jsonInput = file_get_contents('php://input');
      $data = json_decode($jsonInput, true);

      if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error en el formato JSON de entrada');
      }

      $boletos = $data['boletos'] ?? [];

      if (empty($boletos)) {
        throw new Exception('No se proporcionaron números de boleto');
      }

      // Verificar disponibilidad usando INNER JOIN
      $resultados = $this->model->verificarDisponibilidadConJoin($boletos);

      // Verificar si hay boletos no disponibles
      $noDisponibles = array_filter($resultados, function ($boleto) {
        return !$boleto['disponible'];
      });

      if (!empty($noDisponibles)) {
        $numerosNoDisponibles = array_map(function ($boleto) {
          return $boleto['numero'];
        }, $noDisponibles);

        http_response_code(409); // Conflict
        echo json_encode([
          'error' => true,
          'mensaje' => 'Algunos boletos no están disponibles',
          'boletosNoDisponibles' => $numerosNoDisponibles
        ]);
        exit;
      }

      echo json_encode([
        'success' => true,
        'disponibles' => $resultados
      ]);
      exit;
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'error' => true,
        'mensaje' => $e->getMessage()
      ]);
      exit;
    }
  }

  public function obtenerBoletosPaginados()
  {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

    try {
      $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
      $porPagina = isset($_GET['por_pagina']) ? (int)$_GET['por_pagina'] : 100;

      // Validar parámetros
      if ($pagina < 1) $pagina = 1;
      if ($porPagina < 1 || $porPagina > 200) $porPagina = 100;

      $boletos = $this->model->obtenerBoletosPaginados($pagina, $porPagina);

      echo json_encode([
        'success' => true,
        'data' => $boletos,
        'pagina' => $pagina,
        'por_pagina' => $porPagina
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
      ]);
    }
  }

  public function obtenerBoletos($peticion = null, $array = null)
  {

    try {
      // Validar si $array es un array
      // Validar si $array es un array no vacío
      if ($array !== null) {
        if (!is_array($array) || empty($array)) {
          echo json_encode([
            'success' => false,
            'error' => 'El parámetro proporcionado no es un array válido o está vacío',
          ]);
          exit;
        }

        $idbol = $array["boleto"];
        $idrif = $array["id_rifa"];

        $boletos = $this->model->verificarBoletosXCompra($idrif, $idbol);

        if (empty($boletos) || !isset($boletos['success']) || $boletos['success'] == false) {
          echo json_encode([
            'success' => false,
            'data' => $boletos['data'] ?? [],
            'total' => $boletos['total'] ?? 0,
          ]);
          exit;
        }

        echo json_encode([
          'success' => true,
          'data' => $boletos['data'],
          'total' => $boletos['total'],
        ]);
        exit;
      }

      // Selecciona el método según el valor de $peticion
      $boletos = $peticion
        ? $this->model->obtenerBoletosGandores()
        : $this->model->obtenerBoletos();

      if (empty($boletos) || !isset($boletos['success']) || $boletos['success'] == false) {
        echo json_encode([
          'success' => false,
          'data' => $boletos['data'] ?? [],
          'total' => $boletos['total'] ?? 0,
        ]);
        exit;
      }

      echo json_encode([
        'success' => true,
        'data' => $boletos['data'],
        'total' => $boletos['total'],
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
      ]);
    }
  }

  public function confirmarPago($id_compra)
  {
    try {
      $r = $this->model->marcarCompraComoPagada($id_compra);
      return [
        'success' => true,
        'mensaje' => '¡Compra confirmada como pagada!',
        'marc' => $r,
      ];
    } catch (Exception $e) {
      return [
        'success' => false,
        'mensaje' => 'Error: ' . $e->getMessage(),
      ];
    }
  }

  public function rechazarPago($id_compra)
  {
    try {
      $r = $this->model->marcarCompraComoRechazada($id_compra);
      return [
        'success' => true,
        'mensaje' => '¡Compra confirmada como rechazada!',
        'marc' => $r,
      ];
    } catch (Exception $e) {
      return [
        'success' => false,
        'mensaje' => 'Error: ' . $e->getMessage(),
      ];
    }
  }
}
