<?php
session_start();

echo "<h1>🔧 PRUEBA DE GUARDADO DE VALORES</h1>";

// Simular datos como los envía el formulario
$_POST = [
    'paso' => '3',
    'nombre_paso' => 'valores',
    'valores' => [
        'Innovación',
        'Excelencia',
        'Responsabilidad Social',
        'Trabajo en Equipo'
    ]
];

// Simular método HTTP
$_SERVER['REQUEST_METHOD'] = 'POST';

// Simular sesión de usuario
$_SESSION['user'] = ['id_usuario' => 1];

echo "<h2>📤 Datos que envía el formulario:</h2>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();

echo "<h2>🎯 Probando procesarDatosPaso directamente:</h2>";

try {
    // Probar el método de procesamiento de datos
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('procesarDatosPaso');
    $method->setAccessible(true);
    
    $datos_procesados = $method->invoke($controller, 'valores', $_POST);
    
    echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>Datos procesados correctamente:</strong>";
    echo "<pre>" . print_r($datos_procesados, true) . "</pre>";
    echo "</div>";
    
    // Probar el guardado en sesión temporal
    $reflection2 = new ReflectionClass($controller);
    $method2 = $reflection2->getMethod('guardarDatosPaso');
    $method2->setAccessible(true);
    
    $result = $method2->invoke($controller, 3, 'valores', $datos_procesados);
    
    if ($result) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>¡Guardado exitoso en sesión temporal!</strong>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Excepción:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<h2>📋 Verificando datos en sesión temporal:</h2>";
if (isset($_SESSION['plan_temporal']['valores'])) {
    echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>Datos en sesión temporal:</strong>";
    echo "<pre>" . print_r($_SESSION['plan_temporal']['valores'], true) . "</pre>";
    echo "</div>";
} else {
    echo "<div style='background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "⚠️ <strong>No hay datos en sesión temporal</strong>";
    echo "</div>";
}

echo "<hr>";
echo "<h2>🎯 PRÓXIMOS PASOS:</h2>";
echo "<ol>";
echo "<li>Si el guardado es exitoso, los valores deberían aparecer en la sesión temporal</li>";
echo "<li>Al finalizar el plan, estos valores se guardarán en la base de datos</li>";
echo "<li>Probar el flujo completo desde el formulario real</li>";
echo "</ol>";
?>
