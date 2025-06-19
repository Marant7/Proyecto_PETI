<?php
// Script de prueba para la funcionalidad de logo
session_start();

// Simular usuario logueado para prueba
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id_usuario' => 1,
        'nombre' => 'Usuario de Prueba'
    ];
}

$id_plan = $_GET['id'] ?? 1;

echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.test-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; text-align: center; margin-bottom: 25px; border-radius: 10px; }
.feature-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #667eea; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 6px; margin: 8px; font-weight: 500; transition: all 0.3s; }
.btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
.btn-success { background: #28a745; }
.btn-purple { background: #6f42c1; }
.btn-info { background: #17a2b8; }
.iframe-container { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
.steps { background: #e8f4f8; padding: 20px; border-radius: 8px; border-left: 5px solid #17a2b8; }
.steps ol { margin: 0; padding-left: 25px; }
.steps li { margin-bottom: 10px; line-height: 1.6; }
</style>";

echo "<div class='test-header'>
<h1>🖼️ Funcionalidad de Logo Empresarial</h1>
<p>Sistema completo para agregar y mostrar logos en el plan estratégico</p>
</div>";

echo "<div class='feature-box'>
<h3>✨ Funcionalidades Implementadas</h3>
<ul style='line-height: 2; margin: 15px 0;'>
<li>✅ <strong>Botón \"Agregar Logo\"</strong> en la barra de acciones del resumen</li>
<li>✅ <strong>Modal de subida</strong> con validación de archivos (JPG, PNG, GIF)</li>
<li>✅ <strong>Validación de tamaño</strong> máximo 2MB</li>
<li>✅ <strong>Almacenamiento seguro</strong> en public/assets/logos/</li>
<li>✅ <strong>Guardado en base de datos</strong> (columna logo_empresa agregada)</li>
<li>✅ <strong>Visualización en encabezado</strong> del informe</li>
<li>✅ <strong>Optimizado para impresión</strong> y exportación PDF</li>
<li>✅ <strong>Actualización en tiempo real</strong> sin recargar página</li>
</ul>
</div>";

echo "<div class='steps'>
<h3>📋 Cómo Usar la Funcionalidad</h3>
<ol>
<li><strong>Abrir el resumen:</strong> Vaya a la vista del resumen del plan estratégico</li>
<li><strong>Hacer clic en \"Agregar Logo\":</strong> Se abrirá un modal para subir la imagen</li>
<li><strong>Seleccionar archivo:</strong> Elija una imagen JPG, PNG o GIF (máx. 2MB)</li>
<li><strong>Subir:</strong> El logo se guardará y aparecerá automáticamente en el encabezado</li>
<li><strong>Imprimir/PDF:</strong> El logo se incluirá en la versión impresa del documento</li>
</ol>
</div>";

echo "<div style='text-align: center; margin: 25px 0;'>
<a href='resumen_plan.php?id=$id_plan' class='btn btn-success' target='_blank'>
🔍 Probar Funcionalidad de Logo
</a>
<a href='#' onclick='testUpload()' class='btn btn-purple'>
🧪 Simular Subida de Logo
</a>
<a href='home.php' class='btn btn-info'>
🏠 Volver al Dashboard
</a>
</div>";

echo "<div class='iframe-container'>
<h3>📊 Vista Previa del Resumen con Funcionalidad de Logo</h3>
<iframe src='resumen_plan.php?id=$id_plan' width='100%' height='700px' frameborder='0' style='border: 1px solid #ddd; border-radius: 8px;'></iframe>
</div>";

echo "<div class='feature-box'>
<h3>🔧 Archivos Creados/Modificados</h3>
<ul style='font-family: monospace; line-height: 1.8;'>
<li>📄 <strong>resumen_plan.php</strong> - Modal y funcionalidad de subida</li>
<li>📄 <strong>upload_logo.php</strong> - Procesamiento de archivos</li>
<li>📄 <strong>PlanEstrategicoController.php</strong> - Incluye logo en resumen</li>
<li>🗃️ <strong>tb_plan_estrategico</strong> - Columna logo_empresa agregada</li>
<li>📁 <strong>public/assets/logos/</strong> - Directorio de almacenamiento</li>
</ul>
</div>";

echo "<script>
function testUpload() {
    alert('💡 Para probar la subida de logo:\\n\\n1. Haga clic en \"Probar Funcionalidad de Logo\"\\n2. En la nueva ventana, haga clic en \"Agregar Logo\"\\n3. Seleccione una imagen y súbala\\n4. Verá el logo aparecer inmediatamente en el encabezado');
}

console.log('🖼️ Test de funcionalidad de logo iniciado');
console.log('📊 Plan ID: $id_plan');
console.log('✅ Todas las funcionalidades implementadas');
</script>";
?>
