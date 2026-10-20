<header>
    <h1>Menu</h1>
    <nav>
        <ul>
            <li><a href="index.php?a=admlis">Administradores</a></li>
            <li><a href="index.php?a=sallis">Salas</a></li>
            <li><a href="index.php?a=pellis">Películas</a></li>
            <li><a href="index.php?a=funlis">Funciones</a></li>
            <li>
                <form action="index.php" method="post" style="display:inline;">
                    <input type="hidden" name="logout" value="1">
                    <input type="hidden" name="csrf"
                           value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
                    <button type="submit">Cerrar sesión (<?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? '') ?>)</button>
                </form>
            </li>
        </ul>
    </nav>
</header>
