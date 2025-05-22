<?php
require_once __DIR__ . '/../config/clsconexion.php';

class PorterModel {
    private $conexion;
    private $totalFactoresEnTabla;

    public function __construct() {
        $this->conexion = (new clsConexion())->getConexion();
        $this->totalFactoresEnTabla = 17; 
    }

    public function guardarOActualizarAnalisis($id_empresa, $datosAnalisis) {
        try {
            $stmt_check = $this->conexion->prepare("SELECT id_fp FROM tb_fuerza_porter WHERE id_empresa = ?");
            if (!$stmt_check) {
                error_log("PorterModel - Error preparando SELECT: " . $this->conexion->error);
                return false;
            }
            $stmt_check->bind_param("i", $id_empresa);
            $stmt_check->execute();
            $resultado_check = $stmt_check->get_result();
            $id_fp_existente = null;
            if ($fila = $resultado_check->fetch_assoc()) {
                $id_fp_existente = $fila['id_fp'];
            }
            $stmt_check->close();

            $tipos_bind = "";
            $valores_bind = [];

            $columnas_q_factores_set = [];
            $nombres_columnas_q_insert = []; 
            $placeholders_q_insert = []; 
            
            for ($i = 0; $i < $this->totalFactoresEnTabla; $i++) {
                $nombre_columna_db = "q" . $i;
                $nombre_input_form = "factor_" . $i;
                $valor_factor = isset($datosAnalisis[$nombre_input_form]) ? intval($datosAnalisis[$nombre_input_form]) : null;

                $columnas_q_factores_set[] = "`" . $nombre_columna_db . "` = ?";
                $nombres_columnas_q_insert[] = "`" . $nombre_columna_db . "`";
                $placeholders_q_insert[] = "?";
                
                $tipos_bind .= "i"; 
                $valores_bind[] = $valor_factor;
            }

            if ($id_fp_existente !== null) {
                $columnas_set_final = $columnas_q_factores_set; 

                $columnas_set_final[] = "`puntaje_total` = ?";
                $tipos_bind_update = $tipos_bind . "i"; 
                $valores_bind_update = array_merge($valores_bind, [$datosAnalisis['puntaje_total']]);

                $columnas_set_final[] = "`texto_conclusion_generada` = ?";
                $tipos_bind_update .= "s"; 
                $valores_bind_update = array_merge($valores_bind_update, [$datosAnalisis['texto_conclusion_generada']]);
                
                $columnas_set_final[] = "`fecha_analisis` = NOW()";

                $sql = "UPDATE tb_fuerza_porter SET " . implode(", ", $columnas_set_final) . " WHERE id_fp = ?";
                $tipos_bind_final = $tipos_bind_update . "i";
                $valores_bind_final = array_merge($valores_bind_update, [$id_fp_existente]);

            } else {
                $nombres_columnas_insert_final = ["`id_empresa`"];
                $placeholders_insert_final = ["?"];
                $tipos_insert_final = "i";
                $valores_insert_final = [$id_empresa];

                $nombres_columnas_insert_final = array_merge($nombres_columnas_insert_final, $nombres_columnas_q_insert);
                $placeholders_insert_final = array_merge($placeholders_insert_final, $placeholders_q_insert);
                $tipos_insert_final .= $tipos_bind; 
                $valores_insert_final = array_merge($valores_insert_final, $valores_bind); 

                $nombres_columnas_insert_final = array_merge($nombres_columnas_insert_final, ["`puntaje_total`", "`texto_conclusion_generada`", "`fecha_analisis`"]);
                $placeholders_insert_final = array_merge($placeholders_insert_final, ["?", "?", "NOW()"]);
                $tipos_insert_final .= "is"; 

                $valores_insert_final = array_merge($valores_insert_final, [$datosAnalisis['puntaje_total'], $datosAnalisis['texto_conclusion_generada']]);
                
                $sql = "INSERT INTO tb_fuerza_porter (" . implode(", ", $nombres_columnas_insert_final) . ") VALUES (" . implode(", ", $placeholders_insert_final) . ")";
                $tipos_bind_final = $tipos_insert_final;
                $valores_bind_final = $valores_insert_final;
            }

            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                error_log("PorterModel - Error preparando SQL: " . $this->conexion->error . " --- SQL: " . $sql);
                return false;
            }
            
            if (!empty($tipos_bind_final) && !empty($valores_bind_final)) {
                 $stmt->bind_param($tipos_bind_final, ...$valores_bind_final);
            }


            if (!$stmt->execute()) {
                error_log("PorterModel - Error ejecutando SQL: " . $stmt->error);
                $stmt->close();
                return false;
            }

            $stmt->close();
            return true;

        } catch (Exception $e) {
            error_log("PorterModel - Excepción: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerAnalisisPorEmpresa($id_empresa) {
        $stmt = $this->conexion->prepare("SELECT * FROM tb_fuerza_porter WHERE id_empresa = ? ORDER BY fecha_analisis DESC LIMIT 1");
        if (!$stmt) { 
            error_log("PorterModel - Error preparando SELECT para obtener: " . $this->conexion->error);
            return null; 
        }
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = $resultado->fetch_assoc();
        $stmt->close();
        return $datos;
    }
}
?>