<?php
session_start();

$lang = $_GET['lang'] ?? 'es';

// --- AVISO IMPORTANTE: ESTOS DATOS DEBEN SER IGUALES QUE EN INDEX.PHP Y DETALLE.PHP ---
// Idealmente, deberías mover esto a un archivo separado (ej. 'datos.inc.php') y usar 'require_once'.
$productos_temp = [
    // Usamos IDs de ejemplo que coinciden con los del index/detalle si es necesario
    1 => ['nombre' => 'MacBook Air M3 13"', 'imagen' => 'macbook-air-m3-13in-midnight-config-202402', 'precio' => 999],
    2 => ['nombre' => 'iPhone 15 Pro', 'imagen' => 'iphone-15-pro-black-titanium-select-202309-ecommerce', 'precio' => 999],
    // Añade el resto de tus productos aquí para que la página funcione
];
$productos = $productos_temp; // Usamos esto por simplicidad

// Traducciones e info de idiomas
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
$moneda = $idiomas[$lang]['moneda_simbolo'];


if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = [];
foreach ($_SESSION['carrito'] as $item) {
    if (isset($item['id'])) {
        $carrito[$item['id']] = $item;
    }
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['actualizar_cantidades'])) {
        if (isset($_POST['cantidades']) && is_array($_POST['cantidades'])) {
            foreach ($_POST['cantidades'] as $id => $cantidad) {
                $id = intval($id);
                $cantidad = max(0, intval($cantidad));

                if (isset($carrito[$id])) {
                    if ($cantidad === 0) {
                        unset($carrito[$id]);
                    } else {
                        $carrito[$id]['cantidad'] = $cantidad;
                    }
                }
            }
            $_SESSION['carrito'] = array_values($carrito);
            $mensaje = $t['msg_actualizado'];
        }
    } elseif (isset($_POST['eliminar'])) {
        $idEliminar = intval($_POST['eliminar']);
        if (isset($carrito[$idEliminar])) {
            unset($carrito[$idEliminar]);
            $_SESSION['carrito'] = array_values($carrito);
            $mensaje = $t['msg_eliminado'];
        }
    } elseif (isset($_POST['pagar'])) {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $metodo_pago = $_POST['metodo_pago'] ?? '';

        if ($nombre === '' || $email === '' || $direccion === '' || $metodo_pago === '' || empty($carrito)) {
            $mensaje = $t['msg_error_pago'];
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensaje = $t['msg_email_invalido'];
        } else {
            // Simulación de pago
            $_SESSION['carrito'] = [];
            $mensaje = sprintf($t['msg_gracias'], htmlspecialchars($nombre));
        }
    }
    header('Location: carrito.php?lang=' . urlencode($lang) . '&mensaje=' . urlencode($mensaje));
    exit;
}

if (isset($_GET['mensaje'])) {
    $mensaje = urldecode($_GET['mensaje']);
}

$subtotal = 0;
foreach ($carrito as $item) {
    if (isset($productos[$item['id']])) {
        $subtotal += $productos[$item['id']]['precio'] * $item['cantidad'];
    }
}

