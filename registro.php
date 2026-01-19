<?php
require_once 'config.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $mensaje = "Este correo electrónico ya está registrado.";
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $insert = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        if ($insert->execute([$nombre, $email, $password_hash])) {
            header("Location: login.php?registrado=1");
            exit;
        } else {
            $mensaje = "Hubo un error al crear la cuenta. Inténtalo de nuevo.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Aura Stock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f5f5f7; --text: #1d1d1f; --accent: #000000; --gray: #86868b; }
        
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

        .register-container {
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
        }

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

        /* BOTÓN PERSONALIZADO: NEGRO CON LETRAS BLANCAS */
        .btn-register {
            width: 100%;
            background: #000000;
            color: #ffffff;
            padding: 18px;
            border-radius: 14px;
            border: none;
            font-weight: 600;
            font-size: 17px;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .btn-register:hover { 
            background: #1d1d1f; 
            transform: translateY(-1px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .btn-register:active { transform: translateY(0); }

        .error-msg {
            color: #d70000;
            background: #fff2f2;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .footer-links { margin-top: 40px; font-size: 15px; color: var(--gray); }
        .footer-links a { color: #007aff; text-decoration: none; font-weight: 600; }
        
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

<div class="register-container">
    <a href="index.php" class="logo"></a>
    <h1>Crea tu cuenta</h1>
    <p>Únete a Aura Stock.</p>

    <?php if ($mensaje): ?>
        <div class="error-msg"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form action="registro.php" method="POST">
        <div class="form-group">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
        </div>
        
        <div class="form-group">
            <input type="email" name="email" placeholder="Correo electrónico" required>
        </div>
        
        <div class="form-group">
            <input type="password" id="pass" name="password" placeholder="Contraseña" required>
            <span class="toggle-password" onclick="togglePass()">Mostrar</span>
        </div>
        
        <button type="submit" class="btn-register">Continuar</button>
    </form>

    <div class="footer-links">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
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