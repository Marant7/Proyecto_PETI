<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Controllers/identificacionController.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'No autenticado']);
    exit;
}
$controller = new identificacionController();
$id_empresa = $controller->obtenerIdEmpresaPorUsuario($_SESSION['user_id']);
if (!$id_empresa) {
    echo json_encode(['exito' => false, 'mensaje' => 'Empresa no encontrada']);
    exit;
}
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['matrices']) || !is_array($input['matrices'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos inválidos']);
    exit;
}
$todoOk = true;
foreach ($input['matrices'] as $celda) {
    if (!isset($celda['tipo_matriz'], $celda['fila'], $celda['columna'])) continue;
    $valor = is_null($celda['valor']) ? 0 : intval($celda['valor']);
    $res = $controller->guardarCeldaMatriz($id_empresa, $celda['tipo_matriz'], $celda['fila'], $celda['columna'], $valor);
    if (!$res['exito']) $todoOk = false;
}
echo json_encode(['exito' => $todoOk]);
?>