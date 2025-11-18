<?php
session_start();
require "db.php"; // Conexão com o banco de dados

// Buscar produtos da categoria "Petardos"
$sql = "SELECT * FROM produto WHERE categoria = 'Petardos'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petardos - Kaboom</title>
    <link rel="stylesheet" href="../CSS/petardos.css">
</head>
<body>

<header>
    <div class="logo">
        <h1>🔥 Kaboom</h1>
    </div>

    <nav class="menu">
        <ul>
            <li><a href="index.php">Início</a></li>
            <li><a href="petardos.php" class="active">Petardos</a></li>
            <li><a href="#">Fumos</a></li>
            <li><a href="#">Tochas</a></li>
            <li><a href="#">Strobes</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </nav>

    <div class="actions">

        <?php if (isset($_SESSION['user'])): ?>
            <span class="username">👋 Olá, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></span>

            <a href="../PHP/dashboard.php">
                <button class="login-btn">Painel</button>
            </a>

            <a href="../PHP/logout.php">
                <button class="login-btn logout-btn">Sair</button>
            </a>

        <?php else: ?>
            <a href="../HTML/login_index.html">
                <button class="login-btn">Login</button>
            </a>
        <?php endif; ?>

        <a href="carrinho.php" class="cart">🛒</a>
    </div>
</header>

<main>

    <h2 style="text-align:center; margin:30px 0; color:#c40000;">Petardos</h2>

    <section class="produtos">

        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $prod): ?>
                <div class="produto-card">
                    <h3><?php echo htmlspecialchars($prod['nome']); ?></h3>
                    <p><?php echo htmlspecialchars($prod['descricao']); ?></p>
                    <p class="preco">€<?php echo number_format($prod['preco'], 2, ',', '.'); ?></p>

                    <a href="add_carrinho.php?id=<?php echo $prod['id_produto']; ?>" class="add-btn">
                        Adicionar ao Carrinho
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; color:#aaa; font-size:1.1rem;">
                Não há produtos cadastrados nesta categoria ainda.
            </p>
        <?php endif; ?>

    </section>
</main>

<footer>
    <p>© 2025 Kaboom — Todos os direitos reservados.</p>
</footer>

</body>
</html>
