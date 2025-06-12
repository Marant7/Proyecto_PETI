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
          <form id="formVisionMision" action="../Controllers/visiMisiController.php" method="post">
            <h2>Misión de la empresa</h2>
            <label for="mision">Escriba la misión del proyecto:</label><br>
            <textarea name="mision" id="mision" required></textarea><br><br>

            <h2>Visión de la empresa</h2>
            <label for="vision">Escriba la Visión del proyecto:</label><br>
            <textarea name="vision" id="vision" required></textarea><br><br>

            <button type="submit">Guardar Información</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
