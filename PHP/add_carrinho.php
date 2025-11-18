<?php
session_start();

// Verifica se o utilizador está logado
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
    echo "<script>alert('Precisas estar logado como cliente para adicionar ao carrinho!'); window.location.href='login_index.php';</script>";
    exit();
}

// Recebe dados do formulário (produto e quantidade)
$id_produto = $_POST['id_produto'] ?? null;
$quantidade = $_POST['quantidade'] ?? 1; // padrão 1

// Validação básica
if (!$id_produto || $quantidade < 1) {
    echo "<script>alert('Dados inválidos!'); window.history.back();</script>";
    exit();
}

// Cria carrinho na sessão se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Se produto já existe no carrinho → atualiza quantidade
if (isset($_SESSION['carrinho'][$id_produto])) {
    $_SESSION['carrinho'][$id_produto] += $quantidade;
} else {
    // Adiciona produto novo (nome e preço podem ser preenchidos depois)
    $_SESSION['carrinho'][$id_produto] = [
        'nome' => "Produto $id_produto", // temporário
        'preco' => 0,                    // temporário
        'quantidade' => $quantidade
    ];
}

// Redireciona de volta para a página da categoria ou index
header("Location: index.php");
exit();
?>
