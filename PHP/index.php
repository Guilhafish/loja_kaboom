<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kaboom</title>
    <link rel="stylesheet" href="../CSS/index.css">
    <style>
        /* Apenas imagem de fundo limpa */
        body {
            background: url('../IMG/banner.png') center/cover no-repeat fixed;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            /* Prevenir seleção de texto para ajudar a prevenir zoom */
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            /* Prevenir ação de toque duplo para zoom */
            touch-action: manipulation;
        }
        
        /* Permitir seleção apenas em campos de texto */
        input, textarea {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }
        
        /* Container principal - botão MAIS PARA BAIXO */
        main {
            flex: 1;
            display: flex;
            align-items: flex-end; /* Alinha no fundo */
            justify-content: center;
            padding-bottom: 150px; /* AUMENTADO para botão mais abaixo */
        }
        
        /* Botão VERMELHO (cor inicial) */
        .btn {
            background: linear-gradient(45deg, #ff0000, #cc0000); /* VERMELHO */
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 18px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(255, 0, 0, 0.4); /* Sombra vermelha */
            border: none;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            /* Prevenir arrastar acidental */
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 0, 0, 0.6); /* Sombra vermelha mais forte */
        }
        
        /* Rodapé SEM transparência */
        footer {
            background-color: #000000; /* PRETO sólido */
            color: white;
            text-align: center;
            padding: 20px;
            width: 100%;
        }
        
        /* Header SEM transparência */
        header {
            background-color: #000000; /* PRETO sólido */
        }
        
        /* JavaScript para bloquear Ctrl+ e Ctrl- */
        script {
            display: none;
        }
    </style>
</head>
<body oncontextmenu="return false;"> <!-- Desabilita menu de contexto -->
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
    <!-- Botão MAIS PARA BAIXO -->
    <a href="petardos.php" class="btn">Ver Produtos</a>
</main>

<footer>
    <p>© 2025 Kaboom — Todos os direitos reservados.</p>
</footer>

<script>
// Bloqueia zoom com Ctrl+ e Ctrl-
document.addEventListener('keydown', function(e) {
    // Bloqueia Ctrl + + (zoom in)
    if ((e.ctrlKey && (e.key === '+' || e.key === '=')) || 
        // Bloqueia Ctrl + - (zoom out)
        (e.ctrlKey && e.key === '-') ||
        // Bloqueia Ctrl + scroll do mouse
        (e.ctrlKey && (e.key === 'Add' || e.key === 'Subtract')) ||
        // Bloqueia Ctrl + 0 (reset zoom)
        (e.ctrlKey && e.key === '0')) {
        e.preventDefault();
        return false;
    }
});

// Bloqueia zoom com toque duplo em dispositivos móveis
var lastTouchEnd = 0;
document.addEventListener('touchend', function(event) {
    var now = (new Date()).getTime();
    if (now - lastTouchEnd <= 300) {
        event.preventDefault();
    }
    lastTouchEnd = now;
}, false);

// Bloqueia gesto de pinça (zoom) em mobile
document.addEventListener('gesturestart', function(e) {
    e.preventDefault();
});

// Desabilita menu de contexto (botão direito)
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Previne scroll com Ctrl (zoom do mouse)
document.addEventListener('wheel', function(e) {
    if(e.ctrlKey) {
        e.preventDefault();
    }
}, { passive: false });
</script>
</body>
</html>