<?php
session_start();

require_once '../Models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }    public function register($nombre, $apellido, $usuario, $correo, $password) {
        try {
            if ($this->userModel->register($nombre, $apellido, $usuario, $correo, $password)) {
                // Registro exitoso
                $_SESSION['success_message'] = "Usuario registrado exitosamente. Puede iniciar sesión.";
                header('Location: ../Vista/login.php');
                exit();
            } else {
                throw new Exception("Error desconocido al registrar usuario.");
            }
        } catch (Exception $e) {
            // Guardar mensaje de error en sesión
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../Vista/registerView.php');
            exit();
        }
    }

    public function login($usuario, $password) {        // Debug: verificar que los datos lleguen
        error_log("Intentando login con usuario: " . $usuario);
        
        $user = $this->userModel->login($usuario, $password);
        
        // Debug: verificar resultado del modelo
        error_log("Resultado del login: " . ($user ? "éxito" : "fallo"));
        
        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            error_log("Sesión iniciada, redirigiendo...");
            header('Location: ../Vista/home.php');
            exit();
        } else {
            echo "Usuario o contraseña incorrectos. Debug: usuario='$usuario'";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';
    $controller = new UserController();

    switch ($action) {        case 'register':
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $usuario = $_POST['usuario'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $controller->register($nombre, $apellido, $usuario, $correo, $password);
            break;

        case 'login':
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';
            $controller->login($usuario, $password);
            break;

        default:
            echo "Acción no válida.";
            break;
    }
}
