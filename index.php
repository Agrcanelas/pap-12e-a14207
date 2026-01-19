<?php
require_once 'config.php';

// 1. Lógica de Búsqueda y Filtros
$cat = $_GET['categoria'] ?? 'Todas';
$buscar = $_GET['buscar'] ?? '';
$precio_max = $_GET['precio_max'] ?? 3000;

// Consulta principal con filtros
$sql = "SELECT * FROM productos WHERE precio <= ?";
$params = [$precio_max];

if ($cat !== 'Todas') {
    $sql .= " AND categoria = ?";
    $params[] = $cat;
}

if (!empty($buscar)) {
    $sql .= " AND (nombre LIKE ? OR categoria LIKE ?)";
    $params[] = "%$buscar%";
    $params[] = "%$buscar%";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Consulta para "Novedades" (Los últimos 4 añadidos)
$stmt_news = $pdo->query("SELECT * FROM productos ORDER BY id DESC LIMIT 4");
$novedades = $stmt_news->fetchAll(PDO::FETCH_ASSOC);

$cantidadCarrito = isset($_SESSION['carrito']) ? array_sum(array_column($_SESSION['carrito'], 'cantidad')) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Stock | Premium Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #f5f5f7; --card: #ffffff; --text: #1d1d1f; --accent: #000000; --blue: #007aff; --gray: #86868b; --red: #ff3b30; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); color: var(--text); margin: 0; padding-top: 60px; overflow-x: hidden; -webkit-font-smoothing: antialiased; }
        
        header {
            position: fixed; top: 0; width: 100%; height: 65px;
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);
            display: flex; justify-content: space-around; align-items: center;
            z-index: 1000; border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .search-form { display: flex; background: #f2f2f7; border-radius: 10px; padding: 6px 15px; width: 30%; transition: all 0.3s; }
        .search-form:focus-within { background: #e5e5ea; box-shadow: 0 0 0 3px rgba(0,0,0,0.05); }
        .search-form input { border: none; background: transparent; width: 100%; outline: none; font-size: 14px; color: var(--text); }
        .search-form button { border: none; background: transparent; cursor: pointer; opacity: 0.5; }

        .logo-container { display: flex; align-items: center; text-decoration: none; color: var(--text); }
        .brand-name { font-size: 19px; font-weight: 600; margin-left: 6px; letter-spacing: -0.5px; }

        .btn-admin {
            color: #000 !important; font-weight: 600; border: 1px solid #000;
            padding: 6px 14px; border-radius: 10px; text-decoration: none;
            font-size: 13px; margin-right: 15px; transition: all 0.2s;
        }
        .btn-admin:hover { background: #000; color: #fff !important; }

        .hero { background: #000; color: #fff; text-align: center; padding: 80px 20px; }
        .hero h1 { font-size: 56px; margin: 0; letter-spacing: -3px; font-weight: 600; }
        .hero p { font-size: 22px; color: #a1a1a6; margin: 15px 0 0; font-weight: 300; }

        .status-alert { background: #34c759; color: white; text-align: center; padding: 15px; font-weight: 600; font-size: 14px; }

        .container { display: flex; max-width: 1400px; margin: 0 auto; padding: 50px 20px; gap: 50px; }
        
        .sidebar { width: 200px; flex-shrink: 0; }
        .filter-group { background: white; padding: 25px; border-radius: 24px; margin-bottom: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
        .filter-group h4 { margin-top: 0; margin-bottom: 18px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--gray); font-weight: 600; }

        .nav-links { display: flex; align-items: center; gap: 15px; }
        .nav-item { text-decoration: none; color: var(--text); font-size: 13px; font-weight: 400; transition: color 0.2s; }
        .nav-item:hover { color: var(--blue); }

        /* PRODUCT GRID REFINADO */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; width: 100%; }
        
        .product-card {
            background: var(--card); border-radius: 28px; padding: 40px; text-align: center;
            /* Transiciones suaves para todos los efectos */
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                        box-shadow 0.4s ease, 
                        opacity 0.3s ease; 
            text-decoration: none; color: inherit; 
            display: flex; flex-direction: column; align-items: center; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            position: relative;
        }

        /* EFECTO DE ELEVACIÓN AL PASAR EL MOUSE */
        .product-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); 
        }

        .product-card img { 
            height: 200px; object-fit: contain; margin-bottom: 25px; 
            transition: transform 0.5s ease; /* Suavizar el zoom */
        }

        /* ZOOM EN LA IMAGEN */
        .product-card:hover img {
            transform: scale(1.08);
        }

        .price { font-size: 20px; font-weight: 600; margin-top: 15px; letter-spacing: -0.5px; }

        .news-section { padding: 80px 8%; background: #fff; text-align: center; border-bottom: 1px solid #f5f5f7; }
        .news-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }
        .news-card { text-align: center; text-decoration: none; color: inherit; transition: all 0.3s; }
        .news-card img { height: 140px; object-fit: contain; }

        input[type=range] { accent-color: #000; }
        footer { background: #fff; padding: 80px 20px; text-align: center; border-top: 1px solid #d2d2d7; font-size: 13px; color: var(--gray); }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="logo-container">
        <span style="font-size: 22px;"></span>
        <span class="brand-name">Aura Stock</span>
    </a>

    <form action="index.php" method="GET" class="search-form">
        <input type="text" name="buscar" id="inputLiveSearch" placeholder="¿Qué estás buscando?" value="<?php echo htmlspecialchars($buscar ?? ''); ?>" autocomplete="off">
        <button type="submit">🔍</button>
    </form>

    <nav class="nav-links">
        <?php if(isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
            <a href="admin.php" class="btn-admin">Gestionar</a>
        <?php endif; ?>

        <?php if(isset($_SESSION['usuario_id'])): ?>
            <a href="perfil.php" class="nav-item" style="font-weight:600;">Cuenta</a>
        <?php else: ?>
            <a href="login.php" class="nav-item">Entrar</a>
            <a href="registro.php" class="nav-item">Unirse</a>
        <?php endif; ?>
        
        <a href="carrito.php" class="nav-item" style="margin-left: 5px; background: #000; color: #fff; padding: 6px 12px; border-radius: 20px; display: flex; align-items: center; gap: 5px;">
            <span style="font-size: 14px;">🛒</span> <?php echo $cantidadCarrito; ?>
        </a>
    </nav>
</header>

<?php if (isset($_GET['status']) && $_GET['status'] === 'compra_exitosa'): ?>
    <div class="status-alert">🎉 ¡Pedido realizado! Revisa los detalles en tu perfil.</div>
<?php endif; ?>

<?php if ($cat == 'Todas' && empty($buscar)): ?>
<section class="hero">
    <h1>Aura Stock.</h1>
    <p>Tecnología para quienes buscan la perfección.</p>
</section>

<section class="news-section">
    <h2 style="font-size: 32px; margin-bottom: 50px; letter-spacing: -1px;">Lo último</h2>
    <div class="news-grid">
        <?php foreach ($novedades as $n): ?>
            <a href="detalle.php?id=<?php echo $n['id']; ?>" class="news-card">
                <img src="img/<?php echo $n['imagen']; ?>.jpg" alt="">
                <h4 style="font-size: 17px; margin: 20px 0 5px; font-weight: 600;"><?php echo $n['nombre']; ?></h4>
                <span style="color: var(--blue); font-size: 13px; font-weight: 600;">Recién llegado</span>
            </a>
        <?php endforeach; ?> 
    </div>
</section>
<?php endif; ?>

<div class="container">
    <aside class="sidebar">
        <div class="filter-group">
            <h4>Colecciones</h4>
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <a href="index.php?categoria=Todas" style="text-decoration:none; font-size: 15px; color:<?php echo $cat=='Todas' ? '#000':'var(--gray)';?>; font-weight:<?php echo $cat=='Todas' ? '600':'400';?>;">Todas</a>
                <a href="index.php?categoria=iPhone" style="text-decoration:none; font-size: 15px; color:<?php echo $cat=='iPhone' ? '#000':'var(--gray)';?>; font-weight:<?php echo $cat=='iPhone' ? '600':'400';?>;">iPhone</a>
                <a href="index.php?categoria=Mac" style="text-decoration:none; font-size: 15px; color:<?php echo $cat=='Mac' ? '#000':'var(--gray)';?>; font-weight:<?php echo $cat=='Mac' ? '600':'400';?>;">Mac</a>
                <a href="index.php?categoria=Watch" style="text-decoration:none; font-size: 15px; color:<?php echo $cat=='Watch' ? '#000':'var(--gray)';?>; font-weight:<?php echo $cat=='Watch' ? '600':'400';?>;">Watch</a>
            </div>
        </div>

        <div class="filter-group">
            <h4>Precio</h4>
            <p style="font-size: 13px; margin-bottom: 12px; color: var(--gray);">Hasta: <strong><span id="priceVal" style="color: #000;"><?php echo $precio_max; ?></span>€</strong></p>
            <form action="index.php" method="GET">
                <input type="hidden" name="categoria" value="<?php echo $cat; ?>">
                <input type="range" name="precio_max" min="0" max="3000" step="50" 
                       value="<?php echo $precio_max; ?>" style="width: 100%;"
                       oninput="document.getElementById('priceVal').innerText = this.value">
                <button type="submit" style="width: 100%; margin-top: 20px; background: #000; color: #fff; border: none; padding: 14px; border-radius: 12px; cursor: pointer; font-weight: 600; font-size: 14px; transition: 0.2s;">Aplicar Filtro</button>
            </form>
        </div>
    </aside>

    <main style="flex-grow: 1;">
        <h2 id="resultHeading" style="margin-top: 0; font-size: 36px; letter-spacing: -1.5px; margin-bottom: 40px; font-weight: 600;">
            <?php echo $buscar ? "Resultados para '$buscar'" : ($cat == 'Todas' ? "Nuestro Catálogo" : $cat); ?>
        </h2>
        
        <div class="product-grid" id="mainGrid">
            <?php if (empty($productos)): ?>
                <div id="noResults" style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                    <p style="font-size: 18px; color: var(--gray);">No hemos encontrado lo que buscas.</p>
                    <a href="index.php" style="color: var(--blue); text-decoration: none; font-weight: 600;">Ver todo el catálogo ➔</a>
                </div>
            <?php endif; ?>

            <?php foreach ($productos as $p): ?>
                <a href="detalle.php?id=<?php echo $p['id']; ?>" class="product-card">
                    <img src="img/<?php echo $p['imagen']; ?>.jpg" alt="">
                    <h3 style="font-size: 21px; margin: 0; font-weight: 600; letter-spacing: -0.5px;"><?php echo $p['nombre']; ?></h3>
                    <p style="color: var(--gray); font-size: 14px; margin-top: 8px;"><?php echo $p['color']; ?></p>
                    <div class="price"><?php echo number_format($p['precio'], 2, ',', '.'); ?> €</div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<footer>
    <div style="margin-bottom: 25px; font-size: 24px; color: #000;"></div>
    <p>© 2026 Aura Stock. Todos los derechos reservados.</p>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('inputLiveSearch');
    const productCards = document.querySelectorAll('.product-card');
    const heading = document.getElementById('resultHeading');
    const originalHeading = heading.innerText;

    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase().trim();
        
        // Actualizar el título dinámicamente con una suave transición
        heading.innerText = term.length > 0 ? `Buscando: "${e.target.value}"` : originalHeading;

        productCards.forEach(card => {
            const text = card.innerText.toLowerCase();
            
            if(text.includes(term)) {
                card.style.display = 'flex';
                // Usamos un pequeño delay para que la opacidad se anime después del display
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 10);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    if(card.style.opacity === '0') card.style.display = 'none';
                }, 300);
            }
        });
    });
});
</script>

</body>
</html>