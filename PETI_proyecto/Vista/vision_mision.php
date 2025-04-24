<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ingresar Visión y Diseño</title>
  <link rel="stylesheet" href="../public/css/vision_mision.css">
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
  <div class="main-layout">
    <div class="navLateral">  </div>
    <?php include 'sidebar.php'; ?>
    <div class="pageContent">
        <div class="content">
            <div class="container">
                <h2>Visión de la empresa</h2>
                <form action="../controlador/guardar_vision_diseno.php" method="post">
                <label for="vision">Escriba la Visión del proyecto:</label><br>
                <textarea name="vision" required></textarea><br><br>
                <button type="submit">Guardar Información</button>
                </form>
            </div>
            <div class="container">
                <h2>Misión de la empresa</h2>
                <form action="../controlador/guardar_vision_diseno.php" method="post">
                <label for="vision">Escriba la misión del proyecto:</label><br>
                <textarea name="vision" required></textarea><br><br>
                <button type="submit">Guardar Información</button>
            </form>
        </div>
        </div>
    </div>
  </div>
</body>
</html>
