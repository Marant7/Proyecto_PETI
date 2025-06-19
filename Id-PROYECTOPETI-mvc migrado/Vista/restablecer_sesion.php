<?php
session_start();

// Script para restablecer sesión y hacer login automático
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Sesión</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 5px; color: white; font-weight: bold; }
        .btn-primary { background-color: #007bff; }
        .btn-success { background-color: #28a745; }
        .btn-warning { background-color: #ffc107; color: #212529; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Restablecer Sesión</h1>
        
        <?php
        try {
            require_once '../config/clsconexion.php';
            $conexion = new clsConexion();
            $pdo = $conexion->getConexion();
            
            // Verificar sesión actual
            echo '<div class="info">';
            echo '<h2>📊 Estado Actual de Sesión</h2>';
            if (isset($_SESSION['user'])) {
                echo '<p>✅ Hay sesión activa</p>';
                echo '<pre>' . print_r($_SESSION['user'], true) . '</pre>';
            } else {
                echo '<p>❌ No hay sesión activa</p>';
            }
            echo '</div>';
            
            // Buscar usuario en BD
            echo '<div class="info">';
            echo '<h2>👤 Buscar Usuario en BD</h2>';
            
            // Intentar encontrar la tabla de usuarios
            $stmt = $pdo->prepare("SHOW TABLES LIKE '%usuario%'");
            $stmt->execute();
            $tablas_usuario = $stmt->fetchAll(PDO::FETCH_NUM);
            
            if (count($tablas_usuario) > 0) {
                $tabla_usuario = $tablas_usuario[0][0];
                echo "<p><strong>Tabla de usuarios encontrada:</strong> $tabla_usuario</p>";
                
                // Buscar el usuario específico
                $stmt = $pdo->prepare("SELECT * FROM `$tabla_usuario` WHERE id_usuario = 4 OR id = 4");
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($usuario) {
                    echo '<p>✅ Usuario encontrado en BD:</p>';
                    echo '<pre>' . print_r($usuario, true) . '</pre>';
                    
                    // Opción para restablecer sesión
                    if (isset($_POST['restablecer_sesion'])) {
                        $_SESSION['user'] = $usuario;
                        echo '<div class="success">';
                        echo '<h3>🎉 Sesión Restablecida</h3>';
                        echo '<p>La sesión ha sido restablecida con los datos del usuario.</p>';
                        echo '<a href="home.php" class="btn btn-success">🏠 Ir al Dashboard</a>';
                        echo '</div>';
                    } else {
                        echo '<form method="post">';
                        echo '<button type="submit" name="restablecer_sesion" class="btn btn-success">🔄 Restablecer Sesión</button>';
                        echo '</form>';
                    }
                } else {
                    echo '<p class="error">❌ No se encontró el usuario con ID 4</p>';
                    
                    // Mostrar todos los usuarios disponibles
                    $stmt = $pdo->prepare("SELECT * FROM `$tabla_usuario` LIMIT 5");
                    $stmt->execute();
                    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($usuarios) > 0) {
                        echo '<h4>Usuarios disponibles:</h4>';
                        foreach ($usuarios as $user) {
                            echo '<div style="border: 1px solid #ddd; margin: 5px; padding: 10px;">';
                            echo '<strong>ID:</strong> ' . ($user['id_usuario'] ?? $user['id']) . '<br>';
                            echo '<strong>Nombre:</strong> ' . ($user['nombre_usuario'] ?? $user['nombre'] ?? 'No disponible') . '<br>';
                            echo '<strong>Email:</strong> ' . ($user['email_usuario'] ?? $user['email'] ?? 'No disponible') . '<br>';
                            
                            $user_id = $user['id_usuario'] ?? $user['id'];
                            echo '<form method="post" style="display: inline;">';
                            echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
                            echo '<button type="submit" name="login_usuario" class="btn btn-primary">Hacer Login</button>';
                            echo '</form>';
                            echo '</div>';
                        }
                    }
                }
                
                // Proceso de login con usuario específico
                if (isset($_POST['login_usuario']) && isset($_POST['user_id'])) {
                    $user_id = $_POST['user_id'];
                    $stmt = $pdo->prepare("SELECT * FROM `$tabla_usuario` WHERE id_usuario = ? OR id = ?");
                    $stmt->execute([$user_id, $user_id]);
                    $usuario_login = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($usuario_login) {
                        $_SESSION['user'] = $usuario_login;
                        echo '<div class="success">';
                        echo '<h3>🎉 Login Exitoso</h3>';
                        echo '<p>Sesión iniciada con usuario ID: ' . $user_id . '</p>';
                        echo '<a href="home.php" class="btn btn-success">🏠 Ir al Dashboard</a>';
                        echo '</div>';
                    }
                }
                
            } else {
                echo '<p class="error">❌ No se encontró tabla de usuarios</p>';
            }
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<h2>❌ Error</h2>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
        ?>
        
        <div class="info">
            <h2>🔗 Navegación</h2>
            <a href="verificar_tablas_bd.php" class="btn btn-warning">🔍 Verificar Tablas</a>
            <a href="home.php" class="btn btn-primary">🏠 Dashboard</a>
            <a href="login.php" class="btn btn-primary">🔐 Login Manual</a>
        </div>
    </div>
</body>
</html>
