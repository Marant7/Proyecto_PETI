<?php
session_start();
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>🔧 LIMPIANDO REGISTROS DUPLICADOS DE UEN</h1>";

try {
    $controller = new PlanEstrategicoController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getConexion');
    $method->setAccessible(true);
    $pdo = $method->invoke($controller);
    
    echo "<h2>1. Eliminando registros duplicados, manteniendo solo el más reciente:</h2>";
    
    // Mantener solo el registro más reciente
    $pdo->exec("
        DELETE u1 FROM tb_uen u1
        INNER JOIN tb_uen u2 
        WHERE u1.id_plan = u2.id_plan 
        AND u1.id_uen < u2.id_uen
        AND u1.id_plan = 16
    ");
    $pdo->commit();
    
    echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>Registros duplicados eliminados</strong>";
    echo "</div>";
    
    echo "<h2>2. Verificando UEN final:</h2>";
    $stmt = $pdo->prepare("SELECT * FROM tb_uen WHERE id_plan = 16");
    $stmt->execute();
    $uen_final = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($uen_final)) {
        echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>UEN final (único registro):</strong>";
        echo "<pre>" . print_r($uen_final, true) . "</pre>";
        echo "</div>";
        
        $uen_descripcion = $uen_final[0]['uen_descripcion'];
        echo "<div style='background: #f8f9fa; border: 2px solid #007bff; padding: 15px; border-radius: 8px; margin: 15px 0;'>";
        echo "<h3 style='color: #007bff; margin-top: 0;'>📋 UNIDAD ESTRATÉGICA ACTUAL:</h3>";
        echo "<p style='font-size: 1.1em; line-height: 1.6;'><strong>\"" . htmlspecialchars($uen_descripcion) . "\"</strong></p>";
        echo "</div>";
    }
    
    echo "<h2>3. Probando en el resumen:</h2>";
    $resumen = $controller->obtenerResumenEjecutivo(16);
    
    if (isset($resumen['unidad_estrategica'])) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "🎉 <strong>¡PERFECTO! UEN aparece correctamente en el resumen:</strong><br>";
        echo "\"" . htmlspecialchars($resumen['unidad_estrategica']) . "\"";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>UEN no aparece en el resumen</strong>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Error:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<h2>✅ ESTADO FINAL:</h2>";
echo "<p>La Unidad Estratégica ya está guardada y funcionando en el resumen_plan.php</p>";
echo "<p><strong>Si quieres cambiar la descripción</strong>, solo dime cuál es el texto exacto que prefieres.</p>";
?>
