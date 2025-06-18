<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Plan Estratégico</title>
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
    ?>
    
    <div class="header">
        <h1>Nuevo Plan Estratégico</h1>
        <p>Usuario: <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></p>
    </div>

    <div class="container">
        <h2>Crear un Nuevo Plan Estratégico</h2>        <form action="../Controllers/PlanController.php?action=create" method="POST">
            <div class="form-group">
                <label for="nombre_empresa">Nombre de la Empresa:</label>
                <input type="text" id="nombre_empresa" name="nombre_empresa" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción de la Empresa:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>
              <div class="buttons">
                <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">Siguiente</button>
                <a href="home.php" style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
