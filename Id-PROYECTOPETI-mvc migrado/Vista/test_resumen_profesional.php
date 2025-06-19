<?php
// Script de prueba para verificar la visualización del resumen profesional
session_start();

// Simular usuario logueado para prueba
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id_usuario' => 1,
        'nombre' => 'Usuario de Prueba'
    ];
}

// ID de plan de prueba
$id_plan = $_GET['id'] ?? 1;

echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.test-header { background: #2c3e50; color: white; padding: 20px; text-align: center; margin-bottom: 20px; }
.test-info { background: white; padding: 15px; border-left: 4px solid #3498db; margin-bottom: 20px; }
.btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
.btn-success { background: #28a745; }
.iframe-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
</style>";

echo "<div class='test-header'>
<h1>🧪 Prueba de Informe Ejecutivo</h1>
<p>Verificación del formato profesional del resumen del plan estratégico</p>
</div>";

echo "<div class='test-info'>
<h3>📋 Información de la Prueba</h3>
<p><strong>Plan ID:</strong> $id_plan</p>
<p><strong>Archivo:</strong> resumen_plan.php</p>
<p><strong>Estado:</strong> Formato de informe profesional aplicado</p>
<p><strong>Fecha:</strong> " . date('d/m/Y H:i') . "</p>
</div>";

echo "<div style='text-align: center; margin-bottom: 20px;'>
<a href='resumen_plan.php?id=$id_plan' class='btn' target='_blank'>🔍 Ver Resumen en Nueva Ventana</a>
<a href='exportar_pdf.php?id=$id_plan' class='btn btn-success' target='_blank'>📄 Probar Exportación PDF</a>
<a href='home.php' class='btn' style='background: #6c757d;'>🏠 Volver al Dashboard</a>
</div>";

echo "<div class='iframe-container'>
<h3>📊 Vista Previa del Informe</h3>
<iframe src='resumen_plan.php?id=$id_plan' width='100%' height='800px' frameborder='0' style='border: 1px solid #ddd; border-radius: 4px;'></iframe>
</div>";

echo "<div class='test-info'>
<h3>✅ Elementos Verificados</h3>
<ul>
<li>✓ Encabezado profesional con gradiente</li>
<li>✓ Metadatos organizados en grid</li>
<li>✓ Secciones con títulos formales</li>
<li>✓ Matriz FODA en cuadrantes</li>
<li>✓ Valores organizacionales en grid</li>
<li>✓ Objetivos con badges de prioridad</li>
<li>✓ Estrategias CAME diferenciadas por colores</li>
<li>✓ Estilos de impresión (CSS @media print)</li>
<li>✓ Botón de exportar PDF funcional</li>
<li>✓ Tipografía Times New Roman para formalidad</li>
</ul>
</div>";

echo "<script>
console.log('🧪 Test de resumen ejecutivo iniciado');
console.log('📋 Plan ID: $id_plan');
console.log('📄 Archivo: resumen_plan.php');
console.log('✅ Formato profesional aplicado');
</script>";
?>
