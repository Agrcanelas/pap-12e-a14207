<?php
require_once 'config.php';

$mensaje = '';
$tipo = 'error';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');

    $stmt = $pdo->prepare("SELECT nombre FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $mensaje = "Se ha enviado un enlace de recuperación a " . htmlspecialchars($email);
        $tipo = 'exito';
    } else {
        $mensaje = "Este correo no está registrado en nuestro sistema.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding-top: 100px; background: #f5f5f7; }
        .card { background: white; display: inline-block; padding: 30px; border-radius: 15px; width: 300px; }
        .exito { color: green; } .error { color: red; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Recuperar acceso</h2>
        <?php if ($mensaje): ?>
            <p class="<?php echo $tipo; ?>"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Tu correo" required style="width:100%; padding:10px; margin-bottom:10px;">
            <button type="submit" style="width:100%; padding:10px; background:#000; color:#fff; border:none; border-radius:5px;">Enviar</button>
        </form>
        <br>
        <a href="login.php">Volver al login</a>
    </div>
</body>
</html>