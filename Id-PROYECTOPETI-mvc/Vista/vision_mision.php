<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visión y Misión</title>
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
        <h1>Definir Visión y Misión</h1>
        <p>Usuario: <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></p>
    </div>

    <div class="container">
        <h2>Paso 2: Visión y Misión de la Empresa</h2>
        
        <form action="../Controllers/VisionMisionController.php?action=save" method="POST">
            <div class="form-group">
                <label for="vision">Visión:</label>
                <textarea id="vision" name="vision" rows="4" placeholder="Describe la visión de la empresa..." required></textarea>
            </div>
            
            <div class="form-group">
                <label for="mision">Misión:</label>
                <textarea id="mision" name="mision" rows="4" placeholder="Describe la misión de la empresa..." required></textarea>
            </div>
            
            <div class="buttons">
                <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">Siguiente</button>
                <a href="nuevo_plan.php" style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;">Anterior</a>
                <a href="home.php" style="background-color: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cancelar</a>
            </div>
        </form>
    </div>

    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group textarea, .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: Arial, sans-serif;
        }
        .buttons {
            margin-top: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</body>
</html>
