<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header("Location: ../Vista/index.php");
    exit();
}

// Obtener datos del plan temporal
$plan_temporal = $_SESSION['plan_temporal'] ?? [];

// Datos automáticos (cargados desde la sesión)
$nombre_empresa = $plan_temporal['nuevo_plan']['empresa'] ?? 'No especificado';
$fecha_elaboracion = date('d/m/Y');
$mision = $plan_temporal['vision_mision']['mision'] ?? 'No definida';
$vision = $plan_temporal['vision_mision']['vision'] ?? 'No definida';
$valores = $plan_temporal['valores'] ?? [];
$uen_descripcion = $plan_temporal['objetivos']['uen_descripcion'] ?? 'No especificada';

// Procesar objetivos correctamente
$objetivos_generales = [];
$objetivos_especificos = [];

if (isset($plan_temporal['objetivos']['objetivos_generales'])) {
    $obj_gen = $plan_temporal['objetivos']['objetivos_generales'];
    if (is_array($obj_gen)) {
        $objetivos_generales = array_filter($obj_gen);
    }
}

if (isset($plan_temporal['objetivos']['objetivos_especificos'])) {
    $obj_esp = $plan_temporal['objetivos']['objetivos_especificos'];
    if (is_array($obj_esp)) {
        // Los objetivos específicos pueden estar agrupados por objetivo general
        foreach ($obj_esp as $grupo) {
            if (is_array($grupo)) {
                $objetivos_especificos = array_merge($objetivos_especificos, array_filter($grupo));
            } elseif (is_string($grupo) && !empty(trim($grupo))) {
                $objetivos_especificos[] = trim($grupo);
            }
        }
    }
}

// Consolidar datos FODA
$fortalezas = [];
$debilidades = [];
$oportunidades = [];
$amenazas = [];

// Cargar FODA desde todas las fuentes
if (!empty($plan_temporal['cadena_valor']['fortalezas'])) {
    $fortalezas = array_merge($fortalezas, $plan_temporal['cadena_valor']['fortalezas']);
}
if (!empty($plan_temporal['cadena_valor']['debilidades'])) {
    $debilidades = array_merge($debilidades, $plan_temporal['cadena_valor']['debilidades']);
}
if (!empty($plan_temporal['matriz_bcg']['fortalezas'])) {
    $bcg_fortalezas = is_array($plan_temporal['matriz_bcg']['fortalezas']) 
        ? $plan_temporal['matriz_bcg']['fortalezas'] 
        : array_filter(explode("\n", $plan_temporal['matriz_bcg']['fortalezas']));
    $fortalezas = array_merge($fortalezas, $bcg_fortalezas);
}
if (!empty($plan_temporal['matriz_bcg']['debilidades'])) {
    $bcg_debilidades = is_array($plan_temporal['matriz_bcg']['debilidades']) 
        ? $plan_temporal['matriz_bcg']['debilidades'] 
        : array_filter(explode("\n", $plan_temporal['matriz_bcg']['debilidades']));
    $debilidades = array_merge($debilidades, $bcg_debilidades);
}
if (!empty($plan_temporal['pest']['oportunidades'])) {
    $oportunidades = array_merge($oportunidades, $plan_temporal['pest']['oportunidades']);
}
if (!empty($plan_temporal['pest']['amenazas'])) {
    $amenazas = array_merge($amenazas, $plan_temporal['pest']['amenazas']);
}
if (!empty($plan_temporal['fuerzas_porter']['oportunidades'])) {
    $porter_oportunidades = is_array($plan_temporal['fuerzas_porter']['oportunidades']) 
        ? $plan_temporal['fuerzas_porter']['oportunidades'] 
        : array_filter(explode("\n", $plan_temporal['fuerzas_porter']['oportunidades']));
    $oportunidades = array_merge($oportunidades, $porter_oportunidades);
}
if (!empty($plan_temporal['fuerzas_porter']['amenazas'])) {
    $porter_amenazas = is_array($plan_temporal['fuerzas_porter']['amenazas']) 
        ? $plan_temporal['fuerzas_porter']['amenazas'] 
        : array_filter(explode("\n", $plan_temporal['fuerzas_porter']['amenazas']));
    $amenazas = array_merge($amenazas, $porter_amenazas);
}

