<?php
session_start();
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>🔧 CREANDO TABLA tb_uen PARA UNIDAD ESTRATÉGICA</h1>";

try {
    $controller = new PlanEstrategicoController();
    // Usar reflexión para acceder al método privado
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getConexion');
    $method->setAccessible(true);
    $pdo = $method->invoke($controller);
    
    echo "<h2>📋 1. Creando tabla tb_uen:</h2>";
    
    $create_table_sql = "
        CREATE TABLE IF NOT EXISTS tb_uen (
            id_uen int(11) NOT NULL AUTO_INCREMENT,
            id_plan int(11) NOT NULL,
            uen_descripcion text NOT NULL,
            fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id_uen),
            KEY fk_uen_plan (id_plan),
            CONSTRAINT fk_uen_plan FOREIGN KEY (id_plan) REFERENCES tb_plan_estrategico (id_plan) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ";
    
    $pdo->exec($create_table_sql);
    echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>✅ Tabla tb_uen creada exitosamente</div>";
    
    echo "<h2>🔍 2. Verificando datos temporales:</h2>";
    if (isset($_SESSION['plan_temporal'])) {
        echo "<h3>Datos del plan temporal:</h3>";
        echo "<pre>" . print_r($_SESSION['plan_temporal'], true) . "</pre>";
        
        if (isset($_SESSION['plan_temporal']['objetivos']['uen_descripcion'])) {
            $uen_desc = $_SESSION['plan_temporal']['objetivos']['uen_descripcion'];
            echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "✅ <strong>UEN encontrada en sesión:</strong> " . htmlspecialchars($uen_desc);
            echo "</div>";
            
            // Insertar para el plan 16 como ejemplo
            echo "<h3>🚀 Insertando UEN para el plan 16:</h3>";
            $stmt = $pdo->prepare("
                INSERT INTO tb_uen (id_plan, uen_descripcion) 
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE uen_descripcion = VALUES(uen_descripcion)
            ");
            $stmt->execute([16, $uen_desc]);
            echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>✅ UEN insertada para el plan 16</div>";
        } else {
            echo "<div style='background: #ffebee; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
            echo "❌ No se encontró uen_descripcion en la sesión temporal";
            echo "</div>";
        }
    } else {
        echo "<div style='background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "⚠️ No hay plan temporal en la sesión";
        echo "</div>";
        
        // Insertar un ejemplo para el plan 16
        echo "<h3>📝 Insertando ejemplo de UEN para el plan 16:</h3>";
        $stmt = $pdo->prepare("
            INSERT INTO tb_uen (id_plan, uen_descripcion) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE uen_descripcion = VALUES(uen_descripcion)
        ");
        $stmt->execute([16, "Esta es la unidad estratégica de negocio definida por el usuario para orientar los objetivos estratégicos del plan."]);
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>✅ UEN de ejemplo insertada para el plan 16</div>";
    }
    
    echo "<h2>✅ 3. Verificando inserción:</h2>";
    $stmt = $pdo->prepare("SELECT * FROM tb_uen WHERE id_plan = 16");
    $stmt->execute();
    $uen_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($uen_data) {
        echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>Datos encontrados en tb_uen:</strong>";
        echo "<pre>" . print_r($uen_data, true) . "</pre>";
        echo "</div>";
    } else {
        echo "<div style='background: #ffebee; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ No se encontraron datos en tb_uen para el plan 16";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Error:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<h2>🎯 PRÓXIMO PASO:</h2>";
echo "<p>Ejecutar de nuevo el test del resumen para verificar que la Unidad Estratégica aparezca correctamente.</p>";
?>
