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

    // Remover pedido
    $stmt = $pdo->prepare("DELETE FROM pedido WHERE id_pedido = ?");
    $stmt->execute([$_GET['id']]);

    header("Location: gerir_pedidos.php?removido=1");
    exit();

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>