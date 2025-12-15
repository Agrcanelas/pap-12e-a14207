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
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists('usuarios.json')) {
        $usuarios = json_decode(file_get_contents('usuarios.json'), true);
        $existe = false;
        foreach ($usuarios as $usuario) {
            if ($usuario['email'] === $email) {
                $existe = true;
                break;
            }
        }
        if ($existe) {
            // Mensaje de éxito (simulado)
            $mensaje = sprintf($t['msg_enviado_exito'], htmlspecialchars($email));
        } else {
            $mensaje = $t['msg_email_no_existe'];
        }
    } else {
        $mensaje = $t['msg_no_usuarios'];
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
  /* --- Estilos al estilo Apple (Minimalista y Limpio) --- */
  :root {
      --color-primary: #0071e3; --color-background: #ffffff; --color-card: #f9f9f9;
      --color-text: #1d1d1f; --color-text-secondary: #86868b; --color-border: #d2d2d7;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
      font-family: 'Inter', sans-serif;
      background-color: var(--color-background);
      color: var(--color-text);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      padding: 20px;
      /* Ajuste si decides usar header fijo en esta página */
      /* padding-top: 48px; */
  }
  /* Si decides usar el header fijo, descomenta y ajusta el body margin/padding */
  /* header { position: fixed; top: 0; width: 100%; ... } */

  .container {
      max-width: 400px;
      background: var(--color-card);
      border: 1px solid var(--color-border);
      border-radius: 15px;
      padding: 30px 40px;
      color: var(--color-text);
      text-align: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  }
  h2 { font-weight: 600; font-size: 1.5rem; margin-bottom: 20px; color: var(--color-text); }
  p { color: var(--color-text-secondary); margin-bottom: 20px; font-size: 0.9rem; }
  input[type="email"] { width: 100%; padding: 12px 14px; margin-bottom: 20px; border: 1px solid var(--color-border); border-radius: 8px; background: var(--color-background); color: var(--color-text); transition: border-color 0.3s ease, box-shadow 0.3s ease; }
  input[type="email"]:focus { outline: none; border-color: var(--color-primary); box-shadow: 0 0 0 1px var(--color-primary); }
  button { width: 100%; background-color: var(--color-primary); color: white; border: none; border-radius: 8px; padding: 14px 0; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease; }
  button:hover { background-color: #005bb5; }
  .mensaje { margin-bottom: 20px; font-weight: 600; padding: 10px 15px; border-radius: 8px; }
  .mensaje.error { background-color: #ffdddd; border: 1px solid #ff5c5c; color: #a70000; }
  .mensaje.exito { background-color: #e0f7e0; border: 1px solid #4caf50; color: #1b5e20; }
  a { color: var(--color-primary); text-decoration: none; font-size: 0.9rem; }
  a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="container">
  <h2><?php echo htmlspecialchars($t['titulo_pagina']); ?></h2>
  <p><?php echo htmlspecialchars($t['instruccion']); ?></p>
  <?php if ($mensaje): ?>
    <div class="mensaje <?php echo (strpos($mensaje, 'enviado') !== false || strpos($mensaje, 'Revisa') !== false) ? 'exito' : 'error'; ?>">
      <?php echo htmlspecialchars($mensaje); ?>
    </div>
  <?php endif; ?>
  <form method="POST" action="recuperar.php?lang=<?php echo htmlspecialchars($lang); ?>">
    <input type="email" name="email" placeholder="<?php echo htmlspecialchars($t['tu_email']); ?>" required autofocus value="<?php echo htmlspecialchars($email); ?>">
    <button type="submit"><?php echo htmlspecialchars($t['enviar_enlace']); ?></button>
  </form>
  <p><a href="login.php?lang=<?php echo htmlspecialchars($lang); ?>"><?php echo htmlspecialchars($t['volver_login']); ?></a></p>
</div>

</body>
</html>
