<?php
require_once 'config.php';

$error = "";

// Mostrar mensaje de éxito si viene del registro
$mensaje_registro = isset($_GET['registrado']) ? "¡Cuenta creada con éxito! Ya puedes iniciar sesión." : "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nombre'] = $user['nombre'];
        $_SESSION['usuario_rol'] = $user['rol']; 

        header("Location: index.php");
        exit;
    } else {
        $error = "El correo o la contraseña no son correctos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Aura Stock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f5f5f7; --text: #1d1d1f; --accent: #000000; --blue: #007aff; --gray: #86868b; }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #fff; 
            margin: 0; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh;
            color: var(--text);
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo { 
            font-size: 64px; 
            text-decoration: none; 
            color: var(--text); 
            margin-bottom: 10px; 
            display: inline-block;
            transition: transform 0.3s ease;
        }
        .logo:hover { transform: scale(1.05); }

        h1 { font-size: 40px; font-weight: 600; margin: 0 0 10px; letter-spacing: -1.5px; }
        p { color: var(--gray); font-size: 17px; margin-bottom: 40px; }

        .form-group { position: relative; width: 100%; margin-bottom: 15px; }

        input {
            width: 100%;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #d2d2d7;
            font-size: 16px;
            box-sizing: border-box;
            outline: none;
            background: #fff;
            transition: all 0.2s;
        }

        input:focus { 
            border-color: #000; 
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
        }

        .btn-login {
            width: 100%;
            background: #000;
            color: #fff;
            padding: 18px;
            border-radius: 14px;
            border: none;
            font-weight: 600;
            font-size: 17px;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .btn-login:hover { 
            background: #1d1d1f; 
            transform: translateY(-1px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .error-msg {
            color: #d70000;
            background: #fff2f2;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            border: 1px solid rgba(215, 0, 0, 0.1);
        }

        .success-msg {
            color: #28a745;
            background: #e8f5e9;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            border: 1px solid rgba(40, 167, 69, 0.1);
        }

        .footer-links { margin-top: 40px; font-size: 15px; color: var(--gray); }
        .footer-links a { color: var(--blue); text-decoration: none; font-weight: 600; }
        .footer-links a:hover { text-decoration: underline; }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            user-select: none;
        }
    </style>
</head>
<body>

<div class="login-container">
    <a href="index.php" class="logo"></a>
    <h1>Inicia sesión</h1>
    <p>Gestiona tus pedidos y mucho más.</p>

    <?php if ($error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($mensaje_registro): ?>
        <div class="success-msg"><?php echo $mensaje_registro; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <input type="email" name="email" placeholder="Correo electrónico" required autocomplete="email">
        </div>
        
        <div class="form-group">
            <input type="password" id="pass" name="password" placeholder="Contraseña" required autocomplete="current-password">
            <span class="toggle-password" onclick="togglePass()">Mostrar</span>
        </div>
        
        <button type="submit" class="btn-login">Continuar</button>
    </form>

    <div class="footer-links">
        ¿No tienes una cuenta? <a href="registro.php">Crea la tuya ahora.</a>
    </div>
</div>

<script>
    function togglePass() {
        const p = document.getElementById('pass');
        const btn = document.querySelector('.toggle-password');
        if (p.type === "password") {
            p.type = "text";
            btn.innerText = "Ocultar";
        } else {
            p.type = "password";
            btn.innerText = "Mostrar";
        }
    }
</script>

</body>
</html>