<?php
$flash = $_SESSION['flash_funcion'] ?? null;
unset($_SESSION['flash_funcion']);
$funciones = $resp['funciones'] ?? [];
$codigoBuscar = $resp['codigo_buscar'] ?? '';
$busquedaRealizada = $resp['busqueda_realizada'] ?? false;
$e = static fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>
<section>
    <div class="barra"><h1>Funciones de cine</h1><a class="btn" href="index.php?a=funcre">+ Registrar función</a></div>
    <?php if ($flash): ?><div class="mensaje <?= $flash['tipo'] === 'exito' ? 'mensaje-exito' : 'mensaje-error' ?>" role="status"><?= $e($flash['mensaje']) ?></div><?php endif; ?>
    <form method="get" action="index.php" class="formulario"><label for="codigo_buscar">Buscar por código<input id="codigo_buscar" name="codigo_buscar" maxlength="30" value="<?= $e($codigoBuscar) ?>"></label><div class="acciones"><button class="btn" type="submit" name="a" value="funlis">Buscar</button><a class="btn btn-gris" href="index.php?a=funlis">Limpiar</a></div></form>
    <?php if (!$funciones): ?><p><?= $busquedaRealizada ? 'No se encontró una función con ese código.' : 'No hay funciones registradas. Registre primero una sala y una película.' ?></p><?php else: ?>
    <div style="overflow-x:auto"><table class="tabla"><thead><tr><th>Código</th><th>Película</th><th>Sala</th><th>Fecha y hora</th><th>Acciones</th></tr></thead><tbody>
    <?php foreach ($funciones as $f): ?><tr><td><?= $e($f['codigo']) ?></td><td><?= $e($f['nombre_pelicula']) ?> (<?= $e($f['codigo_pelicula']) ?>)</td><td><?= $e($f['nombre_sala']) ?> (<?= $e($f['codigo_sala']) ?>)</td><td><?= $e(date('d/m/Y H:i', strtotime($f['fecha_hora']))) ?></td><td><form method="post" action="index.php" onsubmit="return confirm('¿Está seguro de eliminar esta función?');"><input type="hidden" name="controlador" value="funcioncontrolador"><input type="hidden" name="accion" value="eliminarFuncion"><input type="hidden" name="vista1" value="listFuncionVista"><input type="hidden" name="id" value="<?= (int)$f['id'] ?>"><input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf'] ?? '') ?>"><button class="btn btn-gris" type="submit">Eliminar</button></form></td></tr><?php endforeach; ?>
    </tbody></table></div><?php endif; ?>
</section>
