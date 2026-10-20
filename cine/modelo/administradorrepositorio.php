<?php
require_once __DIR__ . "/conectar.php";

class administradorrepositorio {
    private PDO $conexion;

    public function __construct() {
        $this->conexion = conectar::conexion();
    }

    /**
     * Devuelve los datos del administrador (sin la contraseña) si las
     * credenciales son correctas; si no, false.
     */
    public function login(string $username, string $password) {
        $sql = "SELECT * FROM administradores WHERE username = :username LIMIT 1";
        $sentencia = $this->conexion->prepare($sql);
        $sentencia->bindValue(":username", $username);
        $sentencia->execute();
        $administrador = $sentencia->fetch(PDO::FETCH_ASSOC);

        if (!$administrador) {
            return false;
        }

        if (!password_verify($password, $administrador["password"])) {
            return false;
        }

        unset($administrador["password"]);
        return $administrador;
    }

    /* ---------- NESTOR: agregar aquí crear(), listar() y buscarPorUsername() ---------- */
}
