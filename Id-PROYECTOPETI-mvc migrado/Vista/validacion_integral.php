<?php
session_start();

// Script de validación integral del sistema
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación Integral del Sistema</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }
        .info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        h3 {
            color: #555;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            margin-left: 10px;
        }
        .ok { background-color: #28a745; color: white; }
        .fail { background-color: #dc3545; color: white; }
        .detail {
            font-size: 0.9em;
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
        .btn-primary { background-color: #007bff; }
        .btn-success { background-color: #28a745; }
        .btn-info { background-color: #17a2b8; }
        .btn-warning { background-color: #ffc107; color: #212529; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Validación Integral del Sistema de Planes Estratégicos</h1>
        
        <?php
        $tests_passed = 0;
        $total_tests = 0;
        
        // ===== TEST 1: CONFIGURACIÓN DE LA BASE DE DATOS =====
        $total_tests++;
        echo '<div class="test-section">';
        echo '<h2>1. 🗄️ Configuración de Base de Datos</h2>';
        
        try {
            require_once '../config/clsconexion.php';
            $conexion = new clsConexion();
            $pdo = $conexion->getConexion();
            
            if ($pdo instanceof PDO) {
                echo '<h3>Conexión a BD <span class="status ok">✓ OK</span></h3>';
                $tests_passed++;
            } else {
                echo '<h3>Conexión a BD <span class="status fail">✗ FAIL</span></h3>';
            }
            
            // Verificar tablas principales
            $tablas_requeridas = [
                'tb_usuario',
                'tb_planes_estrategicos',
                'tb_analisis_pest',
                'tb_matriz_came',
                'tb_sintesis_estrategias',
                'tb_oportunidades',
                'tb_amenazas',
                'tb_fortalezas',
                'tb_debilidades'
            ];
            
            $tablas_existentes = [];
            foreach ($tablas_requeridas as $tabla) {
                $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
                $stmt->execute([$tabla]);
                if ($stmt->fetch()) {
                    $tablas_existentes[] = $tabla;
                }
            }
            
            echo '<div class="detail">';
            echo '<strong>Tablas encontradas:</strong> ' . count($tablas_existentes) . '/' . count($tablas_requeridas) . '<br>';
            foreach ($tablas_requeridas as $tabla) {
                $existe = in_array($tabla, $tablas_existentes);
                echo "• $tabla " . ($existe ? '✓' : '✗') . '<br>';
            }
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<h3>Conexión a BD <span class="status fail">✗ ERROR</span></h3>';
            echo '<div class="detail">Error: ' . $e->getMessage() . '</div>';
        }
        echo '</div>';
        
        // ===== TEST 2: ESTADO DE LA SESIÓN =====
        $total_tests++;
        echo '<div class="test-section">';
        echo '<h2>2. 👤 Estado de la Sesión</h2>';
        
        if (isset($_SESSION['user'])) {
            echo '<h3>Sesión de Usuario <span class="status ok">✓ ACTIVA</span></h3>';
            $tests_passed++;
            
            $user = $_SESSION['user'];
            echo '<div class="detail">';
            echo '<strong>Datos del usuario:</strong><br>';
            foreach ($user as $key => $value) {
                echo "• $key: " . (is_array($value) ? json_encode($value) : $value) . '<br>';
            }
            echo '</div>';
            
            // Verificar ID de usuario
            $user_id = $user['id_usuario'] ?? $user['id'] ?? null;
            if ($user_id) {
                echo '<h3>ID de Usuario <span class="status ok">✓ DISPONIBLE</span></h3>';
                echo '<div class="detail">ID detectado: ' . $user_id . '</div>';
            } else {
                echo '<h3>ID de Usuario <span class="status fail">✗ NO ENCONTRADO</span></h3>';
            }
            
        } else {
            echo '<h3>Sesión de Usuario <span class="status fail">✗ NO ACTIVA</span></h3>';
            echo '<div class="detail">No hay sesión iniciada. <a href="login.php" class="btn btn-primary">Iniciar Sesión</a></div>';
        }
        echo '</div>';
        
        // ===== TEST 3: CONTROLADOR =====
        $total_tests++;
        echo '<div class="test-section">';
        echo '<h2>3. ⚙️ Controlador del Plan Estratégico</h2>';
        
        try {
            require_once '../Controllers/PlanEstrategicoController.php';
            $controller = new PlanEstrategicoController();
            echo '<h3>Controlador <span class="status ok">✓ CARGADO</span></h3>';
            $tests_passed++;
            
            // Verificar métodos críticos
            $metodos_requeridos = [
                'obtenerPlanesUsuario',
                'obtenerDetallesPlan',
                'guardarPaso1',
                'guardarPaso2',
                'guardarPaso3',
                'guardarPaso4',
                'guardarPaso5'
            ];
            
            echo '<div class="detail">';
            echo '<strong>Métodos disponibles:</strong><br>';
            foreach ($metodos_requeridos as $metodo) {
                $existe = method_exists($controller, $metodo);
                echo "• $metodo " . ($existe ? '✓' : '✗') . '<br>';
            }
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<h3>Controlador <span class="status fail">✗ ERROR</span></h3>';
            echo '<div class="detail">Error: ' . $e->getMessage() . '</div>';
        }
        echo '</div>';
        
        // ===== TEST 4: OBTENER PLANES DEL USUARIO =====
        if (isset($_SESSION['user']) && isset($controller)) {
            $total_tests++;
            echo '<div class="test-section">';
            echo '<h2>4. 📋 Planes del Usuario</h2>';
            
            try {
                $user_id = $_SESSION['user']['id_usuario'] ?? $_SESSION['user']['id'] ?? null;
                
                if ($user_id) {
                    $planes = $controller->obtenerPlanesUsuario($user_id);
                    
                    if (is_array($planes)) {
                        echo '<h3>Consulta de Planes <span class="status ok">✓ EXITOSA</span></h3>';
                        $tests_passed++;
                        
                        echo '<div class="detail">';
                        echo '<strong>Planes encontrados:</strong> ' . count($planes) . '<br><br>';
                        
                        if (count($planes) > 0) {
                            foreach ($planes as $index => $plan) {
                                echo "<strong>Plan " . ($index + 1) . ":</strong><br>";
                                echo "• ID: " . ($plan['id'] ?? 'N/A') . '<br>';
                                echo "• Nombre: " . ($plan['nombre_empresa'] ?? 'N/A') . '<br>';
                                echo "• Fecha: " . ($plan['fecha_creacion'] ?? 'N/A') . '<br>';
                                echo "• Usuario ID: " . ($plan['id_usuario'] ?? 'N/A') . '<br><br>';
                            }
                        } else {
                            echo 'No se encontraron planes para este usuario.<br>';
                            echo '<a href="wizard_paso1.php" class="btn btn-success">Crear Primer Plan</a>';
                        }
                        echo '</div>';
                    } else {
                        echo '<h3>Consulta de Planes <span class="status fail">✗ ERROR</span></h3>';
                        echo '<div class="detail">La consulta no devolvió un array válido</div>';
                    }
                } else {
                    echo '<h3>Consulta de Planes <span class="status fail">✗ SIN ID USUARIO</span></h3>';
                }
                
            } catch (Exception $e) {
                echo '<h3>Consulta de Planes <span class="status fail">✗ ERROR</span></h3>';
                echo '<div class="detail">Error: ' . $e->getMessage() . '</div>';
            }
            echo '</div>';
        }
        
        // ===== TEST 5: ARCHIVOS DEL SISTEMA =====
        $total_tests++;
        echo '<div class="test-section">';
        echo '<h2>5. 📁 Archivos del Sistema</h2>';
        
        $archivos_criticos = [
            'home.php' => 'Dashboard principal',
            'ver_plan.php' => 'Vista de plan completo',
            'resumen_plan.php' => 'Vista de resumen ejecutivo',
            'wizard_paso1.php' => 'Wizard paso 1',
            'wizard_paso2.php' => 'Wizard paso 2',
            'wizard_paso3.php' => 'Wizard paso 3',
            'wizard_paso4.php' => 'Wizard paso 4',
            'wizard_paso5.php' => 'Wizard paso 5',
            '../Controllers/PlanEstrategicoController.php' => 'Controlador principal',
            '../config/clsconexion.php' => 'Configuración BD'
        ];
        
        $archivos_encontrados = 0;
        foreach ($archivos_criticos as $archivo => $descripcion) {
            $existe = file_exists($archivo);
            if ($existe) $archivos_encontrados++;
            echo "• $descripcion ($archivo) " . ($existe ? '✓' : '✗') . '<br>';
        }
        
        if ($archivos_encontrados == count($archivos_criticos)) {
            echo '<h3>Archivos del Sistema <span class="status ok">✓ COMPLETOS</span></h3>';
            $tests_passed++;
        } else {
            echo '<h3>Archivos del Sistema <span class="status fail">✗ INCOMPLETOS</span></h3>';
        }
        echo '</div>';
        
        // ===== RESUMEN FINAL =====
        echo '<div class="test-section ' . ($tests_passed == $total_tests ? 'success' : ($tests_passed > $total_tests * 0.7 ? 'warning' : 'error')) . '">';
        echo '<h2>📊 Resumen de Validación</h2>';
        echo '<h3>Tests Pasados: ' . $tests_passed . '/' . $total_tests . '</h3>';
        
        $porcentaje = round(($tests_passed / $total_tests) * 100);
        echo '<div class="detail">';
        echo '<strong>Porcentaje de éxito:</strong> ' . $porcentaje . '%<br><br>';
        
        if ($porcentaje >= 90) {
            echo '🎉 <strong>EXCELENTE:</strong> El sistema está funcionando correctamente.';
        } elseif ($porcentaje >= 70) {
            echo '⚠️ <strong>BUENO:</strong> El sistema funciona pero puede tener algunos problemas menores.';
        } else {
            echo '❌ <strong>PROBLEMAS:</strong> Se detectaron problemas importantes que requieren atención.';
        }
        echo '</div>';
        echo '</div>';
        
        // ===== ENLACES DE NAVEGACIÓN =====
        echo '<div class="test-section info">';
        echo '<h2>🔗 Enlaces de Navegación</h2>';
        echo '<div class="detail">';
        
        if (isset($_SESSION['user'])) {
            echo '<a href="home.php" class="btn btn-primary">🏠 Dashboard Principal</a>';
            echo '<a href="wizard_paso1.php" class="btn btn-success">➕ Crear Nuevo Plan</a>';
            
            if (isset($planes) && count($planes) > 0) {
                $primer_plan = $planes[0];
                echo '<a href="ver_plan.php?id=' . $primer_plan['id'] . '" class="btn btn-info">👁️ Ver Primer Plan</a>';
                echo '<a href="resumen_plan.php?id=' . $primer_plan['id'] . '" class="btn btn-warning">📄 Resumen Ejecutivo</a>';
            }
        } else {
            echo '<a href="login.php" class="btn btn-primary">🔐 Iniciar Sesión</a>';
        }
        
        echo '<a href="logout.php" class="btn btn-warning">🚪 Cerrar Sesión</a>';
        echo '</div>';
        echo '</div>';
        ?>
    </div>
</body>
</html>
