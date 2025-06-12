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
  <title>Valores</title>
  <link rel="stylesheet" href="../public/css/valores.css">
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
        <script src="../public/js/valores.js"></script>

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

        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
            <div class="mdl-tabs__tab-bar">
                <a href="#tabListProducts" class="mdl-tabs__tab is-active">Presentación</a>
                <a href="#tabNewProduct" class="mdl-tabs__tab">Edición</a>
            </div>
            
            <!-- Panel de Presentación -->
            <div class="mdl-tabs__panel is-active" id="tabListProducts">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col">
                        <div class="full-width panel mdl-shadow--2dp">
                            <div class="full-width panel-tittle bg-primary text-center tittles">
                                Objetivos Estratégicos y Específicos
                            </div>
                            <div class="full-width panel-content">
                                <?php foreach ($datos as $item): ?>
                                    <div class="mdl-card mdl-shadow--2dp full-width" style="margin-bottom: 20px;">
                                        <div class="mdl-card__title" style="background-color: #f5f5f5;">
                                            <h2 class="mdl-card__title-text">
                                                <i class="zmdi zmdi-bookmark" style="margin-right: 10px;"></i>
                                                <?= htmlspecialchars($item['estrategico']['nombre_obj_estra']) ?>
                                            </h2>
                                        </div>
                                        <div class="mdl-card__supporting-text">
                                            <?php if (!empty($item['especificos'])): ?>
                                                <ul class="mdl-list" style="width: 100%;">
                                                    <?php foreach ($item['especificos'] as $especifico): ?>
                                                        <li class="mdl-list__item" style="padding: 10px 0;">
                                                            <span class="mdl-list__item-primary-content">
                                                                <i class="zmdi zmdi-check-circle mdl-list__item-icon" style="color: #4CAF50;"></i>
                                                                <?= htmlspecialchars($especifico['descripcion_espe']) ?>
                                                            </span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p style="color: #999; padding: 16px;">No hay objetivos específicos registrados</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Panel de Edición -->
            <div class="mdl-tabs__panel" id="tabNewProduct">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col">
                        <div class="full-width panel mdl-shadow--2dp">
                            <div class="full-width panel-tittle bg-primary text-center tittles">
                                Edición de Objetivos
                            </div>
							<br>
							<!-- Botón para agregar objetivo estratégico -->
<!-- Botón para agregar objetivo estratégico minimalista sin bordes redondeados -->
<button id="btnAgregar" class="btn-agregar" onclick="agregarObjetivo()">Agregar objetivo estratégico</button>

<style>
    .btn-agregar {
        background-color: #007bff; /* Color azul suave */
        color: white;
        padding: 10px 20px; /* Espaciado cómodo */
        font-size: 16px; /* Texto de tamaño medio */
        border: 2px solid #007bff; /* Borde del mismo color */
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s; /* Transición para cambio de color */
        outline: none; /* Elimina el borde azul por defecto cuando está seleccionado */
    }

    .btn-agregar:hover {
        background-color: #0056b3; /* Cambio de color al pasar el mouse */
        color: #e0e0e0; /* Color de texto más claro */
    }

    .btn-agregar:focus {
        outline: none; /* Elimina el borde azul cuando el botón está enfocado */
    }
</style>



							<br>
                            <div class="full-width panel-content">
                                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width">
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
                                                        <ul style="margin: 0; padding-left: 16px;">
                                                            <?php foreach ($item['especificos'] as $especifico): ?>
                                                                <li style="padding: 4px 0;">
                                                                    <?= htmlspecialchars($especifico['descripcion_espe']) ?>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php else: ?>
                                                        <span style="color: #999;">Sin objetivos específicos</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
												<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn-editar" 
														data-id="<?= $item['estrategico']['id_obj_estra'] ?>" 
														data-nombre="<?= htmlspecialchars($item['estrategico']['nombre_obj_estra']) ?>">
													<i class="zmdi zmdi-edit"></i>
												</button>
                                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn-eliminar" 
															data-id="<?= $item['estrategico']['id_obj_estra'] ?>">
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
        </div>
    </section>
    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar clic en botones de eliminar
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const idObjetivo = this.getAttribute('data-id');
            
            // Confirmar antes de eliminar
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción eliminará el objetivo estratégico y todos sus objetivos específicos!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarObjetivo(idObjetivo);
                }
            });
        });
    });
    
    // Función para enviar la solicitud de eliminación
    function eliminarObjetivo(id) {
        fetch('../Controllers/eliminarObjetivo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire(
                    '¡Eliminado!',
                    'El objetivo ha sido eliminado.',
                    'success'
                ).then(() => {
                    // Recargar la página para ver los cambios
                    location.reload();
                });
            } else {
                Swal.fire(
                    'Error',
                    'Ocurrió un error al eliminar: ' + data.message,
                    'error'
                );
            }
        })
        .catch(error => {
            Swal.fire(
                'Error',
                'Error de conexión: ' + error,
                'error'
            );
        });
    }
});
</script>


