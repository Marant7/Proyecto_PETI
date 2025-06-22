<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    echo "No hay usuario en sesión";
    exit();
}

// Obtener plan_id de la URL
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
    $datos_previos = $model->obtenerPEST($plan_id);
} catch (Exception $e) {
    error_log("Error obteniendo datos previos de PEST: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis PEST - Plan Estratégico</title>
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/plan-estrategico.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .main-content {
            margin-left: 300px;
            padding: 30px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
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
        
        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .tab {
            padding: 15px 30px;
            background: #f5f5f5;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            color: #666;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
            transition: all 0.3s ease;
        }
        
        .tab.active {
            background: #1976d2;
            color: white;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.3);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .progress-container {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #1976d2;
        }
        
        .progress-bar {
            width: 100%;
            height: 12px;
            background: #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
            margin-top: 8px;
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(90deg, #1976d2 0%, #42a5f5 100%);
            transition: width 0.3s ease;
        }
        
        .question-item {
            background: #fafafa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #1976d2;
            transition: all 0.3s ease;
        }
        
        .question-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }
        
        .question-item p {
            margin: 0 0 15px 0;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        
        .question-item select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            transition: border-color 0.3s ease;
        }
        
        .question-item select:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }
        
        .results-section {
            margin-top: 30px;
        }
        
        .chart-container {
            position: relative;
            height: 400px;
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .factor-score {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .factor-score span {
            width: 150px;
            font-weight: 600;
        }
        
        .factor-score strong {
            width: 80px;
            text-align: center;
        }
        
        .factor-score .progress-bar {
            flex: 1;
            margin-left: 15px;
            height: 8px;
        }
        
        .evaluation-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
            border-left: 5px solid #1976d2;
        }
        
        .opportunities-threats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        .section-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .section-title h4 {
            margin: 0;
            color: #333;
            font-size: 1.3em;
        }
        
        .add-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            color: white;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        
        .opportunity-btn {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        }
        
        .threat-btn {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        }
        
        .input-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .input-item input {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            margin-right: 10px;
            font-family: inherit;
            transition: border-color 0.3s ease;
        }
        
        .input-item input:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }
        
        .delete-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: #f44336;
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .delete-btn:hover {
            background: #d32f2f;
            transform: scale(1.1);
        }
        
        .save-container {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
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
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .alert-info { background: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }
        .alert-warning { background: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
        .alert-danger { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        .success {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%) !important;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>📊 Análisis P.E.S.T</h1>
                <p>Autodiagnóstico del Entorno Global</p>
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
            
            <div class="tabs">
                <button class="tab active" onclick="showTab('formulario')">📝 Formulario</button>
                <button class="tab" onclick="showTab('resultados')">📈 Resultados</button>
            </div>
            
            <!-- Tab Formulario -->
            <div id="tab-formulario" class="tab-content active">
                <div class="progress-container">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span><strong>Progreso del Análisis:</strong></span>
                        <span id="progress-text">0/25 preguntas</span>
                    </div>
                    <div class="progress-bar">
                        <div id="progress-fill" class="progress" style="width: 0%;"></div>
                    </div>
                </div>
                
                <form id="pestForm">
                    <input type="hidden" name="id_plan" value="<?php echo htmlspecialchars($plan_id); ?>">
                    
                    <div class="pest-questions">
                        <?php
                        $preguntas = [
                            "Los cambios en la composición étnica de los consumidores de nuestro mercado está teniendo un notable impacto.",
                            "El envejecimiento de la población tiene un importante impacto en la demanda.",
                            "Los nuevos estilos de vida y tendencias originan cambios en la oferta de nuestro sector.",
                            "El envejecimiento de la población tiene un importante impacto en la oferta del sector donde operamos.",
                            "Las variaciones en el nivel de riqueza de la población impactan considerablemente en la demanda de los productos/servicios del sector donde operamos.",
                            "La legislación fiscal afecta muy considerablemente a la economía de las empresas del sector donde operamos.",
                            "La legislación laboral afecta muy considerablemente a la operativa del sector donde actuamos.",
                            "Las subvenciones otorgadas por las Administraciones Públicas son claves en el desarrollo competitivo del mercado donde operamos.",
                            "El impacto que tiene la legislación de protección al consumidor, en la manera de producir bienes y/o servicios es muy importante.",
                            "La normativa autonómica tiene un impacto considerable en el funcionamiento del sector donde actuamos.",
                            "Las expectativas de crecimiento económico generales afectan crucialmente al mercado donde operamos.",
                            "La política de tipos de interés es fundamental en el desarrollo financiero del sector donde trabaja nuestra empresa.",
                            "La globalización permite a nuestra industria gozar de importantes oportunidades en nuevos mercados.",
                            "La situación del empleo es fundamental para el desarrollo económico de nuestra empresa y nuestro sector.",
                            "Las expectativas del ciclo económico de nuestro sector impactan en la situación económica de sus empresas.",
                            "Las Administraciones Públicas están incentivando el esfuerzo tecnológico de las empresas de nuestro sector.",
                            "Internet, el comercio electrónico, el wireless y otras NTIC están impactando en la demanda de nuestros productos/servicios y en los de la competencia.",
                            "El empleo de NTIC´s es generalizado en el sector donde trabajamos.",
                            "En nuestro sector, es de gran importancia ser pionero o referente en el empleo de aplicaciones tecnológicas.",
                            "En el sector donde operamos, para ser competitivos, es condición \"sine qua non\" innovar constantemente.",
                            "La legislación medioambiental afecta al desarrollo de nuestro sector.",
                            "Los clientes de nuestro mercado exigen que se seamos socialmente responsables, en el plano medioambiental.",
                            "En nuestro sector, la políticas medioambientales son una fuente de ventajas competitivas.",
                            "La creciente preocupación social por el medio ambiente impacta notablemente en la demanda de productos/servicios ofertados en nuestro mercado.",
                            "El factor ecológico es una fuente de diferenciación clara en el sector donde opera nuestra empresa."
                        ];
                        
                        foreach ($preguntas as $index => $pregunta):
                            $numero = $index + 1;
                            $valor_previo = isset($datos_previos['respuestas']["pest_$numero"]) ? $datos_previos['respuestas']["pest_$numero"] : 1;
                        ?>
                            <div class="question-item">
                                <p><strong><?php echo $numero; ?>.</strong> <?php echo $pregunta; ?></p>
                                <select name="pest_<?php echo $numero; ?>" required>
                                    <option value="1" <?php echo ($valor_previo == 1) ? 'selected' : ''; ?>>En total desacuerdo</option>
                                    <option value="2" <?php echo ($valor_previo == 2) ? 'selected' : ''; ?>>No está de acuerdo</option>
                                    <option value="3" <?php echo ($valor_previo == 3) ? 'selected' : ''; ?>>Está de acuerdo</option>
                                    <option value="4" <?php echo ($valor_previo == 4) ? 'selected' : ''; ?>>Está bastante de acuerdo</option>
                                    <option value="5" <?php echo ($valor_previo == 5) ? 'selected' : ''; ?>>En total acuerdo</option>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div style="text-align: center; margin-top: 20px; padding: 15px; background-color: #f5f5f5; border-radius: 8px;">
                        <p style="margin: 0; color: #666;">
                            <strong>¿Ya completó el análisis?</strong><br>
                            <button type="button" onclick="showTab('resultados')" style="background: #1976d2; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-top: 10px;">
                                Ir a Resultados y Oportunidades/Amenazas →
                            </button>
                        </p>
                    </div>
                </form>
            </div>
            
            <!-- Tab Resultados -->
            <div id="tab-resultados" class="tab-content">
                <div class="results-section">
                    <h3 style="text-align: center; color: #1976d2; margin-bottom: 30px;">Resultados del Análisis P.E.S.T</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                        <div class="chart-container">
                            <h4 style="text-align: center; margin-bottom: 20px;">Gráfico Radar</h4>
                            <canvas id="pestChart"></canvas>
                        </div>
                        <div class="chart-container">
                            <h4 style="text-align: center; margin-bottom: 20px;">Gráfico de Barras</h4>
                            <canvas id="pestBarChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="factor-scores">
                        <h4>Puntuaciones por Factor:</h4>
                        <div class="factor-score">
                            <span style="color: #2196F3;">Demográfico:</span>
                            <strong id="factor1-score">0/25</strong>
                            <div class="progress-bar">
                                <div id="factor1-progress" class="progress" style="background-color: #2196F3;"></div>
                            </div>
                        </div>
                        <div class="factor-score">
                            <span style="color: #4CAF50;">Legal/Político:</span>
                            <strong id="factor2-score">0/25</strong>
                            <div class="progress-bar">
                                <div id="factor2-progress" class="progress" style="background-color: #4CAF50;"></div>
                            </div>
                        </div>
                        <div class="factor-score">
                            <span style="color: #FF9800;">Económico:</span>
                            <strong id="factor3-score">0/25</strong>
                            <div class="progress-bar">
                                <div id="factor3-progress" class="progress" style="background-color: #FF9800;"></div>
                            </div>
                        </div>
                        <div class="factor-score">
                            <span style="color: #9C27B0;">Tecnológico:</span>
                            <strong id="factor4-score">0/25</strong>
                            <div class="progress-bar">
                                <div id="factor4-progress" class="progress" style="background-color: #9C27B0;"></div>
                            </div>
                        </div>
                        <div class="factor-score">
                            <span style="color: #00BCD4;">Medioambiental:</span>
                            <strong id="factor5-score">0/25</strong>
                            <div class="progress-bar">
                                <div id="factor5-progress" class="progress" style="background-color: #00BCD4;"></div>
                            </div>
                        </div>
                        <div style="text-align: center; margin-top: 20px;">
                            <strong id="total-score" style="font-size: 1.2em; color: #1976d2;">Puntuación Total: 0/125</strong>
                        </div>
                    </div>
                    
                    <div class="evaluation-box">
                        <h4 style="margin-top: 0; color: #1976d2;">Evaluación de Resultados:</h4>
                        <div id="evaluation-text">
                            Complete el formulario para ver la evaluación.
                        </div>
                    </div>
                    
                    <div class="opportunities-threats">
                        <div class="section-box" style="border-left: 4px solid #4caf50;">
                            <div class="section-title">
                                <h4><i>📈</i> Oportunidades</h4>
                                <button type="button" onclick="agregarOportunidad()" class="add-btn opportunity-btn">+</button>
                            </div>
                            <div id="oportunidades-container">
                                <?php 
                                $oportunidades_previas = $datos_previos['oportunidades'] ?? [''];
                                foreach ($oportunidades_previas as $index => $oportunidad):
                                ?>
                                    <div class="input-item">
                                        <input type="text" name="oportunidades[]" class="oportunidad-input" 
                                               placeholder="Ingrese una oportunidad identificada..." 
                                               value="<?php echo htmlspecialchars($oportunidad); ?>">
                                        <button type="button" onclick="eliminarItem(this)" class="delete-btn">×</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <small style="color: #666; margin-top: 10px; display: block;">
                                <strong>Sugerencias:</strong> Nuevos mercados, avances tecnológicos, cambios regulatorios favorables, etc.
                            </small>
                        </div>
                        
                        <div class="section-box" style="border-left: 4px solid #f44336;">
                            <div class="section-title">
                                <h4><i>📉</i> Amenazas</h4>
                                <button type="button" onclick="agregarAmenaza()" class="add-btn threat-btn">+</button>
                            </div>
                            <div id="amenazas-container">
                                <?php 
                                $amenazas_previas = $datos_previos['amenazas'] ?? [''];
                                foreach ($amenazas_previas as $index => $amenaza):
                                ?>
                                    <div class="input-item">
                                        <input type="text" name="amenazas[]" class="amenaza-input" 
                                               placeholder="Ingrese una amenaza identificada..." 
                                               value="<?php echo htmlspecialchars($amenaza); ?>">
                                        <button type="button" onclick="eliminarItem(this)" class="delete-btn">×</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <small style="color: #666; margin-top: 10px; display: block;">
                                <strong>Sugerencias:</strong> Competencia intensa, cambios regulatorios adversos, crisis económicas, etc.
                            </small>
                        </div>
                    </div>
                    
                    <div class="save-container">
                        <button onclick="guardarPEST()" class="btn-save" id="btnGuardar">
                            💾 GUARDAR ANÁLISIS PEST Y CONTINUAR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let pestResultados = null;
        let pestRadarChart = null;
        let pestBarChart = null;
        
        // Función para cambiar entre tabs
        function showTab(tabName) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            document.querySelector(`[onclick="showTab('${tabName}')"]`).classList.add('active');
            document.getElementById(`tab-${tabName}`).classList.add('active');
            
            if (tabName === 'resultados') {
                procesarResultadosPEST();
            }
        }
        
        // Función para actualizar el progreso
        function updateProgress() {
            const selects = document.querySelectorAll('#pestForm select');
            const answeredQuestions = Array.from(selects).filter(select => select.value !== '').length;
            const totalQuestions = selects.length;
            const progressPercentage = (answeredQuestions / totalQuestions) * 100;
            
            document.getElementById('progress-text').textContent = `${answeredQuestions}/${totalQuestions} preguntas`;
            document.getElementById('progress-fill').style.width = `${progressPercentage}%`;
        }
        
        // Función para procesar resultados PEST
        function procesarResultadosPEST() {
            const selects = document.querySelectorAll('#pestForm select');
            let total = 0;
            let factor1 = 0, factor2 = 0, factor3 = 0, factor4 = 0, factor5 = 0;
            
            // 5 preguntas por factor
            for (let i = 1; i <= 5; i++) {
                const select = document.querySelector(`select[name="pest_${i}"]`);
                factor1 += parseInt(select ? select.value : 1);
            }
            for (let i = 6; i <= 10; i++) {
                const select = document.querySelector(`select[name="pest_${i}"]`);
                factor2 += parseInt(select ? select.value : 1);
            }
            for (let i = 11; i <= 15; i++) {
                const select = document.querySelector(`select[name="pest_${i}"]`);
                factor3 += parseInt(select ? select.value : 1);
            }
            for (let i = 16; i <= 20; i++) {
                const select = document.querySelector(`select[name="pest_${i}"]`);
                factor4 += parseInt(select ? select.value : 1);
            }
            for (let i = 21; i <= 25; i++) {
                const select = document.querySelector(`select[name="pest_${i}"]`);
                factor5 += parseInt(select ? select.value : 1);
            }
            
            total = factor1 + factor2 + factor3 + factor4 + factor5;
            pestResultados = { factor1, factor2, factor3, factor4, factor5, total };
            
            mostrarResultados(pestResultados);
        }
        
        // Función para mostrar los resultados
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
            if (resultados.total >= 100) {
                evaluacion = `<strong>Excelente (${resultados.total}/125):</strong> El entorno global es muy favorable para su organización.`;
            } else if (resultados.total >= 75) {
                evaluacion = `<strong>Bueno (${resultados.total}/125):</strong> El entorno es generalmente favorable con algunas áreas que requieren atención.`;
            } else if (resultados.total >= 50) {
                evaluacion = `<strong>Regular (${resultados.total}/125):</strong> El entorno presenta desafíos moderados que requieren atención estratégica.`;
            } else {
                evaluacion = `<strong>Desafiante (${resultados.total}/125):</strong> El entorno presenta significativos retos que requieren estrategias específicas.`;
            }
            
            document.getElementById('evaluation-text').innerHTML = evaluacion;
            
            // Generar gráficos
            generarGraficos(resultados);
        }
        
        // Función para generar gráficos
        function generarGraficos(resultados) {
            // Destruir gráficos existentes
            if (pestRadarChart) pestRadarChart.destroy();
            if (pestBarChart) pestBarChart.destroy();

            // Gráfico Radar
            const ctxRadar = document.getElementById('pestChart').getContext('2d');
            pestRadarChart = new Chart(ctxRadar, {
                type: 'radar',
                data: {
                    labels: ['Demográfico', 'Legal/Político', 'Económico', 'Tecnológico', 'Medioambiental'],
                    datasets: [{
                        label: 'Puntuación PEST',
                        data: [resultados.factor1, resultados.factor2, resultados.factor3, resultados.factor4, resultados.factor5],
                        backgroundColor: 'rgba(25, 118, 210, 0.2)',
                        borderColor: 'rgba(25, 118, 210, 1)',
                        pointBackgroundColor: 'rgba(25, 118, 210, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(25, 118, 210, 1)',
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
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Gráfico de Barras
            const ctxBar = document.getElementById('pestBarChart').getContext('2d');
            pestBarChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Demográfico', 'Legal/Político', 'Económico', 'Tecnológico', 'Medioambiental'],
                    datasets: [{
                        label: 'Puntuación',
                        data: [resultados.factor1, resultados.factor2, resultados.factor3, resultados.factor4, resultados.factor5],
                        backgroundColor: ['#2196F3', '#4CAF50', '#FF9800', '#9C27B0', '#00BCD4']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 25,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
        
        // Funciones para agregar/eliminar oportunidades y amenazas
        function agregarOportunidad() {
            const container = document.getElementById('oportunidades-container');
            const div = document.createElement('div');
            div.className = 'input-item';
            div.innerHTML = `
                <input type="text" name="oportunidades[]" class="oportunidad-input" 
                       placeholder="Ingrese una oportunidad identificada...">
                <button type="button" onclick="eliminarItem(this)" class="delete-btn">×</button>
            `;
            container.appendChild(div);
        }
        
        function agregarAmenaza() {
            const container = document.getElementById('amenazas-container');
            const div = document.createElement('div');
            div.className = 'input-item';
            div.innerHTML = `
                <input type="text" name="amenazas[]" class="amenaza-input" 
                       placeholder="Ingrese una amenaza identificada...">
                <button type="button" onclick="eliminarItem(this)" class="delete-btn">×</button>
            `;
            container.appendChild(div);
        }
        
        function eliminarItem(button) {
            button.parentElement.remove();
        }
        
        // Función para guardar PEST
        function guardarPEST() {
            if (!pestResultados) {
                procesarResultadosPEST();
            }
            
            const btnGuardar = document.getElementById('btnGuardar');
            const formData = new FormData(document.getElementById('pestForm'));
            
            // Agregar oportunidades y amenazas
            const oportunidadesInputs = document.querySelectorAll('.oportunidad-input');
            const amenazasInputs = document.querySelectorAll('.amenaza-input');
            
            oportunidadesInputs.forEach((input, index) => {
                if (input.value.trim()) {
                    formData.append(`oportunidades[${index}]`, input.value.trim());
                }
            });
            
            amenazasInputs.forEach((input, index) => {
                if (input.value.trim()) {
                    formData.append(`amenazas[${index}]`, input.value.trim());
                }
            });
            
            // Mostrar estado de carga
            btnGuardar.classList.add('loading');
            btnGuardar.textContent = '⏳ Guardando...';
            btnGuardar.disabled = true;            fetch('../Controllers/PlanController.php?action=guardarPEST', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.text(); // Primero obtener como texto para ver qué devuelve
            })
            .then(text => {
                console.log('Response text:', text); // Ver la respuesta completa
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        btnGuardar.classList.add('success');
                        btnGuardar.textContent = '✅ Guardado Exitoso';
                          Swal.fire({
                            icon: 'success',
                            title: '¡Excelente!',
                            text: 'El análisis PEST se ha guardado correctamente',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#4CAF50'
                        }).then((result) => {
                            // No redirigir automáticamente, solo mostrar el mensaje
                            btnGuardar.classList.remove('loading');
                            btnGuardar.textContent = '💾 GUARDAR ANÁLISIS PEST Y CONTINUAR';
                            btnGuardar.disabled = false;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error al guardar los datos',
                            confirmButtonColor: '#f44336'
                        });
                        
                        btnGuardar.classList.remove('loading');
                        btnGuardar.textContent = '💾 GUARDAR ANÁLISIS PEST Y CONTINUAR';
                        btnGuardar.disabled = false;
                    }
                } catch (jsonError) {
                    console.error('Error parsing JSON:', jsonError);
                    console.error('Response was:', text);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error del Servidor',
                        html: `<div style="text-align: left; max-height: 300px; overflow-y: auto;"><pre>${text}</pre></div>`,
                        confirmButtonColor: '#f44336'
                    });
                    
                    btnGuardar.classList.remove('loading');
                    btnGuardar.textContent = '💾 GUARDAR ANÁLISIS PEST Y CONTINUAR';
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
                
                btnGuardar.classList.remove('loading');
                btnGuardar.textContent = '💾 GUARDAR ANÁLISIS PEST Y CONTINUAR';
                btnGuardar.disabled = false;
            });
        }
        
        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
            procesarResultadosPEST();
            
            // Escuchar cambios en selects
            document.querySelectorAll('#pestForm select').forEach(select => {
                select.addEventListener('change', function() {
                    updateProgress();
                    if (document.getElementById('tab-resultados').classList.contains('active')) {
                        procesarResultadosPEST();
                    }
                });
            });
        });
    </script>
</body>
</html>
