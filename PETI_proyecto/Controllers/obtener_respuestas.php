<?php
// Este archivo obtiene las respuestas guardadas del usuario actual
// Debe colocarse en la misma carpeta que tu controlador

require_once __DIR__ . '/../config/clsconexion.php';

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit();
}

$id_usuario = $_SESSION['user_id'];

try {
    // Crear conexión
    $conexion = (new clsConexion())->getConexion();
    
    // Obtener el ID de la empresa asociada al usuario
    $stmt = $conexion->prepare("SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($fila = $resultado->fetch_assoc()) {
        $id_empresa = $fila['id_empresa'];
        
        // Consultar si hay evaluación guardada para esta empresa
        $stmt = $conexion->prepare("SELECT * FROM tb_cadena_valor WHERE id_empresa = ?");
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($evaluacion = $resultado->fetch_assoc()) {
            // Devolver los datos encontrados
            echo json_encode([
                'success' => true, 
                'data' => $evaluacion
            ]);
        } else {
            // No hay evaluación guardada
            echo json_encode(['success' => false, 'message' => 'No hay evaluación guardada']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No se encontró la empresa del usuario']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
}
?>