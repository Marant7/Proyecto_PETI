<?php
session_start();

// Simular un usuario logueado
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id_usuario' => 1,
        'nombre' => 'Test',
        'apellido' => 'User'
    ];
}

echo "<h2>Test de Guardado de Valores - Estado de Sesión</h2>";

echo "<h3>Estado INICIAL de la sesión:</h3>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

// Simular guardado manual como lo haría el controlador
$_SESSION['plan_temporal'] = [
    'id_usuario' => $_SESSION['user']['id_usuario'],
    'fecha_inicio' => date('Y-m-d H:i:s'),
    'paso_actual' => 1,
    'datos' => []
];

// Simular valores guardados
$valores_test = ['Honestidad', 'Responsabilidad', 'Calidad', 'Innovación'];
$_SESSION['plan_temporal']['valores'] = $valores_test;
$_SESSION['plan_temporal']['paso_actual'] = 4; // Siguiente paso después de valores

echo "<h3>Después de guardar valores:</h3>";
echo "<pre>" . print_r($_SESSION['plan_temporal'], true) . "</pre>";

// Ahora vamos a probar si podemos recuperar los valores
echo "<h3>Prueba de recuperación de valores:</h3>";
if (isset($_SESSION['plan_temporal']['valores'])) {
    echo "<p>✅ Valores encontrados en sesión:</p>";
    echo "<ul>";
    foreach ($_SESSION['plan_temporal']['valores'] as $valor) {
        echo "<li>" . htmlspecialchars($valor) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>❌ No se encontraron valores en sesión</p>";
}

// Ahora probamos el controlador real
echo "<hr><h3>Probando con el controlador real:</h3>";

// Simular POST real
$_POST = [
    'paso' => '3',
    'nombre_paso' => 'valores',
    'valores' => [
        'Honestidad Real',
        'Responsabilidad Real',
        'Calidad Real'
    ]
];

$_SERVER['REQUEST_METHOD'] = 'POST';

echo "<p>Datos POST enviados:</p>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

// Test con fetch real al controlador
echo "<h3>Enviando petición real al controlador:</h3>";

// Crear URL del controlador
$url = 'http://localhost/Proyecto_PETI/Id-PROYECTOPETI-mvc/index.php?controller=PlanEstrategico&action=guardarPaso';

// Usar cURL para simular la petición AJAX
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

// Pasar cookies de sesión
$session_name = session_name();
$session_id = session_id();
curl_setopt($ch, CURLOPT_COOKIE, $session_name . '=' . $session_id);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<p>Código HTTP: " . $http_code . "</p>";
echo "<p>Respuesta del servidor:</p>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";

// Verificar estado final de la sesión
echo "<h3>Estado FINAL de la sesión:</h3>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";
?>
?>
