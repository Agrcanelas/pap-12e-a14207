<?php
require_once 'config.php';

if (!function_exists('formatear_precio')) {
    function formatear_precio($cantidad) {
        return number_format($cantidad, 2, ',', '.') . ' €';
    }
}

$items_detallados = [];
$subtotal_carrito = 0;

// Eliminar un item
if (isset($_GET['eliminar'])) {
    $key_a_eliminar = $_GET['eliminar'];
    if (isset($_SESSION['carrito'][$key_a_eliminar])) {
        unset($_SESSION['carrito'][$key_a_eliminar]);
    }
    header("Location: carrito.php");
    exit;
}

// Vaciar carrito
if (isset($_GET['vaciar'])) {
    unset($_SESSION['carrito']);
    unset($_SESSION['descuento']); // También vaciamos el descuento
    header("Location: carrito.php");
    exit;
}

// Quitar cupón manualmente
if (isset($_GET['quitar_cupon'])) {
    unset($_SESSION['descuento']);
    header("Location: carrito.php");
    exit;
}

if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $key => $datos) {
        $id = $datos['id'];
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            $fila_subtotal = $producto['precio'] * $datos['cantidad'];
            $subtotal_carrito += $fila_subtotal;
            $items_detallados[] = [
                'key' => $key, 
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'imagen' => $producto['imagen'],
                'color' => $datos['color'],
                'cantidad' => $datos['cantidad'],
                'subtotal' => $fila_subtotal
            ];
        }
    }
}

