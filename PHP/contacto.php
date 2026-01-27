<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta charset="UTF-8">
    <title>Contacto - Kaboom</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/contacto.css">
</head>
<body>

<header>
    
    <div class="logo">
        <h1>🔥 Kaboom</h1>
    </div>

    <nav class="menu">
        <ul>
            <li><a href="index.php">Início</a></li>
            <li><a href="petardos.php">Petardos</a></li>
            <li><a href="fumos.php">Fumos</a></li>
            <li><a href="tochas.php">Tochas</a></li>
            <li><a href="strobes.php">Strobes</a></li>
            <li><a href="contacto.php" class="active">Contacto</a></li>
        </ul>
    </nav>

    <div class="actions">
        <?php if (isset($_SESSION['user'])): ?>
            <span class="username">👋 Olá, <strong><?= htmlspecialchars($_SESSION['user']); ?></strong></span>

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

<main class="contacto-container">

    <h2>📞 Contacta-nos</h2>

    <!-- Informações -->
    <section class="info">
        <p><strong>Email:</strong> suporte@kaboom.pt</p>
        <p><strong>Telefone:</strong> +351 912 345 678</p>
        <p><strong>Morada:</strong> Rua da Pirotecnia, 123 – Lisboa</p>
        <p><strong>Horário:</strong> Seg–Sex 09h–18h | Sáb 09h–13h</p>
    </section>

    <!-- Mapa -->
    <section class="mapa">
        <h3>📍 Onde estamos</h3>
        <iframe 
            src="https://www.google.com/maps?q=Lisboa&output=embed"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </section>

    <!-- Aviso de segurança -->
    <section class="seguranca">
        <h3>⚠️ Aviso de Segurança</h3>
        <p>
            A Kaboom vende produtos pirotécnicos apenas para maiores de 18 anos.
            O uso incorreto destes produtos pode causar ferimentos graves.
            Utilize sempre com responsabilidade e siga as instruções do fabricante.
        </p>
    </section>

</main>

<footer>
    <p>© 2025 Kaboom — Todos os direitos reservados.</p>
</footer>

</body>
</html>
