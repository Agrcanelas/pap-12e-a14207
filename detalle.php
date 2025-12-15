<?php
ob_start();
session_start();

$lang = $_GET['lang'] ?? 'es';

// Traducciones e info de idiomas (Versión ampliada y corregida)
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


// --- DATOS DE PRODUCTOS (Actualizados con múltiples imágenes) ---
$productos = [
    1 => ['nombre'=>'MacBook Air M3 13"','categoria'=>'Mac','imagen_base'=>'macbook-air-m3-13in-midnight-config-202402','descripcion_corta'=>'Increíblemente fino, increíblemente rápido con chip M3.','descripcion_completa'=>'El MacBook Air con chip M3 es aún más portátil y potente. Con hasta 18 horas de batería, el MacBook Air tiene un diseño de aluminio 100% reciclado y una pantalla Liquid Retina de alta resolución.','caracteristicas'=>['Chip M3','8GB RAM','256GB SSD','Pantalla Retina Líquida de 13.6"','Batería de 18 horas'],'imagenes'=>['macbook-air-m3-13in-midnight-config-202402','macbook-air-m3-13in-midnight-config-202402-2','macbook-air-m3-13in-midnight-config-202402-3']],
    2 => ['nombre'=>'iPhone 15 Pro','categoria'=>'iPhone','imagen_base'=>'iphone-15-pro-black-titanium-select-202309-ecommerce','descripcion_corta'=>'Titanio. Tan robusto. Tan ligero. Tan Pro.','descripcion_completa'=>'El iPhone 15 Pro está fabricado con titanio de calidad aeroespacial. La asombrosa pantalla Super Retina XDR con ProMotion, el potente chip A17 Pro y el avanzado sistema de cámaras, lo convierten en una herramienta profesional para fotógrafos y creadores.','caracteristicas'=>['Chip A17 Pro','Cámaras Pro de 48MP','Botón de Acción personalizable','Pantalla ProMotion de 6.1"','USB 3 tipo C'],'imagenes'=>['iphone-15-pro-black-titanium-select-202309-ecommerce','iphone-15-pro-black-titanium-select-202309-ecommerce-2','iphone-15-pro-black-titanium-select-202309-ecommerce-3']],
];

$variantes = [
    ['id'=>101,'producto_id'=>1,'capacidad'=>'256GB','color'=>'Midnight (Negro)','precio'=>999,'imagen'=>'macbook-air-m3-13in-midnight-config-202402'],
    ['id'=>102,'producto_id'=>1,'capacidad'=>'512GB','color'=>'Midnight (Negro)','precio'=>1199,'imagen'=>'macbook-air-m3-13in-midnight-config-202402'],
    ['id'=>103,'producto_id'=>1,'capacidad'=>'256GB','color'=>'Starlight (Dorado)','precio'=>999,'imagen'=>'macbook-air-m3-13in-starlight-config-202402'],
    ['id'=>104,'producto_id'=>1,'capacidad'=>'512GB','color'=>'Starlight (Dorado)','precio'=>1199,'imagen'=>'macbook-air-m3-13in-starlight-config-202402'],
    ['id'=>201,'producto_id'=>2,'capacidad'=>'128GB','color'=>'Titanio Negro','precio'=>999,'imagen'=>'iphone-15-pro-black-titanium-select-202309-ecommerce'],
    ['id'=>202,'producto_id'=>2,'capacidad'=>'256GB','color'=>'Titanio Negro','precio'=>1099,'imagen'=>'iphone-15-pro-black-titanium-select-202309-ecommerce'],
    ['id'=>203,'producto_id'=>2,'capacidad'=>'128GB','color'=>'Titanio Blanco','precio'=>999,'imagen'=>'iphone-15-pro-white-titanium-select-202309-ecommerce'],
    ['id'=>204,'producto_id'=>2,'capacidad'=>'256GB','color'=>'Titanio Blanco','precio'=>1099,'imagen'=>'iphone-15-pro-white-titanium-select-202309-ecommerce'],
];
// --- FIN DATOS DE PRODUCTOS ---


// Lógica para encontrar la variante correcta
$variante_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$variante_actual = null;
foreach ($variantes as $v) {
    if ($v['id'] === $variante_id) {
        $variante_actual = $v;
        break;
    }
}

