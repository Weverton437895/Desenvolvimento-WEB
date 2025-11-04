<?php
require '../config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: ../login.php");
    exit;
}

$usuario = ['nome' => 'Administrador Master', 'foto' => null];
if ($_SESSION['usuario_id'] != 0) {
    $query = $pdo->prepare("SELECT nome, foto FROM usuario WHERE id = ?");
    $query->execute([$_SESSION['usuario_id']]);
    $usuario = $query->fetch();
}

$id = $_GET['id'];

$query = $pdo->prepare("SELECT * FROM servico WHERE id = ?");
$query->execute([$id]);
$produto = $query->fetch();

if (!$produto) {
    header("Location: gerenciar_servicos.php");
    exit;
}

$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caminhoFoto = $produto['foto'];
    
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); 
        $nomeArquivo = uniqid('produto_', true) . '.' . $extensao;
        $caminhoFoto = 'images/servicos/' . $nomeArquivo;
        
        if (!is_dir('../images/servicos')) {
            mkdir('../images/servicos', 0777, true);
        }
        
        move_uploaded_file($_FILES['foto']['tmp_name'], '../' . $caminhoFoto);
        
        if (!empty($produto['foto']) && file_exists('../' . $produto['foto'])) {
            unlink('../' . $produto['foto']);
        }
    }
    
    $query = $pdo->prepare("UPDATE servico SET titulo = ?, descricao = ?, foto = ? WHERE id = ?");
    $query->execute([$_POST['titulo'], $_POST['descricao'], $caminhoFoto, $id]);
    
    $sucesso = "✓ Produto atualizado com sucesso! Redirecionando...";
    header("refresh:2;url=gerenciar_servicos.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Editar Produto</title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo"><h1>Tech Innovation - Admin</h1></div>
            <nav class="navbar">
                <ul>
                    <li><a href="gerenciar_servicos.php">← Voltar para Produtos</a></li>
                    <li><a href="../dashboard.php">Dashboard</a></li>
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

    <div class="form-container" style="margin-top: 50px;">
        <h1>Editar Produto/Serviço</h1>
        
        <?php if ($sucesso): ?>
            <div class="message success-message"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <?php if (!empty($produto['foto'])): ?>
            <div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 12px;">
                <p style="margin-bottom: 15px;"><strong>Foto Atual:</strong></p>
                <img src="../<?php echo htmlspecialchars($produto['foto']); ?>" 
                     style="max-width: 400px; max-height: 300px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <p style="margin-top: 10px; color: #999; font-size: 0.9rem;">Faça upload de uma nova foto para substituir</p>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Título do Produto: *</label>
                <input type="text" name="titulo" required value="<?php echo htmlspecialchars($produto['titulo']); ?>" maxlength="100">
            </div>

            <div class="form-group">
                <label>Descrição Completa: *</label>
                <textarea name="descricao" required rows="8"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Nova Foto (deixe em branco para manter a atual):</label>
                <input type="file" name="foto" accept="image/*" id="fotoInput">
                <small style="color: #999;">Formatos aceitos: JPG, PNG, GIF</small>
                
                <div id="imagePreview" style="margin-top: 15px; display: none;">
                    <p><strong>Nova foto (preview):</strong></p>
                    <img id="preview" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 2px solid #667eea;">
                </div>
            </div>

            <button type="submit" class="btn-submit">Atualizar Produto</button>
        </form>

        <div class="text-center">
            <a href="gerenciar_servicos.php">← Cancelar e voltar</a>
        </div>
    </div>

    <script>
        document.getElementById('fotoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>