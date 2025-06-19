<?php
// Script más preciso para encontrar IFs PHP sin cerrar

$contenido = file_get_contents('ver_plan.php');
$lineas = explode("\n", $contenido);

$stack = [];
$problemas = [];

foreach ($lineas as $numero => $linea) {
    $linea_num = $numero + 1;
    $linea_limpia = trim($linea);
    
    // Buscar <?php if
    if (preg_match('/\<\?php\s+if\s*\([^)]*\)\s*:\s*\?\>/', $linea_limpia)) {
        $stack[] = ['linea' => $linea_num, 'contenido' => $linea_limpia];
        echo "Línea $linea_num: IF abierto -> $linea_limpia\n";
    }
    
    // Buscar <?php endif;
    if (preg_match('/\<\?php\s+endif\s*;\s*\?\>/', $linea_limpia)) {
        if (!empty($stack)) {
            $if_abierto = array_pop($stack);
            echo "Línea $linea_num: ENDIF cierra IF de línea {$if_abierto['linea']}\n";
        } else {
            echo "Línea $linea_num: ❌ ENDIF sin IF correspondiente -> $linea_limpia\n";
        }
    }
}

echo "\n=== RESUMEN ===\n";
if (!empty($stack)) {
    echo "❌ IFs sin cerrar:\n";
    foreach ($stack as $if_sin_cerrar) {
        echo "  - Línea {$if_sin_cerrar['linea']}: {$if_sin_cerrar['contenido']}\n";
    }
} else {
    echo "✅ Todos los IFs están cerrados correctamente\n";
}

// Buscar específicamente el final del archivo
echo "\n=== ÚLTIMAS 10 LÍNEAS ===\n";
$total_lineas = count($lineas);
for ($i = max(0, $total_lineas - 10); $i < $total_lineas; $i++) {
    $linea_num = $i + 1;
    echo "Línea $linea_num: " . trim($lineas[$i]) . "\n";
}
?>
