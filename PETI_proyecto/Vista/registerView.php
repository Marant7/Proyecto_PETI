<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../public/css/normalize.css">
    <link rel="stylesheet" href="../public/css/sweetalert2.css">
    <link rel="stylesheet" href="../public/css/material.min.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <script src="../public/js/sweetalert2.min.js"></script>
</head>
<body  class="cover" style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;" >



    <!-- Mostrar mensaje de error o éxito -->
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
<div class="container-login" style="max-width: 500px;">
    <p class="text-center" style="font-size: 100px;">
            <i class="zmdi zmdi-account-circle"></i>
        </p>
<p class="text-center text-condensedLight" style="font-size: 24px;">Registrarse</p>

<form method="POST" action="UserController.php?action=register">
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">        
        <label class="mdl-textfield__label" for="nombre">Nombre:</label>
        <input class="mdl-textfield__input" type="text" name="nombre" required><br><br>
</div>
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
        <label class="mdl-textfield__label" for="apellido">Apellido:</label>
        <input class="mdl-textfield__input" type="text" name="apellido" required><br><br>
</div>
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
        <label class="mdl-textfield__label" for="usuario">Nombre de usuario:</label>
        <input class="mdl-textfield__input" type="text" name="usuario" required><br><br>
</div>
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
        <label class="mdl-textfield__label" for="correo">Correo electrónico:</label>
        <input class="mdl-textfield__input" type="email" name="correo" required><br><br>
</div>
<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
        <label  class="mdl-textfield__label" for="password">Contraseña:</label>
        <input  class="mdl-textfield__input" type="password" name="password" required><br><br>
</div>
        <input type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect" style="color: #3F51B5; float:right; font-size: 16px;" value="Registrarse">
    </form>
</div>
</div>
</body>
</html>
