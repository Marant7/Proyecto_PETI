<?php
// Incluir el modelo de Usuario
require_once __DIR__ . '/../Models/Usuario.php'; 

class UsuarioController {
    private $model;

    // Constructor, recibe la conexión a la base de datos
    public function __construct($conexion) {
        $this->model = new Usuario($conexion);
    }

    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ./index.php?controller=Usuario&action=login');
            exit;
        }

        $usuario = $_SESSION['usuario'];
        $nombre = $_SESSION['nombre'];
        $apellido = $_SESSION['apellido'];

        include __DIR__ . '/../Vista/home.php';
        exit;
    }

    public function login() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            header('Location: ./index.php?controller=Usuario&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';

            try {
                $user = $this->model->login($usuario, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user['id_usuario'];
                    $_SESSION['usuario'] = $user['usuario'];
                    $_SESSION['nombre'] = $user['nombre'];
                    $_SESSION['apellido'] = $user['apellido'];

                    header('Location: ./index.php?controller=Usuario&action=index');
                    exit();
                } else {
                    $_SESSION['error_message'] = 'Usuario o contraseña incorrectos.';
                    header('Location: ./index.php?controller=Usuario&action=login');
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Error del sistema: ' . $e->getMessage();
                header('Location: ./index.php?controller=Usuario&action=login');
                exit();
            }
        } else {
            include __DIR__ . '/../Vista/login.php';
            exit();
        }
    }

    public function register() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=Usuario&action=index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
            $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
            $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if (empty($nombre) || empty($apellido) || empty($usuario) || empty($correo) || empty($password)) {
                $error = "Todos los campos son obligatorios";
            } else {
                try {
                    $result = $this->model->register($nombre, $apellido, $usuario, $correo, $password);

                    if ($result) {
                        $_SESSION['success_message'] = 'Usuario registrado correctamente. Ahora puedes iniciar sesión.';
                        header('Location: index.php?controller=Usuario&action=login');
                        exit();
                    } else {
                        $error = "No se pudo registrar el usuario. El nombre de usuario o correo electrónico ya existe.";
                    }
                } catch (Exception $e) {
                    $error = 'Error del sistema: ' . $e->getMessage();
                }
            }
        }

        include __DIR__ . '/../Vista/registerView.php';
        exit;
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        // Agregar un mensaje de éxito antes de redirigir
        $_SESSION['success_message'] = 'Has cerrado sesión exitosamente.';

        header('Location: index.php?controller=Usuario&action=login');
        exit();
    }
}
?>
