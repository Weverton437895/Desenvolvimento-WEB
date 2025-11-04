<?php
require '../config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$query = $pdo->query("SELECT * FROM usuario ORDER BY id DESC");
$usuarios = $query->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Gerenciar Usuários</title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo"><h1>Admin - Usuários</h1></div>
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
        <h2>Gerenciar Usuários</h2>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th style="width: 100px;">Foto</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th style="width: 120px;">Nível</th>
                        <th style="width: 150px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $user): ?>
                        <tr>
                            <td><strong>#<?php echo $user['id']; ?></strong></td>
                            <td>
                                <?php if (!empty($user['foto'])): ?>
                                    <img src="../<?php echo htmlspecialchars($user['foto']); ?>" 
                                         alt="Foto" 
                                         style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 50px; height: 50px; background: #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #999; font-size: 0.8rem;">
                                        Sem foto
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($user['nome']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $user['nivel'] === 'admin' ? 'admin' : 'user'; ?>">
                                    <?php echo $user['nivel']; ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($user['id'] != $_SESSION['usuario_id']): ?>
                                    <a href="deletar_usuario.php?id=<?php echo $user['id']; ?>" 
                                       class="btn-small btn-delete" 
                                       onclick="return confirm('Tem certeza que deseja excluir o usuário:\n\n<?php echo htmlspecialchars($user['nome']); ?>?\n\nEsta ação não pode ser desfeita!')">
                                        Excluir
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999; font-size: 0.85rem;">Você</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>