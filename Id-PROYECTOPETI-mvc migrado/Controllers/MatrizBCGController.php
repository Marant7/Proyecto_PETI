<?php
session_start();
require_once 'config/clsconexion.php';

class MatrizBCGController {
    private $conexion;
    
    public function __construct($db = null) {
        if ($db) {
            // Si se pasa la conexión desde el router
            $this->conexion = new stdClass();
            $this->conexion->conexion = $db;
        } else {
            // Crear nueva conexión si no se pasa
            $this->conexion = new clsConexion();
        }
    }    public function mostrarFormulario($id_empresa = null) {
        // Si se pasa ID por parámetro, guardarlo en sesión
        if ($id_empresa) {
            $_SESSION['id_empresa'] = $id_empresa;
        }
        
        require_once 'Vista/matriz_bcg.php';
    }public function guardarMatrizBCG() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Obtener ID de empresa desde POST o sesión
                $id_empresa = $_POST['id_empresa'] ?? $_SESSION['id_empresa'] ?? null;
                
                if (!$id_empresa) {
                    throw new Exception("ID de empresa no encontrado");
                }
                
                // Obtener la conexión PDO
                $pdo = $this->getConexion();
                
                // Comenzar transacción
                $pdo->beginTransaction();
                
                // 1. Guardar/actualizar configuración principal de matriz BCG
                $id_matriz_bcg = $this->guardarConfiguracionMatriz($id_empresa, $_POST);
                
                // 2. Guardar previsión de ventas
                if (isset($_POST['ventas']) && is_array($_POST['ventas'])) {
                    $this->guardarPrevisionVentas($id_empresa, $_POST['ventas'], $_POST['productos'] ?? []);
                }
                
                // 3. Guardar fortalezas y debilidades
                if (isset($_POST['fortalezas']) && is_array($_POST['fortalezas'])) {
                    $this->guardarFortalezasBCG($id_matriz_bcg, $id_empresa, $_POST['fortalezas']);
                }
                
                if (isset($_POST['debilidades']) && is_array($_POST['debilidades'])) {
                    $this->guardarDebilidadesBCG($id_matriz_bcg, $id_empresa, $_POST['debilidades']);
                }
                
                // Confirmar transacción
                $pdo->commit();
                
                // Guardar en sesión para navegación
                $_SESSION['matriz_bcg_guardada'] = true;
                $_SESSION['id_matriz_bcg'] = $id_matriz_bcg;
                
                // Respuesta exitosa
                echo json_encode(['success' => true, 'message' => 'Matriz BCG guardada exitosamente']);
                
            } catch (Exception $e) {
                // Revertir transacción en caso de error
                $pdo->rollback();
                echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
            }
            exit();
        }
    }
    
    private function getConexion() {
        if (isset($this->conexion->conexion)) {
            return $this->conexion->conexion; // Conexión desde el router
        } else {
            return $this->conexion->getConexion(); // Conexión desde clsConexion
        }
    }
    
    private function prepare($sql) {
        $pdo = $this->getConexion();
        return $pdo->prepare($sql);
    }
      private function guardarConfiguracionMatriz($id_empresa, $datos) {
        $num_productos = intval($datos['numProductos'] ?? 0);
        $num_competidores = intval($datos['numCompetidores'] ?? 5);
        $anio_inicio = intval($datos['anioInicio'] ?? 2020);
        $anio_fin = intval($datos['anioFin'] ?? 2025);
        
        // Verificar si ya existe una matriz para esta empresa
        $sql_check = "SELECT id_matriz_bcg FROM tb_matriz_bcg WHERE id_empresa = ?";
        $stmt_check = $this->conexion->prepare($sql_check);
        $stmt_check->execute([$id_empresa]);
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Actualizar matriz existente
            $id_matriz_bcg = $result['id_matriz_bcg'];
            
            $sql_update = "UPDATE tb_matriz_bcg SET num_productos = ?, num_competidores = ?, anio_inicio = ?, anio_fin = ?, fecha_actualizacion = NOW() WHERE id_matriz_bcg = ?";
            $stmt_update = $this->conexion->prepare($sql_update);
            $stmt_update->execute([$num_productos, $num_competidores, $anio_inicio, $anio_fin, $id_matriz_bcg]);
        } else {
            // Crear nueva matriz
            $sql_insert = "INSERT INTO tb_matriz_bcg (id_empresa, num_productos, num_competidores, anio_inicio, anio_fin) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $this->conexion->prepare($sql_insert);
            $stmt_insert->execute([$id_empresa, $num_productos, $num_competidores, $anio_inicio, $anio_fin]);
            $id_matriz_bcg = $this->conexion->getConexion()->lastInsertId();
        }
        
        return $id_matriz_bcg;
    }
    
    private function guardarPrevisionVentas($id_empresa, $ventas, $productos) {
        // Limpiar datos anteriores
        $sql_delete = "DELETE FROM tb_venta WHERE id_empresa = ?";
        $stmt_delete = $this->conexion->prepare($sql_delete);
        $stmt_delete->execute([$id_empresa]);
        
        // Insertar nuevos datos
        $sql_insert = "INSERT INTO tb_venta (id_empresa, nombre_producto, prevision_ventas, anio) VALUES (?, ?, ?, ?)";
        $stmt_insert = $this->conexion->prepare($sql_insert);
        
        for ($i = 0; $i < count($ventas); $i++) {
            $nombre_producto = $productos[$i] ?? "Producto " . ($i + 1);
            $venta = floatval($ventas[$i] ?? 0);
            $anio = 2025; // Año actual por defecto
            
            $stmt_insert->execute([$id_empresa, $nombre_producto, $venta, $anio]);
        }
    }
    
    private function guardarFortalezasBCG($id_matriz_bcg, $id_empresa, $fortalezas) {
        // Limpiar datos anteriores
        $sql_delete = "DELETE FROM tb_fortalezas_bcg WHERE id_matriz_bcg = ?";
        $stmt_delete = $this->conexion->prepare($sql_delete);
        $stmt_delete->execute([$id_matriz_bcg]);
        
        // Insertar nuevos datos
        $sql_insert = "INSERT INTO tb_fortalezas_bcg (id_matriz_bcg, id_empresa, descripcion, orden) VALUES (?, ?, ?, ?)";
        $stmt_insert = $this->conexion->prepare($sql_insert);
        
        foreach ($fortalezas as $orden => $descripcion) {
            if (!empty(trim($descripcion))) {
                $orden_int = $orden + 1;
                $stmt_insert->execute([$id_matriz_bcg, $id_empresa, $descripcion, $orden_int]);
            }
        }
    }
    
    private function guardarDebilidadesBCG($id_matriz_bcg, $id_empresa, $debilidades) {
        // Limpiar datos anteriores
        $sql_delete = "DELETE FROM tb_debilidades_bcg WHERE id_matriz_bcg = ?";
        $stmt_delete = $this->conexion->prepare($sql_delete);
        $stmt_delete->execute([$id_matriz_bcg]);
        
        // Insertar nuevos datos
        $sql_insert = "INSERT INTO tb_debilidades_bcg (id_matriz_bcg, id_empresa, descripcion, orden) VALUES (?, ?, ?, ?)";
        $stmt_insert = $this->conexion->prepare($sql_insert);
        
        foreach ($debilidades as $orden => $descripcion) {
            if (!empty(trim($descripcion))) {
                $orden_int = $orden + 1;
                $stmt_insert->execute([$id_matriz_bcg, $id_empresa, $descripcion, $orden_int]);
            }
        }
    }
}
?>