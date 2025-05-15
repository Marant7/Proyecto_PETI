<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "No hay usuario en sesión";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTODIAGNOSTICO P.E.S.T</title>
    <link rel="stylesheet" href="../public/css/pest.css">
</head>
<body>
<h1>AUTODIAGNOSTICO ENTORNO GLOBAL P.E.S.T</h1>

<form method="post" action="procesar.php">
    <!-- Factores Sociales -->
    <div class="question-group">
        <div class="group-title">Factores Sociales</div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">1.</span> Los cambios en la composicón étnica de los consumidores de nuestro mercado está teniendo un notable impacto.
            </div>
            <select name="pregunta1" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">2.</span> El envejecimiento de la población tiene un importante impacto en la demanda.
            </div>
            <select name="pregunta2" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">3.</span> Los nuevos estilos de vida y tendencias originan cambios en la oferta de nuestro sector.
            </div>
            <select name="pregunta3" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">4.</span> El envejecimiento de la población tiene un importante impacto en la oferta del sector donde operamos.
            </div>
            <select name="pregunta4" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">5.</span> Las variaciones en el nivel de riqueza de la población impactan considerablemente en la demanda de los productos/servicios del sector donde operamos.
            </div>
            <select name="pregunta5" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
    </div>
    
    <!-- Factores Políticos -->
    <div class="question-group">
        <div class="group-title">Factores Políticos</div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">6.</span> La legislación fiscal afecta muy considerablemente a la economía de las empresas del sector donde operamos.
            </div>
            <select name="pregunta6" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">7.</span> La legislación laboral afecta muy considerablemente a la operativa del sector donde actuamos.
            </div>
            <select name="pregunta7" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">8.</span> Las subvenciones otorgadas por las Administraciones Públicas son claves en el desarrollo competitivo del mercado donde operamos.
            </div>
            <select name="pregunta8" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">9.</span> El impacto que tiene la legislación de protección al consumidor, en la manera de producir bienes y/o servicios es muy importante.
            </div>
            <select name="pregunta9" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">10.</span> La normativa autonómica tiene un impacto considerable en el funcionamiento del sector donde actuamos.
            </div>
            <select name="pregunta10" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
    </div>
    
    <!-- Factores Económicos -->
    <div class="question-group">
        <div class="group-title">Factores Económicos</div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">11.</span> Las expectativas de crecimiento económico generales afectan crucialmente al mercado donde operamos.
            </div>
            <select name="pregunta11" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">12.</span> La política de tipos de interés es fundamental en el desarrollo financiero del sector donde trabaja nuestra empresa.
            </div>
            <select name="pregunta12" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">13.</span> La globalización permite a nuestra industria gozar de importantes oportunidades en nuevos mercados.
            </div>
            <select name="pregunta13" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">14.</span> La situación del empleo es fundamental para el desarrollo económico de nuestra empresa y nuestro sector.
            </div>
            <select name="pregunta14" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">15.</span> Las expectativas del ciclo económico de nuestro sector impactan en la situación económica de sus empresas.
            </div>
            <select name="pregunta15" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
    </div>
    
    <!-- Factores Tecnológicos -->
    <div class="question-group">
        <div class="group-title">Factores Tecnológicos</div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">16.</span> Las Administraciones Públicas están incentivando el esfuerzo tecnológico de las empresas de nuestro sector.
            </div>
            <select name="pregunta16" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">17.</span> Internet, el comercio electrónico, el wireless y otras NTIC están impactando en la demanda de nuestros productos/servicios y en los de la competencia.
            </div>
            <select name="pregunta17" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">18.</span> El empleo de NTIC´s es generalizado en el sector donde trabajamos.
            </div>
            <select name="pregunta18" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">19.</span> En nuestro sector, es de gran importancia ser pionero o referente en el empleo de aplicaciones tecnológicas.
            </div>
            <select name="pregunta19" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">20.</span> En el sector donde operamos, para ser competitivos, es condición "sine qua non" innovar constantemente.
            </div>
            <select name="pregunta20" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
    </div>
    
    <!-- Factores Medioambientales -->
    <div class="question-group">
        <div class="group-title">Factores Medioambientales</div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">21.</span> La legislación medioambiental afecta al desarrollo de nuestro sector.
            </div>
            <select name="pregunta21" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">22.</span> Los clientes de nuestro mercado exigen que seamos socialmente responsables, en el plano medioambiental.
            </div>
            <select name="pregunta22" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">23.</span> En nuestro sector, las políticas medioambientales son una fuente de ventajas competitivas.
            </div>
            <select name="pregunta23" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">24.</span> La creciente preocupación social por el medio ambiente impacta notablemente en la demanda de productos/servicios ofertados en nuestro mercado.
            </div>
            <select name="pregunta24" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
        
        <div class="question-item">
            <div class="question-text">
                <span class="question-number">25.</span> El factor ecológico es una fuente de diferenciación clara en el sector donde opera nuestra empresa.
            </div>
            <select name="pregunta25" required>
                <option value="1">Total desacuerdo</option>
                <option value="2">No está de acuerdo</option>
                <option value="3">Está de acuerdo</option>
                <option value="4">Está bastante de acuerdo</option>
                <option value="5">En total acuerdo</option>
            </select>
        </div>
    </div>
    
    <input type="submit" value="Enviar respuestas">
