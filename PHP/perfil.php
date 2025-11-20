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

        $novo_nome = $_POST['nome'];
        $novo_email = $_POST['email'];
        $novo_telefone = $_POST['telefone'];
        $novo_endereco = $_POST['endereco'];
        $nova_senha = !empty($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : null;

        // Atualizar dados
        if ($nova_senha) {
            $stmtUpdate = $pdo->prepare("
                UPDATE cliente 
                SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, senha = :senha 
                WHERE id_cliente = :id
            ");
            $stmtUpdate->execute([
                ':nome' => $novo_nome,
                ':email' => $novo_email,
                ':telefone' => $novo_telefone,
                ':endereco' => $novo_endereco,
                ':senha' => $nova_senha,
                ':id' => $cliente['id_cliente']
            ]);
        } else {
            // Sem troca de senha
            $stmtUpdate = $pdo->prepare("
                UPDATE cliente 
                SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco  
                WHERE id_cliente = :id
            ");
            $stmtUpdate->execute([
                ':nome' => $novo_nome,
                ':email' => $novo_email,
                ':telefone' => $novo_telefone,
                ':endereco' => $novo_endereco,
                ':id' => $cliente['id_cliente']
            ]);
        }

        // Atualizar sessão
        $_SESSION['user'] = $novo_nome;

        $mensagem = "Dados atualizados com sucesso!";
    }

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
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
    <input type="text" name="telefone" value="<?= $cliente['telefone']; ?>" required>

    <label>Endereço</label>
    <input type="text" name="endereco" value="<?= $cliente['endereco']; ?>" required>

    <label>Alterar Senha (opcional)</label>
    <input type="password" name="senha" placeholder="Nova senha (deixe vazio para não alterar)">

    <button type="submit" class="btn">Salvar Alterações</button>
</form>

</body>
</html>
