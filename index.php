<?php
ob_start();
session_start();

$lang = $_GET['lang'] ?? 'es';

// --- DATOS DE PRODUCTOS Y VARIANTE S (Recomendación: mover a 'includes/datos.inc.php') ---
$productos = [
    1 => ['nombre'=>'MacBook Air M3 13"','categoria'=>'Mac','imagen_base'=>'macbook-air-m3-13in-midnight-config-202402','descripcion_corta'=>'Increíblemente fino, increíblemente rápido con chip M3.'],
    2 => ['nombre'=>'iPhone 15 Pro','categoria'=>'iPhone','imagen_base'=>'iphone-15-pro-black-titanium-select-202309-ecommerce','descripcion_corta'=>'Titanio. Tan robusto. Tan ligero. Tan Pro.'],
];

$variantes = [
    ['id'=>101,'producto_id'=>1,'capacidad'=>'256GB','color'=>'Medianoche (Negro)','precio'=>999,'imagen'=>'macbook-air-m3-13in-midnight-config-202402'],
    ['id'=>102,'producto_id'=>1,'capacidad'=>'512GB','color'=>'Medianoche (Negro)','precio'=>1199,'imagen'=>'macbook-air-m3-13in-midnight-config-202402'],
    ['id'=>103,'producto_id'=>1,'capacidad'=>'256GB','color'=>'Luz de estrella (Dorado)','precio'=>999,'imagen'=>'macbook-air-m3-13in-starlight-config-202402'],
    ['id'=>104,'producto_id'=>1,'capacidad'=>'512GB','color'=>'Luz de estrella (Dorado)','precio'=>1199,'imagen'=>'macbook-air-m3-13in-starlight-config-202402'],
    ['id'=>201,'producto_id'=>2,'capacidad'=>'128GB','color'=>'Titanio Negro','precio'=>999,'imagen'=>'iphone-15-pro-black-titanium-select-202309-ecommerce'],
    ['id'=>202,'producto_id'=>2,'capacidad'=>'256GB','color'=>'Titanio Negro','precio'=>1099,'imagen'=>'iphone-15-pro-black-titanium-select-202309-ecommerce'],
    ['id'=>203,'producto_id'=>2,'capacidad'=>'128GB','color'=>'Titanio Blanco','precio'=>999,'imagen'=>'iphone-15-pro-white-titanium-select-202309-ecommerce'],
    ['id'=>204,'producto_id'=>2,'capacidad'=>'256GB','color'=>'Titanio Blanco','precio'=>1099,'imagen'=>'iphone-15-pro-white-titanium-select-202309-ecommerce'],
];
// --- FIN DATOS DE PRODUCTOS Y VARIANTE S ---


