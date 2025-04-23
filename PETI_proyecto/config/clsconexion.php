<?php

class clsConexion
{
    private $server;
    private $user;
    private $pass;
    private $dbname;
    private $conexion;

    public function __construct()
    {
        $this->server = "localhost";
        $this->user   = "root";
        $this->pass   = "";
        $this->dbname = "appplanestrategico";

        // Creación de la conexión con MySQLi
        $this->conexion = new mysqli($this->server, $this->user, $this->pass, $this->dbname);

        // Verificamos si hay algún error de conexión
        if ($this->conexion->connect_error) {
            die("Error en la conexión a la base de datos: " . $this->conexion->connect_error);
        }

        // Establecemos la codificación de caracteres a utf8
        $this->conexion->set_charset('utf8');
    }

    // Método para obtener la conexión
    public function getConexion()
    {
        return $this->conexion;
    }

    // Método para cerrar la conexión
    public function Cerrarconex()
    {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
?>
