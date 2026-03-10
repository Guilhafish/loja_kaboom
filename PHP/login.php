<?php
session_start();

// Configuração da ligação
$host = "localhost";
$dbname = "loja_pirotecnia"; 
$user = "guimira";     
$pass = "1234";        

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recebe dados do formulário
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validação básica
    if (strlen($username) < 3 || strlen($password) < 3) {
        die("<script>alert('Dados inválidos!'); window.location.href='../HTML/login_index.html';</script>");
    }

    // -----------------------------
    // 1️⃣ Verificar ADMIN (igual)
    // -----------------------------
    $stmtAdmin = $pdo->prepare("SELECT * FROM Admin WHERE nome = :username AND senha = :password LIMIT 1");
    $stmtAdmin->execute([
        ':username' => $username,
        ':password' => $password
    ]);

    if ($stmtAdmin->rowCount() > 0) {
        $_SESSION['user'] = $username;
        $_SESSION['tipo'] = "admin";
        header("Location: dashboard.php");
        exit();
    }

    // -----------------------------
    // 2️⃣ Verificar CLIENTE (corrigido para hash)
    // -----------------------------
    $stmtCliente = $pdo->prepare("SELECT * FROM Cliente WHERE nome = :username LIMIT 1");
    $stmtCliente->execute([':username' => $username]);
    $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    if ($cliente && password_verify($password, $cliente['senha'])) {
        $_SESSION['user'] = $username;
        $_SESSION['tipo'] = "cliente";
        header("Location: dashboard.php");
        exit();
    }

    // -----------------------------
    // Caso nenhum resultado
    // -----------------------------
    echo "<script>alert('Credenciais inválidas!'); window.location.href='../HTML/login_index.html';</script>";
    exit();

} catch (PDOException $e) {
    die("Erro na ligação: " . $e->getMessage());
}