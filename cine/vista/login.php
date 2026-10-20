<?php
session_start();
// Si ya está logueado, ir directo al sistema
if (!empty($_SESSION["logueado"])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
</head>
<body>
    <h1>Iniciar sesión</h1>

    <?php if (isset($_GET["error"])): ?>
        <p style="color:red;">Datos incorrectos.</p>
    <?php endif; ?>

    <form action="../index.php" method="post">
        <input type="text" name="identificador" placeholder="Username" required> <br>
        <input type="password" name="password" placeholder="Password" required> <br>
        <input type="hidden" name="controlador" value="logincontrolador">
        <input type="hidden" name="accion" value="login">
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
