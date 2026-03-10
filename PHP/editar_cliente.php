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
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Captura o ID do cliente
$id = $_GET['id'];

// Buscar dados do cliente
$stmt = $pdo->prepare("SELECT * FROM cliente WHERE id_cliente = :id");
$stmt->execute([":id" => $id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("Cliente não encontrado.");
}

// Processa o formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $senha = $_POST['senha'];

    // Validação nome
    if (strlen($nome) < 3) {
        $erro = "O nome deve ter pelo menos 3 caracteres.";
    }

    // Validação email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido.";
    }

    // Validação telefone
    elseif (!preg_match('/^[0-9]{9}$/', $telefone)) {
        $erro = "O telefone deve conter exatamente 9 números.";
    }

    else {

        // adicionar prefixo +351
        $telefone = "+351" . $telefone;

        // Se o admin quiser alterar a senha
        if (!empty($senha)) {

            if (strlen($senha) < 8) {
                $erro = "A password deve ter pelo menos 8 caracteres.";
            } else {

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $update = $pdo->prepare("
                    UPDATE cliente SET 
                        nome = :nome,
                        email = :email,
                        senha = :senha,
                        telefone = :telefone,
                        endereco = :endereco
                    WHERE id_cliente = :id
                ");

                $update->execute([
                    ":nome" => htmlspecialchars($nome),
                    ":email" => $email,
                    ":senha" => $senhaHash,
                    ":telefone" => $telefone,
                    ":endereco" => htmlspecialchars($endereco),
                    ":id" => $id
                ]);
            }

        } else {

            // Sem alteração de senha
            $update = $pdo->prepare("
                UPDATE cliente SET 
                    nome = :nome,
                    email = :email,
                    telefone = :telefone,
                    endereco = :endereco
                WHERE id_cliente = :id
            ");

            $update->execute([
                ":nome" => htmlspecialchars($nome),
                ":email" => $email,
                ":telefone" => $telefone,
                ":endereco" => htmlspecialchars($endereco),
                ":id" => $id
            ]);
        }

        header("Location: gerir_clientes.php");
        exit();
    }
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
    <input type="password" name="senha" placeholder="Nova senha (mínimo 8 caracteres)">

    <label>Telefone:</label>
    <input type="text" name="telefone"
    pattern="[0-9]{9}"
    maxlength="9"
    value="<?= str_replace('+351','',$cliente['telefone']) ?>"
    required>

    <label>Endereço:</label>
    <input type="text" name="endereco" value="<?= $cliente['endereco'] ?>" required>

    <button type="submit">Guardar Alterações</button>
</form>

</body>
</html>
