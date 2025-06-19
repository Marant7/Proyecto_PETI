<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    echo "No hay usuario en sesión";
    exit();
}

$perfil_competitivo_data = [
    "titulo_general" => "PERFIL COMPETITIVO",
    "columnas_escala" => ["Nada", "Poco", "Medio", "Alto", "Muy Alto"],
    "secciones" => [
        [
            "titulo_seccion" => "Rivalidad empresas del sector",
            "factores" => [
                ["nombre" => "- Crecimiento", "hostil" => "Lento", "favorable" => "Rápido"],
                ["nombre" => "- Naturaleza de los competidores", "hostil" => "Muchos", "favorable" => "Pocos"],
                ["nombre" => "- Exceso de capacidad productiva", "hostil" => "Sí", "favorable" => "No"],
                ["nombre" => "- Rentabilidad media del sector", "hostil" => "Baja", "favorable" => "Alta"],
                ["nombre" => "- Diferenciación del producto", "hostil" => "Escasa", "favorable" => "Elevada"],
                ["nombre" => "- Barreras de salida", "hostil" => "Bajas", "favorable" => "Altas"],
            ]
        ],
        [
            "titulo_seccion" => "Barreras de Entrada",
            "factores" => [
                ["nombre" => "- Economías de escala", "hostil" => "No", "favorable" => "Sí"],
                ["nombre" => "- Necesidad de capital", "hostil" => "Bajas", "favorable" => "Altas"],
                ["nombre" => "- Acceso a la tecnología", "hostil" => "Fácil", "favorable" => "Difícil"],
                ["nombre" => "- Reglamentos o leyes limitativas", "hostil" => "No", "favorable" => "Sí"],
                ["nombre" => "- Trámites burocráticos", "hostil" => "No", "favorable" => "Sí"],
                ["nombre" => "- Reacción esperada actuales competidores", "hostil" => "Escasa", "favorable" => "Enérgica"],
            ]
        ],
        [
            "titulo_seccion" => "Poder de los Clientes",
            "factores" => [
                ["nombre" => "- Número de clientes", "hostil" => "Pocos", "favorable" => "Muchos"],
                ["nombre" => "- Posibilidad de integración ascendente", "hostil" => "Pequeña", "favorable" => "Grande"],
                ["nombre" => "- Rentabilidad de los clientes", "hostil" => "Baja", "favorable" => "Alta"],
                ["nombre" => "- Coste de cambio de proveedor para cliente", "hostil" => "Bajo", "favorable" => "Alto"],
            ]
        ],
        [
            "titulo_seccion" => "Productos sustitutivos",
            "factores" => [
                ["nombre" => "- Disponibilidad de Productos Sustitutivos", "hostil" => "Grande", "favorable" => "Pequeña"],
            ]
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuerzas Porter</title>
    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/plan-estrategico.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 100%; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        h2 { color: #1976d2; text-align: center; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background: #f0f0f0; font-weight: bold; }
        .section-header { background: #e8f5e9; color: #388e3c; font-weight: bold; text-align: left; }
        .factor-cell { text-align: left; background: #fafafa; }
        .hostil { color: #d32f2f; font-weight: bold; }
        .favorable { color: #1976d2; font-weight: bold; }
        input[type="radio"] { width: 16px; height: 16px; }        .conclusion { background: #f5f5f5; padding: 15px; margin: 20px 0; border-left: 5px solid #1976d2; }
        button { background: #2196F3; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; display: block; margin: 0 auto; }
        button:hover { background: #1976d2; }
        button:active { transform: translateY(1px); }
        .btn-primary { background: #4CAF50 !important; }
        
        form {
            max-width: 900px;
            margin: 0 auto;
        }
        .btn-primary:hover { background: #45a049 !important; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .oportunidades-amenazas { margin-top: 40px; }
        .oportunidades-amenazas h3 { color: #1976d2; margin-bottom: 15px; }
        .oportunidades-amenazas label { font-weight: bold; margin-bottom: 5px; display: block; }
        .oportunidades-amenazas textarea { width: 100%; min-height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px; }
        .btn-siguiente { background: #4CAF50; color: white; padding: 12px 30px; border: none; border-radius: 4px; font-size: 16px; text-decoration: none; display: inline-block; margin: 20px 0; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Análisis de Fuerzas Porter</h2>        <form method="POST" action="../index.php?controller=PlanEstrategico&action=guardarPaso" id="formFuerzasPorter">
            <input type="hidden" name="paso" value="7">
            <input type="hidden" name="nombre_paso" value="fuerzas_porter">
            <table>
                <thead>
                    <tr>
                        <th>PERFIL COMPETITIVO</th>
                        <th>Nada</th>
                        <th>Poco</th>
                        <th>Medio</th>
                        <th>Alto</th>
                        <th>Muy Alto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $factor_index = 0;
                    foreach ($perfil_competitivo_data['secciones'] as $seccion):
                    ?>
                        <tr>
                            <td colspan="7" class="section-header"><?php echo $seccion['titulo_seccion']; ?></td>
                        </tr>
                        <?php foreach ($seccion['factores'] as $factor): ?>
                            <tr>
                                <td class="factor-cell">
                                    <?php echo $factor['nombre']; ?><br>
                                    <small>(<span class="hostil">Hostil: <?php echo $factor['hostil']; ?></span> / <span class="favorable">Favorable: <?php echo $factor['favorable']; ?></span>)</small>
                                </td>
                                <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="1" class="radio-factor"></td>
                                <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="2" class="radio-factor"></td>
                                <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="3" class="radio-factor"></td>
                                <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="4" class="radio-factor"></td>
                                <td><input type="radio" name="factor_<?php echo $factor_index; ?>" value="5" class="radio-factor"></td>
                                <td id="estado_<?php echo $factor_index; ?>">-</td>
                            </tr>
                        <?php $factor_index++; endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="conclusion">
                <strong>CONCLUSIÓN:</strong> <span id="conclusion-text">Seleccione todas las opciones para ver la conclusión.</span><br>
                <strong>Total:</strong> <span id="total-score">0</span>
            </div>

            <div class="oportunidades-amenazas">
                <h3>Oportunidades y Amenazas</h3>
                <div id="oportunidades-container">
                    <label for="oportunidades">Oportunidades:</label>
                    <textarea name="oportunidades[]" placeholder="Ingrese las oportunidades identificadas..." style="width: 100%; min-height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;"></textarea>
                </div>
                <button type="button" onclick="agregarOportunidad()" style="background: #1976d2; color: white; padding: 8px 20px; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; margin-bottom: 15px;">Agregar Oportunidad</button>
                
                <div id="amenazas-container">
                    <label for="amenazas">Amenazas:</label>
                    <textarea name="amenazas[]" placeholder="Ingrese las amenazas identificadas..." style="width: 100%; min-height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;"></textarea>
                </div>
                <button type="button" onclick="agregarAmenaza()" style="background: #1976d2; color: white; padding: 8px 20px; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; margin-bottom: 15px;">Agregar Amenaza</button>
                
                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" style="background: #4CAF50; color: white; padding: 15px 30px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: bold; box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.3s ease;">
                        � GUARDAR Y CONTINUAR AL SIGUIENTE PASO
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.radio-factor');
            
            function updateScore() {
                let total = 0;
                let completed = 0;
                
                for(let i = 0; i < <?php echo $factor_index; ?>; i++) {
                    const selectedRadio = document.querySelector(`input[name="factor_${i}"]:checked`);
                    const estadoCell = document.getElementById(`estado_${i}`);
                    
                    if(selectedRadio) {
                        const value = parseInt(selectedRadio.value);
                        total += value;
                        completed++;
                        estadoCell.innerHTML = '<span style="color: #43a047; font-weight: bold;">✓</span>';
                    } else {
                        estadoCell.innerHTML = '-';
                    }
                }
                
                document.getElementById('total-score').textContent = total;
                
                let conclusion = '';
                if(total < 30) {
                    conclusion = 'Estamos en un mercado altamente competitivo, en el que es muy difícil hacerse un hueco en el mercado.';
                } else if(total < 45) {
                    conclusion = 'Estamos en un mercado de competitividad relativamente alta, pero con ciertas modificaciones en el producto y la política comercial de la empresa, podría encontrarse un nicho de mercado.';
                } else if(total < 60) {
                    conclusion = 'La situación actual del mercado es favorable a la empresa.';
                } else {
                    conclusion = 'Estamos en una situación excelente para la empresa.';
                }
                
                document.getElementById('conclusion-text').textContent = conclusion;
            }
            
            radios.forEach(radio => {
                radio.addEventListener('change', updateScore);
            });
        });

        function agregarOportunidad() {
            const container = document.getElementById('oportunidades-container');
            const textarea = document.createElement('textarea');
            textarea.name = 'oportunidades[]';
            textarea.placeholder = 'Ingrese otra oportunidad...';
            textarea.style = 'width: 100%; min-height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;';
            container.appendChild(textarea);
        }

        function agregarAmenaza() {
            const container = document.getElementById('amenazas-container');
            const textarea = document.createElement('textarea');
            textarea.name = 'amenazas[]';
            textarea.placeholder = 'Ingrese otra amenaza...';
            textarea.style = 'width: 100%; min-height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;';            container.appendChild(textarea);
        }        // Manejar envío del formulario con AJAX
        document.getElementById('formFuerzasPorter').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('../index.php?controller=PlanEstrategico&action=guardarPaso', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'pest_nuevo.php';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar los datos');
            });
        });
    </script>
</body>
</html>