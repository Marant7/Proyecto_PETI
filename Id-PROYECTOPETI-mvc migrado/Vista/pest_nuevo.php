<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header("Location: ../Vista/index.php");
    exit();
}

// Cargar datos previos de PEST si existen
$pest_data = $_SESSION['plan_temporal']['pest'] ?? null;
$oportunidades_previas = $pest_data['oportunidades'] ?? [];
$amenazas_previas = $pest_data['amenazas'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PETI - Análisis PEST</title>
    
    <!-- Material Design CSS -->
    <link rel="stylesheet" href="../public/css/material.min.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../public/css/normalize.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/plan-estrategico.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- pageContent -->
    <section class="full-width pageContent">
        <section class="full-width header-well">
            <div class="full-width header-well-icon">
                <i class="zmdi zmdi-balance-wallet"></i>
            </div>
            <div class="full-width header-well-text">
                <p class="text-condensedLight">
                    AUTODIAGNOSTICO ENTORNO GLOBAL P.E.S.T
                </p>
            </div>
        </section>

        <div class="full-width divider-menu-h"></div>

        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <div class="full-width panel mdl-shadow--2dp">
                    <div class="full-width panel-tittle bg-primary text-center tittles">
                        Análisis P.E.S.T
                    </div>
                    <div class="full-width panel-content">
                        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                            <div class="mdl-tabs__tab-bar">
                                <a href="#formulario-tab" class="mdl-tabs__tab is-active">Formulario</a>
                                <a href="#resultados-tab" class="mdl-tabs__tab" id="resultados-tab-link">Resultados</a>
                            </div>

                            <!-- Tab de Formulario -->
                            <div class="mdl-tabs__panel is-active" id="formulario-tab">
                                <form class="pest-form full-width" method="post" action="../index.php?controller=PlanEstrategico&action=guardarPaso" id="pestForm">
                                    <input type="hidden" name="paso" value="8">
                                    <input type="hidden" name="nombre_paso" value="pest">
                                      <!-- Las 25 Preguntas del Análisis PEST -->
                                    <div class="pest-questions">
                                        <div class="question-item">
                                            <p><strong>1.</strong> Los cambios en la composición étnica de los consumidores de nuestro mercado está teniendo un notable impacto.</p>
                                            <select name="pest_1" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>2.</strong> El envejecimiento de la población tiene un importante impacto en la demanda.</p>
                                            <select name="pest_2" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>3.</strong> Los nuevos estilos de vida y tendencias originan cambios en la oferta de nuestro sector.</p>
                                            <select name="pest_3" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>4.</strong> El envejecimiento de la población tiene un importante impacto en la oferta del sector donde operamos.</p>
                                            <select name="pest_4" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>5.</strong> Las variaciones en el nivel de riqueza de la población impactan considerablemente en la demanda de los productos/servicios del sector donde operamos.</p>
                                            <select name="pest_5" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>6.</strong> La legislación fiscal afecta muy considerablemente a la economía de las empresas del sector donde operamos.</p>
                                            <select name="pest_6" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>7.</strong> La legislación laboral afecta muy considerablemente a la operativa del sector donde actuamos.</p>
                                            <select name="pest_7" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>8.</strong> Las subvenciones otorgadas por las Administraciones Públicas son claves en el desarrollo competitivo del mercado donde operamos.</p>
                                            <select name="pest_8" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>9.</strong> El impacto que tiene la legislación de protección al consumidor, en la manera de producir bienes y/o servicios es muy importante.</p>
                                            <select name="pest_9" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>10.</strong> La normativa autonómica tiene un impacto considerable en el funcionamiento del sector donde actuamos.</p>
                                            <select name="pest_10" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>11.</strong> Las expectativas de crecimiento económico generales afectan crucialmente al mercado donde operamos.</p>
                                            <select name="pest_11" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>12.</strong> La política de tipos de interés es fundamental en el desarrollo financiero del sector donde trabaja nuestra empresa.</p>
                                            <select name="pest_12" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>13.</strong> La globalización permite a nuestra industria gozar de importantes oportunidades en  nuevos mercados.</p>
                                            <select name="pest_13" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>14.</strong> La situación del empleo es fundamental para el desarrollo económico de nuestra empresa y nuestro sector.</p>
                                            <select name="pest_14" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>15.</strong> Las expectativas del ciclo económico de nuestro sector impactan en la situación económica de sus empresas.</p>
                                            <select name="pest_15" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>16.</strong> Las Administraciones Públicas están incentivando el esfuerzo tecnológico de las empresas de nuestro sector.</p>
                                            <select name="pest_16" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>17.</strong> Internet, el comercio electrónico, el wireless y otras NTIC están impactando en la demanda de nuestros productos/servicios y en los de la competencia.</p>
                                            <select name="pest_17" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>18.</strong> El empleo de NTIC´s es generalizado en el sector donde trabajamos.</p>
                                            <select name="pest_18" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>19.</strong> En nuestro sector, es de gran importancia ser pionero o referente en el empleo de aplicaciones tecnológicas.</p>
                                            <select name="pest_19" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>20.</strong> En el sector donde operamos, para ser competitivos, es condición "sine qua non" innovar constantemente.</p>
                                            <select name="pest_20" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>21.</strong> La legislación medioambiental afecta al desarrollo de nuestro sector.</p>
                                            <select name="pest_21" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>22.</strong> Los clientes de nuestro mercado exigen que se seamos socialmente responsables, en el plano medioambiental.</p>
                                            <select name="pest_22" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>23.</strong> En nuestro sector, la políticas medioambientales son una fuente de ventajas competitivas.</p>
                                            <select name="pest_23" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>24.</strong> La creciente preocupación social por el medio ambiente impacta notablemente en la demanda de productos/servicios ofertados en nuestro mercado.</p>
                                            <select name="pest_24" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>
                                        </div>
                                        <div class="question-item">
                                            <p><strong>25.</strong> El factor ecológico es una fuente de diferenciación clara en el sector donde opera nuestra empresa.</p>
                                            <select name="pest_25" required>
                                                <option value="1" selected>En total desacuerdo</option>
                                                <option value="2">No está de acuerdo</option>
                                                <option value="3">Está de acuerdo</option>
                                                <option value="4">Está bastante de acuerdo</option>
                                                <option value="5">En total acuerdo</option>
                                            </select>                                        </div>
                                    </div>
                                      <!-- Botón de Submit oculto - se usa desde la pestaña de Resultados -->
                                    <div class="text-center" style="margin-top: 30px; padding: 20px; display: none;">
                                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" 
                                                style="background-color: #4CAF50; color: white; padding: 15px 30px; font-size: 16px; font-weight: bold;">
                                            🔒 GUARDAR ANÁLISIS PEST Y CONTINUAR
                                        </button>
                                    </div>
                                </form>
                                <div class="text-center" style="margin-top: 20px; padding: 15px; background-color: #f5f5f5; border-radius: 5px;">
                                    <p style="margin: 0; color: #666;">
                                        <strong>¿Ya completó el análisis?</strong><br>
                                        <a href="#" onclick="document.querySelector('a[href=&quot;#resultados-tab&quot;]').click(); return false;" 
                                           style="color: #2196F3; text-decoration: underline;">
                                            Ir a la pestaña de Resultados para agregar oportunidades y amenazas
                                        </a>
                                    </p>
                                </div>
                            </div>                            <!-- Tab de Resultados -->
                            <div class="mdl-tabs__panel" id="resultados-tab">
                                <div class="results-container" id="results-container" style="display: none;">
                                    <h3 class="text-center tittles">Resultados del Análisis P.E.S.T</h3>
                                    <div class="mdl-grid">
                                        <div class="mdl-cell mdl-cell--6-col">
                                            <h4 class="text-center">Gráfico Radar</h4>
                                            <div class="chart-container" style="position: relative; height: 400px;">
                                                <canvas id="pestChart"></canvas>
                                            </div>
                                        </div>
                                        <div class="mdl-cell mdl-cell--6-col">
                                            <h4 class="text-center">Gráfico de Barras</h4>
                                            <div class="chart-container" style="position: relative; height: 400px;">
                                                <canvas id="pestBarChart"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mdl-grid">
                                        <div class="mdl-cell mdl-cell--12-col">
                                            <div class="factors-summary">
                                                <h4>Puntuaciones por Factor:</h4>                                                <div class="factor-score">
                                                    <span style="color: #2196F3;">Demográfico:</span>
                                                    <strong id="factor1-score">0/25</strong>
                                                    <div class="progress-bar">
                                                        <div id="factor1-progress" class="progress" style="width: 0%; background-color: #2196F3;"></div>
                                                    </div>
                                                </div>
                                                <div class="factor-score">
                                                    <span style="color: #4CAF50;">Legal/Político:</span>
                                                    <strong id="factor2-score">0/25</strong>
                                                    <div class="progress-bar">
                                                        <div id="factor2-progress" class="progress" style="width: 0%; background-color: #4CAF50;"></div>
                                                    </div>
                                                </div>
                                                <div class="factor-score">
                                                    <span style="color: #FF9800;">Económico:</span>
                                                    <strong id="factor3-score">0/25</strong>
                                                    <div class="progress-bar">
                                                        <div id="factor3-progress" class="progress" style="width: 0%; background-color: #FF9800;"></div>
                                                    </div>
                                                </div>
                                                <div class="factor-score">
                                                    <span style="color: #9C27B0;">Tecnológico:</span>
                                                    <strong id="factor4-score">0/25</strong>
                                                    <div class="progress-bar">
                                                        <div id="factor4-progress" class="progress" style="width: 0%; background-color: #9C27B0;"></div>
                                                    </div>
                                                </div>
                                                <div class="factor-score">
                                                    <span style="color: #00BCD4;">Medioambiental:</span>
                                                    <strong id="factor5-score">0/25</strong>
                                                    <div class="progress-bar">
                                                        <div id="factor5-progress" class="progress" style="width: 0%; background-color: #00BCD4;"></div>
                                                    </div>
                                                </div>
                                                <div class="total-score">
                                                    <strong id="total-score">Puntuación Total: 0/125</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    <div class="evaluation-results" id="evaluation-results">
                                        <h4>Evaluación de Resultados:</h4>
                                        <div id="evaluation-text" class="alert alert-info">
                                            Complete el formulario para ver la evaluación.
                                        </div>
                                    </div>                                    <!-- Sección de Oportunidades -->
                                    <div class="opportunities-section" style="margin-top: 30px;">                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                            <h4><i class="zmdi zmdi-trending-up" style="color: #4CAF50;"></i> Oportunidades Identificadas:</h4>
                                            <button type="button" onclick="agregarOportunidad()" class="add-btn opportunity-add-btn" 
                                                    style="width: 40px; height: 40px; border-radius: 50%; background-color: #4CAF50; border: none; color: white; font-size: 20px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                                                <i class="zmdi zmdi-plus" style="color: white; font-size: 18px;">+</i>
                                            </button>
                                        </div>                                        <div id="oportunidades-container">
                                            <?php if (!empty($oportunidades_previas)): ?>
                                                <?php foreach ($oportunidades_previas as $index => $oportunidad): ?>
                                                    <div class="oportunidad-item" style="margin-bottom: 10px; display: flex; align-items: center;">
                                                        <input type="text" name="oportunidad_<?php echo $index + 1; ?>" class="oportunidad-input" 
                                                               value="<?php echo htmlspecialchars($oportunidad); ?>"
                                                               placeholder="Ej: Nuevos mercados emergentes en el sector tecnológico"
                                                               style="flex: 1; padding: 10px; border: 2px solid #4CAF50; border-radius: 5px; font-family: inherit; margin-right: 10px;">
                                                        <button type="button" onclick="eliminarOportunidad(this)" class="delete-btn" 
                                                                style="width: 32px; height: 32px; border-radius: 50%; background-color: #f44336; border: none; color: white; font-size: 16px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                                                            <i class="zmdi zmdi-delete" style="color: white; font-size: 14px;">×</i>
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="oportunidad-item" style="margin-bottom: 10px; display: flex; align-items: center;">
                                                    <input type="text" name="oportunidad_1" class="oportunidad-input" 
                                                           placeholder="Ej: Nuevos mercados emergentes en el sector tecnológico"
                                                           style="flex: 1; padding: 10px; border: 2px solid #4CAF50; border-radius: 5px; font-family: inherit; margin-right: 10px;">
                                                    <button type="button" onclick="eliminarOportunidad(this)" class="delete-btn" 
                                                            style="width: 32px; height: 32px; border-radius: 50%; background-color: #f44336; border: none; color: white; font-size: 16px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                                                        <i class="zmdi zmdi-delete" style="color: white; font-size: 14px;">×</i>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="opportunity-suggestions" style="margin-top: 10px;">
                                            <small style="color: #666;">
                                                <strong>Sugerencias:</strong> Nuevos mercados, avances tecnológicos, cambios regulatorios favorables, 
                                                tendencias sociales positivas, alianzas estratégicas, etc.
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Sección de Amenazas -->
                                    <div class="threats-section" style="margin-top: 30px;">                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                            <h4><i class="zmdi zmdi-trending-down" style="color: #F44336;"></i> Amenazas Identificadas:</h4>
                                            <button type="button" onclick="agregarAmenaza()" class="add-btn threat-add-btn" 
                                                    style="width: 40px; height: 40px; border-radius: 50%; background-color: #F44336; border: none; color: white; font-size: 20px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                                                <i class="zmdi zmdi-plus" style="color: white; font-size: 18px;">+</i>
                                            </button>
                                        </div>                                        <div id="amenazas-container">
                                            <?php if (!empty($amenazas_previas)): ?>
                                                <?php foreach ($amenazas_previas as $index => $amenaza): ?>
                                                    <div class="amenaza-item" style="margin-bottom: 10px; display: flex; align-items: center;">
                                                        <input type="text" name="amenaza_<?php echo $index + 1; ?>" class="amenaza-input" 
                                                               value="<?php echo htmlspecialchars($amenaza); ?>"
                                                               placeholder="Ej: Competencia intensa de empresas multinacionales"
                                                               style="flex: 1; padding: 10px; border: 2px solid #F44336; border-radius: 5px; font-family: inherit; margin-right: 10px;">
                                                        <button type="button" onclick="eliminarAmenaza(this)" class="delete-btn" 
                                                                style="width: 32px; height: 32px; border-radius: 50%; background-color: #f44336; border: none; color: white; font-size: 16px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                                                            <i class="zmdi zmdi-delete" style="color: white; font-size: 14px;">×</i>
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="amenaza-item" style="margin-bottom: 10px; display: flex; align-items: center;">
                                                    <input type="text" name="amenaza_1" class="amenaza-input" 
                                                           placeholder="Ej: Competencia intensa de empresas multinacionales"
                                                           style="flex: 1; padding: 10px; border: 2px solid #F44336; border-radius: 5px; font-family: inherit; margin-right: 10px;">
                                                    <button type="button" onclick="eliminarAmenaza(this)" class="delete-btn" 
                                                            style="width: 32px; height: 32px; border-radius: 50%; background-color: #f44336; border: none; color: white; font-size: 16px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                                                        <i class="zmdi zmdi-delete" style="color: white; font-size: 14px;">×</i>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="threat-suggestions" style="margin-top: 10px;">
                                            <small style="color: #666;">
                                                <strong>Sugerencias:</strong> Competencia intensa, cambios regulatorios desfavorables, 
                                                crisis económicas, obsolescencia tecnológica, cambios en preferencias del consumidor, etc.
                                            </small>
                                        </div>
                                    </div>                                    <div class="text-center" style="margin-top: 30px;">
                                        <button onclick="guardarPESTCompleto()" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" 
                                                style="background-color: #4CAF50; color: white; padding: 3px 30px; font-size: 16px; font-weight: bold;">
                                            <i class="zmdi zmdi-save"></i> Guardar PEST y Continuar
                                        </button>
                                        <div style="margin-top: 15px;">
                                            <small style="color: #666;">
                                                <strong>Importante:</strong> Complete el análisis PEST y agregue al menos una oportunidad y una amenaza antes de continuar.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center" id="no-results" style="padding: 50px;">
                                    <p>Complete el formulario para ver los resultados del análisis PEST.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- Scripts -->    <script src="../public/js/material.min.js"></script>
    <script>
        // JavaScript para mejorar la experiencia del usuario
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('pestForm');
            const selects = form.querySelectorAll('select');
            
            // Establecer "En total desacuerdo" (valor 1) como opción por defecto
            selects.forEach(select => {
                if (!select.value) {
                    select.value = '1';
                }
            });
            
            // Agregar contador de progreso
            function updateProgress() {
                const answeredQuestions = Array.from(selects).filter(select => select.value !== '').length;
                const totalQuestions = selects.length;
                const progressPercentage = (answeredQuestions / totalQuestions) * 100;
                
                // Crear o actualizar barra de progreso si no existe
                let progressContainer = document.getElementById('progress-container');
                if (!progressContainer) {
                    progressContainer = document.createElement('div');
                    progressContainer.id = 'progress-container';
                    progressContainer.innerHTML = `
                        <div style="margin: 20px 0; padding: 10px; background: #f5f5f5; border-radius: 5px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Progreso del formulario:</span>
                                <span id="progress-text">${answeredQuestions}/${totalQuestions} preguntas</span>
                            </div>
                            <div style="width: 100%; height: 10px; background: #e0e0e0; border-radius: 5px; overflow: hidden;">
                                <div id="progress-bar" style="height: 100%; background: #2196F3; transition: width 0.3s ease; width: ${progressPercentage}%;"></div>
                            </div>
                        </div>
                    `;
                    form.insertBefore(progressContainer, form.firstChild);
                } else {
                    document.getElementById('progress-text').textContent = `${answeredQuestions}/${totalQuestions} preguntas`;
                    document.getElementById('progress-bar').style.width = `${progressPercentage}%`;
                }
            }
            
            // Escuchar cambios en todos los selects
            selects.forEach(select => {
                select.addEventListener('change', updateProgress);
            });
            
            // Inicializar progreso
            updateProgress();
              // Validación del formulario antes de enviar
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const unansweredQuestions = Array.from(selects).filter(select => select.value === '');
                if (unansweredQuestions.length > 0) {
                    alert(`Por favor, complete todas las preguntas. Quedan ${unansweredQuestions.length} preguntas sin responder.`);
                    unansweredQuestions[0].focus();
                    return false;
                }
                
                // Mostrar mensaje de carga
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.innerHTML = '<i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Procesando...';
                submitButton.disabled = true;
                
                // Enviar con AJAX
                const formData = new FormData(this);
                
                fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'identificacion_de_estrategias.php';
                    } else {
                        alert('Error: ' + data.message);
                        submitButton.innerHTML = 'Continuar';
                        submitButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al guardar los datos');
                    submitButton.innerHTML = 'Continuar';
                    submitButton.disabled = false;
                });
            });
            
            // Función para animar las secciones al hacer scroll
            function animateOnScroll() {
                const questions = document.querySelectorAll('.question-item');
                questions.forEach(question => {
                    const rect = question.getBoundingClientRect();
                    if (rect.top < window.innerHeight && rect.bottom > 0) {
                        question.style.opacity = '1';
                        question.style.transform = 'translateY(0)';
                    }
                });
            }
            
            // Aplicar estilos iniciales para animación
            document.querySelectorAll('.question-item').forEach(question => {
                question.style.opacity = '0';
                question.style.transform = 'translateY(20px)';
                question.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            });
            
            // Ejecutar animación inicial y en scroll
            animateOnScroll();
            window.addEventListener('scroll', animateOnScroll);
        });

        // Variables globales para almacenar resultados
        let pestResultados = null;

        // Función para procesar resultados en tiempo real
        function procesarResultadosPESTRealtime() {
            const form = document.getElementById('pestForm');
            const selects = form.querySelectorAll('select');
            let total = 0;
            let factor1 = 0, factor2 = 0, factor3 = 0, factor4 = 0, factor5 = 0;
            
            // 5 preguntas por factor
            for (let i = 1; i <= 5; i++) factor1 += parseInt(document.querySelector(`select[name="pest_${i}"]`).value);
            for (let i = 6; i <= 10; i++) factor2 += parseInt(document.querySelector(`select[name="pest_${i}"]`).value);
            for (let i = 11; i <= 15; i++) factor3 += parseInt(document.querySelector(`select[name="pest_${i}"]`).value);
            for (let i = 16; i <= 20; i++) factor4 += parseInt(document.querySelector(`select[name="pest_${i}"]`).value);
            for (let i = 21; i <= 25; i++) factor5 += parseInt(document.querySelector(`select[name="pest_${i}"]`).value);
            
            total = factor1 + factor2 + factor3 + factor4 + factor5;
            pestResultados = {
                factor1, factor2, factor3, factor4, factor5, total
            };
            mostrarResultados(pestResultados);
        }        
        // Mostrar resultados y gráficos desde el inicio y en cada cambio
        window.addEventListener('DOMContentLoaded', function() {
            procesarResultadosPESTRealtime();
            const form = document.getElementById('pestForm');
            const selects = form.querySelectorAll('select');
            selects.forEach(select => {
                select.addEventListener('change', procesarResultadosPESTRealtime);
            });
        });

        // Función para mostrar los resultados en la interfaz
        function mostrarResultados(resultados) {
            // Actualizar puntuaciones
            document.getElementById('factor1-score').textContent = `${resultados.factor1}/25`;
            document.getElementById('factor2-score').textContent = `${resultados.factor2}/25`;
            document.getElementById('factor3-score').textContent = `${resultados.factor3}/25`;
            document.getElementById('factor4-score').textContent = `${resultados.factor4}/25`;
            document.getElementById('factor5-score').textContent = `${resultados.factor5}/25`;
            document.getElementById('total-score').textContent = `Puntuación Total: ${resultados.total}/125`;
            
            // Actualizar barras de progreso
            document.getElementById('factor1-progress').style.width = `${(resultados.factor1/25)*100}%`;
            document.getElementById('factor2-progress').style.width = `${(resultados.factor2/25)*100}%`;
            document.getElementById('factor3-progress').style.width = `${(resultados.factor3/25)*100}%`;
            document.getElementById('factor4-progress').style.width = `${(resultados.factor4/25)*100}%`;
            document.getElementById('factor5-progress').style.width = `${(resultados.factor5/25)*100}%`;
            
            // Generar evaluación
            let evaluacion = '';
            let claseAlerta = '';
            
            if (resultados.total >= 100) {
                evaluacion = `<strong>Excelente (${resultados.total}/125):</strong> El entorno global es muy favorable para su organización. Existe un ambiente propicio para el crecimiento y desarrollo. Aproveche las oportunidades presentes y mantenga la ventaja competitiva.`;
                claseAlerta = 'alert-success';
            } else if (resultados.total >= 75) {
                evaluacion = `<strong>Bueno (${resultados.total}/125):</strong> El entorno es generalmente favorable con algunas áreas que requieren atención. La organización puede prosperar con estrategias adecuadas para abordar las áreas de mejora identificadas.`;
                claseAlerta = 'alert-info';
            } else if (resultados.total >= 50) {
                evaluacion = `<strong>Regular (${resultados.total}/125):</strong> El entorno presenta desafíos moderados que requieren atención estratégica. Es importante desarrollar planes específicos para mitigar los riesgos y aprovechar las oportunidades disponibles.`;
                claseAlerta = 'alert-warning';
            } else {
                evaluacion = `<strong>Desafiante (${resultados.total}/125):</strong> El entorno presenta significativos retos que requieren estrategias específicas y cuidadosa planificación. Se recomienda desarrollar planes de contingencia y buscar asesoramiento especializado.`;
                claseAlerta = 'alert-danger';
            }
            
            const evaluationDiv = document.getElementById('evaluation-text');
            evaluationDiv.innerHTML = evaluacion;
            evaluationDiv.className = `alert ${claseAlerta}`;
            
            // Mostrar container de resultados
            document.getElementById('results-container').style.display = 'block';
            document.getElementById('no-results').style.display = 'none';
            
            // Generar gráficos
            generarGraficos(resultados);
        }

        // Función para cambiar a la tab de resultados
        function cambiarATabResultados() {
            const resultsTab = document.getElementById('resultados-tab-link');
            const resultsPanel = document.getElementById('resultados-tab');
            const formularioTab = document.querySelector('a[href="#formulario-tab"]');
            const formularioPanel = document.getElementById('formulario-tab');
            
            if (resultsTab && resultsPanel) {
                formularioTab.classList.remove('is-active');
                formularioPanel.classList.remove('is-active');
                resultsTab.classList.add('is-active');
                resultsPanel.classList.add('is-active');
            }
        }

        // Corrección de Chart.js destroy()
        function safeDestroyChart(chart) {
            if (chart && typeof chart.destroy === 'function') {
                chart.destroy();
            }
        }

        // Función para generar los gráficos
        function generarGraficos(resultados) {
            // Destruir gráficos existentes si los hay
            safeDestroyChart(window.pestRadarChart);
            safeDestroyChart(window.pestBarChart);

            // Gráfico Radar
            const ctxRadar = document.getElementById('pestChart').getContext('2d');
            window.pestRadarChart = new Chart(ctxRadar, {
                type: 'radar',
                data: {
                    labels: ['Demográfico', 'Legal/Político', 'Económico', 'Tecnológico', 'Medioambiental'],
                    datasets: [{
                        label: 'Puntuación PEST',
                        data: [resultados.factor1, resultados.factor2, resultados.factor3, resultados.factor4, resultados.factor5],
                        backgroundColor: 'rgba(33, 150, 243, 0.2)',
                        borderColor: 'rgba(33, 150, 243, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(33, 150, 243, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 25,
                            stepSize: 5
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // Gráfico de Barras
            const ctxBar = document.getElementById('pestBarChart').getContext('2d');
            window.pestBarChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Demográfico', 'Legal/Político', 'Económico', 'Tecnológico', 'Medioambiental'],
                    datasets: [{
                        label: 'Puntuación por Factor',
                        data: [resultados.factor1, resultados.factor2, resultados.factor3, resultados.factor4, resultados.factor5],
                        backgroundColor: [
                            'rgba(33, 150, 243, 0.8)',
                            'rgba(76, 175, 80, 0.8)',
                            'rgba(255, 152, 0, 0.8)',
                            'rgba(156, 39, 176, 0.8)',
                            'rgba(0, 188, 212, 0.8)'
                        ],
                        borderColor: [
                            'rgba(33, 150, 243, 1)',
                            'rgba(76, 175, 80, 1)',
                            'rgba(255, 152, 0, 1)',
                            'rgba(156, 39, 176, 1)',
                            'rgba(0, 188, 212, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 25,
                            stepSize: 5,
                            title: {
                                display: true,
                                text: 'Puntuación'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Factores PEST'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + '/25';
                                }
                            }
                        }
                    }
                }
            });
        }        // Función para guardar PEST completo (análisis + oportunidades + amenazas)
        function guardarPESTCompleto() {
            if (!pestResultados) {
                alert('Complete el análisis PEST primero.');
                // Cambiar a la pestaña del formulario
                document.querySelector('a[href="#formulario-tab"]').click();
                return;
            }
            
            // Obtener todas las oportunidades
            const oportunidadesInputs = document.querySelectorAll('.oportunidad-input');
            const oportunidades = Array.from(oportunidadesInputs)
                .map(input => input.value.trim())
                .filter(value => value !== '');
            
            // Obtener todas las amenazas
            const amenazasInputs = document.querySelectorAll('.amenaza-input');
            const amenazas = Array.from(amenazasInputs)
                .map(input => input.value.trim())
                .filter(value => value !== '');
            
            // Validar que haya al menos una oportunidad y una amenaza
            if (oportunidades.length === 0) {
                alert('Agregue al menos una oportunidad antes de continuar.');
                return;
            }
            
            if (amenazas.length === 0) {
                alert('Agregue al menos una amenaza antes de continuar.');
                return;
            }
            
            // Crear FormData con todos los datos
            const formData = new FormData();
            formData.append('nombre_paso', 'pest');
            
            // Agregar respuestas del análisis PEST (del formulario principal)
            const form = document.getElementById('pestForm');
            const formDataOriginal = new FormData(form);
            for (let [key, value] of formDataOriginal.entries()) {
                formData.append(key, value);
            }
            
            // Agregar factores calculados
            formData.append('factor_politico', pestResultados.factor1);
            formData.append('factor_economico', pestResultados.factor2);
            formData.append('factor_social', pestResultados.factor3);
            formData.append('factor_tecnologico', pestResultados.factor4);
            
            // Agregar oportunidades y amenazas
            oportunidades.forEach((oportunidad, index) => {
                formData.append(`oportunidades[${index}]`, oportunidad);
            });
            amenazas.forEach((amenaza, index) => {
                formData.append(`amenazas[${index}]`, amenaza);
            });
            
            // Mostrar mensaje de carga
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="zmdi zmdi-refresh zmdi-hc-spin"></i> Guardando...';
            button.disabled = true;
            
            // Enviar al controlador principal
            fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`PEST guardado exitosamente con ${oportunidades.length} oportunidades y ${amenazas.length} amenazas.`);
                    window.location.href = 'identificacion_de_estrategias.php';
                } else {
                    alert('Error: ' + data.message);
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar los datos.');
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }        // Contadores para IDs únicos - inicializar con datos existentes
        let oportunidadCounter = <?php echo count($oportunidades_previas); ?> || 1;
        let amenazaCounter = <?php echo count($amenazas_previas); ?> || 1;

        // Función para agregar nueva oportunidad
        function agregarOportunidad() {
            oportunidadCounter++;
            const container = document.getElementById('oportunidades-container');
            const newItem = document.createElement('div');
            newItem.className = 'oportunidad-item';
            newItem.style.cssText = 'margin-bottom: 10px; display: flex; align-items: center;';
              newItem.innerHTML = `
                <input type="text" name="oportunidad_${oportunidadCounter}" class="oportunidad-input" 
                       placeholder="Ej: Nueva oportunidad identificada en el entorno externo"
                       style="flex: 1; padding: 10px; border: 2px solid #4CAF50; border-radius: 5px; font-family: inherit; margin-right: 10px;">
                <button type="button" onclick="eliminarOportunidad(this)" class="delete-btn" 
                        style="width: 32px; height: 32px; border-radius: 50%; background-color: #f44336; border: none; color: white; font-size: 16px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                    <i class="zmdi zmdi-delete" style="color: white; font-size: 14px;">×</i>
                </button>
            `;
            
            container.appendChild(newItem);
            
            // Animar la entrada del nuevo elemento
            newItem.style.opacity = '0';
            newItem.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                newItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                newItem.style.opacity = '1';
                newItem.style.transform = 'translateY(0)';
            }, 10);
        }

        // Función para eliminar oportunidad
        function eliminarOportunidad(button) {
            const container = document.getElementById('oportunidades-container');
            if (container.children.length > 1) {
                const item = button.parentElement;
                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    item.remove();
                }, 300);
            } else {
                alert('Debe mantener al menos una oportunidad.');
            }
        }

        // Función para agregar nueva amenaza
        function agregarAmenaza() {
            amenazaCounter++;
            const container = document.getElementById('amenazas-container');
            const newItem = document.createElement('div');
            newItem.className = 'amenaza-item';
            newItem.style.cssText = 'margin-bottom: 10px; display: flex; align-items: center;';
              newItem.innerHTML = `
                <input type="text" name="amenaza_${amenazaCounter}" class="amenaza-input" 
                       placeholder="Ej: Nueva amenaza identificada en el entorno externo"
                       style="flex: 1; padding: 10px; border: 2px solid #F44336; border-radius: 5px; font-family: inherit; margin-right: 10px;">
                <button type="button" onclick="eliminarAmenaza(this)" class="delete-btn" 
                        style="width: 32px; height: 32px; border-radius: 50%; background-color: #f44336; border: none; color: white; font-size: 16px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                    <i class="zmdi zmdi-delete" style="color: white; font-size: 14px;">×</i>
                </button>
            `;
            
            container.appendChild(newItem);
            
            // Animar la entrada del nuevo elemento
            newItem.style.opacity = '0';
            newItem.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                newItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                newItem.style.opacity = '1';
                newItem.style.transform = 'translateY(0)';
            }, 10);
        }

        // Función para eliminar amenaza
        function eliminarAmenaza(button) {
            const container = document.getElementById('amenazas-container');
            if (container.children.length > 1) {
                const item = button.parentElement;
                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    item.remove();
                }, 300);
            } else {
                alert('Debe mantener al menos una amenaza.');
            }
        }
    </script>

    <!-- Estilos adicionales para PEST -->
    <style>
        .pest-questions {
            margin: 20px 0;
        }
        
        .question-item {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color: #fafafa;
        }
        
        .question-item p {
            margin-bottom: 10px;
            font-weight: 500;
        }

        .question-item select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 2px solid #ddd;
            border-radius: 5px;
            background-color: white;
            color: #333;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .question-item select:focus {
            outline: none;
            border-color: #2196F3;
            box-shadow: 0 0 5px rgba(33, 150, 243, 0.3);
        }

        .question-item select:hover {
            border-color: #999;
        }
        
        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        
        .radio-group label:hover {
            background-color: #e3f2fd;
        }
        
        .radio-group input[type="radio"] {
            margin: 0;
        }
        
        .factors-summary {
            padding: 20px;
        }
        
        .factor-score {
            margin-bottom: 15px;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 5px;
        }
        
        .progress {
            height: 100%;
            transition: width 0.5s ease;
        }
        
        .total-score {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
        }
        
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-info {
            background-color: #cce7ff;
            border: 1px solid #99d6ff;
            color: #004085;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .chart-container {
            padding: 20px;
        }

        /* Animación para cuando se cambian las tabs */
        .mdl-tabs__panel {
            transition: opacity 0.3s ease;
        }

        /* Mejorar el aspecto de las tabs */
        .mdl-tabs__tab {
            padding: 15px 20px;
            font-weight: 500;
        }

        .mdl-tabs__tab.is-active {
            color: #2196F3 !important;
        }

        /* Estilos para oportunidades y amenazas dinámicas */
        .oportunidad-item, .amenaza-item {
            transition: all 0.3s ease;
        }
        
        .oportunidad-item:hover, .amenaza-item:hover {
            transform: translateX(5px);
        }
        
        .opportunities-section h4, .threats-section h4 {
            margin-bottom: 0;
        }
        
        /* Estilos mejorados para botones + y × */
        .add-btn:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;
        }
        
        .opportunity-add-btn:hover {
            background-color: #45a049 !important;
        }
        
        .threat-add-btn:hover {
            background-color: #da190b !important;
        }
        
        .delete-btn:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;
            background-color: #da190b !important;
        }
        
        /* Asegurar que los iconos sean visibles */
        .add-btn i, .delete-btn i {
            display: inline-block !important;
            line-height: 1 !important;
            vertical-align: middle !important;
        }
        
        .mdl-button--mini-fab {
            min-width: 32px !important;
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .mdl-button--mini-fab:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .mdl-button--mini-fab i {
            font-size: 16px !important;
            line-height: 32px !important;
        }
        
        /* Animaciones para entrada y salida */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
        
        .slide-in {
            animation: slideIn 0.3s ease;
        }
        
        .slide-out {
            animation: slideOut 0.3s ease;
        }
    </style>
</body>
</html>
