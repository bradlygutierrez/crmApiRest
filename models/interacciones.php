<?php
require_once '../config/Database.php';

class Interaccion {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarInteracciones() {
        $query = "SELECT * FROM interacciones";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarInteraccion($data) {
        $query = "INSERT INTO interacciones (fecha_interaccion, nota_interaccion, estado_interaccion) 
                  VALUES (:fecha_interaccion, :nota_interaccion, :estado_interaccion)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":fecha_interaccion", $data['fecha_interaccion']);
        $stmt->bindParam(":nota_interaccion", $data['nota_interaccion']);
        $stmt->bindParam(":estado_interaccion", $data['estado_interaccion']);
        
        $stmt->execute();
    }
}
?>
