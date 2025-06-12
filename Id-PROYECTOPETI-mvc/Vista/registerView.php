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
<body class="cover" style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">

    <!-- Mostrar mensaje de error o éxito -->
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <div class="container-login" style="max-width: 500px;">
      <form method="POST" action="index.php?controller=Usuario&action=register">
        
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" name="nombre" required>
                <label class="mdl-textfield__label" for="nombre">Nombre</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" name="apellido" required>
                <label class="mdl-textfield__label" for="apellido">Apellido</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" name="usuario" required>
                <label class="mdl-textfield__label" for="usuario">Usuario</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="email" name="correo" required>
                <label class="mdl-textfield__label" for="correo">Correo electrónico</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="password" name="password" required>
                <label class="mdl-textfield__label" for="password">Contraseña</label>
            </div>
            <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect">Registrarse</button>
        </form>
        <p class="text-center">¿Ya tienes una cuenta? <a href="index.php?controller=Usuario&action=login">Iniciar sesión</a></p>
    </div>

</body>
</html>
