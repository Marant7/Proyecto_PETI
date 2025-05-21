<?php

class CameController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function mostrarFormulario($id_empresa) {
        $debilidades = $this->model->obtenerFactoresPorEmpresa("tb_debilidades", $id_empresa);
        $amenazas = $this->model->obtenerFactoresPorEmpresa("tb_amenazas", $id_empresa);
        $fortalezas = $this->model->obtenerFactoresPorEmpresa("tb_fortalezas", $id_empresa);
        $oportunidades = $this->model->obtenerFactoresPorEmpresa("tb_oportunidades", $id_empresa);

        include 'views/came_formulario.php';
    }

    public function guardarAccion() {
        $id_empresa = $_POST['id_empresa'];
        $tipo = $_POST['tipo'];
        $id_factor = $_POST['id_factor'];
        $descripcion = $_POST['descripcion_accion'];

        $this->model->guardarAccionCAME($id_empresa, $tipo, $id_factor, $descripcion);
        header("Location: index.php?accion=mostrarCAME&id_empresa=$id_empresa");
    }
}
