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
        try {
            // Validar que se pasaron los datos necesarios
            if (!isset($data['nombre_contacto'], $data['email_contacto'], $data['telefono_contacto'], $data['cargo'], $data['nombre_empresa'])) {
                throw new Exception("Faltan datos requeridos");
            }

            $contacto = new Contacto();
            $contacto->registrarContacto($data);
            echo json_encode(["message" => "Contacto registrado"], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            // Manejo de excepciones
            http_response_code(400); // Código de error 400 (Bad Request)
            echo json_encode(["message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }
}
?>
