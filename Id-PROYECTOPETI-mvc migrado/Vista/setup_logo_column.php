<?php
// Script para agregar la columna logo_empresa si no existe
require_once '../config/clsconexion.php';

try {
    $conexion = new clsConexion();
    $pdo = $conexion->getConexion();
    
    // Verificar si la columna existe
    $stmt = $pdo->prepare("SHOW COLUMNS FROM tb_plan_estrategico LIKE 'logo_empresa'");
    $stmt->execute();
    $column_exists = $stmt->fetch();
    
    if (!$column_exists) {
        // Agregar la columna
        $sql = "ALTER TABLE tb_plan_estrategico ADD COLUMN logo_empresa VARCHAR(255) NULL";
        $pdo->exec($sql);
        echo "✅ Columna 'logo_empresa' agregada correctamente a la tabla tb_plan_estrategico\n";
    } else {
        echo "ℹ️ La columna 'logo_empresa' ya existe en la tabla tb_plan_estrategico\n";
    }
    
    // Verificar estructura final
    $stmt = $pdo->prepare("DESCRIBE tb_plan_estrategico");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\n📋 Estructura actual de tb_plan_estrategico:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
