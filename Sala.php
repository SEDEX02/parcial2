<?php
require_once __DIR__ . '/../core/Model.php';

class Sala extends Model
{
    public function listar(): array
    {
        $sql = "SELECT id, nombre, codigo, capacidad FROM salas ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorCodigo(string $codigo): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nombre, codigo, capacidad FROM salas WHERE codigo = :codigo");
        $stmt->execute([':codigo' => $codigo]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nombre, codigo, capacidad FROM salas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    public function crear(string $nombre, string $codigo, int $capacidad): bool
    {
        $sql = "INSERT INTO salas (nombre, codigo, capacidad) VALUES (:nombre, :codigo, :capacidad)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'    => $nombre,
            ':codigo'    => $codigo,
            ':capacidad' => $capacidad,
        ]);
    }

    public function contarFunciones(int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM funciones WHERE sala_id = :id");
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn();
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM salas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
