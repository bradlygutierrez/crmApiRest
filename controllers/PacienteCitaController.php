<?php
require_once __DIR__ . '/../models/PacienteCita.php';
header('Content-Type: application/json');

class PacienteCitaController
{
    public function obtenerPacientesYCitasMesActual()
    {
        $pacienteCita = new PacienteCita();
        $data = $pacienteCita->obtenerPacientesYCitasMesActual();

        if (!empty($data)) {
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No se encontraron datos para el mes actual"], JSON_UNESCAPED_UNICODE);
        }
    }
}
?>
