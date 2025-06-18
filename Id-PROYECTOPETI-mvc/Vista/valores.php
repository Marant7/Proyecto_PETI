<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Valores Empresariales</title>
  <link rel="stylesheet" href="/public/css/main.css">
</head>
<body>
    <div class="header">
        <h1>Definir Valores Empresariales</h1>
        <p>Usuario: <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></p>
    </div>

    <div class="container">
        <h2>Paso 3: Valores de la Empresa</h2>

        <!-- Mostrar mensaje si viene en GET -->
        <?php if (isset($_GET['msg'])): ?>
            <p style="color: green;"><?= htmlspecialchars($_GET['msg']) ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <form action="../Controllers/ValoresController.php?action=save" method="POST" id="formValores">
            <div id="valoresFields">
                <div class="form-group">
                    <label for="valor1">Valor 1:</label>
                    <input type="text" name="valores[]" required placeholder="Ingrese un valor empresarial...">
                </div>
            </div>
            
            <div style="margin: 15px 0;">
                <button type="button" id="addValor" style="background-color: #007bff; color: white; padding: 8px 15px; border: none; border-radius: 4px;">Agregar otro valor</button>
            </div>
            
            <div class="buttons">
                <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">Siguiente</button>
                <a href="vision_mision.php" style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;">Anterior</a>
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
        .form-group input {
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

    <script>
        document.getElementById('addValor').addEventListener('click', function() {
            const valoresFields = document.getElementById('valoresFields');
            const valorCount = valoresFields.children.length + 1;
            
            const newDiv = document.createElement('div');
            newDiv.className = 'form-group';
            newDiv.innerHTML = `
                <label for="valor${valorCount}">Valor ${valorCount}:</label>
                <input type="text" name="valores[]" required placeholder="Ingrese un valor empresarial...">
                <button type="button" onclick="this.parentElement.remove()" style="background-color: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; margin-left: 10px;">Eliminar</button>
            `;
            
            valoresFields.appendChild(newDiv);
        });
    </script>
</body>
</html>