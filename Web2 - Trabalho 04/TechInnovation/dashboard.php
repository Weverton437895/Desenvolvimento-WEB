<?php
require 'config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$query = $pdo->prepare("SELECT nome, email, foto FROM usuario WHERE id = ?");
$query->execute([$_SESSION['usuario_id']]);
$usuario = $query->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuario")->fetchColumn();
$totalProdutos = $pdo->query("SELECT COUNT(*) FROM servico")->fetchColumn();
$totalAvaliacoes = $pdo->query("SELECT COUNT(*) FROM avaliacao")->fetchColumn();
$totalContatos = $pdo->query("SELECT COUNT(*) FROM contato")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard - Admin</title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h1>Tech Innovation - Admin</h1>
            </div>
            <nav class="navbar">
                <ul>
                    <li><a href="index.php">Página Inicial</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                </ul>
            </nav>
            <div class="user-info">
                <?php if (!empty($usuario['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto">
                <?php endif; ?>
                <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
                <a href="logout.php" class="btn-login">Sair</a>
            </div>
        </div>
    </header>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <div style="display: flex; align-items: center; gap: 20px;">
                <?php if (!empty($usuario['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto">
                <?php endif; ?>
                <div>
                    <h2>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h2>
                    <p style="color: #666;"><?php echo htmlspecialchars($usuario['email']); ?></p>
                </div>
            </div>
        </div>

        <h2 style="margin-bottom: 20px;">Painel de Controle</h2>

        <div class="menu-cards">
            <div class="menu-card">
                <h3>Usuários</h3>
                <p>Total: <strong><?php echo $totalUsuarios; ?></strong></p>
                <a href="admin/gerenciar_usuarios.php" class="btn btn-primary">Gerenciar</a>
            </div>

            <div class="menu-card">
                <h3>Produtos</h3>
                <p>Total: <strong><?php echo $totalProdutos; ?></strong></p>
                <a href="admin/gerenciar_servicos.php" class="btn btn-primary">Gerenciar</a>
            </div>

            <div class="menu-card">
                <h3>Avaliações</h3>
                <p>Total: <strong><?php echo $totalAvaliacoes; ?></strong></p>
                <a href="admin/gerenciar_avaliacoes.php" class="btn btn-primary">Gerenciar</a>
            </div>

            <div class="menu-card">
                <h3>Contatos</h3>
                <p>Total: <strong><?php echo $totalContatos; ?></strong></p>
                <a href="admin/gerenciar_contatos.php" class="btn btn-primary">Gerenciar</a>
            </div>
        </div>
    </div>
</body>
</html>