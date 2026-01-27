<?php
session_start();

// Verifica se cliente está logado
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: login_index.php");
    exit();
}

// Conexão BD
$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar dados do cliente logado
    $stmtCliente = $pdo->prepare("SELECT * FROM Cliente WHERE nome = :nome LIMIT 1");
    $stmtCliente->execute([':nome' => $_SESSION['user']]);
    $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        echo "<script>alert('Cliente não encontrado!'); window.location.href='index.php';</script>";
        exit();
    }

    // Buscar pedidos do cliente
    $stmtPedidos = $pdo->prepare("
        SELECT *
        FROM Pedido
        WHERE id_cliente = :id
        ORDER BY data_pedido DESC
    ");
    $stmtPedidos->execute([':id' => $cliente['id_cliente']]);
    $pedidos = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta charset="UTF-8">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="../CSS/meus_pedidos.css">
</head>
<body>

<h1>📦 Os Meus Pedidos</h1>

<a href="dashboard.php" class="btn-vermelho">⬅ Voltar ao Dashboard</a>

<br><br>

<?php if (empty($pedidos)): ?>

    <p>Ainda não fizeste nenhum pedido!</p>

<?php else: ?>

<table>
    <tr>
        <th>ID Pedido</th>
        <th>Data</th>
        <th>Status</th>
        <th>Total (€)</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($pedidos as $pedido): ?>
    <tr>
        <td><?= $pedido['id_pedido']; ?></td>
        <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>

        <td>
            <?php 
                $statusClass = 'status-' . strtolower($pedido['status']);
                echo "<span class='$statusClass'>{$pedido['status']}</span>";
            ?>
        </td>

        <td><?= number_format($pedido['total'], 2, ',', '.'); ?> €</td>

        <td>
            <a href="visualizar_pedido.php?id=<?= $pedido['id_pedido']; ?>">🔍 Ver Itens</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php endif; ?>

</body>
</html>
