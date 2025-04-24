<!DOCTYPE html>
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
		<script src="../sweetalert2.min.js"></script>
		<script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="../js/main.js"></script>
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
				<a href="#tabListProducts" class="mdl-tabs__tab is-active">Presentacion</a>
                
				<a href="#tabNewProduct" class="mdl-tabs__tab">Edicion</a>
			</div>
			<div class="mdl-tabs__panel is-active" id="tabListProducts">
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

    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <div class="full-width panel mdl-shadow--2dp">
                <div class="full-width panel-tittle bg-primary text-center tittles">
                    Objetivos Estratégicos y Específicos
                </div>
                <div class="full-width panel-content">
                    <div class="mdl-list">
                        <?php foreach ($datos as $item): ?>
                            <div class="mdl-list__item mdl-list__item--two-line" style="border-bottom: 1px solid rgba(0,0,0,0.12);">
                                <span class="mdl-list__item-primary-content">
                                    <i class="zmdi zmdi-bookmark mdl-list__item-avatar"></i>
                                    <span style="font-weight: 500;"><?= htmlspecialchars($item['estrategico']['nombre_obj_estra']) ?></span>
                                    <span class="mdl-list__item-sub-title">
                                        <?php if (!empty($item['especificos'])): ?>
                                            <?php foreach ($item['especificos'] as $especifico): ?>
                                                <div style="padding: 8px 0 8px 16px; border-left: 2px solid #2196F3; margin: 4px 0;">
                                                    <?= htmlspecialchars($especifico['descripcion_espe']) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <em style="color: #999;">No hay objetivos específicos registrados</em>
                                        <?php endif; ?>
                                    </span>
                                </span>
                                <span class="mdl-list__item-secondary-content">
                                    <a class="mdl-list__item-secondary-action" href="#"><i class="zmdi zmdi-edit"></i></a>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Alternativa con tabla -->
                    <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width" style="margin-top: 20px;">
                        <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Objetivo Estratégico</th>
                                <th class="mdl-data-table__cell--non-numeric">Objetivos Específicos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos as $item): ?>
                                <tr>
                                    <td class="mdl-data-table__cell--non-numeric" style="font-weight: 500;">
                                        <?= htmlspecialchars($item['estrategico']['nombre_obj_estra']) ?>
                                    </td>
                                    <td class="mdl-data-table__cell--non-numeric">
                                        <?php if (!empty($item['especificos'])): ?>
                                            <ul class="mdl-list" style="padding: 0; margin: 0;">
                                                <?php foreach ($item['especificos'] as $especifico): ?>
                                                    <li style="padding: 8px 0; border-bottom: 1px solid rgba(0,0,0,0.05);">
                                                        <?= htmlspecialchars($especifico['descripcion_espe']) ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <span style="color: #999;">Sin objetivos específicos</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="zmdi zmdi-edit"></i>
                                        </button>
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="zmdi zmdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
			<div class="mdl-tabs__panel" id="tabNewProduct">
    


			
		</div>
	</section>
</body>
</html>