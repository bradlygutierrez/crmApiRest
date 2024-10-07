<?php
require_once __DIR__ . '/../config/Database.php';  // Ajusta la ruta usando __DIR__
header('Content-Type: application/json');


class Venta
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarVentas()
    {
        $query = " SELECT Venta.id_venta, Cliente.nombre_cliente, Venta.fecha_venta, Venta.monto_venta, Venta.estado_venta FROM Venta INNER JOIN Cliente ON Venta.id_cliente = Cliente.id_cliente";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Asegúrate de devolver los resultados como un arreglo asociativo
    }


    public function registrarVenta($data)
    {
        $query = "INSERT INTO ventas (fecha_venta, monto_venta, estado_venta) 
                  VALUES (:fecha_venta, :monto_venta, :estado_venta)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":fecha_venta", $data['fecha_venta']);
        $stmt->bindParam(":monto_venta", $data['monto_venta']);
        $stmt->bindParam(":estado_venta", $data['estado_venta']);

        $stmt->execute();
    }
}
?>