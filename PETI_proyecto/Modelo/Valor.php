<?php
class Valor {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function listarValores() {
        $sql = "SELECT * FROM tb_valores";
        return $this->db->ConsultaResult($sql);
    }

    public function guardarValor($valor, $id_empresa) {
        $sql = "INSERT INTO tb_valores (valor, id_empresa) VALUES ('$valor', '$id_empresa')";
        return $this->db->Consulta($sql);
    }

    public function eliminarValor($id) {
        $sql = "DELETE FROM tb_valores WHERE id_valores = $id";
        return $this->db->Consulta($sql);
    }

    public function obtenerValor($id) {
        $sql = "SELECT * FROM tb_valores WHERE id_valores = $id";
        $res = $this->db->ConsultaResult($sql);
        return $res->fetch_assoc();
    }

    public function actualizarValor($id, $valor, $id_empresa) {
        $sql = "UPDATE tb_valores SET valor='$valor', id_empresa='$id_empresa' WHERE id_valores=$id";
        return $this->db->Consulta($sql);
    }
}
?>
