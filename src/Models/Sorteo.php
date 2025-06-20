<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class SorteoModel
{
  private $db;

  public function __construct()
  {
    $this->db = new Conexion();
  }

  public function obtenerSorteos()
  {
    try {
      $sql = "SELECT
              r.id_rifa,
              r.titulo,
              r.descripcion,
              r.imagen,
              r.fecha_creacion,
              c.estado,
              c.id_configuracion,
              c.boletos_maximos,
              c.precio_boleto
              FROM rifas r
              INNER JOIN configuracion c ON r.id_configuracion = c.id_configuracion
              ORDER BY
                r.fecha_creacion DESC";

      $result = $this->db->consultar($sql, []);

      // Procesar los resultados
      $sorteos = [];
      foreach ($result as $row) {
        $id_rifa = $row['id_rifa'];

        if (!isset($sorteos[$id_rifa])) {
          $sorteos[$id_rifa] = [
            'id_rifa' => $id_rifa,
            'titulo' => $row['titulo'],
            'descripcion' => $row['descripcion'],
            'imagen' => $row['imagen'],
            'fecha_creacion' => $row['fecha_creacion'],
            'estado' => $row['estado'],
            'configuracion' => [
              'id_configuracion' => $row['id_configuracion'],
              'boletos_maximos' => $row['boletos_maximos'],
              'precio_boleto' => $row['precio_boleto'],
            ],
          ];
        }
      }

      return array_values($sorteos);
    } catch (Exception $e) {
      throw new Exception("Error al obtener los datos de los sorteos: " . $e->getMessage());
    }
  }

  public function obtenerSorteosByID($id_sorteo)
  {
    try {
      $sql = "SELECT
                r.id_rifa,
                r.titulo,
                r.descripcion,
                r.imagen,
                r.fecha_creacion,
                c.id_configuracion,
                c.id_usuario,
                u.usuario,
                c.titulo AS titulo_config,
                c.fecha_inicio,
                c.fecha_final,
                c.precio_boleto,
                c.boletos_minimos,
                c.boletos_maximos,
                c.numero_contacto,
                c.url_rifa,
                c.texto_ejemplo,
                c.estado
              FROM rifas r
              INNER JOIN configuracion c ON r.id_configuracion = c.id_configuracion
              INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
              WHERE r.id_rifa = :id_sorteo";

      $result = $this->db->consultar(
        $sql,
        [':id_sorteo' => $id_sorteo]
      );

      $sorteos = [];
      foreach ($result as $row) {
        $sorteos[] = [
          'id_rifa' => $row['id_rifa'],
          'id_configuracion' => $row['id_configuracion'],
          'id_usuario' => $row['id_usuario'],
          'titulo' => $row['titulo_config'],
          'fecha_inicio' => $row['fecha_inicio'],
          'fecha_final' => $row['fecha_final'],
          'precio_boleto' => $row['precio_boleto'],
          'boletos_minimos' => $row['boletos_minimos'],
          'boletos_maximos' => $row['boletos_maximos'],
          'usuario' => $row['usuario'],
          'numero_contacto' => $row['numero_contacto'],
          'url_rifa' => $row['url_rifa'],
          'texto_ejemplo' => $row['texto_ejemplo'],
          'estado' => $row['estado'],
        ];
      }

      return $sorteos;
    } catch (Exception $e) {
      throw new Exception("Error al obtener los datos del sorteo: " . $e->getMessage());
    }
  }

  public function obtenerSorteosByUser($id_usuario)
  {
    try {
      $sql = "SELECT
              r.id_rifa,
              r.titulo,
              r.descripcion,
              r.imagen,
              r.fecha_creacion,
              c.estado,
              c.id_configuracion,
              c.boletos_maximos,
              c.precio_boleto
              FROM rifas r
              INNER JOIN configuracion c ON r.id_configuracion = c.id_configuracion
              INNER JOIN boletos b ON r.id_rifa = b.id_rifa
              WHERE
                b.id_usuario = :id_usuario
              ORDER BY
                r.fecha_creacion DESC";

      $result = $this->db->consultar(
        $sql,
        [':id_usuario' => $id_usuario]
      );

      // Procesar los resultados
      $sorteos = [];
      foreach ($result as $row) {
        $id_rifa = $row['id_rifa'];

        if (!isset($sorteos[$id_rifa])) {
          $sorteos[$id_rifa] = [
            'id_rifa' => $id_rifa,
            'titulo' => $row['titulo'],
            'descripcion' => $row['descripcion'],
            'imagen' => $row['imagen'],
            'fecha_creacion' => $row['fecha_creacion'],
            'estado' => $row['estado'],
            'configuracion' => [
              'id_configuracion' => $row['id_configuracion'],
              'boletos_maximos' => $row['boletos_maximos'],
              'precio_boleto' => $row['precio_boleto'],
            ],
          ];
        }
      }

      return array_values($sorteos);
    } catch (Exception $e) {
      throw new Exception("Error al obtener los datos de los sorteos del usuario: " . $e->getMessage());
    }
  }

  public function obtenerSorteosActivos()
  {
    try {
      $sql = "SELECT
        r.id_rifa,
        r.titulo,
        r.descripcion,
        r.imagen,
        r.fecha_creacion,
        c.estado,
        c.url_rifa,
        c.texto_ejemplo,
        c.numero_contacto,
        c.id_configuracion,
        c.boletos_maximos,
        c.boletos_minimos,
        c.precio_boleto,
        GROUP_CONCAT(p.nombre SEPARATOR ' - ') AS nombres_premios,
        GROUP_CONCAT(p.descripcion SEPARATOR ' || ') AS descripciones_premios
      FROM rifas r
      INNER JOIN configuracion c ON r.id_configuracion = c.id_configuracion
      INNER JOIN premios p ON r.id_rifa = p.id_rifa
      WHERE
        c.estado = 1
      GROUP BY
        r.id_rifa,
        r.titulo,
        r.descripcion,
        r.imagen,
        r.fecha_creacion,
        c.estado,
        c.url_rifa,
        c.texto_ejemplo,
        c.numero_contacto,
        c.id_configuracion,
        c.boletos_maximos,
        c.boletos_minimos,
        c.precio_boleto
      ";

      $result = $this->db->consultar($sql, []);

      // Procesar los resultados
      $sorteos = [];
      foreach ($result as $row) {
        $id_rifa = $row['id_rifa'];

        $nombres_premios_array = explode(' - ', $row["nombres_premios"]);
        $descripciones_premios_array = explode(' || ', $row["descripciones_premios"]);

        // Decodificar cada nombre y descripción de premio, limpiando las barras invertidas y comillas
        foreach ($nombres_premios_array as &$nombre) {
          $nombre = trim($nombre, "\"");
          $nombre = stripslashes($nombre);
          $nombre = json_decode($nombre, true);
          if (!is_array($nombre)) {
            $nombre = '';
          }
        }
        foreach ($descripciones_premios_array as &$desc) {
          $desc = trim($desc, "\"");
          $desc = stripslashes($desc);
          $desc = json_decode($desc, true);
          if (!is_array($desc)) {
            $desc = '';
          }
        }

        if (!isset($sorteos[$id_rifa])) {
          $sorteos[$id_rifa] = [
            'id_rifa' => $id_rifa,
            'titulo' => $row['titulo'],
            'descripcion' => $row['descripcion'],
            'imagen' => $row['imagen'],
            'fecha_creacion' => $row['fecha_creacion'],
            'estado' => $row['estado'],
            'url_rifa' => $row['url_rifa'],
            'numero_contacto' => $row['numero_contacto'],
            'texto_importante' => $row['texto_ejemplo'],
            'configuracion' => [
              'id_configuracion' => $row['id_configuracion'],
              'boletos_maximos' => $row['boletos_maximos'],
              'boletos_minimos' => $row['boletos_minimos'],
              'precio_boleto' => $row['precio_boleto'],
            ],
            'premios' => [
              'nombres' => $nombres_premios_array,
              'descripciones' => $descripciones_premios_array
            ]
          ];
        }
      }

      return array_values($sorteos);
    } catch (Exception $e) {
      throw new Exception("Error al obtener los datos de los sorteos: " . $e->getMessage());
    }
  }
}
