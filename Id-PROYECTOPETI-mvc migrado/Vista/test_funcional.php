<?php
session_start();

// Script de testing funcional completo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Funcional Completo</title>
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
        .test-step {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
        }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-primary { background-color: #007bff; }
        .btn-success { background-color: #28a745; }
        .btn-info { background-color: #17a2b8; }
        .btn-warning { background-color: #ffc107; color: #212529; }
        .btn-danger { background-color: #dc3545; }
        pre { background-color: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Test Funcional Completo del Sistema</h1>
        <p>Este script ejecuta una batería completa de tests para validar todas las funcionalidades del sistema.</p>
        
        <?php
        $resultados = [];
        
        // ===== TEST 1: BASE DE DATOS =====
        echo '<div class="test-step">';
        echo '<h2>1. 🗄️ Test de Base de Datos</h2>';
        
        try {
            require_once '../config/clsconexion.php';
            $conexion = new clsConexion();
            $pdo = $conexion->getConexion();
            
            if ($pdo instanceof PDO) {
                echo '<p class="success">✅ Conexión a BD exitosa</p>';
                $resultados['bd_conexion'] = true;
                
                // Test de tablas
                $tablas = ['tb_usuario', 'tb_planes_estrategicos', 'tb_analisis_pest', 'tb_matriz_came'];
                $tablas_ok = 0;
                
                foreach ($tablas as $tabla) {
                    $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
                    $stmt->execute([$tabla]);
                    if ($stmt->fetch()) {
                        $tablas_ok++;
                    }
                }
                
                echo "<p>Tablas verificadas: $tablas_ok/" . count($tablas) . "</p>";
                $resultados['bd_tablas'] = ($tablas_ok == count($tablas));
                
            } else {
                echo '<p class="error">❌ Error en conexión a BD</p>';
                $resultados['bd_conexion'] = false;
            }
        } catch (Exception $e) {
            echo '<p class="error">❌ Excepción en BD: ' . $e->getMessage() . '</p>';
            $resultados['bd_conexion'] = false;
        }
        echo '</div>';
        
        // ===== TEST 2: CONTROLADOR =====
        echo '<div class="test-step">';
        echo '<h2>2. ⚙️ Test del Controlador</h2>';
        
        try {
            require_once '../Controllers/PlanEstrategicoController.php';
            $controller = new PlanEstrategicoController();
            echo '<p class="success">✅ Controlador cargado correctamente</p>';
            $resultados['controlador'] = true;
            
            // Verificar métodos críticos
            $metodos = ['obtenerPlanesUsuario', 'obtenerDetallePlan', 'guardarPaso1'];
            $metodos_ok = 0;
            
            foreach ($metodos as $metodo) {
                if (method_exists($controller, $metodo)) {
                    $metodos_ok++;
                }
            }
            
            echo "<p>Métodos disponibles: $metodos_ok/" . count($metodos) . "</p>";
            $resultados['controlador_metodos'] = ($metodos_ok == count($metodos));
            
        } catch (Exception $e) {
            echo '<p class="error">❌ Error en controlador: ' . $e->getMessage() . '</p>';
            $resultados['controlador'] = false;
        }
        echo '</div>';
        
        // ===== TEST 3: GESTIÓN DE USUARIOS =====
        echo '<div class="test-step">';
        echo '<h2>3. 👤 Test de Gestión de Usuarios</h2>';
        
        try {
            // Verificar usuarios existentes
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tb_usuario");
            $stmt->execute();
            $total_usuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            echo "<p>Usuarios en sistema: $total_usuarios</p>";
            
            if ($total_usuarios == 0) {
                // Crear usuario de prueba
                $stmt = $pdo->prepare("
                    INSERT INTO tb_usuario (nombre_usuario, email_usuario, password_usuario, fecha_registro) 
                    VALUES (?, ?, ?, ?)
                ");
                $password_hash = password_hash('test123', PASSWORD_DEFAULT);
                $stmt->execute([
                    'Usuario Test',
                    'test@test.com',
                    $password_hash,
                    date('Y-m-d H:i:s')
                ]);
                echo '<p class="success">✅ Usuario de prueba creado</p>';
            }
            
            // Simular sesión de usuario
            if (!isset($_SESSION['user'])) {
                $stmt = $pdo->prepare("SELECT * FROM tb_usuario LIMIT 1");
                $stmt->execute();
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($usuario) {
                    $_SESSION['user'] = $usuario;
                    echo '<p class="success">✅ Sesión de prueba iniciada</p>';
                    $resultados['sesion'] = true;
                } else {
                    echo '<p class="error">❌ No se pudo crear sesión</p>';
                    $resultados['sesion'] = false;
                }
            } else {
                echo '<p class="success">✅ Sesión ya activa</p>';
                $resultados['sesion'] = true;
            }
            
        } catch (Exception $e) {
            echo '<p class="error">❌ Error en gestión de usuarios: ' . $e->getMessage() . '</p>';
            $resultados['sesion'] = false;
        }
        echo '</div>';
        
        // ===== TEST 4: OBTENER PLANES =====
        echo '<div class="test-step">';
        echo '<h2>4. 📋 Test de Obtención de Planes</h2>';
        
        if (isset($_SESSION['user']) && isset($controller)) {
            try {
                $user_id = $_SESSION['user']['id_usuario'];
                $planes = $controller->obtenerPlanesUsuario($user_id);
                
                if (is_array($planes)) {
                    echo '<p class="success">✅ Consulta de planes exitosa</p>';
                    echo "<p>Planes encontrados: " . count($planes) . "</p>";
                    $resultados['obtener_planes'] = true;
                    
                    if (count($planes) > 0) {
                        echo '<h4>Planes disponibles:</h4>';
                        foreach ($planes as $plan) {
                            echo "<p>• ID: {$plan['id']}, Empresa: {$plan['nombre_empresa']}</p>";
                        }
                    }
                } else {
                    echo '<p class="error">❌ Error en consulta de planes</p>';
                    $resultados['obtener_planes'] = false;
                }
                
            } catch (Exception $e) {
                echo '<p class="error">❌ Excepción en obtener planes: ' . $e->getMessage() . '</p>';
                $resultados['obtener_planes'] = false;
            }
        } else {
            echo '<p class="error">❌ No hay sesión o controlador disponible</p>';
            $resultados['obtener_planes'] = false;
        }
        echo '</div>';
        
        // ===== TEST 5: CREAR PLAN DE PRUEBA =====
        echo '<div class="test-step">';
        echo '<h2>5. ➕ Test de Creación de Plan</h2>';
        
        if (isset($_SESSION['user']) && isset($controller)) {
            try {
                // Verificar si ya existe un plan de prueba
                $user_id = $_SESSION['user']['id_usuario'];
                $planes_existentes = $controller->obtenerPlanesUsuario($user_id);
                
                if (count($planes_existentes) == 0) {
                    // Crear plan de prueba usando el flujo del wizard
                    $datos_plan = [
                        'nombre_empresa' => 'Empresa Test',
                        'mision' => 'Misión de prueba para validar el sistema',
                        'vision' => 'Visión de prueba para el futuro de la empresa',
                        'valores' => 'Valores corporativos de prueba'
                    ];
                      // Simular sesión temporal para crear plan
                    $_SESSION['plan_temporal'] = [
                        'id_usuario' => $user_id,
                        'fecha_inicio' => date('Y-m-d H:i:s'),
                        'paso_actual' => 1,
                        'datos' => [
                            'paso1' => $datos_plan
                        ]
                    ];
                    
                    // Simular guardado de plan completo directamente
                    $response = $controller->guardarPlanCompleto();
                    
                    if (isset($response['success']) && $response['success']) {
                        echo '<p class="success">✅ Plan de prueba creado exitosamente</p>';
                        $resultados['crear_plan'] = true;
                    } else {
                        echo '<p class="error">❌ Error al crear plan de prueba</p>';
                        $resultados['crear_plan'] = false;
                    }
                } else {
                    echo '<p class="info">ℹ️ Ya existen planes, no es necesario crear uno de prueba</p>';
                    $resultados['crear_plan'] = true;
                }
                
            } catch (Exception $e) {
                echo '<p class="error">❌ Excepción al crear plan: ' . $e->getMessage() . '</p>';
                $resultados['crear_plan'] = false;
            }
        } else {
            echo '<p class="error">❌ No hay sesión o controlador disponible</p>';
            $resultados['crear_plan'] = false;
        }
        echo '</div>';
        
        // ===== TEST 6: VISTAS DEL SISTEMA =====
        echo '<div class="test-step">';
        echo '<h2>6. 👁️ Test de Vistas del Sistema</h2>';
        
        $vistas = [
            'home.php' => 'Dashboard principal',
            'ver_plan.php' => 'Vista de plan completo',
            'resumen_plan.php' => 'Resumen ejecutivo'
        ];
        
        $vistas_ok = 0;
        foreach ($vistas as $archivo => $descripcion) {
            if (file_exists($archivo)) {
                echo "<p>✅ $descripcion ($archivo) - Disponible</p>";
                $vistas_ok++;
            } else {
                echo "<p>❌ $descripcion ($archivo) - No encontrado</p>";
            }
        }
        
        $resultados['vistas'] = ($vistas_ok == count($vistas));
        echo '</div>';
        
        // ===== RESUMEN FINAL =====
        echo '<div class="test-step">';
        echo '<h2>📊 Resumen de Tests</h2>';
        
        $tests_pasados = array_sum($resultados);
        $total_tests = count($resultados);
        $porcentaje = round(($tests_pasados / $total_tests) * 100);
        
        echo "<h3>Resultado: $tests_pasados/$total_tests tests pasados ($porcentaje%)</h3>";
        
        echo '<h4>Detalle de resultados:</h4>';
        foreach ($resultados as $test => $resultado) {
            $icono = $resultado ? '✅' : '❌';
            echo "<p>$icono $test</p>";
        }
        
        if ($porcentaje >= 90) {
            echo '<div class="success"><h4>🎉 SISTEMA FUNCIONANDO CORRECTAMENTE</h4>';
            echo '<p>Todos los componentes principales están operativos.</p></div>';
        } elseif ($porcentaje >= 70) {
            echo '<div class="info"><h4>⚠️ SISTEMA FUNCIONAL CON ADVERTENCIAS</h4>';
            echo '<p>El sistema funciona pero hay algunos componentes que requieren atención.</p></div>';
        } else {
            echo '<div class="error"><h4>❌ SISTEMA CON PROBLEMAS CRÍTICOS</h4>';
            echo '<p>Se detectaron problemas importantes que impiden el funcionamiento óptimo.</p></div>';
        }
        echo '</div>';
        
        // ===== NAVEGACIÓN =====
        echo '<div class="test-step info">';
        echo '<h2>🧭 Navegación del Sistema</h2>';
        echo '<p>Enlaces para probar las funcionalidades del sistema:</p>';
        
        if (isset($_SESSION['user'])) {
            echo '<a href="home.php" class="btn btn-primary">🏠 Dashboard</a>';
            echo '<a href="wizard_paso1.php" class="btn btn-success">➕ Nuevo Plan</a>';
            
            if (isset($planes) && count($planes) > 0) {
                $primer_plan = $planes[0];
                echo '<a href="ver_plan.php?id=' . $primer_plan['id'] . '" class="btn btn-info">👁️ Ver Plan</a>';
                echo '<a href="resumen_plan.php?id=' . $primer_plan['id'] . '" class="btn btn-warning">📄 Resumen</a>';
            }
        }
        
        echo '<a href="validacion_integral.php" class="btn btn-info">🔍 Validación Detallada</a>';
        echo '<a href="setup_usuarios.php" class="btn btn-warning">👥 Gestionar Usuarios</a>';
        echo '</div>';
        ?>
    </div>
</body>
</html>
