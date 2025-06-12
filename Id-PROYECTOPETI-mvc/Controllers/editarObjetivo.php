<?php
require_once '../config/clsconexion.php';
require_once '../Models/ObjetivoEstrategicoModel.php';
require_once '../Models/ObjetivoEspecificoModel.php';

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['idObjetivo']) || !isset($input['nombreObjetivo']) || !isset($input['objetivosEspecificos'])) {
        throw new Exception('Datos de entrada no válidos');
    }
    
    $idObjetivo = $input['idObjetivo'];
    $nombreObjetivo = trim($input['nombreObjetivo']);
    $objetivosEspecificos = $input['objetivosEspecificos'];
    
    if (empty($nombreObjetivo)) {
        throw new Exception('El nombre del objetivo estratégico no puede estar vacío');
    }
    
    if (count($objetivosEspecificos) === 0) {
        throw new Exception('Debe haber al menos un objetivo específico');
    }
    
    $conexion = new clsConexion();
    $db = $conexion->getConexion();
    $db->begin_transaction();
    
    try {
        // Actualizar objetivo estratégico
        $modelEstrategico = new ObjetivoEstrategicoModel($db);
        $modelEstrategico->actualizarObjetivo($idObjetivo, $nombreObjetivo);
        
        // Procesar objetivos específicos
        $modelEspecifico = new ObjetivoEspecificoModel($db);
        
        foreach ($objetivosEspecificos as $especifico) {
            if ($especifico['id'] === 'nuevo') {
                // Insertar nuevo objetivo específico
                $modelEspecifico->agregarObjetivoEspecifico($especifico['descripcion'], $idObjetivo);
            } else {
                // Actualizar objetivo específico existente
                $modelEspecifico->actualizarObjetivoEspecifico($especifico['id'], $especifico['descripcion']);
            }
        }
        
        $db->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Objetivo actualizado correctamente'
        ]);
    } catch (Exception $e) {
        $db->rollback();
        throw $e;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>