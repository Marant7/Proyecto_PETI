<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    echo "No hay usuario en sesión";
    exit();
}

// Obtener plan_id de la URL o crear uno nuevo
$plan_id = $_GET['id_plan'] ?? null;
if (!$plan_id) {
    echo "Error: Plan ID no especificado";
    exit();
}

// Obtener datos previos si existen
$datos_previos = [];
try {
    require_once __DIR__ . '/../config/clsconexion.php';
    require_once __DIR__ . '/../Models/PlanModel.php';
    
    $db = (new clsConexion())->getConexion();
    $model = new PlanModel($db);
    $datos_previos = $model->obtenerFuerzasPorter($plan_id);
} catch (Exception $e) {
    error_log("Error obteniendo datos previos de Fuerzas Porter: " . $e->getMessage());
}

$perfil_competitivo_data = [
    "titulo_general" => "PERFIL COMPETITIVO",
    "columnas_escala" => ["Nada", "Poco", "Medio", "Alto", "Muy Alto"],
    "secciones" => [
        [
            "titulo_seccion" => "Rivalidad empresas del sector",
            "factores" => [
                ["nombre" => "- Crecimiento", "hostil" => "Lento", "favorable" => "Rápido"],
                ["nombre" => "- Naturaleza de los competidores", "hostil" => "Muchos", "favorable" => "Pocos"],
                ["nombre" => "- Exceso de capacidad productiva", "hostil" => "Sí", "favorable" => "No"],
                ["nombre" => "- Rentabilidad media del sector", "hostil" => "Baja", "favorable" => "Alta"],
                ["nombre" => "- Diferenciación del producto", "hostil" => "Escasa", "favorable" => "Elevada"],
                ["nombre" => "- Barreras de salida", "hostil" => "Bajas", "favorable" => "Altas"],
            ]
        ],
        [
            "titulo_seccion" => "Barreras de Entrada",
            "factores" => [
                ["nombre" => "- Economías de escala", "hostil" => "No", "favorable" => "Sí"],
                ["nombre" => "- Necesidad de capital", "hostil" => "Bajas", "favorable" => "Altas"],
                ["nombre" => "- Acceso a la tecnología", "hostil" => "Fácil", "favorable" => "Difícil"],
                ["nombre" => "- Reglamentos o leyes limitativas", "hostil" => "No", "favorable" => "Sí"],
                ["nombre" => "- Trámites burocráticos", "hostil" => "No", "favorable" => "Sí"],
                ["nombre" => "- Reacción esperada actuales competidores", "hostil" => "Escasa", "favorable" => "Enérgica"],
            ]
        ],
        [
            "titulo_seccion" => "Poder de los Clientes",
            "factores" => [
                ["nombre" => "- Número de clientes", "hostil" => "Pocos", "favorable" => "Muchos"],
                ["nombre" => "- Posibilidad de integración ascendente", "hostil" => "Pequeña", "favorable" => "Grande"],
                ["nombre" => "- Rentabilidad de los clientes", "hostil" => "Baja", "favorable" => "Alta"],
                ["nombre" => "- Coste de cambio de proveedor para cliente", "hostil" => "Bajo", "favorable" => "Alto"],
            ]
        ],
        [
            "titulo_seccion" => "Productos sustitutivos",
            "factores" => [
                ["nombre" => "- Disponibilidad de Productos Sustitutivos", "hostil" => "Grande", "favorable" => "Pequeña"],
            ]
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuerzas Porter - Plan Estratégico</title>
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/plan-estrategico.css">
    <link rel="stylesheet" href="../public/css/fuerzas_porter.css">
    <style>
        .main-content {
            margin-left: 300px;
            padding: 30px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 40px;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #e3f2fd;
        }
        
        .page-header h1 {
            color: #1976d2;
            font-size: 2.2em;
            margin: 0;
            font-weight: 300;
        }
        
        .page-header p {
            color: #666;
            margin: 10px 0 0 0;
            font-size: 1.1em;
        }
        
        .datos-previos-info {
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .datos-previos-info i {
            color: #4caf50;
            font-size: 20px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        th, td { 
            border: 1px solid #e0e0e0; 
            padding: 12px; 
            text-align: center; 
        }
        
        th { 
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: white;
            font-weight: 600;
            font-size: 0.9em;
        }
        
        .section-header { 
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            color: #2e7d32;
            font-weight: bold; 
            text-align: left;
            font-size: 1.1em;
        }
        
        .factor-cell { 
            text-align: left; 
            background: #fafafa;
            padding: 15px;
        }
        
        .factor-cell small {
            display: block;
            margin-top: 8px;
            font-size: 0.85em;
        }
        
        .hostil { 
            color: #d32f2f; 
            font-weight: bold; 
        }
        
        .favorable { 
            color: #1976d2; 
            font-weight: bold; 
        }
        
        input[type="radio"] { 
            width: 18px; 
            height: 18px;
            cursor: pointer;
            transform: scale(1.2);
        }
        
        .conclusion { 
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
            padding: 25px; 
            margin: 30px 0; 
            border-left: 5px solid #9c27b0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .conclusion strong {
            color: #7b1fa2;
            font-size: 1.1em;
        }
        
        .oportunidades-amenazas { 
            margin-top: 40px; 
        }
        
        .oportunidades-amenazas h3 { 
            color: #1976d2; 
            margin-bottom: 20px;
            font-size: 1.4em;
            font-weight: 500;
        }
        
        .oportunidades-amenazas label { 
            font-weight: 600; 
            margin-bottom: 8px; 
            display: block;
            color: #424242;
        }
          .oportunidades-amenazas textarea { 
            width: 100%; 
            min-height: 100px; 
            padding: 15px; 
            border: 2px solid #e0e0e0; 
            border-radius: 8px; 
            margin-bottom: 15px;
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.3s ease;
            resize: vertical;
        }
        
        .textarea-container {
            position: relative;
            margin-bottom: 15px;
        }
        
        .textarea-container textarea {
            width: calc(100% - 50px);
            margin-bottom: 0;
        }
        
        .btn-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(244, 67, 54, 0.3);
        }
        
        .btn-remove:hover {
            background: #d32f2f;
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(244, 67, 54, 0.4);
        }
        
        .oportunidades-amenazas textarea:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }
        
        .btn-add {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }
        
        .btn-save {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }
        
        .btn-save:active {
            transform: translateY(0);
        }
        
        .save-container {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }
        
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        .success {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%) !important;
        }
        
        .estado-completado {
            color: #4caf50 !important;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>📊 Análisis de Fuerzas Porter</h1>
                <p>Evalúa el entorno competitivo de tu empresa</p>
            </div>
            
            <?php if (!empty($datos_previos)): ?>
                <div class="datos-previos-info">
                    <i>ℹ️</i>
                    <div>
                        <strong>Datos Previos Encontrados:</strong> Se han cargado los datos guardados anteriormente. 
                        Puedes modificarlos y guardar los cambios.
                        <br><small><strong>Última actualización:</strong> <?php echo $datos_previos['fecha_guardado'] ?? 'No disponible'; ?></small>
                    </div>
                </div>
            <?php endif; ?>
            
            <form id="formFuerzasPorter">
                <input type="hidden" name="id_plan" value="<?php echo htmlspecialchars($plan_id); ?>">
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40%;">PERFIL COMPETITIVO</th>
                            <th style="width: 10%;">Nada</th>
                            <th style="width: 10%;">Poco</th>
                            <th style="width: 10%;">Medio</th>
                            <th style="width: 10%;">Alto</th>
                            <th style="width: 10%;">Muy Alto</th>
                            <th style="width: 10%;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $factor_index = 0;
                        foreach ($perfil_competitivo_data['secciones'] as $seccion):
                        ?>
                            <tr>
                                <td colspan="7" class="section-header"><?php echo $seccion['titulo_seccion']; ?></td>
                            </tr>
                            <?php foreach ($seccion['factores'] as $factor): ?>
                                <tr>
                                    <td class="factor-cell">
                                        <?php echo $factor['nombre']; ?>
                                        <small>
                                            (<span class="hostil">Hostil: <?php echo $factor['hostil']; ?></span> / 
                                            <span class="favorable">Favorable: <?php echo $factor['favorable']; ?></span>)
                                        </small>
                                    </td>
                                    <?php 
                                    $valor_previo = isset($datos_previos['factores'][$factor_index]) ? $datos_previos['factores'][$factor_index] : null;
                                    ?>
                                    <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="1" class="radio-factor" <?php echo ($valor_previo == 1) ? 'checked' : ''; ?>></td>
                                    <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="2" class="radio-factor" <?php echo ($valor_previo == 2) ? 'checked' : ''; ?>></td>
                                    <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="3" class="radio-factor" <?php echo ($valor_previo == 3) ? 'checked' : ''; ?>></td>
                                    <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="4" class="radio-factor" <?php echo ($valor_previo == 4) ? 'checked' : ''; ?>></td>
                                    <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="5" class="radio-factor" <?php echo ($valor_previo == 5) ? 'checked' : ''; ?>></td>
                                    <td id="estado_<?php echo $factor_index; ?>">-</td>
                                </tr>
                            <?php $factor_index++; endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="conclusion">
                    <strong>CONCLUSIÓN:</strong> <span id="conclusion-text">Seleccione todas las opciones para ver la conclusión.</span><br><br>
                    <strong>Puntuación Total:</strong> <span id="total-score">0</span> puntos
                </div>

                <div class="oportunidades-amenazas">
                    <h3>📈 Oportunidades y Amenazas</h3>
                      <div id="oportunidades-container">
                        <label for="oportunidades">Oportunidades:</label>
                        <?php 
                        $oportunidades_previas = $datos_previos['oportunidades'] ?? [''];
                        foreach ($oportunidades_previas as $index => $oportunidad):
                        ?>
                            <div class="textarea-container">
                                <textarea name="oportunidades[]" placeholder="Ingrese las oportunidades identificadas..."><?php echo htmlspecialchars($oportunidad); ?></textarea>
                                <?php if ($index > 0 || count($oportunidades_previas) > 1): ?>
                                    <button type="button" onclick="eliminarElemento(this)" class="btn-remove">✕</button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" onclick="agregarOportunidad()" class="btn-add">+ Agregar Oportunidad</button>
                      <div id="amenazas-container">
                        <label for="amenazas">Amenazas:</label>
                        <?php 
                        $amenazas_previas = $datos_previos['amenazas'] ?? [''];
                        foreach ($amenazas_previas as $index => $amenaza):
                        ?>
                            <div class="textarea-container">
                                <textarea name="amenazas[]" placeholder="Ingrese las amenazas identificadas..."><?php echo htmlspecialchars($amenaza); ?></textarea>
                                <?php if ($index > 0 || count($amenazas_previas) > 1): ?>
                                    <button type="button" onclick="eliminarElemento(this)" class="btn-remove">✕</button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" onclick="agregarAmenaza()" class="btn-add">+ Agregar Amenaza</button>
                    
                    <div class="save-container">
                        <button type="submit" class="btn-save" id="btnGuardar">
                            💾 GUARDAR Y CONTINUAR
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.radio-factor');
            
            function updateScore() {
                let total = 0;
                let completed = 0;
                
                for(let i = 0; i < <?php echo $factor_index; ?>; i++) {
                    const selectedRadio = document.querySelector(`input[name="factor_${i}"]:checked`);
                    const estadoCell = document.getElementById(`estado_${i}`);
                    
                    if(selectedRadio) {
                        const value = parseInt(selectedRadio.value);
                        total += value;
                        completed++;
                        estadoCell.innerHTML = '<span class="estado-completado">✓</span>';
                    } else {
                        estadoCell.innerHTML = '-';
                    }
                }
                
                document.getElementById('total-score').textContent = total;
                
                let conclusion = '';
                if(total < 30) {
                    conclusion = 'Estamos en un mercado altamente competitivo, en el que es muy difícil hacerse un hueco en el mercado.';
                } else if(total < 45) {
                    conclusion = 'Estamos en un mercado de competitividad relativamente alta, pero con ciertas modificaciones en el producto y la política comercial de la empresa, podría encontrarse un nicho de mercado.';
                } else if(total < 60) {
                    conclusion = 'La situación actual del mercado es favorable a la empresa.';
                } else {
                    conclusion = 'Estamos en una situación excelente para la empresa.';
                }
                
                document.getElementById('conclusion-text').textContent = conclusion;
            }
            
            // Actualizar puntuación al cargar y cuando cambien los valores
            updateScore();
            radios.forEach(radio => {
                radio.addEventListener('change', updateScore);
            });
        });        function agregarOportunidad() {
            const container = document.getElementById('oportunidades-container');
            const div = document.createElement('div');
            div.className = 'textarea-container';
            
            const textarea = document.createElement('textarea');
            textarea.name = 'oportunidades[]';
            textarea.placeholder = 'Ingrese otra oportunidad...';
            
            const btnRemove = document.createElement('button');
            btnRemove.type = 'button';
            btnRemove.className = 'btn-remove';
            btnRemove.innerHTML = '✕';
            btnRemove.onclick = function() { eliminarElemento(this); };
            
            div.appendChild(textarea);
            div.appendChild(btnRemove);
            container.appendChild(div);
        }

        function agregarAmenaza() {
            const container = document.getElementById('amenazas-container');
            const div = document.createElement('div');
            div.className = 'textarea-container';
            
            const textarea = document.createElement('textarea');
            textarea.name = 'amenazas[]';
            textarea.placeholder = 'Ingrese otra amenaza...';
            
            const btnRemove = document.createElement('button');
            btnRemove.type = 'button';
            btnRemove.className = 'btn-remove';
            btnRemove.innerHTML = '✕';
            btnRemove.onclick = function() { eliminarElemento(this); };
            
            div.appendChild(textarea);
            div.appendChild(btnRemove);
            container.appendChild(div);
        }

        function eliminarElemento(button) {
            const container = button.parentElement;
            const parent = container.parentElement;
            
            // Solo eliminar si no es el último elemento
            const textareas = parent.querySelectorAll('.textarea-container');
            if (textareas.length > 1) {
                container.remove();
            } else {
                // Si es el último elemento, solo limpiar el contenido
                const textarea = container.querySelector('textarea');
                textarea.value = '';
            }
        }

        // Manejar envío del formulario con AJAX
        document.getElementById('formFuerzasPorter').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btnGuardar = document.getElementById('btnGuardar');
            const formData = new FormData(this);
            
            // Mostrar estado de carga
            btnGuardar.classList.add('loading');
            btnGuardar.textContent = '⏳ Guardando...';
            btnGuardar.disabled = true;
            
            fetch('../Controllers/PlanController.php?action=guardarFuerzasPorter', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar éxito
                    btnGuardar.classList.add('success');
                    btnGuardar.textContent = '✅ Guardado Exitoso';
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Excelente!',
                        text: 'El análisis de Fuerzas Porter se ha guardado correctamente',
                        confirmButtonText: 'Continuar',
                        confirmButtonColor: '#4CAF50'
                    }).then((result) => {                        if (result.isConfirmed) {
                            // Redirigir al siguiente paso
                            window.location.href = '../Controllers/PlanController.php?action=editarPEST&id_plan=<?php echo $plan_id; ?>';
                        }
                    });
                    
                } else {
                    // Mostrar error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar los datos',
                        confirmButtonColor: '#f44336'
                    });
                    
                    // Restaurar botón
                    btnGuardar.classList.remove('loading');
                    btnGuardar.textContent = '💾 GUARDAR Y CONTINUAR';
                    btnGuardar.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'Error al comunicarse con el servidor',
                    confirmButtonColor: '#f44336'
                });
                
                // Restaurar botón
                btnGuardar.classList.remove('loading');
                btnGuardar.textContent = '💾 GUARDAR Y CONTINUAR';
                btnGuardar.disabled = false;
            });
        });
    </script>
</body>
</html>