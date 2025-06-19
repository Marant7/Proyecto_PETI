<?php

class VisionMisionController {
      public function save() {
        session_start();
        
        // Guardar datos de visión y misión en sesión temporalmente
        $_SESSION['temp_vision_mision'] = [
            'vision' => $_POST['vision'] ?? '',
            'mision' => $_POST['mision'] ?? ''
        ];
        
        // Redirigir al siguiente paso: valores
        header('Location: ../Vista/valores.php');
        exit();
    }
}

// Manejar las acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';
    $controller = new VisionMisionController();

    switch ($action) {
        case 'save':
            $controller->save();
            break;

        default:
            echo "Acción no válida.";
            break;
    }
}
?>
