<?php

class ObjetivosController {
    
    public function save() {
        session_start();
        
        // Guardar datos de objetivos en sesión temporalmente
        $_SESSION['temp_objetivos'] = [
            'uen_descripcion' => $_POST['uen_descripcion'] ?? '',
            'objetivos_generales' => $_POST['objetivos_generales'] ?? [],
            'objetivos_especificos' => $_POST['objetivos_especificos'] ?? []
        ];
        
        // Redirigir directamente a cadena_valor.php después de guardar los objetivos
        header('Location: ../Vista/cadena_valor.php');
        exit();
        
        // Mostrar resumen final con todos los datos
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>Plan Estratégico Completado</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
                .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .section { margin-bottom: 25px; padding: 20px; border-radius: 5px; }
                .empresa { background-color: #e8f5e8; border-left: 5px solid #4CAF50; }
                .vision-mision { background-color: #e3f2fd; border-left: 5px solid #2196F3; }
                .valores { background-color: #fff3e0; border-left: 5px solid #FF9800; }
                .objetivos { background-color: #f3e5f5; border-left: 5px solid #9C27B0; }
                h1 { color: #333; text-align: center; margin-bottom: 30px; }
                h3 { color: #333; margin-top: 0; }
                ul { margin: 10px 0; }
                li { margin-bottom: 5px; }
                .btn-home { background-color: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px; display: inline-block; margin-top: 20px; }
                .btn-home:hover { background-color: #218838; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>🎉 ¡Plan Estratégico Completado Exitosamente! 🎉</h1>
                
                <div class='section empresa'>
                    <h3>📋 Datos de la Empresa</h3>
                    <p><strong>Nombre:</strong> " . htmlspecialchars($_SESSION['temp_empresa']['nombre_empresa'] ?? 'No definido') . "</p>
                    <p><strong>Descripción:</strong> " . htmlspecialchars($_SESSION['temp_empresa']['descripcion'] ?? 'No definido') . "</p>
                </div>
                
                <div class='section vision-mision'>
                    <h3>🎯 Visión y Misión</h3>
                    <p><strong>Visión:</strong> " . htmlspecialchars($_SESSION['temp_vision_mision']['vision'] ?? 'No definido') . "</p>
                    <p><strong>Misión:</strong> " . htmlspecialchars($_SESSION['temp_vision_mision']['mision'] ?? 'No definido') . "</p>
                </div>
                
                <div class='section valores'>
                    <h3>⭐ Valores Empresariales</h3>";
                    
        if (!empty($_SESSION['temp_valores'])) {
            echo "<ul>";
            foreach ($_SESSION['temp_valores'] as $valor) {
                echo "<li>" . htmlspecialchars($valor) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No se definieron valores</p>";
        }
        
        echo "       </div>
                
                <div class='section objetivos'>
                    <h3>🚀 Objetivos Estratégicos</h3>
                    <p><strong>UEN (Unidades Estratégicas de Negocio):</strong></p>
                    <p>" . htmlspecialchars($_SESSION['temp_objetivos']['uen_descripcion'] ?? 'No definido') . "</p>
                    
                    <p><strong>Objetivos Generales Estratégicos:</strong></p>";
                    
        if (!empty($_SESSION['temp_objetivos']['objetivos_generales'])) {
            echo "<ul>";
            foreach ($_SESSION['temp_objetivos']['objetivos_generales'] as $index => $objetivo) {
                echo "<li><strong>Objetivo " . ($index + 1) . ":</strong> " . htmlspecialchars($objetivo) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No se definieron objetivos generales</p>";
        }
          echo "          <p><strong>Objetivos Específicos:</strong></p>";
        
        if (!empty($_SESSION['temp_objetivos']['objetivos_especificos'])) {
            foreach ($_SESSION['temp_objetivos']['objetivos_especificos'] as $objetivoGeneralNum => $especificos) {
                echo "<p><strong>Para Objetivo General " . $objetivoGeneralNum . ":</strong></p>";
                echo "<ul>";
                foreach ($especificos as $index => $objetivo) {
                    echo "<li><strong>Objetivo Específico " . $objetivoGeneralNum . "." . ($index + 1) . ":</strong> " . htmlspecialchars($objetivo) . "</li>";
                }
                echo "</ul>";
            }
        } else {
            echo "<p>No se definieron objetivos específicos</p>";
        }
        
        echo "       </div>
                
                <div class='text-center'>
                    <a href='../Vista/cadena_valor.php' class='btn-home' style='background-color:#007bff;'>Siguiente: Cadena de Valor</a>
                    <a href='../Vista/home.php' class='btn-home'>🏠 Volver al Home</a>
                </div>
            </div>
        </body>
        </html>";
        
        // Limpiar datos temporales
        unset($_SESSION['temp_empresa']);
        unset($_SESSION['temp_vision_mision']);
        unset($_SESSION['temp_valores']);
        unset($_SESSION['temp_objetivos']);
    }
}

// Manejar las acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';
    $controller = new ObjetivosController();

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
