<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Validación de usuario (sin base de datos)
    if ($usuario === "admin" && $clave === "admin") {
        $_SESSION['usuario'] = $usuario;
        header("Location: ../Views/home.php");
        exit();
    } else {
        echo "Usuario o contraseña incorrectos. <a href='../Vista/home.php'>Intentar de nuevo</a>";
    }
}
?>
