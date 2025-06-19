<?php
// Prueba del nuevo ver_plan.php mejorado con barra lateral

echo "<h1>🎉 Prueba del Sistema Ver Plan Completo Mejorado</h1>";
echo "<p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>";

echo "<h2>✨ Mejoras implementadas:</h2>";
echo "<ul style='line-height: 1.8;'>";
echo "<li>✅ <strong>Barra lateral de navegación</strong> con enlaces a todas las secciones</li>";
echo "<li>✅ <strong>IDs en todas las secciones</strong> para navegación suave</li>";
echo "<li>✅ <strong>Análisis PEST mejorado</strong> con valores numéricos (5, 5, 5, 5)</li>";
echo "<li>✅ <strong>Diseño responsivo</strong> y moderno</li>";
echo "<li>✅ <strong>Modo solo lectura</strong> - visualización sin edición</li>";
echo "<li>✅ <strong>Navegación automática</strong> entre secciones</li>";
echo "<li>✅ <strong>Todos los datos detallados</strong> visibles</li>";
echo "</ul>";

echo "<h2>🧭 Secciones disponibles en la barra lateral:</h2>";
$secciones = [
    'info-general' => 'Información General',
    'vision-mision' => 'Visión y Misión',
    'valores' => 'Valores',
    'objetivos' => 'Objetivos Estratégicos',
    'uen' => 'UEN',
    'cadena-valor' => 'Cadena de Valor',
    'matriz-bcg' => 'Matriz BCG',
    'fuerzas-porter' => 'Fuerzas de Porter',
    'analisis-pest' => 'Análisis PEST (con valores numéricos)',
    'analisis-foda' => 'Análisis FODA',
    'identificacion-estrategica' => 'Identificación Estratégica',
    'matriz-came' => 'Matriz CAME',
    'resumen-ejecutivo' => 'Resumen Ejecutivo'
];

echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 10px; margin: 20px 0;'>";
foreach ($secciones as $id => $nombre) {
    echo "<div style='background: #e3f2fd; border: 1px solid #bbdefb; color: #1565c0; padding: 10px; border-radius: 5px; text-align: center;'>";
    echo "<strong>📋 $nombre</strong>";
    echo "</div>";
}
echo "</div>";

// Obtener lista de planes disponibles
require_once '../config/clsconexion.php';
$conexion = new clsConexion();
$pdo = $conexion->getConexion();

$stmt = $pdo->prepare("SELECT id_plan, nombre_plan, empresa FROM tb_plan_estrategico ORDER BY fecha_creacion DESC LIMIT 3");
$stmt->execute();
$planes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($planes) {
    echo "<h2>🚀 Prueba el sistema mejorado:</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
    echo "<tr style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;'>";
    echo "<th style='padding: 15px;'>Plan</th>";
    echo "<th style='padding: 15px;'>Empresa</th>";
    echo "<th style='padding: 15px;'>Ver Plan Mejorado</th>";
    echo "</tr>";
    
    foreach ($planes as $plan) {
        echo "<tr style='background: white;'>";
        echo "<td style='padding: 12px;'><strong>" . htmlspecialchars($plan['nombre_plan']) . "</strong></td>";
        echo "<td style='padding: 12px;'>" . htmlspecialchars($plan['empresa']) . "</td>";
        echo "<td style='padding: 12px; text-align: center;'>";
        echo "<a href='ver_plan.php?id=" . $plan['id_plan'] . "' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;'>";
        echo "<i style='font-size: 16px;'>👁️</i> Ver Plan Completo Mejorado";
        echo "</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px;'>";
    echo "<strong>⚠️ ADVERTENCIA:</strong> No hay planes estratégicos disponibles.";
    echo "</div>";
}

echo "<h2>🎯 Características especiales del análisis PEST:</h2>";
echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 20px; border-radius: 5px;'>";
echo "<p><strong>Ahora se muestran:</strong></p>";
echo "<ul>";
echo "<li>📊 <strong>Valores numéricos</strong> de cada factor (Político: 5, Económico: 5, Social: 5, Tecnológico: 5)</li>";
echo "<li>📝 <strong>Descripciones detalladas</strong> de cada factor</li>";
echo "<li>🎨 <strong>Diseño visual</strong> con colores distintivos por factor</li>";
echo "<li>📋 <strong>Oportunidades y amenazas</strong> específicas del PEST</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🎨 Características del nuevo diseño:</h2>";
echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;'>";

echo "<div style='background: white; border-radius: 8px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);'>";
echo "<h3 style='color: #667eea; margin-top: 0;'>🧭 Navegación</h3>";
echo "<ul>";
echo "<li>Barra lateral fija</li>";
echo "<li>Scroll suave entre secciones</li>";
echo "<li>Indicador de sección activa</li>";
echo "<li>Responsive para móviles</li>";
echo "</ul>";
echo "</div>";

echo "<div style='background: white; border-radius: 8px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);'>";
echo "<h3 style='color: #667eea; margin-top: 0;'>📊 Visualización</h3>";
echo "<ul>";
echo "<li>Datos completos visibles</li>";
echo "<li>Sin posibilidad de edición</li>";
echo "<li>Diseño profesional</li>";
echo "<li>Iconos descriptivos</li>";
echo "</ul>";
echo "</div>";

echo "</div>";

echo "<div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px; text-align: center; margin: 30px 0;'>";
echo "<h2 style='margin: 0;'>🎉 ¡Sistema Mejorado Listo!</h2>";
echo "<p style='margin: 10px 0 0 0; font-size: 1.1em;'>El Ver Plan Completo ahora muestra todos los datos con navegación profesional</p>";
echo "</div>";
?>
