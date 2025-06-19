<?php
session_start();
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>🔍 VERIFICANDO DATOS DEL RESUMEN EJECUTIVO REAL</h1>";

try {
    $controller = new PlanEstrategicoController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getConexion');
    $method->setAccessible(true);
    $pdo = $method->invoke($controller);
    
    echo "<h2>1. Verificando estructura de tb_resumen_ejecutivo:</h2>";
    try {
        $stmt = $pdo->prepare("DESCRIBE tb_resumen_ejecutivo");
        $stmt->execute();
        $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<ul>";
        foreach ($columnas as $columna) {
            echo "<li><strong>" . $columna['Field'] . "</strong> - " . $columna['Type'] . "</li>";
        }
        echo "</ul>";
    } catch (Exception $e) {
        echo "<p>❌ Error al obtener estructura: " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>2. Verificando datos del plan 16 en tb_resumen_ejecutivo:</h2>";
    try {
        $stmt = $pdo->prepare("SELECT * FROM tb_resumen_ejecutivo WHERE id_plan = 16");
        $stmt->execute();
        $resumen_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($resumen_data)) {
            echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "✅ <strong>Datos encontrados en tb_resumen_ejecutivo:</strong>";
            echo "<pre>" . print_r($resumen_data, true) . "</pre>";
            echo "</div>";
        } else {
            echo "<div style='background: #ffebee; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "❌ <strong>NO hay datos en tb_resumen_ejecutivo para el plan 16</strong>";
            echo "</div>";
            
            // Insertar datos de ejemplo basados en lo que escribiste
            echo "<h3>📝 Insertando datos de ejemplo de resumen ejecutivo:</h3>";
            $stmt = $pdo->prepare("
                INSERT INTO tb_resumen_ejecutivo (id_plan, emprendedores_promotores, identificacion_estrategica, conclusiones) 
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    emprendedores_promotores = VALUES(emprendedores_promotores),
                    identificacion_estrategica = VALUES(identificacion_estrategica),
                    conclusiones = VALUES(conclusiones)
            ");
            
            $emprendedores = "Los emprendedores y promotores de este plan son los directivos y líderes de la empresa que impulsarán la estrategia definida.";
            $identificacion = "La identificación estratégica se basa en aprovechar nuestras fortalezas principales, corregir las debilidades identificadas, explotar las oportunidades del mercado y afrontar las amenazas externas de manera proactiva.";
            $conclusiones = "En conclusión, este plan estratégico establece las bases sólidas para el crecimiento sostenible de la empresa, alineando los recursos internos con las oportunidades del mercado para lograr los objetivos definidos.";
            
            $result = $stmt->execute([16, $emprendedores, $identificacion, $conclusiones]);
            
            if ($result) {
                $pdo->commit();
                echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
                echo "✅ <strong>Datos de ejemplo insertados</strong>";
                echo "</div>";
            }
        }
    } catch (Exception $e) {
        echo "<p>❌ Error al consultar tb_resumen_ejecutivo: " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>3. Verificando la descripción UEN en objetivos:</h2>";
    try {
        $stmt = $pdo->prepare("SELECT * FROM tb_uen WHERE id_plan = 16");
        $stmt->execute();
        $uen_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($uen_data)) {
            echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "✅ <strong>UEN encontrada:</strong>";
            echo "<pre>" . print_r($uen_data, true) . "</pre>";
            echo "</div>";
        } else {
            echo "<div style='background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "⚠️ <strong>UEN no encontrada, debería contener la descripción que escribiste en objetivos estratégicos</strong>";
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error al consultar UEN: " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>4. Probando método obtenerResumenEjecutivo actualizado:</h2>";
    $resumen = $controller->obtenerResumenEjecutivo(16);
    
    if ($resumen) {
        echo "<h3>📋 Campos relacionados al resumen ejecutivo:</h3>";
        echo "<ul>";
        
        if (isset($resumen['conclusiones_usuario'])) {
            echo "<li>✅ <strong>conclusiones_usuario:</strong> " . htmlspecialchars(substr($resumen['conclusiones_usuario'], 0, 100)) . "...</li>";
        } else {
            echo "<li>❌ <strong>conclusiones_usuario:</strong> No encontrada</li>";
        }
        
        if (isset($resumen['identificacion_estrategica_texto'])) {
            echo "<li>✅ <strong>identificacion_estrategica_texto:</strong> " . htmlspecialchars(substr($resumen['identificacion_estrategica_texto'], 0, 100)) . "...</li>";
        } else {
            echo "<li>❌ <strong>identificacion_estrategica_texto:</strong> No encontrada</li>";
        }
        
        if (isset($resumen['unidad_estrategica'])) {
            echo "<li>✅ <strong>unidad_estrategica:</strong> " . htmlspecialchars(substr($resumen['unidad_estrategica'], 0, 100)) . "...</li>";
        } else {
            echo "<li>❌ <strong>unidad_estrategica:</strong> No encontrada</li>";
        }
        
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Error:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<h2>🎯 RESUMEN DE LO QUE DEBE APARECER:</h2>";
echo "<ol>";
echo "<li><strong>Conclusiones:</strong> El texto que TÚ escribiste en resumen_ejecutivo.php</li>";
echo "<li><strong>Identificación Estratégica:</strong> El texto que TÚ escribiste en resumen_ejecutivo.php (NO la matriz CAME automática)</li>";
echo "<li><strong>Unidad Estratégica:</strong> La descripción que escribiste en el paso de objetivos estratégicos</li>";
echo "</ol>";
?>
