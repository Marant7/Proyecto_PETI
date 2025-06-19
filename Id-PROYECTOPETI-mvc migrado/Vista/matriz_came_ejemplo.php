<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Verificar que hay un plan temporal activo
if (!isset($_SESSION['plan_temporal'])) {
    header('Location: nuevo_plan.php');
    exit();
}

// ===============================================
// CONSOLIDAR DATOS FODA DE TODAS LAS FUENTES
// ===============================================
$plan_temporal = $_SESSION['plan_temporal'] ?? [];

// Obtener datos de cada módulo
$bcg_data = $plan_temporal['matriz_bcg'] ?? null;
$fuerzas_porter_data = $plan_temporal['fuerzas_porter'] ?? null;
$pest_data = $plan_temporal['pest'] ?? null;
$cadena_valor_data = $plan_temporal['cadena_valor'] ?? null;
$foda_data = $_SESSION['foda_data'] ?? null;

// Consolidar TODAS las fortalezas, debilidades, oportunidades y amenazas
$fortalezas_consolidadas = [];
$debilidades_consolidadas = [];
$oportunidades_consolidadas = [];
$amenazas_consolidadas = [];

// Si ya existen datos FODA directos, mantenerlos como base
if ($foda_data) {
    $fortalezas_consolidadas = $foda_data['fortalezas'] ?? [];
    $debilidades_consolidadas = $foda_data['debilidades'] ?? [];
    $oportunidades_consolidadas = $foda_data['oportunidades'] ?? [];
    $amenazas_consolidadas = $foda_data['amenazas'] ?? [];
}

// Agregar fortalezas y debilidades de Cadena de Valor
if ($cadena_valor_data) {
    if (!empty($cadena_valor_data['fortalezas'])) {
        $fortalezas_consolidadas = array_merge($fortalezas_consolidadas, $cadena_valor_data['fortalezas']);
    }
    if (!empty($cadena_valor_data['debilidades'])) {
        $debilidades_consolidadas = array_merge($debilidades_consolidadas, $cadena_valor_data['debilidades']);
    }
}

// Agregar fortalezas y debilidades de BCG
if ($bcg_data) {
    if (!empty($bcg_data['fortalezas'])) {
        $bcg_fortalezas = is_array($bcg_data['fortalezas']) 
            ? $bcg_data['fortalezas'] 
            : array_filter(explode("\n", $bcg_data['fortalezas']));
        $fortalezas_consolidadas = array_merge($fortalezas_consolidadas, $bcg_fortalezas);
    }
    if (!empty($bcg_data['debilidades'])) {
        $bcg_debilidades = is_array($bcg_data['debilidades']) 
            ? $bcg_data['debilidades'] 
            : array_filter(explode("\n", $bcg_data['debilidades']));
        $debilidades_consolidadas = array_merge($debilidades_consolidadas, $bcg_debilidades);
    }
}

// Agregar oportunidades y amenazas de PEST
if ($pest_data) {
    if (!empty($pest_data['oportunidades'])) {
        $oportunidades_consolidadas = array_merge($oportunidades_consolidadas, $pest_data['oportunidades']);
    }
    if (!empty($pest_data['amenazas'])) {
        $amenazas_consolidadas = array_merge($amenazas_consolidadas, $pest_data['amenazas']);
    }
}

// Agregar oportunidades y amenazas de Porter
if ($fuerzas_porter_data) {
    if (!empty($fuerzas_porter_data['oportunidades'])) {
        $porter_oportunidades = is_array($fuerzas_porter_data['oportunidades']) 
            ? $fuerzas_porter_data['oportunidades'] 
            : array_filter(explode("\n", $fuerzas_porter_data['oportunidades']));
        $oportunidades_consolidadas = array_merge($oportunidades_consolidadas, $porter_oportunidades);
    }
    if (!empty($fuerzas_porter_data['amenazas'])) {
        $porter_amenazas = is_array($fuerzas_porter_data['amenazas']) 
            ? $fuerzas_porter_data['amenazas'] 
            : array_filter(explode("\n", $fuerzas_porter_data['amenazas']));
        $amenazas_consolidadas = array_merge($amenazas_consolidadas, $porter_amenazas);
    }
}

