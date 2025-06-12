<?php
require_once '../config/clsconexion.php';
require_once '../Models/ObjetivoEspecificoModel.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception('ID de objetivo no válido');
    }

    $id = $_GET['id'];
    $conexion = new clsConexion();
    $db = $conexion->getConexion();
    
    $model = new ObjetivoEspecificoModel($db);
    $especificos = $model->getEspecificosByEstrategicoId($id);
    
    echo json_encode([
        'success' => true,
        'objetivosEspecificos' => $especificos
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>