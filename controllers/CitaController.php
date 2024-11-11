<?php
require_once __DIR__ . '/../models/Cita.php';
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
}

