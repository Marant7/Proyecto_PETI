<?php
session_start();

// Script para verificar si el resumen estratégico se guarda en la BD
echo "<h2>Verificación del Resumen Estratégico en BD</h2>";

// Incluir configuración de BD
require_once '../config/clsconexion.php';

try {
    $conexion = new clsConexion();
    $pdo = $conexion->getConexion(); // Usar el método correcto
    
    echo "<h3>✓ Conexión a BD establecida</h3>";
    
    // Verificar estructura de la tabla tb_sintesis_estrategias
    echo "<h3>Estructura de tb_sintesis_estrategias:</h3>";
    $stmt = $pdo->query("DESCRIBE tb_sintesis_estrategias");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? '') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    
    // Verificar datos actuales en la tabla
    echo "<h3>Datos actuales en tb_sintesis_estrategias:</h3>";
    $stmt = $pdo->query("SELECT * FROM tb_sintesis_estrategias ORDER BY fecha_actualizacion DESC LIMIT 10");
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($resultados) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>ID Empresa</th><th>Relación</th><th>Tipología</th><th>Puntuación</th><th>Descripción</th><th>Fecha Actualización</th></tr>";
        foreach ($resultados as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id_sintesis']) . "</td>";
            echo "<td>" . htmlspecialchars($row['id_empresa']) . "</td>";
            echo "<td>" . htmlspecialchars($row['relacion']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tipologia_estrategia']) . "</td>";
            echo "<td>" . htmlspecialchars($row['puntuacion']) . "</td>";
            echo "<td>" . htmlspecialchars($row['descripcion'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['fecha_actualizacion']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay datos en la tabla tb_sintesis_estrategias</p>";
    }
    
    // Verificar datos de sesión relacionados con estrategias
    echo "<h3>Datos de estrategias en sesión:</h3>";
    if (isset($_SESSION['estrategias_evaluacion'])) {
        echo "<pre>" . print_r($_SESSION['estrategias_evaluacion'], true) . "</pre>";
    } else {
        echo "<p>No hay datos de evaluación de estrategias en sesión</p>";
    }
    
    // Verificar datos del plan temporal
    echo "<h3>Plan temporal en sesión:</h3>";
    if (isset($_SESSION['plan_temporal']['estrategias'])) {
        echo "<pre>" . print_r($_SESSION['plan_temporal']['estrategias'], true) . "</pre>";
    } else {
        echo "<p>No hay datos de estrategias en plan_temporal</p>";
    }
    
    // Información del usuario
    echo "<h3>Información del usuario:</h3>";
    if (isset($_SESSION['user'])) {
        echo "<pre>" . print_r($_SESSION['user'], true) . "</pre>";
    } else {
        echo "<p>No hay información de usuario en sesión</p>";
    }
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
?>

<hr>
<p><a href="identificacion_de_estrategias.php">Volver a Identificación de Estrategias</a></p>
<p><a href="../index.php?controller=PlanEstrategico&action=finalizarPlan">Probar Finalizar Plan (esto debería guardar en BD)</a></p>
