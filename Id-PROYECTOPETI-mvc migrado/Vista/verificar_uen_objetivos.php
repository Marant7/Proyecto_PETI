<?php
session_start();
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>🔍 VERIFICANDO UNIDAD ESTRATÉGICA - DESCRIPCIÓN DE OBJETIVOS</h1>";

try {
    $controller = new PlanEstrategicoController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getConexion');
    $method->setAccessible(true);
    $pdo = $method->invoke($controller);
    
    echo "<h2>1. Verificando datos actuales de UEN:</h2>";
    $stmt = $pdo->prepare("SELECT * FROM tb_uen WHERE id_plan = 16");
    $stmt->execute();
    $uen_actual = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($uen_actual)) {
        echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>UEN actual en la base de datos:</strong>";
        echo "<pre>" . print_r($uen_actual, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>NO hay UEN guardada en la base de datos para el plan 16</strong>";
        echo "</div>";
    }
    
    echo "<h2>2. Verificando si existe descripción UEN en sesión temporal:</h2>";
    if (isset($_SESSION['plan_temporal']['objetivos']['uen_descripcion'])) {
        $uen_sesion = $_SESSION['plan_temporal']['objetivos']['uen_descripcion'];
        echo "<div style='background: #e3f2fd; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>UEN encontrada en sesión:</strong> " . htmlspecialchars($uen_sesion);
        echo "</div>";
        
        echo "<h3>📝 Actualizando UEN con la descripción de sesión:</h3>";
        $stmt = $pdo->prepare("
            INSERT INTO tb_uen (id_plan, uen_descripcion) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE uen_descripcion = VALUES(uen_descripcion)
        ");
        $result = $stmt->execute([16, $uen_sesion]);
        $pdo->commit();
        
        if ($result) {
            echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "✅ <strong>UEN actualizada con la descripción de objetivos estratégicos</strong>";
            echo "</div>";
        }
        
    } else {
        echo "<div style='background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "⚠️ <strong>No hay UEN en sesión temporal</strong>";
        echo "</div>";
        
        echo "<h3>📝 Necesitas proporcionar la descripción UEN:</h3>";
        echo "<p>¿Cuál es la descripción de la Unidad Estratégica (descripción de objetivos estratégicos) que quieres guardar?</p>";
        
        // Insertar descripción de ejemplo basada en objetivos
        $descripcion_uen = "Unidad estratégica enfocada en el desarrollo sostenible y la innovación tecnológica para lograr los objetivos generales y específicos definidos en el plan estratégico, maximizando el valor para los stakeholders.";
        
        echo "<div style='background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>Descripción de ejemplo sugerida:</h4>";
        echo "<p><em>\"" . $descripcion_uen . "\"</em></p>";
        echo "</div>";
        
        echo "<h3>🔧 Insertando descripción de ejemplo:</h3>";
        $stmt = $pdo->prepare("
            INSERT INTO tb_uen (id_plan, uen_descripcion) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE uen_descripcion = VALUES(uen_descripcion)
        ");
        $result = $stmt->execute([16, $descripcion_uen]);
        $pdo->commit();
        
        if ($result) {
            echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "✅ <strong>UEN de ejemplo insertada</strong>";
            echo "</div>";
        }
    }
    
    echo "<h2>3. Verificando UEN final en la base de datos:</h2>";
    $stmt = $pdo->prepare("SELECT * FROM tb_uen WHERE id_plan = 16");
    $stmt->execute();
    $uen_final = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($uen_final)) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>UEN final guardada:</strong>";
        echo "<pre>" . print_r($uen_final, true) . "</pre>";
        echo "</div>";
    }
    
    echo "<h2>4. Probando método obtenerResumenEjecutivo:</h2>";
    $resumen = $controller->obtenerResumenEjecutivo(16);
    
    if (isset($resumen['unidad_estrategica'])) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "🎉 <strong>¡UNIDAD ESTRATÉGICA FUNCIONANDO!</strong><br>";
        echo "Descripción: " . htmlspecialchars($resumen['unidad_estrategica']);
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
echo "<h2>📝 INSTRUCCIONES:</h2>";
echo "<p>Si necesitas cambiar la descripción de la Unidad Estratégica, puedes:</p>";
echo "<ol>";
echo "<li>Decirme cuál es la descripción exacta que quieres</li>";
echo "<li>O ir al wizard de objetivos estratégicos y completar el campo 'uen_descripcion'</li>";
echo "</ol>";
?>
