<?php

namespace App\Models;

use App\config\Conexion;
use Exception;

class MonedaModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Conexion();
    }

    public function getPrecioActual(string $moneda)
    {
        try {
            $sql = "SELECT * FROM moneda_actual WHERE moneda = :moneda";
            $stmt = $this->db->consultar(
                $sql,
                [':moneda' => $moneda]
            );

            return [
                "data" => [
                    "moneda" => $stmt[0]['moneda'],
                    "precio" => $stmt[0]['precio'],
                    "ultima_actualizacion" => $stmt[0]['ultima_actualizacion'],
                ],
                "message" => "Precios actuales obtenidos exitosamente."
            ];
        } catch (Exception $e) {
            // Log del error (opcional)
            throw new Exception("Error al obtener los datos de moneda: " . $e->getMessage());
        }
    }

    public function actualizarPrecio(string $moneda, float $precio): bool
    {
        try {

            // Verificar si existen los registros para VES y COP
            $sql_check = "SELECT COUNT(*) FROM moneda_actual WHERE moneda IN ('VES', 'COP')";
            $stmt_check = $this->db->consultar($sql_check, []);
            $count = $stmt_check[0]['COUNT(*)'];

            // Si no existen, crear los registros
            if ($count < 2) {
                if (!in_array('VES', array_column($this->db->consultar("SELECT moneda FROM moneda_actual", []), 'moneda'))) {
                    $sql_insert_ves = "INSERT IGNORE INTO moneda_actual (moneda, precio, ultima_actualizacion) VALUES ('VES', 0, NOW())";
                    $this->db->ejecutar($sql_insert_ves, []);
                }
                if (!in_array('COP', array_column($this->db->consultar("SELECT moneda FROM moneda_actual", []), 'moneda'))) {
                    $sql_insert_cop = "INSERT IGNORE INTO moneda_actual (moneda, precio, ultima_actualizacion) VALUES ('COP', 0, NOW())";
                    $this->db->ejecutar($sql_insert_cop, []);
                }
            }

            $sql1 = "UPDATE moneda_actual SET precio = :precio, ultima_actualizacion = NOW() WHERE moneda_actual.moneda = :moneda";
            $this->db->ejecutar(
                $sql1,
                [
                    ':moneda' => $moneda,
                    ':precio' => $precio
                ]
            );

            $sql = "INSERT INTO historico_precio_moneda (moneda, precio) VALUES (:moneda, :precio) ON DUPLICATE KEY UPDATE precio = VALUES(precio), fecha_hora = NOW()";
            $this->db->ejecutar(
                $sql,
                [
                    ':moneda' => $moneda,
                    ':precio' => $precio
                ]
            );

            return true;
        } catch (Exception $e) {
            // Log del error (opcional)
            throw new Exception("Error al actualizar montos: " . $e->getMessage());
        }
    }

    // Puedes agregar más métodos si necesitas interactuar con la tabla de otras formas
}
