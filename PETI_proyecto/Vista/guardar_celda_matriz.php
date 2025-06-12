<?php
// Agregar logs para debugging
error_log("guardar_celda_matriz.php iniciado");

require_once 'Controllers/identificacionController.php';

try {
    $controller = new identificacionController();
    error_log("Controller creado exitosamente");
    
    $controller->procesarGuardarCelda();
} catch (Exception $e) {
    error_log("Error en guardar_celda_matriz.php: " . $e->getMessage());
    echo json_encode(['exito' => false, 'mensaje' => 'Error del servidor: ' . $e->getMessage()]);
}
?>