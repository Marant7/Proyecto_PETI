<?php
// Script de depuración completa del guardado
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir controlador
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h1>Debug: Guardado Completo del Plan Estratégico</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .info { color: blue; }
    .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
    .code { background: #f5f5f5; padding: 10px; font-family: monospace; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>";

// 1. Verificar estado de sesión
echo "<div class='section'>";
echo "<h2>1. Estado de la Sesión</h2>";
if (isset($_SESSION['plan_temporal'])) {
    echo "<p class='success'>✓ Sesión del plan encontrada</p>";
    
    // Mostrar módulos disponibles
    $modulos = ['nuevo_plan', 'vision_mision', 'valores', 'objetivos', 'cadena_valor', 'matriz_bcg', 'fuerzas_porter', 'pest', 'estrategias', 'matriz_came', 'resumen_ejecutivo'];
    echo "<h3>Módulos en sesión:</h3>";
    echo "<ul>";
    foreach ($modulos as $modulo) {
        if (isset($_SESSION['plan_temporal'][$modulo])) {
            echo "<li class='success'>✓ $modulo</li>";
        } else {
            echo "<li class='error'>✗ $modulo (FALTANTE)</li>";
        }
    }
    echo "</ul>";
    
    // Mostrar contenido de módulos críticos
    echo "<h3>Contenido de módulos críticos:</h3>";
    $modulos_criticos = ['pest', 'matriz_came', 'estrategias'];
    foreach ($modulos_criticos as $modulo) {
        echo "<h4>$modulo:</h4>";
        if (isset($_SESSION['plan_temporal'][$modulo])) {
            echo "<pre class='code'>" . print_r($_SESSION['plan_temporal'][$modulo], true) . "</pre>";
        } else {
            echo "<p class='error'>NO DISPONIBLE</p>";
        }
    }
} else {
    echo "<p class='error'>✗ No hay sesión del plan</p>";
}
echo "</div>";

// 2. Simular guardado y capturar errores
echo "<div class='section'>";
echo "<h2>2. Simulación de Guardado</h2>";

if (isset($_SESSION['plan_temporal'])) {
    try {
        $controller = new PlanEstrategicoController();
        
        // Activar logging de errores
        ini_set('log_errors', 1);
        ini_set('error_log', dirname(__FILE__) . '/debug_errors.log');
        
        echo "<p class='info'>Iniciando simulación de guardado...</p>";
        
        // Capturar salida de logs
        ob_start();
        
        // Intentar guardar el plan
        $id_plan = $controller->guardarPlanCompleto();
        
        $output = ob_get_clean();
        
        if ($id_plan) {
            echo "<p class='success'>✓ Plan guardado exitosamente con ID: $id_plan</p>";
        } else {
            echo "<p class='error'>✗ Error: No se devolvió ID de plan</p>";
        }
        
        if ($output) {
            echo "<h3>Salida del proceso:</h3>";
            echo "<pre class='code'>$output</pre>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Exception: " . $e->getMessage() . "</p>";
        echo "<pre class='code'>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "<p class='error'>No se puede simular guardado sin sesión</p>";
}
echo "</div>";

// 3. Verificar logs de errores
echo "<div class='section'>";
echo "<h2>3. Logs de Errores</h2>";

$log_file = dirname(__FILE__) . '/debug_errors.log';
if (file_exists($log_file)) {
    $logs = file_get_contents($log_file);
    if ($logs) {
        echo "<pre class='code'>$logs</pre>";
    } else {
        echo "<p class='info'>Archivo de logs vacío</p>";
    }
} else {
    echo "<p class='info'>No se encontró archivo de logs</p>";
}
echo "</div>";

// 4. Verificar estado de la base de datos después del intento
echo "<div class='section'>";
echo "<h2>4. Estado de la Base de Datos</h2>";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=plan_estrategico;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener el último plan creado
    $stmt = $pdo->prepare("SELECT * FROM tb_plan_estrategico ORDER BY id_plan DESC LIMIT 1");
    $stmt->execute();
    $ultimo_plan = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($ultimo_plan) {
        $id_plan = $ultimo_plan['id_plan'];
        echo "<p class='success'>✓ Último plan en BD: ID $id_plan</p>";
        echo "<pre class='code'>" . print_r($ultimo_plan, true) . "</pre>";
        
        // Verificar datos en cada tabla
        $tablas_verificar = [
            'tb_vision_mision' => 'vision, mision',
            'tb_valores' => 'COUNT(*) as total',
            'tb_analisis_pest' => 'factor_politico, factor_economico, factor_social, factor_tecnologico',
            'tb_matriz_came' => 'corregir, afrontar, mantener, explotar',
            'tb_sintesis_estrategias' => 'COUNT(*) as total',
            'tb_oportunidades' => 'COUNT(*) as total',
            'tb_amenazas' => 'COUNT(*) as total',
            'tb_fortalezas' => 'COUNT(*) as total',
            'tb_debilidades' => 'COUNT(*) as total'
        ];
        
        echo "<h3>Estado de tablas relacionadas:</h3>";
        echo "<table>";
        echo "<tr><th>Tabla</th><th>Estado</th><th>Datos</th></tr>";
        
        foreach ($tablas_verificar as $tabla => $campos) {
            try {
                $stmt = $pdo->prepare("SELECT $campos FROM $tabla WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $datos = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($datos) {
                    $tiene_datos = false;
                    foreach ($datos as $key => $value) {
                        if (!empty($value) && $value !== '0') {
                            $tiene_datos = true;
                            break;
                        }
                    }
                    
                    $estado = $tiene_datos ? "<span class='success'>✓ CON DATOS</span>" : "<span class='error'>✗ VACÍA</span>";
                    $datos_str = json_encode($datos);
                } else {
                    $estado = "<span class='error'>✗ SIN REGISTROS</span>";
                    $datos_str = "N/A";
                }
                
                echo "<tr><td>$tabla</td><td>$estado</td><td>$datos_str</td></tr>";
                
            } catch (Exception $e) {
                echo "<tr><td>$tabla</td><td><span class='error'>ERROR</span></td><td>" . $e->getMessage() . "</td></tr>";
            }
        }
        echo "</table>";
        
    } else {
        echo "<p class='error'>✗ No se encontraron planes en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Error de conexión a BD: " . $e->getMessage() . "</p>";
}
echo "</div>";

// 5. Test específico de funciones individuales
echo "<div class='section'>";
echo "<h2>5. Test de Funciones Individuales</h2>";

if (isset($_SESSION['plan_temporal']) && isset($ultimo_plan)) {
    try {
        $controller = new PlanEstrategicoController();
        $id_plan = $ultimo_plan['id_plan'];
        
        // Test función guardarPEST
        echo "<h3>Test guardarPEST:</h3>";
        if (isset($_SESSION['plan_temporal']['pest'])) {
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=plan_estrategico;charset=utf8", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Usar reflection para acceder al método privado
                $reflection = new ReflectionClass($controller);
                $method = $reflection->getMethod('guardarPEST');
                $method->setAccessible(true);
                $method->invoke($controller, $pdo, $id_plan, $_SESSION['plan_temporal']);
                
                echo "<p class='success'>✓ guardarPEST ejecutado sin errores</p>";
            } catch (Exception $e) {
                echo "<p class='error'>✗ Error en guardarPEST: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p class='error'>✗ No hay datos PEST en sesión</p>";
        }
        
        // Test función guardarMatrizCAME
        echo "<h3>Test guardarMatrizCAME:</h3>";
        if (isset($_SESSION['plan_temporal']['matriz_came'])) {
            try {
                $pdo = new PDO("mysql:host=localhost;dbname=plan_estrategico;charset=utf8", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $reflection = new ReflectionClass($controller);
                $method = $reflection->getMethod('guardarMatrizCAME');
                $method->setAccessible(true);
                $method->invoke($controller, $pdo, $id_plan, $_SESSION['plan_temporal']);
                
                echo "<p class='success'>✓ guardarMatrizCAME ejecutado sin errores</p>";
            } catch (Exception $e) {
                echo "<p class='error'>✗ Error en guardarMatrizCAME: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p class='error'>✗ No hay datos matriz_came en sesión</p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error en tests individuales: " . $e->getMessage() . "</p>";
    }
}
echo "</div>";

echo "<p><strong>Debug completado.</strong> Revisa los resultados arriba para identificar el problema.</p>";
?>
