<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaboom</title>
    <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>
<header>
    <div class="logo">
        <h1>🔥 Kaboom</h1>
    </div>
 
    <nav class="menu">
        <ul>
            <li><a href="index.php" class="active">Início</a></li>
            <li><a href="petardos.php">Petardos</a></li>
            <li><a href="fumos.php">Fumos</a></li>
            <li><a href="tochas.php">Tochas</a></li>
            <li><a href="strobes.php">Strobes</a></li>
            <li><a href="contacto.php">Contacto</a></li>
        </ul>
    </nav>
 
    <div class="actions">
        <?php if (isset($_SESSION['user'])): ?>
            <span class="username">👋 Olá, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></span>

            <a href="../PHP/dashboard.php">
                <button class="login-btn">Painel</button>
            </a>

            <a href="../PHP/logout.php">
                <button class="logout-btn">Sair</button>
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
    <section class="hero">
        <div class="hero-content">
            <h2>Explosão de Cores e Emoção!</h2>
            <p>Os melhores petardos, fumos e efeitos pirotécnicos, com segurança e qualidade.</p>
            <a href="petardos.php" class="btn">Ver Produtos</a>
        </div>
    </section>
</main>
 
<footer>
    <p>© 2025 Kaboom — Todos os direitos reservados.</p>
</footer>
</body>
</html>
