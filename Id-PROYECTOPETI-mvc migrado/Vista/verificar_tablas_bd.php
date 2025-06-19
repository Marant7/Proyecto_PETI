<?php
// Script para verificar el nombre real de las tablas en la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Tablas de BD</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 5px; color: white; font-weight: bold; }
        .btn-primary { background-color: #007bff; }
        .btn-success { background-color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Verificación de Tablas en Base de Datos</h1>
        
        <?php
        try {
            require_once '../config/clsconexion.php';
            $conexion = new clsConexion();
            $pdo = $conexion->getConexion();
            
            echo '<div class="success">';
            echo '<h2>✅ Conexión Exitosa a Base de Datos</h2>';
            echo '</div>';
            
            // Mostrar todas las tablas de la base de datos
            echo '<div class="info">';
            echo '<h2>📋 Todas las Tablas en la Base de Datos</h2>';
            
            $stmt = $pdo->prepare("SHOW TABLES");
            $stmt->execute();
            $tablas = $stmt->fetchAll(PDO::FETCH_NUM);
            
            echo '<table>';
            echo '<tr><th>#</th><th>Nombre de Tabla</th><th>Acciones</th></tr>';
            foreach ($tablas as $index => $tabla) {
                $nombre_tabla = $tabla[0];
                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td><strong>' . $nombre_tabla . '</strong></td>';
                echo '<td>';
                if (strpos($nombre_tabla, 'plan') !== false || strpos($nombre_tabla, 'estrateg') !== false) {
                    echo '<span style="background-color: #28a745; color: white; padding: 3px 6px; border-radius: 3px;">RELACIONADA</span>';
                }
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';
            
            // Buscar específicamente tablas relacionadas con planes
            echo '<div class="info">';
            echo '<h2>🎯 Tablas Relacionadas con Planes</h2>';
            
            $tablas_plan = [];
            foreach ($tablas as $tabla) {
                $nombre_tabla = $tabla[0];
                if (strpos($nombre_tabla, 'plan') !== false || 
                    strpos($nombre_tabla, 'estrateg') !== false ||
                    strpos($nombre_tabla, 'usuario') !== false) {
                    $tablas_plan[] = $nombre_tabla;
                }
            }
            
            if (count($tablas_plan) > 0) {
                echo '<p><strong>Tablas encontradas relacionadas con planes:</strong></p>';
                echo '<ul>';
                foreach ($tablas_plan as $tabla) {
                    echo '<li><strong>' . $tabla . '</strong>';
                    
                    // Mostrar estructura de cada tabla relacionada
                    $stmt = $pdo->prepare("DESCRIBE `$tabla`");
                    $stmt->execute();
                    $campos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo ' (' . count($campos) . ' campos)';
                    echo '<br><small>Campos: ';
                    $nombres_campos = array_map(function($campo) { return $campo['Field']; }, $campos);
                    echo implode(', ', $nombres_campos);
                    echo '</small>';
                    echo '</li><br>';
                }
                echo '</ul>';
            } else {
                echo '<p class="error">No se encontraron tablas relacionadas con planes</p>';
            }
            echo '</div>';
            
            // Mostrar contenido de tabla de usuarios
            echo '<div class="info">';
            echo '<h2>👤 Verificar Tabla de Usuarios</h2>';
            
            $tabla_usuario = null;
            foreach ($tablas as $tabla) {
                if (strpos($tabla[0], 'usuario') !== false) {
                    $tabla_usuario = $tabla[0];
                    break;
                }
            }
            
            if ($tabla_usuario) {
                echo "<p><strong>Tabla de usuarios encontrada:</strong> $tabla_usuario</p>";
                
                $stmt = $pdo->prepare("SELECT * FROM `$tabla_usuario` LIMIT 5");
                $stmt->execute();
                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($usuarios) > 0) {
                    echo '<table>';
                    echo '<tr>';
                    foreach (array_keys($usuarios[0]) as $campo) {
                        echo '<th>' . $campo . '</th>';
                    }
                    echo '</tr>';
                    
                    foreach ($usuarios as $usuario) {
                        echo '<tr>';
                        foreach ($usuario as $valor) {
                            echo '<td>' . htmlspecialchars($valor) . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>No hay usuarios en la tabla</p>';
                }
            } else {
                echo '<p class="error">No se encontró tabla de usuarios</p>';
            }
            echo '</div>';
            
            // Mostrar contenido de la tabla principal de planes (cualquiera que sea)
            echo '<div class="info">';
            echo '<h2>📊 Verificar Tabla Principal de Planes</h2>';
            
            $tabla_plan_principal = null;
            $posibles_nombres = ['tb_planes_estrategicos', 'tb_plan_estrategico', 'planes_estrategicos', 'plan_estrategico'];
            
            foreach ($posibles_nombres as $nombre) {
                if (in_array($nombre, array_column($tablas, 0))) {
                    $tabla_plan_principal = $nombre;
                    break;
                }
            }
            
            if ($tabla_plan_principal) {
                echo "<p><strong>Tabla principal encontrada:</strong> $tabla_plan_principal</p>";
                
                $stmt = $pdo->prepare("SELECT * FROM `$tabla_plan_principal` LIMIT 10");
                $stmt->execute();
                $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<p><strong>Total de registros:</strong> " . count($planes) . "</p>";
                
                if (count($planes) > 0) {
                    echo '<table>';
                    echo '<tr>';
                    foreach (array_keys($planes[0]) as $campo) {
                        echo '<th>' . $campo . '</th>';
                    }
                    echo '</tr>';
                    
                    foreach ($planes as $plan) {
                        echo '<tr>';
                        foreach ($plan as $valor) {
                            echo '<td>' . htmlspecialchars($valor) . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>No hay planes en la tabla</p>';
                }
            } else {
                echo '<p class="error">No se encontró tabla principal de planes con nombres comunes</p>';
            }
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<h2>❌ Error de Conexión</h2>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
        ?>
        
        <div class="info">
            <h2>🔗 Navegación</h2>
            <a href="login.php" class="btn btn-primary">🔐 Iniciar Sesión</a>
            <a href="home.php" class="btn btn-success">🏠 Dashboard</a>
        </div>
    </div>
</body>
</html>