// Traducciones e info de idiomas + banderas emoji + MONEDA Y SIMBOLO
$idiomas = [
  'es' => [
    'nombre' => 'ES', 
    'bandera' => '🇪🇸', 
    'moneda_simbolo' => '€', 
    'traducciones' => [
      'titulo' => 'Aura Store', 'iniciar_sesion' => 'Iniciar sesión', 'registrarse' => 'Registrarse', 'carrito' => 'Carrito',
      'categorias' => ['Todas', 'Mac', 'iPhone', 'Apple Watch', 'Accesorios', 'iPad'], 'buscar' => 'Buscar productos...',
      'ordenar_por' => 'Ordenar por', 'precio_menor' => 'Precio: menor a mayor', 'precio_mayor' => 'Precio: mayor a menor',
      'novedad_titulo' => 'iPhone 15 Pro (Titanio)', 'novedad_sub' => 'Descubre el poder de lo imposible. Chip A17 Pro y cámara de 48MP.',
      'mas_info' => 'Más información >', 'comprar' => 'Comprar >', 'todos_productos' => 'Todos los Productos',
    ]
  ],
  'en' => [
    'nombre' => 'EN', 
    'bandera' => '🇬🇧', 
    'moneda_simbolo' => '£', 
    'traducciones' => [
      'titulo' => 'Aura Store', 'iniciar_sesion' => 'Sign In', 'registrarse' => 'Sign Up', 'carrito' => 'Cart',
      'categorias' => ['All', 'Mac', 'iPhone', 'Apple Watch', 'Accessories', 'iPad'], 'buscar' => 'Search products...',
      'ordenar_por' => 'Sort by', 'precio_menor' => 'Price: low to high', 'precio_mayor' => 'Price: high to low',
      'novedad_titulo' => 'iPhone 15 Pro (Titanium)', 'novedad_sub' => 'Discover the power of impossible. A17 Pro chip and 48MP camera.',
      'mas_info' => 'Learn more >', 'comprar' => 'Buy >', 'todos_productos' => 'All Products',
    ]
  ],
  'fr' => [
    'nombre' => 'FR', 
    'bandera' => '🇫🇷', 
    'moneda_simbolo' => '€', 
    'traducciones' => [
      'titulo' => 'Aura Store', 'iniciar_sesion' => 'Connexion', 'registrarse' => 'Inscription', 'carrito' => 'Panier',
      'categorias' => ['Tous', 'Mac', 'iPhone', 'Apple Watch', 'Accessoires', 'iPad'], 'buscar' => 'Rechercher des produits...',
      'ordenar_por' => 'Trier par', 'precio_menor' => 'Prix : croissant', 'precio_mayor' => 'Prix : décroissant',
      'novedad_titulo' => 'iPhone 15 Pro (Titane)', 'novedad_sub' => 'Découvrez le pouvoir de l\'impossible. Puce A17 Pro et appareil photo 48MP.',
      'mas_info' => 'En savoir plus >', 'comprar' => 'Acheter >', 'todos_productos' => 'Tous les produits',
    ]
  ],
  'pt' => [
    'nombre' => 'PT', 
    'bandera' => '🇵🇹', 
    'moneda_simbolo' => '€', 
    'traducoes' => [ // Nota: algunas palabras cambian en portugués
      'titulo' => 'Aura Store', 'iniciar_sesion' => 'Iniciar Sessão', 'registrarse' => 'Registar', 'carrito' => 'Carrinho',
      'categorias' => ['Todos', 'Mac', 'iPhone', 'Apple Watch', 'Acessórios', 'iPad'], 'buscar' => 'Procurar produtos...',
      'ordenar_por' => 'Ordenar por', 'precio_menor' => 'Preço: menor para maior', 'precio_mayor' => 'Preço: maior para menor',
      'novedad_titulo' => 'iPhone 15 Pro (Titânio)', 'novedad_sub' => 'Descubra o poder do impossível. Chip A17 Pro e câmera de 48MP.',
      'mas_info' => 'Mais informações >', 'comprar' => 'Comprar >', 'todos_productos' => 'Todos os Produtos',
    ]
  ],
  'us' => [
    'nombre' => 'US', 
    'bandera' => '🇺🇸', 
    'moneda_simbolo' => '$', 
    'traducciones' => [
      'titulo' => 'Aura Store', 'iniciar_sesion' => 'Sign In', 'registrarse' => 'Sign Up', 'carrito' => 'Cart',
      'categorias' => ['All', 'Mac', 'iPhone', 'Apple Watch', 'Accessories', 'iPad'], 'buscar' => 'Search products...',
      'ordenar_por' => 'Sort by', 'precio_menor' => 'Price: low to high', 'precio_mayor' => 'Price: high to low',
      'novedad_titulo' => 'iPhone 15 Pro (Titanium)', 'novedad_sub' => 'Discover the power of impossible. A17 Pro chip and 48MP camera.',
      'mas_info' => 'Learn more >', 'comprar' => 'Buy >', 'todos_productos' => 'All Products',
    ]
  ],
];

// ... (resto del código) ...


if (!isset($idiomas[$lang])) $lang = 'es';
$t = $idiomas[$lang]['traducciones'] ?? $idiomas[$lang]['traducoes'] ?? $idiomas[$lang]['traductions'];
$moneda = $idiomas[$lang]['moneda_simbolo'];


