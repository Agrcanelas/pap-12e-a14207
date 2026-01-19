<?php
require_once 'config.php';

if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$nombre_usuario = htmlspecialchars($_SESSION['usuario_nombre']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - <?php echo $nombre_tienda; ?></title>
    <style>
        body { font-family: sans-serif; background: #f5f5f7; margin: 0; padding: 40px; }
        .dashboard { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 20px; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background: #0071e3; color: white; text-decoration: none; border-radius: 8px; margin: 10px; }
        .btn-danger { background: #d70015; }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Hola, <?php echo $nombre_usuario; ?></h1>
        <p>Bienvenido a tu panel de control.</p>
        <hr>
        <a href="index.php" class="btn">Ir a la tienda</a>
        <a href="recuperar.php" class="btn">Cambiar contraseña</a>
        <a href="?logout=1" class="btn btn-danger">Cerrar sesión</a>
    </div>
</body>
</html>