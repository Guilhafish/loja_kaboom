<?php
session_start();

// ----------------------
// 1️⃣ Verifica login
// ----------------------
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
    echo "<script>alert('Precisas estar logado como cliente para adicionar ao carrinho!'); window.location.href='login_index.html';</script>";
    exit();
}

// ----------------------
// 2️⃣ Conexão com o banco
// ----------------------
$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na ligação: " . $e->getMessage());
}

// ----------------------
// 3️⃣ Recebe ID do produto e quantidade
// ----------------------
$id_produto = $_GET['id'] ?? null;
$quantidade = 1; // padrão 1

if (!$id_produto || !is_numeric($id_produto) || $quantidade < 1) {
    echo "<script>alert('Dados inválidos!'); window.history.back();</script>";
    exit();
}

// ----------------------
// 4️⃣ Busca dados do produto no banco
// ----------------------
$stmt = $pdo->prepare("SELECT nome, preco FROM produto WHERE id_produto = :id LIMIT 1");
$stmt->execute([':id' => $id_produto]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo "<script>alert('Produto não encontrado!'); window.history.back();</script>";
    exit();
}

// ----------------------
// 5️⃣ Cria carrinho se não existir
// ----------------------
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// ----------------------
// 6️⃣ Adiciona ou atualiza o produto no carrinho
// ----------------------
if (isset($_SESSION['carrinho'][$id_produto])) {
    $_SESSION['carrinho'][$id_produto]['quantidade'] += $quantidade;
} else {
    $_SESSION['carrinho'][$id_produto] = [
        'nome' => $produto['nome'],
        'preco' => $produto['preco'],
        'quantidade' => $quantidade
    ];
}

// ----------------------
// 7️⃣ Redireciona de volta para a página anterior
// ----------------------
$referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $referer");
exit();
?>
