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
  <title>Ingresar Visión y Diseño</title>
  <link rel="stylesheet" href="../public/css/fuerzas_porter.css">
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
                    <!-- Muestra el nombre del usuario -->
                    <li class="text-condensedLight noLink">
                        <small><?php echo $_SESSION['usuario']; ?></small>
                    </li>
                    <li class="noLink">
                        <figure>
                            <img src="../public/assets/img/avatar-male.png" alt="Avatar" class="img-responsive">
                        </figure>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

  <div class="main-layout">
    <div class="navLateral">  </div>
    <?php include 'sidebar.php'; ?>
    <div class="pageContent">
      <div class="content">
        <div class="container">
		<div class="descripcion">
			<div class="descripcion">
				A continuación marque con una X en las casillas que estime conveniente según el estado actual de su empresa. Valore su perfil competitivo en la escala Hostil-Favorable. Al finalizar lea la conclusión, para su caso particular, relativa al análisis del entorno próximo.
			</div>

			<table>
				<tr>
					<th rowspan="2" class="subtitulo">PERFIL COMPETITIVO</th>
				</tr>
				<tr>
					<th class="hostil">Hostil</th>
					<th>Nada</th>
					<th>Poco</th>
					<th>Medio</th>
					<th>Alto</th>
					<th>Muy Alto</th>
					<th class="favorable">Favorable</th>
				</tr>

				<!-- RIVALIDAD EMPRESAS -->
				<tr class="fila-titulo">
					<td colspan="9">Rivalidad empresas del sector</td>
				</tr>

				<?php
				$factores_rivalidad = [
					["- Crecimiento", "Lento", "Rápido"],
					["- Naturaleza de los competidores", "Muchos", "Pocos"],
					["- Exceso de capacidad productiva", "Sí", "No"],
					["- Rentabilidad media del sector", "Baja", "Alta"],
					["- Diferenciación del producto", "Escasa", "Elevada"],
					["- Barreras de salida", "Bajas", "Altas"],
				];

				foreach ($factores_rivalidad as $factor) {
					echo "<tr>";
					echo "<td style='text-align: left; background-color: #e0f0d8;'>{$factor[0]}</td>";
					echo "<td class='hostil'>{$factor[1]}</td>";
					for ($i = 0; $i < 5; $i++) {
						echo "<td><input type='checkbox'></td>";
					}
					echo "<td class='favorable' colspan='2'>{$factor[2]}</td>";
					echo "</tr>";
				}
				?>

				<!-- BARRERAS DE ENTRADA -->
				<tr class="fila-titulo">
					<td colspan="9">Barreras de Entrada</td>
				</tr>

				<?php
				$factores_barreras = [
					["- Economías de escala", "No", "Sí"],
					["- Necesidad de capital", "Bajas", "Altas"],
					["- Acceso a la tecnología", "Fácil", "Difícil"],
					["- Reglamentos o leyes limitativas", "No", "Sí"],
					["- Trámites burocráticos", "No", "Sí"],
					["- Reacción esperada actuales competidores", "Escasa", "Enérgica"],
				];

				foreach ($factores_barreras as $factor) {
					echo "<tr>";
					echo "<td style='text-align: left; background-color: #e0f0d8;'>{$factor[0]}</td>";
					echo "<td class='hostil'>{$factor[1]}</td>";
					for ($i = 0; $i < 5; $i++) {
						echo "<td><input type='checkbox'></td>";
					}
					echo "<td class='favorable' colspan='2'>{$factor[2]}</td>";
					echo "</tr>";
				}
				?>

				<!-- PODER DE LOS CLIENTES -->
				<tr class="fila-titulo">
					<td colspan="9">Poder de los Clientes</td>
				</tr>
				<?php
				$factores_barreras = [
					["- Número de clientes", "Pocos", "Muchos"],
					["- Posibilidad de integración ascendente", "Pequeña", "Grande"],
					["- Rentabilidad de los clientes", "Baja", "Alta"],
					["- Coste de cambio de proveedor para cliente", "Bajo", "Alto"],
				];

				foreach ($factores_barreras as $factor) {
					echo "<tr>";
					echo "<td style='text-align: left; background-color: #e0f0d8;'>{$factor[0]}</td>";
					echo "<td class='hostil'>{$factor[1]}</td>";
					for ($i = 0; $i < 5; $i++) {
						echo "<td><input type='checkbox'></td>";
					}
					echo "<td class='favorable' colspan='2'>{$factor[2]}</td>";
					echo "</tr>";
				}
				?>

				<!-- PRODUCTOS SUSTITUTIVOS -->
				<tr class="fila-titulo">
					<td colspan="9">Productos Sustitutivos</td>
					<?php
				$factores_barreras = [
					["- Disponibilidad de Productos Sustitutivos", "Grande", "Pequeña"],
				];

				foreach ($factores_barreras as $factor) {
					echo "<tr>";
					echo "<td style='text-align: left; background-color: #e0f0d8;'>{$factor[0]}</td>";
					echo "<td class='hostil'>{$factor[1]}</td>";
					for ($i = 0; $i < 5; $i++) {
						echo "<td><input type='checkbox'></td>";
					}
					echo "<td class='favorable' colspan='2'>{$factor[2]}</td>";
					echo "</tr>";
				}
				?>
				</tr>

				<tr>
					<th rowspan="2" class="subtitulo">Conclusion</th>
				</tr>

				<tr>
					<th colspan="6" style="text-align: center;">NADA</th>
					<th>Total</th>
				</tr>
			</table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
