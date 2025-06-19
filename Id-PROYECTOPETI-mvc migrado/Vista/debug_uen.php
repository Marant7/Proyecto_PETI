<?php
session_start();
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>🔍 DEBUG DIRECTO DE LA CONSULTA UEN</h1>";

try {
    $controller = new PlanEstrategicoController();
    // Usar reflexión para acceder al método privado
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getConexion');
    $method->setAccessible(true);
    $pdo = $method->invoke($controller);
    
    echo "<h2>1. Verificando datos en tb_uen:</h2>";
    $stmt = $pdo->prepare("SELECT * FROM tb_uen WHERE id_plan = 16");
    $stmt->execute();
    $uen_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($uen_data, true) . "</pre>";
    
    echo "<h2>2. Probando la consulta exacta del controlador:</h2>";
    $stmt = $pdo->prepare("SELECT uen_descripcion FROM tb_uen WHERE id_plan = ?");
    $stmt->execute([16]);
    $uen = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<strong>Resultado de la consulta:</strong>";
    echo "<pre>" . print_r($uen, true) . "</pre>";
    
    if ($uen && !empty($uen['uen_descripcion'])) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>UEN encontrada:</strong> " . htmlspecialchars($uen['uen_descripcion']);
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>UEN NO encontrada o vacía</strong>";
        echo "</div>";
    }
    
    echo "<h2>3. Probando método obtenerResumenEjecutivo completo:</h2>";
    $resumen = $controller->obtenerResumenEjecutivo(16);
    
    if (isset($resumen['unidad_estrategica'])) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>unidad_estrategica encontrada en resumen:</strong> " . htmlspecialchars($resumen['unidad_estrategica']);
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>unidad_estrategica NO encontrada en resumen</strong>";
        echo "</div>";
        
        echo "<h4>Claves disponibles en el resumen:</h4>";
        echo "<ul>";
        foreach (array_keys($resumen) as $key) {
            echo "<li>" . $key . "</li>";
        }
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Error:</strong> " . $e->getMessage();
    echo "</div>";
}
?>