// Lógica de Descuento
$porcentaje_descuento = $_SESSION['descuento'] ?? 0;
$monto_descuento = $subtotal_carrito * ($porcentaje_descuento / 100);
$total_final = $subtotal_carrito - $monto_descuento;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Bolsa - Aura Stock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f5f5f7; --text: #1d1d1f; --accent: #000; --blue: #007aff; --gray: #86868b; --red: #ff3b30; }
        body { font-family: 'Inter', sans-serif; background-color: #fff; margin: 0; padding-top: 100px; color: var(--text); -webkit-font-smoothing: antialiased; }

        header {
            position: fixed; top: 0; width: 100%; height: 65px;
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);
            display: flex; justify-content: space-around; align-items: center;
            z-index: 1000; border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .cart-container { max-width: 840px; margin: 0 auto; padding: 20px; animation: fadeIn 0.8s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .cart-header { border-bottom: 1px solid #d2d2d7; padding-bottom: 20px; margin-bottom: 10px; }
        h1 { font-size: 40px; font-weight: 600; margin: 0; letter-spacing: -1.5px; }
        
        .cart-item {
            display: grid; grid-template-columns: 140px 1fr 120px; gap: 30px;
            padding: 40px 0; border-bottom: 1px solid #e2e2e7; align-items: start;
        }
        .item-img img { width: 140px; height: 140px; object-fit: contain; background: var(--bg); border-radius: 18px; }
        
        .item-info h2 { font-size: 22px; margin: 0 0 8px; font-weight: 600; }
        .item-info p { color: var(--gray); margin: 4px 0; font-size: 15px; }
        
        .btn-eliminar { color: var(--blue); text-decoration: none; font-size: 14px; font-weight: 500; display: inline-block; margin-top: 15px; }

        .item-price { text-align: right; font-size: 20px; font-weight: 600; letter-spacing: -0.5px; }

        .summary-box { background: var(--bg); border-radius: 24px; padding: 35px; margin-top: 40px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 16px; }
        .total-row { font-size: 26px; font-weight: 600; border-top: 1px solid #d2d2d7; padding-top: 20px; margin-top: 10px; }
        
        /* CUPONES ESTILO APPLE */
        .coupon-section { background: #fff; padding: 20px; border-radius: 18px; margin-bottom: 25px; border: 1px solid #d2d2d7; }
        .coupon-form { display: flex; gap: 10px; margin-top: 10px; }
        .coupon-form input { flex-grow: 1; padding: 12px; border-radius: 10px; border: 1px solid #d2d2d7; font-size: 14px; outline: none; }
        .coupon-form button { background: var(--accent); color: #fff; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; font-weight: 600; }
        .coupon-active { display: flex; justify-content: space-between; align-items: center; color: #34c759; font-weight: 600; font-size: 14px; }

        .btn-checkout {
            background: var(--accent); color: white; padding: 20px;
            border-radius: 14px; border: none; font-weight: 600; font-size: 17px;
            cursor: pointer; text-decoration: none; display: block; text-align: center;
            margin-top: 30px; transition: all 0.3s;
        }
        .btn-checkout:hover { background: #1d1d1f; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        
        .empty-cart { text-align: center; padding: 120px 20px; }
        .btn-vaciar { color: var(--gray); text-decoration: none; font-size: 13px; transition: color 0.2s; }
        .btn-vaciar:hover { color: var(--red); }
    </style>
</head>
<body>

<header>
    <a href="index.php" style="text-decoration: none; color: #000; font-weight: 600; font-size: 19px;"> Aura Stock</a>
    <nav><a href="index.php" style="text-decoration: none; color: var(--blue); font-size: 14px; font-weight: 500;">Seguir comprando</a></nav>
</header>

<div class="cart-container">
    <?php if (empty($items_detallados)): ?>
        <div class="empty-cart">
            <h1>Tu bolsa está vacía.</h1>
            <p style="color: var(--gray); margin: 20px 0 40px; font-size: 18px;">Encuentra algo que te guste y añádelo aquí.</p>
            <a href="index.php" class="btn-checkout" style="display: inline-block; padding: 15px 40px;">Ver productos</a>
        </div>
    <?php else: ?>
        <div class="cart-header">
            <h1>Tu bolsa.</h1>
            <p style="color: var(--gray); margin-top: 10px;">El envío es gratuito en todos los pedidos.</p>
        </div>
        
        <?php foreach ($items_detallados as $item): ?>
            <div class="cart-item">
                <div class="item-img">
                    <img src="img/<?php echo $item['imagen']; ?>.jpg" alt="">
                </div>
                <div class="item-info">
                    <h2><?php echo htmlspecialchars($item['nombre']); ?></h2>
                    <p>Color: <?php echo htmlspecialchars($item['color']); ?></p>
                    <p>Cantidad: <?php echo $item['cantidad']; ?></p>
                    <a href="carrito.php?eliminar=<?php echo $item['key']; ?>" class="btn-eliminar">Eliminar</a>
                </div>
                <div class="item-price">
                    <?php echo formatear_precio($item['subtotal']); ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="summary-box">
            <div class="coupon-section">
                <?php if($porcentaje_descuento > 0): ?>
                    <div class="coupon-active">
                        <span>✅ Cupón del <?php echo $porcentaje_descuento; ?>% aplicado</span>
                        <a href="carrito.php?quitar_cupon=1" style="color: var(--red); text-decoration:none; font-size: 12px;">Eliminar</a>
                    </div>
                <?php else: ?>
                    <span style="font-size: 14px; font-weight: 600;">¿Tienes un código de descuento?</span>
                    <form action="aplicar_cupon.php" method="POST" class="coupon-form">
                        <input type="text" name="codigo_cupon" placeholder="Código" required>
                        <button type="submit">Aplicar</button>
                    </form>
                    <?php if(isset($_GET['error']) && $_GET['error'] == 'cupon_invalido'): ?>
                        <p style="color: var(--red); font-size: 12px; margin-top: 10px;">El código no es válido o ha expirado.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="summary-row">
                <span>Subtotal</span>
                <span><?php echo formatear_precio($subtotal_carrito); ?></span>
            </div>
            
            <?php if($porcentaje_descuento > 0): ?>
            <div class="summary-row" style="color: #34c759;">
                <span>Descuento (<?php echo $porcentaje_descuento; ?>%)</span>
                <span>- <?php echo formatear_precio($monto_descuento); ?></span>
            </div>
            <?php endif; ?>

            <div class="summary-row">
                <span>Envío</span>
                <span style="color: #34c759; font-weight: 600;">Gratis</span>
            </div>
            <div class="total-row summary-row">
                <span>Total</span>
                <span><?php echo formatear_precio($total_final); ?></span>
            </div>
            
            <a href="finalizar_pedido.php" class="btn-checkout">Finalizar compra</a>
            
            <div style="text-align: center; margin-top: 25px;">
                <a href="carrito.php?vaciar=1" class="btn-vaciar" onclick="return confirm('¿Vaciar toda la bolsa?')">Vaciar bolsa de compra</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer style="padding: 80px 0; text-align: center; color: var(--gray); font-size: 13px;">
    <div style="font-size: 22px; color: #000; margin-bottom: 15px;"></div>
    <p>© 2026 Aura Stock. Pago seguro con encriptación SSL.</p>
</footer>

</body>
</html>