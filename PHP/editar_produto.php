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
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Captura o ID do produto
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Produto inválido.");
}

// Buscar produto
$stmt = $pdo->prepare("SELECT * FROM produto WHERE id_produto = :id");
$stmt->execute(["id" => $id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    $categoria = $_POST['categoria'];

    // 🚨 VALIDAÇÃO DE ESTOQUE
    if ($estoque < 0) {
        echo "<script>alert('O estoque não pode ser negativo!'); history.back();</script>";
        exit();
    }

    $update = $pdo->prepare("
        UPDATE produto 
        SET nome = :nome,
            descricao = :descricao,
            preco = :preco,
            estoque = :estoque,
            categoria = :categoria 
        WHERE id_produto = :id
    ");

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
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
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
    <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']); ?>" required>

    <label>Descrição:</label>
    <textarea name="descricao" required><?= htmlspecialchars($produto['descricao']); ?></textarea>

    <label>Preço:</label>
    <input type="number" name="preco" step="0.01" min="0" value="<?= $produto['preco']; ?>" required>

    <label>Estoque:</label>
    <input type="number" name="estoque" min="0" value="<?= $produto['estoque']; ?>" required>

    <label>Categoria:</label>
    <select name="categoria" required>
        <option value="Petardos" <?= $produto['categoria'] == 'Petardos' ? 'selected' : '' ?>>Petardos</option>
        <option value="Fumos" <?= $produto['categoria'] == 'Fumos' ? 'selected' : '' ?>>Fumos</option>
        <option value="Tochas" <?= $produto['categoria'] == 'Tochas' ? 'selected' : '' ?>>Tochas</option>
        <option value="Strobes" <?= $produto['categoria'] == 'Strobes' ? 'selected' : '' ?>>Strobes</option>
    </select>

    <button type="submit">Guardar Alterações</button>
</form>

</body>
</html>