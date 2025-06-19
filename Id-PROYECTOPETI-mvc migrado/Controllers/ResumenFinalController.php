<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ResumenFinalController {
    public function mostrarResumen() {
        // Recuperar datos de Fuerzas Porter desde la sesión
        $fuerzasPorter = $_SESSION['fuerzas_porter'] ?? [];
        require_once __DIR__ . '/../Vista/resumen_final.php';
    }

    public function guardarFuerzasPorter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Guardar datos marcados y oportunidades/amenazas en sesión
            $factores = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'factor_') === 0) {
                    $factores[$key] = $value;
                }
            }
            $_SESSION['fuerzas_porter'] = [
                'factores' => $factores,
                'oportunidades' => $_POST['oportunidades'] ?? [],
                'amenazas' => $_POST['amenazas'] ?? []
            ];
            // Redirigir al resumen
            header('Location: index.php?controller=ResumenFinal&action=mostrarResumen');
            exit();
        }
    }
}
