<?php
require_once __DIR__ . '/../models/Ticket.php';
header('Content-Type: application/json');

class TicketController
{
    public function __construct() {}

    // Listar todos los tickets
    public function listarTickets()
    {
        $ticket = new Ticket();
        $stmt = $ticket->listarTickets();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay tickets disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    // Registrar un nuevo ticket
    public function registrarTicket($data)
    {
        $ticket = new Ticket();
        if ($ticket->registrarTicket($data)) {
            echo json_encode(["message" => "Ticket registrado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al registrar el ticket"]);
        }
    }

    // Actualizar un ticket existente
    public function actualizarTicket($id, $data)
    {
        $ticket = new Ticket();
        if ($ticket->actualizarTicket($id, $data)) {
            echo json_encode(["message" => "Ticket actualizado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al actualizar el ticket"]);
        }
    }
}
?>
