<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/clsconexion.php';
require_once __DIR__ . '/../Controllers/BcgsController.php';

// Obtener datos de productos desde el controlador BCG
$bcgsController = new BcgsController();
$datosProductos = $bcgsController->index(); // debe retornar los datos, NO incluir la vista

// Conexión para objetivos estratégicos
$conexion = new clsConexion();
$db = $conexion->getConexion();

// Consulta para obtener objetivos estratégicos
$queryEstrategicos = "SELECT * FROM tb_obj_estra";
$resultEstrategicos = $db->query($queryEstrategicos);

if (!$resultEstrategicos) {
    die("Error en la consulta: " . $db->error);
}

$datosObjetivos = [];
while ($estrategico = $resultEstrategicos->fetch_assoc()) {
    $queryEspecificos = "SELECT * FROM tb_obj_especificos WHERE id_obj_estra = ?";
    $stmt = $db->prepare($queryEspecificos);
    $stmt->bind_param("i", $estrategico['id_obj_estra']);
    $stmt->execute();
    $resultEspecificos = $stmt->get_result();
    
    $especificos = [];
    while ($especifico = $resultEspecificos->fetch_assoc()) {
        $especificos[] = $especifico;
    }

    $datosObjetivos[] = [
        'estrategico' => $estrategico,
        'especificos' => $especificos
    ];
    
    $stmt->close();
}

// Cerrar conexión
$conexion->Cerrarconex();

