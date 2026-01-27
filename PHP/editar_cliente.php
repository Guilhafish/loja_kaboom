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

// Captura o ID do cliente
$id = $_GET['id'];

// Buscar dados do cliente
$stmt = $pdo->prepare("SELECT * FROM cliente WHERE id_cliente = :id");
$stmt->execute([":id" => $id]);
$cliente = $stmt->fetch();

// Processa o formulário
if ($_POST) {
    $update = $pdo->prepare("UPDATE cliente SET 
                                nome = :nome,
                                email = :email,
                                senha = :senha,
                                telefone = :telefone,
                                endereco = :endereco
                             WHERE id_cliente = :id");

    $update->execute([
        ":nome" => $_POST['nome'],
        ":email" => $_POST['email'],
        ":senha" => $_POST['senha'],
        ":telefone" => $_POST['telefone'],
        ":endereco" => $_POST['endereco'],
        ":id" => $id
    ]);

    header("Location: gerir_clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="../CSS/editar_cliente.css">
</head>
<body>

<!-- Botão voltar -->
<a href="gerir_clientes.php" class="button-voltar">
    <button type="button">⬅ Voltar</button>
</a>

<form method="POST">
    <h1>✏ Editar Cliente</h1>

    <label>Nome:</label>
    <input type="text" name="nome" value="<?= $cliente['nome'] ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= $cliente['email'] ?>" required>

    <label>Senha:</label>
    <input type="text" name="senha" value="<?= $cliente['senha'] ?>" required>

    <label>Telefone:</label>
    <input type="text" name="telefone" value="<?= $cliente['telefone'] ?>" required>

    <label>Endereço:</label>
    <input type="text" name="endereco" value="<?= $cliente['endereco'] ?>" required>

    <button type="submit">Guardar Alterações</button>
</form>

</body>
</html>
