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
      $sql = "SELECT b.id_boleto, b.id_rifa, b.numero_boleto, b.estado AS estado, c.estado AS rifa_estado, r.id_rifa FROM boletos b INNER JOIN rifas r ON r.id_rifa = b.id_rifa INNER JOIN configuracion c ON c.id_configuracion = r.id_configuracion WHERE c.estado = 1 ORDER BY b.numero_boleto ASC";

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


  // Método para obtener boletos filtrados por id_rifa y/o estado
  public function obtenerBoletosBy($id_boleto, $id_rifa)
  {
    try {
      $sql = "WITH SelectedPurchase AS (
                SELECT
                    dc.id_boleto,
                    cb.id_compra,
                    cb.estado AS purchase_status,
                    dc.nom_comprador,
                    dc.ape_comprador,
                    dc.telefono_comprador,
                    dc.precio_unitario,
                    cb.total_compra,
                    cb.fecha_compra,
                    ROW_NUMBER() OVER (
                        PARTITION BY dc.id_boleto
                        ORDER BY
                            CASE WHEN cb.estado = 'aprobado' THEN 1 ELSE 2 END,
                            cb.fecha_compra DESC
                    ) as rn
                FROM
                    detalle_compras dc
                INNER JOIN
                    compras_boletos cb ON dc.id_compra = cb.id_compra
                WHERE
                    -- Filtramos en la CTE solo por el id_boleto específico
                    dc.id_boleto = :id_boleto_cte
            )
            SELECT
                b.id_rifa,
                b.id_boleto,
                b.numero_boleto,
                b.estado AS boleto_es,
                c.precio_boleto,
                -- Columnas de compra/comprador que serán NULL si la compra seleccionada no es 'aprobado' o no existe
                CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.id_compra END AS id_compra,
                CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.nom_comprador END AS cliente,
                CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.ape_comprador END AS a_cliente,
                CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.telefono_comprador END AS telefono,
                CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.precio_unitario END AS precio_boleto_compra,
                CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.total_compra END AS total_compra,
                CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.purchase_status END AS estado_compra,
                CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.fecha_compra END AS fecha_compra
            FROM
                boletos b
            INNER JOIN
                rifas r ON r.id_rifa = b.id_rifa
            INNER JOIN
                configuracion c ON c.id_configuracion = r.id_configuracion
            LEFT JOIN
                SelectedPurchase sp ON b.id_boleto = sp.id_boleto AND sp.rn = 1
            LEFT JOIN
                usuarios u ON b.id_usuario = u.id_usuario
            WHERE
                b.id_boleto = :id_boleto_main AND b.id_rifa = :id_rifa_main
            LIMIT 1; -- Aseguramos que solo se devuelva una fila de la consulta principal (aunque la lógica de la CTE ya lo debería garantizar para un boleto único)
            ";

      $params = [
        ':id_boleto_cte' => $id_boleto, // Parámetro para la CTE
        ':id_boleto_main' => $id_boleto, // Parámetro para la WHERE principal
        ':id_rifa_main' => $id_rifa    // Parámetro para la WHERE principal
      ];

      $boletos = $this->db->consultar($sql, $params);

      // Si no se encuentra el boleto, devuelve un resultado de "no éxito"
      if (empty($boletos)) {
        return [
          'success' => false,
          'message' => 'Boleto no encontrado o no pertenece a la rifa especificada.',
          'data' => null // Devolvemos null para el dato en caso de no éxito
        ];
      }

      // Procesar el único resultado encontrado
      $boleto = $boletos[0]; // Como esperamos un solo resultado, tomamos el primero

      $data = [
        'id_compra' => $boleto['id_compra'] ?? null,
        'id_rifa' => $boleto['id_rifa'],
        'id_boleto' => $boleto['id_boleto'],
        'numero_boleto' => $boleto['numero_boleto'],
        'cliente' => !empty($boleto['cliente']) ? ucwords(strtolower($boleto['cliente'])) : null,
        'a_cliente' => !empty($boleto['a_cliente']) ? ucwords(strtolower($boleto['a_cliente'])) : null,
        'telefono' => !empty($boleto['telefono']) ? substr($boleto['telefono'], 0, 4) . '****' . substr($boleto['telefono'], -2) : null,
        'precio_boleto' => $boleto['precio_boleto'],
        'precio_boleto_compra' => $boleto['precio_boleto_compra'] ?? null,
        'total_compra' => $boleto['total_compra'] ?? null,
        'estado' => $boleto['estado_compra'] ?? null,
        'boleto_es' => $boleto['boleto_es'],
        'fecha_compra' => $boleto['fecha_compra'] ?? null,
      ];

      return [
        'success' => true,
        'data' => $data,
        'total' => 1 // Siempre será 1 si success es true
      ];
    } catch (Exception $e) {
      // Es buena práctica registrar el error completo para depuración
      error_log("Error al obtener boleto único: " . $e->getMessage() . " - SQL: " . $sql);
      throw new Exception("Error al obtener el boleto: " . $e->getMessage());
    }
  }

  public function obtenerBoletosGandores()
  {
    try {
      $sql = "WITH CompraAprobadaPorBoleto AS (
                  SELECT
                      dc.id_boleto,
                      MAX(cb.id_compra) AS id_compra_valida -- Usamos MAX para elegir una si hay múltiples aprobadas
                  FROM
                      detalle_compras dc
                  INNER JOIN
                      compras_boletos cb ON dc.id_compra = cb.id_compra
                  WHERE
                      cb.estado = 'aprobado'
                  GROUP BY
                      dc.id_boleto
              )
              SELECT
                  b.id_rifa,
                  b.id_boleto,
                  b.numero_boleto,
                  b.estado AS boleto_es,
                  c.precio_boleto, -- Este precio es del boleto en sí, no de la compra
                  -- Columnas de compra/comprador que serán NULL si no hay una compra aprobada
                  capb.id_compra_valida AS id_compra, -- Será NULL si no se encontró una compra aprobada
                  dc.nom_comprador AS cliente,
                  dc.ape_comprador AS a_cliente,
                  dc.telefono_comprador AS telefono,
                  cb.total_compra,
                  cb.estado, -- Este campo mostrará 'aprobado' o NULL
                  cb.fecha_compra
              FROM
                  boletos b
              INNER JOIN
                  rifas r ON r.id_rifa = b.id_rifa
              INNER JOIN
                  configuracion c ON c.id_configuracion = r.id_configuracion
              LEFT JOIN
                  CompraAprobadaPorBoleto capb ON b.id_boleto = capb.id_boleto -- Unimos para obtener el ID de la compra aprobada
              LEFT JOIN
                  detalle_compras dc ON capb.id_compra_valida = dc.id_compra AND b.id_boleto = dc.id_boleto -- Unimos detalle_compras usando el ID de la compra aprobada
              LEFT JOIN
                  compras_boletos cb ON capb.id_compra_valida = cb.id_compra -- Unimos compras_boletos usando el ID de la compra aprobada
              LEFT JOIN
                  usuarios u ON b.id_usuario = u.id_usuario -- Se mantiene este JOIN, aunque no se seleccionan columnas de 'u'
              WHERE
                  c.estado = 2
                  AND b.estado NOT IN ('disponible', 'pendiente', 'vendido', 'reservado')
              ORDER BY
                  r.fecha_creacion DESC;";

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
          'boleto_es' => $boleto['boleto_es'] ?? null,
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

  public function verificarBoletosXCompra($id_rifa, $numero_boleto)
  {
    try {
      // **Validación de parámetros (mejorada):**
      // Los type hints 'int' en la firma de la función ya fuerzan que sean enteros.
      // Si llegan como null o no-enteros, PHP generará un TypeError antes de esta línea.
      // La validación de 'empty' ahora es menos crítica, pero puedes mantenerla si prefieres
      // un control más explícito para strings vacíos o ceros si no son válidos.
      if (empty($id_rifa) || empty($numero_boleto)) {
        throw new Exception("Faltan parámetros requeridos: ID de rifa o número de boleto.");
      }

      // Sanitización: Generalmente, si usas sentencias preparadas con PDO/MySQLi,
      // no necesitas htmlspecialchars/strip_tags para los parámetros numéricos de la consulta SQL.
      // Es útil si los datos van a ser mostrados en HTML.
      // $id_rifa = htmlspecialchars(strip_tags($id_rifa), ENT_QUOTES, 'UTF-8');
      // $numero_boleto = htmlspecialchars(strip_tags($numero_boleto), ENT_QUOTES, 'UTF-8');

      $sql = "WITH SelectedPurchase AS (
                SELECT
                    dc.id_boleto,
                    cb.id_compra,
                    cb.estado AS purchase_status, -- Estado de la compra
                    dc.nom_comprador,
                    dc.ape_comprador,
                    dc.telefono_comprador,
                    dc.precio_unitario,
                    cb.total_compra,
                    cb.fecha_compra,
                    ROW_NUMBER() OVER (
                        PARTITION BY dc.id_boleto
                        ORDER BY
                            CASE WHEN cb.estado = 'aprobado' THEN 1 ELSE 2 END, -- Prioriza las aprobadas
                            cb.fecha_compra DESC -- Luego la más reciente
                    ) as rn
                FROM
                    detalle_compras dc
                INNER JOIN
                    compras_boletos cb ON dc.id_compra = cb.id_compra
                WHERE
                    -- Filtramos en la CTE solo por el id_boleto específico, que obtenemos de la tabla boletos
                    dc.id_boleto = (SELECT b_inner.id_boleto FROM boletos b_inner WHERE b_inner.id_rifa = :id_rifa_cte AND b_inner.numero_boleto = :numero_boleto_cte LIMIT 1)
              )
              SELECT
                  b.id_rifa,
                  b.id_boleto,
                  b.numero_boleto,
                  b.estado AS boleto_es, -- Estado del boleto
                  c.precio_boleto, -- Precio base del boleto desde la configuración
                  -- Columnas de compra/comprador que serán NULL si la compra seleccionada no es 'aprobado' o no existe
                  CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.id_compra END AS id_compra,
                  CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.nom_comprador END AS cliente,
                  CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.ape_comprador END AS a_cliente,
                  CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.telefono_comprador END AS telefono,
                  CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.precio_unitario END AS precio_boleto_compra,
                  CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.total_compra END AS total_compra,
                  CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.purchase_status END AS estado_compra,
                  CASE WHEN sp.purchase_status NOT IN ('aprobado', 'pendiente') THEN NULL ELSE sp.fecha_compra END AS fecha_compra
              FROM
                  boletos b
              INNER JOIN
                  rifas r ON r.id_rifa = b.id_rifa
              INNER JOIN
                  configuracion c ON c.id_configuracion = r.id_configuracion
              LEFT JOIN
                  SelectedPurchase sp ON b.id_boleto = sp.id_boleto AND sp.rn = 1 -- Unimos con la mejor compra para el boleto
              LEFT JOIN
                  usuarios u ON b.id_usuario = u.id_usuario
              WHERE
                  b.id_rifa = :id_rifa_main
                  AND b.numero_boleto = :numero_boleto_main
              LIMIT 1; -- Aseguramos que la consulta principal devuelva una sola fila
            ";

      $params = [
        ":id_rifa_cte" => $id_rifa,
        ":numero_boleto_cte" => $numero_boleto,
        ":id_rifa_main" => $id_rifa,
        ":numero_boleto_main" => $numero_boleto
      ];

      $boletos = $this->db->consultar($sql, $params);

      // **Manejo de resultados para un solo item:**
      if (empty($boletos)) {
        return [
          'success' => false,
          'message' => 'Boleto no encontrado para la rifa y número especificados.',
          'data' => null // Devolvemos null para el dato si no hay resultado
        ];
      }

      // Si hay resultados, esperamos solo uno, así que tomamos el primer elemento.
      $boleto = $boletos[0];

      $data[0] = [
        'id_compra' => $boleto['id_compra'] ?? null,
        'id_rifa' => $boleto['id_rifa'],
        'id_boleto' => $boleto['id_boleto'],
        'numero_boleto' => $boleto['numero_boleto'],
        'cliente' => !empty($boleto['cliente']) ? ucwords(strtolower($boleto['cliente'])) : null,
        'a_cliente' => !empty($boleto['a_cliente']) ? ucwords(strtolower($boleto['a_cliente'])) : null,
        'telefono' => !empty($boleto['telefono']) ? substr($boleto['telefono'], 0, 4) . '****' . substr($boleto['telefono'], -2) : null,
        'precio_boleto' => $boleto['precio_boleto'],
        'precio_boleto_compra' => $boleto['precio_boleto_compra'] ?? null, // Usamos el nombre renombrado
        'total_compra' => $boleto['total_compra'] ?? null,
        'estado' => $boleto['estado_compra'] ?? null, // Usamos el nombre renombrado
        'boleto_es' => $boleto['boleto_es'],
        'fecha_compra' => $boleto['fecha_compra'] ?? null,
      ];

      return [
        'success' => true,
        'data' => $data,
        'total' => 1, // Siempre es 1 cuando se encuentra un boleto
        'marco' => ["comprados" => "0"], // Mantengo este campo si es parte de tu respuesta esperada
      ];
    } catch (Exception $e) {
      // Es buena práctica registrar el error completo para depuración
      error_log("Error al verificar boleto por compra: " . $e->getMessage());
      throw new Exception("Error al verificar boleto: " . $e->getMessage());
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
