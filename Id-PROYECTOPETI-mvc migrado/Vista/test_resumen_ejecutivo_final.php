<?php
session_start();
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>🎯 TEST FINAL - RESUMEN EJECUTIVO COMPLETO</h1>";
echo "<p><strong>Verificando todos los campos que TÚ escribiste en resumen_ejecutivo.php</strong></p>";
echo "<hr>";

$controller = new PlanEstrategicoController();
$id_plan = 16;

echo "<h2>📊 OBTENIENDO RESUMEN EJECUTIVO DEL PLAN $id_plan</h2>";

$resumen = $controller->obtenerResumenEjecutivo($id_plan);

if ($resumen) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>RESUMEN EJECUTIVO OBTENIDO EXITOSAMENTE</strong>";
    echo "</div>";
    
    echo "<h3>📝 1. Información Básica:</h3>";
    echo "<ul>";
    echo "<li><strong>Nombre del Plan:</strong> " . ($resumen['nombre_plan'] ?? 'No definido') . "</li>";
    echo "<li><strong>Empresa:</strong> " . ($resumen['empresa'] ?? 'No definida') . "</li>";
    echo "</ul>";
    
    echo "<h3>💎 2. Emprendedores y Promotores (Campo del Resumen Ejecutivo):</h3>";
    if (isset($resumen['emprendedores_promotores']) && !empty($resumen['emprendedores_promotores'])) {
        echo "<div style='background: #e3f2fd; padding: 10px; border-left: 4px solid #2196f3; margin: 10px 0;'>";
        echo "✅ <strong>ENCONTRADO:</strong> " . htmlspecialchars($resumen['emprendedores_promotores']);
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 10px; border-left: 4px solid #f44336; margin: 10px 0;'>";
        echo "❌ <strong>NO ENCONTRADO</strong> - Campo 'emprendedores_promotores'";
        echo "</div>";
    }
    
    echo "<h3>🎯 3. Identificación Estratégica (Texto Manual que TÚ escribiste):</h3>";
    if (isset($resumen['identificacion_estrategica_usuario']) && !empty($resumen['identificacion_estrategica_usuario'])) {
        echo "<div style='background: #e8f5e8; padding: 10px; border-left: 4px solid #4caf50; margin: 10px 0;'>";
        echo "✅ <strong>ENCONTRADO:</strong> " . htmlspecialchars($resumen['identificacion_estrategica_usuario']);
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 10px; border-left: 4px solid #f44336; margin: 10px 0;'>";
        echo "❌ <strong>NO ENCONTRADO</strong> - Campo 'identificacion_estrategica_usuario'";
        echo "</div>";
    }
    
    echo "<h3>📝 4. Conclusiones (Texto Manual que TÚ escribiste):</h3>";
    if (isset($resumen['conclusiones_usuario']) && !empty($resumen['conclusiones_usuario'])) {
        echo "<div style='background: #e8f5e8; padding: 10px; border-left: 4px solid #4caf50; margin: 10px 0;'>";
        echo "✅ <strong>ENCONTRADO:</strong> " . htmlspecialchars($resumen['conclusiones_usuario']);
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 10px; border-left: 4px solid #f44336; margin: 10px 0;'>";
        echo "❌ <strong>NO ENCONTRADO</strong> - Campo 'conclusiones_usuario'";
        echo "</div>";
    }
    
    echo "<h3>🏢 5. Unidad Estratégica (Descripción de Objetivos):</h3>";
    if (isset($resumen['unidad_estrategica']) && !empty($resumen['unidad_estrategica'])) {
        echo "<div style='background: #e3f2fd; padding: 10px; border-left: 4px solid #2196f3; margin: 10px 0;'>";
        echo "✅ <strong>ENCONTRADO:</strong> " . htmlspecialchars(substr($resumen['unidad_estrategica'], 0, 150)) . "...";
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 10px; border-left: 4px solid #f44336; margin: 10px 0;'>";
        echo "❌ <strong>NO ENCONTRADO</strong> - Campo 'unidad_estrategica'";
        echo "</div>";
    }
    
    echo "<hr>";
    echo "<h2>📋 TODOS LOS CAMPOS DISPONIBLES EN EL RESUMEN:</h2>";
    echo "<div style='background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 0.9em;'>";
    echo "<ul>";
    foreach (array_keys($resumen) as $key) {
        $valor = is_array($resumen[$key]) ? '[Array con ' . count($resumen[$key]) . ' elementos]' : (strlen($resumen[$key]) > 50 ? substr($resumen[$key], 0, 50) . '...' : $resumen[$key]);
        echo "<li><strong>$key:</strong> " . htmlspecialchars($valor) . "</li>";
    }
    echo "</ul>";
    echo "</div>";
    
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>ERROR:</strong> No se pudo obtener el resumen del plan";
    echo "</div>";
}

echo "<hr>";
echo "<h2>🎯 RESUMEN DE LO QUE DEBE APARECER EN EL RESUMEN FINAL:</h2>";
echo "<ol>";
echo "<li>✅ <strong>Emprendedores y Promotores:</strong> El texto que TÚ escribiste en resumen_ejecutivo.php</li>";
echo "<li>🎯 <strong>Identificación Estratégica:</strong> El texto que TÚ escribiste en resumen_ejecutivo.php (NO la matriz CAME)</li>";
echo "<li>✅ <strong>Conclusiones:</strong> El texto que TÚ escribiste en resumen_ejecutivo.php</li>";
echo "<li>✅ <strong>Unidad Estratégica:</strong> La descripción UEN del paso de objetivos</li>";
echo "</ol>";
?>
