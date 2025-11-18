<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
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
<li><a href="index.php">Início</a></li>
<li><a href="petardos.php">Petardos</a></li>
<li><a href="#">Fumos</a></li>
<li><a href="#">Tochas</a></li>
<li><a href="#">Strobes</a></li>
<li><a href="#">Contacto</a></li>
</ul>
</nav>
 
        <div class="actions">
 
            <?php if (isset($_SESSION['user'])): ?>
<!-- Mostrar nome -->
<span class="username">
                    👋 Olá, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>
</span>
 
                <!-- Botão para ir ao painel -->
<a href="../PHP/dashboard.php">
<button class="login-btn">Painel</button>
</a>
 
                <!-- Botão de logout -->
<a href="../PHP/logout.php">
<button class="login-btn logout-btn">Sair</button>
</a>
 
            <?php else: ?>
<!-- Mostrar botão login quando NÃO logado -->
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
<a href="#" class="btn">Ver Produtos</a>
</div>
</section>
</main>
 
    <footer>
<p>© 2025 Kaboom — Todos os direitos reservados.</p>
</footer>
 
</body>
</html>