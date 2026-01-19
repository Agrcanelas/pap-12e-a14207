<?php
require_once 'config.php';

// --- SEGURIDAD: CONTROL DE ACCESO ---
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: index.php?error=acceso_denegado");
    exit;
}

$mensaje = "";
$producto_editar = null;

// 1. LÓGICA PARA CARGAR DATOS EN EL FORMULARIO (MODO EDICIÓN)
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id_editar]);
    $producto_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 2. LÓGICA PARA ELIMINAR
if (isset($_GET['eliminar'])) {
    $id_borrar = $_GET['eliminar'];
    $sql_borrar = "DELETE FROM productos WHERE id = ?";
    $stmt_borrar = $pdo->prepare($sql_borrar);
    if ($stmt_borrar->execute([$id_borrar])) {
        $mensaje = "✅ Producto eliminado con éxito.";
    }
}

/// 3. LÓGICA PARA GUARDAR (AÑADIR O ACTUALIZAR)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];
    $color = $_POST['color'];
    $stock = $_POST['stock']; 
    $id = $_POST['id'] ?? null;

    if ($_POST['accion'] == 'añadir') {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $nombre_foto = time() . "_" . basename($_FILES['foto']['name']);
            move_uploaded_file($_FILES['foto']['tmp_name'], "img/" . $nombre_foto);
            $nombre_db = pathinfo($nombre_foto, PATHINFO_FILENAME);

            $sql = "INSERT INTO productos (nombre, precio, categoria, color, imagen, stock) VALUES (?, ?, ?, ?, ?, ?)";
            $pdo->prepare($sql)->execute([$nombre, $precio, $categoria, $color, $nombre_db, $stock]);
            $mensaje = "✅ Producto añadido correctamente.";
        }
    } 
    elseif ($_POST['accion'] == 'editar') {
        $sql = "UPDATE productos SET nombre=?, precio=?, categoria=?, color=?, stock=? WHERE id=?";
        $pdo->prepare($sql)->execute([$nombre, $precio, $categoria, $color, $stock, $id]);
        $mensaje = "✅ Producto actualizado con éxito.";
        $producto_editar = null; 
    }
}

