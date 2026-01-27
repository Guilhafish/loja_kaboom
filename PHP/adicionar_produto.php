<?php
session_start();

// Verifica se é admin
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../HTML/index.php");
    exit();
}

// Conexão com o banco
$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

// Processa o formulário
if ($_POST) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $categoria = $_POST['categoria'];

    $stmt = $pdo->prepare("INSERT INTO produto (nome, descricao, preco, estoque, categoria) 
                           VALUES (:nome, :descricao, :preco, :estoque, :categoria)");

    $stmt->execute([
        ":nome" => $nome,
        ":descricao" => $descricao,
        ":preco" => $preco,
        ":estoque" => $estoque,
        ":categoria" => $categoria
    ]);

    header("Location: gerir_produtos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta charset="UTF-8">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="../CSS/adicionar_produto.css">
</head>
<body>

<!-- Botão voltar -->
<a href="gerir_produtos.php" class="button-voltar">
    <button type="button">⬅ Voltar</button>
</a>

<form method="POST">
    <h1>➕ Adicionar Produto</h1>

    <label>Nome:</label>
    <input type="text" name="nome" required>

    <label>Descrição:</label>
    <textarea name="descricao" required></textarea>

    <label>Preço:</label>
    <input type="number" name="preco" step="0.01" required>

    <label>Estoque:</label>
    <input type="number" name="estoque" required>

    <label>Categoria:</label>
    <input type="text" name="categoria" required>

    <button type="submit">Salvar</button>
</form>

</body>
</html>
