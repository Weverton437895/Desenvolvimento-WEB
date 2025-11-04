<?php
require 'config.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = $pdo->prepare("SELECT id FROM usuario WHERE email = ?");
    $query->execute([$_POST['email']]);
    if ($query->fetch()) {
        $erro = "Este email já está cadastrado!";
    } else {
        $caminhoFoto = null;
        
        if(isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); 
            $nomeArquivo = uniqid('user_', true) . '.' . $extensao;
            $caminhoFoto = 'images/users/' . $nomeArquivo;

            if (!is_dir('images/users')) {
                mkdir('images/users', 0777, true);
            }

            move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoFoto);
        }
        
        $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $query = $pdo->prepare("INSERT INTO usuario (nome, email, senha, foto, nivel) VALUES (?, ?, ?, ?, 'usuario')");
        $query->execute([$_POST['nome'], $_POST['email'], $senhaHash, $caminhoFoto]);

        $sucesso = "Cadastro realizado! Redirecionando...";
        header("refresh:2;url=login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cadastro - Tech Innovation</title>
</head>
<body>
    <div class="form-container">
        <h1>Criar Conta</h1>
        
        <?php if ($erro): ?>
            <div class="message error-message"><?php echo $erro; ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="message success-message"><?php echo $sucesso; ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="nome" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required minlength="6">
            </div>

            <div class="form-group">
                <label>Foto (opcional):</label>
                <input type="file" name="foto" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">Cadastrar</button>
        </form>

        <div class="text-center">
            <p>Já tem conta? <a href="login.php">Faça login</a></p>
            <p><a href="index.php">← Voltar para o início</a></p>
        </div>
    </div>
</body>
</html>