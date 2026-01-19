<?php
session_start();
session_destroy(); // Apaga a sessão do utilizador
header("Location: index.php"); // Redireciona para a página inicial
exit;
?>