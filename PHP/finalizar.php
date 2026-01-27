<?php
session_start();

// Verifica se cliente está logado
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
    echo "<script>alert('Precisas estar logado como cliente!'); window.location.href='login_index.html';</script>";
    exit();
}

// Carrinho vazio?
if (empty($_SESSION['carrinho'])) {
    echo "<script>alert('O carrinho está vazio!'); window.location.href='index.php';</script>";
    exit();
}

// Configuração do banco de dados
$host = "localhost";
$dbname = "loja_pirotecnia"; 
$user = "guimira";      
$pass = "1234";         

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pega dados do cliente logado
    $stmtCliente = $pdo->prepare("SELECT * FROM Cliente WHERE nome = :nome LIMIT 1");
    $stmtCliente->execute([':nome' => $_SESSION['user']]);
    $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        echo "<script>alert('Cliente não encontrado!'); window.location.href='index.php';</script>";
        exit();
    }

    // Se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $metodo_pagamento = $_POST['metodo_pagamento'] ?? '';
        $dados_pagamento = '';

        switch($metodo_pagamento) {
            case 'MBWay':
                $dados_pagamento = $_POST['mbway_numero'] ?? '';
                if (!$dados_pagamento) {
                    echo "<script>alert('Insere o teu número MBWay!'); history.back();</script>";
                    exit();
                }
                break;

            case 'PayPal':
                $dados_pagamento = $_POST['paypal_email'] ?? '';
                if (!$dados_pagamento) {
                    echo "<script>alert('Insere o teu email PayPal!'); history.back();</script>";
                    exit();
                }
                break;

            case 'BTC':
                $dados_pagamento = $_POST['btc_endereco'] ?? '';
                if (!$dados_pagamento) {
                    echo "<script>alert('Insere o teu endereço BTC!'); history.back();</script>";
                    exit();
                }
                break;

            default:
                echo "<script>alert('Escolhe um método de pagamento!'); history.back();</script>";
                exit();
        }

        // Calcula total do carrinho
        $total = 0;
        foreach($_SESSION['carrinho'] as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
        
        // Inserir pedido
        $stmtPedido = $pdo->prepare("
            INSERT INTO Pedido (id_cliente, data_pedido, status, total)
            VALUES (:id_cliente, NOW(), 'pendente', :total)
        ");
        $stmtPedido->execute([
            ':id_cliente' => $cliente['id_cliente'],
            ':total' => $total
        ]);

        $id_pedido = $pdo->lastInsertId();

        // Inserir itens do pedido
        $stmtItem = $pdo->prepare("
            INSERT INTO itempedido (id_pedido, id_produto, quantidade, preco_unitario)
            VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario)
        ");

        foreach($_SESSION['carrinho'] as $id => $item) {
            $stmtItem->execute([
                ':id_pedido' => $id_pedido,
                ':id_produto' => $id,
                ':quantidade' => $item['quantidade'],
                ':preco_unitario' => $item['preco'] // aqui é o preço de cada produto
            ]);
        }

        // INSERIR PAGAMENTO AQUI
        $stmtPagamento = $pdo->prepare("
            INSERT INTO pagamento (id_pedido, metodo, valor, data_pagamento, status)
            VALUES (:id_pedido, :metodo, :valor, NOW(), 'pendente')
        ");
        $stmtPagamento->execute([
            ':id_pedido' => $id_pedido,
            ':metodo' => $metodo_pagamento,
            ':valor' => $total
        ]);

        // Limpar carrinho
        unset($_SESSION['carrinho']);

        echo "<script>alert('Pedido finalizado com sucesso! Status: Pendente'); window.location.href='index.php';</script>";
        exit();
    }

} catch (PDOException $e) {
    die("Erro na ligação: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Finalizar Compra - Kaboom</title>
<link rel="stylesheet" href="../CSS/finalizar.css">
<script>
function mostrarCampos(valor) {
    document.getElementById('campo_mbway').style.display = (valor === 'MBWay') ? 'block' : 'none';
    document.getElementById('campo_paypal').style.display = (valor === 'PayPal') ? 'block' : 'none';
    document.getElementById('campo_btc').style.display = (valor === 'BTC') ? 'block' : 'none';
}
</script>
</head>
<body>
<div class="finalizar-container">

    <!-- Botão voltar -->
    <div class="button-voltar">
        <a href="carrinho.php"><button>⬅ Voltar ao Carrinho</button></a>
    </div>

    <h2>Finalizar Compra</h2>

    <form method="POST">
        <label>Nome:</label>
        <input type="text" value="<?php echo htmlspecialchars($cliente['nome']); ?>" readonly>

        <label>Email:</label>
        <input type="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" readonly>

        <label>Telefone:</label>
        <input type="text" value="<?php echo htmlspecialchars($cliente['telefone']); ?>" readonly>

        <label>Endereço:</label>
        <input type="text" value="<?php echo htmlspecialchars($cliente['endereco']); ?>" readonly>

        <label>Escolhe o método de pagamento:</label>
        <select id="metodo_pagamento" name="metodo_pagamento" required onchange="mostrarCampos(this.value)">
            <option value="">-- Seleciona --</option>
            <option value="MBWay">MBWay</option>
            <option value="PayPal">PayPal</option>
            <option value="BTC">BTC</option>
        </select>

        <div id="campo_mbway" class="pagamento-campo">
            <label>Número MBWay:</label>
            <input type="text" name="mbway_numero" placeholder="Ex: 912345678">
        </div>

        <div id="campo_paypal" class="pagamento-campo">
            <label>Email PayPal:</label>
            <input type="email" name="paypal_email" placeholder="email@paypal.com">
        </div>

        <div id="campo_btc" class="pagamento-campo">
            <label>Endereço BTC:</label>
            <input type="text" name="btc_endereco" placeholder="Ex: 1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa">
        </div>

        <button type="submit">Finalizar Compra</button>
    </form>
</div>
</body>
</html>
