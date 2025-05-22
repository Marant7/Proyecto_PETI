<?php 
ob_start(); 
header('Content-Type: application/json'); 
require_once "../Models/ClsCadenaValor.php"; 
session_start();  

class ControladorCadenaValor {     
    private $modelo;          
    
    public function __construct() {         
        $this->modelo = new ClsCadenaValor();     
    }          
    
    public function guardar() {         
        // Verificar autenticación         
        if (!isset($_SESSION['user_id'])) {             
            error_log("Error: Usuario no autenticado");             
            echo json_encode(["success" => false, "error" => "Usuario no autenticado."]);             
            exit();         
        }                  
        
        $id_usuario = $_SESSION['user_id'];                  
        
        try {             
            // Log para depuración             
            error_log("Datos recibidos: " . print_r($_POST, true));                          
            
            // Verificar que las preguntas existan en el POST             
            $respuestas = [];             
            for ($i = 1; $i <= 25; $i++) {                 
                if (isset($_POST["q$i"])) {                     
                    $respuestas["q$i"] = intval($_POST["q$i"]);                 
                } else {                     
                    error_log("Falta respuesta a la pregunta $i");                     
                    echo json_encode(["success" => false, "error" => "Faltan respuestas a todas las preguntas."]);                     
                    exit();                 
                }             
            }                          
            
            // Verificar si existe el porcentaje             
            if (isset($_POST['porcentaje'])) {                 
                $porcentaje = floatval($_POST['porcentaje']);             
            } else {                 
                error_log("Falta el porcentaje");                 
                echo json_encode(["success" => false, "error" => "Falta el porcentaje de evaluación."]);                 
                exit();             
            }
            
            // Obtener el resultado (textarea)
            $resultado = isset($_POST['resultado']) ? trim($_POST['resultado']) : '';
            
            // Validar que el resultado no esté vacío
            if (empty($resultado)) {
                error_log("Falta el resultado/reflexión");
                echo json_encode(["success" => false, "error" => "Debe completar la reflexión sobre los resultados."]);
                exit();
            }
                          
            // Obtener el ID de la empresa asociada al usuario             
            require_once __DIR__ . '/../config/clsconexion.php';             
            $conexion = (new clsConexion())->getConexion();                          
            
            $stmt = $conexion->prepare("SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?");             
            $stmt->bind_param("i", $id_usuario);             
            $stmt->execute();             
            $resultado_query = $stmt->get_result();                          
            
            if ($fila = $resultado_query->fetch_assoc()) {                 
                $id_empresa = $fila['id_empresa'];                                  
                
                // Llamar al modelo para guardar los datos                 
                $resultado_guardado = $this->modelo->guardarEvaluacion($id_empresa, $respuestas, $porcentaje, $resultado);                                  
                
                if ($resultado_guardado) {                     
                    echo json_encode([
                        "success" => true, 
                        "porcentaje" => $porcentaje,
                        "mensaje" => "Evaluación guardada correctamente"
                    ]);                 
                } else {                     
                    error_log("Error al guardar la evaluación");                     
                    echo json_encode(["success" => false, "error" => "Error al guardar la evaluación."]);                 
                }             
            } else {                 
                error_log("No se encontró la empresa del usuario");                 
                echo json_encode(["success" => false, "error" => "No se encontró la empresa del usuario."]);             
            }         
        } catch (Exception $e) {             
            error_log("Excepción: " . $e->getMessage());             
            echo json_encode(["success" => false, "error" => "Error: " . $e->getMessage()]);         
        }                  
        
        exit();     
    } 
}  

// Ejecutar cuando se recibe una solicitud POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {     
    $controlador = new ControladorCadenaValor();     
    $controlador->guardar(); 
} 
?>