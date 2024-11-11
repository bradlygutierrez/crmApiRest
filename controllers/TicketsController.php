<?php
require_once __DIR__ . '/../models/Tickets.php';
require_once __DIR__ . '/../models/Usuario.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Iniciar sesión para acceder a las variables de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class TicketController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

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

    // Listar los tickets del usuario que ha iniciado sesión
    public function listarTicketsPorUsuario()
    {
        $user = (new Usuario())->obtenerUsuarioLogeado();

        // Verificar si el usuario ha iniciado sesión
        if ($user === null) {
            http_response_code(401); // Código de no autorizado
            echo json_encode(["message" => "Usuario no autenticado"]);
            exit;
        }

        // Obtener el ID del usuario de la sesión
        $id_usuario = $user['user_id'];
        $ticket = new Ticket();

        // Llamar al método que lista solo los tickets del usuario
        $stmt = $ticket->listarTicketsPorUsuario($id_usuario);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verificar si se encontraron tickets
        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay tickets disponibles para este usuario"], JSON_UNESCAPED_UNICODE);
        }
    }


    // Obtener el número de tickets pendientes
    public function obtenerTicketsPendientes()
    {
        $ticket = new Ticket();
        $totalPendientes = $ticket->contarTicketsPendientes();

        echo json_encode(['totalPendientes' => $totalPendientes]);
    }

    // Registrar un nuevo ticket
    public function registrarTicket($data)
    {
        // Verificar si el usuario está autenticado
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
        // Verificar si el usuario está autenticado
        if (isset($_SESSION['user_id'])) {
            $data['id_usuario'] = $_SESSION['user_id']; // Asignar el id_usuario desde la sesión
            $ticket = new Ticket();

            if ($ticket->actualizarTicket($id, $data)) {
                echo json_encode(["message" => "Ticket actualizado correctamente"]);
            } else {
                echo json_encode(["message" => "Error al actualizar el ticket"]);
            }
        } else {
            echo json_encode(["message" => "No se ha iniciado sesión."]);
        }
    }

    public function cambiarEstadoTicket($id_ticket, $data)
    {
        error_log(print_r($data, true)); // Verificar que $data contiene "status"
        $ticket = new Ticket();
        $resultado = $ticket->cambiarEstadoTicket($id_ticket, $data['status']);
        echo $resultado;
    }
    

}
?>