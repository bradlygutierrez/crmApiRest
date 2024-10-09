<?php
require_once __DIR__ . '/../config/Database.php';

class Cita
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarCitas(): mixed
    {
        // Realizamos un INNER JOIN para obtener información del paciente y servicio relacionados con la cita
        $query = "SELECT 
                Cita.id_cita,
                Cita.fecha_cita,
                Cita.hora_cita,
                Cita.estado_cita,
                Paciente.nombre_paciente,
                Servicio.nombre_servicio,
                Servicio.costo_servicio
            FROM Cita
            INNER JOIN Paciente ON Cita.id_paciente = Paciente.id_paciente
            INNER JOIN Servicio ON Cita.id_servicio = Servicio.id_servicio
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Devuelve un array asociativo
    }
    

    public function registrarCita($data)
    {
        $query = "INSERT INTO Cita (fecha_cita, motivo_cita, id_paciente, id_usuario) 
                  VALUES (:fecha_cita, :motivo_cita, :id_paciente, :id_usuario)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":fecha_cita", $data['fecha_cita']);
        $stmt->bindParam(":motivo_cita", $data['motivo_cita']);
        $stmt->bindParam(":id_paciente", $data['id_paciente']);
        $stmt->bindParam(":id_usuario", $data['id_usuario']);
        
        return $stmt->execute();
    }
}
?>
