<?php
session_start();

$msg = "";
$tokenValido = false;

$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pega token da URL
    if (isset($_GET['token'])) {
        $token = $_GET['token'];

        // Verifica se token existe e ainda não expirou
        $stmt = $pdo->prepare("SELECT id_cliente FROM cliente WHERE token_recuperacao = :token AND token_expira > NOW() LIMIT 1");
        $stmt->execute([':token' => $token]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            $tokenValido = true;

            // Se formulário for enviado
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $novaSenha = $_POST['nova_senha'] ?? '';
                $confirmSenha = $_POST['confirm_senha'] ?? '';

                // Validações
                if (strlen($novaSenha) < 8) {
                    $msg = "A senha deve ter pelo menos 8 caracteres.";
                } elseif ($novaSenha !== $confirmSenha) {
                    $msg = "As senhas não coincidem.";
                } else {
                    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);

                    // Atualiza senha e remove token
                    $stmtUpdate = $pdo->prepare("UPDATE cliente SET senha = :senha, token_recuperacao = NULL, token_expira = NULL WHERE id_cliente = :id");
                    $stmtUpdate->execute([
                        ':senha' => $hash,
                        ':id' => $cliente['id_cliente']
                    ]);

                    $msg = "Senha redefinida com sucesso! <a href='../HTML/login_index.html'>Entrar</a>";
                    $tokenValido = false; // token usado
                }
            }
        } else {
            $msg = "Token inválido ou expirado. <a href='../HTML/login_index.html'>Entrar</a>";
        }
    } else {
        $msg = "Token não fornecido. <a href='../HTML/login_index.html'>Entrar</a>";
    }

} catch (PDOException $e) {
    $msg = "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - Kaboom</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>

<div class="container">

    <!-- Sidebar (igual ao login) -->
    <aside class="sidebar">
        <div class="logo">
            <h1>🔥 Kaboom</h1>
        </div>
        <nav class="menu">
            <ul>
                <li><a href="../PHP/index.php">Início</a></li>
                <li><a href="../PHP/petardos.php">Petardos</a></li>
                <li><a href="../PHP/fumos.php">Fumos</a></li>
                <li><a href="../PHP/tochas.php">Tochas</a></li>
                <li><a href="../PHP/strobes.php">Strobes</a></li>
                <li><a href="../PHP/contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </aside>

    <main class="login-main">
        <div class="login-box">

            <!-- Botão voltar -->
            <a href="../HTML/login_index.html" class="button-voltar">
                <button type="button">⬅ Voltar ao Login</button>
            </a>

            <h2>Redefinir Senha</h2>

            <?php if ($msg): ?>
                <p style="color: #c40000; font-size: 0.9rem;"><?= $msg; ?></p>
            <?php endif; ?>

            <?php if ($tokenValido): ?>
            <form method="POST">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" placeholder="Mínimo 8 caracteres" required>

                <label for="confirm_senha">Confirmar Senha</label>
                <input type="password" id="confirm_senha" name="confirm_senha" placeholder="Digite novamente" required>

                <button type="submit" class="btn">Redefinir Senha</button>
            </form>
            <?php endif; ?>

        </div>
    </main>
</div>

</body>
</html>