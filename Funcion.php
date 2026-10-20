<?php
require_once __DIR__ . '/../config/Database.php';

class Funcion
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function listar(): array
    {
        $sql = "SELECT f.id, f.codigo, f.fecha_hora, s.nombre AS nombre_sala, s.codigo AS codigo_sala, p.nombre AS nombre_pelicula, p.codigo AS codigo_pelicula
                FROM funciones f
                INNER JOIN salas s ON s.id = f.id_sala
                INNER JOIN peliculas p ON p.id = f.id_pelicula
                ORDER BY f.fecha_hora ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorCodigo(string $codigo): ?array
    {
        $stmt = $this->db->prepare("SELECT f.id, f.codigo, f.fecha_hora, f.id_sala, f.id_pelicula, s.nombre AS nombre_sala, s.codigo AS codigo_sala, p.nombre AS nombre_pelicula, p.codigo AS codigo_pelicula FROM funciones f INNER JOIN salas s ON s.id=f.id_sala INNER JOIN peliculas p ON p.id=f.id_pelicula WHERE f.codigo=:codigo LIMIT 1");
        $stmt->execute([':codigo' => $codigo]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function listarSalas(): array
    {
        return $this->db->query('SELECT id, codigo, nombre FROM salas ORDER BY nombre')->fetchAll();
    }

    public function listarPeliculas(): array
    {
        return $this->db->query('SELECT id, codigo, nombre, clasificacion FROM peliculas ORDER BY nombre')->fetchAll();
    }

    public function existeSala(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM salas WHERE id=:id');
        $stmt->execute([':id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function existePelicula(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM peliculas WHERE id=:id');
        $stmt->execute([':id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function existeConflictoSala(int $idSala, string $fechaHora): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM funciones WHERE id_sala=:id_sala AND fecha_hora=:fecha_hora');
        $stmt->execute([':id_sala' => $idSala, ':fecha_hora' => $fechaHora]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function crear(string $codigo, int $idSala, int $idPelicula, string $fechaHora): bool
    {
        $stmt = $this->db->prepare('INSERT INTO funciones (codigo,id_sala,id_pelicula,fecha_hora) VALUES (:codigo,:id_sala,:id_pelicula,:fecha_hora)');
        return $stmt->execute([':codigo'=>$codigo, ':id_sala'=>$idSala, ':id_pelicula'=>$idPelicula, ':fecha_hora'=>$fechaHora]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM funciones WHERE id=:id');
        return $stmt->execute([':id'=>$id]);
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id,codigo FROM funciones WHERE id=:id');
        $stmt->execute([':id'=>$id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }
}
