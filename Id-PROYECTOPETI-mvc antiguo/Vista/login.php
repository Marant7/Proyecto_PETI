<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body>    <form action="../Controllers/UserController.php?action=login" method="POST">
        <h2>Inicio de Sesión</h2>
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Iniciar Sesión</button>
        
        <p>¿No tienes cuenta? <a href="registerView.php">Regístrate aquí</a></p>
    </form>
</body>
</html>