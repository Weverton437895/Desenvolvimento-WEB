<?php
require 'config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'usuario') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$produtoId = $_GET['id'];

$query = $pdo->prepare("SELECT * FROM servico WHERE id = ?");
$query->execute([$produtoId]);
$produto = $query->fetch();

if (!$produto) {
    header("Location: index.php");
    exit;
}

$query = $pdo->prepare("SELECT nome, email, foto FROM usuario WHERE id = ?");
$query->execute([$_SESSION['usuario_id']]);
$usuario = $query->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: avaliar_produto.php?produto_id=" . $produtoId);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Checkout - <?php echo htmlspecialchars($produto['titulo']); ?></title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h1>Tech Innovation</h1>
            </div>
            <div class="user-info">
                <?php if (!empty($usuario['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto">
                <?php endif; ?>
                <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
                <a href="logout.php" class="btn-login">Sair</a>
            </div>
        </div>
    </header>

    <div class="checkout-container">
        <div class="checkout-box">
            <h1 style="text-align: center; margin-bottom: 30px; color: #4CAF50;">Finalizar Compra</h1>
            
            <div class="product-details">
                <?php if (!empty($produto['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($produto['foto']); ?>" 
                         alt="<?php echo htmlspecialchars($produto['titulo']); ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/250?text=Sem+Imagem" alt="Sem imagem">
                <?php endif; ?>
                
                <div class="product-info">
                    <h2><?php echo htmlspecialchars($produto['titulo']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
                </div>
            </div>

            <form method="post">
                <div class="form-group">
                    <label>Cliente:</label>
                    <input type="text" value="<?php echo htmlspecialchars($usuario['nome']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Produto:</label>
                    <input type="text" value="<?php echo htmlspecialchars($produto['titulo']); ?>" readonly>
                </div>

                <button type="submit" class="btn-submit">Finalizar Compra</button>
            </form>

            <div class="text-center">
                <a href="index.php">← Cancelar</a>
            </div>
        </div>
    </div>
</body>
</html>