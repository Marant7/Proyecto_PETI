<?php
// Simular parámetro GET y probar ver_plan.php
$_GET['id'] = '22';

// Capturar cualquier salida
ob_start();
try {
    include 'ver_plan.php';
    $output = ob_get_contents();
    ob_end_clean();
    
    // Verificar si se redirigió a home.php
    $headers = headers_list();
    $redirect_found = false;
    foreach ($headers as $header) {
        if (strpos($header, 'Location: home.php') !== false) {
            $redirect_found = true;
            break;
        }
    }
    
    if ($redirect_found) {
        echo "<h1>❌ PROBLEMA: Se está redirigiendo a home.php</h1>";
        echo "<p>Esto significa que obtenerDetallePlan() está retornando null o falso.</p>";
    } else {
        echo "<h1>✅ SUCCESS: ver_plan.php se carga correctamente</h1>";
        echo "<p>No hay redirección, el archivo se está ejecutando correctamente.</p>";
        
        // Mostrar parte del contenido para verificar
        $preview = substr($output, 0, 500);
        echo "<h3>Vista previa del contenido generado:</h3>";
        echo "<pre>" . htmlspecialchars($preview) . "...</pre>";
    }
    
} catch (Exception $e) {
    ob_end_clean();
    echo "<h1>❌ ERROR: Excepción al cargar ver_plan.php</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
