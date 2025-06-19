<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PestController {
    private $db;

    public function __construct($db = null) {
        $this->db = $db;
    }

    public function index() {
        $this->mostrarFormulario();
    }    public function mostrarFormulario() {
        require_once __DIR__ . '/../Vista/pest_nuevo.php';
    }

    public function mostrarResultados() {
        // Mostrar la página con los resultados
        require_once __DIR__ . '/../Vista/pest_nuevo.php';
    }    public function procesarFormulario() {        if ($_SERVER['REQUEST_METHOD'] === 'POST') {            // Verificar si es una solicitud de guardado de resultados específicos
            if (isset($_POST['save_results'])) {
                // Decodificar las oportunidades y amenazas desde JSON
                $oportunidades = isset($_POST['oportunidades']) ? json_decode($_POST['oportunidades'], true) : [];
                $amenazas = isset($_POST['amenazas']) ? json_decode($_POST['amenazas'], true) : [];
                
                $_SESSION['pest_resultados'] = [
                    'factor1' => intval($_POST['factor1']),
                    'factor2' => intval($_POST['factor2']),
                    'factor3' => intval($_POST['factor3']),
                    'factor4' => intval($_POST['factor4']),
                    'factor5' => intval($_POST['factor5']),
                    'total' => intval($_POST['total']),
                    'oportunidades' => $oportunidades,
                    'amenazas' => $amenazas,
                    'respuestas' => []
                ];
                echo "Resultados guardados exitosamente";
                return;
            }
            
            // Procesar y guardar las respuestas del formulario PEST
            $respuestas = $_POST;
            
            // Calcular puntuaciones por categoría (5 factores con 5 preguntas cada uno)
            $factor1 = 0; // Demográfico
            $factor2 = 0; // Legal/Político
            $factor3 = 0; // Económico
            $factor4 = 0; // Tecnológico
            $factor5 = 0; // Medioambiental
              // Factor 1: Demográfico (preguntas 1-5)
            for ($i = 1; $i <= 5; $i++) {
                if (isset($respuestas["pest_$i"])) {
                    $factor1 += intval($respuestas["pest_$i"]);
                }
            }
            
            // Factor 2: Legal/Político (preguntas 6-10)
            for ($i = 6; $i <= 10; $i++) {
                if (isset($respuestas["pest_$i"])) {
                    $factor2 += intval($respuestas["pest_$i"]);
                }
            }
            
            // Factor 3: Económico (preguntas 11-15)
            for ($i = 11; $i <= 15; $i++) {
                if (isset($respuestas["pest_$i"])) {
                    $factor3 += intval($respuestas["pest_$i"]);
                }
            }
            
            // Factor 4: Tecnológico (preguntas 16-20)
            for ($i = 16; $i <= 20; $i++) {
                if (isset($respuestas["pest_$i"])) {
                    $factor4 += intval($respuestas["pest_$i"]);
                }
            }
            
            // Factor 5: Medioambiental (preguntas 21-25)
            for ($i = 21; $i <= 25; $i++) {
                if (isset($respuestas["pest_$i"])) {
                    $factor5 += intval($respuestas["pest_$i"]);
                }
            }            // Procesar oportunidades y amenazas del formulario regular
            $oportunidades = [];
            $amenazas = [];
            
            // Extraer oportunidades del formulario
            foreach ($respuestas as $key => $value) {
                if (strpos($key, 'oportunidad_') === 0 && !empty($value)) {
                    $oportunidades[] = $value;
                }
                if (strpos($key, 'amenaza_') === 0 && !empty($value)) {
                    $amenazas[] = $value;
                }
            }
            
            // Guardar resultados en la sesión con las nuevas claves
            $_SESSION['pest_resultados'] = [
                'factor1' => $factor1,
                'factor2' => $factor2,
                'factor3' => $factor3,
                'factor4' => $factor4,
                'factor5' => $factor5,
                'total' => $factor1 + $factor2 + $factor3 + $factor4 + $factor5,
                'oportunidades' => $oportunidades,
                'amenazas' => $amenazas,
                'respuestas' => $respuestas
            ];
            
            // Redirigir de vuelta al formulario con los resultados para mostrar la tab de resultados
            header('Location: ../index.php?controller=Pest&action=mostrarResultados');
            exit();
        }
    }
}

// Manejo directo cuando se accede al controlador
if (basename($_SERVER['SCRIPT_NAME']) === 'PestController.php') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new PestController();
        $controller->procesarFormulario();
    } else {
        $controller = new PestController();
        $controller->mostrarFormulario();
    }
}