<!-- Agrega esto antes del cierre </body> -->
<div id="objetivoModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 600px; margin: 50px auto; background: white; padding: 20px; border-radius: 5px;">
        <span class="close-modal" style="float: right; font-size: 24px; cursor: pointer;">&times;</span>
        <h2 style="color: #333; margin-bottom: 20px;">Agregar Nuevo Objetivo Estratégico</h2>
        
        <form id="formObjetivo">
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="objetivoEstrategico" style="display: block; margin-bottom: 5px; font-weight: bold;">Objetivo Estratégico:</label>
                <input type="text" id="objetivoEstrategico" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Objetivos Específicos:</label>
                <div id="objetivosEspecificosContainer">
                    <div class="objetivo-especifico" style="display: flex; margin-bottom: 10px;">
                        <input type="text" name="objetivosEspecificos[]" required style="flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <button type="button" class="btn-eliminar-especifico" style="margin-left: 10px; background: #ff4444; color: white; border: none; border-radius: 4px; padding: 0 10px; cursor: pointer;">×</button>
                    </div>
                </div>
                <button type="button" id="btnAgregarEspecifico" style="background: #4CAF50; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; margin-top: 5px;">
                    <i class="zmdi zmdi-plus"></i> Agregar Objetivo Específico
                </button>
            </div>
            
            <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="btn-cancelar" style="background: #ccc; color: #333; border: none; padding: 10px 20px; border-radius: 4px; margin-right: 10px; cursor: pointer;">Cancelar</button>
                <button type="submit" style="background: #2196F3; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
    display: flex;
    align-items: flex-start;
    padding-top: 50px;
    overflow-y: auto;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    width: 90%;
    max-width: 600px;
}

.close-modal {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close-modal:hover {
    color: #333;
}

.btn-eliminar-especifico {
    background: #ff4444;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 0 10px;
    cursor: pointer;
    height: 38px;
}

.btn-eliminar-especifico:hover {
    background: #cc0000;
}
</style>

<script>
// Función para mostrar el modal
function agregarObjetivo() {
    document.getElementById('objetivoModal').style.display = 'flex';
    document.getElementById('objetivoEstrategico').focus();
}

// Cerrar modal
document.querySelector('.close-modal').addEventListener('click', function() {
    document.getElementById('objetivoModal').style.display = 'none';
});

document.querySelector('.btn-cancelar').addEventListener('click', function() {
    document.getElementById('objetivoModal').style.display = 'none';
});

// Cerrar al hacer clic fuera del modal
window.addEventListener('click', function(event) {
    if (event.target === document.getElementById('objetivoModal')) {
        document.getElementById('objetivoModal').style.display = 'none';
    }
});

// Agregar nuevo campo de objetivo específico
document.getElementById('btnAgregarEspecifico').addEventListener('click', function() {
    const container = document.getElementById('objetivosEspecificosContainer');
    const div = document.createElement('div');
    div.className = 'objetivo-especifico';
    div.style.display = 'flex';
    div.style.marginBottom = '10px';
    div.innerHTML = `
        <input type="text" name="objetivosEspecificos[]" required style="flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        <button type="button" class="btn-eliminar-especifico" style="margin-left: 10px; background: #ff4444; color: white; border: none; border-radius: 4px; padding: 0 10px; cursor: pointer;">×</button>
    `;
    container.appendChild(div);
    
    // Agregar evento al nuevo botón de eliminar
    div.querySelector('.btn-eliminar-especifico').addEventListener('click', function() {
        if (document.querySelectorAll('.objetivo-especifico').length > 1) {
            div.remove();
        } else {
            Swal.fire('Información', 'Debe haber al menos un objetivo específico', 'info');
        }
    });
});

// Manejar el envío del formulario
document.getElementById('formObjetivo').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const objetivoEstrategico = document.getElementById('objetivoEstrategico').value.trim();
    const objetivosEspecificos = Array.from(document.querySelectorAll('input[name="objetivosEspecificos[]"]'))
        .map(input => input.value.trim())
        .filter(val => val !== '');
    
    if (!objetivoEstrategico) {
        Swal.fire('Error', 'Debe ingresar un objetivo estratégico', 'error');
        return;
    }
    
    if (objetivosEspecificos.length === 0) {
        Swal.fire('Error', 'Debe ingresar al menos un objetivo específico', 'error');
        return;
    }
    
    // Mostrar loader
    Swal.fire({
        title: 'Guardando...',
        html: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Enviar datos al servidor
    fetch('../Controllers/agregarObjetivo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            objetivoEstrategico: objetivoEstrategico,
            objetivosEspecificos: objetivosEspecificos
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Objetivo agregado correctamente',
                icon: 'success'
            }).then(() => {
                document.getElementById('objetivoModal').style.display = 'none';
                location.reload(); // Recargar para ver los cambios
            });
        } else {
            Swal.fire('Error', data.message || 'Error al guardar el objetivo', 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error de conexión: ' + error, 'error');
    });
});
</script>

