<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Estratégico - <?php echo htmlspecialchars($plan_completo['plan_info']['nombre_plan']); ?></title>
    <link rel="stylesheet" href="/public/css/main.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #4CAF50; padding-bottom: 20px; }
        .section { margin-bottom: 40px; }
        .section-title { background: #4CAF50; color: white; font-weight: bold; padding: 15px; margin: 20px 0 15px 0; border-radius: 4px; font-size: 18px; }
        .subsection-title { background: #f0f0f0; color: #333; font-weight: bold; padding: 10px; margin: 15px 0 10px 0; border-left: 4px solid #4CAF50; font-size: 16px; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .info-card { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #2196F3; }
        .info-label { font-weight: bold; color: #333; margin-bottom: 5px; }
        .info-value { color: #666; line-height: 1.4; }
        .list-item { background: #fff; margin: 8px 0; padding: 15px; border-radius: 4px; border-left: 4px solid #4CAF50; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .btn-back { background: #666; color: white; text-decoration: none; padding: 12px 24px; border-radius: 4px; }
        .btn-print { background: #2196F3; color: white; text-decoration: none; padding: 12px 24px; border-radius: 4px; }
        .btn-edit { background: #FF9800; color: white; text-decoration: none; padding: 12px 24px; border-radius: 4px; }
        .matriz-bcg-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; }
        .bcg-card { background: #e3f2fd; padding: 15px; border-radius: 8px; border-left: 4px solid #2196F3; }
        .table-responsive { overflow-x: auto; margin: 15px 0; }
        .mini-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .mini-table th, .mini-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .mini-table th { background: #f0f0f0; font-weight: bold; }
        .cadena-valor-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; }
        .cv-item { text-align: center; padding: 10px; border-radius: 4px; }
        .cv-si { background: #e8f5e8; color: #2e7d32; }
        .cv-no { background: #ffebee; color: #c62828; }
        .estrategias-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .estrategia-card { padding: 15px; border-radius: 8px; border-left: 4px solid; }
        .tipo-FO { border-left-color: #4CAF50; background: #e8f5e8; }
        .tipo-FA { border-left-color: #FF9800; background: #fff3e0; }
        .tipo-DO { border-left-color: #2196F3; background: #e3f2fd; }
        .tipo-DA { border-left-color: #F44336; background: #ffebee; }
        .came-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .came-item { padding: 20px; border-radius: 8px; border-left: 4px solid #4CAF50; background: #f8f9fa; }
        .no-data { text-align: center; color: #999; font-style: italic; padding: 20px; }
        @media print {
            .header { margin-bottom: 20px; }
            .btn-back, .btn-print, .btn-edit { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>📊 <?php echo htmlspecialchars($plan_completo['plan_info']['nombre_plan']); ?></h1>
                <p style="color: #666; margin: 5px 0;">
                    🏢 <?php echo htmlspecialchars($plan_completo['plan_info']['empresa']); ?> | 
                    📅 Creado: <?php echo date('d/m/Y', strtotime($plan_completo['plan_info']['fecha_creacion'])); ?> |
                    ✅ Estado: <?php echo ucfirst($plan_completo['plan_info']['estado']); ?>
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="listado_planes.php" class="btn-back">← Volver</a>
                <button onclick="window.print()" class="btn-print">🖨️ Imprimir</button>
                <a href="../index.php?controller=PlanEstrategico&action=editarPlan&id=<?php echo $plan_completo['plan_info']['id_plan']; ?>" class="btn-edit">✏️ Editar</a>
            </div>
        </div>

        <!-- INFORMACIÓN BÁSICA -->
        <?php if (!empty($plan_completo['plan_info']['descripcion'])): ?>
        <div class="section">
            <div class="section-title">📝 Descripción del Plan</div>
            <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['plan_info']['descripcion'])); ?></div>
        </div>
        <?php endif; ?>

        <!-- VISIÓN Y MISIÓN -->
        <?php if ($plan_completo['vision_mision']): ?>
        <div class="section">
            <div class="section-title">🎯 Visión y Misión</div>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">🌟 Visión</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['vision_mision']['vision'])); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">🎯 Misión</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['vision_mision']['mision'])); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- VALORES -->
        <?php if (!empty($plan_completo['valores'])): ?>
        <div class="section">
            <div class="section-title">💎 Valores Organizacionales</div>
            <?php foreach ($plan_completo['valores'] as $valor): ?>
                <div class="list-item">
                    <strong><?php echo htmlspecialchars($valor['valor']); ?></strong>
                    <?php if (!empty($valor['descripcion'])): ?>
                        <br><span style="color: #666;"><?php echo htmlspecialchars($valor['descripcion']); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- OBJETIVOS ESTRATÉGICOS -->
        <?php if (!empty($plan_completo['objetivos'])): ?>
        <div class="section">
            <div class="section-title">🎯 Objetivos Estratégicos</div>
            <?php foreach ($plan_completo['objetivos'] as $objetivo): ?>
                <div class="list-item">
                    <?php echo htmlspecialchars($objetivo['objetivo']); ?>
                    <?php if (!empty($objetivo['categoria'])): ?>
                        <br><small style="color: #666;">Categoría: <?php echo htmlspecialchars($objetivo['categoria']); ?></small>
                    <?php endif; ?>
                    <span style="float: right; padding: 2px 8px; background: #e0e0e0; border-radius: 12px; font-size: 11px;">
                        <?php echo ucfirst($objetivo['prioridad']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- CADENA DE VALOR -->
        <?php if ($plan_completo['cadena_valor']): ?>
        <div class="section">
            <div class="section-title">🔗 Análisis de Cadena de Valor</div>
            <div class="cadena-valor-grid">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <div class="cv-item <?php echo $plan_completo['cadena_valor']["q$i"] ? 'cv-si' : 'cv-no'; ?>">
                        <strong>P<?php echo $i; ?></strong><br>
                        <?php echo $plan_completo['cadena_valor']["q$i"] ? 'SÍ' : 'NO'; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- MATRIZ BCG -->
        <?php if ($plan_completo['matriz_bcg']['config']): ?>
        <div class="section">
            <div class="section-title">📈 Matriz BCG</div>
            
            <div class="matriz-bcg-summary">
                <div class="bcg-card">
                    <strong>Configuración</strong><br>
                    Productos: <?php echo $plan_completo['matriz_bcg']['config']['num_productos']; ?><br>
                    Competidores: <?php echo $plan_completo['matriz_bcg']['config']['num_competidores']; ?><br>
                    Período: <?php echo $plan_completo['matriz_bcg']['config']['anio_inicio']; ?>-<?php echo $plan_completo['matriz_bcg']['config']['anio_fin']; ?>
                </div>
            </div>

            <?php if (!empty($plan_completo['matriz_bcg']['productos'])): ?>
                <div class="subsection-title">📦 Productos</div>
                <?php foreach ($plan_completo['matriz_bcg']['productos'] as $producto): ?>
                    <div class="list-item">
                        <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- FORTALEZAS Y DEBILIDADES -->
        <?php if (!empty($plan_completo['fortalezas_debilidades']['fortalezas']) || !empty($plan_completo['fortalezas_debilidades']['debilidades'])): ?>
        <div class="section">
            <div class="section-title">💪 Análisis Interno (Fortalezas y Debilidades)</div>
            
            <div class="info-grid">
                <div class="info-card" style="border-left-color: #4CAF50;">
                    <div class="info-label">💪 Fortalezas</div>
                    <?php if (!empty($plan_completo['fortalezas_debilidades']['fortalezas'])): ?>
                        <?php foreach ($plan_completo['fortalezas_debilidades']['fortalezas'] as $fortaleza): ?>
                            <div style="margin: 8px 0; padding: 8px; background: #e8f5e8; border-radius: 4px;">
                                <?php echo htmlspecialchars($fortaleza['descripcion']); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">No hay fortalezas registradas</div>
                    <?php endif; ?>
                </div>
                
                <div class="info-card" style="border-left-color: #F44336;">
                    <div class="info-label">⚠️ Debilidades</div>
                    <?php if (!empty($plan_completo['fortalezas_debilidades']['debilidades'])): ?>
                        <?php foreach ($plan_completo['fortalezas_debilidades']['debilidades'] as $debilidad): ?>
                            <div style="margin: 8px 0; padding: 8px; background: #ffebee; border-radius: 4px;">
                                <?php echo htmlspecialchars($debilidad['descripcion']); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">No hay debilidades registradas</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- FUERZAS DE PORTER -->
        <?php if ($plan_completo['fuerzas_porter']): ?>
        <div class="section">
            <div class="section-title">⚔️ Análisis de las 5 Fuerzas de Porter</div>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">🏭 Poder de Negociación de Proveedores</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['fuerzas_porter']['poder_negociacion_proveedores'])); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">🛒 Poder de Negociación de Compradores</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['fuerzas_porter']['poder_negociacion_compradores'])); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">🔄 Amenaza de Productos Sustitutos</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['fuerzas_porter']['amenaza_productos_sustitutos'])); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">🚪 Amenaza de Nuevos Competidores</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['fuerzas_porter']['amenaza_nuevos_competidores'])); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">⚡ Rivalidad entre Competidores</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['fuerzas_porter']['rivalidad_competidores'])); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ANÁLISIS PEST -->
        <?php if ($plan_completo['analisis_pest']): ?>
        <div class="section">
            <div class="section-title">🌍 Análisis PEST</div>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">🏛️ Factor Político</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['analisis_pest']['factor_politico'])); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">💰 Factor Económico</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['analisis_pest']['factor_economico'])); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">👥 Factor Social</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['analisis_pest']['factor_social'])); ?></div>
                </div>
                <div class="info-card">
                    <div class="info-label">💻 Factor Tecnológico</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['analisis_pest']['factor_tecnologico'])); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- OPORTUNIDADES Y AMENAZAS -->
        <?php if (!empty($plan_completo['oportunidades_amenazas']['oportunidades']) || !empty($plan_completo['oportunidades_amenazas']['amenazas'])): ?>
        <div class="section">
            <div class="section-title">🌐 Análisis Externo (Oportunidades y Amenazas)</div>
            
            <div class="info-grid">
                <div class="info-card" style="border-left-color: #2196F3;">
                    <div class="info-label">🚀 Oportunidades</div>
                    <?php if (!empty($plan_completo['oportunidades_amenazas']['oportunidades'])): ?>
                        <?php foreach ($plan_completo['oportunidades_amenazas']['oportunidades'] as $oportunidad): ?>
                            <div style="margin: 8px 0; padding: 8px; background: #e3f2fd; border-radius: 4px;">
                                <?php echo htmlspecialchars($oportunidad['descripcion']); ?>
                                <small style="color: #666;">(<?php echo $oportunidad['origen']; ?>)</small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">No hay oportunidades registradas</div>
                    <?php endif; ?>
                </div>
                
                <div class="info-card" style="border-left-color: #FF9800;">
                    <div class="info-label">⚠️ Amenazas</div>
                    <?php if (!empty($plan_completo['oportunidades_amenazas']['amenazas'])): ?>
                        <?php foreach ($plan_completo['oportunidades_amenazas']['amenazas'] as $amenaza): ?>
                            <div style="margin: 8px 0; padding: 8px; background: #fff3e0; border-radius: 4px;">
                                <?php echo htmlspecialchars($amenaza['descripcion']); ?>
                                <small style="color: #666;">(<?php echo $amenaza['origen']; ?>)</small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">No hay amenazas registradas</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ESTRATEGIAS -->
        <?php if (!empty($plan_completo['estrategias'])): ?>
        <div class="section">
            <div class="section-title">🎯 Estrategias Identificadas</div>
            <div class="estrategias-grid">
                <?php foreach ($plan_completo['estrategias'] as $estrategia): ?>
                    <div class="estrategia-card tipo-<?php echo $estrategia['tipo_estrategia']; ?>">
                        <strong><?php echo $estrategia['tipo_estrategia']; ?></strong>
                        <span style="float: right; font-size: 11px; background: rgba(0,0,0,0.1); padding: 2px 6px; border-radius: 8px;">
                            <?php echo ucfirst($estrategia['prioridad']); ?>
                        </span>
                        <br><br>
                        <?php echo nl2br(htmlspecialchars($estrategia['descripcion'])); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- MATRIZ CAME -->
        <?php if ($plan_completo['matriz_came']): ?>
        <div class="section">
            <div class="section-title">🔧 Matriz CAME</div>
            <div class="came-grid">
                <div class="came-item">
                    <div class="info-label">🔧 CORREGIR (Debilidades)</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['matriz_came']['corregir'])); ?></div>
                </div>
                <div class="came-item">
                    <div class="info-label">⚔️ AFRONTAR (Amenazas)</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['matriz_came']['afrontar'])); ?></div>
                </div>
                <div class="came-item">
                    <div class="info-label">💪 MANTENER (Fortalezas)</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['matriz_came']['mantener'])); ?></div>
                </div>
                <div class="came-item">
                    <div class="info-label">🚀 EXPLOTAR (Oportunidades)</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($plan_completo['matriz_came']['explotar'])); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- FOOTER -->
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e0e0e0; color: #666;">
            <p>Plan estratégico generado el <?php echo date('d/m/Y H:i'); ?></p>
        </div>
    </div>
</body>
</html>
