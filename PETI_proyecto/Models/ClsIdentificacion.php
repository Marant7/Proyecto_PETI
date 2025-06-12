<?php
require_once(__DIR__ . '/../config/clsConexion.php');

class ClsIdentificacion {
    private $conexion;

    public function __construct() {
        $conn = new clsConexion();
        $this->conexion = $conn->getConexion();
    }

    public function obtenerFortalezas($id_empresa) {
        $sql = "SELECT descripcion FROM tb_fortalezas WHERE id_empresa = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerOportunidades($id_empresa) {
        $sql = "SELECT descripcion FROM tb_oportunidades WHERE id_empresa = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerDebilidades($id_empresa) {
        $sql = "SELECT descripcion FROM tb_debilidades WHERE id_empresa = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerAmenazas($id_empresa) {
        $sql = "SELECT descripcion FROM tb_amenazas WHERE id_empresa = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTodoFODA($id_empresa) {
        return [
            'fortalezas'    => $this->obtenerFortalezas($id_empresa),
            'oportunidades' => $this->obtenerOportunidades($id_empresa),
            'debilidades'   => $this->obtenerDebilidades($id_empresa),
            'amenazas'      => $this->obtenerAmenazas($id_empresa),
        ];
    }
}
?>
