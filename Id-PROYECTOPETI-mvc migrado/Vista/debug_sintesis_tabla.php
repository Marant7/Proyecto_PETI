<?php
// Script simple para verificar la estructura de tb_sintesis_estrategias
require_once '../config/clsconexion.php';

$conexion = new clsConexion();
$pdo = $conexion->getConexion();

echo "<h1>🔍 Verificación de tb_sintesis_estrategias</h1>";

try {
    // Ver estructura de la tabla
    echo "<h2>📋 Estructura de la tabla</h2>";
    $stmt = $pdo->prepare("DESCRIBE tb_sintesis_estrategias");
    $stmt->execute();
    $estructura = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
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
    
    // Ver todos los datos de la tabla
    echo "<h2>📊 Todos los datos en la tabla</h2>";
    $stmt = $pdo->prepare("SELECT * FROM tb_sintesis_estrategias");
    $stmt->execute();
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p><strong>Total de registros:</strong> " . count($datos) . "</p>";
    
    if (count($datos) > 0) {
        echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
        echo '<tr style="background-color: #f2f2f2;">';
        foreach (array_keys($datos[0]) as $columna) {
            echo '<th>' . $columna . '</th>';
        }
        echo '</tr>';
        
        foreach ($datos as $fila) {
            echo '<tr>';
            foreach ($fila as $valor) {
                echo '<td>' . htmlspecialchars(substr($valor ?? '', 0, 100)) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "<p>No hay datos en la tabla</p>";
    }
    
    // Buscar registros relacionados con el plan 16
    echo "<h2>🎯 Búsqueda específica para Plan ID 16</h2>";
    
    // Intentar con diferentes posibles nombres de columnas
    $posibles_relaciones = [
        'id_plan',
        'id_estrategia', 
        'plan_id',
        'estrategia_id',
        'id_sintesis',
        'sintesis_id'
    ];
    
    foreach ($posibles_relaciones as $columna) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM tb_sintesis_estrategias WHERE $columna = 16");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($resultados) > 0) {
                echo "<p>✅ <strong>Encontrado con columna '$columna':</strong> " . count($resultados) . " registros</p>";
                echo '<pre>' . print_r($resultados, true) . '</pre>';
            } else {
                echo "<p>❌ No encontrado con columna '$columna'</p>";
            }
        } catch (Exception $e) {
            echo "<p>⚠️ Error con columna '$columna': " . $e->getMessage() . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
