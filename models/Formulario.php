<?php
require_once __DIR__ . '/../config/Database.php';

class Formulario
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarFormularios()
    {
        $query = "SELECT * FROM FormularioSatisfaccion";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna el resultado
    }

    public function registrarFormulario($data)
    {
        $query = "INSERT INTO FormularioSatisfaccion 
                  (limpieza, atencion, calidad_servicio, tiempo_espera, instalaciones) 
                  VALUES (:limpieza, :atencion, :calidad_servicio, :tiempo_espera, :instalaciones)";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":limpieza", $data['limpieza']);
        $stmt->bindParam(":atencion", $data['atencion']);
        $stmt->bindParam(":calidad_servicio", $data['calidad_servicio']);
        $stmt->bindParam(":tiempo_espera", $data['tiempo_espera']);
        $stmt->bindParam(":instalaciones", $data['instalaciones']);

        // Ejecutar la consulta
        return $stmt->execute();
    }
}
?>