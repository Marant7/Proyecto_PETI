<?php
// Models/Usuario.php

class Usuario {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para verificar el login
    public function login($usuario, $password) {
        // Consulta para obtener el usuario basado en el nombre de usuario
        $query = "SELECT * FROM tb_usuario WHERE usuario = ? LIMIT 1";
        $stmt = $this->db->prepare($query);

        // Vincular el parámetro
        $stmt->bind_param("s", $usuario);  // "s" indica que el parámetro es un string

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();

        // Verificar si el usuario existe
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Comparar la contraseña ingresada con la almacenada en la base de datos
            if ($password === $row['password']) {  // Sin hash, comparando directamente
                return $row; // El usuario y la contraseña son correctos
            }
        }

        return false; // Usuario o contraseña incorrectos
    }
    // Función para registrar un nuevo usuario
    public function registerUser($nombre, $apellido, $usuario, $correo, $password) {
        // Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Consulta SQL para insertar el usuario
        $sql = "INSERT INTO tb_usuario (nombre, apellido, usuario, correo, password) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $apellido, $usuario, $correo, $hashed_password);

        return $stmt->execute();
    }
    // Verificar si el nombre de usuario ya existe
    public function userExists($usuario) {
        $sql = "SELECT * FROM tb_usuario WHERE usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;  // Si existe, devuelve true
    }
}
?>