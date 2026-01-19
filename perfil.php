<?php
require_once 'config.php';

// Bloque de seguridad
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$nombre = $_SESSION['usuario_nombre'];

// 1. Consultar datos del usuario
$stmt_user = $pdo->prepare("SELECT email FROM usuarios WHERE id = ?");
$stmt_user->execute([$usuario_id]);
$user_data = $stmt_user->fetch();
$email = $user_data['email'] ?? "Sin email";

// 2. Traer los pedidos reales
$stmt_pedidos = $pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC");
$stmt_pedidos->execute([$usuario_id]);
$mis_pedidos = $stmt_pedidos->fetchAll();

$cantidadCarrito = isset($_SESSION['carrito']) ? array_sum(array_column($_SESSION['carrito'], 'cantidad')) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Aura Stock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f5f5f7; --text: #1d1d1f; --accent: #000000; --blue: #007aff; --gray: #86868b; --red: #ff3b30; }
        body { font-family: 'Inter', sans-serif; background-color: #fff; margin: 0; padding-top: 100px; color: var(--text); -webkit-font-smoothing: antialiased; }

        header {
            position: fixed; top: 0; width: 100%; height: 65px;
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);
            display: flex; justify-content: space-around; align-items: center;
            z-index: 1000; border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .profile-container { max-width: 800px; margin: 0 auto; padding: 20px; animation: fadeIn 0.8s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .welcome-card {
            background: var(--bg);
            padding: 50px 40px;
            border-radius: 28px;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            gap: 30px;
        }
        .avatar-circle {
            width: 90px; height: 90px; background: var(--accent);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-size: 36px; font-weight: 600; color: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        h1 { font-size: 42px; font-weight: 600; margin: 0; letter-spacing: -2px; }
        .user-email { color: var(--gray); font-size: 17px; margin: 5px 0 15px; }

        .section-title { font-size: 28px; font-weight: 600; margin: 0 0 25px; letter-spacing: -1px; }
        
        .order-card {
            background: #fff;
            border: 1px solid #e2e2e7;
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .order-card:hover { transform: scale(1.01); border-color: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

        .order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }

        .order-status {
            font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
            padding: 6px 16px; border-radius: 20px;
        }
        .status-pendiente { background: #fef3c7; color: #92400e; }
        .status-entregado { background: #dcfce7; color: #166534; }

        .btn-support {
            display: inline-block; padding: 12px 24px; border-radius: 12px;
            background: #000; color: #fff; text-decoration: none;
            font-weight: 600; font-size: 14px; transition: 0.2s;
        }
        .btn-support:hover { opacity: 0.8; }

        .empty-state { text-align: center; padding: 80px 20px; border: 2px dashed #f5f5f7; border-radius: 28px; }
        
        footer { margin-top: 100px; padding: 60px; text-align: center; color: var(--gray); font-size: 13px; }
    </style>
</head>
<body>

<header>
    <a href="index.php" style="text-decoration: none; color: #000; font-weight: 600; font-size: 19px;"> Aura Stock</a>
    <nav>
        <a href="index.php" style="text-decoration: none; color: var(--text); margin-right: 25px; font-size: 14px;">Tienda</a>
        <a href="carrito.php" style="text-decoration: none; color: #fff; background: #000; padding: 6px 14px; border-radius: 20px; font-size: 13px;">🛒 <?php echo $cantidadCarrito; ?></a>
    </nav>
</header>

<div class="profile-container">
    <div class="welcome-card">
        <div class="avatar-circle">
            <?php echo strtoupper(substr($nombre, 0, 1)); ?>
        </div>
        <div>
            <h1>Hola, <?php echo htmlspecialchars($nombre); ?>.</h1>
            <p class="user-email"><?php echo htmlspecialchars($email); ?></p>
            <a href="logout.php" style="color: var(--red); text-decoration: none; font-size: 14px; font-weight: 600;" onclick="return confirm('¿Seguro que quieres salir?')">Cerrar sesión</a>
        </div>
    </div>

    <h2 class="section-title">Tus pedidos</h2>

    <?php if (empty($mis_pedidos)): ?>
        <div class="empty-state">
            <p style="font-size: 18px; color: var(--gray); margin-bottom: 20px;">Aún no has realizado ninguna compra.</p>
            <a href="index.php" style="color: var(--blue); text-decoration: none; font-weight: 600;">Empezar a comprar ➔</a>
        </div>
    <?php else: ?>
        <?php foreach ($mis_pedidos as $pedido): ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <p style="margin: 0; font-weight: 600; font-size: 18px; letter-spacing: -0.5px;">Referencia: AS-<?php echo $pedido['id']; ?></p>
                        <p style="margin: 5px 0 0; font-size: 14px; color: var(--gray);">
                            Realizado el <?php echo date('d/m/Y', strtotime($pedido['fecha'])); ?>
                        </p>
                    </div>
                    <span class="order-status <?php echo (isset($pedido['estado']) && $pedido['estado'] == 'Entregado') ? 'status-entregado' : 'status-pendiente'; ?>">
                        <?php echo $pedido['estado'] ?? 'Procesando'; ?>
                    </span>
                </div>
                
                <div style="margin-top: 20px; border-top: 1px solid #f5f5f7; padding-top: 25px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="margin: 0; font-size: 13px; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px;">Total pagado</p>
                        <p style="margin: 5px 0 0; font-weight: 600; font-size: 24px; letter-spacing: -1px;"><?php echo number_format($pedido['total'], 2, ',', '.'); ?> €</p>
                    </div>
                    <a href="mailto:soporte@aurastock.com?subject=Ayuda pedido AS-<?php echo $pedido['id']; ?>" class="btn-support">Ayuda con mi pedido</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<footer>
    <div style="font-size: 24px; color: #000; margin-bottom: 20px;"></div>
    <p>Aura Stock — Excelencia Tecnológica 2026</p>
    <p style="opacity: 0.5; margin-top: 10px;">Privacidad y Seguridad garantizada.</p>
</footer>

</body>
</html>