<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "No hay usuario en sesión";
    exit();
}

require_once '../config/clsconexion.php';

$conexion = new clsConexion();
$db = $conexion->getConexion();



?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Valores</title>
  <link rel="stylesheet" href="../public/css/valores.css">
  <link rel="stylesheet" href="../public/css/normalize.css">
		<link rel="stylesheet" href="../public/css/sweetalert2.css">
		<link rel="stylesheet" href="../public/css/material.min.css">
		<link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
		<link rel="stylesheet" href="../public/css/jquery.mCustomScrollbar.css">
		<link rel="stylesheet" href="../public/css/main.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="../public/js/jquery-1.11.2.min.js"><\/script>')</script>
		<script src="../public/js/material.min.js"></script>
		<script src="../public/js/sweetalert2.min.js"></script>
		<script src="../public/js/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="../public/js/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../public/js/valores.js"></script>

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
	        <a href="#" class="Notification" id="notifation-unread-2">
	            <div class="Notification-icon"><i class="zmdi zmdi-upload bg-success"></i></div>
	            <div class="Notification-text">
	                <p>
	                    <i class="zmdi zmdi-circle"></i>
	                    <strong>Archive uploaded</strong> 
	                    <br>
	                    <small>31 Mins Ago</small>
	                </p>
	            </div>
	            <div class="mdl-tooltip mdl-tooltip--left" for="notifation-unread-2">Notification as UnRead</div>
	        </a> 
	        <a href="#" class="Notification" id="notifation-read-2">
	            <div class="Notification-icon"><i class="zmdi zmdi-mail-send bg-danger"></i></div>
	            <div class="Notification-text">
	                <p>
	                    <i class="zmdi zmdi-circle-o"></i>
	                    <strong>New Mail</strong> 
	                    <br>
	                    <small>37 Mins Ago</small>
	                </p>
	            </div>
	            <div class="mdl-tooltip mdl-tooltip--left" for="notifation-read-2">Notification as Read</div>
	        </a>
	        <a href="#" class="Notification" id="notifation-read-3">
	            <div class="Notification-icon"><i class="zmdi zmdi-folder bg-primary"></i></div>
	            <div class="Notification-text">
	                <p>
	                    <i class="zmdi zmdi-circle-o"></i>
	                    <strong>Folder delete</strong> 
	                    <br>
	                    <small>1 hours Ago</small>
	                </p>
	            </div>
	            <div class="mdl-tooltip mdl-tooltip--left" for="notifation-read-3">Notification as Read</div>
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
						<!-- <i class="zmdi zmdi-notifications-active btn-Notification" id="notifications"></i> -->
						<div class="mdl-tooltip" for="notifications">Notifications</div>
					</li>
					<li class="btn-exit" id="btn-exit">
						<i class="zmdi zmdi-power"></i>
						<div class="mdl-tooltip" for="btn-exit">LogOut</div>
					</li>
					<li class="text-condensedLight noLink" ><small>User Name</small></li>
					<li class="noLink">
						<figure>
							<img src="assets/img/avatar-male.png" alt="Avatar" class="img-responsive">
						</figure>
					</li>
				</ul>
			</nav>
		</div>
	</div>
	<?php include 'sidebar.php'; ?>
	<!-- pageContent -->
	<section class="full-width pageContent">
        <?php
        // 1. Incluimos la clase de conexión
        require_once '../config/clsconexion.php';

        // 2. Creamos la instancia de conexión
        $conexion = new clsConexion();
        $db = $conexion->getConexion();

        // 3. Consulta para obtener objetivos estratégicos
        $queryEstrategicos = "SELECT * FROM tb_obj_estra";
        $resultEstrategicos = $db->query($queryEstrategicos);

        if (!$resultEstrategicos) {
            die("Error en la consulta: " . $db->error);
        }

        // 4. Procesamos los resultados
        $datos = [];
        while ($estrategico = $resultEstrategicos->fetch_assoc()) {
            // Consulta para objetivos específicos de este estratégico
            $queryEspecificos = "SELECT * FROM tb_obj_especificos WHERE id_obj_estra = ?";
            $stmt = $db->prepare($queryEspecificos);
            $stmt->bind_param("i", $estrategico['id_obj_estra']);
            $stmt->execute();
            $resultEspecificos = $stmt->get_result();
            
            $especificos = [];
            while ($especifico = $resultEspecificos->fetch_assoc()) {
                $especificos[] = $especifico;
            }
            
            $datos[] = [
                'estrategico' => $estrategico,
                'especificos' => $especificos
            ];
            
            $stmt->close();
        }

        // 5. Cerramos la conexión
        $conexion->Cerrarconex();
        ?>

        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
            <div class="mdl-tabs__tab-bar">
                <a href="#tabListProducts" class="mdl-tabs__tab is-active">Presentación</a>
                <a href="#tabNewProduct" class="mdl-tabs__tab">Edición</a>
            </div>
            
           <!-- Panel de Presentación -->
