<?php
session_start();

// Verifica se cliente está logado
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
    echo "<script>alert('Precisas estar logado como cliente!'); window.location.href='login_index.php';</script>";
    exit();
}

// Recebe id do produto
$id_produto = $_POST['id_produto'] ?? null;

if ($id_produto && isset($_SESSION['carrinho'][$id_produto])) {
    unset($_SESSION['carrinho'][$id_produto]);
}

// Redireciona para o carrinho
header("Location: carrinho.php");
exit();
?>
