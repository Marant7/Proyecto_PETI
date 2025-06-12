<?php
require_once '../config/clsconexion.php';

header('Content-Type: application/json');

try {
    // Validar entrada
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception('ID de objetivo no válido');
    }

    $id = $_POST['id'];
    $conexion = new clsConexion();
    $db = $conexion->getConexion();

    // Iniciar transacción
    $db->begin_transaction();

    try {
        // 1. Eliminar objetivos específicos relacionados
        $queryDeleteEspecificos = "DELETE FROM tb_obj_especificos WHERE id_obj_estra = ?";
        $stmtEspecificos = $db->prepare($queryDeleteEspecificos);
        $stmtEspecificos->bind_param("i", $id);
        $stmtEspecificos->execute();

        // 2. Eliminar objetivo estratégico
        $queryDeleteEstrategico = "DELETE FROM tb_obj_estra WHERE id_obj_estra = ?";
        $stmtEstrategico = $db->prepare($queryDeleteEstrategico);
        $stmtEstrategico->bind_param("i", $id);
        $stmtEstrategico->execute();

        // Confirmar transacción
        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Objetivo eliminado correctamente'
        ]);
    } catch (Exception $e) {
        // Revertir transacción en caso de error
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