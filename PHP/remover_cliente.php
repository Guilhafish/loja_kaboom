<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../HTML/index.php");
    exit();
}

$host = "localhost";
$dbname = "loja_pirotecnia";
$user = "guimira";
$pass = "1234";

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM cliente WHERE id_cliente = :id");
$stmt->execute([":id" => $id]);

header("Location: gerir_clientes.php");
exit();
