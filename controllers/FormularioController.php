<?php
require_once __DIR__ . '/../models/Formulario.php';

class FormularioController {
    public function listarFormularios() {
        $formulario = new Formulario();
        $stmt = $formulario->listarFormularios();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay formularios disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarFormulario($data) {
        $formulario = new Formulario();
        $formulario->registrarFormulario($data);
        echo json_encode(["message" => "Formulario registrado"]);
    }
}
?>
