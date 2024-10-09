<?php
require_once __DIR__ . '/../config/Database.php';

class Interaccion
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarInteracciones(): mixed
    {
        // Realizamos un INNER JOIN para obtener información del paciente, usuario y tipo de interacción
        $query = "SELECT Interaccion.id_interaccion, Interaccion.fecha_interaccion, Interaccion.nota_interaccion, Paciente.nombre_paciente, Usuario.nombre_usuario, TipoInteraccion.nombre_tipoInteraccion FROM Interaccion INNER JOIN Paciente ON Interaccion.id_paciente = Paciente.id_paciente INNER JOIN Usuario ON Interaccion.id_usuario = Usuario.id_usuario INNER JOIN TipoInteraccion ON Interaccion.id_tipoInteraccion = TipoInteraccion.id_tipoInteraccion";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Devuelve un array asociativo
    }
    
    public function registrarInteraccion($data)
    {
        $query = "INSERT INTO Interaccion (fecha_interaccion, nota_interaccion, id_paciente, id_usuario, id_tipoInteraccion) 
                  VALUES (:fecha_interaccion, :nota_interaccion, :id_paciente, :id_usuario, :id_tipoInteraccion)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":fecha_interaccion", $data['fecha_interaccion']);
        $stmt->bindParam(":nota_interaccion", $data['nota_interaccion']);
        $stmt->bindParam(":id_paciente", $data['id_paciente']);
        $stmt->bindParam(":id_usuario", $data['id_usuario']);
        $stmt->bindParam(":id_tipoInteraccion", $data['id_tipoInteraccion']);
        
        return $stmt->execute();
    }
}
?>
