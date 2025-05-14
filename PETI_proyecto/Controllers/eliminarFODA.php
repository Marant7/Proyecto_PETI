<?php
header('Content-Type: application/json');

require_once '../config/clsconexion.php';

$conexion = new clsConexion();
$db = $conexion->getConexion();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !isset($data['tipo'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$id = intval($data['id']);
$tipo = $data['tipo'];

$tabla = '';
$campo = '';

switch ($tipo) {
    case 'Fortaleza':
        $tabla = 'tb_fortalezas';
        $campo = 'id_fortaleza';
        break;
    case 'Oportunidad':
        $tabla = 'tb_oportunidades';
        $campo = 'id_oportunidad';
        break;
    case 'Debilidad':
        $tabla = 'tb_debilidades';
        $campo = 'id_debilidad';
        break;
    case 'Amenaza':
        $tabla = 'tb_amenazas';
        $campo = 'id_amenaza';
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Tipo inválido.']);
        exit;
}

$query = "DELETE FROM $tabla WHERE $campo = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conexion->Cerrarconex();
