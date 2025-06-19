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

echo "<h2>Test Directo de Valores</h2>";

echo "<h3>1. Verificar estado inicial de sesión</h3>";
if (isset($_SESSION['plan_temporal'])) {
    echo "<p>✅ plan_temporal existe:</p>";
    echo "<pre>" . print_r($_SESSION['plan_temporal'], true) . "</pre>";
} else {
    echo "<p>❌ plan_temporal NO existe</p>";
    $_SESSION['plan_temporal'] = [
        'id_usuario' => $_SESSION['user']['id_usuario'],
        'fecha_inicio' => date('Y-m-d H:i:s'),
        'paso_actual' => 1
    ];
    echo "<p>✅ plan_temporal creado</p>";
}

echo "<h3>2. Simular guardado manual de valores</h3>";
$valores_test = ['Honestidad', 'Responsabilidad', 'Calidad', 'Innovación'];
$_SESSION['plan_temporal']['valores'] = $valores_test;
echo "<p>✅ Valores guardados manualmente</p>";

echo "<h3>3. Verificar que se guardaron</h3>";
if (isset($_SESSION['plan_temporal']['valores'])) {
    echo "<p>✅ Valores encontrados:</p>";
    foreach ($_SESSION['plan_temporal']['valores'] as $i => $valor) {
        echo "<p>" . ($i+1) . ". " . htmlspecialchars($valor) . "</p>";
    }
} else {
    echo "<p>❌ Valores NO encontrados</p>";
}

echo "<h3>4. Probar guardado real mediante formulario (simulado)</h3>";

// Simular datos POST como los envía el formulario real
$_POST = [
    'paso' => '3',
    'nombre_paso' => 'valores',
    'valores' => [
        'Integridad',
        'Excelencia',
        'Compromiso'
    ]
];

$_SERVER['REQUEST_METHOD'] = 'POST';

echo "<p>Datos POST:</p>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

// Incluir y ejecutar controlador
try {
    // Limpiar cualquier salida para evitar problemas con headers
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Capturar la salida del controlador
    ob_start();
    
    // Incluir controlador
    require_once '../Controllers/PlanEstrategicoController.php';
    $controller = new PlanEstrategicoController();
    
    // Usar reflexión para acceder a métodos privados
    $reflection = new ReflectionClass($controller);
    
    $procesarDatos = $reflection->getMethod('procesarDatosPaso');
    $procesarDatos->setAccessible(true);
    $datos_procesados = $procesarDatos->invoke($controller, 'valores', $_POST);
    
    $guardarDatos = $reflection->getMethod('guardarDatosPaso');
    $guardarDatos->setAccessible(true);
    $resultado = $guardarDatos->invoke($controller, '3', 'valores', $datos_procesados);
    
    // Limpiar buffer de salida
    ob_end_clean();
    
    echo "<p>✅ Datos procesados correctamente:</p>";
    echo "<pre>" . print_r($datos_procesados, true) . "</pre>";
    
    echo "<p>Resultado del guardado: " . ($resultado ? "✅ Éxito" : "❌ Error") . "</p>";
    
} catch (Exception $e) {
    ob_end_clean();
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<h3>5. Estado final de la sesión</h3>";
echo "<pre>" . print_r($_SESSION['plan_temporal'], true) . "</pre>";

echo "<h3>6. Verificar base de datos (si hay datos guardados)</h3>";

// Conectar a la base de datos y verificar si hay valores guardados
try {
    require_once '../config/clsconexion.php';
    $conexion = new clsconexion();
    $pdo = $conexion->obtenerConexion();
    
    // Buscar valores en la tabla
    $sql = "SELECT * FROM tb_valores WHERE id_plan IN (SELECT id_plan FROM tb_plan_estrategico WHERE id_usuario = :id_usuario) ORDER BY id_plan DESC LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_usuario' => $_SESSION['user']['id_usuario']]);
    $valores_bd = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($valores_bd) {
        echo "<p>✅ Valores encontrados en base de datos:</p>";
        foreach ($valores_bd as $valor) {
            echo "<p>ID: {$valor['id_valor']}, Plan: {$valor['id_plan']}, Valor: {$valor['valor']}</p>";
        }
    } else {
        echo "<p>❌ No se encontraron valores en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error al verificar base de datos: " . $e->getMessage() . "</p>";
}
?>
