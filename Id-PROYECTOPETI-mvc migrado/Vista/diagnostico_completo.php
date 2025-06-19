<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Completo del Sistema PETI</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { border: 1px solid #ddd; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .warning { background-color: #fff3cd; border-color: #ffeeba; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-ok { color: #28a745; font-weight: bold; }
        .status-warning { color: #ffc107; font-weight: bold; }
        .status-error { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico Completo del Sistema PETI</h1>
    
    <?php
    require_once '../config/clsconexion.php';
    
    try {
        $conexion = new clsConexion();
        $pdo = $conexion->getConexion();
        echo "<div class='section success'><h2>✅ Conexión a Base de Datos: EXITOSA</h2></div>";
    } catch (Exception $e) {
        echo "<div class='section error'><h2>❌ Error de Conexión: " . htmlspecialchars($e->getMessage()) . "</h2></div>";
        exit();
    }
    ?>
    
    <!-- 1. ESTADO DE LA SESIÓN -->
    <div class="section">
        <h2>📊 Estado de la Sesión</h2>
        <?php if (isset($_SESSION['user'])): ?>
            <p class="status-ok">✅ Usuario logueado: <?php echo htmlspecialchars($_SESSION['user']['nombre'] ?? 'No definido'); ?></p>
            <p><strong>ID Usuario:</strong> <?php echo htmlspecialchars($_SESSION['user']['id_usuario'] ?? $_SESSION['user_id'] ?? 'No definido'); ?></p>
        <?php else: ?>
            <p class="status-error">❌ No hay usuario en sesión</p>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['plan_temporal'])): ?>
            <p class="status-ok">✅ Plan temporal activo</p>
            <p><strong>Paso actual:</strong> <?php echo htmlspecialchars($_SESSION['plan_temporal']['paso_actual'] ?? 'No definido'); ?></p>
            <p><strong>Módulos completados:</strong></p>
            <ul>
                <?php 
                $modulos = ['nuevo_plan', 'vision_mision', 'valores', 'objetivos', 'cadena_valor', 'matriz_bcg', 'fuerzas_porter', 'pest', 'estrategias', 'matriz_came', 'resumen_ejecutivo'];
                foreach ($modulos as $modulo) {
                    if (isset($_SESSION['plan_temporal'][$modulo])) {
                        echo "<li class='status-ok'>✅ $modulo</li>";
                    } else {
                        echo "<li class='status-warning'>⏳ $modulo</li>";
                    }
                }
                ?>
            </ul>
        <?php else: ?>
            <p class="status-warning">⚠️ No hay plan temporal en sesión</p>
        <?php endif; ?>
    </div>
    
    <!-- 2. ESTADO DE LAS TABLAS EN BD -->
    <div class="section">
        <h2>🗄️ Estado de las Tablas en Base de Datos</h2>
        <table>
            <tr><th>Tabla</th><th>Total Registros</th><th>Estado</th></tr>
            <?php
            $tablas_importantes = [
                'tb_sintesis_estrategias' => 'Síntesis Estratégica',
                'tb_fortalezas' => 'Fortalezas',
                'tb_debilidades' => 'Debilidades', 
                'tb_oportunidades' => 'Oportunidades',
                'tb_amenazas' => 'Amenazas',
                'tb_valores' => 'Valores',
                'tb_came' => 'Matriz CAME',
                'tb_respuestas_pest' => 'Análisis PEST',
                'tb_matrices_foda' => 'Evaluación FODA',
                'tb_usuario' => 'Usuarios',
                'tb_empresa' => 'Empresas'
            ];
            
            foreach ($tablas_importantes as $tabla => $descripcion) {
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabla");
                    $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                    
                    if ($count > 0) {
                        echo "<tr><td>$descripcion ($tabla)</td><td>$count</td><td class='status-ok'>✅ Con datos</td></tr>";
                    } else {
                        echo "<tr><td>$descripcion ($tabla)</td><td>0</td><td class='status-warning'>⚠️ Sin datos</td></tr>";
                    }
                } catch (Exception $e) {
                    echo "<tr><td>$descripcion ($tabla)</td><td>-</td><td class='status-error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
            }
            ?>
        </table>
    </div>
    
    <!-- 3. DATOS ESPECÍFICOS DE SÍNTESIS ESTRATÉGICA -->
    <div class="section">
        <h2>🎯 Datos de Síntesis Estratégica</h2>
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM tb_sintesis_estrategias ORDER BY fecha_actualizacion DESC");
            $sintesis = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($sintesis) {
                echo "<table>";
                echo "<tr><th>Relación</th><th>Tipología</th><th>Puntuación</th><th>Descripción</th><th>Última Actualización</th></tr>";
                foreach ($sintesis as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['relacion']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tipologia_estrategia']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['puntuacion']) . "</td>";
                    echo "<td>" . htmlspecialchars(substr($row['descripcion'], 0, 60)) . "...</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_actualizacion']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='status-warning'>⚠️ No hay datos de síntesis estratégica</p>";
            }
        } catch (Exception $e) {
            echo "<p class='status-error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </div>
    
    <!-- 4. DATOS FODA RECIENTES -->
    <div class="section">
        <h2>📋 Datos FODA Más Recientes</h2>
        <?php
        $tablas_foda = ['tb_fortalezas', 'tb_debilidades', 'tb_oportunidades', 'tb_amenazas'];
        foreach ($tablas_foda as $tabla) {
            echo "<h4>" . ucfirst(str_replace(['tb_', '_'], ['', ' '], $tabla)) . ":</h4>";
            try {
                $stmt = $pdo->query("SELECT descripcion, fecha_creacion FROM $tabla ORDER BY fecha_creacion DESC LIMIT 3");
                $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if ($datos) {
                    echo "<ul>";
                    foreach ($datos as $item) {
                        echo "<li>" . htmlspecialchars($item['descripcion']) . " <small>(" . htmlspecialchars($item['fecha_creacion']) . ")</small></li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>Sin datos</p>";
                }
            } catch (Exception $e) {
                echo "<p class='status-error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        ?>
    </div>
    
    <!-- 5. EVALUACIÓN DE MATRICES FODA -->
    <div class="section">
        <h2>🔢 Evaluación de Matrices FODA</h2>
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM tb_matrices_foda ORDER BY fecha_creacion DESC LIMIT 10");
            $matrices = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($matrices) {
                echo "<table>";
                echo "<tr><th>Tipo Relación</th><th>Elemento 1</th><th>Elemento 2</th><th>Puntuación</th><th>Fecha</th></tr>";
                foreach ($matrices as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['tipo_relacion'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['elemento_1'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['elemento_2'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['puntuacion'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['fecha_creacion'] ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='status-warning'>⚠️ No hay evaluaciones de matrices FODA guardadas</p>";
            }
        } catch (Exception $e) {
            echo "<p class='status-error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </div>
    
    <!-- 6. RECOMENDACIONES -->
    <div class="section">
        <h2>💡 Recomendaciones</h2>
        <h4>Para completar el flujo correctamente:</h4>
        <ol>
            <li><strong>Crear nuevo plan:</strong> <a href="nuevo_plan.php">nuevo_plan.php</a></li>
            <li><strong>Definir visión/misión:</strong> <a href="vision_mision.php">vision_mision.php</a></li>
            <li><strong>Establecer valores:</strong> <a href="valores.php">valores.php</a></li>
            <li><strong>Objetivos estratégicos:</strong> <a href="objetivos_estrategicos.php">objetivos_estrategicos.php</a></li>
            <li><strong>Cadena de valor:</strong> <a href="cadena_valor.php">cadena_valor.php</a></li>
            <li><strong>Matriz BCG:</strong> <a href="matriz_bcg.php">matriz_bcg.php</a></li>
            <li><strong>Fuerzas Porter:</strong> <a href="fuerzas_porter.php">fuerzas_porter.php</a></li>
            <li><strong>Análisis PEST:</strong> <a href="pest_nuevo.php">pest_nuevo.php</a></li>
            <li><strong>Identificación estratégica:</strong> <a href="identificacion_de_estrategias.php">identificacion_de_estrategias.php</a></li>
            <li><strong>Matriz CAME:</strong> <a href="matriz_came_ejemplo.php">matriz_came_ejemplo.php</a></li>
            <li><strong>Resumen ejecutivo:</strong> <a href="resumen_ejecutivo.php">resumen_ejecutivo.php</a></li>
        </ol>
        
        <h4>Estado actual del sistema:</h4>
        <?php
        $datos_en_sesion = isset($_SESSION['plan_temporal']) ? count($_SESSION['plan_temporal']) - 3 : 0; // -3 por los campos de control
        $datos_en_bd = 0;
        
        foreach ($tablas_importantes as $tabla => $desc) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabla");
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                if ($count > 0) $datos_en_bd++;
            } catch (Exception $e) {}
        }
        
        if ($datos_en_sesion > 5 && $datos_en_bd > 3) {
            echo "<p class='status-ok'>✅ Sistema funcionando correctamente. La mayoría de módulos tienen datos.</p>";
        } elseif ($datos_en_sesion > 2) {
            echo "<p class='status-warning'>⚠️ Hay datos en sesión pero pocos guardados en BD. Complete el flujo hasta el final.</p>";
        } else {
            echo "<p class='status-error'>❌ Pocos datos en el sistema. Recomiende empezar un nuevo plan desde el inicio.</p>";
        }
        ?>
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="nuevo_plan.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🆕 Crear Nuevo Plan</a>
        <a href="home.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">🏠 Ir al Inicio</a>
    </div>
</body>
</html>
