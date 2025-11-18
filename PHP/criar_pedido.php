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

$clientes = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter clientes
    $stmt = $pdo->query("SELECT id_cliente, nome FROM cliente ORDER BY nome");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_cliente = $_POST['id_cliente'];
        $status = $_POST['status'];
        $total = $_POST['total'];

        // Inserir pedido
        $stmt = $pdo->prepare("INSERT INTO pedido (id_cliente, data_pedido, status, total) VALUES (?, NOW(), ?, ?)");
        $stmt->execute([$id_cliente, $status, $total]);
        
        header("Location: gerir_pedidos.php?sucesso=1");
        exit();

    } catch (PDOException $e) {
        $erro = "Erro ao criar pedido: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Criar Pedido</title>
    <link rel="stylesheet" href="../CSS/criar_pedido.css">
</head>
<body>

<!-- Botão voltar -->
<a href="gerir_pedidos.php" class="button-voltar">
    <button type="button">⬅ Voltar</button>
</a>

<form method="POST">
    <h1>➕ Criar Novo Pedido</h1>

    <?php if (isset($erro)): ?>
        <div class="erro"><?= $erro ?></div>
    <?php endif; ?>

    <label>Cliente:</label>
    <select name="id_cliente" required>
        <option value="">Selecione um cliente</option>
        <?php foreach ($clientes as $cliente): ?>
            <option value="<?= $cliente['id_cliente'] ?>"><?= $cliente['nome'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Status:</label>
    <select name="status" required>
        <option value="pendente">Pendente</option>
        <option value="processado">Processado</option>
        <option value="enviado">Enviado</option>
        <option value="entregue">Entregue</option>
        <option value="cancelado">Cancelado</option>
    </select>

    <label>Total (€):</label>
    <input type="number" name="total" step="0.01" min="0" required>

    <button type="submit">Criar Pedido</button>
</form>

</body>
</html>
