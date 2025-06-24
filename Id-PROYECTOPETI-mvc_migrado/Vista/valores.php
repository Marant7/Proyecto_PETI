<?php
// Obtener datos del usuario desde la sesión
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
// Inicializar variables para evitar warnings
if (!isset($valores_previos)) $valores_previos = [];
if (!isset($plan_id)) $plan_id = $_GET['id_plan'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Valores Empresariales</title>
  <link rel="stylesheet" href="/public/css/main.css">
  <link rel="stylesheet" href="/public/css/plan-estrategico.css">
  <link rel="stylesheet" href="../public/css/valores.css">
  <!-- Agregamos Font Awesome para iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div style="display:flex; min-height:100vh;">
        <!-- Barra lateral -->
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="header">
                <h1> Definir Valores Empresariales</h1>
                <p><i class="fas fa-user"></i> Usuario: <?php echo $user ? htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) : 'Invitado'; ?></p>
            </div>

            <div class="container">
                <h2><i class="fas fa-heart"></i> Paso 3: Valores de la Empresa</h2>

                <form id="formValores">
                    <input type="hidden" name="id_plan" value="<?php echo htmlspecialchars($plan_id); ?>">
                    <div id="valoresFields">
                        <?php if (!empty($valores_previos)): ?>
                            <?php foreach ($valores_previos as $index => $valor): ?>
                                <div class="form-group">
                                    <label for="valor<?= $index + 1 ?>">
                                        <i class="fas fa-star"></i> Valor <?= $index + 1 ?>:
                                    </label>
                                    <input type="text" name="valores[]" value="<?= htmlspecialchars($valor) ?>" required placeholder="Ingrese un valor empresarial...">
                                    <?php if ($index > 0): ?>
                                        <button type="button" class="btn btn-eliminar" onclick="this.parentElement.remove()">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="form-group">
                                <label for="valor1">
                                    <i class="fas fa-star"></i> Valor 1:
                                </label>
                                <input type="text" name="valores[]" required placeholder="Ej: Integridad, Excelencia, Compromiso...">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div style="text-align: center;">
                        <button type="button" id="addValor" class="btn">
                            <i class="fas fa-plus"></i> Agregar otro valor
                        </button>
                    </div>
                    
                    <div class="buttons">
                        <button type="submit" class="btn btn-guardar">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="../Controllers/PlanController.php?action=editarVisionMision&id_plan=<?php echo htmlspecialchars($plan_id); ?>" class="btn btn-anterior">
                            <i class="fas fa-arrow-left"></i> Anterior
                        </a>
                        <a href="home.php" class="btn btn-cancelar">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>   

    <script>
        document.getElementById('addValor').addEventListener('click', function() {
            const valoresFields = document.getElementById('valoresFields');
            const valorCount = valoresFields.children.length + 1;
            
            const newDiv = document.createElement('div');
            newDiv.className = 'form-group';
            newDiv.innerHTML = `
                <label for="valor${valorCount}">
                    <i class="fas fa-star"></i> Valor ${valorCount}:
                </label>
                <input type="text" name="valores[]" required placeholder="Ingrese un valor empresarial...">
                <button type="button" class="btn btn-eliminar" onclick="eliminarValor(this)">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            `;
            
            valoresFields.appendChild(newDiv);
            
            // Agregar animación al nuevo elemento
            newDiv.style.opacity = '0';
            newDiv.style.transform = 'translateY(20px)';
            setTimeout(() => {
                newDiv.style.transition = 'all 0.3s ease';
                newDiv.style.opacity = '1';
                newDiv.style.transform = 'translateY(0)';
            }, 10);
        });

        // Función mejorada para eliminar valores con animación
        function eliminarValor(button) {
            const formGroup = button.parentElement;
            formGroup.style.transition = 'all 0.3s ease';
            formGroup.style.transform = 'translateX(-100%)';
            formGroup.style.opacity = '0';
            
            setTimeout(() => {
                formGroup.remove();
            }, 300);
        }

        // Manejar envío del formulario con indicador de carga
        document.getElementById('formValores').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Mostrar indicador de carga
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            
            const formData = new FormData(this);
            
            // Debug: verificar datos
            console.log('Plan ID:', formData.get('id_plan'));
            console.log('Valores:', formData.getAll('valores[]'));
            
            fetch('../Controllers/PlanController.php?action=guardarValores', {
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
                        // Mostrar mensaje de éxito
                        submitBtn.innerHTML = '<i class="fas fa-check"></i> ¡Guardado!';
                        submitBtn.style.background = 'linear-gradient(135deg, #27ae60 0%, #229954 100%)';
                        
                        // Mostrar alerta de éxito
                        mostrarAlerta('¡Valores guardados correctamente!', 'success');
                        
                        // Restaurar botón después de 2 segundos
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('loading');
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Error desconocido');
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    mostrarAlerta('Error en la respuesta del servidor: ' + text, 'error');
                    
                    // Restaurar botón
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('loading');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al guardar los datos: ' + error.message, 'error');
                
                // Restaurar botón
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
            });
        });

        // Función para mostrar alertas elegantes
        function mostrarAlerta(mensaje, tipo) {
            const alerta = document.createElement('div');
            alerta.className = `alerta alerta-${tipo}`;
            alerta.innerHTML = `
                <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                ${mensaje}
            `;
            
            // Estilos para la alerta
            alerta.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                background: ${tipo === 'success' ? 'linear-gradient(135deg, #27ae60 0%, #229954 100%)' : 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)'};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                z-index: 10000;
                font-weight: 600;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            `;
            
            document.body.appendChild(alerta);
            
            // Animación de entrada
            setTimeout(() => {
                alerta.style.transform = 'translateX(0)';
            }, 10);
            
            // Remover después de 5 segundos
            setTimeout(() => {
                alerta.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (alerta.parentNode) {
                        alerta.parentNode.removeChild(alerta);
                    }
                }, 300);
            }, 5000);
        }
    </script>
</body>
</html>