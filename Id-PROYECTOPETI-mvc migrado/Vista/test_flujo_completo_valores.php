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

echo "<h2>🔍 Test Completo: Flujo de Valores hasta Base de Datos</h2>";

echo "<h3>1. ✅ Inicializar sesión y simular wizard hasta valores</h3>";

// Simular plan temporal con datos previos
$_SESSION['plan_temporal'] = [
    'id_usuario' => $_SESSION['user']['id_usuario'],
    'fecha_inicio' => date('Y-m-d H:i:s'),
    'paso_actual' => 3,
    'nuevo_plan' => [
        'nombre_plan' => 'Plan Test Valores',
        'empresa' => 'Empresa Test',
        'descripcion' => 'Plan de prueba para verificar valores'
    ],
    'vision_mision' => [
        'vision' => 'Ser una empresa líder en el mercado',
        'mision' => 'Brindar productos y servicios de calidad'
    ]
];

echo "<p>✅ Plan temporal inicializado</p>";

echo "<h3>2. ✅ Simular guardado de valores (como en el wizard real)</h3>";

$valores_test = [
    'Honestidad',
    'Responsabilidad', 
    'Calidad',
    'Innovación',
    'Compromiso'
];

$_SESSION['plan_temporal']['valores'] = $valores_test;
$_SESSION['plan_temporal']['paso_actual'] = 4;

echo "<p>✅ Valores guardados en sesión:</p>";
foreach ($valores_test as $i => $valor) {
    echo "<p>" . ($i+1) . ". " . htmlspecialchars($valor) . "</p>";
}

echo "<h3>3. ✅ Simular completar wizard (objetivos, análisis, etc.)</h3>";

// Agregar datos mínimos para completar wizard
$_SESSION['plan_temporal']['objetivos'] = [
    'uen_descripcion' => 'Unidad estratégica de prueba',
    'objetivos_generales' => ['Objetivo general 1'],
    'objetivos_especificos' => ['Objetivo específico 1']
];

$_SESSION['plan_temporal']['resumen_ejecutivo'] = [
    'identificacion_estrategica' => 'Identificación estratégica de prueba',
    'conclusiones' => 'Conclusiones del plan estratégico'
];

echo "<p>✅ Wizard completado con datos mínimos</p>";

echo "<h3>4. ✅ Estado de la sesión antes de finalizar</h3>";
echo "<details><summary>Ver datos completos de sesión</summary>";
echo "<pre>" . print_r($_SESSION['plan_temporal'], true) . "</pre>";
echo "</details>";

echo "<h3>5. 💾 Probar finalizarPlan (guardar en BD)</h3>";

try {
    require_once '../Controllers/PlanEstrategicoController.php';
    $controller = new PlanEstrategicoController();
    
    echo "<p>📋 Ejecutando finalizarPlan...</p>";
    
    // Simular request
    $_SERVER['REQUEST_METHOD'] = 'POST';
    
    // Capturar la salida JSON
    ob_start();
    $controller->finalizarPlan();
    $json_response = ob_get_clean();
    
    echo "<p>📤 Respuesta del servidor:</p>";
    echo "<pre>" . htmlspecialchars($json_response) . "</pre>";
    
    $response = json_decode($json_response, true);
    
    if ($response && $response['success']) {
        $id_plan = $response['id_plan'];
        echo "<p>✅ Plan guardado exitosamente con ID: $id_plan</p>";
        
        // Verificar valores en base de datos
        echo "<h3>6. 🔍 Verificar valores en base de datos</h3>";
        
        require_once '../config/clsconexion.php';
        $conexion = new clsConexion();
        $pdo = $conexion->getConexion();
        
        $sql = "SELECT * FROM tb_valores WHERE id_plan = :id_plan ORDER BY id_valor";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_plan' => $id_plan]);
        $valores_bd = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($valores_bd) {
            echo "<p>✅ Valores encontrados en base de datos:</p>";
            foreach ($valores_bd as $valor) {
                echo "<p>🔹 ID: {$valor['id_valor']}, Valor: '{$valor['valor']}'</p>";
            }
            
            echo "<h3>🎉 RESULTADO: LOS VALORES SE GUARDARON CORRECTAMENTE</h3>";
            echo "<p>✅ Los valores se guardan en sesión durante el wizard</p>";
            echo "<p>✅ Los valores se transfieren a BD al finalizar el plan</p>";
            echo "<p>✅ El sistema funciona correctamente</p>";
            
        } else {
            echo "<p>❌ No se encontraron valores en la base de datos</p>";
            echo "<p>🔍 Revisar método guardarValores en el controlador</p>";
        }
        
    } else {
        echo "<p>❌ Error al guardar el plan:</p>";
        echo "<p>" . ($response['message'] ?? 'Error desconocido') . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error durante la prueba: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h3>7. 🧹 Limpiar sesión de prueba</h3>";
unset($_SESSION['plan_temporal']);
echo "<p>✅ Sesión limpiada</p>";
?>
