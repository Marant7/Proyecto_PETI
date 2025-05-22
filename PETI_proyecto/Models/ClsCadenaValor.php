<?php 
require_once __DIR__ . '/../config/clsconexion.php';

class ClsCadenaValor {
    private $conexion;

    public function __construct() {
        $this->conexion = (new clsConexion())->getConexion();
    }

    public function guardarEvaluacion($id_empresa, $respuestas, $porcentaje, $resultado) {
        try {
            // Verificar si ya existe una evaluación para esta empresa
            $stmt = $this->conexion->prepare("SELECT id_evaluacion FROM tb_cadena_valor WHERE id_empresa = ?");
            $stmt->bind_param("i", $id_empresa);
            $stmt->execute();
            $resultado_query = $stmt->get_result();
            
            if ($resultado_query->num_rows > 0) {
                // Si existe, actualizamos
                $row = $resultado_query->fetch_assoc();
                $id_evaluacion = $row['id_evaluacion'];
                
                $sql = "UPDATE tb_cadena_valor SET 
                    q1=?, q2=?, q3=?, q4=?, q5=?, q6=?, q7=?, q8=?, q9=?, q10=?,
                    q11=?, q12=?, q13=?, q14=?, q15=?, q16=?, q17=?, q18=?, q19=?, q20=?,
                    q21=?, q22=?, q23=?, q24=?, q25=?, porcentaje_resultado=?, resultado=?,
                    fecha_evaluacion=NOW()
                    WHERE id_evaluacion=?";
                
                $stmt = $this->conexion->prepare($sql);
                
                if (!$stmt) {
                    error_log("Error preparando la actualización: " . $this->conexion->error);
                    return false;
                }
                
                $stmt->bind_param(
                    "iiiiiiiiiiiiiiiiiiiiiiiiidsi",
                    $respuestas['q1'], $respuestas['q2'], $respuestas['q3'], $respuestas['q4'], $respuestas['q5'],
                    $respuestas['q6'], $respuestas['q7'], $respuestas['q8'], $respuestas['q9'], $respuestas['q10'],
                    $respuestas['q11'], $respuestas['q12'], $respuestas['q13'], $respuestas['q14'], $respuestas['q15'],
                    $respuestas['q16'], $respuestas['q17'], $respuestas['q18'], $respuestas['q19'], $respuestas['q20'],
                    $respuestas['q21'], $respuestas['q22'], $respuestas['q23'], $respuestas['q24'], $respuestas['q25'],
                    $porcentaje, $resultado, $id_evaluacion
                );
            } else {
                // Si no existe, insertamos
                $sql = "INSERT INTO tb_cadena_valor (
                    id_empresa, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10,
                    q11, q12, q13, q14, q15, q16, q17, q18, q19, q20,
                    q21, q22, q23, q24, q25, porcentaje_resultado, resultado, fecha_evaluacion
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                
                $stmt = $this->conexion->prepare($sql);
                
                if (!$stmt) {
                    error_log("Error preparando la inserción: " . $this->conexion->error);
                    return false;
                }
                
                $stmt->bind_param(
                    "iiiiiiiiiiiiiiiiiiiiiiiiiids",
                    $id_empresa,
                    $respuestas['q1'], $respuestas['q2'], $respuestas['q3'], $respuestas['q4'], $respuestas['q5'],
                    $respuestas['q6'], $respuestas['q7'], $respuestas['q8'], $respuestas['q9'], $respuestas['q10'],
                    $respuestas['q11'], $respuestas['q12'], $respuestas['q13'], $respuestas['q14'], $respuestas['q15'],
                    $respuestas['q16'], $respuestas['q17'], $respuestas['q18'], $respuestas['q19'], $respuestas['q20'],
                    $respuestas['q21'], $respuestas['q22'], $respuestas['q23'], $respuestas['q24'], $respuestas['q25'],
                    $porcentaje, $resultado
                );
            }
            
            if (!$stmt->execute()) {
                error_log("Error ejecutando la consulta: " . $stmt->error);
                return false;
            }
            
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Error en ClsCadenaValor::guardarEvaluacion: " . $e->getMessage());
            return false;
        }
    }

    // Método adicional para obtener una evaluación existente
    public function obtenerEvaluacion($id_empresa) {
        try {
            $stmt = $this->conexion->prepare("
                SELECT * FROM tb_cadena_valor 
                WHERE id_empresa = ? 
                ORDER BY fecha_evaluacion DESC 
                LIMIT 1
            ");
            $stmt->bind_param("i", $id_empresa);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            if ($resultado->num_rows > 0) {
                return $resultado->fetch_assoc();
            }
            return null;
        } catch (Exception $e) {
            error_log("Error en ClsCadenaValor::obtenerEvaluacion: " . $e->getMessage());
            return null;
        }
    }
}
?>