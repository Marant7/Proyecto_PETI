<?php
session_start();

echo "<h1>🔧 PRUEBA COMPLETA: VALORES EN BASE DE DATOS</h1>";

require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();

// Simular un plan temporal completo con valores
$_SESSION['plan_temporal'] = [
    'id_usuario' => 1,
    'fecha_inicio' => date('Y-m-d H:i:s'),
    'paso_actual' => 12,
    'nuevo_plan' => [
        'nombre_plan' => 'Plan Test Valores',
        'empresa' => 'Empresa Test',
        'descripcion' => 'Descripción test'
    ],
    'vision_mision' => [
        'vision' => 'Visión test',
        'mision' => 'Misión test'
    ],
    'valores' => [
        'Innovación',
        'Excelencia', 
        'Responsabilidad Social',
        'Trabajo en Equipo',
        'Transparencia'
    ],
    'objetivos' => [
        'uen_descripcion' => 'UEN Test',
        'objetivos_generales' => ['Objetivo general 1'],
        'objetivos_especificos' => ['Objetivo específico 1']
    ],
    'resumen_ejecutivo' => [
        'emprendedores_promotores' => 'Test emprendedores',
        'identificacion_estrategica' => 'Test identificación',
        'conclusiones' => 'Test conclusiones'
    ]
];

echo "<h2>📊 Datos temporales preparados:</h2>";
echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
echo "✅ <strong>Valores en sesión temporal:</strong>";
echo "<pre>" . print_r($_SESSION['plan_temporal']['valores'], true) . "</pre>";
echo "</div>";

try {
    echo "<h2>💾 Probando guardarPlanCompleto:</h2>";
    
    $id_plan = $controller->guardarPlanCompleto();
    
    if ($id_plan) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>¡Plan guardado exitosamente con ID: $id_plan!</strong>";
        echo "</div>";
        
        // Verificar valores en base de datos
        echo "<h3>🔍 Verificando valores guardados:</h3>";
        $resumen = $controller->obtenerResumenEjecutivo($id_plan);
        
        if (isset($resumen['valores']) && !empty($resumen['valores'])) {
            echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "✅ <strong>Valores encontrados en base de datos:</strong>";
            echo "<ul>";
            foreach ($resumen['valores'] as $valor) {
                echo "<li><strong>" . htmlspecialchars($valor['valor']) . "</strong>";
                if (!empty($valor['descripcion'])) {
                    echo " - " . htmlspecialchars($valor['descripcion']);
                }
                echo "</li>";
            }
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "❌ <strong>Valores NO encontrados en base de datos</strong>";
            echo "</div>";
        }
        
        // Verificar también directamente en la tabla
        echo "<h3>🔍 Verificación directa en tb_valores:</h3>";
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getConexion');
        $method->setAccessible(true);
        $pdo = $method->invoke($controller);
        
        $stmt = $pdo->prepare("SELECT * FROM tb_valores WHERE id_plan = ? ORDER BY orden");
        $stmt->execute([$id_plan]);
        $valores_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($valores_db)) {
            echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "✅ <strong>Valores en tb_valores:</strong>";
            echo "<pre>" . print_r($valores_db, true) . "</pre>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "❌ <strong>NO hay valores en tb_valores para el plan $id_plan</strong>";
            echo "</div>";
        }
        
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>Error al guardar el plan</strong>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Excepción:</strong> " . $e->getMessage();
    echo "<br><strong>Trace:</strong><br><pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "<hr>";
echo "<h2>🎯 RESUMEN:</h2>";
echo "<ol>";
echo "<li>✅ Valores se procesan correctamente desde el formulario</li>";
echo "<li>✅ Valores se guardan en sesión temporal</li>";
echo "<li>🔍 Verificar si valores se guardan en base de datos al finalizar</li>";
echo "</ol>";
?>
