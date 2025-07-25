<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Obtener el plan_id de la URL
$plan_id = $_GET['id_plan'] ?? null;

if (!$plan_id) {
    header('Location: home.php');
    exit();
}

// ===============================================
// CONSOLIDAR DATOS FODA DE TODAS LAS FUENTES
// ===============================================

// Obtener datos de la base de datos
$db = (new clsConexion())->getConexion();
$model = new PlanModel($db);

// Obtener datos de cada módulo
$bcg_data = $model->obtenerMatrizBCG($plan_id);
$fuerzas_porter_data = $model->obtenerFuerzasPorter($plan_id);
$pest_data = $model->obtenerPEST($plan_id);
$cadena_valor_data = $model->obtenerCadenaValor($plan_id);
$estrategias_data = $model->obtenerEstrategias($plan_id);

// Decodificar JSON solo para BCG (que devuelve string JSON), los demás ya son arrays
$bcg_data = $bcg_data ? json_decode($bcg_data, true) : [];
// Los demás métodos ya devuelven arrays decodificados
$fuerzas_porter_data = $fuerzas_porter_data ?: [];
$pest_data = $pest_data ?: [];
$cadena_valor_data = $cadena_valor_data ?: [];
$estrategias_data = $estrategias_data ?: [];

// Consolidar TODAS las fortalezas, debilidades, oportunidades y amenazas
$fortalezas_consolidadas = [];
$debilidades_consolidadas = [];
$oportunidades_consolidadas = [];
$amenazas_consolidadas = [];

// Agregar datos FODA de estrategias si existen
if ($estrategias_data && isset($estrategias_data['foda'])) {
    $foda_data = $estrategias_data['foda'];
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
$came_data = $datos_previos ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz CAME - Plan Estratégico</title>
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/matriz_came.css">

</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container" style="margin-left: 240px;">        <h2>Matriz CAME - Paso Final</h2>
        
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
          <form action="../Controllers/PlanController.php?action=guardarMatrizCame" method="POST" id="formMatrizCame">
            <input type="hidden" name="id_plan" value="<?php echo htmlspecialchars($plan_id); ?>">
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
        <?php endif; ?>              <div class="navigation">
                <a href="../Controllers/PlanController.php?action=editarEstrategias&id_plan=<?php echo htmlspecialchars($plan_id); ?>" class="btn-primary">← Anterior</a>
                
                <div style="display: flex; flex-direction: column; align-items: flex-end;">
                    <div style="margin-bottom: 10px;">                        <button type="submit" class="btn-primary">💾 Guardar Matriz CAME</button>
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
    </div>    <script>        // Función para guardar paso actual
        document.getElementById('formMatrizCame').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Mostrar estado de carga
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '⏳ Guardando...';
            submitBtn.disabled = true;
            
            fetch('../Controllers/PlanController.php?action=guardarMatrizCame', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(text => {
                console.log('Response text:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        alert('✅ Matriz CAME guardada correctamente');
                    } else {
                        alert('❌ Error al guardar: ' + data.message);
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    console.error('Response was:', text);
                    alert('❌ Error en la respuesta del servidor: ' + text.substring(0, 200));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Error de conexión: ' + error.message);
            })
            .finally(() => {
                // Restaurar botón
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });        // Función para ir al resumen ejecutivo
        function irAResumenEjecutivo() {
            // Primero guardar CAME
            const formData = new FormData(document.getElementById('formMatrizCame'));
            
            // Mostrar estado de carga
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '⏳ Guardando y continuando...';
            btn.disabled = true;
            
            fetch('../Controllers/PlanController.php?action=guardarMatrizCame', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(text => {
                console.log('Response text:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        // Redirigir al resumen ejecutivo
                        window.location.href = '../Controllers/PlanController.php?action=editarResumenEjecutivo&id_plan=<?php echo htmlspecialchars($plan_id); ?>';
                    } else {
                        alert('❌ Error al guardar CAME: ' + data.message);
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    console.error('Response was:', text);
                    alert('❌ Error en la respuesta del servidor: ' + text.substring(0, 200));
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Error al guardar CAME: ' + error.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
        
        // Mostrar datos previos si existen
        window.addEventListener('load', function() {
            console.log('Matriz CAME cargada - Integrada en el sistema');
        });
    </script>
</body>
</html>
