<?php
// Evitar cualquier salida antes del JSON
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en pantalla

session_start();

// Ruta absoluta para evitar problemas
$config_path = dirname(__DIR__) . '/config/clsconexion.php';
if (file_exists($config_path)) {
    require_once $config_path;
} else {
    require_once '../config/clsconexion.php';
}

class PlanEstrategicoController {
    private $conexion;
    
    public function __construct($db = null) {
        try {
            if ($db) {
                $this->conexion = new stdClass();
                $this->conexion->conexion = $db;
            } else {
                $this->conexion = new clsConexion();
            }
        } catch (Exception $e) {
            error_log("Error en constructor PlanEstrategicoController: " . $e->getMessage());
            // Para AJAX, devolver error JSON
            if (isset($_POST['paso'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Error de configuración: ' . $e->getMessage()]);
                exit();
            }
        }
    }

    // ===============================================
    // GESTIÓN DE SESIÓN TEMPORAL
    // ===============================================
      public function iniciarPlan() {
        // Inicializar sesión del plan
        $_SESSION['plan_temporal'] = [
            'id_usuario' => $_SESSION['user']['id_usuario'] ?? 1,
            'fecha_inicio' => date('Y-m-d H:i:s'),
            'paso_actual' => 1,
            'datos' => []
        ];
        
        // Redirigir al primer paso
        header('Location: Vista/nuevo_plan.php');
        exit();
    }
    
    public function guardarPaso($id = null) {
        // Limpiar cualquier salida previa
        if (ob_get_level()) {
            ob_clean();
        }
        
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        
        try {
            // Log para depuración
            error_log("PlanEstrategicoController::guardarPaso - Método: " . ($_SERVER['REQUEST_METHOD'] ?? 'undefined'));
            error_log("PlanEstrategicoController::guardarPaso - POST data: " . print_r($_POST, true));
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido: " . ($_SERVER['REQUEST_METHOD'] ?? 'undefined'));
            }
            
            $paso = $_POST['paso'] ?? null;
            $nombre_paso = $_POST['nombre_paso'] ?? null;
            
            error_log("PlanEstrategicoController::guardarPaso - paso: $paso, nombre_paso: $nombre_paso");
            
            if (!$paso || !$nombre_paso) {
                throw new Exception("Datos de paso no válidos: paso=" . ($paso ?? 'null') . ", nombre_paso=" . ($nombre_paso ?? 'null'));
            }
            
            // Verificar que la sesión esté funcionando
            if (!isset($_SESSION)) {
                throw new Exception("Sesión no iniciada");
            }
            
            // Procesar datos según el paso
            $datos_paso = $this->procesarDatosPaso($nombre_paso, $_POST);
            error_log("PlanEstrategicoController::guardarPaso - datos procesados: " . print_r($datos_paso, true));
            
            // Guardar en sesión
            $resultado = $this->guardarDatosPaso($paso, $nombre_paso, $datos_paso);
            error_log("PlanEstrategicoController::guardarPaso - resultado guardar: " . ($resultado ? 'true' : 'false'));
            
            if ($resultado) {
                $response = ['success' => true, 'message' => 'Datos guardados correctamente en sesión'];
            } else {
                $response = ['success' => false, 'message' => 'Error al guardar en sesión'];
            }
            
            error_log("PlanEstrategicoController::guardarPaso - respuesta: " . json_encode($response));
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log("PlanEstrategicoController::guardarPaso - Exception: " . $e->getMessage());
            $response = ['success' => false, 'message' => $e->getMessage()];
            echo json_encode($response);
        } catch (Error $e) {
            error_log("PlanEstrategicoController::guardarPaso - Error: " . $e->getMessage());
            $response = ['success' => false, 'message' => 'Error del sistema: ' . $e->getMessage()];
            echo json_encode($response);
        }
        
        // Asegurar que se termine la ejecución aquí
        exit();
    }
    
    private function procesarDatosPaso($nombre_paso, $post_data) {
        switch ($nombre_paso) {
            case 'nuevo_plan':
                return [
                    'nombre_plan' => $post_data['nombre_plan'] ?? '',
                    'empresa' => $post_data['empresa'] ?? '',
                    'descripcion' => $post_data['descripcion'] ?? ''
                ];
                
            case 'vision_mision':
                return [
                    'vision' => $post_data['vision'] ?? '',
                    'mision' => $post_data['mision'] ?? ''
                ];
                
            case 'valores':
                return $post_data['valores'] ?? [];
                  case 'objetivos':
                return [
                    'uen_descripcion' => $post_data['uen_descripcion'] ?? '',
                    'objetivos_generales' => $post_data['objetivos_generales'] ?? [],
                    'objetivos_especificos' => $post_data['objetivos_especificos'] ?? []
                ];
                  case 'cadena_valor':
                return [
                    'q1' => $post_data['q1'] ?? 0,
                    'q2' => $post_data['q2'] ?? 0,
                    'q3' => $post_data['q3'] ?? 0,
                    'q4' => $post_data['q4'] ?? 0,
                    'q5' => $post_data['q5'] ?? 0,
                    'q6' => $post_data['q6'] ?? 0,
                    'q7' => $post_data['q7'] ?? 0,
                    'q8' => $post_data['q8'] ?? 0,
                    'q9' => $post_data['q9'] ?? 0,
                    'q10' => $post_data['q10'] ?? 0,
                    'fortalezas' => $post_data['fortalezas'] ?? [],
                    'debilidades' => $post_data['debilidades'] ?? [],
                    'porcentaje' => $post_data['porcentaje'] ?? 0
                ];
                
            case 'matriz_bcg':
                return [
                    'num_productos' => $post_data['numProductos'] ?? 5,
                    'num_competidores' => $post_data['numCompetidores'] ?? 5,
                    'anio_inicio' => $post_data['anioInicio'] ?? 2020,
                    'anio_fin' => $post_data['anioFin'] ?? 2025,
                    'productos' => $post_data['productos'] ?? [],
                    'ventas' => $post_data['ventas'] ?? [],
                    'fortalezas' => $post_data['fortalezas'] ?? [],
                    'debilidades' => $post_data['debilidades'] ?? [],
                    // Agregar otros datos de BCG que se capturen
                ];
                  case 'fuerzas_porter':
                return [
                    'poder_negociacion_proveedores' => $post_data['poder_negociacion_proveedores'] ?? '',
                    'poder_negociacion_compradores' => $post_data['poder_negociacion_compradores'] ?? '',
                    'amenaza_productos_sustitutos' => $post_data['amenaza_productos_sustitutos'] ?? '',
                    'amenaza_nuevos_competidores' => $post_data['amenaza_nuevos_competidores'] ?? '',
                    'rivalidad_competidores' => $post_data['rivalidad_competidores'] ?? '',
                    'oportunidades' => $post_data['oportunidades'] ?? [],
                    'amenazas' => $post_data['amenazas'] ?? [],
                    // Capturar todas las respuestas de radio buttons
                    'respuestas' => array_filter($post_data, function($key) {
                        return strpos($key, 'factor_') === 0;
                    }, ARRAY_FILTER_USE_KEY)
                ];                  case 'pest':
                // Debug: ver qué datos llegan
                error_log("PlanEstrategicoController::procesarDatosPaso - PEST datos recibidos: " . json_encode($post_data));
                
                $oportunidades = $post_data['oportunidades'] ?? [];
                $amenazas = $post_data['amenazas'] ?? [];
                
                // Asegurar que las oportunidades y amenazas son arrays
                if (!is_array($oportunidades)) {
                    $oportunidades = [];
                }
                if (!is_array($amenazas)) {
                    $amenazas = [];
                }
                
                error_log("PlanEstrategicoController::procesarDatosPaso - PEST oportunidades: " . json_encode($oportunidades));
                error_log("PlanEstrategicoController::procesarDatosPaso - PEST amenazas: " . json_encode($amenazas));
                
                return [
                    'factor_politico' => $post_data['factor_politico'] ?? '',
                    'factor_economico' => $post_data['factor_economico'] ?? '',
                    'factor_social' => $post_data['factor_social'] ?? '',
                    'factor_tecnologico' => $post_data['factor_tecnologico'] ?? '',
                    'oportunidades' => $oportunidades,
                    'amenazas' => $amenazas,
                    // Capturar todas las respuestas PEST
                    'respuestas' => array_filter($post_data, function($key) {
                        return strpos($key, 'pest_') === 0 || strpos($key, 'politico_') === 0 || 
                               strpos($key, 'economico_') === 0 || strpos($key, 'social_') === 0 || 
                               strpos($key, 'tecnologico_') === 0;
                    }, ARRAY_FILTER_USE_KEY)
                ];case 'estrategias':
                // Procesar datos FODA con formato especial (fortaleza_1, debilidad_1, etc.)
                $fortalezas = [];
                $debilidades = [];
                $oportunidades = [];
                $amenazas = [];
                
                // Extraer fortalezas (fortaleza_1, fortaleza_2, etc.)
                foreach ($post_data as $key => $value) {
                    if (strpos($key, 'fortaleza_') === 0 && !empty(trim($value))) {
                        $fortalezas[] = trim($value);
                    }
                    if (strpos($key, 'debilidad_') === 0 && !empty(trim($value))) {
                        $debilidades[] = trim($value);
                    }
                    if (strpos($key, 'oportunidad_') === 0 && !empty(trim($value))) {
                        $oportunidades[] = trim($value);
                    }
                    if (strpos($key, 'amenaza_') === 0 && !empty(trim($value))) {
                        $amenazas[] = trim($value);
                    }
                }
                
                return [
                    'estrategias' => $post_data['estrategias'] ?? [],
                    'fortalezas' => $fortalezas,
                    'debilidades' => $debilidades,
                    'oportunidades' => $oportunidades,
                    'amenazas' => $amenazas,
                    // Capturar estrategias específicas si existen
                    'fo_estrategias' => $post_data['fo_estrategias'] ?? [],
                    'fa_estrategias' => $post_data['fa_estrategias'] ?? [],
                    'do_estrategias' => $post_data['do_estrategias'] ?? [],
                    'da_estrategias' => $post_data['da_estrategias'] ?? []
                ];
                
            case 'matriz_came':
                $came_datos = [];
                
                // Procesar estrategias individuales para cada elemento FODA
                foreach ($post_data as $key => $value) {
                    if (strpos($key, 'corregir_') === 0 || 
                        strpos($key, 'afrontar_') === 0 || 
                        strpos($key, 'mantener_') === 0 || 
                        strpos($key, 'explotar_') === 0) {
                        $came_datos[$key] = $value;
                    }
                }
                
                // También mantener compatibilidad con formato anterior si se necesita
                if (isset($post_data['corregir'])) $came_datos['corregir'] = $post_data['corregir'];
                if (isset($post_data['afrontar'])) $came_datos['afrontar'] = $post_data['afrontar'];
                if (isset($post_data['mantener'])) $came_datos['mantener'] = $post_data['mantener'];
                if (isset($post_data['explotar'])) $came_datos['explotar'] = $post_data['explotar'];
                
                return $came_datos;
                
            case 'resumen_ejecutivo':
                return [
                    'emprendedores_promotores' => $post_data['emprendedores_promotores'] ?? '',
                    'identificacion_estrategica' => $post_data['identificacion_estrategica'] ?? '',
                    'conclusiones' => $post_data['conclusiones'] ?? ''
                ];
                
            default:
                return $post_data;
        }
    }
    
    public function finalizarPlan($id = null) {
        // Limpiar cualquier salida previa
        if (ob_get_level()) {
            ob_clean();
        }
        
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        
        try {
            error_log("PlanEstrategicoController::finalizarPlan - Iniciando finalización del plan");
            
            // Verificar que hay sesión temporal
            if (!isset($_SESSION['plan_temporal'])) {
                throw new Exception("No hay un plan temporal para guardar");
            }
            
            error_log("PlanEstrategicoController::finalizarPlan - Plan temporal encontrado");
            error_log("PlanEstrategicoController::finalizarPlan - Datos plan temporal: " . print_r($_SESSION['plan_temporal'], true));
            
            $id_plan = $this->guardarPlanCompleto();
            error_log("PlanEstrategicoController::finalizarPlan - Plan guardado con ID: " . $id_plan);
            
            $response = [
                'success' => true, 
                'message' => 'Plan estratégico guardado exitosamente',
                'id_plan' => $id_plan
            ];
            
            error_log("PlanEstrategicoController::finalizarPlan - Respuesta exitosa: " . json_encode($response));
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log("PlanEstrategicoController::finalizarPlan - Exception: " . $e->getMessage());
            error_log("PlanEstrategicoController::finalizarPlan - Exception trace: " . $e->getTraceAsString());
            $response = ['success' => false, 'message' => $e->getMessage()];
            echo json_encode($response);
        } catch (Error $e) {
            error_log("PlanEstrategicoController::finalizarPlan - Error: " . $e->getMessage());
            error_log("PlanEstrategicoController::finalizarPlan - Error trace: " . $e->getTraceAsString());
            $response = ['success' => false, 'message' => 'Error del sistema: ' . $e->getMessage()];
            echo json_encode($response);
        }
        
        exit();
    }      public function guardarDatosPaso($paso, $nombre_paso, $datos) {
        if (!isset($_SESSION['plan_temporal'])) {
            $_SESSION['plan_temporal'] = [
                'id_usuario' => $_SESSION['user']['id_usuario'] ?? 1,
                'fecha_inicio' => date('Y-m-d H:i:s'),
                'paso_actual' => 1,
                'datos' => []
            ];
        }
        
        $_SESSION['plan_temporal'][$nombre_paso] = $datos;
        
        // Mapear nombres de paso a números para el contador
        $pasos_numericos = [
            'nuevo_plan' => 1,
            'vision_mision' => 2,
            'valores' => 3,
            'objetivos' => 4,
            'cadena_valor' => 5,
            'matriz_bcg' => 6,
            'fuerzas_porter' => 7,
            'pest' => 8,
            'estrategias' => 9,
            'matriz_came' => 10,
            'resumen_ejecutivo' => 11
        ];
        
        $paso_numerico = $pasos_numericos[$paso] ?? (is_numeric($paso) ? intval($paso) : 1);
        $_SESSION['plan_temporal']['paso_actual'] = $paso_numerico + 1;
        
        return true;
    }
    
    public function obtenerDatosPaso($paso) {
        return $_SESSION['plan_temporal']['datos'][$paso] ?? [];
    }
      // ===============================================
    // GUARDADO FINAL EN BASE DE DATOS
    // ===============================================
    
    public function guardarPlanCompleto() {
        error_log("PlanEstrategicoController::guardarPlanCompleto - Iniciando guardado completo");
        
        if (!isset($_SESSION['plan_temporal'])) {
            throw new Exception("No hay un plan temporal para guardar");
        }
          try {
            error_log("PlanEstrategicoController::guardarPlanCompleto - Obteniendo conexión");
            $pdo = $this->getConexion();
            
            if (!$pdo) {
                throw new Exception("No se pudo obtener la conexión a la base de datos");
            }
            
            error_log("PlanEstrategicoController::guardarPlanCompleto - Iniciando transacción");
            $transaction_started = false;
            
            try {
                $pdo->beginTransaction();
                $transaction_started = true;
                error_log("PlanEstrategicoController::guardarPlanCompleto - Transacción iniciada correctamente");
            } catch (Exception $trans_e) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Error al iniciar transacción: " . $trans_e->getMessage());
                throw new Exception("Error al iniciar transacción: " . $trans_e->getMessage());
            }
              // Los datos están directamente en plan_temporal con nombres de pasos
            $plan_temporal = $_SESSION['plan_temporal'];
            $id_usuario = $plan_temporal['id_usuario'] ?? $_SESSION['user']['id_usuario'];
            
            error_log("PlanEstrategicoController::guardarPlanCompleto - ID Usuario: " . $id_usuario);
            error_log("PlanEstrategicoController::guardarPlanCompleto - Datos completos de sesión: " . print_r($plan_temporal, true));
            
            // Verificar qué módulos están disponibles
            $modulos_disponibles = [];
            foreach (['vision_mision', 'valores', 'objetivos', 'cadena_valor', 'matriz_bcg', 'fuerzas_porter', 'pest', 'estrategias', 'matriz_came', 'resumen_ejecutivo'] as $modulo) {
                if (isset($plan_temporal[$modulo])) {
                    $modulos_disponibles[] = $modulo;
                }
            }
            error_log("PlanEstrategicoController::guardarPlanCompleto - Módulos disponibles: " . implode(', ', $modulos_disponibles));
            
            // 1. CREAR PLAN PRINCIPAL
            error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando plan principal");
            $id_plan = $this->guardarPlanPrincipal($pdo, $id_usuario, $plan_temporal);
            error_log("PlanEstrategicoController::guardarPlanCompleto - Plan principal guardado con ID: " . $id_plan);
            
            // 2. GUARDAR CADA MÓDULO
            error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando módulos individuales");
            
            if (isset($plan_temporal['vision_mision'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando visión/misión");
                $this->guardarVisionMision($pdo, $id_plan, $plan_temporal);
            }
            
            if (isset($plan_temporal['valores'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando valores");
                $this->guardarValores($pdo, $id_plan, $plan_temporal);
            }
            
            if (isset($plan_temporal['objetivos'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando objetivos");
                $this->guardarObjetivos($pdo, $id_plan, $plan_temporal);
            }
            
            if (isset($plan_temporal['cadena_valor'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando cadena de valor");
                $this->guardarCadenaValor($pdo, $id_plan, $plan_temporal);
            }
            
            if (isset($plan_temporal['matriz_bcg'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando matriz BCG");
                $this->guardarMatrizBCG($pdo, $id_plan, $plan_temporal);
            }
            
            if (isset($plan_temporal['fuerzas_porter'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando fuerzas porter");
                $this->guardarFuerzasPorter($pdo, $id_plan, $plan_temporal);
            }
            
            if (isset($plan_temporal['pest'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando PEST");
                $this->guardarPEST($pdo, $id_plan, $plan_temporal);
            }
            
            if (isset($plan_temporal['estrategias'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando estrategias");
                $this->guardarEstrategias($pdo, $id_plan, $plan_temporal);
            }
              if (isset($plan_temporal['matriz_came'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando matriz CAME");
                $this->guardarMatrizCAME($pdo, $id_plan, $plan_temporal);
            }
            
            if (isset($plan_temporal['resumen_ejecutivo'])) {
                error_log("PlanEstrategicoController::guardarPlanCompleto - Guardando resumen ejecutivo");
                $this->guardarResumenEjecutivo($pdo, $id_plan, $plan_temporal);
            }
              error_log("PlanEstrategicoController::guardarPlanCompleto - Confirmando transacción");
            if ($transaction_started) {
                $pdo->commit();
                error_log("PlanEstrategicoController::guardarPlanCompleto - Transacción confirmada exitosamente");
            }
              // Limpiar sesión temporal (comentado para debug)
            error_log("PlanEstrategicoController::guardarPlanCompleto - NO limpiando sesión temporal para debug");
            // unset($_SESSION['plan_temporal']);
            
            error_log("PlanEstrategicoController::guardarPlanCompleto - Guardado completo exitoso con ID: " . $id_plan);
            return $id_plan;
            
        } catch (Exception $e) {
            error_log("PlanEstrategicoController::guardarPlanCompleto - Exception: " . $e->getMessage());
            if (isset($pdo) && isset($transaction_started) && $transaction_started) {
                try {
                    error_log("PlanEstrategicoController::guardarPlanCompleto - Haciendo rollback");
                    $pdo->rollBack();
                    error_log("PlanEstrategicoController::guardarPlanCompleto - Rollback exitoso");
                } catch (Exception $rollback_e) {
                    error_log("PlanEstrategicoController::guardarPlanCompleto - Error en rollback: " . $rollback_e->getMessage());
                }
            }
            throw $e;
        }
    }
    
    // ===============================================
    // MÉTODOS DE GUARDADO ESPECÍFICOS
    // ===============================================
    
    private function guardarPlanPrincipal($pdo, $id_usuario, $datos) {
        $stmt = $pdo->prepare("
            INSERT INTO tb_plan_estrategico 
            (id_usuario, nombre_plan, empresa, descripcion, estado) 
            VALUES (?, ?, ?, ?, 'completado')
        ");
        
        $nombre_plan = $datos['nuevo_plan']['nombre_plan'] ?? 'Plan Estratégico';
        $empresa = $datos['nuevo_plan']['empresa'] ?? 'Mi Empresa';
        $descripcion = $datos['nuevo_plan']['descripcion'] ?? '';
        
        $stmt->execute([$id_usuario, $nombre_plan, $empresa, $descripcion]);
        return $pdo->lastInsertId();
    }
    
    private function guardarVisionMision($pdo, $id_plan, $datos) {
        if (!isset($datos['vision_mision'])) return;
        
        $stmt = $pdo->prepare("
            INSERT INTO tb_vision_mision (id_plan, vision, mision) 
            VALUES (?, ?, ?)
        ");
        
        $vision = $datos['vision_mision']['vision'] ?? '';
        $mision = $datos['vision_mision']['mision'] ?? '';
        
        $stmt->execute([$id_plan, $vision, $mision]);
    }
    
    private function guardarValores($pdo, $id_plan, $datos) {
        if (!isset($datos['valores'])) return;
        
        $stmt = $pdo->prepare("
            INSERT INTO tb_valores (id_plan, valor, descripcion, orden) 
            VALUES (?, ?, ?, ?)
        ");
          foreach ($datos['valores'] as $index => $valor) {
            // Verificar si $valor es un string simple o un array
            if (is_string($valor)) {
                // Caso: valores enviados como array simple desde el formulario
                $stmt->execute([
                    $id_plan, 
                    $valor, 
                    '', // descripción vacía por defecto
                    $index
                ]);
            } else {
                // Caso: valores enviados como array de objetos
                $stmt->execute([
                    $id_plan, 
                    $valor['valor'] ?? '', 
                    $valor['descripcion'] ?? '', 
                    $index
                ]);
            }
        }}    private function guardarObjetivos($pdo, $id_plan, $datos) {
        if (!isset($datos['objetivos'])) return;
        
        $objetivos_data = $datos['objetivos'];
        
        // Guardar la descripción de la Unidad Estratégica
        if (!empty($objetivos_data['uen_descripcion'])) {
            // Crear tabla tb_uen si no existe
            $create_table_sql = "
                CREATE TABLE IF NOT EXISTS tb_uen (
                    id_uen int(11) NOT NULL AUTO_INCREMENT,
                    id_plan int(11) NOT NULL,
                    uen_descripcion text NOT NULL,
                    fecha_creacion timestamp DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id_uen),
                    KEY fk_uen_plan (id_plan),
                    CONSTRAINT fk_uen_plan FOREIGN KEY (id_plan) REFERENCES tb_plan_estrategico (id_plan) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ";
            $pdo->exec($create_table_sql);
            
            // Insertar la unidad estratégica
            $stmt = $pdo->prepare("
                INSERT INTO tb_uen (id_plan, uen_descripcion) 
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE uen_descripcion = VALUES(uen_descripcion)
            ");
            $stmt->execute([$id_plan, trim($objetivos_data['uen_descripcion'])]);
        }
        
        // Guardar objetivos generales
        if (!empty($objetivos_data['objetivos_generales'])) {
            $stmt = $pdo->prepare("
                INSERT INTO tb_objetivos_estrategicos (id_plan, objetivo, categoria, prioridad, orden) 
                VALUES (?, ?, 'general', 'alta', ?)
            ");
            
            foreach ($objetivos_data['objetivos_generales'] as $index => $objetivo) {
                if (!empty(trim($objetivo))) {
                    $stmt->execute([$id_plan, trim($objetivo), $index]);
                }
            }
        }
        
        // Guardar objetivos específicos
        if (!empty($objetivos_data['objetivos_especificos'])) {
            $stmt = $pdo->prepare("
                INSERT INTO tb_objetivos_estrategicos (id_plan, objetivo, categoria, prioridad, orden) 
                VALUES (?, ?, 'especifico', 'media', ?)
            ");
            
            $orden = 0;
            foreach ($objetivos_data['objetivos_especificos'] as $general_id => $especificos) {
                if (is_array($especificos)) {
                    foreach ($especificos as $especifico) {
                        if (!empty(trim($especifico))) {
                            $stmt->execute([$id_plan, trim($especifico), $orden]);
                            $orden++;
                        }
                    }
                }
            }
        }
    }
    
    private function guardarCadenaValor($pdo, $id_plan, $datos) {
        if (!isset($datos['cadena_valor'])) return;
        
        $stmt = $pdo->prepare("
            INSERT INTO tb_cadena_valor 
            (id_plan, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $cv = $datos['cadena_valor'];
        $stmt->execute([
            $id_plan,
            $cv['q1'] ?? 0, $cv['q2'] ?? 0, $cv['q3'] ?? 0, $cv['q4'] ?? 0, $cv['q5'] ?? 0,
            $cv['q6'] ?? 0, $cv['q7'] ?? 0, $cv['q8'] ?? 0, $cv['q9'] ?? 0, $cv['q10'] ?? 0
        ]);
    }
    
    private function guardarMatrizBCG($pdo, $id_plan, $datos) {
        if (!isset($datos['matriz_bcg'])) return;
        
        $bcg = $datos['matriz_bcg'];
        
        // Configuración
        $stmt = $pdo->prepare("
            INSERT INTO tb_matriz_bcg_config 
            (id_plan, num_productos, num_competidores, anio_inicio, anio_fin) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $id_plan,
            $bcg['num_productos'] ?? 5,
            $bcg['num_competidores'] ?? 5,
            $bcg['anio_inicio'] ?? 2020,
            $bcg['anio_fin'] ?? 2025
        ]);
        
        // Productos
        if (isset($bcg['productos'])) {
            $stmt = $pdo->prepare("INSERT INTO tb_matriz_bcg_productos (id_plan, nombre_producto, orden) VALUES (?, ?, ?)");
            foreach ($bcg['productos'] as $index => $producto) {
                $stmt->execute([$id_plan, $producto, $index]);
                $id_producto = $pdo->lastInsertId();
                
                // Ventas
                if (isset($bcg['ventas'][$index])) {
                    $this->guardarVentasProducto($pdo, $id_plan, $id_producto, $bcg['ventas'][$index], $bcg);
                }
                
                // TCM
                if (isset($bcg['tcm'][$index])) {
                    $this->guardarTCMProducto($pdo, $id_plan, $id_producto, $bcg['tcm'][$index]);
                }
                
                // Demanda Global
                if (isset($bcg['demanda_global'][$index])) {
                    $this->guardarDemandaProducto($pdo, $id_plan, $id_producto, $bcg['demanda_global'][$index], $bcg);
                }
                
                // Competidores
                if (isset($bcg['competidores'][$index])) {
                    $this->guardarCompetidoresProducto($pdo, $id_plan, $id_producto, $bcg['competidores'][$index]);
                }
            }
        }
        
        // Fortalezas y Debilidades
        $this->guardarFortalezasDebilidades($pdo, $id_plan, $bcg);
    }
    
    private function guardarVentasProducto($pdo, $id_plan, $id_producto, $ventas, $config) {
        $stmt = $pdo->prepare("INSERT INTO tb_matriz_bcg_ventas (id_plan, id_producto, anio, valor) VALUES (?, ?, ?, ?)");
        $anio_inicio = $config['anio_inicio'] ?? 2020;
        $anio_fin = $config['anio_fin'] ?? 2025;
        
        $index = 0;
        for ($anio = $anio_inicio; $anio <= $anio_fin; $anio++) {
            $valor = $ventas[$index] ?? 0;
            $stmt->execute([$id_plan, $id_producto, $anio, $valor]);
            $index++;
        }
    }
    
    private function guardarTCMProducto($pdo, $id_plan, $id_producto, $tcm_data) {
        $stmt = $pdo->prepare("INSERT INTO tb_matriz_bcg_tcm (id_plan, id_producto, periodo, valor) VALUES (?, ?, ?, ?)");
        foreach ($tcm_data as $periodo => $valor) {
            $stmt->execute([$id_plan, $id_producto, $periodo, $valor]);
        }
    }
    
    private function guardarDemandaProducto($pdo, $id_plan, $id_producto, $demanda, $config) {
        $stmt = $pdo->prepare("INSERT INTO tb_matriz_bcg_demanda_global (id_plan, id_producto, anio, valor) VALUES (?, ?, ?, ?)");
        $anio_inicio = $config['anio_inicio'] ?? 2020;
        $anio_fin = $config['anio_fin'] ?? 2025;
        
        $index = 0;
        for ($anio = $anio_inicio; $anio <= $anio_fin; $anio++) {
            $valor = $demanda[$index] ?? 0;
            $stmt->execute([$id_plan, $id_producto, $anio, $valor]);
            $index++;
        }
    }
    
    private function guardarCompetidoresProducto($pdo, $id_plan, $id_producto, $competidores) {
        $stmt = $pdo->prepare("INSERT INTO tb_matriz_bcg_competidores (id_plan, id_producto, competidor, valor) VALUES (?, ?, ?, ?)");
        foreach ($competidores as $competidor => $valor) {
            $stmt->execute([$id_plan, $id_producto, $competidor, $valor]);
        }
    }
    
    private function guardarFortalezasDebilidades($pdo, $id_plan, $datos) {
        // Fortalezas
        if (isset($datos['fortalezas'])) {
            $stmt = $pdo->prepare("INSERT INTO tb_fortalezas (id_plan, descripcion, orden) VALUES (?, ?, ?)");
            foreach ($datos['fortalezas'] as $index => $fortaleza) {
                $stmt->execute([$id_plan, $fortaleza, $index]);
            }
        }
        
        // Debilidades
        if (isset($datos['debilidades'])) {
            $stmt = $pdo->prepare("INSERT INTO tb_debilidades (id_plan, descripcion, orden) VALUES (?, ?, ?)");
            foreach ($datos['debilidades'] as $index => $debilidad) {
                $stmt->execute([$id_plan, $debilidad, $index]);
            }
        }
    }
    
    private function guardarFuerzasPorter($pdo, $id_plan, $datos) {
        if (!isset($datos['fuerzas_porter'])) return;
        
        $fp = $datos['fuerzas_porter'];
        $stmt = $pdo->prepare("
            INSERT INTO tb_fuerzas_porter 
            (id_plan, poder_negociacion_proveedores, poder_negociacion_compradores, 
             amenaza_productos_sustitutos, amenaza_nuevos_competidores, rivalidad_competidores) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $id_plan,
            $fp['poder_negociacion_proveedores'] ?? '',
            $fp['poder_negociacion_compradores'] ?? '',
            $fp['amenaza_productos_sustitutos'] ?? '',
            $fp['amenaza_nuevos_competidores'] ?? '',
            $fp['rivalidad_competidores'] ?? ''
        ]);
    }
    
    private function guardarPEST($pdo, $id_plan, $datos) {
        if (!isset($datos['pest'])) return;
        
        $pest = $datos['pest'];
        $stmt = $pdo->prepare("
            INSERT INTO tb_analisis_pest 
            (id_plan, factor_politico, factor_economico, factor_social, factor_tecnologico) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $id_plan,
            $pest['factor_politico'] ?? '',
            $pest['factor_economico'] ?? '',
            $pest['factor_social'] ?? '',
            $pest['factor_tecnologico'] ?? ''
        ]);
        
        // Guardar oportunidades y amenazas derivadas de PEST
        if (isset($pest['oportunidades'])) {
            $stmt = $pdo->prepare("INSERT INTO tb_oportunidades (id_plan, descripcion, origen, orden) VALUES (?, ?, 'pest', ?)");
            foreach ($pest['oportunidades'] as $index => $oportunidad) {
                $stmt->execute([$id_plan, $oportunidad, $index]);
            }
        }
        
        if (isset($pest['amenazas'])) {
            $stmt = $pdo->prepare("INSERT INTO tb_amenazas (id_plan, descripcion, origen, orden) VALUES (?, ?, 'pest', ?)");
            foreach ($pest['amenazas'] as $index => $amenaza) {
                $stmt->execute([$id_plan, $amenaza, $index]);
            }
        }
    }    private function guardarEstrategias($pdo, $id_plan, $datos) {
        if (!isset($datos['estrategias'])) return;
        
        // Obtener el ID de empresa desde la sesión del usuario
        $id_empresa = $_SESSION['user']['id_empresa'] ?? $_SESSION['user_id'] ?? 1;
        
        // Procesar datos de evaluación FODA si existen en sesión
        $evaluacion_data = $_SESSION['estrategias_evaluacion'] ?? null;
        
        if ($evaluacion_data && isset($evaluacion_data['totales'])) {
            $totales = $evaluacion_data['totales'];
            
            // Determinar la estrategia principal según los totales
            $max_total = max($totales);
            $estrategia_principal = '';
            $descripcion_principal = '';
            
            if ($totales['fo'] == $max_total) {
                $estrategia_principal = 'FO';
                $descripcion_principal = 'Estrategia Ofensiva - Usar fortalezas para aprovechar oportunidades';
            } elseif ($totales['fa'] == $max_total) {
                $estrategia_principal = 'AF';
                $descripcion_principal = 'Estrategia Defensiva - Usar fortalezas para defenderse de amenazas';
            } elseif ($totales['do'] == $max_total) {
                $estrategia_principal = 'OD';
                $descripcion_principal = 'Estrategia de Supervivencia - Superar debilidades aprovechando oportunidades';
            } else {
                $estrategia_principal = 'AD';
                $descripcion_principal = 'Estrategia de Reorientación - Reestructuración para afrontar debilidades y amenazas';
            }
            
            // Guardar en tb_sintesis_estrategias
            $stmt = $pdo->prepare("
                INSERT INTO tb_sintesis_estrategias (id_empresa, relacion, tipologia_estrategia, puntuacion, descripcion) 
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                tipologia_estrategia = VALUES(tipologia_estrategia),
                puntuacion = VALUES(puntuacion),
                descripcion = VALUES(descripcion),
                fecha_actualizacion = CURRENT_TIMESTAMP
            ");
            
            // Guardar cada relación estratégica
            $relaciones = [
                'FO' => ['Estrategia Ofensiva', $totales['fo'], 'Usar fortalezas para aprovechar oportunidades'],
                'AF' => ['Estrategia Defensiva', $totales['fa'], 'Usar fortalezas para defenderse de amenazas'],
                'OD' => ['Estrategia de Supervivencia', $totales['do'], 'Superar debilidades aprovechando oportunidades'],
                'AD' => ['Estrategia de Reorientación', $totales['da'], 'Reestructuración para afrontar debilidades y amenazas']
            ];
            
            foreach ($relaciones as $relacion => $datos_rel) {
                $stmt->execute([
                    $id_empresa,
                    $relacion,
                    $datos_rel[0],
                    $datos_rel[1],
                    $datos_rel[2]
                ]);
            }
            
            error_log("PlanEstrategicoController::guardarEstrategias - Guardado completado en tb_sintesis_estrategias para empresa $id_empresa");
        }
    }
    
    private function guardarMatrizCAME($pdo, $id_plan, $datos) {
        if (!isset($datos['matriz_came'])) return;
        
        $came = $datos['matriz_came'];
        
        // Separar estrategias individuales por tipo
        $estrategias_corregir = [];
        $estrategias_afrontar = [];
        $estrategias_mantener = [];
        $estrategias_explotar = [];
        
        foreach ($came as $key => $value) {
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
        
        // Consolidar estrategias en textos únicos para la tabla principal (compatibilidad)
        $corregir_consolidado = implode("\n\n", array_filter($estrategias_corregir));
        $afrontar_consolidado = implode("\n\n", array_filter($estrategias_afrontar));
        $mantener_consolidado = implode("\n\n", array_filter($estrategias_mantener));
        $explotar_consolidado = implode("\n\n", array_filter($estrategias_explotar));
        
        // Usar datos consolidados o individuales según disponibilidad
        $corregir_final = $corregir_consolidado ?: ($came['corregir'] ?? '');
        $afrontar_final = $afrontar_consolidado ?: ($came['afrontar'] ?? '');
        $mantener_final = $mantener_consolidado ?: ($came['mantener'] ?? '');
        $explotar_final = $explotar_consolidado ?: ($came['explotar'] ?? '');
        
        $stmt = $pdo->prepare("
            INSERT INTO tb_matriz_came (id_plan, corregir, afrontar, mantener, explotar) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $id_plan,
            $corregir_final,
            $afrontar_final,
            $mantener_final,
            $explotar_final
        ]);
        
        // Opcional: guardar estrategias individuales en una tabla separada para mayor detalle
        $this->guardarEstrategiasIndividuales($pdo, $id_plan, $came);
    }
    
    private function guardarEstrategiasIndividuales($pdo, $id_plan, $came_data) {        // Crear tabla para estrategias individuales si no existe
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS tb_estrategias_came_detalle (
                id_estrategia_detalle INT AUTO_INCREMENT PRIMARY KEY,
                id_plan INT NOT NULL,
                tipo_came ENUM('corregir', 'afrontar', 'mantener', 'explotar') NOT NULL,
                indice_elemento INT NOT NULL,
                estrategia TEXT,
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_plan) REFERENCES tb_plan_estrategico(id_plan) ON DELETE CASCADE
            )
        ");
        
        $stmt = $pdo->prepare("
            INSERT INTO tb_estrategias_came_detalle (id_plan, tipo_came, indice_elemento, estrategia) 
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($came_data as $key => $value) {
            if (empty($value)) continue;
            
            $parts = explode('_', $key);
            if (count($parts) === 2) {
                $tipo = $parts[0];
                $indice = (int)$parts[1];
                
                if (in_array($tipo, ['corregir', 'afrontar', 'mantener', 'explotar'])) {
                    $stmt->execute([$id_plan, $tipo, $indice, $value]);
                }
            }
        }    }
    
    // ===============================================
    // UTILIDADES
    // ===============================================
      private function getConexion() {
        try {
            // Crear conexión directa
            $dsn = "mysql:host=localhost;dbname=plan_estrategico;charset=utf8";
            $pdo = new PDO($dsn, "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false); // Desactivar autocommit para transacciones
            error_log("PlanEstrategicoController::getConexion - Conexión establecida exitosamente");
            return $pdo;
        } catch (PDOException $e) {
            error_log("PlanEstrategicoController::getConexion - Error de conexión: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public function obtenerProgresoActual() {
        return $_SESSION['plan_temporal']['paso_actual'] ?? 1;
    }
    
    public function obtenerDatosPlan() {
        return $_SESSION['plan_temporal']['datos'] ?? [];
    }
    
    // ===============================================
    // OBTENER DATOS DE SESIÓN
    // ===============================================
    
    public function obtenerDatosSesion($nombre_paso = null) {
        if (!isset($_SESSION['plan_temporal'])) {
            return null;
        }
        
        if ($nombre_paso) {
            return $_SESSION['plan_temporal'][$nombre_paso] ?? null;
        }
        
        return $_SESSION['plan_temporal'];
    }
    
    public static function getDatosPaso($nombre_paso) {
        if (!isset($_SESSION['plan_temporal'])) {
            return null;
        }
          return $_SESSION['plan_temporal'][$nombre_paso] ?? null;
    }
    
    private function guardarResumenEjecutivo($pdo, $id_plan, $datos) {
        if (!isset($datos['resumen_ejecutivo'])) return;
        
        $resumen = $datos['resumen_ejecutivo'];
        
        // Verificar si ya existe un resumen para este plan
        $stmt = $pdo->prepare("SELECT id_resumen FROM tb_resumen_ejecutivo WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        $existe = $stmt->fetch();
        
        if ($existe) {
            // Actualizar resumen existente
            $stmt = $pdo->prepare("
                UPDATE tb_resumen_ejecutivo 
                SET emprendedores_promotores = ?, 
                    identificacion_estrategica = ?, 
                    conclusiones = ?
                WHERE id_plan = ?
            ");
            $stmt->execute([
                $resumen['emprendedores_promotores'] ?? '',
                $resumen['identificacion_estrategica'] ?? '',
                $resumen['conclusiones'] ?? '',
                $id_plan
            ]);
        } else {
            // Crear nuevo resumen
            $stmt = $pdo->prepare("
                INSERT INTO tb_resumen_ejecutivo 
                (id_plan, emprendedores_promotores, identificacion_estrategica, conclusiones) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $id_plan,
                $resumen['emprendedores_promotores'] ?? '',
                $resumen['identificacion_estrategica'] ?? '',
                $resumen['conclusiones'] ?? ''
            ]);        }
    }
    // ===============================================
    // MÉTODOS PARA CONSULTA DE PLANES
    // ===============================================
      public function obtenerPlanesUsuario($id_usuario = null) {
        try {
            $pdo = $this->getConexion();
            
            // Obtener ID de usuario de manera más flexible
            if ($id_usuario === null) {
                $id_usuario = $_SESSION['user']['id_usuario'] ?? 
                             $_SESSION['user']['id'] ?? 
                             $_SESSION['usuario_id'] ?? 
                             $_SESSION['user_id'] ?? 1;
            }
            
            error_log("PlanEstrategicoController::obtenerPlanesUsuario - Buscando planes para usuario ID: " . $id_usuario);            $stmt = $pdo->prepare("
                SELECT 
                    p.id_plan as id,
                    p.id_plan,
                    p.nombre_plan as nombre_empresa,
                    p.empresa,
                    p.descripcion,
                    p.fecha_creacion,
                    p.fecha_modificacion,
                    p.id_usuario
                FROM tb_plan_estrategico p
                WHERE p.id_usuario = ?
                ORDER BY p.fecha_creacion DESC
            ");
            
            $stmt->execute([$id_usuario]);
            $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("PlanEstrategicoController::obtenerPlanesUsuario - Encontrados " . count($planes) . " planes para usuario " . $id_usuario);
            
            return $planes;
            
        } catch (Exception $e) {
            error_log("Error al obtener planes del usuario: " . $e->getMessage());
            return [];
        }
    }
      public function obtenerDetallePlan($id_plan) {
        try {
            $pdo = $this->getConexion();

            // Plan principal
            $stmt = $pdo->prepare("SELECT * FROM tb_plan_estrategico WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$plan) {
                return null;
            }
            
            // Visión y Misión
            $stmt = $pdo->prepare("SELECT * FROM tb_vision_mision WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan['vision_mision'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Valores
            $stmt = $pdo->prepare("SELECT * FROM tb_valores WHERE id_plan = ? ORDER BY orden");
            $stmt->execute([$id_plan]);
            $plan['valores'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Objetivos Estratégicos
            $stmt = $pdo->prepare("SELECT * FROM tb_objetivos_estrategicos WHERE id_plan = ? ORDER BY categoria, orden");
            $stmt->execute([$id_plan]);
            $plan['objetivos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // UEN (Unidad Estratégica de Negocio)
            $stmt = $pdo->prepare("SELECT * FROM tb_uen WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan['uen'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Cadena de Valor
            $stmt = $pdo->prepare("SELECT * FROM tb_cadena_valor WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan['cadena_valor'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Matriz BCG
            $stmt = $pdo->prepare("SELECT * FROM tb_matriz_bcg WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan['matriz_bcg'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Fuerzas de Porter
            $stmt = $pdo->prepare("SELECT * FROM tb_fuerzas_porter WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan['fuerzas_porter'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Análisis PEST
            $stmt = $pdo->prepare("SELECT * FROM tb_analisis_pest WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan['pest'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // FODA
            $stmt = $pdo->prepare("SELECT * FROM tb_fortalezas WHERE id_plan = ? ORDER BY orden");
            $stmt->execute([$id_plan]);
            $plan['fortalezas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stmt = $pdo->prepare("SELECT * FROM tb_debilidades WHERE id_plan = ? ORDER BY orden");
            $stmt->execute([$id_plan]);
            $plan['debilidades'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stmt = $pdo->prepare("SELECT * FROM tb_oportunidades WHERE id_plan = ? ORDER BY orden");
            $stmt->execute([$id_plan]);
            $plan['oportunidades'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stmt = $pdo->prepare("SELECT * FROM tb_amenazas WHERE id_plan = ? ORDER BY orden");
            $stmt->execute([$id_plan]);
            $plan['amenazas'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Identificación Estratégica
            $stmt = $pdo->prepare("SELECT * FROM tb_identificacion_estrategica WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan['identificacion_estrategica'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Matriz CAME
            $stmt = $pdo->prepare("SELECT * FROM tb_matriz_came WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan['matriz_came'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Resumen Ejecutivo
            $stmt = $pdo->prepare("SELECT * FROM tb_resumen_ejecutivo WHERE id_plan = ?");
            $stmt->execute([$id_plan]);
            $plan['resumen_ejecutivo'] = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $plan;
            
        } catch (Exception $e) {
            error_log("Error al obtener detalle del plan: " . $e->getMessage());
            return null;
        }
    }public function obtenerResumenEjecutivo($id_plan) {
        try {
            $pdo = $this->getConexion();
              // Obtener información básica del plan
            $stmt = $pdo->prepare("
                SELECT 
                    p.nombre_plan,
                    p.empresa,
                    p.fecha_creacion,
                    p.descripcion,
                    p.logo_empresa
                FROM tb_plan_estrategico p
                WHERE p.id_plan = ?
            ");
            $stmt->execute([$id_plan]);
            $plan = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$plan) {
                return null;
            }
            
            // Obtener visión y misión
            try {
                $stmt = $pdo->prepare("SELECT vision, mision FROM tb_vision_mision WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $vision_mision = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($vision_mision) {
                    $plan['vision'] = $vision_mision['vision'];
                    $plan['mision'] = $vision_mision['mision'];
                }
            } catch (Exception $e) {
                error_log("Error al obtener visión/misión: " . $e->getMessage());
            }
            
            // Obtener valores
            try {
                $stmt = $pdo->prepare("SELECT valor, descripcion FROM tb_valores WHERE id_plan = ? ORDER BY orden");
                $stmt->execute([$id_plan]);
                $valores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $plan['valores'] = $valores;
            } catch (Exception $e) {
                error_log("Error al obtener valores: " . $e->getMessage());
            }            // Obtener objetivos estratégicos y unidad estratégica
            try {
                $stmt = $pdo->prepare("SELECT objetivo, categoria, prioridad FROM tb_objetivos_estrategicos WHERE id_plan = ? ORDER BY orden");
                $stmt->execute([$id_plan]);
                $objetivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $plan['objetivos_estrategicos'] = $objetivos;
                
                // Obtener unidad estratégica del paso objetivos
                $stmt = $pdo->prepare("SELECT uen_descripcion FROM tb_uen WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $uen = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($uen && !empty($uen['uen_descripcion'])) {
                    $plan['unidad_estrategica'] = $uen['uen_descripcion'];
                }
            } catch (Exception $e) {
                error_log("Error al obtener objetivos/UEN: " . $e->getMessage());
            }
            
            // Obtener estrategias de síntesis (todas las empresas por ahora, luego filtraremos)
            try {
                $stmt = $pdo->prepare("
                    SELECT 
                        relacion,
                        tipologia_estrategia,
                        puntuacion,
                        descripcion
                    FROM tb_sintesis_estrategias 
                    WHERE id_empresa = 1
                    ORDER BY puntuacion DESC
                ");
                $stmt->execute();
                $estrategias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($estrategias) > 0) {
                    $plan['estrategias_principales'] = '';
                    foreach ($estrategias as $estrategia) {
                        $plan['estrategias_principales'] .= "• **" . $estrategia['tipologia_estrategia'] . "** (" . $estrategia['relacion'] . "): " . $estrategia['descripcion'] . "\n";
                    }
                    $plan['total_estrategias'] = count($estrategias);
                    $plan['acciones_competitivas'] = $estrategias; // Para mostrar individualmente
                }
            } catch (Exception $e) {
                error_log("Error al obtener síntesis: " . $e->getMessage());
            }
            
            // Obtener análisis PEST
            try {
                $stmt = $pdo->prepare("SELECT * FROM tb_analisis_pest WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $pest = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($pest) {
                    $plan['analisis_externo'] = "**Análisis del Entorno Externo (PEST)**\n" .
                                               "• Factores Políticos: Puntuación " . $pest['factor_politico'] . "/10\n" .
                                               "• Factores Económicos: Puntuación " . $pest['factor_economico'] . "/10\n" .
                                               "• Factores Sociales: Puntuación " . $pest['factor_social'] . "/10\n" .
                                               "• Factores Tecnológicos: Puntuación " . $pest['factor_tecnologico'] . "/10";
                    $plan['analisis_pest'] = $pest; // Para acceso individual
                }
            } catch (Exception $e) {
                error_log("Error al obtener PEST: " . $e->getMessage());
            }
            
            // Obtener matriz CAME
            try {
                $stmt = $pdo->prepare("SELECT * FROM tb_matriz_came WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $came = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($came) {
                    $plan['matriz_came'] = "**Estrategias CAME**\n" .
                                          "• **Corregir** (Debilidades): " . $came['corregir'] . "\n" .
                                          "• **Afrontar** (Amenazas): " . $came['afrontar'] . "\n" .
                                          "• **Mantener** (Fortalezas): " . $came['mantener'] . "\n" .
                                          "• **Explotar** (Oportunidades): " . $came['explotar'];
                    $plan['identificacion_estrategica'] = $came; // Para acceso individual
                }
            } catch (Exception $e) {
                error_log("Error al obtener CAME: " . $e->getMessage());
            }
            
            // Obtener FODA
            try {
                $stmt = $pdo->prepare("SELECT descripcion FROM tb_fortalezas WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $fortalezas = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                $stmt = $pdo->prepare("SELECT descripcion FROM tb_debilidades WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $debilidades = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                $stmt = $pdo->prepare("SELECT descripcion FROM tb_oportunidades WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $oportunidades = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                $stmt = $pdo->prepare("SELECT descripcion FROM tb_amenazas WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $amenazas = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                $plan['analisis_interno'] = "**Análisis Interno y Externo (FODA)**\n\n" .
                                           "**FORTALEZAS:**\n• " . implode("\n• ", $fortalezas) . "\n\n" .
                                           "**DEBILIDADES:**\n• " . implode("\n• ", $debilidades) . "\n\n" .
                                           "**OPORTUNIDADES:**\n• " . implode("\n• ", $oportunidades) . "\n\n" .
                                           "**AMENAZAS:**\n• " . implode("\n• ", $amenazas);
                
                // Almacenar arrays individuales para el resumen
                $plan['fortalezas'] = $fortalezas;
                $plan['debilidades'] = $debilidades;
                $plan['oportunidades_foda'] = $oportunidades;
                $plan['amenazas'] = $amenazas;
                  } catch (Exception $e) {
                error_log("Error al obtener FODA: " . $e->getMessage());
            }
              // Obtener conclusiones del usuario (las que TÚ escribiste manualmente)
            try {
                $stmt = $pdo->prepare("SELECT conclusiones, identificacion_estrategica, emprendedores_promotores FROM tb_resumen_ejecutivo WHERE id_plan = ?");
                $stmt->execute([$id_plan]);
                $resumen_user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($resumen_user) {
                    if (!empty($resumen_user['conclusiones'])) {
                        $plan['conclusiones_usuario'] = $resumen_user['conclusiones'];
                    }
                    if (!empty($resumen_user['identificacion_estrategica'])) {
                        $plan['identificacion_estrategica_usuario'] = $resumen_user['identificacion_estrategica'];
                    }
                    if (!empty($resumen_user['emprendedores_promotores'])) {
                        $plan['emprendedores_promotores'] = $resumen_user['emprendedores_promotores'];
                    }
                }
            } catch (Exception $e) {
                error_log("Error al obtener resumen ejecutivo del usuario: " . $e->getMessage());
            }
              // Agregar estadísticas y conclusiones basadas en los datos reales
            $total_fortalezas = count($fortalezas ?? []);
            $total_debilidades = count($debilidades ?? []);
            $total_oportunidades = count($oportunidades ?? []);
            $total_amenazas = count($amenazas ?? []);
            $total_elementos_foda = $total_fortalezas + $total_debilidades + $total_oportunidades + $total_amenazas;
            $total_valores = count($plan['valores'] ?? []);
            $total_objetivos = count($plan['objetivos_estrategicos'] ?? []);
            
            $plan['estadisticas'] = "**Estadísticas del Análisis:**\n" .
                                   "• Total elementos FODA analizados: " . $total_elementos_foda . "\n" .
                                   "• Fortalezas identificadas: " . $total_fortalezas . "\n" .
                                   "• Debilidades identificadas: " . $total_debilidades . "\n" .
                                   "• Oportunidades identificadas: " . $total_oportunidades . "\n" .
                                   "• Amenazas identificadas: " . $total_amenazas . "\n" .
                                   "• Valores organizacionales: " . $total_valores . "\n" .
                                   "• Objetivos estratégicos: " . $total_objetivos . "\n" .
                                   "• Estrategias generadas: " . (count($estrategias ?? []) ?? 0);
            
            $plan['conclusiones'] = "Este plan estratégico presenta un enfoque integral para el desarrollo de " . $plan['empresa'] . ". " .
                                   "Se han identificado " . (count($estrategias ?? []) ?? 0) . " estrategias principales que aprovechan las " . 
                                   $total_fortalezas . " fortalezas internas y las " . $total_oportunidades . " oportunidades del entorno, " .
                                   "mientras mitigan las " . $total_debilidades . " debilidades y " . $total_amenazas . " amenazas identificadas. " .
                                   "El plan incluye " . $total_valores . " valores organizacionales y " . $total_objetivos . " objetivos estratégicos. " .
                                   "El análisis muestra un total de " . $total_elementos_foda . " elementos estratégicos considerados.";
              return $plan;
            
        } catch (Exception $e) {
            error_log("Error al obtener resumen ejecutivo: " . $e->getMessage());
            return null;
        }
    }

    // ===============================================
    // EXPORTACIÓN A PDF
    // ===============================================
    
    public function exportarPDF($id_plan) {
        try {
            // Obtener datos del resumen
            $resumen = $this->obtenerResumenEjecutivo($id_plan);
            
            if (!$resumen) {
                throw new Exception("No se pudo obtener la información del plan.");
            }
            
            // Para navegadores que soportan print CSS, generar HTML optimizado
            $this->generarHTMLParaPDF($resumen, $id_plan);
            
        } catch (Exception $e) {
            // Redirigir con error
            header("Location: resumen_plan.php?id=$id_plan&error=" . urlencode($e->getMessage()));
            exit();
        }
    }
    
    private function generarHTMLParaPDF($resumen, $id_plan) {
        // Generar JavaScript para abrir ventana de impresión
        header('Content-Type: text/html; charset=UTF-8');
        
        echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe PDF - ' . htmlspecialchars($resumen['nombre_plan']) . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .pdf-mensaje { text-align: center; padding: 50px; }
        .btn { display: inline-block; padding: 10px 20px; margin: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .btn-secondary { background: #6c757d; }
    </style>
</head>
<body>
    <div class="pdf-mensaje">
        <h2>Exportación a PDF</h2>
        <p>Para exportar este informe a PDF, puede:</p>
        <ol style="text-align: left; display: inline-block;">
            <li><strong>Opción 1:</strong> Use Ctrl+P en el resumen del plan para imprimir/guardar como PDF</li>
            <li><strong>Opción 2:</strong> Use las opciones de impresión de su navegador</li>
            <li><strong>Opción 3:</strong> Utilice herramientas como Print to PDF o navegadores con exportación PDF</li>
        </ol>
        <p><a href="resumen_plan.php?id=' . $id_plan . '" class="btn">Ver Resumen para Imprimir</a></p>
        <p><a href="home.php" class="btn btn-secondary">Volver al Dashboard</a></p>
    </div>
    
    <script>
        // Auto-abrir ventana del resumen optimizada para impresión
        setTimeout(function() {
            window.open("resumen_plan.php?id=' . $id_plan . '", "_blank");
        }, 1000);
        
        // Redirigir después de 5 segundos
        setTimeout(function() {
            window.location.href = "resumen_plan.php?id=' . $id_plan . '";
        }, 5000);
    </script>
</body>
</html>';
        exit();
    }
}
