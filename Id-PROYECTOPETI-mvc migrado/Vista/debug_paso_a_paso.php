<?php
// Script específico para depurar el guardado paso a paso
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/debug_detailed.log');

echo "<h1>Depuración Detallada del Guardado</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .info { color: blue; }
    .warning { color: orange; }
    .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; }
    .code { background: #f5f5f5; padding: 10px; font-family: monospace; white-space: pre-wrap; }
    .step { border-left: 4px solid #007cba; padding-left: 15px; margin: 10px 0; }
</style>";

// Generar datos de prueba si no existen
if (!isset($_SESSION['plan_temporal'])) {
    // Datos de prueba simplificados pero completos
    $_SESSION['user'] = ['id_usuario' => 1, 'id_empresa' => 1];
    $_SESSION['plan_temporal'] = [
        'id_usuario' => 1,
        'fecha_inicio' => date('Y-m-d H:i:s'),
        'paso_actual' => 11,
        'nuevo_plan' => [
            'nombre_plan' => 'Plan Test Debug',
            'empresa' => 'Empresa Debug',
            'descripcion' => 'Plan para debugging detallado'
        ],
        'vision_mision' => [
            'vision' => 'Ser una empresa líder en debugging',
            'mision' => 'Identificar y resolver errores de guardado'
        ],
        'valores' => [
            ['valor' => 'Precisión', 'descripcion' => 'Identificar problemas con exactitud'],
            ['valor' => 'Eficiencia', 'descripcion' => 'Resolver problemas rápidamente']
        ],
        'pest' => [
            'factor_politico' => 'Factor político de prueba para debugging',
            'factor_economico' => 'Factor económico de prueba para debugging',
            'factor_social' => 'Factor social de prueba para debugging',
            'factor_tecnologico' => 'Factor tecnológico de prueba para debugging',
            'oportunidades' => [
                'Oportunidad PEST 1',
                'Oportunidad PEST 2'
            ],
            'amenazas' => [
                'Amenaza PEST 1',
                'Amenaza PEST 2'
            ]
        ],
        'estrategias' => [
            'fortalezas' => ['Fortaleza 1', 'Fortaleza 2'],
            'debilidades' => ['Debilidad 1', 'Debilidad 2'],
            'oportunidades' => ['Oportunidad 1', 'Oportunidad 2'],
            'amenazas' => ['Amenaza 1', 'Amenaza 2']
        ],
        'matriz_came' => [
            'corregir_1' => 'Estrategia corregir 1',
            'corregir_2' => 'Estrategia corregir 2',
            'afrontar_1' => 'Estrategia afrontar 1',
            'afrontar_2' => 'Estrategia afrontar 2',
            'mantener_1' => 'Estrategia mantener 1',
            'mantener_2' => 'Estrategia mantener 2',
            'explotar_1' => 'Estrategia explotar 1',
            'explotar_2' => 'Estrategia explotar 2'
        ]
    ];
    echo "<div class='section info'>ℹ Datos de prueba generados automáticamente</div>";
}

// Incluir el controlador
require_once '../Controllers/PlanEstrategicoController.php';

echo "<div class='section'>";
echo "<h2>Inicio del Proceso de Depuración</h2>";
echo "<p>Probando el guardado paso a paso con logging detallado...</p>";
echo "</div>";

try {
    // Crear instancia del controlador
    $controller = new PlanEstrategicoController();
    
    // Crear conexión directa para testing
    $pdo = new PDO("mysql:host=localhost;dbname=plan_estrategico;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='section success'>✓ Conexión a base de datos establecida</div>";
    
    // PASO 1: Guardar plan principal
    echo "<div class='step'>";
    echo "<h3>PASO 1: Guardando Plan Principal</h3>";
    
    $stmt = $pdo->prepare("
        INSERT INTO tb_plan_estrategico 
        (id_usuario, nombre_plan, empresa, descripcion, estado) 
        VALUES (?, ?, ?, ?, 'completado')
    ");
    
    $nombre_plan = $_SESSION['plan_temporal']['nuevo_plan']['nombre_plan'];
    $empresa = $_SESSION['plan_temporal']['nuevo_plan']['empresa'];
    $descripcion = $_SESSION['plan_temporal']['nuevo_plan']['descripcion'];
    
    $result = $stmt->execute([1, $nombre_plan, $empresa, $descripcion]);
    $id_plan = $pdo->lastInsertId();
    
    if ($result && $id_plan) {
        echo "<p class='success'>✓ Plan principal guardado con ID: $id_plan</p>";
    } else {
        echo "<p class='error'>✗ Error al guardar plan principal</p>";
        throw new Exception("Error al guardar plan principal");
    }
    echo "</div>";
    
    // PASO 2: Guardar Visión/Misión
    echo "<div class='step'>";
    echo "<h3>PASO 2: Guardando Visión/Misión</h3>";
    
    $stmt = $pdo->prepare("
        INSERT INTO tb_vision_mision (id_plan, vision, mision) 
        VALUES (?, ?, ?)
    ");
    
    $vision = $_SESSION['plan_temporal']['vision_mision']['vision'];
    $mision = $_SESSION['plan_temporal']['vision_mision']['mision'];
    
    $result = $stmt->execute([$id_plan, $vision, $mision]);
    
    if ($result) {
        echo "<p class='success'>✓ Visión/Misión guardada</p>";
    } else {
        echo "<p class='error'>✗ Error al guardar Visión/Misión</p>";
    }
    echo "</div>";
    
    // PASO 3: Guardar Valores
    echo "<div class='step'>";
    echo "<h3>PASO 3: Guardando Valores</h3>";
    
    $stmt = $pdo->prepare("
        INSERT INTO tb_valores (id_plan, valor, descripcion, orden) 
        VALUES (?, ?, ?, ?)
    ");
    
    foreach ($_SESSION['plan_temporal']['valores'] as $index => $valor) {
        $result = $stmt->execute([
            $id_plan, 
            $valor['valor'], 
            $valor['descripcion'], 
            $index
        ]);
        
        if ($result) {
            echo "<p class='success'>✓ Valor '$valor[valor]' guardado</p>";
        } else {
            echo "<p class='error'>✗ Error al guardar valor '$valor[valor]'</p>";
        }
    }
    echo "</div>";
    
    // PASO 4: Guardar PEST (CRÍTICO)
    echo "<div class='step'>";
    echo "<h3>PASO 4: Guardando Análisis PEST (CRÍTICO)</h3>";
    
    $pest_data = $_SESSION['plan_temporal']['pest'];
    echo "<p class='info'>Datos PEST a guardar:</p>";
    echo "<div class='code'>" . print_r($pest_data, true) . "</div>";
    
    // Verificar si ya existe registro PEST para este plan
    $stmt = $pdo->prepare("SELECT id_pest FROM tb_analisis_pest WHERE id_plan = ?");
    $stmt->execute([$id_plan]);
    $existe_pest = $stmt->fetch();
    
    if ($existe_pest) {
        echo "<p class='warning'>⚠ Ya existe registro PEST para plan $id_plan, actualizando...</p>";
        
        $stmt = $pdo->prepare("
            UPDATE tb_analisis_pest 
            SET factor_politico = ?, factor_economico = ?, factor_social = ?, factor_tecnologico = ?
            WHERE id_plan = ?
        ");
        
        $result = $stmt->execute([
            $pest_data['factor_politico'],
            $pest_data['factor_economico'],
            $pest_data['factor_social'],
            $pest_data['factor_tecnologico'],
            $id_plan
        ]);
    } else {
        echo "<p class='info'>Creando nuevo registro PEST...</p>";
        
        $stmt = $pdo->prepare("
            INSERT INTO tb_analisis_pest 
            (id_plan, factor_politico, factor_economico, factor_social, factor_tecnologico) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $id_plan,
            $pest_data['factor_politico'],
            $pest_data['factor_economico'],
            $pest_data['factor_social'],
            $pest_data['factor_tecnologico']
        ]);
    }
    
    if ($result) {
        echo "<p class='success'>✓ Análisis PEST guardado correctamente</p>";
        
        // Verificar que se guardó
        $stmt = $pdo->prepare("SELECT * FROM tb_analisis_pest WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        $verificacion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($verificacion) {
            echo "<p class='success'>✓ Verificación: PEST encontrado en BD</p>";
            echo "<div class='code'>" . print_r($verificacion, true) . "</div>";
        } else {
            echo "<p class='error'>✗ Verificación: PEST NO encontrado en BD</p>";
        }
        
        // Guardar oportunidades y amenazas PEST
        if (isset($pest_data['oportunidades'])) {
            $stmt = $pdo->prepare("INSERT INTO tb_oportunidades (id_plan, descripcion, origen, orden) VALUES (?, ?, 'pest', ?)");
            foreach ($pest_data['oportunidades'] as $index => $oportunidad) {
                $stmt->execute([$id_plan, $oportunidad, $index]);
            }
            echo "<p class='success'>✓ Oportunidades PEST guardadas (" . count($pest_data['oportunidades']) . " items)</p>";
        }
        
        if (isset($pest_data['amenazas'])) {
            $stmt = $pdo->prepare("INSERT INTO tb_amenazas (id_plan, descripcion, origen, orden) VALUES (?, ?, 'pest', ?)");
            foreach ($pest_data['amenazas'] as $index => $amenaza) {
                $stmt->execute([$id_plan, $amenaza, $index]);
            }
            echo "<p class='success'>✓ Amenazas PEST guardadas (" . count($pest_data['amenazas']) . " items)</p>";
        }
        
    } else {
        echo "<p class='error'>✗ Error al guardar Análisis PEST</p>";
        $errorInfo = $stmt->errorInfo();
        echo "<div class='code'>Error SQL: " . print_r($errorInfo, true) . "</div>";
    }
    echo "</div>";
    
    // PASO 5: Guardar Matriz CAME (CRÍTICO)
    echo "<div class='step'>";
    echo "<h3>PASO 5: Guardando Matriz CAME (CRÍTICO)</h3>";
    
    $came_data = $_SESSION['plan_temporal']['matriz_came'];
    echo "<p class='info'>Datos CAME a guardar:</p>";
    echo "<div class='code'>" . print_r($came_data, true) . "</div>";
    
    // Procesar datos CAME
    $estrategias_corregir = [];
    $estrategias_afrontar = [];
    $estrategias_mantener = [];
    $estrategias_explotar = [];
    
    foreach ($came_data as $key => $value) {
        if (strpos($key, 'corregir_') === 0) {
            $estrategias_corregir[] = $value;
        } elseif (strpos($key, 'afrontar_') === 0) {
            $estrategias_afrontar[] = $value;
        } elseif (strpos($key, 'mantener_') === 0) {
            $estrategias_mantener[] = $value;
        } elseif (strpos($key, 'explotar_') === 0) {
            $estrategias_explotar[] = $value;
        }
    }
    
    $corregir_final = implode("\n\n", array_filter($estrategias_corregir));
    $afrontar_final = implode("\n\n", array_filter($estrategias_afrontar));
    $mantener_final = implode("\n\n", array_filter($estrategias_mantener));
    $explotar_final = implode("\n\n", array_filter($estrategias_explotar));
    
    echo "<p class='info'>Estrategias consolidadas:</p>";
    echo "<div class='code'>";
    echo "Corregir: " . strlen($corregir_final) . " caracteres\n";
    echo "Afrontar: " . strlen($afrontar_final) . " caracteres\n";
    echo "Mantener: " . strlen($mantener_final) . " caracteres\n";
    echo "Explotar: " . strlen($explotar_final) . " caracteres\n";
    echo "</div>";
    
    $stmt = $pdo->prepare("
        INSERT INTO tb_matriz_came (id_plan, corregir, afrontar, mantener, explotar) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        $id_plan,
        $corregir_final,
        $afrontar_final,
        $mantener_final,
        $explotar_final
    ]);
    
    if ($result) {
        echo "<p class='success'>✓ Matriz CAME guardada correctamente</p>";
        
        // Verificar que se guardó
        $stmt = $pdo->prepare("SELECT * FROM tb_matriz_came WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        $verificacion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($verificacion) {
            echo "<p class='success'>✓ Verificación: CAME encontrado en BD</p>";
            echo "<div class='code'>" . print_r($verificacion, true) . "</div>";
        } else {
            echo "<p class='error'>✗ Verificación: CAME NO encontrado en BD</p>";
        }
        
    } else {
        echo "<p class='error'>✗ Error al guardar Matriz CAME</p>";
        $errorInfo = $stmt->errorInfo();
        echo "<div class='code'>Error SQL: " . print_r($errorInfo, true) . "</div>";
    }
    echo "</div>";
    
    // PASO 6: Guardar Síntesis Estratégica (CRÍTICO)
    echo "<div class='step'>";
    echo "<h3>PASO 6: Guardando Síntesis Estratégica (CRÍTICO)</h3>";
    
    if (isset($_SESSION['plan_temporal']['estrategias'])) {
        $id_empresa = 1; // ID de empresa fijo para testing
        
        // Simular datos de evaluación (normalmente vendrían de la sesión)
        $totales_simulados = [
            'fo' => 8,
            'fa' => 6,
            'do' => 5,
            'da' => 4
        ];
        
        echo "<p class='info'>Guardando síntesis estratégica para empresa ID: $id_empresa</p>";
        echo "<div class='code'>Totales simulados: " . print_r($totales_simulados, true) . "</div>";
        
        $stmt = $pdo->prepare("
            INSERT INTO tb_sintesis_estrategias (id_empresa, relacion, tipologia_estrategia, puntuacion, descripcion) 
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            tipologia_estrategia = VALUES(tipologia_estrategia),
            puntuacion = VALUES(puntuacion),
            descripcion = VALUES(descripcion)
        ");
        
        $relaciones = [
            'FO' => ['Estrategia Ofensiva', $totales_simulados['fo'], 'Usar fortalezas para aprovechar oportunidades'],
            'AF' => ['Estrategia Defensiva', $totales_simulados['fa'], 'Usar fortalezas para defenderse de amenazas'],
            'OD' => ['Estrategia de Supervivencia', $totales_simulados['do'], 'Superar debilidades aprovechando oportunidades'],
            'AD' => ['Estrategia de Reorientación', $totales_simulados['da'], 'Reestructuración para afrontar debilidades y amenazas']
        ];
        
        foreach ($relaciones as $relacion => $datos_rel) {
            $result = $stmt->execute([
                $id_empresa,
                $relacion,
                $datos_rel[0],
                $datos_rel[1],
                $datos_rel[2]
            ]);
            
            if ($result) {
                echo "<p class='success'>✓ Relación $relacion guardada</p>";
            } else {
                echo "<p class='error'>✗ Error al guardar relación $relacion</p>";
                $errorInfo = $stmt->errorInfo();
                echo "<div class='code'>Error SQL: " . print_r($errorInfo, true) . "</div>";
            }
        }
        
        // Verificar que se guardó
        $stmt = $pdo->prepare("SELECT * FROM tb_sintesis_estrategias WHERE id_empresa = ?");
        $stmt->execute([$id_empresa]);
        $verificacion = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($verificacion) {
            echo "<p class='success'>✓ Verificación: Síntesis encontrada en BD (" . count($verificacion) . " registros)</p>";
            echo "<div class='code'>" . print_r($verificacion, true) . "</div>";
        } else {
            echo "<p class='error'>✗ Verificación: Síntesis NO encontrada en BD</p>";
        }
        
    } else {
        echo "<p class='error'>✗ No hay datos de estrategias en sesión</p>";
    }
    echo "</div>";
    
    echo "<div class='section success'>";
    echo "<h2>✓ PROCESO COMPLETADO</h2>";
    echo "<p>ID del plan guardado: <strong>$id_plan</strong></p>";
    echo "<p>Revisa los pasos anteriores para identificar cualquier problema.</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='section error'>";
    echo "<h2>✗ ERROR EN EL PROCESO</h2>";
    echo "<p>Exception: " . $e->getMessage() . "</p>";
    echo "<div class='code'>" . $e->getTraceAsString() . "</div>";
    echo "</div>";
}

// Mostrar logs si existen
$log_file = dirname(__FILE__) . '/debug_detailed.log';
if (file_exists($log_file)) {
    echo "<div class='section'>";
    echo "<h2>Logs del Sistema</h2>";
    $logs = file_get_contents($log_file);
    if ($logs) {
        echo "<div class='code'>$logs</div>";
    } else {
        echo "<p class='info'>No hay logs disponibles</p>";
    }
    echo "</div>";
}

?>
