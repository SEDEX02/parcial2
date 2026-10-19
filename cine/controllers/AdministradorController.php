<?php
require_once __DIR__ . '/../config/Database.php';   // Debe exponer Database::getConnection(): PDO
require_once __DIR__ . '/../models/Administrador.php';

class AdministradorController
{
    private Administrador $modelo;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->modelo = new Administrador(Database::getConnection());
    }

    public function index(): void
    {
        $administradores = $this->modelo->listar();
        require __DIR__ . '/../views/administradores/index.php';
    }

    public function crear(): void
    {
        $errores = $_SESSION['errores'] ?? [];
        $old     = $_SESSION['old'] ?? [];
        unset($_SESSION['errores'], $_SESSION['old']);
        require __DIR__ . '/../views/administradores/crear.php';
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('crear');
        }

        $nombre   = trim($_POST['nombre']   ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password']      ?? '';
        $correo   = trim($_POST['correo']   ?? '');
        $errores  = [];

        if ($nombre === '')   $errores[] = 'El nombre es obligatorio.';
        if ($username === '') $errores[] = 'El username es obligatorio.';
        if ($password === '') $errores[] = 'La contraseña es obligatoria.';
        if ($correo === '')   $errores[] = 'El correo es obligatorio.';

        if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo no tiene un formato válido.';
        }
        if ($password !== '' && !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            $errores[] = 'La contraseña solo puede contener letras y números.';
        }
        if ($username !== '' && $this->modelo->buscarPorUsername($username)) {
            $errores[] = 'El username ya está en uso.';
        }

        if ($errores) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old'] = ['nombre' => $nombre, 'username' => $username, 'correo' => $correo];
            $this->redirigir('crear');
        }

        try {
            $this->modelo->crear($nombre, $username, $password, $correo);
            $_SESSION['flash'] = ['tipo' => 'exito', 'mensaje' => 'Administrador creado correctamente.'];
            $this->redirigir('index');
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {   // violación de UNIQUE (carrera entre consulta e insert)
                $_SESSION['errores'] = ['El username ya está en uso.'];
            } else {
                $_SESSION['errores'] = ['Error al guardar en la base de datos.'];
            }
            $_SESSION['old'] = ['nombre' => $nombre, 'username' => $username, 'correo' => $correo];
            $this->redirigir('crear');
        }
    }

    private function redirigir(string $accion): void
    {
        header('Location: index.php?controller=administrador&action=' . $accion);
        exit;
    }
}
