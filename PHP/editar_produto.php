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

// Captura o ID do produto
$id = $_GET['id'];

// Buscar produto
$stmt = $pdo->prepare("SELECT * FROM produto WHERE id_produto = :id");
$stmt->execute(["id" => $id]);
$produto = $stmt->fetch();

// Processa o formulário
if ($_POST) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $categoria = $_POST['categoria'];

    $update = $pdo->prepare("UPDATE produto 
        SET nome = :nome, descricao = :descricao, preco = :preco,
            estoque = :estoque, categoria = :categoria 
        WHERE id_produto = :id");
    
    $update->execute([
        ":nome" => $nome,
        ":descricao" => $descricao,
        ":preco" => $preco,
        ":estoque" => $estoque,
        ":categoria" => $categoria,
        ":id" => $id
    ]);

    header("Location: gerir_produtos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../CSS/editar_produto.css">
</head>
<body>

<!-- Botão voltar -->
<a href="gerir_produtos.php" class="button-voltar">
    <button type="button">⬅ Voltar</button>
</a>

<form method="POST">
    <h1>✏ Editar Produto</h1>

    <label>Nome:</label>
    <input type="text" name="nome" value="<?= $produto['nome'] ?>" required>

    <label>Descrição:</label>
    <textarea name="descricao" required><?= $produto['descricao'] ?></textarea>

    <label>Preço:</label>
    <input type="number" name="preco" step="0.01" value="<?= $produto['preco'] ?>" required>

    <label>Estoque:</label>
    <input type="number" name="estoque" value="<?= $produto['estoque'] ?>" required>

    <label>Categoria:</label>
    <input type="text" name="categoria" value="<?= $produto['categoria'] ?>" required>

    <button type="submit">Guardar Alterações</button>
</form>

</body>
</html>