</form>

<!-- Sección de resultados con gráfico P.E.S.T -->
<div class="results-container">
    <h2>Resultados del Análisis P.E.S.T</h2>
    <div class="chart-container">
        <canvas id="pestChart"></canvas>
    </div>
    <div class="factors-summary">
        <div class="factor-box social">
            <h3>Social</h3>
            <div class="score">0</div>
            <div class="factor-details">
                Preguntas: 1-5<br>
                Total: <span class="average">0</span>/100
            </div>
        </div>
        <div class="factor-box political">
            <h3>Político</h3>
            <div class="score">0</div>
            <div class="factor-details">
                Preguntas: 6-10<br>
                Total: <span class="average">0</span>/100
            </div>
        </div>
        <div class="factor-box economic">
            <h3>Económico</h3>
            <div class="score">0</div>
            <div class="factor-details">
                Preguntas: 11-15<br>
                Total: <span class="average">0</span>/100
            </div>
        </div>
        <div class="factor-box technological">
            <h3>Tecnológico</h3>
            <div class="score">0</div>
            <div class="factor-details">
                Preguntas: 16-20<br>
                Total: <span class="average">0</span>/100
            </div>
        </div>
        <div class="factor-box environmental">
            <h3>Medioambiental</h3>
            <div class="score">0</div>
            <div class="factor-details">
                Preguntas: 21-25<br>
                Total: <span class="average">0</span>/100
            </div>
        </div>
    </div>
</div>

<div class="evaluation-results">
    <div id="political-eval"></div>
    <div id="economic-eval"></div>
    <div id="social-eval"></div>
    <div id="tech-eval"></div>
    <div id="enviromental-eval"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Función para mapear los valores de los combobox a la nueva escala
function mapValueToScale(value) {
    switch(parseInt(value)) {
        case 1: return 0;   // Total desacuerdo
        case 2: return 5;   // No está de acuerdo
        case 3: return 10;  // Está de acuerdo
        case 4: return 15;  // Está bastante de acuerdo
        case 5: return 20;  // En total acuerdo
        default: return 0;
    }
}

// Función para calcular los resultados
function calculateResults() {
    // Inicializar sumas por categoría
    let social = 0, political = 0, economic = 0, technological = 0, environmental = 0;
    
    // Calcular suma para factores sociales (preguntas 1-5)
    for(let i = 1; i <= 5; i++) {
        const value = document.querySelector(`select[name="pregunta${i}"]`).value;
        social += mapValueToScale(value);
    }
    
    // Calcular suma para factores políticos (preguntas 6-10)
    for(let i = 6; i <= 10; i++) {
        const value = document.querySelector(`select[name="pregunta${i}"]`).value;
        political += mapValueToScale(value);
    }
    
    // Calcular suma para factores económicos (preguntas 11-15)
    for(let i = 11; i <= 15; i++) {
        const value = document.querySelector(`select[name="pregunta${i}"]`).value;
        economic += mapValueToScale(value);
    }
    
    // Calcular suma para factores tecnológicos (preguntas 16-20)
    for(let i = 16; i <= 20; i++) {
        const value = document.querySelector(`select[name="pregunta${i}"]`).value;
        technological += mapValueToScale(value);
    }
    
    // Calcular suma para factores medioambientales (preguntas 21-25)
    for(let i = 21; i <= 25; i++) {
        const value = document.querySelector(`select[name="pregunta${i}"]`).value;
        environmental += mapValueToScale(value);
    }
    
    // Actualizar el gráfico con los resultados
    updatePestChart(social, political, economic, technological, environmental);
}

