<?php
class Database {
    public static function getConnection(): PDO {
        $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        // Crea la base y la tabla automáticamente si no existen
        $pdo = new PDO('mysql:host=127.0.0.1;charset=utf8mb4', 'root', '', $opts);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS cine CHARACTER SET utf8mb4");
        $pdo->exec("USE cine");
        $pdo->exec("CREATE TABLE IF NOT EXISTS administradores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            correo VARCHAR(100) NOT NULL
        )");
        return $pdo;
    }
}
