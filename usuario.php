<?php
session_start();

$lang = $_GET['lang'] ?? 'es';

// Traducciones básicas necesarias para esta página
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


if (!isset($idiomas[$lang])) $lang = 'es';
$t = $idiomas[$lang]['traducciones'] ?? $idiomas[$lang]['traducoes'] ?? $idiomas[$lang]['traductions'];
$moneda = $idiomas[$lang]['moneda_simbolo'] ?? '€';

// Verificar si el usuario ha iniciado sesión, si no, redirigir al login
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: login.php?lang=" . urlencode($lang));
    exit;
}

$nombre_usuario = $_SESSION['usuario_nombre'];
$cantidadCarrito = isset($_SESSION['carrito']) ? array_sum(array_column($_SESSION['carrito'], 'cantidad')) : 0;


// Lógica para cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php?lang=" . urlencode($lang));
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo htmlspecialchars($t['titulo']); ?></title>
<link href="fonts.googleapis.com" rel="stylesheet">
<style>
    /* Estilos consistentes con el resto de la tienda */
    :root { --color-primary: #0071e3; --color-background: #ffffff; --color-card: #f9f9f9; --color-text: #1d1d1f; --color-text-secondary: #86868b; --color-border: #d2d2d7; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background-color: var(--color-background); color: var(--color-text); padding-top: 48px; }
    header { position: fixed; top: 0; width: 100%; background: rgba(255, 255, 255, 0.95); border-bottom: 1px solid var(--color-border); height: 48px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; backdrop-filter: blur(10px); z-index: 1000; }
    .logo a { color: var(--color-text); font-weight: 700; font-size: 1rem; text-decoration: none; }
    .nav-items { display: flex; align-items: center; gap: 20px; }
    .nav-items a { color: var(--color-text); font-size: 0.8rem; text-decoration: none; opacity: 0.8; }
    .main-container { max-width: 1000px; margin: 40px auto; padding: 20px; }
    .profile-container { background: var(--color-card); border: 1px solid var(--color-border); border-radius: 15px; padding: 40px; max-width: 600px; margin: 40px auto; }
    h1 { font-size: 2rem; margin-bottom: 20px; color: var(--color-text); }
    p { margin-bottom: 15px; color: var(--color-text-secondary); }
    .btn-logout { background-color: #ff3b3b; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; margin-top: 20px; }
    .btn-logout:hover { background-color: #e00000; }
    .profile-link { display: block; margin-top: 15px; color: var(--color-primary); text-decoration: none; }
</style>
</head>
<body>
<!-- INICIO HEADER DINÁMICO INTEGRADO -->
<header>
  <div class="logo">
    <a href="index.php?lang=<?php echo htmlspecialchars($lang); ?>"> Aura Store</a>
  </div>
  <div class="nav-items">
    <?php if (isset($_SESSION['usuario_nombre'])): ?>
      <!-- Aquí estamos en usuario.php, solo mostramos enlaces a tienda y carrito -->
      <a href="index.php?lang=<?php echo htmlspecialchars($lang); ?>">Tienda</a>
      <a href="carrito.php?lang=<?php echo htmlspecialchars($lang); ?>"><?php echo htmlspecialchars($t['carrito']); ?> (<?php echo htmlspecialchars($cantidadCarrito); ?>)</a>
    <?php else: ?>
      <!-- Esto no debería verse si el usuario está logueado, pero es el fallback -->
      <a href="login.php?lang=<?php echo htmlspecialchars($lang); ?>"><?php echo htmlspecialchars($t['iniciar_sesion']); ?></a>
      <a href="registro.php?lang=<?php echo htmlspecialchars($lang); ?>"><?php echo htmlspecialchars($t['registrarse']); ?></a>
      <a href="carrito.php?lang=<?php echo htmlspecialchars($lang); ?>"><?php echo htmlspecialchars($t['carrito']); ?> (<?php echo htmlspecialchars($cantidadCarrito); ?>)</a>
    <?php endif; ?>

    <!-- Selector de Idioma -->
     <select onchange="window.location.href='?lang='+this.value">
        <?php foreach ($idiomas as $codigo => $datos): ?>
            <option value="<?php echo htmlspecialchars($codigo); ?>" <?php echo $lang === $codigo ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($datos['bandera']) . ' ' . htmlspecialchars($datos['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select>
  </div>
</header>
<!-- FIN HEADER DINÁMICO -->

<div class="main-container">
    <div class="profile-container">
        <h1><?php echo htmlspecialchars(sprintf($t['bienvenido'], $nombre_usuario)); ?></h1>
        <p><?php echo htmlspecialchars($t['area_perfil']); ?></p>
        <p><?php echo htmlspecialchars($t['gestion_pedidos']); ?></p>

        <a href="usuario.php?logout=true&lang=<?php echo htmlspecialchars($lang); ?>" class="btn-logout"><?php echo htmlspecialchars($t['cerrar_sesion_btn']); ?></a>
        
        <a href="#" class="profile-link"><?php echo htmlspecialchars($t['ver_pedidos']); ?></a>
        <a href="#" class="profile-link"><?php echo htmlspecialchars($t['editar_datos']); ?></a>
        <a href="recuperar.php?lang=<?php echo htmlspecialchars($lang); ?>" class="profile-link"><?php echo htmlspecialchars($t['cambiar_contrasena']); ?></a>
    </div>
</div>

</body>
</html>
