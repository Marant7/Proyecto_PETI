<?php
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h2>🔍 Test: Verificar datos CAME en resumen</h2>";

try {
    $controller = new PlanEstrategicoController();
    
    // Obtener el último plan creado
    require_once '../config/clsconexion.php';
    $conexion = new clsConexion();
    $pdo = $conexion->getConexion();
    
    $sql = "SELECT id_plan FROM tb_plan_estrategico ORDER BY id_plan DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($plan) {
        $id_plan = $plan['id_plan'];
        echo "<p>✅ Plan encontrado: ID $id_plan</p>";
        
        // Verificar si hay datos CAME
        $sql_came = "SELECT * FROM tb_matriz_came WHERE id_plan = ?";
        $stmt_came = $pdo->prepare($sql_came);
        $stmt_came->execute([$id_plan]);
        $came = $stmt_came->fetch(PDO::FETCH_ASSOC);
        
        if ($came) {
            echo "<h3>✅ Datos CAME encontrados en BD:</h3>";
            echo "<div style='background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
            echo "<p><strong>Corregir:</strong> " . ($came['corregir'] ?: '<em>Vacío</em>') . "</p>";
            echo "<p><strong>Afrontar:</strong> " . ($came['afrontar'] ?: '<em>Vacío</em>') . "</p>";  
            echo "<p><strong>Mantener:</strong> " . ($came['mantener'] ?: '<em>Vacío</em>') . "</p>";
            echo "<p><strong>Explotar:</strong> " . ($came['explotar'] ?: '<em>Vacío</em>') . "</p>";
            echo "</div>";
        } else {
            echo "<p>❌ No se encontraron datos CAME para este plan</p>";
            
            // Crear datos CAME de prueba
            echo "<h3>📝 Creando datos CAME de prueba...</h3>";
            $sql_insert = "INSERT INTO tb_matriz_came (id_plan, corregir, afrontar, mantener, explotar) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $result = $stmt_insert->execute([
                $id_plan,
                "Implementar capacitación continua para mejorar las habilidades del personal y corregir deficiencias operativas.",
                "Desarrollar planes de contingencia y diversificar mercados para afrontar amenazas económicas y competitivas.",
                "Continuar invirtiendo en tecnología y mantener la calidad del servicio que nos diferencia en el mercado.",
                "Aprovechar las nuevas oportunidades digitales para expandir nuestra presencia online y captar nuevos clientes."
            ]);
            
            if ($result) {
                echo "<p>✅ Datos CAME de prueba creados exitosamente</p>";
                $came = [
                    'corregir' => "Implementar capacitación continua para mejorar las habilidades del personal y corregir deficiencias operativas.",
                    'afrontar' => "Desarrollar planes de contingencia y diversificar mercados para afrontar amenazas económicas y competitivas.",
                    'mantener' => "Continuar invirtiendo en tecnología y mantener la calidad del servicio que nos diferencia en el mercado.",
                    'explotar' => "Aprovechar las nuevas oportunidades digitales para expandir nuestra presencia online y captar nuevos clientes."
                ];
            }
        }
        
        // Probar el método obtenerResumenEjecutivo
        echo "<h3>🔍 Probando obtenerResumenEjecutivo:</h3>";
        $resumen = $controller->obtenerResumenEjecutivo($id_plan);
        
        if (isset($resumen['identificacion_estrategica'])) {
            echo "<p>✅ Campo 'identificacion_estrategica' encontrado en resumen</p>";
            echo "<div style='background: #e7f3ff; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
            echo "<h4>Datos CAME en resumen:</h4>";
            if (is_array($resumen['identificacion_estrategica'])) {
                foreach (['corregir', 'afrontar', 'mantener', 'explotar'] as $tipo) {
                    $valor = $resumen['identificacion_estrategica'][$tipo] ?? '';
                    echo "<p><strong>" . ucfirst($tipo) . ":</strong> " . ($valor ?: '<em>Vacío</em>') . "</p>";
                }
            } else {
                echo "<p>Formato: " . gettype($resumen['identificacion_estrategica']) . "</p>";
                echo "<p>Contenido: " . htmlspecialchars($resumen['identificacion_estrategica']) . "</p>";
            }
            echo "</div>";
        } else {
            echo "<p>❌ Campo 'identificacion_estrategica' NO encontrado en resumen</p>";
            echo "<p>Campos disponibles: " . implode(', ', array_keys($resumen)) . "</p>";
        }
        
        echo "<h3>✅ Resultado:</h3>";
        if (isset($resumen['identificacion_estrategica']) && is_array($resumen['identificacion_estrategica'])) {
            echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
            echo "<p>✅ Los datos CAME se pueden mostrar correctamente en el resumen</p>";
            echo "<p>✅ La sección 'Acciones Competitivas' mostrará las respuestas CAME del usuario</p>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
            echo "<p>❌ Hay un problema con la recuperación de datos CAME</p>";
            echo "<p>🔧 Revisar el método obtenerResumenEjecutivo en el controlador</p>";
            echo "</div>";
        }
        
    } else {
        echo "<p>❌ No se encontró ningún plan en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
