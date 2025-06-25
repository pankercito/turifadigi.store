<?php

namespace App\config;

use Exception;
use PDO;
use PDOException;

class Conexion
{

  private $dbname;
  private $servidor;
  private $usuario;
  private $password;
  private $conexion;

  public function __construct()
  {

    $this->dbname = 'turifadigi';
    $this->servidor = 'localhost';
    $this->usuario = 'turif';
    $this->password = 'Viki321.';
    try {
      $this->conexion = new PDO("mysql:host=$this->servidor;dbname=$this->dbname;charset=utf8mb4", $this->usuario, $this->password);
      $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
      throw new Exception("Error en la conexion: " . $e->getMessage());
    }
  }


  public function ejecutar($sql, array $parametros)
  {
    try {
      $this->conexion->beginTransaction();
      $sentencia = $this->conexion->prepare($sql);

      if (!empty($parametros)) {
        foreach ($parametros as $parametro => $valor) {
          $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
          $sentencia->bindValue($parametro, $valor, $tipo);
        }
      }

      $sentencia->execute();
      $ultimo_id_insertado = $this->conexion->lastInsertId();
      $this->conexion->commit();
      return $ultimo_id_insertado;
    } catch (PDOException $e) {
      $this->conexion->rollBack();
      throw new Exception("Error en la ejecución: " . $e->getMessage());
    }
  }

  public function consultar($sql, array $parametros)
  {
    try {
      $sentencia = $this->conexion->prepare($sql);

      if (!empty($parametros)) {
        foreach ($parametros as $parametro => $valor) {
          $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
          $sentencia->bindValue($parametro, $valor, $tipo);
        }
      }

      $sentencia->execute();
      return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error en la consulta: " . $e->getMessage());
    }
  }

  public function backup()
  {
    try {
      $consulta = $this->conexion->query("SHOW TABLES");
      $tablas = $consulta->fetchAll(PDO::FETCH_COLUMN);
      $respaldo_data = 'SET FOREIGN_KEY_CHECKS = 0;';
      foreach ($tablas as $tabla) {
        $crear = $this->conexion->query("SHOW CREATE TABLE $tabla")->fetch(PDO::FETCH_ASSOC);
        $respaldo_data .= "\n\n" . $crear['Create Table'] . ";\n\n";
        $filas = $this->conexion->query("SELECT * FROM $tabla")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($filas as $fila) {
          $respaldo_data .= "INSERT INTO $tabla VALUES (";
          $respaldo_data .= "'" . implode("','", array_map(function ($valor) {
            return addslashes($valor);
          }, $fila)) . "'";
          $respaldo_data .= ");\n";
        }
      }
      $respaldo_data .= "\n\n" . 'SET FOREIGN_KEY_CHECKS = 1;';
      $path_bck = $_ENV['BCK_BACKUP_PATH'];
      if (!file_exists("storage/")) {
        mkdir("storage/", 0755);
        if (!file_exists("storage/backup/")) {
          mkdir("storage/backup/", 0755);
        }
      } else {
        if (!file_exists("storage/backup/")) {
          mkdir("storage/backup/", 0755);
        }
      }
      $respaldo_archivo = 'backup_' . date("Ymd_His") . '.sql';
      if (file_put_contents($path_bck . $respaldo_archivo, $respaldo_data)) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException $e) {
      die("Error de conexión a la base de datos: " . $e->getMessage());
    }
  }

  public function restore($respaldo_archivo)
  {
    $ruta = $_ENV['BCK_BACKUP_PATH'];
    try {
      $result = $this->conexion->query("SHOW TABLES");
      $tablas = $result->fetchAll(PDO::FETCH_COLUMN);
      $this->conexion->exec("SET FOREIGN_KEY_CHECKS = 0");
      foreach ($tablas as $tabla) {
        $this->conexion->beginTransaction();
        $this->conexion->exec("DROP TABLE IF EXISTS " . $tabla);
        $this->conexion->commit();
      }
      $sql = file_get_contents($ruta . $respaldo_archivo);
      $this->conexion->beginTransaction();
      $this->conexion->exec($sql);
      $this->conexion->commit();
      $this->conexion->exec("SET FOREIGN_KEY_CHECKS = 1");
      return true;
    } catch (PDOException $e) {
      die("Error de conexión a la base de datos: " . $e->getMessage());
      return "Error de conexión a la base de datos: " . $e->getMessage();
    }
  }

  public function restore_factory($respaldo_archivo)
  {
    $ruta = $_ENV['BCK_BACKUP_FACTORY_PATH'];
    try {
      $sql = file_get_contents($ruta . $respaldo_archivo);
      $this->conexion->exec($sql);
      return true;
    } catch (PDOException $e) {
      die("Error de conexión a la base de datos: " . $e->getMessage());
    }
  }

  public function listarBck()
  {
    $ruta = $_ENV['BCK_BACKUP_PATH'];
    $elementos = [];
    if (!file_exists("storage/")) {
      mkdir("storage/", 0755);
      if (!file_exists("storage/backup/")) {
        mkdir("storage/backup/", 0755);
        $elementos = scandir($ruta);
      }
    } else {
      $elementos = scandir($ruta);
    }
    return $elementos;
  }

  public function habilitar_revision_foreign_key()
  {
    try {
      $this->conexion->exec("SET FOREIGN_KEY_CHECKS = 1;");
      return true;
    } catch (PDOException $e) {
      die("Error de conexión a la base de datos: " . $e->getMessage());
      return "Error de conexión a la base de datos: " . $e->getMessage();
    }
  }

  public function deshabilitar_revision_foreign_key()
  {
    try {
      $this->conexion->exec("SET FOREIGN_KEY_CHECKS = 0;");
      return true;
    } catch (PDOException $e) {
      die("Error de conexión a la base de datos: " . $e->getMessage());
      return "Error de conexión a la base de datos: " . $e->getMessage();
    }
  }
}
