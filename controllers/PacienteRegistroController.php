<?php
require_once __DIR__ . '/../models/PacienteRegistro.php';
header('Content-Type: application/json');

class PacienteRegistroController
{
    public function obtenerPacientesPorMes()
    {
        $pacienteRegistro = new PacienteRegistro();
        $data = $pacienteRegistro->obtenerPacientesPorMes();

        if (!empty($data)) {
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No se encontraron datos"], JSON_UNESCAPED_UNICODE);
        }
    }
}
?>
