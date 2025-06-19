<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
    $user = $_SESSION['user'];
    
    // Obtener datos previos si existen
    $datos_previos = $_SESSION['plan_temporal']['valores'] ?? null;
    $valores_previos = $datos_previos ?? [];
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Valores Empresariales</title>
  <link rel="stylesheet" href="/public/css/main.css">
  <link rel="stylesheet" href="/public/css/plan-estrategico.css">
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

        <form action="../index.php?controller=PlanEstrategico&action=guardarPaso" method="POST" id="formValores">
            <input type="hidden" name="paso" value="3">
            <input type="hidden" name="nombre_paso" value="valores">            <div id="valoresFields">
                <?php if (!empty($valores_previos)): ?>
                    <?php foreach ($valores_previos as $index => $valor): ?>
                        <div class="form-group">
                            <label for="valor<?= $index + 1 ?>">Valor <?= $index + 1 ?>:</label>
                            <input type="text" name="valores[]" value="<?= htmlspecialchars($valor) ?>" required placeholder="Ingrese un valor empresarial...">
                            <?php if ($index > 0): ?>
                                <button type="button" onclick="this.parentElement.remove()" style="background-color: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; margin-left: 10px;">Eliminar</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="form-group">
                        <label for="valor1">Valor 1:</label>
                        <input type="text" name="valores[]" required placeholder="Ingrese un valor empresarial...">
                    </div>
                <?php endif; ?>
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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .header {
            background-color: #007bff;
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

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
            background-color: #007bff;
            text-align: center;
            cursor: pointer;
        }

        .buttons a.cancel {
            background-color: #dc3545;
        }

        .buttons a:hover, .buttons button:hover {
            opacity: 0.9;
        }

        #addValor {
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #addValor:hover {
            background-color: #218838;
        }
    </style>    <script>
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

        // Manejar envío del formulario
        document.getElementById('formValores').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'objetivos_estrategicos.php';
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