// Aquí ya puedes usar $datosProductos y $datosObjetivos en tu vista


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.2.1/dist/chartjs-plugin-annotation.min.js"></script>

    <meta charset="UTF-8">
    <title>Matriz de Participación</title>
    <link rel="stylesheet" href="../public/css/valores.css">
    <link rel="stylesheet" href="../public/css/normalize.css">
    
    <link rel="stylesheet" href="../public/css/material.min.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="../public/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="../public/css/main.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../public/js/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="../public/js/material.min.js"></script>
    
    <script src="../public/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../public/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Notifications area -->
    <section class="full-width container-notifications">
        <div class="full-width container-notifications-bg btn-Notification"></div>
        <section class="NotificationArea">
            <div class="full-width text-center NotificationArea-title tittles">Notifications <i class="zmdi zmdi-close btn-Notification"></i></div>
            <a href="#" class="Notification" id="notifation-unread-1">
                <div class="Notification-icon"><i class="zmdi zmdi-accounts-alt bg-info"></i></div>
                <div class="Notification-text">
                    <p>
                        <i class="zmdi zmdi-circle"></i>
                        <strong>Edicion de Vision</strong> 
                        <br>
                        <small>Just Now</small>
                    </p>
                </div>
                <div class="mdl-tooltip mdl-tooltip--left" for="notifation-unread-1">Notification as UnRead</div> 
            </a>
            <a href="#" class="Notification" id="notifation-read-1">
                <div class="Notification-icon"><i class="zmdi zmdi-cloud-download bg-primary"></i></div>
                <div class="Notification-text">
                    <p>
                        <i class="zmdi zmdi-circle-o"></i>
                        <strong>New Updates</strong> 
                        <br>
                        <small>30 Mins Ago</small>
                    </p>
                </div>
                <div class="mdl-tooltip mdl-tooltip--left" for="notifation-read-1">Notification as Read</div>
            </a>
        </section>
    </section>
    
    <!-- navBar -->
    <div class="full-width navBar">
        <div class="full-width navBar-options">
            <i class="zmdi zmdi-more-vert btn-menu" id="btn-menu"></i>	
            <div class="mdl-tooltip" for="btn-menu">Menu</div>
            <nav class="navBar-options-list">
                <ul class="list-unstyle">
                    <li class="btn-Notification" id="notifications">
                        <i class="zmdi zmdi-notifications"></i>
                        <div class="mdl-tooltip" for="notifications">Notifications</div>
                    </li>
                    <li class="btn-exit" id="btn-exit">
                        <i class="zmdi zmdi-power"></i>
                        <div class="mdl-tooltip" for="btn-exit">LogOut</div>
                    </li>
                    <li class="text-condensedLight noLink"><small><?php echo $_SESSION['user_name'] ?? 'Usuario'; ?></small></li>
                    <li class="noLink">
                        <figure>
                            <img src="../assets/img/avatar-male.png" alt="Avatar" class="img-responsive">
                        </figure>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    
    <?php include 'sidebar.php'; ?>
    
    <!-- pageContent -->
    <section class="full-width pageContent">
        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
            <div class="mdl-tabs__tab-bar">
                <a href="#tabListProducts" class="mdl-tabs__tab is-active">Presentación</a>
                <a href="#tabNewProduct" class="mdl-tabs__tab">Edición</a>
            </div>
            
            <!-- Panel de Presentación -->
            <div class="mdl-tabs__panel is-active" id="tabListProducts">
                <div class="mdl-grid">
                    <!-- Contenido de presentación -->
                </div>
            </div>
            
            <!-- Panel de Edición -->
            <div class="mdl-tabs__panel" id="tabNewProduct">
                <div class="mdl-grid">
                    <h4 class="text-center">Previsión de Ventas</h4>
                    <table class="mdl-data-table mdl-js-data-table full-width" id="tablaVentas">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Ventas</th>
                                <th>% S/ TOTAL</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoVentas">
                            <!-- Se llenará dinámicamente -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong>TOTAL</strong></td>
                                <td id="totalVentas">0</td>
                                <td>100.00%</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="text-center" style="margin-top: 20px;">
                        <button id="btnAgregarProducto" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                            Agregar Producto
                        </button>
                    </div>
                    
                    <div>
                        <hr style="margin: 40px 0;">
                        <h4 class="text-center">TASAS DE CRECIMIENTO DEL MERCADO (TCM)</h4>
                        <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 20px;">
                            <label>Desde: 
                                <input type="number" id="anioInicio" min="2000" max="2100" value="2012" class="mdl-textfield__input" style="width: 80px;">
                            </label>
                            <label>Hasta: 
                                <input type="number" id="anioFin" min="2000" max="2100" value="2016" class="mdl-textfield__input" style="width: 80px;">
                            </label>
                            <button id="generarTablaTCM" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                                Generar
                            </button>
                        </div>

                        <div style="overflow-x:auto;">
                            <table class="mdl-data-table mdl-js-data-table full-width" id="tablaTCM">
                                <thead id="theadTCM">
                                    <!-- Se llenará dinámicamente -->
                                </thead>
                                <tbody id="cuerpoTCM">
                                    <!-- Se llenará dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <hr style="margin: 40px 0;">
                        <h4 class="text-center">CUADRO BCG</h4>
                        <div style="overflow-x:auto;">
                            <table class="mdl-data-table mdl-js-data-table full-width" id="tablaBCG">
                                <thead id="theadBCG">
                                    <!-- Se llenará dinámicamente -->
                                </thead>
                                <tbody id="tbodyBCG">
                                    <!-- Se llenará dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="overflow-x:auto;">
                        <hr style="margin: 40px 0;">
                        <h4 class="text-center">EVOLUCIÓN DE LA DEMANDA GLOBAL SECTOR (en miles de soles)</h4>
                        <div class="text-center" style="margin-bottom: 20px;">
                            <button id="generarTablaDemanda" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                                Generar Tabla de Demanda Global
                            </button>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="mdl-data-table mdl-js-data-table full-width" id="tablaDemanda">
                                <thead id="theadDemanda"></thead>
                                <tbody id="tbodyDemanda"></tbody>
                            </table>
                        </div>
                    </div>

                    <div style="overflow-x:auto;">
                        <hr style="margin: 40px 0;">
                        <h4 class="text-center">NIVELES DE VENTA DE LOS COMPETIDORES DE CADA PRODUCTO</h4>
                        <div id="contenedorNivelesCompetencia" style="display: flex; gap: 20px; overflow-x: auto; padding: 10px;">
                            <!-- Aquí se generarán dinámicamente las subtablas -->
                        </div>
                        <div class="text-center" style="margin-top: 20px;">
                            <button id="generarTablaCompetidores" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                                Generar Tabla de Competidores
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            #tablaBCG input,
            #tablaTCM input {
                text-align: right;
                padding-right: 5px;
            }
        </style>

        <div style="margin-top: 50px;">
            <h4 class="text-center">MATRIZ BCG</h4>
            <canvas id="matrizBCG" width="600" height="400"></canvas>
        </div>

        <!-- Pasar datos de PHP a JavaScript -->
        <script>
            const datosProductosBD = <?php echo json_encode($datosProductos); ?>;
        </script>

        <!-- Script principal -->
        <script>
            // Registrar plugins correctamente para Chart.js 4
            Chart.register(
                ChartDataLabels,
                window['chartjs-plugin-annotation']
            );

            // Dibujar emojis de cuadrantes
            const cuadrantesEmojiPlugin = {
                id: 'cuadrantesEmojiPlugin',
                afterDraw(chart) {
                    const { ctx } = chart;
                    const xMid = chart.scales.x.getPixelForValue(1);
                    const yMid = chart.scales.y.getPixelForValue(10);

                    ctx.font = "28px sans-serif";
                    ctx.textAlign = "center";
                    ctx.textBaseline = "middle";

                    ctx.fillText("❓", xMid - 150, yMid - 100);
                    ctx.fillText("⭐", xMid + 150, yMid - 100);
                    ctx.fillText("🐶", xMid - 150, yMid + 100);
                    ctx.fillText("🐄", xMid + 150, yMid + 100);
                }
            };

            // Obtener color por cuadrante
            function getColorCuadrante(prm, tcm) {
                if (prm >= 1 && tcm >= 10) return 'rgba(0, 123, 255, 0.6)'; // Estrella
                if (prm < 1 && tcm >= 10) return 'rgba(255, 193, 7, 0.6)';  // Interrogante
                if (prm < 1 && tcm < 10) return 'rgba(108, 117, 125, 0.6)'; // Perro
                if (prm >= 1 && tcm < 10) return 'rgba(40, 167, 69, 0.6)';  // Vaca
                return 'rgba(100, 100, 100, 0.6)';
            }

            // Variables globales
            let contadorProducto = 1;
            const cuerpoVentas = document.getElementById("cuerpoVentas");
            const totalVentasEl = document.getElementById("totalVentas");

            // Función para obtener el número actual de productos
            function getNumeroProductos() {
                return cuerpoVentas.querySelectorAll("tr").length;
            }

            // Función para obtener los nombres de los productos
            function getNombresProductos() {
                const productos = [];
                cuerpoVentas.querySelectorAll("tr").forEach(fila => {
                    productos.push(fila.querySelector("td").textContent);
                });
                return productos;
            }

            // Función para actualizar todas las tablas dependientes
            function actualizarTodasLasTablas() {
                actualizarTablaTCM();
                actualizarTablaBCG();
                actualizarTablaDemanda();
                actualizarTablaCompetidores();
            }

            // ========== TABLA DE VENTAS ==========
            function actualizarPorcentajes() {
                const filas = cuerpoVentas.querySelectorAll("tr");
                let total = 0;

                // Sumar ventas
                filas.forEach(fila => {
                    const input = fila.querySelector("input");
                    total += parseFloat(input.value) || 0;
                });

                totalVentasEl.textContent = total.toLocaleString();

                // Calcular porcentajes
                filas.forEach(fila => {
                    const input = fila.querySelector("input");
                    const porcentajeEl = fila.querySelector(".porcentaje");
                    const valor = parseFloat(input.value) || 0;
                    const porcentaje = total > 0 ? (valor / total * 100).toFixed(2) : "0.00";
                    porcentajeEl.textContent = porcentaje + "%";
                });
                
                // Actualizar otras tablas cuando cambian las ventas
                actualizarTodasLasTablas();
            }

            function agregarFila(valor = 0, nombrePersonalizado = null) {
                const tr = document.createElement("tr");
                const nombre = nombrePersonalizado || `Producto ${contadorProducto}`;
                
                tr.innerHTML = `
                    <td>${nombre}</td>
                    <td><input type="number" min="0" value="${valor}" class="mdl-textfield__input venta-input" style="width:80px;"></td>
                    <td class="porcentaje">0.00%</td>
                    <td><button class="mdl-button mdl-js-button mdl-button--icon btn-eliminar"><i class="zmdi zmdi-delete"></i></button></td>
                `;

                cuerpoVentas.appendChild(tr);
                if(!nombrePersonalizado) contadorProducto++;

                // Agregar eventos
                tr.querySelector("input").addEventListener("input", actualizarPorcentajes);
                tr.querySelector(".btn-eliminar").addEventListener("click", () => {
                    tr.remove();
                    renombrarProductos();
                    actualizarPorcentajes();
                    actualizarTodasLasTablas();
                });

                componentHandler.upgradeDom();
                actualizarPorcentajes();
                actualizarTodasLasTablas();
            }

            function renombrarProductos() {
                const filas = cuerpoVentas.querySelectorAll("tr");
                contadorProducto = 1;
                filas.forEach(fila => {
                    if(fila.querySelector("td").textContent.startsWith("Producto ")) {
                        fila.querySelector("td").textContent = "Producto " + contadorProducto;
                        contadorProducto++;
                    }
                });
            }

            // ========== TABLA TCM ==========
            function actualizarTablaTCM() {
                const inicio = parseInt(document.getElementById("anioInicio").value);
                const fin = parseInt(document.getElementById("anioFin").value);
                const cuerpoTCM = document.getElementById("cuerpoTCM");
                const numProductos = getNumeroProductos();
                
                cuerpoTCM.innerHTML = "";

                for (let anio = inicio; anio <= fin; anio++) {
                    const fila = document.createElement("tr");
                    let celdas = `<td>${anio} - ${anio + 1}</td>`;
                    
                    for (let i = 0; i < numProductos; i++) {
                        celdas += `<td><input type="number" min="0" max="100" step="0.01" value="3.00" class="mdl-textfield__input" style="width: 70px;">%</td>`;
                    }
                    
                    fila.innerHTML = celdas;
                    cuerpoTCM.appendChild(fila);
                }

                // Actualizar encabezados
                const theadTCM = document.querySelector("#tablaTCM thead");
                theadTCM.innerHTML = `<tr><th>PERIODO</th>${getNombresProductos().map(p => `<th>${p}</th>`).join("")}</tr>`;

                componentHandler.upgradeDom();
            }

            // ========== TABLA BCG ==========
            function actualizarTablaBCG() {
                const tablaBCG = document.getElementById("tablaBCG");
                const numProductos = getNumeroProductos();
                const nombresProductos = getNombresProductos();

                // Obtener % S/VTAS desde la tabla de ventas
                const porcentajesVentas = Array.from(cuerpoVentas.querySelectorAll(".porcentaje")).map(td =>
                    td.textContent.replace('%', '').trim()
                );

                // Obtener tasas TCM desde la tabla TCM
                const cuerpoTCM = document.getElementById("cuerpoTCM");
                const filasTCM = Array.from(cuerpoTCM.querySelectorAll("tr"));
                const tcmCalculado = [];

                for (let i = 0; i < numProductos; i++) {
                    let suma = 0;
                    let cantidad = 0;

                    for (const fila of filasTCM) {
                        const celda = fila.querySelectorAll("td")[i + 1]; // columna del producto
                        const input = celda ? celda.querySelector("input") : null;
                        const valor = input ? parseFloat(input.value) : 0;

                        if (!isNaN(valor)) {
                            suma += valor;
                            cantidad++;
                        }
                    }

                    const promedio = cantidad > 0 ? suma / cantidad : 0;
                    const limitado = Math.min(promedio, 20); // máximo 20%
                    tcmCalculado.push(limitado.toFixed(2));
                }

                // Obtener PRM desde tabla de competencia (mayor / venta)
                const tablasCompetencia = document.querySelectorAll(".tabla-competencia");
                const inputsVentas = Array.from(cuerpoVentas.querySelectorAll("input"));
                const prmCalculado = [];

                for (let i = 0; i < numProductos; i++) {
                    const venta = parseFloat(inputsVentas[i]?.value || 0);
                    const tabla = tablasCompetencia[i];
                    const inputMayor = tabla?.querySelector(".input-mayor");
                    const mayor = inputMayor ? parseFloat(inputMayor.value) : 0;

                    let prm = 0;
                    if (venta !== 0) {
                        const razon = mayor / venta;
                        prm = razon > 2 ? 2 : razon;
                    }

                    prmCalculado.push(prm.toFixed(2));
                }

                // Crear encabezados
                tablaBCG.querySelector("thead").innerHTML = `
                    <tr>
                        <th>BCG</th>
                        ${nombresProductos.map(p => `<th>${p}</th>`).join("")}
                    </tr>
                `;

                // Crear cuerpo
                tablaBCG.querySelector("tbody").innerHTML = `
                    <tr>
                        <td>TCM</td>
                        ${tcmCalculado.map(v => `<td><input type="number" value="${v}" class="mdl-textfield__input" style="width: 70px;" readonly>%</td>`).join("")}
                    </tr>
                    <tr>
                        <td>PRM</td>
                        ${prmCalculado.map(v => `<td><input type="number" value="${v}" class="mdl-textfield__input" style="width: 70px;" readonly></td>`).join("")}
                    </tr>
                    <tr>
                        <td>% S/VTAS</td>
                        ${porcentajesVentas.map(p => `<td><input type="number" value="${p}" class="mdl-textfield__input" style="width: 70px;" readonly>%</td>`).join("")}
                    </tr>
                `;

                componentHandler.upgradeDom();
                generarMatrizBCG();
            }

            // ========== TABLA DEMANDA GLOBAL ==========
            function actualizarTablaDemanda() {
                const inicio = parseInt(document.getElementById("anioInicio").value);
                const fin = parseInt(document.getElementById("anioFin").value);
                const theadDemanda = document.getElementById("theadDemanda");
                const tbodyDemanda = document.getElementById("tbodyDemanda");
                
                theadDemanda.innerHTML = "";
                tbodyDemanda.innerHTML = "";

                // Crear encabezado
                const headerRow = document.createElement("tr");
                headerRow.innerHTML = `<th>AÑOS</th>${getNombresProductos().map(p => `<th>${p}</th>`).join("")}`;
                theadDemanda.appendChild(headerRow);

                // Crear filas por año
                for (let anio = inicio; anio <= fin; anio++) {
                    const row = document.createElement("tr");
                    row.innerHTML = `<td>${anio}</td>${Array(getNumeroProductos()).fill('<td><input type="number" class="mdl-textfield__input" style="width: 80px;"></td>').join("")}`;
                    tbodyDemanda.appendChild(row);
                }

                componentHandler.upgradeDom();
            }

            // ========== TABLA COMPETIDORES ==========
            function actualizarTablaCompetidores() {
    const contenedor = document.getElementById("contenedorNivelesCompetencia");
    contenedor.innerHTML = "";

    const productos = getNombresProductos();
    const inputsVentas = Array.from(cuerpoVentas.querySelectorAll("input"));

    productos.forEach((producto, index) => {
        const valorEmpresa = parseFloat(inputsVentas[index]?.value || 0);
        const tabla = document.createElement("table");
        tabla.className = "mdl-data-table mdl-js-data-table tabla-competencia";
        tabla.dataset.index = index;
        tabla.style.minWidth = "200px";

        const competidores = datosProductosBD[index]?.competidores || [];

        tabla.innerHTML = `
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center;">${producto}</th>
                </tr>
                <tr>
                    <th>EMPRESA</th>
                    <th><input type="number" value="${valorEmpresa}" class="mdl-textfield__input input-empresa" style="width: 70px;"></th>
                </tr>
                <tr>
                    <th>Competidor</th>
                    <th>Ventas</th>
                </tr>
            </thead>
            <tbody>
                ${Array.from({ length: 9 }, (_, i) => {
                    const comp = competidores[i] || {};
                    const nombre = comp.nombre_competidor || `${producto.substring(0,2)}-${i + 1}`;
                    const valor = comp.ventas || "";
                    return `
                        <tr>
                            <td>${nombre}</td>
                            <td><input type="number" class="mdl-textfield__input input-competidor" style="width: 70px;" value="${valor}"></td>
                        </tr>
                    `;
                }).join("")}
                <tr>
                    <td><strong>Mayor</strong></td>
                    <td><input type="number" class="mdl-textfield__input input-mayor" style="width: 70px;" readonly></td>
                </tr>
            </tbody>
        `;

        contenedor.appendChild(tabla);
    });

    componentHandler.upgradeDom();
    registrarEventosCompetencia();
    actualizarTablaBCG();
}


            function registrarEventosCompetencia() {
                const tablas = document.querySelectorAll(".tabla-competencia");

                tablas.forEach(tabla => {
                    const inputCompetidores = tabla.querySelectorAll(".input-competidor");
                    const inputMayor = tabla.querySelector(".input-mayor");

                    const actualizarMayor = () => {
                        let max = 0;
                        inputCompetidores.forEach(input => {
                            const val = parseFloat(input.value);
                            if (!isNaN(val) && val > max) {
                                max = val;
                            }
                        });
                        inputMayor.value = max;
                        actualizarTablaBCG();
                    };

                    inputCompetidores.forEach(input => input.addEventListener("input", actualizarMayor));
                    actualizarMayor();
                });
            }

            // ========== MATRIZ BCG ==========
            function generarMatrizBCG() {
                const ctx = document.getElementById("matrizBCG").getContext("2d");
                
                let data = [];
                
                if(datosProductosBD && datosProductosBD.length > 0) {
                    data = datosProductosBD.map(producto => ({
                        x: parseFloat(producto.prm),
                        y: parseFloat(producto.tcm),
                        r: Math.max(5, (producto.porcentaje_total / 100) * 30),
                        nombre: producto.nombre,
                        backgroundColor: getColorCuadrante(producto.prm, producto.tcm)
                    }));
                } else {
                    const nombresProductos = getNombresProductos();
                    const tablaBCG = document.getElementById("tablaBCG");
                    const filas = tablaBCG.querySelectorAll("tbody tr");

                    const tcmVals = Array.from(filas[0].querySelectorAll("input")).map(i => parseFloat(i.value));
                    const prmVals = Array.from(filas[1].querySelectorAll("input")).map(i => parseFloat(i.value));
                    const vtasVals = Array.from(filas[2].querySelectorAll("input")).map(i => parseFloat(i.value));

                    data = nombresProductos.map((nombre, i) => ({
                        x: prmVals[i],
                        y: tcmVals[i],
                        r: Math.max(5, (vtasVals[i] / 100) * 30),
                        nombre,
                        backgroundColor: getColorCuadrante(prmVals[i], tcmVals[i])
                    }));
                }

                if (window.matrizChart) window.matrizChart.destroy();

                window.matrizChart = new Chart(ctx, {
                    type: 'bubble',
                    data: {
                        datasets: [{
                            label: 'Productos',
                            data: data,
                            borderColor: 'black',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            datalabels: {
                                formatter: (val) => `${val.r.toFixed(2)}%`,
                                color: '#fff',
                                font: {
                                    weight: 'bold',
                                    size: 12
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (ctx) {
                                        return ctx.raw.nombre + ` (PRM: ${ctx.raw.x}, TCM: ${ctx.raw.y})`;
                                    }
                                }
                            },
                            legend: { display: false },
                            annotation: {
                                annotations: {
                                    lineaVertical: {
                                        type: 'line',
                                        xMin: 1,
                                        xMax: 1,
                                        borderColor: 'gray',
                                        borderWidth: 2,
                                        borderDash: [6, 4]
                                    },
                                    lineaHorizontal: {
                                        type: 'line',
                                        yMin: 10,
                                        yMax: 10,
                                        borderColor: 'gray',
                                        borderWidth: 2,
                                        borderDash: [6, 4]
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: { display: true, text: 'PRM' },
                                min: 0,
                                max: 2
                            },
                            y: {
                                title: { display: true, text: 'TCM (%)' },
                                min: 0,
                                max: 20
                            }
                        }
                    },
                    plugins: [cuadrantesEmojiPlugin]
                });
            }

            // ========== EVENT LISTENERS ==========
            document.getElementById("btnAgregarProducto").addEventListener("click", () => agregarFila());

            document.getElementById("generarTablaTCM").addEventListener("click", () => {
                const inicio = parseInt(document.getElementById("anioInicio").value);
                const fin = parseInt(document.getElementById("anioFin").value);

                if (isNaN(inicio) || isNaN(fin) || inicio > fin) {
                    Swal.fire("Error", "El intervalo de años no es válido", "error");
                    return;
                }

                actualizarTablaTCM();
            });

            document.getElementById("generarTablaDemanda").addEventListener("click", actualizarTablaDemanda);
            document.getElementById("generarTablaCompetidores").addEventListener("click", actualizarTablaCompetidores);

            // Escuchar cambios en inputs de TCM para actualizar BCG automáticamente
            document.getElementById("tablaTCM").addEventListener("input", function (e) {
                if (e.target.tagName === "INPUT") {
                    actualizarTablaBCG();
                }
            });

            // Escuchar cambios en los inputs de ventas para actualizar tabla de competidores
            cuerpoVentas.addEventListener("input", function (e) {
                if (e.target && e.target.classList.contains("venta-input")) {
                    actualizarTablaCompetidores();
                }
            });

            // Inicialización
            document.addEventListener('DOMContentLoaded', function() {
                if(datosProductosBD && datosProductosBD.length > 0) {
                    document.getElementById("cuerpoVentas").innerHTML = "";
                    datosProductosBD.forEach(producto => {
                        agregarFila(producto.ventas, producto.nombre);
                    });
                } else {
                    for (let i = 0; i < 5; i++) {
                        agregarFila([500, 30, 2000, 10, 10][i]);
                    }
                }
            });
        </script>
    </section>
</body>
</html>