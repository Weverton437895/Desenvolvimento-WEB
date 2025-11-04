<?php
require 'config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'usuario') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['produto_id'])) {
    header("Location: index.php");
    exit;
}

$produtoId = $_GET['produto_id'];

$query = $pdo->prepare("SELECT * FROM servico WHERE id = ?");
$query->execute([$produtoId]);
$produto = $query->fetch();

if (!$produto) {
    header("Location: index.php");
    exit;
}

$query = $pdo->prepare("SELECT nome, foto FROM usuario WHERE id = ?");
$query->execute([$_SESSION['usuario_id']]);
$usuario = $query->fetch();

$sucesso = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $query = $pdo->prepare("INSERT INTO avaliacao (nome, estrelas, comentario) VALUES (?, ?, ?)");
        $query->execute([
            $usuario['nome'],
            (int)$_POST['estrelas'],
            $_POST['comentario']
        ]);

        $sucesso = "✓ Avaliação enviada com sucesso! Redirecionando...";
        header("Location: index.php");
    } catch (PDOException $e) {
        $erro = "Erro ao salvar avaliação: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Avaliar Produto</title>
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

    <div class="form-container" style="margin-top: 50px; max-width: 700px;">
        <h1>Avaliar Produto</h1>
        
        <div style="text-align: center; margin-bottom: 30px; padding: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: white;">
            <?php if (!empty($produto['foto'])): ?>
                <img src="<?php echo htmlspecialchars($produto['foto']); ?>" 
                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; margin-bottom: 15px; border: 3px solid white;">
            <?php endif; ?>
            <h3 style="color: white; margin: 0;"><?php echo htmlspecialchars($produto['titulo']); ?></h3>
        </div>

        <?php if ($sucesso): ?>
            <div class="message success-message"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="message error-message"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Seu Nome:</label>
                <input type="text" value="<?php echo htmlspecialchars($usuario['nome']); ?>" readonly style="background: #f5f5f5;">
            </div>

            <div class="form-group">
                <label>Avaliação (1 a 5 estrelas): *</label>
                <select name="estrelas" required style="font-size: 1.1rem;">
                    <option value="">Selecione sua avaliação</option>
                    <option value="5">Excelente</option>
                    <option value="4">Muito Bom</option>
                    <option value="3">Bom</option>
                    <option value="2">Regular</option>
                    <option value="1">Ruim</option>
                </select>
            </div>

            <div class="form-group">
                <label>Comentário: *</label>
                <textarea name="comentario" required placeholder="Conte sua experiência com este produto... O que você achou? Recomendaria?" rows="6"></textarea>
            </div>

            <button type="submit" class="btn-submit">Enviar Avaliação</button>
        </form>

        <div class="text-center">
            <a href="index.php">← Voltar sem avaliar</a>
        </div>
    </div>

    
</body>
</html>
