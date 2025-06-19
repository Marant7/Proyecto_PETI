<?php
session_start();

echo "<h1>Diagnóstico de Datos de Sesión - Resumen Ejecutivo</h1>";

if (!isset($_SESSION['plan_temporal'])) {
    echo "<p style='color: red;'>❌ No hay plan temporal en sesión</p>";
    exit();
}

$plan_temporal = $_SESSION['plan_temporal'];

echo "<h2>📋 Estructura del Plan Temporal:</h2>";
echo "<pre>";
foreach ($plan_temporal as $modulo => $datos) {
    echo "<strong>$modulo:</strong> " . (is_array($datos) ? count($datos) . " elementos" : "dato simple") . "\n";
}
echo "</pre>";

echo "<h2>🎯 Objetivos Estratégicos:</h2>";
if (isset($plan_temporal['objetivos'])) {
    echo "<h3>Datos completos de objetivos:</h3>";
    echo "<pre>";
    print_r($plan_temporal['objetivos']);
    echo "</pre>";
    
    $objetivos_generales = $plan_temporal['objetivos']['objetivos_generales'] ?? [];
    $objetivos_especificos = $plan_temporal['objetivos']['objetivos_especificos'] ?? [];
    
    echo "<h3>Objetivos Generales procesados:</h3>";
    echo "<pre>";
    print_r($objetivos_generales);
    echo "</pre>";
    
    echo "<h3>Objetivos Específicos procesados:</h3>";
    echo "<pre>";
    print_r($objetivos_especificos);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>❌ No hay datos de objetivos</p>";
}

echo "<h2>⚡ Matriz CAME:</h2>";
if (isset($plan_temporal['matriz_came'])) {
    echo "<h3>Datos completos de CAME:</h3>";
    echo "<pre>";
    print_r($plan_temporal['matriz_came']);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>❌ No hay datos de matriz CAME</p>";
}

echo "<h2>🔍 Análisis FODA:</h2>";
$modulos_foda = ['cadena_valor', 'matriz_bcg', 'pest', 'fuerzas_porter'];
foreach ($modulos_foda as $modulo) {
    echo "<h3>$modulo:</h3>";
    if (isset($plan_temporal[$modulo])) {
        echo "<pre>";
        print_r($plan_temporal[$modulo]);
        echo "</pre>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay datos de $modulo</p>";
    }
}
?>
