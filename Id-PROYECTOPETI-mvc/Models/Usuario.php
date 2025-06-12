<?php
require_once __DIR__ . '/../Config/clsConexion.php'; // Asegúrate de que la ruta sea correcta

class Usuario {
    private $db;

    // Constructor, recibe la conexión a la base de datos
    public function __construct($db) {
        $this->db = $db;
    }

    // Método para registrar al usuario en la base de datos
    public function register($nombre, $apellido, $usuario, $correo, $password) {
        try {
            // Verificar si ya existe el usuario o correo
            $sql = "SELECT COUNT(*) FROM tb_usuario WHERE usuario = :usuario OR correo = :correo";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                return false; // Usuario o correo ya existe
            }
            
            // Encriptar la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar el nuevo usuario
            $sql = "INSERT INTO tb_usuario (nombre, apellido, usuario, correo, password) 
                    VALUES (:nombre, :apellido, :usuario, :correo, :password)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            
            // Registro de depuración para verificar la consulta y los parámetros
            error_log("Consulta SQL: " . $sql);
            error_log("Parámetros: usuario=" . $usuario . ", correo=" . $correo);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // Registrar el error pero no mostrarlo al usuario
            error_log("Error en register: " . $e->getMessage());
            return false;
        }
    }

    // Verificar si el nombre de usuario ya existe en la base de datos
    public function userExists($usuario) {
        $stmt = $this->db->prepare("SELECT * FROM tb_usuario WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;  // Si existe, devuelve true
    }
     // Método para verificar el login
     public function login($usuario, $password) {
        try {
            $sql = "SELECT * FROM tb_usuario WHERE usuario = :usuario LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Registrar el error pero no mostrarlo al usuario
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }
}
?>
