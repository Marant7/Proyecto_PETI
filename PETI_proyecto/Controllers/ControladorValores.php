<?php
ob_start();
header('Content-Type: application/json');
require_once "../Models/ClsValores.php";
session_start();

class ControladorValores {
    private $modelo;

    public function __construct() {
        $this->modelo = new ClsValores();
    }

    public function guardar() {
        if (isset($_POST['valores']) && isset($_SESSION['user_id'])) {
            $valores = $_POST['valores'];
            $id_usuario = $_SESSION['user_id'];

            require_once __DIR__ . '/../config/clsconexion.php';
            $conexion = (new clsConexion())->getConexion();

            $stmt = $conexion->prepare("SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                $id_empresa = $fila['id_empresa'];
                
                foreach ($valores as $valor) {
                    $this->modelo->guardarValor($id_empresa, $valor);
                }

                echo json_encode(["success" => true]);
                exit();
            }
        }

        // Si hubo un problema
        echo json_encode(["success" => false]);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controlador = new ControladorValores();
    $controlador->guardar();
}
