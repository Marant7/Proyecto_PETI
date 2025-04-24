<?php
require_once '../Controllers/LoginController.php';  // Si estás dentro de la carpeta Vista
// Ya no es necesario aquí, el session_start() ya se ejecutó en LoginController.php
if (isset($_SESSION['error_message'])) {
    echo "<script>Swal.fire('Error', '" . $_SESSION['error_message'] . "', 'error');</script>";
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../public/css/normalize.css">
    <link rel="stylesheet" href="../public/css/sweetalert2.css">
    <link rel="stylesheet" href="../public/css/material.min.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <script src="../public/js/sweetalert2.min.js"></script>
</head>
<body class="cover" style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">

    <div class="container-login" style="max-width: 500px;">
        <p class="text-center" style="font-size: 100px;">
            <i class="zmdi zmdi-account-circle"></i>
        </p>
        <p class="text-center text-condensedLight" style="font-size: 24px;">Inicia Sesión con tu Cuenta</p>

        <form method="POST" action="../Controllers/LoginController.php"> <!-- Aquí se ajusta la ruta -->
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
        <input class="mdl-textfield__input" type="text" name="usuario" id="userName" style="font-size: 18px;" required>
        <label class="mdl-textfield__label" for="userName">Usuario</label>
    </div>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
        <input class="mdl-textfield__input" type="password" name="password" id="pass" style="font-size: 18px;" required>
        <label class="mdl-textfield__label" for="pass">Contraseña</label>
    </div>
    <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect" style="color: #3F51B5; float:right; font-size: 16px;">Iniciar Sesión</button>
</form>
    </div>

</body>
</html>