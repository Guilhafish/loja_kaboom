<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../HTML/index.php");
    exit();
}

$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

$id = $_GET['id'];

// Buscar produto
$stmt = $pdo->prepare("SELECT * FROM produto WHERE id_produto = :id");
$stmt->execute(["id" => $id]);
$produto = $stmt->fetch();

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

<form method="POST">
    <title>Editar Produto</title>
    <h1>✏ Editar Produto</h1>

    Nome: <input type="text" name="nome" value="<?= $produto['nome'] ?>" required><br><br>
    Descrição: <textarea name="descricao" required><?= $produto['descricao'] ?></textarea><br><br>
    Preço: <input type="number" name="preco" step="0.01" value="<?= $produto['preco'] ?>" required><br><br>
    Estoque: <input type="number" name="estoque" value="<?= $produto['estoque'] ?>" required><br><br>
    Categoria: <input type="text" name="categoria" value="<?= $produto['categoria'] ?>" required><br><br>

    <button type="submit">Guardar Alterações</button>
</form>
