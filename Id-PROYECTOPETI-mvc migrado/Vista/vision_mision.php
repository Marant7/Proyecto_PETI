<?php
// Obtener datos del usuario desde la sesión
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
// Inicializar variables para evitar warnings
if (!isset($vision_previa)) $vision_previa = '';
if (!isset($mision_previa)) $mision_previa = '';
if (!isset($plan_id)) $plan_id = $_GET['id_plan'] ?? '';
?>
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

        .content {
            margin-left: 240px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div style="display:flex; min-height:100vh;">
        <!-- Barra lateral -->
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="header">
                <h1>Definir Visión y Misión</h1>
                <p>Usuario: <?php echo $user ? htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) : 'Invitado'; ?></p>
            </div>

            <div class="container">
                <h2>Paso 2: Visión y Misión de la Empresa</h2>
                
                <form id="formVisionMision">
                    <input type="hidden" name="id_plan" value="<?php echo htmlspecialchars($plan_id); ?>">
                    <div class="form-group">
                        <label for="vision">Visión:</label>
                        <textarea id="vision" name="vision" rows="4" placeholder="Describe la visión de la empresa..." required><?php echo htmlspecialchars($vision_previa); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="mision">Misión:</label>
                        <textarea id="mision" name="mision" rows="4" placeholder="Describe la misión de la empresa..." required><?php echo htmlspecialchars($mision_previa); ?></textarea>
                    </div>                    <div class="buttons">
                        <button type="submit">Guardar</button>
                        <a href="nuevo_plan.php">Anterior</a>
                        <a href="home.php" class="cancel">Cancelar</a>
                    </div>
                </form>
            </div>            <script>
                document.getElementById('formVisionMision').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    
                    // Debug: verificar datos
                    console.log('Plan ID:', formData.get('id_plan'));
                    console.log('Vision:', formData.get('vision'));
                    console.log('Mision:', formData.get('mision'));
                    
                    // Mostrar indicador de carga
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Guardando...';
                    submitBtn.disabled = true;
                    
                    fetch('../Controllers/PlanController.php?action=guardarVisionMision', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.text();
                    })
                    .then(text => {
                        console.log('Response text:', text);
                        
                        // Restaurar botón
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                // Mostrar mensaje de éxito más visible
                                alert('¡✅ Visión y misión guardadas correctamente!');
                                
                                // Opcionalmente, cambiar el color del botón temporalmente
                                submitBtn.style.backgroundColor = '#28a745';
                                submitBtn.textContent = '✅ Guardado exitoso';
                                
                                // Redirigir al siguiente paso después de 2 segundos
                                setTimeout(() => {
                                    window.location.href = '../Controllers/PlanController.php?action=editarValores&id_plan=' + document.querySelector('input[name="id_plan"]').value;
                                }, 2000);
                            } else {
                                alert('❌ Error: ' + data.message);
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            alert('❌ Error en la respuesta del servidor. Revise la consola para más detalles.');
                            console.error('Respuesta completa:', text);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        
                        // Restaurar botón
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        
                        alert('❌ Error al guardar los datos: ' + error.message);
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>
