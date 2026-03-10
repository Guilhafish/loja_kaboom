<?php
session_start();

// Verifica se o cliente está logado
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'cliente') {
    echo "<script>alert('Precisas estar logado como cliente!'); window.location.href='../HTML/login_index.html';</script>";
    exit();
}

// Carrinho existe?
$carrinho = $_SESSION['carrinho'] ?? [];
$total_geral = 0;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - Kaboom</title>
    <link rel="stylesheet" href="../CSS/carrinho.css"> <!-- podes criar carrinho.css separado se quiseres -->
</head>
<body>
    <header>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>🔥</text></svg>">
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
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
        <div class="actions">
            <a href="dashboard.php"><button class="login-btn">Painel</button></a>
            <a href="logout.php"><button class="logout-btn">Sair</button></a>
        </div>
    </header>

    <main>
        <section class="carrinho-section">
            <div class="carrinho-box">
                <h2>Meu Carrinho</h2>

                <?php if (empty($carrinho)): ?>
                    <p>O carrinho está vazio.</p>
                    <a href="index.php" class="btn">Continuar Comprando</a>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>Quantidade</th>
                                <th>Subtotal</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($carrinho as $id => $item): 
                            $subtotal = $item['preco'] * $item['quantidade'];
                            $total_geral += $subtotal;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td>€ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                                <td><?php echo $item['quantidade']; ?></td>
                                <td>€ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                <td>
                                    <form style="display:inline;" action="update_carrinho.php" method="POST">
                                        <input type="hidden" name="id_produto" value="<?php echo $id; ?>">
                                        <button type="submit" name="acao" value="add">+</button>
                                    </form>
                                    <form style="display:inline;" action="update_carrinho.php" method="POST">
                                        <input type="hidden" name="id_produto" value="<?php echo $id; ?>">
                                        <button type="submit" name="acao" value="remove">-</button>
                                    </form>
                                    <form style="display:inline;" action="remover_item.php" method="POST">
                                        <input type="hidden" name="id_produto" value="<?php echo $id; ?>">
                                        <button type="submit">X</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h3>Total Geral: € <?php echo number_format($total_geral, 2, ',', '.'); ?></h3>
                    <a href="index.php" class="btn">Continuar Comprando</a>
                    <a href="finalizar.php" class="btn">Finalizar Compra</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2026 Kaboom — Todos os direitos reservados.</p>
    </footer>
</body>
</html>
