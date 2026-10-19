<?php
class Administrador
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Inserta un administrador. Lanza PDOException (código 23000 si username duplicado). */
    public function crear(string $nombre, string $username, string $password, string $correo): bool
    {
        $sql = "INSERT INTO administradores (nombre, username, password, correo)
                VALUES (:nombre, :username, :password, :correo)";
        $st = $this->db->prepare($sql);
        return $st->execute([
            ':nombre'   => $nombre,
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':correo'   => $correo,
        ]);
    }

    /** Lista sin incluir la contraseña. */
    public function listar(): array
    {
        $st = $this->db->query("SELECT id, nombre, username, correo FROM administradores ORDER BY id DESC");
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Devuelve la fila completa (incluye hash, útil para el login) o null. */
    public function buscarPorUsername(string $username): ?array
    {
        $st = $this->db->prepare("SELECT * FROM administradores WHERE username = :u LIMIT 1");
        $st->execute([':u' => $username]);
        $fila = $st->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }
}