// Eliminar duplicados
$fortalezas = array_values(array_filter(array_unique($fortalezas)));
$debilidades = array_values(array_filter(array_unique($debilidades)));
$oportunidades = array_values(array_filter(array_unique($oportunidades)));
$amenazas = array_values(array_filter(array_unique($amenazas)));

// Acciones competitivas (estrategias de CAME)
$acciones_competitivas = [];
if (!empty($plan_temporal['matriz_came'])) {
    $came_data = $plan_temporal['matriz_came'];
    
    // Buscar estrategias individuales
    if (!empty($came_data['estrategias_individuales'])) {
        foreach ($came_data['estrategias_individuales'] as $estrategia) {
            if (is_string($estrategia) && !empty(trim($estrategia))) {
                $acciones_competitivas[] = trim($estrategia);
            }
        }
    }
    
    // También buscar en las categorías CAME tradicionales
    $categorias_came = ['corregir', 'afrontar', 'mantener', 'explotar'];
    foreach ($categorias_came as $categoria) {
        if (!empty($came_data[$categoria])) {
            if (is_array($came_data[$categoria])) {
                foreach ($came_data[$categoria] as $estrategia) {
                    if (is_string($estrategia) && !empty(trim($estrategia))) {
                        $acciones_competitivas[] = trim($estrategia);
                    }
                }
            } elseif (is_string($came_data[$categoria]) && !empty(trim($came_data[$categoria]))) {
                $acciones_competitivas[] = trim($came_data[$categoria]);
            }
        }
    }
    
    // Buscar estrategias con patron de nombre específico (corregir_1, afrontar_2, etc.)
    foreach ($came_data as $key => $value) {
        if (preg_match('/^(corregir|afrontar|mantener|explotar)_\d+$/', $key) && 
            is_string($value) && !empty(trim($value))) {
            $acciones_competitivas[] = trim($value);
        }
    }
}