<!-- Modal para editar objetivo -->
<div id="editarObjetivoModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 600px; margin: 50px auto; background: white; padding: 20px; border-radius: 5px;">
        <span class="close-editar-modal" style="float: right; font-size: 24px; cursor: pointer;">&times;</span>
        <h2 style="color: #333; margin-bottom: 20px;">Editar Objetivo Estratégico</h2>
        
        <form id="formEditarObjetivo">
            <input type="hidden" id="editarIdObjetivo">
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editarObjetivoEstrategico" style="display: block; margin-bottom: 5px; font-weight: bold;">Objetivo Estratégico:</label>
                <input type="text" id="editarObjetivoEstrategico" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Objetivos Específicos:</label>
                <div id="editarObjetivosEspecificosContainer">
                    <!-- Los objetivos específicos se cargarán aquí dinámicamente -->
                </div>
                <button type="button" id="btnAgregarEspecificoEditar" style="background: #4CAF50; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; margin-top: 5px;">
                    <i class="zmdi zmdi-plus"></i> Agregar Objetivo Específico
                </button>
            </div>
            
            <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="btn-cancelar-editar" style="background: #ccc; color: #333; border: none; padding: 10px 20px; border-radius: 4px; margin-right: 10px; cursor: pointer;">Cancelar</button>
                <button type="submit" style="background: #2196F3; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
	// Función para abrir el modal de edición con los datos
function abrirEdicion(idObjetivo, nombreObjetivo, objetivosEspecificos) {
    const modal = document.getElementById('editarObjetivoModal');
    modal.style.display = 'flex';
    
    // Llenar los campos del formulario
    document.getElementById('editarIdObjetivo').value = idObjetivo;
    document.getElementById('editarObjetivoEstrategico').value = nombreObjetivo;
    
    // Limpiar y llenar los objetivos específicos
    const container = document.getElementById('editarObjetivosEspecificosContainer');
    container.innerHTML = '';
    
    objetivosEspecificos.forEach((especifico, index) => {
        const div = document.createElement('div');
        div.className = 'objetivo-especifico';
        div.style.display = 'flex';
        div.style.marginBottom = '10px';
        div.innerHTML = `
            <input type="text" name="editarObjetivosEspecificos[]" value="${especifico.descripcion_espe}" required style="flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <input type="hidden" name="editarIdsEspecificos[]" value="${especifico.id_obj_espe}">
            <button type="button" class="btn-eliminar-especifico" style="margin-left: 10px; background: #ff4444; color: white; border: none; border-radius: 4px; padding: 0 10px; cursor: pointer;">×</button>
        `;
        container.appendChild(div);
        
        // Agregar evento al botón de eliminar
        div.querySelector('.btn-eliminar-especifico').addEventListener('click', function() {
            if (document.querySelectorAll('#editarObjetivosEspecificosContainer .objetivo-especifico').length > 1) {
                div.remove();
            } else {
                Swal.fire('Información', 'Debe haber al menos un objetivo específico', 'info');
            }
        });
    });
    
    // Enfocar el primer campo
    document.getElementById('editarObjetivoEstrategico').focus();
}

