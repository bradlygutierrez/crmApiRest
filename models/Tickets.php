<?php
require_once __DIR__ . '/../config/Database.php';

class Ticket
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Listar todos los tickets
    public function listarTickets(): mixed
    {
        $query = "SELECT 
                    TicketSoporte.id_ticket,
                    Usuario.nombre_usuario AS solicitante,
                    TicketSoporte.fecha_ticket,
                    TicketSoporte.titulo,
                    TicketSoporte.descripcion,
                    TicketSoporte.ultima_actualizacion,
                    TicketSoporte.estado
                  FROM TicketSoporte
                  INNER JOIN Usuario ON TicketSoporte.id_usuario = Usuario.id_usuario";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Registrar un nuevo ticket
    public function registrarTicket($data)
    {
        $query = "INSERT INTO TicketSoporte (id_usuario, fecha_ticket, titulo, descripcion, estado) 
                  VALUES (:id_usuario, :fecha_ticket, :titulo, :descripcion, :estado)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_usuario", $data['id_usuario']);
        $stmt->bindParam(":fecha_ticket", $data['fecha_ticket']);
        $stmt->bindParam(":titulo", $data['titulo']);
        $stmt->bindParam(":descripcion", $data['descripcion']);
        $stmt->bindParam(":estado", $data['estado']);
        
        return $stmt->execute();
    }

    // Actualizar un ticket existente
    public function actualizarTicket($id, $data)
    {
        $query = "UPDATE TicketSoporte 
                  SET titulo = :titulo, descripcion = :descripcion, estado = :estado, ultima_actualizacion = CURRENT_TIMESTAMP
                  WHERE id_ticket = :id_ticket";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_ticket", $id);
        $stmt->bindParam(":titulo", $data['titulo']);
        $stmt->bindParam(":descripcion", $data['descripcion']);
        $stmt->bindParam(":estado", $data['estado']);

        return $stmt->execute();
    }
}
?>
