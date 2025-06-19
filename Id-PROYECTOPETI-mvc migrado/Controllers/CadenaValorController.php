<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'save') {
    // Guardar los datos de la cadena de valor en la sesión
    $_SESSION['temp_cadena_valor'] = [
        'porcentaje' => $_POST['porcentaje'] ?? '',
        'resultado' => $_POST['resultado'] ?? '',
        'fortalezas' => isset($_POST['fortalezas']) ? (array)$_POST['fortalezas'] : [],
        'debilidades' => isset($_POST['debilidades']) ? (array)$_POST['debilidades'] : [],
    ];
    // Redirigir a la matriz BCG (flujo wizard)
    header('Location: ../Vista/matriz_bcg.php');
    exit();
}
// Si no es POST o no es acción save, mostrar error
http_response_code(404);
echo 'Not found';
