<h2>Salas</h2>

<p><a class="btn" href="?c=sala&amp;a=crear">Nueva sala</a></p>

<?php if (empty($salas)): ?>
    <p>No hay salas registradas.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Capacidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salas as $sala): ?>
                <tr>
                    <td><?= htmlspecialchars($sala['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($sala['codigo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $sala['capacidad'] ?></td>
                    <td>
                        <form method="post" action="?c=sala&amp;a=eliminar" style="display:inline"
                              onsubmit="return confirm('¿Seguro que desea eliminar esta sala?');">
                            <input type="hidden" name="id" value="<?= (int) $sala['id'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
