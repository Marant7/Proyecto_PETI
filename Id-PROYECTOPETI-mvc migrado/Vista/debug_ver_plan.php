<?php
// Script de depuración para diagnosticar el problema con ver_plan.php

session_start();

// Simular sesión para la prueba si no existe
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 1,
        'nombre' => 'Test User'
    ];
}

echo "<h1>Depuración de ver_plan.php</h1>";

// Verificar si tenemos un ID de plan
$id_plan = $_GET['id'] ?? null;
echo "<p><strong>ID Plan recibido:</strong> " . ($id_plan ? $id_plan : 'NO RECIBIDO') . "</p>";

if (!$id_plan) {
    echo "<p style='color: red;'>⚠️ No se recibió ID de plan. Esto es lo que causa la redirección a home.php</p>";
    echo "<p>Prueba accediendo con: debug_ver_plan.php?id=1</p>";
    exit();
}

// Cargar el controlador
require_once '../Controllers/PlanEstrategicoController.php';
$controller = new PlanEstrategicoController();

echo "<h2>Prueba de obtenerDetallePlan()</h2>";

try {
    $plan = $controller->obtenerDetallePlan($id_plan);
    
    if ($plan) {
        echo "<p style='color: green;'>✅ Plan encontrado exitosamente</p>";
        echo "<h3>Datos del plan:</h3>";
        echo "<pre>";
        print_r(array_keys($plan));
        echo "</pre>";
        
        echo "<h4>Información básica:</h4>";
        echo "<ul>";
        echo "<li><strong>Nombre:</strong> " . ($plan['nombre_plan'] ?? 'No definido') . "</li>";
        echo "<li><strong>Empresa:</strong> " . ($plan['empresa'] ?? 'No definido') . "</li>";
        echo "<li><strong>Fecha creación:</strong> " . ($plan['fecha_creacion'] ?? 'No definido') . "</li>";
        echo "</ul>";
        
        echo "<h4>Secciones disponibles:</h4>";
        echo "<ul>";
        $secciones = [
            'vision_mision' => 'Visión y Misión',
            'valores' => 'Valores',
            'objetivos' => 'Objetivos Estratégicos',
            'uen' => 'UEN',
            'cadena_valor' => 'Cadena de Valor',
            'matriz_bcg' => 'Matriz BCG',
            'fuerzas_porter' => 'Fuerzas de Porter',
            'pest' => 'Análisis PEST',
            'fortalezas' => 'Fortalezas',
            'debilidades' => 'Debilidades',
            'oportunidades' => 'Oportunidades',
            'amenazas' => 'Amenazas',
            'identificacion_estrategica' => 'Identificación Estratégica',
            'matriz_came' => 'Matriz CAME',
            'resumen_ejecutivo' => 'Resumen Ejecutivo'
        ];
        
        foreach ($secciones as $key => $nombre) {
            $disponible = isset($plan[$key]) && !empty($plan[$key]);
            $color = $disponible ? 'green' : 'orange';
            echo "<li style='color: $color;'><strong>$nombre:</strong> " . ($disponible ? '✅ Disponible' : '⚠️ Vacío o no disponible') . "</li>";
        }
        echo "</ul>";
        
    } else {
        echo "<p style='color: red;'>❌ No se pudo obtener el plan (retornó null)</p>";
        echo "<p>Esto significa que:</p>";
        echo "<ul>";
        echo "<li>El ID del plan no existe en la base de datos</li>";
        echo "<li>Hay un error en la consulta SQL</li>";
        echo "<li>Problema de conexión a la base de datos</li>";
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al ejecutar obtenerDetallePlan(): " . $e->getMessage() . "</p>";
}

// Verificar qué planes existen en la base de datos
echo "<h2>Planes disponibles en la base de datos:</h2>";
try {
    // Conectar directamente para verificar
    require_once '../config/clsconexion.php';
    $conexion = new clsConexion();
    $pdo = $conexion->getConexion();
    
    $stmt = $pdo->prepare("SELECT id_plan, nombre_plan, empresa, fecha_creacion FROM tb_plan_estrategico ORDER BY fecha_creacion DESC");
    $stmt->execute();
    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($planes) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nombre Plan</th><th>Empresa</th><th>Fecha Creación</th><th>Acción</th></tr>";
        foreach ($planes as $p) {
            echo "<tr>";
            echo "<td>" . $p['id_plan'] . "</td>";
            echo "<td>" . htmlspecialchars($p['nombre_plan']) . "</td>";
            echo "<td>" . htmlspecialchars($p['empresa']) . "</td>";
            echo "<td>" . $p['fecha_creacion'] . "</td>";
            echo "<td><a href='debug_ver_plan.php?id=" . $p['id_plan'] . "'>Probar</a> | <a href='ver_plan.php?id=" . $p['id_plan'] . "'>Ver Plan</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay planes en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al consultar planes: " . $e->getMessage() . "</p>";
}

echo "<h2>Enlaces de prueba:</h2>";
echo "<ul>";
echo "<li><a href='resumen_plan.php?id=1'>resumen_plan.php?id=1</a></li>";
echo "<li><a href='ver_plan.php?id=1'>ver_plan.php?id=1</a></li>";
echo "<li><a href='debug_ver_plan.php?id=1'>debug_ver_plan.php?id=1</a></li>";
echo "</ul>";
?>
