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

    // Query para obter pedidos com informação do cliente
    $stmt = $pdo->query("
        SELECT p.*, c.nome as nome_cliente 
        FROM pedido p 
        LEFT JOIN cliente c ON p.id_cliente = c.id_cliente 
        ORDER BY p.data_pedido DESC
    ");
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerir Pedidos</title>
    <link rel="stylesheet" href="../CSS/gerir_pedidos.css">
</head>
<body>

<h1>📦 Gerir Pedidos</h1>

<a href="dashboard.php">⬅ Voltar ao Painel</a>
<br><br>
<a href="criar_pedido.php">
    <button>➕ Criar Novo Pedido</button>
</a>
<br><br>

<table>
    <tr>
        <th>ID Pedido</th>
        <th>Cliente</th>
        <th>Data do Pedido</th>
        <th>Status</th>
        <th>Total (€)</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($pedidos as $pedido): ?>
    <tr>
        <td><?= $pedido['id_pedido']; ?></td>
        <td><?= $pedido['nome_cliente'] ?: 'Cliente não encontrado'; ?></td>
        <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
        <td>
            <?php 
            $statusClass = 'status-' . strtolower($pedido['status']);
            echo "<span class='$statusClass'>" . $pedido['status'] . "</span>";
            ?>
        </td>
        <td><?= number_format($pedido['total'], 2, ',', '.'); ?> €</td>
        <td>
            <a href="editar_pedido.php?id=<?= $pedido['id_pedido']; ?>">✏ Editar</a>
            |
            <a href="remover_pedido.php?id=<?= $pedido['id_pedido']; ?>" 
               onclick="return confirm('Tem certeza que deseja remover este pedido?')">🗑 Remover</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>