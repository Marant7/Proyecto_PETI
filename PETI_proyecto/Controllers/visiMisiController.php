<?php
ob_start();
session_start(); 
header('Content-Type: application/json');  
require_once "../Models/visiMisi.php";  

class visiMisiController {
    private $modelo;

    public function __construct() {
        $this->modelo = new visiMisi();  
    }

    public function guardar() {

        if (isset($_POST['vision']) && isset($_POST['mision']) && isset($_SESSION['user_id'])) {
            $vision = $_POST['vision'];
            $mision = $_POST['mision'];
            $id_usuario = $_SESSION['user_id'];  

            require_once __DIR__ . '/../config/clsconexion.php';
            $conexion = (new clsConexion())->getConexion();

            $stmt = $conexion->prepare("SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($fila = $resultado->fetch_assoc()) {
                $id_empresa = $fila['id_empresa'];  
                $fecha_creacion = date('Y-m-d H:i:s'); 

                $this->modelo->actualizarVisionMision($id_empresa, $vision, $mision, $fecha_creacion);  // Usamos id_empresa

                echo json_encode([
                    "success" => true, 
                    "msg" => "¡Misión y Visión guardadas con éxito!"  // El mensaje que aparecerá en el frontend
                ]);
                exit();
            } else {
                echo json_encode([
                    "success" => false, 
                    "error" => "No se encontró la empresa asociada al usuario"
                ]);
                exit();
            }
        }

        echo json_encode([
            "success" => false, 
            "error" => "Datos incompletos"
        ]);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controlador = new visiMisiController();
    $controlador->guardar();
}
?>
