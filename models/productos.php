<?php
require_once '../config/Database.php';

class Producto {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarProductos() {
        $query = "SELECT * FROM Producto";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function registrarProducto($data) {
        $query = "INSERT INTO productos (nombre_producto, costo_producto, cantidad_producto) 
                  VALUES (:nombre_producto, :costo_producto, :cantidad_producto)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre_producto", $data['nombre_producto']);
        $stmt->bindParam(":costo_producto", $data['costo_producto']);
        $stmt->bindParam(":cantidad_producto", $data['cantidad_producto']);
        
        $stmt->execute();
    }
}
?>
