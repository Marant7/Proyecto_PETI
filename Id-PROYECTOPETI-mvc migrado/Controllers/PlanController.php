<?php
// Limpiar buffer de salida para evitar conflictos con JSON
ob_start();

require_once __DIR__ . '/../config/clsconexion.php';
require_once __DIR__ . '/../Models/PlanModel.php';

class PlanController {
    private $db;
    
    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            // Fallback para compatibilidad
            $this->db = (new clsConexion())->getConexion();
        }
    }
    public function crear() {
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit();
        }
        $empresa_id = $_POST['empresa_id'] ?? null;
        if ($empresa_id) {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            $plan_id = $model->crearPlan($empresa_id);
            if ($plan_id) {
                echo json_encode(['success' => true, 'plan_id' => $plan_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear el plan']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Empresa no especificada']);
        }
    }    public function guardarVisionMision() {
        // Asegurar que el header esté configurado para JSON
        header('Content-Type: application/json');
        
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit();
        }
        
        $plan_id = $_POST['id_plan'] ?? null;
        $vision = $_POST['vision'] ?? '';
        $mision = $_POST['mision'] ?? '';
        
        // Debug: log de datos recibidos
        error_log("=== GUARDANDO VISION MISION ===");
        error_log("Plan ID: " . $plan_id);
        error_log("Vision: " . $vision);
        error_log("Mision: " . $mision);
        error_log("Usuario ID: " . ($_SESSION['user']['id'] ?? 'No ID'));
          if ($plan_id && !empty(trim($vision)) && !empty(trim($mision))) {
            try {
                $db = (new clsConexion())->getConexion();
                error_log("Conexión a BD: OK");
                
                $model = new PlanModel($db);
                
                // Verificar que el plan existe
                if (!$model->verificarPlanExiste($plan_id)) {
                    echo json_encode(['success' => false, 'message' => 'El plan especificado no existe']);
                    exit();
                }
                
                $ok = $model->guardarVisionMision($plan_id, $vision, $mision);
                
                error_log("Resultado del guardado: " . ($ok ? 'true' : 'false'));
                
                if ($ok) {
                    echo json_encode(['success' => true, 'message' => 'Datos guardados correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta en la base de datos']);
                }
            } catch (Exception $e) {
                error_log("Error en guardarVisionMision: " . $e->getMessage());
                error_log("Stack trace: " . $e->getTraceAsString());
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
            }
        } else {
            $missing = [];
            if (!$plan_id) $missing[] = 'id_plan';
            if (empty(trim($vision))) $missing[] = 'vision';
            if (empty(trim($mision))) $missing[] = 'mision';
            error_log("Datos incompletos. Faltan: " . implode(', ', $missing));
            echo json_encode(['success' => false, 'message' => 'Datos incompletos. Faltan: ' . implode(', ', $missing)]);
        }
    }public function editarVisionMision() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        $plan_id = $_GET['id_plan'] ?? null;
        if ($plan_id) {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            $datos = $model->obtenerVisionMision($plan_id);
            $vision_previa = $datos['vision'] ?? '';
            $mision_previa = $datos['mision'] ?? '';
        } else {
            $vision_previa = '';
            $mision_previa = '';
            $plan_id = '';
        }
        $user = $_SESSION['user'] ?? null;
        include '../Vista/vision_mision.php';
    }
    
    public function guardarValores() {
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit();
        }
        
        $plan_id = $_POST['id_plan'] ?? null;
        $valores = $_POST['valores'] ?? [];
        
        // Debug: log de datos recibidos
        error_log("Plan ID: " . $plan_id);
        error_log("Valores: " . print_r($valores, true));
        
        // Filtrar valores vacíos
        $valores = array_filter($valores, function($valor) {
            return !empty(trim($valor));
        });
        
        if ($plan_id && !empty($valores)) {
            try {
                $db = (new clsConexion())->getConexion();
                $model = new PlanModel($db);
                $ok = $model->guardarValores($plan_id, $valores);
                if ($ok) {
                    echo json_encode(['success' => true, 'message' => 'Valores guardados correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta en la base de datos']);
                }
            } catch (Exception $e) {
                error_log("Error en guardarValores: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
            }
        } else {
            $missing = [];
            if (!$plan_id) $missing[] = 'id_plan';
            if (empty($valores)) $missing[] = 'valores';
            echo json_encode(['success' => false, 'message' => 'Datos incompletos. Faltan: ' . implode(', ', $missing)]);
        }
    }
      public function editarValores() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        $plan_id = $_GET['id_plan'] ?? null;
        if ($plan_id) {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            $valores_previos = $model->obtenerValores($plan_id);
        } else {
            $valores_previos = [];
            $plan_id = '';
        }
        $user = $_SESSION['user'] ?? null;
        include '../Vista/valores.php';
    }
    
    public function guardarObjetivos() {
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit();
        }
        
        $plan_id = $_POST['id_plan'] ?? null;
        $uen_descripcion = $_POST['uen_descripcion'] ?? '';
        $objetivos_generales = $_POST['objetivos_generales'] ?? [];
        $objetivos_especificos = $_POST['objetivos_especificos'] ?? [];
        
        // Debug: log de datos recibidos
        error_log("Plan ID: " . $plan_id);
        error_log("UEN: " . $uen_descripcion);
        error_log("Objetivos Generales: " . print_r($objetivos_generales, true));
        error_log("Objetivos Específicos: " . print_r($objetivos_especificos, true));
        
        // Filtrar objetivos vacíos
        $objetivos_generales = array_filter($objetivos_generales, function($objetivo) {
            return !empty(trim($objetivo));
        });
        
        if ($plan_id && !empty(trim($uen_descripcion)) && !empty($objetivos_generales)) {
            try {
                $db = (new clsConexion())->getConexion();
                $model = new PlanModel($db);
                $ok = $model->guardarObjetivos($plan_id, $uen_descripcion, $objetivos_generales, $objetivos_especificos);
                if ($ok) {
                    echo json_encode(['success' => true, 'message' => 'Objetivos guardados correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta en la base de datos']);
                }
            } catch (Exception $e) {
                error_log("Error en guardarObjetivos: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
            }
        } else {
            $missing = [];
            if (!$plan_id) $missing[] = 'id_plan';
            if (empty(trim($uen_descripcion))) $missing[] = 'uen_descripcion';
            if (empty($objetivos_generales)) $missing[] = 'objetivos_generales';
            echo json_encode(['success' => false, 'message' => 'Datos incompletos. Faltan: ' . implode(', ', $missing)]);
        }
    }
    
    public function editarObjetivos() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        $plan_id = $_GET['id_plan'] ?? null;
        if ($plan_id) {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            $objetivos_data = $model->obtenerObjetivos($plan_id);
            // También obtener la misión para mostrarla
            $vision_mision = $model->obtenerVisionMision($plan_id);
            $mision = $vision_mision['mision'] ?? 'No se ha definido misión';
        } else {
            $objetivos_data = [
                'uen_descripcion' => '',
                'objetivos_generales' => [],
                'objetivos_especificos' => []
            ];
            $mision = 'No se ha definido misión';
            $plan_id = '';
        }
        $user = $_SESSION['user'] ?? null;
        
        // Extraer datos para la vista
        $uen_previa = $objetivos_data['uen_descripcion'];
        $objetivos_generales_previos = $objetivos_data['objetivos_generales'];
        $objetivos_especificos_previos = $objetivos_data['objetivos_especificos'];
        
        include '../Vista/objetivos_estrategicos.php';
    }
    
    public function guardarCadenaValor() {
        // Asegurar que el header esté configurado para JSON
        header('Content-Type: application/json');
        
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit();
        }
        
        $plan_id = $_POST['id_plan'] ?? null;
        
        // Obtener todas las respuestas del cuestionario
        $respuestas = [];
        for ($i = 1; $i <= 25; $i++) {
            $respuestas["q$i"] = $_POST["q$i"] ?? null;
        }
        
        // Obtener otros datos
        $resultado = $_POST['resultado'] ?? '';
        $porcentaje = $_POST['porcentaje'] ?? 0;
        $fortalezas = array_filter($_POST['fortalezas'] ?? []);
        $debilidades = array_filter($_POST['debilidades'] ?? []);
        
        // Debug: log de datos recibidos
        error_log("=== GUARDANDO CADENA VALOR ===");
        error_log("Plan ID: " . $plan_id);
        error_log("Porcentaje: " . $porcentaje);
        error_log("Fortalezas: " . print_r($fortalezas, true));
        error_log("Debilidades: " . print_r($debilidades, true));
        
        // Verificar que tengamos los datos mínimos
        if ($plan_id && !empty($respuestas) && !empty($fortalezas) && !empty($debilidades)) {
            try {
                $db = (new clsConexion())->getConexion();
                $model = new PlanModel($db);
                
                // Verificar que el plan existe
                if (!$model->verificarPlanExiste($plan_id)) {
                    echo json_encode(['success' => false, 'message' => 'El plan especificado no existe']);
                    exit();
                }
                
                // Preparar datos para guardar
                $cadena_valor_data = [
                    'respuestas' => $respuestas,
                    'resultado' => $resultado,
                    'porcentaje' => $porcentaje,
                    'fortalezas' => $fortalezas,
                    'debilidades' => $debilidades
                ];
                
                $ok = $model->guardarCadenaValor($plan_id, $cadena_valor_data);
                
                if ($ok) {
                    echo json_encode(['success' => true, 'message' => 'Cadena de valor guardada correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta en la base de datos']);
                }
            } catch (Exception $e) {
                error_log("Error en guardarCadenaValor: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
            }
        } else {
            $missing = [];
            if (!$plan_id) $missing[] = 'id_plan';
            if (empty($respuestas)) $missing[] = 'respuestas';
            if (empty($fortalezas)) $missing[] = 'fortalezas';
            if (empty($debilidades)) $missing[] = 'debilidades';
            
            error_log("Datos incompletos. Faltan: " . implode(', ', $missing));
            echo json_encode(['success' => false, 'message' => 'Datos incompletos. Faltan: ' . implode(', ', $missing)]);
        }
    }
    
    public function editarCadenaValor() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $plan_id = $_GET['id_plan'] ?? null;
        if ($plan_id) {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            $cadena_valor_previa = $model->obtenerCadenaValor($plan_id);
        } else {
            $cadena_valor_previa = [];
            $plan_id = '';
        }
        
        $user = $_SESSION['user'] ?? null;
        include '../Vista/cadena_valor.php';
    }
    
    public function guardarMatrizBCG() {
        header('Content-Type: application/json');
        
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit();
        }
        
        $plan_id = $_POST['id_plan'] ?? null;
        
        // Recopilar todos los datos del formulario de Matriz BCG
        $datos_bcg = [
            'productos' => $_POST['productos'] ?? [],
            'ventas' => $_POST['ventas'] ?? [],
            'porcentaje_total' => $_POST['porcentaje_total'] ?? [],
            'fortalezas' => $_POST['fortalezas'] ?? [],
            'debilidades' => $_POST['debilidades'] ?? []
        ];
        
        // Agregar datos TCM (Tasas de Crecimiento del Mercado)
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'tcm_') === 0) {
                $datos_bcg[$key] = $value;
            }
            if (strpos($key, 'demanda_') === 0) {
                $datos_bcg[$key] = $value;
            }
            if (strpos($key, 'competidor_') === 0) {
                $datos_bcg[$key] = $value;
            }
        }
        
        // Debug: log de datos recibidos
        error_log("=== GUARDANDO MATRIZ BCG ===");
        error_log("Plan ID: " . $plan_id);
        error_log("Datos BCG: " . json_encode($datos_bcg));
        
        if ($plan_id && !empty($datos_bcg['productos'])) {
            try {
                $db = (new clsConexion())->getConexion();
                $model = new PlanModel($db);
                $resultado = $model->guardarMatrizBCG($plan_id, json_encode($datos_bcg));
                
                if ($resultado) {
                    error_log("Matriz BCG guardada exitosamente");
                    echo json_encode(['success' => true, 'message' => 'Matriz BCG guardada correctamente']);
                } else {
                    error_log("Error al guardar Matriz BCG en la base de datos");
                    echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
                }
            } catch (Exception $e) {
                error_log("Excepción al guardar Matriz BCG: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
            }
        } else {
            error_log("Datos insuficientes para guardar Matriz BCG");
            echo json_encode(['success' => false, 'message' => 'Datos insuficientes']);
        }
    }
      public function editarMatrizBCG() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $user = $_SESSION['user'];
        $plan_id = $_GET['id_plan'] ?? null;
        $matriz_bcg_previa = [];
        
        if ($plan_id) {
            try {
                $db = (new clsConexion())->getConexion();
                $model = new PlanModel($db);
                $datos = $model->obtenerMatrizBCG($plan_id);
                
                // Debug: log de datos obtenidos
                error_log("=== CARGANDO MATRIZ BCG ===");
                error_log("Plan ID: " . $plan_id);
                error_log("Datos crudos: " . ($datos ?? 'null'));
                
                if ($datos && !empty($datos)) {
                    $matriz_bcg_previa = json_decode($datos, true) ?? [];
                    error_log("Datos decodificados: " . print_r($matriz_bcg_previa, true));
                } else {
                    error_log("No hay datos previos de Matriz BCG");
                }
            } catch (Exception $e) {
                error_log("Error al obtener Matriz BCG: " . $e->getMessage());
            }
        }
        
        // Incluir la vista
        include __DIR__ . '/../Vista/matriz_bcg.php';
    }

    public function test() {
        header('Content-Type: application/json');
        session_start();
        
        try {
            // Verificar sesión
            if (!isset($_SESSION['user'])) {
                echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
                return;
            }
            
            // Verificar conexión a BD
            $db = (new clsConexion())->getConexion();
            
            // Hacer una consulta simple
            $stmt = $db->query("SELECT 1 as test");
            $result = $stmt->fetch();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Conexión OK',
                'user' => $_SESSION['user']['nombre'] ?? 'Sin nombre',
                'test_query' => $result['test'] ?? 'Sin resultado'
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    public function guardarFuerzasPorter() {
        header('Content-Type: application/json');
        
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit();
        }
        
        $plan_id = $_POST['id_plan'] ?? null;
        
        // Debug: log de datos recibidos
        error_log("=== GUARDANDO FUERZAS PORTER ===");
        error_log("Plan ID: " . $plan_id);
        error_log("POST data: " . print_r($_POST, true));
        
        if ($plan_id) {
            try {
                $db = (new clsConexion())->getConexion();
                $model = new PlanModel($db);
                
                // Verificar que el plan existe
                if (!$model->verificarPlanExiste($plan_id)) {
                    echo json_encode(['success' => false, 'message' => 'El plan especificado no existe']);
                    exit();
                }
                
                // Procesar factores del análisis
                $factores = [];
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'factor_') === 0) {
                        $factor_index = str_replace('factor_', '', $key);
                        $factores[$factor_index] = intval($value);
                    }
                }
                
                // Procesar oportunidades y amenazas
                $oportunidades = $_POST['oportunidades'] ?? [];
                $amenazas = $_POST['amenazas'] ?? [];
                
                // Filtrar valores vacíos
                $oportunidades = array_filter($oportunidades, function($item) {
                    return !empty(trim($item));
                });
                $amenazas = array_filter($amenazas, function($item) {
                    return !empty(trim($item));
                });
                
                // Calcular total y conclusión
                $total = array_sum($factores);
                $conclusion = '';
                if($total < 30) {
                    $conclusion = 'Estamos en un mercado altamente competitivo, en el que es muy difícil hacerse un hueco en el mercado.';
                } else if($total < 45) {
                    $conclusion = 'Estamos en un mercado de competitividad relativamente alta, pero con ciertas modificaciones en el producto y la política comercial de la empresa, podría encontrarse un nicho de mercado.';
                } else if($total < 60) {
                    $conclusion = 'La situación actual del mercado es favorable a la empresa.';
                } else {
                    $conclusion = 'Estamos en una situación excelente para la empresa.';
                }
                
                // Preparar datos para guardar
                $fuerzas_porter_data = [
                    'factores' => $factores,
                    'oportunidades' => array_values($oportunidades),
                    'amenazas' => array_values($amenazas),
                    'total' => $total,
                    'conclusion' => $conclusion,
                    'fecha_guardado' => date('Y-m-d H:i:s')
                ];
                
                $ok = $model->guardarFuerzasPorter($plan_id, $fuerzas_porter_data);
                
                error_log("Resultado del guardado: " . ($ok ? 'true' : 'false'));
                
                if ($ok) {
                    echo json_encode(['success' => true, 'message' => 'Análisis de Fuerzas Porter guardado correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
                }
            } catch (Exception $e) {
                error_log("Error en guardarFuerzasPorter: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Plan ID no especificado']);
        }
    }
    
    public function editarFuerzasPorter() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $plan_id = $_GET['id_plan'] ?? null;
        $datos_previos = [];
        
        if ($plan_id) {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            $datos_previos = $model->obtenerFuerzasPorter($plan_id);
        }
        
        $user = $_SESSION['user'] ?? null;
        include '../Vista/fuerzas_porter.php';
    }    public function guardarPEST() {
        // Limpiar cualquier salida previa
        if (ob_get_level()) ob_clean();
        
        // Asegurar que el header esté configurado para JSON
        header('Content-Type: application/json');
        
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!isset($_SESSION['user'])) {
                echo json_encode(['success' => false, 'message' => 'No autenticado']);
                exit();
            }
            
            $plan_id = $_POST['id_plan'] ?? null;
            
            if (!$plan_id) {
                echo json_encode(['success' => false, 'message' => 'Plan ID no especificado']);
                exit();
            }
            
            // Conectar a la base de datos
            $db = (new clsConexion())->getConexion();
            if (!$db) {
                echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
                exit();
            }
            
            $model = new PlanModel($db);
            
            // Procesar respuestas del análisis PEST (25 preguntas)
            $respuestas = [];
            for ($i = 1; $i <= 25; $i++) {
                $respuestas["pest_$i"] = intval($_POST["pest_$i"] ?? 1);
            }
            
            // Calcular puntuaciones por factor (5 preguntas por factor)
            $factor1 = $respuestas['pest_1'] + $respuestas['pest_2'] + $respuestas['pest_3'] + $respuestas['pest_4'] + $respuestas['pest_5'];
            $factor2 = $respuestas['pest_6'] + $respuestas['pest_7'] + $respuestas['pest_8'] + $respuestas['pest_9'] + $respuestas['pest_10'];
            $factor3 = $respuestas['pest_11'] + $respuestas['pest_12'] + $respuestas['pest_13'] + $respuestas['pest_14'] + $respuestas['pest_15'];
            $factor4 = $respuestas['pest_16'] + $respuestas['pest_17'] + $respuestas['pest_18'] + $respuestas['pest_19'] + $respuestas['pest_20'];
            $factor5 = $respuestas['pest_21'] + $respuestas['pest_22'] + $respuestas['pest_23'] + $respuestas['pest_24'] + $respuestas['pest_25'];
            
            $factores = [
                'demografico' => $factor1,
                'legal_politico' => $factor2,
                'economico' => $factor3,
                'tecnologico' => $factor4,
                'medioambiental' => $factor5
            ];
            
            $total = $factor1 + $factor2 + $factor3 + $factor4 + $factor5;
            
            // Generar evaluación automática
            if ($total >= 100) {
                $evaluacion = 'Excelente: El entorno global es muy favorable para su organización.';
            } elseif ($total >= 75) {
                $evaluacion = 'Bueno: El entorno es generalmente favorable con algunas áreas que requieren atención.';
            } elseif ($total >= 50) {
                $evaluacion = 'Regular: El entorno presenta desafíos moderados que requieren atención estratégica.';
            } else {
                $evaluacion = 'Desafiante: El entorno presenta significativos retos que requieren estrategias específicas.';
            }
              // Procesar oportunidades y amenazas
            $oportunidades = [];
            $amenazas = [];
            
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'oportunidades') === 0) {
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            if (!empty(trim($item))) {
                                $oportunidades[] = trim($item);
                            }
                        }
                    } elseif (!empty(trim($value))) {
                        $oportunidades[] = trim($value);
                    }
                } elseif (strpos($key, 'amenazas') === 0) {
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            if (!empty(trim($item))) {
                                $amenazas[] = trim($item);
                            }
                        }
                    } elseif (!empty(trim($value))) {
                        $amenazas[] = trim($value);
                    }
                }
            }
            
            // Preparar datos para guardar
            $pest_data = [
                'respuestas' => $respuestas,
                'factores' => $factores,
                'total' => $total,
                'evaluacion' => $evaluacion,
                'oportunidades' => $oportunidades,
                'amenazas' => $amenazas,
                'fecha_guardado' => date('Y-m-d H:i:s')
            ];
            
            // Intentar guardar
            $ok = $model->guardarPEST($plan_id, $pest_data);
            
            if ($ok) {
                echo json_encode(['success' => true, 'message' => 'Análisis PEST guardado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
        }
        
        exit();
    }
    
    public function editarPEST() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $plan_id = $_GET['id_plan'] ?? null;
        $datos_previos = [];
        
        if ($plan_id) {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            $datos_previos = $model->obtenerPEST($plan_id);
        }
        
        $user = $_SESSION['user'] ?? null;
        include '../Vista/pest_nuevo.php';
    }
    
    public function guardarEstrategias() {
        // Limpiar buffer y configurar header para JSON
        ob_clean();
        header('Content-Type: application/json');
        
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if (!isset($_SESSION['user'])) {
                echo json_encode(['success' => false, 'message' => 'No autenticado']);
                exit();
            }            $plan_id = $_POST['id_plan'] ?? null;
            $foda_data = $_POST['foda_data'] ?? null;
            $evaluacion_data = $_POST['evaluacion_data'] ?? null;
            
            // Decodificar JSON si los datos vienen como string
            if (is_string($foda_data)) {
                $foda_data = json_decode($foda_data, true);
            }
            if (is_string($evaluacion_data)) {
                $evaluacion_data = json_decode($evaluacion_data, true);
            }
            
            error_log("=== GUARDANDO ESTRATEGIAS ===");
            error_log("Plan ID: " . $plan_id);
            error_log("FODA Data (después de decode): " . print_r($foda_data, true));
            error_log("Evaluacion Data (después de decode): " . print_r($evaluacion_data, true));
              // Permitir guardar solo con evaluacion_data, foda_data es opcional
            if ($plan_id && $evaluacion_data) {
                $model = new PlanModel($this->db);
                
                // Verificar que el plan existe
                if (!$model->verificarPlanExiste($plan_id)) {
                    echo json_encode(['success' => false, 'message' => 'El plan especificado no existe']);
                    exit();
                }
                
                $estrategias_data = [
                    'foda' => $foda_data ?? [], // Usar array vacío si no hay datos FODA
                    'evaluacion' => $evaluacion_data,
                    'fecha_actualizacion' => date('Y-m-d H:i:s')
                ];
                
                $ok = $model->guardarEstrategias($plan_id, $estrategias_data);
                
                if ($ok) {
                    echo json_encode(['success' => true, 'message' => 'Estrategias guardadas correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al guardar las estrategias']);
                }            } else {
                $missing = [];
                if (!$plan_id) $missing[] = 'ID del plan';
                if (!$evaluacion_data) $missing[] = 'datos de evaluación';
                
                echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos: ' . implode(', ', $missing)]);
            }
        } catch (Exception $e) {
            error_log("Error en guardarEstrategias: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }
      public function editarEstrategias() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $plan_id = $_GET['id_plan'] ?? null;
        $datos_previos = [];
        
        if ($plan_id) {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            $datos_previos = $model->obtenerEstrategias($plan_id);
        }
        
        $user = $_SESSION['user'] ?? null;
        include '../Vista/identificacion_de_estrategias.php';
    }    public function editarMatrizCame() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $plan_id = $_GET['id_plan'] ?? null;
        $datos_previos = [];
        
        if ($plan_id) {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            $datos_previos = $model->obtenerMatrizCame($plan_id);
        }
        
        $user = $_SESSION['user'] ?? null;
        include '../Vista/matriz_came_ejemplo.php';
    }    public function editarResumenEjecutivo() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $plan_id = $_GET['id_plan'] ?? null;
        
        if (!$plan_id) {
            header('Location: ../Vista/home.php');
            exit();
        }
        
        // Inicializar modelo
        $db = (new clsConexion())->getConexion();
        $model = new PlanModel($db);
        
        // Obtener información de la empresa y fecha
        $informacion_empresa = $model->obtenerInformacionEmpresa($plan_id);
        $nombre_empresa = $informacion_empresa['nombre_empresa'] ?? 'Empresa No Encontrada';
        $fecha_elaboracion = $model->obtenerFechaElaboracion($plan_id);
        
        // Pasar variables a la vista
        $user = $_SESSION['user'] ?? null;
        include '../Vista/resumen_ejecutivo.php';
    }

    /**
     * Vista de solo lectura del resumen ejecutivo para presentación formal
     */
    public function verResumenEjecutivo() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $plan_id = $_GET['id_plan'] ?? null;
        
        if (!$plan_id) {
            header('Location: ../Vista/home.php');
            exit();
        }
        
        // Inicializar modelo
        $db = (new clsConexion())->getConexion();
        $model = new PlanModel($db);
        
        // Obtener información de la empresa y fecha
        $informacion_empresa = $model->obtenerInformacionEmpresa($plan_id);
        $nombre_empresa = $informacion_empresa['nombre_empresa'] ?? 'Empresa No Encontrada';
        $fecha_elaboracion = $model->obtenerFechaElaboracion($plan_id);
        
        // Pasar variables a la vista de solo lectura
        $user = $_SESSION['user'] ?? null;
        $modo_solo_lectura = true; // Variable para indicar que es solo lectura
        include '../Vista/resumen_ejecutivo_presentacion.php';
    }
    
    public function guardarResumenEjecutivo() {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit();
        }
        
        $plan_id = $_POST['id_plan'] ?? $_GET['id_plan'] ?? null;
        
        if (!$plan_id) {
            echo json_encode(['success' => false, 'message' => 'ID del plan no encontrado']);
            return;
        }
        
        $db = (new clsConexion())->getConexion();
        $model = new PlanModel($db);
        
        // Obtener todos los datos del resumen ejecutivo del POST
        $datos_resumen = [
            'emprendedores_promotores' => $_POST['emprendedores_promotores'] ?? '',
            'identificacion_estrategica' => $_POST['identificacion_estrategica'] ?? '',
            'conclusiones' => $_POST['conclusiones'] ?? '',
            'fecha_guardado' => date('Y-m-d H:i:s')
        ];
        
        // Guardar el resumen ejecutivo
        $resultado = $model->guardarResumenEjecutivo($plan_id, $datos_resumen);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Resumen ejecutivo guardado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el resumen ejecutivo']);
        }
    }

    /**
     * Exportar resumen ejecutivo a PDF
     */
    public function exportarResumenPDF() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $plan_id = $_GET['id_plan'] ?? null;
        
        if (!$plan_id) {
            echo "<script>alert('ID del plan no encontrado'); window.history.back();</script>";
            exit();
        }
        
        try {
            // Incluir el exportador
            require_once __DIR__ . '/../Models/ResumenEjecutivoPDFExporter.php';
            
            // Crear y generar el PDF
            $exportador = new ResumenEjecutivoPDFExporter($plan_id);
            $exportador->generarPDF();
            
            // Descargar el PDF
            $exportador->descargar();
            
        } catch (Exception $e) {
            echo "<script>alert('Error al generar PDF: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        }
    }

    /**
     * Ver resumen ejecutivo en PDF (mostrar en navegador)
     */
    public function verResumenPDF() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        
        $plan_id = $_GET['id_plan'] ?? null;
        
        if (!$plan_id) {
            echo "<script>alert('ID del plan no encontrado'); window.history.back();</script>";
            exit();
        }
        
        try {
            // Incluir el exportador
            require_once __DIR__ . '/../Models/ResumenEjecutivoPDFExporter.php';
            
            // Crear y generar el PDF
            $exportador = new ResumenEjecutivoPDFExporter($plan_id);
            $exportador->generarPDF();
            
            // Mostrar el PDF en el navegador
            $exportador->mostrar();
            
        } catch (Exception $e) {
            echo "<script>alert('Error al generar PDF: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        }
    }
    
    /**
     * Guardar matriz CAME
     */
    public function guardarMatrizCame() {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit();
        }
        
        $plan_id = $_POST['id_plan'] ?? null;
        
        if (!$plan_id) {
            echo json_encode(['success' => false, 'message' => 'ID del plan no encontrado']);
            return;
        }
        
        try {
            $db = (new clsConexion())->getConexion();
            $model = new PlanModel($db);
            
            // Recopilar todos los datos de la matriz CAME
            $matriz_came_data = [];
            
            // Procesar todas las estrategias de los POST
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'corregir_') === 0 || 
                    strpos($key, 'afrontar_') === 0 || 
                    strpos($key, 'mantener_') === 0 || 
                    strpos($key, 'explotar_') === 0) {
                    if (!empty(trim($value))) {
                        $matriz_came_data[$key] = trim($value);
                    }
                }
            }
            
            // Agregar metadatos
            $matriz_came_data['fecha_guardado'] = date('Y-m-d H:i:s');
            
            // Guardar en la base de datos
            $resultado = $model->guardarMatrizCame($plan_id, $matriz_came_data);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Matriz CAME guardada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la matriz CAME']);
            }
        } catch (Exception $e) {
            error_log("Error al guardar matriz CAME: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }
}

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    if (isset($_GET['action']) && $_GET['action'] === 'editarVisionMision') {
        (new PlanController())->editarVisionMision();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarVisionMision') {
        (new PlanController())->guardarVisionMision();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'editarValores') {
        (new PlanController())->editarValores();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarValores') {
        (new PlanController())->guardarValores();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'editarObjetivos') {
        (new PlanController())->editarObjetivos();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarObjetivos') {
        (new PlanController())->guardarObjetivos();    } elseif (isset($_GET['action']) && $_GET['action'] === 'editarCadenaValor') {
        (new PlanController())->editarCadenaValor();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarCadenaValor') {
        (new PlanController())->guardarCadenaValor();    } elseif (isset($_GET['action']) && $_GET['action'] === 'editarMatrizBCG') {
        (new PlanController())->editarMatrizBCG();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarMatrizBCG') {
        (new PlanController())->guardarMatrizBCG();    } elseif (isset($_GET['action']) && $_GET['action'] === 'editarFuerzasPorter') {
        (new PlanController())->editarFuerzasPorter();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarFuerzasPorter') {
        (new PlanController())->guardarFuerzasPorter();    } elseif (isset($_GET['action']) && $_GET['action'] === 'editarPEST') {
        (new PlanController())->editarPEST();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarPEST') {
        (new PlanController())->guardarPEST();    } elseif (isset($_GET['action']) && $_GET['action'] === 'editarEstrategias') {
        (new PlanController())->editarEstrategias();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarEstrategias') {
        (new PlanController())->guardarEstrategias();    } elseif (isset($_GET['action']) && $_GET['action'] === 'editarMatrizCame') {
        (new PlanController())->editarMatrizCame();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarMatrizCame') {
        (new PlanController())->guardarMatrizCame();    } elseif (isset($_GET['action']) && $_GET['action'] === 'editarResumenEjecutivo') {
        (new PlanController())->editarResumenEjecutivo();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'verResumenEjecutivo') {
        (new PlanController())->verResumenEjecutivo();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'guardarResumenEjecutivo') {
        (new PlanController())->guardarResumenEjecutivo();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'exportarResumenPDF') {
        (new PlanController())->exportarResumenPDF();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'verResumenPDF') {
        (new PlanController())->verResumenPDF();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'test') {
        (new PlanController())->test();
    } else {
        (new PlanController())->crear();
    }
}
