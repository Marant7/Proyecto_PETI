<?php
require_once '../config/clsconexion.php';
require_once '../Models/ClsPest.php';

class PestController {
    private $model;

      public function __construct() {
        $this->model = new ClsPest();  
    }

    public function guardarRespuestas($id_empresa, $respuestas) {
        try {
            return $this->model->guardarRespuestas($id_empresa, $respuestas);
        } catch (Exception $e) {
            throw $e; // Re-lanzar la excepción para manejarla en el código de la solicitud POST
        }
    }
}

// Manejo de la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo "No hay usuario en sesión";
        exit();
    }

    $id_empresa = 1; // Cambiar según la lógica de tu aplicación
    $respuestas = [];
    for ($i = 1; $i <= 25; $i++) {
        $key = "pregunta" . $i;
        $respuestas[] = isset($_POST[$key]) ? intval($_POST[$key]) : null;
    }    try {        $controller = new PestController();
        $controller->guardarRespuestas($id_empresa, $respuestas);
        // Redirigir al usuario a pest_nuevo.php con un mensaje de éxito
        header('Location: ../Vista/pest_nuevo.php?success=1');
        exit();
    } catch (Exception $e) {
        // Redirigir a la página original con un mensaje de error
        header('Location: ../Vista/pest_nuevo.php?error=' . urlencode($e->getMessage()));
        exit();
    }
}
