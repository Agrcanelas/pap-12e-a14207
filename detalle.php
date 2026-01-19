<?php
require_once 'config.php';

// Función de ayuda
if (!function_exists('formatear_precio')) {
    function formatear_precio($cantidad) {
        return number_format($cantidad, 2, ',', '.') . ' €';
    }
}

// 1. Validar ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header("Location: index.php"); exit; }

// 2. Obtener producto (Asegúrate de que 'stock' esté en tu tabla)
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) { die("Producto no encontrado."); }

// Calcular cantidad del carrito
$cantidadCarrito = isset($_SESSION['carrito']) ? array_sum(array_column($_SESSION['carrito'], 'cantidad')) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($p['nombre']); ?> - Aura Stock</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f5f5f7; --text: #1d1d1f; --accent: #000000; --blue: #007aff; --gray: #86868b; --red: #d32f2f; }
        body { font-family: 'Inter', sans-serif; background-color: #fff; margin: 0; padding-top: 100px; color: var(--text); -webkit-font-smoothing: antialiased; }

        /* HEADER */
        header {
            position: fixed; top: 0; width: 100%; height: 65px;
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);
            display: flex; justify-content: space-around; align-items: center;
            z-index: 1000; border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .logo-container { display: flex; align-items: center; text-decoration: none; color: var(--text); }
        .brand-name { font-size: 19px; font-weight: 600; margin-left: 6px; letter-spacing: -0.5px; }
        .nav-links { display: flex; align-items: center; gap: 20px; }
        .nav-item { text-decoration: none; color: var(--text); font-size: 13px; }
        .btn-admin-pill { color: #000; font-weight: 600; border: 1px solid #000; padding: 5px 12px; border-radius: 10px; text-decoration: none; font-size: 12px; }

        /* CONTENEDOR */
        .container-detail { max-width: 1000px; margin: 0 auto; padding: 20px; animation: fadeIn 0.8s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .product-wrapper { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 60px; align-items: start; }

        /* VISOR DE IMAGEN */
        .image-viewer { 
            background: var(--bg); border-radius: 30px; padding: 60px; 
            display: flex; justify-content: center; align-items: center;
            height: 500px; position: sticky; top: 120px;
        }
        .image-viewer img { max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.5s ease; }

        /* PANEL DE INFO */
        .info-panel h1 { font-size: 48px; margin: 0 0 15px; letter-spacing: -2px; font-weight: 600; }
        .price-tag { font-size: 24px; margin-bottom: 40px; font-weight: 400; color: var(--text); }
        .option-group { border-top: 1px solid #e2e2e7; padding: 25px 0; }
        .option-title { font-weight: 600; margin-bottom: 12px; display: block; font-size: 14px; }
        
        .custom-select {
            width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #d2d2d7;
            font-size: 16px; background: #fff; appearance: none; outline: none;
        }

        .btn-buy {
            width: 100%; background: #000; color: white; padding: 18px;
            border-radius: 14px; border: none; font-weight: 600; font-size: 17px; cursor: pointer;
            transition: all 0.3s ease; margin-top: 10px;
        }
        .btn-buy:hover { background: #1d1d1f; transform: translateY(-2px); }

        /* ESTILO AGOTADO */
        .out-of-stock-card {
            background: #fdf2f2; border: 1px solid #f8d7da; padding: 25px;
            border-radius: 18px; text-align: center; margin-top: 10px;
        }

        .alert-success { background: #f5f5f7; color: #000; padding: 20px; border-radius: 18px; margin-bottom: 30px; text-align: center; border: 1px solid #d2d2d7; font-size: 14px; }

        @media (max-width: 850px) { .product-wrapper { grid-template-columns: 1fr; } .image-viewer { height: 350px; position: relative; top: 0; } }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="logo-container">
        <span style="font-size: 22px;"></span>
        <span class="brand-name">Aura Stock</span>
    </a>
    <nav class="nav-links">
        <?php if(isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
            <a href="admin.php" class="btn-admin-pill">Gestionar</a>
        <?php endif; ?>
        <a href="index.php" class="nav-item">Tienda</a>
        <a href="carrito.php" class="nav-item" style="background: #000; color: #fff; padding: 6px 14px; border-radius: 20px;">
            🛒 <?php echo $cantidadCarrito; ?>
        </a>
    </nav>
</header>

<div class="container-detail">

    <?php if (isset($_GET['success'])): ?>
        <div class="alert-success">
            Bolsa de Aura Stock actualizada. 
            <a href="carrito.php" style="color: var(--blue); font-weight: 600; margin-left: 10px; text-decoration: none;">Ver bolsa →</a>
        </div>
    <?php endif; ?>

    <div class="product-wrapper">
        <div class="image-viewer">
            <img src="img/<?php echo htmlspecialchars($p['imagen']); ?>.jpg" alt="<?php echo htmlspecialchars($p['nombre']); ?>">
        </div>

        <div class="info-panel">
            <span style="color: #bf4800; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Nuevo</span>
            <h1><?php echo htmlspecialchars($p['nombre']); ?></h1>
            <div class="price-tag"><?php echo formatear_precio($p['precio']); ?></div>

            <?php if ($p['stock'] > 0): ?>
                <form action="anadir_carrito.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">

                    <div class="option-group">
                        <span class="option-title">Acabado</span>
                        <select name="color" class="custom-select">
                            <option value="<?php echo htmlspecialchars($p['color']); ?>"><?php echo htmlspecialchars($p['color']); ?></option>
                        </select>

                        <span class="option-title" style="margin-top: 25px;">¿Cuántos necesitas?</span>
                        <select name="cantidad" class="custom-select">
                            <?php 
                            // Solo permite seleccionar hasta el stock disponible (máximo 5)
                            $max = min($p['stock'], 5);
                            for($i=1; $i<=$max; $i++): 
                            ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-buy">Añadir a la bolsa</button>
                    <p style="color: #34c759; font-size: 13px; margin-top: 12px; font-weight: 500; text-align: center;">
                        ✓ En stock (<?php echo $p['stock']; ?> unidades)
                    </p>
                </form>
            <?php else: ?>
                <div class="out-of-stock-card">
                    <span style="color: var(--red); font-weight: 600; font-size: 18px;">Producto agotado</span>
                    <p style="color: var(--gray); font-size: 14px; margin-top: 5px;">
                        Lo sentimos, actualmente no tenemos unidades disponibles de este modelo.
                    </p>
                    <a href="index.php" style="color: var(--blue); text-decoration: none; font-size: 14px; font-weight: 600; display: block; margin-top: 10px;">Ver otros productos →</a>
                </div>
            <?php endif; ?>

            <div class="option-group" style="margin-top: 30px;">
                <p style="color: var(--gray); line-height: 1.6; font-size: 15px; font-weight: 400;">
                    Experimenta la cima de la innovación. El <strong><?php echo htmlspecialchars($p['nombre']); ?></strong> 
                    en color <?php echo htmlspecialchars($p['color']); ?> ofrece un equilibrio perfecto entre potencia y diseño. 
                    <br><br>
                    <span style="color: var(--text); font-weight: 600;">✓ Envío gratuito y devoluciones sencillas.</span>
                </p>
            </div>
        </div>
    </div>
</div>

<footer style="margin-top: 100px; padding: 80px 20px; text-align: center; color: var(--gray); font-size: 13px; border-top: 1px solid #f5f5f7;">
    <div style="font-size: 24px; color: #000; margin-bottom: 20px;"></div>
    <p>© 2026 Aura Stock. Todos los derechos reservados.</p>
</footer>

</body>
</html>