// Cargar datos previos del resumen ejecutivo si existen
$resumen_previo = $plan_temporal['resumen_ejecutivo'] ?? [];
$emprendedores_promotores = $resumen_previo['emprendedores_promotores'] ?? '';
$identificacion_estrategica = $resumen_previo['identificacion_estrategica'] ?? '';
$conclusiones = $resumen_previo['conclusiones'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PETI - Resumen Ejecutivo</title>
    
    <!-- Material Design CSS -->
    <link rel="stylesheet" href="../public/css/material.min.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../public/css/normalize.css">
    <link rel="stylesheet" href="../public/css/main.css">
    
    <style>
        .resumen-section {
            margin-bottom: 30px;
            padding: 20px;
            border-left: 4px solid #2196F3;
            background-color: #f8f9fa;
            border-radius: 0 8px 8px 0;
        }
        .resumen-section h3 {
            color: #2196F3;
            margin-top: 0;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .resumen-section h4 {
            color: #455a64;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .automatico {
            background-color: #e8f5e8;
            border-left-color: #4CAF50;
        }
        .automatico h3 {
            color: #4CAF50;
        }
        .manual {
            background-color: #fff3e0;
            border-left-color: #FF9800;
        }
        .manual h3 {
            color: #FF9800;
        }
        .lista-items {
            padding-left: 0;
            list-style: none;
        }
        .lista-items li {
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
            position: relative;
            padding-left: 25px;
        }
        .lista-items li:before {
            content: "•";
            color: #2196F3;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .campo-readonly {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            color: #666;
            min-height: 60px;
        }
        .btn-guardar {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .btn-guardar:hover {
            background-color: #45a049;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .encabezado {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
        }
        .encabezado h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        .encabezado p {
            margin: 10px 0 0 0;
            font-size: 1.2em;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <section class="full-width pageContent">
        <div class="full-width divider-menu-h"></div>
        
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <div class="full-width panel mdl-shadow--2dp">
                    
                    <!-- Encabezado -->
                    <div class="encabezado">
                        <h1><i class="zmdi zmdi-assignment"></i> Resumen Ejecutivo</h1>
                        <p>Consolidación final del Plan Estratégico de Tecnologías de Información</p>
                    </div>
                      <div class="full-width panel-content">
                        <form id="resumenForm" method="post">
                            <input type="hidden" name="paso" value="11">
                            <input type="hidden" name="nombre_paso" value="resumen_ejecutivo">
                            
                            <!-- Información General (Automática) -->
                            <div class="resumen-section automatico">
                                <h3><i class="zmdi zmdi-info"></i> Información General</h3>
                                <div class="mdl-grid">
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4>Nombre de la Empresa:</h4>
                                        <div class="campo-readonly"><?php echo htmlspecialchars($nombre_empresa); ?></div>
                                    </div>
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4>Fecha de Elaboración:</h4>
                                        <div class="campo-readonly"><?php echo $fecha_elaboracion; ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Emprendedores/Promotores (Manual) -->
                            <div class="resumen-section manual">
                                <h3><i class="zmdi zmdi-accounts"></i> Emprendedores / Promotores</h3>
                                <textarea name="emprendedores_promotores" 
                                          class="mdl-textfield__input" 
                                          rows="4" 
                                          style="width: 100%; padding: 10px; border: 2px solid #FF9800; border-radius: 5px;"
                                          placeholder="Ingrese los nombres y roles de los emprendedores o promotores del proyecto..."><?php echo htmlspecialchars($emprendedores_promotores); ?></textarea>
                            </div>
                            
                            <!-- Misión, Visión y Valores (Automático) -->
                            <div class="resumen-section automatico">
                                <h3><i class="zmdi zmdi-flag"></i> Misión, Visión y Valores</h3>
                                <div class="mdl-grid">
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4>Misión:</h4>
                                        <div class="campo-readonly"><?php echo htmlspecialchars($mision); ?></div>
                                    </div>
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4>Visión:</h4>
                                        <div class="campo-readonly"><?php echo htmlspecialchars($vision); ?></div>
                                    </div>
                                </div>
                                <div style="margin-top: 20px;">
                                    <h4>Valores:</h4>
                                    <div class="campo-readonly">
                                        <?php if (!empty($valores)): ?>
                                            <ul class="lista-items">
                                                <?php foreach ($valores as $valor): ?>
                                                    <li><?php echo htmlspecialchars($valor); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            No se han definido valores
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Unidades Estratégicas (Automático) -->
                            <div class="resumen-section automatico">
                                <h3><i class="zmdi zmdi-business"></i> Unidades Estratégicas</h3>
                                <h4>Descripción UEN:</h4>
                                <div class="campo-readonly"><?php echo htmlspecialchars($uen_descripcion); ?></div>
                            </div>
                            
                            <!-- Objetivos Estratégicos (Automático) -->
                            <div class="resumen-section automatico">
                                <h3><i class="zmdi zmdi-target"></i> Objetivos Estratégicos</h3>
                                <div class="mdl-grid">
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4>Objetivos Generales:</h4>                                        <div class="campo-readonly">
                                            <?php if (!empty($objetivos_generales)): ?>
                                                <ul class="lista-items">
                                                    <?php foreach ($objetivos_generales as $objetivo): ?>
                                                        <?php if (is_string($objetivo) && !empty(trim($objetivo))): ?>
                                                            <li><?php echo htmlspecialchars(trim($objetivo)); ?></li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                No se han definido objetivos generales
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4>Objetivos Específicos:</h4>
                                        <div class="campo-readonly">
                                            <?php if (!empty($objetivos_especificos)): ?>
                                                <ul class="lista-items">
                                                    <?php foreach ($objetivos_especificos as $objetivo): ?>
                                                        <?php if (is_string($objetivo) && !empty(trim($objetivo))): ?>
                                                            <li><?php echo htmlspecialchars(trim($objetivo)); ?></li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                No se han definido objetivos específicos
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Análisis FODA (Automático) -->
                            <div class="resumen-section automatico">
                                <h3><i class="zmdi zmdi-chart"></i> Análisis FODA</h3>
                                <div class="mdl-grid">
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4 style="color: #4CAF50;"><i class="zmdi zmdi-trending-up"></i> Fortalezas:</h4>
                                        <div class="campo-readonly">
                                            <?php if (!empty($fortalezas)): ?>
                                                <ul class="lista-items">
                                                    <?php foreach ($fortalezas as $fortaleza): ?>
                                                        <li><?php echo htmlspecialchars($fortaleza); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                No se han identificado fortalezas
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4 style="color: #FF9800;"><i class="zmdi zmdi-trending-down"></i> Debilidades:</h4>
                                        <div class="campo-readonly">
                                            <?php if (!empty($debilidades)): ?>
                                                <ul class="lista-items">
                                                    <?php foreach ($debilidades as $debilidad): ?>
                                                        <li><?php echo htmlspecialchars($debilidad); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                No se han identificado debilidades
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdl-grid" style="margin-top: 20px;">
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4 style="color: #2196F3;"><i class="zmdi zmdi-plus-circle"></i> Oportunidades:</h4>
                                        <div class="campo-readonly">
                                            <?php if (!empty($oportunidades)): ?>
                                                <ul class="lista-items">
                                                    <?php foreach ($oportunidades as $oportunidad): ?>
                                                        <li><?php echo htmlspecialchars($oportunidad); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                No se han identificado oportunidades
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="mdl-cell mdl-cell--6-col">
                                        <h4 style="color: #F44336;"><i class="zmdi zmdi-alert-triangle"></i> Amenazas:</h4>
                                        <div class="campo-readonly">
                                            <?php if (!empty($amenazas)): ?>
                                                <ul class="lista-items">
                                                    <?php foreach ($amenazas as $amenaza): ?>
                                                        <li><?php echo htmlspecialchars($amenaza); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                No se han identificado amenazas
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Identificación Estratégica (Manual) -->
                            <div class="resumen-section manual">
                                <h3><i class="zmdi zmdi-compass"></i> Identificación Estratégica</h3>
                                <textarea name="identificacion_estrategica" 
                                          class="mdl-textfield__input" 
                                          rows="5" 
                                          style="width: 100%; padding: 10px; border: 2px solid #FF9800; border-radius: 5px;"
                                          placeholder="Describa la identificación estratégica de la organización, su posicionamiento y enfoque principal..."><?php echo htmlspecialchars($identificacion_estrategica); ?></textarea>
                            </div>
                              <!-- Acciones Competitivas (Automático) -->
                            <div class="resumen-section automatico">
                                <h3><i class="zmdi zmdi-settings"></i> Acciones Competitivas (Estrategias de Matriz CAME)</h3>
                                <p style="margin-bottom: 15px; color: #666; font-style: italic;">
                                    Estrategias derivadas del análisis CAME (Corregir, Afrontar, Mantener, Explotar)
                                </p>
                                <div class="campo-readonly">
                                    <?php if (!empty($acciones_competitivas)): ?>
                                        <ul class="lista-items">
                                            <?php foreach (array_unique($acciones_competitivas) as $accion): ?>
                                                <li><?php echo htmlspecialchars($accion); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <small style="color: #4CAF50; margin-top: 10px; display: block;">
                                            <strong>Total de estrategias:</strong> <?php echo count(array_unique($acciones_competitivas)); ?>
                                        </small>
                                    <?php else: ?>
                                        <div style="text-align: center; padding: 20px; color: #999;">
                                            <i class="zmdi zmdi-info" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                                            No se han definido acciones competitivas.<br>
                                            <small>Complete la Matriz CAME para ver las estrategias aquí.</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Conclusiones (Manual) -->
                            <div class="resumen-section manual">
                                <h3><i class="zmdi zmdi-check-circle"></i> Conclusiones</h3>
                                <textarea name="conclusiones" 
                                          class="mdl-textfield__input" 
                                          rows="6" 
                                          style="width: 100%; padding: 10px; border: 2px solid #FF9800; border-radius: 5px;"
                                          placeholder="Escriba las conclusiones principales del Plan Estratégico de TI, recomendaciones y próximos pasos..."><?php echo htmlspecialchars($conclusiones); ?></textarea>
                            </div>
                            
                            <!-- Botón de Guardar -->
                            <div class="text-center" style="margin: 40px 0;">
                                <button type="button" onclick="guardarResumen()" class="btn-guardar">
                                    <i class="zmdi zmdi-save"></i> Guardar Resumen y Finalizar Plan Estratégico
                                </button>
                                <div style="margin-top: 15px;">
                                    <small style="color: #666;">
                                        <strong>Importante:</strong> Al hacer clic en este botón se guardará el resumen ejecutivo y se finalizará el plan estratégico.
                                    </small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DEBUG: Mostrar datos de sesión (temporal) -->
    <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
        <div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 1px solid #ccc;">
            <h3>DEBUG - Datos de Sesión:</h3>
            <h4>Objetivos Generales:</h4>
            <pre><?php print_r($plan_temporal['objetivos']['objetivos_generales'] ?? 'No encontrado'); ?></pre>
            
            <h4>Objetivos Específicos:</h4>
            <pre><?php print_r($plan_temporal['objetivos']['objetivos_especificos'] ?? 'No encontrado'); ?></pre>
            
            <h4>Matriz CAME:</h4>
            <pre><?php print_r($plan_temporal['matriz_came'] ?? 'No encontrado'); ?></pre>
            
            <h4>Acciones Competitivas Procesadas:</h4>
            <pre><?php print_r($acciones_competitivas); ?></pre>
        </div>
    <?php endif; ?>    <script>        function guardarResumen() {
            const form = document.getElementById('resumenForm');
            const formData = new FormData(form);
            
            // Asegurar que los campos ocultos estén presentes
            formData.set('paso', '11');
            formData.set('nombre_paso', 'resumen_ejecutivo');
            
            // Validar campos obligatorios
            const emprendedores = formData.get('emprendedores_promotores').trim();
            const identificacion = formData.get('identificacion_estrategica').trim();
            const conclusiones = formData.get('conclusiones').trim();
            
            if (!emprendedores) {
                alert('Por favor, complete el campo "Emprendedores / Promotores"');
                return;
            }
            
            if (!identificacion) {
                alert('Por favor, complete el campo "Identificación Estratégica"');
                return;
            }
            
            if (!conclusiones) {
                alert('Por favor, complete el campo "Conclusiones"');
                return;
            }
            
            // Mostrar mensaje de carga
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Guardando...';
            button.disabled = true;
              // Enviar datos
            fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ahora finalizar el plan completo
                    return fetch('../index.php?controller=PlanEstrategico&action=finalizarPlan', {
                        method: 'POST'
                    });
                } else {
                    throw new Error('Error al guardar resumen: ' + data.message);
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ¡Resumen ejecutivo guardado y plan estratégico finalizado exitosamente!');
                    window.location.href = 'home.php';
                } else {
                    alert('Error al finalizar el plan: ' + data.message);
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar el resumen ejecutivo.');
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }
    </script>
</body>
</html>
