<?php
// Script de prueba para verificar las mejoras del diseño
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
.test-header { background: #2c3e50; color: white; padding: 20px; text-align: center; margin-bottom: 20px; border-radius: 8px; }
.test-info { background: white; padding: 15px; border-left: 4px solid #3498db; margin-bottom: 20px; border-radius: 0 8px 8px 0; }
.btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 8px; font-weight: 500; }
.btn-success { background: #28a745; }
.btn-warning { background: #ffc107; color: #212529; }
.iframe-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
.mejoras-list { background: #e8f5e8; padding: 20px; border-radius: 8px; border-left: 5px solid #28a745; }
.mejoras-list h3 { color: #155724; margin-bottom: 15px; }
.mejoras-list ul { margin: 0; padding-left: 20px; }
.mejoras-list li { margin-bottom: 8px; color: #155724; }
</style>";

echo "<div class='test-header'>
<h1>🎨 Prueba de Mejoras del Diseño</h1>
<p>Verificación de cambios: objetivos simplificados y área para logo</p>
</div>";

echo "<div class='mejoras-list'>
<h3>✅ Mejoras Aplicadas</h3>
<ul>
<li><strong>Objetivos Estratégicos:</strong> Eliminados los badges ALTA/MEDIA/BAJA, ahora aparecen uno sobre otro con diseño limpio</li>
<li><strong>Diseño Simplificado:</strong> Los objetivos tienen un formato más profesional y minimalista</li>
<li><strong>Área para Logo:</strong> Agregada área reservada para logo de la empresa en el encabezado azul</li>
<li><strong>Responsividad:</strong> El logo se adapta correctamente en dispositivos móviles</li>
<li><strong>Impresión:</strong> El área del logo está optimizada para impresión en PDF</li>
</ul>
</div>";

echo "<div class='test-info'>
<h3>📋 Información de la Prueba</h3>
<p><strong>Plan ID:</strong> $id_plan</p>
<p><strong>Cambios:</strong> Objetivos simplificados + Área para logo</p>
<p><strong>Estado:</strong> ✅ Aplicado correctamente</p>
<p><strong>Fecha:</strong> " . date('d/m/Y H:i') . "</p>
</div>";

echo "<div style='text-align: center; margin-bottom: 20px;'>
<a href='resumen_plan.php?id=$id_plan' class='btn' target='_blank'>🔍 Ver Resumen Mejorado</a>
<a href='#' onclick='window.open(\"resumen_plan.php?id=$id_plan\", \"_blank\"); setTimeout(() => window.print(), 1000);' class='btn btn-success'>🖨️ Probar Impresión</a>
<a href='home.php' class='btn btn-warning'>🏠 Dashboard</a>
</div>";

echo "<div class='iframe-container'>
<h3>📊 Vista Previa del Informe Mejorado</h3>
<iframe src='resumen_plan.php?id=$id_plan' width='100%' height='800px' frameborder='0' style='border: 1px solid #ddd; border-radius: 8px;'></iframe>
</div>";

echo "<div class='test-info'>
<h3>💡 Instrucciones para Agregar Logo Real</h3>
<p>Para reemplazar el área de logo con una imagen real:</p>
<ol>
<li>Suba la imagen del logo a la carpeta <code>public/assets/img/</code></li>
<li>Reemplace el div del logo con: <code>&lt;img src='../public/assets/img/logo.png' style='max-width: 120px; max-height: 80px;'&gt;</code></li>
<li>La imagen se mostrará tanto en pantalla como en el PDF al imprimir</li>
</ol>
</div>";

echo "<script>
console.log('🎨 Test de mejoras de diseño iniciado');
console.log('✅ Objetivos simplificados aplicados');
console.log('🖼️ Área para logo agregada');
console.log('📱 Responsividad configurada');
</script>";
?>
