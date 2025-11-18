<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../HTML/index.php");
    exit();
}

// Conexão
$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

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

<form method="POST">
    <title>Adicionar Produto</title>
    <h1>➕ Adicionar Produto</h1>

    Nome: <input type="text" name="nome" required><br><br>
    Descrição: <textarea name="descricao" required></textarea><br><br>
    Preço: <input type="number" name="preco" step="0.01" required><br><br>
    Estoque: <input type="number" name="estoque" required><br><br>
    Categoria: <input type="text" name="categoria" required><br><br>

    <button type="submit">Salvar</button>
</form>
