<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>🧪 Prueba de Envío - Resumen Ejecutivo</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>📥 Datos POST Recibidos:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h2>📝 Análisis:</h2>";
    echo "<ul>";
    echo "<li><strong>paso:</strong> " . ($_POST['paso'] ?? '❌ NO ENVIADO') . "</li>";
    echo "<li><strong>nombre_paso:</strong> " . ($_POST['nombre_paso'] ?? '❌ NO ENVIADO') . "</li>";
    echo "<li><strong>emprendedores_promotores:</strong> " . (isset($_POST['emprendedores_promotores']) ? '✅ ENVIADO' : '❌ NO ENVIADO') . "</li>";
    echo "<li><strong>identificacion_estrategica:</strong> " . (isset($_POST['identificacion_estrategica']) ? '✅ ENVIADO' : '❌ NO ENVIADO') . "</li>";
    echo "<li><strong>conclusiones:</strong> " . (isset($_POST['conclusiones']) ? '✅ ENVIADO' : '❌ NO ENVIADO') . "</li>";
    echo "</ul>";
    
    // Intentar llamar al controlador real
    echo "<h2>🔄 Intentando guardar en sesión:</h2>";
    try {
        $_SESSION['plan_temporal']['resumen_ejecutivo'] = [
            'emprendedores_promotores' => $_POST['emprendedores_promotores'] ?? '',
            'identificacion_estrategica' => $_POST['identificacion_estrategica'] ?? '',
            'conclusiones' => $_POST['conclusiones'] ?? ''
        ];
        echo "<p style='color: green;'>✅ Datos guardados en sesión exitosamente</p>";
        
        echo "<h3>📋 Datos guardados:</h3>";
        echo "<pre>";
        print_r($_SESSION['plan_temporal']['resumen_ejecutivo']);
        echo "</pre>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p>No se han enviado datos POST. Use el formulario del resumen ejecutivo.</p>";
}

if (isset($_SESSION['plan_temporal'])) {
    echo "<h2>🗂️ Estado Actual de la Sesión:</h2>";
    echo "<h3>Módulos completados:</h3>";
    echo "<ul>";
    foreach ($_SESSION['plan_temporal'] as $modulo => $datos) {
        echo "<li><strong>$modulo:</strong> ✅</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ No hay plan temporal en sesión</p>";
}
?>
