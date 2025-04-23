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
		<script src="/PETI_proyecto/sweetalert2.min.js"></script>
		<script src="/PETI_proyecto/js/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="/PETI_proyecto/js/main.js"></script>
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
				
			</div>
			<div class="mdl-tabs__panel" id="tabNewProduct">
    <div class="table-responsive">
        <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width table-hover">
            <thead>
                <tr>
                    <th class="text-center">Misión</th>
                    <th class="text-center">Objetivo Estratégico</th>
                    <th class="text-center">Objetivo Específico</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($objetivos_estrategicos)): ?>
                    <?php
                    $rowspan_mision = 0;
                    foreach ($objetivos_estrategicos as $obj) {
                        $rowspan_mision += max(1, count($obj['especificos']));
                    }
                    ?>
                    <?php $mision_impresa = false; ?>
                    <?php foreach ($objetivos_estrategicos as $obj): ?>
                        <?php $especificos = $obj['especificos']; ?>
                        <?php if (empty($especificos)): ?>
                            <tr>
                                <?php if (!$mision_impresa): ?>
                                    <td rowspan="<?= $rowspan_mision ?>" class="text-center"><?= htmlspecialchars($empresa['mision']) ?></td>
                                    <?php $mision_impresa = true; ?>
                                <?php endif; ?>
                                <td class="text-center"><?= htmlspecialchars($obj['descripcion']) ?></td>
                                <td class="text-center">-</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($especificos as $i => $esp): ?>
                                <tr>
                                    <?php if (!$mision_impresa): ?>
                                        <td rowspan="<?= $rowspan_mision ?>" class="text-center"><?= htmlspecialchars($empresa['mision']) ?></td>
                                        <?php $mision_impresa = true; ?>
                                    <?php endif; ?>
                                    <?php if ($i == 0): ?>
                                        <td rowspan="<?= count($especificos) ?>" class="text-center"><?= htmlspecialchars($obj['descripcion']) ?></td>
                                    <?php endif; ?>
                                    <td class="text-center"><?= htmlspecialchars($esp['descripcion']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No hay datos disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

			
		</div>
	</section>
</body>
</html>