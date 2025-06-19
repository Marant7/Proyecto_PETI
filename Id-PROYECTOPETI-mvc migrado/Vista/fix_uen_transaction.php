<?php
session_start();
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>🔧 SOLUCIONANDO EL PROBLEMA DE TRANSACCIONES UEN</h1>";

try {
    $controller = new PlanEstrategicoController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getConexion');
    $method->setAccessible(true);
    $pdo = $method->invoke($controller);
    
    echo "<h2>1. Verificando configuración de la base de datos:</h2>";
    
    // Verificar autocommit
    $stmt = $pdo->query("SELECT @@autocommit");
    $autocommit = $stmt->fetchColumn();
    echo "<p><strong>Autocommit:</strong> " . ($autocommit ? 'ON' : 'OFF') . "</p>";
    
    // Verificar si hay transacciones pendientes
    $stmt = $pdo->query("SELECT * FROM information_schema.INNODB_TRX");
    $trx = $stmt->fetchAll();
    echo "<p><strong>Transacciones activas:</strong> " . count($trx) . "</p>";
    
    echo "<h2>2. Verificando datos existentes:</h2>";
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_uen WHERE id_plan = 16");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    echo "<p><strong>Registros en tb_uen para plan 16:</strong> " . $count . "</p>";
    
    echo "<h2>3. Limpiando datos previos:</h2>";
    $stmt = $pdo->prepare("DELETE FROM tb_uen WHERE id_plan = 16");
    $stmt->execute();
    $pdo->commit(); // Asegurar commit
    echo "<div style='background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;'>⚠️ Datos previos eliminados</div>";
    
    echo "<h2>4. Insertando UEN con transacción explícita:</h2>";
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("INSERT INTO tb_uen (id_plan, uen_descripcion) VALUES (?, ?)");
    $uen_text = "Esta es la unidad estratégica de negocio que yo escribí manualmente en el wizard para definir el enfoque estratégico de mi empresa.";
    
    $result = $stmt->execute([16, $uen_text]);
    
    if ($result) {
        $pdo->commit();
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>UEN insertada y confirmada con commit explícito</strong>";
        echo "</div>";
    } else {
        $pdo->rollback();
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>Error al insertar UEN</strong>";
        echo "</div>";
    }
    
    echo "<h2>5. Verificando persistencia:</h2>";
    $stmt = $pdo->prepare("SELECT * FROM tb_uen WHERE id_plan = 16");
    $stmt->execute();
    $uen_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($uen_data)) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>UEN confirmada persistente:</strong>";
        echo "<pre>" . print_r($uen_data, true) . "</pre>";
        echo "</div>";
        
        echo "<h2>6. Probando método obtenerResumenEjecutivo:</h2>";
        $resumen = $controller->obtenerResumenEjecutivo(16);
        
        if (isset($resumen['unidad_estrategica'])) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "🎉 <strong>¡ÉXITO! unidad_estrategica encontrada:</strong><br>";
            echo htmlspecialchars($resumen['unidad_estrategica']);
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "❌ <strong>unidad_estrategica todavía no encontrada en resumen</strong>";
            echo "</div>";
        }
        
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>UEN NO persistió en la base de datos</strong>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    if (isset($pdo)) {
        try {
            $pdo->rollback();
        } catch (Exception $rollback_e) {
            // Ignorar error de rollback
        }
    }
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Error:</strong> " . $e->getMessage();
    echo "</div>";
}
?>
