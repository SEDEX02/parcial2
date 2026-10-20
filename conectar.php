<?php
class conectar {

    public static function conexion() {
        try {
            $conexion = new PDO(
                'mysql:host=localhost;dbname=cine;charset=utf8mb4',
                'root',
                '',
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            // No mostrar detalles al usuario; registrar en el log del servidor
            error_log("Error de conexión: " . $e->getMessage());
            die("Error al conectar con la base de datos.");
        }
        return $conexion;
    }
}
