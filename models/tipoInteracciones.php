<?php
require_once __DIR__ . '/../config/Database.php';  // Ajusta la ruta usando __DIR__

class TipoInteraccion {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarTiposInteraccion() {
        $query = "SELECT * FROM tipos_interaccion";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTipoInteraccion($id) {
        $query = "SELECT * FROM tipos_interaccion WHERE id_tipointeraccion = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
