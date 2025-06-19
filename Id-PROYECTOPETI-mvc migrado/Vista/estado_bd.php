<?php
require_once '../config/clsconexion.php';

echo "<h1>Estado Actual de la Base de Datos</h1>";

try {
    $conexion = new clsConexion();
    $pdo = $conexion->getConexion();
    
    // Verificar tabla de planes
    echo "<h2>1. Planes Estratégicos</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_plan_estrategico");
    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Total de planes:</strong> " . $total['total'] . "</p>";
    
    if ($total['total'] > 0) {
        $stmt = $pdo->query("
            SELECT id_plan, nombre_plan, empresa, id_usuario, estado, fecha_creacion 
            FROM tb_plan_estrategico 
            ORDER BY fecha_creacion DESC
        ");
        $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th>ID</th><th>Nombre</th><th>Empresa</th><th>Usuario</th><th>Estado</th><th>Fecha</th>";
        echo "</tr>";
        
        foreach ($planes as $plan) {
            echo "<tr>";
            echo "<td>" . $plan['id_plan'] . "</td>";
            echo "<td>" . htmlspecialchars($plan['nombre_plan']) . "</td>";
            echo "<td>" . htmlspecialchars($plan['empresa']) . "</td>";
            echo "<td>" . $plan['id_usuario'] . "</td>";
            echo "<td>" . $plan['estado'] . "</td>";
            echo "<td>" . $plan['fecha_creacion'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Verificar otras tablas relacionadas
    $tablas = [
        'tb_vision_mision',
        'tb_valores',
        'tb_fuerzas_porter',
        'tb_oportunidades',
        'tb_amenazas',
        'tb_fortalezas',
        'tb_debilidades',
        'tb_cadena_valor',
        'tb_pest',
        'tb_matriz_came',
        'tb_resumen_ejecutivo'
    ];
    
    echo "<h2>2. Datos por Módulo</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>Tabla</th><th>Total Registros</th><th>Planes con Datos</th>";
    echo "</tr>";
    
    foreach ($tablas as $tabla) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabla");
            $total_registros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            $stmt = $pdo->query("SELECT COUNT(DISTINCT id_plan) as planes FROM $tabla");
            $planes_con_datos = $stmt->fetch(PDO::FETCH_ASSOC)['planes'];
            
            echo "<tr>";
            echo "<td>" . $tabla . "</td>";
            echo "<td>" . $total_registros . "</td>";
            echo "<td>" . $planes_con_datos . "</td>";
            echo "</tr>";
        } catch (Exception $e) {
            echo "<tr>";
            echo "<td>" . $tabla . "</td>";
            echo "<td colspan='2' style='color: red;'>Error: " . $e->getMessage() . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    
    // Si hay planes, mostrar los IDs para pruebas
    if ($total['total'] > 0) {
        echo "<h2>3. Enlaces de Prueba</h2>";
        $primer_plan = $planes[0]['id_plan'];
        echo "<p><strong>Enlaces para probar con el plan ID " . $primer_plan . ":</strong></p>";
        echo "<ul>";
        echo "<li><a href='ver_plan.php?id=" . $primer_plan . "' target='_blank'>Ver Plan Completo</a></li>";
        echo "<li><a href='resumen_plan.php?id=" . $primer_plan . "' target='_blank'>Ver Resumen Ejecutivo</a></li>";
        echo "</ul>";
    }
    
    // Verificar usuarios
    echo "<h2>4. Usuarios</h2>";
    try {
        $stmt = $pdo->query("SELECT id_usuario, nombre, email, usuario FROM tb_usuario LIMIT 5");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($usuarios) > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr style='background-color: #f0f0f0;'>";
            echo "<th>ID Usuario</th><th>Nombre</th><th>Email</th><th>Usuario</th>";
            echo "</tr>";
            
            foreach ($usuarios as $usuario) {
                echo "<tr>";
                echo "<td>" . $usuario['id_usuario'] . "</td>";
                echo "<td>" . htmlspecialchars($usuario['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['email']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['usuario']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay usuarios en la tabla tb_usuario.</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error al consultar usuarios: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error de conexión: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Navegación</h2>";
echo "<ul>";
echo "<li><a href='test_dashboard.php'>Dashboard de Prueba</a></li>";
echo "<li><a href='diagnostico_sesion.php'>Diagnóstico de Sesión</a></li>";
echo "<li><a href='wizard_estrategico.php'>Crear Nuevo Plan</a></li>";
echo "<li><a href='home.php'>Dashboard Real</a></li>";
echo "</ul>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f5f5f5;
}

h1 {
    color: #333;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
}

h2 {
    color: #555;
    margin-top: 30px;
}

table {
    margin-top: 10px;
    font-size: 14px;
    background-color: white;
}

th {
    padding: 8px;
    text-align: left;
    background-color: #f0f0f0;
}

td {
    padding: 8px;
}

ul {
    background-color: white;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #667eea;
}

a {
    color: #667eea;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
