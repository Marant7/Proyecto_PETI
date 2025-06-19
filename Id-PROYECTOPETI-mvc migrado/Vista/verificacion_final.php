<?php
session_start();
require_once '../config/clsconexion.php';
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>Verificación Final del Dashboard</h1>";

// 1. Verificar sesión de usuario
echo "<h2>1. Estado de la Sesión</h2>";
echo "<pre>";
echo "SESSION completa:\n";
var_dump($_SESSION);
echo "</pre>";

// Determinar ID de usuario actual
$user_id = $_SESSION['user']['id_usuario'] ?? 
           $_SESSION['user']['id'] ?? 
           $_SESSION['usuario_id'] ?? 
           $_SESSION['user_id'] ?? null;

echo "<h3>ID de Usuario Detectado: " . ($user_id ?? 'NULL') . "</h3>";

// 2. Probar el controlador
echo "<h2>2. Test del Controlador</h2>";
try {
    $controller = new PlanEstrategicoController();
    $planes = $controller->obtenerPlanesUsuario();
    
    echo "<h3>Planes encontrados: " . count($planes) . "</h3>";
    
    if (count($planes) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID Plan</th><th>Nombre</th><th>Empresa</th><th>Estado</th><th>Fecha Creación</th><th>Visión</th><th>Misión</th></tr>";
        
        foreach ($planes as $plan) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($plan['id_plan']) . "</td>";
            echo "<td>" . htmlspecialchars($plan['nombre_plan']) . "</td>";
            echo "<td>" . htmlspecialchars($plan['empresa']) . "</td>";
            echo "<td>" . htmlspecialchars($plan['estado']) . "</td>";
            echo "<td>" . htmlspecialchars($plan['fecha_creacion']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($plan['vision'] ?? 'N/A', 0, 50)) . "...</td>";
            echo "<td>" . htmlspecialchars(substr($plan['mision'] ?? 'N/A', 0, 50)) . "...</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Probar detalle del primer plan
        $primer_plan = $planes[0];
        echo "<h3>Detalle del Primer Plan (ID: " . $primer_plan['id_plan'] . ")</h3>";
        $detalle = $controller->obtenerDetallePlan($primer_plan['id_plan']);
        if ($detalle) {
            echo "<p><strong>✓ Detalle del plan obtenido correctamente</strong></p>";
            echo "<ul>";
            echo "<li>Visión/Misión: " . (isset($detalle['vision_mision']) ? 'SÍ' : 'NO') . "</li>";
            echo "<li>Valores: " . count($detalle['valores']) . " elementos</li>";
            echo "<li>Fuerzas Porter: " . count($detalle['fuerzas_porter']) . " elementos</li>";
            echo "<li>FODA - Oportunidades: " . count($detalle['foda']['oportunidades']) . " elementos</li>";
            echo "<li>FODA - Amenazas: " . count($detalle['foda']['amenazas']) . " elementos</li>";
            echo "<li>FODA - Fortalezas: " . count($detalle['foda']['fortalezas']) . " elementos</li>";
            echo "<li>FODA - Debilidades: " . count($detalle['foda']['debilidades']) . " elementos</li>";
            echo "<li>Cadena de Valor: " . count($detalle['cadena_valor']) . " elementos</li>";
            echo "<li>PEST: " . (isset($detalle['pest']) ? 'SÍ' : 'NO') . "</li>";
            echo "<li>Matriz CAME: " . count($detalle['matriz_came']) . " elementos</li>";
            echo "<li>Resumen Ejecutivo: " . (isset($detalle['resumen_ejecutivo']) ? 'SÍ' : 'NO') . "</li>";
            echo "</ul>";
        } else {
            echo "<p><strong>✗ Error al obtener detalle del plan</strong></p>";
        }
        
        // Probar resumen ejecutivo
        echo "<h3>Resumen Ejecutivo del Primer Plan</h3>";
        $resumen = $controller->obtenerResumenEjecutivo($primer_plan['id_plan']);
        if ($resumen) {
            echo "<p><strong>✓ Resumen ejecutivo obtenido correctamente</strong></p>";
            echo "<p>Situación actual: " . substr($resumen['situacion_actual'] ?? 'N/A', 0, 100) . "...</p>";
        } else {
            echo "<p><strong>✗ Error al obtener resumen ejecutivo</strong></p>";
        }
        
    } else {
        echo "<p><strong>No se encontraron planes para este usuario.</strong></p>";        // Verificar si hay planes en la base de datos
        echo "<h3>Verificar Planes en la Base de Datos</h3>";
        $conexion_obj = new clsConexion();
        $pdo = $conexion_obj->getConexion();
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_plan_estrategico");
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Total de planes en la base de datos: " . $total['total'] . "</p>";
        
        if ($total['total'] > 0) {
            $stmt = $pdo->query("
                SELECT id_plan, nombre_plan, empresa, id_usuario, fecha_creacion 
                FROM tb_plan_estrategico 
                ORDER BY fecha_creacion DESC 
                LIMIT 5
            ");
            $todos_planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h4>Últimos 5 planes (todos los usuarios):</h4>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID Plan</th><th>Nombre</th><th>Empresa</th><th>ID Usuario</th><th>Fecha</th></tr>";
            
            foreach ($todos_planes as $plan) {
                $highlight = ($plan['id_usuario'] == $user_id) ? 'style="background-color: yellow;"' : '';
                echo "<tr $highlight>";
                echo "<td>" . htmlspecialchars($plan['id_plan']) . "</td>";
                echo "<td>" . htmlspecialchars($plan['nombre_plan']) . "</td>";
                echo "<td>" . htmlspecialchars($plan['empresa']) . "</td>";
                echo "<td>" . htmlspecialchars($plan['id_usuario']) . "</td>";
                echo "<td>" . htmlspecialchars($plan['fecha_creacion']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><em>Los planes resaltados en amarillo pertenecen al usuario actual.</em></p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}

// 3. Links de navegación para probar
echo "<h2>3. Enlaces de Prueba</h2>";
echo "<p><a href='home.php' target='_blank'>🏠 Ir al Dashboard (home.php)</a></p>";

if (isset($planes) && count($planes) > 0) {
    $plan_id = $planes[0]['id_plan'];
    echo "<p><a href='ver_plan.php?id=" . $plan_id . "' target='_blank'>👁️ Ver Plan Completo (ID: " . $plan_id . ")</a></p>";
    echo "<p><a href='resumen_plan.php?id=" . $plan_id . "' target='_blank'>📋 Ver Resumen Ejecutivo (ID: " . $plan_id . ")</a></p>";
}

echo "<p><a href='wizard_estrategico.php' target='_blank'>✨ Crear Nuevo Plan (Wizard)</a></p>";

?>
