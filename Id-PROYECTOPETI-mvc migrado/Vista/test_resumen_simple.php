<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>TEST RESUMEN EJECUTIVO</h1>";

if ($_POST) {
    echo "<h2>Datos POST recibidos:</h2>";
    echo "<pre>";
    foreach ($_POST as $key => $value) {
        echo "$key: " . (is_array($value) ? json_encode($value) : $value) . "\n";
    }
    echo "</pre>";
    
    echo "<h2>Validación de campos requeridos:</h2>";
    echo "paso: " . ($_POST['paso'] ?? 'NULL') . "\n";
    echo "nombre_paso: " . ($_POST['nombre_paso'] ?? 'NULL') . "\n";
    echo "emprendedores_promotores: " . ($_POST['emprendedores_promotores'] ?? 'NULL') . "\n";
    echo "identificacion_estrategica: " . ($_POST['identificacion_estrategica'] ?? 'NULL') . "\n";
    echo "conclusiones: " . ($_POST['conclusiones'] ?? 'NULL') . "\n";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Resumen Ejecutivo</title>
</head>
<body>
    <form method="post">
        <input type="hidden" name="paso" value="11">
        <input type="hidden" name="nombre_paso" value="resumen_ejecutivo">
        
        <h3>Emprendedores/Promotores:</h3>
        <textarea name="emprendedores_promotores" rows="3" style="width:100%">Test emprendedores</textarea>
        
        <h3>Identificación Estratégica:</h3>
        <textarea name="identificacion_estrategica" rows="3" style="width:100%">Test identificación</textarea>
        
        <h3>Conclusiones:</h3>
        <textarea name="conclusiones" rows="3" style="width:100%">Test conclusiones</textarea>
        
        <br><br>
        <button type="submit">Enviar Test</button>
    </form>

    <h2>Contenido de la sesión:</h2>
    <pre>
<?php print_r($_SESSION['plan_temporal'] ?? 'No hay plan temporal'); ?>
    </pre>
</body>
</html>
