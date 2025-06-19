<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Cargar datos de Matriz BCG desde sesión si existen
$plan_temporal = $_SESSION['plan_temporal'] ?? [];
$datos_bcg = $plan_temporal['matriz_bcg'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz BCG</title>
    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/plan-estrategico.css">    <style>
        /* Aplicación de diseño simple para la página Matriz BCG */
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
.text-center {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}
.section-title {
    font-size: 18px;
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 15px;
}
input[type="number"], button {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}
button {
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
}
button:hover {
    background-color: #45a049;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}
th {
    background-color: #4CAF50;
    color: white;
}
tfoot td {
    background-color: #f0f0f0;
    font-weight: bold;
}        .table-container {
            overflow-x: auto;
            width: 100%;
            margin: 20px 0;
        }
        
        #paso1 {
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #4CAF50;
            max-width: 800px;
            margin: 0 auto 20px;
        }
        
        #tablaTCM, #tablaDemandaGlobal, #tablaCompetidores, #tablaMatrizBCG {
            width: 100%;
            margin: 0 auto;
        }
          /* Estilos específicos para tabla TCM */
        #tablaTCM { border: 2px solid #6D4C41; }
        #tablaTCM th { text-align: center; font-weight: bold; }
        #tablaTCM td { vertical-align: middle; }
        #tablaTCM input[type="number"] { 
            background: #e3f2fd; 
            border: 2px solid #2196f3; 
            color: #1976d2; 
            font-weight: bold; 
        }
        #tablaTCM input[type="number"]:focus { 
            outline: none; 
            border-color: #0d47a1; 
            box-shadow: 0 0 8px rgba(33, 150, 243, 0.3); 
        }
        .porcentaje-display { 
            font-size: 13px; 
            font-weight: bold; 
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1); 
        }
        
        /* Estilos específicos para tabla de Demanda Global */
        #tablaDemandaGlobal { border: 2px solid #9C27B0; }
        #tablaDemandaGlobal th { text-align: center; font-weight: bold; }
        #tablaDemandaGlobal td { vertical-align: middle; }
        #tablaDemandaGlobal input[type="number"] { 
            background: #f3e5f5; 
            border: 2px solid #9C27B0; 
            color: #7B1FA2; 
            font-weight: bold; 
        }
        #tablaDemandaGlobal input[type="number"]:focus { 
            outline: none; 
            border-color: #4A148C; 
            box-shadow: 0 0 8px rgba(156, 39, 176, 0.3); 
        }        .porcentaje-display-demanda { 
            font-size: 13px; 
            font-weight: bold; 
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1); 
        }
        
        /* Estilos específicos para tabla de Competidores */
        #tablaCompetidores { border: 2px solid #FF5722; }
        #tablaCompetidores th { text-align: center; font-weight: bold; }
        #tablaCompetidores td { vertical-align: middle; text-align: center; }
        #tablaCompetidores input[type="number"] { 
            background: #fff3e0; 
            border: 2px solid #FF5722; 
            color: #D84315; 
            font-weight: bold; 
        }
        #tablaCompetidores input[type="number"]:focus { 
            outline: none; 
            border-color: #BF360C; 
            box-shadow: 0 0 8px rgba(255, 87, 34, 0.3); 
        }
        #numCompetidores { 
            border: 2px solid #FF5722; 
            font-weight: bold; 
            color: #D84315; 
        }
          #anioInicio, #anioFin { 
            border: 2px solid #4CAF50; 
            font-weight: bold; 
            color: #2E7D32; 
        }
        
        /* Estilos para sección FODA */
        .foda-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .foda-item input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 10px;
        }
        
        .btn-eliminar {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }
        
        .btn-eliminar:hover {
            background: #c82333;
        }
        
        .btn-agregar {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background 0.3s;
        }
        
        .btn-agregar:hover {
            background: #218838;
        }    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Matriz BCG</h2>
        <form action="../index.php?controller=PlanEstrategico&action=guardarPaso" method="POST" class="form-center">
            <!-- Campo oculto para identificar el paso -->
            <input type="hidden" name="paso" value="6">
            <input type="hidden" name="nombre_paso" value="matriz_bcg">
            
            <div class="section-title">Previsión de Ventas</div>
            
            <!-- Paso 1: Definir cantidad de productos -->
            <div id="paso1" style="margin-bottom: 20px;">
                <label for="numProductos" style="font-weight: bold;">¿Cuántos productos desea ingresar?</label>
                <input type="number" id="numProductos" min="1" max="20" placeholder="Ej: 5" style="margin-left: 10px; padding: 5px;">
                <button type="button" class="add-btn" onclick="generarTablaProductos()" style="margin-left: 10px;">Generar Tabla</button>
            </div>

            <!-- Paso 2: Tabla de productos (inicialmente oculta) -->            <div id="paso2" style="display: none; overflow-x: auto; width: 100%;">
                <table id="tablaProductos" style="width: 100%; margin: 0 auto;">
                    <thead>
                        <tr style="background-color: #4CAF50; color: white;">
                            <th>PRODUCTOS</th>
                            <th>VENTAS</th>
                            <th>% S/ TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las filas se generarán dinámicamente -->
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f0f0f0; font-weight: bold;">
                            <td>TOTAL</td>
                            <td id="totalVentas">0</td>
                            <td id="totalPorcentaje">0.00%</td>
                        </tr>
                    </tfoot>                </table>

                <!-- Configuración de períodos para TCM -->
                <div style="margin-top: 30px;">
                    <div class="section-title">Configuración de Períodos</div>
                    <div style="text-align: center; margin: 20px 0;">
                        <label for="anioInicio" style="font-weight: bold; margin-right: 10px;">Año de Inicio:</label>
                        <input type="number" id="anioInicio" min="2000" max="2030" placeholder="2012" 
                               style="width: 80px; text-align: center; padding: 8px; margin-right: 20px;">
                        
                        <label for="anioFin" style="font-weight: bold; margin-right: 10px;">Año de Fin:</label>
                        <input type="number" id="anioFin" min="2000" max="2030" placeholder="2016" 
                               style="width: 80px; text-align: center; padding: 8px; margin-right: 20px;">
                        
                        <button type="button" class="add-btn" onclick="generarTablaTCM()">Generar Tabla TCM</button>
                    </div>
                </div>

                <!-- Tabla de Tasas de Crecimiento del Mercado (TCM) -->                <div id="sectionTCM" style="display: none; margin-top: 30px;">
                    <div class="section-title" style="background: #8BC34A;">TASAS DE CRECIMIENTO DEL MERCADO (TCM)</div>
                    <div style="overflow-x: auto; width: 100%;">
                        <table id="tablaTCM" style="margin-top: 15px; width: 100%;">
                            <thead>
                                <tr style="background-color: #6D4C41; color: white;">
                                    <th rowspan="2" style="vertical-align: middle;">PERIODOS</th>
                                    <th colspan="100%" style="text-align: center;">MERCADOS</th>
                                </tr>
                                <tr id="headerProductos" style="background-color: #6D4C41; color: white;">
                                    <!-- Se llenarán dinámicamente los nombres de productos -->
                                </tr>
                            </thead>
                            <tbody id="bodyTCM">
                                <!-- Se llenarán dinámicamente los períodos y datos -->
                            </tbody>
                        </tbody>
                    </table>                </div>

                <!-- Tabla de Evolución de la Demanda Global del Sector -->
                <div id="sectionDemandaGlobal" style="display: none; margin-top: 30px;">                    <div class="section-title" style="background: #9C27B0;">EVOLUCIÓN DE LA DEMANDA GLOBAL SECTOR (en miles de soles)</div>
                    <div style="overflow-x: auto; width: 100%;">
                        <table id="tablaDemandaGlobal" style="margin-top: 15px; width: 100%;">
                            <thead>
                                <tr style="background-color: #6D4C41; color: white;">
                                    <th rowspan="2" style="vertical-align: middle;">AÑOS</th>
                                    <th colspan="100%" style="text-align: center;">MERCADOS</th>
                                </tr>
                                <tr id="headerProductosDemanda" style="background-color: #6D4C41; color: white;">
                                    <!-- Se llenarán dinámicamente los nombres de productos -->
                                </tr>
                            </thead>
                            <tbody id="bodyDemandaGlobal">
                                <!-- Se llenarán dinámicamente los años y datos -->
                            </tbody>
                        </table>
                    </div>                </div>

                <!-- Configuración de Competidores -->
                <div id="sectionConfigCompetidores" style="display: none; margin-top: 30px;">
                    <div class="section-title">Configuración de Competidores</div>
                    <div style="text-align: center; margin: 20px 0;">
                        <label for="numCompetidores" style="font-weight: bold; margin-right: 10px;">¿Cuántos competidores desea agregar?</label>
                        <input type="number" id="numCompetidores" min="1" max="20" placeholder="9" 
                               style="width: 80px; text-align: center; padding: 8px; margin-right: 20px;">
                        
                        <button type="button" class="add-btn" onclick="generarTablaCompetidores()">Generar Tabla de Competidores</button>
                    </div>
                </div>

                <!-- Tabla de Niveles de Venta de los Competidores -->                <div id="sectionCompetidores" style="display: none; margin-top: 30px;">
                    <div class="section-title" style="background: #FF5722;">NIVELES DE VENTA DE LOS COMPETIDORES DE CADA PRODUCTO</div>
                    <div style="overflow-x: auto; width: 100%;">
                        <table id="tablaCompetidores" style="margin-top: 15px; width: 100%;">
                            <thead id="headerCompetidores">
                                <!-- Se generará dinámicamente -->
                            </thead>
                            <tbody id="bodyCompetidores">
                                <!-- Se generará dinámicamente -->
                            </tbody>
                        </table>
                    </div>                </div>

                <!-- Tabla Resumen Matriz BCG -->                <div id="sectionMatrizBCG" style="display: none; margin-top: 30px;">
                    <div class="section-title" style="background: #2196F3;">MATRIZ BCG - RESUMEN</div>
                    <div style="overflow-x: auto; width: 100%;">
                        <table id="tablaMatrizBCG" style="margin-top: 15px; border: 2px solid #2196F3; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="background: #1976D2; color: white; padding: 12px; font-weight: bold;">BCG</th>
                                    <th id="headerBCGProducto1" style="background: #4CAF50; color: white; padding: 12px;">Producto 1</th>
                                    <th id="headerBCGProducto2" style="background: #FF9800; color: white; padding: 12px;">Producto 2</th>
                                <th id="headerBCGProducto3" style="background: #F44336; color: white; padding: 12px;">Producto 3</th>
                                <th id="headerBCGProducto4" style="background: #9C27B0; color: white; padding: 12px;">Producto 4</th>
                                <th id="headerBCGProducto5" style="background: #00BCD4; color: white; padding: 12px;">Producto 5</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="background: #E3F2FD; font-weight: bold; padding: 12px; text-align: center;">TCM</td>
                                <td id="bcgTCM0" style="background: #E8F5E8; text-align: center; padding: 12px; font-weight: bold; color: #2E7D32;">1.00%</td>
                                <td id="bcgTCM1" style="background: #FFF3E0; text-align: center; padding: 12px; font-weight: bold; color: #E65100;">1.00%</td>
                                <td id="bcgTCM2" style="background: #FFEBEE; text-align: center; padding: 12px; font-weight: bold; color: #C62828;">1.00%</td>
                                <td id="bcgTCM3" style="background: #F3E5F5; text-align: center; padding: 12px; font-weight: bold; color: #7B1FA2;">1.00%</td>
                                <td id="bcgTCM4" style="background: #E0F2F1; text-align: center; padding: 12px; font-weight: bold; color: #00695C;">1.00%</td>
                            </tr>
                            <tr>
                                <td style="background: #E3F2FD; font-weight: bold; padding: 12px; text-align: center;">PRM</td>
                                <td id="bcgPRM0" style="background: #E8F5E8; text-align: center; padding: 12px; font-weight: bold; color: #2E7D32;">1.00</td>
                                <td id="bcgPRM1" style="background: #FFF3E0; text-align: center; padding: 12px; font-weight: bold; color: #E65100;">1.00</td>
                                <td id="bcgPRM2" style="background: #FFEBEE; text-align: center; padding: 12px; font-weight: bold; color: #C62828;">1.00</td>
                                <td id="bcgPRM3" style="background: #F3E5F5; text-align: center; padding: 12px; font-weight: bold; color: #7B1FA2;">1.00</td>
                                <td id="bcgPRM4" style="background: #E0F2F1; text-align: center; padding: 12px; font-weight: bold; color: #00695C;">1.00</td>
                            </tr>
                            <tr>
                                <td style="background: #E3F2FD; font-weight: bold; padding: 12px; text-align: center;">% S/VTAS</td>
                                <td id="bcgVentas0" style="background: #E8F5E8; text-align: center; padding: 12px; font-weight: bold; color: #2E7D32;">7%</td>
                                <td id="bcgVentas1" style="background: #FFF3E0; text-align: center; padding: 12px; font-weight: bold; color: #E65100;">7%</td>
                                <td id="bcgVentas2" style="background: #FFEBEE; text-align: center; padding: 12px; font-weight: bold; color: #C62828;">7%</td>
                                <td id="bcgVentas3" style="background: #F3E5F5; text-align: center; padding: 12px; font-weight: bold; color: #7B1FA2;">7%</td>
                                <td id="bcgVentas4" style="background: #E0F2F1; text-align: center; padding: 12px; font-weight: bold; color: #00695C;">7%</td>
                            </tr>
                        </tbody>
                    </table>                    <div style="text-align: center; margin-top: 15px;">
                        <button type="button" class="add-btn" onclick="generarGraficoBCG()">Generar Gráfico de Matriz BCG</button>
                    </div>
                </div>

                <!-- Gráfico Visual de la Matriz BCG -->
                <div id="sectionGraficoBCG" style="display: none; margin-top: 30px;">
                    <div class="section-title" style="background: #673AB7;">GRÁFICO DE LA MATRIZ BCG</div>
                    
                    <!-- Canvas del gráfico -->
                    <div style="background: white; border: 3px solid #673AB7; border-radius: 8px; margin: 20px auto; max-width: 800px; position: relative;">
                        <canvas id="canvasBCG" width="800" height="600" style="display: block; margin: 0 auto; cursor: crosshair;"></canvas>
                        
                        <!-- Leyenda de cuadrantes -->
                        <div style="position: absolute; top: 10px; left: 10px; font-size: 40px;">❓</div>
                        <div style="position: absolute; top: 10px; right: 10px; font-size: 40px;">⭐</div>
                        <div style="position: absolute; bottom: 10px; left: 10px; font-size: 40px;">🐕</div>
                        <div style="position: absolute; bottom: 10px; right: 10px; font-size: 40px;">🐄</div>
                        
                        <!-- Etiquetas de los ejes -->
                        <div style="position: absolute; top: 50%; left: -80px; transform: rotate(-90deg); font-weight: bold; font-size: 14px; color: #673AB7;">
                            CRECIMIENTO (%)
                        </div>
                        <div style="position: absolute; bottom: -40px; left: 50%; transform: translateX(-50%); font-weight: bold; font-size: 14px; color: #673AB7;">
                            PARTICIPACIÓN RELATIVA EN EL MERCADO (→)
                        </div>
                    </div>
                    
                    <!-- Información de los cuadrantes -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                        <div style="background: #fff3e0; padding: 15px; border-radius: 8px; border-left: 5px solid #ff9800;">
                            <h4 style="margin: 0 0 10px 0; color: #e65100;">❓ INCÓGNITA</h4>
                            <p style="margin: 0; font-size: 12px;">Alto crecimiento, baja participación. Requieren inversión para convertirse en estrellas.</p>
                        </div>
                        <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; border-left: 5px solid #2196f3;">
                            <h4 style="margin: 0 0 10px 0; color: #1976d2;">⭐ ESTRELLA</h4>
                            <p style="margin: 0; font-size: 12px;">Alto crecimiento, alta participación. Líderes del mercado en crecimiento.</p>
                        </div>
                        <div style="background: #ffebee; padding: 15px; border-radius: 8px; border-left: 5px solid #f44336;">
                            <h4 style="margin: 0 0 10px 0; color: #c62828;">🐕 PERRO</h4>
                            <p style="margin: 0; font-size: 12px;">Bajo crecimiento, baja participación. Candidatos para desinversión.</p>
                        </div>
                        <div style="background: #e8f5e8; padding: 15px; border-radius: 8px; border-left: 5px solid #4caf50;">
                            <h4 style="margin: 0 0 10px 0; color: #2e7d32;">🐄 VACA</h4>
                            <p style="margin: 0; font-size: 12px;">Bajo crecimiento, alta participación. Generadores de efectivo.</p>
                        </div>
                    </div>
                    
                    <!-- Lista de productos y su clasificación -->
                    <div id="clasificacionProductos" style="margin-top: 20px; background: #f8f9fa; padding: 20px; border-radius: 8px;">
                        <h4 style="margin: 0 0 15px 0; color: #673AB7;">Clasificación de Productos:</h4>
                        <div id="listaClasificacion"></div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div style="text-align: center; margin-top: 30px;">
                    <button type="button" onclick="volverAlPaso1()" style="background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 4px; margin-right: 10px;">
                        ← Cambiar Cantidad de Productos
                    </button>
                    <button type="submit" class="add-btn" style="padding: 10px 30px; font-size: 16px;">
                        Guardar y Continuar →
                    </button>
                </div>
            </div>            <div class="section-title">Fortalezas y Debilidades</div>
            
            <!-- Fortalezas -->
            <div style="margin-bottom: 20px; text-align: center;">
                <label style="font-weight: bold; color: #2e7d32; display: block; margin-bottom: 10px;">Fortalezas:</label>
                <div id="fortalezasContainer" style="max-width: 800px; margin: 0 auto;">
                    <div class="foda-item">
                        <input type="text" name="fortalezas[]" placeholder="Escriba una fortaleza..." style="width: 90%; margin-bottom: 5px;">
                        <button type="button" onclick="eliminarItem(this)" class="btn-eliminar">×</button>
                    </div>
                </div>
                <button type="button" onclick="agregarItem('fortalezasContainer', 'fortalezas')" class="btn-agregar">+ Agregar Fortaleza</button>
            </div>

            <!-- Debilidades -->
            <div style="margin-bottom: 20px; text-align: center;">
                <label style="font-weight: bold; color: #d32f2f; display: block; margin-bottom: 10px;">Debilidades:</label>
                <div id="debilidadesContainer" style="max-width: 800px; margin: 0 auto;">
                    <div class="foda-item">
                        <input type="text" name="debilidades[]" placeholder="Escriba una debilidad..." style="width: 90%; margin-bottom: 5px;">
                        <button type="button" onclick="eliminarItem(this)" class="btn-eliminar">×</button>
                    </div>
                </div>
                <button type="button" onclick="agregarItem('debilidadesContainer', 'debilidades')" class="btn-agregar">+ Agregar Debilidad</button>
            </div>
        </form>
    </div>    <script>
        let numProductos = 0;        // Funciones para manejar elementos de Fortalezas y Debilidades
        function agregarItem(containerId, fieldName) {
            const container = document.getElementById(containerId);
            const newItem = document.createElement('div');
            newItem.className = 'foda-item';
            
            let placeholder = '';
            if (fieldName === 'fortalezas') {
                placeholder = 'Escriba una fortaleza...';
            } else if (fieldName === 'debilidades') {
                placeholder = 'Escriba una debilidad...';
            }
            
            newItem.innerHTML = `
                <input type="text" name="${fieldName}[]" placeholder="${placeholder}" style="width: 90%; margin-bottom: 5px;">
                <button type="button" onclick="eliminarItem(this)" class="btn-eliminar">×</button>
            `;
            container.appendChild(newItem);
        }

        function eliminarItem(button) {
            const item = button.parentElement;
            const container = item.parentElement;
            
            // No permitir eliminar si es el único elemento
            if (container.children.length > 1) {
                item.remove();
            } else {
                alert('Debe mantener al menos un elemento.');
            }
        }

        function generarTablaProductos() {
            numProductos = parseInt(document.getElementById('numProductos').value);
            
            if (numProductos < 1 || numProductos > 20) {
                alert('Por favor ingrese un número entre 1 y 20');
                return;
            }
              const tbody = document.getElementById('tablaProductos').querySelector('tbody');
            tbody.innerHTML = '';
            
            // Generar filas de la tabla principal
            for (let i = 1; i <= numProductos; i++) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <input type="text" name="productos[]" placeholder="Producto ${i}" 
                               style="width: 100%; border: none; text-align: center; font-weight: bold;" required>
                    </td>
                    <td>
                        <input type="number" name="ventas[]" min="0" step="0.01" 
                               placeholder="0" style="width: 100%; border: none; text-align: center;" 
                               onchange="calcularPorcentajes(); actualizarTablaCompetidores()" required>
                    </td>
                    <td>
                        <span class="porcentaje" style="font-weight: bold;">0.00%</span>
                        <input type="hidden" name="porcentaje_total[]" value="0">
                    </td>
                `;
                tbody.appendChild(row);
            }
            
            // Mostrar el paso 2
            document.getElementById('paso2').style.display = 'block';
            
            // Ocultar el paso 1
            document.getElementById('paso1').style.display = 'none';
            
            // Scroll hacia la tabla
            document.getElementById('paso2').scrollIntoView({ behavior: 'smooth' });
        }

        function calcularPorcentajes() {
            const ventasInputs = document.querySelectorAll('input[name="ventas[]"]');
            const porcentajeSpans = document.querySelectorAll('.porcentaje');
            const porcentajeHiddens = document.querySelectorAll('input[name="porcentaje_total[]"]');
            
            let totalVentas = 0;
            const ventas = [];
            
            // Calcular total de ventas
            ventasInputs.forEach((input, index) => {
                const valor = parseFloat(input.value) || 0;
                ventas[index] = valor;
                totalVentas += valor;
            });
            
            // Calcular y mostrar porcentajes
            ventasInputs.forEach((input, index) => {
                const porcentaje = totalVentas > 0 ? (ventas[index] / totalVentas) * 100 : 0;
                porcentajeSpans[index].textContent = porcentaje.toFixed(2) + '%';
                porcentajeHiddens[index].value = porcentaje.toFixed(2);
            });
            
            // Actualizar totales
            document.getElementById('totalVentas').textContent = totalVentas.toFixed(2);
            document.getElementById('totalPorcentaje').textContent = '100.00%';
        }        function volverAlPaso1() {
            document.getElementById('paso1').style.display = 'block';
            document.getElementById('paso2').style.display = 'none';
            document.getElementById('numProductos').value = '';
        }

        function generarTablaTCM() {
            const anioInicio = parseInt(document.getElementById('anioInicio').value);
            const anioFin = parseInt(document.getElementById('anioFin').value);
            
            if (!anioInicio || !anioFin || anioInicio >= anioFin) {
                alert('Por favor ingrese años válidos (el año de fin debe ser mayor al año de inicio)');
                return;
            }
            
            if (numProductos === 0) {
                alert('Primero debe generar la tabla de productos');
                return;
            }
            
            // Obtener nombres de productos desde la primera tabla
            const nombresProductos = [];
            const productosInputs = document.querySelectorAll('input[name="productos[]"]');
            productosInputs.forEach(input => {
                nombresProductos.push(input.value || `Producto ${nombresProductos.length + 1}`);
            });
            
            // Generar header de productos
            const headerProductos = document.getElementById('headerProductos');
            headerProductos.innerHTML = '';
            nombresProductos.forEach((nombre, index) => {
                const th = document.createElement('th');
                th.textContent = nombre;
                th.style.backgroundColor = getProductColor(index);
                th.style.color = 'white';
                th.style.padding = '10px';
                headerProductos.appendChild(th);
            });
            
            // Actualizar colspan del header MERCADOS
            document.querySelector('#tablaTCM thead tr:first-child th:last-child').setAttribute('colspan', numProductos);
            
            // Generar filas de períodos
            const bodyTCM = document.getElementById('bodyTCM');
            bodyTCM.innerHTML = '';
            
            for (let año = anioInicio; año < anioFin; año++) {
                const row = document.createElement('tr');
                
                // Columna de período
                const cellPeriodo = document.createElement('td');
                cellPeriodo.innerHTML = `<strong>${año}</strong><br><small>${año + 1}</small>`;
                cellPeriodo.style.backgroundColor = '#f8f9fa';
                cellPeriodo.style.fontWeight = 'bold';
                cellPeriodo.style.textAlign = 'center';
                cellPeriodo.style.padding = '12px';
                row.appendChild(cellPeriodo);
                
                // Columnas de productos
                nombresProductos.forEach((nombre, index) => {
                    const cellProducto = document.createElement('td');
                    cellProducto.style.backgroundColor = getProductColor(index, 0.1);
                    cellProducto.style.padding = '8px';
                    cellProducto.innerHTML = `                        <input type="number" 
                               name="tcm_${año}_${index}" 
                               step="0.01" 
                               placeholder="0.00"
                               style="width: 80px; text-align: center; border: 1px solid #007bff; border-radius: 4px; padding: 6px; background: #e3f2fd;"
                               onchange="convertirAPorcentaje(this); actualizarTCMEnBCG()"
                               onblur="convertirAPorcentaje(this)">
                        <div class="porcentaje-display" style="font-weight: bold; color: #1976d2; margin-top: 4px;">0.00%</div>
                    `;
                    row.appendChild(cellProducto);
                });
                
                bodyTCM.appendChild(row);
            }
              // Mostrar la sección TCM
            document.getElementById('sectionTCM').style.display = 'block';
            
            // Generar automáticamente la tabla de Demanda Global
            generarTablaDemandaGlobal(anioInicio, anioFin, nombresProductos);
            
            // Scroll hacia la tabla TCM
            document.getElementById('sectionTCM').scrollIntoView({ behavior: 'smooth' });
        }

        function getProductColor(index, alpha = 1) {
            const colors = [
                `rgba(33, 150, 243, ${alpha})`, // Azul
                `rgba(255, 193, 7, ${alpha})`,  // Amarillo
                `rgba(244, 67, 54, ${alpha})`,  // Rojo
                `rgba(76, 175, 80, ${alpha})`,  // Verde
                `rgba(156, 39, 176, ${alpha})`, // Púrpura
                `rgba(255, 87, 34, ${alpha})`,  // Naranja
                `rgba(96, 125, 139, ${alpha})`, // Gris azulado
                `rgba(121, 85, 72, ${alpha})`   // Marrón
            ];
            return colors[index % colors.length];
        }

        function convertirAPorcentaje(input) {
            const valor = parseFloat(input.value) || 0;
            const porcentajeDisplay = input.parentElement.querySelector('.porcentaje-display');
            porcentajeDisplay.textContent = valor.toFixed(2) + '%';
              // Cambiar color basado en el valor
            if (valor > 5) {
                porcentajeDisplay.style.color = '#4caf50'; // Verde para valores altos
            } else if (valor > 2) {
                porcentajeDisplay.style.color = '#ff9800'; // Naranja para valores medios
            } else {
                porcentajeDisplay.style.color = '#f44336'; // Rojo para valores bajos
            }
        }

        function generarTablaDemandaGlobal(anioInicio, anioFin, nombresProductos) {
            // Generar header de productos para Demanda Global
            const headerProductosDemanda = document.getElementById('headerProductosDemanda');
            headerProductosDemanda.innerHTML = '';
            nombresProductos.forEach((nombre, index) => {
                const th = document.createElement('th');
                th.textContent = nombre;
                th.style.backgroundColor = getProductColor(index);
                th.style.color = 'white';
                th.style.padding = '10px';
                headerProductosDemanda.appendChild(th);
            });
            
            // Actualizar colspan del header MERCADOS
            document.querySelector('#tablaDemandaGlobal thead tr:first-child th:last-child').setAttribute('colspan', numProductos);
            
            // Generar filas de años individuales (incluyendo el año final)
            const bodyDemanda = document.getElementById('bodyDemandaGlobal');
            bodyDemanda.innerHTML = '';
            
            for (let año = anioInicio; año <= anioFin; año++) {
                const row = document.createElement('tr');
                
                // Columna de año
                const cellAño = document.createElement('td');
                cellAño.innerHTML = `<strong>${año}</strong>`;
                cellAño.style.backgroundColor = '#f8f9fa';
                cellAño.style.fontWeight = 'bold';
                cellAño.style.textAlign = 'center';
                cellAño.style.padding = '12px';
                cellAño.style.fontSize = '16px';
                row.appendChild(cellAño);
                
                // Columnas de productos
                nombresProductos.forEach((nombre, index) => {
                    const cellProducto = document.createElement('td');
                    cellProducto.style.backgroundColor = getProductColor(index, 0.1);
                    cellProducto.style.padding = '8px';
                    cellProducto.innerHTML = `
                        <input type="number" 
                               name="demanda_${año}_${index}" 
                               step="0.01" 
                               placeholder="0.00"
                               style="width: 80px; text-align: center; border: 1px solid #9C27B0; border-radius: 4px; padding: 6px; background: #f3e5f5;"
                               onchange="convertirAPorcentajeDemanda(this)"
                               onblur="convertirAPorcentajeDemanda(this)">
                        <div class="porcentaje-display-demanda" style="font-weight: bold; color: #7B1FA2; margin-top: 4px;">0.00%</div>
                    `;
                    row.appendChild(cellProducto);
                });
                
                bodyDemanda.appendChild(row);
            }
              // Mostrar la sección de Demanda Global
            document.getElementById('sectionDemandaGlobal').style.display = 'block';
            
            // Mostrar la configuración de competidores
            document.getElementById('sectionConfigCompetidores').style.display = 'block';
        }

        function convertirAPorcentajeDemanda(input) {
            const valor = parseFloat(input.value) || 0;
            const porcentajeDisplay = input.parentElement.querySelector('.porcentaje-display-demanda');
            porcentajeDisplay.textContent = valor.toFixed(2) + '%';
              // Cambiar color basado en el valor
            if (valor > 3) {
                porcentajeDisplay.style.color = '#4caf50'; // Verde para valores altos
            } else if (valor > 1) {
                porcentajeDisplay.style.color = '#ff9800'; // Naranja para valores medios
            } else {
                porcentajeDisplay.style.color = '#f44336'; // Rojo para valores bajos
            }
        }

        function generarTablaCompetidores() {
            const numCompetidores = parseInt(document.getElementById('numCompetidores').value);
            
            if (numCompetidores < 1 || numCompetidores > 20) {
                alert('Por favor ingrese un número entre 1 y 20 competidores');
                return;
            }
            
            if (numProductos === 0) {
                alert('Primero debe generar la tabla de productos');
                return;
            }
            
            // Obtener nombres de productos y sus ventas desde la primera tabla
            const nombresProductos = [];
            const ventasEmpresa = [];
            const productosInputs = document.querySelectorAll('input[name="productos[]"]');
            const ventasInputs = document.querySelectorAll('input[name="ventas[]"]');
            
            productosInputs.forEach((input, index) => {
                nombresProductos.push(input.value || `Producto ${index + 1}`);
                ventasEmpresa.push(parseFloat(ventasInputs[index].value) || 0);
            });
            
            // Generar header principal
            const headerCompetidores = document.getElementById('headerCompetidores');
            headerCompetidores.innerHTML = '';
            
            // Primera fila del header con los productos
            const firstRowHeader = document.createElement('tr');
            nombresProductos.forEach((nombre, index) => {
                const th = document.createElement('th');
                th.textContent = nombre;
                th.style.backgroundColor = getProductColor(index);
                th.style.color = 'white';
                th.style.padding = '15px';
                th.style.fontSize = '16px';
                th.style.fontWeight = 'bold';
                th.setAttribute('colspan', '2'); // Para EMPRESA y Ventas
                firstRowHeader.appendChild(th);
            });
            headerCompetidores.appendChild(firstRowHeader);
            
            // Segunda fila del header con EMPRESA y Ventas
            const secondRowHeader = document.createElement('tr');
            nombresProductos.forEach((nombre, index) => {
                // Columna EMPRESA
                const thEmpresa = document.createElement('th');
                thEmpresa.textContent = 'EMPRESA';
                thEmpresa.style.backgroundColor = '#424242';
                thEmpresa.style.color = 'white';
                thEmpresa.style.padding = '10px';
                thEmpresa.style.fontSize = '12px';
                secondRowHeader.appendChild(thEmpresa);
                
                // Columna Ventas
                const thVentas = document.createElement('th');
                thVentas.textContent = 'Ventas';
                thVentas.style.backgroundColor = '#424242';
                thVentas.style.color = 'white';
                thVentas.style.padding = '10px';
                thVentas.style.fontSize = '12px';
                secondRowHeader.appendChild(thVentas);
            });
            headerCompetidores.appendChild(secondRowHeader);
            
            // Generar cuerpo de la tabla
            const bodyCompetidores = document.getElementById('bodyCompetidores');
            bodyCompetidores.innerHTML = '';
            
            // Generar filas de competidores CP-1, CP-2, etc.
            for (let cp = 1; cp <= numCompetidores; cp++) {
                const row = document.createElement('tr');
                
                nombresProductos.forEach((nombre, index) => {
                    // Columna EMPRESA (Competidor)
                    const cellEmpresa = document.createElement('td');
                    cellEmpresa.textContent = `CP-${cp}`;
                    cellEmpresa.style.backgroundColor = '#f5f5f5';
                    cellEmpresa.style.fontWeight = 'bold';
                    cellEmpresa.style.textAlign = 'center';
                    cellEmpresa.style.padding = '10px';
                    row.appendChild(cellEmpresa);
                    
                    // Columna Ventas (Input editable)
                    const cellVentas = document.createElement('td');
                    cellVentas.style.backgroundColor = getProductColor(index, 0.1);
                    cellVentas.style.padding = '8px';
                    cellVentas.innerHTML = `
                        <input type="number" 
                               name="competidor_${cp}_${index}" 
                               step="0.01" 
                               placeholder="0"
                               data-producto="${index}"
                               style="width: 60px; text-align: center; border: 1px solid #FF5722; border-radius: 4px; padding: 6px; font-weight: bold;"
                               onchange="calcularMayor(${index})">
                    `;
                    row.appendChild(cellVentas);
                });
                
                bodyCompetidores.appendChild(row);
            }
            
            // Agregar fila de EMPRESA con sus ventas
            const rowEmpresa = document.createElement('tr');
            rowEmpresa.style.backgroundColor = '#e8f5e8';
            nombresProductos.forEach((nombre, index) => {                // Columna EMPRESA
                const cellEmpresa = document.createElement('td');
                cellEmpresa.innerHTML = `<strong>EMPRESA</strong><br><small>${ventasEmpresa[index].toFixed(2)}</small>`;
                cellEmpresa.style.backgroundColor = '#4CAF50';
                cellEmpresa.style.color = 'white';
                cellEmpresa.style.fontWeight = 'bold';
                cellEmpresa.style.textAlign = 'center';
                cellEmpresa.style.padding = '10px';
                rowEmpresa.appendChild(cellEmpresa);
                
                // Columna Ventas (Valor de la tabla de previsión)
                const cellVentas = document.createElement('td');
                cellVentas.textContent = ventasEmpresa[index].toFixed(2);
                cellVentas.style.backgroundColor = '#C8E6C9';
                cellVentas.style.fontWeight = 'bold';
                cellVentas.style.textAlign = 'center';
                cellVentas.style.padding = '10px';
                cellVentas.style.fontSize = '16px';
                cellVentas.setAttribute('id', `empresa_${index}`);
                rowEmpresa.appendChild(cellVentas);
            });
            bodyCompetidores.appendChild(rowEmpresa);
            
            // Agregar fila de Mayor
            const rowMayor = document.createElement('tr');
            rowMayor.style.backgroundColor = '#fff3e0';
            nombresProductos.forEach((nombre, index) => {
                // Columna EMPRESA
                const cellEmpresa = document.createElement('td');
                cellEmpresa.textContent = 'Mayor';
                cellEmpresa.style.backgroundColor = '#FF9800';
                cellEmpresa.style.color = 'white';
                cellEmpresa.style.fontWeight = 'bold';
                cellEmpresa.style.textAlign = 'center';
                cellEmpresa.style.padding = '10px';
                rowMayor.appendChild(cellEmpresa);
                
                // Columna Mayor (Calculado automáticamente)
                const cellMayor = document.createElement('td');
                cellMayor.textContent = ventasEmpresa[index].toFixed(2); // Inicialmente el valor de la empresa
                cellMayor.style.backgroundColor = '#FFE0B2';
                cellMayor.style.fontWeight = 'bold';
                cellMayor.style.textAlign = 'center';
                cellMayor.style.padding = '10px';
                cellMayor.style.fontSize = '16px';
                cellMayor.style.color = '#E65100';
                cellMayor.setAttribute('id', `mayor_${index}`);
                rowMayor.appendChild(cellMayor);            });
            bodyCompetidores.appendChild(rowMayor);
            
            // Calcular correctamente los valores "Mayor" para todos los productos
            for (let i = 0; i < nombresProductos.length; i++) {
                calcularMayor(i);
            }
            
            // Mostrar la sección de competidores
            document.getElementById('sectionCompetidores').style.display = 'block';
            
            // Generar automáticamente la tabla BCG
            generarTablaResumenBCG();
            
            // Scroll hacia la tabla de competidores
            document.getElementById('sectionCompetidores').scrollIntoView({ behavior: 'smooth' });
        }        function calcularMayor(productoIndex) {
            // Obtener todos los valores de competidores para este producto
            const competidoresInputs = document.querySelectorAll(`input[data-producto="${productoIndex}"]`);
            
            let mayorValor = 0; // Empezar con 0, solo considerar competidores
            
            // Revisar solo los valores de competidores (no incluir empresa)
            competidoresInputs.forEach(input => {
                const valor = parseFloat(input.value) || 0;
                if (valor > mayorValor) {
                    mayorValor = valor;
                }
            });
            
            // Actualizar el valor "Mayor"
            const mayorCell = document.getElementById(`mayor_${productoIndex}`);
            if (mayorCell) {
                mayorCell.textContent = mayorValor.toFixed(2);
            }
            
            // Actualizar la tabla BCG si está visible
            if (document.getElementById('sectionMatrizBCG').style.display === 'block') {
                actualizarPRMEnBCG(productoIndex);
            }
        }function actualizarPRMEnBCG(productoIndex) {
            // Actualizar solo el PRM para el producto específico en la tabla BCG
            const ventasInputs = document.querySelectorAll('input[name="ventas[]"]');
            const valorEmpresa = parseFloat(ventasInputs[productoIndex].value) || 1;
            const mayorCell = document.getElementById(`mayor_${productoIndex}`);
            const valorMayor = mayorCell ? parseFloat(mayorCell.textContent) || 1 : 1;
            const prm = valorEmpresa / valorMayor; // Corregido: empresa / mayor
            document.getElementById(`bcgPRM${productoIndex}`).textContent = prm.toFixed(2);
        }

        function actualizarTCMEnBCG() {
            // Solo actualizar si la tabla BCG está visible
            if (document.getElementById('sectionMatrizBCG').style.display !== 'block') {
                return;
            }
            
            const numProductos = parseInt(document.getElementById('numProductos').value) || 0;
            
            // Actualizar TCM para todos los productos
            for (let i = 0; i < numProductos; i++) {
                const tcmInputs = document.querySelectorAll(`input[name*="tcm_"][name*="_${i}"]`);
                let sumaTCM = 0;
                let cantidadPeriodos = 0;
                tcmInputs.forEach(input => {
                    const valor = parseFloat(input.value) || 0;
                    sumaTCM += valor;
                    cantidadPeriodos++;
                });
                const promedioTCM = cantidadPeriodos > 0 ? (sumaTCM / cantidadPeriodos) : 0;
                document.getElementById(`bcgTCM${i}`).textContent = promedioTCM.toFixed(2) + '%';
            }
        }function actualizarTablaCompetidores() {
            // Solo actualizar si la tabla de competidores ya está generada
            const sectionCompetidores = document.getElementById('sectionCompetidores');
            if (!sectionCompetidores || sectionCompetidores.style.display === 'none') {
                return; // No hacer nada si la tabla no está visible
            }
            
            // Obtener los nuevos valores de ventas
            const ventasInputs = document.querySelectorAll('input[name="ventas[]"]');
            const productosInputs = document.querySelectorAll('input[name="productos[]"]');
            
            ventasInputs.forEach((input, index) => {
                const nuevoValor = parseFloat(input.value) || 0;
                
                // Actualizar el valor en la fila EMPRESA
                const empresaCell = document.getElementById(`empresa_${index}`);
                if (empresaCell) {
                    empresaCell.textContent = nuevoValor.toFixed(2);
                }
                
                // Actualizar el valor pequeño en la columna EMPRESA
                const empresaCellHeader = document.querySelector(`#bodyCompetidores tr:nth-last-child(2) td:nth-child(${(index * 2) + 1})`);
                if (empresaCellHeader) {
                    const nombreProducto = productosInputs[index].value || `Producto ${index + 1}`;
                    empresaCellHeader.innerHTML = `<strong>EMPRESA</strong><br><small>${nuevoValor.toFixed(2)}</small>`;
                }
                
                // Recalcular el valor "Mayor" para este producto
                calcularMayor(index);
            });
            
            // Actualizar la tabla BCG si está visible
            if (document.getElementById('sectionMatrizBCG').style.display === 'block') {
                generarTablaResumenBCG();
            }
        }

        function generarTablaResumenBCG() {
            // Obtener nombres de productos
            const productosInputs = document.querySelectorAll('input[name="productos[]"]');
            const ventasInputs = document.querySelectorAll('input[name="ventas[]"]');
            const numProductos = parseInt(document.getElementById('numProductos').value) || 0;
            
            // Actualizar headers de productos en la tabla BCG
            for (let i = 0; i < 5; i++) {
                const header = document.getElementById(`headerBCGProducto${i + 1}`);
                if (i < numProductos) {
                    header.textContent = productosInputs[i].value || `Producto ${i + 1}`;
                    header.style.display = 'table-cell';
                } else {
                    header.style.display = 'none';
                }
            }
              // Calcular y actualizar valores
            for (let i = 0; i < numProductos; i++) {
                // 1. TCM - Promedio de tasas de crecimiento del mercado
                const tcmInputs = document.querySelectorAll(`input[name*="tcm_"][name*="_${i}"]`);
                let sumaTCM = 0;
                let cantidadPeriodos = 0;
                tcmInputs.forEach(input => {
                    const valor = parseFloat(input.value) || 0;
                    sumaTCM += valor;
                    cantidadPeriodos++;
                });
                const promedioTCM = cantidadPeriodos > 0 ? (sumaTCM / cantidadPeriodos) : 0;
                document.getElementById(`bcgTCM${i}`).textContent = promedioTCM.toFixed(2) + '%';
                  // 2. PRM - Valor empresa / Mayor valor de competidores  
                const valorEmpresa = parseFloat(ventasInputs[i].value) || 1;
                const mayorCell = document.getElementById(`mayor_${i}`);
                const valorMayor = mayorCell ? parseFloat(mayorCell.textContent) || 1 : 1;
                const prm = valorEmpresa / valorMayor;
                document.getElementById(`bcgPRM${i}`).textContent = prm.toFixed(2);
                
                // 3. % S/VTAS - Porcentaje de la Previsión de Ventas
                const porcentajeCell = document.querySelector(`#tablaProductos tbody tr:nth-child(${i + 1}) .porcentaje`);
                const porcentajeVentas = porcentajeCell ? porcentajeCell.textContent : '0.00%';
                document.getElementById(`bcgVentas${i}`).textContent = porcentajeVentas;
            }
            
            // Ocultar columnas de productos no utilizados
            for (let i = numProductos; i < 5; i++) {
                document.getElementById(`bcgTCM${i}`).parentElement.children[i + 1].style.display = 'none';
                document.getElementById(`bcgPRM${i}`).parentElement.children[i + 1].style.display = 'none';
                document.getElementById(`bcgVentas${i}`).parentElement.children[i + 1].style.display = 'none';
            }
            
            // Mostrar la sección de Matriz BCG
            document.getElementById('sectionMatrizBCG').style.display = 'block';
            
            // Scroll hacia la tabla BCG
            setTimeout(() => {
                document.getElementById('sectionMatrizBCG').scrollIntoView({ behavior: 'smooth' });
            }, 500);
        }        function generarMatrizBCG() {
            // Generar automáticamente el gráfico cuando se hace clic en "Generar Matriz BCG Completa"
            generarGraficoBCG();
        }

        function generarGraficoBCG() {
            // Obtener datos necesarios
            const numProductos = parseInt(document.getElementById('numProductos').value) || 0;
            const productosInputs = document.querySelectorAll('input[name="productos[]"]');
            
            if (numProductos === 0) {
                alert('Primero debe generar productos y completar todas las tablas.');
                return;
            }
            
            // Verificar que existan las tablas necesarias
            if (document.getElementById('sectionMatrizBCG').style.display === 'none') {
                alert('Primero debe completar todas las tablas (Previsión de Ventas, TCM, Demanda Global y Competidores).');
                return;
            }
            
            // Mostrar la sección del gráfico
            document.getElementById('sectionGraficoBCG').style.display = 'block';
            
            // Dibujar el gráfico
            dibujarMatrizBCG();
            
            // Generar clasificación de productos
            generarClasificacionProductos();
            
            // Scroll hacia el gráfico
            setTimeout(() => {
                document.getElementById('sectionGraficoBCG').scrollIntoView({ behavior: 'smooth' });
            }, 300);
        }
        
        function dibujarMatrizBCG() {
            const canvas = document.getElementById('canvasBCG');
            const ctx = canvas.getContext('2d');
            const numProductos = parseInt(document.getElementById('numProductos').value) || 0;
            
            // Limpiar canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Configuración del gráfico
            const margin = 80;
            const graphWidth = canvas.width - 2 * margin;
            const graphHeight = canvas.height - 2 * margin;
            
            // Dibujar ejes y cuadrantes
            dibujarEjesYCuadrantes(ctx, margin, graphWidth, graphHeight);
            
            // Dibujar productos como esferas
            for (let i = 0; i < numProductos; i++) {
                dibujarProducto(ctx, i, margin, graphWidth, graphHeight);
            }
        }
        
        function dibujarEjesYCuadrantes(ctx, margin, width, height) {
            // Configurar estilo
            ctx.strokeStyle = '#673AB7';
            ctx.lineWidth = 2;
            
            // Dibujar bordes del gráfico
            ctx.strokeRect(margin, margin, width, height);
            
            // Dibujar líneas divisorias (centro)
            ctx.beginPath();
            // Línea vertical (centro en X)
            ctx.moveTo(margin + width/2, margin);
            ctx.lineTo(margin + width/2, margin + height);
            // Línea horizontal (centro en Y)
            ctx.moveTo(margin, margin + height/2);
            ctx.lineTo(margin + width, margin + height/2);
            ctx.stroke();
            
            // Dibujar cuadrantes con colores suaves
            ctx.globalAlpha = 0.1;
            
            // Cuadrante Incógnita (superior izquierdo)
            ctx.fillStyle = '#FF9800';
            ctx.fillRect(margin, margin, width/2, height/2);
            
            // Cuadrante Estrella (superior derecho)
            ctx.fillStyle = '#2196F3';
            ctx.fillRect(margin + width/2, margin, width/2, height/2);
            
            // Cuadrante Perro (inferior izquierdo)
            ctx.fillStyle = '#F44336';
            ctx.fillRect(margin, margin + height/2, width/2, height/2);
            
            // Cuadrante Vaca (inferior derecho)
            ctx.fillStyle = '#4CAF50';
            ctx.fillRect(margin + width/2, margin + height/2, width/2, height/2);
            
            ctx.globalAlpha = 1.0;
            
            // Etiquetas de los ejes
            ctx.fillStyle = '#673AB7';
            ctx.font = 'bold 12px Arial';
            ctx.textAlign = 'center';
            
            // Etiquetas del eje X (PRM)
            ctx.fillText('Baja', margin + width/4, margin + height + 25);
            ctx.fillText('Alta', margin + 3*width/4, margin + height + 25);
            
            // Etiquetas del eje Y (TCM)
            ctx.save();
            ctx.translate(margin - 25, margin + height/4);
            ctx.rotate(-Math.PI/2);
            ctx.fillText('Alto', 0, 0);
            ctx.restore();
            
            ctx.save();
            ctx.translate(margin - 25, margin + 3*height/4);
            ctx.rotate(-Math.PI/2);
            ctx.fillText('Bajo', 0, 0);
            ctx.restore();
        }
        
        function dibujarProducto(ctx, productoIndex, margin, width, height) {
            // Obtener datos del producto
            const tcmCell = document.getElementById(`bcgTCM${productoIndex}`);
            const prmCell = document.getElementById(`bcgPRM${productoIndex}`);
            const ventasCell = document.getElementById(`bcgVentas${productoIndex}`);
            
            if (!tcmCell || !prmCell || !ventasCell) return;
            
            const tcm = parseFloat(tcmCell.textContent.replace('%', '')) || 0;
            const prm = parseFloat(prmCell.textContent) || 0;
            const ventas = parseFloat(ventasCell.textContent.replace('%', '')) || 0;            // Convertir valores a coordenadas del canvas con mayor rango de movimiento
            
            // PRM: Mapear con rango ampliado para usar todo el ancho
            let x;
            if (prm >= 1.0) {
                // Alta participación: mapear 1.0-4.0 al lado derecho completo
                const prmNormalizado = Math.min((prm - 1.0) / 3.0, 1.0);
                x = margin + width/2 + 20 + (prmNormalizado * (width/2 - 40));
            } else {
                // Baja participación: mapear 0-1.0 al lado izquierdo completo
                const prmNormalizado = prm / 1.0;
                x = margin + 20 + (prmNormalizado * (width/2 - 40));
            }
            
            // TCM: Mapear con rango ampliado para usar toda la altura
            let y;
            if (tcm >= 5.0) {
                // Alto crecimiento: mapear 5%-25% a la parte superior completa
                const tcmNormalizado = Math.min((tcm - 5.0) / 20.0, 1.0);
                y = margin + 20 + ((1 - tcmNormalizado) * (height/2 - 40));
            } else {
                // Bajo crecimiento: mapear 0%-5% a la parte inferior completa
                const tcmNormalizado = tcm / 5.0;
                y = margin + height/2 + 20 + ((1 - tcmNormalizado) * (height/2 - 40));
            }
            
            // Casos especiales para valores extremos
            if (prm >= 2.0) {
                // Muy alta participación: mover hacia el extremo derecho
                x = margin + width - 40;
            }
            if (prm <= 0.3) {
                // Muy baja participación: mover hacia el extremo izquierdo
                x = margin + 40;
            }
            if (tcm >= 15.0) {
                // Muy alto crecimiento: mover hacia el extremo superior
                y = margin + 40;
            }
            if (tcm <= 1.0) {
                // Muy bajo crecimiento: mover hacia el extremo inferior
                y = margin + height - 40;
            }
            
            // Asegurar límites mínimos
            if (x < margin + 30) x = margin + 30;
            if (x > margin + width - 30) x = margin + width - 30;
            if (y < margin + 30) y = margin + 30;
            if (y > margin + height - 30) y = margin + height - 30;
            
            // Tamaño de la esfera basado en el porcentaje de ventas
            const minRadius = 15;
            const maxRadius = 40;
            const radius = minRadius + ((ventas / 100) * (maxRadius - minRadius));
            
            // Color del producto
            const colores = ['#4CAF50', '#FF9800', '#F44336', '#9C27B0', '#00BCD4'];
            const color = colores[productoIndex % colores.length];
            
            // Dibujar sombra
            ctx.shadowColor = 'rgba(0, 0, 0, 0.3)';
            ctx.shadowBlur = 8;
            ctx.shadowOffsetX = 3;
            ctx.shadowOffsetY = 3;
            
            // Dibujar esfera con gradiente
            const gradient = ctx.createRadialGradient(x - radius/3, y - radius/3, 0, x, y, radius);
            gradient.addColorStop(0, lightenColor(color, 0.4));
            gradient.addColorStop(1, color);
            
            ctx.fillStyle = gradient;
            ctx.beginPath();
            ctx.arc(x, y, radius, 0, 2 * Math.PI);
            ctx.fill();
            
            // Quitar sombra
            ctx.shadowColor = 'transparent';
            
            // Borde de la esfera
            ctx.strokeStyle = darkenColor(color, 0.2);
            ctx.lineWidth = 2;
            ctx.stroke();
            
            // Etiqueta del producto
            ctx.fillStyle = 'white';
            ctx.font = 'bold 12px Arial';
            ctx.textAlign = 'center';
            ctx.strokeStyle = color;
            ctx.lineWidth = 3;
            ctx.strokeText(`P${productoIndex + 1}`, x, y + 4);
            ctx.fillText(`P${productoIndex + 1}`, x, y + 4);
        }
        
        function lightenColor(color, percent) {
            const num = parseInt(color.replace("#",""), 16);
            const amt = Math.round(2.55 * percent * 100);
            const R = (num >> 16) + amt;
            const G = (num >> 8 & 0x00FF) + amt;
            const B = (num & 0x0000FF) + amt;
            return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
                (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
                (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
        }
          function darkenColor(color, percent) {
            const num = parseInt(color.replace("#",""), 16);
            const amt = Math.round(2.55 * percent * 100);
            const R = (num >> 16) - amt;
            const G = (num >> 8 & 0x00FF) - amt;
            const B = (num & 0x0000FF) - amt;
            return "#" + (0x1000000 + (R > 255 ? 255 : R < 0 ? 0 : R) * 0x10000 +
                (G > 255 ? 255 : G < 0 ? 0 : G) * 0x100 +
                (B > 255 ? 255 : B < 0 ? 0 : B)).toString(16).slice(1);
        }
        
        function generarClasificacionProductos() {
            const numProductos = parseInt(document.getElementById('numProductos').value) || 0;
            const productosInputs = document.querySelectorAll('input[name="productos[]"]');
            const listaClasificacion = document.getElementById('listaClasificacion');
            
            listaClasificacion.innerHTML = '';
            
            for (let i = 0; i < numProductos; i++) {
                const nombreProducto = productosInputs[i].value || `Producto ${i + 1}`;
                const tcm = parseFloat(document.getElementById(`bcgTCM${i}`).textContent.replace('%', '')) || 0;
                const prm = parseFloat(document.getElementById(`bcgPRM${i}`).textContent) || 0;
                
                // Determinar cuadrante
                let cuadrante, icono, color, decision;
                
                if (tcm >= 5 && prm >= 1) {
                    cuadrante = 'ESTRELLA';
                    icono = '⭐';
                    color = '#2196F3';
                    decision = 'POTENCIAR';
                } else if (tcm >= 5 && prm < 1) {
                    cuadrante = 'INCÓGNITA';
                    icono = '❓';
                    color = '#FF9800';
                    decision = 'EVALUAR';
                } else if (tcm < 5 && prm >= 1) {
                    cuadrante = 'VACA';
                    icono = '🐄';
                    color = '#4CAF50';
                    decision = 'MANTENER';
                } else {
                    cuadrante = 'PERRO';
                    icono = '🐕';
                    color = '#F44336';
                    decision = 'REESTRUCTURAR O DESINVERTIR';
                }
                
                const itemDiv = document.createElement('div');
                itemDiv.style.cssText = `
                    display: flex; 
                    align-items: center; 
                    margin-bottom: 10px; 
                    padding: 10px; 
                    background: white; 
                    border-radius: 5px; 
                    border-left: 4px solid ${color};
                `;
                
                itemDiv.innerHTML = `
                    <span style="font-size: 24px; margin-right: 10px;">${icono}</span>
                    <div style="flex: 1;">
                        <strong style="color: ${color};">${nombreProducto}</strong> - ${cuadrante}
                        <br>
                        <small>TCM: ${tcm.toFixed(2)}% | PRM: ${prm.toFixed(2)} | Decisión: <strong>${decision}</strong></small>
                    </div>
                `;
                  listaClasificacion.appendChild(itemDiv);
            }
        }
          // Función para guardar datos con AJAX
        function guardarDatos() {
            // Recopilar todos los datos del formulario
            const formData = new FormData();
            
            // Datos básicos (sin id_empresa)
            formData.append('paso', '6');
            formData.append('nombre_paso', 'matriz_bcg');
            formData.append('numProductos', document.getElementById('numProductos').value || 0);
            formData.append('numCompetidores', document.getElementById('numCompetidores').value || 5);
            formData.append('anioInicio', document.getElementById('anioInicio').value || 2020);
            formData.append('anioFin', document.getElementById('anioFin').value || 2025);
            
            // Productos
            const productosInputs = document.querySelectorAll('input[name="productos[]"]');
            productosInputs.forEach((input, index) => {
                formData.append('productos[]', input.value || `Producto ${index + 1}`);
            });
            
            // Ventas
            const ventasInputs = document.querySelectorAll('input[name="ventas[]"]');
            ventasInputs.forEach(input => {
                formData.append('ventas[]', input.value || 0);
            });
            
            // Fortalezas
            const fortalezasInputs = document.querySelectorAll('input[name="fortalezas[]"]');
            fortalezasInputs.forEach(input => {
                if (input.value.trim()) {
                    formData.append('fortalezas[]', input.value.trim());
                }
            });
            
            // Debilidades
            const debilidadesInputs = document.querySelectorAll('input[name="debilidades[]"]');
            debilidadesInputs.forEach(input => {
                if (input.value.trim()) {
                    formData.append('debilidades[]', input.value.trim());
                }
            });
            
            // Enviar datos por AJAX
            fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Datos guardados exitosamente en sesión');
                    // Redirigir al siguiente paso del wizard
                    window.location.href = 'fuerzas_porter.php';
                } else {
                    alert('Error al guardar: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión al guardar los datos');
            });
        }
          // Modificar el evento de submit del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            guardarDatos();
        });

        // Cargar datos previos desde sesión si existen
        window.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($datos_bcg)): ?>
                // Precargar datos básicos
                <?php if (isset($datos_bcg['numProductos'])): ?>
                    document.getElementById('numProductos').value = '<?php echo $datos_bcg['numProductos']; ?>';
                    if (<?php echo $datos_bcg['numProductos']; ?> > 0) {
                        generarTablaProductos();
                        
                        // Esperar un momento para que se genere la tabla y luego cargar datos
                        setTimeout(function() {
                            <?php if (isset($datos_bcg['productos']) && is_array($datos_bcg['productos'])): ?>
                                // Precargar productos
                                <?php foreach ($datos_bcg['productos'] as $index => $producto): ?>
                                    const productoInput<?php echo $index; ?> = document.querySelector('input[name="productos[]"]:nth-of-type(<?php echo $index + 1; ?>)');
                                    if (productoInput<?php echo $index; ?>) {
                                        productoInput<?php echo $index; ?>.value = '<?php echo htmlspecialchars($producto); ?>';
                                    }
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <?php if (isset($datos_bcg['ventas']) && is_array($datos_bcg['ventas'])): ?>
                                // Precargar ventas
                                <?php foreach ($datos_bcg['ventas'] as $index => $venta): ?>
                                    const ventaInput<?php echo $index; ?> = document.querySelector('input[name="ventas[]"]:nth-of-type(<?php echo $index + 1; ?>)');
                                    if (ventaInput<?php echo $index; ?>) {
                                        ventaInput<?php echo $index; ?>.value = '<?php echo $venta; ?>';
                                    }
                                <?php endforeach; ?>
                                
                                // Recalcular porcentajes
                                calcularPorcentajes();
                            <?php endif; ?>
                        }, 500);
                    }
                <?php endif; ?>
                
                <?php if (isset($datos_bcg['anioInicio'])): ?>
                    document.getElementById('anioInicio').value = '<?php echo $datos_bcg['anioInicio']; ?>';
                <?php endif; ?>
                
                <?php if (isset($datos_bcg['anioFin'])): ?>
                    document.getElementById('anioFin').value = '<?php echo $datos_bcg['anioFin']; ?>';
                <?php endif; ?>
                
                <?php if (isset($datos_bcg['numCompetidores'])): ?>
                    document.getElementById('numCompetidores').value = '<?php echo $datos_bcg['numCompetidores']; ?>';
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
