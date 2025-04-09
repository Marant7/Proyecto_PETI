<!DOCTYPE html>
<html lang="es">
<head>
<<<<<<< HEAD
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="/PETI_proyecto/public/css/normalize.css">
    <link rel="stylesheet" href="/PETI_proyecto/public/css/sweetalert2.css">
    <link rel="stylesheet" href="/PETI_proyecto/public/css/material.min.css">
    <link rel="stylesheet" href="/PETI_proyecto/public/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="/PETI_proyecto/public/css/main.css">
    <script src="/PETI_proyecto/sweetalert2.min.js"></script>
</head>
<body class="cover" style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">
=======
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
		<script src="/PETI_proyecto/sweetalert2.min.js"></script>
		<script src="/PETI_proyecto/js/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="/PETI_proyecto/js/main.js"></script>
	</head>
	
	
	<body class="cover" style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">
>>>>>>> bb325e419ebc49949ddbbfc3174fb638af81016d
    <div class="container-login" style="max-width: 500px;">
        <p class="text-center" style="font-size: 100px;">
            <i class="zmdi zmdi-account-circle"></i>
        </p>
        <p class="text-center text-condensedLight" style="font-size: 24px;">Inicia Sesión con tu Cuenta</p>
<<<<<<< HEAD
        <form method="POST" action="LoginController.php">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
                <input class="mdl-textfield__input" type="text" name="usuario" id="userName" style="font-size: 18px;" required>
                <label class="mdl-textfield__label" for="userName">Usuario</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
                <input class="mdl-textfield__input" type="password" name="password" id="pass" style="font-size: 18px;" required>
                <label class="mdl-textfield__label" for="pass">Contraseña</label>
            </div>
            <button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect" style="color: #3F51B5; float:right; font-size: 16px;">
=======
        <form action="../Controller/AuthController.php" method="POST">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
                <input class="mdl-textfield__input" type="text" name="usuario" id="userName" required style="font-size: 18px;">
                <label class="mdl-textfield__label" for="userName">Usuario</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%; font-size: 18px;">
                <input class="mdl-textfield__input" type="password" name="clave" id="pass" required style="font-size: 18px;">
                <label class="mdl-textfield__label" for="pass">Contraseña</label>
            </div>
            <button type="submit" id="SingIn" class="mdl-button mdl-js-button mdl-js-ripple-effect" style="color: #3F51B5; float:right; font-size: 16px;">
>>>>>>> bb325e419ebc49949ddbbfc3174fb638af81016d
                Iniciar Sesión <i class="zmdi zmdi-mail-send"></i>
            </button>
        </form>
    </div>
</body>
</html>
