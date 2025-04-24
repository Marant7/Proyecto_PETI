<?php
class ObjetivoEspecificoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getEspecificosByEstrategicoId($id_obj_estra) {
        $query = "SELECT * FROM tb_obj_especificos WHERE id_obj_estra = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_obj_estra); // "i" indica que es un entero
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function agregarObjetivoEspecifico($descripcion, $id_obj_estra) {
        $query = "INSERT INTO tb_obj_especificos (descripcion_espe, id_obj_estra) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $descripcion, $id_obj_estra); // "s" para string, "i" para integer
        return $stmt->execute();
    }
    
    public function actualizarObjetivoEspecifico($id, $descripcion) {
        $query = "UPDATE tb_obj_especificos SET descripcion_espe = ? WHERE id_obj_espe = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $descripcion, $id);
        return $stmt->execute();
    }
    
    public function eliminarPorEstrategico($id_obj_estra) {
        $query = "DELETE FROM tb_obj_especificos WHERE id_obj_estra = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_obj_estra);
        return $stmt->execute();
    }
}
?>