<?php
require_once __DIR__ . '/../models/Interaccion.php';
header('Content-Type: application/json');

class InteraccionController
{
    public function __construct()
    {
    }

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
        try {
            if ($interaccion->registrarInteraccion($data)) {
                echo json_encode(["message" => "Interacción registrada correctamente"]);
            } else {
                echo json_encode(["message" => "Error al registrar la interacción"]);
            }
        } catch (Exception $e) {
            echo json_encode(["message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

    public function modificarInteraccion($id_interaccion, $data)
    {
        $interaccion = new Interaccion();

        try {
            // Llama al método del modelo para modificar la interacción
            $interaccion->modificarInteraccion($id_interaccion, $data);
            echo json_encode(["message" => "Interacción modificada correctamente"], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            // Manejar el error, retornando el mensaje adecuado
            http_response_code(400); // Bad Request
            echo json_encode(["message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

}
?>