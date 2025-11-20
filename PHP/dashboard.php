<?php
session_start();

// ⚠️ Caso não tenha session ainda, podemos ativar após você mandar seu login.php real.
// if (!isset($_SESSION['user'])) {
//     header("Location: login.php");
//     exit();
// }

$tipo = $_SESSION['tipo'] ?? "cliente"; // admin ou cliente
$username = $_SESSION['user'] ?? "Utilizador";

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>

<div class="container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <h1>🔥 Kaboom</h1>
        </div>  

        <div class="menu">
            <ul>
                <li><a href="index.php">🏠 Início</a></li>

                <?php if ($tipo === "admin"): ?>
                    <li><a href="gerir_produtos.php">📦 Gerir Produtos</a></li>
                    <li><a href="gerir_clientes.php">👥 Gerir Clientes</a></li>
                    <li><a href="gerir_pedidos.php">📝 Gerir Pedidos</a></li>
                <?php endif; ?>

                <?php if ($tipo === "cliente"): ?>
                    <li><a href="meus_pedidos.php">🛒 Meus Pedidos</a></li>
                    <li><a href="perfil.php">👤 Perfil</a></li>
                    <li><a href="meus_pagamentos.php">💳 Meus Pagamento</a></li>
                <?php endif; ?>

                <li><a href="logout.php">🚪 Sair</a></li>
            </ul>
        </div>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="main">
        <h1>Bem-vindo, <span><?php echo $username; ?></span>!</h1>

        <?php if ($tipo === "admin"): ?>
            <p class="subtitle">Você está no <strong>Painel de Administração</strong>.</p>
        <?php else: ?>
            <p class="subtitle">Você está na sua área de cliente.</p>
        <?php endif; ?>

        <div class="cards">

            <?php if ($tipo === "admin"): ?>
                <a href="gerir_produtos.php" class="card" style="text-decoration:none; color:inherit;">
                    <h2>Produtos</h2>
                    <p>Gerir catálogo, preços e stock.</p>
                </a>

                <a href="gerir_clientes.php" class="card" style="text-decoration:none; color:inherit;">
                    <h2>Clientes</h2>
                    <p>Ver contas registadas.</p>
                </a>
                <a href="gerir_pedidos.php" class="card" style="text-decoration:none; color:inherit;">
                    <h2>Pedidos</h2>
                    <p>Acompanhar pedidos feitos na loja.</p>
                </a>

            <?php else: ?>

                <a href="meus_pedidos.php" class="card" style="text-decoration:none; color:inherit;">
                    <h2>Meus Pedidos</h2>
                    <p>Consultar compras anteriores.</p>
                </a>

                <a href="perfil.php" class="card" style="text-decoration:none; color:inherit;">
                    <h2>Perfil</h2>
                    <p>Alterar dados pessoais.</p>
                </a>

                <a href="meus_pagamentos.php" class="card" style="text-decoration:none; color:inherit;">
                    <h2>Meus Pagamentos</h2>
                    <p>Gerir os meus pagamento.</p>
                </a>

            <?php endif; ?>

        </div>

    </main>
</div>

</body>
</html>
