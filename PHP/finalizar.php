<?php
session_start();

// Verifica se cliente está logado
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
    echo "<script>alert('Precisas estar logado como cliente!'); window.location.href='login_index.php';</script>";
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

    // Total do pedido
    $total = 0;
    foreach($_SESSION['carrinho'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }

    // Inserir pedido
    $stmt = $pdo->prepare("INSERT INTO Pedido (idcliente, datahora, total) VALUES (:idcliente, NOW(), :total)");
    $stmt->execute([
        ':idcliente' => $_SESSION['user'], // se o id real for diferente, ajustar
        ':total' => $total
    ]);

    $id_pedido = $pdo->lastInsertId();

    // Inserir itens do pedido
    $stmtItem = $pdo->prepare("INSERT INTO ItemPedido (idpedido, idproduto, quantidade, preco) VALUES (:idpedido, :idproduto, :quantidade, :preco)");
    foreach($_SESSION['carrinho'] as $id => $item) {
        $stmtItem->execute([
            ':idpedido' => $id_pedido,
            ':idproduto' => $id,
            ':quantidade' => $item['quantidade'],
            ':preco' => $item['preco']
        ]);
    }

    // Limpa carrinho
    unset($_SESSION['carrinho']);

    echo "<script>alert('Compra finalizada com sucesso!'); window.location.href='index.php';</script>";
    exit();

} catch (PDOException $e) {
    die("Erro na ligação: " . $e->getMessage());
}
?>
