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
        <link rel="stylesheet" href="../public/css/cadena_valor.css">
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
  <!-- navBar -->
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
                    <li class="text-condensedLight noLink">
                        <small><?php echo $_SESSION['usuario']; ?></small>
                    </li>
                    <li class="noLink">
                        <figure>
                            <img src="../public/assets/img/avatar-male.png" alt="Avatar" class="img-responsive">
                        </figure>
                    </li>
					</li>
				</ul>
			</nav>
		</div>
	</div>
    <div class="main-layout">
    <div class="navLateral"></div>
    <?php include 'sidebar.php'; ?>




    <div class="pageContent">
      <div class="content">
        <div class="container">
          <h2>Cadena de Valor</h2>
          <h8>A continuación marque con una X para valorar su empresa en función de cada una de las afirmaciones, 
            de tal forma que 0= En total en desacuerdo; 1= No está de acuerdo; 2=Está de acuerdo; 3= Está bastante de acuerdo; 
            4=En total acuerdo. En caso de no cumplimentar una casilla o duplicar su respuesta le aparecerá el mensaje de error ("¡REF!)</h8>
            <br>

            <form id="evaluationForm">
                <table>
                    <thead>
                        <tr class="header-row">
                            <th class="statement">AUTODIAGNÓSTICO DE LA CADENA DE VALOR INTERNA</th>
                            <th colspan="5">VALORACIÓN</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>0</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="statement">1. La empresa tiene una política sistematizada de cero defectos en la producción de productos/servicios.</td>
                            <td><input type="radio" name="q1" value="0" required></td>
                            <td><input type="radio" name="q1" value="1"></td>
                            <td><input type="radio" name="q1" value="2"></td>
                            <td><input type="radio" name="q1" value="3"></td>
                            <td><input type="radio" name="q1" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">2. La empresa emplea los medios productivos tecnológicamente más avanzados de su sector.</td>
                            <td><input type="radio" name="q2" value="0" required></td>
                            <td><input type="radio" name="q2" value="1"></td>
                            <td><input type="radio" name="q2" value="2"></td>
                            <td><input type="radio" name="q2" value="3"></td>
                            <td><input type="radio" name="q2" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">3. La empresa dispone de un sistema de información y control de gestión  eficiente y eficaz. </td>
                            <td><input type="radio" name="q3" value="0" required></td>
                            <td><input type="radio" name="q3" value="1"></td>
                            <td><input type="radio" name="q3" value="2"></td>
                            <td><input type="radio" name="q3" value="3"></td>
                            <td><input type="radio" name="q3" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">4.Los medios técnicos y técnológicos de la empresa están preparados para competir en un futuro a corto, medio y largo plazo.</td>
                            <td><input type="radio" name="q4" value="0" required></td>
                            <td><input type="radio" name="q4" value="1"></td>
                            <td><input type="radio" name="q4" value="2"></td>
                            <td><input type="radio" name="q4" value="3"></td>
                            <td><input type="radio" name="q4" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">5. La empresa es un referente en su sector en I+D+i.</td>
                            <td><input type="radio" name="q5" value="0" required></td>
                            <td><input type="radio" name="q5" value="1"></td>
                            <td><input type="radio" name="q5" value="2"></td>
                            <td><input type="radio" name="q5" value="3"></td>
                            <td><input type="radio" name="q5" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">6. La excelencia de los procedimientos de la empresa (en ISO, etc.) son una principal fuente de ventaja competiva.</td>
                            <td><input type="radio" name="q6" value="0" required></td>
                            <td><input type="radio" name="q6" value="1"></td>
                            <td><input type="radio" name="q6" value="2"></td>
                            <td><input type="radio" name="q6" value="3"></td>
                            <td><input type="radio" name="q6" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">7. La empresa dispone de página web, y esta se emplea no sólo como escaparate virtual de productos/servicios, sino también para establecer relaciones con clientes y proveedores.</td>
                            <td><input type="radio" name="q7" value="0" required></td>
                            <td><input type="radio" name="q7" value="1"></td>
                            <td><input type="radio" name="q7" value="2"></td>
                            <td><input type="radio" name="q7" value="3"></td>
                            <td><input type="radio" name="q7" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">8. Los productos/servicios que desarrolla nuestra empresa llevan incorporada una tecnología difícil de imitar.</td>
                            <td><input type="radio" name="q8" value="0" required></td>
                            <td><input type="radio" name="q8" value="1"></td>
                            <td><input type="radio" name="q8" value="2"></td>
                            <td><input type="radio" name="q8" value="3"></td>
                            <td><input type="radio" name="q8" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">9.  La empresa es referente en su sector en la optimización, en términos de coste,  de su cadena de producción, siendo ésta una de sus principales ventajas competitivas.</td>
                            <td><input type="radio" name="q9" value="0" required></td>
                            <td><input type="radio" name="q9" value="1"></td>
                            <td><input type="radio" name="q9" value="2"></td>
                            <td><input type="radio" name="q9" value="3"></td>
                            <td><input type="radio" name="q9" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">10.  La informatización de la empresa es una fuente de ventaja competitiva clara respecto a sus competidores.</td>
                            <td><input type="radio" name="q10" value="0" required></td>
                            <td><input type="radio" name="q10" value="1"></td>
                            <td><input type="radio" name="q10" value="2"></td>
                            <td><input type="radio" name="q10" value="3"></td>
                            <td><input type="radio" name="q10" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">11.  Los canales de distribución de la empresa son una importante fuente de ventajas competitivas.</td>
                            <td><input type="radio" name="q11" value="0" required></td>
                            <td><input type="radio" name="q11" value="1"></td>
                            <td><input type="radio" name="q11" value="2"></td>
                            <td><input type="radio" name="q11" value="3"></td>
                            <td><input type="radio" name="q11" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">12. Los productos/servicios de la empresa son altamente, y diferencialmente, valorados por el cliente respecto a nuestros competidores.</td>
                            <td><input type="radio" name="q12" value="0" required></td>
                            <td><input type="radio" name="q12" value="1"></td>
                            <td><input type="radio" name="q12" value="2"></td>
                            <td><input type="radio" name="q12" value="3"></td>
                            <td><input type="radio" name="q12" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">13. La empresa dispone y ejecuta un sistematico plan de marketing y ventas.</td>
                            <td><input type="radio" name="q13" value="0" required></td>
                            <td><input type="radio" name="q13" value="1"></td>
                            <td><input type="radio" name="q13" value="2"></td>
                            <td><input type="radio" name="q13" value="3"></td>
                            <td><input type="radio" name="q13" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">14. La empresa tiene optimizada su gestión financiera.</td>
                            <td><input type="radio" name="q14" value="0" required></td>
                            <td><input type="radio" name="q14" value="1"></td>
                            <td><input type="radio" name="q14" value="2"></td>
                            <td><input type="radio" name="q14" value="3"></td>
                            <td><input type="radio" name="q14" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">15. La empresa busca continuamente el mejorar la relación con sus clientes cortando los plazos de ejecución, personalizando la oferta o mejorando las condiciones de entrega. Pero siempre partiendo de un plan previo.</td>
                            <td><input type="radio" name="q15" value="0" required></td>
                            <td><input type="radio" name="q15" value="1"></td>
                            <td><input type="radio" name="q15" value="2"></td>
                            <td><input type="radio" name="q15" value="3"></td>
                            <td><input type="radio" name="q15" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">16. La empresa es referente en su sector en el lanzamiento de innovadores productos y servicio de éxito demostrado en el mercado.</td>
                            <td><input type="radio" name="q16" value="0" required></td>
                            <td><input type="radio" name="q16" value="1"></td>
                            <td><input type="radio" name="q16" value="2"></td>
                            <td><input type="radio" name="q16" value="3"></td>
                            <td><input type="radio" name="q16" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">17.  Los Recursos Humanos son especialmente responsables del éxito de la empresa, considerándolos incluso como el principal activo estratégico.</td>
                            <td><input type="radio" name="q17" value="0" required></td>
                            <td><input type="radio" name="q17" value="1"></td>
                            <td><input type="radio" name="q17" value="2"></td>
                            <td><input type="radio" name="q17" value="3"></td>
                            <td><input type="radio" name="q17" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">18. Se tiene una plantilla altamente motivada, que conoce con claridad las metas, objetivos y estrategias de la organización.</td>
                            <td><input type="radio" name="q18" value="0" required></td>
                            <td><input type="radio" name="q18" value="1"></td>
                            <td><input type="radio" name="q18" value="2"></td>
                            <td><input type="radio" name="q18" value="3"></td>
                            <td><input type="radio" name="q18" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">19. La empresa siempre trabaja conforme a una estrategia y objetivos claros. </td>
                            <td><input type="radio" name="q19" value="0" required></td>
                            <td><input type="radio" name="q19" value="1"></td>
                            <td><input type="radio" name="q19" value="2"></td>
                            <td><input type="radio" name="q19" value="3"></td>
                            <td><input type="radio" name="q19" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">20.  La gestión del circulante está optimizada.</td>
                            <td><input type="radio" name="q20" value="0" required></td>
                            <td><input type="radio" name="q20" value="1"></td>
                            <td><input type="radio" name="q20" value="2"></td>
                            <td><input type="radio" name="q20" value="3"></td>
                            <td><input type="radio" name="q20" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">21. Se tiene definido claramente el posicionamiento estratégico de todos los productos de la empresa.</td>
                            <td><input type="radio" name="q21" value="0" required></td>
                            <td><input type="radio" name="q21" value="1"></td>
                            <td><input type="radio" name="q21" value="2"></td>
                            <td><input type="radio" name="q21" value="3"></td>
                            <td><input type="radio" name="q21" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">22. Se dispone de una política de marca basada en la reputación que la empresa genera, en la gestión de relación con el cliente y en el posicionamiento estratégico previamente definido.</td>
                            <td><input type="radio" name="q22" value="0" required></td>
                            <td><input type="radio" name="q22" value="1"></td>
                            <td><input type="radio" name="q22" value="2"></td>
                            <td><input type="radio" name="q22" value="3"></td>
                            <td><input type="radio" name="q22" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">23.La cartera de clientes de nuestra empresa está altamente fidelizada, ya que tenemos como principal propósito el deleitarlos día a día. </td>
                            <td><input type="radio" name="q23" value="0" required></td>
                            <td><input type="radio" name="q23" value="1"></td>
                            <td><input type="radio" name="q23" value="2"></td>
                            <td><input type="radio" name="q23" value="3"></td>
                            <td><input type="radio" name="q23" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">24. Nuestra política y equipo de ventas y marketing es una importante ventaja competitiva de nuestra empresa respecto al sector.</td>
                            <td><input type="radio" name="q24" value="0" required></td>
                            <td><input type="radio" name="q24" value="1"></td>
                            <td><input type="radio" name="q24" value="2"></td>
                            <td><input type="radio" name="q24" value="3"></td>
                            <td><input type="radio" name="q24" value="4"></td>
                        </tr>
                        <tr>
                            <td class="statement">25.  El servicio al cliente que prestamos es uno de nuestras principales ventajas competitivas respecto a nuestros competidores.</td>
                            <td><input type="radio" name="q25" value="0" required></td>
                            <td><input type="radio" name="q25" value="1"></td>
                            <td><input type="radio" name="q25" value="2"></td>
                            <td><input type="radio" name="q25" value="3"></td>
                            <td><input type="radio" name="q25" value="4"></td>
                        </tr>
                        <tr class="result-row">
                            <td colspan="5" class="statement">POTENCIAL DE MEJORA DE LA CADENA DE VALOR INTERNA</td>
                            <td id="result-percentage">0%</td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <div class="fortalezas-debilidades">
                <table>
                    <tr>
                        <th colspan="2">FORTALEZAS</th>
                    </tr>
                    <tr>
                        <td>F1)</td>
                        <td><input type="text" style="width: 95%;"></td>
                    </tr>
                    <tr>
                        <td>F2)</td>
                        <td><input type="text" style="width: 95%;"></td>
                    </tr>
                </table>
                
                <table>
                    <tr>
                        <th colspan="2">DEBILIDADES</th>
                    </tr>
                    <tr>
                        <td>D1)</td>
                        <td><input type="text" style="width: 95%;"></td>
                    </tr>
                    <tr>
                        <td>D2)</td>
                        <td><input type="text" style="width: 95%;"></td>
                    </tr>
                </table>
            </div>

            <div class="buttons">
                <button type="button" id="calculateBtn">CÁLCULO DE VALOR</button>
                <button type="reset" id="resetBtn">BORRAR</button>
            </div>

        </div>
      </div>
    </div>




  </div>

</body>
</html>

</body>
</html>
