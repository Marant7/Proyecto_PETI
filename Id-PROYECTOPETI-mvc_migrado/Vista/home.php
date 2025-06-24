<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user = $_SESSION['user'];
$usuario_id = $user['id_usuario'] ?? $user['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresas</title>
    <link rel="stylesheet" href="../public/css/main.css">
    <link rel="stylesheet" href="../public/css/material-design-iconic-font.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f4f6fb, #e8ebf2);
            font-family: 'Roboto', Arial, sans-serif;
            color: #333;
        }
        .centered-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            padding: 40px;
            text-align: center;
        }
        .btn-main {
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            background: #007bff;
            color: #fff;
            font-size: 1.1em;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            margin-top: 20px;
        }
        .btn-main:hover {
            background: #0056b3;
            transform: scale(1.05);
        }
        .empresas-list {
            margin: 30px 0;
            text-align: left;
        }
        .empresa-card {
            background: #f9f9fb;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .empresa-nombre {
            font-size: 1.4em;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }
        .empresa-desc {
            color: #555;
            margin-bottom: 8px;
        }
        .empresa-fecha {
            font-size: 0.9em;
            color: #888;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background: #28a745;
            color: #fff;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        .btn:hover {
            background: #218838;
            transform: scale(1.05);
        }
        .btn-cancel {
            background: #dc3545;
            margin-top: 10px;
        }
        .btn-cancel:hover {
            background: #b52a37;
        }
        .msg {
            text-align: center;
            margin-top: 18px;
            font-size: 1em;
        }
        #modalEmpresa {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        #modalEmpresa .modal-content {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        #modalEmpresa h2 {
            margin-top: 0;
            font-size: 1.5em;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1em;
        }
        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.5em;
            color: #888;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="centered-container">
        <h2>Empresas</h2>
        <p style="color:#555; margin-bottom:18px;">Bienvenido, <strong><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellido']); ?></strong></p>
        <button class="btn-main" onclick="mostrarModalEmpresa()">
            <i class="zmdi zmdi-city"></i> Crear Empresa
        </button>
        <div class="msg" id="msg"></div>
        <div class="empresas-list" id="empresasList"></div>
    </div>
    <!-- Modal para crear empresa -->
    <div id="modalEmpresa">
        <div class="modal-content">
            <button class="close-modal" onclick="cerrarModalEmpresa()">&times;</button>
            <h2>Crear Empresa</h2>
            <form id="formEmpresa">
                <div class="form-group">
                    <label>Nombre de la empresa</label>
                    <input type="text" name="nombre_empresa" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion_empresa" required></textarea>
                </div>
                <button type="submit" class="btn">Guardar Empresa</button>
                <button type="button" class="btn btn-cancel" onclick="cerrarModalEmpresa()">Cancelar</button>
            </form>
        </div>
    </div>
    <script>
    function mostrarModalEmpresa() {
        document.getElementById('modalEmpresa').style.display = 'flex';
    }
    function cerrarModalEmpresa() {
        document.getElementById('modalEmpresa').style.display = 'none';
    }
    function cargarEmpresas() {
        fetch('../Controllers/EmpresaController.php?action=listar')
        .then(res => res.json())
        .then(empresas => {
            const cont = document.getElementById('empresasList');
            if (!empresas.length) {
                cont.innerHTML = '<p style="color:#888; text-align:center;">No tienes empresas registradas.</p>';
                return;
            }
            cont.innerHTML = empresas.map(e => {
                let planesHtml = '';
                if (e.planes && e.planes.length) {                    planesHtml = `<div style='margin-top:10px;'>Planes:<ul style='margin:8px 0 0 18px; padding:0;'>` +
                        e.planes.map(p =>                            `<li style='margin-bottom:4px;'>
                                <span style='color:#007bff;'>Plan del ${p.fecha_creacion ? p.fecha_creacion.substring(0,10) : 'Sin fecha'}</span>
                                <a href="../Controllers/PlanController.php?action=editarVisionMision&id_plan=${p.id}" class="btn btn-warning btn-sm" style="margin-left:10px; padding:4px 12px; font-size:0.95em;">
                                    <i class="zmdi zmdi-edit"></i> Editar
                                </a>
                                <a href="../Controllers/PlanController.php?action=verResumenEjecutivo&id_plan=${p.id}" class="btn btn-info btn-sm" style="margin-left:5px; padding:4px 12px; font-size:0.95em;">
                                    <i class="zmdi zmdi-assignment"></i> Ver Resumen
                                </a>
                            </li>`
                        ).join('') + '</ul></div>';
                }
                return `
                <div class="empresa-card" style="display:flex; align-items:flex-start; justify-content:space-between; gap:10px;">
                    <div style="flex:1;">
                        <div class="empresa-nombre">${e.nombre}</div>
                        <div class="empresa-desc">${e.descripcion}</div>
                        <div class="empresa-fecha">Creada: ${e.fecha_creacion ? e.fecha_creacion.substring(0,10) : ''}</div>
                        ${planesHtml}
                    </div>
                    <button class="btn btn-info" style="min-width:150px; margin-left:10px; height:40px; align-self:center;" onclick="crearPlan(${e.id})">
                        <i class='zmdi zmdi-assignment'></i> Crear nuevo plan
                    </button>
                </div>
                `;
            }).join('');
        });
    }
    document.getElementById('formEmpresa').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        fetch('../Controllers/EmpresaController.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then (data => {
            const msg = document.getElementById('msg');
            if (data.success) {
                msg.textContent = '¡Empresa creada exitosamente!';
                msg.style.color = 'green';
                form.reset();
                cerrarModalEmpresa();
                cargarEmpresas();
            } else {
                msg.textContent = data.message || 'Error al crear la empresa';
                msg.style.color = 'red';
            }
        })
        .catch(() => {
            const msg = document.getElementById('msg');
            msg.textContent = 'Error de conexión';
            msg.style.color = 'red';
        });
    });
    document.addEventListener('DOMContentLoaded', cargarEmpresas);

    function crearPlan(empresa_id) {
        if (!confirm('¿Crear un nuevo plan para esta empresa?')) return;
        fetch('../Controllers/PlanController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'empresa_id=' + encodeURIComponent(empresa_id)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Redirigir a vision_mision.php con el id del plan
                window.location.href = 'vision_mision.php?id_plan=' + data.plan_id;
            } else {
                alert(data.message || 'Error al crear el plan');
            }
        })
        .catch(() => {
            alert('Error de conexión');
        });
    }
    </script>
</body>
</html>