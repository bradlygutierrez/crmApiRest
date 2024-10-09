<?php
require_once __DIR__ . '/../models/TipoInteraccion.php';
header('Content-Type: application/json');

class TipoInteraccionController
{
    public function __construct() {}

    public function listarTipoInteracciones()
    {
        $tipoInteraccion = new TipoInteraccion();
        $stmt = $tipoInteraccion->listarTipoInteracciones();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay tipos de interacciones disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarTipoInteraccion($data)
    {
        $tipoInteraccion = new TipoInteraccion();
        if ($tipoInteraccion->registrarTipoInteraccion($data)) {
            echo json_encode(["message" => "Tipo de interacción registrado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al registrar el tipo de interacción"]);
        }
    }
}
?>
