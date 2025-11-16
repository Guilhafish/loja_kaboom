<?php
// Configuração da ligação
$host = "localhost";
$dbname = "loja_pirotecnia"; 
$user = "guimira";      // teu utilizador MariaDB
$pass = "1234";         // tua password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recebe dados do formulário
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validação básica
    if (strlen($username) < 3 || strlen($password) < 3) {
        die("Dados inválidos (mínimo 3 caracteres).");
    }

    // -----------------------------
    // 1️⃣ Verificar ADMIN
    // -----------------------------
    $stmtAdmin = $pdo->prepare("SELECT * FROM Admin WHERE nome = :username AND senha = :password");
    $stmtAdmin->execute([
        ':username' => $username,
        ':password' => $password
    ]);

    // -----------------------------
    // 2️⃣ Verificar CLIENTE
    // -----------------------------
    $stmtCliente = $pdo->prepare("SELECT * FROM Cliente WHERE nome = :username AND senha = :password");
    $stmtCliente->execute([
        ':username' => $username,
        ':password' => $password
    ]);

    // -----------------------------
    // Resultado
    // -----------------------------
    if ($stmtAdmin->rowCount() > 0 || $stmtCliente->rowCount() > 0) {
        echo "Login bem-sucedido!";
    } else {
        echo "Credenciais inválidas!";
    }

} catch (PDOException $e) {
    die("Erro na ligação: " . $e->getMessage());
}
