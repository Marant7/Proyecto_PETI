<?php
class Usuario {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para verificar el login
    public function login($usuario, $password) {
        // Consulta para obtener el usuario basado en el nombre de usuario
        $query = "SELECT * FROM tb_usuario WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        // Verificar si el usuario existe
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Comparar la contraseña ingresada con la almacenada en la base de datos
            if (password_verify($password, $row['password'])) {
                return $row; // El usuario y la contraseña son correctos
            }
        }
        return false; // Usuario o contraseña incorrectos
    }
}
?>