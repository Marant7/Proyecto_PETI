<?php
// Script para verificar la estructura real de las tablas
require_once '../config/clsconexion.php';

$conexion = new clsConexion();
$pdo = $conexion->getConexion();

echo "<h1>Estructura de Tablas Relacionadas con Planes</h1>";

$tablas = [
    'tb_plan_estrategico',
    'tb_sintesis_estrategias', 
    'tb_analisis_pest',
    'tb_matriz_came',
    'tb_fortalezas',
    'tb_debilidades',
    'tb_oportunidades',
    'tb_amenazas'
];

foreach ($tablas as $tabla) {
    echo "<h2>📋 Tabla: $tabla</h2>";
    
    try {
        $stmt = $pdo->prepare("DESCRIBE $tabla");
        $stmt->execute();
        $estructura = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<table border="1" style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">';
        echo '<tr style="background-color: #f2f2f2;"><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>';
        
        foreach ($estructura as $campo) {
            echo '<tr>';
            echo '<td><strong>' . $campo['Field'] . '</strong></td>';
            echo '<td>' . $campo['Type'] . '</td>';
            echo '<td>' . $campo['Null'] . '</td>';
            echo '<td>' . $campo['Key'] . '</td>';
            echo '<td>' . ($campo['Default'] ?? 'NULL') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        
        // Mostrar algunos datos de ejemplo
        $stmt = $pdo->prepare("SELECT * FROM $tabla LIMIT 2");
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($datos) > 0) {
            echo "<p><strong>Ejemplo de datos:</strong></p>";
            echo '<pre style="background: #f8f9fa; padding: 10px; border-radius: 4px;">';
            print_r($datos[0]);
            echo '</pre>';
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}
?>
