<?php
class PlanModel {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function crearPlan($empresa_id) {
        $stmt = $this->db->prepare("INSERT INTO planes (empresa_id, fecha_creacion) VALUES (?, NOW())");
        $ok = $stmt->execute([$empresa_id]);
        if ($ok) {
            return $this->db->lastInsertId();
        }
        return false;
    }    public function guardarVisionMision($plan_id, $vision, $mision) {
        try {
            // Log para debug
            error_log("=== PLANMODEL guardarVisionMision ===");
            error_log("Plan ID: " . $plan_id);
            error_log("Vision length: " . strlen($vision));
            error_log("Mision length: " . strlen($mision));
            
            $stmt = $this->db->prepare("UPDATE planes SET vision = ?, mision = ? WHERE id = ?");
            $result = $stmt->execute([$vision, $mision, $plan_id]);
            
            error_log("Query ejecutada: " . ($result ? 'true' : 'false'));
            error_log("Filas afectadas: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error PDO en guardarVisionMision: " . $e->getMessage());
            throw $e;
        }
    }
    public function obtenerVisionMision($plan_id) {
        $stmt = $this->db->prepare("SELECT vision, mision FROM planes WHERE id = ?");
        $stmt->execute([$plan_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function guardarValores($plan_id, $valores) {
        $valores_json = json_encode($valores);
        $stmt = $this->db->prepare("UPDATE planes SET valores = ? WHERE id = ?");
        return $stmt->execute([$valores_json, $plan_id]);
    }
    
    public function obtenerValores($plan_id) {
        $stmt = $this->db->prepare("SELECT valores FROM planes WHERE id = ?");
        $stmt->execute([$plan_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado && $resultado['valores']) {
            return json_decode($resultado['valores'], true);
        }
        return [];
    }
    
    public function guardarObjetivos($plan_id, $uen_descripcion, $objetivos_generales, $objetivos_especificos) {
        $objetivos_data = [
            'uen_descripcion' => $uen_descripcion,
            'objetivos_generales' => $objetivos_generales,
            'objetivos_especificos' => $objetivos_especificos
        ];
        $objetivos_json = json_encode($objetivos_data);
        $stmt = $this->db->prepare("UPDATE planes SET objetivos = ? WHERE id = ?");
        return $stmt->execute([$objetivos_json, $plan_id]);
    }
    
    public function obtenerObjetivos($plan_id) {
        $stmt = $this->db->prepare("SELECT objetivos FROM planes WHERE id = ?");
        $stmt->execute([$plan_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado && $resultado['objetivos']) {
            return json_decode($resultado['objetivos'], true);
        }
        return [
            'uen_descripcion' => '',
            'objetivos_generales' => [],
            'objetivos_especificos' => []
        ];
    }
    
    public function obtenerUltimoPlanPorEmpresa($empresa_id) {
        $stmt = $this->db->prepare("SELECT id FROM planes WHERE empresa_id = ? ORDER BY fecha_creacion DESC LIMIT 1");
        $stmt->execute([$empresa_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function obtenerPlanesPorEmpresa($empresa_id) {
        $stmt = $this->db->prepare("SELECT id, fecha_creacion FROM planes WHERE empresa_id = ? ORDER BY fecha_creacion DESC");
        $stmt->execute([$empresa_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function verificarPlanExiste($plan_id) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM planes WHERE id = ?");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Plan existe check - ID: " . $plan_id . " - Resultado: " . ($resultado ? 'existe' : 'no existe'));
            
            return $resultado !== false;
        } catch (PDOException $e) {
            error_log("Error verificando plan: " . $e->getMessage());
            return false;
        }
    }
    
    public function guardarCadenaValor($plan_id, $cadena_valor_data) {
        try {
            // Log para debug
            error_log("=== PLANMODEL guardarCadenaValor ===");
            error_log("Plan ID: " . $plan_id);
            error_log("Datos cadena valor: " . print_r($cadena_valor_data, true));
            
            $cadena_valor_json = json_encode($cadena_valor_data);
            $stmt = $this->db->prepare("UPDATE planes SET cadena_valor = ? WHERE id = ?");
            $result = $stmt->execute([$cadena_valor_json, $plan_id]);
            
            error_log("Query ejecutada: " . ($result ? 'true' : 'false'));
            error_log("Filas afectadas: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error PDO en guardarCadenaValor: " . $e->getMessage());
            throw $e;
        }
    }    public function obtenerCadenaValor($plan_id) {
        try {
            $stmt = $this->db->prepare("SELECT cadena_valor FROM planes WHERE id = ?");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && $resultado['cadena_valor']) {
                return json_decode($resultado['cadena_valor'], true);
            }
            return [];
        } catch (PDOException $e) {
            error_log("Error obteniendo cadena valor: " . $e->getMessage());
            return [];
        }
    }
    
    public function guardarMatrizBCG($plan_id, $matriz_bcg_json) {
        try {
            error_log("=== PLANMODEL guardarMatrizBCG ===");
            error_log("Plan ID: " . $plan_id);
            error_log("Matriz BCG JSON length: " . strlen($matriz_bcg_json));
            
            $stmt = $this->db->prepare("UPDATE planes SET matriz_bcg = ? WHERE id = ?");
            $result = $stmt->execute([$matriz_bcg_json, $plan_id]);
            
            error_log("Query ejecutada: " . ($result ? 'true' : 'false'));
            error_log("Filas afectadas: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error PDO en guardarMatrizBCG: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function obtenerMatrizBCG($plan_id) {
        try {
            $stmt = $this->db->prepare("SELECT matriz_bcg FROM planes WHERE id = ?");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && $resultado['matriz_bcg']) {
                return $resultado['matriz_bcg'];
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error obteniendo matriz BCG: " . $e->getMessage());
            return null;
        }
    }
    
    public function guardarFuerzasPorter($plan_id, $fuerzas_porter_data) {
        try {
            error_log("=== PLANMODEL guardarFuerzasPorter ===");
            error_log("Plan ID: " . $plan_id);
            error_log("Datos fuerzas porter: " . print_r($fuerzas_porter_data, true));
            
            $fuerzas_porter_json = json_encode($fuerzas_porter_data);
            $stmt = $this->db->prepare("UPDATE planes SET fuerzas_porter = ? WHERE id = ?");
            $result = $stmt->execute([$fuerzas_porter_json, $plan_id]);
            
            error_log("Query ejecutada: " . ($result ? 'true' : 'false'));
            error_log("Filas afectadas: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error PDO en guardarFuerzasPorter: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function obtenerFuerzasPorter($plan_id) {
        try {
            $stmt = $this->db->prepare("SELECT fuerzas_porter FROM planes WHERE id = ?");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && $resultado['fuerzas_porter']) {
                return json_decode($resultado['fuerzas_porter'], true);
            }
            return [];
        } catch (PDOException $e) {
            error_log("Error obteniendo fuerzas porter: " . $e->getMessage());
            return [];
        }
    }
    
    public function guardarPEST($plan_id, $pest_data) {
        try {
            error_log("=== PLANMODEL guardarPEST ===");
            error_log("Plan ID: " . $plan_id);
            error_log("Datos PEST: " . print_r($pest_data, true));
            
            $pest_json = json_encode($pest_data);
            $stmt = $this->db->prepare("UPDATE planes SET pest = ? WHERE id = ?");
            $result = $stmt->execute([$pest_json, $plan_id]);
            
            error_log("Query ejecutada: " . ($result ? 'true' : 'false'));
            error_log("Filas afectadas: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error PDO en guardarPEST: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function obtenerPEST($plan_id) {
        try {
            $stmt = $this->db->prepare("SELECT pest FROM planes WHERE id = ?");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && $resultado['pest']) {
                return json_decode($resultado['pest'], true);
            }
            return [];
        } catch (PDOException $e) {
            error_log("Error obteniendo PEST: " . $e->getMessage());
            return [];
        }
    }
    
    public function guardarEstrategias($plan_id, $estrategias_data) {
        try {
            error_log("=== PLANMODEL guardarEstrategias ===");
            error_log("Plan ID: " . $plan_id);
            error_log("Datos Estrategias: " . print_r($estrategias_data, true));
            
            $estrategias_json = json_encode($estrategias_data);
            $stmt = $this->db->prepare("UPDATE planes SET estrategias = ? WHERE id = ?");
            $result = $stmt->execute([$estrategias_json, $plan_id]);
            
            error_log("Query ejecutada: " . ($result ? 'true' : 'false'));
            error_log("Filas afectadas: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error PDO en guardarEstrategias: " . $e->getMessage());
            throw $e;
        }
    }
      public function obtenerEstrategias($plan_id) {
        try {
            $stmt = $this->db->prepare("SELECT estrategias FROM planes WHERE id = ?");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && $resultado['estrategias']) {
                return json_decode($resultado['estrategias'], true);
            }
            return [];
        } catch (PDOException $e) {
            error_log("Error obteniendo estrategias: " . $e->getMessage());
            return [];
        }
    }

    public function guardarMatrizCame($plan_id, $matriz_came_data) {
        try {
            error_log("=== PLANMODEL guardarMatrizCame ===");
            error_log("Plan ID: " . $plan_id);
            error_log("Datos Matriz CAME: " . print_r($matriz_came_data, true));
            
            $matriz_came_json = json_encode($matriz_came_data);
            $stmt = $this->db->prepare("UPDATE planes SET matriz_came = ? WHERE id = ?");
            $result = $stmt->execute([$matriz_came_json, $plan_id]);
            
            error_log("Query ejecutada: " . ($result ? 'true' : 'false'));
            error_log("Filas afectadas: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error PDO en guardarMatrizCame: " . $e->getMessage());
            throw $e;
        }
    }    public function obtenerMatrizCame($plan_id) {
        try {
            $stmt = $this->db->prepare("SELECT matriz_came FROM planes WHERE id = ?");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && $resultado['matriz_came']) {
                return json_decode($resultado['matriz_came'], true);
            }
            return [];
        } catch (PDOException $e) {
            error_log("Error obteniendo matriz CAME: " . $e->getMessage());
            return [];
        }
    }

    public function guardarResumenEjecutivo($plan_id, $datos_resumen) {
        try {
            error_log("=== PLANMODEL guardarResumenEjecutivo ===");
            error_log("Plan ID: " . $plan_id);
            error_log("Datos: " . print_r($datos_resumen, true));
            
            $resumen_json = json_encode($datos_resumen);
            $stmt = $this->db->prepare("UPDATE planes SET resumen_ejecutivo = ? WHERE id = ?");
            $result = $stmt->execute([$resumen_json, $plan_id]);
            
            error_log("Query ejecutada: " . ($result ? 'true' : 'false'));
            error_log("Filas afectadas: " . $stmt->rowCount());
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error PDO en guardarResumenEjecutivo: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerResumenEjecutivo($plan_id) {
        try {
            $stmt = $this->db->prepare("SELECT resumen_ejecutivo FROM planes WHERE id = ?");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && $resultado['resumen_ejecutivo']) {
                return json_decode($resultado['resumen_ejecutivo'], true);
            }
            return [];
        } catch (PDOException $e) {
            error_log("Error obteniendo resumen ejecutivo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene la información de la empresa asociada al plan
     */
    public function obtenerInformacionEmpresa($plan_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT e.nombre as nombre_empresa, e.descripcion, p.fecha_creacion
                FROM planes p
                INNER JOIN empresas e ON p.empresa_id = e.id
                WHERE p.id = ?
            ");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $resultado ? $resultado : [];
        } catch (PDOException $e) {
            error_log("Error obteniendo información de empresa: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene solo el nombre de la empresa para un plan específico
     */
    public function obtenerNombreEmpresa($plan_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT e.nombre as nombre_empresa
                FROM planes p
                INNER JOIN empresas e ON p.empresa_id = e.id
                WHERE p.id = ?
            ");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $resultado ? $resultado['nombre_empresa'] : 'Empresa No Encontrada';
        } catch (PDOException $e) {
            error_log("Error obteniendo nombre de empresa: " . $e->getMessage());
            return 'Error al obtener empresa';
        }
    }

    /**
     * Obtiene la fecha de creación del plan en formato dd/mm/yyyy
     */
    public function obtenerFechaElaboracion($plan_id) {
        try {
            $stmt = $this->db->prepare("SELECT fecha_creacion FROM planes WHERE id = ?");
            $stmt->execute([$plan_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && $resultado['fecha_creacion']) {
                $fecha = new DateTime($resultado['fecha_creacion']);
                return $fecha->format('d/m/Y');
            }
            return date('d/m/Y'); // Fallback a fecha actual
        } catch (PDOException $e) {
            error_log("Error obteniendo fecha de elaboración: " . $e->getMessage());
            return date('d/m/Y'); // Fallback a fecha actual
        }
    }
}
