<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../HTML/index.php");
    exit();
}

$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['id'])) {
        die("Pedido inválido.");
    }
    $id_pedido = $_GET['id'];

    // Se for cliente, pegar o ID do cliente
    $id_cliente = null;
    if ($_SESSION['tipo'] === 'cliente') {
        $stmtCliente = $pdo->prepare("SELECT id_cliente FROM cliente WHERE nome = :nome LIMIT 1");
        $stmtCliente->execute([':nome' => $_SESSION['user']]);
        $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);
        if (!$cliente) {
            die("Cliente não encontrado.");
        }
        $id_cliente = $cliente['id_cliente'];
    }

    // Buscar informações do pedido
    $queryPedido = "
        SELECT p.*, c.nome AS cliente_nome, c.email
        FROM pedido p
        LEFT JOIN cliente c ON p.id_cliente = c.id_cliente
        WHERE p.id_pedido = :id
    ";

    if ($_SESSION['tipo'] === 'cliente') {
        $queryPedido .= " AND p.id_cliente = :id_cliente";
        $stmtPedido = $pdo->prepare($queryPedido);
        $stmtPedido->execute([':id' => $id_pedido, ':id_cliente' => $id_cliente]);
    } else {
        $stmtPedido = $pdo->prepare($queryPedido);
        $stmtPedido->execute([':id' => $id_pedido]);
    }

    $pedido = $stmtPedido->fetch(PDO::FETCH_ASSOC);
    if (!$pedido) {
        die("Pedido não encontrado ou sem permissão para ver.");
    }

    // Buscar itens do pedido
    $stmtItens = $pdo->prepare("
        SELECT i.*, pr.nome AS produto_nome
        FROM itempedido i
        LEFT JOIN produto pr ON pr.id_produto = i.id_produto
        WHERE i.id_pedido = :id
    ");
    $stmtItens->execute([':id' => $id_pedido]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Detalhes do Pedido</title>
<link rel="stylesheet" href="../CSS/visualizar_pedido.css">
</head>
<body>

<h1>📄 Detalhes do Pedido #<?= $pedido['id_pedido']; ?></h1>

<a href="<?= $_SESSION['tipo'] === 'admin' ? 'gerir_pedidos.php' : 'meus_pedidos.php'; ?>" class="btn-vermelho">⬅ Voltar</a>

<h2>👤 Cliente</h2>
<p><strong>Nome:</strong> <?= $pedido['cliente_nome']; ?></p>
<p><strong>Email:</strong> <?= $pedido['email']; ?></p>
<p><strong>Data:</strong> <?= $pedido['data_pedido']; ?></p>
<p><strong>Status:</strong> <?= $pedido['status']; ?></p>
<p><strong>Total:</strong> €<?= number_format($pedido['total'], 2); ?></p>

<h2>🛒 Itens do Pedido</h2>

<table border="1" cellpadding="6">
    <tr>
        <th>Produto</th>
        <th>Quantidade</th>
        <th>Preço Unitário (€)</th>
        <th>Subtotal (€)</th>
    </tr>

    <?php foreach ($itens as $item): ?>
    <tr>
        <td><?= $item['produto_nome']; ?></td>
        <td><?= $item['quantidade']; ?></td>
        <td><?= number_format($item['preco_unitario'], 2); ?></td>
        <td><?= number_format($item['preco_unitario'] * $item['quantidade'], 2); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
