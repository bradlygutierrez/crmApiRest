<?php
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../models/Usuario.php';
header('Content-Type: application/json');

class CitaController
{
    public function __construct()
    {
    }

    public function contarCitasMes()
    {
        $cita = new Cita();
        $totalCitas = $cita->contarCitasMes(); // Llama al método que cuenta las citas del mes
        echo json_encode(['total_citas_mes' => $totalCitas], JSON_UNESCAPED_UNICODE);
    }
    public function listarCitas()
    {
        $cita = new Cita();
        $stmt = $cita->listarCitas();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay citas disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarCita($data)
    {
        $cita = new Cita();

        try {
            $cita->registrarCita($data); // Llama al método del modelo
            echo json_encode(["message" => "Cita registrada correctamente"]);
        } catch (Exception $e) {
            // Manejar el error, retornando el mensaje adecuado
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function listarCitasPorUsuario()
    {
        $user = (new Usuario())->obtenerUsuarioLogeado();
        if ($user === null) {
            http_response_code(401); // Código de no autorizado
            echo json_encode(["message" => "Usuario no autenticado"]);
            exit;
        }

        $cita = new Cita();
        $nombre_paciente = $user['username'];
        $stmt = $cita->listarCitasPorUsuario($nombre_paciente);

        if ($stmt === null) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Error al obtener citas"]);
            exit;
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["message" => "No hay citas disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function modificarCita($id_cita, $data)
{
    $cita = new Cita();

    try {
        // Llama al método del modelo para modificar la cita
        $cita->modificarCita($id_cita, $data);
        echo json_encode(["message" => "Cita modificada correctamente"], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        // Manejar el error, retornando el mensaje adecuado
        http_response_code(400); // Bad Request
        echo json_encode(["message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
}

}

