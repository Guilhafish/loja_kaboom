<?php
session_start();

// Apenas clientes podem aceder
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
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

    // Buscar o cliente logado
    $stmtCliente = $pdo->prepare("SELECT * FROM cliente WHERE nome = :nome LIMIT 1");
    $stmtCliente->execute([':nome' => $_SESSION['user']]);
    $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        die("Erro ao carregar cliente.");
    }

    // Buscar pagamentos ligados aos pedidos do cliente
    $stmt = $pdo->prepare("
        SELECT 
            pg.id_pagamento,
            pg.id_pedido,
            pg.metodo,
            pg.valor,
            pg.data_pagamento,
            pg.status,
            p.data_pedido
        FROM pagamento pg
        INNER JOIN pedido p ON pg.id_pedido = p.id_pedido
        WHERE p.id_cliente = :id_cliente
        ORDER BY pg.data_pagamento DESC
    ");
    $stmt->execute([':id_cliente' => $cliente['id_cliente']]);
    $pagamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta charset="UTF-8">
    <title>Meus Pagamentos</title>
    <link rel="stylesheet" href="../CSS/meus_pagamentos.css">
</head>
<body>

<h1>💳 Meus Pagamentos</h1>

<a href="dashboard.php" class="voltar-btn">⬅ Voltar</a>
<br><br>

<?php if (empty($pagamentos)): ?>
    <p>❌ Ainda não realizaste pagamentos.</p>
<?php else: ?>

<table>
    <tr>
        <th>ID Pagamento</th>
        <th>ID Pedido</th>
        <th>Método</th>
        <th>Data Pagamento</th>
        <th>Status</th>
        <th>Valor (€)</th>
        <th>Detalhes</th>
    </tr>

    <?php foreach ($pagamentos as $pg): ?>
        <tr>

            <td><?= $pg['id_pagamento']; ?></td>

            <td><?= $pg['id_pedido']; ?></td>

            <td><?= $pg['metodo']; ?></td>

            <td><?= date("d/m/Y H:i", strtotime($pg['data_pagamento'])); ?></td>

            <td>
                <?php 
                    $class = "status-" . strtolower($pg['status']);
                    echo "<span class='$class'>{$pg['status']}</span>";
                ?>
            </td>

            <td><?= number_format($pg['valor'], 2, ',', '.'); ?> €</td>

            <td>
                <a class="detalhes-btn" href="visualizar_pedido.php?id=<?= $pg['id_pedido']; ?>">
                    🔍 Ver Pedido
                </a>
            </td>

        </tr>
    <?php endforeach; ?>

</table>

<?php endif; ?>

</body>
</html>
