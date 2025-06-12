<?php
require_once __DIR__ . '/../Models/ClsIdentificacion.php';
require_once '../config/clsconexion.php';

class identificacionController 
{
    private $modelo;
    
    public function __construct() 
    {
        $this->modelo = new ClsIdentificacion();
    }
    
    /**
     * Obtiene todos los datos FODA de una empresa
     * @param int $id_empresa
     * @return array
     */
    public function obtenerDatosFODA($id_empresa)
    {
        // Usamos el modelo que ya tienes creado
        $datos = $this->modelo->obtenerTodoFODA($id_empresa);
        
        // Convertimos los arrays de objetos a arrays simples de strings
        // porque el modelo devuelve [['descripcion' => 'texto'], ['descripcion' => 'texto2']]
        // pero la vista necesita ['texto', 'texto2']
        
        $resultado = [
            'fortalezas'    => $this->extraerDescripciones($datos['fortalezas']),
            'debilidades'   => $this->extraerDescripciones($datos['debilidades']),
            'oportunidades' => $this->extraerDescripciones($datos['oportunidades']),
            'amenazas'      => $this->extraerDescripciones($datos['amenazas'])
        ];
        
        return $resultado;
    }
    
    /**
     * Extrae solo las descripciones de un array de datos
     * @param array $datos
     * @return array
     */
    private function extraerDescripciones($datos)
    {
        $descripciones = [];
        foreach ($datos as $item) {
            if (isset($item['descripcion'])) {
                $descripciones[] = $item['descripcion'];
            }
        }
        return $descripciones;
    }
    
    /**
     * Método auxiliar para obtener el id_empresa de un usuario
     * @param int $id_usuario
     * @return int|null
     */
    public function obtenerIdEmpresaPorUsuario($id_usuario)
    {
        $conexion = (new clsConexion())->getConexion();
        
        $query = "SELECT id_empresa FROM tb_empresa WHERE id_usuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['id_empresa'];
        }
        
