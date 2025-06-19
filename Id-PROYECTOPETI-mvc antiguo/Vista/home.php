<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
    $user = $_SESSION['user'];
    ?>    <h1>Bienvenido al Home</h1>
    <p>Has iniciado sesión correctamente, <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?>!</p>
    <p>Usuario: <?php echo htmlspecialchars($user['usuario']); ?></p>
    
    <div style="margin: 20px 0;">
        <a href="nuevo_plan.php" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px;">Nuevo Plan</a>
        <a href="logout.php" style="background-color: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Cerrar Sesión</a>
    </div>
</body>
</html>
