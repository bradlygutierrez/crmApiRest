<?php
require_once __DIR__ . '/../config/Database.php';  // Ajusta la ruta usando __DIR__

class GestorContactos {
    
    // Método para programar un seguimiento a un cliente
    public function programarSeguimiento($data) {
        // Asumimos que $data contiene los detalles del cliente y el seguimiento
        $idCliente = $data['id_cliente'];
        $fechaSeguimiento = $data['fecha_seguimiento'];
        $notaSeguimiento = $data['nota_seguimiento'];

        // Aquí iría la lógica para insertar la programación en la base de datos
        $query = "INSERT INTO seguimientos (id_cliente, fecha_seguimiento, nota_seguimiento) 
                  VALUES (:id_cliente, :fecha_seguimiento, :nota_seguimiento)";
        $db = Database::getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_cliente', $idCliente);
        $stmt->bindParam(':fecha_seguimiento', $fechaSeguimiento);
        $stmt->bindParam(':nota_seguimiento', $notaSeguimiento);
        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Seguimiento programado exitosamente"];
        } else {
            return ["status" => "error", "message" => "Error al programar seguimiento"];
        }
    }

    // Método para enviar un correo masivo a los clientes
    public function enviarCorreoMasivo($data) {
        $asunto = $data['asunto'];
        $mensaje = $data['mensaje'];
        
        // Aquí suponemos que tenemos una lista de correos obtenida desde la base de datos
        $query = "SELECT email_cliente FROM clientes";
        $db = Database::getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($clientes as $cliente) {
            $this->enviarCorreo($cliente['email_cliente'], $asunto, $mensaje);
        }

        return ["status" => "success", "message" => "Correo masivo enviado a todos los clientes"];
    }

    // Método para gestionar una campaña de marketing
    public function gestionarCampania($data) {
        $nombreCampania = $data['nombre_campania'];
        $descripcion = $data['descripcion'];
        $fechaInicio = $data['fecha_inicio'];
        $fechaFin = $data['fecha_fin'];

        // Guardar la campaña en la base de datos
        $query = "INSERT INTO campanias (nombre_campania, descripcion, fecha_inicio, fecha_fin)
                  VALUES (:nombre_campania, :descripcion, :fecha_inicio, :fecha_fin)";
        $db = Database::getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre_campania', $nombreCampania);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha_inicio', $fechaInicio);
        $stmt->bindParam(':fecha_fin', $fechaFin);
        
        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Campaña gestionada correctamente"];
        } else {
            return ["status" => "error", "message" => "Error al gestionar la campaña"];
        }
    }

    // Método auxiliar para enviar un correo a un cliente
    private function enviarCorreo($email, $asunto, $mensaje) {
        // Aquí iría la lógica para enviar un correo, por ejemplo, usando mail() o una librería externa
        // mail($email, $asunto, $mensaje); // Esta es una implementación simple
        // Para evitar problemas de spam, podrías utilizar un servicio como PHPMailer o Mailgun.
    }
}

?>
