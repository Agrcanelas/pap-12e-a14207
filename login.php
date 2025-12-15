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

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!file_exists('usuarios.json')) {
        $mensaje = $t['msg_no_usuarios'];
    } else {
        $usuarios = json_decode(file_get_contents('usuarios.json'), true);
        $encontrado = false;

        foreach ($usuarios as $usuario) {
            if ($usuario['email'] === $email && isset($usuario['password']) && password_verify($password, $usuario['password'])) {
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $encontrado = true;
                header("Location: index.php?lang=" . urlencode($lang));
                exit;
            }
        }

        if (!$encontrado) {
            $mensaje = $t['msg_error_login'];
        }
    }
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
    /* ... Tus estilos CSS aquí (mismos que antes, solo el header y nav-items cambian un poco en funcionalidad) ... */
    :root { --color-primary: #0071e3; --color-background: #ffffff; --color-card: #f9f9f9; --color-text: #1d1d1f; --color-text-secondary: #86868b; --color-border: #d2d2d7; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background-color: var(--color-background); color: var(--color-text); padding-top: 48px; }
    header { position: fixed; top: 0; width: 100%; background: rgba(255, 255, 255, 0.95); border-bottom: 1px solid var(--color-border); height: 48px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; backdrop-filter: blur(10px); z-index: 1000; }
    .logo a { color: var(--color-text); font-weight: 700; font-size: 1rem; text-decoration: none; }
    .nav-items a { color: var(--color-text); font-size: 0.8rem; text-decoration: none; opacity: 0.8; margin-left: 20px;}
    /* ... (resto de estilos de login-container, h2, inputs, buttons, etc. son los mismos) ... */
    .login-container { max-width: 400px; background: var(--color-card); border: 1px solid var(--color-border); border-radius: 15px; margin: 80px auto 40px; padding: 30px 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    h2 { text-align: center; font-weight: 600; font-size: 1.5rem; margin-bottom: 30px; color: var(--color-text); }
    label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem; color: var(--color-text-secondary); }
    input[type="email"], input[type="password"] { width: 100%; padding: 12px 14px; margin-bottom: 20px; border: 1px solid var(--color-border); border-radius: 8px; font-size: 1rem; background: var(--color-background); color: var(--color-text); transition: border-color 0.3s ease, box-shadow 0.3s ease; }
    input:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 1px var(--color-primary); }
    button { width: 100%; background-color: var(--color-primary); color: white; border: none; border-radius: 8px; padding: 14px 0; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease; }
    button:hover { background-color: #005bb5; }
    .mensaje-error { background-color: #ffdddd; border: 1px solid #ff5c5c; color: #a70000; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; text-align: center; }
    .registro-link { display: block; margin-top: 15px; text-align: center; color: var(--color-primary); font-weight: 400; text-decoration: none; font-size: 0.9rem; transition: text-decoration 0.3s; }
    .registro-link:hover { text-decoration: underline; }
</style>
</head>
<body>
<!-- INICIO HEADER DINÁMICO Y CORREGIDO -->
<header>
  <div class="logo">
    <a href="index.php?lang=<?php echo htmlspecialchars($lang); ?>"> Aura Store</a>
  </div>
  <div class="nav-items">
      <!-- Enlace simple para salir de la página de login -->
      <a href="index.php?lang=<?php echo htmlspecialchars($lang); ?>"><?php echo htmlspecialchars($t['ir_tienda']); ?></a>

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

<div class="login-container">
  <h2><?php echo htmlspecialchars($t['iniciar_sesion']); ?></h2>
  <?php if ($mensaje): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($mensaje); ?></div>
  <?php endif; ?>
  <form method="POST" action="login.php?lang=<?php echo htmlspecialchars($lang); ?>">
    <label for="email"><?php echo htmlspecialchars($t['email_label']); ?></label>
    <input type="email" id="email" name="email" required autofocus>

    <label for="password"><?php echo htmlspecialchars($t['contrasena_label']); ?></label>
    <input type="password" id="password" name="password" required>

    <button type="submit"><?php echo htmlspecialchars($t['entrar_btn']); ?></button>
  </form>
  <a class="registro-link" href="registro.php?lang=<?php echo htmlspecialchars($lang); ?>"><?php echo htmlspecialchars($t['no_cuenta']); ?></a>
  <a class="registro-link" href="recuperar.php?lang=<?php echo htmlspecialchars($lang); ?>"><?php echo htmlspecialchars($t['olvido_contrasena']); ?></a>
</div>

</body>
</html>