// Agregar nuevo campo de objetivo específico en edición
document.getElementById('btnAgregarEspecificoEditar').addEventListener('click', function() {
    const container = document.getElementById('editarObjetivosEspecificosContainer');
    const div = document.createElement('div');
    div.className = 'objetivo-especifico';
    div.style.display = 'flex';
    div.style.marginBottom = '10px';
    div.innerHTML = `
        <input type="text" name="editarObjetivosEspecificos[]" required style="flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        <input type="hidden" name="editarIdsEspecificos[]" value="nuevo">
        <button type="button" class="btn-eliminar-especifico" style="margin-left: 10px; background: #ff4444; color: white; border: none; border-radius: 4px; padding: 0 10px; cursor: pointer;">×</button>
    `;
    container.appendChild(div);
    
    // Agregar evento al nuevo botón de eliminar
    div.querySelector('.btn-eliminar-especifico').addEventListener('click', function() {
        if (document.querySelectorAll('#editarObjetivosEspecificosContainer .objetivo-especifico').length > 1) {
            div.remove();
        } else {
            Swal.fire('Información', 'Debe haber al menos un objetivo específico', 'info');
        }
    });
});

// Manejar el envío del formulario de edición
document.getElementById('formEditarObjetivo').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const idObjetivo = document.getElementById('editarIdObjetivo').value;
    const objetivoEstrategico = document.getElementById('editarObjetivoEstrategico').value.trim();
    
    const objetivosEspecificos = Array.from(document.querySelectorAll('input[name="editarObjetivosEspecificos[]"]'))
        .map(input => input.value.trim())
        .filter(val => val !== '');
    
    const idsEspecificos = Array.from(document.querySelectorAll('input[name="editarIdsEspecificos[]"]'))
        .map(input => input.value);
    
    if (!objetivoEstrategico) {
        Swal.fire('Error', 'Debe ingresar un objetivo estratégico', 'error');
        return;
    }
    
    if (objetivosEspecificos.length === 0) {
        Swal.fire('Error', 'Debe ingresar al menos un objetivo específico', 'error');
        return;
    }
    
    // Mostrar loader
    Swal.fire({
        title: 'Guardando cambios...',
        html: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Preparar datos para enviar
    const datos = {
        idObjetivo: idObjetivo,
        nombreObjetivo: objetivoEstrategico,
        objetivosEspecificos: objetivosEspecificos.map((descripcion, index) => ({
            id: idsEspecificos[index],
            descripcion: descripcion
        }))
    };
    
    // Enviar datos al servidor
    fetch('../Controllers/editarObjetivo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Objetivo actualizado correctamente',
                icon: 'success'
            }).then(() => {
                document.getElementById('editarObjetivoModal').style.display = 'none';
                location.reload(); // Recargar para ver los cambios
            });
        } else {
            Swal.fire('Error', data.message || 'Error al actualizar el objetivo', 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error de conexión: ' + error, 'error');
    });
});

// Cerrar modal de edición
document.querySelector('.close-editar-modal').addEventListener('click', function() {
    document.getElementById('editarObjetivoModal').style.display = 'none';
});

document.querySelector('.btn-cancelar-editar').addEventListener('click', function() {
    document.getElementById('editarObjetivoModal').style.display = 'none';
});

// Asignar evento de clic a los botones de editar
document.addEventListener('DOMContentLoaded', function() {
    // ... (tu código existente para eliminar)
    
    // Manejar clic en botones de editar
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const idObjetivo = this.getAttribute('data-id');
            const nombreObjetivo = this.getAttribute('data-nombre');
            
            // Obtener objetivos específicos via AJAX
            fetch(`../Controllers/obtenerEspecificos.php?id=${idObjetivo}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        abrirEdicion(idObjetivo, nombreObjetivo, data.objetivosEspecificos);
                    } else {
                        Swal.fire('Error', data.message || 'Error al cargar los objetivos específicos', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error de conexión: ' + error, 'error');
                });
        });
    });
});
	</script>
</body>
</html>