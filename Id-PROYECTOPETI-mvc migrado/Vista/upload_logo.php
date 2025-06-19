<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

if (!isset($_POST['id_plan']) || !isset($_FILES['logo'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit();
}

$id_plan = $_POST['id_plan'];
$file = $_FILES['logo'];

try {
    // Validar archivo
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir el archivo');
    }

    // Validar tipo de archivo
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Tipo de archivo no permitido. Use JPG, PNG o GIF');
    }

    // Validar tamaño (2MB máximo)
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new Exception('El archivo es muy grande. Máximo 2MB');
    }

    // Crear directorio si no existe
    $upload_dir = '../public/assets/logos/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Generar nombre único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'logo_plan_' . $id_plan . '.' . $extension;
    $filepath = $upload_dir . $filename;

    // Mover archivo
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('Error al guardar el archivo');
    }    // Guardar en base de datos
    require_once '../config/clsconexion.php';
    $conexion = new clsConexion();
    $pdo = $conexion->getConexion();

    $stmt = $pdo->prepare("
        UPDATE tb_plan_estrategico 
        SET logo_empresa = ? 
        WHERE id_plan = ?
    ");
    $logo_path = 'public/assets/logos/' . $filename;
    $stmt->execute([$logo_path, $id_plan]);

    echo json_encode([
        'success' => true, 
        'message' => 'Logo subido correctamente',
        'logo_path' => $logo_path
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
