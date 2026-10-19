<?php
$campo = function (string $nombre) use ($datos) {
    return htmlspecialchars($datos[$nombre] ?? '', ENT_QUOTES, 'UTF-8');
};
$error = function (string $nombre) use ($errores) {
    return isset($errores[$nombre])
        ? '<small class="error">' . htmlspecialchars($errores[$nombre], ENT_QUOTES, 'UTF-8') . '</small>'
        : '';
};
?>
<h2>Nueva sala</h2>

<?php if (!empty($errores['general'])): ?>
    <p class="error"><?= htmlspecialchars($errores['general'], ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="post" action="?c=sala&amp;a=guardar" novalidate>
    <div>
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" maxlength="100" value="<?= $campo('nombre') ?>" required>
        <?= $error('nombre') ?>
    </div>

    <div>
        <label for="codigo">Código</label>
        <input type="text" id="codigo" name="codigo" maxlength="20" value="<?= $campo('codigo') ?>" required>
        <?= $error('codigo') ?>
    </div>

    <div>
        <label for="capacidad">Capacidad</label>
        <input type="number" id="capacidad" name="capacidad" min="1" step="1" value="<?= $campo('capacidad') ?>" required>
        <?= $error('capacidad') ?>
    </div>

    <button type="submit">Guardar</button>
    <a href="?c=sala&amp;a=index">Cancelar</a>
</form>
