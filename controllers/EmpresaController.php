<?php
require_once __DIR__ . '/../models/Empresa.php';

class EmpresaController
{
    public function listarEmpresas()
    {
        $empresa = new Empresa();
        $stmt = $empresa->listarEmpresas();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["message" => "No hay empresas disponibles"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function registrarEmpresa($data)
    {
        $empresa = new Empresa();
        $empresa->registrarEmpresa($data);
        echo json_encode(["message" => "Empresa registrada"]);
    }

    public function modificarEmpresa($id_empresa, $data)
    {
        $empresa = new Empresa();

        try {
            // Llama al método del modelo para modificar la empresa
            $empresa->modificarEmpresa($id_empresa, $data);
            echo json_encode(["message" => "Empresa modificada correctamente"], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            // Manejar el error, retornando el mensaje adecuado
            http_response_code(400); // Bad Request
            echo json_encode(["message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

}
?>