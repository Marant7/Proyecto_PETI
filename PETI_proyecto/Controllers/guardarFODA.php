<?php
header('Content-Type: application/json');
require_once '../config/clsconexion.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validación de datos
if (
    !isset($data['tipo']) ||
    !isset($data['descripcion']) ||
    !isset($data['id_empresa'])
) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
    exit;
}

$tipo = $data['tipo'];
$descripcion = trim($data['descripcion']);
$id_empresa = intval($data['id_empresa']);
$id = isset($data['id']) ? intval($data['id']) : null;

$tabla = '';
$campo_id = '';
switch ($tipo) {
    case 'Fortaleza':
        $tabla = 'tb_fortalezas';
        $campo_id = 'id_fortaleza';
        break;
    case 'Oportunidad':
        $tabla = 'tb_oportunidades';
        $campo_id = 'id_oportunidad';
        break;
    case 'Debilidad':
        $tabla = 'tb_debilidades';
        $campo_id = 'id_debilidad';
        break;
    case 'Amenaza':
        $tabla = 'tb_amenazas';
        $campo_id = 'id_amenaza';
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
        exit;
}

$conexion = new clsConexion();
$db = $conexion->getConexion();

if ($id) {
    // Actualizar si hay ID
    $query = "UPDATE $tabla SET descripcion = ? WHERE $campo_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("si", $descripcion, $id);
} else {
    // Insertar nuevo registro
    $query = "INSERT INTO $tabla (id_empresa, descripcion) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("is", $id_empresa, $descripcion);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conexion->Cerrarconex();
