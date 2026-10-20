<?php
$datos = $resp['datos'] ?? ['codigo'=>'','id_sala'=>'','id_pelicula'=>'','fecha_hora'=>''];
$errores = $resp['errores'] ?? [];
$salas = $resp['salas'] ?? [];
$peliculas = $resp['peliculas'] ?? [];
$e = static fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<section>
    <div class="barra"><h1>Registrar función</h1><a class="btn btn-gris" href="index.php?a=funlis">Ver funciones</a></div>
    <?php if (!empty($errores['general'])): ?><div class="mensaje mensaje-error" role="alert"><?= $e($errores['general']) ?></div><?php endif; ?>
    <?php if (!$salas || !$peliculas): ?><div class="mensaje mensaje-error">Primero debe registrar al menos una sala y una película.</div><?php endif; ?>
    <form method="post" action="index.php" class="formulario">
        <input type="hidden" name="controlador" value="funcioncontrolador"><input type="hidden" name="accion" value="crearFuncion"><input type="hidden" name="vista1" value="addFuncionVista"><input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf'] ?? '') ?>">
        <label for="codigo">Código de la función<input id="codigo" name="codigo" maxlength="30" pattern="[A-Za-z0-9]+" required value="<?= $e($datos['codigo'] ?? '') ?>"></label>
        <?php if (!empty($errores['codigo'])): ?><div class="mensaje mensaje-error"><?= $e($errores['codigo']) ?></div><?php endif; ?>
        <label for="id_pelicula">Película<select id="id_pelicula" name="id_pelicula" required><option value="">Seleccione una película</option><?php foreach ($peliculas as $p): ?><option value="<?= (int)$p['id'] ?>" <?= (string)($datos['id_pelicula'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>><?= $e($p['nombre']) ?> (<?= $e($p['codigo']) ?>) — <?= $e($p['clasificacion']) ?></option><?php endforeach; ?></select></label>
        <?php if (!empty($errores['id_pelicula'])): ?><div class="mensaje mensaje-error"><?= $e($errores['id_pelicula']) ?></div><?php endif; ?>
        <label for="id_sala">Sala<select id="id_sala" name="id_sala" required><option value="">Seleccione una sala</option><?php foreach ($salas as $s): ?><option value="<?= (int)$s['id'] ?>" <?= (string)($datos['id_sala'] ?? '') === (string)$s['id'] ? 'selected' : '' ?>><?= $e($s['nombre']) ?> (<?= $e($s['codigo']) ?>)</option><?php endforeach; ?></select></label>
        <?php if (!empty($errores['id_sala'])): ?><div class="mensaje mensaje-error"><?= $e($errores['id_sala']) ?></div><?php endif; ?>
        <label for="fecha_hora">Fecha y hora<input id="fecha_hora" name="fecha_hora" type="datetime-local" required value="<?= $e($datos['fecha_hora'] ?? '') ?>"></label>
        <?php if (!empty($errores['fecha_hora'])): ?><div class="mensaje mensaje-error"><?= $e($errores['fecha_hora']) ?></div><?php endif; ?>
        <div class="acciones"><button class="btn" type="submit" <?= (!$salas || !$peliculas) ? 'disabled' : '' ?>>Guardar función</button><a class="btn btn-gris" href="index.php?a=funlis">Cancelar</a></div>
    </form>
</section>
