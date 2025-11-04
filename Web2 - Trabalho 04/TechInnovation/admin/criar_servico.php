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

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caminhoFoto = null;
    
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); 
        $nomeArquivo = uniqid('produto_', true) . '.' . $extensao;
        $caminhoFoto = 'images/servicos/' . $nomeArquivo;
        
        if (!is_dir('../images/servicos')) {
            mkdir('../images/servicos', 0777, true);
        }
        
        move_uploaded_file($_FILES['foto']['tmp_name'], '../' . $caminhoFoto);
    }
    
    $query = $pdo->prepare("INSERT INTO servico (titulo, descricao, foto) VALUES (?, ?, ?)");
    $query->execute([$_POST['titulo'], $_POST['descricao'], $caminhoFoto]);
    
    $sucesso = "✓ Produto cadastrado com sucesso! Redirecionando...";
    header("refresh:2;url=gerenciar_servicos.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Criar Produto</title>
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
        <h1>📦 Criar Novo Produto</h1>
        
        <?php if ($erro): ?>
            <div class="message error-message"><?php echo $erro; ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="message success-message"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Título do Produto: *</label>
                <input type="text" name="titulo" required placeholder="Ex: Consultoria em TI" maxlength="100">
                <small style="color: #999;">Máximo 100 caracteres</small>
            </div>

            <div class="form-group">
                <label>Descrição Completa: *</label>
                <textarea name="descricao" required placeholder="Descreva detalhadamente o produto ou serviço oferecido..." rows="8"></textarea>
                <small style="color: #999;">Seja detalhado para atrair mais clientes</small>
            </div>

            <div class="form-group">
                <label>Foto do Produto:</label>
                <input type="file" name="foto" accept="image/*" id="fotoInput">
                <small style="color: #999;">Formatos aceitos: JPG, PNG, GIF (máx. 5MB)</small>
                
                <!-- Preview da imagem -->
                <div id="imagePreview" style="margin-top: 15px; display: none;">
                    <p><strong>Preview:</strong></p>
                    <img id="preview" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 2px solid #e0e0e0;">
                </div>
            </div>

            <button type="submit" class="btn-submit">Cadastrar Produto</button>
        </form>

        <div class="text-center">
            <a href="gerenciar_servicos.php">← Cancelar e voltar</a>
        </div>
    </div>

    <script>
        // Preview da imagem antes de enviar
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