<div class="mdl-tabs__panel is-active" id="tabListProducts">
    <div class="mdl-grid">
        
        
        
        
       
        
        
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
        <thead>
            <tr>
                <th>PERIODO</th>
                <th>Producto 1</th>
                <th>Producto 2</th>
                <th>Producto 3</th>
                <th>Producto 4</th>
                <th>Producto 5</th>
            </tr>
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
        <thead>
            <tr>
                <th>BCG</th>
                <th>Producto 1</th>
                <th>Producto 2</th>
                <th>Producto 3</th>
                <th>Producto 4</th>
                <th>Producto 5</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>TCM</td>
                <td><input type="number" step="0.01" min="0" max="100" value="3.80" class="mdl-textfield__input" style="width: 70px;">%</td>
                <td><input type="number" step="0.01" min="0" max="100" value="2.80" class="mdl-textfield__input" style="width: 70px;">%</td>
                <td><input type="number" step="0.01" min="0" max="100" value="3.00" class="mdl-textfield__input" style="width: 70px;">%</td>
                <td><input type="number" step="0.01" min="0" max="100" value="3.00" class="mdl-textfield__input" style="width: 70px;">%</td>
                <td><input type="number" step="0.01" min="0" max="100" value="1.00" class="mdl-textfield__input" style="width: 70px;">%</td>
            </tr>
            <tr>
                <td>PRM</td>
                <td><input type="number" step="0.01" min="0" max="100" value="2.00" class="mdl-textfield__input" style="width: 70px;"></td>
                <td><input type="number" step="0.01" min="0" max="100" value="5.00" class="mdl-textfield__input" style="width: 70px;"></td>
                <td><input type="number" step="0.01" min="0" max="100" value="2.00" class="mdl-textfield__input" style="width: 70px;"></td>
                <td><input type="number" step="0.01" min="0" max="100" value="2.00" class="mdl-textfield__input" style="width: 70px;"></td>
                <td><input type="number" step="0.01" min="0" max="100" value="2.00" class="mdl-textfield__input" style="width: 70px;"></td>
            </tr>
            <tr>
                <td>% S/VTAS</td>
                <td><input type="number" step="0.01" min="0" max="100" value="20" class="mdl-textfield__input" style="width: 70px;">%</td>
                <td><input type="number" step="0.01" min="0" max="100" value="1" class="mdl-textfield__input" style="width: 70px;">%</td>
                <td><input type="number" step="0.01" min="0" max="100" value="78" class="mdl-textfield__input" style="width: 70px;">%</td>
                <td><input type="number" step="0.01" min="0" max="100" value="0" class="mdl-textfield__input" style="width: 70px;">%</td>
                <td><input type="number" step="0.01" min="0" max="100" value="0" class="mdl-textfield__input" style="width: 70px;">%</td>
            </tr>
        </tbody>
    </table>
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

<script>
let contadorProducto = 1;
const cuerpo = document.getElementById("cuerpoVentas");
const totalVentasEl = document.getElementById("totalVentas");

function actualizarPorcentajes() {
    const filas = cuerpo.querySelectorAll("tr");
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
}

function agregarFila(valor = 0) {
    const tr = document.createElement("tr");
    tr.innerHTML = `
        <td>Producto ${contadorProducto}</td>
        <td><input type="number" min="0" value="${valor}" class="mdl-textfield__input venta-input" style="width:80px;"></td>
        <td class="porcentaje">0.00%</td>
        <td><button class="mdl-button mdl-js-button mdl-button--icon btn-eliminar"><i class="zmdi zmdi-delete"></i></button></td>
    `;

    cuerpo.appendChild(tr);
    contadorProducto++;

    // Agregar eventos
    tr.querySelector("input").addEventListener("input", actualizarPorcentajes);
    tr.querySelector(".btn-eliminar").addEventListener("click", () => {
        tr.remove();
        renombrarProductos();
        actualizarPorcentajes();
    });

    componentHandler.upgradeDom(); // Actualizar estilos MDL
    actualizarPorcentajes();
}

function renombrarProductos() {
    const filas = cuerpo.querySelectorAll("tr");
    contadorProducto = 1;
    filas.forEach(fila => {
        fila.querySelector("td").textContent = "Producto " + contadorProducto;
        contadorProducto++;
    });
}

document.getElementById("btnAgregarProducto").addEventListener("click", () => {
    agregarFila();
});

// Inicializar con 5 productos
for (let i = 0; i < 5; i++) {
    agregarFila([500, 30, 2000, 10, 10][i]); // valores iniciales
}
</script>

<script>
function generarFilasTCM(inicio, fin) {
    const cuerpo = document.getElementById("cuerpoTCM");
    cuerpo.innerHTML = "";

    for (let anio = inicio; anio <= fin; anio++) {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${anio} - ${anio + 1}</td>
            <td><input type="number" min="0" max="100" step="0.01" value="3.00" class="mdl-textfield__input" style="width: 70px;">%</td>
            <td><input type="number" min="0" max="100" step="0.01" value="2.00" class="mdl-textfield__input" style="width: 70px;">%</td>
            <td><input type="number" min="0" max="100" step="0.01" value="3.00" class="mdl-textfield__input" style="width: 70px;">%</td>
            <td><input type="number" min="0" max="100" step="0.01" value="3.00" class="mdl-textfield__input" style="width: 70px;">%</td>
            <td><input type="number" min="0" max="100" step="0.01" value="1.00" class="mdl-textfield__input" style="width: 70px;">%</td>
        `;
        cuerpo.appendChild(fila);
    }

    componentHandler.upgradeDom(); // Para que se apliquen los estilos MDL
}

document.getElementById("generarTablaTCM").addEventListener("click", () => {
    const inicio = parseInt(document.getElementById("anioInicio").value);
    const fin = parseInt(document.getElementById("anioFin").value);

    if (isNaN(inicio) || isNaN(fin) || inicio > fin) {
        Swal.fire("Error", "El intervalo de años no es válido", "error");
        return;
    }

    generarFilasTCM(inicio, fin);
});

// Inicializar con datos por defecto
generarFilasTCM(2012, 2016);
</script>

</body>
</html>