<?php
require_once __DIR__ . '/../config/Database.php';

class TipoInteraccion
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarTipoInteracciones(): mixed
    {
        $query = "SELECT * FROM TipoInteraccion";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function registrarTipoInteraccion($data)
    {
        $query = "INSERT INTO TipoInteraccion (descripcion) 
                  VALUES (:descripcion)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":descripcion", $data['descripcion']);
        
        return $stmt->execute();
    }
}
?>
