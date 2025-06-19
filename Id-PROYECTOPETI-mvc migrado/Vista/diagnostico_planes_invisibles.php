<?php
session_start();

// Script de diagnóstico específico para el problema de visualización de planes
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico - Planes No Visibles</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
        pre { background-color: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 5px; color: white; font-weight: bold; }
        .btn-primary { background-color: #007bff; }
        .btn-success { background-color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnóstico: Planes No Visibles en Dashboard</h1>
        
        <?php
        try {
            require_once '../config/clsconexion.php';
            require_once '../Controllers/PlanEstrategicoController.php';
            
            $conexion = new clsConexion();
            $pdo = $conexion->getConexion();
            $controller = new PlanEstrategicoController();
            
            // 1. Verificar usuario actual
            echo '<div class="info">';
            echo '<h2>1. 👤 Usuario Actual</h2>';
            if (isset($_SESSION['user'])) {
                $user = $_SESSION['user'];
                $user_id = $user['id_usuario'] ?? $user['id'] ?? null;
                echo "<p><strong>ID Usuario:</strong> $user_id</p>";
                echo "<p><strong>Nombre:</strong> " . ($user['nombre'] ?? 'No disponible') . "</p>";
                echo '<pre>' . print_r($user, true) . '</pre>';
            } else {
                echo '<p class="error">No hay sesión activa</p>';
            }
            echo '</div>';
            
            // 2. Consulta directa a la base de datos
            echo '<div class="info">';
            echo '<h2>2. 🗄️ Consulta Directa a BD - Todos los Planes</h2>';
            $stmt = $pdo->prepare("SELECT * FROM tb_planes_estrategicos ORDER BY fecha_creacion DESC");
            $stmt->execute();
            $todos_planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<p><strong>Total de planes en BD:</strong> " . count($todos_planes) . "</p>";
            
            if (count($todos_planes) > 0) {
                echo '<table border="1" style="width:100%; border-collapse: collapse;">';
                echo '<tr><th>ID</th><th>ID Usuario</th><th>Nombre Empresa</th><th>Fecha</th></tr>';
                foreach ($todos_planes as $plan) {
                    echo '<tr>';
                    echo '<td>' . $plan['id'] . '</td>';
                    echo '<td>' . $plan['id_usuario'] . '</td>';
                    echo '<td>' . ($plan['nombre_empresa'] ?? 'N/A') . '</td>';
                    echo '<td>' . ($plan['fecha_creacion'] ?? 'N/A') . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No hay planes en la base de datos</p>';
            }
            echo '</div>';
            
            // 3. Consulta específica para el usuario actual
            if (isset($user_id)) {
                echo '<div class="warning">';
                echo '<h2>3. 🎯 Consulta Específica para Usuario ID: ' . $user_id . '</h2>';
                
                $stmt = $pdo->prepare("SELECT * FROM tb_planes_estrategicos WHERE id_usuario = ? ORDER BY fecha_creacion DESC");
                $stmt->execute([$user_id]);
                $planes_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<p><strong>Planes encontrados para usuario $user_id:</strong> " . count($planes_usuario) . "</p>";
                
                if (count($planes_usuario) > 0) {
                    echo '<h4>Detalles de los planes:</h4>';
                    foreach ($planes_usuario as $plan) {
                        echo '<div style="border: 1px solid #ddd; margin: 10px 0; padding: 10px;">';
                        echo '<strong>Plan ID:</strong> ' . $plan['id'] . '<br>';
                        echo '<strong>Nombre Empresa:</strong> ' . ($plan['nombre_empresa'] ?? 'No definido') . '<br>';
                        echo '<strong>Fecha:</strong> ' . ($plan['fecha_creacion'] ?? 'No definida') . '<br>';
                        echo '<strong>Usuario ID:</strong> ' . $plan['id_usuario'] . '<br>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="error">❌ No se encontraron planes para este usuario en la consulta directa</p>';
                }
                echo '</div>';
                
                // 4. Probar el método del controlador
                echo '<div class="warning">';
                echo '<h2>4. ⚙️ Prueba del Método obtenerPlanesUsuario()</h2>';
                
                try {
                    $planes_controlador = $controller->obtenerPlanesUsuario($user_id);
                    echo "<p><strong>Resultado del controlador:</strong> " . (is_array($planes_controlador) ? count($planes_controlador) . ' planes' : 'Error - no es array') . "</p>";
                    
                    if (is_array($planes_controlador)) {
                        echo '<pre>' . print_r($planes_controlador, true) . '</pre>';
                    } else {
                        echo '<p class="error">El método no retornó un array válido</p>';
                    }
                } catch (Exception $e) {
                    echo '<p class="error">Error en el controlador: ' . $e->getMessage() . '</p>';
                }
                echo '</div>';
                
                // 5. Verificar estructura de la tabla
                echo '<div class="info">';
                echo '<h2>5. 🏗️ Estructura de la Tabla tb_planes_estrategicos</h2>';
                
                $stmt = $pdo->prepare("DESCRIBE tb_planes_estrategicos");
                $stmt->execute();
                $estructura = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<table border="1" style="width:100%; border-collapse: collapse;">';
                echo '<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>';
                foreach ($estructura as $campo) {
                    echo '<tr>';
                    echo '<td>' . $campo['Field'] . '</td>';
                    echo '<td>' . $campo['Type'] . '</td>';
                    echo '<td>' . $campo['Null'] . '</td>';
                    echo '<td>' . $campo['Key'] . '</td>';
                    echo '<td>' . ($campo['Default'] ?? 'NULL') . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
                
                // 6. Test de corrección
                if (count($planes_usuario) > 0 && (!is_array($planes_controlador) || count($planes_controlador) == 0)) {
                    echo '<div class="error">';
                    echo '<h2>6. 🔧 Problema Detectado</h2>';
                    echo '<p><strong>DIAGNÓSTICO:</strong> Los planes existen en la BD pero el controlador no los está recuperando correctamente.</p>';
                    echo '<p><strong>CAUSA PROBABLE:</strong> Error en el método obtenerPlanesUsuario() del controlador.</p>';
                    
                    if (isset($_POST['corregir_metodo'])) {
                        echo '<h4>🛠️ Aplicando Corrección...</h4>';
                        // La corrección se hará después de este bloque
                    } else {
                        echo '<form method="post">';
                        echo '<button type="submit" name="corregir_metodo" class="btn btn-success">🔧 Corregir Método del Controlador</button>';
                        echo '</form>';
                    }
                    echo '</div>';
                }
            }
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<h2>❌ Error en Diagnóstico</h2>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
        ?>
        
        <div class="info">
            <h2>🔗 Navegación</h2>
            <a href="home.php" class="btn btn-primary">🏠 Dashboard</a>
            <a href="validacion_integral.php" class="btn btn-primary">🔍 Validación Integral</a>
        </div>
    </div>
</body>
</html>
