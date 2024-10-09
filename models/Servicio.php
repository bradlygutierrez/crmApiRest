<?php
require_once __DIR__ . '/../config/Database.php';

class Servicio {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarServicios() {
        $query = "SELECT * FROM Servicio";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna el resultado
    }

    public function registrarServicio($data) {
        $query = "INSERT INTO Servicio (nombre_servicio, descripcion_servicio, precio_servicio) 
                  VALUES (:nombre_servicio, :descripcion_servicio, :precio_servicio)";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":nombre_servicio", $data['nombre_servicio']);
        $stmt->bindParam(":descripcion_servicio", $data['descripcion_servicio']);
        $stmt->bindParam(":precio_servicio", $data['precio_servicio']);

        $stmt->execute();
    }
}
?>
