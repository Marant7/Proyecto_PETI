<?php
class EmpresaModel {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getAll() {
        $query = $this->db->query("SELECT * FROM tb_empresa");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tb_empresa WHERE id_empresa = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $stmt = $this->db->prepare("INSERT INTO tb_empresa (id_usuario, nombre_empresa, mision, vision, fecha_creacion, descripcion) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_usuario'],
            $data['nombre_empresa'],
            $data['mision'],
            $data['vision'],
            $data['fecha_creacion'],
            $data['descripcion']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE tb_empresa SET id_usuario = ?, nombre_empresa = ?, mision = ?, vision = ?, fecha_creacion = ?, descripcion = ? WHERE id_empresa = ?");
        return $stmt->execute([
            $data['id_usuario'],
            $data['nombre_empresa'],
            $data['mision'],
            $data['vision'],
            $data['fecha_creacion'],
            $data['descripcion'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tb_empresa WHERE id_empresa = ?");
        return $stmt->execute([$id]);
    }
}
