<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

session_start();

$msg = "";

$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);

        $stmt = $pdo->prepare("SELECT id_cliente, nome FROM cliente WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            $token = bin2hex(random_bytes(16));
            $stmtToken = $pdo->prepare("UPDATE cliente SET token_recuperacao = :token, token_expira = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE id_cliente = :id");
            $stmtToken->execute([
                ':token' => $token,
                ':id' => $cliente['id_cliente']
            ]);

            $link = "http://localhost/loja_kaboom/PHP/reset_senha.php?token=$token";

            $mail = new PHPMailer(true);

            try {

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'kaboomvalidacao@gmail.com';
                $mail->Password = 'vaxl tgvx ugmf urwu';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];

                $mail->setFrom('kaboomvalidacao@gmail.com', 'Kaboom');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8'; // ✅ Corrige acentuação
                $mail->Subject = 'Recuperação de senha - Kaboom';

                $mail->Body = "
                    <h2>Recuperação de senha</h2>
                    <p>Recebemos um pedido para redefinir a sua senha.</p>
                    <p>Clique no link abaixo:</p>
                    <a href='$link'>$link</a>
                    <p>Este link expira em 10 minutos.</p>
                ";

                $mail->send();

                echo "<script>
                    alert('Email de recuperação enviado com sucesso! Verifique sua caixa de entrada.');
                    window.location.href='../HTML/login_index.html';
                </script>";
                exit();

            } catch (Exception $e) {
                $msg = "Erro ao enviar email: " . $mail->ErrorInfo;
            }
        } else {
            $msg = "Email não encontrado.";
        }
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
    <title>Esqueceu a Senha - Kaboom</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>

<div class="container">

    <!-- Barra lateral esquerda (mesma do login) -->
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

    <!-- Área principal de recuperação -->
    <main class="login-main">
        <div class="login-box">

            <!-- Botão voltar -->
            <a href="../HTML/login_index.html" class="button-voltar">
                <button type="button">⬅ Voltar ao Login</button>
            </a>

            <h2>Esqueceu a senha?</h2>

            <?php if ($msg): ?>
                <p style="color: #c40000; font-size: 0.9rem;"><?= $msg; ?></p>
            <?php endif; ?>

            <form method="POST">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite o seu email" required>

                <button type="submit" class="btn">Enviar link de recuperação</button>
            </form>

            <p class="register-link">
                Lembrou da senha? <a href="../HTML/login_index.html">Entrar</a>
            </p>
        </div>
    </main>

</div>

</body>
</html>