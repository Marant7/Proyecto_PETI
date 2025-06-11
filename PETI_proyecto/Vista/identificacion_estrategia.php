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
  <title>Identificacion Estrategica</title>
  <link rel="stylesheet" href="../public/css/estrategia.css">
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
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="columna-lateral amenazas">AMENAZAS</td>
                        <td class="content-cell amenazas">
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="columna-lateral fortalezas">FORTALEZAS</td>
                        <td class="content-cell fortalezas">
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="columna-lateral oportunidades">OPORTUNIDADES</td>
                        <td class="content-cell oportunidades">
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                            <span class="item-text"></span>
                        </td>
                    </tr>
                </table>

                <!-- Matriz 1: Fortalezas vs Oportunidades -->
                <div class="matrix-container">
                    <div class="instructions">
                        <strong>Instrucciones:</strong> Selecciona el nivel de acuerdo para cada intersección entre Fortalezas y Oportunidades.
                    </div>
                    
                    <div class="matrix-title">Matriz 2: Las fortalezas evaden el efecto negativo de  las amenazas.</div>
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
                <!-- Matriz 2: Debilidades vs Amenazas -->
                              <div class="matrix-container">
                    <div class="instructions">
                        <strong>Instrucciones:</strong> Selecciona el nivel de acuerdo para cada intersección entre Fortalezas y Oportunidades.
                    </div>
                    
                    <div class="matrix-title">Matriz 1: Las fortalezas se usan para tomar ventaja de cada una de las oportunidades</div>
                    <div class="matrix-subtitle">0=En total desacuerdo, 1=No está de acuerdo, 2=Está de acuerdo, 3=Bastante de acuerdo, 4=En total acuerdo</div>
                    
                    <table class="small-matrix" data-matrix="fo">
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

                <!-- Matriz 3: Debilidades vs Amenazas -->
                <div class="matrix-container">
                    <div class="instructions">
                        <strong>Instrucciones:</strong> Selecciona el nivel de acuerdo para cada intersección entre Fortalezas y Oportunidades.
                    </div>
                    
                    <div class="matrix-title">Matriz 4:Las debilidades intensifican notablemente el efecto negativo de las amenazas</div>
                    <div class="matrix-subtitle">0=En total desacuerdo, 1=No está de acuerdo, 2=Está de acuerdo, 3=Bastante de acuerdo, 4=En total acuerdo</div>
                    
                    <table class="small-matrix" data-matrix="fo">
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
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D2</td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D3</td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                                <td><select onchange="updateTotals('fo')"><option value="">--</option><option value="0">0 - En total desacuerdo</option><option value="1">1 - No está de acuerdo</option><option value="2">2 - Está de acuerdo</option><option value="3">3 - Bastante de acuerdo</option><option value="4">4 - En total acuerdo</option></select></td>
                            </tr>
                            <tr class="row-fortalezas">
                                <td class="label-cell">D4</td>
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

            </div>
        </div>
    </div>

    
  </div>

</body>
</html>

</body>
</html>


<script>
        // Función principal para actualizar totales de una matriz específica
        function updateTotals(matrixType) {
            const table = document.querySelector(`[data-matrix="${matrixType}"]`);
            if (!table) return;
            
            // Obtener todas las filas que contienen selects (excluyendo la fila de totales)
            const dataRows = table.querySelectorAll('tbody tr:not(.total-row)');
            
            // Inicializar totales para cada columna
            const columnTotals = [0, 0, 0, 0];
            
            // Recorrer cada fila de datos
            dataRows.forEach(row => {
                const selects = row.querySelectorAll('select');
                selects.forEach((select, columnIndex) => {
                    // Obtener el valor seleccionado y convertir a número
                    const value = parseInt(select.value) || 0;
                    // Sumar al total de la columna correspondiente
                    columnTotals[columnIndex] += value;
                });
            });
            
            // Actualizar las celdas de totales en la interfaz
            for (let i = 0; i < 4; i++) {
                const totalCell = document.getElementById(`total-${matrixType}-col${i + 1}`);
                if (totalCell) {
                    totalCell.textContent = columnTotals[i];
                }
            }
            
            // Calcular y actualizar el total general de la matriz
            const generalTotal = columnTotals.reduce((sum, total) => sum + total, 0);
            const generalTotalSpan = document.getElementById(`total-${matrixType}-general`);
            if (generalTotalSpan) {
                generalTotalSpan.textContent = generalTotal;
            }
            
            // Log para debugging
            console.log(`Totales de matriz ${matrixType.toUpperCase()}:`, {
                'Columna 1': columnTotals[0],
                'Columna 2': columnTotals[1],
                'Columna 3': columnTotals[2],
                'Columna 4': columnTotals[3],
                'Total General': generalTotal
            });
        }
        
        // Función para obtener todos los valores de una matriz
        function getMatrixValues(matrixType) {
            const table = document.querySelector(`[data-matrix="${matrixType}"]`);
            if (!table) return {};
            
            const values = {};
            const dataRows = table.querySelectorAll('tbody tr:not(.total-row)');
            
            dataRows.forEach((row, rowIndex) => {
                const rowLabel = row.querySelector('.label-cell').textContent;
                values[rowLabel] = {};
                
                const selects = row.querySelectorAll('select');
                selects.forEach((select, colIndex) => {
                    const colLabel = table.querySelectorAll('thead th')[colIndex + 1].textContent;
                    values[rowLabel][colLabel] = select.value || '0';
                });
            });
            
            return values;
        }
        
        // Función para obtener todos los valores de todas las matrices
        function getAllMatrixValues() {
            return {
                'Fortalezas_vs_Oportunidades': getMatrixValues('fo'),
                'Fortalezas_vs_Amenazas': getMatrixValues('fa'),
                'Debilidades_vs_Oportunidades': getMatrixValues('do'),
                'Debilidades_vs_Amenazas': getMatrixValues('da')
            };
        }
        
        // Función para exportar datos (útil para debugging o exportación)
        function exportData() {
            const data = getAllMatrixValues();
            console.log('Datos completos del análisis FODA:', JSON.stringify(data, null, 2));
            return data;
        }
        
        // Inicializar totales cuando la página carga
        document.addEventListener('DOMContentLoaded', function() {
            // Pequeño delay para asegurar que todo esté cargado
            setTimeout(() => {
                updateTotals('fo');
                updateTotals('fa');
                updateTotals('do');
                updateTotals('da');
            }, 100);
        });
        
        // También actualizar totales cuando la página sea visible (para casos de cache)
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateTotals('fo');
                updateTotals('fa');
                updateTotals('do');
                updateTotals('da');
            }
        });
    </script>