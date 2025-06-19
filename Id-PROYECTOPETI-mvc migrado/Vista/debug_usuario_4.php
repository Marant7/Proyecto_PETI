<?php
session_start();

// Debug específico para el usuario ID 4
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Usuario ID 4</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        pre { background-color: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 5px; color: white; font-weight: bold; }
        .btn-primary { background-color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug Específico - Usuario ID 4</h1>
        
        <?php
        try {
            require_once '../config/clsconexion.php';
            require_once '../Controllers/PlanEstrategicoController.php';
            
            $conexion = new clsConexion();
            $pdo = $conexion->getConexion();
            $controller = new PlanEstrategicoController();
            
            // Simular sesión del usuario 4
            $_SESSION['user'] = [
                'id_usuario' => 4,
                'nombre' => 'nuevo',
                'apellido' => 'mario',
                'usuario' => 'admin21',
                'correo' => 'admin@g.com'
            ];
            
            echo '<div class="success">';
            echo '<h2>✅ Sesión Simulada Usuario ID 4</h2>';
            echo '<pre>' . print_r($_SESSION['user'], true) . '</pre>';
            echo '</div>';
            
            // Consulta directa
            echo '<div class="info">';
            echo '<h2>📊 Consulta Directa Usuario ID 4</h2>';
            $stmt = $pdo->prepare("SELECT * FROM tb_plan_estrategico WHERE id_usuario = 4 ORDER BY fecha_creacion DESC");
            $stmt->execute();
            $planes_directos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<p><strong>Planes encontrados:</strong> " . count($planes_directos) . "</p>";
            
            if (count($planes_directos) > 0) {
                echo '<table border="1" style="width:100%; border-collapse: collapse;">';
                echo '<tr><th>ID Plan</th><th>Nombre</th><th>Empresa</th><th>Fecha</th></tr>';
                foreach ($planes_directos as $plan) {
                    echo '<tr>';
                    echo '<td>' . $plan['id_plan'] . '</td>';
                    echo '<td>' . ($plan['nombre_plan'] ?? 'N/A') . '</td>';
                    echo '<td>' . ($plan['empresa'] ?? 'N/A') . '</td>';
                    echo '<td>' . ($plan['fecha_creacion'] ?? 'N/A') . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            echo '</div>';
            
            // Probar controlador
            echo '<div class="info">';
            echo '<h2>⚙️ Prueba del Controlador</h2>';
            
            try {
                $planes_controlador = $controller->obtenerPlanesUsuario(4);
                echo "<p><strong>Resultado controlador:</strong> " . (is_array($planes_controlador) ? count($planes_controlador) . ' planes' : 'Error') . "</p>";
                
                if (is_array($planes_controlador) && count($planes_controlador) > 0) {
                    echo '<h4>Planes desde controlador:</h4>';
                    echo '<pre>' . print_r($planes_controlador, true) . '</pre>';
                } else {
                    echo '<p class="error">❌ El controlador no devolvió planes</p>';
                }
            } catch (Exception $e) {
                echo '<p class="error">❌ Error en controlador: ' . $e->getMessage() . '</p>';
            }
            echo '</div>';
            
            // Probar home.php directamente
            echo '<div class="info">';
            echo '<h2>🏠 Simulación de home.php</h2>';
            
            $user = $_SESSION['user'];
            $user_id = $user['id_usuario'] ?? $user['id'] ?? null;
            
            echo "<p><strong>ID Usuario obtenido en home:</strong> $user_id</p>";
            
            if ($user_id) {
                $planes_home = $controller->obtenerPlanesUsuario($user_id);
                echo "<p><strong>Planes obtenidos en home:</strong> " . (is_array($planes_home) ? count($planes_home) : 'Error') . "</p>";
                
                if (is_array($planes_home)) {
                    echo '<pre>' . print_r($planes_home, true) . '</pre>';
                }
            }
            echo '</div>';
            
            // Verificar logs de PHP
            echo '<div class="info">';
            echo '<h2>📝 Logs de Debug</h2>';
            echo '<p>Verificar logs en el archivo de errores de PHP para más detalles.</p>';
            
            // Activar logging detallado
            error_log("DEBUG - Usuario ID 4 tiene " . count($planes_directos) . " planes en BD");
            error_log("DEBUG - Controlador devolvió " . (is_array($planes_controlador) ? count($planes_controlador) : 'error') . " planes");
            
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<h2>❌ Error en Debug</h2>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
        ?>
        
        <div class="info">
            <h2>🔗 Navegación</h2>
            <a href="home.php" class="btn btn-primary">🏠 Probar Dashboard</a>
        </div>
    </div>
</body>
</html>
