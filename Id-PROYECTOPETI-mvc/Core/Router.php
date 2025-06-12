<?php
class Router {
    public function handleRequest() {
        // Obtenemos el controlador y la acción desde la URL
        $controllerName = $_GET['controller'] ?? 'Usuario';  // Controlador por defecto
        $action = $_GET['action'] ?? 'index';  // Acción por defecto
        $id = $_GET['id'] ?? null;  // ID si lo hay

        // Ajustar la ruta para que apunte al UsuarioController
        if ($controllerName === 'Login') {
            $controllerName = 'Usuario';
            $action = 'login';
        }

        // Obtener la conexión de la base de datos
        require_once __DIR__ . '/../config/clsConexion.php';
        $db = (new clsConexion())->getConexion();
        
        if (!$db) {
            die("Error al conectar con la base de datos.");
        }

        // Construir el archivo del controlador
        $controllerFile = __DIR__ . "/../Controllers/{$controllerName}Controller.php";

        // Registro de depuración para verificar la ruta del controlador
        error_log("Buscando controlador en: " . $controllerFile);
        if (!file_exists($controllerFile)) {
            error_log("Controlador no encontrado: " . $controllerFile);
        }

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $className = $controllerName . "Controller";

            if (class_exists($className)) {
                $controller = new $className($db);  // Pasamos la conexión a la instancia del controlador
                if (method_exists($controller, $action)) {
                    $controller->$action($id);
                } else {
                    echo "Método no encontrado.";
                }
            } else {
                echo "Clase de controlador no existe.";
            }
        } else {
            echo "Controlador no encontrado.";
        }
    }
}