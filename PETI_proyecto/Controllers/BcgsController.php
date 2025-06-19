<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/clsconexion.php';

class BcgsController {
    public function index() {
        $empresaId = $_SESSION['empresa_id'] ?? 1;
        $periodo = '2023-2024';

        $db = new clsConexion();
        $conn = $db->getConexion();

        // Obtener productos de la empresa
        $stmt = $conn->prepare("SELECT * FROM tb_bcg_productos WHERE id_empresa = ?");
        $stmt->bind_param("i", $empresaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $datos = [];

        foreach ($productos as $producto) {
            $idProducto = $producto['id_producto'];
            $nombreProducto = $producto['nombre_producto'];

            // TCM
            $stmtTcm = $conn->prepare("SELECT porcentaje_tcm FROM tb_bcg_tcm WHERE id_producto = ? AND periodo = ?");
            $stmtTcm->bind_param("is", $idProducto, $periodo);
            $stmtTcm->execute();
            $stmtTcm->bind_result($tcm);
            $stmtTcm->fetch();
            $stmtTcm->close();

            // Ventas empresa
            $stmtVentas = $conn->prepare("SELECT ventas, porcentaje_total, prm, mayor_venta_competidor FROM tb_bcg_ventas WHERE id_producto = ? ORDER BY fecha_registro DESC LIMIT 1");
            $stmtVentas->bind_param("i", $idProducto);
            $stmtVentas->execute();
            $ventas = $stmtVentas->get_result()->fetch_assoc();
            $stmtVentas->close();

            $ventas = $ventas ?: [
                'ventas' => 0,
                'porcentaje_total' => 0,
                'prm' => 0,
                'mayor_venta_competidor' => 0
            ];

            // Competidores de este producto
            $stmtComp = $conn->prepare("SELECT nombre_competidor, ventas FROM tb_ventas_competidores WHERE id_empresa = ? AND nombre_producto = ?");
            $stmtComp->bind_param("is", $empresaId, $nombreProducto);
            $stmtComp->execute();
            $resultComp = $stmtComp->get_result();
            $competidores = $resultComp->fetch_all(MYSQLI_ASSOC);
            $stmtComp->close();

            $datos[] = [
                'nombre' => $nombreProducto,
                'tcm' => floatval($tcm ?? 0),
                'ventas' => floatval($ventas['ventas']),
                'porcentaje_total' => floatval($ventas['porcentaje_total']),
                'prm' => floatval($ventas['prm']),
                'competidor' => floatval($ventas['mayor_venta_competidor']),
                'competidores' => $competidores // <--- listado completo
            ];
        }

        return $datos;
    }
}
