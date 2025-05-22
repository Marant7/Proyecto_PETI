<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start(); 

require_once __DIR__ . "/../Models/PorterModel.php";

class FuerzasPorterController {
    private $porterModel;
    private $textos_conclusion_mapeo;
    private $totalFactoresRequeridosEnForm;

    public function __construct() {
        $this->porterModel = new PorterModel();
        $this->textos_conclusion_mapeo = [
            "clave_B38" => "Estamos en un mercado altamente competitivo, en el que es muy difícil hacerse un hueco en el mercado.",
            "clave_B39" => "Estamos en un mercado de competitividad relativamente alta, pero con ciertas modificaciones en el producto y la política comercial de la empresa, podría encontrarse un nicho de mercado.",
            "clave_B40" => "La situación actual del mercado es favorable a la empresa.",
            "clave_B41" => "Estamos en una situación excelente para la empresa."
        ];
        $this->totalFactoresRequeridosEnForm = 17;
    }

    public function guardar() {
        header('Content-Type: application/json'); 

        if (!isset($_SESSION['user_id'])) {
            error_log("ControladorPorter: Usuario no autenticado.");
            echo json_encode(["success" => false, "error" => "Usuario no autenticado. Por favor, inicie sesión."]);
            exit();
        }
        $id_usuario_sesion = $_SESSION['user_id'];

        if (empty($_POST)) {
            error_log("ControladorPorter: No se recibieron datos POST.");
            echo json_encode(["success" => false, "error" => "No se recibieron datos para procesar."]);
            exit();
        }
        
        $id_empresa = null;
        require_once __DIR__ . '/../config/clsconexion.php';
        $temp_conexion_obj = new clsConexion();
        $temp_conexion = $temp_conexion_obj->getConexion();
        $stmt_emp = $temp_conexion->prepare("SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?");
        if (!$stmt_emp) {
            error_log("ControladorPorter: Error preparando SELECT id_empresa: " . $temp_conexion->error);
            echo json_encode(["success" => false, "error" => "Error interno del servidor (E1C)."]);
            exit();
        }
        $stmt_emp->bind_param("i", $id_usuario_sesion);
        $stmt_emp->execute();
        $resultado_emp = $stmt_emp->get_result();
        if ($fila_emp = $resultado_emp->fetch_assoc()) {
            $id_empresa = $fila_emp['id_empresa'];
        } else {
            error_log("ControladorPorter: No se encontró empresa para usuario ID: " . $id_usuario_sesion);
            echo json_encode(["success" => false, "error" => "No se encontró una empresa asociada a su cuenta. Por favor, configure una empresa."]);
            $stmt_emp->close();
            exit();
        }
        $stmt_emp->close();

        if ($id_empresa === null) { 
             echo json_encode(["success" => false, "error" => "No se pudo determinar la empresa del usuario."]);
             exit();
        }

        $datosParaGuardar = [];
        $puntaje_total = 0;
        $factores_recibidos_count = 0;

        for ($i = 0; $i < $this->totalFactoresRequeridosEnForm; $i++) {
            $nombre_input_form = "factor_" . $i;
            if (isset($_POST[$nombre_input_form])) {
                $valor = intval($_POST[$nombre_input_form]);
                if ($valor >= 1 && $valor <= 5) {
                    $datosParaGuardar[$nombre_input_form] = $valor; 
                    $puntaje_total += $valor;
                    $factores_recibidos_count++;
                } else {
                    error_log("ControladorPorter: Valor inválido para {$nombre_input_form}: {$valor}");
                    echo json_encode(["success" => false, "error" => "Valor inválido para el factor '" . ($i + 1) . "'. Debe ser entre 1 y 5."]);
                    exit();
                }
            } else {
                error_log("ControladorPorter: Falta respuesta para {$nombre_input_form}.");
                echo json_encode(["success" => false, "error" => "Faltan respuestas. Factor '" . ($i + 1) . "' no fue enviado."]);
                exit();
            }
        }

        if ($factores_recibidos_count !== $this->totalFactoresRequeridosEnForm) {
            error_log("ControladorPorter: Conteo de factores incorrecto. Esperados: {$this->totalFactoresRequeridosEnForm}, Recibidos: {$factores_recibidos_count}");
            echo json_encode(["success" => false, "error" => "Debe seleccionar una opción para todos los factores."]);
            exit();
        }

        $clave_conclusion_calculada = null; 
        if ($puntaje_total < 30) {
            $clave_conclusion_calculada = "clave_B38";
        } else if ($puntaje_total < 45) {
            $clave_conclusion_calculada = "clave_B39";
        } else if ($puntaje_total < 60) {
            $clave_conclusion_calculada = "clave_B40";
        } else { // >= 60
            $clave_conclusion_calculada = "clave_B41";
        }
        $texto_conclusion_calculada = $this->textos_conclusion_mapeo[$clave_conclusion_calculada] ?? "Conclusión no definida para el puntaje.";

        $datosParaGuardar['puntaje_total'] = $puntaje_total;
        $datosParaGuardar['texto_conclusion_generada'] = $texto_conclusion_calculada;

        $resultado_guardado = $this->porterModel->guardarOActualizarAnalisis($id_empresa, $datosParaGuardar);

        if ($resultado_guardado) {
            $_SESSION['mensaje_app'] = "Análisis de Fuerzas de Porter guardado exitosamente.";
            $_SESSION['tipo_mensaje_app'] = "success";
            echo json_encode([
                "success" => true,
                "message" => "Análisis guardado exitosamente.",
                "data" => [
                    "puntaje" => $puntaje_total,
                    "clave_conclusion_calculada" => $clave_conclusion_calculada,
                    "texto_conclusion" => $texto_conclusion_calculada
                ]
            ]);
        } else {
            $_SESSION['mensaje_app'] = "Error al guardar el análisis de Fuerzas de Porter.";
            $_SESSION['tipo_mensaje_app'] = "error";
            error_log("ControladorPorter: PorterModel->guardarOActualizarAnalisis falló.");
            echo json_encode(["success" => false, "error" => "Error al procesar su solicitud. Intente más tarde."]);
        }
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador = new FuerzasPorterController();
    $controlador->guardar();
} else {
    header('Content-Type: application/json');
    http_response_code(405); 
    echo json_encode(["success" => false, "error" => "Método no permitido. Se esperaba una solicitud POST."]);
}

ob_end_flush();
?>