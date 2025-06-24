<?php
class EmpresaModel {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    public function crearEmpresa($nombre, $descripcion, $usuario_id) {
        $stmt = $this->db->prepare("INSERT INTO empresas (nombre, descripcion, usuario_id) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $usuario_id]);
    }
    public function obtenerEmpresasPorUsuario($usuario_id) {
        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE usuario_id = ? ORDER BY id DESC");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerUltimosPlanesPorEmpresas($usuario_id) {
        $db = (new clsConexion())->getConexion();
        $empModel = new EmpresaModel($db);
        $planModel = new PlanModel($db);
        $empresas = $empModel->obtenerEmpresasPorUsuario($usuario_id);
        foreach ($empresas as &$empresa) {
            $plan = $planModel->obtenerUltimoPlanPorEmpresa($empresa['id']);
            $empresa['id_plan'] = $plan['id'] ?? null;
        }
        return $empresas;
    }
}
