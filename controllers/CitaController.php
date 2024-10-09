<?php
require_once __DIR__ . '/../models/Cita.php';
header('Content-Type: application/json');

class CitaController
{
    public function __construct() {}

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
        if ($cita->registrarCita($data)) {
            echo json_encode(["message" => "Cita registrada correctamente"]);
        } else {
            echo json_encode(["message" => "Error al registrar la cita"]);
        }
    }
}
?>
