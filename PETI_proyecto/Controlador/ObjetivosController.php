<?php
require_once 'model/Empresa.php';
require_once 'model/ObjetivoEstrategico.php';
require_once 'model/ObjetivoEspecifico.php';

class ObjetivosController {

    private $empresaModel;
    private $objEstrategicoModel;
    private $objEspecificoModel;

    public function __construct($conexion) {
        $this->empresaModel = new EmpresaModel($conexion);
        $this->objEstrategicoModel = new ObjetivoEstrategicoModel($conexion);
        $this->objEspecificoModel = new ObjetivoEspecificoModel($conexion);
    }

    public function mostrar($id_empresa) {
        $empresa = $this->empresaModel->getById($id_empresa);
        $objetivos_estrategicos = $this->objEstrategicoModel->getByEmpresa($id_empresa);

        foreach ($objetivos_estrategicos as &$obj) {
            $obj['especificos'] = $this->objEspecificoModel->getByObjetivoEstrategico($obj['id_obj_estra']);
        }

        include 'Vista/objetivos.php'; 
    }
}
