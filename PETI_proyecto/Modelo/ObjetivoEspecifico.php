<?php
class ObjetivoEspecificoModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getByObjetivoEstrategico($id_obj_estra) {
        $stmt = $this->db->prepare("SELECT * FROM tb_obj_especificos WHERE id_obj_estra = ?");
        $stmt->execute([$id_obj_estra]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $stmt = $this->db->prepare("INSERT INTO tb_obj_especificos (descripcion_espe, id_obj_estra) VALUES (?, ?)");
        return $stmt->execute([
            $data['descripcion_espe'],
            $data['id_obj_estra']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE tb_obj_especificos SET descripcion_espe = ?, id_obj_estra = ? WHERE id_obj_espe = ?");
        return $stmt->execute([
            $data['descripcion_espe'],
            $data['id_obj_estra'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tb_obj_especificos WHERE id_obj_espe = ?");
        return $stmt->execute([$id]);
    }
}
