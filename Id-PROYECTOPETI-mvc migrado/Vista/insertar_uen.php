<?php
session_start();
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>🔧 INSERTANDO DATOS UEN PARA EL PLAN 16</h1>";

try {
    $controller = new PlanEstrategicoController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getConexion');
    $method->setAccessible(true);
    $pdo = $method->invoke($controller);
    
    echo "<h2>1. Insertando UEN para el plan 16:</h2>";
    $stmt = $pdo->prepare("
        INSERT INTO tb_uen (id_plan, uen_descripcion) 
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE uen_descripcion = VALUES(uen_descripcion)
    ");
    $uen_text = "Esta es la unidad estratégica de negocio que el usuario definió para orientar todos los objetivos estratégicos del plan. La UEN se enfoca en crear valor sostenible a través de la innovación y la eficiencia operacional.";
    
    $result = $stmt->execute([16, $uen_text]);
    
    if ($result) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>UEN insertada exitosamente</strong>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>Error al insertar UEN</strong>";
        echo "</div>";
    }
    
    echo "<h2>2. Verificando inserción:</h2>";
    $stmt = $pdo->prepare("SELECT * FROM tb_uen WHERE id_plan = 16");
    $stmt->execute();
    $uen_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($uen_data)) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>UEN verificada en base de datos:</strong>";
        echo "<pre>" . print_r($uen_data, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>UEN no encontrada después de inserción</strong>";
        echo "</div>";
    }
    
    echo "<h2>3. Probando obtenerResumenEjecutivo ahora:</h2>";
    $resumen = $controller->obtenerResumenEjecutivo(16);
    
    if (isset($resumen['unidad_estrategica'])) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>unidad_estrategica encontrada:</strong> " . htmlspecialchars($resumen['unidad_estrategica']);
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>unidad_estrategica todavía no encontrada</strong>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Error:</strong> " . $e->getMessage();
    echo "</div>";
}
?>
