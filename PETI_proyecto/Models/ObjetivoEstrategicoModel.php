<?php
class ObjetivoEstrategicoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllObjetivosEstrategicos() {
        $query = "SELECT * FROM tb_obj_estra";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarObjetivoEstrategico($nombre, $id_empresa = 1) {
        $query = "INSERT INTO tb_obj_estra (id_empresa, nombre_obj_estra) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("is", $id_empresa, $nombre);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function actualizarObjetivo($id, $nombre) {
        $query = "UPDATE tb_obj_estra SET nombre_obj_estra = ? WHERE id_obj_estra = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    public function eliminarObjetivo($id) {
        $this->db->begin_transaction();
        
        try {
            // Primero eliminamos los objetivos específicos
            $query = "DELETE FROM tb_obj_especificos WHERE id_obj_estra = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            // Luego eliminamos el objetivo estratégico
            $query = "DELETE FROM tb_obj_estra WHERE id_obj_estra = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
?>