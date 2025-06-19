<?php
// Prueba simple de ver_plan.php
echo "Iniciando prueba de ver_plan.php...\n";

session_start();
$_SESSION['user'] = ['id' => 1, 'nombre' => 'Test User'];
$_GET['id'] = '22';

echo "Sesión configurada, ID establecido a 22\n";

require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();

echo "Controlador cargado\n";

$plan = $controller->obtenerDetallePlan('22');

if ($plan) {
    echo "✅ Plan cargado exitosamente\n";
    echo "Plan: " . $plan['nombre_plan'] . "\n";
    echo "Empresa: " . $plan['empresa'] . "\n";
    echo "El archivo ver_plan.php debería funcionar correctamente.\n";
} else {
    echo "❌ Plan no encontrado - esto causaría redirección a home.php\n";
}

echo "Prueba completada.\n";
?>
