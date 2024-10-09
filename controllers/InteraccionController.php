<?php
require_once __DIR__ . '/../models/Interaccion.php';
header('Content-Type: application/json');

class InteraccionController
{
    public function __construct() {}

    public function listarInteracciones()
    {
        $interaccion = new Interaccion();
        $stmt = $interaccion->listarInteracciones();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay interacciones disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarInteraccion($data)
    {
        $interaccion = new Interaccion();
        if ($interaccion->registrarInteraccion($data)) {
            echo json_encode(["message" => "Interacción registrada correctamente"]);
        } else {
            echo json_encode(["message" => "Error al registrar la interacción"]);
        }
    }
}
?>
