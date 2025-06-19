<?php
// Script para gestionar usuarios de prueba
session_start();

require_once '../config/clsconexion.php';

try {
    $conexion = new clsConexion();
    $pdo = $conexion->getConexion();
    
    // Verificar si existen usuarios
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tb_usuario");
    $stmt->execute();
    $total_usuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Si no hay usuarios, crear uno de prueba
    if ($total_usuarios == 0) {
        $stmt = $pdo->prepare("
            INSERT INTO tb_usuario (nombre_usuario, email_usuario, password_usuario, fecha_registro) 
            VALUES (?, ?, ?, ?)
        ");
        $password_hash = password_hash('123456', PASSWORD_DEFAULT);
        $fecha_actual = date('Y-m-d H:i:s');
        
        $stmt->execute([
            'Usuario Prueba',
            'test@example.com', 
            $password_hash,
            $fecha_actual
        ]);
        
        echo "✅ Usuario de prueba creado:<br>";
        echo "Email: test@example.com<br>";
        echo "Password: 123456<br>";
    } else {
        echo "👥 Ya existen $total_usuarios usuario(s) en el sistema.<br>";
    }
    
    // Mostrar usuarios existentes
    $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario, email_usuario, fecha_registro FROM tb_usuario LIMIT 5");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Usuarios disponibles:</h3>";
    foreach ($usuarios as $usuario) {
        echo "• ID: {$usuario['id_usuario']}, Nombre: {$usuario['nombre_usuario']}, Email: {$usuario['email_usuario']}<br>";
    }
    
    // Auto-login para pruebas
    if (!isset($_SESSION['user']) && count($usuarios) > 0) {
        $_SESSION['user'] = $usuarios[0];  // Login automático con el primer usuario
        echo "<br>🔐 Auto-login realizado con el primer usuario disponible.";
    }
    
    echo "<br><br><a href='validacion_integral.php'>🔍 Ejecutar Validación Integral</a>";
    echo " | <a href='home.php'>🏠 Ir al Dashboard</a>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
