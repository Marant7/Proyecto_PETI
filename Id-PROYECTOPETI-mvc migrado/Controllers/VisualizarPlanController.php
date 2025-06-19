<?php
session_start();
require_once 'config/clsconexion.php';

class VisualizarPlanController {
    private $conexion;
    
    public function __construct($db = null) {
        if ($db) {
            $this->conexion = new stdClass();
            $this->conexion->conexion = $db;
        } else {
            $this->conexion = new clsConexion();
        }
    }

    // ===============================================
    // LISTAR PLANES DEL USUARIO
    // ===============================================
    
    public function listarPlanes($id_usuario = null) {
        $id_usuario = $id_usuario ?? $_SESSION['user']['id_usuario'] ?? null;
        
        if (!$id_usuario) {
            throw new Exception("Usuario no identificado");
        }
        
        $pdo = $this->getConexion();
        $stmt = $pdo->prepare("
            SELECT id_plan, nombre_plan, empresa, descripcion, estado, 
                   fecha_creacion, fecha_modificacion 
            FROM tb_plan_estrategico 
            WHERE id_usuario = ? 
            ORDER BY fecha_modificacion DESC
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ===============================================
    // OBTENER PLAN COMPLETO
    // ===============================================
    
    public function obtenerPlanCompleto($id_plan) {
        $pdo = $this->getConexion();
        
        // Verificar que el plan existe y pertenece al usuario
        $stmt = $pdo->prepare("
            SELECT * FROM tb_plan_estrategico 
            WHERE id_plan = ? AND id_usuario = ?
        ");
        $stmt->execute([$id_plan, $_SESSION['user']['id_usuario'] ?? 0]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$plan) {
            throw new Exception("Plan no encontrado o sin permisos");
        }
        
        // Obtener todos los módulos del plan
        $plan_completo = [
            'plan_info' => $plan,
            'vision_mision' => $this->obtenerVisionMision($pdo, $id_plan),
            'valores' => $this->obtenerValores($pdo, $id_plan),
            'objetivos' => $this->obtenerObjetivos($pdo, $id_plan),
            'cadena_valor' => $this->obtenerCadenaValor($pdo, $id_plan),
            'matriz_bcg' => $this->obtenerMatrizBCG($pdo, $id_plan),
            'fortalezas_debilidades' => $this->obtenerFortalezasDebilidades($pdo, $id_plan),
            'fuerzas_porter' => $this->obtenerFuerzasPorter($pdo, $id_plan),
            'analisis_pest' => $this->obtenerAnalisisPEST($pdo, $id_plan),
            'oportunidades_amenazas' => $this->obtenerOportunidadesAmenazas($pdo, $id_plan),
            'estrategias' => $this->obtenerEstrategias($pdo, $id_plan),
            'matriz_came' => $this->obtenerMatrizCAME($pdo, $id_plan)
        ];
        
        return $plan_completo;
    }
    
    // ===============================================
    // MÉTODOS ESPECÍFICOS POR MÓDULO
    // ===============================================
    
    private function obtenerVisionMision($pdo, $id_plan) {
        $stmt = $pdo->prepare("SELECT * FROM tb_vision_mision WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    private function obtenerValores($pdo, $id_plan) {
        $stmt = $pdo->prepare("SELECT * FROM tb_valores WHERE id_plan = ? ORDER BY orden");
        $stmt->execute([$id_plan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function obtenerObjetivos($pdo, $id_plan) {
        $stmt = $pdo->prepare("SELECT * FROM tb_objetivos_estrategicos WHERE id_plan = ? ORDER BY orden");
        $stmt->execute([$id_plan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function obtenerCadenaValor($pdo, $id_plan) {
        $stmt = $pdo->prepare("SELECT * FROM tb_cadena_valor WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    private function obtenerMatrizBCG($pdo, $id_plan) {
        // Configuración
        $stmt = $pdo->prepare("SELECT * FROM tb_matriz_bcg_config WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Productos
        $stmt = $pdo->prepare("SELECT * FROM tb_matriz_bcg_productos WHERE id_plan = ? ORDER BY orden");
        $stmt->execute([$id_plan]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ventas
        $stmt = $pdo->prepare("SELECT * FROM tb_matriz_bcg_ventas WHERE id_plan = ? ORDER BY id_producto, anio");
        $stmt->execute([$id_plan]);
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // TCM
        $stmt = $pdo->prepare("SELECT * FROM tb_matriz_bcg_tcm WHERE id_plan = ? ORDER BY id_producto, periodo");
        $stmt->execute([$id_plan]);
        $tcm = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Demanda Global
        $stmt = $pdo->prepare("SELECT * FROM tb_matriz_bcg_demanda_global WHERE id_plan = ? ORDER BY id_producto, anio");
        $stmt->execute([$id_plan]);
        $demanda = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Competidores
        $stmt = $pdo->prepare("SELECT * FROM tb_matriz_bcg_competidores WHERE id_plan = ? ORDER BY id_producto, competidor");
        $stmt->execute([$id_plan]);
        $competidores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'config' => $config,
            'productos' => $productos,
            'ventas' => $ventas,
            'tcm' => $tcm,
            'demanda_global' => $demanda,
            'competidores' => $competidores
        ];
    }
    
    private function obtenerFortalezasDebilidades($pdo, $id_plan) {
        // Fortalezas
        $stmt = $pdo->prepare("SELECT * FROM tb_fortalezas WHERE id_plan = ? ORDER BY orden");
        $stmt->execute([$id_plan]);
        $fortalezas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Debilidades
        $stmt = $pdo->prepare("SELECT * FROM tb_debilidades WHERE id_plan = ? ORDER BY orden");
        $stmt->execute([$id_plan]);
        $debilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'fortalezas' => $fortalezas,
            'debilidades' => $debilidades
        ];
    }
    
    private function obtenerFuerzasPorter($pdo, $id_plan) {
        $stmt = $pdo->prepare("SELECT * FROM tb_fuerzas_porter WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    private function obtenerAnalisisPEST($pdo, $id_plan) {
        $stmt = $pdo->prepare("SELECT * FROM tb_analisis_pest WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    private function obtenerOportunidadesAmenazas($pdo, $id_plan) {
        // Oportunidades
        $stmt = $pdo->prepare("SELECT * FROM tb_oportunidades WHERE id_plan = ? ORDER BY orden");
        $stmt->execute([$id_plan]);
        $oportunidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Amenazas
        $stmt = $pdo->prepare("SELECT * FROM tb_amenazas WHERE id_plan = ? ORDER BY orden");
        $stmt->execute([$id_plan]);
        $amenazas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'oportunidades' => $oportunidades,
            'amenazas' => $amenazas
        ];
    }
    
    private function obtenerEstrategias($pdo, $id_plan) {
        $stmt = $pdo->prepare("SELECT * FROM tb_estrategias WHERE id_plan = ? ORDER BY tipo_estrategia, orden");
        $stmt->execute([$id_plan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function obtenerMatrizCAME($pdo, $id_plan) {
        $stmt = $pdo->prepare("SELECT * FROM tb_matriz_came WHERE id_plan = ?");
        $stmt->execute([$id_plan]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    // ===============================================
    // VISTA DE LISTADO
    // ===============================================
    
    public function mostrarListado() {
        try {
            $planes = $this->listarPlanes();
            require_once 'Vista/listado_planes.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    // ===============================================
    // VISTA DE PLAN COMPLETO
    // ===============================================
    
    public function mostrarPlan($id_plan) {
        try {
            $plan_completo = $this->obtenerPlanCompleto($id_plan);
            require_once 'Vista/ver_plan_completo.php';
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    // ===============================================
    // UTILIDADES
    // ===============================================
    
    private function getConexion() {
        if (method_exists($this->conexion, 'getConexion')) {
            return $this->conexion->getConexion();
        } else {
            return $this->conexion->conexion;
        }
    }
}
