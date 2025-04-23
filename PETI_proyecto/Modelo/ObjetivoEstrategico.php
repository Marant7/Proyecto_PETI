<?php
class ObjetivoEstrategicoModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getAll() {
        $query = $this->db->query("SELECT * FROM tb_obj_estra");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByEmpresa($id_empresa) {
        $stmt = $this->db->prepare("SELECT * FROM tb_obj_estra WHERE id_empresa = ?");
        $stmt->execute([$id_empresa]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $stmt = $this->db->prepare("INSERT INTO tb_obj_estra (id_empresa, nombre_obj_estra) VALUES (?, ?)");
        return $stmt->execute([
            $data['id_empresa'],
            $data['nombre_obj_estra']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE tb_obj_estra SET id_empresa = ?, nombre_obj_estra = ? WHERE id_obj_estra = ?");
        return $stmt->execute([
            $data['id_empresa'],
            $data['nombre_obj_estra'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tb_obj_estra WHERE id_obj_estra = ?");
        return $stmt->execute([$id]);
    }
}
