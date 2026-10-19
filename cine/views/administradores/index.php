<?php $titulo = 'Administradores'; require __DIR__ . '/../layout/header.php'; ?>

<div class="barra">
    <h1>Administradores</h1>
    <a class="btn" href="index.php?controller=administrador&action=crear">+ Nuevo administrador</a>
</div>

<table class="tabla">
    <thead>
        <tr><th>ID</th><th>Nombre</th><th>Username</th><th>Correo</th></tr>
    </thead>
    <tbody>
    <?php if (empty($administradores)): ?>
        <tr><td colspan="4">No hay administradores registrados.</td></tr>
    <?php else: foreach ($administradores as $a): ?>
        <tr>
            <td><?= e($a['id']) ?></td>
            <td><?= e($a['nombre']) ?></td>
            <td><?= e($a['username']) ?></td>
            <td><?= e($a['correo']) ?></td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layout/footer.php'; ?>
