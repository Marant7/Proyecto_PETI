<?php
session_start();

echo "<h1>Diagnóstico de Sesión de Usuario</h1>";

echo "<h2>1. Contenido completo de \$_SESSION:</h2>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

echo "<h2>2. Verificación de Usuario Logueado:</h2>";
if (isset($_SESSION['user'])) {
    echo "<p style='color: green;'>✓ Usuario logueado encontrado</p>";
    
    $user = $_SESSION['user'];
    echo "<h3>Datos del usuario:</h3>";
    echo "<ul>";
    
    if (isset($user['id_usuario'])) {
        echo "<li><strong>ID Usuario (id_usuario):</strong> " . $user['id_usuario'] . "</li>";
    }
    
    if (isset($user['id'])) {
        echo "<li><strong>ID Usuario (id):</strong> " . $user['id'] . "</li>";
    }
    
    if (isset($user['nombre'])) {
        echo "<li><strong>Nombre:</strong> " . $user['nombre'] . "</li>";
    }
    
    if (isset($user['email'])) {
        echo "<li><strong>Email:</strong> " . $user['email'] . "</li>";
    }
    
    if (isset($user['usuario'])) {
        echo "<li><strong>Usuario:</strong> " . $user['usuario'] . "</li>";
    }
    
    echo "</ul>";
    
    // Determinar ID de usuario final
    $user_id = $user['id_usuario'] ?? $user['id'] ?? null;
    echo "<h3>ID de Usuario Final Detectado: " . ($user_id ?? 'NULL') . "</h3>";
    
} else {
    echo "<p style='color: red;'>✗ No hay usuario logueado en la sesión</p>";
}

echo "<h2>3. Verificar Planes para este Usuario:</h2>";
if (isset($_SESSION['user'])) {
    require_once '../config/clsconexion.php';
    
    try {
        $user = $_SESSION['user'];
        $user_id = $user['id_usuario'] ?? $user['id'] ?? null;
        
        if ($user_id) {
            $conexion = new clsConexion();
            $pdo = $conexion->getConexion();
            
            // Buscar planes por ID de usuario
            $stmt = $pdo->prepare("
                SELECT 
                    p.id_plan,
                    p.nombre_plan,
                    p.empresa,
                    p.estado,
                    p.fecha_creacion,
                    p.id_usuario
                FROM tb_plan_estrategico p
                WHERE p.id_usuario = ?
                ORDER BY p.fecha_creacion DESC
            ");
            
            $stmt->execute([$user_id]);
            $planes_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<p><strong>Planes encontrados para usuario ID " . $user_id . ":</strong> " . count($planes_usuario) . "</p>";
            
            if (count($planes_usuario) > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-top: 10px;'>";
                echo "<tr style='background-color: #f0f0f0;'>";
                echo "<th>ID Plan</th><th>Nombre</th><th>Empresa</th><th>Estado</th><th>Fecha Creación</th><th>ID Usuario</th>";
                echo "</tr>";
                
                foreach ($planes_usuario as $plan) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($plan['id_plan']) . "</td>";
                    echo "<td>" . htmlspecialchars($plan['nombre_plan']) . "</td>";
                    echo "<td>" . htmlspecialchars($plan['empresa']) . "</td>";
                    echo "<td>" . htmlspecialchars($plan['estado']) . "</td>";
                    echo "<td>" . htmlspecialchars($plan['fecha_creacion']) . "</td>";
                    echo "<td>" . htmlspecialchars($plan['id_usuario']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: orange;'>No se encontraron planes para este usuario.</p>";
                
                // Mostrar todos los planes para referencia
                echo "<h4>Todos los planes en la base de datos (para referencia):</h4>";
                $stmt_all = $pdo->query("
                    SELECT id_plan, nombre_plan, empresa, id_usuario, fecha_creacion 
                    FROM tb_plan_estrategico 
                    ORDER BY fecha_creacion DESC 
                    LIMIT 10
                ");
                $todos_planes = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($todos_planes) > 0) {
                    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-top: 10px;'>";
                    echo "<tr style='background-color: #f0f0f0;'>";
                    echo "<th>ID Plan</th><th>Nombre</th><th>Empresa</th><th>ID Usuario</th><th>Fecha Creación</th>";
                    echo "</tr>";
                    
                    foreach ($todos_planes as $plan) {
                        $highlight = ($plan['id_usuario'] == $user_id) ? 'style="background-color: yellow;"' : '';
                        echo "<tr $highlight>";
                        echo "<td>" . htmlspecialchars($plan['id_plan']) . "</td>";
                        echo "<td>" . htmlspecialchars($plan['nombre_plan']) . "</td>";
                        echo "<td>" . htmlspecialchars($plan['empresa']) . "</td>";
                        echo "<td>" . htmlspecialchars($plan['id_usuario']) . "</td>";
                        echo "<td>" . htmlspecialchars($plan['fecha_creacion']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<p><em>Los planes resaltados en amarillo corresponden al usuario actual.</em></p>";
                } else {
                    echo "<p>No hay planes en la base de datos.</p>";
                }
            }
            
        } else {
            echo "<p style='color: red;'>Error: No se pudo determinar el ID del usuario.</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error al consultar la base de datos: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>4. Enlaces de Navegación:</h2>";
echo "<p><a href='home.php'>🏠 Ir al Dashboard</a></p>";
echo "<p><a href='wizard_estrategico.php'>✨ Crear Nuevo Plan</a></p>";
echo "<p><a href='login.php'>🔑 Página de Login</a></p>";
echo "<p><a href='logout.php'>🚪 Cerrar Sesión</a></p>";

?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f5f5f5;
}

h1 {
    color: #333;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
}

h2 {
    color: #555;
    margin-top: 30px;
}

table {
    margin-top: 10px;
    font-size: 14px;
}

th {
    padding: 8px;
    text-align: left;
}

td {
    padding: 8px;
}

pre {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    overflow-x: auto;
}

ul {
    background-color: white;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #667eea;
}

a {
    color: #667eea;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
