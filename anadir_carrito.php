<?php
require_once 'config.php';

// Asegurarnos de que el carrito exista en la sesión
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $color = $_POST['color'] ?? 'Original';
    // Validamos que la cantidad sea al menos 1
    $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
    if ($cantidad < 1) { $cantidad = 1; }

    if ($id) {
        // Identificador único: permite tener el mismo producto en diferentes colores
        $key = $id . "_" . $color;
        
        if (isset($_SESSION['carrito'][$key])) {
            // Si ya existe esta combinación, sumamos la nueva cantidad
            $_SESSION['carrito'][$key]['cantidad'] += $cantidad;
        } else {
            // Si es nuevo, lo registramos
            $_SESSION['carrito'][$key] = [
                'id' => $id,
                'color' => $color,
                'cantidad' => $cantidad
            ];
        }
        
        // Redirigir con éxito
        header("Location: detalle.php?id=$id&success=1");
        exit;
    }
}

// Si alguien intenta entrar a este archivo sin enviar el formulario, lo mandamos al inicio
header("Location: index.php");
exit;