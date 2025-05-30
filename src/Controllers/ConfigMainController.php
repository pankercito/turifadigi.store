<?php

namespace App\Controllers;

use App\Models\ConfigMain;
use Exception;

class ConfigMainController
{
  private $model;

  // Constructor: Inicializa el modelo ConfigMain
  public function __construct()
  {
    $this->model = new ConfigMain();
  }

  // Método index: Carga la vista para crear un sorteo
  public function index()
  {
    require_once 'views/admin/crear_sorteo.php';
  }

  // Método crearSorteo: Maneja la creación de un nuevo sorteo
  public function crearSorteo()
  {
    try {
      // Obtener datos del formulario
      $idUsuario = $_SESSION['id_usuario'] ?? 0;
      //echo "<pre>ID Usuario: ";
      //print_r($idUsuario);
      //echo "</pre>";
      //echo "<pre>Var dump ID Usuario: ";
      //var_dump($idUsuario);
      //echo "</pre>";

      // Verificar si el título es un array y convertirlo a JSON si es necesario
      if (is_array($_POST['titulo'])) {
        $titulo = json_encode($_POST['titulo'], JSON_UNESCAPED_UNICODE);
      } else {
        $titulo = json_encode(json_decode($_POST['titulo'], true), JSON_UNESCAPED_UNICODE);
      }
      //echo "<pre>Título procesado: ";
      //print_r($titulo);
      //echo "</pre>";
      //echo "<pre>Var dump título: ";
      //var_dump($titulo);
      //echo "</pre>";

      $precioBoleto = $_POST['precio_boleto'] ?? 0;
      $boletosMinimos = $_POST['boletos_minimos'] ?? 0;
      $boletosMaximos = $_POST['boletos_maximos'] ?? 0;
      $fechaInicio = $_POST['fecha_inicio'] ?? '';
      $fechaFin = $_POST['fecha_final'] ?? '';
      $numeroContacto = $_POST['numero_contacto'] ?? '';
      $urlRifa = $_POST['url_rifa'] ?? '';

      //echo "<pre>Datos del formulario: ";
      //echo "Precio boleto: ";
      //print_r($precioBoleto);
      //echo "\n";
      //echo "Boletos mínimos: ";
      //print_r($boletosMinimos);
      //echo "\n";
      //echo "Boletos máximos: ";
      //print_r($boletosMaximos);
      //echo "\n";
      //echo "Fecha inicio: ";
      //print_r($fechaInicio);
      //echo "\n";
      //echo "Fecha fin: ";
      //print_r($fechaFin);
      //echo "\n";
      //echo "Número contacto: ";
      //print_r($numeroContacto);
      //echo "\n";
      //echo "URL rifa: ";
      //print_r($urlRifa);
      //echo "\n";
      //echo "</pre>";

      // Verificar si texto_ejemplo es un array y convertirlo a JSON si es necesario
      if (is_array($_POST['texto_ejemplo'])) {
        $textoEjemplo = json_encode($_POST['texto_ejemplo'], JSON_UNESCAPED_UNICODE);
      } else {
        $textoEjemplo = json_encode(json_decode($_POST['texto_ejemplo'], true), JSON_UNESCAPED_UNICODE);
      }
      //echo "<pre>Texto ejemplo procesado: ";
      //print_r($textoEjemplo);
      //echo "</pre>";
      //echo "<pre>Var dump texto ejemplo: ";
      //var_dump($textoEjemplo);
      //echo "</pre>";

      // Verificar si premios es un array y convertirlo a JSON si es necesario


      // Verificar si premios existe y es un array
      if (!isset($_POST['premios']) || !is_array($_POST['premios'])) {
        throw new Exception('Los datos de los premios no son válidos');
      }

      $premios = [];
      foreach ($_POST['premios'] as $premio) {
        // Validar y procesar el nombre del premio
        if (!isset($premio['nombre'])) {
          throw new Exception('El nombre del premio es requerido');
        }
        $nombre = is_array($premio['nombre']) ?
          json_encode($premio['nombre'], JSON_UNESCAPED_UNICODE) : (json_decode($premio['nombre'], true) ?
            json_encode(json_decode($premio['nombre'], true), JSON_UNESCAPED_UNICODE) :
            $premio['nombre']);

        // Validar y procesar la descripción del premio
        if (!isset($premio['descripcion'])) {
          throw new Exception('La descripción del premio es requerida');
        }

        $descripcion = is_array($premio['descripcion']) ?  $premio['descripcion'] :  json_encode($premio['descripcion'], JSON_UNESCAPED_UNICODE);

        $premios[] = [
          'nombre' => $nombre,
          'descripcion' => $descripcion
        ];
      }

      //echo "<pre>Premios procesados: ";
      //print_r($premios);
      //echo "</pre>";
      //echo "<pre>Var dump premios: ";
      //var_dump($premios);
      //echo "</pre>";

      $imagen = $_FILES['imagen'] ?? null;
      //echo "<pre>Datos de la imagen: ";
      //print_r($imagen);
      //echo "</pre>";
      //echo "<pre>Var dump imagen: ";
      //var_dump($imagen);
      //echo "</pre>";

      // Validaciones básicas
      if (strtotime($fechaInicio) > strtotime($fechaFin)) {
        throw new Exception('La fecha de inicio no puede ser mayor a la fecha final');
      }

      if ($precioBoleto <= 0) {
        throw new Exception('El precio del boleto debe ser mayor a 0');
      }

      if ($boletosMinimos <= 0 || $boletosMaximos <= 0) {
        throw new Exception('Las cantidades de boletos deben ser mayores a 0');
      }

      if ($boletosMinimos > $boletosMaximos) {
        throw new Exception('La cantidad mínima no puede ser mayor a la máxima');
      }

      // Procesar imagen si existe
      $rutaImagen = 'assets/img/banners/'; // Ruta por defecto
      if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
        $extension = $this->extencion($imagen['name']);
        $nombreArchivo = 'banner_' . time() . '.' . $extension;
        $destino = 'assets/img/banners/';

        if ($this->moveFile($imagen['tmp_name'], $destino, $nombreArchivo)) {
          $rutaImagen = $destino . $nombreArchivo;
        }
      }
      //echo "<pre>Ruta de la imagen: ";
      //print_r($rutaImagen);
      //echo "</pre>";
      //echo "<pre>Var dump ruta imagen: ";
      //var_dump($rutaImagen);
      //echo "</pre>";

      // Crear el sorteo usando el modelo
      $idSorteo = $this->model->crearSorteo(
        $idUsuario,
        $titulo,
        $fechaInicio,
        $fechaFin,
        $precioBoleto,
        $boletosMinimos,
        $boletosMaximos,
        $numeroContacto,
        $urlRifa,
        $textoEjemplo,
        $premios,
        $rutaImagen
      );
      //echo "<pre>ID del sorteo creado: ";
      //print_r($idSorteo);
      //echo "</pre>";
      //echo "<pre>Var dump ID sorteo: ";
      //var_dump($idSorteo);
      //echo "</pre>";

      if ($idSorteo) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Sorteo creado exitosamente']);
        exit();
      } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Error al crear el sorteo']);
        exit();
      }
    } catch (Exception $e) {
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'error' => $e->getMessage()]);
      exit();
    }
  }

  public function actualizarSorteo($id, $estado, $ganadores = null)
  {
    $this->model->desactivarRifas();

    if ($id != null && $estado != null) {

      try {
        if ($ganadores) {
          $mrc = $this->model->finalizarRifa($id, $estado, $ganadores);
        } else {
          $mrc = $this->model->actualizarRifa($id, $estado);
        }

        if ($mrc == true) {
          return [
            'success' => $mrc,
            'mensaje' => 'estado actualizado correctamente'
          ];
        }
      } catch (\Exception $th) {
        throw new \Exception("Error al actualizar el sorteo: " . $th->getMessage());
      }
    }
  }

  private function moveFile($archivo, $destino, $nombreDelArchivo = 'sin_nombre')
  {
    if (!file_exists($destino)) {
      mkdir($destino, 0755, true);
    }

    $rutaCompleta = $destino . $nombreDelArchivo;
    return move_uploaded_file($archivo, $rutaCompleta);
  }

  private function extencion($name)
  {
    $info = pathinfo($name);
    return $info['extension'] ?? 'no_espesificado';
  }
}
