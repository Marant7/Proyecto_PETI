<?php

class UserModel {
    private $db;

    public function __construct() {
        require_once '../config/clsconexion.php';
        $this->db = new clsconexion();
    }    public function register($nombre, $apellido, $usuario, $correo, $password) {
        // Verificar si el usuario ya existe
        if ($this->usuarioExiste($usuario)) {
            throw new Exception("El nombre de usuario '$usuario' ya está en uso. Por favor elija otro.");
        }
        
        // Verificar si el correo ya existe
        if ($this->correoExiste($correo)) {
            throw new Exception("El correo electrónico '$correo' ya está registrado.");
        }
        
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO tb_usuario (nombre, apellido, usuario, correo, password) VALUES (:nombre, :apellido, :usuario, :correo, :password)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':password', $hashedPassword);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al registrar usuario: " . $e->getMessage());
        }
    }
    
    public function usuarioExiste($usuario) {
        $query = "SELECT COUNT(*) FROM tb_usuario WHERE usuario = :usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    public function correoExiste($correo) {
        $query = "SELECT COUNT(*) FROM tb_usuario WHERE correo = :correo";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function login($usuario, $password) {
        $query = "SELECT * FROM tb_usuario WHERE usuario = :usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Para el usuario existente que tiene password sin hash
            if ($result['password'] === $password) {
                return $result;
            }
            // Para nuevos usuarios con password hasheado
            if (password_verify($password, $result['password'])) {
                return $result;
            }
        }
        return false;
    }
}
