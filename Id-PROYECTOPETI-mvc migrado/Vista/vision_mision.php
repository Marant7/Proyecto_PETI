<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visión y Misión</title>
    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/plan-estrategico.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: none;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .buttons a, .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            background-color: #4CAF50;
            text-align: center;
            cursor: pointer;
        }

        .buttons a.cancel {
            background-color: #dc3545;
        }

        .buttons a:hover, .buttons button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body><?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
    $user = $_SESSION['user'];
    
    // Obtener datos previos si existen
    $datos_previos = $_SESSION['plan_temporal']['vision_mision'] ?? null;
    $vision_previa = $datos_previos['vision'] ?? '';
    $mision_previa = $datos_previos['mision'] ?? '';
    ?>
    
    <div class="header">
        <h1>Definir Visión y Misión</h1>
        <p>Usuario: <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></p>
    </div>

    <div class="container">
        <h2>Paso 2: Visión y Misión de la Empresa</h2>
        
        <form action="../index.php?controller=PlanEstrategico&action=guardarPaso" method="POST">
            <input type="hidden" name="paso" value="2">
            <input type="hidden" name="nombre_paso" value="vision_mision">            <div class="form-group">
                <label for="vision">Visión:</label>
                <textarea id="vision" name="vision" rows="4" placeholder="Describe la visión de la empresa..." required><?php echo htmlspecialchars($vision_previa); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="mision">Misión:</label>
                <textarea id="mision" name="mision" rows="4" placeholder="Describe la misión de la empresa..." required><?php echo htmlspecialchars($mision_previa); ?></textarea>
            </div>
              <div class="buttons">
                <button type="submit">Siguiente</button>
                <a href="nuevo_plan.php">Anterior</a>
                <a href="home.php" class="cancel">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'valores.php';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar los datos');
            });
        });
    </script>
</body>
</html>
