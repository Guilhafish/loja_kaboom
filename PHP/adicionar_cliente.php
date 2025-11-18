<?php
session_start();

// Apenas admin pode aceder
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../HTML/index.php");
    exit();
}

// Conexão com o banco
$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

// Processa o formulário
if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO cliente (nome, email, senha, telefone, endereco) 
                           VALUES (:nome, :email, :senha, :telefone, :endereco)");

    $stmt->execute([
        ":nome" => $_POST['nome'],
        ":email" => $_POST['email'],
        ":senha" => $_POST['senha'],
        ":telefone" => $_POST['telefone'],
        ":endereco" => $_POST['endereco']
    ]);

    header("Location: gerir_clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Cliente</title>
    <link rel="stylesheet" href="../CSS/adicionar_cliente.css">
</head>
<body>

<!-- Botão voltar -->
<a href="gerir_clientes.php" class="button-voltar">
    <button type="button">⬅ Voltar</button>
</a>



<form method="POST">
    <h1>➕ Adicionar Cliente</h1>
    <label>Nome:</label>
    <input type="text" name="nome" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Senha:</label>
    <input type="text" name="senha" required>

    <label>Telefone:</label>
    <input type="text" name="telefone" required>

    <label>Endereço:</label>
    <input type="text" name="endereco" required>

    <button type="submit">Salvar</button>
</form>

</body>
</html>
