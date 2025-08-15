<?php
class Database
{
    private static $host = "sql5.freesqldatabase.com";  // El host de la base de datos
    private static $db_name = "sql5795066";  // Cambia esto por el nombre de tu base de datos
    private static $username = "sql5795066";  // Cambia esto por el usuario de tu base de datos
    private static $password = "S5XzaER2Vt";  // Cambia esto por la contraseña de tu base de datos
    private static $conn;

    // Método para obtener la conexión a la base de datos
    public static function getConnection()
    {
        self::$conn = null;

        try {
            // Crear una nueva conexión usando PDO con charset utf8mb4
            self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4", self::$username, self::$password);
            // Establecer el modo de error de PDO para lanzar excepciones
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            // En caso de error, se captura la excepción y se muestra el mensaje de error
            echo "Error de conexión: " . $exception->getMessage();
        }

        return self::$conn;
    }
}
?>