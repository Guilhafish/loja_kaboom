<?php
// criar_conta.php
$msg = "";

// Configuração da ligação
$host = "localhost";
$dbname = "loja_pirotecnia"; 
$user = "guimira";      // teu utilizador MariaDB
$pass = "1234";         // tua password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $endereco = $_POST['endereco'] ?? '';

        // Validação simples
        if (strlen($nome) < 3 || strlen($senha) < 3) {
            $msg = "Nome e senha devem ter pelo menos 3 caracteres.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO cliente (nome, email, senha, telefone, endereco) VALUES (:nome, :email, :senha, :telefone, :endereco)");
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha,
                ':telefone' => $telefone,
                ':endereco' => $endereco
            ]);
            $msg = "Conta criada com sucesso!";
        }
    }
} catch (PDOException $e) {
    $msg = "Erro na ligação: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
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
                <li><a href="../HTML/index.html">Início</a></li>
                <li><a href="#">Petardos</a></li>
                <li><a href="#">Fumos</a></li>
                <li><a href="#">Tochas</a></li>
                <li><a href="#">Strobes</a></li>
                <li><a href="#">Contacto</a></li>
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
                <input type="text" name="telefone" required>

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
    <p>© 2025 Kaboom — Todos os direitos reservados.</p>
</footer>

</body>
</html>
