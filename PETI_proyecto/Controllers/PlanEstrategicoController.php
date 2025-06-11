<?php
require_once __DIR__ . "/../Models/PlanEstrategicoModel.php";

class PlanEstrategicoController {
    private $modelo;

    public function __construct() {
        $this->modelo = new PlanEstrategicoModel();
    }

    public function cargarDatosPlanEstrategico() {
        if (!isset($_SESSION['user_id'])) {
            error_log("PlanEstrategicoController: No hay user_id en sesión.");
            return null; 
        }

        $id_usuario = $_SESSION['user_id'];
        
        try {
            $datosPlan = $this->modelo->getResumenPlanEstrategicoCompleto($id_usuario);
            
            if ($datosPlan === null) {
                error_log("PlanEstrategicoController: No se encontraron datos del plan para el usuario ID: " . $id_usuario);
                return $this->getDatosPorDefecto("No se encontraron datos completos del plan para esta empresa.");
            }
            return $datosPlan;

        } catch (Exception $e) {
            error_log("Error en PlanEstrategicoController: " . $e->getMessage());
            return $this->getDatosPorDefecto("Ocurrió un error al cargar los datos del plan: " . $e->getMessage());
        }
    }

    private function getDatosPorDefecto($mensajeError = "Datos no disponibles.") {
        return [
            'error_message' => $mensajeError,
            'nombre_empresa' => "N/A",
            'responsable_nombres' => "N/A",
            'responsable_codigo' => "N/A",
            'logo_placeholder' => "Logo no disponible",
            'mision' => $mensajeError,
            'vision' => $mensajeError,
            'objetivos' => [],
            'foda' => [
                'fortalezas' => [$mensajeError],
                'debilidades' => [$mensajeError],
                'oportunidades' => [$mensajeError],
                'amenazas' => [$mensajeError]
            ],
            'estrategia_identificada' => $mensajeError,
            'conclusiones' => $mensajeError
        ];
    }
}
?>