$todos_productos = $pdo->query("SELECT * FROM productos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Admin - Gestión Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #000; --blue: #007aff; --red: #ff3b30; --bg: #f5f5f7; --text: #1d1d1f; --gray: #86868b; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 40px; -webkit-font-smoothing: antialiased; }
        .container { max-width: 1100px; margin: auto; animation: fadeIn 0.8s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .header-admin { display: flex; justify-content: space-between; align-items: center; margin-bottom: 50px; }
        .badge-admin { background: var(--text); color: #fff; padding: 5px 12px; border-radius: 8px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .btn-view-store { text-decoration: none; background: #fff; color: #000; padding: 10px 20px; border-radius: 20px; font-weight: 600; font-size: 14px; border: 1px solid #d2d2d7; transition: 0.3s; }
        .card { background: white; padding: 40px; border-radius: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 40px; }
        .card h2 { margin-top: 0; font-size: 26px; letter-spacing: -1px; margin-bottom: 30px; font-weight: 600; }
        .grid-form { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .input-group { display: flex; flex-direction: column; gap: 8px; }
        .input-group label { font-size: 13px; font-weight: 600; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; }
        input, select { padding: 15px; border: 1px solid #d2d2d7; border-radius: 14px; font-size: 15px; outline: none; background: #fff; transition: border 0.3s; }
        .btn-submit { grid-column: span 2; background: var(--accent); color: white; border: none; padding: 18px; border-radius: 14px; cursor: pointer; font-weight: 600; font-size: 16px; transition: all 0.3s; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 15px 10px; border-bottom: 1px solid #f5f5f7; color: var(--gray); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 20px 10px; border-bottom: 1px solid #f5f5f7; vertical-align: middle; }
        .img-preview { width: 60px; height: 60px; border-radius: 12px; object-fit: contain; background: var(--bg); padding: 8px; }
        .btn-edit { color: var(--blue); text-decoration: none; font-weight: 600; margin-right: 20px; font-size: 13px; }
        .btn-delete { color: var(--red); text-decoration: none; font-weight: 600; font-size: 13px; }
        .alert { padding: 20px; background: #34c759; color: white; border-radius: 16px; margin-bottom: 30px; font-weight: 600; text-align: center; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .status-pend { background: #fff9c4; color: #856404; }
        .status-done { background: #dcfce7; color: #14532d; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-admin">
        <div>
            <h1>Admin Aura <span class="badge-admin">Gestión</span></h1>
            <p style="color: var(--gray); margin-top: 5px;">Hola, <strong><?php echo $_SESSION['usuario_nombre']; ?></strong>. Tienes el control total.</p>
        </div>
        <div style="display: flex; gap: 15px; align-items: center;">
            <a href="index.php" class="btn-view-store">Ver Tienda ➔</a>
            <a href="logout.php" style="color: var(--red); text-decoration: none; font-size: 14px; font-weight: 600;">Salir</a>
        </div>
    </div>

    <?php if($mensaje): ?>
        <div class="alert"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <div class="card">
        <h2><?php echo $producto_editar ? "Editar Producto" : "Nuevo Producto"; ?></h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data" class="grid-form">
            <input type="hidden" name="accion" value="<?php echo $producto_editar ? 'editar' : 'añadir'; ?>">
            <?php if($producto_editar): ?>
                <input type="hidden" name="id" value="<?php echo $producto_editar['id']; ?>">
            <?php endif; ?>
            
            <div class="input-group">
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Ej: MacBook Pro M3" value="<?php echo $producto_editar['nombre'] ?? ''; ?>" required>
            </div>
            
            <div class="input-group">
                <label>Precio (€)</label>
                <input type="number" name="precio" placeholder="0.00" step="0.01" value="<?php echo $producto_editar['precio'] ?? ''; ?>" required>
            </div>
            
            <div class="input-group">
                <label>Categoría</label>
                <select name="categoria">
                    <option value="iPhone" <?php echo (isset($producto_editar) && $producto_editar['categoria'] == 'iPhone') ? 'selected' : ''; ?>>iPhone</option>
                    <option value="Mac" <?php echo (isset($producto_editar) && $producto_editar['categoria'] == 'Mac') ? 'selected' : ''; ?>>Mac</option>
                    <option value="Watch" <?php echo (isset($producto_editar) && $producto_editar['categoria'] == 'Watch') ? 'selected' : ''; ?>>Watch</option>
                </select>
            </div>
            
            <div class="input-group">
                <label>Acabado / Color</label>
                <input type="text" name="color" placeholder="Ej: Medianoche" value="<?php echo $producto_editar['color'] ?? ''; ?>" required>
            </div>

            <div class="input-group">
                <label>Stock Disponible</label>
                <input type="number" name="stock" placeholder="0" value="<?php echo $producto_editar['stock'] ?? ''; ?>" required>
            </div>

            <div class="input-group">
                <label>Imagen (.jpg)</label>
                <input type="file" name="foto" accept="image/jpeg" <?php echo $producto_editar ? '' : 'required'; ?>>
            </div>

            <button type="submit" class="btn-submit">
                <?php echo $producto_editar ? "Actualizar Inventario" : "Publicar en Aura Stock"; ?>
            </button>
            
            <?php if($producto_editar): ?>
                <a href="admin.php" class="btn-cancel">Cancelar y volver</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <h2>Inventario (<?php echo count($todos_productos); ?> productos)</h2>
        <table>
            <thead>
                <tr>
                    <th>Vista</th>
                    <th>Detalles</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($todos_productos as $prod): ?>
                <tr>
                    <td>
                        <img src="img/<?php echo $prod['imagen']; ?>.jpg" class="img-preview" onerror="this.src='img/no-image.jpg'">
                    </td>
                    <td>
                        <div style="font-weight: 600; font-size: 15px;"><?php echo htmlspecialchars($prod['nombre']); ?></div>
                        <div style="font-size: 12px; color: var(--gray); margin-top: 3px;">
                            <span style="background: #f0f0f4; padding: 2px 6px; border-radius: 4px;"><?php echo $prod['categoria']; ?></span>
                        </div>
                    </td>
                    <td><strong><?php echo number_format($prod['precio'], 2, ',', '.'); ?>€</strong></td>
                    <td>
                        <span style="font-weight: 600; color: <?php echo ($prod['stock'] <= 0) ? 'var(--red)' : 'inherit'; ?>">
                            <?php echo $prod['stock']; ?> uds.
                        </span>
                    </td>
                    <td>
                        <a href="admin.php?editar=<?php echo $prod['id']; ?>" class="btn-edit">Editar</a>
                        <a href="admin.php?eliminar=<?php echo $prod['id']; ?>" class="btn-delete" onclick="return confirm('¿Eliminar definitivamente?')">Borrar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card" style="border-top: 5px solid #000;">
        <h2>Ventas Recientes</h2>
        <?php
        // MEJORA: Obtenemos los nombres de los productos comprados en la misma consulta
        $sql_pedidos = "SELECT p.*, u.nombre as cliente, 
                        (SELECT GROUP_CONCAT(CONCAT(cantidad, 'x ', nombre_producto) SEPARATOR ', ') 
                         FROM detalles_pedido WHERE pedido_id = p.id) as productos_comprados
                        FROM pedidos p 
                        JOIN usuarios u ON p.usuario_id = u.id 
                        ORDER BY p.fecha DESC LIMIT 10";
        $lista_pedidos = $pdo->query($sql_pedidos)->fetchAll();
        ?>
        <table>
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th>Cliente y Pedido</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista_pedidos as $ped): ?>
                <tr>
                    <td><code style="font-weight: 600;">#AS-<?php echo $ped['id']; ?></code></td>
                    <td>
                        <div style="font-weight: 600;"><?php echo htmlspecialchars($ped['cliente']); ?></div>
                        <div style="font-size: 11px; color: var(--blue); margin-top: 4px; line-height: 1.2;">
                            📦 <?php echo htmlspecialchars($ped['productos_comprados'] ?? 'Detalle no disponible'); ?>
                        </div>
                    </td>
                    <td style="font-size: 13px; color: var(--gray);"><?php echo date('d/m/y H:i', strtotime($ped['fecha'])); ?></td>
                    <td><strong><?php echo number_format($ped['total'], 2, ',', '.'); ?>€</strong></td>
                    <td>
                        <span class="status-badge <?php echo ($ped['estado'] == 'Entregado') ? 'status-done' : 'status-pend'; ?>">
                            <?php echo $ped['estado']; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<footer style="text-align: center; padding: 40px; color: var(--gray); font-size: 12px;">
    Aura Stock Management System v2.1 — 2026
</footer>

</body>
</html>