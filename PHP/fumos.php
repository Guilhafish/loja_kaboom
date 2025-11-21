<?php
session_start();
require "db.php"; // Conexão com o banco de dados

// Buscar produtos da categoria "Fumos"
$sql = "SELECT * FROM produto WHERE categoria = 'Fumos'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fumos - Kaboom</title>

    <!-- CSS externo -->
    <link rel="stylesheet" href="../CSS/fumos.css">
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
            <li><a href="fumos.php" class="active">Fumos</a></li>
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

    <h2 class="titulo-categoria">Fumos</h2>

    <section class="produtos">

        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $prod): ?>
                <div class="produto-card">

                    <h3><?php echo htmlspecialchars($prod['nome']); ?></h3>
                    <p><?php echo htmlspecialchars($prod['descricao']); ?></p>
                    <p class="preco">€<?php echo number_format($prod['preco'], 2, ',', '.'); ?></p>

                    <!-- Controlo de quantidade -->
                    <div class="quantidade-container">
                        <button class="btn-qty" onclick="alterarQtd('<?php echo $prod['id_produto']; ?>', -1)">−</button>

                        <input 
                            type="number" 
                            id="qtd_<?php echo $prod['id_produto']; ?>" 
                            class="quantidade-input" 
                            value="1" min="1">

                        <button class="btn-qty" onclick="alterarQtd('<?php echo $prod['id_produto']; ?>', 1)">+</button>
                    </div>

                    <!-- Botão Adicionar -->
                    <a href="#" class="add-btn"
                       onclick="adicionarCarrinho(<?php echo $prod['id_produto']; ?>)">
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
