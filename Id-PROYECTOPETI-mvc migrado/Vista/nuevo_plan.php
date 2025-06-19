<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Plan Estratégico</title>
    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/plan-estrategico.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            line-height: 1.6;
        }
        .header {
            background-color: #f1f1f1;
            border-bottom: 2px solid #333;
            padding: 10px 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            display: flex;
            align-items: center;
        }
        .header h1 i {
            margin-right: 10px;
        }
        .user-info {
            margin-top: 5px;
            font-size: 15px;
            font-style: italic;
            color: #555;
            display: flex;
            align-items: center;
        }
        .user-info i {
            margin-right: 8px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            font-size: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .instrucciones {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 3px solid #4CAF50;
            font-size: 15px;
            color: #555;
        }
        .instrucciones i {
            color: #4CAF50;
            margin-right: 5px;
        }
        .campo {
            margin-bottom: 15px;
        }
        .campo label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .campo label i {
            margin-right: 8px;
            width: 18px;
            color: #555;
        }
        .campo input[type="text"], 
        .campo textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .campo input[type="text"]:focus, 
        .campo textarea:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 4px rgba(76, 175, 80, 0.2);
        }
        .campo textarea {
            height: 100px;
            resize: vertical;
        }
        .botones {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn-guardar {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-guardar:hover {
            background-color: #45a049;
        }
        .btn-cancelar {
            background-color: transparent;
            color: #666;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-cancelar:hover {
            background-color: #f1f1f1;
        }
    </style>
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
        <h1><i class="fas fa-file-alt"></i> Nuevo Plan Estratégico</h1>
        <div class="user-info"><i class="fas fa-user"></i> Usuario: <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></div>
    </div>

    <div class="container">
        <h2>Crear un Nuevo Plan Estratégico</h2>
        
        <div class="instrucciones">
            <i class="fas fa-info-circle"></i> Instrucciones: Complete la información básica del plan estratégico. Estos datos serán utilizados para identificar su plan en el sistema.
        </div>
        
        <form action="../index.php?controller=PlanEstrategico&action=guardarPaso" method="POST" id="formNuevoPlan">
            <input type="hidden" name="paso" value="1">
            <input type="hidden" name="nombre_paso" value="nuevo_plan">
            
            <div class="campo">
                <label for="nombre_plan"><i class="fas fa-tag"></i> Nombre del Plan Estratégico:</label>
                <input type="text" id="nombre_plan" name="nombre_plan" required placeholder="Ej: Plan Estratégico 2025-2030">
            </div>
            
            <div class="campo">
                <label for="empresa"><i class="fas fa-building"></i> Nombre de la Empresa:</label>
                <input type="text" id="empresa" name="empresa" required placeholder="Nombre de su empresa u organización">
            </div>
            
            <div class="campo">
                <label for="descripcion"><i class="fas fa-align-left"></i> Descripción del Plan:</label>
                <textarea id="descripcion" name="descripcion" required placeholder="Describa brevemente el alcance y propósito de este plan estratégico..."></textarea>
            </div>
            
            <div class="botones">
                <button type="submit" class="btn-guardar"><i class="fas fa-save"></i> GUARDAR Y CONTINUAR</button>
                <a href="home.php" class="btn-cancelar"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>    <script>
        document.getElementById('formNuevoPlan').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostrar indicación de guardado
            const btnSubmit = this.querySelector('button[type="submit"]');
            const originalText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            btnSubmit.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'vision_mision.php';
                } else {
                    // Restaurar botón y mostrar error
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restaurar botón y mostrar error
                btnSubmit.innerHTML = originalText;
                btnSubmit.disabled = false;
                alert('Error al guardar los datos');
            });
        });
        
        // Efectos sutiles para campos de formulario
        const inputs = document.querySelectorAll('.campo input, .campo textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('label').style.color = '#4CAF50';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('label').style.color = '#333';
            });
        });
    </script>
</body>
</html>
