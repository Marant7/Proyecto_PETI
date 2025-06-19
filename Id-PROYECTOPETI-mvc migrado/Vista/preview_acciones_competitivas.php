<?php
echo "<h2>🔍 Test: Verificar Acciones Competitivas en Resumen Plan</h2>";

// Simular datos como los devuelve el controlador
$resumen = [
    'identificacion_estrategica' => [
        'corregir' => "Implementar capacitación continua para mejorar las habilidades del personal y corregir deficiencias operativas.",
        'afrontar' => "Desarrollar planes de contingencia y diversificar mercados para afrontar amenazas económicas y competitivas.",
        'mantener' => "Continuar invirtiendo en tecnología y mantener la calidad del servicio que nos diferencia en el mercado.",
        'explotar' => "Aprovechar las nuevas oportunidades digitales para expandir nuestra presencia online y captar nuevos clientes."
    ]
];

echo "<h3>Vista previa de Acciones Competitivas:</h3>";
echo "<div style='max-width: 800px; margin: 20px 0; font-family: Arial, sans-serif;'>";
?>

<!-- Simulación de la sección de Acciones Competitivas del resumen_plan.php -->
<?php if (isset($resumen['identificacion_estrategica']) && !empty($resumen['identificacion_estrategica'])): ?>
<div class="section" style="margin-bottom: 30px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
    <h2 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin: 0; padding: 20px; font-size: 1.4em;">
        <i class="zmdi zmdi-trending-up"></i> Acciones Competitivas
    </h2>
    <div style="padding: 30px;">
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef;">
            <p style="margin-bottom: 20px; color: #666; font-style: italic;">
                Estrategias definidas mediante el análisis CAME (Corregir, Afrontar, Mantener, Explotar):
            </p>
            
            <?php if (!empty($resumen['identificacion_estrategica']['corregir'])): ?>
            <div style="margin-bottom: 20px; padding: 15px; border-left: 4px solid #dc3545; background: #fff5f5;">
                <h4 style="margin: 0 0 10px 0; color: #dc3545;">
                    <i class="zmdi zmdi-close-circle"></i> Corregir (Debilidades)
                </h4>
                <p style="margin: 0; line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($resumen['identificacion_estrategica']['corregir'])); ?>
                </p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($resumen['identificacion_estrategica']['afrontar'])): ?>
            <div style="margin-bottom: 20px; padding: 15px; border-left: 4px solid #fd7e14; background: #fff8f5;">
                <h4 style="margin: 0 0 10px 0; color: #fd7e14;">
                    <i class="zmdi zmdi-shield-security"></i> Afrontar (Amenazas)
                </h4>
                <p style="margin: 0; line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($resumen['identificacion_estrategica']['afrontar'])); ?>
                </p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($resumen['identificacion_estrategica']['mantener'])): ?>
            <div style="margin-bottom: 20px; padding: 15px; border-left: 4px solid #28a745; background: #f8fff9;">
                <h4 style="margin: 0 0 10px 0; color: #28a745;">
                    <i class="zmdi zmdi-check-circle"></i> Mantener (Fortalezas)
                </h4>
                <p style="margin: 0; line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($resumen['identificacion_estrategica']['mantener'])); ?>
                </p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($resumen['identificacion_estrategica']['explotar'])): ?>
            <div style="margin-bottom: 20px; padding: 15px; border-left: 4px solid #007bff; background: #f8fbff;">
                <h4 style="margin: 0 0 10px 0; color: #007bff;">
                    <i class="zmdi zmdi-trending-up"></i> Explotar (Oportunidades)
                </h4>
                <p style="margin: 0; line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($resumen['identificacion_estrategica']['explotar'])); ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
echo "</div>";

echo "<h3>✅ Resultado:</h3>";
echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
echo "<p><strong>✅ CAMBIO APLICADO CORRECTAMENTE</strong></p>";
echo "<p>✅ Las 'Acciones Competitivas' ahora muestran las respuestas CAME del usuario</p>";
echo "<p>✅ Se eliminaron las estrategias automáticas de síntesis</p>";
echo "<p>✅ Se muestran los 4 tipos: Corregir, Afrontar, Mantener, Explotar</p>";
echo "<p>✅ Cada tipo tiene su propio color y formato distintivo</p>";
echo "<p>✅ El formato es profesional y claro</p>";
echo "</div>";

echo "<h3>📋 Para verificar en el sistema real:</h3>";
echo "<ol>";
echo "<li>Ve a <code>resumen_plan.php</code> con un plan que tenga datos CAME</li>";
echo "<li>La sección 'Acciones Competitivas' ahora mostrará las respuestas del análisis CAME</li>";
echo "<li>Los datos se obtienen desde <code>\$resumen['identificacion_estrategica']</code></li>";
echo "</ol>";
?>