// Limpiar duplicados y vacíos
$fortalezas_consolidadas = array_values(array_filter(array_unique($fortalezas_consolidadas)));
$debilidades_consolidadas = array_values(array_filter(array_unique($debilidades_consolidadas)));
$oportunidades_consolidadas = array_values(array_filter(array_unique($oportunidades_consolidadas)));
$amenazas_consolidadas = array_values(array_filter(array_unique($amenazas_consolidadas)));

// Cargar datos previos de CAME si existen
$came_data = $plan_temporal['matriz_came'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz CAME - Plan Estratégico</title>
    <link rel="stylesheet" href="/public/css/main.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section-title { background: #4CAF50; color: white; font-weight: bold; padding: 15px; margin: 20px 0 10px 0; border-radius: 4px; text-align: center; font-size: 16px; }
        textarea { width: 100%; min-height: 120px; border-radius: 4px; border: 1px solid #ccc; padding: 15px; font-family: inherit; font-size: 14px; }
        
        /* Estilos para la matriz CAME con elementos FODA individuales */
        .came-section { margin-bottom: 40px; }
        .foda-strategy-row { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 25px; 
            margin-bottom: 25px; 
            padding: 20px; 
            background: #f8f9fa; 
            border-radius: 8px; 
            border-left: 5px solid #4CAF50;
        }
        .foda-element { 
            background: white; 
            padding: 15px; 
            border-radius: 6px; 
            border: 1px solid #e0e0e0;
        }
        .foda-element strong { 
            color: #2E7D32; 
            font-size: 14px; 
            display: block; 
            margin-bottom: 10px; 
        }
        .foda-element p { 
            margin: 0; 
            color: #555; 
            font-size: 13px; 
            line-height: 1.4; 
        }
        .strategy-input { 
            display: flex; 
            flex-direction: column; 
        }
        .strategy-input label { 
            font-weight: bold; 
            color: #333; 
            margin-bottom: 8px; 
            font-size: 14px; 
        }
        .strategy-input textarea { 
            border: 2px solid #e0e0e0; 
            transition: border-color 0.3s ease; 
        }
        .strategy-input textarea:focus { 
            border-color: #4CAF50; 
            outline: none; 
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3); 
        }
        .came-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .came-item { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 5px solid #4CAF50; }
        .came-item h3 { margin-top: 0; color: #333; }
        .came-item ul { font-size: 13px; }
        .came-item ul li { margin-bottom: 5px; }
        .btn-primary { background: #4CAF50; color: white; border: none; padding: 15px 30px; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn-primary:hover { background: #45a049; }        .btn-finalizar { 
            background: linear-gradient(45deg, #FF5722, #FF7043); 
            color: white; 
            border: none; 
            padding: 20px 40px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 18px; 
            font-weight: bold; 
            margin-left: 20px; 
            box-shadow: 0 4px 15px rgba(255, 87, 34, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-finalizar:hover { 
            background: linear-gradient(45deg, #E64A19, #FF5722); 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 87, 34, 0.4);
        }
        .btn-finalizar:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        .btn-finalizar:hover:before {
            left: 100%;
        }
        .progress-bar { background: #e0e0e0; height: 10px; border-radius: 5px; margin-bottom: 20px; }
        .progress-fill { background: #4CAF50; height: 100%; border-radius: 5px; width: 100%; }
        .navigation { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e0e0e0; }
    </style>
</head>
<body>
    <div class="container">        <h2>Matriz CAME - Paso Final</h2>
        
        <!-- Barra de progreso -->
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">
            <strong>Paso 10 de 11</strong> - Complete la Matriz CAME y continúe al Resumen Ejecutivo
        </p>
        
        <!-- Alerta explicativa -->
        <div style="background: #E8F5E8; border: 2px solid #4CAF50; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3 style="color: #2E7D32; margin: 0 0 10px 0;">
                🎯 ¡ÚLTIMO PASO! - Finalización del Plan Estratégico
            </h3>
            <p style="margin: 0; color: #2E7D32;">
                <strong>Complete la Matriz CAME y continúe al Resumen Ejecutivo</strong> donde podrá revisar todo su plan antes del guardado final en la base de datos. 
                Una vez finalizado, podrá ver, imprimir y compartir su plan completo.
            </p>
        </div>
        
        <form action="../index.php?controller=PlanEstrategico&action=guardarPaso" method="POST" id="formMatrizCame">
            <input type="hidden" name="paso" value="11">
            <input type="hidden" name="nombre_paso" value="matriz_came">
          <div class="section-title">Matriz CAME</div>
        <p style="text-align: center; margin-bottom: 30px; color: #666;">
            Desarrolle estrategias específicas para cada elemento FODA identificado en los pasos anteriores.
        </p>
          <!-- Mostrar resumen de datos consolidados -->
        <?php if (empty($fortalezas_consolidadas) && empty($debilidades_consolidadas) && empty($oportunidades_consolidadas) && empty($amenazas_consolidadas)): ?>
            <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                <h4 style="color: #856404; margin: 0 0 10px 0;">⚠️ Sin datos FODA</h4>
                <p style="margin: 0; color: #856404;">
                    No se encontraron datos FODA de pasos anteriores. Puede escribir estrategias generales o 
                    <a href="identificacion_de_estrategias.php">regresar para completar el análisis FODA</a>.
                </p>
            </div>
        <?php else: ?>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                <h4 style="color: #333; margin: 0 0 15px 0;">📊 Datos FODA Consolidados</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 14px;">
                    <div><strong>Fortalezas:</strong> <?= count($fortalezas_consolidadas) ?> identificadas</div>
                    <div><strong>Oportunidades:</strong> <?= count($oportunidades_consolidadas) ?> identificadas</div>
                    <div><strong>Debilidades:</strong> <?= count($debilidades_consolidadas) ?> identificadas</div>
                    <div><strong>Amenazas:</strong> <?= count($amenazas_consolidadas) ?> identificadas</div>
                </div>
            </div>
        <?php endif; ?>        
        <!-- SECCIÓN CORREGIR - Debilidades -->
        <?php if (!empty($debilidades_consolidadas)): ?>
        <div class="came-section">
            <h3 style="background: #FF9800; color: white; padding: 15px; margin: 0 0 20px 0; border-radius: 8px;">
                🔧 CORREGIR - Estrategias para las Debilidades
            </h3>
            
            <?php foreach ($debilidades_consolidadas as $index => $debilidad): ?>
            <div class="foda-strategy-row">
                <div class="foda-element">
                    <strong>Debilidad <?= $index + 1 ?>:</strong>
                    <p><?= htmlspecialchars($debilidad) ?></p>
                </div>
                <div class="strategy-input">
                    <label>Estrategia para corregir:</label>
                    <textarea name="corregir_<?= $index ?>" placeholder="¿Cómo corregir esta debilidad específica?"><?= htmlspecialchars($came_data['corregir_' . $index] ?? '') ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- SECCIÓN AFRONTAR - Amenazas -->
        <?php if (!empty($amenazas_consolidadas)): ?>
        <div class="came-section">
            <h3 style="background: #f44336; color: white; padding: 15px; margin: 0 0 20px 0; border-radius: 8px;">
                ⚔️ AFRONTAR - Estrategias para las Amenazas
            </h3>
            
            <?php foreach ($amenazas_consolidadas as $index => $amenaza): ?>
            <div class="foda-strategy-row">
                <div class="foda-element">
                    <strong>Amenaza <?= $index + 1 ?>:</strong>
                    <p><?= htmlspecialchars($amenaza) ?></p>
                </div>
                <div class="strategy-input">
                    <label>Estrategia para afrontar:</label>
                    <textarea name="afrontar_<?= $index ?>" placeholder="¿Cómo afrontar esta amenaza específica?"><?= htmlspecialchars($came_data['afrontar_' . $index] ?? '') ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- SECCIÓN MANTENER - Fortalezas -->
        <?php if (!empty($fortalezas_consolidadas)): ?>
        <div class="came-section">
            <h3 style="background: #4CAF50; color: white; padding: 15px; margin: 0 0 20px 0; border-radius: 8px;">
                💪 MANTENER - Estrategias para las Fortalezas
            </h3>
            
            <?php foreach ($fortalezas_consolidadas as $index => $fortaleza): ?>
            <div class="foda-strategy-row">
                <div class="foda-element">
                    <strong>Fortaleza <?= $index + 1 ?>:</strong>
                    <p><?= htmlspecialchars($fortaleza) ?></p>
                </div>
                <div class="strategy-input">
                    <label>Estrategia para mantener:</label>
                    <textarea name="mantener_<?= $index ?>" placeholder="¿Cómo mantener y potenciar esta fortaleza?"><?= htmlspecialchars($came_data['mantener_' . $index] ?? '') ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <!-- SECCIÓN EXPLOTAR - Oportunidades -->
        <?php if (!empty($oportunidades_consolidadas)): ?>
        <div class="came-section">
            <h3 style="background: #2196F3; color: white; padding: 15px; margin: 0 0 20px 0; border-radius: 8px;">
                🚀 EXPLOTAR - Estrategias para las Oportunidades
            </h3>
            
            <?php foreach ($oportunidades_consolidadas as $index => $oportunidad): ?>
            <div class="foda-strategy-row">
                <div class="foda-element">
                    <strong>Oportunidad <?= $index + 1 ?>:</strong>
                    <p><?= htmlspecialchars($oportunidad) ?></p>
                </div>
                <div class="strategy-input">
                    <label>Estrategia para explotar:</label>
                    <textarea name="explotar_<?= $index ?>" placeholder="¿Cómo explotar esta oportunidad específica?"><?= htmlspecialchars($came_data['explotar_' . $index] ?? '') ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
              <div class="navigation">
                <a href="identificacion_de_estrategias.php" class="btn-primary">← Anterior</a>
                
                <div style="display: flex; flex-direction: column; align-items: flex-end;">
                    <div style="margin-bottom: 10px;">                        <button type="submit" class="btn-primary">💾 Guardar en Sesión</button>
                        <button type="button" class="btn-finalizar" onclick="irAResumenEjecutivo()">
                            📋 CONTINUAR AL RESUMEN EJECUTIVO
                        </button>
                    </div>
                    <small style="color: #666; text-align: right; max-width: 400px;">
                        ⚠️ El último paso es completar el Resumen Ejecutivo antes de finalizar el plan.
                    </small>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Función para guardar paso actual
        document.getElementById('formMatrizCame').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Datos de Matriz CAME guardados en sesión');
                } else {
                    alert('Error al guardar: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión');
            });
        });
          // Función para ir al resumen ejecutivo
        function irAResumenEjecutivo() {
            // Primero guardar CAME en sesión
            const formData = new FormData(document.getElementById('formMatrizCame'));
            
            fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirigir al resumen ejecutivo
                    window.location.href = 'resumen_ejecutivo.php';
                } else {
                    alert('Error al guardar CAME: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar CAME: ' + error.message);
            });
        }
        
        // Mostrar datos previos si existen
        window.addEventListener('load', function() {
            // Aquí puedes cargar datos previos de la sesión si es necesario
            console.log('Matriz CAME cargada - Paso final del plan estratégico');
        });
    </script>
</body>
</html>
