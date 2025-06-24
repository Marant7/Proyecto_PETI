<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Recuperar todos los datos de la sesión del nuevo sistema
$plan_temporal = $_SESSION['plan_temporal'] ?? [];

$empresa = $plan_temporal['nuevo_plan'] ?? [];
$visionMision = $plan_temporal['vision_mision'] ?? [];
$valores = $plan_temporal['valores'] ?? [];
$objetivos = $plan_temporal['objetivos'] ?? [];
$cadenaValor = $plan_temporal['cadena_valor'] ?? [];
$matrizBCG = $plan_temporal['matriz_bcg'] ?? $_SESSION['matriz_bcg'] ?? [];
$bcgFortalezas = $_SESSION['bcg_fortalezas'] ?? '';
$bcgDebilidades = $_SESSION['bcg_debilidades'] ?? '';
$fuerzasPorter = $plan_temporal['fuerzas_porter'] ?? $_SESSION['fuerzas_porter'] ?? [];
$pestResultados = $plan_temporal['pest'] ?? $_SESSION['pest_resultados'] ?? [];

function safe($v) { return htmlspecialchars($v ?? ''); }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Final del Plan Estratégico</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section { margin-bottom: 25px; padding: 20px; border-radius: 5px; }
        .empresa { background-color: #e8f5e8; border-left: 5px solid #4CAF50; }
        .vision-mision { background-color: #e3f2fd; border-left: 5px solid #2196F3; }
        .valores { background-color: #fff3e0; border-left: 5px solid #FF9800; }
        .objetivos { background-color: #f3e5f5; border-left: 5px solid #9C27B0; }        .cadena-valor { background-color: #fbe9e7; border-left: 5px solid #FF7043; }
        .matriz-bcg { background-color: #e1f5fe; border-left: 5px solid #0288d1; }
        .pest-analysis { background-color: #f1f8e9; border-left: 5px solid #689f38; }
        h1 { color: #333; text-align: center; margin-bottom: 30px; }
        h3 { color: #333; margin-top: 0; }
        ul { margin: 10px 0; }
        li { margin-bottom: 5px; }
        .btn-home { background-color: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px; display: inline-block; margin-top: 20px; }
        .btn-home:hover { background-color: #218838; }
        .text-center { text-align: center; }
        .subsection { margin-bottom: 10px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 10px; }
        th, td { border: 1px solid #bbb; padding: 6px 10px; text-align: center; }
        th { background: #e0e0e0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Resumen Final del Plan Estratégico</h1>        <div class="section empresa">
            <h3>📋 Datos del Plan</h3>
            <p><strong>Nombre del Plan:</strong> <?= safe($empresa['nombre_plan']) ?></p>
            <p><strong>Empresa:</strong> <?= safe($empresa['empresa']) ?></p>
            <p><strong>Descripción:</strong> <?= safe($empresa['descripcion']) ?></p>
        </div>
        <div class="section vision-mision">
            <h3>🎯 Visión y Misión</h3>
            <p><strong>Visión:</strong> <?= safe($visionMision['vision']) ?></p>
            <p><strong>Misión:</strong> <?= safe($visionMision['mision']) ?></p>
        </div>
        <div class="section valores">
            <h3>⭐ Valores Empresariales</h3>
            <?php if (!empty($valores)): ?>
                <ul>
                    <?php foreach ($valores as $valor): ?>
                        <li><?= safe($valor) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No se definieron valores</p>
            <?php endif; ?>
        </div>
        <div class="section objetivos">
            <h3>🚀 Objetivos Estratégicos</h3>
            <div class="subsection"><strong>UEN:</strong> <?= safe($objetivos['uen_descripcion']) ?></div>
            <div class="subsection"><strong>Objetivos Generales Estratégicos:</strong></div>
            <?php if (!empty($objetivos['objetivos_generales'])): ?>
                <ul>
                    <?php foreach ($objetivos['objetivos_generales'] as $i => $obj): ?>
                        <li><strong>Objetivo <?= $i+1 ?>:</strong> <?= safe($obj) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No se definieron objetivos generales</p>
            <?php endif; ?>
            <div class="subsection"><strong>Objetivos Específicos:</strong></div>
            <?php if (!empty($objetivos['objetivos_especificos'])): ?>
                <?php foreach ($objetivos['objetivos_especificos'] as $gen => $esps): ?>
                    <p><strong>Para Objetivo General <?= $gen ?>:</strong></p>
                    <ul>
                        <?php foreach ($esps as $j => $esp): ?>
                            <li><strong>Objetivo Específico <?= $gen . '.' . ($j+1) ?>:</strong> <?= safe($esp) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No se definieron objetivos específicos</p>
            <?php endif; ?>
        </div>
        <div class="section cadena-valor">
            <h3>🔗 Cadena de Valor</h3>
            <p><strong>Porcentaje de mejora:</strong> <?= safe($cadenaValor['porcentaje']) ?>%</p>
            <div class="subsection"><strong>Reflexión/Observaciones:</strong></div>
            <p><?= nl2br(safe($cadenaValor['resultado'])) ?></p>
            <div class="subsection"><strong>Fortalezas:</strong></div>
            <ul>
                <?php if (!empty($cadenaValor['fortalezas'])): ?>
                    <?php foreach ($cadenaValor['fortalezas'] as $fort): ?>
                        <li><?= safe($fort) ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No se ingresaron fortalezas</li>
                <?php endif; ?>
            </ul>
            <div class="subsection"><strong>Debilidades:</strong></div>
            <ul>
                <?php if (!empty($cadenaValor['debilidades'])): ?>
                    <?php foreach ($cadenaValor['debilidades'] as $deb): ?>
                        <li><?= safe($deb) ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No se ingresaron debilidades</li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="section matriz-bcg">
            <h3>📊 Matriz BCG</h3>
            <?php if (!empty($matrizBCG)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Periodo</th>
                            <th>Años Demanda</th>
                            <th>Ventas</th>
                            <th>% S/ Total</th>
                            <th>TCM</th>
                            <th>PRM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matrizBCG as $fila): ?>
                            <tr>
                                <td><?= safe($fila['producto']) ?></td>
                                <td><?= safe($fila['periodo']) ?></td>
                                <td><?= safe($fila['anios_demanda']) ?></td>
                                <td><?= safe($fila['ventas']) ?></td>
                                <td><?= safe($fila['porcentaje_total']) ?></td>
                                <td><?= safe($fila['tcm']) ?></td>
                                <td><?= safe($fila['prm']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="subsection"><strong>Fortalezas:</strong></div>
                <p><?= nl2br(safe($bcgFortalezas)) ?></p>
                <div class="subsection"><strong>Debilidades:</strong></div>
                <p><?= nl2br(safe($bcgDebilidades)) ?></p>
            <?php else: ?>
                <p>No se ingresaron datos de la matriz BCG.</p>
            <?php endif; ?>
        </div>
        <div class="section fuerzas-porter">
            <h3>📊 Análisis de Fuerzas Porter</h3>
            <?php if (!empty($fuerzasPorter['factores'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Factor</th>
                            <th>Valor Seleccionado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fuerzasPorter['factores'] as $factor => $valor): ?>
                            <tr>
                                <td><?= htmlspecialchars($factor) ?></td>
                                <td><?= htmlspecialchars($valor) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se ingresaron datos en el análisis de Fuerzas Porter.</p>
            <?php endif; ?>
            <div class="subsection">
                <h4>Oportunidades:</h4>
                <?php if (!empty($fuerzasPorter['oportunidades'])): ?>
                    <ul>
                        <?php foreach ($fuerzasPorter['oportunidades'] as $oportunidad): ?>
                            <li><?= htmlspecialchars($oportunidad) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No se ingresaron oportunidades.</p>
                <?php endif; ?>
            </div>
            <div class="subsection">
                <h4>Amenazas:</h4>
                <?php if (!empty($fuerzasPorter['amenazas'])): ?>
                    <ul>
                        <?php foreach ($fuerzasPorter['amenazas'] as $amenaza): ?>
                            <li><?= htmlspecialchars($amenaza) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No se ingresaron amenazas.</p>
                <?php endif; ?>            </div>
        </div>        <div class="section pest-analysis">
            <h3>🌍 Análisis P.E.S.T (Entorno Global)</h3>
            <?php if (!empty($pestResultados)): ?>
                <div class="subsection">
                    <h4>Puntuaciones por Factor:</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Factor</th>
                                <th>Puntuación</th>
                                <th>Porcentaje</th>
                                <th>Evaluación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Demográfico</strong></td>
                                <td><?= $pestResultados['factor1'] ?>/25</td>
                                <td><?= round(($pestResultados['factor1']/25)*100, 1) ?>%</td>
                                <td><?= $pestResultados['factor1'] >= 20 ? 'Excelente' : ($pestResultados['factor1'] >= 15 ? 'Bueno' : ($pestResultados['factor1'] >= 10 ? 'Regular' : 'Desafiante')) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Legal/Político</strong></td>
                                <td><?= $pestResultados['factor2'] ?>/25</td>
                                <td><?= round(($pestResultados['factor2']/25)*100, 1) ?>%</td>
                                <td><?= $pestResultados['factor2'] >= 20 ? 'Excelente' : ($pestResultados['factor2'] >= 15 ? 'Bueno' : ($pestResultados['factor2'] >= 10 ? 'Regular' : 'Desafiante')) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Económico</strong></td>
                                <td><?= $pestResultados['factor3'] ?>/25</td>
                                <td><?= round(($pestResultados['factor3']/25)*100, 1) ?>%</td>
                                <td><?= $pestResultados['factor3'] >= 20 ? 'Excelente' : ($pestResultados['factor3'] >= 15 ? 'Bueno' : ($pestResultados['factor3'] >= 10 ? 'Regular' : 'Desafiante')) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tecnológico</strong></td>
                                <td><?= $pestResultados['factor4'] ?>/25</td>
                                <td><?= round(($pestResultados['factor4']/25)*100, 1) ?>%</td>
                                <td><?= $pestResultados['factor4'] >= 20 ? 'Excelente' : ($pestResultados['factor4'] >= 15 ? 'Bueno' : ($pestResultados['factor4'] >= 10 ? 'Regular' : 'Desafiante')) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Medioambiental</strong></td>
                                <td><?= $pestResultados['factor5'] ?>/25</td>
                                <td><?= round(($pestResultados['factor5']/25)*100, 1) ?>%</td>
                                <td><?= $pestResultados['factor5'] >= 20 ? 'Excelente' : ($pestResultados['factor5'] >= 15 ? 'Bueno' : ($pestResultados['factor5'] >= 10 ? 'Regular' : 'Desafiante')) ?></td>
                            </tr>
                            <tr style="background-color: #f0f0f0;">
                                <td><strong>TOTAL</strong></td>
                                <td><strong><?= $pestResultados['total'] ?>/125</strong></td>
                                <td><strong><?= round(($pestResultados['total']/125)*100, 1) ?>%</strong></td>
                                <td><strong><?= $pestResultados['total'] >= 100 ? 'Excelente' : ($pestResultados['total'] >= 75 ? 'Bueno' : ($pestResultados['total'] >= 50 ? 'Regular' : 'Desafiante')) ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="subsection">
                    <h4>Evaluación General del Entorno:</h4>
                    <?php 
                    $total = $pestResultados['total'];
                    if ($total >= 100): ?>
                        <div style="padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
                            <strong>Excelente (<?= $total ?>/125):</strong> El entorno global es muy favorable para su organización. 
                            Existe un ambiente propicio para el crecimiento y desarrollo. Aproveche las oportunidades presentes 
                            y mantenga la ventaja competitiva.
                        </div>
                    <?php elseif ($total >= 75): ?>                        <div style="padding: 10px; background-color: #cce7ff; border: 1px solid #99d6ff; border-radius: 5px; color: #004085;">
                            <strong>Bueno (<?= $total ?>/125):</strong> El entorno es generalmente favorable con algunas áreas que 
                            requieren atención. La organización puede prosperar con estrategias adecuadas para abordar las áreas 
                            de mejora identificadas.
                        </div>
                    <?php elseif ($total >= 50): ?>
                        <div style="padding: 10px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; color: #856404;">
                            <strong>Regular (<?= $total ?>/125):</strong> El entorno presenta desafíos moderados que requieren 
                            atención estratégica. Es importante desarrollar planes específicos para mitigar los riesgos y 
                            aprovechar las oportunidades disponibles.
                        </div>
                    <?php else: ?>
                        <div style="padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; color: #721c24;">
                            <strong>Desafiante (<?= $total ?>/125):</strong> El entorno presenta significativos retos que requieren 
                            estrategias específicas y cuidadosa planificación. Se recomienda desarrollar planes de contingencia 
                            y buscar asesoramiento especializado.
                        </div>
                    <?php endif; ?>
                </div>                <div class="subsection">
                    <h4>Recomendaciones Estratégicas:</h4>
                    <ul>
                        <?php if ($pestResultados['factor1'] < 15): ?>
                            <li><strong>Factor Demográfico:</strong> Desarrollar estrategias para adaptarse mejor a los cambios demográficos y aprovechar oportunidades del mercado.</li>
                        <?php endif; ?>
                        <?php if ($pestResultados['factor2'] < 15): ?>
                            <li><strong>Factor Legal/Político:</strong> Desarrollar estrategias de gestión de riesgos políticos y fortalecer relaciones gubernamentales.</li>
                        <?php endif; ?>
                        <?php if ($pestResultados['factor3'] < 15): ?>
                            <li><strong>Factor Económico:</strong> Implementar estrategias de diversificación financiera y monitoreo económico constante.</li>
                        <?php endif; ?>
                        <?php if ($pestResultados['factor4'] < 15): ?>
                            <li><strong>Factor Tecnológico:</strong> Invertir en actualización tecnológica y capacitación del personal en nuevas tecnologías.</li>
                        <?php endif; ?>
                        <?php if ($pestResultados['factor5'] < 15): ?>
                            <li><strong>Factor Medioambiental:</strong> Desarrollar políticas de sostenibilidad y adaptación a regulaciones medioambientales.</li>
                        <?php endif; ?>
                        <?php if ($pestResultados['total'] >= 100): ?>
                            <li><strong>Aprovechar fortalezas:</strong> Capitalizar los factores bien evaluados para impulsar el crecimiento.</li>
                            <li><strong>Monitoreo continuo:</strong> Mantener vigilancia sobre cambios en el entorno global.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php else: ?>
                <p>No se ha completado el análisis PEST. <a href="../Controllers/PestController.php">Realizar análisis PEST</a></p>
            <?php endif; ?>
        </div>

        <div class="text-center">
            <a href="home.php" class="btn-home">🏠 Volver al Home</a>
        </div>
    </div>
</body>
</html>
