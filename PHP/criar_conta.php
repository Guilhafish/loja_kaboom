<?php
// criar_conta.php
$msg = "";

// Configuração da ligação
$host = "localhost";
$dbname = "loja_pirotecnia"; 
$user = "guimira";
$pass = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $telefone = trim($_POST['telefone'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');

        // Validação nome
        if (strlen($nome) < 3) {
            $msg = "O nome deve ter pelo menos 3 caracteres.";
        }

        // Validação email
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = "Email inválido.";
        }

        // Validação senha (mínimo 8)
        elseif (strlen($senha) < 8) {
            $msg = "A password deve ter pelo menos 8 caracteres.";
        }

        // Validação telefone (apenas 9 números)
        elseif (!preg_match('/^[0-9]{9}$/', $telefone)) {
            $msg = "O telefone deve conter exatamente 9 números.";
        }

        else {

            // adicionar prefixo +351
            $telefone = "+351" . $telefone;

            // hash da password
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO cliente 
            (nome, email, senha, telefone, endereco) 
            VALUES (:nome, :email, :senha, :telefone, :endereco)");

            $stmt->execute([
                ':nome' => htmlspecialchars($nome),
                ':email' => $email,
                ':senha' => $senhaHash,
                ':telefone' => $telefone,
                ':endereco' => htmlspecialchars($endereco)
            ]);

            $msg = "Conta criada com sucesso!";
        }
    }

} catch (PDOException $e) {
    $msg = "Os dados inseridos já estão a ser utilizados noutra conta.";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Kaboom</title>
    <link rel="stylesheet" href="../CSS/criar_conta.css">
</head>
<body>
<div class="container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h1>🔥 Kaboom</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="petardos.php">Petardos</a></li>
                <li><a href="fumos.php">Fumos</a></li>
                <li><a href="tochas.php">Tochas</a></li>
                <li><a href="strobes.php">Strobes</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Área principal do formulário -->
    <main class="login-main register-main">
        <div class="login-box register-box">
            <h2>Criar Conta</h2>

            <?php if ($msg): ?>
                <p style="color: <?= strpos($msg, 'Erro') !== false ? 'red' : 'green'; ?>"><?= $msg ?></p>
            <?php endif; ?>

            <form method="POST">
                <label>Nome:</label>
                <input type="text" name="nome" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Senha:</label>
                <input type="password" name="senha" required>

                <label>Telefone:</label>
                <input type="text" name="telefone" pattern="[0-9]{9}" maxlength="9" placeholder="912345678" required>

                <label>Endereço:</label>
                <input type="text" name="endereco" required>

                <button type="submit" class="btn">Criar Conta</button>
            </form>

            <p class="register-link">
                Já tem conta? <a href="../HTML/login_index.html">Entrar</a>
            </p>
        </div>
    </main>

</div>

<footer>
    <p>© 2026 Kaboom — Todos os direitos reservados.</p>
</footer>

</body>
</html>
