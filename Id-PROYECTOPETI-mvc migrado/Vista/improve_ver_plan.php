<?php
// Script para agregar IDs y mejorar el análisis PEST en ver_plan.php

$archivo = 'ver_plan.php';
$contenido = file_get_contents($archivo);

// Agregar IDs a las secciones
$secciones = [
    '<!-- Valores -->' => '<!-- Valores --><div class="section" id="valores">',
    '<!-- Unidad Estratégica de Negocio (UEN) -->' => '<!-- Unidad Estratégica de Negocio (UEN) --><div class="section" id="uen">',
    '<!-- Cadena de Valor -->' => '<!-- Cadena de Valor --><div class="section" id="cadena-valor">',
    '<!-- Matriz BCG -->' => '<!-- Matriz BCG --><div class="section" id="matriz-bcg">',
    '<!-- Fuerzas de Porter -->' => '<!-- Fuerzas de Porter --><div class="section" id="fuerzas-porter">',
    '<!-- Análisis PEST -->' => '<!-- Análisis PEST --><div class="section" id="analisis-pest">',
    '<!-- Identificación Estratégica -->' => '<!-- Identificación Estratégica --><div class="section" id="identificacion-estrategica">',
    '<!-- Análisis FODA -->' => '<!-- Análisis FODA --><div class="section" id="analisis-foda">',
    '<!-- Matriz CAME -->' => '<!-- Matriz CAME --><div class="section" id="matriz-came">',
    '<!-- Resumen Ejecutivo -->' => '<!-- Resumen Ejecutivo --><div class="section" id="resumen-ejecutivo">'
];

foreach ($secciones as $buscar => $reemplazar) {
    $contenido = str_replace($buscar . '\n        <div class="section">', $reemplazar, $contenido);
    $contenido = str_replace($buscar . '\n        <?php if ($plan[', $reemplazar . '\n        <?php if ($plan[', $contenido);
}

// Crear sección mejorada de PEST
$pest_mejorado = '
        <!-- Análisis PEST -->
        <div class="section" id="analisis-pest">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="zmdi zmdi-trending-up"></i> Análisis PEST
                </h2>
            </div>
            <div class="section-content">
                <?php if ($plan[\'pest\']): ?>
                    <!-- Factores PEST con valores numéricos -->
                    <div class="pest-grid">
                        <div class="pest-factor politico">
                            <div class="info-label">Factor Político</div>
                            <div class="info-number"><?php echo $plan[\'pest\'][\'factor_politico\'] ?? \'5\'; ?></div>
                            <?php if (!empty($plan[\'pest\'][\'factores_politicos\'])): ?>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($plan[\'pest\'][\'factores_politicos\'])); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="pest-factor economico">
                            <div class="info-label">Factor Económico</div>
                            <div class="info-number"><?php echo $plan[\'pest\'][\'factor_economico\'] ?? \'5\'; ?></div>
                            <?php if (!empty($plan[\'pest\'][\'factores_economicos\'])): ?>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($plan[\'pest\'][\'factores_economicos\'])); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="pest-factor social">
                            <div class="info-label">Factor Social</div>
                            <div class="info-number"><?php echo $plan[\'pest\'][\'factor_social\'] ?? \'5\'; ?></div>
                            <?php if (!empty($plan[\'pest\'][\'factores_sociales\'])): ?>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($plan[\'pest\'][\'factores_sociales\'])); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="pest-factor tecnologico">
                            <div class="info-label">Factor Tecnológico</div>
                            <div class="info-number"><?php echo $plan[\'pest\'][\'factor_tecnologico\'] ?? \'5\'; ?></div>
                            <?php if (!empty($plan[\'pest\'][\'factores_tecnologicos\'])): ?>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($plan[\'pest\'][\'factores_tecnologicos\'])); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Oportunidades y Amenazas del PEST -->
                    <?php if (!empty($plan[\'pest\'][\'oportunidades\']) || !empty($plan[\'pest\'][\'amenazas\'])): ?>
                    <div class="foda-grid" style="margin-top: 25px;">
                        <?php if (!empty($plan[\'pest\'][\'oportunidades\'])): ?>
                        <div class="foda-section oportunidades">
                            <div class="foda-title">Oportunidades (PEST)</div>
                            <?php 
                            $oportunidades_pest = is_string($plan[\'pest\'][\'oportunidades\']) ? 
                                json_decode($plan[\'pest\'][\'oportunidades\'], true) : 
                                $plan[\'pest\'][\'oportunidades\'];
                            
                            if (is_array($oportunidades_pest)): 
                                foreach ($oportunidades_pest as $oportunidad): ?>
                                    <div class="list-item"><?php echo htmlspecialchars($oportunidad); ?></div>
                                <?php endforeach;
                            endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($plan[\'pest\'][\'amenazas\'])): ?>
                        <div class="foda-section amenazas">
                            <div class="foda-title">Amenazas (PEST)</div>
                            <?php 
                            $amenazas_pest = is_string($plan[\'pest\'][\'amenazas\']) ? 
                                json_decode($plan[\'pest\'][\'amenazas\'], true) : 
                                $plan[\'pest\'][\'amenazas\'];
                            
                            if (is_array($amenazas_pest)): 
                                foreach ($amenazas_pest as $amenaza): ?>
                                    <div class="list-item"><?php echo htmlspecialchars($amenaza); ?></div>
                                <?php endforeach;
                            endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="empty-section">
                        <i class="zmdi zmdi-info"></i>
                        El análisis PEST aún no ha sido completado para este plan.
                    </div>
                <?php endif; ?>
            </div>
        </div>';

echo "Script preparado para mejorar ver_plan.php\n";
echo "- Se agregarán IDs a las secciones\n";
echo "- Se mejorará la visualización del análisis PEST\n";
echo "- Se mostrarán todos los datos detallados\n";
?>
