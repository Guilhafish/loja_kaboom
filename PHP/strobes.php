<?php
session_start();
require "db.php"; // Conexão com o banco de dados

// Buscar produtos da categoria "Strobes"
$sql = "SELECT * FROM produto WHERE categoria = 'Strobes'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strobes - Kaboom</title>
    <link rel="stylesheet" href="../CSS/strobes.css">
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
            <li><a href="strobes.php" class="active">Strobes</a></li>
            <li><a href="contacto.php">Contacto</a></li>
        </ul>
    </nav>

    <div class="actions">
        <?php if (isset($_SESSION['user'])): ?>
            <span class="username">👋 Olá, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></span>
            <a href="../PHP/dashboard.php"><button class="login-btn">Painel</button></a>
            <a href="../PHP/logout.php"><button class="login-btn logout-btn">Sair</button></a>
        <?php else: ?>
            <a href="../HTML/login_index.html"><button class="login-btn">Login</button></a>
        <?php endif; ?>
        <a href="carrinho.php" class="cart">🛒</a>
    </div>
</header>

<main>
    <h2 class="titulo-categoria">Strobes</h2>

    <section class="produtos">
        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $prod): ?>
                <div class="produto-card">
                    <h3><?= htmlspecialchars($prod['nome']); ?></h3>
                    <p><?= htmlspecialchars($prod['descricao']); ?></p>
                    <p class="preco">€<?= number_format($prod['preco'], 2, ',', '.'); ?></p>

                    <!-- Controlo de quantidade -->
                    <div class="quantidade-container">
                        <button class="btn-qty" onclick="alterarQtd('<?= $prod['id_produto']; ?>', -1)">−</button>
                        <input type="number" id="qtd_<?= $prod['id_produto']; ?>" class="quantidade-input" value="1" min="1">
                        <button class="btn-qty" onclick="alterarQtd('<?= $prod['id_produto']; ?>', 1)">+</button>
                    </div>

                    <!-- Botão Adicionar -->
                    <a href="#" class="add-btn" onclick="adicionarCarrinho(<?= $prod['id_produto']; ?>)">
                        Adicionar ao Carrinho
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="sem-produtos">Não há produtos cadastrados nesta categoria ainda.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>© 2025 Kaboom — Todos os direitos reservados.</p>
</footer>

<script>
function alterarQtd(id, valor) {
    let campo = document.getElementById("qtd_" + id);
    let atual = parseInt(campo.value);
    if (atual + valor >= 1) {
        campo.value = atual + valor;
    }
}

function adicionarCarrinho(idProduto) {
    let qtd = document.getElementById("qtd_" + idProduto).value;
    window.location.href = "add_carrinho.php?id=" + idProduto + "&qtd=" + qtd;
}
</script>

</body>
</html>
