<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class DatosPersonales
{
  // Constantes para las tablas y columnas de la base de datos
  const TABLE_NAME = 'datos_personales';
  const COLUMN_ID = 'id_datos';
  const COLUMN_USER_ID = 'id_usuario';
  const COLUMN_NAME = 'nombre';
  const COLUMN_LASTNAME = 'apellido';
  const COLUMN_PHONE = 'telefono';
  const COLUMN_LOCATION = 'ubicacion';
  const COLUMN_DATE = 'fecha_registro';

  private $db;

  /**
   * Constructor: Inicializa la conexión a la base de datos
   */
  public function __construct()
  {
    $this->db = new Conexion();
  }

  /**
   * Inserta datos personales para un usuario
   * @param int $id_usuario - ID del usuario
   * @param array $datos - Datos personales (nombre, apellido, telefono, ubicacion)
   * @return bool - true si se insertó correctamente, false en caso contrario
   */
  public function insert(int $id_usuario, array $datos): bool
  {
    try {
      // Validar datos requeridos
      // Sanitizar datos
      $datos['nombre'] = htmlspecialchars(strip_tags($datos['nombre']));
      $datos['apellido'] = htmlspecialchars(strip_tags($datos['apellido']));
      $datos['telefono'] = htmlspecialchars(strip_tags($datos['telefono']));
      $datos['ubicacion'] = htmlspecialchars(strip_tags($datos['ubicacion']));

      if (
        empty($datos['nombre']) || empty($datos['apellido']) ||
        empty($datos['telefono']) || empty($datos['ubicacion'])
      ) {
        error_log("Error en DatosPersonales::insert: Datos requeridos faltantes");
        return false;
      }

      // Insertar datos personales
      $sql = "INSERT INTO " . self::TABLE_NAME . " 
             (id_usuario, nombre, apellido, telefono, ubicacion) 
             VALUES (:id_usuario, :nombre, :apellido, :telefono, :ubicacion)";

      $this->db->ejecutar($sql, [
        ':id_usuario' => $id_usuario,
        ':nombre' => $datos['nombre'],
        ':apellido' => $datos['apellido'],
        ':telefono' => $datos['telefono'],
        ':ubicacion' => $datos['ubicacion']
      ]);

      return true;
    } catch (Exception $e) {
      error_log("Error en DatosPersonales::insert: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Actualiza los datos personales de un usuario
   * @param int $id_usuario - ID del usuario
   * @param array $datos - Datos personales (nombre, apellido, telefono, ubicacion)
   * @return bool - true si se actualizó correctamente, false en caso contrario
   */
  public function update(int $id_usuario, array $datos): bool
  {
    try {
      // Validar datos requeridos
      if (
        empty($datos['nombre']) || empty($datos['apellido']) ||
        empty($datos['telefono']) || empty($datos['ubicacion'])
      ) {
        return false;
      }

      // Actualizar datos personales
      $sql = "UPDATE " . self::TABLE_NAME . " SET 
             nombre = :nombre, 
             apellido = :apellido, 
             telefono = :telefono, 
             ubicacion = :ubicacion 
             WHERE id_usuario = :id_usuario";

      $this->db->ejecutar($sql, [
        ':id_usuario' => $id_usuario,
        ':nombre' => $datos['nombre'],
        ':apellido' => $datos['apellido'],
        ':telefono' => $datos['telefono'],
        ':ubicacion' => $datos['ubicacion']
      ]);

      return true;
    } catch (Exception $e) {
      error_log("Error en DatosPersonales::update: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene los datos personales de un usuario
   * @param int $id_usuario - ID del usuario
   * @return array|null - Datos personales o null si no existen
   */
  public function getByUsuarioId(int $id_usuario): ?array
  {
    try {
      $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE " . self::COLUMN_USER_ID . " = :id_usuario";
      $result = $this->db->consultar($sql, [':id_usuario' => $id_usuario]);

      return $result ? $result[0] : null;
    } catch (Exception $e) {
      error_log("Error en DatosPersonales::getByUsuarioId: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Verifica si ya existen datos personales para un usuario
   * @param int $id_usuario - ID del usuario
   * @return bool - true si existen, false en caso contrario
   */
  private function existenDatos(int $id_usuario): bool
  {
    try {
      $sql = "SELECT COUNT(*) as count FROM " . self::TABLE_NAME . " WHERE " . self::COLUMN_USER_ID . " = :id_usuario";
      $result = $this->db->consultar($sql, [':id_usuario' => $id_usuario]);

      return $result && $result[0]['count'] > 0;
    } catch (Exception $e) {
      error_log("Error en DatosPersonales::existenDatos: " . $e->getMessage());
      return false;
    }
  }
}
