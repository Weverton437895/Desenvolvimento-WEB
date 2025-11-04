<?php
require '../config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$usuario = ['nome' => 'Administrador Master', 'foto' => null];
if ($_SESSION['usuario_id'] != 0) {
    $query = $pdo->prepare("SELECT nome, foto FROM usuario WHERE id = ?");
    $query->execute([$_SESSION['usuario_id']]);
    $usuario = $query->fetch();
}


if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    

    $query = $pdo->prepare("SELECT foto FROM servico WHERE id = ?");
    $query->execute([$id]);
    $produto = $query->fetch();
    
    $query = $pdo->prepare("DELETE FROM servico WHERE id = ?");
    $query->execute([$id]);
    
    if (!empty($produto['foto']) && file_exists('../' . $produto['foto'])) {
        unlink('../' . $produto['foto']);
    }
    
    header("Location: gerenciar_servicos.php?msg=deletado");
    exit;
}

$query = $pdo->query("SELECT * FROM servico ORDER BY id DESC");
$produtos = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Gerenciar Produtos</title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo"><h1>Tech Innovation - Admin</h1></div>
            <nav class="navbar">
                <ul>
                    <li><a href="../dashboard.php">Dashboard</a></li>
                    <li><a href="gerenciar_usuarios.php">Usuários</a></li>
                    <li><a href="gerenciar_servicos.php">Produtos</a></li>
                    <li><a href="gerenciar_avaliacoes.php">Avaliações</a></li>
                    <li><a href="gerenciar_contatos.php">Contatos</a></li>
                </ul>
            </nav>
            <div class="user-info">
                <?php if (!empty($usuario['foto'])): ?>
                    <img src="../<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto">
                <?php endif; ?>
                <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
                <a href="../logout.php" class="btn-login">Sair</a>
            </div>
        </div>
    </header>

    <div class="dashboard-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2>Gerenciar Produtos/Serviços</h2>
            <a href="criar_servico.php" class="btn btn-primary">+ Adicionar Novo Produto</a>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deletado'): ?>
            <div class="message success-message">✓ Produto deletado com sucesso!</div>
        <?php endif; ?>

        <?php if (empty($produtos)): ?>
            <div class="empty-state">
                <p style="font-size: 1.2rem; margin-bottom: 20px;">Nenhum produto cadastrado ainda.</p>
                <a href="criar_servico.php" class="btn btn-primary btn-large">Cadastrar Primeiro Produto</a>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th style="width: 120px;">Foto</th>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th style="width: 200px; text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td><strong>#<?php echo $produto['id']; ?></strong></td>
                                <td>
                                    <?php if (!empty($produto['foto'])): ?>
                                        <img src="../<?php echo htmlspecialchars($produto['foto']); ?>" 
                                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; display: block;">
                                    <?php else: ?>
                                        <div style="width: 80px; height: 80px; background: #e0e0e0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 0.8rem;">Sem foto</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong style="color: #2c3e50; font-size: 1.1rem;">
                                        <?php echo htmlspecialchars($produto['titulo']); ?>
                                    </strong>
                                </td>
                                <td style="color: #666;">
                                    <?php 
                                        $descricao = htmlspecialchars($produto['descricao']);
                                        echo strlen($descricao) > 100 ? substr($descricao, 0, 100) . '...' : $descricao;
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <a href="editar_servico.php?id=<?php echo $produto['id']; ?>" class="btn-small btn-edit">Editar</a>
                                    <a href="?deletar=<?php echo $produto['id']; ?>" 
                                       class="btn-small btn-delete" 
                                       onclick="return confirm(' Tem certeza que deseja deletar o produto:\n\n<?php echo htmlspecialchars($produto['titulo']); ?>?\n\nEsta ação não pode ser desfeita!')">
                                        Deletar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; text-align: center;">
                <p style="color: #666; margin: 0;">
                    <strong>Total de produtos:</strong> <?php echo count($produtos); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto-hide mensagem de sucesso
        const successMsg = document.querySelector('.success-message');
        if (successMsg) {
            setTimeout(() => {
                successMsg.style.opacity = '0';
                successMsg.style.transition = 'opacity 0.3s';
                setTimeout(() => successMsg.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>