        $stmt->close();
        return null;
    }
    
    /**
     * Guarda o actualiza la síntesis de estrategias
     * @param int $id_empresa
     * @param array $datosEstrategias
     * @return array
     */
    public function guardarSintesisEstrategias($id_empresa, $datosEstrategias)
    {
        $conexion = (new clsConexion())->getConexion();
        
        try {
            $conexion->autocommit(false); // Iniciar transacción
            
            $exito = true;
            $errores = [];
            
            foreach ($datosEstrategias as $estrategia) {
                $query = "INSERT INTO tb_sintesis_estrategias 
                         (id_empresa, relacion, tipologia_estrategia, puntuacion, descripcion) 
                         VALUES (?, ?, ?, ?, ?)
                         ON DUPLICATE KEY UPDATE 
                         tipologia_estrategia = VALUES(tipologia_estrategia),
                         puntuacion = VALUES(puntuacion),
                         descripcion = VALUES(descripcion),
                         fecha_actualizacion = CURRENT_TIMESTAMP";
                
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("issis", 
                    $id_empresa,
                    $estrategia['relacion'],
                    $estrategia['tipologia_estrategia'],
                    $estrategia['puntuacion'],
                    $estrategia['descripcion']
                );
                
                if (!$stmt->execute()) {
                    $exito = false;
                    $errores[] = "Error al guardar estrategia " . $estrategia['relacion'] . ": " . $stmt->error;
                }
                $stmt->close();
            }
            
            if ($exito) {
                $conexion->commit();
                return ['exito' => true, 'mensaje' => 'Síntesis de estrategias guardada correctamente'];
            } else {
                $conexion->rollback();
                return ['exito' => false, 'mensaje' => 'Error al guardar', 'errores' => $errores];
            }
            
        } catch (Exception $e) {
            $conexion->rollback();
            return ['exito' => false, 'mensaje' => 'Error de base de datos: ' . $e->getMessage()];
        } finally {
            $conexion->autocommit(true);
        }
    }
    
    /**
     * Obtiene la síntesis de estrategias guardada
     * @param int $id_empresa
     * @return array
     */
    public function obtenerSintesisEstrategias($id_empresa)
    {
        $conexion = (new clsConexion())->getConexion();
        
        $query = "SELECT relacion, tipologia_estrategia, puntuacion, descripcion, fecha_actualizacion 
                  FROM tb_sintesis_estrategias 
                  WHERE id_empresa = ? 
                  ORDER BY FIELD(relacion, 'FO', 'AF', 'AD', 'OD')";
        
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id_empresa);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $estrategias = [];
        while ($row = $result->fetch_assoc()) {
            $estrategias[] = $row;
        }
        
        $stmt->close();
        return $estrategias;
    }
    
    /**
     * Procesa la solicitud AJAX para guardar síntesis
     */
    public function procesarGuardarSintesis()
    {
        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
            return;
        }
        
        // Verificar que el usuario esté logueado
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
            return;
        }
        
        // Obtener el id_empresa del usuario
        $id_usuario = $_SESSION['user_id'];
        $id_empresa = $this->obtenerIdEmpresaPorUsuario($id_usuario);
        
        if (!$id_empresa) {
            echo json_encode(['exito' => false, 'mensaje' => 'No se encontró empresa asociada']);
            return;
        }
        
        // Obtener los datos JSON del cuerpo de la petición
        $input = file_get_contents('php://input');
        $datos = json_decode($input, true);
        
        if (!$datos || !isset($datos['estrategias'])) {
            echo json_encode(['exito' => false, 'mensaje' => 'Datos inválidos']);
            return;
        }
        
        // Validar que los datos tengan la estructura correcta
        $estrategiasRequeridas = ['FO', 'AF', 'AD', 'OD'];
        foreach ($estrategiasRequeridas as $relacion) {
            if (!isset($datos['estrategias'][$relacion])) {
                echo json_encode(['exito' => false, 'mensaje' => "Falta la estrategia $relacion"]);
                return;
            }
        }
        
        // Preparar datos para guardar
        $datosEstrategias = [];
        foreach ($datos['estrategias'] as $relacion => $estrategia) {
            $datosEstrategias[] = [
                'relacion' => $relacion,
                'tipologia_estrategia' => $estrategia['tipologia_estrategia'],
                'puntuacion' => intval($estrategia['puntuacion']),
                'descripcion' => $estrategia['descripcion']
            ];
        }
        
        // Guardar en la base de datos
        $resultado = $this->guardarSintesisEstrategias($id_empresa, $datosEstrategias);
        
        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($resultado);
    }

    public function guardarCeldaMatriz($id_empresa, $tipoMatriz, $fila, $columna, $valor)
{
    $conexion = (new clsConexion())->getConexion();
    
    $query = "INSERT INTO tb_matrices_foda (id_empresa, tipo_matriz, fila, columna, valor) 
              VALUES (?, ?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE 
              valor = VALUES(valor),
              fecha_actualizacion = CURRENT_TIMESTAMP";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("isssi", $id_empresa, $tipoMatriz, $fila, $columna, $valor);
    
    if ($stmt->execute()) {
        $stmt->close();
        return ['exito' => true];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ['exito' => false, 'mensaje' => $error];
    }
}

/**
 * Obtiene todos los valores de las matrices de una empresa
 * @param int $id_empresa
 * @return array
 */
public function obtenerMatricesFODA($id_empresa)
{
    $conexion = (new clsConexion())->getConexion();
    
    $query = "SELECT tipo_matriz, fila, columna, valor 
              FROM tb_matrices_foda 
              WHERE id_empresa = ?
              ORDER BY tipo_matriz, fila, columna";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_empresa);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $matrices = [];
    while ($row = $result->fetch_assoc()) {
        $matrices[$row['tipo_matriz']][$row['fila']][$row['columna']] = $row['valor'];
    }
    
    $stmt->close();
    return $matrices;
}

/**
 * Procesa la solicitud AJAX para guardar una celda de matriz
 */
public function procesarGuardarCelda()
{
    error_log("procesarGuardarCelda iniciado");
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        error_log("Método no es POST: " . $_SERVER['REQUEST_METHOD']);
        http_response_code(405);
        echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
        return;
    }
    
    session_start();
    if (!isset($_SESSION['user_id'])) {
        error_log("Usuario no autenticado");
        http_response_code(401);
        echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
        return;
    }
    
    $id_usuario = $_SESSION['user_id'];
    error_log("ID Usuario: " . $id_usuario);
    
    $id_empresa = $this->obtenerIdEmpresaPorUsuario($id_usuario);
    error_log("ID Empresa: " . ($id_empresa ?? 'NULL'));
    
    if (!$id_empresa) {
        error_log("No se encontró empresa para usuario: " . $id_usuario);
        echo json_encode(['exito' => false, 'mensaje' => 'No se encontró empresa asociada']);
        return;
    }
    
    $input = file_get_contents('php://input');
    error_log("Input recibido: " . $input);
    
    $datos = json_decode($input, true);
    error_log("Datos decodificados: " . print_r($datos, true));
    
    if (!$datos || !isset($datos['tipo_matriz'], $datos['fila'], $datos['columna'], $datos['valor'])) {
        error_log("Datos inválidos o faltantes");
        echo json_encode(['exito' => false, 'mensaje' => 'Datos inválidos']);
        return;
    }
    
    $resultado = $this->guardarCeldaMatriz(
        $id_empresa,
        $datos['tipo_matriz'],
        $datos['fila'],
        $datos['columna'],
        intval($datos['valor'])
    );
    
    error_log("Resultado del guardado: " . print_r($resultado, true));
    
    header('Content-Type: application/json');
    echo json_encode($resultado);
}
}