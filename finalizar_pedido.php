<?php
require_once 'config.php';

/**
 * PROCESO DE FINALIZACIÓN DE PEDIDO (Lógica de Negocio)
 * Este archivo no muestra HTML, procesa la base de datos y redirige.
 */

// 1. Validaciones de seguridad
if (!isset($_SESSION['usuario_id'])) {
    // Si no está logueado, lo mandamos al login avisando que debe entrar para comprar
    header("Location: login.php?error=necesitas_loguearte");
    exit;
}

if (empty($_SESSION['carrito'])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$total = 0;

try {
    $pdo->beginTransaction();

    // 2. Calcular total real desde la base de datos (evita manipulaciones de precio en el cliente)
    foreach ($_SESSION['carrito'] as $item) {
        $stmt = $pdo->prepare("SELECT precio FROM productos WHERE id = ?");
        $stmt->execute([$item['id']]);
        $prod_db = $stmt->fetch();
        
        if ($prod_db) {
            $total += $prod_db['precio'] * $item['cantidad'];
        }
    }

    // 3. Crear el registro del Pedido
    // Asegúrate de que tu tabla 'pedidos' tenga: id, usuario_id, total, fecha (timestamp)
    $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total, fecha) VALUES (?, ?, NOW())");
    $stmt->execute([$usuario_id, $total]);
    $pedido_id = $pdo->lastInsertId();

    // 4. Insertar los detalles del pedido y ACTUALIZAR STOCK
    $stmt_detalle = $pdo->prepare("INSERT INTO detalles_pedido 
        (pedido_id, producto_id, nombre_producto, cantidad, precio_unitario, color) 
        VALUES (?, ?, ?, ?, ?, ?)");

    // Preparamos la consulta de actualización de stock una sola vez para ser más eficientes
    $stmt_update_stock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");

    foreach ($_SESSION['carrito'] as $item) {
        // Consultar info fresca del producto (incluyendo stock)
        $stmt_p = $pdo->prepare("SELECT nombre, precio, stock FROM productos WHERE id = ?");
        $stmt_p->execute([$item['id']]);
        $prod_info = $stmt_p->fetch();

        if ($prod_info) {
            // VERIFICACIÓN DE ÚLTIMO MOMENTO: ¿Hay stock suficiente?
            if ($prod_info['stock'] < $item['cantidad']) {
                throw new Exception("Stock insuficiente para el producto: " . $prod_info['nombre']);
            }

            // Insertamos el detalle
            $stmt_detalle->execute([
                $pedido_id,
                $item['id'],
                $prod_info['nombre'],
                $item['cantidad'],
                $prod_info['precio'],
                $item['color']
            ]);

            // RESTAR STOCK: Solo se ejecuta si el stock es suficiente
            $stmt_update_stock->execute([
                $item['cantidad'], 
                $item['id'], 
                $item['cantidad']
            ]);
        }
    }

    // 5. Todo salió bien, confirmamos cambios
    $pdo->commit();

    // 6. Limpieza de carrito
    unset($_SESSION['carrito']);

    // Redirección con mensaje de éxito (el cual ya tienes configurado en tu index.php)
    header("Location: index.php?status=compra_exitosa");
    exit;

} catch (Exception $e) {
    // Si algo falla, deshacemos todo para no dejar pedidos huérfanos
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Log del error y aviso al usuario
    error_log("Error en pedido: " . $e->getMessage());
    header("Location: carrito.php?error=error_procesamiento");
    exit;
}