<?php

namespace App\Models;

use App\config\Conexion;
use Exception;
use PDO;
use PhpOption\None;

class BoletoModel
{
  private $db;

  public function __construct()
  {
    $this->db = new Conexion();
  }

  public function verificarDisponibilidad($numero)
  {
    $numero = htmlspecialchars(strip_tags($numero), ENT_QUOTES, 'UTF-8');

    $sql = "SELECT estado FROM boletos WHERE numero_boleto = :numero";
    $result = $this->db->consultar($sql, [':numero' => $numero]);

    if (empty($result)) {
      return false;
    }

    return $result[0]['estado'] === 'disponible';
  }

  public function verificarDisponibilidadConJoin($boletos)
  {
    try {

      $boletos = array_map(function ($boleto) {
        return htmlspecialchars(strip_tags($boleto), ENT_QUOTES, 'UTF-8');
      }, $boletos);

      $params = [];
      $placeholders = [];
      foreach ($boletos as $index => $numero) {
        $paramName = ":boleto" . ($index + 1);
        $params[$paramName] = $numero;
        $placeholders[] = $paramName;
      }

      // Modificamos la consulta para obtener todos los boletos, incluso si no existen
      $sql = "SELECT b.numero_boleto, 
                     CASE 
                       WHEN b.id_boleto IS NULL THEN false
                       WHEN b.estado = 'disponible' THEN true
                       ELSE false
                     END as disponible,
                     COALESCE(b.estado, 'no_existe') as estado
              FROM (SELECT " . implode(" UNION ALL SELECT ", array_fill(0, count($boletos), "?")) . ") AS nums(numero)
              LEFT JOIN boletos b ON b.numero_boleto = nums.numero";

      // Reemplazamos los placeholders con los números de boleto
      $result = $this->db->consultar($sql, $boletos);

      if (empty($result)) {
        // Si no hay resultados, significa que ningún boleto existe
        throw new Exception("Error al consultar los boletos");
      }

      $resultados = [];
      $mensajesError = [];

      foreach ($result as $row) {
        $estado = $row['estado'];
        $mensaje = "";

        switch ($estado) {
          case 'no_existe':
            $mensaje = "El boleto {$row['numero_boleto']} no existe en el sistema";
            break;
          case 'reservado':
            $mensaje = "El boleto {$row['numero_boleto']} está reservado";
            break;
          case 'vendido':
            $mensaje = "El boleto {$row['numero_boleto']} ya está vendido";
            break;
        }

        if ($mensaje) {
          $mensajesError[] = $mensaje;
        }

        $resultados[] = [
          'numero' => $row['numero_boleto'],
          'disponible' => $row['disponible'],
          'estado' => $estado
        ];
      }

      if (!empty($mensajesError)) {
        throw new Exception(implode("\n", $mensajesError));
      }

      return $resultados;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function procesarCompraConJoin($id_usuario, $boletos, $montoPagado, $titular, $referencia, $metodoPago)
  {

    try {

      // 1. Primero verificamos la disponibilidad de todos los boletos
      $boletosVerificar = [];
      // Sanitize boletos array
      $boletos = array_map(function ($boleto) {
        return htmlspecialchars(strip_tags($boleto), ENT_QUOTES, 'UTF-8');
      }, $boletos);

      $titular = htmlspecialchars(strip_tags($titular), ENT_QUOTES, 'UTF-8');
      $referencia = htmlspecialchars(strip_tags($referencia), ENT_QUOTES, 'UTF-8');
      $metodoPago = htmlspecialchars(strip_tags($metodoPago), ENT_QUOTES, 'UTF-8');

      foreach ($boletos as $numeroBoleto) {
        $sqlBoleto = "SELECT b.id_boleto FROM boletos b INNER JOIN rifas r ON b.id_rifa = r.id_rifa INNER JOIN configuracion c ON c.id_configuracion = r.id_configuracion WHERE b.numero_boleto = :numero AND b.estado ='disponible' AND c.estado= 1";
        $resultBoleto = $this->db->consultar($sqlBoleto, [':numero' => $numeroBoleto]);

        if (empty($resultBoleto)) {
          throw new Exception("El boleto {$numeroBoleto} no está disponible");
        }
        $boletosVerificar[] = $resultBoleto[0]['id_boleto'];
      }

      // 2. Si llegamos aquí, todos los boletos están disponibles. Creamos la compra
      $rifa = $this->db->consultar("SELECT r.id_rifa, c.precio_boleto FROM rifas r INNER JOIN configuracion c ON c.id_configuracion = r.id_configuracion WHERE c.estado= 1", []);

      $sqlCompra = "INSERT INTO compras_boletos (id_rifa, fecha_compra, estado, total_compra) 
                    VALUES (:id_rifa, NOW(), 'pendiente', :total)";

      if (empty($rifa)) {
        throw new Exception("No se encontró una rifa activa");
      }

      $id_rifa_activa = $rifa[0]['id_rifa'];
      $precioUnitario = $rifa[0]['precio_boleto'];
      $totalCompra = count($boletosVerificar) * $precioUnitario;

      $idCompra = $this->db->ejecutar($sqlCompra, [
        ':id_rifa' => $id_rifa_activa,
        ':total' => $totalCompra
      ]);

      if (!$idCompra) {
        throw new Exception("Error al crear la compra");
      }

      // 4. Marcamos los boletos como reservados y creamos el detalle
      $boletosInsertados = [];

      foreach ($boletosVerificar as $index => $idBoleto) {
        // Actualizar estado del boleto a reservado
        $sqlUpdateBoleto = "UPDATE boletos 
                           SET estado = 'reservado', id_usuario = :id_usuario 
                           WHERE id_boleto = :id_boleto";

        $this->db->ejecutar($sqlUpdateBoleto, [
          ':id_boleto' => $idBoleto,
          ':id_usuario' => $id_usuario
        ]);

        // Insertar detalle de compra
        $sqlDetalle = "INSERT INTO detalle_compras (id_compra, id_boleto, precio_unitario) 
                       VALUES (:id_compra, :id_boleto, :precio_unitario)";

        $this->db->ejecutar($sqlDetalle, [
          ':id_compra' => $idCompra,
          ':id_boleto' => $idBoleto,
          ':precio_unitario' => $precioUnitario
        ]);

        $boletosInsertados[] = $boletos[$index];
      }
      // Obtener datos personales del usuario para detalle_compras
      $sqlDatosPersonales = "SELECT nombre, apellido, telefono FROM datos_personales WHERE id_usuario = :id_usuario";
      $datosPersonales = $this->db->consultar($sqlDatosPersonales, [':id_usuario' => $id_usuario]);
      $nombre = isset($datosPersonales[0]['nombre']) ? $datosPersonales[0]['nombre'] : null;
      $apellido = isset($datosPersonales[0]['apellido']) ? $datosPersonales[0]['apellido'] : null;
      $telefono = isset($datosPersonales[0]['telefono']) ? $datosPersonales[0]['telefono'] : null;

      // Actualizar detalle_compras con los datos del comprador
      $sqlUpdateDetalle = "UPDATE detalle_compras 
               SET nom_comprador = :nombre, ape_comprador = :apellido, telefono_comprador = :telefono 
               WHERE id_compra = :id_compra";
      $this->db->ejecutar($sqlUpdateDetalle, [
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':telefono' => $telefono,
        ':id_compra' => $idCompra
      ]);

      // 5. Registrar el pago como pendiente
      $sqlPago = "INSERT INTO pagos (id_compra, titular, referencia, metodo, monto_pagado) 
                  VALUES (:id_compra, :titular, :referencia, :metodo, :monto)";

      $this->db->ejecutar($sqlPago, [
        ':id_compra' => $idCompra,
        ':titular' => $titular,
        ':referencia' => $referencia,
        ':metodo' => $metodoPago,
        ':monto' => $montoPagado
      ]);

      return [
        'success' => true,
        'id_compra' => $idCompra,
        'boletos' => $boletosInsertados,
        'mensaje' => 'Compra procesada correctamente.'
      ];
    } catch (Exception $e) {
      throw $e;
    }
  }

  // Método para obtener boletos paginados con mejor rendimiento
  public function obtenerBoletosPaginados($pagina = 1, $porPagina = 100)
  {
    try {
      // Asegurarse de que los boletos estén inicializados
      // $this->inicializarBoletos();

      $offset = ($pagina - 1) * $porPagina;

      // Optimizamos la consulta para mejor rendimiento
      $sql = "SELECT 
              b.numero_boleto,
              b.estado,
              CASE 
                  WHEN b.estado = 'disponible' THEN 'disponible'
                  ELSE b.estado
              END as estado_real
              FROM boletos b
              WHERE b.id_rifa = 1 
              ORDER BY CAST(b.numero_boleto AS UNSIGNED)
              LIMIT :limit OFFSET :offset";

      $boletos = $this->db->consultar($sql, [
        ':limit' => $porPagina,
        ':offset' => $offset
      ]);

      return [
        'success' => true,
        'data' => $boletos,
        'pagina' => $pagina,
        'por_pagina' => $porPagina,
        'total' => count($boletos)
      ];
    } catch (Exception $e) {
      throw new Exception("Error al obtener boletos: " . $e->getMessage());
    }
  }

  // Método para obtener boletos paginados con mejor rendimiento
  public function obtenerBoletos()
  {
    try {
      // Optimizamos la consulta para mejor rendimiento
      $sql = "SELECT b.id_boleto, b.id_rifa, b.numero_boleto, b.estado AS estado, c.estado AS rifa_estado, r.id_rifa FROM boletos b INNER JOIN rifas r ON r.id_rifa = b.id_rifa INNER JOIN configuracion c ON c.id_configuracion = r.id_configuracion WHERE c.estado = 1 ORDER BY b.id_boleto ASC";

      $boletos = $this->db->consultar($sql, []);

      if (count($boletos) == 0) {
        return [
          'success' => false,
          'data' => ["rifa_estado" => "0"],
          'total' => count($boletos)
        ];
      }
      return [
        'success' => true,
        'data' => $boletos,
        'total' => count($boletos)
      ];
    } catch (Exception $e) {
      throw new Exception("Error al obtener boletos: " . $e->getMessage());
    }
  }

  public function obtenerBoletosGandores()
  {
    try {
      $sql = "SELECT
          cb.id_compra,
          b.id_rifa,
          b.id_boleto,          
          c.precio_boleto,
          b.numero_boleto,
          dc.nom_comprador AS cliente,
          dc.ape_comprador AS a_cliente,
          dc.telefono_comprador AS telefono,
          cb.total_compra,
          cb.estado,
          cb.fecha_compra
          FROM
            boletos b
          INNER JOIN
            rifas r ON r.id_rifa = b.id_rifa
          INNER JOIN
            configuracion c ON c.id_configuracion = r.id_configuracion
          LEFT JOIN -- Primero une detalle_compras, ya que depende de 'b'
            detalle_compras dc ON  b.id_boleto=dc.id_boleto
          LEFT JOIN -- Luego une compras_boletos, ya que depende de 'dc'
            compras_boletos cb ON cb.id_compra = dc.id_compra
          LEFT JOIN -- Usa LEFT JOIN para usuarios
            usuarios u ON b.id_usuario = u.id_usuario
          WHERE
            c.estado = 2
            AND b.estado NOT IN ('disponible', 'pendiente')
          ORDER BY
            b.id_boleto ASC;";

      $boletos = $this->db->consultar($sql, []);

      if (count($boletos) == 0) {
        return [
          'success' => false,
          'data' => ["rifa_estado" => "0"],
          'total' => 0,
        ];
      }

      $data = [];
      foreach ($boletos as $boleto) {
        $data[] = [
            'id_compra' => $boleto['id_compra'] ?? null,
            'id_rifa' => $boleto['id_rifa'],
            'id_boleto' => $boleto['id_boleto'],
            'numero_boleto' => $boleto['numero_boleto'],
            'cliente' => !empty($boleto['cliente']) ? ucwords(strtolower($boleto['cliente'])) : null,
            'a_cliente' => !empty($boleto['a_cliente']) ? ucwords(strtolower($boleto['a_cliente'])) : null,
            'telefono' => !empty($boleto['telefono']) ? substr($boleto['telefono'], 0, 4) . '****' . substr($boleto['telefono'], -2) : null,
            'precio_boleto' => $boleto['precio_boleto'],
            'total_compra' => $boleto['total_compra'] ?? null,
            'estado' => $boleto['estado'] ?? null,
            'fecha_compra' => $boleto['fecha_compra'] ?? null,
        ];
      }

      return [
        'success' => true,
        'data' => $data,
        'total' => count($boletos)
      ];
    } catch (Exception $e) {
      throw new Exception("Error al obtener boletos: " . $e->getMessage());
    }
  }

  public function verificarBoletosXCompra($id_rifa, $boleto)
  {
    try {

      // Validar que los parámetros existan y no estén vacíos
      if (empty($id_rifa) || empty($boleto)) {
        throw new Exception("Faltan parámetros requeridos: id_rifa o boleto.");
      }

      $id_rifa = htmlspecialchars(strip_tags($id_rifa), ENT_QUOTES, 'UTF-8');
      $boleto = htmlspecialchars(strip_tags($boleto), ENT_QUOTES, 'UTF-8');

      $sql = "SELECT
          b.id_rifa,
          b.id_boleto,
          b.numero_boleto,
          cb.id_compra,
          dc.nom_comprador AS cliente,
          dc.ape_comprador AS a_cliente,
          dc.telefono_comprador AS telefono,
          dc.precio_unitario AS precio_boleto,
          cb.total_compra,
          cb.estado,
          cb.fecha_compra
          FROM
            boletos b
            INNER JOIN
            rifas r ON r.id_rifa = b.id_rifa
            INNER JOIN
            configuracion c ON c.id_configuracion = r.id_configuracion
            LEFT JOIN -- Primero une detalle_compras, ya que depende de 'b'
            detalle_compras dc ON  b.id_boleto=dc.id_boleto
            LEFT JOIN -- Luego une compras_boletos, ya que depende de 'dc'
            compras_boletos cb ON cb.id_compra = dc.id_compra
            LEFT JOIN -- Usa LEFT JOIN para usuarios
            usuarios u ON b.id_usuario = u.id_usuario
          WHERE
            b.id_rifa = :id_rifa
            AND b.numero_boleto = :boleto
          ORDER BY
            b.id_boleto ASC;";

      $boletos = $this->db->consultar($sql, [
        ":id_rifa" => $id_rifa,
        ":boleto" => $boleto
      ]);

      if (!$boletos) {
        return [
          'success' => false,
          'data' => ["comprados" => "0"],
          'total' => 0,
        ];
      }

      $data = [];
      foreach ($boletos as $boleto) {
        $data[] = [
          'id_compra' => $boleto['id_compra'] ?? null,
          'id_rifa' => $boleto['id_rifa'],
          'id_boleto' => $boleto['id_boleto'],
          'numero_boleto' => $boleto['numero_boleto'],
          'cliente' => !empty($boleto['cliente']) ? ucwords(strtolower($boleto['cliente'])) : null,
          'a_cliente' => !empty($boleto['a_cliente']) ? ucwords(strtolower($boleto['a_cliente'])) : null,
          'telefono' => !empty($boleto['telefono']) ? substr($boleto['telefono'], 0, 4) . '****' . substr($boleto['telefono'], -2) : null,
          'precio_boleto' => $boleto['precio_boleto'],
          'total_compra' => $boleto['total_compra'],
          'estado' => $boleto['estado'],
          'fecha_compra' => $boleto['fecha_compra'] ?? null,
        ];
      }
      return [
        'success' => true,
        'data' => $data,
        'marco' => ["comprados" => "0"],
        'total' => count($boletos)
      ];
    } catch (Exception $e) {
      throw new Exception("Error al obtener boletos: " . $e->getMessage());
    }
  }

  public function obtenerCompras()
  {
    try {
      $sql = "SELECT
              cb.id_compra,
              b.id_rifa,
              b.numero_boleto,
              dc.nom_comprador AS cliente,
              dc.ape_comprador AS a_cliente,
              dc.telefono_comprador AS telefono,
              p.metodo AS metodo_pago,
              cb.total_compra,
              cb.estado,
              cb.fecha_compra,
              p.validacion AS estado_pago,
              p.titular,
              p.referencia, 
              p.monto_pagado
              FROM compras_boletos cb
              INNER JOIN detalle_compras dc ON cb.id_compra = dc.id_compra
              INNER JOIN boletos b ON dc.id_boleto = b.id_boleto
              INNER JOIN pagos p ON cb.id_compra = p.id_compra
              ORDER BY
                cb.fecha_compra DESC";

      $result = $this->db->consultar($sql, []);

      // Procesar los resultados para agrupar boletos por compra
      $compras = [];
      foreach ($result as $row) {
        $id_compra = $row['id_compra'];

        if (!isset($compras[$id_compra])) {
          // Primera vez que vemos esta compra
          $compras[$id_compra] = [
            'id_compra' => $id_compra,
            'cliente' => ucwords(strtolower($row['cliente'] . " " . $row['a_cliente'])),
            'metodo_pago' => $row['metodo_pago'],
            'total' => $row['total_compra'],
            'monto_pagado' => $row['monto_pagado'],
            'estado' => $row['estado'],
            'fecha_compra' => $row['fecha_compra'],
            'estado_pago' => $row['estado_pago'],
            'titular' => $row['titular'],
            'id_rifa' => $row['id_rifa'],
            'telefono' => substr($row['telefono'], 0, 4) . '****' . substr($row['telefono'], -2),
            'referencia' => $row['referencia'],
            'boletos' => []
          ];
        }

        // Agregar el número de boleto a la compra
        $compras[$id_compra]['boletos'][] = $row['numero_boleto'];
      }

      return array_values($compras);
    } catch (Exception $e) {
      throw new Exception("Error al obtener los datos de compras: " . $e->getMessage());
    }
  }

  public function obtenerComprasByID($id_entrada)
  {
    try {
      $sql = "SELECT
              cb.id_compra,
              b.id_rifa,
              b.numero_boleto,
              dc.nom_comprador AS cliente,
              dc.ape_comprador AS a_cliente,
              dc.telefono_comprador AS telefono,
              p.metodo AS metodo_pago,
              cb.total_compra,
              cb.estado,
              cb.fecha_compra,
              p.validacion AS estado_pago,
              p.titular,
              p.referencia, 
              p.monto_pagado
              FROM compras_boletos cb
              INNER JOIN detalle_compras dc ON cb.id_compra = dc.id_compra
              INNER JOIN boletos b ON dc.id_boleto = b.id_boleto
              INNER JOIN pagos p ON cb.id_compra = p.id_compra
              WHERE
                cb.id_compra = :id_entrada
              ORDER BY
                cb.fecha_compra DESC";

      $result = $this->db->consultar(
        $sql,
        [':id_entrada' => $id_entrada]
      );

      // Procesar los resultados para agrupar boletos por compra
      $compras = [];
      foreach ($result as $row) {
        $id_compra = $row['id_compra'];

        if (!isset($compras[$id_compra])) {
          // Primera vez que vemos esta compra
          $compras[$id_compra] = [
            'id_compra' => $id_compra,
            'cliente' => ucwords(strtolower($row['cliente'] . " " . $row['a_cliente'])),
            'metodo_pago' => $row['metodo_pago'],
            'total' => $row['total_compra'],
            'monto_pagado' => $row['monto_pagado'],
            'estado' => $row['estado'],
            'fecha_compra' => $row['fecha_compra'],
            'estado_pago' => $row['estado_pago'],
            'titular' => $row['titular'],
            'referencia' => $row['referencia'],
            'id_rifa' => $row['id_rifa'],
            'boletos' => []
          ];
        }

        // Agregar el número de boleto a la compra
        $compras[$id_compra]['boletos'][] = $row['numero_boleto'];
      }

      return array_values($compras);
    } catch (Exception $e) {
      throw new Exception("Error al obtener los datos de compras: " . $e->getMessage());
    }
  }

  public function obtenerComprasByUser($id_entrada)
  {
    try {
      $sql = "SELECT
              cb.id_compra,
              b.id_rifa,
              b.numero_boleto,
              dp.nombre AS cliente,
              dp.apellido AS a_cliente,
              p.metodo AS metodo_pago,
              cb.total_compra,
              cb.estado,
              cb.fecha_compra,
              p.validacion AS estado_pago,
              p.titular,
              p.referencia, 
              p.monto_pagado
              FROM compras_boletos cb
              INNER JOIN detalle_compras dc ON cb.id_compra = dc.id_compra
              INNER JOIN boletos b ON dc.id_boleto = b.id_boleto
              INNER JOIN datos_personales dp ON b.id_usuario = dp.id_usuario  
              INNER JOIN pagos p ON cb.id_compra = p.id_compra
              WHERE
                b.id_usuario = :id_entrada
              ORDER BY
                cb.fecha_compra DESC";

      $result = $this->db->consultar(
        $sql,
        [':id_entrada' => $id_entrada]
      );

      // Procesar los resultados para agrupar boletos por compra
      $compras = [];
      foreach ($result as $row) {
        $id_compra = $row['id_compra'];

        if (!isset($compras[$id_compra])) {
          // Primera vez que vemos esta compra

          $compras[$id_compra] = [
            'id_compra' => $id_compra,
            'cliente' => ucwords(strtolower($row['cliente'] . " " . $row['a_cliente'])),
            'metodo_pago' => $row['metodo_pago'],
            'total' => $row['total_compra'],
            'monto_pagado' => $row['monto_pagado'],
            'estado' => $row['estado'],
            'fecha_compra' => $row['fecha_compra'],
            'estado_pago' => $row['estado_pago'],
            'titular' => $row['titular'],
            'referencia' => $row['referencia'],
            'id_rifa' => $row['id_rifa'],
            'boletos' => []
          ];
        }

        // Agregar el número de boleto a la compra
        $compras[$id_compra]['boletos'][] = $row['numero_boleto'];
      }

      return array_values($compras);
    } catch (Exception $e) {
      throw new Exception("Error al obtener los datos de compras: " . $e->getMessage());
    }
  }

  public function marcarCompraComoPagada($id_comp)
  {
    try {

      $id_compra = htmlspecialchars(strip_tags($id_comp), ENT_QUOTES, 'UTF-8');
      
      $sql = "SELECT dc.id_boleto, p.id_pago FROM compras_boletos cb INNER JOIN detalle_compras dc ON cb.id_compra = dc.id_compra INNER JOIN pagos p on p.id_compra = cb.id_compra WHERE cb.id_compra = :id_compra";
      $result = $this->db->consultar($sql, [':id_compra' => $id_compra]);

      if ($result === false) {
        throw new Exception("Error al consultar los boletos asociados a la compra.");
      }

      $boletos = [];
      $id_pago = null;
      foreach ($result as $row) {
        if (!isset($row['id_boleto'])) {
          throw new Exception("No se encontró el id_boleto en el resultado.");
        }
        $boletos[] = $row['id_boleto'];
        // Tomamos el primer id_pago encontrado (debería ser el mismo para todos)
        if ($id_pago === null && isset($row['id_pago'])) {
          $id_pago = $row['id_pago'];
        }
      }

      foreach ($boletos as $id_boleto) {
        $sqlUpdate = "UPDATE boletos SET estado = 'vendido' WHERE id_boleto = :id_boleto";
        $updateResult = $this->db->ejecutar($sqlUpdate, [':id_boleto' => $id_boleto]);
        if ($updateResult === false) {
          throw new Exception("Error al actualizar el estado del boleto con id $id_boleto.");
        }
      }

      $sql1 = "UPDATE compras_boletos SET estado = 'aprobado' WHERE id_compra = :id_compra";
      $updateCompra = $this->db->ejecutar($sql1, [':id_compra' => $id_compra]);
      if ($updateCompra === false) {
        throw new Exception("Error al actualizar el estado de la compra.");
      }

      // Nueva sentencia para actualizar el estado del pago
      if ($id_pago !== null) {
        $sqlPago = "UPDATE pagos SET validacion = 'aprobado' WHERE id_pago = :id_pago";
        $updatePago = $this->db->ejecutar($sqlPago, [':id_pago' => $id_pago]);
        if ($updatePago === false) {
          throw new Exception("Error al actualizar el estado del pago.");
        }
      } else {
        throw new Exception("No se encontró el id_pago asociado a la compra.");
      }

      return true;
    } catch (Exception $e) {
      error_log("Error en marcarCompraComoPagada: " . $e->getMessage());
      throw new Exception("Error al actualizar el estado: " . $e->getMessage());
    }
  }

  public function marcarCompraComoRechazada($id_comp)
  {
    try {

      $id_compra = htmlspecialchars(strip_tags($id_comp), ENT_QUOTES, 'UTF-8');

      $sql = "SELECT dc.id_boleto, p.id_pago FROM compras_boletos cb INNER JOIN detalle_compras dc ON cb.id_compra = dc.id_compra INNER JOIN pagos p on p.id_compra = cb.id_compra WHERE cb.id_compra = :id_compra";
      $result = $this->db->consultar($sql, [':id_compra' => $id_compra]);

      if ($result === false) {
        throw new Exception("Error al consultar los boletos asociados a la compra.");
      }

      $boletos = [];
      $id_pago = null;
      foreach ($result as $row) {
        if (!isset($row['id_boleto'])) {
          throw new Exception("No se encontró el id_boleto en el resultado.");
        }
        $boletos[] = $row['id_boleto'];
        // Tomamos el primer id_pago encontrado (debería ser el mismo para todos)
        if ($id_pago === null && isset($row['id_pago'])) {
          $id_pago = $row['id_pago'];
        }
      }

      foreach ($boletos as $id_boleto) {
        $sqlUpdate = "UPDATE boletos SET id_usuario = NULL, estado = 'disponible' WHERE id_boleto = :id_boleto";
        $updateResult = $this->db->ejecutar($sqlUpdate, [':id_boleto' => $id_boleto]);
        if ($updateResult === false) {
          throw new Exception("Error al actualizar el estado del boleto con id $id_boleto.");
        }
      }

      $sql1 = "UPDATE compras_boletos SET estado = 'rechazado' WHERE id_compra = :id_compra";
      $updateCompra = $this->db->ejecutar($sql1, [':id_compra' => $id_compra]);
      if ($updateCompra === false) {
        throw new Exception("Error al actualizar el estado de la compra.");
      }

      // Nueva sentencia para actualizar el estado del pago
      if ($id_pago !== null) {
        $sqlPago = "UPDATE pagos SET validacion = 'rechazado' WHERE id_pago = :id_pago";
        $updatePago = $this->db->ejecutar($sqlPago, [':id_pago' => $id_pago]);
        if ($updatePago === false) {
          throw new Exception("Error al actualizar el estado del pago.");
        }
      } else {
        throw new Exception("No se encontró el id_pago asociado a la compra.");
      }

      return true;
    } catch (Exception $e) {
      error_log("Error en marcarCompraComoRechazada: " . $e->getMessage());
      throw new Exception("Error al actualizar el estado: " . $e->getMessage());
    }
  }
}
