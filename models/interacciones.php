<?php
require_once __DIR__ . '/../config/Database.php';  // Ajusta la ruta usando __DIR__

class Interaccion
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarInteracciones()
    {
        $query = "SELECT 
    i.id_interaccion, 
    c.nombre_cliente, 
    i.fecha_interaccion, 
    i.nota_interaccion, 
    ti.nombre_tipo_interaccion
    FROM 
        Interaccion i
    INNER JOIN 
        Cliente c ON i.id_cliente = c.id_cliente
    INNER JOIN 
        TipoInteraccion ti ON i.tipo_interaccion = ti.id_tipo_interaccion;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function registrarInteraccion($data)
    {
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