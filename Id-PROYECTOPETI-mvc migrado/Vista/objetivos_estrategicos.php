<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objetivos Estratégicos</title>
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
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .content {
            margin-left: 240px;
            padding: 20px;
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
            justify-content: center;
            gap: 15px;
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

        .objetivos-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
            justify-content: center;
        }

        .mision-column, .objetivos-generales, .objetivos-especificos {
            flex: 1;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .mision-column {
            background-color: #e8f5e8;
            border-color: #4CAF50;
        }

        .objetivos-generales {
            background-color: #e3f2fd;
            border-color: #2196F3;
        }

        .objetivos-especificos {
            background-color: #fff3e0;
            border-color: #FF9800;
        }

        .objetivo-item {
            background-color: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }        .mision-display {
            background-color: rgba(255,255,255,0.8);
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-style: italic;
            color: #333;
        }
        .objetivo-especifico-group {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: rgba(255,255,255,0.5);
        }
        .objetivo-especifico-group h4 {
            margin: 0 0 10px 0;
            padding: 5px;
            background-color: rgba(245,124,0,0.1);
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <?php
    // Obtener datos del usuario desde la sesión
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    // Inicializar variables para evitar warnings
    if (!isset($uen_previa)) $uen_previa = '';
    if (!isset($objetivos_generales_previos)) $objetivos_generales_previos = [];
    if (!isset($objetivos_especificos_previos)) $objetivos_especificos_previos = [];
    if (!isset($plan_id)) $plan_id = $_GET['id_plan'] ?? '';
    if (!isset($mision)) $mision = 'No se ha definido misión';
    
    $empresa = 'Empresa sin nombre'; // Esto se puede obtener de la BD si es necesario
    ?>
    
    <div style="display:flex; min-height:100vh;">
        <!-- Barra lateral -->
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="header">
                <h1>Objetivos Estratégicos</h1>
                <p>Usuario: <?php echo $user ? htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) : 'Invitado'; ?></p>
                <p>Empresa: <?php echo htmlspecialchars($empresa); ?></p>
            </div>

            <div class="container" style="max-width: 1200px;">
                <h2>Paso 4: Definir Objetivos Estratégicos</h2>
                
                <form id="formObjetivos">
                    <input type="hidden" name="id_plan" value="<?php echo htmlspecialchars($plan_id); ?>">
                    <!-- Sección UEN -->
                    <div class="uen-section">
                        <h3>UEN (Unidades Estratégicas de Negocio)</h3>
                        <p style="font-style: italic; color: #666;">En su caso, comente en este apartado las distintas UEN que tiene su empresa</p>                        <div class="form-group">
                            <label for="uen_descripcion">Descripción de UEN:</label>
                            <textarea id="uen_descripcion" name="uen_descripcion" rows="4" required placeholder="Describa las unidades estratégicas de negocio de su empresa..."><?php echo htmlspecialchars($uen_previa); ?></textarea>
                        </div>
                    </div>

            <!-- Contenedor de objetivos -->
            <div class="objetivos-container">
                <!-- Columna Misión -->
                <div class="mision-column">
                    <h3 style="text-align: center; margin-bottom: 15px; color: #2E7D32;">MISIÓN</h3>
                    <div class="mision-display">
                        <?php echo htmlspecialchars($mision); ?>
                    </div>
                </div>                <!-- Columna Objetivos Generales Estratégicos -->
                <div class="objetivos-generales">
                    <h3 style="text-align: center; margin-bottom: 15px; color: #1976D2;">OBJETIVOS GENERALES O ESTRATÉGICOS</h3>                    <div id="objetivosGenerales">
                        <?php if (!empty($objetivos_generales_previos)): ?>
                            <?php foreach ($objetivos_generales_previos as $index => $objetivo): ?>
                                <div class="objetivo-item" data-objetivo="<?= $index + 1 ?>">
                                    <label>Objetivo General <?= $index + 1 ?>:</label>
                                    <textarea name="objetivos_generales[]" rows="3" required placeholder="Defina un objetivo general estratégico..."><?php echo htmlspecialchars($objetivo); ?></textarea>
                                    <?php if ($index > 0): ?>
                                        <button type="button" onclick="eliminarObjetivoGeneral(<?= $index + 1 ?>)" style="background-color: #dc3545; color: white; padding: 3px 8px; border: none; border-radius: 3px; margin-top: 5px; float: right;">Eliminar</button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="objetivo-item" data-objetivo="1">
                                <label>Objetivo General 1:</label>
                                <textarea name="objetivos_generales[]" rows="3" required placeholder="Defina un objetivo general estratégico..."></textarea>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="addObjetivoGeneral" style="background-color: #2196F3; color: white; padding: 5px 10px; border: none; border-radius: 3px; margin-top: 10px;">Agregar Objetivo General</button>
                </div>

                <!-- Columna Objetivos Específicos -->
                <div class="objetivos-especificos">
                    <h3 style="text-align: center; margin-bottom: 15px; color: #F57C00;">OBJETIVOS ESPECÍFICOS</h3>                    <div id="objetivosEspecificos">
                        <?php if (!empty($objetivos_especificos_previos)): ?>
                            <?php foreach ($objetivos_especificos_previos as $general_num => $especificos): ?>
                                <div class="objetivo-especifico-group" data-for-objetivo="<?= $general_num ?>">
                                    <h4 style="color: #F57C00; margin-bottom: 10px;">Para Objetivo General <?= $general_num ?>:</h4>
                                    <?php foreach ($especificos as $index => $especifico): ?>
                                        <div class="objetivo-item">
                                            <label>Objetivo Específico <?= $general_num ?>.<?= $index + 1 ?>:</label>
                                            <textarea name="objetivos_especificos[<?= $general_num ?>][]" rows="2" required placeholder="Objetivo específico para el objetivo general <?= $general_num ?>..."><?php echo htmlspecialchars($especifico); ?></textarea>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="objetivo-especifico-group" data-for-objetivo="1">
                                <h4 style="color: #F57C00; margin-bottom: 10px;">Para Objetivo General 1:</h4>
                                <div class="objetivo-item">
                                    <label>Objetivo Específico 1.1:</label>
                                    <textarea name="objetivos_especificos[1][]" rows="2" required placeholder="Primer objetivo específico para el objetivo general 1..."></textarea>
                                </div>
                                <div class="objetivo-item">
                                    <label>Objetivo Específico 1.2:</label>
                                    <textarea name="objetivos_especificos[1][]" rows="2" required placeholder="Segundo objetivo específico para el objetivo general 1..."></textarea>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>            <div class="buttons" style="margin-top: 30px; text-align: center;">
                <button type="submit" style="background-color: #28a745; color: white; padding: 12px 25px; border: none; border-radius: 5px; margin-right: 10px; font-size: 16px; font-weight: bold;">💾 GUARDAR Y CONTINUAR</button>
                <a href="../Controllers/PlanController.php?action=editarValores&id_plan=<?php echo htmlspecialchars($plan_id); ?>" style="background-color: #6c757d; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin-right: 10px; font-size: 16px;">Anterior</a>
                <a href="home.php" style="background-color: #dc3545; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-size: 16px;">Cancelar</a>
            </div>
        </form>
    </div>
        </div>
    </div><script>
        let objetivoGeneralCount = 1;

        document.getElementById('addObjetivoGeneral').addEventListener('click', function() {
            objetivoGeneralCount++;
            
            // Agregar nuevo objetivo general
            const containerGenerales = document.getElementById('objetivosGenerales');
            const newObjetivoGeneral = document.createElement('div');
            newObjetivoGeneral.className = 'objetivo-item';
            newObjetivoGeneral.setAttribute('data-objetivo', objetivoGeneralCount);
            newObjetivoGeneral.innerHTML = `
                <label>Objetivo General ${objetivoGeneralCount}:</label>
                <textarea name="objetivos_generales[]" rows="3" required placeholder="Defina un objetivo general estratégico..."></textarea>
                <button type="button" onclick="eliminarObjetivoGeneral(${objetivoGeneralCount})" style="background-color: #dc3545; color: white; padding: 3px 8px; border: none; border-radius: 3px; margin-top: 5px; float: right;">Eliminar</button>
            `;
            containerGenerales.appendChild(newObjetivoGeneral);
            
            // Agregar los 2 objetivos específicos correspondientes
            const containerEspecificos = document.getElementById('objetivosEspecificos');
            const newObjetivosEspecificos = document.createElement('div');
            newObjetivosEspecificos.className = 'objetivo-especifico-group';
            newObjetivosEspecificos.setAttribute('data-for-objetivo', objetivoGeneralCount);
            newObjetivosEspecificos.innerHTML = `
                <h4 style="color: #F57C00; margin-bottom: 10px;">Para Objetivo General ${objetivoGeneralCount}:</h4>
                <div class="objetivo-item">
                    <label>Objetivo Específico ${objetivoGeneralCount}.1:</label>
                    <textarea name="objetivos_especificos[${objetivoGeneralCount}][]" rows="2" required placeholder="Primer objetivo específico para el objetivo general ${objetivoGeneralCount}..."></textarea>
                </div>
                <div class="objetivo-item">
                    <label>Objetivo Específico ${objetivoGeneralCount}.2:</label>
                    <textarea name="objetivos_especificos[${objetivoGeneralCount}][]" rows="2" required placeholder="Segundo objetivo específico para el objetivo general ${objetivoGeneralCount}..."></textarea>
                </div>
            `;
            containerEspecificos.appendChild(newObjetivosEspecificos);
        });

        function eliminarObjetivoGeneral(numero) {
            // Eliminar objetivo general
            const objetivoGeneral = document.querySelector(`[data-objetivo="${numero}"]`);
            if (objetivoGeneral) {
                objetivoGeneral.remove();
            }
            
            // Eliminar objetivos específicos correspondientes
            const objetivosEspecificos = document.querySelector(`[data-for-objetivo="${numero}"]`);
            if (objetivosEspecificos) {
                objetivosEspecificos.remove();
            }
            
            // Renumerar objetivos restantes
            renumerarObjetivos();
        }

        function renumerarObjetivos() {
            const objetivosGenerales = document.querySelectorAll('#objetivosGenerales .objetivo-item');
            const objetivosEspecificosGroups = document.querySelectorAll('#objetivosEspecificos .objetivo-especifico-group');
            
            objetivosGenerales.forEach((objetivo, index) => {
                const numero = index + 1;
                objetivo.setAttribute('data-objetivo', numero);
                objetivo.querySelector('label').textContent = `Objetivo General ${numero}:`;
                
                const eliminarBtn = objetivo.querySelector('button[onclick]');
                if (eliminarBtn) {
                    eliminarBtn.setAttribute('onclick', `eliminarObjetivoGeneral(${numero})`);
                }
            });
            
            objetivosEspecificosGroups.forEach((group, index) => {
                const numero = index + 1;
                group.setAttribute('data-for-objetivo', numero);
                group.querySelector('h4').textContent = `Para Objetivo General ${numero}:`;
                
                const labels = group.querySelectorAll('label');
                const textareas = group.querySelectorAll('textarea');
                
                labels[0].textContent = `Objetivo Específico ${numero}.1:`;
                labels[1].textContent = `Objetivo Específico ${numero}.2:`;
                
                textareas[0].name = `objetivos_especificos[${numero}][]`;
                textareas[1].name = `objetivos_especificos[${numero}][]`;
                
                textareas[0].placeholder = `Primer objetivo específico para el objetivo general ${numero}...`;
                textareas[1].placeholder = `Segundo objetivo específico para el objetivo general ${numero}...`;
            });
              objetivoGeneralCount = objetivosGenerales.length;
        }        // Manejar envío del formulario
        document.getElementById('formObjetivos').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Debug: verificar datos
            console.log('Plan ID:', formData.get('id_plan'));
            console.log('UEN:', formData.get('uen_descripcion'));
            console.log('Objetivos Generales:', formData.getAll('objetivos_generales[]'));
            console.log('Objetivos Específicos:', formData.getAll('objetivos_especificos[1][]'));
            
            fetch('../Controllers/PlanController.php?action=guardarObjetivos', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Response text:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        alert('¡Objetivos guardados correctamente!');
                        // Redirigir al siguiente paso
                        window.location.href = 'cadena_valor.php?id_plan=' + document.querySelector('input[name="id_plan"]').value;
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    alert('Error en la respuesta del servidor: ' + text);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar los datos: ' + error.message);
            });
        });
    </script>
</body>
</html>
