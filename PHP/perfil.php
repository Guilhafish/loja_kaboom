<?php
session_start();

// Apenas clientes podem aceder
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: ../HTML/index.php");
    exit();
}

$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar dados do cliente logado
    $stmt = $pdo->prepare("SELECT * FROM cliente WHERE nome = :nome LIMIT 1");
    $stmt->execute([':nome' => $_SESSION['user']]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        die("Erro ao carregar perfil.");
    }

    // Se o formulário for enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $novo_nome = trim($_POST['nome']);
        $novo_email = trim($_POST['email']);
        $novo_telefone = trim($_POST['telefone']);
        $novo_endereco = trim($_POST['endereco']);
        $nova_senha = $_POST['senha'];

        // Validação nome
        if (strlen($novo_nome) < 3) {
            $erro = "O nome deve ter pelo menos 3 caracteres.";
        }

        // Validação email
        elseif (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
            $erro = "Email inválido.";
        }

        // Validação telefone (9 números)
        elseif (!preg_match('/^[0-9]{9}$/', $novo_telefone)) {
            $erro = "O telefone deve conter exatamente 9 números.";
        }

        else {

            // adicionar prefixo +351
            $novo_telefone = "+351" . $novo_telefone;

            // Verificar senha se foi preenchida
            if (!empty($nova_senha)) {

                if (strlen($nova_senha) < 8) {
                    $erro = "A password deve ter pelo menos 8 caracteres.";
                } else {

                    $senhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);

                    $stmtUpdate = $pdo->prepare("
                        UPDATE cliente 
                        SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, senha = :senha 
                        WHERE id_cliente = :id
                    ");

                    $stmtUpdate->execute([
                        ':nome' => htmlspecialchars($novo_nome),
                        ':email' => $novo_email,
                        ':telefone' => $novo_telefone,
                        ':endereco' => htmlspecialchars($novo_endereco),
                        ':senha' => $senhaHash,
                        ':id' => $cliente['id_cliente']
                    ]);

                    $mensagem = "Dados atualizados com sucesso!";
                }

            } else {

                // Sem alteração de senha
                $stmtUpdate = $pdo->prepare("
                    UPDATE cliente 
                    SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco  
                    WHERE id_cliente = :id
                ");

                $stmtUpdate->execute([
                    ':nome' => htmlspecialchars($novo_nome),
                    ':email' => $novo_email,
                    ':telefone' => $novo_telefone,
                    ':endereco' => htmlspecialchars($novo_endereco),
                    ':id' => $cliente['id_cliente']
                ]);

                $mensagem = "Dados atualizados com sucesso!";
            }

            // Atualizar sessão
            $_SESSION['user'] = $novo_nome;
        }
    }

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
<meta charset="UTF-8">
<title>Meu Perfil</title>
<link rel="stylesheet" href="../CSS/perfil.css">
</head>
<body>

<h1>👤 Meu Perfil</h1>

<a href="dashboard.php" class="voltar-btn">⬅ Voltar</a>
<br><br>

<?php if (isset($mensagem)): ?>
    <p style="color: green;"><?= $mensagem; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Nome</label>
    <input type="text" name="nome" value="<?= $cliente['nome']; ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= $cliente['email']; ?>" required>

    <label>Telefone</label>
    <input type="text" name="telefone" pattern="[0-9]{9}" maxlength="9"
    value="<?= str_replace('+351','',$cliente['telefone']); ?>" required>

    <label>Endereço</label>
    <input type="text" name="endereco" value="<?= $cliente['endereco']; ?>" required>

    <label>Alterar Senha (opcional)</label>
    <input type="password" name="senha" placeholder="Nova senha (deixe vazio para não alterar)">

    <button type="submit" class="btn">Salvar Alterações</button>
</form>

</body>
</html>
