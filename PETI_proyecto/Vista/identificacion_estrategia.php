<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "No hay usuario en sesión";
    exit();
}

require_once '../config/clsConexion.php';
require_once '../Controllers/identificacionController.php';

$id_usuario = $_SESSION['user_id'];

$controller = new identificacionController();

// Obtener el id_empresa del usuario
$id_empresa = $controller->obtenerIdEmpresaPorUsuario($id_usuario);

if (!$id_empresa) {
    echo "No se encontró empresa asociada al usuario";
    exit();
}

$datos = $controller->obtenerDatosFODA($id_empresa);

$fortalezas    = $datos['fortalezas'] ?? [];
$debilidades   = $datos['debilidades'] ?? [];
$oportunidades = $datos['oportunidades'] ?? [];
$amenazas      = $datos['amenazas'] ?? [];
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Identificacion Estrategica</title>
  <link rel="stylesheet" href="../public/css/estrategiaidentifica.css">
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
  <script src="../public/js/estrategia.js"></script>
  <script src="../public/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../public/js/visiMisi.js"></script> <!-- Agregar el archivo JS -->

</head>
<body>
  <!-- navBar -->
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
                    <li class="text-condensedLight noLink">
                        <small><?php echo $_SESSION['usuario']; ?></small>
                    </li>
                    <li class="noLink">
                        <figure>
                            <img src="../public/assets/img/avatar-male.png" alt="Avatar" class="img-responsive">
                        </figure>
                    </li>
					</li>
				</ul>
			</nav>
		</div>
	</div>
    <div class="main-layout">
    <div class="navLateral"></div>
    <?php include 'sidebar.php'; ?>




    <div class="pageContent">
        <div class="content">
            <div class="container">
                <h2>Identificación Estratégica</h2>
                <h5>Según ha ido cumplimentando en las fases anteriores, los factores internos y externos de su empresa son los siguientes:</h5>
                <br>
                
                   <table class="foda-matrix">
                        <tr>
                            <td class="columna-lateral debilidades">DEBILIDADES</td>
                            <td class="content-cell debilidades">
                                <?php if (empty($debilidades)): ?>
                                    <span class="item-text">No hay debilidades registradas</span>
                                <?php else: ?>
                                    <?php foreach ($debilidades as $descripcion): ?>
                                        <span class="item-text"><?php echo htmlspecialchars($descripcion); ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="columna-lateral amenazas">AMENAZAS</td>
                            <td class="content-cell amenazas">
                                <?php if (empty($amenazas)): ?>
                                    <span class="item-text">No hay amenazas registradas</span>
                                <?php else: ?>
                                    <?php foreach ($amenazas as $descripcion): ?>
                                        <span class="item-text"><?php echo htmlspecialchars($descripcion); ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="columna-lateral fortalezas">FORTALEZAS</td>
                            <td class="content-cell fortalezas">
                                <?php if (empty($fortalezas)): ?>
                                    <span class="item-text">No hay fortalezas registradas</span>
                                <?php else: ?>
                                    <?php foreach ($fortalezas as $descripcion): ?>
                                        <span class="item-text"><?php echo htmlspecialchars($descripcion); ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="columna-lateral oportunidades">OPORTUNIDADES</td>
                            <td class="content-cell oportunidades">
                                <?php if (empty($oportunidades)): ?>
                                    <span class="item-text">No hay oportunidades registradas</span>
                                <?php else: ?>
                                    <?php foreach ($oportunidades as $descripcion): ?>
                                        <span class="item-text"><?php echo htmlspecialchars($descripcion); ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>


                <!-- Matriz 1: Fortalezas vs Oportunidades -->
                <div class="matrix-container">
                    <div class="instructions">
                        <strong>Instrucciones:</strong> Selecciona el nivel de acuerdo para cada intersección entre Fortalezas y Oportunidades.
                    </div>
                    
                    <div class="matrix-title"> Las fortalezas se usan para tomar ventaja en cada una las oportunidades.</div>
                    <div class="matrix-subtitle">0=En total desacuerdo, 1=No está de acuerdo, 2=Está de acuerdo, 3=Bastante de acuerdo, 4=En total acuerdo</div>
                    
                    <table class="small-matrix" data-matrix="fo">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="header-oportunidades">O1</th>
                                <th class="header-oportunidades">O2</th>
                                <th class="header-oportunidades">O3</th>
                                <th class="header-oportunidades">O4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="row-fortalezas">
                                <td class="label-cell">F1</td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">F2</td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">F3</td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">F4</td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="total-row">
                                <td class="label-cell">Total</td>
                                <td class="total-cell" id="total-fo-col1">0</td>
                                <td class="total-cell" id="total-fo-col2">0</td>
                                <td class="total-cell" id="total-fo-col3">0</td>
                                <td class="total-cell" id="total-fo-col4">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Matriz 2: Fortalezas vs Amenazas -->
                              <div class="matrix-container">
                    <div class="instructions">
                        <strong>Instrucciones:</strong> Selecciona el nivel de acuerdo para cada intersección entre Fortalezas y Oportunidades.
                    </div>
                    
                    <div class="matrix-title">Las fortalezas evaden el efecto negativo de  las amenazas.</div>
                    <div class="matrix-subtitle">0=En total desacuerdo, 1=No está de acuerdo, 2=Está de acuerdo, 3=Bastante de acuerdo, 4=En total acuerdo</div>
                    
                    <table class="small-matrix" data-matrix="fa">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="header-oportunidades">A1</th>
                                <th class="header-oportunidades">A2</th>
                                <th class="header-oportunidades">A3</th>
                                <th class="header-oportunidades">A4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="row-fortalezas">
                                <td class="label-cell">F1</td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">F2</td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">F3</td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">F4</td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fa')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="total-row">
                                <td class="label-cell">Total</td>
                                <td class="total-cell" id="total-fa-col1">0</td>
                                <td class="total-cell" id="total-fa-col2">0</td>
                                <td class="total-cell" id="total-fa-col3">0</td>
                                <td class="total-cell" id="total-fa-col4">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Matriz 3: Debilidades vs Amenazas -->
                <div class="matrix-container">
                    <div class="instructions">
                        <strong>Instrucciones:</strong> Selecciona el nivel de acuerdo para cada intersección entre Fortalezas y Oportunidades.
                    </div>
                    
                    <div class="matrix-title">Las debilidades intensifican notablemente el efecto negativo de las amenazas</div>
                    <div class="matrix-subtitle">0=En total desacuerdo, 1=No está de acuerdo, 2=Está de acuerdo, 3=Bastante de acuerdo, 4=En total acuerdo</div>
                    
                    <table class="small-matrix" data-matrix="da">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="header-oportunidades">A1</th>
                                <th class="header-oportunidades">A2</th>
                                <th class="header-oportunidades">A3</th>
                                <th class="header-oportunidades">A4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D1</td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D2</td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D3</td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D4</td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('da')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="total-row">
                                <td class="label-cell">Total</td>
                                <td class="total-cell" id="total-da-col1">0</td>
                                <td class="total-cell" id="total-da-col2">0</td>
                                <td class="total-cell" id="total-da-col3">0</td>
                                <td class="total-cell" id="total-da-col4">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <!-- Matriz 4: Oportunidades vs Debilidades -->

                <div class="matrix-container">
                    <div class="instructions">
                        <strong>Instrucciones:</strong> Selecciona el nivel de acuerdo para cada intersección entre Fortalezas y Oportunidades.
                    </div>
                    
                    <div class="matrix-title">Superamos las debilidades tomando ventaja de las oportunidades</div>
                    <div class="matrix-subtitle">0=En total desacuerdo, 1=No está de acuerdo, 2=Está de acuerdo, 3=Bastante de acuerdo, 4=En total acuerdo</div>
                    
                    <table class="small-matrix" data-matrix="do">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="header-oportunidades">O1</th>
                                <th class="header-oportunidades">O2</th>
                                <th class="header-oportunidades">O3</th>
                                <th class="header-oportunidades">O4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D1</td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D2</td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D3</td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D4</td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('do')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="total-row">
                                <td class="label-cell">Total</td>
                                <td class="total-cell" id="total-do-col1">0</td>
                                <td class="total-cell" id="total-do-col2">0</td>
                                <td class="total-cell" id="total-do-col3">0</td>
                                <td class="total-cell" id="total-do-col4">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Matriz 4: Debilidades vs Amenazas -->
                    Superamos las debilidades tomando ventaja de las oportunidades
                <!-- Síntesis de Resultados -->
                <div class="sintesis-section">
                    <div class="sintesis-title">Síntesis de Resultados</div>
                    
                    <table class="sintesis-table">
                        <thead>
                            <tr>
                                <th>Relaciones</th>
                                <th>Tipología de estrategia</th>
                                <th>Puntuación</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="relacion-cell">FO</td>
                                <td class="estrategia-cell">Estrategia Ofensiva</td>
                                <td class="puntuacion-cell">4</td>
                                <td class="descripcion-cell">Deberá adoptar estrategias de crecimiento</td>
                            </tr>
                            <tr>
                                <td class="relacion-cell">AF</td>
                                <td class="estrategia-cell">Estrategia Defensiva</td>
                                <td class="puntuacion-cell">0</td>
                                <td class="descripcion-cell">La empresa no está preparada para enfrentarse a las amenazas</td>
                            </tr>
                            <tr>
                                <td class="relacion-cell">AD</td>
                                <td class="estrategia-cell">Estrategia de Supervivencia</td>
                                <td class="puntuacion-cell">17</td>
                                <td class="descripcion-cell">Se enfrenta a amenazas externas sin las fortalezas necesarias para luchar con la competencia</td>
                            </tr>
                            <tr>
                                <td class="relacion-cell">OD</td>
                                <td class="estrategia-cell">Estrategia de Reorientación</td>
                                <td class="puntuacion-cell">0</td>
                                <td class="descripcion-cell">La empresa no puede aprovechar las oportunidades porque carece de preparación adecuada</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="sintesis-note">
                        La puntuación mayor te indica la estrategia que deberá llevar a cabo.
                    </div>
                </div>

                <div style="margin: 20px 0; text-align: right;">
                    <button id="btn-guardar-matrices" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                        Guardar Matrices FODA
                    </button>
                    <span id="mensaje-guardar-matrices" style="margin-left: 15px; color: green; font-weight: bold;"></span>
                </div>

            </div>
        </div>
    </div>

</body>
</html>

</body>
</html>


