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

    $stmt = $pdo->query("SELECT * FROM cliente ORDER BY id_cliente ASC");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerir Clientes</title>
    <link rel="stylesheet" href="../CSS/gerir_clientes.css">
</head>
<body>

<h1>👥 Gerir Clientes</h1>

<a href="dashboard.php">⬅ Voltar ao Painel</a>
<br><br>
<a href="adicionar_cliente.php">
    <button>➕ Adicionar Cliente</button>
</a>
<br><br>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Senha</th>
        <th>Telefone</th>
        <th>Endereço</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($clientes as $c): ?>
    <tr>
        <td><?= $c['id_cliente']; ?></td>
        <td><?= $c['nome']; ?></td>
        <td><?= $c['email']; ?></td>
        <td><?= $c['senha']; ?></td>
        <td><?= $c['telefone']; ?></td>
        <td><?= $c['endereco']; ?></td>
        <td>
            <a href="editar_cliente.php?id=<?= $c['id_cliente']; ?>">✏ Editar</a>
            |
            <a href="remover_cliente.php?id=<?= $c['id_cliente']; ?>" onclick="return confirm('Tem certeza que deseja remover este cliente?')">🗑 Remover</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>