// LÓGICA DE FILTROS, BÚSQUEDA Y ORDEN MEJORADA
$categoriaSeleccionada = $_GET['categoria'] ?? $t['categorias'];
$busqueda = strtolower(trim($_GET['busqueda'] ?? ''));
$orden = $_GET['orden'] ?? '';

$variantesFiltradas = array_filter($variantes, function($v) use ($categoriaSeleccionada, $productos, $t, $busqueda) {
    $productoPadre = $productos[$v['producto_id']];
    $categoriaOk = $categoriaSeleccionada === $t['categorias'] || $productoPadre['categoria'] === $categoriaSeleccionada;
    $busquedaOk = true;
    if ($busqueda) {
        $nombreCompleto = strtolower($productoPadre['nombre'] . ' ' . $v['color'] . ' ' . $v['capacidad']);
        $busquedaOk = str_contains($nombreCompleto, $busqueda);
    }
    return $categoriaOk && $busquedaOk;
});

if ($orden === 'precio_asc') { usort($variantesFiltradas, fn($a,$b) => $a['precio'] <=> $b['precio']); } 
elseif ($orden === 'precio_desc') { usort($variantesFiltradas, fn($a,$b) => $b['precio'] <=> $a['precio']); }

// Lógica de paginación
$productosPorPagina = 8;
$totalProductos = count($variantesFiltradas);
$totalPaginas = ceil($totalProductos / $productosPorPagina);
$paginaActual = max(1, min($totalPaginas, intval($_GET['pagina'] ?? 1)));
$offset = ($paginaActual - 1) * $productosPorPagina;
$productosPagina = array_slice($variantesFiltradas, $offset, $productosPorPagina);
$cantidadCarrito = isset($_SESSION['carrito']) ? array_sum(array_column($_SESSION['carrito'], 'cantidad')) : 0;
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($t['titulo']); ?></title>
  <!-- RUTA CORREGIDA -->
  <link href="fonts.googleapis.com" rel="stylesheet">
  <style>
    /* Estilos Generales y Header/Hero/Grid */
    :root { --color-primary: #0071e3; --color-background: #ffffff; --color-card: #f9f9f9; --color-text: #1d1d1f; --color-text-secondary: #86868b; --color-border: #d2d2d7; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background-color: var(--color-background); color: var(--color-text); margin: 0; padding-top: 48px; position: relative; overflow-x: hidden; }
    header { position: fixed; top: 0; box-sizing: border-box; width: 100%; background: rgba(255, 255, 255, 0.9); border-bottom: 1px solid var(--color-border); height: 48px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; z-index: 1000; backdrop-filter: blur(10px); }
    .logo a { color: var(--color-text); font-weight: 700; font-size: 1rem; text-decoration: none; cursor: pointer; }
    .nav-items { display: flex; align-items: center; gap: 20px; }
    .nav-items a { color: var(--color-text); font-size: 0.8rem; text-decoration: none; opacity: 0.8; }
    .main-container { max-width: 1000px; margin: 0 auto; padding: 20px; }
    .hero-banner { background-color: var(--color-card); border-radius: 15px; padding: 40px; margin-bottom: 40px; display: flex; align-items: center; justify-content: space-between; border: 1px solid var(--color-border); }
    .hero-content { flex: 1; }
    .hero-content span { display: block; color: var(--color-primary); font-weight: 600; margin-bottom: 10px; }
    .hero-content h1 { font-size: 3rem; font-weight: 700; margin-bottom: 10px; }
    .hero-content p { font-size: 1.2rem; color: var(--color-text-secondary); margin-bottom: 20px; }
    .btn-hero { background-color: var(--color-primary); color: white; padding: 10px 20px; border-radius: 20px; font-weight: 600; display: inline-block; margin-right: 10px; transition: background-color 0.3s; text-decoration: none; }
    .btn-hero:hover { background-color: #005bb5; }
    .btn-hero-secondary { background-color: transparent; color: var(--color-primary); border: 1px solid var(--color-primary); }
    .btn-hero-secondary:hover { background-color: var(--color-primary); color: white; }
    .hero-img { flex: 1; max-width: 400px; height: auto; object-fit: contain; margin-left: 20px; mix-blend-mode: darken; }
    .section-title { font-size: 1.8rem; font-weight: 700; margin-top: 40px; margin-bottom: 20px; border-bottom: 1px solid var(--color-border); padding-bottom: 10px; }
    .menu-categorias { margin: 20px auto 10px; display: flex; justify-content: center; gap: 30px; padding: 10px 0; border-bottom: 1px solid var(--color-border); border-top: 1px solid var(--color-border); }
    .menu-categorias a { font-weight: 400; color: var(--color-text-secondary); text-decoration: none; font-size: 0.9rem; }
    .menu-categorias a:hover { color: var(--color-text); }
    .menu-categorias .activo { color: var(--color-text); font-weight: 600; pointer-events: none; border-bottom: 2px solid var(--color-primary); }
    form#busqueda-form { max-width: 900px; margin: 20px auto; display: flex; justify-content: center; gap: 10px; }
    form#busqueda-form input[type="search"], form#busqueda-form select { padding: 8px 12px; width: 200px; border-radius: 12px; border: 1px solid var(--color-border); background: var(--color-background); color: var(--color-text); font-size: 0.9rem; }
    form#busqueda-form button { padding: 8px 16px; border-radius: 12px; background-color: var(--color-primary); color: white; border: none; cursor: pointer; }
    .grid-productos { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 30px; margin-top: 30px; }
    .producto-card { background: var(--color-card); border-radius: 15px; border: 1px solid var(--color-border); padding: 20px; text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .producto-card:hover { transform: translateY(-5px); box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08); }
    .producto-link { text-decoration: none; display: block; }
    .producto-img { width: 100%; height: 150px; object-fit: contain; margin-bottom: 15px; mix-blend-mode: darken; }
    .producto-nombre { font-size: 1rem; font-weight: 600; color: var(--color-text); margin-bottom: 5px; }
    .producto-meta { font-size: 0.8rem; color: var(--color-text-secondary); margin-bottom: 5px; }
    .producto-precio { font-size: 0.9rem; font-weight: 400; color: var(--color-text-secondary); margin-bottom: 15px; }
    .btn-añadir { background-color: var(--color-primary); color: white; padding: 8px 15px; border: none; border-radius: 20px; cursor: pointer; font-weight: 500; transition: background-color 0.3s ease; text-decoration: none; display: inline-block; }
    .paginacion { display: flex; justify-content: center; margin-top: 40px; gap: 10px; }
    .paginacion a, .paginacion span { padding: 8px 15px; border-radius: 12px; border: 1px solid var(--color-border); text-decoration: none; color: var(--color-text); }
    .paginacion .current-page { background-color: var(--color-primary); color: white; border-color: var(--color-primary); pointer-events: none; }
    .promo-section { display: flex; align-items: center; justify-content: space-between; padding: 40px; margin: 40px auto; border-radius: 15px; max-width: 1000px; border: 1px solid var(--color-border); background-color: var(--color-card); }
    @media (max-width: 768px) {
        .hero-banner, .promo-section { flex-direction: column; text-align: center; }
    }

    /* --- Estilos para la nueva sección de banners de categorías --- */
    .category-banners { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
    .category-banners > div { border-radius: 15px; padding: 30px; color: white; background-size: cover; background-position: center; min-height: 200px; display: flex; flex-direction: column; justify-content: flex-end; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); }
    .category-banners h2 { font-size: 1.5rem; margin-bottom: 5px; }
    .category-banners p { font-size: 0.9rem; margin-bottom: 15px; opacity: 0.8; }
    .category-banners a { color: white; text-decoration: none; font-weight: 600; align-self: flex-start; padding: 8px 15px; border-radius: 20px; background-color: rgba(255, 255, 255, 0.2); transition: background-color 0.3s; }
    .category-banners a:hover { background-color: rgba(255, 255, 255, 0.4); }
    .banner-mac { background-image: url('store.storeimages.cdn-apple.com'); background-color: #2b3a4a; }
    .banner-watch { background-image: url('store.storeimages.cdn-apple.com'); background-color: #a70000; }
    .banner-ipad { background-image: url('store.storeimages.cdn-apple.com'); background-color: #1d1d1f; }
    @media (max-width: 768px) { .category-banners { grid-template-columns: 1fr; } }
    
    /* --- Estilos para la nueva sección de testimonios --- */
    .testimonios-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 20px; }
    .testimonio-card { background: var(--color-card); border: 1px solid var(--color-border); border-radius: 15px; padding: 25px; display: flex; flex-direction: column; justify-content: space-between; height: 100%; }
    .testimonio-card p { font-size: 0.9rem; color: var(--color-text); margin-bottom: 20px; line-height: 1.4; }
    .testimonio-autor { display: flex; flex-direction: column; font-size: 0.8rem; color: var(--color-text-secondary); }
    .testimonio-autor strong { margin-top: 5px; color: var(--color-text); }
    @media (max-width: 768px) { .testimonios-grid { grid-template-columns: 1fr; } }


    /* --- Estilos para el nuevo Footer --- */
    footer { background-color: var(--color-card); border-top: 1px solid var(--color-border); padding: 40px 20px; margin-top: 60px; }
    .footer-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; max-width: 1000px; margin: 0 auto; }
    .footer-section h3 { margin-bottom: 15px; color: var(--color-text); font-size: 1rem; }
    .footer-section p, .footer-section a { color: var(--color-text-secondary); font-size: 0.8rem; text-decoration: none; }
    .footer-section a:hover { text-decoration: underline; }
    .footer-section ul { list-style: none; }
    .footer-section ul li { margin-bottom: 8px; }
  </style>
</head>
<body>

    <!-- HEADER DINÁMICO INTEGRADO -->
    <header>
      <div class="logo">
        <a href="index.php?lang=<?php echo htmlspecialchars($lang ?? 'es'); ?>"> Aura Store</a>
      </div>
      <div class="nav-items">
        <?php if (isset($_SESSION['usuario_nombre'])): ?>
          <a href="usuario.php?lang=<?php echo htmlspecialchars($lang ?? 'es'); ?>">Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a>
          <a href="usuario.php?logout=true&lang=<?php echo htmlspecialchars($lang ?? 'es'); ?>">Cerrar Sesión</a>
          <a href="carrito.php?lang=<?php echo htmlspecialchars($lang ?? 'es'); ?>"><?php echo htmlspecialchars($t['carrito'] ?? 'Carrito'); ?> (<?php echo htmlspecialchars($cantidadCarrito ?? 0); ?>)</a>
        <?php else: ?>
          <a href="login.php?lang=<?php echo htmlspecialchars($lang ?? 'es'); ?>"><?php echo htmlspecialchars($t['iniciar_sesion'] ?? 'Iniciar sesión'); ?></a>
          <a href="registro.php?lang=<?php echo htmlspecialchars($lang ?? 'es'); ?>"><?php echo htmlspecialchars($t['registrarse'] ?? 'Registrarse'); ?></a>
          <a href="carrito.php?lang=<?php echo htmlspecialchars($lang ?? 'es'); ?>"><?php echo htmlspecialchars($t['carrito'] ?? 'Carrito'); ?> (<?php echo htmlspecialchars($cantidadCarrito ?? 0); ?>)</a>
        <?php endif; ?>

        <select onchange="window.location.href='?lang='+this.value">
            <?php foreach ($idiomas as $codigo => $datos): ?>
                <option value="<?php echo htmlspecialchars($codigo); ?>" <?php echo ($lang ?? 'es') === $codigo ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($datos['bandera']) . ' ' . htmlspecialchars($datos['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>
      </div>
    </header>

    <div class="main-container">
        <!-- SECCIÓN DESTACADA (HERO BANNER) -->
        <div class="hero-banner">
            <div class="hero-content">
                <span>Nuevo</span>
                <h1><?php echo htmlspecialchars($t['novedad_titulo']); ?></h1>
                <p><?php echo htmlspecialchars($t['novedad_sub']); ?></p>
                <a href="detalle.php?id=201&lang=<?php echo htmlspecialchars($lang); ?>" class="btn-hero"><?php echo htmlspecialchars($t['mas_info']); ?></a>
                <a href="anadir_carrito.php?id=201&lang=<?php echo htmlspecialchars($lang); ?>" class="btn-hero btn-hero-secondary"><?php echo htmlspecialchars($t['comprar']); ?></a>
            </div>
            <img src="store.storeimages.cdn-apple.com" alt="iPhone 15 Pro" class="hero-img">
        </div>

        <!-- MENÚ DE CATEGORÍAS -->
        <nav class="menu-categorias">
            <?php foreach ($t['categorias'] as $cat): ?>
                <?php 
                    $params = array_merge($_GET, ['categoria' => $cat, 'pagina' => 1]);
                    $queryString = http_build_query($params);
                ?>
                <a href="?<?php echo htmlspecialchars($queryString); ?>" class="<?php echo ($categoriaSeleccionada === $cat) ? 'activo' : ''; ?>">
                    <?php echo htmlspecialchars($cat); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- FORMULARIO DE BÚSQUEDA Y ORDEN -->
        <form id="busqueda-form" method="GET" action="">
            <input type="hidden" name="lang" value="<?php echo htmlspecialchars($lang); ?>">
            <?php if (isset($_GET['categoria'])): ?><input type="hidden" name="categoria" value="<?php echo htmlspecialchars($_GET['categoria']); ?>"><?php endif; ?>
            
            <input type="search" name="busqueda" placeholder="<?php echo htmlspecialchars($t['buscar']); ?>" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>">
            
            <select name="orden" onchange="this.form.submit()">
                <option value="">-- <?php echo htmlspecialchars($t['ordenar_por']); ?> --</option>
                <option value="precio_asc" <?php echo $orden === 'precio_asc' ? 'selected' : ''; ?>><?php echo htmlspecialchars($t['precio_menor']); ?></option>
                <option value="precio_desc" <?php echo $orden === 'precio_desc' ? 'selected' : ''; ?>><?php echo htmlspecialchars($t['precio_mayor']); ?></option>
            </select>
            <button type="submit">🔍</button>
        </form>

        <!-- SECCIÓN DE BANNERS VISUALES DE CATEGORÍA (Nueva Adición) -->
        <div class="category-banners">
            <div class="banner-mac">
                <h2>MacBook</h2>
                <p>Potencia que te acompaña a todas partes.</p>
                <a href="index.php?categoria=Mac&lang=<?php echo htmlspecialchars($lang); ?>">Comprar Mac ></a>
            </div>
            <div class="banner-watch">
                <h2>Apple Watch</h2>
                <p>El futuro de la salud, en tu muñeca.</p>
                <a href="index.php?categoria=Apple Watch&lang=<?php echo htmlspecialchars($lang); ?>">Comprar Watch ></a>
            </div>
            <div class="banner-ipad">
                <h2>iPad Pro</h2>
                <p>Tu estudio de creatividad móvil.</p>
                <a href="index.php?categoria=iPad&lang=<?php echo htmlspecialchars($lang); ?>">Comprar iPad ></a>
            </div>
        </div>

        <h2 class="section-title"><?php echo htmlspecialchars($t['todos_productos']); ?> (<?php echo htmlspecialchars($totalProductos); ?>)</h2>

        <!-- GRID DE PRODUCTOS -->
        <div class="grid-productos">
            <?php foreach ($productosPagina as $producto_variante): 
                $producto_padre = $productos[$producto_variante['producto_id']];
            ?>
                <div class="producto-card">
                    <a href="detalle.php?id=<?php echo htmlspecialchars($producto_variante['id']); ?>&lang=<?php echo htmlspecialchars($lang); ?>" class="producto-link">
                        <img src="store.storeimages.cdn-apple.com<?php echo urlencode($producto_variante['imagen']); ?>?wid=400&hei=400&fmt=jpeg&qlt=95" alt="<?php echo htmlspecialchars($producto_padre['nombre']); ?>" class="producto-img">
                        
                        <div class="producto-nombre">
                            <?php echo htmlspecialchars($producto_padre['nombre']); ?>
                        </div>
                        <div class="producto-meta">
                            <?php echo htmlspecialchars($producto_variante['capacidad']); ?> | <?php echo htmlspecialchars($producto_variante['color']); ?>
                        </div>
                        <div class="producto-precio"><?php echo htmlspecialchars($moneda); ?><?php echo number_format($producto_variante['precio'], 0, '.', ','); ?></div>
                    </a>
                    
                    <a href="anadir_carrito.php?id=<?php echo htmlspecialchars($producto_variante['id']); ?>&lang=<?php echo htmlspecialchars($lang); ?>" class="btn-añadir">
                        + <?php echo htmlspecialchars($t['carrito']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- FIN GRID DE PRODUCTOS -->

        <!-- PAGINACIÓN -->
        <div class="paginacion">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php $queryParams = http_build_query(array_merge($_GET, ['pagina' => $i])); ?>
                <a href="?<?php echo htmlspecialchars($queryParams); ?>" class="<?php echo $paginaActual == $i ? 'current-page' : ''; ?>">
                    <?php echo htmlspecialchars($i); ?>
                </a>
            <?php endfor; ?>
        </div>

    </div>
    <!-- FIN main-container -->

    <!-- SECCIONES PROMOCIONALES (Anteriores) -->
    <div class="promo-section promo-watch">
        <div class="promo-content">
            <h2>Apple Watch Series 9</h2>
            <p>Un futuro brillante en tu muñeca. Ahora con detección de doble toque.</p>
            <a href="#" class="btn-hero">Más información ></a>
            <a href="#" class="btn-hero btn-hero-secondary">Comprar ></a>
        </div>
        <img src="store.storeimages.cdn-apple.comMT4P3_VW_34?wid=800&hei=800&fmt=jpeg&qlt=95" alt="Apple Watch Promo" class="promo-img">
    </div>

    <!-- SECCIÓN DE TESTIMONIOS (Nueva Adición) -->
    <div class="main-container">
        <h2 class="section-title">Lo que dicen nuestros clientes</h2>
        
        <div class="testimonios-grid">
            <div class="testimonio-card">
                <p>"Mi nuevo iPhone llegó en 24 horas y el servicio de atención al cliente fue excelente. ¡Muy recomendable Aura Store!"</p>
                <div class="testimonio-autor">
                    <span>⭐⭐⭐⭐⭐</span>
                    <strong>Ana G.</strong>
                </div>
            </div>
            
            <div class="testimonio-card">
                <p>"La mejor tienda para productos Apple en línea. Precios competitivos y la garantía de que son originales."</p>
                <div class="testimonio-autor">
                    <span>⭐⭐⭐⭐⭐</span>
                    <strong>Geordana A.</strong>
                </div>
            </div>

             <div class="testimonio-card">
                <p>"Me encanta el diseño de la web y la facilidad para encontrar lo que busco. ¡Ya tengo mi MacBook Air M3!"</p>
                <div class="testimonio-autor">
                    <span>⭐⭐⭐⭐⭐</span>
                    <strong>Maximo A.</strong>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN SECCIÓN DE TESTIMONIOS -->

    <!-- INICIO FOOTER COMPLETO (Nueva Adición) -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3> Aura Store</h3>
                <p>&copy; 2025 Aura Store. Todos los derechos reservados.</p>
            </div>
            <div class="footer-section">
                <h3>Información</h3>
                <ul>
                    <li><a href="acerca_de.php">Acerca de nosotros</a></li>
                    <li><a href="contacto.php">Contacto</a></li>
                    <li><a href="faq.php">Preguntas Frecuentes</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Legal</h3>
                <ul>
                    <li><a href="terminos.php">Términos y Condiciones</a></li>
                    <li><a href="privacidad.php">Política de Privacidad</a></li>
                    <li><a href="envios.php">Envíos y Devoluciones</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Síguenos</h3>
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Facebook</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- FIN FOOTER COMPLETO -->

</body>
</html>
