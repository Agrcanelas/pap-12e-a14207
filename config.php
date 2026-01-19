<?php
session_start();

$host = 'localhost';
$db   = 'aura_store';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Configuración general
$nombre_tienda = "Aura Store";

function formatear_precio($precio) {
    return "€" . number_format($precio, 2);
}
?>