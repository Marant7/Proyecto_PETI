<?php
ob_start();
header('Content-Type: application/json');
require_once "../Models/ClsCadenaValor.php";
session_start();

class ControladorCadenaValor
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ClsCadenaValor();
    }

    public function guardar()
    {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["success" => false, "error" => "Usuario no autenticado."]);
            exit();
        }

        if (!isset($_POST['respuestas']) || !is_array($_POST['respuestas'])) {
            echo json_encode(["success" => false, "error" => "No se han recibido respuestas válidas."]);
            exit();
        }

        $respuestas = $_POST['respuestas'];
        $id_usuario = $_SESSION['user_id'];

        if (count($respuestas) !== 25) {
            echo json_encode(["success" => false, "error" => "Debe haber exactamente 25 respuestas."]);
            exit();
        }

        // Verificar que todas las respuestas sean valores enteros válidos entre 1 y 5 (por ejemplo)
        foreach ($respuestas as $r) {
            if (!is_numeric($r) || $r < 1 || $r > 5) {
                echo json_encode(["success" => false, "error" => "Las respuestas deben ser números válidos entre 1 y 5."]);
                exit();
            }
        }

        // Convertir a enteros y calcular el porcentaje
        $respuestas = array_map('intval', $respuestas);
        $porcentaje = array_sum($respuestas) * 4; // ejemplo: escala de 1-5 y se multiplica por 4 para llevar a porcentaje

        require_once __DIR__ . '/../config/clsconexion.php';
        $conexion = (new clsConexion())->getConexion();

        $stmt = $conexion->prepare("SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $id_empresa = $fila['id_empresa'];
            $this->modelo->guardarEvaluacion($id_empresa, $respuestas, $porcentaje);
            echo json_encode(["success" => true, "porcentaje" => $porcentaje]);
        } else {
            echo json_encode(["success" => false, "error" => "No se encontró la empresa del usuario."]);
        }

        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new ControladorCadenaValor();
    $controlador->guardar();
}
