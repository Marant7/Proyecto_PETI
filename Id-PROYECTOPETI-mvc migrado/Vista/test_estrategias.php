<?php
session_start();

// Script de prueba para verificar el guardado de estrategias
echo "<h2>Test de Identificación de Estrategias</h2>";

// Simular datos de prueba
$_SESSION['foda_data'] = [
    'fortalezas' => ['Fortaleza 1', 'Fortaleza 2'],
    'debilidades' => ['Debilidad 1'],
    'oportunidades' => ['Oportunidad 1', 'Oportunidad 2'],
    'amenazas' => ['Amenaza 1']
];

echo "<h3>Datos FODA en sesión:</h3>";
echo "<pre>" . print_r($_SESSION['foda_data'], true) . "</pre>";

echo "<h3>Contenido completo de la sesión:</h3>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

// Formulario de prueba
?>
<h3>Formulario de prueba:</h3>
<form method="POST" action="../index.php?controller=PlanEstrategico&action=guardarPaso">
    <input type="hidden" name="paso" value="estrategias">
    <input type="hidden" name="nombre_paso" value="Identificación de Estrategias">
    <input type="hidden" name="save_evaluacion" value="1">
    
    <!-- Algunos datos de prueba -->
    <input type="hidden" name="fo_f1_o1" value="3">
    <input type="hidden" name="fo_f1_o2" value="2">
    <input type="hidden" name="fa_f1_a1" value="1">
    
    <button type="submit">Probar Guardado</button>
</form>

<hr>
<a href="identificacion_de_estrategias.php">Volver a Identificación de Estrategias</a>