$costoEnvio = $subtotal > 0 && $subtotal < 500 ? 20.00 : 0.00;
$impuestos = $subtotal * 0.21;
$totalFinal = $subtotal + $costoEnvio + $impuestos;
$cantidadCarrito = isset($_SESSION['carrito']) ? array_sum(array_column($_SESSION['carrito'], 'cantidad')) : 0;
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo htmlspecialchars($t['titulo_carrito']); ?> - Aura Store</title>
<link href="fonts.googleapis.com" rel="stylesheet">
<style>
    /* ... Tus estilos CSS aquí (son los mismos que me enviaste) ... */
     :root {
        --color-primary: #0071e3; --color-background: #ffffff; --color-card: #f9f9f9;
        --color-text: #1d1d1f; --color-text-secondary: #86868b; --color-border: #d2d2d7;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background-color: var(--color-background); color: var(--color-text); padding-top: 48px; }
    header { position: fixed; top: 0; width: 100%; background: rgba(255, 255, 255, 0.95); border-bottom: 1px solid var(--color-border); height: 48px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; backdrop-filter: blur(10px); z-index: 1000; }
    .logo a { font-weight: 700; font-size: 1rem; color: var(--color-text); text-decoration: none; }
    .nav-items { display: flex; align-items: center; gap: 20px; }
    .nav-items a { color: var(--color-text); font-size: 0.8rem; text-decoration: none; opacity: 0.8; }
    .main-container { max-width: 1000px; margin: 0 auto; padding: 40px 20px; }
    h1 { text-align: center; margin-bottom: 40px; font-size: 2rem; font-weight: 600; color: var(--color-text); }
    .mensaje { background-color: var(--color-card); border-radius: 12px; padding: 15px; text-align: center; margin-bottom: 20px; color: var(--color-text); border: 1px solid var(--color-border); }
    .carrito-wrapper { display: flex; gap: 30px; }
    .productos-carrito { flex: 2; }
    .resumen-carrito { flex: 1; position: sticky; top: 68px; height: fit-content; background: var(--color-card); border: 1px solid var(--color-border); border-radius: 15px; padding: 20px; }
    .tabla-carrito { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .tabla-carrito thead th { text-align: left; color: var(--color-text-secondary); font-weight: 400; font-size: 0.9rem; padding: 10px 0; border-bottom: 1px solid var(--color-border); }
    .fila-producto { border-bottom: 1px solid var(--color-border); }
    .fila-producto td { padding: 20px 0; vertical-align: middle; }
    .producto-info { display: flex; align-items: center; gap: 15px; }
    .producto-info img { width: 80px; height: 80px; object-fit: contain; mix-blend-mode: darken; border-radius: 8px; border: 1px solid var(--color-border); background: white; }
    .producto-nombre { font-weight: 600; }
    .producto-precio-unidad, .producto-precio-total { font-weight: 600; color: var(--color-text-secondary); }
    .producto-precio-total { color: var(--color-text); }
    input[type="number"] { width: 60px; padding: 8px; border-radius: 8px; border: 1px solid var(--color-border); background: var(--color-background); color: var(--color-text); text-align: center; font-size: 1rem; appearance: none; margin: 0; }
    input[type="number"]:focus { outline: none; border-color: var(--color-primary); }
    .btn-eliminar { background: none; border: none; color: var(--color-text-secondary); cursor: pointer; font-size: 1.2rem; transition: color 0.3s; }
    .btn-eliminar:hover { color: #ff3b3b; }
    .botones-carrito { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
    .btn-continuar-compra, .btn-actualizar { background: var(--color-background); color: var(--color-text); padding: 10px 20px; border: 1px solid var(--color-border); border-radius: 12px; cursor: pointer; transition: background-color 0.3s; font-weight: 600; }
    .btn-actualizar { background-color: var(--color-primary); color: white; border-color: var(--color-primary); }
    .resumen-carrito h2 { font-size: 1.2rem; font-weight: 600; margin-bottom: 20px; }
    .resumen-linea { display: flex; justify-content: space-between; margin-bottom: 10px; color: var(--color-text-secondary); font-size: 0.9rem; }
    .resumen-total { display: flex; justify-content: space-between; font-size: 1.4rem; font-weight: 700; margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--color-border); }
    .form-pago input, .form-pago select, .form-pago textarea { width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 8px; background: var(--color-background); color: var(--color-text); margin-bottom: 15px; font-size: 1rem; }
    .form-pago input:focus, .form-pago select:focus, .form-pago textarea:focus { outline: none; border-color: var(--color-primary); }
    .form-pago button { width: 100%; padding: 12px; border: none; border-radius: 8px; background-color: var(--color-primary); color: white; font-size: 1rem; font-weight: 600; cursor: pointer; }
    @media (max-width: 900px) { .carrito-wrapper { flex-direction: column; } .resumen-carrito { position: static; } }
</style>
</head>
<body>

    <!-- INICIO HEADER DINÁMICO INTEGRADO -->
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
        
        <!-- El selector de idioma aquí es complicado porque no sabe en qué página está, lo dejamos simple -->
         <select onchange="window.location.href='?lang='+this.value">
            <?php foreach ($idiomas as $codigo => $datos): ?>
                <option value="<?php echo htmlspecialchars($codigo); ?>" <?php echo ($lang ?? 'es') === $codigo ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($datos['bandera']) . ' ' . htmlspecialchars($datos['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>
      </div>
    </header>
    <!-- FIN HEADER DINÁMICO -->

    <div class="main-container">
        <h1><?php echo htmlspecialchars($t['titulo_carrito']); ?></h1>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <?php if (empty($carrito)): ?>
            <p style="text-align: center; font-size: 1.2rem;"><?php echo htmlspecialchars($t['carrito_vacio']); ?></p>
        <?php else: ?>
            <div class="carrito-wrapper">
                <div class="productos-carrito">
                    <form action="carrito.php?lang=<?php echo htmlspecialchars($lang); ?>" method="post" id="form-carrito">
                        <table class="tabla-carrito">
                            <thead>
                                <tr>
                                    <th><?php echo htmlspecialchars($t['producto_col']); ?></th>
                                    <th><?php echo htmlspecialchars($t['precio_col']); ?></th>
                                    <th><?php echo htmlspecialchars($t['cantidad_col']); ?></th>
                                    <th style="text-align: right;"><?php echo htmlspecialchars($t['total_col']); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carrito as $item): ?>
                                    <?php if (isset($productos[$item['id']])): ?>
                                        <?php
                                            $infoProducto = $productos[$item['id']];
                                            $subtotal_producto = $infoProducto['precio'] * $item['cantidad'];
                                        ?>
                                        <tr class="fila-producto">
                                            <td>
                                                <div class="producto-info">
                                                    <!-- Ruta de imagen de ejemplo. Debes sustituirla. -->
                                                    <img src="store.storeimages.cdn-apple.com<?php echo urlencode($infoProducto['imagen']); ?>?wid=80&hei=80" alt="<?php echo htmlspecialchars($infoProducto['nombre']); ?>">
                                                    <div class="producto-nombre"><?php echo htmlspecialchars($infoProducto['nombre']); ?></div>
                                                </div>
                                            </td>
                                            <td class="producto-precio-unidad"><?php echo htmlspecialchars($moneda); ?><?php echo number_format($infoProducto['precio'], 2); ?></td>
                                            <td>
                                                <input type="number" name="cantidades[<?php echo htmlspecialchars($item['id']); ?>]" value="<?php echo htmlspecialchars($item['cantidad']); ?>" min="0">
                                            </td>
                                            <td class="producto-precio-total" style="text-align: right;"><?php echo htmlspecialchars($moneda); ?><?php echo number_format($subtotal_producto, 2); ?></td>
                                            <td>
                                                <button type="submit" name="eliminar" value="<?php echo htmlspecialchars($item['id']); ?>" class="btn-eliminar" title="Eliminar producto">×</button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="botones-carrito">
                            <button type="button" class="btn-continuar-compra" onclick="window.location.href='index.php?lang=<?php echo htmlspecialchars($lang); ?>'"><?php echo htmlspecialchars($t['continuar_compra']); ?></button>
                            <button type="submit" name="actualizar_cantidades" class="btn-actualizar"><?php echo htmlspecialchars($t['actualizar_carrito']); ?></button>
                        </div>
                    </form>
                </div>

                <div class="resumen-carrito">
                    <h2><?php echo htmlspecialchars($t['resumen_pedido']); ?></h2>
                    <div class="resumen-linea">
                        <span><?php echo htmlspecialchars($t['subtotal']); ?>:</span>
                        <span><?php echo htmlspecialchars($moneda); ?><?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="resumen-linea">
                        <span><?php echo htmlspecialchars($t['envio']); ?>:</span>
                        <span><?php echo htmlspecialchars($moneda); ?><?php echo number_format($costoEnvio, 2); ?></span>
                    </div>
                     <div class="resumen-linea">
                        <span><?php echo htmlspecialchars($t['impuestos']); ?> (21%):</span>
                        <span><?php echo htmlspecialchars($moneda); ?><?php echo number_format($impuestos, 2); ?></span>
                    </div>
                    
                    <div class="resumen-total">
                        <span><?php echo htmlspecialchars($t['total_col']); ?>:</span>
                        <span><?php echo htmlspecialchars($moneda); ?><?php echo number_format($totalFinal, 2); ?></span>
                    </div>

                    <form action="carrito.php?lang=<?php echo htmlspecialchars($lang); ?>" method="post" class="form-pago" style="margin-top: 30px;">
                        <h3><?php echo htmlspecialchars($t['info_envio']); ?></h3>
                        <input type="text" name="nombre" placeholder="<?php echo htmlspecialchars($t['nombre_completo']); ?>" required>
                        <input type="email" name="email" placeholder="<?php echo htmlspecialchars($t['correo_electronico']); ?>" required>
                        <textarea name="direccion" placeholder="<?php echo htmlspecialchars($t['direccion_completa']); ?>" rows="3" required></textarea>
                        <select name="metodo_pago" required>
                            <option value="">-- <?php echo htmlspecialchars($t['metodo_pago']); ?> --</option>
                            <option value="tarjeta"><?php echo htmlspecialchars($t['tarjeta_credito']); ?></option>
                            <option value="paypal"><?php echo htmlspecialchars($t['paypal']); ?></option>
                        </select>
                        <button type="submit" name="pagar"><?php echo htmlspecialchars($t['pago_ahora']); ?></button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
