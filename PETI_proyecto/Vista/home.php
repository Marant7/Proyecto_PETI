<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Vista/index.php");
    exit();
}
?>TYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
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
    <!-- Notifications area -->
    <section class="full-width container-notifications">
        <div class="full-width container-notifications-bg btn-Notification"></div>
        <section class="NotificationArea">
            <div class="full-width text-center NotificationArea-title tittles">Notifications <i class="zmdi zmdi-close btn-Notification"></i></div>
            <!-- Aquí van las notificaciones -->
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
                    <a href="../Controllers/logout.php" style="color: inherit; text-decoration: none;">
                        <i class="zmdi zmdi-power"></i>
                    </a>
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

    <!-- Aquí sigue el contenido de la página -->
    <?php include 'sidebar.php'; ?>
    <?php include 'pageContent.php'; ?>
</body>
</html>
