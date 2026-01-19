<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo_cupon'];

    $stmt = $pdo->prepare("SELECT * FROM cupones WHERE codigo = ? AND activo = 1");
    $stmt->execute([$codigo]);
    $cupon = $stmt->fetch();

    if ($cupon) {
        $_SESSION['descuento'] = $cupon['descuento_porcentaje'];
        header("Location: carrito.php?status=cupon_aplicado");
    } else {
        header("Location: carrito.php?error=cupon_invalido");
    }
}