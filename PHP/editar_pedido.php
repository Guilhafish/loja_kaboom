<?php
session_start();

// Apenas admin pode aceder
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../HTML/index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: gerir_pedidos.php");
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

    // Obter pedido
    $stmt = $pdo->prepare("SELECT * FROM pedido WHERE id_pedido = ?");
    $stmt->execute([$_GET['id']]);
    $pedido = $stmt->fetch();

    if (!$pedido) {
        header("Location: gerir_pedidos.php");
        exit();
    }

    // Obter lista de clientes
    $stmt = $pdo->query("SELECT id_cliente, nome FROM cliente ORDER BY nome");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Processar atualização
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_cliente = $_POST['id_cliente'];
        $status = $_POST['status'];
        $total = $_POST['total'];
        
        $stmt = $pdo->prepare("UPDATE pedido SET id_cliente = ?, status = ?, total = ? WHERE id_pedido = ?");
        $stmt->execute([$id_cliente, $status, $total, $_GET['id']]);
        
        header("Location: gerir_pedidos.php?atualizado=1");
        exit();
    }

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Pedido</title>
</head>
<body>

<h1>✏ Editar Pedido #<?= $pedido['id_pedido'] ?></h1>

<a href="gerir_pedidos.php">⬅ Voltar aos Pedidos</a>
<br><br>

<form method="POST">
    <label>Cliente:</label><br>
    <select name="id_cliente" required>
        <option value="">Selecione um cliente</option>
        <?php foreach ($clientes as $cliente): ?>
            <option value="<?= $cliente['id_cliente'] ?>" 
                <?= $cliente['id_cliente'] == $pedido['id_cliente'] ? 'selected' : '' ?>>
                <?= $cliente['nome'] ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Status:</label><br>
    <select name="status" required>
        <option value="pendente" <?= $pedido['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
        <option value="processado" <?= $pedido['status'] == 'processado' ? 'selected' : '' ?>>Processado</option>
        <option value="enviado" <?= $pedido['status'] == 'enviado' ? 'selected' : '' ?>>Enviado</option>
        <option value="entregue" <?= $pedido['status'] == 'entregue' ? 'selected' : '' ?>>Entregue</option>
        <option value="cancelado" <?= $pedido['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
    </select>
    <br><br>

    <label>Total (€):</label><br>
    <input type="number" name="total" step="0.01" min="0" value="<?= $pedido['total'] ?>" required>
    <br><br>

    <button type="submit">Atualizar Pedido</button>
</form>

</body>
</html>