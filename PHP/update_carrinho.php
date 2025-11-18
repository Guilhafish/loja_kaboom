<?php
session_start();

// Verifica se cliente está logado
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
    echo "<script>alert('Precisas estar logado como cliente!'); window.location.href='login_index.php';</script>";
    exit();
}

// Recebe dados via POST
$id_produto = $_POST['id_produto'] ?? null;
$acao = $_POST['acao'] ?? null;

if (!$id_produto || !in_array($acao, ['add', 'remove'])) {
    echo "<script>alert('Dados inválidos!'); window.history.back();</script>";
    exit();
}

// Verifica se o carrinho existe
if (!isset($_SESSION['carrinho'][$id_produto])) {
    echo "<script>alert('Produto não existe no carrinho!'); window.history.back();</script>";
    exit();
}

// Executa ação
if ($acao === 'add') {
    $_SESSION['carrinho'][$id_produto]['quantidade'] += 1;
} elseif ($acao === 'remove') {
    $_SESSION['carrinho'][$id_produto]['quantidade'] -= 1;
    if ($_SESSION['carrinho'][$id_produto]['quantidade'] <= 0) {
        unset($_SESSION['carrinho'][$id_produto]);
    }
}

// Redireciona para o carrinho
header("Location: carrinho.php");
exit();
?>
