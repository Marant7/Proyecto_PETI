<?php
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

            // TCM
            $stmtTcm = $conn->prepare("SELECT porcentaje_tcm FROM tb_bcg_tcm WHERE id_producto = ? AND periodo = ?");
            $stmtTcm->bind_param("is", $idProducto, $periodo);
            $stmtTcm->execute();
            $stmtTcm->bind_result($tcm);
            $stmtTcm->fetch();
            $stmtTcm->close();

            // Ventas
            $stmtVentas = $conn->prepare("SELECT ventas, porcentaje_total, prm, mayor_venta_competidor FROM tb_bcg_ventas WHERE id_producto = ? ORDER BY fecha_registro DESC LIMIT 1");
            $stmtVentas->bind_param("i", $idProducto);
            $stmtVentas->execute();
            $ventas = $stmtVentas->get_result()->fetch_assoc();
            $stmtVentas->close();

            $datos[] = [
                'nombre' => $producto['nombre_producto'],
                'tcm' => $tcm ?? 0,
                'ventas' => $ventas['ventas'] ?? 0,
                'porcentaje_total' => $ventas['porcentaje_total'] ?? 0,
                'prm' => $ventas['prm'] ?? 0,
                'competidor' => $ventas['mayor_venta_competidor'] ?? 0
            ];
        }

        include 'Vista/matriz_participacion.php';
    }
}
