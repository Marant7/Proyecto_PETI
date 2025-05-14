<?php 
require_once __DIR__ . '/../config/clsconexion.php';


class ClsCadenaValor
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = (new clsConexion())->getConexion();
    }

    public function guardarEvaluacion($id_empresa, $respuestas, $porcentaje)
    {
        $sql = "INSERT INTO tb_cadena_valor (
            id_empresa, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10,
            q11, q12, q13, q14, q15, q16, q17, q18, q19, q20,
            q21, q22, q23, q24, q25, porcentaje_resultado
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error en prepare: " . $this->conexion->error);
        }

        // Asignar valores (todos tinyint menos el último decimal)
        $stmt->bind_param(
            "iiiiiiiiiiiiiiiiiiiiiiiii",
            $id_empresa,
            ...$respuestas,
            $porcentaje
        );

        if (!$stmt->execute()) {
            throw new Exception("Error en execute: " . $stmt->error);
        }

        $stmt->close();
    }
}


?>