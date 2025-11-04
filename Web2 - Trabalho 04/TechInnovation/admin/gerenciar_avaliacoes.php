<?php
require '../config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$query = $pdo->query("SELECT * FROM avaliacao ORDER BY id DESC");
$avaliacoes = $query->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Gerenciar Avaliações</title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo"><h1>Admin - Avaliações</h1></div>
            <nav class="navbar">
                <ul>
                    <li><a href="../dashboard.php">Dashboard</a></li>
                    <li><a href="gerenciar_usuarios.php">Usuários</a></li>
                    <li><a href="gerenciar_servicos.php">Produtos</a></li>
                    <li><a href="gerenciar_avaliacoes.php">Avaliações</a></li>
                    <li><a href="gerenciar_contatos.php">Contatos</a></li>
                </ul>
            </nav>
            <a href="../logout.php" class="btn-login">Sair</a>
        </div>
    </header>
    <div class="container" style="margin-top: 30px;">
        <h2>Gerenciar Avaliações</h2>
        <?php if (empty($avaliacoes)): ?>
            <p style="text-align: center; color: #999; padding: 40px;">Nenhuma avaliação.</p>
        <?php else: ?>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Estrelas</th>
                            <th>Comentário</th>
                            <th style="width: 150px; text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($avaliacoes as $aval): ?>
                            <tr>
                                <td><strong>#<?php echo $aval['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($aval['nome']); ?></td>
                                <td><?php echo str_repeat('⭐', $aval['estrelas']); ?></td>
                                <td><?php echo htmlspecialchars(substr($aval['comentario'], 0, 60)) . '...'; ?></td>
                                <td style="text-align: center;">
                                    <a href="deletar_avaliacao.php?id=<?php echo $aval['id']; ?>" 
                                       class="btn-small btn-delete" 
                                       onclick="return confirm('Tem certeza que deseja excluir esta avaliação?\n\nEsta ação não pode ser desfeita!')">
                                        Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>