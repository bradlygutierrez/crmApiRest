<?php
require_once '../config/Database.php';
header('Content-Type: application/json');


class Cliente
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarClientes():mixed
    {
        $query = "SELECT * FROM Cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Depuración: imprime los resultados
        return $stmt; // Retorna el resultado
    }


    public function registrarCliente($data)
    {
        $query = "INSERT INTO clientes (nombre_cliente, edad_cliente, email_cliente, telefono_cliente, fecha_registro, historial_compras, nota_cliente) 
                  VALUES (:nombre_cliente, :edad_cliente, :email_cliente, :telefono_cliente, :fecha_registro, :historial_compras, :nota_cliente)";
        $stmt = $this->conn->prepare($query);

        // Binding de parámetros
        $stmt->bindParam(":nombre_cliente", $data['nombre_cliente']);
        $stmt->bindParam(":edad_cliente", $data['edad_cliente']);
        $stmt->bindParam(":email_cliente", $data['email_cliente']);
        $stmt->bindParam(":telefono_cliente", $data['telefono_cliente']);
        $stmt->bindParam(":fecha_registro", $data['fecha_registro']);
        $stmt->bindParam(":historial_compras", $data['historial_compras']);
        $stmt->bindParam(":nota_cliente", $data['nota_cliente']);

        $stmt->execute();
    }
}
?>