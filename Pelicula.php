<?php
require_once __DIR__ . '/../core/Model.php';

class Pelicula extends Model
{
    public const CLASIFICACIONES = ['TP', '7', '12', '15', '18'];

    public function listar(): array
    {
        $sql = "SELECT id, nombre, codigo, clasificacion FROM peliculas ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorCodigo(string $codigo): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nombre, codigo, clasificacion FROM peliculas WHERE codigo = :codigo");
        $stmt->execute([':codigo' => $codigo]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nombre, codigo, clasificacion FROM peliculas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    public function crear(string $nombre, string $codigo, string $clasificacion): bool
    {
        $sql = "INSERT INTO peliculas (nombre, codigo, clasificacion) VALUES (:nombre, :codigo, :clasificacion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'        => $nombre,
            ':codigo'        => $codigo,
            ':clasificacion' => $clasificacion,
        ]);
    }

    public function contarFunciones(int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM funciones WHERE pelicula_id = :id");
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn();
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM peliculas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
