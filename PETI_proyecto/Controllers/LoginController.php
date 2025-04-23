<?php
session_start();  // Inicia la sesión solo aquí, una vez

// Verificar si el formulario ha sido enviado
if (isset($_POST['usuario']) && isset($_POST['password'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Incluir el modelo de Usuario y la conexión
    require_once '../Models/Usuario.php';  // Incluye el modelo
    require_once '../config/clsconexion.php';  // Incluye la conexión

    // Establecer la conexión a la base de datos
    $db = new clsConexion();
    $conn = $db->getConexion(); // Suponiendo que esta función te devuelve la conexión

    // Comprobar que la conexión es válida
    if ($conn) {
        // Crear una instancia del modelo Usuario
        $usuarioModel = new Usuario($conn);

        // Intentar autenticar al usuario
        $user = $usuarioModel->login($usuario, $password);

        if ($user) {
            // Si el login es exitoso, establecer las variables de sesión
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['apellido'] = $user['apellido'];

            // Redirigir al home.php o la página principal
            header('Location: ../Vista/home.php');  // Ajusta la ruta a la página principal
            exit();  // Asegúrate de detener la ejecución después de la redirección
        } else {
            // Si las credenciales son incorrectas, redirigir a login.php con un mensaje de error
            $_SESSION['error_message'] = 'Usuario o contraseña incorrectos.';
            header('Location: ../Vista/login.php');  // Redirigir al login
            exit();
        }
    } else {
        // Si no se puede establecer la conexión, redirigir a login con un mensaje de error
        $_SESSION['error_message'] = 'No se pudo conectar a la base de datos.';
        header('Location: ../Vista/login.php');  // Redirigir al login
        exit();
    }
}
?>
