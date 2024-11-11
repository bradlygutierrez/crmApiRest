<?php
require_once __DIR__ . '/../config/Database.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true"); // Permitir el uso de cookies y credenciales
header('Content-Type: application/json');
header("Access-Control-Allow-Credentials y Access-Control-Allow-Origin");



class Ticket
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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

    // Listar los tickets de un usuario específico
    public function listarTicketsPorUsuario($id_usuario): mixed
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
                  INNER JOIN Usuario ON TicketSoporte.id_usuario = Usuario.id_usuario
                  WHERE TicketSoporte.id_usuario = :id_usuario";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->execute();
        return $stmt;
    }

    // Registrar un nuevo ticket
    public function registrarTicket($data)
    {
        $query = "INSERT INTO TicketSoporte (id_usuario,fecha_ticket, titulo, descripcion ) 
                  VALUES (:id_usuario,CURRENT_TIMESTAMP, :titulo, :descripcion)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_usuario", $data['id_usuario']);
        $stmt->bindParam(":titulo", $data['titulo']);
        $stmt->bindParam(":descripcion", $data['descripcion']);

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

    public function cambiarEstadoTicket($id_ticket, $nuevo_estado)
    {
        $query = "UPDATE TicketSoporte 
          SET estado = :estado, ultima_actualizacion = CURRENT_TIMESTAMP
          WHERE id_ticket = :id_ticket";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $nuevo_estado);
        $stmt->bindParam(":id_ticket", $id_ticket);

        if ($stmt->execute()) {
            return json_encode(["message" => "El estado del ticket ha sido actualizado a resuelto."]);
        } else {
            return json_encode(["message" => "No se pudo actualizar el estado del ticket."]);
        }
    }


}
?>