<?php

require_once __DIR__ . '/../config/Database.php';

class Usuario
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();

    }

    public function listarUsuarios()
    {
        $query = "SELECT * FROM Usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function registrarUsuario($data)
    {
        // Verificar que todos los datos requeridos estén presentes
        if (!isset($data['nombre_usuario'], $data['email_usuario'], $data['contraseña'], $data['rol'])) {
            return false;
        }

        // Asignar variables
        $nombre_usuario = $data['nombre_usuario'];
        $email_usuario = $data['email_usuario'];
        $contraseña = password_hash($data['contraseña'], PASSWORD_DEFAULT); // Hash de la contraseña
        $rol = $data['rol'];

        // Preparar la consulta SQL para insertar el usuario
        $query = "INSERT INTO Usuario (nombre_usuario, email_usuario, contraseña, rol) 
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Ejecutar la consulta con los datos proporcionados
        return $stmt->execute([$nombre_usuario, $email_usuario, $contraseña, $rol]);
    }

    public function actualizarUsuario($id_usuario, $data)
    {
        // Verificar que el ID y al menos un campo para actualizar estén presentes
        if (empty($id_usuario) || !isset($data['nombre_usuario'], $data['email_usuario'], $data['contraseña'], $data['rol'])) {
            return false;
        }

        // Preparar los valores a actualizar
        $nombre_usuario = $data['nombre_usuario'] ?? null;
        $email_usuario = $data['email_usuario'] ?? null;
        $contraseña = !empty($data['contraseña']) ? password_hash($data['contraseña'], PASSWORD_DEFAULT) : null;
        $rol = $data['rol'] ?? null;

        // Crear la consulta SQL dinámicamente
        $fieldsToUpdate = [];
        $params = [];

        if ($nombre_usuario !== null) {
            $fieldsToUpdate[] = "nombre_usuario = ?";
            $params[] = $nombre_usuario;
        }

        if ($email_usuario !== null) {
            $fieldsToUpdate[] = "email_usuario = ?";
            $params[] = $email_usuario;
        }

        if ($contraseña !== null) {
            $fieldsToUpdate[] = "contraseña = ?";
            $params[] = $contraseña;
        }

        if ($rol !== null) {
            $fieldsToUpdate[] = "rol = ?";
            $params[] = $rol;
        }

        // Si no hay campos que actualizar, retornar false
        if (empty($fieldsToUpdate)) {
            return false;
        }

        // Agregar el ID del usuario al final de los parámetros
        $params[] = $id_usuario;

        // Combinar los campos a actualizar en la consulta SQL
        $query = "UPDATE Usuario SET " . implode(", ", $fieldsToUpdate) . " WHERE id_usuario = ?";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Ejecutar la consulta con los parámetros
        return $stmt->execute($params);
    }


    public function autenticarUsuario($email, $contraseña)
    {
        $query = "SELECT * FROM Usuario WHERE email_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);

        // Verificar si el usuario existe
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar la contraseña con password_verify
            if (password_verify($contraseña, $user['contraseña'])) {
                // Iniciar sesión si la contraseña es correcta
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['username'] = $user['nombre_usuario'];
                $_SESSION['email'] = $user['email_usuario'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['logged_in'] = true;

                return true;
            }
        }

        return false; // Usuario no encontrado o contraseña incorrecta
    }

    public function logoutUsuario()
    {
        session_unset();
        session_destroy();
        return true;
    }

    public function obtenerUsuarioLogeado()
    {
        // Asegúrate de que la sesión esté iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si el usuario está logeado
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            return [
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'rol' => $_SESSION['rol']
            ];
        }

        return null; // No hay usuario logeado
    }

    public function obtenerUsuarioPorEmail($email)
    {
        $query = "SELECT id_usuario, email_usuario, contraseña FROM Usuario WHERE email_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



}
