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
<h2>Nueva película</h2>

<?php if (!empty($errores['general'])): ?>
    <p class="error"><?= htmlspecialchars($errores['general'], ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="post" action="?c=pelicula&amp;a=guardar" novalidate>
    <div>
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" maxlength="150" value="<?= $campo('nombre') ?>" required>
        <?= $error('nombre') ?>
    </div>

    <div>
        <label for="codigo">Código</label>
        <input type="text" id="codigo" name="codigo" maxlength="20" value="<?= $campo('codigo') ?>" required>
        <?= $error('codigo') ?>
    </div>

    <div>
        <label for="clasificacion">Clasificación</label>
        <select id="clasificacion" name="clasificacion" required>
            <option value="">-- Seleccione --</option>
            <?php foreach ($clasificaciones as $opcion): ?>
                <option value="<?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?>"
                    <?= ($datos['clasificacion'] ?? '') === $opcion ? 'selected' : '' ?>>
                    <?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?= $error('clasificacion') ?>
    </div>

    <button type="submit">Guardar</button>
    <a href="?c=pelicula&amp;a=index">Cancelar</a>
</form>
