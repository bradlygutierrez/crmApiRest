<?php
require_once __DIR__ . '/../config/Database.php';

class Servicio
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarServicios()
    {
        $query = "SELECT * FROM Servicio";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna el resultado
    }

    public function registrarServicio($data)
    {
        $query = "INSERT INTO Servicio (nombre_servicio, costo_servicio, duracion_servicio) 
                  VALUES (:nombre_servicio, :costo_servicio, :duracion_servicio)";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":nombre_servicio", $data['nombre_servicio']);
        $stmt->bindParam(":costo_servicio", $data['costo_servicio']);
        $stmt->bindParam(":duracion_servicio", $data['duracion_servicio']);

        return $stmt->execute(); // Retorna verdadero si la ejecución es exitosa
    }

    public function actualizarServicio($id_servicio, $data)
    {
        $query = "UPDATE Servicio 
                  SET nombre_servicio = :nombre_servicio, 
                      costo_servicio = :costo_servicio, 
                      duracion_servicio = :duracion_servicio 
                  WHERE id_servicio = :id_servicio";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":nombre_servicio", $data['nombre_servicio']);
        $stmt->bindParam(":costo_servicio", $data['costo_servicio']);
        $stmt->bindParam(":duracion_servicio", $data['duracion_servicio']);
        $stmt->bindParam(":id_servicio", $id_servicio);

        return $stmt->execute(); // Retorna verdadero si la ejecución es exitosa
    }
}
?>