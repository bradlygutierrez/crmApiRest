<?php
require_once __DIR__ . '/../models/Contacto.php';

class ContactoController {
    public function listarContactos() {
        $contacto = new Contacto();
        $stmt = $contacto->listarContactos();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay contactos disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarContacto($data) {
        $contacto = new Contacto();
        $contacto->registrarContacto($data);
        echo json_encode(["message" => "Contacto registrado"]);
    }
}
?>
