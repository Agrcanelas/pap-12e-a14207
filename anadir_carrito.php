<?php
session_start();

// Este script solo procesa la adición al carrito y redirige.
// Asume que los datos de productos son consistentes o se cargan aquí si es necesario.

$variante_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$lang = filter_input(INPUT_GET, 'lang', FILTER_SANITIZE_STRING) ?? 'es';

if (!$variante_id) {
    // Si no hay ID válido, redirigir a la página principal
    header("Location: index.php?lang=" . urlencode($lang));
    exit;
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$producto_anadido = false;

// Comprobar si el producto ya está en el carrito para aumentar la cantidad
foreach ($_SESSION['carrito'] as $key => $item) {
    if ($item['id'] == $variante_id) {
        $_SESSION['carrito'][$key]['cantidad']++;
        $producto_anadido = true;
        break;
    }
}

// Si no estaba, añadirlo como nuevo ítem
if (!$producto_anadido) {
    $_SESSION['carrito'][] = [
        'id' => $variante_id,
        'cantidad' => 1
    ];
}

// Redirigir de vuelta a la página anterior o al index
// Puedes usar $_SERVER['HTTP_REFERER'] si quieres que regrese exactamente a la página de detalle
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: index.php?lang=" . urlencode($lang));
}
exit;
?>
