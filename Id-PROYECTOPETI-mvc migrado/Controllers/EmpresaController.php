<?php
require_once __DIR__ . '/../config/clsconexion.php';
require_once __DIR__ . '/../Models/EmpresaModel.php';

class EmpresaController {
    public function crear() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../Vista/login.php');
            exit();
        }
        $user = $_SESSION['user'];
        $usuario_id = $user['id_usuario'] ?? $user['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre_empresa'] ?? '';
            $descripcion = $_POST['descripcion_empresa'] ?? '';
            if ($nombre && $descripcion && $usuario_id) {
                $db = (new clsConexion())->getConexion();
                $model = new EmpresaModel($db);
                $ok = $model->crearEmpresa($nombre, $descripcion, $usuario_id);
                if ($ok) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }
    public function listar() {
        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([]);
            exit();
        }
        $user = $_SESSION['user'];
        $usuario_id = $user['id_usuario'] ?? $user['id'] ?? null;
        require_once __DIR__ . '/../Models/PlanModel.php';
        $db = (new clsConexion())->getConexion();
        $empModel = new EmpresaModel($db);
        $planModel = new PlanModel($db);
        $empresas = $empModel->obtenerEmpresasPorUsuario($usuario_id);
        foreach ($empresas as &$empresa) {
            $planes = $planModel->obtenerPlanesPorEmpresa($empresa['id']);
            $empresa['planes'] = $planes;
        }
        echo json_encode($empresas);
    }
}

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    if (isset($_GET['action']) && $_GET['action'] === 'listar') {
        (new EmpresaController())->listar();
    } else {
        (new EmpresaController())->crear();
    }
}
