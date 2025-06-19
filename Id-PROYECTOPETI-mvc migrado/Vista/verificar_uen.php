<?php
// Script para verificar las tablas de objetivos y UEN
require_once '../Controllers/PlanEstrategicoController.php';

try {
    $controller = new PlanEstrategicoController();
    $pdo = $controller->getConexion();
    
    echo "<h2>🔍 VERIFICANDO TABLAS PARA UNIDAD ESTRATÉGICA</h2>";
    
    // Mostrar todas las tablas
    echo "<h3>📋 Tablas disponibles en la base de datos:</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<ul>";
    foreach ($tablas as $tabla) {
        echo "<li>" . $tabla . "</li>";
    }
    echo "</ul>";
    
    // Buscar datos de UEN en tb_objetivos_estrategicos
    echo "<h3>🎯 Buscando datos de UEN en tb_objetivos_estrategicos del plan 16:</h3>";
    try {
        $stmt = $pdo->prepare("DESCRIBE tb_objetivos_estrategicos");
        $stmt->execute();
        $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h4>Estructura de la tabla tb_objetivos_estrategicos:</h4>";
        echo "<ul>";
        foreach ($columnas as $columna) {
            echo "<li><strong>" . $columna['Field'] . "</strong> - " . $columna['Type'] . "</li>";
        }
        echo "</ul>";
        
        // Ver datos reales
        $stmt = $pdo->prepare("SELECT * FROM tb_objetivos_estrategicos WHERE id_plan = 16");
        $stmt->execute();
        $objetivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h4>Datos reales:</h4>";
        if (!empty($objetivos)) {
            echo "<pre>" . print_r($objetivos, true) . "</pre>";
        } else {
            echo "<p>No hay datos en tb_objetivos_estrategicos para el plan 16</p>";
        }
    } catch (Exception $e) {
        echo "<p>Error al consultar tb_objetivos_estrategicos: " . $e->getMessage() . "</p>";
    }
    
    // Verificar si existe alguna referencia a UEN en otras tablas
    echo "<h3>🔍 Buscando referencias a 'uen' en otras tablas:</h3>";
    foreach ($tablas as $tabla) {
        try {
            $stmt = $pdo->query("DESCRIBE $tabla");
            $columnas = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($columnas as $columna) {
                if (stripos($columna, 'uen') !== false) {
                    echo "<p>✅ Encontrada columna '<strong>$columna</strong>' en tabla '<strong>$tabla</strong>'</p>";
                    
                    // Ver datos si los hay
                    $stmt = $pdo->prepare("SELECT $columna FROM $tabla WHERE id_plan = 16 LIMIT 5");
                    $stmt->execute();
                    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($datos)) {
                        echo "<pre>" . print_r($datos, true) . "</pre>";
                    }
                }
            }
        } catch (Exception $e) {
            // Tabla no accesible, continuar
        }
    }
    
    // Buscar en el plan estratégico principal
    echo "<h3>📋 Verificando tb_plan_estrategico:</h3>";
    try {
        $stmt = $pdo->prepare("DESCRIBE tb_plan_estrategico");
        $stmt->execute();
        $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h4>Estructura:</h4>";
        echo "<ul>";
        foreach ($columnas as $columna) {
            echo "<li><strong>" . $columna['Field'] . "</strong> - " . $columna['Type'] . "</li>";
        }
        echo "</ul>";
        
        $stmt = $pdo->prepare("SELECT * FROM tb_plan_estrategico WHERE id_plan = 16");
        $stmt->execute();
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($plan) {
            echo "<h4>Datos del plan 16:</h4>";
            echo "<pre>" . print_r($plan, true) . "</pre>";
        }
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p>Error de conexión: " . $e->getMessage() . "</p>";
}
?>
