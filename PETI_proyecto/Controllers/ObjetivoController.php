<?php
require_once 'models/ObjetivoEstrategicoModel.php';
require_once 'models/ObjetivoEspecificoModel.php';

class ObjetivoController {
    private $estrategicoModel;
    private $especificoModel;

    public function __construct($db) {
        $this->estrategicoModel = new ObjetivoEstrategicoModel($db);
        $this->especificoModel = new ObjetivoEspecificoModel($db);
    }

    public function listarObjetivos() {
        // Obtener todos los objetivos estratégicos
        $objetivosEstrategicos = $this->estrategicoModel->getAllObjetivosEstrategicos();
        
        // Para cada objetivo estratégico, obtener sus específicos
        $datos = [];
        foreach ($objetivosEstrategicos as $estrategico) {
            $especificos = $this->especificoModel->getEspecificosByEstrategicoId($estrategico['id_obj_estra']);
            $datos[] = [
                'estrategico' => $estrategico,
                'especificos' => $especificos
            ];
        }
        
        // Cargar la vista
        require 'views/objetivos.php';
    }
}
?>