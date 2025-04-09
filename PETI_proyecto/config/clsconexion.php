<?php 
class clsConexion
{
    private $server;
    private $user;
    private $pass;
    private $dbname;
    private $conexion;

    // CONSTRUCTOR DE LA CLASE
    public function __construct()
    {
        $this->server = "localhost";
        $this->user   = "root";
        $this->pass   = "";
        $this->dbname = "";

        $this->conexion = new mysqli($this->server, $this->user, $this->pass, $this->dbname);

        if ($this->conexion->connect_error) {
            die("Error en la conexión a la base de datos: " . $this->conexion->connect_error);
        }
        
        $this->conexion->set_charset('utf8');
    }

    // DESTRUCTOR DE LA CLASE
    public function __destruct()
    {
        $this->Cerrarconex();
    }

    // MANDAR UNA CONSULTA INSERT, UPDATE, DELETE
    public function Consulta($sql)
    {
        return $this->conexion->query($sql);
    }

    // MANDAR UNA CONSULTA PARA RETORNAR DATOS
    public function ConsultaResult($sql)
    {
        $resultado = $this->conexion->query($sql);
        return $resultado;
    }

    // CERRAMOS LA CONSULTA PARA LIBERAR MEMORIA
    public function Liberar($sql)
    {
        $sql->free();
    }

    // CERRAR CONEXIÓN
    public function Cerrarconex()
    {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
