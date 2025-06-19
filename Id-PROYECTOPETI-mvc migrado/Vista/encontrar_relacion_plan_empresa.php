<?php
// Script para encontrar la relación correcta entre planes y empresas
require_once '../config/clsconexion.php';

$conexion = new clsConexion();
$pdo = $conexion->getConexion();

echo "<h1>🔍 Encontrar Relación Plan → Empresa</h1>";

try {
    // Ver el plan ID 16 específicamente
    echo "<h2>📋 Plan ID 16</h2>";
    $stmt = $pdo->prepare("SELECT * FROM tb_plan_estrategico WHERE id_plan = 16");
    $stmt->execute();
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($plan) {
        echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
        foreach ($plan as $campo => $valor) {
            echo '<tr>';
            echo '<td><strong>' . $campo . '</strong></td>';
            echo '<td>' . htmlspecialchars($valor ?? '') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        
        // Verificar si hay algún campo que pueda relacionarse con id_empresa
        echo "<h2>🔗 Buscar Datos de Síntesis para este Plan</h2>";
        
        // Intentar diferentes relaciones posibles
        $relaciones_posibles = [
            ['campo' => 'id_empresa', 'valor' => 1], // El valor que vimos en la tabla
            ['campo' => 'id_empresa', 'valor' => $plan['id_usuario']], // Por si coincide con id_usuario
        ];
        
        // Si hay algún campo empresa en el plan
        if (isset($plan['empresa'])) {
            echo "<p><strong>Empresa del plan:</strong> " . $plan['empresa'] . "</p>";
        }
        
        foreach ($relaciones_posibles as $relacion) {
            echo "<h3>Probando: {$relacion['campo']} = {$relacion['valor']}</h3>";
            
            $stmt = $pdo->prepare("SELECT * FROM tb_sintesis_estrategias WHERE {$relacion['campo']} = ?");
            $stmt->execute([$relacion['valor']]);
            $estrategias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($estrategias) > 0) {
                echo "<p>✅ <strong>ENCONTRADO:</strong> " . count($estrategias) . " estrategias</p>";
                
                echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
                echo '<tr style="background-color: #f2f2f2;">';
                echo '<th>Relación</th><th>Tipología</th><th>Puntuación</th><th>Descripción</th>';
                echo '</tr>';
                
                foreach ($estrategias as $estrategia) {
                    echo '<tr>';
                    echo '<td>' . $estrategia['relacion'] . '</td>';
                    echo '<td>' . $estrategia['tipologia_estrategia'] . '</td>';
                    echo '<td>' . $estrategia['puntuacion'] . '</td>';
                    echo '<td>' . htmlspecialchars(substr($estrategia['descripcion'], 0, 100)) . '...</td>';
                    echo '</tr>';
                }
                echo '</table>';
                
                echo "<p><strong>🎯 SOLUCIÓN ENCONTRADA:</strong> Usar id_empresa = {$relacion['valor']} para obtener las estrategias del plan 16</p>";
                break;
            } else {
                echo "<p>❌ No encontrado</p>";
            }
        }
        
    } else {
        echo "<p>❌ No se encontró el plan ID 16</p>";
    }
    
    // Verificar todas las tablas relacionadas para encontrar el patrón
    echo "<h2>🔍 Verificar Otras Tablas</h2>";
    
    $tablas_relacionadas = [
        'tb_analisis_pest',
        'tb_matriz_came', 
        'tb_fortalezas',
        'tb_debilidades',
        'tb_oportunidades',
        'tb_amenazas'
    ];
    
    foreach ($tablas_relacionadas as $tabla) {
        echo "<h3>📋 Tabla: $tabla</h3>";
        
        try {
            // Intentar con id_plan = 16
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM $tabla WHERE id_plan = 16");
            $stmt->execute();
            $total_plan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            echo "<p>Con id_plan = 16: $total_plan registros</p>";
            
            // Si hay datos, mostrar un ejemplo
            if ($total_plan > 0) {
                $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE id_plan = 16 LIMIT 1");
                $stmt->execute();
                $ejemplo = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<pre>' . print_r($ejemplo, true) . '</pre>';
            }
            
        } catch (Exception $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
