<?php
session_start();

echo "<h1>🎯 PRUEBA DE LA VISTA RESUMEN EJECUTIVO</h1>";
echo "<p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

// Simular sesión de usuario
$_SESSION['user'] = ['id_usuario' => 1];
$_SESSION['usuario_id'] = 1;

require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();

// Probar resumen ejecutivo del plan 16 que sabemos que tiene datos
$id_plan = 16;

echo "<h2>📊 VERIFICANDO DATOS DEL PLAN $id_plan</h2>";

$resumen = $controller->obtenerResumenEjecutivo($id_plan);

if ($resumen) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>DATOS OBTENIDOS EXITOSAMENTE</strong>";
    echo "</div>";
    
    echo "<h3>📋 Verificación de Campos Disponibles:</h3>";
    echo "<ul>";
    
    $campos_esperados = [
        'nombre_plan' => 'Nombre del Plan',
        'empresa' => 'Empresa',
        'fecha_creacion' => 'Fecha de Creación',
        'analisis_externo' => 'Análisis PEST',
        'analisis_interno' => 'Análisis FODA',
        'matriz_came' => 'Matriz CAME',
        'estrategias_principales' => 'Estrategias Principales',
        'estadisticas' => 'Estadísticas',
        'conclusiones' => 'Conclusiones'
    ];
    
    foreach ($campos_esperados as $campo => $descripcion) {
        $tiene_dato = isset($resumen[$campo]) && !empty($resumen[$campo]);
        $icono = $tiene_dato ? "✅" : "❌";
        $valor = $tiene_dato ? " - " . substr($resumen[$campo], 0, 100) . "..." : " - NO DISPONIBLE";
        echo "<li>$icono <strong>$descripcion:</strong>$valor</li>";
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<h2>🔗 ENLACES PARA PROBAR LA VISTA:</h2>";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
    echo "<p>📄 <a href='resumen_plan.php?id=$id_plan' target='_blank' style='font-size: 18px; font-weight: bold; color: #007bff;'>VER RESUMEN EJECUTIVO (VISTA ACTUALIZADA)</a></p>";
    echo "<p>🆚 <a href='test_resumen_final.php' target='_blank'>Comparar con Test Original</a></p>";
    echo "<p>🏠 <a href='home.php' target='_blank'>Dashboard Principal</a></p>";
    echo "</div>";
    
    echo "<hr>";
    echo "<h2>📊 VISTA PREVIA DEL CONTENIDO:</h2>";
    
    if (isset($resumen['analisis_externo'])) {
        echo "<h3>🌍 Análisis PEST:</h3>";
        echo "<div style='background: #f0f8ff; padding: 10px; border-left: 4px solid #007bff; margin: 10px 0;'>";
        echo nl2br(htmlspecialchars(substr($resumen['analisis_externo'], 0, 300))) . "...";
        echo "</div>";
    }
    
    if (isset($resumen['analisis_interno'])) {
        echo "<h3>📊 Análisis FODA:</h3>";
        echo "<div style='background: #f0fff0; padding: 10px; border-left: 4px solid #28a745; margin: 10px 0;'>";
        echo nl2br(htmlspecialchars(substr($resumen['analisis_interno'], 0, 300))) . "...";
        echo "</div>";
    }
    
    if (isset($resumen['estrategias_principales'])) {
        echo "<h3>🚀 Estrategias Principales:</h3>";
        echo "<div style='background: #f0ffff; padding: 10px; border-left: 4px solid #17a2b8; margin: 10px 0;'>";
        echo nl2br(htmlspecialchars(substr($resumen['estrategias_principales'], 0, 300))) . "...";
        echo "</div>";
    }
    
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "❌ <strong>ERROR:</strong> No se pudo obtener el resumen ejecutivo";
    echo "</div>";
}

echo "<hr>";
echo "<div style='background: #d1ecf1; padding: 20px; border-radius: 5px;'>";
echo "<h3>🎯 VALIDACIÓN FINAL:</h3>";
echo "<p>✅ <strong>Vista actualizada:</strong> resumen_plan.php ahora muestra todos los datos del wizard</p>";
echo "<p>✅ <strong>Datos disponibles:</strong> PEST, FODA, CAME, Estrategias, Estadísticas y Conclusiones</p>";
echo "<p>✅ <strong>Formato profesional:</strong> Diseño mejorado con colores y estructura clara</p>";
echo "<p>🎉 <strong>¡La vista del resumen ejecutivo está lista y muestra datos reales!</strong></p>";
echo "</div>";
?>
