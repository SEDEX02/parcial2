<h2>Películas</h2>

<p><a class="btn" href="?c=pelicula&amp;a=crear">Nueva película</a></p>

<?php if (empty($peliculas)): ?>
    <p>No hay películas registradas.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Clasificación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peliculas as $pelicula): ?>
                <tr>
                    <td><?= htmlspecialchars($pelicula['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($pelicula['codigo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($pelicula['clasificacion'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <form method="post" action="?c=pelicula&amp;a=eliminar" style="display:inline"
                              onsubmit="return confirm('¿Seguro que desea eliminar esta película?');">
                            <input type="hidden" name="id" value="<?= (int) $pelicula['id'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
