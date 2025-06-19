<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba Simple - Resumen Ejecutivo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin: 15px 0; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .debug { background: #f0f0f0; padding: 10px; border: 1px solid #ccc; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🧪 Prueba Simple - Resumen Ejecutivo</h1>
    
    <div class="debug">
        <h3>📋 Información de Debug:</h3>
        <p><strong>Acción del formulario:</strong> test_resumen.php</p>
        <p><strong>Método:</strong> POST</p>
    </div>
    
    <form method="POST" action="test_resumen.php" id="testForm">
        <input type="hidden" name="paso" value="11">
        <input type="hidden" name="nombre_paso" value="resumen_ejecutivo">
        
        <div class="form-group">
            <label for="emprendedores">Emprendedores/Promotores:</label>
            <textarea name="emprendedores_promotores" id="emprendedores" rows="3" required>Juan Pérez - Director TI, María González - Analista de Sistemas</textarea>
        </div>
        
        <div class="form-group">
            <label for="identificacion">Identificación Estratégica:</label>
            <textarea name="identificacion_estrategica" id="identificacion" rows="4" required>La organización se posiciona como líder en innovación tecnológica, enfocándose en la transformación digital y la excelencia operativa.</textarea>
        </div>
        
        <div class="form-group">
            <label for="conclusiones">Conclusiones:</label>
            <textarea name="conclusiones" id="conclusiones" rows="5" required>El plan estratégico de TI establecido permitirá a la organización alcanzar sus objetivos de modernización tecnológica, mejorando la eficiencia operativa y la competitividad en el mercado.</textarea>
        </div>
        
        <button type="submit">🧪 Probar Envío de Datos</button>
    </form>
    
    <div class="debug">
        <h3>🔧 Siguiente Paso:</h3>
        <p>1. Complete los campos y haga clic en "Probar Envío de Datos"</p>
        <p>2. Verifique que los datos se reciban correctamente</p>
        <p>3. Si funciona, el problema está en el formulario principal</p>
    </div>
    
    <script>
        // Debug adicional
        document.getElementById('testForm').addEventListener('submit', function(e) {
            console.log('Formulario enviado');
            console.log('Datos del formulario:', new FormData(this));
        });
    </script>
</body>
</html>
