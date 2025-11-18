<?php
session_start();

// Apenas admin pode aceder
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../HTML/index.php");
    exit();
}

// Conexão
$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar produtos
    $stmt = $pdo->query("SELECT * FROM produto ORDER BY id_produto ASC");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerir Produtos</title>
    <link rel="stylesheet" href="../CSS/gerir_produtos.css">
</head>
<body>

<h1>📦 Gerir Produtos</h1>

<a href="dashboard.php">⬅ Voltar ao Painel</a>
<br><br>
<a href="adicionar_produto.php">
    <button>➕ Adicionar Produto</button>
</a>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Preço (€)</th>
        <th>Estoque</th>
        <th>Categoria</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($produtos as $p): ?>
    <tr>
        <td><?php echo $p['id_produto']; ?></td>
        <td><?php echo $p['nome']; ?></td>
        <td><?php echo $p['descricao']; ?></td>
        <td><?php echo number_format($p['preco'], 2, ',', '.'); ?> €</td>
        <td><?php echo $p['estoque']; ?></td>
        <td><?php echo $p['categoria']; ?></td>

        <td>
            <a href="editar_produto.php?id=<?php echo $p['id_produto']; ?>">✏ Editar</a>
            |
            <a href="remover_produto.php?id=<?php echo $p['id_produto']; ?>" onclick="return confirm('Tem certeza que deseja remover este produto?')">🗑 Remover</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
