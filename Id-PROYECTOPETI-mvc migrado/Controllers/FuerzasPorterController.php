<?php
// Controlador para la etapa de Fuerzas de Porter (Oportunidades y Amenazas)
class FuerzasPorterController {
    public function mostrarFormulario() {
        session_start();
        // Validar sesión de usuario
        if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
            header('Location: index.php?c=User&a=login');
            exit();
        }
        require_once 'Vista/fuerzas_porter.php';
    }

    public function guardarOportunidadesAmenazas() {
        session_start();
        // Validar sesión de usuario
        if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
            header('Location: index.php?c=User&a=login');
            exit();
        }
        // Guardar datos del formulario en sesión
        $_SESSION['fuerzas_porter'] = [
            'oportunidades' => $_POST['oportunidades'] ?? '',
            'amenazas' => $_POST['amenazas'] ?? ''
        ];
        // Redirigir al resumen final
        header('Location: index.php?c=ResumenFinal&a=mostrarResumen');
        exit();
    }
}
