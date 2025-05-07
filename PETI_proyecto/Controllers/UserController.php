<?php
class UserController {
    private $model;

    public function __construct($db) {
        $this->model = new UserModel($db);
    }

    // Función para manejar el registro de usuario
    public function register() {
        // Verificar si el formulario fue enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recoger los datos del formulario
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $usuario = $_POST["usuario"];
            $correo = $_POST["correo"];
            $password = $_POST["password"];

            // Validaciones básicas
            if (empty($nombre) || empty($apellido) || empty($usuario) || empty($correo) || empty($password)) {
                $error = "Todos los campos son obligatorios.";
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $error = "Correo electrónico inválido.";
            } elseif ($this->model->userExists($usuario)) {
                $error = "El nombre de usuario ya está en uso.";
            } else {
                // Registrar usuario si no hay errores
                if ($this->model->registerUser($nombre, $apellido, $usuario, $correo, $password)) {
                    $success = "Registro exitoso. Puedes iniciar sesión.";
                } else {
                    $error = "Hubo un error al registrar el usuario. Intenta de nuevo.";
                }
            }

            // Cargar la vista con los mensajes de error o éxito
            include("views/registerView.php");
        } else {
            // Si no se envió el formulario, solo mostrar el formulario de registro
            include("views/registerView.php");
        }
    }
}
?>
