<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!function_exists('e')) {
    function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'Cine') ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<nav class="menu">
    <span class="logo">🎬 Cine</span>
    <a href="index.php?controller=administrador&action=index">Administradores</a>
    <a href="index.php?controller=sala&action=index">Salas</a>
    <a href="index.php?controller=pelicula&action=index">Películas</a>
    <a href="index.php?controller=funcion&action=index">Funciones</a>
    <a href="index.php?controller=auth&action=logout" class="salir">Cerrar sesión</a>
</nav>
<main class="contenedor">
<?php if ($flash): ?>
    <div class="mensaje <?= $flash['tipo'] === 'exito' ? 'mensaje-exito' : 'mensaje-error' ?>">
        <?= e($flash['mensaje']) ?>
    </div>
<?php endif; ?>
