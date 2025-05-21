<?php

class CameModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerFactoresPorEmpresa($tabla, $id_empresa) {
        $stmt = $this->conexion->prepare("SELECT * FROM $tabla WHERE id_empresa = ?");
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function guardarAccionCAME($id_empresa, $tipo, $id_factor, $descripcion) {
        $stmt = $this->conexion->prepare("INSERT INTO tb_came (id_empresa, tipo, id_factor, descripcion_accion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isis", $id_empresa, $tipo, $id_factor, $descripcion);
        return $stmt->execute();
    }

    public function obtenerAccionesCAME($id_empresa, $tipo) {
        $stmt = $this->conexion->prepare("SELECT * FROM tb_came WHERE id_empresa = ? AND tipo = ?");
        $stmt->bind_param("is", $id_empresa, $tipo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