// Configuración del gráfico con escala 0-100
let pestChart;

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('pestChart').getContext('2d');
    
    pestChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Social', 'Político', 'Económico', 'Tecnológico', 'Medioambiental'],
            datasets: [{
                label: 'Puntuación P.E.S.T',
                data: [0, 0, 0, 0, 0],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20,
                        callback: function(value) {
                            return value;
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Puntuación: ${context.raw}/100`;
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Ejecutar cálculo cuando cambie cualquier combobox
    document.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', calculateResults);
    });
    
    // Calcular resultados iniciales
    calculateResults();
});

// Función para actualizar el gráfico con los nuevos valores
function updatePestChart(social, political, economic, technological, environmental) {
    // Actualizar gráfico
    pestChart.data.datasets[0].data = [social, political, economic, technological, environmental];
    pestChart.update();
    
    // Actualizar resumen
    document.querySelector('.social .score').textContent = social;
    document.querySelector('.political .score').textContent = political;
    document.querySelector('.economic .score').textContent = economic;
    document.querySelector('.technological .score').textContent = technological;
    document.querySelector('.environmental .score').textContent = environmental;
    
    document.querySelector('.social .average').textContent = social;
    document.querySelector('.political .average').textContent = political;
    document.querySelector('.economic .average').textContent = economic;
    document.querySelector('.technological .average').textContent = technological;
    document.querySelector('.environmental .average').textContent = environmental;
    // Llamar después de actualizar el gráfico
    evaluateResults();
}

// Función para evaluar y mostrar resultados
function evaluateResults() {
    const scores = pestChart.data.datasets[0].data;
    
    // Evaluar cada factor
    document.getElementById('political-eval').innerHTML = 
        scores[1] >= 70 ? 
        '<p class="high-impact">✓ Hay un notable impacto de factores políticos en el funcionamiento de la empresa</p>' :
        '<p class="low-impact">✗ No hay un notable impacto de factores políticos en el funcionamiento de la empresa</p>';

    document.getElementById('economic-eval').innerHTML = 
        scores[2] >= 70 ?
        '<p class="high-impact">✓ Hay un notable impacto de factores económicos en el funcionamiento de la empresa</p>' :
        '<p class="low-impact">✗ No hay un notable impacto de factores económicos en el funcionamiento de la empresa</p>';

    document.getElementById('social-eval').innerHTML = 
        scores[0] >= 70 ?
        '<p class="high-impact">✓ Hay un notable impacto de factores sociales en el funcionamiento de la empresa</p>' :
        '<p class="low-impact">✗ No hay un notable impacto de factores sociales en el funcionamiento de la empresa</p>';

    document.getElementById('tech-eval').innerHTML = 
        scores[3] >= 70 ?
        '<p class="high-impact">✓ Hay un notable impacto de factores tecnológicos en el funcionamiento de la empresa</p>' :
        '<p class="low-impact">✗ No hay un notable impacto de factores tecnológicos en el funcionamiento de la empresa</p>';
     document.getElementById('enviromental-eval').innerHTML = 
        scores[4] >= 70 ?
        '<p class="high-impact">✓ Hay un notable impacto de factores medio ambientales en el funcionamiento de la empresa</p>' :
        '<p class="low-impact">✗ No hay un notable impacto de factores medio ambientales en el funcionamiento de la empresa</p>';
}





</script>


<style>
/* Estilos mejorados para la sección de resultados */
.results-container {
    margin: 40px auto;
    padding: 25px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    max-width: 1000px;
}

.results-container h2 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 25px;
    font-size: 24px;
}

.chart-container {
    height: 400px;
    margin: 30px 0;
    position: relative;
}

.factors-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.factor-box {
    padding: 15px;
    border-radius: 8px;
    color: white;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.factor-box:hover {
    transform: translateY(-5px);
}

.factor-box h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
}

.factor-box .score {
    font-size: 28px;
    font-weight: bold;
    margin: 10px 0;
}

.factor-box .factor-details {
    font-size: 14px;
    opacity: 0.9;
}


.evaluation-results {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}
.high-impact {
    color: #28a745;
    font-weight: bold;
}
.low-impact {
    color: #dc3545;
}


/* Colores por categoría */
.social { background: linear-gradient(135deg, #36a2eb 0%, #2a80ba 100%); }
.political { background: linear-gradient(135deg, #ff6384 0%, #d4506b 100%); }
.economic { background: linear-gradient(135deg, #4bc0c0 0%, #3a9a9a 100%); }
.technological { background: linear-gradient(135deg, #ff9f40 0%, #e07f30 100%); }
.environmental { background: linear-gradient(135deg, #9966ff 0%, #7a4fd8 100%); }

/* Responsive */
@media (max-width: 768px) {
    .chart-container {
        height: 300px;
    }
    
    .factors-summary {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .factors-summary {
        grid-template-columns: 1fr;
    }
    
    .results-container {
        padding: 15px;
    }
}
</style>

</body>
</html>