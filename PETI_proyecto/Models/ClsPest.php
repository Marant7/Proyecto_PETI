<?php
class ClsPest {
    private $conn;

    public function __construct() {
        $this->conn = (new clsConexion())->getConexion();
    }

    public function guardarRespuestas($id_empresa, $respuestas) {
        $sql = "INSERT INTO tb_respuestas_pest (
            id_empresa, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10,
            q11, q12, q13, q14, q15, q16, q17, q18, q19, q20,
            q21, q22, q23, q24, q25
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?
        )";
        
        $params = array_merge([$id_empresa], $respuestas);
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
        }
        
        $tipos = str_repeat("i", count($params));
        
        $stmt->bind_param($tipos, ...$params);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al guardar las respuestas: " . $stmt->error);
        }
        
        $stmt->close();
        return true;
    }
}
