<?php
// Debug para verificar usuario y planes
session_start();
require_once '../Controllers/PlanEstrategicoController.php';

echo "<h2>Debug: Usuario y Planes</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .debug-section { background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .success { color: green; }
    .error { color: red; }
    pre { background: #e9ecef; padding: 10px; border-radius: 3px; }
</style>";

// 1. Verificar sesión de usuario
echo "<div class='debug-section'>";
echo "<h3>1. Información de Sesión</h3>";
if (isset($_SESSION['user'])) {
    echo "<p class='success'>✓ Usuario autenticado</p>";
    echo "<pre>" . print_r($_SESSION['user'], true) . "</pre>";
    $id_usuario_sesion = $_SESSION['user']['id_usuario'] ?? 'No definido';
    echo "<p><strong>ID Usuario en sesión:</strong> $id_usuario_sesion</p>";
} else {
    echo "<p class='error'>✗ No hay usuario en sesión</p>";
}
echo "</div>";

// 2. Verificar últimos planes en la BD
echo "<div class='debug-section'>";
echo "<h3>2. Últimos Planes en Base de Datos</h3>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=plan_estrategico;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT id_plan, id_usuario, nombre_plan, empresa, fecha_creacion FROM tb_plan_estrategico ORDER BY id_plan DESC LIMIT 5");
    $stmt->execute();
    $planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($planes) {
        echo "<p class='success'>✓ Planes encontrados en BD:</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID Plan</th><th>ID Usuario</th><th>Nombre</th><th>Empresa</th><th>Fecha</th></tr>";
        foreach ($planes as $plan) {
            echo "<tr>";
            echo "<td>" . $plan['id_plan'] . "</td>";
            echo "<td>" . $plan['id_usuario'] . "</td>";
            echo "<td>" . htmlspecialchars($plan['nombre_plan']) . "</td>";
            echo "<td>" . htmlspecialchars($plan['empresa']) . "</td>";
            echo "<td>" . $plan['fecha_creacion'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>✗ No se encontraron planes en la BD</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error al consultar BD: " . $e->getMessage() . "</p>";
}
echo "</div>";

// 3. Probar consulta del controlador
echo "<div class='debug-section'>";
echo "<h3>3. Prueba de Consulta del Controlador</h3>";
if (isset($_SESSION['user'])) {
    try {
        $controller = new PlanEstrategicoController();
        $planes_controller = $controller->obtenerPlanesUsuario($_SESSION['user']['id_usuario']);
        
        echo "<p><strong>Planes obtenidos por el controlador:</strong> " . count($planes_controller) . "</p>";
        if (!empty($planes_controller)) {
            echo "<pre>" . print_r($planes_controller, true) . "</pre>";
        } else {
            echo "<p class='error'>✗ El controlador no devolvió planes</p>";
            
            // Probar con diferentes IDs de usuario
            echo "<h4>Probando con diferentes IDs de usuario:</h4>";
            for ($i = 1; $i <= 3; $i++) {
                $planes_test = $controller->obtenerPlanesUsuario($i);
                echo "<p>Usuario ID $i: " . count($planes_test) . " planes</p>";
            }
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error en controlador: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='error'>✗ No se puede probar sin usuario en sesión</p>";
}
echo "</div>";

// 4. Verificar estructura de tabla de usuarios
echo "<div class='debug-section'>";
echo "<h3>4. Estructura de Tabla de Usuarios</h3>";
try {
    $stmt = $pdo->prepare("DESCRIBE tb_usuarios");
    $stmt->execute();
    $estructura = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th></tr>";
    foreach ($estructura as $campo) {
        echo "<tr>";
        echo "<td>" . $campo['Field'] . "</td>";
        echo "<td>" . $campo['Type'] . "</td>";
        echo "<td>" . $campo['Null'] . "</td>";
        echo "<td>" . $campo['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Verificar usuarios existentes
    $stmt = $pdo->prepare("SELECT id_usuario, nombre, apellido, usuario FROM tb_usuarios LIMIT 5");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>Usuarios en BD:</h4>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Usuario</th></tr>";
    foreach ($usuarios as $usuario) {
        echo "<tr>";
        echo "<td>" . $usuario['id_usuario'] . "</td>";
        echo "<td>" . htmlspecialchars($usuario['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($usuario['apellido']) . "</td>";
        echo "<td>" . htmlspecialchars($usuario['usuario']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Error al verificar usuarios: " . $e->getMessage() . "</p>";
}
echo "</div>";

?>

<p><a href="home.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">← Volver al Home</a></p>
