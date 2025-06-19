<?php

class PlanController {
    
    public function create() {
        // Por ahora solo redirigimos al siguiente paso
        // Aquí podrías guardar los datos temporalmente en sesión si fuera necesario
        session_start();
        
        // Guardar datos de la empresa en sesión temporalmente
        $_SESSION['temp_empresa'] = [
            'nombre_empresa' => $_POST['nombre_empresa'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? ''
        ];
        
        header('Location: ../Vista/vision_mision.php');
        exit();
    }
}

// Manejar las acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';
    $controller = new PlanController();

    switch ($action) {
        case 'create':
            $controller->create();
            break;

        default:
            echo "Acción no válida.";
            break;
    }
}
?>
