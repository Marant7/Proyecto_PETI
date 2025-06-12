<?php

class clsConexion
{
    private $server;
    private $user;
    private $pass;
    private $dbname;
    private $conexion;
    private $dsn;

    public function __construct()
    {
        $this->server = "localhost";
        $this->user   = "root";
        $this->pass   = "";
        $this->dbname = "appplanestrategico";
        $this->dsn    = "mysql:host={$this->server};dbname={$this->dbname};charset=utf8";

        try {
            // Creación de la conexión con PDO
            $this->conexion = new PDO($this->dsn, $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error en la conexión a la base de datos: " . $e->getMessage());
        }
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
            $this->conexion = null;
        }
    }
}
?>