if (!$variante_actual) {
    header("Location: index.php?lang=" . urlencode($lang));
    exit;
}

$producto_actual = $productos[$variante_actual['producto_id']];
$cantidadCarrito = isset($_SESSION['carrito']) ? array_sum(array_column($_SESSION['carrito'], 'cantidad')) : 0;
$opciones_color = [];
$opciones_capacidad = [];
foreach ($variantes as $v) {
    if ($v['producto_id'] === $variante_actual['producto_id']) {
        $opciones_color[$v['color']] = true;
        $opciones_capacidad[$v['capacidad']] = true;
    }
}
$colores_unicos = array_keys($opciones_color);
$capacidades_unicas = array_keys($opciones_capacidad);
$variantes_json = json_encode(array_values(array_filter($variantes, fn($v) => $v['producto_id'] === $variante_actual['producto_id'])));
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($producto_actual['nombre']) . ' | ' . htmlspecialchars($t['titulo']); ?></title>
  <!-- RUTA CORREGIDA -->
  <link href="fonts.googleapis.com" rel="stylesheet">
  <style>
    /* ... Tus estilos CSS aquí (omitiendo por brevedad) ... */
    :root {
        --color-primary: #0071e3; --color-background: #ffffff; --color-card: #f9f9f9;
        --color-text: #1d1d1f; --color-text-secondary: #86868b; --color-border: #d2d2d7;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background-color: var(--color-background); color: var(--color-text); padding-top: 48px; }
    header { position: fixed; top: 0; box-sizing: border-box; width: 100%; background: rgba(255, 255, 255, 0.9); border-bottom: 1px solid var(--color-border); height: 48px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; z-index: 1000; backdrop-filter: blur(10px); }
    .logo a { color: var(--color-text); font-weight: 700; font-size: 1rem; text-decoration: none; cursor: pointer; }
    .nav-items { display: flex; align-items: center; gap: 20px; }
    .nav-items a { color: var(--color-text); font-size: 0.8rem; text-decoration: none; opacity: 0.8; }
    .main-container { max-width: 1000px; margin: 20px auto; padding: 20px; }
    .product-detail-container { display: flex; gap: 40px; margin-top: 40px; }
    .product-image-gallery { flex: 1; display: flex; flex-direction: column; gap: 10px; }
    .product-image-gallery .main-image { max-width: 100%; object-fit: contain; mix-blend-mode: darken; }
    .product-image-gallery .thumbnail-strip { display: flex; gap: 10px; overflow-x: auto; padding: 5px 0; }
    .product-image-gallery .thumbnail { width: 60px; height: 60px; object-fit: contain; border: 1px solid var(--color-border); border-radius: 8px; cursor: pointer; opacity: 0.6; transition: opacity 0.3s; }
    .product-image-gallery .thumbnail:hover, .product-image-gallery .thumbnail.active { opacity: 1; border-color: var(--color-primary); }
    .product-detail-info { flex: 1; }
    .product-detail-info h1 { font-size: 2.5rem; margin-bottom: 15px; }
    .price { font-size: 1.8rem; color: var(--color-primary); margin-bottom: 20px; font-weight: 600; }
    .option-selector { margin-bottom: 25px; }
    .option-selector label { display: block; font-weight: 600; margin-bottom: 10px; }
    .option-btn { padding: 10px 15px; border: 1px solid var(--color-border); background: var(--color-background); border-radius: 10px; cursor: pointer; margin-right: 10px; transition: all 0.3s ease; font-size: 0.9rem; }
    .option-btn:hover { border-color: var(--color-primary); }
    .option-btn.active { border-color: var(--color-primary); background-color: var(--color-primary); color: white; box-shadow: 0 0 0 1px var(--color-primary); }
    .btn-añadir { background-color: var(--color-primary); color: white; padding: 12px 25px; border: none; border-radius: 20px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; margin-top: 10px; transition: background-color 0.3s ease; }
    .btn-añadir:hover { background-color: #005bb5; }
    .back-link { margin-bottom: 20px; display: inline-block; color: var(--color-primary); text-decoration: none; font-size: 0.9rem; font-weight: 500; }
    .detail-meta { display: block; margin-bottom: 10px; color: var(--color-text-secondary); }
    .specs-section { margin-top: 40px; }
    .specs-section h3 { margin-bottom: 15px; }
    .features-list { list-style-type: none; margin-top: 15px; padding-left: 0; }
    .features-list li { margin-bottom: 10px; padding-left: 0; position: relative; color: var(--color-text); }
    .feature-icon { margin-right: 10px; color: var(--color-primary); }
    .related-products { margin-top: 60px; }
    .related-products h3 { margin-bottom: 20px; }
    .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 30px; }
    .related-card { background: var(--color-card); border-radius: 15px; border: 1px solid var(--color-border); padding: 15px; text-align: center; }
    @media (max-width: 768px) {
        .product-detail-container { flex-direction: column; text-align: center; }
        .product-detail-info { margin-top: 20px; }
        .product-image-gallery .main-image { max-width: 80%; }
        .btn-añadir { justify-content: center; }
    }
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

        <select onchange="window.location.href='?id=<?php echo htmlspecialchars($variante_id); ?>&lang='+this.value">
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
        <a href="index.php?lang=<?php echo htmlspecialchars($lang); ?>" class="back-link">← <?php echo htmlspecialchars($t['regresar_productos']); ?></a>

        <div class="product-detail-container">
            <!-- GALERÍA DE IMÁGENES -->
            <div class="product-image-gallery">
                <!-- Rutas de imagen de ejemplo. Debes sustituirlas. -->
                <img id="product-image" src="store.storeimages.cdn-apple.com<?php echo urlencode($variante_actual['imagen']); ?>?wid=1000&hei=1000&fmt=jpeg&qlt=95" alt="<?php echo htmlspecialchars($producto_actual['nombre']); ?>" class="main-image">
                
                <div class="thumbnail-strip">
                    <?php foreach ($producto_actual['imagenes'] as $img_name): ?>
                        <img src="store.storeimages.cdn-apple.com<?php echo urlencode($img_name); ?>?wid=100&hei=100&fmt=jpeg&qlt=95" class="thumbnail" data-full-image="store.storeimages.cdn-apple.com<?php echo urlencode($img_name); ?>?wid=1000&hei=1000&fmt=jpeg&qlt=95">
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="product-detail-info">
                <h1 id="product-name"><?php echo htmlspecialchars($producto_actual['nombre']); ?></h1>
                
                <span class="detail-meta">📦 <?php echo htmlspecialchars($t['categoria_producto']); ?>: <?php echo htmlspecialchars($producto_actual['categoria']); ?></span>
                
                <div class="price" id="product-price">💰 <?php echo htmlspecialchars($moneda); ?><?php echo number_format($variante_actual['precio'], 0, '.', ','); ?></div>
                
                <p><?php echo htmlspecialchars($producto_actual['descripcion_corta']); ?></p>

                <!-- SELECTOR DE COLOR -->
                <div class="option-selector">
                    <label><?php echo htmlspecialchars($t['seleccionar_color']); ?>: <span id="selected-color-name"><?php echo htmlspecialchars($variante_actual['color']); ?></span></label>
                    <?php foreach ($colores_unicos as $color): ?>
                        <button class="option-btn color-btn <?php echo ($color === $variante_actual['color']) ? 'active' : ''; ?>" data-color="<?php echo htmlspecialchars($color); ?>">
                            <?php echo htmlspecialchars($color); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- SELECTOR DE CAPACIDAD -->
                <div class="option-selector">
                    <label><?php echo htmlspecialchars($t['seleccionar_capacidad']); ?>: <span id="selected-capacity-name"><?php echo htmlspecialchars($variante_actual['capacidad']); ?></span></label>
                    <?php foreach ($capacidades_unicas as $capacidad): ?>
                        <button class="option-btn capacity-btn <?php echo ($capacidad === $variante_actual['capacidad']) ? 'active' : ''; ?>" data-capacity="<?php echo htmlspecialchars($capacidad); ?>">
                            <?php echo htmlspecialchars($capacidad); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <a href="anadir_carrito.php?id=<?php echo htmlspecialchars($variante_actual['id']); ?>&lang=<?php echo htmlspecialchars($lang); ?>" class="btn-añadir" id="add-to-cart-link">
                    🛒 <?php echo htmlspecialchars($t['comprar']); ?>
                </a>
            </div>
        </div>

        <!-- SECCIÓN DE DESCRIPCIÓN AMPLIADA Y ESPECIFICACIONES -->
        <div class="specs-section">
            <h3><?php echo htmlspecialchars($t['descripcion_ampliada']); ?></h3>
            <p><?php echo htmlspecialchars($producto_actual['descripcion_completa']); ?></p>
        </div>

        <!-- SECCIÓN DE PRODUCTOS RELACIONADOS (SIMULADA) -->
        <div class="related-products">
            <h3><?php echo htmlspecialchars($t['productos_relacionados']); ?></h3>
            <div class="related-grid">
                <?php 
                $productos_relacionados = array_filter($variantes, fn($v) => $productos[$v['producto_id']]['categoria'] === $producto_actual['categoria'] && $v['id'] !== $variante_actual['id']);
                $slice = array_slice($productos_relacionados, 0, 4);
                foreach ($slice as $relacionado):
                    $prod_rel = $productos[$relacionado['producto_id']];
                ?>
                <div class="related-card">
                    <a href="detalle.php?id=<?php echo htmlspecialchars($relacionado['id']); ?>&lang=<?php echo htmlspecialchars($lang); ?>">
                         <!-- Rutas de imagen de ejemplo. Debes sustituirlas. -->
                        <img src="store.storeimages.cdn-apple.com<?php echo urlencode($relacionado['imagen']); ?>?wid=200&hei=200&fmt=jpeg&qlt=95" alt="<?php echo htmlspecialchars($prod_rel['nombre']); ?>">
                        <h4><?php echo htmlspecialchars($prod_rel['nombre']); ?></h4>
                        <p><?php echo htmlspecialchars($relacionado['capacidad']); ?> | <?php echo htmlspecialchars($relacionado['color']); ?></p>
                        <span><?php echo htmlspecialchars($moneda); ?><?php echo number_format($relacionado['precio'], 0, '.', ','); ?></span>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // ... Tu script JS para la interactividad de variantes aquí (es el mismo que me enviaste antes) ...
        const allVariants = <?php echo $variantes_json; ?>;
        const currentLang = "<?php echo htmlspecialchars($lang); ?>";
        // URL base corregida de ejemplo:
        const imageBaseUrl = "store.storeimages.cdn-apple.com";
        const imageUrlParams = "?wid=1000&hei=1000&fmt=jpeg&qlt=95";

        let selectedColor = "<?php echo htmlspecialchars($variante_actual['color']); ?>";
        let selectedCapacity = "<?php echo htmlspecialchars($variante_actual['capacidad']); ?>";

        function updateProductDisplay() {
            const matchedVariant = allVariants.find(v => {
                return v.color === selectedColor && v.capacidad === selectedCapacity;
            });

            if (matchedVariant) {
                const newImageUrl = imageBaseUrl + matchedVariant.imagen + imageUrlParams;
                document.getElementById('product-image').src = newImageUrl;
                document.getElementById('product-price').innerHTML = `💰 <?php echo htmlspecialchars($moneda); ?>${matchedVariant.precio.toLocaleString('<?php echo htmlspecialchars($lang); ?>')}`;
                const cartLink = document.getElementById('add-to-cart-link');
                cartLink.href = `anadir_carrito.php?id=${matchedVariant.id}&lang=${currentLang}`;
                document.getElementById('selected-color-name').textContent = selectedColor;
                document.getElementById('selected-capacity-name').textContent = selectedCapacity;
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            const firstThumbnail = document.querySelector('.thumbnail-strip .thumbnail');
            if (firstThumbnail) {
                firstThumbnail.classList.add('active');
            }
        });

        document.querySelectorAll('.color-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.color-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                selectedColor = this.getAttribute('data-color');
                updateProductDisplay();
            });
        });

        document.querySelectorAll('.capacity-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.capacity-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                selectedCapacity = this.getAttribute('data-capacity');
                updateProductDisplay();
            });
        });

        document.querySelectorAll('.thumbnail').forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('product-image').src = this.getAttribute('data-full-image');
            });
        });

        updateProductDisplay();
    </script>

</body>
</html>
