<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadena de Valor</title>
  <link rel="stylesheet" href="../public/css/cadena_valor.css">
  <link rel="stylesheet" href="../public/css/main.css">
  <link rel="stylesheet" href="../public/css/plan-estrategico.css">  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
    }
    .container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .header {
      text-align: center;
      padding: 20px;
      background-color: #4CAF50;
      color: white;
      border-radius: 8px;
      margin-bottom: 30px;
    }
    .fortalezas-debilidades {
      margin-top: 40px;
      padding: 20px;
      background-color: #f8f9fa;
      border-radius: 8px;
      box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }
    .foda-section {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
    }
    .fortalezas, .debilidades {
      flex: 1;
      min-width: 300px;
    }
    .fortalezas {
      background-color: #d4edda;
      padding: 15px;
      border-radius: 5px;
      border-left: 5px solid #28a745;
    }
    .debilidades {
      background-color: #f8d7da;
      padding: 15px;
      border-radius: 5px;
      border-left: 5px solid #dc3545;
    }
    .item-input {
      display: flex;
      margin-bottom: 10px;
      align-items: center;
    }
    .item-input input {
      flex: 1;
      padding: 8px;
      margin-right: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .btn-remove {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 3px;
      cursor: pointer;
    }
    .btn-add {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      margin-top: 10px;
      cursor: pointer;
    }
    .navigation-buttons {
      margin-top: 30px;
      text-align: center;
    }
    .navigation-buttons a, .navigation-buttons button {
      padding: 12px 25px;
      margin: 0 10px;
      text-decoration: none;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .btn-primary { background-color: #28a745; color: white; }
    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-danger { background-color: #dc3545; color: white; }
    
    /* Mejoras para la tabla */
    .table-container {
      overflow-x: auto;
      width: 100%;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px 8px;
      text-align: center;
      font-size: 14px;
    }
    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }
    .statement {
      text-align: left;
      padding-left: 10px;
    }
    .header-row th {
      background-color: #4CAF50;
      color: white;
      padding: 12px 8px;
    }
    input[type="radio"] {
      margin: 0 auto;
      display: block;
      transform: scale(1.2);
    }
    
    /* Mejoras responsivas */
    @media (max-width: 768px) {
      .foda-section {
        flex-direction: column;
      }
      .statement {
        font-size: 13px;
      }
      th, td {
        padding: 6px 4px;
        font-size: 12px;
      }
    }
  </style>
</head>
<body>    <div class="header">
        <h1>Paso 5: Cadena de Valor</h1>
        <p>Usuario: <?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></p>
    </div>

    <div class="container">
        <h2 style="text-align: center; margin-bottom: 20px;">Cadena de Valor</h2>
        <p style="text-align: center; margin-bottom: 25px;">A continuación marque con una X para valorar su empresa en función de cada una de las afirmaciones, 
          de tal forma que 0= En total en desacuerdo; 1= No está de acuerdo; 2=Está de acuerdo; 3= Está bastante de acuerdo; 
          4=En total acuerdo.</p>

        <form id="evaluationForm" action="../index.php?controller=PlanEstrategico&action=guardarPaso" method="POST">
            <input type="hidden" name="paso" value="5">
            <input type="hidden" name="nombre_paso" value="cadena_valor">
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr class="header-row">
                            <th class="statement">AUTODIAGNÓSTICO DE LA CADENA DE VALOR INTERNA</th>
                            <th colspan="5">VALORACIÓN</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>0</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="statement">1. La empresa tiene una política sistematizada de cero defectos en la producción de productos/servicios.</td>
                            <td><input type="radio" name="q1" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q1" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q1" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q1" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q1" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">2. La empresa emplea los medios productivos tecnológicamente más avanzados de su sector.</td>
                            <td><input type="radio" name="q2" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q2" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q2" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q2" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q2" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">3. La empresa dispone de un sistema de información y control de gestión  eficiente y eficaz. </td>
                            <td><input type="radio" name="q3" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q3" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q3" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q3" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q3" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">4.Los medios técnicos y técnológicos de la empresa están preparados para competir en un futuro a corto, medio y largo plazo.</td>
                            <td><input type="radio" name="q4" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q4" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q4" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q4" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q4" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">5. La empresa es un referente en su sector en I+D+i.</td>
                            <td><input type="radio" name="q5" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q5" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q5" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q5" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q5" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">6. La excelencia de los procedimientos de la empresa (en ISO, etc.) son una principal fuente de ventaja competiva.</td>
                            <td><input type="radio" name="q6" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q6" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q6" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q6" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q6" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">7. La empresa dispone de página web, y esta se emplea no sólo como escaparate virtual de productos/servicios, sino también para establecer relaciones con clientes y proveedores.</td>
                            <td><input type="radio" name="q7" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q7" value="1" class="respuesta" ></td>
                            <td><input type="radio" name="q7" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q7" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q7" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">8. Los productos/servicios que desarrolla nuestra empresa llevan incorporada una tecnología difícil de imitar.</td>
                            <td><input type="radio" name="q8" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q8" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q8" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q8" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q8" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">9.  La empresa es referente in su sector en la optimización, en términos de coste,  de su cadena de producción, siendo ésta una de sus principales ventajas competitivas.</td>
                            <td><input type="radio" name="q9" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q9" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q9" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q9" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q9" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">10.  La informatización de la empresa es una fuente de ventaja competitiva clara respecto a sus competidores.</td>
                            <td><input type="radio" name="q10" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q10" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q10" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q10" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q10" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">11.  Los canales de distribución de la empresa son una importante fuente de ventajas competitivas.</td>
                            <td><input type="radio" name="q11" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q11" value="1" class="respuesta" ></td>
                            <td><input type="radio" name="q11" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q11" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q11" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">12. Los productos/servicios de la empresa son altamente, y diferencialmente, valorados por el cliente respecto a nuestros competidores.</td>
                            <td><input type="radio" name="q12" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q12" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q12" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q12" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q12" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">13. La empresa dispone y ejecuta un sistematico plan de marketing y ventas.</td>
                            <td><input type="radio" name="q13" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q13" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q13" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q13" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q13" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">14. La empresa tiene optimizada su gestión financiera.</td>
                            <td><input type="radio" name="q14" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q14" value="1" class="respuesta" ></td>
                            <td><input type="radio" name="q14" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q14" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q14" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">15. La empresa busca continuamente el mejorar la relación con sus clientes cortando los plazos de ejecución, personalizando la oferta o mejorando las condiciones de entrega. Pero siempre partiendo de un plan previo.</td>
                            <td><input type="radio" name="q15" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q15" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q15" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q15" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q15" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">16. La empresa es referente en su sector en el lanzamiento de innovadores productos y servicio de éxito demostrado en el mercado.</td>
                            <td><input type="radio" name="q16" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q16" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q16" value="2" class="respuesta" ></td>
                            <td><input type="radio" name="q16" value="3" class="respuesta" ></td>
                            <td><input type="radio" name="q16" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">17.  Los Recursos Humanos son especialmente responsables del éxito de la empresa, considerándolos incluso como el principal activo estratégico.</td>
                            <td><input type="radio" name="q17" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q17" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q17" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q17" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q17" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">18. Se tiene una plantilla altamente motivada, que conoce con claridad las metas, objetivos y estrategias de la organización.</td>
                            <td><input type="radio" name="q18" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q18" value="1" class="respuesta" ></td>
                            <td><input type="radio" name="q18" value="2" class="respuesta" ></td>
                            <td><input type="radio" name="q18" value="3" class="respuesta" ></td>
                            <td><input type="radio" name="q18" value="4" class="respuesta" ></td>
                        </tr>
                        <tr>
                            <td class="statement">19. La empresa siempre trabaja conforme a una estrategia y objetivos claros. </td>
                            <td><input type="radio" name="q19" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q19" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q19" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q19" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q19" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">20.  La gestión del circulante está optimizada.</td>
                            <td><input type="radio" name="q20" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q20" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q20" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q20" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q20" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">21. Se tiene definido claramente el posicionamiento estratégico de todos los productos de la empresa.</td>
                            <td><input type="radio" name="q21" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q21" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q21" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q21" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q21" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">22. Se dispone de una política de marca basada en la reputación que la empresa genera, en la gestión de relación con el cliente y en el posicionamiento estratégico previamente definido.</td>
                            <td><input type="radio" name="q22" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q22" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q22" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q22" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q22" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">23.La cartera de clientes de nuestra empresa está altamente fidelizada, ya que tenemos como principal propósito el deleitarlos día a día. </td>
                            <td><input type="radio" name="q23" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q23" value="1" class="respuesta" ></td>
                            <td><input type="radio" name="q23" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q23" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q23" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">24. Nuestra política y equipo de ventas y marketing es una importante ventaja competitiva de nuestra empresa respecto al sector.</td>
                            <td><input type="radio" name="q24" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q24" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q24" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q24" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q24" value="4" class="respuesta"></td>
                        </tr>
                        <tr>
                            <td class="statement">25.  El servicio al cliente que prestamos es uno de nuestras principales ventajas competitivas respecto a nuestros competidores.</td>
                            <td><input type="radio" name="q25" value="0" class="respuesta" required></td>
                            <td><input type="radio" name="q25" value="1" class="respuesta"></td>
                            <td><input type="radio" name="q25" value="2" class="respuesta"></td>
                            <td><input type="radio" name="q25" value="3" class="respuesta"></td>
                            <td><input type="radio" name="q25" value="4" class="respuesta"></td>
                        </tr>
                        <tr class="result-row">
                            <td colspan="5" class="statement">POTENCIAL DE MEJORA DE LA CADENA DE VALOR INTERNA</td>
                            <td id="result-percentage">0%</td>
                        </tr>
                    </tbody>                </table>
            </div>

            <div class="reflection-section" style="margin: 20px 0;">
                <label for="resultado" style="display: block; font-weight: bold; margin-bottom: 10px;">
                    Reflexione sobre el resultado obtenido. Anote aquellas observaciones que puedan ser de su interés:
                </label>
                <textarea 
                    id="resultado" 
                    name="resultado" 
                    rows="5" 
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: Arial, sans-serif;"
                    placeholder="Escriba aquí sus observaciones sobre los resultados obtenidos...">
                </textarea>
            </div>

            <div class="buttons" style="margin: 20px 0; text-align: center;">
                <button type="button" id="btn-calcular" class="btn btn-primary">Calcular</button>
            </div>

            <!-- Sección de Fortalezas y Debilidades -->
            <div class="fortalezas-debilidades">
                <h3 style="text-align: center; margin-bottom: 15px;">Análisis de Fortalezas y Debilidades</h3>
                <p style="text-align: center; margin-bottom: 20px;">Basándose en la evaluación de la cadena de valor, identifique las principales fortalezas y debilidades de su empresa:</p>
                
                <div class="foda-section">
                    <div class="fortalezas">
                        <h4 style="color: #28a745; margin-bottom: 15px; text-align: center;">🟢 FORTALEZAS</h4>
                        <div id="fortalezasContainer">
                                <div class="item-input">
                                    <input type="text" name="fortalezas[]" placeholder="Describa una fortaleza de su empresa..." required>
                                    <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add" onclick="addFortaleza()">+ Agregar Fortaleza</button>
                        </div>                        <div class="debilidades">
                            <h4 style="color: #dc3545; margin-bottom: 15px; text-align: center;">🔴 DEBILIDADES</h4>
                            <div id="debilidadesContainer">
                                <div class="item-input">
                                    <input type="text" name="debilidades[]" placeholder="Describa una debilidad de su empresa..." required>
                                    <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            </div>
                            <button type="button" class="btn-add" onclick="addDebilidad()">+ Agregar Debilidad</button>
                        </div>
                    </div>
                </div>                <!-- Campos ocultos para el porcentaje -->
                <input type="hidden" name="porcentaje" id="porcentaje-hidden">
            </form>

            <!-- Botones de navegación -->
            <div class="navigation-buttons">
                <button type="submit" form="evaluationForm" class="btn-primary">Siguiente</button>
                <a href="objetivos_estrategicos.php" class="btn-secondary">Anterior</a>
                <a href="home.php" class="btn-danger">Cancelar</a>
            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Función para calcular el porcentaje basado en las respuestas seleccionadas
    function calcularPorcentaje() {
        const inputs = document.querySelectorAll('input[type="radio"]:checked');
        let total = 0;
        
        // Verificar que todas las preguntas estén respondidas
        if (inputs.length < 25) {
            alert('Debes responder todas las preguntas antes de calcular');
            return null;
        }
        
        // Sumar los valores
        inputs.forEach(input => {
            total += parseInt(input.value);
        });
        
        // Calcular porcentaje (100 - porcentaje de mejora)
        // Valor máximo posible: 25 preguntas * 4 puntos = 100
        const porcentajeMejora = Math.round((1 - (total / 100)) * 100);
        return porcentajeMejora;
    }
    
    // Botón calcular
    const calcularButton = document.getElementById("btn-calcular");
    if (calcularButton) {
        calcularButton.addEventListener("click", function() {
            const porcentaje = calcularPorcentaje();
            if (porcentaje !== null) {
                document.getElementById("porcentaje-hidden").value = porcentaje;
                const tablePercentageCell = document.querySelector(".result-row #result-percentage");
                if (tablePercentageCell) {
                    tablePercentageCell.textContent = `${porcentaje}%`;
                }
            }
        });
    }
    
    // Manejar envío del formulario
    document.getElementById("evaluationForm").addEventListener("submit", function(e) {
        e.preventDefault();
        
        // Verificar que todas las preguntas tengan respuesta
        let todasRespondidas = true;
        for (let i = 1; i <= 25; i++) {
            const selected = document.querySelector(`input[name="q${i}"]:checked`);
            if (!selected) {
                todasRespondidas = false;
                alert(`Debes responder la pregunta ${i}`);
                break;
            }
        }
        
        if (!todasRespondidas) return;
        
        // Verificar textarea de resultado
        const textareaResultado = document.querySelector('textarea[name="resultado"]');
        if (!textareaResultado || textareaResultado.value.trim() === '') {
            alert('Debes completar la reflexión sobre los resultados');
            return;
        }
        
        // Verificar fortalezas y debilidades
        const fortalezas = document.querySelectorAll('input[name="fortalezas[]"]');
        const debilidades = document.querySelectorAll('input[name="debilidades[]"]');
        
        let fortalezasValidas = 0;
        let debilidadesValidas = 0;
        
        fortalezas.forEach(input => {
            if (input.value.trim() !== '') fortalezasValidas++;
        });
        
        debilidades.forEach(input => {
            if (input.value.trim() !== '') debilidadesValidas++;
        });
        
        if (fortalezasValidas === 0) {
            alert('Debe agregar al menos una fortaleza');
            return;
        }
        
        if (debilidadesValidas === 0) {
            alert('Debe agregar al menos una debilidad');
            return;
        }
        
        const porcentaje = calcularPorcentaje();
        if (porcentaje === null) return;
          document.getElementById("porcentaje-hidden").value = porcentaje;
        
        // Enviar formulario con AJAX
        const formData = new FormData(this);
        
        fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'matriz_bcg.php';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar los datos');
        });
    });
});

// Funciones para agregar/quitar fortalezas y debilidades
function addFortaleza() {
    const container = document.getElementById('fortalezasContainer');
    const newDiv = document.createElement('div');
    newDiv.className = 'item-input';
    newDiv.innerHTML = `
        <input type="text" name="fortalezas[]" placeholder="Describa una fortaleza de su empresa...">
        <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
    `;
    container.appendChild(newDiv);
}

function addDebilidad() {
    const container = document.getElementById('debilidadesContainer');
    const newDiv = document.createElement('div');
    newDiv.className = 'item-input';
    newDiv.innerHTML = `
        <input type="text" name="debilidades[]" placeholder="Describa una debilidad de su empresa...">
        <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
    `;
    container.appendChild(newDiv);
}

function removeItem(button) {
    // No permitir eliminar si es el único elemento
    const container = button.parentElement.parentElement;
    if (container.children.length > 1) {
        button.parentElement.remove();
    } else {
        alert('Debe mantener al menos un elemento');
    }
}
</script>

</body>
</html>