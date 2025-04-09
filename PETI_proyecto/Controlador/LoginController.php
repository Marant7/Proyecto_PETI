<?php
// Asegúrate de incluir la clase Usuario y la conexión a la base de datos.
require_once 'Usuario.php';
require_once 'db.php'; // Archivo donde tienes la conexión a la base de datos

// Controlador de Login
class LoginController {

    private $usuarioModel;

    public function __construct($db) {
        $this->usuarioModel = new Usuario($db);
    }

    public function login() {
        // Verificar si el formulario ha sido enviado
        if (isset($_POST['usuario']) && isset($_POST['password'])) {
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];

            // Intentar autenticar al usuario
            $user = $this->usuarioModel->login($usuario, $password);
            
            if ($user) {
                // Iniciar sesión y redirigir al usuario a la página principal
                session_start();
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['apellido'] = $user['apellido'];
                header('Location: /home.php'); // Redirigir a la página de inicio
                exit();
            } else {
                // Si las credenciales no son válidas, mostrar mensaje de error
                echo "<script>Swal.fire('Error', 'Usuario o contraseña incorrectos.', 'error');</script>";
            }
        }
    }
}
?>
