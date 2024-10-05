<?php
require_once '../models/clientes.php';
header('Content-Type: application/json');


class ClienteController
{   
    public function __construct(){
    }
    
    public function listarClientes()
    {
        header('Content-Type: application/json; charset=utf-8'); 
        $cliente = new Cliente();// Asegura que la respuesta es JSON y en UTF-8
        $stmt = $cliente->listarClientes(); 
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Usa $this->cliente en lugar de crear una nueva instancia
    
        if (!empty($result)) { // Cambia $clientes a $result
            echo json_encode($result, JSON_UNESCAPED_UNICODE); // Envía la respuesta JSON
        } else {
            echo json_encode(["message" => "No hay clientes disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }
    


    public function registrarCliente($data)
    {
        $cliente = new Cliente();
        $cliente->registrarCliente($data);
        echo json_encode(["message" => "Cliente registrado"]);
    }

    // Métodos actualizarCliente(), obtenerCliente()...
}
?>