<?php
require_once 'Controllers/identificacionController.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$controller = new identificacionController();
$id_empresa = $controller->obtenerIdEmpresaPorUsuario($_SESSION['user_id']);

if ($id_empresa) {
    $matrices = $controller->obtenerMatricesFODA($id_empresa);
    header('Content-Type: application/json');
    echo json_encode($matrices);
} else {
    echo json_encode([]);
}
?>