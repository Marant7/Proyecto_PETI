<?php
session_start();

// Verificar datos de resumen ejecutivo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Resumen Ejecutivo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        pre { background-color: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug Resumen Ejecutivo</h1>
        
        <?php
        try {
            require_once '../config/clsconexion.php';
            
            $conexion = new clsConexion();
            $pdo = $conexion->getConexion();
            
            // 1. Ver todos los planes del usuario 4
            echo '<div class="info">';
            echo '<h2>📋 Planes del Usuario ID 4</h2>';
            $stmt = $pdo->prepare("SELECT id_plan, nombre_plan, empresa, fecha_creacion FROM tb_plan_estrategico WHERE id_usuario = 4 ORDER BY fecha_creacion DESC");
            $stmt->execute();
            $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($planes) > 0) {
                echo '<table>';
                echo '<tr><th>ID Plan</th><th>Nombre</th><th>Empresa</th><th>Fecha</th><th>Acciones</th></tr>';
                foreach ($planes as $plan) {
                    echo '<tr>';
                    echo '<td>' . $plan['id_plan'] . '</td>';
                    echo '<td>' . ($plan['nombre_plan'] ?? 'N/A') . '</td>';
                    echo '<td>' . ($plan['empresa'] ?? 'N/A') . '</td>';
                    echo '<td>' . ($plan['fecha_creacion'] ?? 'N/A') . '</td>';
                    echo '<td><a href="?debug_plan=' . $plan['id_plan'] . '">Analizar</a></td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            echo '</div>';
            
            // 2. Si se selecciona un plan específico para analizar
            if (isset($_GET['debug_plan'])) {
                $id_plan = $_GET['debug_plan'];
                
                echo '<div class="success">';
                echo '<h2>🔍 Análisis Detallado - Plan ID: ' . $id_plan . '</h2>';
                  // Verificar tabla tb_sintesis_estrategias
                echo '<h3>📊 Tabla tb_sintesis_estrategias</h3>';
                $stmt = $pdo->prepare("SELECT * FROM tb_sintesis_estrategias WHERE id_estrategia = ?");
                $stmt->execute([$id_plan]);
                $sintesis = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($sintesis) > 0) {
                    echo '<table>';
                    echo '<tr>';
                    foreach (array_keys($sintesis[0]) as $column) {
                        echo '<th>' . $column . '</th>';
                    }
                    echo '</tr>';
                    foreach ($sintesis as $row) {
                        echo '<tr>';
                        foreach ($row as $value) {
                            echo '<td>' . htmlspecialchars(substr($value ?? '', 0, 100)) . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>No hay datos en tb_sintesis_estrategias para este plan</p>';
                }
                
                // Verificar otras tablas relacionadas
                $tablas_relacionadas = [
                    'tb_analisis_pest' => 'Análisis PEST',
                    'tb_matriz_came' => 'Matriz CAME',
                    'tb_fortalezas' => 'Fortalezas',
                    'tb_debilidades' => 'Debilidades',
                    'tb_oportunidades' => 'Oportunidades',
                    'tb_amenazas' => 'Amenazas'
                ];
                  foreach ($tablas_relacionadas as $tabla => $nombre) {
                    echo '<h3>📋 ' . $nombre . ' (' . $tabla . ')</h3>';
                    
                    // Intentar diferentes posibles nombres de columnas
                    $posibles_columnas = ['id_plan', 'id_estrategia', 'plan_id'];
                    $datos = [];
                    
                    foreach ($posibles_columnas as $columna) {
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE $columna = ?");
                            $stmt->execute([$id_plan]);
                            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (count($datos) > 0) {
                                echo '<p>✅ Encontrados ' . count($datos) . ' registros (columna: ' . $columna . ')</p>';
                                break;
                            }
                        } catch (Exception $e) {
                            // Continuar con la siguiente columna
                        }
                    }
                    
                    if (count($datos) > 0) {
                        echo '<pre>' . print_r($datos[0], true) . '</pre>';
                    } else {
                        echo '<p>❌ No hay datos en esta tabla para ninguna columna de relación</p>';
                    }
                }
                
                echo '</div>';
                
                // 3. Probar el método obtenerResumenEjecutivo
                echo '<div class="info">';
                echo '<h2>⚙️ Prueba del Método obtenerResumenEjecutivo</h2>';
                
                try {
                    require_once '../Controllers/PlanEstrategicoController.php';
                    $controller = new PlanEstrategicoController();
                    $resumen = $controller->obtenerResumenEjecutivo($id_plan);
                    
                    echo '<h4>Resultado del método:</h4>';
                    echo '<pre>' . print_r($resumen, true) . '</pre>';
                    
                } catch (Exception $e) {
                    echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
                }
                echo '</div>';
            }
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<h2>❌ Error</h2>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
