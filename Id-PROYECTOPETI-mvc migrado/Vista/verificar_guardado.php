<?php
session_start();
require_once '../config/clsconexion.php';

echo "<h2>🔍 Estado de la BD después del guardado</h2>";

try {
    $conexion = new clsConexion();
    $pdo = $conexion->getConexion();
    
    echo "<h3>📊 Conteo de registros por tabla:</h3>";
    
    $tablas = [
        'tb_valores' => 'Valores',
        'tb_fortalezas' => 'Fortalezas', 
        'tb_debilidades' => 'Debilidades',
        'tb_oportunidades' => 'Oportunidades',
        'tb_amenazas' => 'Amenazas',
        'tb_came' => 'Matriz CAME',
        'tb_sintesis_estrategias' => 'Síntesis Estratégica',
        'tb_respuestas_pest' => 'Respuestas PEST',
        'tb_matrices_foda' => 'Evaluaciones FODA'
    ];
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Tabla</th><th>Total Registros</th><th>Estado</th></tr>";
    
    foreach ($tablas as $tabla => $nombre) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabla");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $estado = $count > 0 ? "✅ Con datos ($count)" : "❌ Vacía";
            echo "<tr><td>$nombre</td><td>$count</td><td>$estado</td></tr>";
        } catch (Exception $e) {
            echo "<tr><td>$nombre</td><td>ERROR</td><td>❌ " . $e->getMessage() . "</td></tr>";
        }
    }
    echo "</table>";
    
    // Ver registros más recientes de valores
    echo "<h3>📝 Últimos valores guardados:</h3>";
    try {
        $stmt = $pdo->query("SELECT * FROM tb_valores ORDER BY id_valor DESC LIMIT 5");
        $valores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($valores) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>ID Plan</th><th>Valor</th><th>Descripción</th></tr>";
            foreach ($valores as $valor) {
                echo "<tr>";
                echo "<td>" . $valor['id_valor'] . "</td>";
                echo "<td>" . $valor['id_plan'] . "</td>";
                echo "<td>" . htmlspecialchars($valor['valor']) . "</td>";
                echo "<td>" . htmlspecialchars($valor['descripcion'] ?? '') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay valores en la tabla</p>";
        }
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    
    // Verificar si hay datos en sesión todavía
    echo "<h3>📋 Estado de la sesión:</h3>";
    if (isset($_SESSION['plan_temporal'])) {
        echo "<p>✅ Plan temporal existe</p>";
        echo "<p><strong>Módulos en sesión:</strong></p>";
        echo "<ul>";
        $modulos = ['nuevo_plan', 'vision_mision', 'valores', 'objetivos', 'cadena_valor', 'matriz_bcg', 'fuerzas_porter', 'pest', 'estrategias', 'matriz_came', 'resumen_ejecutivo'];
        foreach ($modulos as $modulo) {
            if (isset($_SESSION['plan_temporal'][$modulo])) {
                echo "<li>✅ $modulo</li>";
            } else {
                echo "<li>❌ $modulo</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>❌ No hay plan temporal en sesión</p>";
    }
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Error: " . $e->getMessage() . "</h3>";
}
?>
<p><a href="resumen_ejecutivo.php">Volver al Resumen Ejecutivo</a></p>
