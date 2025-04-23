<?php
// Asegúrate de incluir el controlador correcto
include '../Controller/ValorController.php';

// Crear una instancia del controlador
$controlador = new ValorController();

// Obtener los valores
$valores = $controlador->getValores();

// Mostrar los valores
foreach ($valores as $valor) {
    echo "<p>Valor: " . htmlspecialchars($valor['valor']) . "</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="/PETI_proyecto/public/css/normalize.css">
    <link rel="stylesheet" href="/PETI_proyecto/public/css/sweetalert2.css">
    <link rel="stylesheet" href="/PETI_proyecto/public/css/material.min.css">
    <link rel="stylesheet" href="/PETI_proyecto/public/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/PETI_proyecto/public/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="/PETI_proyecto/public/css/main.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/PETI_proyecto/public/js/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="/PETI_proyecto/public/js/material.min.js"></script>
    <script src="/PETI_proyecto/public/sweetalert2.min.js"></script>
    <script src="/PETI_proyecto/public/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/PETI_proyecto/public/js/main.js"></script>
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
                    <p><i class="zmdi zmdi-circle"></i><strong>Edición de Visión</strong><br><small>Just Now</small></p>
                </div>
                <div class="mdl-tooltip mdl-tooltip--left" for="notifation-unread-1">Notification as UnRead</div>
            </a>
            <!-- More notifications can be added here -->
        </section>
    </section>

    <!-- navBar -->
    <div class="full-width navBar">
        <div class="full-width navBar-options">
            <i class="zmdi zmdi-more-vert btn-menu" id="btn-menu"></i>
            <div class="mdl-tooltip" for="btn-menu">Menu</div>
            <nav class="navBar-options-list">
                <ul class="list-unstyle">
                    <li class="btn-Notification" id="notifications"><i class="zmdi zmdi-notifications"></i></li>
                    <li class="btn-exit" id="btn-exit"><i class="zmdi zmdi-power"></i></li>
                    <li class="text-condensedLight noLink"><small>User Name</small></li>
                    <li class="noLink">
                        <figure>
                            <img src="assets/img/avatar-male.png" alt="Avatar" class="img-responsive">
                        </figure>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <?php include '../Vista/sidebar.php'; ?>

    <!-- pageContent -->
    <section class="full-width pageContent">
        <section class="full-width header-well">
            <div class="full-width header-well-icon">
                <i class="zmdi zmdi-washing-machine"></i>
            </div>
            <div class="full-width header-well-text">
                <p class="text-condensedLight">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde aut nulla accusantium minus corporis accusamus fuga harum natus molestias necessitatibus.
                </p>
            </div>
        </section>

        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
            <div class="mdl-tabs__tab-bar">
                <a href="#tabListProducts" class="mdl-tabs__tab is-active">Presentación</a>
                <a href="#tabNewProduct" class="mdl-tabs__tab">Edición</a>
            </div>
            <div class="mdl-tabs__panel is-active" id="tabListProducts">
                <!-- Aquí se pueden listar los valores desde la base de datos -->
                <?php
                // Incluye el controlador que maneja la visualización de los valores
                $controlador = new ValorController();
                $valores = $controlador->getValores(); // Obtener los valores desde la base de datos
                foreach ($valores as $valor) {
                    echo "<p>Valor: " . htmlspecialchars($valor['valor']) . "</p>";
                }
                ?>
            </div>

            <div class="mdl-tabs__panel" id="tabNewProduct">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
                        <div class="full-width panel mdl-shadow--2dp">
                            <div class="full-width panel-tittle bg-primary text-center tittles">
                                Edición de los Valores
                            </div>
                            <div class="full-width panel-content">
                                <form action="../Controller/ValorController.php" method="POST">
                                    <div class="mdl-grid">
                                        <div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--6-col-desktop">
                                            <h5 class="text-condensedLight">Valores</h5>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="number" name="valor" id="valor" required>
                                                <label class="mdl-textfield__label" for="valor">Escriba aquí</label>
                                                <span class="mdl-textfield__error">Invalid</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center">
                                        <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored bg-primary" type="submit" name="guardarValor" id="btn-addProduct">
                                            <i class="zmdi zmdi-plus"></i>
                                        </button>
                                        <div class="mdl-tooltip" for="btn-addProduct">Guardar Valores</div>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</body>
</html>
