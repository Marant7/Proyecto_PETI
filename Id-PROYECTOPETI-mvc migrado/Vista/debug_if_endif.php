<?php
// Script para encontrar IFs sin cerrar en ver_plan.php

$contenido = file_get_contents('ver_plan.php');
$lineas = explode("\n", $contenido);

$ifs = 0;
$endifs = 0;
$problemas = [];

foreach ($lineas as $numero => $linea) {
    $linea_num = $numero + 1;
    
    // Contar ifs
    if (preg_match('/if\s*\([^)]*\)\s*:/', $linea)) {
        $ifs++;
        echo "Línea $linea_num: IF encontrado (Total IFs: $ifs)\n";
        echo "  -> " . trim($linea) . "\n";
    }
    
    // Contar endifs
    if (preg_match('/endif\s*;/', $linea)) {
        $endifs++;
        echo "Línea $linea_num: ENDIF encontrado (Total ENDIFs: $endifs)\n";
        echo "  -> " . trim($linea) . "\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "Total IFs: $ifs\n";
echo "Total ENDIFs: $endifs\n";
echo "Diferencia: " . ($ifs - $endifs) . "\n";

if ($ifs != $endifs) {
    echo "\n❌ PROBLEMA: Hay " . ($ifs - $endifs) . " IF(s) sin cerrar\n";
} else {
    echo "\n✅ CORRECTO: Todos los IFs tienen su ENDIF\n";
}
?>
