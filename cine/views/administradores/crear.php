<?php $titulo = 'Nuevo administrador'; require __DIR__ . '/../layout/header.php'; ?>

<h1>Nuevo administrador</h1>

<?php if (!empty($errores)): ?>
    <div class="mensaje mensaje-error">
        <ul>
        <?php foreach ($errores as $err): ?>
            <li><?= e($err) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="formulario" method="POST" action="index.php?controller=administrador&action=guardar">
    <label>Nombre
        <input type="text" name="nombre" value="<?= e($old['nombre'] ?? '') ?>" required>
    </label>
    <label>Username
        <input type="text" name="username" value="<?= e($old['username'] ?? '') ?>" required>
    </label>
    <label>Contraseña (solo letras y números)
        <input type="password" name="password" pattern="[a-zA-Z0-9]+" required>
    </label>
    <label>Correo
        <input type="email" name="correo" value="<?= e($old['correo'] ?? '') ?>" required>
    </label>
    <div class="acciones">
        <button type="submit" class="btn">Guardar</button>
        <a class="btn btn-gris" href="index.php?controller=administrador&action=index">Cancelar</a>
    </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>
