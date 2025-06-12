<?php
require_once '../config/clsconexion.php';

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['objetivoEstrategico']) || !isset($input['objetivosEspecificos'])) {
        throw new Exception('Datos de entrada no válidos');
    }
    
    $objetivoEstrategico = trim($input['objetivoEstrategico']);
    $objetivosEspecificos = $input['objetivosEspecificos'];
    
    if (empty($objetivoEstrategico)) {
        throw new Exception('El objetivo estratégico no puede estar vacío');
    }
    
    if (count($objetivosEspecificos) === 0) {
        throw new Exception('Debe agregar al menos un objetivo específico');
    }
    
    $conexion = new clsConexion();
    $db = $conexion->getConexion();
    $db->begin_transaction();
    
    try {
        // Insertar objetivo estratégico (asumiendo id_empresa = 1)
        $query = "INSERT INTO tb_obj_estra (id_empresa, nombre_obj_estra) VALUES (1, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $objetivoEstrategico);
        $stmt->execute();
        
        $idObjetivo = $db->insert_id;
        
        // Insertar objetivos específicos
        $query = "INSERT INTO tb_obj_especificos (descripcion_espe, id_obj_estra) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        
        foreach ($objetivosEspecificos as $especifico) {
            $especifico = trim($especifico);
            if (!empty($especifico)) {
                $stmt->bind_param("si", $especifico, $idObjetivo);
                $stmt->execute();
            }
        }
        
        $db->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Objetivo agregado correctamente'
        ]);
    } catch (Exception $e) {
        $db->rollback();
        throw new Exception('Error al guardar en la base de datos: ' . $e->getMessage());
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>