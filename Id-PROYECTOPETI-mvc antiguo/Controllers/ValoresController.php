<?php

class ValoresController {
      public function save() {
        session_start();
        
        // Guardar datos de valores en sesión temporalmente
        $_SESSION['temp_valores'] = $_POST['valores'] ?? [];
        
        // Redirigir al siguiente paso: objetivos estratégicos
        header('Location: ../Vista/objetivos_estrategicos.php');
        exit();
    }
}

// Manejar las acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';
    $controller = new ValoresController();

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
